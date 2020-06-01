<?php
/**
 * @author		Muhammad Faqih Zulfikar
 * @copyright	Copyright (c) 2020 FaqZul (https://github.com/FaqZul/CodeIgniter-FauthZ-Library)
 * @license		https://opensource.org/licenses/MIT 	MIT License
 * @link		https://github.com/FaqZul
 * @package		FaqZul/CodeIgniter-FauthZ-Library
 * @version		0.1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

define('STATUS_ACTIVATED', 1);
define('STATUS_NOT_ACTIVATED', 0);

class Fauthz {
	private $CE = array();
	private $CF = array();
	private $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->config('fauthz', TRUE);
		$this->CF = $this->CI->config->item('fauthz');
		// Try Autologin?
		if ($this->config('autologin'))
			$this->__autologin();
	}

	/**
	 * Login user on the site. Return TRUE if login is successful (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string	(username or email or both depending on settings in config file)
	 * @param	string
	 * @param	bool
	 * @param	bool
	 * @return	bool
	 */
	public function login($login, $password, $remember = NULL, $byEmail = NULL, $byUsername = NULL) {
		if (isset($login, $password)) {
			is_bool($byEmail) OR $byEmail = $this->config('login_by_email');
			is_bool($byUsername) OR $byUsername = ($this->config('use_username') && $this->config('login_by_username'));
			if ($byEmail && $byUsername)
				$key = 'login';
			else if ($byUsername)
				$key = 'username';
			else
				$key = 'email';
			if ( ! is_null($user = $this->user_get($login, $key, ['password', 'banned', 'bantxt']))) {
				require_once 'phpass-0.5/PasswordHash.php';
				$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));
				if ($passhash->CheckPassword($password, $user->password)) {
					if ($user->banned == 1) { $this->CE = ['banned' => $user->bantxt]; }
					else {
						$this->__load_sess();
						$this->CI->session->set_userdata(['status' => ($user->activated == 1) ? STATUS_ACTIVATED: STATUS_NOT_ACTIVATED, 'email' => $user->email, 'username' => $user->username, 'user_id' => $user->id]);
						if ($user->activated == 0) { $this->CE = ['not_activated' => '']; }
						else {
							if ($remember)
								$this->__autologin_add($user->id);
							$this->login_attempt_del($login);
							if ($this->config('log_login'))
								$this->__log_inout($user->id, session_id());
							return TRUE;
						}
					}
				}
				else {
					$this->login_attempt_add($login);
					$this->CE = ['password' => 'fauthz_incorrect_password'];
				}
			}
			else {
				$this->login_attempt_add($login);
				$this->CE = ['login' => 'fauthz_incorrect_login'];
			}
		}
		return FALSE;
	}

	/**
	 * Logout user from the site.
	 *
	 * @return	void
	 */
	public function logout() {
		$this->__autologin_del();
		$this->__load_sess();
		if ($this->config('log_login') && $this->config('log_logout'))
			$this->__log_inout($this->get_user_id(), session_id(), 'logout');
		$this->CI->session->sess_destroy();
	}

	/**
	 * Check if EMail available for registering.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function is_email_available($str) { return (isset($str) && is_string($str)) ? $this->_available($str, 'email'): FALSE; }

	/**
	 * Check if user logged in. Also test if user is activated or not.
	 *
	 * @param	bool
	 * @return	bool
	 */
	public function is_logged_in($activated = TRUE) {
		$this->__load_sess();
		return $this->CI->session->userdata('status') === ($activated ? STATUS_ACTIVATED: STATUS_NOT_ACTIVATED);
	}

	/**
	 * Check if Username available for registering.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function is_username_available($str) { return isset($str) ? $this->_available($str, 'username'): FALSE; }

	/**
	 * Get user email.
	 *
	 * @return	string
	 */
	public function get_email() {
		$this->__load_sess();
		return $this->CI->session->userdata('email');
	}

	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	array
	 */
	public function get_error_message() { return $this->CE; }

	/**
	 * Get username.
	 *
	 * @return	string
	 */
	public function get_username() {
		$this->__load_sess();
		return $this->CI->session->userdata('username');
	}

	/**
	 * Get user ID.
	 *
	 * @return	int
	 */
	public function get_user_id() {
		$this->__load_sess();
		return $this->CI->session->userdata('user_id');
	}

	/**
	 * Get config.
	 *
	 * @param	string
	 * @return	string
	 */
	public function config($str = '') { return isset($this->CF[$str]) ? $this->CF[$str]: NULL; }

	/**
	 * Set config.
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public function config_set($key, $val = '') {
		if (is_array($key)) {
			foreach ($key as $k => $v)
				if ( ! is_int($k))
					$this->config_set($k, $v);
		} else if (is_string($key) && isset($this->CF[$key])) { $this->CF[$key] = $val; }
	}

	/**
	 * Create new user on the site and return some data about it:
	 * user_id, username, password, email, new_email_key (if any).
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	array
	 */
	public function create_user($username, $email, $password, $email_activation) {
		if ( ! empty($username) && ! $this->is_username_available($username)) { $this->CE = ['username' => 'fauthz_username_in_use']; }
		else if ( ! $this->is_email_available($email)) { $this->CE = ['email' => 'fauthz_email_in_use']; }
		else {
			require_once 'phpass-0.5/PasswordHash.php';
			$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));
			$hashpass = $passhash->HashPassword($password);
			$data = ['username' => ( ! empty($username)) ? $username: NULL, 'email' => $email, 'password' => $hashpass];
			if ($email_activation)
				$data['info'] = json_encode(['new_email_key' => md5(rand() . microtime())]);
			if ( ! is_null($res = $this->_user_add($data, ! $email_activation))) {
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				if ($email_activation) {
					$data['new_email_key'] = json_decode($data['info'])->new_email_key;
					unset($data['info']);
				}
				return $data;
			}
		}
		return NULL;
	}

	/**
	 * Change email for activation and return some data about user:
	 * user_id, username, email, new_email_key.
	 * Can be called for not activated users only.
	 *
	 * @param	string
	 * @return	array
	 */
	public function change_email($str) {
		$user_id = $this->get_user_id();
		if ( ! is_null($user = $this->user_get($user_id, 'id', ["JSON_VALUE(info, '$.new_email_key') AS new_email_key"])) && $user->activated == 0) {
			$data = ['email' => $str, 'username' => $user->username, 'user_id' => $user_id];
			if ($user->email === $str) {
				$data['new_email_key'] = $user->new_email_key;
				return $data;
			}
			else if ($this->_available($str, 'email')) {
				$data['new_email_key'] = md5(rand() . microtime());
				$this->_set_new_email($user->id, $str, $data['new_email_key'], FALSE);
				return $data;
			} else { $this->CE = ['email' => 'fauthz_email_in_use']; }
		}
		return NULL;
	}

	/**
	 * Activate user using given key.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function activate_user($user_id, $key, $byPasssword = FALSE) {
		$this->_user_del_exp($this->config('email_activation_expire'));
		return (isset($user_id, $key) && is_numeric($user_id) && is_string($key)) ? $this->_user_act($user_id, $key, ($byPasssword) ? 'password': 'email'): FALSE;
	}

	/**
	 * Set new password key for user and return some data about user:
	 * user_id, username, email, new_pass_key.
	 * The password key can be used to verify user when resetting his/her password.
	 *
	 * @param	string
	 * @return	array
	 */
	public function forgot_password($str) {
		if (isset($str)) {
			if ( ! is_null($user = $this->user_get($str, 'login'))) {
				$key = md5(rand() . microtime());
				$arr = ['email' => $user->email, 'new_password_key' => $key, 'username' => $user->username, 'user_id' => $user->id];
				$this->__load_crud();
				$this->CI->db->set('info', "JSON_SET(info, '$.new_password_key', '$key', '$.new_password_ts', '" . date('Y-m-d H:i:s') . "')", FALSE);
				$this->CI->db->where('id', $user->id);
				$this->CI->db->update('fauthz');
				return $arr;
			} else { $this->CE = ['login' => 'fauthz_incorrect_email_or_username']; }
		}
		return NULL;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function can_reset_password($user_id, $new_password_key) {
		if (isset($user_id, $new_password_key) && is_numeric($user_id)) {
			$this->__load_crud();
			return $this->CI->crud->readData('id', 'fauthz', ['id' => $user_id, "JSON_VALUE(info, '$.new_password_key') =" => $new_password_key, "JSON_VALUE(info, '$.new_password_ts') >" => date('Y-m-d H:i:s', time() - $this->config('forgot_password_expire'))])->num_rows() === 1;
		}
		return FALSE;
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user) and return some data about it:
	 * user_id, username, new_password, email.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	public function reset_password($user_id, $new_pass_key, $new_pass) {
		if (isset($user_id, $new_pass_key, $new_pass) && is_numeric($user_id)) {
			if ( ! is_null($user = $this->user_get((int) $user_id)) && $user->activated == 1) {
				require_once 'phpass-0.5/PasswordHash.php';
				$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));
				$this->__load_crud();
				$this->CI->db->set('password', $passhash->HashPassword());
				$this->CI->db->set('info', "JSON_SET(info, '$.new_password_key', NULL, '$.new_password_ts', NULL)", FALSE);
				$this->CI->db->where(['id' => $user->id, "JSON_VALUE(info, '$.new_password_key') =" => $new_pass_key]);
				if ($this->CI->db->update('fauthz')) {
					$this->_autologin_clear($user->id);
					return ['email' => $user->email, 'username' => $user->username, 'user_id' => $user->id, 'new_password' => $new_password];
				}
			}
		}
		return NULL;
	}

	/**
	 * Change user password (only when user is logged in)
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function change_password($old_pass, $new_pass) {
		if (isset($old_pass, $new_pass)) {
			if ( ! is_null($user = $this->user_get($this->get_user_id(), 'id', ['password'])) && $user->activated == 1) {
				require_once 'phpass-0.5/PasswordHash.php';
				$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));;
				if ($passhash->CheckPassword($old_pass, $user->password))
					return $this->CI->crud->updateData('fauthz', ['password' => $passhash->HashPassword($new_pass)], ['id' => $user->id]);
				else
					$this->CE = ['password_old' => 'fauthz_incorrect_password'];
			}
		}
		return FALSE;
	}

	/**
	 * Change user email (only when user is logged in) and return some data about user:
	 * user_id, username, new_email, new_email_key.
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	public function set_new_email($new_email, $password) {
		if (isset($new_email, $password) && is_string($new_email)) {
			if ( ! is_null($user = $this->user_get($this->get_user_id(), 'id', ['password', "JSON_VALUE(info, '$.new_email') AS new_email", "JSON_VALUE(info, '$.new_email_key') AS new_email_key"])) && $user->activated == 1) {
				require_once 'phpass-0.5/PasswordHash.php';
				$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));;
				if ($passhash->CheckPassword($password, $user->password)) {
					$arr = ['new_email' => $new_email, 'username' => $user->username, 'user_id' => $user->id];
					if ($user->email === $new_email) { $this->CE = ['email' => 'fauthz_current_email']; }
					else if ($user->new_email === $new_email) {
						$arr['new_email_key'] = $user->new_email_key;
						return $arr;
					}
					else if ($this->_available($new_email, 'email')) {
						$arr['new_email_key'] = md5(rand() . microtime());
						$this->_set_new_email($user->id, $new_email, $arr['new_email_key'], TRUE);
						return $arr;
					} else { $this->CE = ['email' => 'fauthz_email_in_use']; }
				} else { $this->CE = ['password' => 'fauthz_incorrect_password']; }
			}
		}
		return NULL;
	}

	/**
	 * Activate new email, if email activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function activate_new_email($user_id, $new_email_key) {
		if (isset($user_id, $new_email_key) && is_numeric($user_id)) {
			$this->__load_crud();
			$this->CI->db->set('email', "JSON_VALUE(info, '$.new_email')", FALSE);
			$this->CI->db->set('info', "JSON_SET(info, '$.new_email', NULL, '$.new_email_key', NULL)", FALSE);
			$this->CI->db->where(['id' => (int) $user_id, "JSON_VALUE(info, '$.new_email_key') =" => $new_email_key]);
			return $this->CI->db->update('fauthz');
		}
		return FALSE;
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @param	string
	 * @return	bool
	 */
	public function delete_user($password) {
		if (isset($password)) {
			if ( ! is_null($user = $this->user_get($this->get_user_id(), 'id', ['password'])) && $user->activated == 1) {
				require_once 'phpass-0.5/PasswordHash.php';
				$passhash = new PasswordHash($this->config('phpass_hash_strength'), $this->config('phpass_hash_portable'));;
				if ($passhash->CheckPassword($password, $user->password)) {
					if ($this->_user_del($user->id)) {
						$this->logout();
						return TRUE;
					}
				} else { $this->CE = ['password' => 'fauthz_incorrect_password']; }
			}
		}
		return FALSE;
	}

	/**
	 * Get user by key.
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	public function user_get($val, $key = 'id', $ret = array()) {
		$get = ['id', 'username', 'email', 'activated'];
		$var = implode(',', array_merge($get, $ret));
		$whr = [$key => $val];
		if ($key === 'login')
			$whr = ['or_where' => ['username' => $val, 'email' => $val]];
		$this->__load_crud();
		$sql = $this->CI->crud->readData($var, 'fauthz', $whr);
		if ($sql->num_rows() === 1) {
			$res = $sql->row();
			$res->id = (int) $res->id;
			$sql->free_result();
			return $res;
		}
		$sql->free_result();
		return NULL;
	}

	/**
	 * Increase number of attempts for given IP-address and login (if attempts to login is being counted).
	 *
	 * @param	string
	 * @return	void
	 */
	public function login_attempt_add($str) {
		if ( ! $this->login_attempt_max($str))
			$this->_attack_add($this->CI->input->ip_address(), $str);
	}

	/**
	 * Clear all attempt records for given IP-address and login (if attempts to login is being counted).
	 *
	 * @param	string
	 * @return	void
	 */
	public function login_attempt_del($str) {
		if ($this->config('login_count_attempts'))
			$this->_attack_del($this->CI->input->ip_address(), $str, $this->config('login_attempt_expire'));
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config).
	 *
	 * @param	string
	 * @return	bool
	 */
	public function login_attempt_max($str) { return ($this->config('login_count_attempts')) ? $this->_attack_num($this->CI->input->ip_address(), $str) >= $this->config('login_max_attempts'): FALSE; }

	/**
	 * Increase number of attempts for given IP-address and login.
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public function _attack_add($ip, $login) {
		$this->__load_crud();
		$this->CI->crud->createData('fauthz_attack', ['ip' => $ip, 'login' => $login]);
	}

	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	public function _attack_del($ip, $login, $expire = 86400) {
		$this->__load_crud();
		$this->CI->crud->group_set([0 => 'group_start']);
		$this->CI->crud->group_end([1 => 1]);
		$this->CI->crud->deleteData('fauthz_attack', ['ip' => $ip, 'login' => $login, 'or_where' => ['ts <' => date('Y-m-d H:i:s', time() - $expire)]]);
	}

	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	public function _attack_num($ip, $login) {
		$whr = ['ip' => $ip];
		if (isset($login) && is_string($login) && strlen($login) > 0)
			$whr['or_where'] = ['login' => $login];
		$this->__load_crud();
		return $this->CI->crud->readData('id', 'fauthz_attack', $whr)->num_rows();
	}

	/**
	 * Save data for user's autologin.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function _autologin_add($user_id, $key) {
		$arr = ['ip' => $this->CI->input->ip_address(), 'key' => $key, 'ts' => date('Y-m-d H:i:s'), 'ua' => $this->CI->input->user_agent()];
		$this->__load_crud();
		$this->CI->db->set('info', "JSON_ARRAY_INSERT(info, '$.autologin[0]', JSON_QUERY('" . json_encode($arr) . "', '$'))", FALSE);
		$this->CI->db->where('id', (int) $user_id);
		return $this->CI->db->update('fauthz');
	}

	/**
	 * Delete user's autologin data.
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	public function _autologin_del($user_id, $key) {
		$this->__load_crud();
		$this->CI->db->set('info', "JSON_REMOVE(info, REPLACE(JSON_UNQUOTE(JSON_SEARCH(info, 'one', '$key', '', '$.autologin[*].key')), '.key', ''))", FALSE);
		$this->CI->db->where(['id' => (int) $user_id, "JSON_SEARCH(info, 'one', '$key', '', '$.autologin[*].key') !=" => NULL]);
		$this->CI->db->update('fauthz');
	}

	/**
	 * Delete all autologin data for given user.
	 *
	 * @param	int
	 * @return	void
	 */
	public function _autologin_clear($user_id) {
		$this->__load_crud();
		$this->CI->db->set('info', "JSON_SET(info, '$.autologin', JSON_ARRAY())", FALSE);
		$this->CI->db->where('id', $user_id);
		$this->CI->db->update('fauthz');
	}

	/**
	 * Purge autologin data for given user and login conditions.
	 *
	 * @param	int
	 * @return	void
	 */
	public function _autologin_purge($user_id) {
		$this->__load_crud();
		$user_id = (int) $user_id;
		$user = $this->CI->crud->readData("JSON_QUERY(info, '$.autologin') AS autologin", 'fauthz', ['id' => $user_id])->row();
		if (isset($user)) {
			$autologin = array();
			$ip = $this->CI->input->ip_address();
			$ua = substr($this->CI->input->user_agent(), 0, 149);
			$ual = json_decode($user->autologin);
			foreach ($ual as $ualk => $ualv) {
				if ((isset($ualv->ip) && $ualv->ip !== $ip) && (isset($ualv->ua) && substr($ualv->ua, 0, 149) !== $ua))
					$autologin[] = $ual[$ualk];
			}
			$this->CI->db->set('info', "JSON_SET(info, '$.autologin', JSON_QUERY('" . json_encode($autologin) . "', '$'))", FALSE);
			$this->CI->db->where('id', $user_id);
			$this->CI->db->update('fauthz');
		}
	}

	/**
	 * Check if value with specified key available.
	 *
	 * @param	int
	 * @param	int
	 * @return	bool
	 */
	public function _available($val, $key = 'id') {
		$this->__load_crud();
		return $this->CI->crud->readData('id', 'fauthz', [$key => $val])->num_rows() === 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function _set_new_email($user_id, $new_email, $new_email_key, $activated) {
		$data = array();
		$json = ['$.new_email_key', $new_email_key];
		$this->__load_crud();
		if ($activated) {
			$json[] = '$.new_email';
			$json[] = $new_email;
		} else { $this->CI->db->set('email', $new_email); }
		$this->CI->db->set('info', "JSON_SET(info, '" . implode("','", $json) . "')", FALSE);
		$this->CI->db->where(['id' => $user_id, 'activated' => ($activated) ? 1: 0]);
		return $this->CI->db->update('fauthz');
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function _user_act($uid, $key, $by = 'email') {
		$this->__load_crud();
		$uid = (int) $uid;
		$user = $this->CI->crud->readData('id', 'fauthz', ['id' => $uid, 'activated' => 0, "JSON_VALUE(info, '$.new_" . $by . "_key') =" => $key])->num_rows();
		if ($user === 1) {
			$this->CI->db->set('activated', 1);
			$this->CI->db->set('info', "JSON_SET(info, '$.new_email_key', NULL)", FALSE);
			$this->CI->db->where('id', $uid);
			$this->CI->db->update('fauthz');
			$this->_user_init_json($uid, 1);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Create new user record.
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	public function _user_add(array $arr, $activated = TRUE) {
		$arr['activated'] = ($activated) ? 1: 0;
		$arr['fauthz_create_date'] = date('Y-m-d H:i:s');
		$arr['fauthz_create_ip'] = $this->CI->input->ip_address();
		$this->__load_crud();
		$sql = $this->CI->crud->createData('fauthz', $arr, TRUE);
		if ($sql['message'] === '') {
			$uid = $sql['insert_id'];
			if ($activated)
				$this->_user_init_json($uid);
			return ['user_id' => $uid];
		}
		return NULL;
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	public function _user_del($user_id) {
		$this->__load_crud();
		return $this->CI->crud->deleteData('fauthz', ['id' => (int) $user_id]);
	}

	/**
	 * Purge table of non-activated users.
	 *
	 * @param	int
	 * @return	void
	 */
	public function _user_del_exp($expire_period = 172800) {
		$this->__load_crud();
		$this->CI->crud->deleteData('fauthz', ['activated' => 0, 'fauthz_create_date <' => time() - $expire_period]);
	}

	/**
	 * Init JSON value for field info and log.
	 *
	 * @param	int
	 * @param	int
	 * @return	void
	 */
	public function _user_init_json($user_id, $json = 0) {
		$this->__load_crud();
		if ($json === 1) {
			$this->CI->db->set('info', "JSON_SET(info, '$.autologin', JSON_ARRAY())", FALSE);
			$this->CI->db->set('log', "JSON_OBJECT()", FALSE);
			$this->CI->db->where('id', $user_id);
			$this->CI->db->update('fauthz');
		} else { $this->CI->crud->updateData('fauthz', ['info' => json_encode(['autologin' => []]), 'log' => json_encode([], JSON_FORCE_OBJECT)], ['id' => $user_id]); }
	}

	/**
	 * Login user automatically if he/she provides correct autologin verification.
	 *
	 * @return	void
	 */
	public function __autologin() {
		if ( ! $this->is_logged_in() && ! $this->is_logged_in(FALSE)) {
			$this->CI->load->helper('cookie');
			if ( ! is_null($cookie = get_cookie($this->config('autologin_cookie_name')))) {
				$data = unserialize($cookie);
				if ($data !== FALSE && is_array($data) && isset($data['key']) && isset($data['user_id'])) {
					$this->__load_crud();
					$ual = $this->CI->crud->readData('id, username', 'fauthz', ['id' => $data['user_id'], "JSON_SEARCH(info, 'one', '" . $data['key'] . "', '', '$.autologin[*].key') !=" => NULL])->row();
					if (isset($ual)) {
						$this->__load_sess();
						$this->CI->session->set_userdata(['status' => STATUS_ACTIVATED, 'username' => $ual->username, 'user_id' => (int) $ual->id]);
						set_cookie(['name' => $this->config('autologin_cookie_name'), 'value' => $cookie, 'expire' => $this->config('autologin_cookie_life')]);
						if ($this->config('log_login'))
							$this->__log_inout($ual->id, session_id());
					}
				}
			}
		}
	}

	/**
	 * Save data for user's autologin.
	 *
	 * @param	int
	 * @return	bool
	 */
	public function __autologin_add($user_id) {
		$this->CI->load->helper('cookie');
		$key = substr(md5(uniqid(rand() . get_cookie($this->config('sess_cookie_name')))), 0, 16);
		$this->_autologin_purge($user_id);
		if ($this->_autologin_add($user_id, md5($key))) {
			set_cookie($this->config('autologin_cookie_name'), serialize(['key' => $key, 'user_id' => (int) $user_id]), $this->config('autologin_cookie_life'));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Clear user's autologin data.
	 *
	 * @return	void
	 */
	public function __autologin_del() {
		$this->CI->load->helper('cookie');
		if ( ! is_null($cookie = get_cookie($this->config('autologin_cookie_name')))) {
			$data = unserialize($cookie);
			$this->_autologin_del($data['user_id'], $data['key']);
			delete_cookie($this->config('autologin_cookie_name'));
		}
	}

	public function __log_inout($uid, $sid = '', $var = 'login') {
		if (isset($uid) && is_numeric($uid)) {
			$arr = ['ip' => $this->CI->input->ip_address(), 'ts' => date('Y-m-d H:i:s'), 'ua' => $this->CI->input->user_agent()];
			$this->__load_crud();
			if ($var === 'login')
				$this->CI->db->set('log', "JSON_SET(log, '$.$sid', JSON_QUERY('" . json_encode(['login' => $arr]) . "', '$'))", FALSE);
			else
				$this->CI->db->set('log', "JSON_SET(log, '$.$sid.$var', JSON_QUERY('" . json_encode($arr) . "', '$'))", FALSE);
			$this->CI->db->where('id', $uid);
			$this->CI->db->update('fauthz');
		}
	}

	public function __load_crud() {
		if ( ! isset($this->CI->crud) OR ! is_object($this->CI->crud)) {
			$this->CI->load->add_package_path($this->config('crud_path'));
			$this->CI->load->model('crud');
			$this->CI->load->remove_package_path();
		}
	}

	public function __load_sess() {
		if ($this->CI->load->is_loaded('session') === FALSE)
			$this->CI->load->library('session');
	}

}

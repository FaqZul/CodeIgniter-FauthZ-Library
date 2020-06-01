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

class Fauth extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->add_package_path(APPPATH . 'third_party/fauthz');
		$this->load->library('fauthz');
		$this->lang->load('fauthz');
	}

	public function index() {
		$this->load->library('session');
		( ! is_null($msg = $this->session->flashdata('msg'))) ? $this->load->view('fzIndex', ['msg' => $msg]): redirect('fauth/login');
	}

	public function login() {
		if ($this->fauthz->is_logged_in()) { redirect(); }
		else if ($this->fauthz->is_logged_in(FALSE)) { redirect('fauth/send-again'); }
		else {
			$data['error'] = array();
			$data['login_by_email'] = $this->fauthz->config('login_by_email');
			$data['login_by_username'] = ($this->fauthz->config('use_username') && $this->fauthz->config('login_by_username'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('login', 'Login', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[' . $this->fauthz->config('password_min_length') . ']|max_length[' . $this->fauthz->config('password_max_length') . ']');
			if ($this->fauthz->config('login_count_attempts') && ! is_null($login = $this->input->post('login')) && $this->fauthz->login_attempt_max($login))
				$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|required|callback__check_captcha');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');
			if ($this->form_validation->run()) {
				if ($this->fauthz->login($this->form_validation->set_value('login'), $this->form_validation->set_value('password'), $this->form_validation->set_value('remember'))) { redirect(); }
				else {
					$error = $this->fauthz->get_error_message();
					if (isset($error['banned']))
						$data['error']['banned'] = $this->lang->line('fauthz_message_banned') . ' ' . $error['banned'];
					else if (isset($error['not_activated']))
						redirect('fauth/send-again');
					else
						foreach ($error as $ek => $ev)
							$data['error'][$ek] = $this->lang->line($ev);
				}
			}
			if ($this->fauthz->config('login_count_attempts') && ! is_null($login = $this->input->post('login')) && $this->fauthz->login_attempt_max($login))
				$data['captcha'] = $this->_create_captcha();
			if ($data['login_by_email'] && $data['login_by_username'])
				$data['login_label'] = 'EMail or Username';
			else if ($data['login_by_username'])
				$data['login_label'] = 'Username';
			else
				$data['login_label'] = 'EMail';
			$data['title'] = 'Login | ' . $this->fauthz->config('website_name');
			$this->load->view('fzLogin', $data);
		}
	}

	public function logout() {
		$this->fauthz->logout();
		$this->_set_msg($this->lang->line('fauthz_message_logged_out'));
	}

	public function register() {
		if ($this->fauthz->is_logged_in()) { redirect(); }
		else if ($this->fauthz->is_logged_in(FALSE)) { redirect('fauth/send-again'); }
		else if ( ! $this->fauthz->config('allow_registration')) { $this->_set_msg($this->lang->line('fauthz_message_registration_disabled')); }
		else {
			$this->load->library('form_validation');
			$useUsername = $this->fauthz->config('use_username');
			if ($useUsername)
				$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[' . $this->fauthz->config('username_max_length') . ']|min_length[' . $this->fauthz->config('username_min_length') . ']|alpha_dash');
			$this->form_validation->set_rules('email', 'EMail', 'trim|required|max_length[' . $this->fauthz->config('email_max_length') . ']|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|matches[password]');
			if ($this->fauthz->config('captcha_registration'))
				$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|required|callback__check_captcha');
			$data['error'] = array();
			$emailActivation = $this->fauthz->config('email_activation');
			if ($this->form_validation->run()) {
				if ( ! is_null($data = $this->fauthz->create_user($useUsername ? $this->form_validation->set_value('username'): NULL, $this->form_validation->set_value('email'), $this->form_validation->set_value('password'), $emailActivation))) {
					$data['site_name'] = $this->fauthz->config('website_name');
					if ($emailActivation) {
						$data['activation_period'] = $this->fauthz->config('email_activation_expire') / 3600;
						$this->_send_mail('activate', $data['email'], $data);
						unset($data['password']);
						$this->_set_msg($this->lang->line('fauthz_message_registration_completed_1'));
					}
					else {
						if ($this->fauthz->config('email_account_details'))
							$this->_send_mail('welcome', $data['email'], $data);
						unset($data['password']);
						$this->_set_msg($this->lang->line('fauthz_message_registration_completed_2') . ' ' . anchor('fauth/login', 'Login'));
					}
				}
				else {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				}
			}
			if ($this->fauthz->config('captcha_registration'))
				$data['captcha'] = $this->_create_captcha();
			$data['title'] = 'Register | ' . $this->fauthz->config('website_name');
			$data['useUsername'] = $useUsername;
			$this->load->view('fzRegister', $data);
		}
	}

	public function send_again() {
		if ($this->fauthz->is_logged_in(FALSE)) {
			$data['error'] = array();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'EMail', 'trim|required|max_length[' . $this->fauthz->config('email_max_length') . ']|valid_email');
			if ($this->form_validation->run()) {
				if ( ! is_null($data = $this->fauthz->change_email($this->form_validation->set_value('email')))) {
					$data['site_name'] = $this->fauthz->config('website_name');
					$data['activation_period'] = $this->fauthz->config('email_activation_expire') / 3600;
					$this->_send_mail('activate', $data['email'], $data);
					$this->_set_msg(sprintf($this->lang->line('fauthz_message_activation_email_sent'), $data['email']));
				}
				else {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				}
			}
			$data['title'] = 'Send Again | ' . $this->fauthz->config('website_name');
			$this->load->view('fzSendAgain', $data);
		} else { redirect('fauth/login'); }
	}

	public function activate($uid = NULL, $key = NULL) {
		if ($this->fauthz->activate_user((int) $uid, $key)) {
			$this->fauthz->logout();
			$this->_set_msg($this->lang->line('fauthz_message_activation_completed') . ' ' . anchor('fauth/login', 'Login'));
		} else { $this->_set_msg($this->lang->line('fauthz_message_activation_failed')); }
	}

	public function forgot_password() {
		if ($this->fauthz->is_logged_in()) { redirect(); }
		else if ($this->fauthz->is_logged_in(FALSE)) { redirect('fauth/send-again'); }
		else {
			$data['error'] = array();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('login', 'EMail or Username', 'trim|required');
			if ($this->form_validation->run()) {
				if ( ! is_null($data = $this->fauthz->forgot_password($this->form_validation->set_value('login')))) {
					$data['site_name'] = $this->fauthz->config('website_name');
					$this->_send_mail('ForgotPassword', $data['email'], $data);
					$this->_set_msg($this->lang->line('fauthz_message_new_password_sent'));
				}
				else {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				}
			}
			$data['title'] = 'Forgot Password | ' . $this->fauthz->config('website_name');
			$this->load->view('fzForgotPassword', $data);
		}
	}

	public function reset_password($uid = NULL, $key = NULL) {
		$data['error'] = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'New Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
		$this->form_validation->set_rules('password_confirm', 'Confirm New Password', 'trim|required|matches[password]');
		if ($this->form_validation->run()) {
			if ( ! is_null($data = $this->fauthz->reset_password($uid, $key, $this->form_validation->set_value('password')))) {
				$data['site_name'] = $this->fauthz->config('website_name');
				$this->_send_mail('ResetPassword', $data['email'], $data);
				$this->_set_msg($this->lang->line('fauthz_message_new_password_activated') . ' ' . anchor('fauth/login', 'Login'));
			} else { $this->_set_msg($this->lang->line('fauthz_message_new_password_failed')); }
		}
		else {
			if ($this->fauthz->config('email_activation'))
				$this->fauthz->activate_user($uid, $key, TRUE);
			if ( ! $this->fauthz->can_reset_password($uid, $key))
				$this->_set_msg($this->lang->line('fauthz_message_new_password_failed'));
		}
		$data['title'] = 'Reset Password | ' . $this->fauthz->config('website_name');
		$this->load->view('fzResetPassword', $data);
	}

	public function change_password() {
		if ($this->fauthz->is_logged_in()) {
			$data['error'] = array();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password_old', 'Old Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
			$this->form_validation->set_rules('password_new', 'New Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
			$this->form_validation->set_rules('password_new_confirm', 'Confirm New Password', 'trim|required|matches[password_new]');
			if ($this->form_validation->run()) {
				if ( ! $this->fauthz->change_password($this->form_validation->set_value('password_old'), $this->form_validation->set_value('password_new'))) {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				} else { $this->_set_msg($this->lang->line('fauthz_message_password_changed')); }
			}
			$data['title'] = 'Change Password | ' . $this->fauthz->config('website_name');
			$this->load->view('fzChangePassword', $data);
		} else { redirect('fauth/login'); }
	}

	public function change_email() {
		if ($this->fauthz->is_logged_in()) {
			$data['error'] = array();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'EMail', 'trim|required|max_length[' . $this->fauthz->config('email_max_length') . ']|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
			if ($this->form_validation->run()) {
				if ( ! is_null($data = $this->fauthz->set_new_email($this->form_validation->set_value('email'), $this->form_validation->set_value('password')))) {
					$data['site_name'] = $this->fauthz->config('website_name');
					$this->_send_mail('ChangeEmail', $data['email'], $data);
					$this->_set_msg(sprintf($this->lang->line('fauthz_message_new_email_sent'), $data['new_email']));
				}
				else {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				}
			}
			$data['title'] = 'Change EMail | ' . $this->fauthz->config('website_name');
			$this->load->view('fzChangeEmail', $data);
		} else { redirect(); }
	}

	public function reset_email($uid = NULL, $key = NULL) {
		if ($this->fauthz->activate_new_email((int) $uid, $key)) {
			$this->fauthz->logout();
			$this->_set_msg($this->lang->line('fauthz_message_new_email_activated') . ' ' . anchor('fauth/login', 'Login'));
		} else { $this->_set_msg($this->lang->line('fauthz_message_new_email_failed')); }
	}

	public function unregister() {
		if ($this->fauthz->is_logged_in()) {
			$data['error'] = array();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[' . $this->fauthz->config('password_max_length') . ']|min_length[' . $this->fauthz->config('password_min_length') . ']|alpha_dash');
			if ($this->form_validation->run()) {
				if ( ! $this->fauthz->delete_user($this->form_validation->set_value('password'))) {
					$error = $this->fauthz->get_error_message();
					foreach ($error as $ek => $ev)
						$data['error'][$ek] = $this->lang->line($ev);
				} else { $this->_set_msg($this->lang->line('fauthz_message_unregistered')); }
			}
			$data['title'] = 'Unregister | ' . $this->fauthz->config('website_name');
			$this->load->view('fzUnregister', $data);
		} else { redirect('fauth/login'); }
	}

	public function _check_captcha($var) {
		$time = $this->session->flashdata('fauthz_captcha_time');
		$word = $this->session->flashdata('fauthz_captcha_word');
		list($usec, $sec) = explode(' ', microtime());
		$now = ((float) $usec + (float) $sec);
		if ($now - $time > $this->fauthz->config('captcha_expire')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('fauthz_captcha_expired'));
			return FALSE;
		}
		else if (($this->fauthz->config('captcha_case_sensitive') && $var !== $word) OR strtolower($var) !== strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('fauthz_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	public function _create_captcha() {
		$this->load->helper('captcha');
		$cap = create_captcha([
			'img_path' => $this->fauthz->config('captcha_path'),
			'img_url' => base_url($this->fauthz->config('captcha_path')),
			'font_path' => $this->fauthz->config('captcha_font_path'),
			'img_height' => $this->fauthz->config('captcha_height'),
			'img_width' => $this->fauthz->config('captcha_width'),
			'expiration' => $this->fauthz->config('captcha_expire'),
			'word_length' => $this->fauthz->config('captcha_length'),
			'font_size' => $this->fauthz->config('captcha_font_size')]);
		$this->session->set_flashdata(['fauthz_captcha_time' => $cap['time'], 'fauthz_captcha_word' => $cap['word']]);
		return $cap['image'];
	}

	public function _remap($str, $arr = array()) {
		$str = str_replace('-', '_', $str);
		if (method_exists($this, $str))
			return call_user_func_array([$this, $str], $arr);
		show_404();
	}

	public function _send_mail($type, $to, &$data) {
		// $this->load->library('email');
		// $this->email->from($this->fauthz->config('website_mail'), $this->fauthz->config('website_name'));
		// $this->email->reply_to($this->fauthz->config('website_mail'), $this->fauthz->config('website_name'));
		// $this->email->to($to);
		// $this->email->subject(sprintf($this->lang->line('fauthz_subject_' . strtolower($type)), $this->fauthz->config('website_name')));
		// $this->email->message($this->load->view('fzMail' . ucfirst($type), $data, TRUE));
		// $this->email->set_alt_message($this->load->view('fzMail' . ucfirst($type) . 'TXT', $data, TRUE));
		// $this->email->send();
		file_put_contents(APPPATH . 'cache/' . date('YmdHis') . '.html', $this->load->view('fzMail' . ucfirst($type), $data, TRUE));
		file_put_contents(APPPATH . 'cache/' . date('YmdHis') . '.txt', $this->load->view('fzMail' . ucfirst($type) . 'TXT', $data, TRUE));
	}

	public function _set_msg($str = NULL) {
		$this->load->library('session');
		$this->session->set_flashdata('msg', $str);
		redirect('fauth');
	}

}

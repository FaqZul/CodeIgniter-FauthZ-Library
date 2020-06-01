<?php
use PHPUnit\Framework\TestCase;

class FauthzTest extends TestCase {

	public function setUp() {
		$this->CI =& get_instance();
		$this->CI->load->add_package_path(FCPATH . 'vendor/faqzul/codeigniter-fauthz-library');
		$this->CI->load->library('fauthz');
		$this->CI->load->remove_package_path();
	}

	/**
	 * @test
	 */
	public function create_user_activated() {
		$arr = $this->CI->fauthz->create_user('admin', 'admin@fauthz.com', 'P455W012D', FALSE);
		$this->assertEquals('admin', $arr['username']);
		return $arr;
	}

	/**
	 * @test
	 */
	public function create_user_activatedWithoutUsername() {
		$this->CI->fauthz->config_set('use_username', FALSE);
		$arr = $this->CI->fauthz->create_user('', 'test@fauthz.com', 'P455W012D', FALSE);
		$this->assertNull($arr['username']);
		$this->assertEquals('test@fauthz.com', $arr['email']);
		$this->assertTrue(array_key_exists('password', $arr));
		$this->assertFalse(array_key_exists('info', $arr));
		$this->assertTrue(is_int($arr['user_id']));
		return $arr;
	}

	/**
	 * @depends create_user_activatedWithoutUsername
	 */
	public function testLoginEmail(array $arr) {
		$bool = $this->CI->fauthz->login($arr['email'], $arr['password'], '', TRUE);
		$this->assertTrue($bool);
	}

	/**
	 * @depends create_user_activated
	*/
	public function testLoginUsername(array $arr) {
		$bool = $this->CI->fauthz->login($arr['username'], $arr['password'], '', FALSE, TRUE);
		$this->assertTrue($bool);
	}

}

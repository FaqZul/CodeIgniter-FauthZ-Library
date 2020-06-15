<?php
/**
 * @author		Muhammad Faqih Zulfikar
 * @copyright	Copyright (c) 2020 FaqZul (https://github.com/FaqZul/CodeIgniter-FauthZ-Library)
 * @license		https://opensource.org/licenses/MIT 	MIT License
 * @link		https://github.com/FaqZul
 * @package		FaqZul/CodeIgniter-FauthZ-Library
 * @version		1.0.0-dev
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Website details
|
| These details are used in emails sent by authentication library.
|--------------------------------------------------------------------------
*/
$config['website_mail'] = 'webmaster@your-site.com';
$config['website_name'] = 'Your project';

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
*/
$config['phpass_hash_portable'] = FALSE;
$config['phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Registration settings
|
| 'allow_registration' = Registration is enabled or not.
| 'captcha_registration' = Registration uses CAPTCHA.
| 'email_activation' = Requires user to activate their account using email after registration.
| 'email_activation_expire' = Time before users who don't activate their account getting deleted from database. Default is 48 hours (60 * 60 * 24 * 2).
| 'email_account_details' = Email with account details is sent after registration (only when 'email_activation' is FALSE).
| 'use_username' = Username is required or not.
|
| 'username_min_length' = Min length of user's username.
| 'username_max_length' = Max length of user's username.
| 'password_min_length' = Min length of user's password.
| 'password_max_length' = Max length of user's password.
|--------------------------------------------------------------------------
*/
$config['allow_registration'] = TRUE;
$config['captcha_registration'] = FALSE;
$config['email_activation'] = TRUE;
$config['email_activation_expire'] = 60 * 60 * 24 * 2;
$config['email_account_details'] = TRUE;
$config['use_username'] = TRUE;

$config['email_max_length'] = 32;
$config['password_max_length'] = 16;
$config['password_min_length'] = 4;
$config['username_max_length'] = 16;
$config['username_min_length'] = 4;

/*
|--------------------------------------------------------------------------
| Login settings
|
| 'login_by_username' = Username can be used to login.
| 'login_by_email' = Email can be used to login.
| You have to set at least one of 2 settings above to TRUE.
| 'login_by_username' makes sense only when 'use_username' is TRUE.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60 *60 * 24).
| 'login_log' = Enable log login
| 'logout_log' = Enable log logout (login_log must TRUE)
|--------------------------------------------------------------------------
*/
$config['login_by_username'] = TRUE;
$config['login_by_email'] = TRUE;
$config['login_count_attempts'] = TRUE;
$config['login_max_attempts'] = 5;
$config['login_attempt_expire'] = 60 * 60 * 24;
$config['log_login'] = TRUE;
$config['log_logout'] = TRUE;

/*
|--------------------------------------------------------------------------
| Auto login settings
|
| 'autologin' = Try Auto Login.
| 'autologin_cookie_name' = Auto login cookie name.
| 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60 * 60 * 24 * 31 * 2).
|--------------------------------------------------------------------------
*/
$config['autologin'] = FALSE;
$config['autologin_cookie_name'] = 'autologin';
$config['autologin_cookie_life'] = 60 * 60 * 24 * 31 * 2;

/*
|--------------------------------------------------------------------------
| Forgot password settings
|
| 'forgot_password_expire' = Time before forgot password key become invalid. Default is 15 minutes (60 * 15).
|--------------------------------------------------------------------------
*/
$config['forgot_password_expire'] = 60 * 15;

/*
|--------------------------------------------------------------------------
| Captcha
|
| You can set captcha that created by FauthZ library in here.
| 'captcha_case_sensitive' = Captcha case sensitive or not.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_font_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_path' = Directory where the catpcha will be created.
|--------------------------------------------------------------------------
*/
$config['captcha_case_sensitive'] = TRUE;
$config['captcha_expire'] = 180;
$config['captcha_font_path'] = 'captcha/fonts/5.ttf';;
$config['captcha_font_size'] = 16;
$config['captcha_length'] = 8;
$config['captcha_height'] = 50;
$config['captcha_path'] = 'captcha/';
$config['captcha_width'] = 200;

/*
|--------------------------------------------------------------------------
| Path for package CodeIgniter-CRUD-Model
|--------------------------------------------------------------------------
*/
$config['crud_path'] = FCPATH . 'vendor/faqzul/codeigniter-crud-model/';

/*
|--------------------------------------------------------------------------
| Session Prefix
|
| You can add an optional prefix,
| which will be added to the session name.
|--------------------------------------------------------------------------
*/
$config['sess_prefix'] = '';

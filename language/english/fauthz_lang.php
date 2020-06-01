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

// Errors
$lang['fauthz_incorrect_password'] = 'Incorrect password';
$lang['fauthz_incorrect_login'] = 'Incorrect login';
$lang['fauthz_incorrect_email_or_username'] = 'Login or email doesn\'t exist';
$lang['fauthz_email_in_use'] = 'Email is already used by another user. Please choose another email.';
$lang['fauthz_username_in_use'] = 'Username already exists. Please choose another username.';
$lang['fauthz_current_email'] = 'This is Your current email';
$lang['fauthz_incorrect_captcha'] = 'Your confirmation code does not match the one in the image.';
$lang['fauthz_captcha_expired'] = 'Your confirmation code has expired. Please try again.';

// Notifications
$lang['fauthz_message_logged_out'] = 'You have been successfully logged out.';
$lang['fauthz_message_registration_disabled'] = 'Registration is disabled.';
$lang['fauthz_message_registration_completed_1'] = 'You have successfully registered. Check Your email address to activate Your account.';
$lang['fauthz_message_registration_completed_2'] = 'You have successfully registered.';
$lang['fauthz_message_activation_email_sent'] = 'A new activation email has been sent to %s. Follow the instructions in the email to activate Your account.';
$lang['fauthz_message_activation_completed'] = 'Your account has been successfully activated.';
$lang['fauthz_message_activation_failed'] = 'The activation code You entered is incorrect or expired.';
$lang['fauthz_message_password_changed'] = 'Your password has been successfully changed.';
$lang['fauthz_message_new_password_sent'] = 'An email with instructions for creating a new password has been sent to You.';
$lang['fauthz_message_new_password_activated'] = 'You have successfully reset Your password';
$lang['fauthz_message_new_password_failed'] = 'Your activation key is incorrect or expired. Please check Your email again and follow the instructions.';
$lang['fauthz_message_new_email_sent'] = 'A confirmation email has been sent to %s. Follow the instructions in the email to complete this change of email address.';
$lang['fauthz_message_new_email_activated'] = 'You have successfully changed Your email';
$lang['fauthz_message_new_email_failed'] = 'Your activation key is incorrect or expired. Please check Your email again and follow the instructions.';
$lang['fauthz_message_banned'] = 'You are banned.';
$lang['fauthz_message_unregistered'] = 'Your account has been deleted...';

// Email subjects
$lang['fauthz_subject_welcome'] = 'Welcome to %s!';
$lang['fauthz_subject_activate'] = 'Welcome to %s!';
$lang['fauthz_subject_forgotpassword'] = 'Forgot Your password on %s?';
$lang['fauthz_subject_resetpassword'] = 'Your new password on %s';
$lang['fauthz_subject_changeemail'] = 'Your new email address on %s';

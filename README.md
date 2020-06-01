# CodeIgniter-FauthZ-Library

[![Build Status](https://travis-ci.org/FaqZul/CodeIgniter-FauthZ-Library.svg?branch=master)](https://travis-ci.org/FaqZul/CodeIgniter-FauthZ-Library)

Authentication library for PHP-Framework [CodeIgniter](https://www.codeigniter.com). It's based on [Tank Auth](https://konyukhov.com/soft/tank_auth/), although the code was seriously reworked.

## Prerequisites
* PHP version 5.6 or newer is recommended.<br>
It should work on 5.4.8 as well, but we strongly advise you NOT to run such old versions of PHP, because of potential security and performance issues, as well as missing features.
* [CodeIgniter 3.x](https://github.com/bcit-ci/CodeIgniter)
* [CodeIgniter-CRUD-Model ~3.2.0](https://github.com/FaqZul/CodeIgniter-CRUD-Model)
* [MariaDB ^10.2.7](https://mariadb.com/downloads/)

## Feature
### It's simple
* Basic auth options ([register](#register), [login](#login), [logout](#logout), [unregister](#unregister)).
* Username is optional, only email is obligatory.
### It's secure
* Using phpass library for password hashing (instead of unsafe md5).
* Counting login attempt for bruteforce preventing (optional). Failed login attempt determined by IP and by username.
* Logging last login IP-address and time (optional).
* CAPTCHA for registration and repetitive login attempt (optional).
* Unactivated accounts and forgotten password requests auto-expire.
### It's easy to manage
* Strict MVC Model: [controller](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/tree/master/controllers) for controlling, [views](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/tree/master/views) for representation and library as model interface.
* Language file support.
* View files contain only necessary HTML code without redundant decoration.
* Most of the features are optional and can be turned or switched-off in well-documented config file.
### It's full featured
* Login using username, email address or both (depending on config settings).
* Registration is instant or after activation by email (optional).
* "Remember me" option.
* Forgot password (letting users pick a new password upon reactivation).
* Changed email or password for registered users.
* Email or password can be changed even before account is activated.
* Ban user (optional).
* User profile (optional).
* CAPTCHA support.
* HTML or plain-text emails.

## Getting Started
### Composer
```sh
faqzul@Trisquel:/var/www/CodeIgniter$ composer require faqzul/codeigniter-fauthz-library
```
### Manual
1. [Download](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/releases/latest) the latest version of the library.
2. Unzip the package to application/third_party/.
3. [Download](https://github.com/FaqZul/CodeIgniter-CRUD-Model/releases/latest) the latest version of [CodeIgniter-CRUD-Model](https://github.com/FaqZul/CodeIgniter-CRUD-Model/releases).
4. Unzip the package [CodeIgniter-CRUD-Model](https://github.com/FaqZul/CodeIgniter-CRUD-Model) to application/third_party/.

And then install database schema into Your [MariaDB](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/sql/mariadb.sql) database.

## Configuration
### Composer
Change the following line in the `application/config/autoload.php` file for use in Your controller class.
```php
$autoload['packages'] = array();
↓
$autoload['packages'] = array(FCPATH . 'vendor/faqzul/codeigniter-fauthz-library/');
```
#### Setting FauthZ Preferences
```php
$this->load->library('fauthz');
$this->fauthz->config_set('website_mail', 'web@domain.com');
$this->fauthz->config_set('website_name', 'CodeIgniter Authentication');
$this->fauthz->config_set('captcha_registration', TRUE);
```
And more preferences You can see at [config/fauthz.php](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/config/fauthz.php).
### Manual
* Change the following line in the `application/config/autoload.php` file for use in Your controller class.
```php
$autoload['packages'] = array();
↓
$autoload['packages'] = array(APPPATH . 'third_party/codeigniter-fauthz-library/');
```
* Change the following line in the `application/third_party/codeigniter-fauthz-library/config/fauthz.php`.
```php
$config['crud_path'] = FCPATH . 'vendor/faqzul/codeigniter-crud-model/';
↓
$config['crud_path'] = APPPATH . 'third_party/codeigniter-crud-model/';
```

## Usage
### [register](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L71)
Register user on the site. If registration is successfull, a new user account is created. If **[email_activation](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/config/fauthz.php#L53)** flag in config-file is set to TRUE, then this account have to be activated by clicking special link sent by email; otherwise it is activated already. Please notice: after registration user remains unauthenticated; login is still required.
### [login](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L27)
Login user on the site. If login is successfull and user account is activated, s/he is redirected to the home page. If account is not activated, then **[send_again](#send_again)** is invoked (see below). In case of login failure user remains on the same page.
### [logout](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L66)
Logout user.
### [send_again](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L117)
Send activation email again, to the same or new email address. This method is invoked every time after non-activated user logins on the site. It may be useful when user didn't receive activation mail sent on registration due to problem with mailbox or misprint in email address. User may change their email or leave it as is.
### [activate](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L140)
Activate user account. Normally this method is invoked by clicking a link in activation email. Clicking a link in **[forgot password](#forgot_password)** email activates account as well. User is verified by User ID and authentication code in the URL.
### [forgot_password](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L147)
Generate special reset code (to change password) and sent it to user. Obviously this method may be used when user has forgotten their password.
### [reset_password](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L171)
Replace user password (forgotten) with a new one (set by user). Then method can be called by clicking on link in mail. User is verified by User ID and authentication code in the URL.
### [change_password](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L193)
"Normal" password changing (as compared with resetting forgotten password). Can be called only when user is logged in and activated. For higher security user's old password is needed.
### [change_email](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L212)
Change user's email. Can be called only when user is logged in and activated. For higher security user's password is required. The new email won't be applied until it is activated by clicking a link in a mail sent to this email address.
### [reset_email](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L235)
Activate new email address and replace user's email with a new one. This method can be called by clicking a link in a mail. User is verified by User ID and authentication code in the URL.
### [unregister](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/controllers/Fauth.php#L242)
Delete user account. Can be called only when user is logged in and activated. For higher security user's password is required.

## Versioning
We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/tags).

## Authors
* **Muhammad Faqih Zulfikar** - *Developer*<br>

## License
This project is licensed under the MIT License - see the [LICENSE](https://github.com/FaqZul/CodeIgniter-FauthZ-Library/blob/master/LICENSE) file for details.
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

// Errors
$lang['fauthz_incorrect_password'] = 'Kata sandi salah';
$lang['fauthz_incorrect_login'] = 'Login salah';
$lang['fauthz_incorrect_email_or_username'] = 'Username atau Email tidak ada';
$lang['fauthz_email_in_use'] = 'Email sudah digunakan oleh pengguna lain. Silakan pilih email lain.';
$lang['fauthz_username_in_use'] = 'Nama Pengguna sudah ada. Silakan pilih nama pengguna lain.';
$lang['fauthz_current_email'] = 'Ini adalah email Anda saat ini';
$lang['fauthz_incorrect_captcha'] = 'Kode konfirmasi Anda tidak cocok dengan yang ada di gambar.';
$lang['fauthz_captcha_expired'] = 'Kode konfirmasi Anda telah kedaluwarsa. Silakan coba lagi.';

// Notifications
$lang['fauthz_message_logged_out'] = 'Anda telah berhasil keluar.';
$lang['fauthz_message_registration_disabled'] = 'Pendaftaran tidak diperbolehkan.';
$lang['fauthz_message_registration_completed_1'] = 'Anda telah berhasil mendaftar. Periksa alamat email Anda untuk mengaktifkan akun Anda.';
$lang['fauthz_message_registration_completed_2'] = 'Anda telah berhasil mendaftar.';
$lang['fauthz_message_activation_email_sent'] = 'Email aktivasi baru telah dikirim ke %s. Ikuti instruksi dalam email untuk mengaktifkan akun Anda.';
$lang['fauthz_message_activation_completed'] = 'Akun Anda telah berhasil diaktifkan.';
$lang['fauthz_message_activation_failed'] = 'Kode aktivasi yang Anda masukkan salah atau kedaluwarsa.';
$lang['fauthz_message_password_changed'] = 'Kata sandi Anda berhasil diubah.';
$lang['fauthz_message_new_password_sent'] = 'Email dengan instruksi untuk membuat kata sandi baru telah dikirimkan kepada Anda.';
$lang['fauthz_message_new_password_activated'] = 'Anda telah berhasil mengatur ulang kata sandi Anda';
$lang['fauthz_message_new_password_failed'] = 'Kunci aktivasi Anda salah atau kedaluwarsa. Silakan periksa lagi email Anda dan ikuti instruksinya.';
$lang['fauthz_message_new_email_sent'] = 'Email konfirmasi telah dikirim ke %s. Ikuti instruksi dalam email untuk menyelesaikan perubahan alamat email ini.';
$lang['fauthz_message_new_email_activated'] = 'Anda telah berhasil mengubah email Anda';
$lang['fauthz_message_new_email_failed'] = 'Kunci aktivasi Anda salah atau kedaluwarsa. Silakan periksa lagi email Anda dan ikuti instruksinya.';
$lang['fauthz_message_banned'] = 'Anda diblokir.';
$lang['fauthz_message_unregistered'] = 'Akun Anda telah dihapus...';

// Email subjects
$lang['fauthz_subject_welcome'] = 'Selamat datang di %s!';
$lang['fauthz_subject_activate'] = 'Selamat datang di %s!';
$lang['fauthz_subject_forgotpassword'] = 'Lupa kata sandi Anda di %s?';
$lang['fauthz_subject_resetpassword'] = 'Kata sandi baru Anda di %s';
$lang['fauthz_subject_changeemail'] = 'Alamat email baru Anda di %s';

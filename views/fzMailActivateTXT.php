<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
Welcome to <?php echo $site_name; ?>,

Thanks for joining <?php echo $site_name; ?>. We listed Your sign in details below, make sure You keep them safe.
To verify your email address, please follow this link:
<?php echo site_url('fauth/activate/' . $user_id . '/' . $new_email_key) . PHP_EOL; ?>

Please verify Your email within <?php echo $activation_period; ?> hours, otherwise Your registration will become invalid and You will have to register again.
<?php echo (( ! empty($username)) ? 'Your username: ' . $username . PHP_EOL: '') . 'Your email address: ' . $email . PHP_EOL; ?>


Have fun!
The <?php echo $site_name; ?> Team
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
Welcome to <?php echo $site_name ?>,

Thanks for joining <?php echo $site_name; ?>. We listed Your sign in details below, make sure You keep them safe.
Follow this link to login on the site:

<?php echo site_url('fauth/login'); ?>

<?php echo (( ! empty($username)) ? 'Your username: ' . $username . PHP_EOL: PHP_EOL) . 'Your email address: ' . $email . PHP_EOL; ?>


Have fun!
The <?php echo $site_name; ?> Team
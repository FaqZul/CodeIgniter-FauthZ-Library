<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
Hi<?php echo ( ! empty($username)) ? ' ' . $username: ''; ?>,

You have changed Your password.
Please, keep it in Your records so You don't forget it.
<?php echo (( ! empty($username)) ? 'Your Username: ' . $username . PHP_EOL: '') . 'Your email address: ' . $email . PHP_EOL; ?>


Thank You,
The <?php echo $site_name; ?> Team
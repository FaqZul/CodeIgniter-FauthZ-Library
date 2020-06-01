<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
Hi<?php echo ( ! empty($username)) ? ' ' . $username: ''; ?>,

You have changed Your email address for <?php echo $site_name; ?>.
Follow this link to confirm Your new email address:
<?php echo site_url('fauth/reset-email/' . $user_id . '/' . $new_email_key) . PHP_EOL; ?>

Your new email: <?php echo $new_email . PHP_EOL; ?>

You received this email, because it was requested by a <?php echo $site_name; ?> user. If You have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.


Thank You,
The <?php echo $site_name; ?> Team
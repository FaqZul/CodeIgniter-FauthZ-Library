<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
Hi<?php echo ( ! empty($username)) ? ' ' . $username: ''; ?>,

Forgot Your password, huh? No big deal.
To create a new password, just follow this link:
<?php echo site_url('fauth/reset-password/' . $user_id . '/' . $new_password_key); ?>


You received this email, because it was requested by a <?php echo $site_name; ?> user. This is part of the procedure to create a new password on the system. If You DID NOT request a new password then please ignore this email and Your password will remain the same.


Thank You,
The <?php echo $site_name; ?> Team
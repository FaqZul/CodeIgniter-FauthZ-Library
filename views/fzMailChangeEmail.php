<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your new email address on <?php echo $site_name; ?></title>
	</head>
	<body>
		<div style="margin: 0 auto; max-width: 800px; padding: 30px 0;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
							<h2 style="color: black; font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px;">Your new email address on <?php echo $site_name; ?>!</h2>
							You have changed Your email address for <?php echo $site_name; ?>.<br />
							Follow this link to confirm Your new email address:<br /><br />
							<big style="font: 16px/18px Arial, Helvetica, sans-serif; font-weight: bold;">
								<a href="<?php echo site_url('fauth/reset-email/' . $user_id . '/' . $new_email_key); ?>" style="color: #3366CC;">Confirm Your new email</a>
							</big><br /><br />
							Link doesn't work? Copy the following link to Your browser address bar:<br />
							<nobr>
								<a href="<?php echo site_url('fauth/reset-email/' . $user_id . '/' . $new_email_key); ?>" style="color: #3366CC;"><?php echo site_url('fauth/reset-email/' . $user_id . '/' . $new_email_key); ?></a>
							</nobr><br /><br />
							Your new email: <?php echo $new_email; ?><br /><br />
							You received this email, because it was requested by a <a href="<?php echo site_url(''); ?>" style="color: #3366CC;"><?php echo $site_name; ?></a> user. If You have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br /><br /><br />
							Have fun!<br />The <?php echo $site_name; ?> Team
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
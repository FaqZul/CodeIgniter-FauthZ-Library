<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Create a new password on <?php echo $site_name; ?></title>
	</head>
	<body>
		<div style="margin: 0 auto; max-width: 800px; padding: 30px 0;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
							<h2 style="color: black; font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px;">Create a new password</h2>
							Forgot Your password, huh? No big deal.<br />
							To create a new password, just follow this link:<br /><br />
							<big style="font: 16px/18px Arial, Helvetica, sans-serif; font-weight: bold;">
								<a href="<?php echo site_url('fauth/reset-password/' . $user_id . '/' . $new_password_key); ?>" style="color: #3366CC;">Create a new password</a>
							</big><br /><br />
							Link doesn't work? Copy the following link to Your browser address bar:<br />
							<nobr>
								<a href="<?php echo site_url('fauth/reset-password/' . $user_id . '/' . $new_password_key); ?>" style="color: #3366CC;"><?php echo site_url('fauth/reset-password/' . $user_id . '/' . $new_password_key); ?></a>
							</nobr><br /><br />
							You received this email, because it was requested by a <a href="<?php echo site_url(); ?>" style="color: #3366CC;"><?php echo $site_name; ?></a> user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and Your password will remain the same.<br /><br /><br />
							Thank You,<br />The <?php echo $site_name; ?> Team
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
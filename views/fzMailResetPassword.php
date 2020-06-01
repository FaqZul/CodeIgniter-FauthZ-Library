<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your new password on <?php echo $site_name; ?></title>
	</head>
	<body>
		<div style="margin: 0 auto; max-width: 800px; padding: 30px 0;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
							<h2 style="color: black; font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px;">Your new password on <?php echo $site_name; ?></h2>
							You have changed your password.<br />
							Please, keep it in your records so you don't forget it.<br />
							<?php echo ( ! empty($username)) ? 'Your username: ' . $username . '<br />': ''; ?>Your email address: <?php echo $email; ?><br /><br /><br />
							Thank You,<br />The <?php echo $site_name; ?> Team
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
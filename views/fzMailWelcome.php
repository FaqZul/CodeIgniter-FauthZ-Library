<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to <?php echo $site_name; ?></title>
	</head>
	<body>
		<div style="margin: 0 auto; max-width: 800px; padding: 30px 0;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
							<h2 style="color: black; font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px;">Welcome to <?php echo $site_name; ?>!</h2>
							Thanks for joining <?php echo $site_name; ?>. We listed Your sign in details below, make sure You keep them safe.<br />
							To open Your <?php echo $site_name; ?> homepage, please follow this link:<br /><br />
							<big style="font: 16px/18px Arial, Helvetica, sans-serif; font-weight: bold;">
								<a href="<?php echo site_url('fauth/login'); ?>" style="color: #3366CC;">Go to <?php echo $site_name; ?> now!</a>
							</big><br />
							Link doesn't work? Copy the following link to Your browser address bar:<br />
							<nobr>
								<a href="<?php echo site_url('fauth/login'); ?>" style="color: #3366CC;"><?php echo site_url('fauth/login'); ?></a>
							</nobr><br /><br />
							<?php echo ( ! empty($username)) ? 'Your username: ' . $username . '<br />': ''; ?>Your email address: <?php echo $email; ?><br /><br /><br />
							Have fun!<br />The <?php echo $site_name; ?> Team
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
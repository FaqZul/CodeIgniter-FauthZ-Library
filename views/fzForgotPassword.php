<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<?php echo form_open(); ?>
			<table>
				<tbody>
					<tr>
						<td>
							<label for="login">EMail or Username</label>
						</td>
						<td><input autofocus="" id="login" name="login" required="" style="width: 100%;" type="login" value="<?php echo set_value('login'); ?>" /></td>
						<td style="color: red;"><?php echo form_error('login', ' ', ' ') . (isset($error['login']) ? $error['login']: ''); ?></td>
					</tr>
					<tr>
						<td colspan="3"><input name="reset" type="submit" value="Get a new password" /></td>
					</tr>
					<tr>
						<td style="text-align: center;">Elapsed Time: {elapsed_time}</td>
						<td style="text-align: center;">Memory Usage: {memory_usage}</td>
					</tr>
				</tbody>
			</table>
		<?php echo form_close(PHP_EOL); ?>
	</body>
</html>
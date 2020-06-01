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
							<label for="email">EMail</label>
						</td>
						<td><input autofocus="" id="email" maxlength="<?php echo $this->fauthz->config('email_max_length'); ?>" name="email" required="" style="width: 100%;" type="email" value="<?php echo set_value('email'); ?>" /></td>
						<td style="color: red;"><?php echo form_error('email', ' ', ' ') . (isset($error['email']) ? $error['email']: ''); ?></td>
					</tr>
<?php
if ($useUsername) {
?>
					<tr>
						<td>
							<label for="username">Username</label>
						</td>
						<td><input id="username" maxlength="<?php echo $this->fauthz->config('username_max_length'); ?>" minlength="<?php echo $this->fauthz->config('username_min_length'); ?>" name="username" required="" style="width: 100%;" type="text" value="<?php echo set_value('username'); ?>" /></td>
						<td style="color: red;"><?php echo form_error('username', ' ', ' ') . (isset($error['username']) ? $error['username']: ''); ?></td>
					</tr>
<?php
}
?>
					<tr>
						<td>
							<label for="password">Password</label>
						</td>
						<td><input id="password" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password', ' ', ' '); ?></td>
					</tr>
					<tr>
						<td>
							<label for="password_confirm">Confirm Password</label>
						</td>
						<td><input id="password_confirm" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password_confirm" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password_confirm', ' ', ' '); ?></td>
					</tr>
<?php
if (isset($captcha)) {
?>
					<tr>
						<td colspan="2" style="text-align: center;"><?php echo $captcha; ?></td>
					</tr>
					<tr>
						<td>
							<label for="captcha">Confirmation Code</label>
						</td>
						<td><input id="captcha" name="captcha" maxlength="<?php echo $this->fauthz->config('captcha_length'); ?>" required="" style="width: 100%;" type="text" /></td>
						<td style="color: red;"><?php echo form_error('captcha', ' ', ' '); ?></td>
					</tr>
<?php
}
?>
					<tr>
						<td colspan="3"><input name="submit" type="submit" value="Register" /></td>
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
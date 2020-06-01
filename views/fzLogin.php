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
							<label for="login"><?php echo $login_label; ?></label>
						</td>
						<td><input autofocus="" id="login" name="login" required="" style="width: 100%;" type="text" value="<?php echo set_value('login'); ?>" /></td>
						<td style="color: red;"><?php echo form_error('login', ' ', ' ') . (isset($error['login']) ? $error['login']: ''); ?></td>
					</tr>
					<tr>
						<td>
							<label for="password">Password</label>
						</td>
						<td><input id="password" name="password" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password', ' ', ' ') . (isset($error['password']) ? $error['password']: ''); ?></td>
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
						<td colspan="3">
							<input id="remember" name="remember" type="checkbox" value="1" />
							<label for="remember">Remember Me</label>
						</td>
					</tr>
					<tr>
						<td colspan="3"><input name="submit" type="submit" value="Let Me in" /></td>
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
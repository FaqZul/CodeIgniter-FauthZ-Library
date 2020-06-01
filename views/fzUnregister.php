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
							<label for="password">Password</label>
						</td>
						<td><input autofocus="" id="password" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password', ' ', ' ') . (isset($error['password']) ? $error['password']: ''); ?></td>
					</tr>
					<tr>
						<td colspan="3"><input name="unreg" type="submit" value="Delete Account" /></td>
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
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
							<label for="password_old">Old Password</label>
						</td>
						<td><input autofocus="" id="password_old" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password_old" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password_old', ' ', ' ') . (isset($error['password_old']) ? $error['password_old']: ''); ?></td>
					</tr>
					<tr>
						<td>
							<label for="password_new">New Password</label>
						</td>
						<td><input autofocus="" id="password_new" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password_new" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password_new', ' ', ' ') . (isset($error['password_new']) ? $error['password_new']: ''); ?></td>
					</tr>
					<tr>
						<td>
							<label for="password_new_confirm">Confirm New Password</label>
						</td>
						<td><input autofocus="" id="password_new_confirm" maxlength="<?php echo $this->fauthz->config('password_max_length'); ?>" minlength="<?php echo $this->fauthz->config('password_min_length'); ?>" name="password_new_confirm" required="" style="width: 100%;" type="password" /></td>
						<td style="color: red;"><?php echo form_error('password_new_confirm', ' ', ' ') . (isset($error['password_new_confirm']) ? $error['password_new_confirm']: ''); ?></td>
					</tr>
					<tr>
						<td colspan="3"><input name="change" type="submit" value="Change Password" /></td>
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
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
					<tr>
						<td colspan="3"><input name="submit" type="submit" value="Send" /></td>
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
<div class="wrap">
	<h1><?php _e('Login Settings', 'vkapi') ?></h1>

	<p>
		<?php printf(__('If you dont have <b>Application ID</b> and <b>Secure key</b> : go this <a href="%s" target="_blank">link</a> and select <b>Web-site</b>. It\'s easy.', 'vkapi'), 'https://vk.com/editapp?act=create'); ?>

		<br/>

		<?php printf(__('If don\'t remember: go this <a href="%s" target="_blank">link</a> and choose need application.', 'vkapi'), 'https://vk.com/apps?act=manage'); ?>
	</p>

	<form action="options.php" method="post" novalidate="novalidate">
		<?php settings_fields('darx-login'); ?>

		<div class="card" style="max-width:100%">
			<?php do_settings_sections('darx-login-settings'); ?>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
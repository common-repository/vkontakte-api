<div class="wrap">
	<h1><?php _e('Crosspost Settings', 'vkapi') ?></h1>

	<form action="options.php" method="post" novalidate="novalidate">
		<?php settings_fields('darx-crosspost'); ?>

		<div class="card" style="max-width:100%">
			<?php do_settings_sections('darx-crosspost-settings'); ?>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
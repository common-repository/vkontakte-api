<style>
	a:focus {
		box-shadow: none;
	}

	.card {
		max-width: 100%;
	}

	.darx-tab {
		display: none;
	}

	.darx-tab.active {
		display: block;
	}
</style>

<div class="wrap">
	<h1><?php _e('Likes Settings', 'vkapi') ?></h1>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a data-tab="base" href="" class="nav-tab"><?php _e('Base', 'vkapi'); ?></a>
		<a data-tab="vk" href="" class="nav-tab">VKontakte</a>
		<a data-tab="fb" href="" class="nav-tab">Facebook</a>
		<a data-tab="tw" href="" class="nav-tab">Twitter</a>
		<a data-tab="mr" href="" class="nav-tab">Mail.ru</a>
		<a data-tab="ok" href="" class="nav-tab">OK.ru</a>
	</h2>

	<form action="options.php" method="post" novalidate="novalidate">
		<?php settings_fields('darx-likes'); ?>

		<div class="darx-tab" id="tab-base">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-base'); ?>
			</div>
		</div>
		<div class="darx-tab" id="tab-vk">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-vk'); ?>
			</div>
		</div>
		<div class="darx-tab" id="tab-fb">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-fb'); ?>
			</div>
		</div>
		<div class="darx-tab" id="tab-tw">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-tw'); ?>
			</div>
		</div>
		<div class="darx-tab" id="tab-mr">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-mr'); ?>
			</div>
		</div>
		<div class="darx-tab" id="tab-ok">
			<div class="card">
				<?php do_settings_sections('darx-likes-settings-ok'); ?>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
	<script>
        jQuery(function($) {
            var $navs = $('.nav-tab');
            $navs.on('click', function(e) {
                e.preventDefault();

                var $this = $(this);
                var tab = $this.data('tab');
                window.location.hash = tab;

                $('.nav-tab').removeClass('nav-tab-active');
                $this.addClass('nav-tab-active');

                $('.darx-tab').removeClass('active');
                $('#tab-' + tab).addClass('active');

                return false;
            });

            if( window.location.hash ) {
                var hash = window.location.toString().split('#')[1]; // coz firefox bug
                var $nav = $('a[data-tab="' + hash + '"]');
                if( $nav.length ) {
                    $nav.triggerHandler('click');
                }
            } else {
                $navs.first().triggerHandler('click');
            }

            $('#submit').on('mousedown', function() {
                var hash = window.location.toString().split('#')[1]; // coz firefox bug
                var $ref = $(this).closest('form').find('input[name="_wp_http_referer"]');
                $ref.val($ref.val() + '#' + hash);

            });
        });
	</script>
</div>
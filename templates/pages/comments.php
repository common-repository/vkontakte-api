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
            <h1>Comments Settings</h1>

            <h2 class="nav-tab-wrapper wp-clearfix">
                <a data-tab="vk" href="" class="nav-tab">VKontakte</a>
                <a data-tab="fb" href="" class="nav-tab">Facebook</a>
                <a data-tab="base" href="" class="nav-tab"><?php _e('Base', 'vkapi'); ?></a>
</h2>

<form action="options.php" method="post" novalidate="novalidate">
	<?php settings_fields('darx-comments'); ?>

	<div class="darx-tab" id="tab-base">
		<div class="card">
			<?php do_settings_sections('darx-comments-settings-base'); ?>
		</div>
	</div>
	<div class="darx-tab" id="tab-vk">
		<p>
			<?php printf(__('If you dont have <b>Application ID</b> and <b>Secure key</b> : go this <a href="%s" target="_blank">link</a> and select <b>Web-site</b>. It\'s easy.', 'vkapi'), 'https://vk.com/editapp?act=create'); ?>

			<br/>

			<?php printf(__('If don\'t remember: go this <a href="%s" target="_blank">link</a> and choose need application.', 'vkapi'), 'https://vk.com/apps?act=manage'); ?>
		</p>

		<div class="card">
			<?php do_settings_sections('darx-comments-settings-vk'); ?>
		</div>
	</div>
	<div class="darx-tab" id="tab-fb">
		<p>
			<?php printf(__('Facebook <b>App ID</b> : go this <a href="%s" target="_blank">link</a> and register your site(blog). It\'s easy.', 'vkapi'), 'https://developers.facebook.com/apps'); ?>
		</p>

		<div class="card">
			<?php do_settings_sections('darx-comments-settings-fb'); ?>
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
<?php
/*
Plugin Name: VKontakte API - crossposting, comments, social buttons, login
Plugin URI: https://wordpress.org/plugins/vkontakte-api/
Description: Add API functions from vk.com in your own blog.
Version: 4.2.4
Author: Webcraftic
Author URI: https://cm-wp.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /translate
Text Domain: vkapi
*/

// Exit if accessed directly
if( !defined('ABSPATH') ) {
    exit;
}

/**
 * Версия VK API. Подставляется в запросы
 */
define('WVAI_API_VERSION', '5.95');

/**
 * -----------------------------------------------------------------------------
 * CHECK REQUIREMENTS
 * Check compatibility with php and wp version of the user's site. As well as checking
 * compatibility with other plugins from Webcraftic.
 * -----------------------------------------------------------------------------
 */

require_once( dirname( __FILE__ ) . '/libs/factory/core/includes/class-factory-requirements.php' );

$plugin_info = array(
	'prefix'               => 'wbcr_vkapi_',
	'plugin_name'          => 'wbcr_vkapi',
	'plugin_title'         => __( 'VKontakte API', 'vkapi' ),
	'plugin_text_domain'   => 'vkapi',

	// PLUGIN SUPPORT
	'support_details'      => array(
		'url' => site_url(),
	),

	// Настройки рекламы от CreativeMotion
	'render_adverts'       => true, // Показывать рекламу CreativeMotion в админке Wordpress?
	'adverts_settings'     => array(
		'dashboard_widget' => true, // если true, показывать виджет новостей на страницу Dashboard
		'notice'           => true, // если true, показывать сквозное уведомление на всех страницах админ панели Wordpress
	),

	// FRAMEWORK MODULES
	'load_factory_modules' => array(
		array( 'libs/factory/adverts', 'factory_adverts_124', 'admin' ),
	),
);

/**
 * Checks compatibility with WordPress, php and other plugins.
 */
$wbcr_compatibility = new Wbcr_Factory446_Requirements( __FILE__, array_merge( $plugin_info, array(
	'plugin_already_activate' => defined( 'WBCR_VAI_PLUGIN_ACTIVE' ),
	'required_php_version'    => '5.4',
	'required_wp_version'     => '4.2.0',
) ) );

/**
 * If the plugin is compatible, it will continue its work, otherwise it will be stopped and the user will receive a warning.
 */
if ( ! $wbcr_compatibility->check() ) {
	return;
}

define( 'WBCR_VAI_PLUGIN_ACTIVE', true );

define( 'WBCR_VAI_PLUGIN_VERSION', $wbcr_compatibility->get_plugin_version() );

/**
 * Папка плагина
 */
define( 'WVAI_PLUGIN_DIR', dirname( __FILE__ ) );
/**
 * Относительный путь к папке плагина
 */
define( 'WVAI_PLUGIN_BASE', plugin_basename( __FILE__ ) );
/**
 * URL папки плагина
 */
define( 'WVAI_PLUGIN_URL', plugins_url( null, __FILE__ ) );

require_once( WVAI_PLUGIN_DIR . '/classes/parent.class.php' );
require_once( WVAI_PLUGIN_DIR . '/classes/js.class.php' );

/**
 * Class VK_api
 */
class VK_api extends Darx_Parent {

	/**
	 * @var string имя плагина
	 */
	private $_plugin_basename;

	/**
	 * Обновление логики, в зависимости от версии API
     * (сейчас не актуально)
	 */
	private function _update()
		{
			$version_current = '3.32.5.9';
			$version_old = intval(get_option('vkapi_version'));

			if( version_compare($version_old, $version_current) !== 0 ) {
				update_option('vkapi_version', $version_current);
			}

			if( version_compare($version_old, '3.32.5') === -1 ) {
				add_option('vkapi_crosspost_title', '1');
				add_option('vkapi_comm_is_switcher', '1');
			}

			if( version_compare($version_old, 1) === -1 ) {
				update_option('vkapi_vk_group', -intval(get_option('vkapi_vk_group')));
				update_option('vkapi_comm_is_postid', '1');
				delete_option('vkapi__active_installs');
				delete_option('vkapi_some_logo');
				delete_option('vkapi_some_logo_e');
				delete_option('fbapi_admin_id');
				delete_option('vkapi_crosspost_category');

				wp_clear_scheduled_hook('vkapi_cron');
				wp_clear_scheduled_hook('vkapi_cron_hourly');
				wp_clear_scheduled_hook('vkapi_cron_daily');
				wp_schedule_event(time(), 'hourly', 'vkapi_cron_hourly');
				wp_schedule_event(time(), 'daily', 'vkapi_cron_daily');

				$options = array(
					'vkapi_show_comm',
					'vkapi_show_first',
					'vkapi_show_like',
					'vkapi_show_share',
					'vkapi_login',
					'fbapi_show_comm',
					'fbapi_show_like',
					'gpapi_show_like',
					'tweet_show_share',
					'mrc_show_share',
					'ok_show_share',
				);
				foreach($options as $option) {
					$value = get_option($option) === 'true'
						? 1
						: 0;
					update_option($option, $value);
				}
			}

			if( version_compare($version_old, $version_current) === -1 ) {
				$response = wp_remote_get('https://api.vk.com/method/utils.checkLink?url=' . get_site_url());
				$response = wp_remote_retrieve_body($response);
				$response = json_decode($response, true);
				if( isset($response['response']) && $response['response']['status'] === 'status' ) {
					$this->_notice_error('api.vk.com', -1, 'Site URL is banned on vk.com platform!');
				};
			}
		}

	/**
	 * VK_api constructor.
	 */
	public function __construct()
		{

			// init

			$this->_plugin_basename = WVAI_PLUGIN_BASE;
			$this->_update();
			load_plugin_textdomain('vkapi', false, dirname($this->_plugin_basename) . '/translate/');

			// installation

			register_activation_hook(__FILE__, array('VK_api', 'install'));
			register_uninstall_hook(__FILE__, array('VK_api', 'uninstall'));
			register_deactivation_hook(__FILE__, array('VK_api', 'pause'));

			// admin side

			add_action('admin_menu', array($this, 'add_page_modules'), 1);
			add_action('admin_menu', array($this, 'add_page_misc'), 1024);
			add_action('admin_init', array($this, 'register_settings'));
			add_action('admin_notices', array($this, 'post_notice')); # fix admin notice

			add_filter('plugin_action_links_' . $this->_plugin_basename, array($this, 'own_actions_links'));
			add_filter('plugin_row_meta', array($this, 'plugin_meta'), 1, 2);
			add_filter('login_headerurl', array($this, 'loginHeaderUrl'));

			// schedule
			add_action('vkapi_cron_hourly', array($this, 'cron_hourly'));
			add_action('vkapi_cron_daily', array($this, 'cron_daily'));

			// misc
			if( get_option('vkapi_some_revision_d') ) {
				add_action('admin_init', array($this, 'disableRevisions'));
				remove_action('pre_post_update', 'wp_save_post_revision');
			}

			// support other plugins

			add_action('um_after_form', 'Darx_Login::add_login_form');
		}

	/**
	 * Активация плагина
	 */
	static public function install()
		{
		    wp_clear_scheduled_hook('vkapi_cron_hourly');
			wp_clear_scheduled_hook('vkapi_cron_daily');
			wp_schedule_event(time(), 'hourly', 'vkapi_cron_hourly');
			wp_schedule_event(time(), 'daily', 'vkapi_cron_daily');
			// init platform
			add_option('vkapi_appid');
			add_option('vkapi_api_secret');
			add_option('vkapi_at');
			// comments
			add_option('vkapi_comm_width', '600');
			add_option('vkapi_comm_limit', '15');
			add_option('vkapi_comm_graffiti', '1');
			add_option('vkapi_comm_photo', '1');
			add_option('vkapi_comm_audio', '1');
			add_option('vkapi_comm_video', '1');
			add_option('vkapi_comm_link', '1');
			add_option('vkapi_comm_autoPublish', '1');
			add_option('vkapi_comm_height', '0');
			add_option('vkapi_show_first', 'wp');
			add_option('vkapi_notice_admin', '1');
			add_option('vkapi_comm_is_postid', '1');
			// button align
			add_option('vkapi_align', 'left');
			add_option('vkapi_like_top', '0');
			add_option('vkapi_like_bottom', '1');
			// vk like
			add_option('vkapi_like_type', 'full');
			add_option('vkapi_like_verb', '0');
			// vk share
			add_option('vkapi_share_type', 'round');
			add_option('vkapi_share_text', 'Сохранить');
			// facebook
			// show ?
			add_option('vkapi_show_first', '0');
			add_option('vkapi_show_like', '0');
			add_option('vkapi_show_share', '0');
			add_option('fbapi_show_like', '0');
			add_option('fbapi_show_comm', '0');
			add_option('gpapi_show_like', '0');
			add_option('tweet_show_share', '0');
			add_option('mrc_show_share', '0');
			add_option('ok_show_share', '0');
			// over
			add_option('vkapi_some_revision_d', '1');
			add_option('vkapi_close_wp', '0');
			add_option('vkapi_login', '1');
			// categories
			add_option('vkapi_like_cat', '0');
			add_option('vkapi_share_cat', '0');
			add_option('fbapi_like_cat', '0');
			add_option('gpapi_like_cat', '0');
			add_option('tweet_share_cat', '0');
			add_option('mrc_share_cat', '0');
			add_option('ok_share_cat', '0');
			// tweet
			add_option('tweet_account');
			// crosspost
			add_option('vkapi_vk_group');
			add_option('vkapi_crosspost_default', '0');
			add_option('vkapi_crosspost_title', '1');
			add_option('vkapi_crosspost_length', '888');
			add_option('vkapi_crosspost_images_count', '1');
			add_option('vkapi_crosspost_delay', '0');
			add_option('vkapi_crosspost_link', '0');
			add_option('vkapi_crosspost_signed', '1');
			add_option('vkapi_crosspost_anti', '0');
			add_option('vkapi_crosspost_post_types', array('post', 'page'));
			add_option('vkapi_tags', '0');
		}

	// todo: what? how? Один не самый адекватный сказал что ловит 500 ошибку при деактивации

	/**
	 * Деактивация плагина
	 */
	static function pause()
		{
			if( function_exists('wp_clear_scheduled_hook') ) {
				wp_clear_scheduled_hook('vkapi_cron_hourly');
				wp_clear_scheduled_hook('vkapi_cron_daily');
			}
		}

	/**
	 * Удаление плагина
	 */
	static function uninstall()
		{
			delete_option('vkapi_appid');
			delete_option('vkapi_api_secret');
			delete_option('vkapi_comm_width');
			delete_option('vkapi_comm_limit');
			delete_option('vkapi_comm_graffiti');
			delete_option('vkapi_comm_photo');
			delete_option('vkapi_comm_audio');
			delete_option('vkapi_comm_video');
			delete_option('vkapi_comm_link');
			delete_option('vkapi_comm_autoPublish');
			delete_option('vkapi_comm_height');
			delete_option('vkapi_show_first');
			delete_option('vkapi_like_type');
			delete_option('vkapi_like_verb');
			delete_option('vkapi_like_cat');
			delete_option('vkapi_like_top');
			delete_option('vkapi_like_bottom');
			delete_option('vkapi_share_cat');
			delete_option('vkapi_share_type');
			delete_option('vkapi_share_text');
			delete_option('vkapi_align');
			delete_option('vkapi_show_comm');
			delete_option('vkapi_show_like');
			delete_option('fbapi_show_comm');
			delete_option('vkapi_show_share');
			delete_option('vkapi_some_logo');
			delete_option('vkapi_some_revision_d');
			delete_option('vkapi_close_wp');
			delete_option('vkapi_login');
			delete_option('tweet_show_share');
			delete_option('tweet_account');
			delete_option('tweet_share_cat');
			delete_option('gpapi_show_like');
			delete_option('fbapi_like_cat');
			delete_option('fbapi_show_like');
			delete_option('gpapi_like_cat');
			delete_option('mrc_show_share');
			delete_option('mrc_share_cat');
			delete_option('ok_show_share');
			delete_option('ok_share_cat');
			delete_option('vkapi_vk_group');
			delete_option('vkapi_at');
			delete_option('vkapi_crosspost_default');
			delete_option('vkapi_crosspost_title');
			delete_option('vkapi_crosspost_length');
			delete_option('vkapi_crosspost_images_count');
			delete_option('vkapi_crosspost_delay');
			delete_option('vkapi_crosspost_link');
			delete_option('vkapi_crosspost_signed');
			delete_option('vkapi_crosspost_anti');
			delete_option('vkapi_crosspost_post_types');
			delete_option('vkapi_crosspost_is_categories');
			delete_option('vkapi_tags');
			delete_option('ya_show_share');
			delete_option('ya_share_cat');
		}

	/**
	 * Добавление страницы Модулей
     * и пунка меню Модули
	 */
	public function add_page_modules()
		{
			add_menu_page('Social API — ' . __('Modules', 'vkapi'), 'Social API', 'manage_options', 'darx-modules', array(
				$this,
				'render_page'
			), 'dashicons-controls-volumeon');

			add_submenu_page('darx-modules', __('Modules', 'vkapi') . '— Social API ', __('Modules', 'vkapi'), 'manage_options', 'darx-modules', array(
				$this,
				'render_page'
			));
		}

	/**
	 * Добавление в меню пункта Полезные мелочи
	 */
	public function add_page_misc()
		{

			add_submenu_page('darx-modules', __('Misc', 'vkapi') . '— Social API ', __('Misc', 'vkapi'), 'manage_options', 'darx-misc-settings', array(
				$this,
				'page_misc'
			));
		}

	/**
	 * Уведомление
	 */
	public function post_notice()
		{
			// Предупреждение, что нужно установить сервисный ключ
			if( get_option('vkapi_show_comm') && !get_option('vkapi_api_service_token') ) {
				echo '<div class="notice notice-error">
						<p>Для использования комментариев Вконтакте, требуется установить "Сервисный ключ", пожалуйста, перейдите на <a href="' . admin_url('admin.php?page=darx-comments-settings#vk') . '">страницу настроек</a> для установки ключа. Ключ вы можете получить в настройках приложения Вконтакте.</p>
				      </div>';
			}

			$array = get_option('vkapi_msg');
			if( empty($array) ) {
				return;
			}
			foreach($array as $item) {
				echo "<div class='{$item['type']}'><p>{$item['msg']}</p></div>";
			}
			delete_option('vkapi_msg');
		}

	// todo: translate

	/**
     * Добавляет ссылки в список плагинов
     *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function own_actions_links($links)
		{
			unset($links['edit']);
			$links['settings'] = '<a href="admin.php?page=darx-modules">' . __('Settings') . '</a>';
			$links['deactivate'] = '<span id="vkapi_deactivate">' . $links['deactivate'] . '</span>';

			$script = '
<script>
	jQuery(document).on("click", "#vkapi_deactivate > a", function(e) {
		if ( !confirm("Если возникли сложности — ты всегда можешь связаться с автором плагина.\\r\\nПродолжить?") ) {
			e.preventDefault();
			return false;
		}
	});
</script>';
			$links['deactivate'] .= $script;

			return $links;
		}

	/**
     * Ссылка на логотипе на странице авторизации
     *
	 * @return string|void
	 */
	public function loginHeaderUrl()
		{
			return home_url();
		}

	/**
	 * Отключение ревизий
	 */
	public function disableRevisions()
		{
			if(!defined('WP_POST_REVISIONS'))
			    define('WP_POST_REVISIONS',false);
		}

	/**
     * Добавляет ссылки в список плагинов
     *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function plugin_meta($links, $file)
		{
			if( $file == $this->_plugin_basename ) {
				$href = admin_url('admin.php?page=darx-modules');
				$anchor = __('Settings');
				$links[] = "<a href='{$href}'>{$anchor}</a>";
				$links[] = 'Code is poetry!';
			}

			return $links;
		}

	/**
	 * Отправка статистики приложение VK API
	 */
	public function cron_daily()
		{
			if( $vk_at = get_option('vkapi_at') ) {
				wp_remote_get('https://api.vk.com/method/stats.trackVisitor?v='.WVAI_API_VERSION.'&access_token=' . $vk_at, array('user-agent' => 'Standalone'));
			}
		}

	/**
	 *
	 */
	public function cron_hourly()
		{
			// todo: process crosspost old post here in future
			// todo: refactor anti crosspost and move to own module
			if( get_option('vkapi_crosspost_anti') ) {
				chdir(plugin_dir_path(__FILE__));
				require_once('php/cron.php');
			}
		}

	/**
     * Страница со списком модулей
	 * @throws Exception
	 */
	public function page_modules()
		{
			?>


		<?php
		}

	/**
	 * Страница Полезные мелочи
	 */
	public function page_misc()
		{
			?>
			<div class="wrap">
				<h1><?php _e('Misc', 'vkapi'); ?></h1>

				<p>
					Полезные мелочи
				</p>

				<form action="options.php" method="post" novalidate="novalidate">
					<?php settings_fields('darx-misc'); ?>

					<div class="darx-tab" id="tab-base">
						<div class="card">
							<?php do_settings_sections('darx-settings-misc-base'); ?>
						</div>
					</div>

					<?php submit_button(); ?>
				</form>
			</div>
		<?php
		}

	/**
	 * Регистрация настроек на странице Полезные мелочи
	 */
	public function register_settings()
		{
			add_settings_section('darx-base', // id
				'', // title
				'__return_null', // callback
				'darx-settings-misc-base' // page
			);

			register_setting('darx-misc', 'vkapi_some_revision_d');
			add_settings_field('vkapi_some_revision_d', // id
				__('Disable Revision Post Save', 'vkapi'), // title
				array($this, 'render_settings_field'), // callback
				'darx-settings-misc-base', // page
				'darx-base', // section
				array(
					'label_for' => 'vkapi_some_revision_d',
					'type' => 'checkbox',
					'descr' => '',
				) // args
			);
		}
}

new VK_api();

/* =Vkapi Widgets
todo: refactor
-------------------------------------------------------------- */
/*Тут были классы виджетов*/

require_once( WVAI_PLUGIN_DIR . '/libs/factory/core/boot.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/ajax.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/crosspost.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/comments.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/likes.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/login.php' );
require_once( WVAI_PLUGIN_DIR . '/includes/class.plugin.php' );

try {
	new WVAI_Plugin( __FILE__, array_merge( $plugin_info, array(
		'plugin_version'     => WBCR_VAI_PLUGIN_VERSION,
		'plugin_text_domain' => $wbcr_compatibility->get_text_domain(),
	) ) );
} catch ( Exception $exception ) {
	// Plugin wasn't initialized due to an error
	define( 'WINP_PLUGIN_THROW_ERROR', true );

	$wbcr_plugin_error_func = function () use ( $exception ) {
		$error = sprintf( "The %s plugin has stopped. <b>Error:</b> %s Code: %s", 'VKontakte API', $exception->getMessage(), $exception->getCode() );
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action( 'admin_notices', $wbcr_plugin_error_func );
	add_action( 'network_admin_notices', $wbcr_plugin_error_func );
}

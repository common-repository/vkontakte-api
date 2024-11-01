<?php

	if( !defined('DB_NAME') ) {
		die;
		bitch;
		die;
	}

	// todo: update widget

/**
 * Модуль Логин
 */
class Darx_Login extends Darx_Parent {

	/**
	 * URL API
	 * @var string
	 */
	static public $login_url_vk = 'social-api/login/vk';
	/**
	 * @var string
	 */
	static public $login_url_vk_full = '';

	/**
	 * Darx_Login constructor.
	 */
	public function __construct()
		{
			$this->module_slug = 'login';
		    // urls
			self::$login_url_vk_full = site_url() . '/' . self::$login_url_vk;

			// add sub-page
			add_action('admin_menu', array($this, 'add_page'), 1);
			// register settings
			add_action('admin_init', array($this, 'register_settings'));

			if( get_option('vkapi_login') && get_option('vkapi_appid') && get_option('vkapi_api_secret') ) {
				// add api page
				add_action('parse_request', array($this, 'parse_request'));
				// widgets
				add_action('widgets_init', array($this, 'widgets_init'));
				// login form render
				add_action('login_form', 'Darx_Login::add_login_form', 1);
				add_action('register_form', 'Darx_Login::add_login_form', 1);
				// admin bar
				//add_action('admin_bar_menu', array($this, 'user_links'));
				// avatar
				add_filter('get_avatar', array($this, 'get_avatar'), 5, 1024);
			}
		}

	/**
	 * Добавляет пункт меню для модуля
	 * и отображает настройки модуля
	 */
	public function add_page()
		{
			add_submenu_page('darx-modules', __('Login Settings — Social API', 'vkapi'), __('Login Settings', 'vkapi'), 'manage_options', 'darx-login-settings', array(
				$this,
				'render_page'
			));
		}

	/**
	 * Регистрация настроек модуля
	 */
	public function register_settings()
		{
			// sections

			add_settings_section('darx-login-vk', // id
				'VK.com', // title
				'__return_null', // callback
				'darx-login-settings' // page
			);

			// vk

			register_setting('darx-login', 'vkapi_appid');
			add_settings_field('vkapi_appid', // id
				__('Application ID', 'vkapi'), // title
				array($this, 'render_settings_field'), // callback
				'darx-login-settings', // page
				'darx-login-vk', // section
				array(
					'label_for' => 'vkapi_appid',
					'type' => 'number',
					'descr' => 'Ваше приложение должно иметь тип "Веб-Сайт", введите его id в поле выше, <a href="https://youtu.be/2FJMksrz8Z8">пример создания приложения</a>.',
				) // args
			);

			register_setting('darx-login', 'vkapi_api_secret');
			add_settings_field('vkapi_api_secret', // id
				__('Secure key', 'vkapi'), // title
				array($this, 'render_settings_field'), // callback
				'darx-login-settings', // page
				'darx-login-vk', // section
				array(
					'label_for' => 'vkapi_api_secret',
					'type' => 'text',
					'descr' => 'Введите секретный код приложение, он находится на странице настройки приложения, под его ID',
				) // args
			);

			register_setting('darx-login', 'vkapi_login');
			add_settings_field('vkapi_login', // id
				'', // title
				array($this, 'render_settings_field'), // callback
				'darx-login-settings', // page
				'darx-login-vk', // section
				array(
					'label_for' => 'vkapi_login',
					'type' => 'checkbox',
					'descr' => '',
				) // args
			);
		}

		// todo: check hook documentation

	/**
	 * Форма авторизации через ВК
	 * @throws WVKException
	 */
	static function add_login_form()
		{
			if( $vkapi_appid = get_option('vkapi_appid') ) {

				if( !is_user_logged_in() ) {
					require_once(dirname(__FILE__) . '/libs/VKException.php');
					require_once(dirname(__FILE__) . '/libs/VK.php');

					$app_id = get_option('vkapi_appid');
					$app_secret = get_option('vkapi_api_secret');

					if( !empty($app_id) && !empty($app_secret) ) {
						$vk = new WVK_Connect($app_id, $app_secret);
						$vk->setApiVersion(WVAI_API_VERSION);
						$authorize_url = $vk->getAuthorizeUrl('email', self::$login_url_vk_full);

						echo '<style>
								#vkapi-login-button {
										width: 100%;
									    overflow: hidden;
									    text-overflow: ellipsis;
									    white-space: nowrap;
									    padding: 10px 16px 8px;
									    margin: 10px 0 15px;
									    font-size: 12.5px;
									    display: inline-block;
									    zoom: 1;
									    cursor: pointer;
									    outline: none;
									    font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica Neue,sans-serif;
									    vertical-align: top;
									    line-height: 15px;
									    text-align: center;
									    text-decoration: none;
									    background:#5181b8 url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAOCAYAAADABlfOAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDoAABSCAABFVgAADqXAAAXb9daH5AAAAEJSURBVHjatNS/K4VRHMfxQ9e9MSgpNhZ/AIPBoPwTdoPsDFyLTcpm8QfYJINFKf6FeydKKVEMWEx+DPdleW6Ob/c+t8SnnuH5fM7nXef7nPMkpL9+EnZ86xkTqRA2/dRLyoQBNLP8vB2shuJuVhrDe8j7snwpZNvtYBKtLHhCNStedoKiipvMf8V4vo3TUFzJskYX6Ebw11KYzXxY8IDBblDMhLE0UElROAvlvcJvBn8Rd9n7J6ZTJ2EKbwGwjkflaqQyYcvvVC+DVnDRA7DfYc6wXAYewVUJdLQ49AfB/8BcGXgYx92gxZp+HIbsHkO9ZryAI9wWX/katSyv4SS7PC3Mpv/4oXwNAIiJMTDi10cTAAAAAElFTkSuQmCC") 40px 9px no-repeat;
									    color: #fff;
									    border: 0;
									    border-radius: 4px;
									    box-sizing: border-box;
									    box-shadow: none !important;
								}
								#vkapi-login-button::-moz-focus-inner {
								    border: 0;
								    box-shadow: none !important;
								}

								#vkapi-login-button:hover {
								    background-color: #5b88bd;
								    text-decoration: none;
								    box-shadow: none !important;
								    border: 0;
								}
								#vkapi-login-button:active {
								    background-color: #4872a3;
								    padding-top: 11px;
								    padding-bottom: 7px
								}

							  </style>';

						echo '<div><a id="vkapi-login-button" href="' . $authorize_url . '">Войти через Вконтакте</a></div>';

						if( isset($_GET['vkapi_login_error']) ) {
							echo '<div style="border:1px solid #f51829; padding:10px;margin:10px 0;">';
							if( $_GET['vkapi_login_error'] == 'unknown_error' ) {
								echo '<span style="color:#f51829;">Произошла неизвестная ошибка во время авторизации через Вконтакте. Пожалуйста, обратитесь к администратору сайта для решения этой проблемы.</span>';
							}
							if( $_GET['vkapi_login_error'] == 'invalid_access_token' ) {
								echo '<span style="color:#f51829;">Не удачная попытка авторизации. Пожалуйста, попробуйте снова или обратитесь к администратору сайта для решения этой проблемы.</span>';
							}

							if( $_GET['vkapi_login_error'] == 'user_creating_error' ) {
								if( isset($_GET['message']) ) {
									echo '<span style="color:#f51829;">Неудалось создать пользователя. ' . esc_html($_GET['message']) . '</span>';
								} else {
									echo '<span style="color:#f51829;">Неудалось создать пользователя. Скорее всего на сайте запрещена регистрация или произошла неизвестная ошибка.</span>';
								}
							}
							echo '</div>';
						}
					}
				}
			}
		}

		/**
		 * Получить пользователя
		 * @param $id
		 *
		 * @return WP_User | false
		 */
		private function _vk_wpuser_get($id)
		{
		    $users =  get_users(array(
			    'meta_key' => 'vkapi_uid',
			    'meta_value' => $id,
			    'number' => 1,
			    'count_total' => false
		    ));
		    if(!count($users)) return false;

		    return array_shift($users);
		}

		/**
		 * Создать пользователя
		 * @param integer $id
		 *
		 * @return WP_User | false
		 */
		private function _vk_wpuser_create(array $user_info)
		{
			$userdata = array();

			$userdata['user_email'] = isset($user_info['email'])
				? $user_info['email']
				: $user_info['email'];

			//$userdata['user_login'] = 'vk_id' . $user_info['id'];

			if( !empty($userdata['user_email']) ) {
				$email_parts = explode('@', $userdata['user_email']);
				if( sizeof($email_parts) == 2 && !empty($email_parts[0]) ) {
					$userdata['user_login'] = $email_parts[0];
				}
			}
			$userdata['nickname'] = $userdata['user_login'];
			$userdata['first_name'] = isset($user_info['first_name'])
				? $user_info['first_name']
				: $user_info['first_name'];
			$userdata['last_name'] = isset($user_info['last_name'])
				? $user_info['last_name']
				: $user_info['last_name'];
			$userdata['user_pass'] = wp_generate_password();

			$userdata['display_name'] = "{$userdata['first_name']} {$userdata['last_name']}";

			$uid = wp_insert_user($userdata);
			if( is_wp_error($uid) ) {
				return $uid;
			}

			if( isset($user_info['photo_200']) ) {
				add_user_meta($uid, 'vkapi_ava', $user_info['photo_200'], false);
			}

			add_user_meta($uid, 'vkapi_uid', $user_info['id'], true);

			$user = get_user_by('id', $uid);

			return $user;
		}

	/**
	 * Отправка запроса
	 * @param $wp
	 */
	public function parse_request($wp)
		{
			if( $wp->request === self::$login_url_vk ) {
				$login_url = site_url('wp-login.php', 'login');

				require_once(dirname(__FILE__) . '/libs/VKException.php');
				require_once(dirname(__FILE__) . '/libs/VK.php');

				try {
					$vk = new WVK_Connect(get_option('vkapi_appid'), get_option('vkapi_api_secret'));
					$vk->setApiVersion(WVAI_API_VERSION);
					$request = $vk->getAccessToken(self::$login_url_vk_full);

					$user_id = (int)$request['user_id'];
					$email = null;
					if(isset($request['email']) && !empty($request['email'])){
						$email = $request['email'];
                    }

					$access_token = $request['access_token'];

					$params = array(
						'user_ids' => $user_id,
						'fields' => 'domain,first_name,last_name,photo_200',
					);

					$get_user_response = $vk->api('users.get', $params);

					$user_info = array();

					if( isset($get_user_response['response']) && !empty($get_user_response['response']) ) {
						$user_info = $get_user_response['response'][0];
					}

					$user_info['id'] = $user_id;
					$user_info['email'] = $email;
				} catch( WVKException $e ) {
					wp_redirect(add_query_arg(array('vkapi_login_error' => 'unknown_error'), $login_url));
					exit;
				}

				if( empty($access_token) ) {
					wp_redirect(add_query_arg(array('vkapi_login_error' => 'invalid_access_token'), $login_url));
					exit;
				}

				$user = $this->_vk_wpuser_get($user_id);

				if( $user === false ) {
					$user = $this->_vk_wpuser_create($user_info);
				}

				if( is_wp_error($user) ) {
					wp_redirect(add_query_arg(array(
						'vkapi_login_error' => 'user_creating_error',
						'message' => urlencode($user->get_error_message())
					), $login_url));
					exit;
				}
				wp_set_auth_cookie($user->ID, false);
				do_action('wp_login', $user->user_login, $user);

				/** @var WP_User $user */

				// code from wp-login.php with options and filters!

				if( isset($_REQUEST['redirect_to']) ) {
					$redirect_to = $requested_redirect_to = sanitize_text_field($_REQUEST['redirect_to']);
					if( get_user_option('use_ssl', $user->ID) && false !== strpos($redirect_to, 'wp-admin') ) {
						$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
					}
				} else {
					$redirect_to = admin_url();
					$requested_redirect_to = '';
				}

				$redirect_to = apply_filters('login_redirect', $redirect_to, $requested_redirect_to, $user);

				if( empty($redirect_to) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() ) {
					if( is_multisite() && !get_active_blog_for_user($user->ID) && !is_super_admin($user->ID) ) {
						$redirect_to = user_admin_url();
					} elseif( is_multisite() && !$user->has_cap('read') ) {
						$redirect_to = get_dashboard_url($user->ID);
					} elseif( !$user->has_cap('edit_posts') ) {
						$redirect_to = $user->has_cap('read')
							? admin_url('profile.php')
							: home_url();
					}
				}

				wp_safe_redirect($redirect_to);
				exit;
			}
		}

	/**
	 * Регистрация виджета
	 */
	public function widgets_init()
		{
			//register_widget('VKAPI_Login');
		}

		// todo: find action only to avatar source

	/**
	 * Получает html код аватара (<img>)
	 *
	 * @param string $avatar <img> tag for the user's avatar.
	 * @param string $id_or_email ID или email пользователя
	 * @param string $size Размер
	 * @param string $default Аватар по умолчанию
	 * @param string $alt Альтернативный текст
	 *
	 * @return string Готовый <img> тэг с аватаром
	 */
	public function get_avatar($avatar, $id_or_email, $size, $default, $alt)
		{
			if( is_numeric($id_or_email) ) {
				$id = $id_or_email;
			} elseif( is_string($id_or_email) ) {
				$user = get_user_by('email', $id_or_email);
				$id = $user->ID;
			} elseif( is_object($id_or_email) ) {
				// $id_or_email is comment object
				$id = $id_or_email->user_id;
			}

			if( isset($id) ) {
				$src = get_user_meta($id, 'vkapi_ava', true);
				if( !empty($src) ) {
					$avatar = "<img src='{$src}' alt='{$alt}' class='avatar avatar-{$size}' width='{$size}' height='{$size}' />";
				}
			}

			return $avatar;
		}
	}

	new Darx_Login();

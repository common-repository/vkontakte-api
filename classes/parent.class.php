<?php

if ( ! defined( 'DB_NAME' ) ) {
	die;
	bitch;
	die;
}

/**
 * Основной функционал модуля. Его наследуют классы модулей
 */
class Darx_Parent {

	/**
	 * @var string Адрес сервера VK API
	 */
	protected $_api_server = 'https://api.vk.com/method/';
	/**
	 * @var string Версия API
	 */
	protected $_api_version = WVAI_API_VERSION;
	/**
	 * @var string Слаг модуля (по умолчанию слаг страницы со списком модулей)
	 */
	protected $module_slug = "modules";

	/**
	 * @param string $type Тип уведомления
	 * @param string $message Текст уведомления
	 */
	private function _notice( $type, $message ) {
		$array = get_option( 'vkapi_msg' );
		if ( ! is_array( $array ) ) {
			$array = array();
		}

		$array[] = array(
			'type' => $type,
			'msg'  => $message
		);

		update_option( 'vkapi_msg', $array );
	}

	/**
	 * Вывод ошибок в уведомления
	 *
	 * @param string $type
	 * @param int $code
	 * @param string $message
	 */
	protected function _notice_error( $type, $code, $message ) {
		$s = "<b>Error.</b><br/><b>Type:</b> {$type}.<br/><b>Code:</b> {$code}.<br/><b>Message:</b> {$message}.";
		$this->_notice( 'error', $s );
	}

	/**
	 * Уведомление об успешности
	 * @param string $message
	 */
	protected function _notice_success( $message ) {
		$s = "<span class=\"dashicons dashicons-yes\"></span>{$message}";
		$this->_notice( 'updated', $s );
	}

	/**
	 * Вывод ошибок CURL в уведомления
	 * @param resource $ch
	 */
	protected function _notice_error_curl( $ch ) {
		$code    = curl_errno( $ch );
		$message = curl_error( $ch );
		if ( $code === 3 ) {
			$message .= '. URL: ' . curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		}
		$this->_notice_error( 'php_curl', $code, $message );
	}

	/**
	 * Вывод ошибок VK в уведомления
	 * @param array $error
	 */
	protected function _notice_error_vk( array $error ) {
		switch ( $error['error_msg'] ) {
			case 'Access Token out of date, please update.':
				// todo: !!add link to update access token without leave page
				$error['error_msg'] = __( 'Access Token out of date, please update.' );
				break;
			case 'Access denied: edit time expired':
				// Запись уже ранее отправлялась в группу ВК.
				// При обновлении записи на сайте была попытка обновить запись в группе, но время редактирования истекло.
				$error['error_msg'] = __( 'Access denied: edit time expired' );
				break;
			case 'User authorization failed: invalid session.':
				// Авторизация не успешна.
				// Обновите Access Token.
				$error['error_msg'] = __( 'User authorization failed: invalid session.' );
				break;
		}

		$this->_notice_error( 'api.vk.com', $error['error_code'], $error['error_msg'] );
	}

	/**
	 * Отправляет запрос на сервер API
	 *
	 * @param $url
	 * @param array $params
	 * @param bool $is_post Send GET or POST request
	 * @param array $curl_opts
	 *
	 * @return bool|string Return response on success or false on failure
	 */
	protected function _request( $url, array $params = array(), $is_post = false, $curl_opts = array() ) {
		if ( ! function_exists( 'curl_init' ) ) {
			$this->_notice_error(
				'php_curl',
				- 1,
				sprintf( __( 'Extension %s not installed', 'vkapi' ), 'php_curl' )
			);
		}

		// prevent malformed error (code 3)
		$url = str_replace( ' ', '%20', $url );

		if ( $is_post ) {
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			if ( version_compare( phpversion(), '5.5.0' ) !== - 1 ) {
				foreach ( $params as $key => $value ) {
					if ( $value[0] === '@' ) {
						$params[ $key ] = curl_file_create( mb_substr( $value, 1 ) );
					}
				}
			}
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		} else {
			$ch = curl_init( $url . '?' . http_build_query( $params ) );
		}

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 25 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Standalone' );

		if ( count( $curl_opts ) ) {
			curl_setopt_array( $ch, $curl_opts );
		}

		$result = curl_exec( $ch );

		if ( $result === false ) {
			$this->_notice_error_curl( $ch );
			if ( curl_errno( $ch ) === 35 ) {
				return $this->_request( $url, $params, $is_post, array( CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1) );
			}
		}

		curl_close( $ch );

		return $result;
	}
	/**
	 * Содержимое страницы модуля 'module_slug'
	 *
	 */
	public function render_page() {
		require_once WVAI_PLUGIN_DIR . "/templates/pages/".$this->module_slug.".php";
	}

	/**
	 * Преобразование массива с настройками в HTML
	 *
	 * @param $atts
	 */
	public function render_settings_field( $atts ) {
		$id   = $atts['label_for'];
		$type = $atts['type'];

		switch ( $type ) {
			default:
				$form_option = esc_attr( get_option( $id ) );
				echo "<input name=\"{$id}\" type=\"{$type}\" id=\"{$id}\" value=\"{$form_option}\" />";
				break;

			case 'checkbox':
				$checked = checked( '1', get_option( $id ), false );
				echo '<label>';
				echo "<input name=\"{$id}\" type=\"checkbox\" id=\"{$id}\" value=\"1\" {$checked} />\n";
				echo __( 'Activate' );
				echo '</label>';
				break;

			case 'radio':
				$checked = checked( '1', get_option( $id ), false );
				echo '<label>';
				echo "<input name=\"{$id}\" type=\"radio\" id=\"{$id}\" value=\"{$atts['value']}\" {$checked} />\n";
				echo @$atts['title'];
				echo '</label>';
				break;

			case 'select':
				$current = get_option( $id );
				echo '<label>';
				echo "<select name=\"{$id}\" id=\"{$id}\">";
				foreach ( $atts['values'] as $value => $title ) {
					$selected = selected( $value, $current, false );
					echo "<option value=\"{$value}\" {$selected}>{$title}</option>";
				}
				echo '</select>';
				echo '</label>';
				break;

			case 'post_types':
				$post_types_all       = get_post_types( array(), 'objects' );
				$post_types_ignore    = array( 'attachment', 'revision', 'nav_menu_item', 'link' );
				$post_types_crosspost = (array) get_option( $id );
				foreach ( $post_types_all as $post_type ) {
					if ( in_array( $post_type->name, $post_types_ignore, true ) ) {
						continue;
					}
					$checked = in_array( $post_type->name, $post_types_crosspost, true ) ? 'checked="checked"' : '';
					echo '<label>';
					echo "<input name=\"{$id}[]\" type=\"checkbox\" value=\"{$post_type->name}\" {$checked} />\n";
					echo $post_type->label;
					echo '&nbsp;&nbsp;';
					echo '</label><br>';
				}
				break;
		}

		if ( array_key_exists( 'descr', $atts ) ) {
			echo "<p class=\"description\">{$atts['descr']}</p>";
		}
	}

	// todo: recheck lang param in vk api, maybe get from wp options

	/**
	 * Обращение к VK API
	 *
	 * @param $method
	 * @param $params
	 * @param bool $is_post
	 *
	 * @return string JSON
	 */
	protected function _vk_call( $method, $params, $is_post = false ) {
		if ( ! array_key_exists( 'v', $params ) ) {
			$params['v'] = $this->_api_version;
		}

		$params['lang'] = 'ru';

		$url = $this->_api_server;

		if ( ! array_key_exists( 'access_token', $params ) ) {
			$url = preg_replace( '/^https:/', 'http:', $url );
		}

		$result = $this->_request( $url . $method, $params, $is_post );

		if ( $result === false ) {
			return false;
		}

		$response = json_decode( $result, true );

		if ( isset( $response['error'] ) ) {
			$this->_notice_error_vk( $response['error'] );

			return false;
		}

		return $response;
	}

	/**
	 * Получение всех изображений по IMG тэгу
	 *
	 * @param string $html Текст
	 * @param int $count Количество получаемых изображений
	 *
	 * @return array Массив адресов изображений
	 */
	protected function _get_images_from_html( $html, $count = 5 ) {
		if ( (bool) preg_match_all( '#<img[^>]+src=[\'"]([^\'"]+)[\'"]#ui', $html, $matches ) ) {
			return array_slice( $matches[1], 0, $count );
		}

		return array();
	}
}
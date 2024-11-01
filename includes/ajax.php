<?php
/**
 * Ajax requests
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 20.05.2018, Webcraftic
 * @version 1.0
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * Проверка маркера доступа
 */
function wbcr_vkapi_valid_access_token()
{
	check_ajax_referer('darx-crosspost-options');

	if( !current_user_can('manage_options') ) {
		echo json_encode(array('error' => __('You don\'t have enough capability to edit this information.', 'clearfy')));
		exit;
	}

	$access_token = isset($_REQUEST['access_token'])
		? sanitize_text_field($_REQUEST['access_token'])
		: null;

	if( empty($access_token) ) {
		wp_send_json(array('error' => 'Access required. You must pass the access token.'));
		exit;
	}

	$request = wp_remote_get('https://api.vk.com/method/account.getAppPermissions?access_token=' . $access_token . '&v=' . WVAI_API_VERSION);

	if( is_wp_error($request) ) {
		wp_send_json(array('error' => 'Error requesting api vkontakte.'));
		exit;
	}

	$request_body = wp_remote_retrieve_body($request);

	if( empty($request_body) ) {
		wp_send_json(array('error' => 'Response body is empty.'));
		exit;
	}

	$data = json_decode($request_body);

	if( !empty($data->error) ) {
		wp_send_json(array('error' => $data->error->error_msg));
		exit;
	}

	// groups 262144 + wall 8192 + photos 4 + offline 65536 = 335876
	if( $data->response !== 335876 ) {
		wp_send_json(array('error' => 'Access token does not have permissions for the plugin to work.'));
		exit;
	}

	wp_send_json(array('success' => true));
	exit;
}

add_action('wp_ajax_wbcr_vkapi_valid_access_token', 'wbcr_vkapi_valid_access_token');

/**
 * Проверка существования группы или страницы пользователя
 */
function wbcr_vkapi_valid_owner_id()
{
	check_ajax_referer('darx-crosspost-options');

	if( !current_user_can('manage_options') ) {
		wp_send_json(array('error' => __('You don\'t have enough capability to edit this information.', 'clearfy')));
		exit;
	}

	$access_token = isset($_REQUEST['access_token'])
		? sanitize_text_field($_REQUEST['access_token'])
		: null;

	$screen_name = isset($_REQUEST['screen_name'])
		? sanitize_text_field($_REQUEST['screen_name'])
		: null;

	if( empty($screen_name) || empty($access_token) ) {
		wp_send_json(array('error' => 'Access required. You must pass the access token and screen name.'));
		exit;
	}

	$request = wp_remote_get('https://api.vk.com/method/utils.resolveScreenName?screen_name=' . $screen_name . '&access_token=' . $access_token . '&v=' . WVAI_API_VERSION);

	if( is_wp_error($request) ) {
		wp_send_json(array('error' => 'Error requesting api vkontakte.'));
		exit;
	}

	$request_body = wp_remote_retrieve_body($request);

	if( empty($request_body) ) {
		wp_send_json(array('error' => 'Response body is empty.'));
		exit;
	}

	$data = json_decode($request_body, ARRAY_A);

	if( !isset($data['response']) || empty($data['response']) ) {
		if( !empty($data->error) ) {
			wp_send_json(array('error' => $data->error->error_msg));
		} else {
			wp_send_json(array('error' => 'Group or public page not found.', 'code' => 'screen_name_not_found'));
		}
		exit;
	}
	wp_send_json($data['response']);
	exit;
}

add_action('wp_ajax_wbcr_vkapi_valid_owner_id', 'wbcr_vkapi_valid_owner_id');

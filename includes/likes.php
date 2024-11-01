<?php

if( !defined('DB_NAME') ) {
    die;
    bitch;
    die;
}

/**
 * Модуль Лайки
 */
class Darx_Likes extends Darx_Parent {

	/**
	 * Darx_Likes constructor.
	 */
	public function __construct()
    {
        $this->module_slug = 'likes';
        // add sub-page
        add_action('admin_menu', array($this, 'add_page'), 1);
        // register settings
        add_action('admin_init', array($this, 'register_settings'));
        // add post meta box
        add_action('do_meta_boxes', array($this, 'add_post_meta_box'), 1);
        // save post meta
        add_filter('save_post', array($this, 'save_post'), 1, 3);
        // add likes under post content
        add_filter('the_content', array($this, 'add_buttons'), 1024);
        add_action('wp_enqueue_scripts', array($this, 'register_styles'));
    }

	/**
	 * Подключаем стили для модуля
	 */
	public function register_styles()
	{
	    wp_enqueue_style('social-button', WVAI_PLUGIN_URL . '/assets/css/social-button.css', array(), '4.0.1');
	}

	/**
	 * Добавляет пункт меню для модуля
     * и отображает настройки модуля
	 */
	public function add_page()
    {
        //module_slug обязательно должен быть назначен
        add_submenu_page('darx-modules', __('Likes Settings — Social API', 'vkapi'), __('Likes Settings', 'vkapi'), 'manage_options', 'darx-likes-settings',
            array($this,'render_page')
        );
    }

	/**
	 * Регистрация настроек модуля
	 */
	public function register_settings()
    {
        // base

        add_settings_section('darx-base', // id
            '', // title
            '__return_null', // callback
            'darx-likes-settings-base' // page
        );

        register_setting('darx-likes', 'vkapi_like');
        add_settings_field('vkapi_like', // id
            __('Show before post', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-base', // page
            'darx-base', // section
            array(
                'label_for' => 'vkapi_like',
                'type' => 'select',
                'values' => array(
                    'top' => __('Show before post', 'vkapi'),
                    'bottom' => __('Show after post', 'vkapi'),
                ),
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_align');
        add_settings_field('vkapi_align', // id
            __('Align', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-base', // page
            'darx-base', // section
            array(
                'label_for' => 'vkapi_align',
                'type' => 'select',
                'values' => array(
                    'left' => __('left', 'vkapi'),
                    'right' => __('right', 'vkapi'),
                ),
                'descr' => '',
            ) // args
        );

        // vk

        add_settings_section('darx-vk-like', // id
            __('Like button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-vk' // page
        );

        register_setting('darx-likes', 'vkapi_show_like');
        add_settings_field('vkapi_show_like', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-like', // section
            array(
                'label_for' => 'vkapi_show_like',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_like_type');
        add_settings_field('vkapi_like_type', // id
            __('Button style', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-like', // section
            array(
                'label_for' => 'vkapi_like_type',
                'type' => 'select',
                'values' => array(
                    'full' => __('Button with text counter', 'vkapi'),
                    'button' => __('Button with mini counter', 'vkapi'),
                    'mini' => __('Mini button', 'vkapi'),
                    'vertical' => __('Mini button with counter at the top', 'vkapi'),
                ),
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_like_verb');
        add_settings_field('vkapi_like_verb', // id
            __('Statement', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-like', // section
            array(
                'label_for' => 'vkapi_like_verb',
                'type' => 'select',
                'values' => array(
                    '0' => __('I like', 'vkapi'),
                    '1' => __('It\'s interesting', 'vkapi'),
                ),
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_like_cat');
        add_settings_field('vkapi_like_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-like', // section
            array(
                'label_for' => 'vkapi_like_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        add_settings_section('darx-vk-share', // id
            __('Share button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-vk' // page
        );

        register_setting('darx-likes', 'vkapi_show_share');
        add_settings_field('vkapi_show_share', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-share', // section
            array(
                'label_for' => 'vkapi_show_share',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_share_type');
        add_settings_field('vkapi_share_type', // id
            __('Button style', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-share', // section
            array(
                'label_for' => 'vkapi_share_type',
                'type' => 'select',
                'values' => array(
                    'round' => __('Button', 'vkapi'),
                    'round_nocount' => __('Button without a Counter', 'vkapi'),
                    'button' => __('Button Right Angles', 'vkapi'),
                    'button_nocount' => __('Button without a Counter Right Angles', 'vkapi'),
                    'link' => __('Link', 'vkapi'),
                    'link_noicon' => __('Link without an Icon', 'vkapi'),
                ),
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_share_text');
        add_settings_field('vkapi_share_text', // id
            __('Text on the button', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-share', // section
            array(
                'label_for' => 'vkapi_share_text',
                'type' => 'text',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'vkapi_like_cat');
        add_settings_field('vkapi_like_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-vk', // page
            'darx-vk-like', // section
            array(
                'label_for' => 'vkapi_like_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        // fb

        add_settings_section('darx-fb-like', // id
            __('Like button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-fb' // page
        );

        register_setting('darx-likes', 'fbapi_show_like');
        add_settings_field('fbapi_show_like', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-fb', // page
            'darx-fb-like', // section
            array(
                'label_for' => 'fbapi_show_like',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'fbapi_like_cat');
        add_settings_field('fbapi_like_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-fb', // page
            'darx-fb-like', // section
            array(
                'label_for' => 'fbapi_like_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        // Twitter
        add_settings_section('darx-tw-like', // id
            __('Like button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-tw' // page
        );

        register_setting('darx-likes', 'tweet_show_share');
        add_settings_field('tweet_show_share', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-tw', // page
            'darx-tw-like', // section
            array(
                'label_for' => 'tweet_show_share',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'tweet_share_cat');
        add_settings_field('tweet_share_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-tw', // page
            'darx-tw-like', // section
            array(
                'label_for' => 'tweet_share_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'tweet_account');
        add_settings_field('tweet_account', // id
            __('Twitter account', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-tw', // page
            'darx-tw-like', // section
            array(
                'label_for' => 'tweet_account',
                'type' => 'text',
                'descr' => '',
            ) // args
        );

        // mr

        add_settings_section('darx-mr-like', // id
            __('Like button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-mr' // page
        );

        register_setting('darx-likes', 'mrc_show_share');
        add_settings_field('mrc_show_share', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-mr', // page
            'darx-mr-like', // section
            array(
                'label_for' => 'mrc_show_share',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'mrc_share_cat');
        add_settings_field('mrc_share_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-mr', // page
            'darx-mr-like', // section
            array(
                'label_for' => 'mrc_share_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        // ok

        add_settings_section('darx-ok-like', // id
            __('Like button', 'vkapi'), // title
            '__return_null', // callback
            'darx-likes-settings-ok' // page
        );

        register_setting('darx-likes', 'ok_show_share');
        add_settings_field('ok_show_share', // id
            __('Enable', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-ok', // page
            'darx-ok-like', // section
            array(
                'label_for' => 'ok_show_share',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );

        register_setting('darx-likes', 'ok_share_cat');
        add_settings_field('ok_share_cat', // id
            __('Show in Categories page and Home', 'vkapi'), // title
            array($this, 'render_settings_field'), // callback
            'darx-likes-settings-ok', // page
            'darx-ok-like', // section
            array(
                'label_for' => 'ok_share_cat',
                'type' => 'checkbox',
                'descr' => '',
            ) // args
        );
    }

	/**
     * Создание метабокса с параметрами
	 * @param $page
	 */
	public function add_post_meta_box($page)
    {
        add_meta_box('vkapi_meta_box_likes', 'VKapi: ' . __('Social Likes', 'vkapi'), array(
            $this,
            'render_post_meta_box_likes'
        ), $page, 'advanced');
    }

	/**
	 * Содержимое метабокса с параметрами
	 */
	public function render_post_meta_box_likes()
    {
        global $post;
        $option = get_post_meta($post->ID, 'vkapi_buttons', true);
        if( $option === '' ) {
            $option = 1;
        }

        echo '<input type="radio" name="vkapi_buttons" value="1"';
        checked($option, 1);
        echo '/>' . __('Enable', 'vkapi') . '<br />';

        echo '<input type="radio" name="vkapi_buttons" value="0"';
        checked($option, 0);
        echo '/>' . __('Disable', 'vkapi');
    }

	/**
     * Выполняется после сохранения поста. Если отключили лайки, то это записывается в мета-данные поста
	 * @param $post_id
	 */
	public function save_post($post_id)
    {
        if( isset($_REQUEST['vkapi_buttons']) && $_REQUEST['vkapi_buttons'] === '0' ) {
            update_post_meta($post_id, 'vkapi_buttons', $_REQUEST['vkapi_comments']);
        }
    }

	/**
     * Отображение кнопок Лайк
	 * @param $content
	 *
	 * @return string
	 */
	public function add_buttons($content)
    {
        global $post;
        $count = 0;
        if( !is_feed() && get_post_meta($post->ID, 'vkapi_buttons', true) !== '0' ) {
            $is_singular = is_singular();
            // vk like
            if( get_option('vkapi_show_like') ) {
                if( $is_singular || get_option('vkapi_like_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'vkapi_button_like'), 6);
                    Darx_JS::add('vk', 'https://vk.com/js/api/openapi.js');
                }
            }
            // vk share
            if( get_option('vkapi_show_share') ) {
                if( $is_singular || get_option('vkapi_share_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'vkapi_button_share'), 5);
                    Darx_JS::add('vkshare', 'https://vk.com/js/api/share.js');
                    // todo: remove script, make vanilla link
                    ?>
                    <script type="text/javascript">
                        window.stManager = {};
                        window.stManager.done = function(type) {
                            if( type === 'api/share.js' ) {
                                darx.fireEvent(document, 'vkapi_vkshare');
                            }
                        };
                    </script>
                <?php
                }
            }
            // fb like
            if( get_option('fbapi_show_like') ) {
                if( $is_singular || get_option('fbapi_like_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'fbapi_button_like'), 5);
                    $locale = get_locale();
                    $option = get_option('fbapi_appid');
                    Darx_JS::add('fb', 'https://connect.facebook.net/' . $locale . '/all.js#xfbml=1&status=1&cookie=1&version=v2.6&appId=' . $option);
                }
            }
            // tweet me
            if( get_option('tweet_show_share') ) {
                if( $is_singular || get_option('tweet_share_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'tweet_button_share'), 5);
                    Darx_JS::add('tw', 'https://platform.twitter.com/widgets.js');
                }
            }
            // mrc share
            if( get_option('mrc_show_share') ) {
                if( $is_singular || get_option('mrc_share_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'mrc_button_share'), 5);
                    Darx_JS::add('mr', 'https://connect.mail.ru/js/loader.js');
                }
            }
            // ok share
            if( get_option('ok_show_share') ) {
                if( $is_singular || get_option('ok_share_cat') ) {
                    ++$count;
                    add_action('add_social_button_action', array($this, 'ok_button_share'), 5);
                    Darx_JS::add('ok', 'https://connect.ok.ru/connect.js');
                }
            }
            // shake
            if( $count ) {
                ob_start();
                do_action('add_social_button_action');
                $align = get_option('vkapi_align');
                $echo = "<!--noindex--><div style='clear:both;'><ul class='nostyle' style='float:{$align}'>"
                        .ob_get_clean().
                        '</ul></div><br style="clear:both;"><!--/noindex-->';

                if( get_option('vkapi_like') ) {
                    if( get_option('vkapi_like') == "bottom" )
                        $content .= $echo; //bottom
                    else
                        $content = $echo . $content; //top
                }
            }
        }

        return $content;
    }

    // todo: check image size in api docs

	/**
	 * Поставить лайк
	 */
	public function vkapi_button_like()
    {
        global $post;
        $div_id = "vkapi_like_{$post->ID}_" . mt_rand();

        echo "<li><div id='{$div_id}'></div></li>";
        $type = get_option('vkapi_like_type');
        $verb = get_option('vkapi_like_verb');
        $vkapi_title = addcslashes(do_shortcode($post->post_title), '\'');
        $vkapi_url = get_permalink();
        $vkapi_text = str_replace(array("\r\n", "\n", "\r"), ' <br />', do_shortcode($post->post_content));
        $temp = get_the_post_thumbnail($post->ID, array(600, 268));
        $vkapi_image = $this->_get_images_from_html($temp, 1) or $this->_get_images_from_html($vkapi_text, 1);
        $vkapi_image = !empty($vkapi_image)
            ? $vkapi_image[0]
            : '';
        $vkapi_text = strip_tags($vkapi_text);
        $vkapi_text = addcslashes($vkapi_text, '\'');
        $vkapi_descr = $vkapi_text = mb_substr($vkapi_text, 0, 139);
        $appID = get_option('vkapi_appid');
        echo "<script type=\"text/javascript\">";
        echo <<<JS
            (function(){
                darx.addEvent(document, 'vk', function(){
                    VK.init({ apiId: {$appID} });
                    VK.Widgets.Like('{$div_id}', {
                        width: 1,
                        height: 20,
                        type: '{$type}',
                        verb: '{$verb}',
                        pageTitle: '{$vkapi_title}',
                        pageDescription: '{$vkapi_descr}',
                        pageUrl: '{$vkapi_url}',
                        pageImage: '{$vkapi_image}',
                        text: '{$vkapi_text}'
                    }, {$post->ID});
                });
            })();
JS;
	    echo "</script>";
    }

    // todo: check image size in api docs

	/**
	 * Поделиться кнопка
	 */
	public function vkapi_button_share()
    {
        global $post;
        $post_id = $post->ID;
        $div_id = "vkapi_share_{$post_id}_" . mt_rand();
        echo "<li><div class='vkapishare' id='$div_id'></div></li>";
        $vkapi_type = get_option('vkapi_share_type');
        $vkapi_title = addcslashes(do_shortcode($post->post_title), '\'');
        $vkapi_url = get_permalink();
        $vkapi_descr = str_replace(array("\r\n", "\n", "\r"), ' <br />', do_shortcode($post->post_content));
        $temp = get_the_post_thumbnail($post->ID, array(600, 268));
        $vkapi_image = $this->_get_images_from_html($temp, 1) or $this->_get_images_from_html($vkapi_descr, 1);
        $vkapi_image = !empty($vkapi_image)
            ? $vkapi_image[0]
            : '';
        $vkapi_descr = strip_tags($vkapi_descr);
        $vkapi_descr = addcslashes($vkapi_descr, '\'');
        $vkapi_descr = mb_substr($vkapi_descr, 0, 139);
        $vkapi_text = get_option('vkapi_share_text');
        $vkapi_text = addcslashes($vkapi_text, '\'');
        echo "
        <script type=\"text/javascript\">
            (function(){
                darx.addEvent(document, 'vkapi_vkshare', function () {
                    document.getElementById('{$div_id}').innerHTML = VK.Share.button(
                        {
                            url: '{$vkapi_url}',
                            title: '{$vkapi_title}',
                            description: '{$vkapi_descr}',
                            image: '{$vkapi_image}'
                        },
                        {
                            type: '{$vkapi_type}',
                            text: '{$vkapi_text}'
                        }
                    );
                });
            })();
        </script>";
    }

    // todo: refactor

	/**
	 *
	 */
	public function fbapi_button_like()
    {
        $url = preg_replace('/^https/', 'http', get_permalink());
        echo "<li><div
                class='fb-like'
                data-href='{$url}'
                data-send='false'
                data-layout='button_count'
                data-width='100'
                data-show-faces='true'></div></li>";
    }

    // todo: refactor

	/**
	 *
	 */
	public function tweet_button_share()
    {
        global $post;
        $url = get_permalink();
        $who = get_option('tweet_account');
        $title = addcslashes(do_shortcode($post->post_title), '\'');
        echo "<li><div><a
                style='border:none'
                rel='nofollow'
                href='https://twitter.com/share'
                class='twitter-share-button'
                data-url='{$url}'
                data-text='{$title}'
                data-via='{$who}'
                data-dnt='true'
                data-count='none'></a></div></li>";
    }

    // todo: refactor

	/**
	 *
	 */
	public function mrc_button_share()
    {
        $url = rawurlencode(get_permalink());
        echo "<li><div style=\"max-width:65px\"><a target=\"_blank\"
                class=\"mrc__plugin_uber_like_button\"
                style='display: none'
                href=\"{$url}\"
                data-mrc-config=\"{'nt':'1','cm':'1','sz':'20','st':'1','tp':'mm'}\">Нравится</a></div></li>";
    }

    // todo: refactor

	/**
	 *
	 */
	public function ok_button_share()
    {
        static $i = 0;
        ++$i;
        $url = rawurlencode(get_permalink());
        $id = 'okapi_share_' . $i;
        echo "<li><div id=\"{$id}\"><script type=\"text/javascript\">
                (function(){
                    darx.addEvent(document, 'ok', function () {
                        setTimeout(function () {
                            OK.CONNECT.insertShareWidget(
                                \"{$id}\",
                                \"{$url}\",
                                \"{width:145,height:30,st:'oval',sz:20,ck:1}\"
                            );
                        }, 0);
                    });
                })();
            </script></div></li>";
    }
}

new Darx_Likes();
<?php
/*
Plugin Name: WH Tweaks
Plugin URI: https://webheadcoder.com/wh-tweaks
Description: Common functionality WordPress core should have but maybe shouldn't.
Version: 1.0.2
Author: Webhead LLC
*/


define( 'WHT_VERSION', '1.0.2' );
define( 'WHT_PLUGIN', __FILE__ );
define( 'WHT_OPTIONS_NAME', 'wht_options' );
define( 'WHT_OPTIONS_PAGE_ID', 'wht-options' );
define( 'WHT_SIDEBAR_OPTIONS_PREFIX', 'wht_sidebar-' );

define( 'WHT_COMMA_PLACEHOLDER', '|:|' );

require_once( plugin_dir_path( WHT_PLUGIN ) . 'blocks/blocks.php' );
require_once( plugin_dir_path( WHT_PLUGIN ) . 'functions.php' );
require_once( plugin_dir_path( WHT_PLUGIN ) . 'shortcodes.php' );
require_once( plugin_dir_path( WHT_PLUGIN ) . 'options-page.php' );

/**
 * Add Settings link to plugins
 */
function wht_settings_link($links, $file) {
    static $this_plugin;
    if (!$this_plugin) $this_plugin = plugin_basename( WHT_PLUGIN );
    if ($file == $this_plugin){
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=' . WHT_OPTIONS_PAGE_ID . ' ' ) . '">'.__( 'Settings' ).'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
 }
 add_filter('plugin_action_links', 'wht_settings_link', 10, 2 );

/**
 * Return the url to this plugin.
 */
function wht_url() {
    return plugins_url( '', WHT_PLUGIN );
}

/**
 * Setup more hook-basd options a little earlier than normal.
 */
function wht_plugins_loaded() {

    //disable rest api endpoints
    if ( wht_option( 'disable_rest', false ) && !is_user_logged_in() ) {
        remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 );
    }

}
add_action( 'plugins_loaded', 'wht_plugins_loaded' );

/**
 * Set up all the hooks based on options.
 */
function wht_init() {
    global $wp_version;
    $overrides = apply_filters( 'wht_activated_overrides', array() );
    //Don't output the WordPress version.
    if ( wht_option( 'wp_version', true ) ) {
        add_filter( 'the_generator','__return_false' );
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'script_loader_src', 'wht_obscure_wp_version_strings', 1000 );
        add_filter( 'style_loader_src', 'wht_obscure_wp_version_strings', 1000 );
    }



    //Say something, but not exactly what
    if ( wht_option( 'login_errors', true ) ) {
        add_filter( 'wp_login_errors', 'wht_login_errors');
        add_action( 'lost_password', 'wht_lost_password' );
        add_action( 'lostpassword_post', 'wht_lostpassword_post' );
    }

    // set return-path
    if ( wht_option( 'wp_mail_return_path', true ) ) {
        add_filter( 'phpmailer_init', 'wht_phpmailer_init' );
    }

    if ( wht_option( 'shortcodes', true ) ) {
        //current blog date and year
        add_shortcode( 'year' , 'wht_sc_year' );
        add_shortcode( 'date' , 'wht_sc_date' );
    }

    if ( wht_option( 'widget_doshortcode', true ) ) {
        add_filter( 'widget_text', 'do_shortcode' );
    }

    if ( wht_option( 'taxonomy_highlight', true ) )  {
        add_action( 'admin_enqueue_scripts', 'wht_admin_taxonomy_highlight' );   
    }

    if ( wht_option( 'blocks_editor_styles', true ) )  {
        add_action( 'admin_enqueue_scripts', 'wht_admin_blocks_editor_styles' );   
    }

    if ( wht_option( 'blocks_container_block', true ) )  {
        add_action( 'enqueue_block_editor_assets', 'wht_enqueue_block_editor_assets' );
    }

    if ( wht_option( 'ping', true ) )  {
        add_action( 'pre_ping', 'wht_no_self_ping' );
    }


    //login updates
    if ( wht_option( 'login_link_home', true ) )  {
        if ( version_compare( $wp_version, '5.2', '>=' ) ) {
            add_filter( 'login_headertext', 'wht_login_headertext' );
        }
        else {
            // deprecated in 5.2.
            add_filter( 'login_headertitle', 'wht_login_headertext' );
        }
        add_filter( 'login_headerurl', 'wht_login_headerurl' );
    }
    add_action( 'login_enqueue_scripts', 'wht_custom_login' );

    //remove emoji support
    if ( wht_option( 'remove_emojis', true ) )  {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
    }

    // show links in excerpt
    if ( wht_option( 'excerpt_show_links', true ) ) {
        remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
        add_filter( 'get_the_excerpt', 'wht_wp_trim_excerpt' );
    }

    // show private parents in dropdowns
    if ( wht_option( 'private_parents', true ) ) {
        add_filter( 'page_attributes_dropdown_pages_args', 'wht_show_private_parents' );
        add_filter( 'quick_edit_dropdown_pages_args', 'wht_show_private_parents' );
    }

    // allow commas in taxonomies
    if ( wht_option( 'comma_taxonomy', false ) ) {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_admin() ) {
            add_action( 'check_ajax_referer', 'wht_taxonomy_replace_commas', 10, 2 );   
        }

        add_filter( 'get_the_taxonomies', 'wht_comma_taxonomies_filter' );
        add_filter( 'get_terms',          'wht_comma_taxonomies_filter' );
        add_filter( 'get_the_terms',      'wht_comma_taxonomies_filter' );

        add_filter( 'edit_tag_form_pre',  'wht_comma_taxonomy_filter' );
        add_filter( 'get_term',           'wht_comma_taxonomy_filter' );
        add_filter( 'get_post_tag',       'wht_comma_taxonomy_filter' );

        add_filter( 'term_name',          'wht_comma_insert' );
    }

    // show main site's sidebars on all sites for Multisite
    if ( wht_option( 'mu_main_sidebar', false ) ) {
        if ( is_main_site() ) {
            add_action( 'customize_save_after', 'wht_sidebar_on_all_sites' );
            add_action( 'wp_ajax_save-widget', 'wht_sidebars_ajax', 1 );
            add_action( 'wp_ajax_widgets-order', 'wht_sidebars_ajax', 1 );
            add_action( 'sidebar_admin_setup', 'wht_sidebars_sidebar_admin_setup' );
        }
    }
    if ( wht_option( 'mu_get_main_sidebar', !is_main_site() ) ) {
        add_filter( 'dynamic_sidebar_has_widgets', 'wht_dynamic_sidebar', 10, 2 );
    }

    if ( wht_option( 'remove_author_page', false ) ) {
        add_action( 'template_redirect', 'wht_remove_author_page' );
    }
    if ( wht_option( 'prevent_enum', false ) ) {
        if (!is_admin()) {
            // default URL format
            if ( preg_match( '/author=([0-9]*)/i', ($_SERVER['QUERY_STRING'] ?? '') ) ) {
                wht_403();
            }
            add_filter( 'redirect_canonical', 'wht_check_author_enum', 10, 2 );
        }
    }

    if ( wht_option( 'video_container', false ) ) {
        add_filter( 'embed_oembed_html', 'wht_embed_html', 10, 3 );
        add_filter( 'video_embed_html', 'wht_embed_html' ); // Jetpack
        add_filter( 'wp_footer', 'wht_css_public' );
    }

    if ( wht_option( 'remove_flush_all', false ) ) {
        remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
    }

}
add_action( 'init', 'wht_init' );

/**
 * Get option
 */
function wht_option( $name, $default = false ) {
    $options = get_option( WHT_OPTIONS_NAME );
    if ( !empty( $options ) && isset( $options[$name] ) ) {
        $ret = $options[$name];
    }
    else {
        $ret = $default;
    }
    return $ret;
}

if ( wht_option( 'notifications', true ) ) :
/**
 * Disables user notifications for when a new user signs up or changes their password.
 */
if ( !function_exists('wp_new_user_notification') ) :
function wp_new_user_notification($user_id, $plaintext_pass = '') {}
endif;
if ( !function_exists('wp_password_change_notification') ) :
function wp_password_change_notification( $user ) {}
endif;

endif;


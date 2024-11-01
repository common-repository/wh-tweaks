<?php
/*********************************
 * Options page
 *********************************/

// don't load directly
if ( !defined('ABSPATH') )
    die('-1');

/**
 *  Add menu page
 */
function wht_options_add_page() {
    $wht_hook = add_options_page( 'WH Tweaks', // Page title
                      'WH Tweaks', // Label in sub-menu
                      'manage_options', // capability
                      WHT_OPTIONS_PAGE_ID, // page identifier 
                      'wht_options_do_page' ); // call back function name
                      
    add_action( "admin_enqueue_scripts-" . $wht_hook, 'wht_admin_scripts' );
}
add_action('admin_menu', 'wht_options_add_page');

/**
 * Init plugin options to white list our options
 */
function wht_options_init() {
    register_setting( 'wht_options_options', WHT_OPTIONS_NAME, 'wht_options_validate' );
}
add_action('admin_init', 'wht_options_init' );


function wht_admin_notice() {
    $options = get_option( WHT_OPTIONS_NAME );
    if ( empty( $options ) ) {
        echo '<div class="update-nag" id="messages"><p>WH Tweaks is activated, but not fully configured.  You are only getting the default fixes.  <a href="">Configure WH Tweaks to get the most out of the plugin!</a></p></div>';
    }
}

/**
 * Draw the menu page itself
 */
function wht_options_do_page() {
    if ( !current_user_can( 'manage_options' ) ) { 
     wp_die( __( 'You do not have sufficient permissions to access this page.' ) ); 
    } 
    $logo_id = wht_option( 'login_logo', '' );
    $logo2x_url = $logo_url = '';
    if ( !empty( $logo_id ) ) {
        $logo_url = wp_get_attachment_url( $logo_id );
    }
    $logo2x_id = wht_option( 'login_logo2x', '' );
    if ( !empty( $logo2x_id ) ) {
        $logo2x_url = wp_get_attachment_url( $logo2x_id );
    }
    if ( empty( $logo_url ) ) {
        $logo_url = admin_url() . '/images/wordpress-logo.svg';
    }
    if ( empty( $logo2x_url ) ) {
        $logo2x_url = admin_url() . '/images/wordpress-logo.svg';
    }
    $current_user = wp_get_current_user();
    $user_url = home_url() . '/author/' . $current_user->user_login;
    $user_id_url = home_url() . '/?author=' . $current_user->ID;
    ?>
    <div class="wrap">
            <div class="wht-header">
                <div class="wht-description">
                <h2>WH Tweaks</h2>
                    <p class="intro">
                        Activate/Deactivate common fixes and customizations to WordPress.
                    </p>
                </div>
                <div class="wht-donate">
                    <p>If this plugin has helped you, please consider giving back.</p>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="XB7VGHH5FTEJU">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>
            </div>
            <div class="clear"></div>
            <hr>
            <form method="post" action="options.php">
                <?php settings_fields( 'wht_options_options' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Security <a href="https://webheadcoder.com/wh-tweaks/#security" class="help" title="more info on Security options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <fieldset>
                                <p>
                                    <label for="wht_activate_wp_version">
                                        <input type="checkbox" id="wht_activate_wp_version" name="<?php echo WHT_OPTIONS_NAME;?>[wp_version]" value="1" <?php checked( 1, wht_option( 'wp_version', true ) ) ?> /> Scramble the WordPress version on public side.</label> 
                                </p>
                                <p>
                                    <label for="wht_activate_login_errors">
                                        <input type="checkbox" id="wht_activate_login_errors" name="<?php echo WHT_OPTIONS_NAME;?>[login_errors]" value="1" <?php checked( 1, wht_option( 'login_errors', true ) ) ?>/>
                                        Obscure the login errors on the Login and Lost Password forms.</label>
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Additions <a href="https://webheadcoder.com/wh-tweaks/#additions" class="help" title="more info on Additions options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <fieldset>
                                <p>
                                    <label for="wht_activate_wp_mail_return_path">
                                        <input type="checkbox" id="wht_activate_wp_mail_return_path" name="<?php echo WHT_OPTIONS_NAME;?>[wp_mail_return_path]" value="1" <?php checked( 1, wht_option( 'wp_mail_return_path', true ) ) ?>/> Set the Return-Path if not already set when sending mail through WordPress.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_shortcodes">
                                        <input type="checkbox" id="wht_activate_shortcodes" name="<?php echo WHT_OPTIONS_NAME;?>[shortcodes]" value="1" <?php checked( 1, wht_option( 'shortcodes', true ) ) ?> /> Add shortcodes to output the current [year] and [date].</label>
                                </p>
                                <p>
                                    <label for="wht_activate_taxonomy_highlight">
                                        <input type="checkbox" id="wht_activate_taxonomy_highlight" name="<?php echo WHT_OPTIONS_NAME;?>[taxonomy_highlight]" value="1" <?php checked( 1, wht_option( 'taxonomy_highlight', true ) ) ?>/> Make child categories show with a light gray background.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_excerpt_show_links">
                                        <input type="checkbox" id="wht_activate_excerpt_show_links" name="<?php echo WHT_OPTIONS_NAME;?>[excerpt_show_links]" value="1" <?php checked( 1, wht_option( 'excerpt_show_links', true ) ) ?>/> Let excerpts show links.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_private_parents">
                                        <input type="checkbox" id="wht_activate_private_parents" name="<?php echo WHT_OPTIONS_NAME;?>[private_parents]" value="1" <?php checked( 1, wht_option( 'private_parents', true ) ) ?>/> Show private pages in Parent dropdowns.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_comma_taxonomy">
                                        <input type="checkbox" id="wht_activate_comma_taxonomy" name="<?php echo WHT_OPTIONS_NAME;?>[comma_taxonomy]" value="1" <?php checked( 1, wht_option( 'comma_taxonomy', false ) ) ?>/> Allow commas in category terms (Caution: This plugin is required if you wish to continue to show commas in category terms).</label>
                                </p>
                                <p>
                                    <label for="wht_activate_video_container">
                                        <input type="checkbox" id="wht_activate_video_container" name="<?php echo WHT_OPTIONS_NAME;?>[video_container]" value="1" <?php checked( 1, wht_option( 'video_container', false ) ) ?>/> Puts a container around embeded videos allowing them to be responsive.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_blocks_editor_styles">
                                        <input type="checkbox" id="wht_activate_blocks_editor_styles" name="<?php echo WHT_OPTIONS_NAME;?>[blocks_editor_styles]" value="1" <?php checked( 1, wht_option( 'blocks_editor_styles', true ) ) ?>/> Make the blocks editor a little nicer easier to read.</label>
                                </p>

                                <?php if ( wht_option( 'blocks_container_block', false ) ) : ?>
                                <p>
                                    <label for="wht_activate_blocks_container_block">
                                        <input type="checkbox" id="wht_activate_blocks_container_block" name="<?php echo WHT_OPTIONS_NAME;?>[blocks_container_block]" value="1" <?php checked( 1, wht_option( 'blocks_container_block', true ) ) ?>/> Add a generic container block to the editor. (This option will disappear after it's been unchecked.  The editor now has its own container block)</label>
                                </p>
                                <?php endif; ?>

                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Subtractions <a href="https://webheadcoder.com/wh-tweaks/#subtractions" class="help" title="more info on Subtractions options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <fieldset>
                                <p>
                                    <label for="wht_activate_notifications">
                                        <input type="checkbox" id="wht_activate_notifications" name="<?php echo WHT_OPTIONS_NAME;?>[notifications]" value="1" <?php checked( 1, wht_option( 'notifications', true ) ) ?> /> Don't send the admin email notifications when a new user signs up or a user changes their password.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_disable_rest">
                                        <input type="checkbox" id="wht_activate_disable_rest" name="<?php echo WHT_OPTIONS_NAME;?>[disable_rest]" value="1" <?php checked( 1, wht_option( 'disable_rest', false ) ) ?> /> Disable all the default WordPress REST endpoints.</label>
                                </p>
                                <p>
                                    <label for="wht_activate_remove_author_page">
                                        <input type="checkbox" id="wht_activate_remove_author_page" name="<?php echo WHT_OPTIONS_NAME;?>[remove_author_page]" value="1" <?php checked( 1, wht_option( 'remove_author_page', false ) ) ?> /> Remove the author pages.  For example: <a href="<?php echo esc_url( $user_url ); ?>" target="_blank"><?php echo $user_url; ?></a></label>
                                </p>
                                <p>
                                    <label for="wht_activate_prevent_enum">
                                        <input type="checkbox" id="wht_activate_prevent_enum" name="<?php echo WHT_OPTIONS_NAME;?>[prevent_enum]" value="1" <?php checked( 1, wht_option( 'prevent_enum', false ) ) ?> /> Prevent author enumeration.  For example: <a href="<?php echo esc_url( $user_id_url ); ?>" target="_blank"><?php echo $user_id_url; ?></a></label>
                                </p>
                                <p>
                                    <label for="wht_activate_remove_flush_all">
                                        <input type="checkbox" id="wht_activate_remove_flush_all" name="<?php echo WHT_OPTIONS_NAME;?>[remove_flush_all]" value="1" <?php checked( 1, wht_option( 'remove_flush_all', false ) ) ?> /> Remove 'wp_ob_end_flush_all' from WordPress.<br><i>Only activate this if you have one of these issues: <a href="https://core.trac.wordpress.org/ticket/18525">#18525</a> or <a href="https://core.trac.wordpress.org/ticket/22430">#2243</a></i>.</label>
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Optimize <a href="https://webheadcoder.com/wh-tweaks/#optimize" class="help" title="more info on Optimize options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <fieldset>
                                <p>
                                    <label for="wht_activate_remove_emojis">
                                        <input type="checkbox" id="wht_activate_remove_emojis" name="<?php echo WHT_OPTIONS_NAME;?>[remove_emojis]" value="1" <?php checked( 1, wht_option( 'remove_emojis', false ) ) ?> />Don't load emojis styles and scripts.</label>
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <?php if ( is_multisite() ) : ?>
                    <tr>
                        <th scope="row">Multisite <a href="https://webheadcoder.com/wh-tweaks/#multisite" class="help" title="more info on Multisite options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <fieldset>
                                <?php if ( is_main_site() ) : ?>
                                <p>
                                    <label for="wht_activate_mu_main_sidebar">
                                        <input type="checkbox" id="wht_activate_mu_main_sidebar" name="<?php echo WHT_OPTIONS_NAME;?>[mu_main_sidebar]" value="1" <?php checked( 1, wht_option( 'mu_main_sidebar', false ) ) ?> />Show the main site's static sidebar on all sites by default.  CAUTION:  <a href="https://webheadcoder.com/wh-tweaks/#multisite" target="_blank">See notes for more info.</a></label>
                                </p>
                                <?php else : ?>
                                <p>
                                    <label for="wht_activate_mu_get_main_sidebar">
                                        <input type="checkbox" id="wht_activate_mu_get_main_sidebar" name="<?php echo WHT_OPTIONS_NAME;?>[mu_get_main_sidebar]" value="1" <?php checked( 1, wht_option( 'mu_get_main_sidebar', true ) ) ?> />Show the main site's sidebar if sidebar is empty.</label>
                                </p>
                                <?php endif; ?>
                            </fieldset>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th scope="row">Personalize Login Form<a href="https://webheadcoder.com/wh-tweaks/#login" class="help" title="more info on Login options" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></th>
                        <td>
                            <table class="form-table login-form-table">

                                <tr>
                                    <td colspan="2">
                                        <fieldset>
                                            <p>
                                                <label for="wht_activate_login_link_home">
                                                    <input type="checkbox" id="wht_activate_login_link_home" name="<?php echo WHT_OPTIONS_NAME;?>[login_link_home]" value="1" <?php checked( 1, wht_option( 'login_link_home', true ) ) ?> />Link logo on login page to <a href="<?php echo home_url(); ?>">your home page</a></label>
                                            </p>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Logo on login page
                                    </th>
                                    <td>
                                        <div class="uploader">
                                            <input type="hidden" id="login_logo" name="<?php echo WHT_OPTIONS_NAME;?>[login_logo]" value="<?php echo $logo_id ; ?>" />
                                            <a href = "#" 
                                               class = "media-uploader-link" 
                                               id = "login_logo_button"
                                               data-uploader_title = "Choose a Login Logo"
                                               data-uploader_button_text = "Select"
                                            >
                                            <div id="login_logo_thumb">
                                                <img class="logo_thumb" src="<?php echo $logo_url; ?>" data-wp_logo="<?php echo admin_url() ?>/images/wordpress-logo.svg" alt="Sample Logo" />
                                            </div>
                                            <span id="login_logo_choose" class="choose-link">Change Image</span>
                                            </a> <span id="login_logo_remove" class="remove-link"> | <a href="#">Reset Image</a></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Logo on login page for retina screens 
                                    </th>
                                    <td>
                                        <div class="uploader">
                                            <input type="hidden" id="login_logo2x" name="<?php echo WHT_OPTIONS_NAME;?>[login_logo2x]" value="<?php echo $logo2x_id; ?>" />
                                            <a href = "#" 
                                               class = "media-uploader-link" 
                                               id = "login_logo2x_button"
                                               data-uploader_title = "Choose a Login Logo"
                                               data-uploader_button_text = "Select"
                                            >
                                            <div id="login_logo2x_thumb">
                                                <img class="logo_thumb" src="<?php echo $logo2x_url; ?>" data-wp_logo="<?php echo admin_url() ?>/images/wordpress-logo.svg" alt="Sample Logo" />
                                            </div>
                                            <span id="login_logo2x_choose" class="choose-link">Change Image</span>
                                            </a> <span id="login_logo2x_remove" class="remove-link"> | <a href="#">Reset Image</a></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Links and button color:
                                    </th>
                                    <td><input type="text" name="<?php echo WHT_OPTIONS_NAME;?>[login_primary]" value="<?php echo wht_option( 'login_primary', '#0085ba' ); ?>" class="wp-color-picker" data-default-color="#0085ba" /></td>
                                </tr>
                                <tr>
                                    <th>
                                        Border and shadow colors:
                                    </th>
                                    <td><input type="text" name="<?php echo WHT_OPTIONS_NAME;?>[login_secondary]" value="<?php echo wht_option( 'login_secondary', '#0085ba' ); ?>" class="wp-color-picker" data-default-color="#0085ba" /></td>
                                </tr>
                                <tr>
                                    <th>
                                        Logo Width:
                                    </th>
                                    <td><input type="text" name="<?php echo WHT_OPTIONS_NAME;?>[login_width]" class="medium-text" value="<?php echo wht_option( 'login_width', '320px' ); ?>" data-default-value="320px" /></td>
                                </tr>
                                <tr>
                                    <th>
                                        Logo Height:
                                    </th>
                                    <td><input type="text" name="<?php echo WHT_OPTIONS_NAME;?>[login_height]" class="medium-text" value="<?php echo wht_option( 'login_height', '84px' ); ?>" data-default-value="84px" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="#" id="reset-login">Reset All Login Settings</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save All') ?>" />
                </p>
        </form>
    </div>
    <?php 
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function wht_options_validate($input) {
    global $wp_settings_errors;
    $setting_names = array( 
        'wp_version', 
        'login_errors', 
        'wp_mail_return_path',
        'shortcodes', 
        'taxonomy_highlight',
        'excerpt_show_links',
        'notifications',
        'disable_rest',
        'remove_emojis',
        'login_link_home',
        'private_parents',
        'mu_main_sidebar',
        'mu_get_main_sidebar',
        'remove_author_page',
        'prevent_enum',
        'blocks_editor_styles',
        'blocks_container_block'
    );
    foreach( $setting_names as $name ) {
        if ( !isset( $input[$name] ) ) {
            $input[$name] = 0;
        }   
    }

    //if mu_main_sidebar changed, store sidebars.
    if ( is_main_site() && wht_option( 'mu_main_sidebar', 0 ) != $input['mu_main_sidebar'] ) {
        if ( $input['mu_main_sidebar'] ) {
            wht_sidebar_on_all_sites();
        }
        else {
            wht_clear_sidebars();
        }
    }

    return $input;
}


/**
 * Enqueue Scripts
 */
function wht_admin_scripts() {
    do_action ('wht_admin_scripts');
}

/**
 * Enqueue scripts for the admin side.
 */
function wht_enqueue_scripts($hook) {
    if( 'settings_page_wht-options' != $hook )
        return;

    // Include in admin_enqueue_scripts action hook
    wp_enqueue_media();
    //wp_enqueue_script( 'custom-header' );

    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_script( 'wht-options',
        plugins_url( '/js/options.js', WHT_PLUGIN ),
        array( 'jquery', 'wp-color-picker' ),
        WHT_VERSION,
        true );
    wp_enqueue_style( 'wht-options',
        plugins_url( '/css/options.css', WHT_PLUGIN ),
        array( ),
        WHT_VERSION );
    //wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
}
add_action( 'admin_enqueue_scripts', 'wht_enqueue_scripts' );


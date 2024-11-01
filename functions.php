<?php

require_once( 'features/comma_taxonomy.php' );
require_once( 'features/mu_sidebars.php' );

/**
 * Say something was incorrect, but not exaclty what.
 */
function wht_login_errors( $errors ) {
    if ( $errors->get_error_code() == 'invalid_username' || $errors->get_error_code() == 'invalid_password' ) {
        $errors->remove( $errors->get_error_code() );
        $errors->add( 'invalid_username', __( '<strong>ERROR</strong>: Invalid username or incorrect password.', 'wht' ) .
            ' <a href="' . wp_lostpassword_url() . '">' .
            __( 'Lost your password?' ) .
            '</a>');
    }
    return $errors;
}

/**
 * Add actions to change the lost password form.
 */
function wht_lost_password() {
    add_filter( 'gettext', 'wht_login_password_title', 10, 2 );
}

/**
 * Force email to be entered.
 */
function wht_lostpassword_post( $errors ) {
    if ( !strpos( $_POST['user_login'], '@' ) || ( $errors && $errors->get_error_code() == 'invalidcombo' ) ) {
        $errors->remove( 'invalidcombo' );
        $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
    }
}

/**
 * Change Retrieve Password to only Email Address.
 */
function wht_login_password_title( $translated_text, $untranslated_text ) {
    switch ( $untranslated_text ) {
        case 'Username or Email:':
            $translated_text = __( 'Email&nbsp;Address:' );
            break;
        case 'Please enter your username or email address. You will receive a link to create a new password via email.':
            $translated_text = __('Please enter your email address. You will receive a link to create a new password via email.', 'wp-fixes' );
            break;
        case 'Invalid username or email':
            $translated_text = __('Please enter your email address. You will receive a link to create a new password via email.', 'wp-fixes' );
            break;
        default:    
            break;
    }
    return $translated_text;
}

/**
 * Instead of removing the wp version, keep this cache-busting feature and 
 * return a combo of wp and theme version to retain cache busting.
 */
function wht_obscure_wp_version_strings( $src ) {
    global $wp_version;

    $parts = explode( '?', $src );
    if ( count( $parts ) > 1 && $parts[1] === 'ver=' . $wp_version ) {
        $theme  = wp_get_theme();
        $version = $theme->get( 'Version' );
        $wp_parts = explode( '.', $wp_version );
        array_splice( $wp_parts, 2, 0, $version );
        $new_src = $parts[0] . '?ver=99' . implode('.', $wp_parts);
        return apply_filters( 'wht_obscure_wp_version_strings', $new_src, $src );
    }
    else {
        return $src;
    }
}

/**
 * Title is blog name
 */
function wht_login_headertext() {
    return get_bloginfo('name');
}

/**
 * URL is blog home
 */
function wht_login_headerurl() {
    return home_url();
}

/**
 * Include the stylesheet for the post page.
 */
function wht_admin_taxonomy_highlight( $hook ) {
    if ( $hook == 'post.php' ) {
        wp_enqueue_style( 'wht_admin_post_css', wht_url() . '/css/admin-post.css' );
    }
}

function wht_admin_blocks_editor_styles( $hook ) {
    if ( $hook == 'post.php' ) {
        wp_enqueue_style( 'wht_admin_editor_css', wht_url() . '/css/admin-editor.css' );
    }
}

/**
 * Expand the logo area
 */
function wht_custom_login() { 
    $options = array(
        /*
        the advanced key can be added like below using filters.

        'advanced'          => array(
            'border_color'     => '#006799',
            'box_shadow'       => '0 1px 0 #006799',
            'text_shadow'      => '0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799'            
        ),
        */
        'logo_id'           => wht_option( 'login_logo', '' ),
        'logo2x_id'         => wht_option( 'login_logo2x', '' ),
        'primary_color'     => wht_option( 'login_primary', '#0085ba' ),
        'secondary_color'   => wht_option( 'login_secondary', '#006799' ),
        'background_width'  => wht_option( 'login_width', '320px' ),
        'background_height' => wht_option( 'login_height', '84px' )
    );
    extract( apply_filters( 'wht_custom_login', $options ) );
    $border_color = isset( $advanced['border_color'] ) ? $advanced['border_color'] : $secondary_color;
    $box_shadow = isset( $advanced['box_shadow'] ) ? $advanced['box_shadow'] : sprintf( '0 1px 0 %s', $secondary_color );
    $text_shadow = isset( $advanced['text_shadow'] ) ? $advanced['text_shadow'] : sprintf( '0 -1px 1px %1$s, 1px 0 1px %1$s, 0 1px 1px %1$s, -1px 0 1px %1$s ', $secondary_color );
    $padding_bottom = isset( $advanced['padding_bottom'] ) ? $advanced['padding_bottom'] : 0;
    if ( !empty( $logo_id ) ) {
        $logo_url = wp_get_attachment_url( $logo_id );
    }
    if ( !empty( $logo2x_id ) ) {
        $logo2x_url = wp_get_attachment_url( $logo2x_id );
    }
?>
    <style>
        body.login div#login h1 a {
            <?php if ( !empty( $logo_url ) ) : ?>
            background-size: <?php echo $background_width . ' ' . $background_height; ?>;
            background-image: url(<?php echo $logo_url; ?>);
            width: 100%;
            height: <?php echo $background_height; ?>;
            <?php endif; ?>
            padding-bottom: <?php echo $padding_bottom; ?>;
        }
        body.login #nav a,
        body.login #backtoblog a {
            color:<?php echo $primary_color;?> !important;
        }
        body.login.wp-core-ui .button-primary {
            background-color: <?php echo $primary_color; ?>;
            border-color: <?php echo $border_color; ?>;
            box-shadow: <?php echo $box_shadow; ?>;
            text-shadow: <?php echo $text_shadow; ?>;
        }
        body input[type="text"]:focus, 
        body input[type="search"]:focus, 
        body input[type="radio"]:focus, 
        body input[type="tel"]:focus, 
        body input[type="time"]:focus, 
        body input[type="url"]:focus,
        body input[type="week"]:focus,
        body input[type="password"]:focus,
        body input[type="checkbox"]:focus,
        body input[type="color"]:focus,
        body input[type="date"]:focus,
        body input[type="datetime"]:focus,
        body input[type="datetime-local"]:focus,
        body input[type="email"]:focus,
        body input[type="month"]:focus,
        body input[type="number"]:focus,
        body select:focus,
        body textarea:focus {
            border-color: <?php echo $secondary_color; ?>;
            box-shadow: 0 0 2px rgba(<?php echo implode(',', wht_hex2rgb( $secondary_color ) );?>, 0.8);
        }
        <?php if ( !empty( $logo2x_url ) ) : ?>
        @media (-webkit-min-device-pixel-ratio: 2), 
            (min-resolution: 192dpi) { 
                body.login div#login h1 a {
                    background-size: <?php echo $background_width . ' ' . $background_height; ?>;
                    background-image: url(<?php echo $logo2x_url;?>);
                    height: <?php echo $background_height; ?>;
                }
        }
        <?php endif; ?>
    </style>
<?php }

/**
 * No self pinging.
 */
function wht_no_self_ping( $links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, $home ) ) {
            unset($links[$l]);
        }
    }

}

/**
 * change hex to rgb.
 * http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 */
function wht_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/**
 * Allow links.
 * Thanks to: 
 *  https://lewayotte.com/2010/09/22/allowing-hyperlinks-in-your-wordpress-excerpts/
 */
function wht_wp_trim_excerpt($text) {
    $raw_excerpt = $text;
    if ( '' == $text && in_the_loop() ) {
        $text = get_the_content('');

        $text = strip_shortcodes( $text );

        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $text = strip_tags($text, '<a>');
        $excerpt_length = apply_filters('excerpt_length', 55);

        $excerpt_more = apply_filters('excerpt_more', ' [...]');
        $words = preg_split( '/(<a.*?a>)|\n|\r|\t|\s/', $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE );
        if ( count( $words ) > $excerpt_length ) {
            array_pop( $words );
            $text = implode( ' ', $words );
            $text = $text . $excerpt_more;
        } else {
            $text = implode( ' ', $words );
        }
    }
    return apply_filters( 'wht_wp_trim_excerpt', $text, $raw_excerpt );

}

/**
 * Set the Return-path when sending via wp_mail.  This helps to get rid of the "via [webhostdomain.com]" 
 * when the mail server is not on the webhost
 */
function wht_phpmailer_init( $phpmailer ) {
    if ( filter_var( $phpmailer->Sender, FILTER_VALIDATE_EMAIL ) !== true ) {
        $phpmailer->Sender = $phpmailer->From;
    }
}


/**
 * Show private parents.
 *
 * @param   array  $args  Original get_pages() $args.
 *
 * @return  array  $args  Args set to also include posts with pending, draft, and private status.
 */
function wht_show_private_parents( $args ) {
    if ( !empty( $args['post_status'] ) ) {
        $args['post_status'] .= ',private';   
    }
    else {
        $args['post_status'] = 'publish,private';
    }
    return $args;
}

/**
 * 404 the author page so no authors can be shown.
 */
function wht_remove_author_page() {
    if ( is_author() ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
    }
}

/**
 * Check the permalink format for author.
 */
function wht_check_author_enum($redirect, $request) {
    // permalink URL format
    if ( preg_match( '/\?author=([0-9]*)(\/*)/i', $request ) ) {
        wht_403();
    }
    else return $redirect;
}

/**
 * Show a 403 Forbidden error.
 */
function wht_403() {
    header('HTTP/1.0 403 Forbidden');
    die;
}

/**
 * Add responsive container for embeds.
 */
function wht_embed_html( $html ) {
    return '<div class="video-container">' . $html . '</div>';
}

/**
 * Add styling for the video container.
 */
function wht_css_public() { ?>
<style>.video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; } .video-container iframe, .video-container object, .video-container embed, .video-container video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style>
<?php
}


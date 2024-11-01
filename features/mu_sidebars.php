<?php

/**
 * If no widgets found in a sidebar, show the main site's sidebar.
 */
function wht_dynamic_sidebar( $did_one, $index ) {
    if ( !$did_one && !is_admin() ) {
        $html = get_site_option( WHT_SIDEBAR_OPTIONS_PREFIX . $index );
        echo $html;
        return !empty( $html );
    }
    return $did_one;
}

/**
 * Generate and (re)save all sidebars.
 */
function wht_sidebar_on_all_sites( $not_used = null ) {
    global $wp_registered_sidebars;

    if ( ! is_main_site() )
        return $not_used;

    foreach( $wp_registered_sidebars as $sidebar ) {
        $sidebar_id = $sidebar['id'];
        ob_start();
        if ( ! dynamic_sidebar( $sidebar_id ) ) : endif;
        $sidebar_html = ob_get_clean();
        update_site_option( WHT_SIDEBAR_OPTIONS_PREFIX . $sidebar_id, $sidebar_html );
    }
    return $not_used;
}

/**
 * Clear the stored sidebars
 */
function wht_clear_sidebars() {
    global $wp_registered_sidebars;

    foreach( $wp_registered_sidebars as $sidebar ) {
        delete_site_option( WHT_SIDEBAR_OPTIONS_PREFIX . $sidebar['id'] );
    }
}

/**
 * Setup a filter to be run before wp dies.
 */
function wht_sidebars_ajax() {
    add_filter( 'wp_die_ajax_handler', 'wht_sidebar_on_all_sites' );
}

/**
 * We're saving/deleting a widget without js/ajax on wp-admin/widgets.php. 
 */
function wht_sidebars_sidebar_admin_setup() {
    // Only trigger when POST-ing.
    if ( !empty( $_POST ) && ( isset( $_POST['savewidget'] ) || isset( $_POST['removewidget'] ) ) ) {
        wht_sidebar_on_all_sites();
    }
}


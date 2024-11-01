<?php

/**
 * Show the current date.
 */
function wht_sc_date( $atts ) {
    $atts = shortcode_atts( array(
        'format'              => '',
    ), $atts, 'date' );
    return date( $atts['format'], current_time( 'timestamp' ) );
}
/**
 * Show the current year.
 */
function wht_sc_year( $atts ) {
    $atts = shortcode_atts( array(
        'format'              => 'Y',
    ), $atts, 'year' );
    return wht_sc_date( $atts );
}

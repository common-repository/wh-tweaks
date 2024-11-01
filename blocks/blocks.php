<?php

/**
 * Init the plugin
 */
function wht_enqueue_block_editor_assets() {
    if ( !function_exists( 'register_block_type' ) ) {
        return;
    }
    wp_register_script(
        'wht-script',
        plugins_url( 'js/blocks.js', WHT_PLUGIN ),
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        WHT_VERSION,
        true
    );
    wp_register_style( 'wht-editor-style', 
        plugins_url( 'css/editor.css', WHT_PLUGIN ), 
        array( 'wp-edit-blocks' ),
        WHT_VERSION );

    wp_register_style( 'wht-style', 
        plugins_url( 'css/style.css', WHT_PLUGIN ), 
        array(),
        WHT_VERSION );

    register_block_type( 'wh-tweaks/container-block', array(
        'editor_script' => 'wht-script',
        'editor_style'  => 'wht-editor-style',
        'style'         => 'wht-style'
    ) );
}

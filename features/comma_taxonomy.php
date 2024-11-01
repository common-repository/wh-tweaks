<?php

/**
 * Replace commas with placeholder when adding via the quick add (ajax)
 * Temp fix for https://core.trac.wordpress.org/ticket/14691
 */
function wht_taxonomy_replace_commas( $action, $result ) {
    if ( substr( $action, 0, 4 ) == 'add-' ) {
        $slug = substr( $action, 4 );
        if ( !empty( $slug ) ) {
            $taxonomy = get_taxonomy( $slug );   
            if ( $taxonomy ) {
                $post_name = 'new'.$taxonomy->name;
                if ( !empty( $_POST[$post_name] ) && strpos( $_POST[$post_name], ',' ) ) {
                    $new_post_value = '';
                    $stripped_post = stripslashes( $_POST[$post_name] );
                    $new_terms = str_getcsv( trim( $stripped_post ) );
                    foreach ( $new_terms as $new_term ) { 
                        if ( !empty( $new_post_value ) ) {
                            $new_post_value .= ',';
                        }
                        $new_term = preg_replace( '/^([\"])(.*)\\1$/', '\\2', $new_term );
                        $new_post_value .= str_replace( ',', WHT_COMMA_PLACEHOLDER, $new_term );
                    }
                    $_POST[$post_name] = addslashes( $new_post_value );
                }
                else if ( !empty( $_POST[$post_name] ) ) {
                    //to be consistent, remove closing quotes.
                    $stripped_post = stripslashes( $_POST[$post_name] );
                    $_POST[$post_name] = addslashes( preg_replace( '/^([\"])(.*)\\1$/', '\\2', $stripped_post ) );
                }
            }
        }
    }
}

/**
 * Replace WHT_COMMA_PLACEHOLDER with a comma.
 */
function wht_comma_insert( $name ){
    return str_replace( WHT_COMMA_PLACEHOLDER, ', ', $name );
}

/**
 * Replace comma in taxonomy.
 */
function wht_comma_taxonomy_filter( $tag ) {
    $tag_arr_new = $tag;
    if ( $tag && isset( $tag->name ) && strpos( $tag->name, WHT_COMMA_PLACEHOLDER ) ){
        $tag->name = wht_comma_insert( $tag->name );
    }
    return $tag;    
}

/**
 * Replace comma in array of taxonomies.
 */
function wht_comma_taxonomies_filter( $tags_arr ){
    for( $i = 0; $i < count( $tags_arr); $i++ ) {
        $tags_arr[$i] = wht_comma_taxonomy_filter( $tags_arr[$i] );
    }
    return $tags_arr;
}
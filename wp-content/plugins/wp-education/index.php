<?php
/*
Plugin Name: Education
Description: Education functionalities.
Author: Budhan Chandra Kundu
Version: 1.0
*/
function wpa_137268( $args ){
    $args['labels'] = array(
        'name'                  => __( 'Courses' ),
        'singular_name'         => __( 'Course' ),
        'menu_name'             => _x( 'Courses', 'Admin menu name' ),
        'add_new'               => __( 'Add Course' ),
        'add_new_item'          => __( 'Add New Course' ),
        'edit'                  => __( 'Edit' ),
        'edit_item'             => __( 'Edit Course' ),
        'new_item'              => __( 'New Course' ),
        'view'                  => __( 'View Course' ),
        'view_item'             => __( 'View Course' ),
        'search_items'          => __( 'Search Courses' ),
        'not_found'             => __( 'No Courses found' ),
        'not_found_in_trash'    => __( 'No Courses found in trash' ),
        'parent'                => __( 'Parent Course' )
    );
    return $args;
}
add_filter( 'woocommerce_register_post_type_product', 'wpa_137268' );

if ( ! function_exists( 'unregister_post_type' ) ) :
function unregister_post_type() {
    global $wp_post_types;
    if ( isset( $wp_post_types[ 'courses' ] ) ) {
        unset( $wp_post_types[ 'courses' ] );
        return true;
    }
    return false;
}
endif;

add_action('init', 'unregister_post_type');



    function additionalScript(){
        echo '<script language="javascript">
    jQuery(function($) {
        
        $(this).bind("contextmenu", function(e) {
            e.preventDefault();
        });
    }); 
    </script>';
    }
    add_action('wp_head', 'additionalScript');
    
    function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

function my_columns_filter( $columns ) {
    //if( current_user_can('vendor') ) {
        unset($columns['product_type']);
        unset($columns['featured']);
        unset($columns['is_in_stock']);

        return $columns;
    //}
}
add_filter( 'manage_edit-product_columns', 'my_columns_filter', 10, 1 );

<?php
    if( !defined( 'ABSPATH' ) ) die();

/**
 * MODULE: Abandoned Carts functionality
 */

define( 'GB_HCC_AC_TABLE', 'gb_wc_hcc_ac_table' );
define( 'GB_HCC_AC_ZHU', 'gb_hcc_ac_zapier_hook_url' );
define( 'GB_HCC_AC_ZA', 'gb_hcc_ac_zapier_activate' );
define( 'GB_HCC_AC_TM', 1200 );

/**
 * Register abandoned carts page
 *
 * @since 1.5.0
 */

function gb_hcc_add_ac_page()
{
    $options = get_option( 'gb_wc_hcc_options' );

    if( empty( $options['disable_ac'] ) )
    {
        add_submenu_page( 'edit.php?post_type=handsome-checkout', GB_HCC_NAME, __( 'Abandoned Carts', 'gb-wc-hcc' ), 'manage_options', GB_HCC_ID . '-abandoned-carts', 'gb_hcc_do_ac_page' );
    }
}
add_action( 'admin_menu', 'gb_hcc_add_ac_page' );

/**
 * Display abandoned carts page
 *
 * @since 1.5.0
 */

function gb_hcc_do_ac_page()
{
    if( !current_user_can( 'manage_options' ) )
    {
        wp_die( esc_html__( 'Oops, you can\'t access this page.', 'gb-wc-hcc' ) );
    }

    include_once plugin_dir_path( __FILE__ ) . 'admin/gb-wc-hcc-abandoned-carts.php';
}

/**
 * Process the AJAX abandoned carts export request
 *
 * @since 1.5.0
 */

function gb_hcc_ac_export_process()
{
    $gb_hcc_ac_table = json_decode( get_option( GB_HCC_AC_TABLE ), true );

    if( $gb_hcc_ac_table )
    {
        $file_name = md5( time() ) . '.csv';
        $upload_dir = wp_upload_dir();

        $fp = fopen( "{$upload_dir['path']}/{$file_name}", 'w' );

        fputcsv( $fp, ['Email', 'Products', 'Date'] );

        foreach( $gb_hcc_ac_table as $email => $value )
        {
            $user_cart = '';

            foreach( $value['cart'] as $item )
            {
                $user_cart .= "{$item['product_id']}-{$item['qty']};";
            }

            fputcsv( $fp, [ $email, $user_cart, $value['created_at'] ] );
        }

        fclose( $fp );

        echo "{$upload_dir['url']}/{$file_name}";
    }

    wp_die();
}
add_action( 'wp_ajax_hcc_ac_export', 'gb_hcc_ac_export_process', 99 );
add_action( 'wp_ajax_nopriv_hcc_ac_export', 'gb_hcc_ac_export_process', 999 );

/** 
 * Generating hash of cart to access multiple abandoned carts by one email
 *
 * @param $cart Array of product_ids and their qtys
 * @return string
 * @since 1.5.0
 */

function gb_hcc_generate_cart_hash( $cart )
{
    $hash = md5( json_encode( $cart ) );

    return $hash;
}

/**
 * Process the AJAX abandoned carts catch email request
 *
 * @since 1.5.0
 */

function gb_hcc_ac_add_email_process()
{
    $email = $_POST['email'];

    $cart = array();

    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
    {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if( 
            $_product && 
            $_product->exists() && 
            $cart_item['quantity'] > 0 && 
            apply_filters( 'woocommerce_cart_item_visible', TRUE, $cart_item, $cart_item_key ) 
        )
        {
            $cart[] = [
                'product_id'    => $_product->get_id(),
                'qty'           => $cart_item['quantity'],
                'price'         => $cart_item['price'],
                'image_id'      => $cart_item['image_id'],
            ];
        }
    }

    $gb_hcc_ac_table = json_decode( get_option( GB_HCC_AC_TABLE ), TRUE );

    $gb_hcc_ac_table[ $email ][ gb_hcc_generate_cart_hash( $cart ) ] = [
        'cart'              => $cart,
        'created_at'        => date( 'Y-m-g H:i:s', time() ),
        'grand_total'       => WC()->cart->get_cart_contents_total(),
        'discount_total'    => WC()->cart->get_discount_total(),
        'tax_total'         => WC()->cart->get_total_tax(),
    ];

    update_option( GB_HCC_AC_TABLE, json_encode( $gb_hcc_ac_table ) );

    // schedule the appropriate event

    $zapier_activate = get_option( GB_HCC_AC_ZA );

    if( $zapier_activate )
    {
        wp_schedule_single_event( time() + GB_HCC_AC_TM, 'hcc_ac_send_to_zapier', array( $email, $cart ) );
    }

    wp_die();
}
add_action( 'wp_ajax_hcc_ac_add_email', 'gb_hcc_ac_add_email_process', 99 );
add_action( 'wp_ajax_nopriv_hcc_ac_add_email', 'gb_hcc_ac_add_email_process', 999 );

/**
 * Process the AJAX abandoned carts clear table request
 *
 * @since 1.5.0
 */

function gb_hcc_ac_clear_table_process()
{
    update_option( GB_HCC_AC_TABLE, '' );

    wp_die();
}
add_action( 'wp_ajax_hcc_ac_clear_table', 'gb_hcc_ac_clear_table_process', 99 );
add_action( 'wp_ajax_nopriv_hcc_ac_clear_table', 'gb_hcc_ac_clear_table_process', 999 );

/**
 * Catch the email from the checkout form
 *
 * @since 1.5.0
 */

function gb_hcc_ac_add_email()
{
    $options = get_option( 'gb_wc_hcc_options' );

    if( empty( $options['disable_ac'] ) ):

    ?>
    <script type="text/javascript">
    jQuery( document ).ready( function() {

        jQuery( "#billing_email" ).blur( function ( e ) {

            var $this = jQuery( this );
            var email = $this.val();

            if ( email !== '' && validateEmail( email ) ) {

                var data = {
                    action: "hcc_ac_add_email",
                    email: email
                };

                jQuery.post( woocommerce_params.ajax_url, data, function( response ) {
                    //
                });

                return false;
            }
        });
    });

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        return re.test(email);
    }
    </script>
    <?php

    endif;
}
add_action( 'woocommerce_checkout_after_customer_details', 'gb_hcc_ac_add_email' );

/**
 * Send abandoned carts data to Zapier
 *
 * @param $email string
 * @param $cart WC_Cart
 * @since 1.5.0
 */

function gb_hcc_ac_send_to_zapier( $email = '', $cart = array() )
{
    $zapier_activate = get_option( GB_HCC_AC_ZA );
    $zapier_hook_url = get_option( GB_HCC_AC_ZHU );

    $hash = gb_hcc_generate_cart_hash( $cart );
    
    if( $zapier_activate && !empty( $zapier_hook_url ) )
    {
        $gb_hcc_ac_table = json_decode( get_option( GB_HCC_AC_TABLE ), TRUE );

        if( isset( $gb_hcc_ac_table[ $email ][ $hash ] ) )
        {
            $args = array(
                'method'    => 'POST',
                'body'      => json_encode( array(
                    'email'     => $email,
                    'cart'      => $gb_hcc_ac_table[ $email ][ $hash ]['cart'],
                    'date'      => $gb_hcc_ac_table[ $email ][ $hash ]['created_at']
                ) ),
                'headers'   => array(
                    'Content-Type'  => 'application/json',
                ),
            );

            wp_remote_post( $zapier_hook_url, $args );
        }
    }
}
add_action( 'hcc_ac_send_to_zapier', 'gb_hcc_ac_send_to_zapier', 10, 2 );

/**
 * Remove the abandoned cart entry if the order 
 * status switched to processing or completed
 *
 * @param $order_id
 */

function gb_hcc_ac_completed_order( $order_id )
{
    $order = wc_get_order( $order_id );

    $order_email = $order->get_billing_email();
    
    $order_items = $order->get_items();
    $order_details = array();
    
    foreach( $order_items as $item )
    {
        $item_details = $item->get_data();
        $order_details[] = [
            'product_id' => $item_details[ 'product_id' ],
            'qty' => $item_details[ 'quantity' ]
        ];
    }

    $hash = gb_hcc_generate_cart_hash( $order_details );
    
    $gb_hcc_ac_table = json_decode( get_option( GB_HCC_AC_TABLE ), TRUE );

    if( isset( $gb_hcc_ac_table[ $order_email ][ $hash ] ) )
    {
        unset( $gb_hcc_ac_table[ $order_email ][ $hash ] );

        update_option( GB_HCC_AC_TABLE, json_encode( $gb_hcc_ac_table ) );
    }
}

add_action( 'woocommerce_order_status_processing', 'gb_hcc_ac_completed_order', 10, 1);
add_action( 'woocommerce_order_status_completed', 'gb_hcc_ac_completed_order', 10, 1);

?>
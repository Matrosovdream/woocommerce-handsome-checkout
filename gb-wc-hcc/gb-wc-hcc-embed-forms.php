<?php
    if( !defined( 'ABSPATH' ) ) die();

/**
 * MODULE: support for HC Embed Forms
 */

/** Checks $content for embed forms shortcodes, if finds any - returns it's name
 * @param $content String HTML content of the post
 * @return bool|string FALSE when nothing found or content is empty. String with name if was found
 * */

function gb_hcc_form_get_embed_shortcode( $content )
{
	if( $content && has_shortcode( $content, 'wc_hcc_checkout' ) )
	{
		return 'wc_hcc_checkout';
	}
	else
    {
        return FALSE;
    }
}

/**
 * Parse the shortcode post id from the post_content
 *
 * @since 1.4.0
 */


function gb_hcc_form_get_shortcode_id( $content, $shortcode = 'wc_hcc_checkout' )
{
    if( empty( $content ) )
    {
        return FALSE;
    }

    $matches = $matches_numbers = array();

    $shortcode_form_id = FALSE;

    preg_match_all( '/' . get_shortcode_regex( array( $shortcode ) ) . '/s', $content, $matches );

    if( !empty( $matches ) )
    {
        preg_match_all( '!\d+!', json_encode( $matches[0] ), $matches_numbers );

        $shortcode_form_id = $matches_numbers[0][0];
    }

    return $shortcode_form_id;
}

/**
 * Add the products attached to the Embed Forms to cart
 *
 * @param NULL $ref_array grabs all default parameters from do_action_ref_array, 
 *  otherwise $force can be overwritten with wrong value
 * @param bool $force add products to cart even if DOING_AJAX is true
 *
 * @since 1.4.0
 */

function gb_hcc_form_preadd_product_to_cart( $ref_array = NULL, $force = FALSE )
{
    global $post;

    // in case of readding product via ajax request from cached page

    if(
        empty( $post ) &&
        !empty( $_SERVER['HTTP_REFERER'] ) &&
        !empty( $_POST['action'] ) &&
        $_POST['action'] == 'hcc_form_validate_nonce'
    )
    {
        $_SERVER['HTTP_REFERER'] = esc_url_raw( $_SERVER['HTTP_REFERER'] );

        $uri = explode( '?', $_SERVER['HTTP_REFERER'] );
        $uri = $uri[0];

        $slug = basename( untrailingslashit( $uri ) );

        // get post object that might contain shortcode

        $post = gb_hcc_get_post_by_slug( $slug, 'any' );
    }

    // regular process

    if( !empty( $post ) && $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX && $force === FALSE )
        {
            return;
        }
        else
        {
            $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

            if( !empty( $post_id ) )
            {
                gb_hcc_form_add_product_to_cart_by_id( $post_id );
            }
        }
    }
}
add_action( 'wp', 'gb_hcc_form_preadd_product_to_cart', 1 );

/**
 * Service function that adds the items preselected for 
 * the given form to WooCommerce cart
 *
 * @param int $form_id 
 *
 * @since 1.5.4
 */

function gb_hcc_form_add_product_to_cart_by_id( $form_id = 0 )
{
    if( empty( $form_id ) )
    {
        return;
    }

    $product_id = get_post_meta( $form_id, 'hcc_form_product_id', TRUE );

    // $redirect = FALSE;

    try
    {
        if( WC() !== NULL && empty( WC()->cart ) )
        {
            return;
        }

        WC()->cart->empty_cart();

        $product_id = explode( ',', $product_id );

        foreach( $product_id as $id )
        {
            $_product = wc_get_product( $id );

            if( !empty( $_product ) )
            {
                if( $_product->is_type( 'variable' ) )
                {
                    $default_attributes = $_product->get_default_attributes();

                    if( !empty( $default_attributes ) )
                    {
                        foreach( $_product->get_children() as $variation_id )
                        {
                            $single_variation = new WC_Product_Variation( $variation_id );

                            if( $default_attributes == $single_variation->get_attributes() )
                            {
                                WC()->cart->add_to_cart( $variation_id, $quantity = 1 );
                            }
                        }
                    }
                    else
                    {
                        WC()->cart->add_to_cart( $_product->get_children()[0], $quantity = 1 );
                    }
                }
                else
                {
                    WC()->cart->add_to_cart( $id, $quantity = 1 );
                }
            }
        }

        // if( $redirect )
        // {
        //     $url = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        //     wp_redirect( $url );
        // }
    }
    catch( Exception $e )
    {

    }
}

/**
 * Replace the $post content with Embed Form shortcode
 * to make all the features work properly
 *
 * @since 1.5.4
 */

function gb_hcc_form_iframe_replace_content()
{
    if( 
        !empty( $_GET['hcc_form_loader'] ) &&  
        !empty( $_GET['hcc_form_id'] )
    )
    {
        global $post;

        $form_id = intval( $_GET['hcc_form_id'] );

        $post->post_content = '[wc_hcc_checkout id="' . $form_id . '"]';

        show_admin_bar( FALSE );
    }
}
add_action( 'wp', 'gb_hcc_form_iframe_replace_content', 0 );

/**
 * Fix missing placeholders for the Embed Forms
 *
 * @since 1.4.0
 */

function gb_hcc_form_shortcode_fix_missing_placeholders( $fields )
{
    global $post;

    if( !empty( $post ) && gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $fields['billing']['billing_first_name']['placeholder']   = __( 'First name', 'woocommerce' );
        $fields['billing']['billing_last_name']['placeholder']    = __( 'Last name', 'woocommerce' );
        $fields['billing']['billing_company']['placeholder']      = __( 'Company name', 'woocommerce' );
        $fields['billing']['billing_phone']['placeholder']        = __( 'Phone', 'woocommerce' );
        $fields['billing']['billing_email']['placeholder']        = __( 'Email address', 'woocommerce' );

        $fields['billing']['billing_state']['placeholder']    = __( 'State / County', 'woocommerce' );
        $fields['billing']['billing_city']['placeholder']     = __( 'Town / City', 'woocommerce' );
        $fields['billing']['billing_postcode']['placeholder'] = __( 'Postcode / ZIP', 'woocommerce' );

        $fields['shipping']['shipping_first_name']['placeholder'] = __( 'First name', 'woocommerce' );
        $fields['shipping']['shipping_last_name']['placeholder']  = __( 'Last name', 'woocommerce' );
        $fields['shipping']['shipping_company']['placeholder']    = __( 'Company name', 'woocommerce' );

        $fields['shipping']['shipping_state']['placeholder']    = __( 'State / County', 'woocommerce' );
        $fields['shipping']['shipping_city']['placeholder']     = __( 'Town / City', 'woocommerce' );
        $fields['shipping']['shipping_postcode']['placeholder'] = __( 'Postcode / ZIP', 'woocommerce' );
    }

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'gb_hcc_form_shortcode_fix_missing_placeholders' );

/**
 * Declare custom post status for the Embed Forms
 *
 * @since 1.4.0
 */

function gb_hcc_form_shortcode_custom_post_status()
{
    register_post_status( 'shortcode', array(
        'label'                     => __( 'Embed Form', 'gb-wc-hcc' ),
        'exclude_from_search'       => FALSE,
        'public'                    => FALSE,
        'internal'                  => FALSE,
        'protected'                 => FALSE,
        'private'                   => FALSE,
        'publicly_queryable'        => TRUE,
        'show_in_admin_status_list' => TRUE,
        'show_in_admin_all_list'    => FALSE,
        'label_count'               => _n_noop( 'Embed Form <span class="count">(%s)</span>', 'Embed Forms <span class="count">(%s)</span>' )
    ) );
}
add_action( 'admin_init', 'gb_hcc_form_shortcode_custom_post_status' );

/**
 * Force "shortcode" post status on post update event
 *
 * @since 1.4.0
 */

function gb_hcc_form_change_post_status()
{
    global $post;

    if( $post->post_status == 'shortcode' )
    {
        $current_post = get_post( $post->ID, 'ARRAY_A' );

        $current_post['post_status'] = 'shortcode';

        wp_update_post( $current_post );
    }
}
add_action( 'shortcode_to_publish', 'gb_hcc_form_change_post_status' );

/**
 * Force "shortcode" post status on post create event
 *
 * @since 1.4.0
 */

function gb_hcc_form_post_save( $data, $post_data )
{
    if( !isset( $_POST['post_type'] ) || $_POST['post_type'] != 'handsome-checkout' )
    {
        return $data;
    }

    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return $data;
    }

    if( !empty( $post_data['gb-hcc-select-mode'] ) && $post_data['gb-hcc-select-mode'] == 'shortcode' )
    {
        $data['post_status'] = 'shortcode';
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'gb_hcc_form_post_save', 20, 2 );

/**
 * Register the Embed Forms shortcode
 *
 * @since 1.4.0
 */

function gb_hcc_form_shortcode_display( $atts, $content = null )
{
    global $woocommerce;

    $result = '';

    $atts = shortcode_atts( array(
        'id' => 0,
        'iframe' => 0,
    ), $atts );

    $post_id = (int) $atts['id'];

    if(
        !defined( 'DOING_AJAX' ) && 
        is_admin() 
        // isset( $_GET['tve'] ) || 		// Thrive Architect
        // isset( $_GET['fl_builder'] ) 	// Beaver Builder
    )
    {
        return '<div style="text-align: center; padding: 20px; background: transparent; font-size: 16px; font-weight: bold; border: 1px solid #000;">' . __( 'Please, publish this page or save it as a draft to view Handsome Checkout Form. It will only show up on the front-end, outside the visual editor.', 'gb-wc-hcc' ) . '</div>';
    }

    if( empty( $post_id ) )
    {
        return '<div style="text-align: center; padding: 20px; background: transparent; font-size: 16px; font-weight: bold; border: 1px solid #000;">' . __( 'No selected products for this form. Please, check the form settings.', 'gb-wc-hcc' ) . '</div>';
    }

    /* 
    + update iframe height on
    +- screen resize 
    +- checkout update
    +- step switch
    - show the loader icon before 
    it has loaded
    - load the iframe when it is visible
    - or reload by trigger
    */

    if( !empty( $atts['iframe'] ) )
    {
        ob_start();
?>
<script type="text/javascript">
if( typeof gb_hcc_resize_iframe !== "function" )
{ 
    function gb_hcc_resize_iframe(iframe) {
        iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
    }

    function gb_hcc_get_iframe_height(iframe){
        function getComputedBodyStyle(prop) {
            function getPixelValue(value) {
                var PIXEL = /^\d+(px)?$/i;

                if (PIXEL.test(value)) {
                    return parseInt(value,base);
                }

                var 
                    style = el.style.left,
                    runtimeStyle = el.runtimeStyle.left;

                el.runtimeStyle.left = el.currentStyle.left;
                el.style.left = value || 0;
                value = el.style.pixelLeft;
                el.style.left = style;
                el.runtimeStyle.left = runtimeStyle;

                return value;
            }

            var 
                el = iframe.contentWindow.document.body,
                retVal = 0;

            if (document.defaultView && document.defaultView.getComputedStyle) {
                retVal =  document.defaultView.getComputedStyle(el, null)[prop];
            } else {//IE8 & below
                retVal =  getPixelValue(el.currentStyle[prop]);
            } 

            return parseInt(retVal,10);
        }

        return iframe.contentWindow.document.body.offsetHeight +
            getComputedBodyStyle('marginTop') +
            getComputedBodyStyle('marginBottom');
    }
}

jQuery(document).ready(function(){

    window.hcc_form_frame = document.getElementById('hcc-embed-form-frame-<?php echo intval( $post_id ); ?>');

    jQuery(window).on( 'resize', function(){

        gb_hcc_resize_iframe( hcc_form_frame );
    });

});
</script>

<iframe src="/?hcc_form_loader=1&hcc_form_id=<?php echo intval( $post_id ); ?>" onload="gb_hcc_resize_iframe(this);" scrolling="no" style="overflow: hidden; width: 100%; min-height: 1000px;" name="hcc_form_loader" class="hcc-embed-form-frame" id="hcc-embed-form-frame-<?php echo intval( $post_id ); ?>" frameborder="0"></iframe>
<?php
        $result = ob_get_clean();

        return $result;
    }

    $form_order_details 	= get_post_meta( $post_id, 'hcc_form_order_details', TRUE );

    $form_fields_coupon 	= get_post_meta( $post_id, 'hcc_form_fields_coupon', TRUE );
    $form_fields_shipping 	= get_post_meta( $post_id, 'hcc_form_fields_shipping', TRUE );

    $form_color_background 	= get_post_meta( $post_id, 'hcc_form_color_background', TRUE );
    $form_color_links 		= get_post_meta( $post_id, 'hcc_form_color_links', TRUE );
    $form_color_text 		= get_post_meta( $post_id, 'hcc_form_color_text', TRUE );
    $form_color_payment 	= get_post_meta( $post_id, 'hcc_form_color_payment', TRUE );
    $form_color_button_1 	= get_post_meta( $post_id, 'hcc_form_color_button_1', TRUE );
    $form_color_button_2 	= get_post_meta( $post_id, 'hcc_form_color_button_2', TRUE );

    $form_shortcode_fieldnames = get_post_meta( $post_id, 'hcc_form_shortcode_fieldnames', TRUE );
    $form_shortcode_steps 	= get_post_meta( $post_id, 'hcc_form_shortcode_steps', TRUE );
    
    $form_popup_mode = get_post_meta( $post_id, 'hcc_form_popup_mode', TRUE);

    // remove coupon if disabled

    if( empty( $form_fields_coupon ) )
    {
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
    }

    // generic js & css

    ob_start()

    ?>
    <script type="text/javascript">
    jQuery(document).ready( function ()
    {
        // fix cached update_order_review_nonce issue
        var init_checkout = 0;

        var nonce_updated = 0;
        
        <?php if( $form_popup_mode ) : ?>
            jQuery('.wc-hcc-embed-popup-link').click(function () {

                jQuery( '#wc-hcc-embed-modal' ).fadeIn();
                jQuery( '#wc-hcc-embed-modal-overlay' ).fadeIn();
                jQuery('body').css('overflow', 'hidden');
            });
            jQuery('#wc-hcc-embed-modal-close').click(function () {

                jQuery( '#wc-hcc-embed-modal' ).fadeOut();
                jQuery( '#wc-hcc-embed-modal-overlay' ).fadeOut();
                jQuery('body').css('overflow', 'auto');
            });
            jQuery(document).keydown(function (e) {
                if(e.keyCode === 27){
                    jQuery('#wc-hcc-embed-modal-close').trigger("click");
                }
            });
        
        <?php endif; ?>

        jQuery(document).ajaxSend( function( e, request, options ) {

            if( init_checkout == 0 && nonce_updated == 0 )
            {
                if( options.url.indexOf('wc-ajax=update_order_review') > 0 )
                {
                    request.abort();

                    jQuery( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).unblock();

                    init_checkout = 1;
                }
            }
        });

        jQuery(document).ajaxComplete( function( e, request, options ) {

            if( options.url.indexOf('wc-ajax=update_order_review') > 0 )
            {
                if( request.status == 403 )
                {
                    window.location.reload( true );
                }
            }
        });

        var data = {
            'action': 'hcc_form_validate_nonce',
            'nonce': wc_checkout_params.update_order_review_nonce,
            'rnd': Math.floor( Math.random() * 1000 ) + 100
        };

        jQuery.post( wc_checkout_params.ajax_url, data, function( response )
        {
            response = jQuery.parseJSON( response );

            if( typeof response == 'object' && typeof response.result != 'undefined' )
            {
                wc_checkout_params.update_order_review_nonce = response.result;

                nonce_updated = 1;

                jQuery( document.body ).trigger('update_checkout');
            }
        });

        // embed form

        if( jQuery( "#place_order" ).parent( ".hcc-btn-wrapper" ).length )
        {
            // do nothing
        }
        else
        {
            jQuery( "#place_order" ).wrap( '<div class="hcc-btn-wrapper"></div>' );
        }

        jQuery( ".form-row" ).each( function ()
        {
            jQuery( "#billing_city_field" ).removeClass( "form-row-wide" ).addClass( "form-row-first" );
            jQuery( "#billing_state_field" ).removeClass( "form-row-wide" ).addClass( "form-row-last" );
            jQuery( "#shipping_city_field" ).removeClass( "form-row-wide" ).addClass( "form-row-first" );
            jQuery( "#shipping_state_field" ).removeClass( "form-row-wide" ).addClass( "form-row-last" );
        });

        jQuery(document.body).bind("updated_checkout", function()
        {
            if( jQuery( "#place_order" ).parent( ".hcc-btn-wrapper" ).length )
            {
                // do nothing
            }
            else
            {
                jQuery( "#place_order" ).wrap( '<div class="hcc-btn-wrapper"></div>' );
            }
        });

        jQuery( ".woocommerce-info" ).each( function ()
        {
            if( jQuery(this).find( ".showlogin" ).text() )
            {
                jQuery(this).remove();
            }
        });
    });
    </script>
    <link rel="stylesheet" id="gb-wc-hcc-embed-forms-styles" href="<?php echo plugin_dir_url( __FILE__ ) . 'forms/assets/css/embed-forms-styles.css?ver=' . GB_HCC_VER; ?>" type="text/css" media="all">
    <style type="text/css">
    .wc-hcc-embed-edit-link {
    	text-align: center;
    }
    
    .wc-hcc-embed-popup-link {
        cursor: pointer;
    }

    .wc-hcc-embed-edit-link a,
    .wc-hcc-embed-edit-link a:visited {
    	color: #000;
    }

    .woocommerce-terms-and-conditions-wrapper p{
        color: white;
    }
    
    .form-row .validate-required {
        width: 95%;
        display: block;
    }
    
    <?php

        if( !empty( $form_fields_shipping ) )
        {

    ?>
    #ship-to-different-address {
        display: block;
    }
    <?php

        }
        else
        {

    ?>
    #ship-to-different-address {
        display: none;
    }
    <?php

        }

        if( $form_shortcode_fieldnames == 'placeholders' )
        {

    ?>
    .hcc-embed-form-wrapper .woocommerce-billing-fields__field-wrapper .form-row label,
    .hcc-embed-form-wrapper .woocommerce-shipping-fields__field-wrapper .form-row label,
    .hcc-embed-form-wrapper .woocommerce-account-fields .form-row label {
        display: none;
    }
    <?php

        }
        elseif( $form_shortcode_fieldnames == 'both' )
        {

    ?>
    .hcc-embed-form-wrapper .woocommerce-billing-fields__field-wrapper .form-row label,
    .hcc-embed-form-wrapper .woocommerce-shipping-fields__field-wrapper .form-row label,
    .hcc-embed-form-wrapper .woocommerce-account-fields .form-row label {
        display: block;
    }
    <?php

        }

        if( empty( $form_order_details ) )
        {

    ?>
    .hcc-embed-form-wrapper #order_review_heading,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table {
        display: none !important;
    }

    .hcc-embed-form-wrapper .step-content .woocommerce-shipping-fields {
        margin-bottom: 0 !important;
    }
    .hcc-embed-form-wrapper .woocommerce-shipping-fields {
        margin-bottom: 10px !important;
    }
    <?php

        }
        else
        {

    ?>
    .hcc-embed-form-wrapper #order_review_heading,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table {
        display: table !important;
    }

    .hcc-embed-form-wrapper .step-content .woocommerce-shipping-fields {
        margin-bottom: 0 !important;
    }
    .hcc-embed-form-wrapper .woocommerce-shipping-fields {
        margin-bottom: 10px !important;
    }
    <?php

        }
    ?>
    </style>
    <?php

    $result .= ob_get_clean();

    // layout templates

    if( $form_popup_mode )
    {
        $content = $content ? $content : __( 'Your Button Here', 'gb-wc-hcc' );

	    $result .= '<a id="wc-hcc-embed-popup-link" class="wc-hcc-embed-popup-link wc-hcc-embed-popup-link_'. $post_id . '">' . 
	    		html_entity_decode( trim( $content ) ) .
	    	'</a>';

	    // display edit link for admins (pop-up mode)

	    if( current_user_can( 'manage_options' ) )
	    {
	    	$result .= '<div class="wc-hcc-embed-edit-link">(<a href="' . get_edit_post_link( $post_id ) . '" target="_blank" title="' . __( 'this link is only visible to admins', 'gb-wc-hcc' ) . '">' . __( 'edit this form', 'gb-wc-hcc' ) . '</a>)</div>';
	    }

	    $result .= 
            '<div id="wc-hcc-embed-modal-overlay" class="hcc-embed-modal-overlay" style="display: none;"></div>
            <div id="wc-hcc-embed-modal" class="hcc-embed-modal-wrapper" style="display: none;">
                <div class="hcc-embed-modal-header">
                    <button id="wc-hcc-embed-modal-close" type="button" class="hcc-embed-modal-close" aria-hidden="true">&times;</button>
                </div>
                <div class="hcc-embed-modal-body">';
    }
    else
    {
    	// display edit link for admins (non pop-up mode)

    	if( current_user_can( 'manage_options' ) )
    	{
    		$result .= '<div class="wc-hcc-embed-edit-link"><a href="' . get_edit_post_link( $post_id ) . '" target="_blank" title="' . __( 'this link is only visible to admins', 'gb-wc-hcc' ) . '">' . __( 'edit this form', 'gb-wc-hcc' ) . '</a></div>';
    	}
    }

    $plugin_path = plugin_dir_path( __FILE__ );
    
    if( $form_shortcode_steps == 'one_step' )
    {
        ob_start();

        do_action( 'gb_hcc_form_before', $post_id );

        include( $plugin_path . 'forms/one-step-template.php' );

        do_action( 'gb_hcc_form_after', $post_id );

        $result .= ob_get_clean();
    }
    elseif( $form_shortcode_steps == 'two_steps' )
    {
        ob_start();

        do_action( 'gb_hcc_form_before', $post_id );

        include( $plugin_path . 'forms/two-step-template.php' );

        do_action( 'gb_hcc_form_after', $post_id );

        $result .= ob_get_clean();
    }

	if( $form_popup_mode )
    {
	    $result .= '</div></div>';
    }

    return $result;
}
add_shortcode( 'wc_hcc_checkout', 'gb_hcc_form_shortcode_display' );

/**
 * Validate & replace the "update_order_review" nonce
 * for cached pages with Embed Checkout Forms to fix
 * greyed out forms / stuck on loading
 *
 * @since 1.4.1
 */

function gb_hcc_form_validate_nonce()
{
    if( empty( $_POST['nonce'] ) )
    {
        $_POST['nonce'] = '';
    }

    if( empty( $_POST['rnd'] ) )
    {
        $_POST['rnd'] = '';
    }

    $nonce = sanitize_text_field( $_POST['nonce'] );

    $rnd = intval( $_POST['rnd'] );

    $result = $nonce;

    if( !wp_verify_nonce( $nonce, 'update-order-review' ) )
    {
        if( WC()->cart->is_empty() )
        {
            gb_hcc_form_preadd_product_to_cart( $force = TRUE );
        }

        $result = wp_create_nonce( 'update-order-review' );
    }

    echo json_encode(
        array(
            'result' => $result,
            'rnd' => $rnd,
        )
    );

    die();
}
add_action( 'wp_ajax_hcc_form_validate_nonce', 'gb_hcc_form_validate_nonce' );
add_action( 'wp_ajax_nopriv_hcc_form_validate_nonce', 'gb_hcc_form_validate_nonce' );

/**
 * Register the Embed Forms submenu page
 *
 * @since 1.4.0
 */

function gb_hcc_form_add_admin_page()
{
    add_submenu_page( 'edit.php?post_type=handsome-checkout', __( 'Embed Checkout Forms', 'gb-wc-hcc' ) . ' by BogdanFix', __( 'Embed Forms', 'gb-wc-hcc' ), 'manage_options', GB_HCC_ID . '_form_shortcodes', 'gb_hcc_form_do_admin_page' );
}
add_action( 'admin_menu', 'gb_hcc_form_add_admin_page' );

/**
 * Redirect to shortcode listing page
 *
 * @since 1.4.0
 */

function gb_hcc_form_do_admin_page()
{
    if( !current_user_can( 'manage_options' ) )
    {
        wp_die( __( 'Oops, you can\'t access this page.', 'gb-wc-hcc' ) );
    }

    wp_safe_redirect( admin_url( 'edit.php?post_type=handsome-checkout&post_status=shortcode' ) );

    die();
}

/**
 * Display type select metabox
 *
 * @since 1.4.0
 */

function gb_hcc_display_mode_metabox( $post )
{
    ?>
    <style type="text/css">
    .thrive-architect-edit-link {
        display: none !important;
    }
    </style>

    <p>
        <?php esc_html_e( 'Select the type of checkout you want to create below.', 'gb-wc-hcc' ); ?>
    </p>

    <select id="gb-hcc-post-type" name="gb-hcc-select-mode">
        <option disabled="disabled" selected="selected"><?php esc_html_e( 'Select your option...', 'gb-wc-hcc' ); ?></option>
        <option value="page"><?php esc_html_e( 'Checkout Page', 'gb-wc-hcc' ); ?></option>
        <option value="shortcode"><?php esc_html_e( 'Embed Checkout Form (via shortcode)', 'gb-wc-hcc' ); ?></option>
    </select>

    <?php

    if( $post->post_status == 'auto-draft' )
    {

        ?>
        <style type="text/css">
        #gb_hcc_metabox,
        #gb_hcc_shortcode_metabox {
            display: none;
        }
        </style>

        <script type="text/javascript">

            jQuery(document).ready( function()
            {
                jQuery( "#gb-hcc-post-type" ).on( "change", function()
                {
                    var mode = this.value;

                    if( mode == "page" )
                    {
                        jQuery( "#gb_hcc_metabox" ).css( "display", "block" );
                        jQuery( "#gb_hcc_shortcode_metabox" ).css( "display", "none" );
                    }
                    else if( mode == "shortcode" )
                    {
                        jQuery( "#gb_hcc_shortcode_metabox" ).css( "display", "block" );
                        jQuery( "#gb_hcc_metabox" ).css( "display", "none" );
                    }
                });
            });

        </script>
        <?php

    }

    elseif( $post->post_status == 'shortcode' )
    {

        ?>
        <style type="text/css">
        #gb_hcc_metabox,
        #gb_hcc_select_metabox {
            display: none;
        }

        #gb_hcc_shortcode_metabox {
            display: block;
        }

        #edit-slug-box,
        .misc-pub-section.misc-pub-post-status,
        .misc-pub-section.misc-pub-visibility,
        #minor-publishing-actions {
            display: none !important;
        }
        </style>
        <?php

    }

    elseif(
        $post->post_status == 'publish' ||
        $post->post_status == 'draft'
    )
    {

        ?>
        <style type="text/css">
        #gb_hcc_shortcode_metabox,
        #gb_hcc_select_metabox {
            display: none;
        }

        #gb_hcc_metabox {
            display: block;
        }
        </style>
        <?php

    }
}

/**
 * Display form options metabox
 *
 * @since 1.4.0
 */

function gb_hcc_display_form_metabox( $post )
{
    if( $post->post_status == 'publish' || $post->post_status == 'draft' )
    {
        return;
    }

    $form_product_id = get_post_meta( $post->ID, 'hcc_form_product_id', TRUE );

    $form_product_id_json = array();

    if( !empty( $form_product_id ) )
    {
        $form_product_id = explode( ',', $form_product_id );

        foreach( $form_product_id as $id )
        {
            $product = wc_get_product( $id );

            if( !empty( $product ) )
            {
                $form_product_id_json[] = array(
                    'id'   => $id,
                    'text' => '#' . $id . ' &ndash; ' . $product->get_title(),
                );
            }
        }

        $form_product_id_json = json_encode( $form_product_id_json );
    }

    // Order Bump

    $form_order_bump = get_post_meta( $post->ID, 'hcc_form_order_bump', TRUE );

    $form_order_bump_product_id = get_post_meta( $post->ID, 'hcc_form_order_bump_product_id', TRUE );

    $form_order_bump_product_id_json = array();

    if( !empty( $form_order_bump_product_id ) )
    {
        $form_order_bump_product_id = explode( ',', $form_order_bump_product_id );

        foreach( $form_order_bump_product_id as $id )
        {
            $product = wc_get_product( $id );

            if( !empty( $product ) )
            {
                $form_order_bump_product_id_json[] = array(
                    'id'   => $id,
                    'text' => '#' . $id . ' &ndash; ' . $product->get_title(),
                );
            }
        }

        $form_order_bump_product_id_json = json_encode( $form_order_bump_product_id_json );
    }

    $form_order_bump_label       = get_post_meta( $post->ID, 'hcc_form_order_bump_label', TRUE );
    $form_order_bump_highlight   = get_post_meta( $post->ID, 'hcc_form_order_bump_highlight', TRUE );
    $form_order_bump_description = get_post_meta( $post->ID, 'hcc_form_order_bump_description', TRUE );

    // Order Bump END

    // Variations & Quantity Options

    $vq_options                      = get_post_meta( $post->ID, 'hcc_vq_options', TRUE );
    $vq_options_main_title           = get_post_meta( $post->ID, 'hcc_vq_options_main_title', TRUE );
    $vq_options_item_title           = get_post_meta( $post->ID, 'hcc_vq_options_item_title', TRUE );
    $vq_options_price_title          = get_post_meta( $post->ID, 'hcc_vq_options_price_title', TRUE );
    $vq_options_mode                 = get_post_meta( $post->ID, 'hcc_vq_options_mode', TRUE );
    $vq_option_highlight_show        = get_post_meta( $post->ID, 'hcc_vq_option_highlight_show', TRUE );
    $vq_option_highlight             = get_post_meta( $post->ID, 'hcc_vq_option_highlight', TRUE );
    $vq_option_highlight_inscription = get_post_meta( $post->ID, 'hcc_vq_option_highlight_inscription', TRUE );
    $vq_options_table                = get_post_meta( $post->ID, 'hcc_vq_options_sq_table', TRUE );
    $vq_option_selected              = get_post_meta( $post->ID, 'hcc_vq_option_selected', TRUE );

    // Variations & Quantity Options END

    $form_color_background_form 	= get_post_meta( $post->ID, 'hcc_form_color_background', TRUE );
    $form_color_text_form 		= get_post_meta( $post->ID, 'hcc_form_color_text', TRUE );
    $form_color_links_form 		= get_post_meta( $post->ID, 'hcc_form_color_links', TRUE );
    $form_color_payment_form 	= get_post_meta( $post->ID, 'hcc_form_color_payment', TRUE );
    $form_color_button_1 		= get_post_meta( $post->ID, 'hcc_form_color_button_1', TRUE );
    $form_color_button_2 		= get_post_meta( $post->ID, 'hcc_form_color_button_2', TRUE );

    $form_fields_billing_email_first 	= get_post_meta( $post->ID, 'hcc_form_fields_billing_email_first', TRUE );
    $form_fields_shipping_first 		= get_post_meta( $post->ID, 'hcc_form_fields_shipping_first', TRUE );

    $form_fields_billing             = get_post_meta( $post->ID, 'hcc_form_fields_billing', TRUE );
    $form_fields_billing_names       = get_post_meta( $post->ID, 'hcc_form_fields_billing_names', TRUE );
    $form_fields_billing_company     = get_post_meta( $post->ID, 'hcc_form_fields_billing_company', TRUE );
    $form_fields_shipping            = get_post_meta( $post->ID, 'hcc_form_fields_shipping', TRUE );
    $form_fields_coupon              = get_post_meta( $post->ID, 'hcc_form_fields_coupon', TRUE );
    $form_fields_phone               = get_post_meta( $post->ID, 'hcc_form_fields_phone', TRUE );

    $form_order_details    = get_post_meta( $post->ID, 'hcc_form_order_details', TRUE );
    $form_order_total_hide = get_post_meta( $post->ID, 'hcc_form_order_total_hide', TRUE );

    $form_title_text_step1  = get_post_meta( $post->ID, 'hcc_form_title_text_step1', TRUE );
    $form_title_text_step2  = get_post_meta( $post->ID, 'hcc_form_title_text_step2', TRUE );
    $form_button_text_step1 = get_post_meta( $post->ID, 'hcc_form_button_text_step1', TRUE );
    $form_button_text       = get_post_meta( $post->ID, 'hcc_form_button_text', TRUE );

    $form_custom_css = get_post_meta( $post->ID, 'hcc_form_custom_css', TRUE );
    $form_custom_js  = get_post_meta( $post->ID, 'hcc_form_custom_js', TRUE );

    $form_custom_html_footer = get_post_meta( $post->ID, 'hcc_form_custom_html_footer', TRUE );

    // shortcode unique options

    $form_shortcode_steps = get_post_meta( $post->ID, 'hcc_form_shortcode_steps', TRUE );
    $form_shortcode_fieldnames = get_post_meta( $post->ID, 'hcc_form_shortcode_fieldnames', TRUE );
    $form_popup_mode = get_post_meta( $post->ID, 'hcc_form_popup_mode', TRUE);

    $shortcode_id = FALSE;

    if( isset( $post->ID ) )
    {
        $shortcode_id = intval( $post->ID );
    }

    ?>
    <style type="text/css">
    .tip {
        color: #888;
        font-style: italic;
        vertical-align: middle;
    }

    table.wc-hcc-table {
        width: 100%;
    }

    table.wc-hcc-table tr td:first-child {
        width: 30%;
        vertical-align: top;
        line-height: 30px;
    }

    table.wc-hcc-table tr td {
        padding: 20px 0;
    }

    table.wc-hcc-table tr td .wc-hcc-field-label {
        font-size: 16px;
    }

    table.wc-hcc-table tr td .wc-hcc-field-rs {
        font-size: 12px;
        color: #999;
    }

    table.wc-hcc-table textarea {
        width: 100%;
        min-height: 100px;
    }

    table.wc-hcc-table input[type="text"] {
        width: 60%;
    }

    table.wc-hcc-table input[type="checkbox"] {
        margin: 0;
    }

    table.wc-hcc-table-inner tr td:first-child {
        width: auto;
        vertical-align: middle;
        line-height: normal;
    }

    table.wc-hcc-table-inner tr td {
        padding: 0;
    }

    .wc-hcc-left {
        width: 50%;
        float: left;
    }

    .wc-hcc-left-30 {
        width: 30%;
        float: left;
    }

    .wc-hcc-right {
        width: 50%;
        float: right;
    }

    .wc-hcc-right-70 {
        width: 70%;
        float: right;
    }

    .select2-container {
        width: 100% !important;
    }

    .wc-hcc-save-bar {
        display: none;
        position: fixed;
        top: 30px;
        left: 0;
        right: 0;
        width: 100%;
        background: rgba( 230, 230, 230, 0.7 );
        text-align: center;
    }
    .wc-hcc-save-bar > div {
        padding: 10px;
    }

    @media screen and (max-width: 782px) {

        .wc-hcc-save-bar {
            top: 46px;
        }
    }

    #wc-hcc-shortcode-sample {
        width: 100%;
        min-width: 300px;
        font-size: 16px;
        padding: 10px;
        text-align: left;
    }

    table.wc-hcc-vq-options-table {
        width: 100%;
        border-left: 1px solid #f1f1f1;
        border-spacing: 0;
    }

    table.wc-hcc-vq-options-table tr td {
        text-align: left;
        padding: 8px 10px;
    }

    table.wc-hcc-vq-options-table tr td:first-child {
    	vertical-align: middle;
    }

    table.wc-hcc-vq-options-table td {
        border-bottom: 1px solid #f1f1f1;
    }

    td.vq-options-table-header {
        border-top: 1px solid #f1f1f1;
    }

    td.vq-option-radio {
        border-right: 1px solid #f1f1f1;
    }

    table.wc-hcc-vq-options-table td.vq-options-table-trash {
        border: 0;
    }

    table.wc-hcc-vq-options-table input[type="text"] {
        width: 100%;
    }

    input.vq-option-qty {
        width: 4em;
    }

    .wc-hcc-vq-option-controls {
        margin-top: 10px;
    }

    .wc-hcc-vq-option-remove {
        cursor: pointer;
    }

    /* 3rd party */

    .thrive-architect {
        display: none !important;
    }
    </style>

    <input type="hidden" name="hcc_form_editor" value="1" />

    <table class="wc-hcc-table">

        <?php

        if( $shortcode_id )
        {
            $shortcode = '[wc_hcc_checkout id="' . intval( $shortcode_id ) .  '"]';

            if( $form_popup_mode )
            {
                $content_text = __('Your button here', 'gb-wc-hcc');
                $shortcode .= $content_text . '[/wc_hcc_checkout]';
            }

            ?>
            <tr class="popup-shortcode">
                <td style="vertical-align: middle;">
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Copy Embed Shortcode', 'gb-wc-hcc' ); ?>:</div>
                </td>
                <td>
                    <input type="text" id="wc-hcc-shortcode-sample" value='<?php echo esc_textarea( $shortcode ); ?>' readonly="readonly" onfocus="this.select();" onmouseup="return false;" />
                </td>
            </tr>
            <?php
        }
        ?>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Display as a popup', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="checkbox" name="hcc_form_popup_mode" value="1" <?php checked( $form_popup_mode, '1' ) ?> />
                <span class="tip"><?php esc_html_e( 'display embed form as a popup', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>
        
        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Select Product', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <select
                        name="hcc_form_product_id[]"
                        class="wc-hcc-form-product-search"
                        multiple="multiple"
                        data-selected="<?php if ( !empty( $form_product_id_json ) ) { echo htmlspecialchars( $form_product_id_json ); } ?>"
                        data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                        data-action="woocommerce_json_search_products">
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Form Layout', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <select name="hcc_form_shortcode_steps">
                    <option value="one_step" <?php echo ( $form_shortcode_steps == 'one_step' ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'One Step', 'gb-wc-hcc' ); ?></option>
                    <option value="two_steps" <?php echo ( $form_shortcode_steps == 'two_steps' ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'Two Steps (billing / shipping & payment)', 'gb-wc-hcc' ); ?></option>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Field Names Display', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <select name="hcc_form_shortcode_fieldnames">
                    <option value="placeholders" <?php echo ( $form_shortcode_fieldnames == 'placeholders' ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'placeholders', 'gb-wc-hcc' ); ?></option>
                    <option value="both" <?php echo ( $form_shortcode_fieldnames == 'both' ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'labels & placeholders', 'gb-wc-hcc' ); ?></option>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="checkbox" name="hcc_form_order_bump" value="1" <?php checked( $form_order_bump, '1' ) ?> />
                <span class="tip"><?php esc_html_e( 'enable', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>

        <tr class="order-bump" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Product', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <select
                        name="hcc_form_order_bump_product_id[]"
                        class="wc-hcc-form-product-search"
                        data-selected="<?php if ( ! empty( $form_order_bump_product_id_json ) ) { echo htmlspecialchars( $form_order_bump_product_id_json ); } ?>"
                        data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                        data-action="woocommerce_json_search_products">
                </select>
            </td>
        </tr>

        <tr class="order-bump" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Checkbox Label', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_order_bump_label" value="<?php echo esc_html( $form_order_bump_label ); ?>" placeholder="<?php esc_html_e( 'Yes, I will take it!', 'gb-wc-hcc' ); ?>"/>
            </td>
        </tr>

        <tr class="order-bump" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Highlighted Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_order_bump_highlight" value="<?php echo esc_html( $form_order_bump_highlight ); ?>" placeholder="<?php esc_html_e( 'ONE TIME OFFER', 'gb-wc-hcc' ); ?>"/>
            </td>
        </tr>

        <tr class="order-bump" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Main Description', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <textarea name="hcc_form_order_bump_description"><?php echo esc_textarea( $form_order_bump_description ); ?></textarea>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Variations & Quantity Options', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="checkbox" name="hcc_vq_options" value="1" <?php checked( $vq_options, '1' ) ?> />
                <span class="tip"><?php esc_html_e( 'enable', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Main Title', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_vq_options_main_title" value="<?php echo esc_html( $vq_options_main_title ); ?>" placeholder="<?php esc_html_e( 'Product Selection', 'gb-wc-hcc' ); ?>" />
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Item Title', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_vq_options_item_title" value="<?php echo esc_html( $vq_options_item_title ); ?>" placeholder="<?php esc_html_e( 'Item', 'gb-wc-hcc' ); ?>" />
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Price Title', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_vq_options_price_title" value="<?php echo esc_html( $vq_options_price_title ); ?>" placeholder="<?php esc_html_e( 'Price', 'gb-wc-hcc' ); ?>" />
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Highlight Inscription', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_vq_option_highlight_inscription" value="<?php echo esc_html( $vq_option_highlight_inscription ); ?>" placeholder="<?php esc_html_e( 'MOST POPULAR!', 'gb-wc-hcc' ); ?>" />
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Mode', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <select name="hcc_vq_options_mode">
                    <option value="simple_quantity" <?php if( $vq_options_mode == 'simple_quantity' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simple quantity', 'gb-wc-hcc' ); ?></option>
                    <option value="simple_variations" <?php if( $vq_options_mode == 'simple_variations' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simple variations', 'gb-wc-hcc' ); ?></option>
                    <option value="dropdown_quantity_and_variations" <?php if( $vq_options_mode == 'dropdown_quantity_and_variations' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dropdown quantity and variations', 'gb-wc-hcc' ); ?></option>
                </select>
            </td>
        </tr>

        <tr class="vq-options" style="display: none;">
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Table', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <div class="wc-hcc-vq-options-wrap wc-hcc-vq-options-table-simple" data-count="1" style="display: none;">
                    <table class="wc-hcc-vq-options-table">
                        <tr>
                            <td class="vq-options-table-header">
                                <div class="wc-hcc-field-label"><?php esc_html_e( 'Default', 'gb-wc-hcc' ); ?></div>
                            </td>
                            <td class="vq-options-table-header">
                                <div class="wc-hcc-field-label"><?php esc_html_e( 'Qty', 'gb-wc-hcc' ); ?></div>
                            </td>
                            <td class="vq-options-table-header" style="width: 42%;">
                                <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom Name', 'gb-wc-hcc' ); ?></div>
                            </td>
                            <td class="vq-options-table-header" style="width: 16%;">
                                <div class="wc-hcc-field-label"><?php esc_html_e( 'Price', 'gb-wc-hcc' ); ?></div>
                            </td>
                            <td class="vq-options-table-header vq-option-radio">
                                <div class="wc-hcc-field-label">
                                    <?php esc_html_e( 'Highlight', 'gb-wc-hcc' ); ?>&nbsp;
                                    <input type="checkbox" name="hcc_vq_option_highlight_show" value="1" <?php checked( $vq_option_highlight_show, '1' ) ?> />
                                </div>
                            </td>
                            <td class="vq-options-table-trash"></td>
                        </tr>
                        <?php

                        if( !empty( $vq_options_table ) && is_array( $vq_options_table ) )
                        {
                            $counter = 0;

                            foreach( $vq_options_table as $k => $vq_o_t )
                            {
                                ?>
                                <tr class="wc-hcc-vq-option simple-quantity">
                                    <td style="text-align: center;">
                                        <input type="radio"
                                               name="hcc_vq_option_selected"
                                               class="vq-option-selected"
                                            <?php checked( $vq_option_selected, $counter ); ?>
                                               value="<?php echo esc_html( $counter ); ?>" />
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][qty]"
                                               min="0"
                                               step="1"
                                               pattern="[0-9]*"
                                               class="vq-option-qty"
                                               value="<?php echo esc_html( $vq_o_t['qty'] ); ?>" />
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][name]"
                                               placeholder="<?php esc_html_e('optional', 'gb-wc-hcc'); ?>"
                                               class="vq-option-name"
                                               value="<?php echo esc_html( $vq_o_t['name'] ); ?>" />
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][price]"
                                               placeholder="<?php esc_html_e('optional', 'gb-wc-hcc'); ?>"
                                               min="0"
                                               step="0.01"
                                               class="vq-option-price"
                                               value="<?php echo esc_html( $vq_o_t['price'] ); ?>" />
                                    </td>
                                    <td class="vq-option-radio" style="text-align: center;">
                                        <input type="radio"
                                               name="hcc_vq_option_highlight"
                                               class="vq-option-highlight"
                                            <?php checked( $vq_option_highlight, $counter ); ?>
                                               value="<?php echo esc_html( $counter ); ?>" />
                                    </td>
                                    <td class="vq-options-table-trash">
                                        <span class="dashicons dashicons-trash wc-hcc-vq-option-remove"></span>
                                    </td>
                                </tr>
                                <?php

                                $counter++;
                            }
                        }
                        else
                        {
                            ?>
                            <tr class="wc-hcc-vq-option">
                                <td style="text-align: center;">
                                    <input type="radio"
                                           name="hcc_vq_option_selected"
                                           class="vq-option-selected"
                                           value="0"/>
                                </td>
                                <td>
                                    <input type="number"
                                           name="hcc_vq_options_sq_table[0][qty]"
                                           min="0"
                                           step="1"
                                           pattern="[0-9]*"
                                           class="vq-option-qty"
                                           value="1" />
                                </td>
                                <td>
                                    <input type="text"
                                           name="hcc_vq_options_sq_table[0][name]"
                                           placeholder="<?php esc_html_e( 'optional', 'gb-wc-hcc' ); ?>"
                                           class="vq-option-name"
                                           value="" />
                                </td>
                                <td>
                                    <input type="number"
                                           name="hcc_vq_options_sq_table[0][price]"
                                           placeholder="optional"
                                           min="0"
                                           step="0.01"
                                           class="vq-option-price"
                                           value="" />
                                </td>
                                <td class="vq-option-radio" style="text-align: center;">
                                    <input type="radio"
                                           name="hcc_vq_option_highlight"
                                           class="vq-option-highlight"
                                           value="0" />
                                </td>
                                <td class="vq-options-table-trash">
                                    <span class="dashicons dashicons-trash wc-hcc-vq-option-remove"></span>
                                </td>
                            </tr>
                            <?php
                        }

                        ?>
                    </table>
                </div>
                <div class="wc-hcc-vq-option-controls wc-hcc-vq-options-table-simple" style="display: none;">
                    <button class="button wc-hcc-vq-option-add"><?php esc_html_e( '+ add option', 'gb-wc-hcc' ); ?></button>
                </div>
                <div class="wc-hcc-vq-options-table-variation" style="display: none;">
                    <?php esc_html_e( 'Options will be taken from variations of the selected product(s)', 'gb-wc-hcc' ); ?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Template Colors', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <table class="wc-hcc-table-inner">
                    <tr>
                        <td style="min-width: 125px;"><?php esc_html_e( 'Background Color', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="text" name="hcc_form_color_background" value="<?php echo esc_html( $form_color_background_form ); ?>" class="wc-hcc-color-picker"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Text Color', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="text" name="hcc_form_color_text" value="<?php echo esc_html( $form_color_text_form ); ?>" class="wc-hcc-color-picker"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Links Color', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="text" name="hcc_form_color_links" value="<?php echo esc_html( $form_color_links_form ); ?>" class="wc-hcc-color-picker"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Buttons Color', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="text" name="hcc_form_color_button_1" value="<?php echo esc_html( $form_color_button_1 ); ?>" class="wc-hcc-color-picker"/>
                            <input type="text" name="hcc_form_color_button_2" value="<?php echo esc_html( $form_color_button_2 ); ?>" class="wc-hcc-color-picker"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Fields', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <table class="wc-hcc-table-inner">
                    <tr>
                        <td style="min-width: 125px;"><?php esc_html_e( 'Email field first', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_billing_email_first" value="1" <?php checked( $form_fields_billing_email_first ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Swap billing & shipping', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_shipping_first" value="1" <?php checked( $form_fields_shipping_first, '1' ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><br/></td>
                    </tr>
                    <tr>
                        <td style="min-width: 125px;"><?php esc_html_e( 'Billing Address', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_billing" value="1" <?php checked( $form_fields_billing, '1' ) ?> />&nbsp;
                            <span class="tip"><?php esc_html_e( 'important: should be disabled only for "virtual" products', 'gb-wc-hcc' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width: 125px;"><?php esc_html_e( 'First + Last Name', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_billing_names" value="1" <?php checked( $form_fields_billing_names, '1' ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width: 125px;"><?php esc_html_e( 'Company name', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_billing_company" value="1" <?php checked( $form_fields_billing_company, '1' ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Phone Number', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_phone" value="1" <?php checked( $form_fields_phone, '1' ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><br/></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Shipping Address', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_shipping" value="1" <?php checked( $form_fields_shipping, '1' ) ?> />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><br/></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Coupon Box', 'gb-wc-hcc' ); ?></td>
                        <td>
                            <input type="checkbox" name="hcc_form_fields_coupon" value="1" <?php checked( $form_fields_coupon, '1' ) ?> />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Details', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="checkbox" name="hcc_form_order_details" value="1" <?php checked( $form_order_details, '1' ) ?> />
                <span class="tip"><?php esc_html_e( 'display order details table', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Hide Order Total', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="checkbox" name="hcc_form_order_total_hide" value="1" <?php checked( $form_order_total_hide, '1' ) ?> />
                <span class="tip"><?php esc_html_e( 'hide order total box (above the "place order" button)', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 1 Title Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_title_text_step1" value="<?php echo esc_html( $form_title_text_step1 ); ?>" placeholder="<?php esc_html_e( 'Step 1: Billing Details', 'gb-wc-hcc' ); ?>"/>
                <br/>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 2 Title Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_title_text_step2" value="<?php echo esc_html( $form_title_text_step2 ); ?>" placeholder="<?php esc_html_e( 'Step 2: Shipping & Payment', 'gb-wc-hcc' ); ?>"/>
                <br/>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 1 Button Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_button_text_step1" value="<?php echo esc_html( $form_button_text_step1 ); ?>" placeholder="<?php esc_html_e( 'Next Step', 'gb-wc-hcc' ); ?>"/>
                <br/>
                <span class="tip"><?php esc_html_e( 'used in two step template only', 'gb-wc-hcc' ); ?></span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Checkout Button Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <input type="text" name="hcc_form_button_text" value="<?php echo esc_html( $form_button_text ); ?>" placeholder="<?php esc_html_e( 'Place order', 'woocommerce' ); ?>"/>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom CSS', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <textarea name="hcc_form_custom_css"><?php echo $form_custom_css; ?></textarea>
                <p class="description"><?php esc_html_e( 'Custom CSS from this field will be applied only to the current page.', 'gb-wc-hcc' ); ?></p>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom JS', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <textarea name="hcc_form_custom_js"><?php echo $form_custom_js; ?></textarea>
                <p class="description"><?php esc_html_e( 'Custom JS from this field will be applied only to the current page. &#60;script&#62; tags are not required.', 'gb-wc-hcc' ); ?></p>
            </td>
        </tr>

        <tr>
            <td>
                <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom Footer Text', 'gb-wc-hcc' ); ?></div>
            </td>
            <td>
                <textarea name="hcc_form_custom_html_footer"><?php echo $form_custom_html_footer; ?></textarea>
                <p class="description"><?php esc_html_e( 'Any text or HTML from this field will be displayed under the "Place order" button of this form.', 'gb-wc-hcc' ); ?></p>
            </td>
        </tr>
        
    </table>

    <div class="wc-hcc-save-bar">
        <div>
            <div class="button button-primary button-large" id="wc-hcc-save-btn"><?php esc_html_e( 'Update' ); ?></div>
        </div>
    </div>

    <script type="text/javascript">

        jQuery(document).ready(function () {

            // order bump

            jQuery('input[name="hcc_form_order_bump"]').on('change', function ()
            {
                var $this = jQuery(this);

                var $order_bump = jQuery('.order-bump');

                if( $this.is(':checked') )
                {
                    $order_bump.show();
                }
                else
                {
                    $order_bump.hide();
                }

            }).trigger('change');

            // variations & quantity options

            jQuery( 'input[name="hcc_vq_options"]' ).on( 'change', function() {
                var $this = jQuery( this );

                var $vq_options = jQuery( '.vq-options' );

                if( $this.is( ':checked' ) ) {
                    $vq_options.show();
                } else {
                    $vq_options.hide();
                }
            }).trigger( 'change' );

            // toggle vq options mode

            jQuery( 'select[name="hcc_vq_options_mode"]' ).on( 'change', function() {
                var $this = jQuery( this );

                if ( $this.val() === 'simple_quantity' ) {
                    jQuery( '.wc-hcc-vq-options-table-variation' ).hide();
                    jQuery( '.wc-hcc-vq-options-table-simple' ).show();
                } else {
                    jQuery( '.wc-hcc-vq-options-table-simple' ).hide();
                    jQuery( '.wc-hcc-vq-options-table-variation' ).show();
                }
            }).trigger( 'change' );

            // add option item

            jQuery( '.wc-hcc-vq-option-add' ).on( 'click', function( event ) {
                event.preventDefault();

                var $hcc_vq_options_table = jQuery( '.wc-hcc-vq-options-table' );

                var $hcc_option = jQuery( '.wc-hcc-vq-option' ).clone().css( { 'display': 'none' } );

                var hcc_option_count = $hcc_vq_options_table.find( '.wc-hcc-vq-option' ).length;

                $hcc_option.find( '.vq-option-qty' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][qty]' ).attr( 'value', 1 );

                $hcc_option.find( '.vq-option-price' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][price]' ).attr( 'value', '' );

                $hcc_option.find( '.vq-option-name' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][name]' ).attr( 'value', '' );

                $hcc_option.find( '.vq-option-highlight' ).attr( 'name', 'hcc_vq_option_highlight' ).attr( 'value', hcc_option_count );

                $hcc_option.find( '.vq-option-selected' ).attr( 'name', 'hcc_vq_option_selected' ).attr( 'value', hcc_option_count );

                $hcc_vq_options_table.append( $hcc_option[0].outerHTML );

                $hcc_vq_options_table.find( '.wc-hcc-vq-option' ).last().slideDown( 300 );

            });

            // remove option item

            jQuery( '.wc-hcc-vq-options-table' ).on( 'click', '.wc-hcc-vq-option-remove', function( event ) {
                event.preventDefault();

                if( confirm('<?php esc_html_e( 'Are you sure?', 'gb-wc-hcc' ); ?>') != true )
                {
                    return false;
                }

                var $this = jQuery( event.target );

                var $hcc_vq_options_table = jQuery( '.wc-hcc-vq-options-table' );

                if( $hcc_vq_options_table.find('.wc-hcc-vq-option').length == 1 )
                {
                    //
                }
                else
                {
                    $this.closest( '.wc-hcc-vq-option' ).slideUp( 300, function() {
                        jQuery( this ).remove();
                    });
                }
            });

            // init color picker

            jQuery('.wc-hcc-color-picker').wpColorPicker();

            // fill the select product field

            var $product_search = jQuery('.wc-hcc-form-product-search');

            if( $product_search.length > 0 )
            {
                $product_search.addClass('wc-product-search');

                $product_search.each(function ()
                {
                    var $this = jQuery(this);

                    jQuery($this.data('selected')).each(function (index, product) {

                        $this.append('<option value="' + product['id'] + '" selected="selected">' + product['text'] + '</option>');
                    });
                });

                jQuery(document.body).trigger('wc-enhanced-select-init');
            }

            // sticky "save" button

            jQuery(window).scroll(function(){

                if( jQuery(this).scrollTop() > 300 )
                {
                    jQuery('.wc-hcc-save-bar').slideDown();
                }
                else
                {
                    jQuery('.wc-hcc-save-bar').slideUp();
                }
            });

            jQuery('#wc-hcc-save-btn').on('click', function(){

                jQuery('input#publish').trigger('click');

                jQuery(this).attr( 'disabled', true ).text('<?php esc_html_e( 'Updating...' ); ?>');
            });
        });
    </script>
    <?php
}

/**
 * Display custom text/HTML under "place order" button in form
 *
 * @since 1.4.0
 */

function gb_hcc_form_custom_html_footer()
{
    global $post;

    if( !empty( $post ) && $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

        if( !empty( $post_id ) )
        {
            $form_custom_html_footer = get_post_meta( $post_id, 'hcc_form_custom_html_footer', TRUE );

            if( !empty( $form_custom_html_footer ) )
            {
                echo '<p class="hcc-form-custom-html">'. $form_custom_html_footer .'</p>';
            }
        }
    }
}
add_action( 'woocommerce_review_order_after_submit', 'gb_hcc_form_custom_html_footer');

/**
 * HELPER: Make hex color lighter or darker
 *
 * @since 1.4.0
 */

function gb_hcc_adjust_color_brightness( $hex, $steps )
{
    // steps should be between -255 and 255. negative = darker, positive = lighter

    $steps = max( -255, min( 255, $steps ) );

    // normalize into a six character long hex string

    $hex = str_replace( '#', '', $hex );

    if( strlen( $hex ) == 3 )
    {
        $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) .
            str_repeat( substr( $hex, 1, 1 ), 2) .
            str_repeat( substr( $hex, 2, 1 ), 2);
    }

    // split into three parts: R, G and B

    $color_parts = str_split( $hex, 2 );
    $return = '#';

    foreach( $color_parts as $color )
    {
        $color   = hexdec( $color ); // convert to decimal
        $color   = max( 0, min( 255, $color + $steps ) ); // adjust color
        $return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // make two char hex code
    }

    return $return;
}

?>
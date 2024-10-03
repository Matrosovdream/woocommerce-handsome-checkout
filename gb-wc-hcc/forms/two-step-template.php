<?php
    if( !defined( 'ABSPATH' ) ) die();

    if( empty( $post_id ) || !empty( $template_included ) )
    {
        return;
    }

    $template_included = 1;

    $form_title_text_step1 = get_post_meta( $post_id, 'hcc_form_title_text_step1', TRUE );
    $form_title_text_step2 = get_post_meta( $post_id, 'hcc_form_title_text_step2', TRUE );
    $form_button_text_step1 = get_post_meta( $post_id, 'hcc_form_button_text_step1', TRUE );

?>
    <style type="text/css">

    .hcc-embed-form-wrapper {
        padding: 15px 0 15px 0;
        background: transparent !important;
        background-color: transparent;
    }

    .hcc-embed-form-wrapper p,
    .hcc-embed-form-wrapper li {
        color: #FFF;
    }

    .hcc-embed-form-wrapper .shop_table {
        padding: 0 15px 0 15px !important;
    }

    .hcc-embed-form-wrapper #payment .payment_methods {
        margin: 0 15px 0 15px !important;
    }

    .hcc-embed-form-wrapper .woocommerce-checkout-price {
        margin: 0 15px 15px 15px !important;
    }

    .hcc-embed-form-wrapper .woocommerce-privacy-policy-text {
        padding-right: 15px;
        padding-left: 15px;
    }

    .hcc-embed-form-wrapper .link-back1 {
        display: block;
    }

    /* Customizable */

<?php

    if( !empty( $form_color_background ) )
    {

?>

    .hcc-embed-form-wrapper .hcc-color-helper,
    .hcc-embed-form-wrapper .wizard,
    .hcc-embed-form-wrapper .woocommerce .woocommerce-info,
    .hcc-embed-form-wrapper .step-content .woocommerce-shipping-fields,
    .hcc-embed-form-wrapper .step-content .woocommerce-billing-fields,
    .hcc-embed-form-wrapper .step-content .create-account,
    .hcc-embed-form-wrapper .step-content #payment,
    .hcc-embed-form-wrapper .step-content #payment .place-order,
    .hcc-embed-form-wrapper #order_review,
    .hcc-embed-form-wrapper .step2.active,
    .hcc-embed-form-wrapper #payment .payment_methods > li,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table.shop_table,
    .hcc-embed-form-wrapper .woocommerce-checkout-payment#payment,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table th,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table td,
    .hcc-embed-modal-body {
        background: <?php echo esc_html( $form_color_background ); ?> !important;
    }

    .hcc-embed-form-wrapper .step1.active,
    .hcc-embed-form-wrapper .badge,
    .hcc-embed-form-wrapper #order_review_heading {
        background: <?php echo esc_html( gb_hcc_adjust_color_brightness( $form_color_background, 10  ) ); ?> !important;
    }

    .hcc-embed-form-wrapper #payment .payment_methods > li .payment_box,
    .hcc-embed-form-wrapper .step1,
    .hcc-embed-form-wrapper .step2,
    .hcc-embed-form-wrapper .badge-info {
        background: <?php echo esc_html( gb_hcc_adjust_color_brightness( $form_color_background, -10  ) ); ?> !important;
    }

    .hcc-embed-form-wrapper #payment div.payment_box::before {
        border-bottom-color: <?php echo esc_html( gb_hcc_adjust_color_brightness( $form_color_background, -10  ) ); ?> !important;
    }

<?php

    }

    if( !empty( $form_color_links ) )
    {

?>

    .hcc-embed-form-wrapper a,
    .hcc-embed-form-wrapper a:hover,
    .hcc-embed-form-wrapper .link-back1 {
        color: <?php echo esc_html( $form_color_links ); ?> !important;
    }

<?php

    }

    if( !empty( $form_color_text ) )
    {

?>

    .hcc-embed-form-wrapper p,
    .hcc-embed-form-wrapper h6,
    .hcc-embed-form-wrapper h3,
    .hcc-embed-form-wrapper #payment label,
    .hcc-embed-form-wrapper #ship-to-different-address span,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-table,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-item-wrap,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-price,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-table h1,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-table h2,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-table h3,
    .hcc-embed-form-wrapper .wc-hcc-vq-options-table h4,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table,
    .hcc-embed-form-wrapper .hcc-color-helper #payment .payment_methods > li .payment_box,
    .hcc-embed-form-wrapper .woocommerce-billing-fields h3,
    .hcc-embed-form-wrapper .badge,
    .hcc-embed-form-wrapper .badge-text,
    .hcc-embed-form-wrapper .step-content #payment label,
    .hcc-embed-form-wrapper #payment,
    .hcc-embed-form-wrapper .form-row,
    .hcc-embed-form-wrapper .woocommerce-privacy-policy-text,
    .hcc-embed-form-wrapper .woocommerce-privacy-policy-text p,
    .hcc-embed-form-wrapper .woocommerce-billing-fields .form-row label,
    .hcc-embed-form-wrapper .woocommerce-shipping-fields .form-row label,
    .hcc-embed-form-wrapper .woocommerce .woocommerce-info,
    .hcc-embed-form-wrapper .woocommerce .woocommerce-info::before,
    .hcc-embed-form-wrapper .hcc-form-custom-html,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table th,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table td,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table td li,
    .hcc-embed-form-wrapper #payment .payment_methods > li .payment_box {
        color: <?php echo esc_html( $form_color_text ); ?> !important;
    }

<?php

    }

    if( !empty( $form_color_payment ) )
    {

?>
    .hcc-embed-form-wrapper .hcc-color-helper #payment .payment_methods > li,
    .hcc-embed-form-wrapper .hcc-color-helper .woocommerce-checkout-review-order-table tr td,
    .hcc-embed-form-wrapper .hcc-color-helper #payment .payment_methods > li .payment_box {
        background: <?php echo esc_html( $form_color_payment ); ?> !important;
    }

<?php

    }

    if( !empty( $form_color_button_1 ) )
    {

?>
    .hcc-embed-form-wrapper .wizard-button,
    .hcc-embed-form-wrapper .step-content .button.alt,
    .hcc-embed-form-wrapper .checkout_coupon .button,
    .hcc-embed-form-wrapper .hcc-color-helper .checkout_coupon .button {
        background: <?php echo esc_html( $form_color_button_1 ); ?> !important;

<?php

        if( !empty( $form_color_button_2 ) )
        {

?>
        background: -moz-linear-gradient(top, <?php echo esc_html( $form_color_button_1 ); ?> 0, <?php echo esc_html( $form_color_button_2 ); ?> 100%) !important;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0, <?php echo esc_html( $form_color_button_1 ); ?>), color-stop(100%, <?php echo esc_html( $form_color_button_2 ); ?>)) !important;
        background: -webkit-linear-gradient(top, <?php echo esc_html( $form_color_button_1 ); ?> 0, <?php echo esc_html( $form_color_button_2 ); ?> 100%) !important;
        background: -o-linear-gradient(top, <?php echo esc_html( $form_color_button_1 ); ?> 0, <?php echo esc_html( $form_color_button_2 ); ?> 100%) !important;
        background: -ms-linear-gradient(top, <?php echo esc_html( $form_color_button_1 ); ?> 0, <?php echo esc_html( $form_color_button_2 ); ?> 100%) !important;
        background: linear-gradient(to bottom, <?php echo esc_html( $form_color_button_1 ); ?> 0, <?php echo esc_html( $form_color_button_2 ); ?> 100%) !important;
        filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='<?php echo esc_attr( $form_color_button_1 ); ?>', endColorstr='<?php echo esc_attr( $form_color_button_2 ); ?>', GradientType=0) !important;
<?php

        }
?>
    }

<?php

	}

?>
    .wc-hcc-vq-options {
        padding: 10px;
    }
    .woocommerce-billing-fields > h3,
    .woocommerce-shipping-fields > h3 {
        display: none;
    }

</style>

<!-- Handsome Checkout Embed Forms for WooCommerce -->

<div class="hcc-embed-form-wrapper">
    <section id="payment">
        <div class="hcc-container">
            <div class="hcc-row">
                <div class="widget-body fuelux col-md-8 col-md-offset-2">

                <div class="wizard">
                    <div class="steps">
                        <div class="step1 active">
                            <div class="badge badge-info">1</div>
                            <div class="badge-text">

                            <?php

                            if( !empty( $form_title_text_step1 ) )
                            {
	                            echo esc_html( $form_title_text_step1 );
                            }
                            else
                            {
	                            esc_html_e( 'Step 1: Billing Details', 'gb-wc-hcc' );
                            }

                            ?>

                            </div>
                            <span class="chevron"></span>
                        </div>
                        <div class="step2">
                            <div class="badge">2</div>
                            <div class="badge-text">

                            <?php

                            if( !empty( $form_title_text_step2 ) )
                            {
	                            echo esc_html( $form_title_text_step2 );
                            }
                            else
                            {
	                            esc_html_e( 'Step 2: Shipping & Payment', 'gb-wc-hcc' );
                            }

                            ?>

                            </div>
                            <span class="chevron"></span>
                        </div>
                    </div>
                </div>

                <div class="step-content">
                    <div class="step-pane active">
                        <div class="col-md-12">
                            <div class="hcc-color-helper">

                                <?php

                                    $checkout_code = do_shortcode('[woocommerce_checkout]');

                                    if(
                                        empty( $checkout_code ) ||
                                        trim( $checkout_code ) == '<div class="woocommerce"></div>'
                                    )
                                    {
                                        esc_html_e( 'Oops, there are no products in your cart.', 'gb-wc-hcc' );
                                    }
                                    else
                                    {
                                        echo $checkout_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }

                                ?>
                                <div class="hcc-btn-wrapper">
                                    <button class="btn btn-success btn-lg wizard-button btn-block btn-step1">
                                    <?php
                                        if( !empty( $form_button_text_step1 ) )
                                        {
                                            echo esc_html( $form_button_text_step1 );
                                        }
                                        else
                                        {
                                            esc_html_e( 'Next Step', 'gb-wc-hcc' );
                                        }
                                    ?>
                                    </button>
                                </div>

                                <div>
                                    <a href="#" class="link-back1" style="display: none;"><?php esc_html_e('< back to the previous step'); ?></a>
                                </div>

                            </div>
                        </div>

                        <div class="clearfix"></div>

                    </div><!-- end of step one -->
                </div><!-- end of step content div -->
            </div><!-- end of wizard -->
        </div>
    </div>

    </section>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){

    var step1 = jQuery('.woocommerce-info, #customer_details, .btn-step1');
    var step2 = jQuery('#order_review_heading, #order_review, #step2_details, .woocommerce-checkout > .woocommerce-checkout-subtitle, .wc-hcc-vq-options');

    var btn1 = jQuery('.btn-step1');

    // init step 1

    step2.hide();

    jQuery('#customer_details .woocommerce-checkout-subtitle').show();

    jQuery('#step2_details .woocommerce-billing-fields').find('h3, .woocommerce-checkout-subtitle').hide();

    jQuery('.col-2').hide();

    // show step 2

    btn1.on('click', function(){

        if( requiredFieldsFilled() == false )
        {
            return false;
        }

        step1.slideUp();

        step2.slideDown();

        jQuery('.steps .step2').addClass('active');
        jQuery('.steps .step1').removeClass('active');

        jQuery('.steps .step2 .badge').addClass('badge-info');
        jQuery('.steps .step1 .badge').removeClass('badge-info');

        jQuery('html, body').animate(
        {
            scrollTop: jQuery('.steps').offset().top
        },
        'slow');

        jQuery('.link-back1').show();

        jQuery('#order_review').prepend( jQuery('.col-2') );

        jQuery('.col-2').show();
    });

    // show step 1

    jQuery('.steps .step1, .link-back1').on('click', function(e){

        e.preventDefault();

        jQuery('.steps .step1').addClass('active');
        jQuery('.steps .step2').removeClass('active');

        jQuery('.steps .step1 .badge').addClass('badge-info');
        jQuery('.steps .step2 .badge').removeClass('badge-info');

        step1.slideDown();

        step2.slideUp();

        jQuery('html, body').animate(
        {
            scrollTop: jQuery('.steps').offset().top
        },
        'slow');

        jQuery('.link-back1').hide();
    });

    jQuery('.woocommerce-checkout').find('input, select').on('change', function(){

        jQuery(this).closest('.form-row.form-row-error').removeClass('form-row-error');
    });

    // functions

    function requiredFieldsFilled()
    {
        var result = true;

        var $step1 = jQuery('.checkout .col-1');

        if( $step1.is(':visible') )
        {
            $step1.find('.form-row').removeClass('form-row-error');

            $step1.find('.form-row').each(function( i ){

                var $row = jQuery(this);

                if( $row.find('abbr.required').length > 0 )
                {
                    if( 
                        $row.find('input').length > 0 &&
                        jQuery.trim( $row.find('input').val() ) == '' 
                    )
                    {
                        result = false;

                        $row.addClass('form-row-error');
                    }
                    else if( 
                        $row.find('select').length > 0 && 
                        jQuery.trim( $row.find('select option:selected').val() ) == '' 
                    )
                    {
                        result = false;

                        $row.addClass('form-row-error');
                    }
                }

            });
        }

        return result;
    }
});
</script>
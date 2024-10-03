<?php
    if( !defined( 'ABSPATH' ) ) die();

    if( empty( $post_id ) || !empty( $template_included ) )
    {
        return;
    }

    $template_included = 1;
?>
    <style type="text/css">
<?php

    if( !empty( $form_color_background ) )
    {

?>
    .hcc-embed-form-wrapper,
    .hcc-embed-form-wrapper .wizard,
    .hcc-embed-form-wrapper #payment,
    .hcc-embed-form-wrapper #payment .place-order,
    .hcc-embed-form-wrapper #order_review,
    .hcc-embed-form-wrapper #payment .payment_methods > li,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table.shop_table,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table th,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table td,
    .hcc-embed-modal-body {
        background: <?php echo esc_html( $form_color_background ); ?> !important;
    }

    .hcc-embed-form-wrapper .woocommerce .woocommerce-info {
        background: <?php echo esc_html( gb_hcc_adjust_color_brightness( $form_color_background, 10 ) ); ?> !important;
    }

    .hcc-embed-form-wrapper #payment .payment_methods > li .payment_box {
        background: <?php echo esc_html( gb_hcc_adjust_color_brightness( $form_color_background, -10 ) ); ?> !important;
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
    .hcc-embed-form-wrapper .woocommerce-billing-fields h3,
    .hcc-embed-form-wrapper .badge,
    .hcc-embed-form-wrapper .badge-text,
    .hcc-embed-form-wrapper #payment,
    .hcc-embed-form-wrapper .form-row,
    .hcc-embed-form-wrapper .woocommerce-privacy-policy-text,
    .hcc-embed-form-wrapper .woocommerce-privacy-policy-text p,
    .hcc-embed-form-wrapper .woocommerce-billing-fields .form-row label,
    .hcc-embed-form-wrapper .woocommerce-shipping-fields .form-row label,
    .hcc-embed-form-wrapper #order_review_heading,
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
    .hcc-embed-form-wrapper #payment .payment_methods > li,
    .hcc-embed-form-wrapper .woocommerce-checkout-review-order-table tr td,
    #payment .payment_methods > li .payment_box {
        background: <?php echo esc_html( $form_color_payment ); ?> !important;
    }

<?php

    }

    if( !empty( $form_color_button_1 ) )
    {
?>
    .hcc-embed-form-wrapper .wizard-button,
    .hcc-embed-form-wrapper .button.alt,
    .hcc-embed-form-wrapper .woocommerce .checkout_coupon .button {
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

</style>

<!-- Handsome Checkout Embed Forms for WooCommerce-->

<div class="hcc-embed-form-wrapper">
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
</div>
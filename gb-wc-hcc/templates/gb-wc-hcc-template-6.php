<?php 
    if( !defined( 'ABSPATH' ) ) die(); 

    // hide coupon field on checkout page

    function hide_coupon_field_on_checkout( $enabled )
    {
        if( is_checkout() )
        {
            $enabled = false;
        }

        return $enabled;
    }
    add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );

    // define the right mode (cart or order-pay)

    $cart_contents_count = sizeof( WC()->cart->cart_contents );

    $cart_items_mode = TRUE;

    if( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
    {
        global $wp;

        $order_id = $wp->query_vars['order-pay'];

        $order = wc_get_order( $order_id );

        if( !empty( $order ) && ! $order->is_paid() )
        {
            $cart_contents_count = $order->get_item_count();

            $cart_items_mode = FALSE;
        }
        else
        {
            wp_redirect( '/' );

            die();
        }
    }

?><!DOCTYPE html>
<html style="height: 100%;" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="noindex">

    <meta name="generator" content="Handsome Checkout for WooCommerce">

    <!-- (c) 2016-2020 Bogdan Grigoruk (bogdanfix@gmail.com) -->

    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11"/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/bootstrap.css', __FILE__ ); ?>">

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/grid.css', __FILE__ ); ?>">

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/font-awesome.css', __FILE__ ); ?>">

    <?php wp_head(); ?>

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/template-6.css', __FILE__ ); ?>">

    <style type="text/css">
        .wc_hcc_hide {
            display: none;
        }

        .wc-hcc-order-bump-checkbox input[type="checkbox"] {
            -webkit-appearance: checkbox;
        }

        .woocommerce-checkout #order_review_heading,
        .woocommerce-billing-fields > h3,
        .woocommerce-shipping-fields > h3 {
            clear: both;
            padding: 0 0 5px;
            margin: 0;
            font-size: 1.28571em;
            font-weight: 400;
            color: #333;
        }

        .woocommerce-additional-fields > h3 {
            display: none;
        }

        .woocommerce-message {
            display: none;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table {
            display: none;
        }

        #ship-to-different-address {
            display: none;
        }

        .wc-hcc-vq-options .vq-options-main-title {
            font-size: 1.28571em;
            line-height: normal !important;
            font-weight:400;
            border-bottom: 0;
            margin-bottom: 10px;
        }

        .btn-block.btn-step2, .btn-block.btn-step1{
            margin-left: 0;
            margin-right: 0;
        }

        .btn-block.btn-step2{
            margin-top: 20px;
            margin-bottom: 27px;
        }

        .link-back1{
            text-align: right;
            /*position: absolute;*/
            /*right: 0;*/
            /*bottom: 10em;*/
        }

        .wc-hcc-vq-options table td:nth-of-type(1){
            width: 15%;
        }

        .wc-hcc-vq-options table td:nth-of-type(2){
            width: 65%;
        }

        .wc-hcc-vq-options table td:nth-of-type(3){
            width: 20%;
        }

        .wc-hcc-vq-options table td .wc-hcc-field-label h4{
            font-size: 14px;
            margin: 0;
        }

        .wc-hcc-vq-options table td select{
            border: 1px solid #CCC;
            -webkit-appearance: menulist;
        }

        .wc-hcc-vq-option-highlight td{
            vertical-align: middle;
            padding: 10px;
            padding-left: 30px;
            position: relative;
        }

        .wc-hcc-vq-option-highlight td:before{
            position: absolute;
            bottom: 0;
            top: 0;
            left:0;
            right: -0%;
            content: '';
            background-color: #F6E100;
            font-weight: bold;
            border: 1px solid;
            z-index: 10;
        }

        .wc-hcc-vq-option-highlight td:nth-of-type(1):before{
            border-right:none;
            left: -1%;
        }

        .wc-hcc-vq-option-highlight td:nth-of-type(2):before{
            border-left:none;
            right: -2%;
        }

        .wc-hcc-vq-options-item label{
            position: relative;
            z-index: 11;
        }

        .wc-hcc-vq-option-highlight .wc-hcc-field-label{
            position: relative;
            z-index: 11;
        }

        .wc-hcc-vq-option-highlight{
            background-color: transparent !important;
            border:none !important;
        }

        .main__content{
            position: relative;
        }

        .link-back2{
            display: inline-block;
            /*position: absolute;*/
            /*bottom: 8em;*/
        }

        input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap{
            width:85%;
        }

        .wc-hcc-vq-options-item-wrap{
            padding-left: 0!important;
        }

        .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap{
            padding-left: 10px!important;
        }

        .simple_quantity_table  .wc-hcc-vq-options-item-wrap, .simple_variations_table  .wc-hcc-vq-options-item-wrap{
            margin-left: 15px;
        }

        .wc-hcc-vq-options-item{
            position: relative;
        }

        .wc-hcc-vq-options-item input.wc-hcc-vq-option-selection {
            position: absolute;
            margin: 0;
            top: 50%;
            margin-top: -6px;
        }

        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
            width: 60%;
        }

        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
            width: 40%;
        }

        .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
            width: 60%;
        }

        .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
            width: 40%;
        }

        @media (max-width: 991px) {

            .link-back2{
                position: relative;
                bottom: 0;
                width: 100%;
                text-align: right;
            }

            .link-back1{
                position: relative;
                bottom: 0;
                margin-top: 15px;
            }

            .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1), .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
                width:80%;
            }

            .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2),  .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
                width:20%;
            }
        }

        @media (max-width: 441px) {

            .wc-hcc-vq-options-item-wrap{
                width: 80%;
            }
        }

    <?php

        $fields_coupon = get_post_meta( $post->ID, 'hcc_fields_coupon', TRUE );

        if( empty( $fields_coupon ) )
        {

    ?>

        .order-summary__section--discount,
        .order-summary__section--product-list::after{
            display: none !important;
        }

        .order-summary__section--product-list {
            margin: 0 0 5px 0 !important;
        }

    <?php

        }

        if( gb_hcc_template_field( 'fields_shipping', FALSE ) )
        {

    ?>
        #ship-to-different-address {
            display: block;
            padding: 0 0 20px;
        }

        #ship-to-different-address label {
            font-size: 17px;
        }

    <?php

        }

        if( gb_hcc_template_field( 'order_details', FALSE ) ):

    ?>
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table {
            display: table;
            border-collapse: collapse;
            margin: auto;
            margin-bottom: 20px;
            width: 100%;
            background: #F5F5F5;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table td,
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th {
            padding: 10px;
            text-align: left;
            border: 1px solid #CCC;
            border-width: 0 0 1px 0;
            font-size: 15px;
        }
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th:nth-of-type(1){
            width: 30%;
        }
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th:nth-of-type(2){
            width: 70%;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th.product-name,
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th.product-total {
            width: 50%;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .cart_item td.product-name,
        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .cart_item td.product-total {
            font-size: 1em;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .shipping ul {
            margin: 0;
            padding: 0;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .shipping ul li {
            list-style: none;
            display: block;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .shipping ul li label {
            float: none;
            font-size: 1em;
            line-height: 20px;
        }

        .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .shipping ul li input {
            margin: 0.3em 0.5em 0.3em 0;
        }

    <?php

        endif;

    ?>
        .woocommerce-checkout .form-row .select2-container {
            display: none !important;
        }

        .woocommerce-checkout .woocommerce-checkout-subtitle {
            display: block;
            clear: both;
            color: #999;
            margin: 0;
            padding: 0 5px 20px;
        }

        .woocommerce-checkout .woocommerce-checkout-subtitle span {
            color: #F00;
        }

        .woocommerce-checkout form.login p:first-child,
        .woocommerce-checkout form.login p.lost_password {
            margin: 20px 15px;
        }

        .woocommerce-checkout .form-row,
        .checkout_coupon .form-row,
        .woocommerce-checkout .wc_payment_methods li.wc_payment_method .payment_box p {
            position: relative;
            min-height: 1px;
            margin: 0 0 20px;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method .payment_box p.form-row.form-row-wide {
            padding: 0.45em;
        }

        .woocommerce-billing-fields__field-wrapper,
        .woocommerce-shipping-fields__field-wrapper,
        .checkout_coupon {
            margin: -0.45em;
            zoom: 1;
        }

        .woocommerce-billing-fields__field-wrapper:before,
        .woocommerce-billing-fields__field-wrapper:after {
            display: table;
            content: '';
            clear: both;
        }

        .woocommerce-checkout .form-row.form-row-first,
        .woocommerce-checkout .form-row.form-row-last,
        .checkout_coupon .form-row.form-row-first,
        .checkout_coupon .form-row.form-row-last {
            width: 50%;
            float: left;
            padding: 0.45em;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .woocommerce-checkout .form-row,
        .checkout_coupon .form-row.form-row-wide {
            width: 50%;
            float: left;
            padding: 0.45em;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .woocommerce-checkout .form-row.terms,
        .woocommerce-checkout .form-row.place-order,
        .woocommerce-form-login p.form-row:not(.form-row-first):not(.form-row-last) {
            width: 100%;
            float: none;
            padding: 0;
        }

        .woocommerce-checkout .woocommerce-billing-fields .form-row > label,
        .woocommerce-checkout .woocommerce-shipping-fields .form-row > label,
        .woocommerce-checkout .woocommerce-additional-fields .form-row > label,
        .woocommerce-checkout .woocommerce-account-fields .form-row:not(.create-account) > label,
        .checkout_coupon .form-row > label {
            display: block;
            clear: both;
            color: #888;
            padding: 0 0 8px;

            position: absolute;
            top: 8px;
            left: 15px;
            font-size: 12px;
        }

        .woocommerce-checkout .woocommerce-billing-fields 
        .form-row > label.woocommerce-form__label-for-checkbox,
        .woocommerce-checkout .woocommerce-shipping-fields 
        .form-row > label.woocommerce-form__label-for-checkbox,
        .woocommerce-checkout .woocommerce-additional-fields 
        .form-row > label.woocommerce-form__label-for-checkbox,
        .woocommerce-checkout .woocommerce-account-fields 
        .form-row:not(.create-account) > label.woocommerce-form__label-for-checkbox {
            left: 25px;
        }

        .checkout_coupon .form-row label {
            left: 10px;
        }

        .woocommerce-checkout .form-row.wc-terms-and-conditions {
            margin: 0 5px;
        }

        .woocommerce-checkout .form-row.wc-terms-and-conditions label {
            width: 100%;
        }

        .woocommerce-checkout .form-row.wc-terms-and-conditions label input[type="checkbox"] {
            margin-top: 10px;
        }

        .woocommerce-privacy-policy-text {
            padding: 10px 15px;
        }

        .woocommerce-terms-and-conditions-wrapper .form-row {
            width: 100%;
            float: none;
            padding: 0 15px;
        }

        .woocommerce-terms-and-conditions-wrapper .form-row label {
            position: relative;
            font-size: 14px;
            left: 0;
        }

        .woocommerce-checkout .form-row input[type="text"],
        .woocommerce-checkout .form-row input[type="email"],
        .woocommerce-checkout .form-row input[type="password"],
        .woocommerce-checkout .form-row input[type="tel"],
        .woocommerce-checkout .form-row textarea,
        .checkout_coupon .form-row input[type="text"] {
            outline: none;
            border-radius: 4px;
            display: block;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: 100%;
            padding: 0.94em 0.8em;
            word-break: normal;
            transition: all 0.2s ease-out;
            box-shadow: 0 0 0 1px #d9d9d9;
        }

        .woocommerce-checkout .form-row input:focus,
        .woocommerce-checkout .form-row textarea:focus,
        .checkout_coupon .form-row input:focus {
            box-shadow: 0 0 0 2px #dd4b39;
        }

        .woocommerce-checkout form.login .button,
        .woocommerce-checkout form.checkout_coupon .button {
            display: block;
            clear: both;
            border: none;
            font-size: 16px;
            font-weight: bold;
            line-height: 19px;
            padding: 15px 12px 15px 12px;
            width: 99%;
            color: #fff;
            cursor: default;
            background: #c8c8c8;
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        .woocommerce-checkout form.login .button {
            margin-bottom: 10px;
            cursor: pointer;
        }

        .woocommerce-checkout .form-row select {
            outline: none;
            border-radius: 4px;
            display: block;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: 100%;
            padding: 0.94em 0.8em;
            word-break: normal;
            transition: all 0.2s ease-out;
            box-shadow: 0 0 0 1px #d9d9d9;
        }

        .woocommerce-checkout .form-row.form-row-error input,
        .woocommerce-checkout .form-row.form-row-error select {
            border: 1px solid #F00;
        }

        .woocommerce-checkout .form-row.form-row-error > label,
        .woocommerce-checkout .form-row.form-row-error > label {
            color: #F00;
        }

        .woocommerce-checkout .form-row .input-text.wc-credit-card-form-card-cvc {
            width: 100% !important;
        }

        .woocommerce-checkout .wc_payment_methods {
            padding: 0 0 20px 0;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method {
            list-style: none;
            clear: both;
            padding: 5px 0;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method fieldset {
            padding: 10px;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method:only-child input[type="radio"] {
            display: none;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method > input[type="radio"] {
            margin: 0 0 0 0.4em;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method > label {
            display: inline;
            padding: 5px;
            margin: 0;
            font-size: 16px;
            vertical-align: middle;
            cursor: pointer;
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method > label img {
            vertical-align: middle;
            padding-left: 5px;
            max-height: 50px;
            float: none !important;
        }

        @media only screen and (max-width: 600px) {
            .woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span {
                display: block;
                padding: 10px 0;
            }

            .woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span img { /*width: 42px;*/
            }

            .woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span img:first-child {
                padding-left: 0;
            }
        }

        .woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box {
            margin: 20px 5px;
        }

        .woocommerce-checkout .wc-saved-payment-methods {
            margin-left: 10px;
        }

        .woocommerce-checkout .woocommerce-info {
            display: block;
            background: #eefdff;
            padding: 15px 16px;
            margin: 0 0 10px 0;
            list-style: none;
            font-size: 13px;
        }

        .woocommerce-checkout .woocommerce-message {
            display: block;
            background: #fff5d1;
            padding: 15px 16px;
            margin: 0 0 10px 0;
            list-style: none;
            font-size: 13px;
        }

        .woocommerce-checkout .woocommerce-error {
            display: block;
            background: #FEE;
            padding: 15px;
            margin: 0 5px 20px;
            list-style: none;
        }

        .woocommerce-checkout .woocommerce-checkout-price {
            display: block;
            clear: both;
            text-align: left;
            background-color: #e9e9e9;
            padding: 15px;
            color: #000;
            font-size: 23px;
        }

        .woocommerce-checkout .woocommerce-checkout-price .woocommerce-checkout-total-label {
            float: left;
        }

        .woocommerce-checkout .woocommerce-checkout-price .woocommerce-checkout-total-price {
            float: right;
            text-align: right;
        }

        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_variations select {
            -webkit-appearance: menulist;
        }

        .woocommerce-checkout .form-row #place_order,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept {
            display: inline-block;
            width: 100%;
            border-radius: 4px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: background 0.2s ease-in-out, color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, -webkit-box-shadow 0.2s ease-in-out;
            font-weight: 600;
            padding: 1.4em 1.7em;
            background: #6cc24a;
            color: white;
            margin-top: 1.5em;
        }

        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept {
            width: 60%;
        }

        .woocommerce-checkout .form-row #place_order:hover,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
            background: #55a237;
            color: white;
        }

        @media screen and (min-width: 750px) {
            .woocommerce-checkout .form-row #place_order {
                float: right;
                width: auto;
            }
        }

        .woocommerce-checkout .form-row #place_order:active,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:active,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:active {
            background: #67a715;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .3)
        }

        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_reject,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_reject {
            display: block;
            text-align: center;
            font-size: 1.1em;
            text-transform: none;
            text-decoration: underline;
        }

        .sv-wc-payment-gateway-payment-form-manage-payment-methods {
            display: block;
            margin-bottom: 1em;
        }

        .js-sv-wc-payment-gateway-payment-token {
            width: auto;
            margin: 0.5em 0 1em 0;
            display: inline-block;
        }

        .form-row label.sv-wc-payment-gateway-payment-form-saved-payment-method {
            display: inline-block;
            margin: 0.5em 0 1em 0.5em;
            vertical-align: middle;
        }

        input.js-sv-wc-tokenize-payment {
            width: auto;
            vertical-align: middle;
            margin: 0 5px 0 0;
        }

    /* Customizable */

    <?php

        if( gb_hcc_template_field( 'color_header', FALSE ) )
        {

    ?>

        .about-product div hgroup h1 {
            color: <?php gb_hcc_template_field('color_header'); ?>;
        }

        .about-product div hgroup h2 {
            color: <?php gb_hcc_template_field('color_header'); ?>;
        }

    <?php

        }

        if( gb_hcc_template_field( 'color_footer', false ) )
        {

    ?>

        .footer-decoration .middle-container div p ,
        .footer a{
            color: <?php gb_hcc_template_field('color_footer'); ?> !important;
        }

    <?php

        }

        if( gb_hcc_template_field( 'color_bg', false ) )
        {
    ?>

        body {
            background: <?php gb_hcc_template_field('color_bg'); ?>;
        }

    <?php

        }

        if( gb_hcc_template_field( 'color_headline', false ) )
        {

    ?>

        .bullet-points .list-title,
        .testimonials .title,
        .trust-point-title,
        .hcc-order-summary-toggle__icon,
        .hcc-order-summary-toggle__text,
        .contact-info,
        .customer-info,
        .woocommerce-billing-fields h3:first-of-type,
        #order_review_heading, .wc-hcc-vq-options .vq-options-main-title,
        #payment h2:first-of-type {
            color: <?php gb_hcc_template_field('color_headline'); ?> !important;
            fill: <?php gb_hcc_template_field('color_headline'); ?> !important;
        }

    <?php

        }

        if( gb_hcc_template_field( 'color_button_1', false ) )
        {

    ?>
        a:not(.footer-link) {
            color: <?php gb_hcc_template_field('color_button_1'); ?>;
        }
        .input-radio,
        .shipping_method {
            border: 1px solid <?php gb_hcc_template_field('color_button_1'); ?> !important;
        }
        .input-radio:checked,
        .shipping_method:checked {
            -webkit-box-shadow: 0 0 0 10px <?php gb_hcc_template_field('color_button_1'); ?> inset;
            box-shadow: 0 0 0 10px <?php gb_hcc_template_field('color_button_1'); ?> inset;
        }
        .woocommerce-checkout .form-row #place_order,
        .woocommerce-checkout .form-row #place_order:hover,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover,
        .wizard-button,
        .button {
            background: <?php gb_hcc_template_field('color_button_1'); ?>;
    <?php

        if( gb_hcc_template_field( 'color_button_2', false ) )
        {

    ?>
            background: -moz-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0, <?php gb_hcc_template_field('color_button_1'); ?>), color-stop(100%, <?php gb_hcc_template_field('color_button_2'); ?>));
            background: -webkit-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -o-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -ms-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: linear-gradient(to bottom, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php gb_hcc_template_field('color_button_1'); ?>', endColorstr='<?php gb_hcc_template_field('color_button_2'); ?>', GradientType=0);
    <?php

        }

    ?>

        }

        .woocommerce-checkout .form-row #place_order:hover,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover,
        .wizard-button:hover,
        .button:hover {
            opacity: 0.85;
        }

        .woocommerce-checkout .form-row #place_order:active,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:active,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:active,
        .wizard-button:active,
        .button:active {
            background: <?php gb_hcc_template_field('color_button_1'); ?>;
        }

    <?php

        }

        if( gb_hcc_template_field( 'marketplace_image_align', false ) )
        {

    ?>
        .main__header {
            text-align: <?php gb_hcc_template_field('marketplace_image_align'); ?>;
        }

    <?php

        }

    ?>

        /* GB WC 1CU */

        .upsell-container {
            padding: 20px;
        }

        .wc_1cu_default_offer_title h2 {
            font-size: 1.5em;
            text-align: left;
        }

        .wc_1cu_default_offer_desc {
            line-height: 1.4em;
        }

        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn, .page .wc_1cu_custom_offer_btn {
            text-align: center;
        }

        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_price, .page .wc_1cu_default_offer_page .wc_1cu_default_offer_variation_price {
            font-size: 1.6em !important;
            color: #000 !important;
        }

        /* PRELOADER */

        .woocommerce .processing .blockUI.blockOverlay{
            position:fixed !important;
            opacity: 0.8 !important;
        }
        .woocommerce .processing .blockUI.blockOverlay::before {
            position: absolute;
            top: 50% !important;
            background-size: 100px;
            background-image: url('<?php echo esc_url( plugins_url( 'assets/images/preloader.gif', __FILE__ ) ); ?>') !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            width: 100% !important;
            height: 100px !important;
            left:auto !important;
            content: '';
        }
        .woocommerce .processing .blockUI.blockMsg {
            top: 40% !important;
            position: fixed !important;
            display:block !important;
            background-color: none !important;
            width: 100%;
            text-align: center;
            left: 0 !important
        }
        .woocommerce .processing .blockUI.blockMsg::before {
            content: '<?php esc_html_e( 'Please wait. We are processing your payment…', 'gb-wc-hcc' ); ?>';
            text-align:center !important;
            z-index: 9999;
        }

    <?php 

        if( !$cart_items_mode )
        {

    ?>

        .shop_table {
            margin-bottom: 2em;
        }

        .shop_table td,
        .shop_table th {
            padding: 1em;
            background: #FAFAFA;
        }

        .btn-step1,
        .btn-step2, 
        .link-back1,
        .link-back2 {
            display: none !important;
        }

        .woocommerce-checkout #payment > .form-row {
            width: 100%;
            float: none;
            padding: 0;
        }

        @media (max-width: 749px) {

            .sidebar-content {
                margin-top: 180px;
            }
        }

    <?php

        }

    ?>

    </style>
</head>
<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>
<button id="hcc-order-summary-toggle" class="hcc-order-summary-toggle hcc-order-summary-toggle--show">
    <div class="wrap">
        <div class="hcc-order-summary-toggle__inner">
            <div class="hcc-order-summary-toggle__icon-wrapper">
                <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="hcc-order-summary-toggle__icon">
                    <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
                </svg>
            </div>
            <div class="hcc-order-summary-toggle__text hcc-order-summary-toggle__text--show">
                <span><?php esc_html_e( 'Show order summary', 'gb-wc-hcc' ); ?></span>
                <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </div>
            <div class="hcc-order-summary-toggle__total-recap hcc-total-recap">
                <span class="hcc-total-recap__final-price"><?php 

                    if( $cart_items_mode )
                    {
                        echo WC()->cart->get_cart_total();
                    }
                    else
                    {
                        echo wc_price( $order->get_total() );
                    }

                ?></span>
            </div>
        </div>
    </div>
</button>
<div class="content">
    <div class="wrap" id="wrap">
        <div class="sidebar col-md-4">
            <div class="row">
                <div class="sidebar__content">
                    <div class="order-summary order-summary--is-collapsed">
                        <div class="order-summary__sections">
                            <div class="order-summary__section order-summary__section--product-list">
                                <div class="order-summary__section__content">

                                <?php

                                    if( $cart_contents_count )
                                    {
                                        if( $cart_items_mode )
                                        {
                                            $items = WC()->cart->get_cart();

                                            $subtotal = WC()->cart->get_cart_subtotal();
                                            $coupons = FALSE;
                                            $needs_shipping = FALSE;
                                            $shipping_total = FALSE;
                                            $cart_tax = FALSE;
                                            $total = wc_price( WC()->cart->total );
                                        }
                                        else
                                        {
                                            $items = $order->get_items();

                                            $subtotal = wc_price( $order->get_subtotal() );
                                            $coupons = $order->get_coupons();
                                            $needs_shipping = $order->needs_shipping_address();
                                            $shipping_total = wc_price( $order->get_shipping_total() );
                                            $cart_tax = wc_price( $order->get_cart_tax() );
                                            $total = wc_price( $order->get_total() );
                                        }

                                        foreach( $items as $item_key => $item )
                                        {
                                            $_product_id = $item['product_id'];

                                            if( !empty( $item['variation_id'] ) )
                                            {
                                                $_product_id = $item['variation_id'];
                                            }

                                            $currency = get_woocommerce_currency_symbol();
                                            // $tax      = get_post_meta( $_product_id, 'line_tax', TRUE );

                                            $item_visible = FALSE;
                                            $price = wc_price(0);

                                            if( $cart_items_mode )
                                            {
                                                $_product = apply_filters( 
                                                        'woocommerce_cart_item_product', 
                                                        $item['data'], 
                                                        $item, 
                                                        $item_key 
                                                    );

                                                $item_visible = apply_filters( 
                                                        'woocommerce_checkout_cart_item_visible', 
                                                        TRUE, 
                                                        $item, 
                                                        $item_key 
                                                    );

                                                $price = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $item['quantity'] ), $item, $item_key );
                                            }
                                            else
                                            {
                                                $_product = $item->get_product();

                                                if( !empty( $_product ) )
                                                {
                                                    $item_visible = $_product->is_visible();

                                                    $price = wc_price( get_post_meta( $_product_id, '_price', TRUE ) );
                                                }
                                                else
                                                {
                                                    $item_visible = TRUE;

                                                    $price = wc_price( $item->get_total() );
                                                    // $tax = $item->get_total_tax();
                                                }
                                            }

                                            if(
                                                // $_product && 
                                                // $_product->exists() && 
                                                !empty( $item['quantity'] ) // && 
                                                // $item_visible
                                            )
                                            {
                                ?>

                                            <table class="product-table">
                                                <tbody data-order-summary-section="line-items" class="line-items-changed">
                                                <tr class="product <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', '', $item, $item_key ) ); ?>">
                                                    <td class="product__image">
                                                        <div class="product-thumbnail">
                                                            <div class="product-thumbnail__wrapper">

                                                                <?php

                                                                    if( !empty( $_product ) )
                                                                    {
                                                                        echo $_product->get_image( 'thumbnail' );
                                                                    }
                                                                    else
                                                                    {
                                                                        echo wc_placeholder_img();
                                                                    }

                                                                ?>

                                                            </div>
                                                            <span class="product-thumbnail__quantity" aria-hidden="true"><?php echo esc_html( $item['quantity'] ) ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="product__description">
                                                        <span class="product__description__name order-summary__emphasis">
                                                            <?php 

                                                                if( !empty( $_product ) )
                                                                {
                                                                    echo esc_html( $_product->get_name() );
                                                                }
                                                                else
                                                                {
                                                                    echo esc_html( $item['name'] );
                                                                }
                                                            ?>
                                                        </span>

                                                    <?php

                                                        if(
                                                            !empty( $_product ) && 
                                                            $_product instanceof WC_Product_Variation 
                                                        )
                                                        {
                                                            $variables = $_product->get_attributes();
                                                    ?>

                                                            <p class="hcc-product-variables"><?php echo esc_html( ucfirst( implode( ", ", $variables ) ) ); ?></p>

                                                    <?php

                                                        }

                                                    ?>

                                                    </td>
                                                    <td class="product__price">
                                                        <span class="order-summary__emphasis"><?php echo $price; ?></span>
                                                    </td>
                                                </tr>

                                    <?php
                                            }
                                        }

                                    ?>
                                                </tbody>
                                            </table>
                                <?php

                                    }
                                    else
                                    {

                                ?>

                                        <style>
                                            .order-summary__sections {
                                                display: none;
                                            }

                                            .order-summary__section--product-list {
                                                display: none;
                                            }

                                            .checkout-input-container {
                                                font-size: 20px;
                                                margin-bottom: 20px;
                                            }

                                            .checkout-shop-back-btn:hover {
                                                text-decoration: none;
                                            }
                                        </style>

                                <?php

                                    }

                                ?>
                                    <div class="order-summary__scroll-indicator">
                                        <span><?php esc_html_e( 'Scroll for more items', 'gb-wc-hcc' ); ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="12" viewBox="0 0 10 12">
                                            <path d="M9.817 7.624l-4.375 4.2c-.245.235-.64.235-.884 0l-4.375-4.2c-.244-.234-.244-.614 0-.848.245-.235.64-.235.884 0L4.375 9.95V.6c0-.332.28-.6.625-.6s.625.268.625.6v9.35l3.308-3.174c.122-.117.282-.176.442-.176.16 0 .32.06.442.176.244.234.244.614 0 .848"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="order-summary__section order-summary__section--discount">

                            <?php

                                if( $cart_contents_count )
                                {

                            ?>
                                    <form class="checkout_coupon" method="post" style="display: block !important;">

                                        <p class="form-row form-row-first">
                                            <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
                                            <label for="coupon_code"><?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?></label>
                                        </p>

                                        <p class="form-row form-row-last">
                                            <input type="submit" class="button" name="apply_coupon" value="<?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?>">
                                        </p>

                                        <div class="clear"></div>

                                    </form>
                            <?php

                                }

                            ?>

                            </div>
                            <div class="order-summary__section order-summary__section--total-lines" data-order-summary-section="payment-lines">

                            <?php

                                if( $cart_contents_count )
                                {

                            ?>
                                    <table class="total-line-table">
                                        <tbody class="total-line-table__tbody">
                                        <tr class="total-line total-line--subtotal">
                                            <td class="total-line__name"><?php esc_html_e( 'Subtotal', 'gb-wc-hcc' ); ?></td>
                                            <td class="total-line__price hcc-subtotal-price">
                                                <span class="order-summary__emphasis"><?php echo $subtotal; ?></span>
                                            </td>
                                        </tr>

                                        <?php

                                            if( $coupons )
                                            {
                                                foreach( $coupons as $code => $coupon )
                                                {
                                                    $discount_amount_html = $coupon_html = $coupon_label = '';

                                                    if( $cart_items_mode )
                                                    {
                                                        $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );

                                                        $coupon_label = wc_cart_totals_coupon_label( $coupon, FALSE );
                                                    }
                                                    else
                                                    {
                                                        $amount = $coupon->get_discount(); // get_discount_tax();

                                                        $coupon_label = __( 'Coupon:', 'woocommerce' ) . ' ' . $coupon->get_code();
                                                    }

                                                    if( $amount )
                                                    {
                                                        $discount_amount_html = '-' . wc_price( $amount );

                                                        if( $cart_items_mode )
                                                        {
                                                            $coupon_html          = ' <a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . __( '[Remove]', 'woocommerce' ) . '</a>';
                                                        }
                                                    }
                                                    elseif( $coupon->get_free_shipping() )
                                                    {
                                                        $discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
                                                    }

                                        ?>

                                                <tr class="total-line total-line--coupon">
                                                    <td class="total-line__name"><?php echo $coupon_label; ?></td>
                                                    <td class="total-line__price hcc-coupon-price">
                                                        <span class="order-summary__emphasis"><?php echo $discount_amount_html; ?></span>
                                                        <span><?php echo $coupon_html; ?></span>
                                                    </td>
                                                </tr>

                                        <?php

                                                }
                                            }
                                            else
                                            {

                                        ?>
                                                <tr class="total-line total-line--coupon"></tr>

                                        <?php

                                            }

                                            if( $needs_shipping )
                                            {

                                        ?>
                                                <tr class="total-line total-line--shipping">
                                                    <td class="total-line__name"><?php esc_html_e( 'Shipping', 'gb-wc-hcc' ); ?></td>
                                                    <td class="total-line__price hcc-shipping-price">
                                                        <span class="order-summary__emphasis">
                                                            <?php echo $shipping_total; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                        <?php

                                            }
                                            else
                                            {

                                        ?>
                                                <tr class="total-line total-line--shipping"></tr>
                                        <?php

                                            }

                                            if( $cart_tax )
                                            {

                                        ?>      
                                                <tr class="total-line total-line--tax">
                                                    <td class="total-line__name"><?php esc_html_e( 'Tax', 'gb-wc-hcc' ); ?></td>
                                                    <td class="total-line__price hcc-tax-price">
                                                        <span class="order-summary__emphasis"><?php echo $cart_tax; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                        <?php

                                            }
                                            else
                                            {

                                        ?>

                                                <tr class="total-line total-line--tax"></tr>

                                        <?php

                                            }
                                        
                                        ?>
                                        </tbody>
                                        <tfoot class="total-line-table__footer">
                                        <tr class="total-line">
                                            <td class="total-line__name payment-due-label">
                                                <span class="payment-due-label__total"><?php esc_html_e( 'Total', 'gb-wc-hcc' ); ?></span>
                                            </td>
                                            <td class="total-line__price payment-due hcc-total-price">
                                                <span class="payment-due__price"><?php echo $total; ?></span>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>

                            <?php

                                }

                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="sidebar-content">
                <div class="sidebar-content">

                    <?php

                    if( $cart_contents_count )
                    {
                        if ( gb_hcc_template_field( 'bullet_points', false, '[bullet_text]' ) ):

                    ?>
                            <div class="bullet-points">

                                <h2 class="list-title"><?php gb_hcc_template_field( 'bullet_points_title' ); ?></h2>

                                <ul>
                                <?php

                                    gb_hcc_template_field(
                                        'bullet_points',
                                        true,
                                        '<li><i class="fa fa-check pull-left"></i><div class="bullet-text">[bullet_text]</div></li>'
                                    );

                                ?>
                                </ul>

                            </div>

                        <?php

                        endif;

                        if( gb_hcc_template_field( 'testimonials', false, '[testimonial_text]' ) ):

                        ?>

                            <div class="testimonials">

                                <h2 class="title"><?php gb_hcc_template_field( 'testimonials_title' ); ?></h2>

                            <?php

                                gb_hcc_template_field(
                                    'testimonials',
                                    true,
                                    '   <div class="testimonial-item">
                                                <div class="testimonial-image">[testimonial_image]</div>
                                                <p class="testimonial-text">[testimonial_text]</p>
                                                <div class="clear"></div>
                                            </div>'
                                );

                            ?>

                            </div>

                        <?php

                        endif;

                        ?>

                        <div class="trust-point-wrap">

                        <?php

                            gb_hcc_template_field(
                                'trustpoints',
                                true,
                                '   <hr>
                                <div class="trust-point trust-point-guarantee">
                                    <div class="trust-point-image">[trustpoint_image]</div>
                                    <div class="trust-point-title">[trustpoint_title]</div>
                                    <div class="trust-point-text">[trustpoint_text]</div>
                                </div>'
                            );

                        ?>

                        </div>

                <?php

                    }

                ?>

                </div>
            </div>
        </div>
        <div class="main col-md-8">
            <div class="main__header">
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" target="_blank"><?php gb_hcc_template_field( 'marketplace_image' ); ?></a>
            </div>
            <div class="main__content">

            <?php

                if( $cart_contents_count )
                {

            ?>

                    <div class="wizard">
                        <ul class="steps breadcrumb">
                            <li class="step1 breadcrumb__item--current">
                                <span class="breadcrumb__text"><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></span>
                                <i class="fa fa-chevron-right" aria-hidden="true" style="font-size: 10px"></i>
                            </li>
                            <?php
                            $vq_options = get_post_meta( $post->ID, 'hcc_vq_options', TRUE );

                            if( !empty( $vq_options ) ) {
                            ?>
                                <li class="step2 breadcrumb__item">
                                    <span class="breadcrumb__text"><?php esc_html_e( 'Product Selection', 'gb-wc-hcc' ); ?></span>
                                    <i class="fa fa-chevron-right" aria-hidden="true" style="font-size: 10px"></i>
                                </li>
                            <?php
                            }
                            ?>
                            <li class="step3 breadcrumb__item">
                                <span class="breadcrumb__text"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></span>
                            </li>
                        </ul>
                    </div>
            <?php

                }

            ?>
                <div class="step">

                <?php

                    if( 
                        gb_hcc_template_get_post_type() == 'handsome-checkout' || 
                        ( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
                    )
                    {
                        if ( !empty( $_GET['1cu'] ) && !empty( $_GET['1cu_s'] ) && !empty( $_GET['1cu_n'] ) )
                        {
                ?>
                            <style type="text/css">
                            .wizard,
                            .wizard-button,
                            .sidebar,
                            .footer,
                            .checkout-shop-back-btn {
                                display: none !important;
                            }

                            .main.col-md-8,
                            .wc_1cu_default_offer_accept {
                                width: 100% !important;
                            }
                            </style>

                            <div class="upsell-container"> <?php echo do_shortcode( '[wc_1cu_default_page]' ); ?> </div>
                <?php

                        }
                        else
                        {

                ?>
                            <div class="checkout-forms">
                                <div class="checkout-input-container">
                                    <!-- CHECKOUT FORM -->
                                <?php

                                    $checkout_code = do_shortcode( '[woocommerce_checkout]' );

                                    if( empty( $checkout_code ) || trim( $checkout_code ) == '<div class="woocommerce"></div>' )
                                    {
                                        esc_html_e( 'Oops, there are no products in your cart.', 'gb-wc-hcc' );
                                    }
                                    else
                                    {
                                        echo $checkout_code;
                                    }

                                ?>
                                    <!-- END CHECKOUT FORM -->
                                </div>
                            </div>
                <?php

                        }
                    }

                    if( !$cart_contents_count )
                    {
                ?>

                        <a class="btn button wc-backward checkout-shop-back-btn"
                           href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"> <?php esc_html_e( 'Return to shop', 'woocommerce' ) ?> </a>

                <?php

                    }

                ?>
                </div>
                <?php

                if( $cart_contents_count )
                {

                ?>

                    <button class="btn btn-success btn-lg wizard-button btn-block btn-step1">

                    <?php

                        if( gb_hcc_template_field( 'button_text_step1', false ) )
                        {
                            echo gb_hcc_template_field( 'button_text_step1' );
                        }
                        else
                        {
                            esc_html_e( 'Next Step', 'gb-wc-hcc' );
                        }

                    ?>

                    </button>
                    <button class="btn btn-success btn-lg wizard-button btn-block btn-step2">

                    <?php

                        if( gb_hcc_template_field( 'button_text_step1', false ) )
                        {
                            echo gb_hcc_template_field( 'button_text_step1' );
                        }
                        else
                        {
                            esc_html_e( 'Next Step', 'gb-wc-hcc' );
                        }

                    ?>

                    </button>
                    <a href="#" class="link-back1" style="display: inline-block;"><?php esc_html_e( '< back to the previous step', 'gb-wc-hcc' ); ?></a>
                    <a href="#" class="link-back2" style="display: inline-block;"><?php esc_html_e( '< back to the previous step', 'gb-wc-hcc' ); ?></a>
                <?php

                }

                ?>
            </div>
        </div>
        <footer class="footer">
            <div><?php gb_hcc_template_field( 'custom_html_footer' ); ?></div>
        </footer>
        <script>
          jQuery(document).ready(function() {

            jQuery('#order_review').find('.woocommerce-checkout-payment').hide();

          });
        </script>
    </div>
</div>

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide"><?php wp_footer(); ?></div>

</body>
</html>
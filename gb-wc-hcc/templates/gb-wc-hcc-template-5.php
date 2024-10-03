<?php if( !defined( 'ABSPATH' ) ) die(); ?><!DOCTYPE html>
<html style="height: 100%;" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="robots" content="noindex">

    <meta name="generator" content="Handsome Checkout for WooCommerce">

    <!-- (c) 2016-2020 Bogdan Grigoruk (bogdanfix@gmail.com) -->

    <title><?php wp_title('|', true, 'right'); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/bootstrap.css', __FILE__ ); ?>">

    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/font-awesome.min.css', __FILE__); ?>">

    <?php wp_head(); ?>

    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/template-5.css', __FILE__); ?>">

    <style type="text/css">
    .wc_hcc_hide {
        display: none;
    }

    .woocommerce-additional-fields > h3 {
        display: none;
    }

    .woocommerce-checkout form.login p {
        margin: 10px 0;
    }

    form.login {
        margin-bottom: 10px;
    }

    form.login button[type="submit"],
    form.login button[type="submit"]:focus {
        background: #6bb327;
        color: #FFF;
    }

    form.woocommerce-checkout h3 {
        border-bottom: solid 1px #D8D8D8;
        font-weight: 500;
    }

    .woocommerce-checkout-subtitle {
        display: block;
        color: #777;
        font-size: 13px;
        font-weight: 400;
        font-style: italic;
        line-height: 16px;
        margin-bottom: 15px;
    }

    .form-row {
        display: block;
        clear: both;
        margin: 1.1em auto;
    }

    .form-row label {
        display: none;
        font-size: 18px;
    }

    .form-row.wc-terms-and-conditions label {
        display: block;
    }

    .woocommerce-privacy-policy-text {
        padding: 10px 0 0;
    }

    .woocommerce-terms-and-conditions-wrapper .form-row label {
        display: block;
        font-size: 14px;
    }

    .form-row input[type="text"],
    .form-row input[type="email"],
    .form-row input[type="tel"],
    .form-row input[type="password"],
    .form-row textarea,
    .form-row select {
        outline: none;
        padding: 4px 6px;
        border: 1px solid #DDDDDD;
        display: block;
        width: 100%;
        height: 30px;
        font-size: 14px;
        font-weight: normal;
        -webkit-transition: all 0.30s
    }

    .form-row.woocommerce-invalid input,
    .form-row.woocommerce-invalid-required-field input {
        border-color: #f00;
    }

    .checkout_coupon {
        display: flex;
        clear: both;
        background: #f6f6f6;
        padding: 20px;
        margin: 10px 0;
    }

    .checkout_coupon .form-row {
        display: flex;
        margin: 0;
        clear: none;
    }

    .checkout_coupon .form-row-first {
        flex: 1 1 auto;
        margin-right: 10px;
    }

    .checkout_coupon .form-row-last {
        flex: 0 0 auto;
    }

    .checkout_coupon .form-row input[type=text] {
        float: none;
        font-size: 14px;
        line-height: 45px;
    }

    .checkout_coupon p:not(.form-row):first-child {
        display: none;
    }

    form.login .button,
    input[name=apply_coupon],
    button[name=apply_coupon] {
        display: inline-block;
        background: #5bb75b;
        color: #fff;
        line-height: 18px;
        border: 0;
        border-radius: 4px;
        transition: all 0.2s;
        vertical-align: middle;
        padding: 8px 15px;
        height: auto;
        text-align: center;
        text-decoration: none;
        text-transform: none;
        font-size: 16px;
        width: 100%;
        cursor: pointer;
        float: none;
    }

    #coupon_code {
        color: #000000;
        font-weight: bold;
        padding: 0 10px;
        height: 40px;
        border: 1px dashed #d9e0e9;
        vertical-align: middle;
        display: inline-block;
        width: 100%;
        margin: 0;
    }
    #place_order {
        display: block;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 0;
        padding: 12px;
        line-height: 20px;
        text-align: center;
        font-size: 20px;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        color: #fff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #5bb75b;
        background-image: -moz-linear-gradient(top, #62c462, #51a351);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
        background-image: -webkit-linear-gradient(top, #62c462, #51a351);
        background-image: -o-linear-gradient(top, #62c462, #51a351);
        background-image: linear-gradient(to bottom, #62c462, #51a351);
        background-repeat: repeat-x;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
        filter: progid: DXImageTransform.Microsoft.gradient(enabled=false);
    }
    #place_order:active {
        background-image: none;
        outline: 0;
        -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 359px) {

        .checkout_coupon {
            flex-direction: column;
        }

        .checkout_coupon .form-row-first {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }

    .woocommerce-checkout-payment {
        background: #FFF;
    }

    .shop_table {
        display: none;
        width: 100%;
        border: 1px solid #dcd9d6;
        border-bottom: 0;
        background: #EEE;
    }
    .shop_table thead {
        border-bottom: 1px solid #dcd9d6;
        font-size: 15px;
        font-weight: 600;
    }
    .shop_table thead,
    .shop_table tbody,
    .shop_table tfoot,
    .shop_table tr {
        width: 100%;
        display: block;
        position: relative;
    }
    .shop_table thead:after,
    .shop_table tbody:after,
    .shop_table tfoot:after,
    .shop_table tr:after {
        content: "";
        display: table;
        clear: both;
    }
    .shop_table th,
    .shop_table td {
        width: 50%;
        display: block;
        float: left;
        text-align: left;
        padding: 3px 15px;
    }
    .shop_table tr {
        border-bottom: 1px solid #dcd9d6;
    }
    .shop_table .order-total {
        border-bottom: 1px solid #dcd9d6;
    }
    .shop_table .product-total {
        text-align: right;
    }
    .woocommerce-Price-amount.amount {
        width: 100%;
        text-align: right;
        display: block;
    }
    .shop_table #shipping_method .woocommerce-Price-amount.amount {
        width: auto;
        text-align: left;
        display: inline;
    }
    .shop_table #shipping_method label {
        display: inline;
        color: #000;
    }
    .wc_payment_methods {
        padding: 15px;
        border:1px solid #dcd9d6;
        margin: 0;
        list-style:none;
    }
    .wc-payment-form {
        border:0;
        margin: 0;
        padding: 0;
    }
    .wc-payment-form .form-row-wide {
        font-size: 16px;
    }
    .wc_payment_methods input[type=radio] {
        margin: 0;
    }
    .wc_payment_method {
        padding: 5px 0;
    }
    .wc_payment_method > label {
        display: inline;
        padding-left: 5px;
    }
    .wc_payment_method label img {
        vertical-align: middle;
        margin-right: 5px;
        max-height: 50px;
        float: none !important;
    }
    .wc_payment_method > label > span {
        display: block;
        margin-top: 10px;
    }
    .wc_payment_method > label > span img {
        /*width: 40px;*/
        margin: 0 5px;
    }
    .wc_payment_method > label > span img:first-child {
        margin-left: 0;
    }
    .wc_payment_method > label > span img:last-child {
        margin-right: 0;
    }
    .wc_payment_method > label > span img[src*="paypal"] {
        height: 50px;
        width: auto;
    }
    .wc_payment_method .payment_box p.form-row:first-child,
    .wc_payment_method .payment_box p {
        margin: 10px 0;
    }
    .wc_payment_method .payment_box p label {
        display: inline;
        padding: 0;
    }
    .wc-saved-payment-methods li label {
        display: inline;
    }
    .form-row.woocommerce-SavedPaymentMethods-saveNew label {
        font-size: 14px;
    }
    .woocommerce-checkout-total-price,
    .woocommerce-checkout-total-label {
        display: inline-block;
    }
    .woocommerce-checkout-total-price {
        float: right;
    }
    .woocommerce-checkout-price {
        padding: 15px;
        margin-bottom: 15px;
        border:1px solid #dcd9d6;
        background: #f6f6f6;
        font-weight: 600;
    }
    .woocommerce-info {
        background: #f1f9ff;
        padding: 10px;
        margin-bottom: 10px;
    }
    .woocommerce-message {
        background: #fff5d1;
        padding: 10px;
        margin-bottom: 10px;
    }
    .woocommerce-error {
        background: #ffdfdf;
        padding: 10px;
        margin-bottom: 10px;
    }

    .place-order {
        margin: 0;
    }
    .marketplace-image {
        display: block;
        width: 100%;
        height: auto;
        max-width: 300px;
        max-height: 75px;
        margin: 5px 0;
    }

    @media (min-width: 550px) {

        h3 {
            font-size: 2.4rem;
        }
    }

    @media (max-width: 499px) {

        .form-row label {
            display: block;
            margin-bottom: 10px;
        }

        .form-row input[type="text"],
        .form-row input[type="email"],
        .form-row input[type="tel"],
        .form-row input[type="password"],
        .form-row textarea,
        .form-row select {
            float: none;
            width: 100%;
        }
    }

    #ship-to-different-address {
        display: none;
    }
    .wc-hcc-vq-options .vq-options-main-title{
        color: #7543f2;
        font-size: 24.5px;
        border-bottom: 0;
        margin-bottom: 0;
    }
    .wc-hcc-vq-options table td.wc-hcc-vq-options-header h4{
        font-size: 14px;
        margin: 0;
    }
    .wc-hcc-vq-options table td{
        vertical-align: middle;
    }
    .wc-hcc-vq-options table td select{
        margin: 0;
    }
    input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap{
        width:85%;
    }
    .wc-hcc-vq-options table td:nth-of-type(1){
        width: 20%;
    }
    .wc-hcc-vq-options table td:nth-of-type(2){
        width: 50%;
    }
    .wc-hcc-vq-options table td:nth-of-type(3){
        width: 30%;
    }
    .wc-hcc-vq-options table td select{
        width: 80%;
    }
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
        width: 60%;
        padding-left: 8px;
    }
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
        width: 40%;
    }
    .wc-hcc-vq-option-highlight .wc-hcc-vq-options-item{
        position: relative;
    }

    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
        width: 60%;
    }
    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
        width: 40%;
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
        left: -2%;
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
    .wc-hcc-vq-options-item-wrap{
        padding-left: 0 !important;
    }
    .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap {
        padding-left: 10px !important;
    }
    .simple_quantity_table  .wc-hcc-vq-options-item-wrap, .simple_variations_table  .wc-hcc-vq-options-item-wrap{
        margin-left: 10px;
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
    @media (max-width: 441px) {
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
            width: 90%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
            width: 10%;
        }
        .wc-hcc-vq-options-item-wrap{
            width: 80%;
        }
        .wc-hcc-vq-option-highlight-inscription + div{
            font-size:12px;
            line-height: 12px;
        }
    }
    @media (max-width: 1200px) {
        .fuelux .wizard ul li{
            padding: 0 0px 0 20px;
            font-size: 14px;
        }
    }
    @media (max-width: 991px) {
        .wc-hcc-vq-options table td select {
            width: 100%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1), .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
            width:80%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2),  .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
            width:20%;
        }

    }
    <?php 

        if( gb_hcc_template_field( 'fields_shipping', FALSE ) )
        {

    ?>

    #ship-to-different-address {
        display: block;
    }

    #ship-to-different-address label {
        font-size: 18px;
    }

    <?php 

        }

        if( gb_hcc_template_field( 'order_details', FALSE ) ):

    ?>

    .shop_table.woocommerce-checkout-review-order-table {

        display: block;
    }

    <?php

        endif;

    ?>

    /* Customizable */

    <?php 

        if( gb_hcc_template_field( 'color_header', FALSE ) )
        {

    ?>
        .navbar-inverse {
            background: <?php gb_hcc_template_field('color_header'); ?>;
        }
    <?php

        }

        if( gb_hcc_template_field( 'color_footer', FALSE ) )
        {
    ?>
        footer {
            background: <?php gb_hcc_template_field('color_footer'); ?>;
        }
    <?php

        }

        if( gb_hcc_template_field( 'color_bg', FALSE ) )
        {
    ?>
        body {
            background: <?php gb_hcc_template_field('color_bg'); ?>;
        }
    <?php

        }

        if( gb_hcc_template_field( 'color_headline', FALSE ) )
        {
    ?>
        .inner-block header h2,
        .inner-block header p,
        .trustpoint h4,
        .woocommerce-billing-fields > h3,
        .woocommerce-shipping-fields > h3,
        .wc-hcc-vq-options .vq-options-main-title,
        .woocommerce-checkout #order_review_heading {
            color: <?php gb_hcc_template_field('color_headline'); ?>;
        }

        form.login button[type="submit"],
        button[name=apply_coupon] {
            background: <?php gb_hcc_template_field('color_headline'); ?>;
        }

    <?php 

        }

        if( gb_hcc_template_field( 'color_button_1', FALSE ) )
        {

    ?>
        .woocommerce-checkout .form-row #place_order,
        .woocommerce-checkout .form-row #place_order:hover,
        .fuelux .btn-success,
        .fuelux .btn-success:hover,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
            background: <?php gb_hcc_template_field('color_button_1'); ?>;

    <?php 

        if( gb_hcc_template_field( 'color_button_2', FALSE ) )
        {

    ?>
            background: -moz-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0, <?php gb_hcc_template_field('color_button_1'); ?>), color-stop(100%, <?php gb_hcc_template_field('color_button_2'); ?>));
            background: -webkit-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -o-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: -ms-linear-gradient(top, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            background: linear-gradient(to bottom, <?php gb_hcc_template_field('color_button_1'); ?> 0, <?php gb_hcc_template_field('color_button_2'); ?> 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='<?php gb_hcc_template_field('color_button_1'); ?>', endColorstr='<?php gb_hcc_template_field('color_button_2'); ?>', GradientType=0);
    <?php

        }

    ?>
        }

        .woocommerce-checkout .form-row #place_order:hover,
        .fuelux .btn-success:hover,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
            opacity: 0.9;
        }

        .woocommerce-checkout .form-row #place_order:active,
        .fuelux .btn-success:active,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:active,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:active {
            background: <?php gb_hcc_template_field('color_button_1'); ?>;
        }

    <?php

        }

        if( gb_hcc_template_field( 'marketplace_image_align', FALSE ) == 'center' )
        {
        
    ?>
        .navbar-header {
            float: none;
            text-align: center;
        }

        .marketplace-image {
            display: inline;
        }

        header p.right {
            float: none;
            margin-top: 5px;
            font-size: 15px;
        }

    <?php

        }

    ?>

    /* Multistep */

    #order_review_heading, 
    #order_review {
        display: none;
    }

    #order_review_heading {
        margin-top: 0;
    }

    .link-back1 {
        display: block;
        margin-bottom: 10px;
    }
    </style>
</head>
<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <?php gb_hcc_template_field('marketplace_image'); ?>
        </div>

        <p class="right"><?php esc_html_e( 'Need help? Email', 'gb-wc-hcc' ); ?> <a href="mailto:<?php gb_hcc_template_field('support_email'); ?>"><?php gb_hcc_template_field('support_email'); ?></a></p>
    </div>
</header>

<!-- Content -->
<div class="row clearfix">

<div class="inner-block generic">

    <?php

        if( gb_hcc_template_get_post_type() == 'handsome-checkout' )
        {
            if( !empty( $_GET['1cu'] ) && !empty( $_GET['1cu_s'] ) && !empty( $_GET['1cu_n'] ) )
            {
            
    ?>
        <div class="upsell-container">

    <?php 

                echo do_shortcode('[wc_1cu_default_page]');

    ?>

        </div>

    <?php

            }
            else
            {

    ?>

    <header>
        <h2><?php gb_hcc_template_field('product_title'); ?></h2>
        <span class="line"></span>
        <p><?php gb_hcc_template_field('product_description'); ?></p>
    </header>

    <section id="payment">
        <div class="container">
            <div class="row">
                <div class="widget-body fuelux col-md-8 col-md-offset-2">

                <div class="wizard">
                    <ul class="steps">
                        <li class="step1 active">
                            <span class="badge badge-info">1</span><?php esc_html_e( 'Billing details', 'woocommerce' ); ?><span class="chevron"></span>
                        </li>
                        <?php
                $vq_options = get_post_meta( $post->ID, 'hcc_vq_options', TRUE );

                if( !empty( $vq_options ) ) {
                    ?>

                    <li class="step2">
                        <span class="badge">2</span><?php esc_html_e( 'Product Selection', 'gb-wc-hcc' ); ?><span class="chevron"></span>
                    </li>
                    <?php
                    }
                    ?>
                        <li class="step3">
                            <span class="badge">
                            <?php
                                if( !empty( $vq_options ) )
                                {
                            ?>
                                    3
                            <?php
                                }
                                else
                                {
                            ?>
                                    2
                            <?php
                                }
                            ?>
                            </span><?php esc_html_e( 'Your order', 'woocommerce' ); ?><span class="chevron"></span>
                        </li>
                    </ul>
                </div>

                <div class="step-content">

                    <div class="step-pane active">

                        <div class="col-md-8">

                            <div>

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
                                    echo $checkout_code;
                                }

                            ?>

                            <button class="btn btn-success btn-lg wizard-button btn-block btn-step1">

                            <?php
                                if( gb_hcc_template_field( 'button_text_step1', FALSE ) )
                                {
                                    echo gb_hcc_template_field('button_text_step1');
                                }
                                else
                                {
                                    esc_html_e( 'Next Step', 'gb-wc-hcc' );
                                }
                            ?>
                            </button>

                                <button class="btn btn-success btn-lg wizard-button btn-block btn-step2">
                                    <?php
                                    if( gb_hcc_template_field( 'button_text_step1', FALSE ) )
                                    {
                                        echo gb_hcc_template_field('button_text_step1');
                                    }
                                    else
                                    {
                                        esc_html_e( 'Next Step', 'gb-wc-hcc' );
                                    }
                                    ?>
                                </button>

                            <a href="#" class="link-back2" style="display: none;"><?php esc_html_e('< back to the previous step', 'gb-wc-hcc' ); ?></a>

                                <a href="#" class="link-back1" style="display: none;"><?php esc_html_e('< back to the previous step', 'gb-wc-hcc'); ?></a>

                            </div>
                        </div>

                        <div class="col-md-4">

                            <?php if( gb_hcc_template_field( 'bullet_points', FALSE, '[bullet_text]' ) ): ?>

                            <div class="bullet-points">

                                <h2 class="list-title"><?php gb_hcc_template_field('bullet_points_title'); ?></h2>

                                <ul>
                                    <?php 
                                        gb_hcc_template_field( 
                                            'bullet_points', 
                                            TRUE, 
                                            '<li><i class="fa fa-check pull-left"></i><div class="bullet-text">[bullet_text]</div></li>' 
                                        );
                                    ?>
                                </ul>

                            </div>

                            <?php endif; ?>

                            <?php if( gb_hcc_template_field( 'testimonials', FALSE, '[testimonial_text]' ) ): ?>

                            <div class="testimonials">

                                <h2 class="title"><?php gb_hcc_template_field('testimonials_title'); ?></h2>

                                <?php
                                    gb_hcc_template_field(
                                        'testimonials',
                                        TRUE,
                                        '   <div class="testimonial-item">
                                                <div class="testimonial-image">[testimonial_image]</div>
                                                <p class="testimonial-text">[testimonial_text]</p>
                                                <div class="clear"></div>
                                            </div>'
                                    );
                                ?>

                            </div>

                            <?php endif; ?>

                            <div class="trustpoints">

                                <?php 
                                    gb_hcc_template_field( 
                                        'trustpoints', 
                                        TRUE,
                                        '   <div class="trustpoint [class]">
                                                <div class="trustpoint-image">[trustpoint_image]</div>
                                                <h4>[trustpoint_title]</h4>
                                                <div class="trustpoint-text">[trustpoint_text]</div>
                                            </div>
                                        '
                                    );
                                ?>

                            </div>

                        </div>

                        <div class="clearfix"></div>

                    </div><!-- end of step one -->
                </div><!-- end of step content div -->
            </div><!-- end of wizard -->
        </div>
    </div>

    </section>

    <?php

            }
        }

    ?>

</div>

<footer>
    <div class="container">
        <div class="row">
            <div><?php gb_hcc_template_field('custom_html_footer'); ?></div>
            <p class="legal">
                <?php echo esc_html( '&copy; ' . get_bloginfo('name') . ' ' . date('Y') ); ?>
            </p>
        </div>
    </div>
</footer>

<script type="text/javascript">
jQuery(document).ready(function(){

    var step1 = jQuery('.woocommerce-info, #customer_details, .btn-step1, .col2-set > .woocommerce-billing-fields__field-wrapper, .woocommerce-checkout > .woocommerce-checkout-subtitle');
    var step2 = jQuery('.wc-hcc-vq-options, .wc-hcc-vq-options > .vq-options-main-title ')
    var step3 = jQuery('.col2-set + #order_review_heading + .woocommerce-checkout-subtitle, #order_review_heading + .woocommerce-checkout-subtitle, #step2_details > .woocommerce-shipping-fields , #step2_details > .woocommerce-billing-fields > .woocommerce-billing-fields__field-wrapper, #order_review_heading, #order_review'); //#step2_details,   .woocommerce-checkout > .woocommerce-checkout-subtitle
    var btn1 = jQuery('.btn-step1');
    var btn2 = jQuery('.btn-step2');
    step3.hide();
    step2.hide();
    btn1.hide();

    jQuery('body')
        .addClass('hcc-t5-step-1')
        .removeClass('hcc-t5-step-2')
        .removeClass('hcc-t5-step-3');

    jQuery('#customer_details .woocommerce-checkout-subtitle').show();

    jQuery('#step2_details .woocommerce-billing-fields').find('h3, .woocommerce-checkout-subtitle').hide();

    // show step 1

    btn2.on('click', function (){

        if( requiredFieldsFilled() == false )
        {
            return false;
        }

        btn2.hide();

        if( jQuery('.col2-set #wc-hcc-vq-options-table').length === 1 )
        {
            jQuery('.col2-set #wc-hcc-vq-options-table').remove()
        };

        if( jQuery('*').is('.wc-hcc-vq-options, .wc-hcc-vq-options > .vq-options-main-title ') )
        {
            jQuery('body')
                .removeClass('hcc-t5-step-1')
                .addClass('hcc-t5-step-2')
                .removeClass('hcc-t5-step-3');

            step1.slideUp();
            btn1.show();
            step2.slideDown();
            jQuery('.steps .step2').addClass('active');

            jQuery('.steps .step2 .badge').addClass('badge-info');
            jQuery('.link-back2').show();
        }
        else
        {
            jQuery('body')
                .removeClass('hcc-t5-step-1')
                .addClass('hcc-t5-step-2')
                .removeClass('hcc-t5-step-3');

            step2.slideUp();
            step1.slideUp();

            step3.slideDown();

            jQuery('.steps .step3').addClass('active');

            jQuery('.steps .step3 .badge').addClass('badge-info');

            jQuery('html, body').animate(
                {
                    scrollTop: jQuery('.steps').offset().top
                },
                'slow'
            );

            jQuery('.link-back2').show();
        }
    });

    // show step 2
    
    btn1.on('click', function()
    {
        jQuery('body')
            .removeClass('hcc-t5-step-1')
            .removeClass('hcc-t5-step-2')
            .addClass('hcc-t5-step-3');

        step2.slideUp();
        step1.slideUp();

        jQuery('.link-back2').hide();

        step3.slideDown();

        jQuery('.steps .step3').addClass('active');

        jQuery('.steps .step3 .badge').addClass('badge-info');

        jQuery('html, body').animate(
            {
                scrollTop: jQuery('.steps').offset().top
            },
            'slow'
        );

        jQuery('.link-back1').show();
    });

    // show step 1

    jQuery('.steps .step1, .link-back2').on('click', function(e){

        e.preventDefault();

        jQuery('body')
            .addClass('hcc-t5-step-1')
            .removeClass('hcc-t5-step-2')
            .removeClass('hcc-t5-step-3');

        if( jQuery('*').is('.wc-hcc-vq-options, .wc-hcc-vq-options > .vq-options-main-title ') )
        {
            jQuery('.steps .step2').removeClass('active');
            jQuery('.steps .step2 .badge').removeClass('badge-info');
            jQuery('.steps .step3').removeClass('active');
            jQuery('.steps .step3 .badge').removeClass('badge-info');
            jQuery('html, body').animate(
                {
                    scrollTop: jQuery('.steps').offset().top
                },
                'slow'
            );

            jQuery('.link-back2').hide();
            jQuery('.link-back1').hide();
        }
        else
        {
            jQuery('.steps .step3').removeClass('active');
            jQuery('.steps .step3 .badge').removeClass('badge-info');
            jQuery('html, body').animate(
                {
                    scrollTop: jQuery('.steps').offset().top
                },
                'slow'
            );

            jQuery('.link-back1').hide();
            jQuery('.link-back2').hide();
        }

        step1.slideDown();
        step2.slideUp();
        btn2.show();
        btn1.hide();
        step3.slideUp();
    });

    jQuery('.steps .step2, .link-back1').on('click', function(e) {

        e.preventDefault();
        step3.slideUp();
        jQuery('.link-back1').hide();

        if( jQuery('*').is('.wc-hcc-vq-options, .wc-hcc-vq-options > .vq-options-main-title ') )
        {
            jQuery('body')
                .removeClass('hcc-t5-step-1')
                .addClass('hcc-t5-step-2')
                .removeClass('hcc-t5-step-3');

            jQuery('.steps .step3').removeClass('active');
            jQuery('.steps .step3 .badge').removeClass('badge-info');
            jQuery('.steps .step2').addClass('active');
            jQuery('.steps .step2 .badge').addClass('badge-info');
            step1.slideUp();
            step2.slideDown();
            btn2.hide();
            btn1.show();

            jQuery('html, body').animate(
                {
                    scrollTop: jQuery('.steps').offset().top
                },
                'slow'
            );

            jQuery('.link-back2').show();
        } 
        else
        {
            jQuery('body')
                .addClass('hcc-t5-step-1')
                .removeClass('hcc-t5-step-2')
                .removeClass('hcc-t5-step-3');

            jQuery('.steps .step3').removeClass('active');
            jQuery('.steps .step3 .badge').removeClass('badge-info');
            jQuery('.steps .step1').addClass('active');
            jQuery('.steps .step1 .badge').addClass('badge-info');
            step2.slideUp();
            step1.slideDown();
            btn2.hide();
            btn1.show();
            jQuery('html, body').animate(
                {
                    scrollTop: jQuery('.steps').offset().top
                },
                'slow'
            );

            jQuery('.link-back2').hide();
        }
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

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
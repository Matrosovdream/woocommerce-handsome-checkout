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

    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,400i,700,900" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/font-awesome.min.css', __FILE__); ?>">

    <?php wp_head(); ?>

    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/template-4.css', __FILE__); ?>">

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
        clear: both;
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
        margin: 1em auto;
    }

    .form-row label {
        display: inline;
        font-size: 18px;
    }

    .form-row input[type="text"],
    .form-row input[type="email"],
    .form-row input[type="tel"],
    .form-row input[type="password"],
    .form-row textarea,
    .form-row select {
        outline: none;
        padding: 5px;
        border: 1px solid #DDDDDD;
        display: block;
        width: 63%;
        float: right;
        font-size: 16px;
        font-weight: 100;
        -webkit-transition: all 0.30s
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

    input[name=apply_coupon],
    button[name=apply_coupon] {
        display: inline-block;
        background: #6bb327;
        color: #fff;
        line-height: 18px;
        border-radius: 4px;
        transition: all 0.2s;
        vertical-align: middle;
        padding: 11px 15px;
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
        width: 100%;
        height: auto;
        padding: 1em;
        color: #FFF;
        background-color: #50a73a;
        text-align: center;
        font-size: 18px;
        font-weight: normal;
        line-height: 21px;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        opacity: 1;
        -webkit-appearance: none;
    }

    h2.list-title-small{
            font-size: 14px;
            line-height: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 10px;
    }

    h2.title-small{
            font-size: 14px;
            line-height: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 10px;
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
    .shop_table .woocommerce-Price-amount.amount {
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
    .wc_payment_method label {
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
    .woocommerce-privacy-policy-text {
        padding: 10px 0 0;
    }
    .woocommerce-terms-and-conditions-wrapper .form-row label {
        width: 100%;
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
        width: 100%;
        display: block;
        height: auto;
    }

    .testimonial-item {
        background: #f6f6f6;
        padding: 15px;
        margin-bottom: 10px;
    }
    .testimonial-image {
        float: right;
        max-width: 75px;
        margin: 0 0 10px 10px;
    }
    .testimonial-text {
        color: #928780;
        font-size: 14px;
        line-height: 20px;
        font-style: italic;
    }
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
        width: 60%;
    }
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
        width: 40%;
    }
    .wc-hcc-vq-option-highlight .wc-hcc-vq-options-item{
        position: relative;
    }
    .wc-hcc-vq-option-highlight .wc-hcc-vq-options-item-wrap{
        margin-left: 25px;
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
    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
        width: 70%;
    }
    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
        width: 30%;
    }
    input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap{
        width: 90%;
    }
    .wc-hcc-vq-options .vq-options-main-title{
        color: #7543f2;
        line-height: normal !important;
        margin-bottom: 0;
        border-bottom: 0;
        margin-top: 35px;

    }
    .wc-hcc-vq-options table.wc-hcc-vq-options-table{
        margin-bottom:35px;
        border-top: none;
    }
    .wc-hcc-vq-options-item-wrap{
        padding-left: 0 !important;
    }
    .wc-hcc-vq-options table td:nth-of-type(1){
        width: 10%;
        vertical-align: middle;
    }
    .wc-hcc-vq-options table td:nth-of-type(2){
        width: 50%;
        vertical-align: middle;
    }
    .wc-hcc-vq-options table td:nth-of-type(3){
        width: 40%;
        vertical-align: middle;
    }
    .wc-hcc-vq-options table td .wc-hcc-field-label h4{
        margin-bottom: 0;
        font-size: 18px;
    }
    .wc-hcc-vq-options table td select{
        outline: none;
        padding: 5px;
        border: 1px solid #DDDDDD;
        display: block;
        width: 100%;
        font-size: 16px;
        font-weight: 100;
        -webkit-transition: all 0.30s;
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
        right:0;
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
        display: block;
    }
    .wc-hcc-vq-option-highlight .wc-hcc-field-label{
        position: relative;
        z-index: 11;
    }
    .wc-hcc-vq-option-highlight{
        background-color: transparent !important;
        border:none !important;
    }

    .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap{
        padding-left: 10px !important;
    }

    @media (max-width: 991px) {
        .wc-hcc-vq-options table td select{
            width:100%;
        }
        .wc-hcc-vq-options table td:nth-of-type(3){
            width: 10%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1), .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
            width:80%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2),  .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
            width:20%;
        }
    }
    @media (max-width: 991px) {
        .simple_variations_table .wc-hcc-vq-options-item-wrap{
            width:80%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
            width:80%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
            width:20%;
        }
    }
    @media (max-width: 441px) {
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
            width: 90%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
            width: 10%;
        }
        .wc-hcc-vq-option-highlight-inscription + div{
            font-size:12px;
        }
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

    <?php 

        if( gb_hcc_template_field( 'fields_shipping', FALSE ) )
        {

    ?>

    #ship-to-different-address {
        display: block;
    }

    #ship-to-different-address label {
        font-size: 18px;
        box-shadow: none;
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
        .logo-wrap {
            background: <?php gb_hcc_template_field('color_header'); ?>;
        }
    <?php

        }

        if( gb_hcc_template_field( 'color_footer', FALSE ) )
        {
    ?>
        .footer-container footer p {
            color: <?php gb_hcc_template_field('color_footer'); ?>;
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
        .product-description h1.title,
        h1.title,
        h2.title,
        h2.list-title,
        .sidebar h3,
        .woocommerce-billing-fields > h3,
        .woocommerce-shipping-fields > h3,
        .wc-hcc-vq-options .vq-options-main-title,
        .woocommerce-checkout #order_review_heading {
            color: <?php gb_hcc_template_field('color_headline'); ?>;
        }

        .trustpoint.condition-guarantee {
            border-color: <?php gb_hcc_template_field('color_headline'); ?>;
        }

        a {
            color: <?php gb_hcc_template_field('color_headline'); ?>;
        }

        form.login button[type="submit"],
        input[name=apply_coupon] {
            background: <?php gb_hcc_template_field('color_headline'); ?>;
        }

        input:focus { 
            box-shadow: 0 0 5px <?php gb_hcc_template_field('color_headline'); ?>;
        }

    <?php 

        }

        if( gb_hcc_template_field( 'color_button_1', FALSE ) )
        {

    ?>
        .woocommerce-checkout .form-row #place_order,
        .woocommerce-checkout .form-row #place_order:hover,
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
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
            opacity: 0.85;
        }

        .woocommerce-checkout .form-row #place_order:active,
        .page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:active,
        .page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:active {
            background: <?php gb_hcc_template_field('color_button_1'); ?>;
        }

    <?php

        }

        if( gb_hcc_template_field( 'marketplace_image_align', FALSE ) )
        {
        
    ?>
        .logo {
            text-align: <?php gb_hcc_template_field('marketplace_image_align'); ?>;
        }

        .logo img {
            display: inline;
        }
    <?php

        }

    ?>

    /* GB WC 1CU */

    .page.page-gb-wc-hcc-upsell .wc_1cu_default_offer_page .wc_1cu_default_offer_variation_price {
        color: #000;
    }

    .page.page-gb-wc-hcc-upsell .wc_1cu_default_offer_page .woocommerce-Price-amount.amount {
        display: inline;
    }

    .page.page-gb-wc-hcc-upsell .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept {
        background: #f79b00;
    }

    .page.page-gb-wc-hcc-upsell .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover {
        background: #e2920b;
    }

    .page .wc_1cu_default_offer_page .wc_1cu_default_offer_variations select {
        -webkit-appearance: menulist;
    }
    </style>
</head>

<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

<div class="container container-lg">
    <div class="row" style="padding: 0px;">
        <div class="twelve columns logo-wrap">
            <div class="logo">
                <?php gb_hcc_template_field('marketplace_image'); ?>
            </div>
        </div>
    </div>
</div>

<div class="container container-lg">

    <?php

        if( gb_hcc_template_get_post_type() == 'page' )
        {
    ?>
        <div class="upsell-container">

    <?php 
            if ( have_posts() ) :
            
                while ( have_posts() ) : the_post();
                    
                    the_content();

                endwhile;

            endif;
    ?>

        </div>

    <?php

        }

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

    <!-- SIDEBAR MOBILE -->

    <div class="row hide-on-desktop hide-on-tablet">
        <div class="twelve columns sidebar">

            <?php 

                if( gb_hcc_template_field( 'support_phone', FALSE ) )
                {

            ?>
                    <div class="number"><i class="fa fa-phone"></i> <?php gb_hcc_template_field('support_phone'); ?></div>
            <?php

                }

            ?>

            <div class="product-description">

                <div class="product-description-image">
                    <?php gb_hcc_template_field('product_image'); ?>
                </div>

                <h1 class="title"><?php gb_hcc_template_field('product_title'); ?></h1>
                
                <div class="product-description-text">
                    <?php gb_hcc_template_field('product_description'); ?>
                </div>

            </div>

            <div class="bullet-points">

                <h2 class="list-title list-title-small"><?php gb_hcc_template_field('bullet_points_title'); ?></h2>

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

            <div class="testimonials">

                <h2 class="title title-small"><?php gb_hcc_template_field('testimonials_title'); ?></h2>

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

            <div class="trustpoints">
                <?php 
                    gb_hcc_template_field( 
                        'trustpoints', 
                        TRUE,
                        '   <div class="trustpoint [class]">

                                <div class="trustpoint-image">[trustpoint_image]</div>

                                <h3>[trustpoint_title]</h3>

                                <div class="trustpoint-text">[trustpoint_text]</div>
                            </div>'
                    );
                ?>
            </div>

            <div class="phone">
                <h3><?php esc_html_e( 'Need Help?', 'gb-wc-hcc' ); ?></h3>

                <?php 

                    if( gb_hcc_template_field( 'support_phone', FALSE ) )
                    {

                ?>
                        <p class="phone"><?php esc_html_e('Call Us @', 'gb-wc-hcc'); ?> <?php gb_hcc_template_field('support_phone'); ?></p>
                <?php

                    }

                ?>

                <p><?php esc_html_e( 'Email Us Anytime:', 'gb-wc-hcc' ); ?> <a href="mailto:<?php gb_hcc_template_field('support_email'); ?>"><?php gb_hcc_template_field('support_email'); ?></a></p>

            </div>
        </div>
    </div>

    <!-- SIDEBAR MOBILE END -->

    <div class="row order-form-frame">
        <div class="seven columns">
            <article class="order-form">
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
            </article>
        </div>

        <!-- SIDEBAR DESKTOP -->

        <div class="four columns">
            <aside class="sidebar hide-on-mobile">
                <section>

                    <?php 

                        if( gb_hcc_template_field( 'support_phone', FALSE ) )
                        {

                    ?>
                            <div class="number"><i class="fa fa-phone"></i> <?php gb_hcc_template_field('support_phone'); ?></div>
                    <?php

                        }

                    ?>

                    <div class="product-description">

                        <div class="product-description-image">
                            <?php gb_hcc_template_field('product_image'); ?>
                        </div>

                        <h1 class="title"><?php gb_hcc_template_field('product_title'); ?></h1>
                        
                        <div class="product-description-text">
                            <?php gb_hcc_template_field('product_description'); ?>
                        </div>

                    </div>

                    <div class="bullet-points">
                        <h2 class="list-title list-title-small"><?php gb_hcc_template_field('bullet_points_title'); ?></h2>
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

                    <div class="testimonials">
                        <h2 class="title title-small"><?php gb_hcc_template_field('testimonials_title'); ?></h2>
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

                    <div class="trustpoints">
                        <?php 
                            gb_hcc_template_field( 
                                'trustpoints', 
                                TRUE,
                                '   <div class="trustpoint [class]">

                                        <div class="trustpoint-image">[trustpoint_image]</div>

                                        <h3>[trustpoint_title]</h3>

                                        <div class="trustpoint-text">[trustpoint_text]</div>
                                    </div>'
                            );
                        ?>
                    </div>

                    <div class="phone">
                        <h3><?php esc_html_e( 'Need Help?', 'gb-wc-hcc' ); ?></h3>

                        <?php 

                            if( gb_hcc_template_field( 'support_phone', FALSE ) )
                            {

                        ?>
                                <p class="phone"><?php esc_html_e('Call Us @', 'gb-wc-hcc'); ?> <?php gb_hcc_template_field('support_phone'); ?></p>
                        <?php

                            }

                        ?>

                        <p><?php esc_html_e( 'Email Us Anytime:', 'gb-wc-hcc' ); ?> <a href="mailto:<?php gb_hcc_template_field('support_email'); ?>"><?php gb_hcc_template_field('support_email'); ?></a></p>

                    </div>

                </section>
            </aside>
        </div>

        <!-- SIDEBAR DESKTOP END -->
    </div>

    <?php

            }
        }

    ?>

</div>

<div class="footer-container">
    <div class="twelve columns">
        <footer class="wrapper">
            <div><?php gb_hcc_template_field('custom_html_footer'); ?></div>
            <p class="legal">
                <?php echo esc_html( '&copy; ' . get_bloginfo('name') . ' ' . date('Y') ); ?>
            </p>
        </footer>
    </div>
</div>

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
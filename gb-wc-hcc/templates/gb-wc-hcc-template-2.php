<?php if( !defined( 'ABSPATH' ) ) die(); ?><!DOCTYPE html>
<html style="height: 100%;" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <meta name="robots" content="noindex">

    <meta name="generator" content="Handsome Checkout for WooCommerce">

    <!-- (c) 2016-2020 Bogdan Grigoruk (bogdanfix@gmail.com) -->
    
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,500,600,700">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/grid.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/bootstrap.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/bootstrap-theme.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/animate.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/font-awesome.css', __FILE__ ); ?>">
    
	<?php wp_head(); ?>

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/template-2.css', __FILE__ ); ?>">

	<style type="text/css">

	html, body {
		font-family: Lato, Arial, Helvetica;
	}

	.wc_hcc_hide {
		display: none;
	}

	.woocommerce-checkout form.login p:first-child {
		margin: 20px 40px;
	}

	.woocommerce-checkout form.checkout_coupon p.form-row-first {
		margin-top: 20px;
	}

	.woocommerce-checkout form.checkout_coupon p.form-row-last {
		margin-top: 20px;
	}

	.woocommerce-checkout form.login .button,
	.woocommerce-checkout form.checkout_coupon .button {
		float: none;
		background: #444;
		font-size: 14px;
		border: 0;
		padding: 5px 20px;
		text-transform: uppercase;
		font-weight: bold;
		color: #FFF;
	}

	.checkout_coupon p:not(.form-row):first-child {
	    display: none;
	}

	.woocommerce-checkout form.login label {
		font-size: 1.1em;
	}

	.woocommerce-checkout form.login label.inline {
		font-size: 0.9em;
	}

	.woocommerce-checkout form.login label.inline input[type="checkbox"] {
		margin: 10px 5px;
	}

	.woocommerce-checkout form.login .lost_password {
		clear: both;
		margin: 20px 40px;
	}

	.woocommerce-additional-fields > h3 {
		display: none;
	}

	.woocommerce-billing-fields > h3,
	/*.woocommerce-shipping-fields > h3,*/
	.woocommerce-checkout #order_review_heading {
		padding: 22px 0;
		border-radius: 5px 5px 0 0;
		color: #FFF;
		background: #1B79C2;
		margin-bottom: 30px;
		overflow: hidden;
		clear: both;
		padding-left: 30px;
		font-weight: 800;
		font-family: "Open Sans Condensed";
		font-size: 1.8em;
		text-shadow: 0 -1px 0 #1c3e58;
	}

	.woocommerce-shipping-fields > h3 {
		position: relative;
		min-height: 45px;
		padding-left: 15px;
		padding-right: 15px;
		margin: 0 25px 25px 25px;
		clear: both;
		font-size: 1.2em;
	}

	.woocommerce-shipping-fields > h3 input[type="checkbox"] {
		position: relative;
		margin-top: 10px;
		margin-left: 15px;
		margin-right: 5px;
	}

	.woocommerce-shipping-fields > h3,
	.woocommerce-checkout #order_review_heading {
		margin-top: 12px;
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

	<?php 

		if( gb_hcc_template_field( 'fields_shipping', FALSE ) )
		{

	?>

	#ship-to-different-address {
		display: block;
		padding: 0;
		margin: 0 25px;
	}

	#ship-to-different-address label {
		float: none;
		font-size: 17px;
		margin: 0;
	}

	<?php 

		}

		if( gb_hcc_template_field( 'order_details', FALSE ) ):

	?>

	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table {
		display: table;
		border-collapse: collapse;
		margin: auto;
		margin-bottom: 30px;
		width: calc( 100% - 60px );
		background: #F5F5F5;
	}

	@media screen and (max-width: 767px) {
		.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table {
			width: calc( 100% - 30px );
		}
	}

	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table td,
	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th {
		padding: 10px;
		text-align: left;
		border: 1px solid #CCC;
		border-width: 0 0 1px 0;
	}

	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th.product-name,
	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table th.product-total {
		width: 50%;
	}

	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .cart_item td.product-name,
	.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table .cart_item td.product-total {
		font-size: 1.1em;
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

	.woocommerce-checkout-subtitle {
		display: none;
	}

	.woocommerce-checkout .form-row,
	.checkout_coupon .form-row,
	.woocommerce-checkout .wc_payment_methods li.wc_payment_method .payment_box p,
	.woocommerce-checkout .wc_payment_methods li.wc_payment_method .payment_box ul {
		position: relative;
		min-height: 1px;
		padding-left: 15px;
		padding-right: 15px;
		margin: 0 25px 20px 25px;
		clear: both;
	}

	@media screen and (max-width: 767px) {
		.woocommerce-checkout .form-row, 
		.checkout_coupon .form-row, 
		.woocommerce-checkout .wc_payment_methods li.wc_payment_method .payment_box p {
			margin: 0 0 20px 0;
		}
	}

	.woocommerce-checkout .form-row label {
		width: 29%;
		display: block;
		font-weight: 700;
		box-sizing: border-box;
		font-size: 1.3em;
		line-height: 33px;
		text-align: left;
	}

	.woocommerce-checkout .form-row.wc-terms-and-conditions {
		margin: 0 5px;
	}

	.woocommerce-checkout .form-row.wc-terms-and-conditions label {
		width: 100%;
	}

	.woocommerce-checkout .form-row.wc-terms-and-conditions label input[type="checkbox"],
	.woocommerce-checkout .form-row.create-account label input[type="checkbox"] {
		margin-top: 10px;
	}

	.woocommerce-privacy-policy-text {
		padding: 10px 20px 0;
	}

	.woocommerce-terms-and-conditions-wrapper p.form-row label {
		width: 100%;
	}

	.woocommerce-terms-and-conditions-wrapper p.form-row label span {
		font-size: 14px;
	}

	@media screen and (max-width: 480px) {
		
		.woocommerce-terms-and-conditions-wrapper p.form-row label {
			line-height: 20px;
		}
	}

	.woocommerce-terms-and-conditions-wrapper p.form-row label input[type="checkbox"] {
		margin-top: 10px;
	}

	.woocommerce-checkout .form-row input[type="text"],
	.woocommerce-checkout .form-row input[type="email"],
	.woocommerce-checkout .form-row input[type="tel"],
	.woocommerce-checkout .form-row input[type="password"],
	.woocommerce-checkout .form-row textarea,
	.woocommerce-checkout .form-row select {
		display: block;
		width: 71%;
		border: none;
		border: 1px solid #d7d7d7;
		border-radius: 2px;
		font-size: 16px;
		padding: 7px;
		margin-bottom: 15px;
		height: 35px;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .08);
	}

	.woocommerce-checkout .form-row select {
		padding: 7px;
	}

	.woocommerce-checkout .wc_payment_methods {
		padding: 0 25px 20px 25px;
	}

	.woocommerce-checkout .wc_payment_methods .form-row label {
		float: none;
	}

	.wc-credit-card-form {
		clear: both;
	}

	ul.wc-saved-payment-methods li {
		list-style: none;
		clear: both;
	}

	ul.wc-saved-payment-methods li input[type=radio],
	li.wc_payment_method input[type=checkbox] {
		margin: 10px 10px 10px 0;
	}

	@media screen and (max-width: 767px) {
		.woocommerce-checkout .wc_payment_methods {
			padding: 0 0 20px 0;
		}

		.woocommerce-checkout .form-row label {
			width: 100%;
		}

		.woocommerce-checkout .form-row input[type="text"],
		.woocommerce-checkout .form-row input[type="email"],
		.woocommerce-checkout .form-row input[type="tel"],
		.woocommerce-checkout .form-row input[type="password"],
		.woocommerce-checkout .form-row textarea,
		.woocommerce-checkout .form-row select {
			width: 100%;
		}
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method {
		position: relative;
		list-style: none;
		clear: both;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method:only-child input[type="radio"] {
		display: none;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > input[type="radio"] {
		position: absolute;
		left: 0;
		margin: 0.7em 0 0 1em;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method.payment_method_ocupaypal > input[type="radio"] {
		margin: 1.2em 0 0 1em;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > label {
		position: relative;
		min-height: 1px;
		padding-left: 15px;
		padding-right: 15px;
		margin: 0 25px 20px 25px;
		clear: both;
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

		.woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span img {
			width: 42px;
		}

		.woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span img:first-child {
			padding-left: 0;
		}

	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box {
		clear: both;
		margin-top: 20px;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box p.form-row:before,
	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box p.form-row:after {
		clear: none;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box input[type="text"],
	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box select {
		width: 100% !important;
	}

	.woocommerce-checkout .woocommerce-info {
		display: block;
		background: #eefdff;
		padding: 15px 30px;
		/*margin: 0;*/
		list-style: none;
	}

	.woocommerce-checkout .woocommerce-message {
		display: block;
		background: #fff5d1;
		padding: 15px 30px;
		/*margin: 0;*/
		list-style: none;
	}

	.woocommerce-checkout .woocommerce-error {
		display: block;
		background: #FEE;
		padding: 15px 30px;
		/*margin: 0 40px 20px;*/
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
		display: block;
		clear: both;
		background: #27B71D;
		background: -moz-linear-gradient(top, #27B71D 0, #248A1D 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #27B71D), color-stop(100%, #248A1D));
		background: -webkit-linear-gradient(top, #27B71D 0, #248A1D 100%);
		background: -o-linear-gradient(top, #27B71D 0, #248A1D 100%);
		background: -ms-linear-gradient(top, #27B71D 0, #248A1D 100%);
		background: linear-gradient(to bottom, #27B71D 0, #248A1D 100%);

		filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#27B71D', endColorstr='#248A1D', GradientType=0);
		border: none;
		cursor: pointer;
		font-family: sans-serif;
		font-weight: bold;
		font-size: 2em;
		color: #fff;
		padding: 20px 15px;
		width: 100%;
		height: auto;
		border-radius: 10px;
		text-transform: none;
		text-decoration: none;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, .2);
		box-shadow: inset 0 -4px 0 rgba(0, 0, 0, .08);
		margin: 15px auto 35px;
	}

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
	.page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept {
		width: 60%;
	}

	.woocommerce-checkout .form-row #place_order:hover,
	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
	.page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
		background: #30D824;
		background: -moz-linear-gradient(top, #30D824 0, #2EB325 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #30D824), color-stop(100%, #2EB325));
		background: -webkit-linear-gradient(top, #30D824 0, #2EB325 100%);
		background: -o-linear-gradient(top, #30D824 0, #2EB325 100%);
		background: -ms-linear-gradient(top, #30D824 0, #2EB325 100%);
		background: linear-gradient(to bottom, #30D824 0, #2EB325 100%);
		filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#30D824', endColorstr='#2EB325', GradientType=0);
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

	abbr.required {
		border-bottom: 0;
	}

	.heading.grid-container {
		padding-bottom: 0;
	}
	.logo-wrapper img.marketplace-image {
		margin: 5px 0;
	}
	.trust-panel h1 {
		padding-top: 1em;
	}
	.trust-panel .customize-text {
		padding-top: 1em;
	}
	.trust-panel li {
		list-style-image: url('<?php echo esc_url( plugins_url( 'assets/images/check-green.png', __FILE__ ) ); ?>');
	}
	.testimonial-group h2 {
		font-weight: bold;
	}
	.testimonial-item p.testimonial-text {
		display: block;
		color: #333;
		padding-bottom: 10px;
		line-height: 22px;
	}
	p.security {
		margin: 1em 0;
	}
	.footer p {
		margin: 1em 0;
	}

    .wc-hcc-vq-options .vq-options-main-title{
        padding: 22px 0;
        border-radius: 5px 5px 0 0;
        color: #FFF;
        margin-bottom: 0px;
        overflow: hidden;
        clear: both;
        padding-left: 30px;
        font-weight: 800;
        font-family: "Open Sans Condensed";
        font-size: 1.8em;
        text-shadow: 0 -1px 0 #1c3e58;
        background: #7543f2;
        line-height: normal;
    }
    <?php
    if( gb_hcc_template_field( 'color_headline', FALSE ) )
    {
    ?>
    .wc-hcc-vq-options .vq-options-main-title {
        background: <?php gb_hcc_template_field('color_headline'); ?>;
    }

    <?php
    }
    ?>
    .wc-hcc-vq-options table td{
        vertical-align: middle;
    }
    .wc-hcc-vq-options table td:nth-of-type(1){
        width: 20%;
    }
    .wc-hcc-vq-options table td:nth-of-type(2){
        width: 60%;
    }
    .wc-hcc-vq-options table td:nth-of-type(3){
        width: 20%;
    }
    .wc-hcc-vq-options table td select{
        display: block;
        width: 71%;
        border: none;
        border: 1px solid #d7d7d7;
        border-radius: 2px;
        font-size: 16px;
        padding: 7px;
        /* margin-bottom: 15px; */
        height: 35px;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .08);

    }
    table.wc-hcc-vq-options-table {
    	width: 90% !important;
    	margin: auto;
    }
    table.simple_quantity_table  td:nth-of-type(1){
        width:70%;
        padding: 8px 10px;
    }
    table.simple_quantity_table  td:nth-of-type(2){
        width:30%;
        padding: 8px 10px;
    }
    .wc-hcc-vq-option-highlight{
        border: 1px solid rgba(0, 0, 0, .08) !important;
    }
    .wc-hcc-vq-options-item-wrap{
        width: 85%;
        line-height: 18px;
        padding-left: 0 !important;
    }
    .simple_quantity_table  .wc-hcc-vq-options-item-wrap, .simple_variations_table  .wc-hcc-vq-options-item-wrap{
        margin-left: 15px;
    }
    .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap {
        padding-left: 10px !important;
        vertical-align: middle;
    }
    .wc-hcc-vq-options-table .wc-hcc-vq-options-item-wrap,
    .wc-hcc-vq-options-table .wc-hcc-vq-options-price {
    	font-size: 18px;
    }

    @media only screen and (max-width: 480px) {
        .wc-hcc-vq-options-table .wc-hcc-vq-options-header h4 {
        	font-size: 14px;
        }

    	.wc-hcc-vq-options-table .wc-hcc-vq-options-item-wrap {
    		font-size: 14px;
    	}
    	
        .wc-hcc-vq-options-table .wc-hcc-vq-options-price {
        	font-size: 15px;
        }
    }

    @media (max-width: 991px) {

        .wc-hcc-vq-options table td select{
            width: 100%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1), .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
            width:80%;
        }
        .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2),  .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
            width:20%;
        }
    }
	/* Customizable */

<?php 

	if( gb_hcc_template_field( 'color_header', FALSE ) )
	{

?>
	.trust-panel h1,
	.trust-panel h2.list-title {
		color: <?php gb_hcc_template_field('color_header'); ?>;
	}
<?php

	}

	if( gb_hcc_template_field( 'color_footer', FALSE ) )
	{
?>
	.footer p, .footer div {
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
	.woocommerce-billing-fields > h3,
	/*.woocommerce-shipping-fields > h3,*/
	.woocommerce-checkout #order_review_heading {
		background: <?php gb_hcc_template_field('color_headline'); ?>;
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
	.logo-top, .logo-wrapper {
		text-align: <?php gb_hcc_template_field('marketplace_image_align'); ?>;
		float: none;
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

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn {
		text-align: center;
	}

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_price,
	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_variation_price {
		font-size: 1.6em !important;
		color: #000 !important;
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
        right: 0;
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
        right: -4%;
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


    @media (max-width: 991px) {
        .simple_variations_table .wc-hcc-vq-options-item-wrap{
            width: 90%;
            line-height: 1.3rem;
        }

    }
	</style>
</head>
<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

<div class="wrapper">

    <div class="header">
	    <div class="grid-container clearfix heading">
	        <div class="logo-wrapper column1">
	        	<?php gb_hcc_template_field('marketplace_image'); ?>
	        </div>
	    </div>
	</div>

	<div class="grid-container checkout-form-container ng-isolate-scope">

	<?php

		if( gb_hcc_template_get_post_type() == 'page' )
		{

	?>
        <div class="col-md-12 col-sm-12">
			<div class="upsell-container">

	<?php 
			if ( have_posts() ) :
			
				while ( have_posts() ) : the_post();
					
					the_content();

				endwhile;

			endif;
	?>

			</div>
		</div>

	<?php

		}

		if( gb_hcc_template_get_post_type() == 'handsome-checkout' )
		{
			if( !empty( $_GET['1cu'] ) && !empty( $_GET['1cu_s'] ) && !empty( $_GET['1cu_n'] ) )
			{

	?>

        <div class="col-md-12 col-sm-12">
    		<div class="upsell-container">

    			<?php echo do_shortcode('[wc_1cu_default_page]'); ?>

    		</div>
    	</div>

	<?php

			}
			else
			{

	?>

	    <div class="grid-parent hide-on-desktop hide-1024">
	        <div class="sidebar">
	            <div class="trust-panel whats-inside">
	                <div class="trust-panel whats-inside">
	                    <?php gb_hcc_template_field('product_image'); ?>
	                    
	                    <h1><?php gb_hcc_template_field('product_title'); ?></h1>
	                    
	                    <div class="customize-text">
	                    	<h2><?php gb_hcc_template_field('product_description'); ?></h2>
	                    </div>
	                </div>
	                <div class="list-wrapper">
						<h2 class="list-title"><?php gb_hcc_template_field('bullet_points_title'); ?></h2>
	                	<ul class="sidebar-list">
							<?php 
								gb_hcc_template_field( 
									'bullet_points', 
									TRUE, 
									'<li><i class="fa fa-check pull-left"></i><div class="bullet-text">[bullet_text]</div></li>' 
								);
							?>
	                	</ul>
	                </div>
	                <div class="testimonial-group">
	                	<h2><?php gb_hcc_template_field('testimonials_title'); ?></h2>
	                	
	                	<?php 
	                		gb_hcc_template_field( 
	                			'testimonials', 
	                			TRUE,
	                			'	<div class="testimonial-item">
	                					<div class="testimonial-image">[testimonial_image]</div>
	                					<p class="testimonial-text">[testimonial_text]</p>
	                				</div>'
	                		);
	                	?>

	                </div>

	                <div class="trust-point-wrap">

	                	<?php 
	                		gb_hcc_template_field( 
	                			'trustpoints', 
	                			TRUE,
	                			'	<hr>
	                				<div class="trust-point trust-point-guarantee">
	                					<div class="trust-point-image">[trustpoint_image]</div>
	                					<div class="trust-point-title">[trustpoint_title]</div>
	                					<div class="trust-point-text">[trustpoint_text]</div>
	                				</div>'
	                		);
	                	?>

	                </div>

	            </div>
	        </div>
	    </div>

	    <div class="form-container clearfix row">

	        <div class="col-md-8 col-sm-8">

	            <div class="checkout-form">

	            <!-- CHECKOUT FORM -->
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
	            <!-- END CHECKOUT FORM -->
            
		        </div>

		    </div>

	        <div class="col-md-4 col-sm-4 hide-on-mobile trust-panel">
	            <div class="trust-panel whats-inside">
	                <?php gb_hcc_template_field('product_image'); ?>
					
					<h1><?php gb_hcc_template_field('product_title'); ?></h1>
	                                
	            	<div class="customize-text">
	            		<h2><?php gb_hcc_template_field('product_description'); ?></h2>
	           		</div>
	            </div>
	            <div class="list-wrapper">
					<h2 class="list-title"><?php gb_hcc_template_field('bullet_points_title'); ?></h2>
	            	<ul class="sidebar-list">	
						<?php 
							gb_hcc_template_field( 
								'bullet_points', 
								TRUE, 
								'<li><i class="fa fa-check pull-left"></i><div class="bullet-text">[bullet_text]</div></li>' 
							);
						?>
	            	</ul>
	            </div>
	            <div class="testimonial-group">
	            	<h2><?php gb_hcc_template_field('testimonials_title'); ?></h2>
	            	
	            	<?php 
	            		gb_hcc_template_field( 
	            			'testimonials', 
	            			TRUE,
	            			'	<div class="testimonial-item">
	            					<div class="testimonial-image">[testimonial_image]</div>
	            					<p class="testimonial-text">[testimonial_text]</p>
	            				</div>'
	            		);
	            	?>

	            </div>

	            <div class="trust-point-wrap">

	            	<?php 
	            		gb_hcc_template_field( 
	            			'trustpoints', 
	            			TRUE,
	            			'	<hr>
	            				<div class="trust-point trust-point-guarantee">
	            					<div class="trust-point-image">[trustpoint_image]</div>
	            					<div class="trust-point-title">[trustpoint_title]</div>
	            					<div class="trust-point-text">[trustpoint_text]</div>
	            				</div>'
	            		);
	            	?>

	            </div>

	        </div>

		</div>

		<?php

				}
			}

		?>
	</div>

	<div class="clear"></div>

    <footer class="footer">
	    <div class="grid-container">
	        <div class="copyright">
				<div><?php gb_hcc_template_field('custom_html_footer'); ?></div>

	            <p><?php esc_html_e( 'Copyright &copy;', 'gb-wc-hcc' ); ?> <?php echo esc_html( date('Y') . ' ' . get_bloginfo('name') ); ?> - <?php esc_html_e( 'All Rights Reserved', 'gb-wc-hcc' ); ?></p>

	            <p><span class="hide-help"><?php esc_html_e( 'Need Help?', 'gb-wc-hcc' ); ?> </span><?php gb_hcc_template_field('support_email'); ?></p>
	        </div>
<!-- 	        <div class="support-email">
				<div class="hide-on-mobile"> | </div>
	        </div> -->
	        <div class="terms-and-conditions"></div>
	    </div>
	</footer>

</div>

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
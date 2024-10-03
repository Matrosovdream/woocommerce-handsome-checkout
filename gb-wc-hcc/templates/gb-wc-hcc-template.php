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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,300,700">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/bootstrap-grid.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/grid.css', __FILE__ ); ?>">

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/font-awesome.css', __FILE__ ); ?>">
    
	<?php wp_head(); ?>

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/template.css', __FILE__ ); ?>">

	<style type="text/css">

	html, body {
		font-family: Lato, Arial, Helvetica;
	}

	.wc_hcc_hide {
		display: none;
	}

	.woocommerce-checkout #order_review_heading,
	.woocommerce-billing-fields > h3,
	.woocommerce-shipping-fields > h3 {
		clear: both;
		padding: 0 0 5px;
		margin: 0 15px;
		font-size: 26px;
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
    .wc-hcc-vq-options .vq-options-main-title{
        margin: 0 15px;
        color: #333333;
    }
    .wc-hcc-vq-options .wc-hcc-vq-options-header h4 {
    	margin: 0;
    }
    .wc-hcc-vq-options table{
        margin: 0 15px 35px 15px;
    }
    .wc-hcc-vq-options table td:nth-of-type(1){
        width: 10%;
    }
    .wc-hcc-vq-options table td:nth-of-type(2){
        width: 40%;
    }
    .wc-hcc-vq-options table td:nth-of-type(3){
        width: 5%;
    }
    .wc-hcc-vq-options table td select{
        border: 1px solid #d7d7d7;
        background: #f7f7f7;
        border-radius: 2px;
        font-size: 16px;
        padding: 15px 12px 15px 12px;
        width: 100%;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .08);
        -webkit-appearance: menulist-button;
    }
    table.wc-hcc-vq-options-table{
        width:97% !important;
    }
    table.simple_quantity_table  td:nth-of-type(1){
        width:70%;
        padding: 8px 10px;
    }
    table.simple_quantity_table  td:nth-of-type(2){
        width:30%;
        padding: 8px 10px;
    }

    .wc-hcc-vq-option-highlight .wc-hcc-vq-options-item{
        position: relative;
    }

    .wc-hcc-vq-options-item-wrap{
        padding-left: 0 !important;
    }
    .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap{
        padding-left: 10px !important;
    }
    input.wc-hcc-vq-option-selection{
        margin-right:4px;

    }
    input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap{
        width: 85%;
    }

    .simple_quantity_table  .wc-hcc-vq-options-item-wrap, .simple_variations_table  .wc-hcc-vq-options-item-wrap{
        margin-left: 10px;
    }
    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(1){
        width: 70%;
    }
    .wc-hcc-vq-options-table.simple_variations_table td:nth-of-type(2){
        width: 30%;
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
    @media (max-width: 991px) {
        .simple_variations_table .wc-hcc-vq-options-item-wrap{
            width: 90%;
            line-height: 1.3rem;
        }
        .wc-hcc-vq-options table td select{
            width:90%;
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
		padding: 10px 0 20px;
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
		margin-bottom: 30px;
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
		margin: 0 15px;
		padding: 0 0 23px;
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
		padding-left: 15px;
		padding-right: 15px;
		margin: 0 0 20px;
	}

	.woocommerce-checkout .form-row.form-row-first,
	.woocommerce-checkout .form-row.form-row-last,
	.checkout_coupon .form-row.form-row-first,
	.checkout_coupon .form-row.form-row-last {
		width: 50%;
		float: left;
	}

	.woocommerce-checkout .form-row.form-row-wide,
	.checkout_coupon .form-row.form-row-wide {
		width: 100%;
	}

	.woocommerce-checkout .form-row.form-row-wide, 
	.checkout_coupon .form-row.form-row-wide,
	.woocommerce-account-fields .form-row {
		clear: both;
	}

	.woocommerce-checkout .form-row label,
	.checkout_coupon .form-row label {
		display: block;
		clear: both;
		font-size: 16px;
		color: #888;
		padding: 0 0 8px;
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
	    padding: 0 15px;
	    color: #888;
	}

	.woocommerce-checkout .form-row input[type="text"],
	.woocommerce-checkout .form-row input[type="email"],
	.woocommerce-checkout .form-row input[type="password"],
	.woocommerce-checkout .form-row input[type="tel"],
	.woocommerce-checkout .form-row textarea,
	.checkout_coupon .form-row input[type="text"] {
		display: block;
		clear: both;
		border: none;
		border: 1px solid #d7d7d7;
		background: #f7f7f7;
		border-radius: 2px;
		font-size: 16px;
		padding: 15px 12px 15px 12px;
		width: 100%;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .08);
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	.woocommerce-checkout form.login .button,
	.woocommerce-checkout form.checkout_coupon .button {
		display: block;
		clear: both;
		border: none;
		border: 1px solid #d7d7d7;
		font-size: 16px;
		font-weight: bold;
		line-height: 19px;
		padding: 15px 12px 15px 12px;
		width: 100%;
		color: #666;
		background-color: #e6e6e6;
		background-repeat: repeat-x;
		background-image: -moz-linear-gradient(top, #f4f4f4, #e6e6e6);
		background-image: -ms-linear-gradient(top, #f4f4f4, #e6e6e6);
		background-image: -webkit-linear-gradient(top, #f4f4f4, #e6e6e6);
		background-image: -o-linear-gradient(top, #f4f4f4, #e6e6e6);
		background-image: linear-gradient(top, #f4f4f4, #e6e6e6);
	}

	.checkout_coupon p:not(.form-row):first-child {
	    display: none;
	}

	.woocommerce-checkout form.login .button {
		margin-bottom: 10px;
	}

	.woocommerce-checkout .form-row select {
		display: block !important;
		clear: both !important;
		border: none !important;
		border: 1px solid #d7d7d7 !important;
		background: #f7f7f7 !important;
		border-radius: 2px !important;
		font-size: 16px !important;
		padding: 15px 12px 15px 12px !important;
		width: 100% !important;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .08) !important;
		-webkit-box-sizing: border-box !important;
		-moz-box-sizing: border-box !important;
		box-sizing: border-box !important;
	}

	.wc-credit-card-form {
		clear: both;
	}

	ul.wc-saved-payment-methods {
		padding: 10px 30px;
	}

	ul.wc-saved-payment-methods li {
		list-style: none;
		clear: both;
	}

	.woocommerce-checkout .form-row .input-text.wc-credit-card-form-card-cvc {
		width: 100% !important;
	}

	.woocommerce-checkout .wc_payment_methods {
		padding: 0 5px 20px 5px;
	}
	
	.woocommerce-checkout .wc_payment_methods li.wc_payment_method {
		list-style: none;
		clear: both;
		line-height: 35px;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method:only-child input[type="radio"] {
		display: none;
	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > input[type="radio"] {
		margin: 0 0 0 1em;
	}

/*	.woocommerce-checkout .wc_payment_methods li.wc_payment_method.payment_method_ocupaypal > input[type="radio"] {
		margin: 1.5em 0 0 1.5em;
	}*/

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > label {
		padding: 0 5px;
		font-size: 16px;
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
			/*width: 42px;*/
		}

		.woocommerce-checkout .wc_payment_methods li.wc_payment_method > label span img:first-child {
			padding-left: 0;
		}

	}

	.woocommerce-checkout .wc_payment_methods li.wc_payment_method > .payment_box {
		margin-top: 20px;
		margin-left: 0;
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
		margin: 0 15px 20px;
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
		background: #a8d92f;
		background: -moz-linear-gradient(top, #a8d92f 0, #7ec620 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #a8d92f), color-stop(100%, #7ec620));
		background: -webkit-linear-gradient(top, #a8d92f 0, #7ec620 100%);
		background: -o-linear-gradient(top, #a8d92f 0, #7ec620 100%);
		background: -ms-linear-gradient(top, #a8d92f 0, #7ec620 100%);
		background: linear-gradient(to bottom, #a8d92f 0, #7ec620 100%);
		filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#a8d92f', endColorstr='#7ec620', GradientType=0);
		border: none;
		cursor: pointer;
		font-family: Lato, Arial, Helvetica, sans-serif;
		font-size: 30px;
		color: #fff;
		padding: 20px 15px;
		width: 100%;
		height: auto;
		line-height: auto;
		border-radius: 3px;
		text-transform: uppercase;
		text-decoration: none;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, .2);
		box-shadow: inset 0 -4px 0 rgba(0, 0, 0, .08);
		margin: 10px auto;
	}

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
	.page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept {
		width: 60%;
	}

	.woocommerce-checkout .form-row #place_order:hover,
	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept:hover,
	.page .wc_1cu_custom_offer_btn.wc_1cu_custom_offer_accept:hover {
		background: #bbec42;
		background: -moz-linear-gradient(top, #bbec42 0, #8ed72f 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #bbec42), color-stop(100%, #8ed72f));
		background: -webkit-linear-gradient(top, #bbec42 0, #8ed72f 100%);
		background: -o-linear-gradient(top, #bbec42 0, #8ed72f 100%);
		background: -ms-linear-gradient(top, #bbec42 0, #8ed72f 100%);
		background: linear-gradient(to bottom, #bbec42 0, #8ed72f 100%);
		filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#bbec42', endColorstr='#8ed72f', GradientType=0);
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

	if( gb_hcc_template_field( 'color_footer', FALSE ) )
	{
?>
	.footer-decoration .middle-container div p {
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
	.about-product {
		background-color: <?php gb_hcc_template_field('color_headline'); ?>;
	}

<?php 

	}

	if( gb_hcc_template_field( 'color_button_1', FALSE ) )
	{

?>
	.woocommerce-checkout .form-row #place_order,
	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn.wc_1cu_default_offer_accept,
	.woocommerce-checkout .form-row #place_order:hover,
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
	.header .logo {
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

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_btns .wc_1cu_default_offer_btn,
	.page .wc_1cu_custom_offer_btn {
		text-align: center;
	}

	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_price,
	.page .wc_1cu_default_offer_page .wc_1cu_default_offer_variation_price {
		font-size: 1.6em !important;
		color: #000 !important;
	}
	</style>
</head>
<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

<div class="wrapper">
	<div class="wrapper-decoration">
		<header class="header">
			<div class="middle-container">
				<div class="logo"><?php gb_hcc_template_field('marketplace_image'); ?></div>
				<div class="clear"></div>
				<?php 

					if( gb_hcc_template_field( 'support_email', FALSE ) )
					{

				?>
				<div class="help"><h1><?php esc_html_e( 'Need Help?', 'gb-wc-hcc' ); ?> <?php gb_hcc_template_field('support_email'); ?></h1></div>
				<?php 

					}

				?>
			</div>
		</header>

		<div class="main-container">
			<div class="middle-container">

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

				<div class="about-product">
					<?php gb_hcc_template_field('product_image'); ?>
					<div>
						<hgroup>
							<h1 class="has-image"><?php gb_hcc_template_field('product_title'); ?></h1>
							<div class="customize-text">
								<h2><?php gb_hcc_template_field('product_description'); ?></h2>
							</div>
						</hgroup>
					</div>
					<?php
						if( gb_hcc_template_field('fields_guarantee', FALSE) )
						{
					?>
						<div class="money-back-color days">
							<img src="<?php echo plugins_url( 'assets/images/100.png', __FILE__ ); ?>" class="money-back-color">
						</div>
					<?php
						}
					?>
				</div>

				<div class="checkout-forms">
				
					<!-- CHECKOUT CONTAINER LEFT SIDE -->
					<div class="col-md-7 checkout-input-container">

						<!-- SIDEBAR TEXT -->
						<div class="trust-panel whats-inside hide-on-desktop show-991">
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

						</div>

						<div class="trust-point-wrap hide-on-desktop show-991">

							<?php 
								gb_hcc_template_field( 
									'trustpoints', 
									TRUE,
									'	<div class="trust-point trust-point-guarantee">
											<div class="trust-point-image">[trustpoint_image]</div>
											<div class="trust-point-title">[trustpoint_title]</div>
											<div class="trust-point-text">[trustpoint_text]</div>
										</div>'
								);
							?>

						</div>
						<!-- END SIDEBAR TEXT -->

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

					<!-- END CHECKOUT CONTAINER LEFT SIDE -->

					<!-- CHECKOUT CONTAINER RIGHT SIDE -->
					<div class="col-md-5">
						<div class="trust-panel whats-inside hide-991">
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
						</div><!-- close trust-panel -->

						<div class="three-sections-wrap hide-991">
							<div class="three-sections-one"></div>
							<div class="three-sections-two"></div>
							<div class="three-sections-three"></div>
							<div class="clear"></div>
						</div>

						<div class="trust-point-wrap hide-991">

							<?php 
								gb_hcc_template_field( 
									'trustpoints', 
									TRUE,
									'	<div class="trust-point trust-point-guarantee">
											<div class="trust-point-image">[trustpoint_image]</div>
											<div class="trust-point-title">[trustpoint_title]</div>
											<div class="trust-point-text">[trustpoint_text]</div>
										</div>'
								);
							?>

						</div>
					</div>
					<!-- CHECKOUT CONTAINER RIGHT SIDE -->
				</div>

			<?php

					}

				}

			?>

			</div>
		</div>

		<footer class="footer">
			<div class="footer-decoration">
				<div class="middle-container">
					<div>
						<div><?php gb_hcc_template_field('custom_html_footer'); ?></div>

						<p><?php esc_html_e( 'Copyright &copy;', 'gb-wc-hcc' ); ?> <?php echo esc_html( date('Y') . ' ' . get_bloginfo('name') ); ?> - <?php esc_html_e( 'All Rights Reserved', 'gb-wc-hcc' ); ?></p>
					</div>
				</div><!-- close middle-container -->
			</div><!-- close footer-decoration -->                       
		</footer><!-- close footer -->

	</div><!-- close wrapper-decoration -->
</div><!-- close wrapper -->

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
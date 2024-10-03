<?php if( !defined( 'ABSPATH' ) ) die(); ?><!DOCTYPE html>
<html style="height: 100%;" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <meta name="robots" content="noindex">

    <meta name="generator" content="Handsome Checkout for WooCommerce">

    <!-- (c) 2016-2020 Bogdan Grigoruk (bogdanfix@gmail.com) -->

    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11"/>

	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/font-awesome.css', __FILE__ ); ?>">

    <?php wp_head(); ?>

    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/template-3.css', __FILE__); ?>">

    <style type="text/css">
    .wc_hcc_hide {
		display: none;
	}
    .woocommerce-additional-fields > h3 {
        display: none;
    }
    .marketplace-image {
        height: 35px;
        width: auto;
    }
    .m-t-10 {
        margin-top: 10px;
    }
    label {
        font-size: 14px;
        color: #757575;
    }
    div#customer_details {
    	margin-bottom: 30px;
    }

    .woocommerce-checkout form.login p {
        margin: 10px 0;
    }

    form.login {
        margin-bottom: 10px;
    }

    form.login button[type="submit"] {
        height: auto;
        margin: 10px 0;
        font-size: 17px;
        line-height: 30px;
    }

    .woocommerce-checkout-subtitle {
    	display: none;
    }
    .woocommerce-billing-fields .woocommerce-checkout-subtitle {
    	display: block;
    }
    .woocommerce-checkout-subtitle {
        color: #928780;
        font-size: 11px;
        line-height: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .form-row input[type="text"],
    .form-row input[type="email"],
    .form-row input[type="tel"],
    .form-row input[type="password"],
    .form-row textarea,
    .form-row select {
        display: block;
        width: 100%;
        height: 45px;
        color: #1f1f1f;
        font-size: 14px;
        line-height: 45px;
        padding: 0 15px;
        border: 1px solid #dcd9d6;
        margin-bottom: 10px;
    }
    .woocommerce-checkout .form-row select {
    	text-indent: 7px;
    }
    h2, h3 {
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0 0 10px;
    }
    .checkout_coupon {
        display: block;
        clear: both;
        background: #f6f6f6;
        padding: 20px;
        margin: 10px 0;
    }
    .checkout_coupon .form-row {
        display: inline;
    }
    .checkout_coupon p:not(.form-row):first-child {
        display: none;
    }
    input[name="apply_coupon"],
    button[name="apply_coupon"] {
        display: inline-block;
        line-height: 18px;
        padding: 15.5px 27px;
        transition: all 0.2s;
        vertical-align: middle;
        padding: 11px 15px;
        height: auto;
        text-align: center;
        text-decoration: none;
        text-transform: none;
        font-size: 16px;
        width: 38%;
    }
    #coupon_code {
        color: #000;
        font-weight: bold;
        padding: 0 10px;
        height: 40px;
        border: 1px dashed #d9e0e9;
        vertical-align: middle;
        display: inline-block;
        width: 60%;
        margin: 0;
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
    .day30 .content {
        padding-left: 0;
    }
    .day30 img {
        position: static;
        width: 110px;
        float: left;
        margin: 20px 0;
        margin-right: 10px;
    }
    #order_review {
        margin-bottom: 15px;
    }
    .shop_table {
        display: none;
        width: 100%;
        border: 1px solid #dcd9d6;
        border-bottom:0;
    }
    .shop_table thead {
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
    .shop_table tr {
        border-bottom: 1px solid #dcd9d6;
    }
    .shop_table thead:after,
    .shop_table tbody:after,
    .shop_table tfoot:after,
    .shop_table tr:after{
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
    .shop_table .order-total {
        border-bottom: 1px solid #dcd9d6;
        padding-bottom: 20px;
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
    }
    .wc_payment_methods input[type=radio] {
        margin: 0;
    }
    .wc_payment_method {
        padding: 5px 0;
    }
    .wc_payment_method label {
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
    .woocommerce-checkout-total-price,
    .woocommerce-checkout-total-label {
        display: inline-block;
    }
    .woocommerce-checkout-total-price {
        float: right;
    }
    .woocommerce-checkout-price {
        padding: 15px;
        border:1px solid #dcd9d6;
        background: #f6f6f6;
        font-weight: 600;
    }
    .form-row.wc-terms-and-conditions {
        margin: 10px 0;
    }
    .woocommerce-privacy-policy-text {
        padding: 10px 0;
        color: #888;
    }
    .woocommerce-terms-and-conditions-wrapper .form-row label {
        width: 100%;
    }
    .woocommerce-terms-and-conditions-wrapper .form-row label span {
        position: relative;
        padding-left: 0;
        color: #888;
    }
    .woocommerce-terms-and-conditions-wrapper .form-row label input[type="checkbox"] {
        display: inline;
    }
    .woocommerce-terms-and-conditions-wrapper .form-row label span:before,
    .woocommerce-terms-and-conditions-wrapper .form-row label span:after {
        display: none;
    }
    .woocommerce-checkout .form-row #place_order {
        margin-top: 15px;
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
    #ship-to-different-address .checkbox span {
		text-transform: none;
    	font-weight: normal;
    }

    #ship-to-different-address {
        display: none;
    }
    .wc-hcc-vq-options {
        margin-bottom: 30px;
    }
    .vq-options-main-title{
        color: #7543f2;
        margin: 35px 0 10px;
        line-height: normal !important;
    }
    .wc-hcc-vq-options table td.wc-hcc-vq-options-header h4{
        font-size:14px;
    }
    .wc-hcc-vq-options-qty select{
        display: inline-block;
        margin-bottom: 0;
    }
    .wc-hcc-vq-options-item-wrap{
        line-height: 18px;
        vertical-align: middle;
        width: 95%;
    }
    .wc-hcc-vq-options table td{
        vertical-align: middle;
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
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(1){
        width: 60%;
    }
    .wc-hcc-vq-options-table.simple_quantity_table td:nth-of-type(2){
        width: 40%;
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
    input.wc-hcc-vq-option-selection{
        vertical-align: text-bottom;
    }
    input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap{
        width:85%;
    }
    .wc-hcc-vq-options-item-wrap{
        padding-left: 0 !important;
    }
    .simple_quantity_table .wc-hcc-vq-options-item-wrap, .simple_variations_table .wc-hcc-vq-options-item-wrap {
        padding-left: 10px !important;
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

    @media (max-width: 991px) {
        .simple_variations_table .wc-hcc-vq-options-item-wrap{
            width:80%;
        }
        .wc-hcc-vq-options table td:nth-of-type(2){
            width: 60%;
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
    <?php 

        if( gb_hcc_template_field( 'fields_shipping', FALSE ) )
        {

    ?>

    #ship-to-different-address {
        display: block;
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
    	#top-line {
    		background: <?php gb_hcc_template_field('color_header'); ?>;
    	}
    <?php

    	}

    	if( gb_hcc_template_field( 'color_footer', FALSE ) )
    	{
    ?>
    	.footer-component h5, .footer-component p {
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
    	h1.title,
    	h2.title,
    	h2.list-title,
    	.woocommerce-billing-fields > h3,
    	.woocommerce-shipping-fields > h3,
        .vq-options-main-title,
    	.woocommerce-checkout #order_review_heading {
    		color: <?php gb_hcc_template_field('color_headline'); ?>;
    	}

        form.login button[type="submit"],
        input[name=apply_coupon] {
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
        #top-line .wrap {
            text-align: <?php gb_hcc_template_field('marketplace_image_align'); ?>;
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


<div id="top-line">
    <div class="wrap">
        <?php gb_hcc_template_field('marketplace_image'); ?>
    </div>
</div>
<div id="wrap">

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

    <div class="column">

	    <!-- SIDEBAR MOBILE -->

	    <div class="hide-on-desktop hide-on-tablet">

	    	<div class="product-description">

	    		<div class="product-description-image"><?php gb_hcc_template_field('product_image'); ?></div>
	    		
	    		<h1 class="title"><?php gb_hcc_template_field('product_title'); ?></h1>
	    		
	    		<div class="product-description-text">
	    			<?php gb_hcc_template_field('product_description'); ?>
	    		</div>

	    	</div>

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

	    	<div class="testimonials">

		        <h2 class="title"><?php gb_hcc_template_field('testimonials_title'); ?></h2>

		        <?php
			        gb_hcc_template_field(
			            'testimonials',
			            TRUE,
			            '	<div class="testimonial-item">
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
		        		'	<div class="brown-box [class]">
					            <h4>[trustpoint_title]</h4>

					            <div>
					                [trustpoint_image]
					                <p>[trustpoint_text]</p>
					                <div class="clear"></div>
					            </div>
					        </div>'
		        	);
		        ?>

		    </div>

	    </div>

	    <!-- SIDEBAR MOBILE END -->

        <?php
	        $checkout_code = do_shortcode('[woocommerce_checkout]');

	        if( empty( $checkout_code ) || trim( $checkout_code ) == '<div class="woocommerce"></div>' )
	        {
	            esc_html_e( 'Oops, there are no products in your cart.', 'gb-wc-hcc' );
	        }
	        else
	        {
	            echo $checkout_code;
	        }
        ?>

        <p class="security-note"><i class="fa fa-lock"></i> <?php esc_html_e( 'Payment secured by 256-bit encryption', 'gb-wc-hcc' ); ?></p>

        <div class="contacts hide-on-desktop hide-on-tablet">

        	<br />

            <h2 class="title m-t-10"><?php esc_html_e( 'Need Help? Contact Support', 'gb-wc-hcc' ); ?></h2>

            <p class="phone"><i class="fa fa-envelope pull-left"></i><span><?php esc_html_e( 'Email:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_email'); ?></p>

            <?php 

                if( gb_hcc_template_field( 'support_phone', FALSE ) )
                {

            ?>

            <p class="phone"><i class="fa fa-phone pull-left"></i><span><?php esc_html_e( 'Phone:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_phone'); ?></p>

            <?php

                }

            ?>

        </div>

    </div>

    <!-- SIDEBAR DESKTOP -->

    <div class="column hide-on-mobile">

    	<div class="product-description">

    		<div class="product-description-image"><?php gb_hcc_template_field('product_image'); ?></div>
    		
    		<h1 class="title"><?php gb_hcc_template_field('product_title'); ?></h1>
    		
    		<div class="product-description-text">
    			<?php gb_hcc_template_field('product_description'); ?>
    		</div>

    	</div>

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

    	<div class="testimonials">

	        <h2 class="title"><?php gb_hcc_template_field('testimonials_title'); ?></h2>

	        <?php
		        gb_hcc_template_field(
		            'testimonials',
		            TRUE,
		            '	<div class="testimonial-item">
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
	        		'	<div class="brown-box [class]">
				            <h4>[trustpoint_title]</h4>

				            <div>
				                [trustpoint_image]
				                <p>[trustpoint_text]</p>
				                <div class="clear"></div>
				            </div>
				        </div>'
	        	);
	        ?>

	    </div>

	    <div class="contacts">

	        <h2 class="title m-t-10"><?php esc_html_e( 'Need Help? Contact Support', 'gb-wc-hcc' ); ?></h2>

	        <p class="phone"><i class="fa fa-envelope pull-left"></i><span><?php esc_html_e( 'Email:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_email'); ?></p>

            <?php 

                if( gb_hcc_template_field( 'support_phone', FALSE ) )
                {

            ?>

            <p class="phone"><i class="fa fa-phone pull-left"></i><span><?php esc_html_e( 'Phone:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_phone'); ?></p>

            <?php

                }

            ?>

	    </div>

    </div>

    <!-- SIDEBAR DESKTOP END -->

    <?php

    		}
		}

    ?>

</div>
<footer class="mastfoot">
    <div class="wrap">
        <hr>
        <div>

            <div class="footer-component">
                <div><?php gb_hcc_template_field('custom_html_footer'); ?></div>

                <h5><?php echo esc_html( get_bloginfo('name') ); ?></h5>
                <p><?php echo esc_html( date('Y') . ' ' ); esc_html_e( 'All Rights Reserved', 'gb-wc-hcc' ); ?></p>
            </div>

        </div>
    </div>
</footer>

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
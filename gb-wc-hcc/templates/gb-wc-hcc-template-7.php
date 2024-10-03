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

    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/template-7.css', __FILE__ ); ?>">

    <style type="text/css">
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

    	if( gb_hcc_template_field( 'color_footer', FALSE ) )
    	{
    ?>
    	footer .footer-inner p {
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
    </style>
</head>

<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

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

    <div class="header">

        <div class="header-left">
            <?php gb_hcc_template_field('marketplace_image'); ?>
        </div>

        <div class="header-middle">
            <h1><?php gb_hcc_template_field('headline'); ?></h1>
            <h4><?php gb_hcc_template_field('subheadline'); ?></h1>
        </div>

        <div class="header-right">
            <?php gb_hcc_template_field('trustseal_top_image'); ?>
        </div>

        <div class="clear"></div>

    </div>

    <div class="column-main">

        <?php
            define( 'WOOCOMMERCE_CHECKOUT', TRUE );

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

    </div>

    <!-- SIDEBAR DESKTOP -->

    <div class="column-sidebar">

    	<div class="product-description">

    		<div class="product-description-top">
                <div class="product-description-image"><?php gb_hcc_template_field('product_image'); ?></div>
                <div class="product-description-benefits">
                    
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

                    <div class="ingredients">
                        <?php gb_hcc_template_field('ingredients_image'); ?>
                    </div>

                </div>
                <div class="clear"></div>
            </div>
    		
    		<h1 class="title"><?php gb_hcc_template_field('product_title'); ?></h1>
    		
    		<div class="product-description-text">
    			<?php gb_hcc_template_field('product_description'); ?>
    		</div>

    	</div>

        <div class="deals">

            <?php
                gb_hcc_template_field(
                    'deals',
                    TRUE,
                    '<div class="deal-item deal-[deal_type]">
                        <div class="deal-title">[deal_title]</div>
                        <div class="deal-text">[deal_description]</div>
                        <div class="deal-product">[deal_product]</div>
                    </div>'
                );
            ?>

        </div>

    	<div class="testimonials">

	        <h2 class="title"><?php gb_hcc_template_field('testimonials_title'); ?></h2>

	        <?php
		        gb_hcc_template_field(
		            'testimonials',
		            TRUE,
                    '<div class="testimonial-item">
                        <div class="testimonial-image">[testimonial_image]</div>
						<div class="testimonial-text">
                            <div class="testimonial-title">
                                <span>[testimonial_title]</span>
                                <img src="' . plugins_url( 'assets/images/stars-5.png', __FILE__ ) . '" />
                            </div>
                            [testimonial_text]
                        </div>
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
	        		'<div class="trustpoint [class]">
			            <h3>[trustpoint_title]</h3>

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

	        <h3 class="title"><?php esc_html_e( 'Need Help? Contact Support', 'gb-wc-hcc' ); ?></h3>

	        <p class="phone"><i class="fa fa-envelope"></i><span><?php esc_html_e( 'Email:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_email'); ?></p>

            <?php 

                if( gb_hcc_template_field( 'support_phone', FALSE ) )
                {

            ?>

            <p class="phone"><i class="fa fa-phone"></i><span><?php esc_html_e( 'Phone:', 'gb-wc-hcc' ); ?></span> <?php gb_hcc_template_field('support_phone'); ?></p>

            <?php

                }

            ?>

	    </div>

    </div>

    <div class="clear"></div>

    <!-- SIDEBAR DESKTOP END -->

    <div class="trustseal-bottom">
        <?php gb_hcc_template_field('trustseal_bottom_image'); ?>
    </div>

    <?php

    		}
		}

    ?>

</div>
<footer>
    <div class="wrap">
        <div class="footer-inner">
            <div><?php gb_hcc_template_field('custom_html_footer'); ?></div>

            <p><?php echo esc_html( get_bloginfo('name') ); ?></p>
            <p><?php echo esc_html( date('Y') . ' ' ); esc_html_e( 'All Rights Reserved', 'gb-wc-hcc' ); ?></p>
        </div>
    </div>
</footer>

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('.ingredients-btn').on( 'click', function( e ){

        e.preventDefault();

        jQuery('.ingredients-overlay').show().animate( { 'opacity': 1 }, 500 );

    });

    jQuery('.ingredients-overlay').on( 'click', function( e ){

        e.stopPropagation();

        jQuery('.ingredients-overlay').animate( { 'opacity': 0 }, 500, function(){

            jQuery(this).hide();
        });

    });
});
</script>

<?php gb_hcc_custom_admin_bar(); ?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
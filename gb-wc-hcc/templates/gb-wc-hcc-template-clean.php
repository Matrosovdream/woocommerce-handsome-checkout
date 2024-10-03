<?php if( !defined( 'ABSPATH' ) ) die(); ?><!DOCTYPE html>
<html style="height: 100%;" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <meta name="robots" content="noindex">

    <meta name="generator" content="Handsome Checkout by BogdanFix">

    <!-- (c) 2016-2020 Bogdan Grigoruk (bogdanfix@gmail.com) -->
    
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    
	<?php wp_head(); ?>
</head>
<body style="position: relative; min-height: 100%; top: 0px;" <?php body_class(); ?>>

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(document.body).bind( 'updated_checkout', function(){

        parent.gb_hcc_resize_iframe( parent.hcc_form_frame );
    });

    jQuery(document).on( 'change', 'input[name="payment_method"]', function(){

        var timerId = setTimeout( function(){

            parent.gb_hcc_resize_iframe( parent.hcc_form_frame );

        }, 500 );
    });

    jQuery(document).on( 'click', '.btn-step1, .btn-step2, .link-back1, .link-back2, .steps .step1', function(){

        var timerId = setTimeout( function(){

            parent.gb_hcc_resize_iframe( parent.hcc_form_frame );

        }, 500 );
    });

});
</script>

<?php

if( empty( $_GET['hcc_form_id'] ) )
{
    $_GET['hcc_form_id'] = 0;
}

$form_id = intval( $_GET['hcc_form_id'] );

$result = gb_hcc_form_shortcode_display( 
    array( 
        'id' => $form_id,
    )
);

echo $result;

?>

<div class="wc_hcc_hide">
<?php wp_footer(); ?>
</div>

</body>
</html>
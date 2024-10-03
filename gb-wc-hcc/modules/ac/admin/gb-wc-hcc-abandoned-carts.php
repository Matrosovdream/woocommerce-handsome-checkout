<?php
/**
 * MODULE: support for HC Abandoned Carts
 */

if( !defined( 'ABSPATH' ) ) die();

?>
<style type="text/css">
.wrap div.notice {
    display: none !important;
}
</style>
<div class="wrap">
    <h2><?php esc_html_e( 'Abandoned Carts', 'gb-wc-hcc' ); ?></h2>

<?php

$gb_admin_menu = array(
    'ac_list'   => __( 'Your Abandoned Carts', 'gb-wc-hcc' ),
    'ac_zapier' => __( 'Connect Zapier', 'gb-wc-hcc' ),
);

if( empty( $_GET['page'] ) )
{
    $_GET['page'] = '';
}

if( empty( $_GET['tab'] ) )
{
    $_GET['tab'] = 'ac_list';
}

echo '<ul class="subsubsub">';

foreach( $gb_admin_menu as $k => $m )
{
    $_GET['page'] = sanitize_text_field( $_GET['page'] );
    
    echo '<li class="' . esc_attr( $k ) . '"><a href="?post_type=handsome-checkout&page=' . esc_attr( $_GET['page'] ) . '&tab=' . esc_attr( $k ) . '" class="' . ( ( $_GET['tab'] == $k ) ? 'current' : '' ) . '">' . esc_html( $m ) . '</a></li>';

    if( $m != end( $gb_admin_menu ) )
    {
        echo ' | ';
    }
}

echo '</ul><div class="clear"></div>';

if( $_GET['tab'] == 'ac_list' )
{
    $gb_hcc_ac_table = json_decode( get_option( GB_HCC_AC_TABLE ), TRUE );

    ?>
        <div class="hcc-hiw-toggle"><p><span style="font-size: 14px; font-weight: bold; text-decoration: underline; cursor: help;"><?php esc_html_e( 'How It Works?', 'gb-wc-hcc' ); ?></span></p></div>
        <div class="hcc-hiw-block" style="display: none;">
            <p>1. <?php esc_html_e( 'A user types in their email on your checkout form.', 'gb-wc-hcc' ); ?></p>
            <p>2. <?php esc_html_e( 'As soon as the user clicks anywhere outside the email field, the data is captured and held for 20 minutes.', 'gb-wc-hcc' ); ?></p>
            <p>3. <?php esc_html_e( 'If the checkout is completed, the postponed data is not sent.', 'gb-wc-hcc' ); ?></p>
            <p>4. <?php esc_html_e( 'If checkout is not completed in 20 min, the data is sent to the integration (Zapier).', 'gb-wc-hcc' ); ?></p>
        </div>
        <div><p><b><?php esc_html_e( 'Total carts catched:', 'gb-wc-hcc' ) . ' ' . count( $gb_hcc_ac_table ); ?></b></p></div>
    <?php

    if ( $gb_hcc_ac_table ):
        ?>
        <div>
            <p>
                <input id="hcc-ac-export" type="submit" class="button" value="<?php esc_html_e( 'Export CSV', 'gb-wc-hcc' ); ?>">
                <input id="hcc-ac-clear-table" type="submit" class="button" value="<?php esc_html_e( 'Clear table', 'gb-wc-hcc' ); ?>">
            </p>
        </div>
        <div>
            <table class="wp-list-table widefat fixed">
                <thead>
                <tr>
                    <th><?php esc_html_e( 'Email', 'gb-wc-hcc' ); ?></th>
                    <th><?php esc_html_e( 'Products', 'gb-wc-hcc' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'gb-wc-hcc' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ( $gb_hcc_ac_table as $email => $records ):
                    foreach( $records as $record):
                        ?>
                        <tr>
                            <td><?php echo esc_html( $email ); ?></td>
                            <td>
                            <?php
                                $user_cart = '';
    
                                if( !empty( $record['cart'] ) )
                                {
                                    foreach( $record['cart'] as $item )
                                    {
                                        $product = wc_get_product( $item['product_id'] );

                                        if( !empty( $product ) )
                                        {
                                            echo '<div class="hcc-ac-product-name"><span class="hcc-ac-product-qty">' . esc_html( $item['qty'] ) . '</span> &times; ' . esc_html( $product->get_name() ) . '</div>';
                                        }
                                        else
                                        {
                                            echo '<div class="hcc-ac-product-name"><i>' . __( 'Product not found', 'gb-wc-hcc' ) . '</i></div>';
                                        }
                                    }
                                }
                            ?>
                            </td>
                            <td><?php if( !empty( $record['created_at'] ) ) echo esc_html( $record['created_at'] ); ?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php else:?>

        <p><?php esc_html_e( 'Oops, looks like you don\'t have any abandoned cart sessions recorded yet.', 'gb-wc-hcc' ); ?></p>

    <?php endif; ?>

    <script type="text/javascript">
    jQuery( document ).ready( function() {

        jQuery( "#hcc-ac-export" ).click( function( e ) {

            var data = {action: "hcc_ac_export"};

            jQuery.post( ajaxurl, data, function( response ) {
                e.preventDefault();
                window.location.href = response;
            });

            return false;
        });

        jQuery( "#hcc-ac-clear-table" ).click( function( e ) {

            if( confirm( "<?php esc_html_e( 'Are you sure?', 'gb-wc-hcc' ); ?>") )
            {
                var data = {action: "hcc_ac_clear_table"};

                jQuery.post( ajaxurl, data, function( response ) {
                    location.reload();
                });
            }

            return false;
        });

        jQuery( ".hcc-hiw-toggle" ).click(function(){

            var $block = jQuery( ".hcc-hiw-block" );

            if( $block.is(":visible") )
            {
                $block.slideUp(500);
            }
            else
            {
                $block.slideDown(500);
            }
        });
    });
    </script>

<?php
} 
elseif ( $_GET['tab'] == 'ac_zapier' )
{
    if( isset( $_POST['btn_save'] ) && empty( $_GET['edit'] ) )
    {
        update_option( 
            GB_HCC_AC_ZA, 
            isset( $_POST['gb-zapier-activate'] ) ? 
                sanitize_text_field( $_POST['gb-zapier-activate'] ) : 
                '' 
        );

        update_option( 
            GB_HCC_AC_ZHU, 
            isset( $_POST['gb-zapier-hook-url'] ) ? 
                sanitize_text_field( $_POST['gb-zapier-hook-url'] ) : 
                '' 
        );
    }

    $zapier_activate = get_option( GB_HCC_AC_ZA );
    $zapier_hook_url = get_option( GB_HCC_AC_ZHU );

    ?>
    <form method="POST">
        <fieldset>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label>
                            <?php esc_html_e( 'Integrate', 'gb-wc-hcc' ); ?>
                        </label>
                    </th>
                    <td>
                        <p>
                            <label for="gb-zapier-activate">
                                <input type="checkbox" id="gb-zapier-activate" name="gb-zapier-activate" value="1" <?php checked( $zapier_activate, '1' ); ?>>
                                <?php esc_html_e( 'Send to Zapier', 'gb-wc-hcc' ); ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
                            <?php esc_html_e( 'Webhook URL', 'gb-wc-hcc' ); ?>
                        </label>
                    </th>
                    <td>
                        <p>
                            <label for="gb-zapier-hook-url">
                                <input type="url" id="gb-zapier-hook-url" name="gb-zapier-hook-url" value="<?php echo esc_attr( $zapier_hook_url ); ?>" style="width: 100%;">
                            </label>
                        </p>
                        <?php if ( $zapier_activate && empty( $zapier_hook_url ) ): ?>
                            <p class="description" style="color: #D00;">
                                <?php esc_html_e( 'You should insert webhook URL from Zapier here to finish configuration.', 'gb-wc-hcc' ); ?>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <div>
            <h2><?php esc_html_e( 'Data format for Zapier', 'gb-wc-hcc' ) ?></h2>

            <p><?php esc_html_e( 'We will send abandoned cart data to Zapier in this format:', 'gb-wc-hcc' ) ?></p>

            <?php

                $sent_data = array(
                    'email' => 'user@example.com',
                    'date'  => '2000-01-1 00:00:00',
                    'cart'  => array(
                        array(
                            'product_id' => 1,
                            'qty'        => 1
                        )
                    )
                );

            ?>

            <pre style="background: #FFF; border: 1px solid #CCC; padding: 10px; margin: 0;"><?php echo json_encode( $sent_data, JSON_PRETTY_PRINT ); ?></pre>

        </div>
        <div>
            <p>
                <input type="hidden" name="created" value="<?php echo date( 'Y-m-d' ); ?>" readonly="readonly">
                <button type="submit" name="btn_save" id="btn_save" class="button button-primary"><?php esc_html_e( 'Save changes', 'gb-wc-hcc' ) ?></button>
            </p>
        </div>
    </form>
    <?php
}

?>
</div>
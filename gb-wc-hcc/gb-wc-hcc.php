<?php
/*
Plugin Name: WooCommerce Handsome Checkout Pages by BogdanFix
Plugin URI: https://bogdanfix.com/handsome-checkout-pages-for-woocommerce/
Description: Easily setup beautiful checkout pages, that help customers purchase your products easier and faster. High conversion proven design templates included. Using "Embed Checkout Forms" feature you can put the WooCommerce checkout form into any page.
Author: BogdanFix
Version: 1.5.4-beta21
Author URI: https://bogdanfix.com
Text Domain: gb-wc-hcc
Domain Path: /lang

WC requires at least: 3.0.0
WC tested up to: 4.0.0

Copyright: (c) 2016-2020 BogdanFix, Inc (email: bogdan@bogdanfix.com)
*/

/*
WARNING: If you make changes to the actual code of Handsome Checkout,
your changes will be LOST when we send out an update. Please, save your
changes, if you feel they are necessary. We do not offer support for
custom coding to Handsome Checkout. If you’ve created a customization
you think we should add please let us know and we can look at
adding it to the core. Thank you!
*/

define( 'GB_HCC_NAME', 'WooCommerce Handsome Checkout Pages' );
define( 'GB_HCC_VER', '1.5.3' );
define( 'GB_HCC_ID', 'gb_wc_hcc' );
define( 'GB_HCC_FILE', 'gb-wc-hcc/gb-wc-hcc.php' );

/**
 * Required functions
 *
 * @since 1.5.4
 */

require_once plugin_dir_path( __FILE__ ) . 'includes/gb-wc-hcc-dependencies.php';

/**
 * Check if WooCommerce is active, and 
 * if it isn't, disable the plugin.
 *
 * @since 1.5.4
 */

if( !gb_hcc_is_wc_active() )
{
    add_action( 'admin_notices', 'gb_hcc_inactive_notice' );

    return;
}

/**
 * MODULE: Embed Forms
 *
 * @since 1.4.0
 */

require_once plugin_dir_path( __FILE__ ) . 'gb-wc-hcc-embed-forms.php';

/**
 * MODULE: Abandoned Carts
 *
 * @since 1.5.0
 */

require_once plugin_dir_path( __FILE__ ) . 'modules/ac/abandoned_carts.php';

/**
 * Get the update info from the server
 *
 * @since 1.2.0
 */

function gb_hcc_check_update()
{
    // load textdomain

    load_plugin_textdomain( 'gb-wc-hcc', FALSE, basename( dirname( __FILE__ ) ) . '/lang' );

    // check for updates & cache api response

    $update = get_site_transient('gb_wc_hcc_update_data');

    if( $update === FALSE )
    {
        $status = 'release';

        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['beta_subscription'] ) )
        {
            $status = 'beta';
        }

        if( mb_strpos( $_SERVER['SERVER_NAME'], 'www.' ) === 0 )
        {
            $_SERVER['SERVER_NAME'] = mb_substr( $_SERVER['SERVER_NAME'], 4 );
        }

        $update = wp_remote_get( 'https://bogdanfix.com/cp/api/index.php?r=update&k=' . urlencode( $options['key'] ) . '&p=' . urlencode( GB_HCC_ID ) . '&d=' . urlencode( $_SERVER['SERVER_NAME'] ) . '&s=' . urlencode( $status ) );

        if( is_array( $update ) )
        {
            $update = $update['body']; // use the content

            $update = json_decode( $update, TRUE );

            if( !empty( $update ) )
            {
                //
            }
            else
            {
                $update = array(
                    'status' => 'error',
                    'error' => 'json_parse_error',
                    'error_text' => __( 'JSON parse error.', 'gb-wc-hcc' ),
                );
            }
        }

        // if WP_Error response received

        else
        {
            $update = array(
                'status' => 'error',
                'error' => 'request_error',
                'error_text' => $update->get_error_message(),
            );
        }

        update_option( 'gb_wc_hcc_update_data', $update );

        set_site_transient( 'gb_wc_hcc_update_data', $update, 21600 ); // 6
    }
}
add_action( 'init', 'gb_hcc_check_update' );

/**
 * Insert the plugin's update info into the WP update list
 *
 * @param StdClass $plugins update list
 * @return array modified update list
 * @since 1.2.0
 */

function gb_hcc_inject_update( $plugins )
{
    $update = get_option( 'gb_wc_hcc_update_data' );

    if(
        is_array( $update ) &&
        !empty( $update['status'] ) &&
        $update['status'] == 'ok' &&
        !empty( $update['result'] ) &&
        !empty( $update['result']['update_version'] ) &&
        !empty( $update['result']['update_description'] )
    )
    {
        $current_version = intval( str_replace( '.', '', GB_HCC_VER ) );

        // compare versions

        $update_version = intval( str_replace( '.', '', $update['result']['update_version'] ) );
        $update_description = $update['result']['update_description'];
        $update_url = '';

        if( !empty( $update['result']['update_url'] ) )
        {
            $update_url = $update['result']['update_url'];
        }

        if( $current_version < $update_version )
        {
            $plugin = array(
                'id' => 999109,
                'slug' => GB_HCC_ID,
                'plugin' => GB_HCC_FILE,
                'new_version' => $update['result']['update_version'],
                'url' => 'https://bogdanfix.com/handsome-checkout-pages-for-woocommerce/',
                'package' => $update_url,
            );

            $plugin = (object) $plugin;

            if( isset( $plugins->response ) ) {
                $plugins->response[ GB_HCC_FILE ] = $plugin;
            }
            
        }
    }

    return $plugins;
}
add_filter( 'site_transient_update_plugins', 'gb_hcc_inject_update' );

/**
 * Displays admin message
 *
 * @since 1.0.0
 */

function gb_hcc_admin_display_message()
{
    $screen = get_current_screen();

    if( !empty( $screen->id ) )
    {
        $screen = $screen->id;
    }

    $user_id = get_current_user_id();

    $options = get_option('gb_wc_hcc_options');

    // if plugin is not activated

    if( empty( $options['key'] ) && empty( $_POST['gb_key'] ) )
    {
        echo '<div class="notice notice-error"><p>';

        printf(
            __( '<b>%s is not activated.</b> Please, activate it <a href="%s">here</a> to receive regular updates, new features and support.', 'gb-wc-hcc' ),
            GB_HCC_NAME,
            'admin.php?page=' . GB_HCC_ID . '&tab=settings'
        );

        echo '</p></div>';
    }

    // if update is available

    $update = get_option('gb_wc_hcc_update_data');

    if( is_array( $update ) && !empty( $update['status'] ) )
    {
        if(
            $update['status'] == 'ok' &&
            !empty( $update['result'] )
        )
        {
            $current_version = intval( str_replace( '.', '', GB_HCC_VER ) );

            // compare versions

            $update_version = intval( str_replace( '.', '', $update['result']['update_version'] ) );

            if( $current_version < $update_version )
            {
                echo '<div class="notice notice-warning"><p>';

                printf(
                    '<b>
                        ' . GB_HCC_NAME . ' ' . $update['result']['update_version'] . ' ' .
                    __( 'update is available', 'gb-wc-hcc' ) .
                    '.</b> <a href="%s" target="_blank">' . __( 'Check out what\'s new', 'gb-wc-hcc' ) . '</a> ' .
                    __( 'or', 'gb-wc-hcc' ) . ' <a href="%s">' . __( 'go to Plugins menu to update', 'gb-wc-hcc' ) . '</a>.',

                    'https://bogdanfix.com/downloads/hcc/changelog.txt',
                    'admin.php?page=' . GB_HCC_ID . '&tab=settings'
                );

                echo '</p></div>';
            }
        }

        elseif(
            $update['status'] == 'error' &&
            !empty( $update['error'] ) &&
            $update['error'] == 'license_live'
        )
        {
            echo '<div class="notice notice-warning"><p>' . $update['error_text'] . '</p></div>';
        }
    }

    //

    if(
        !empty( $screen ) && (
            $screen == 'edit-handsome-checkout' ||
            $screen == 'handsome-checkout'
        )
    )
    {
        if(
            empty( $options['key'] ) ||
            ( is_array( $update ) && !empty( $update['notice'] ) && $update['notice'] == 'wrong_key' ) ||
            ( is_array( $update ) && !empty( $update['error'] ) && $update['error'] == 'license_live' )
        )
        {
            echo '<style type="text/css">form#post, #posts-filter, a.page-title-action, ul.subsubsub { display: none !important; }</style>';
        }
    }

    // hide welcome message

    if( !empty( $_GET['hcc_hide_wm'] ) )
    {
        update_user_meta( $user_id, 'hcc-hide-wm', '1' );
    }

    // welcome message

    if( !empty( $screen ) && $screen === 'plugins' ) //
    {
        $hide_welcome = get_user_meta( $user_id, 'hcc-hide-wm', TRUE );

        if( empty( $hide_welcome ) )
        {
            $hcc_count = wp_count_posts( 'handsome-checkout' );
            $hcc_count = $hcc_count->publish;

            if( $hcc_count == 0 && empty( $_GET['hcc_pointers'] ) )
            {
                echo '<style type="text/css">
                .woocommerce-message {
                    position: relative;
                    border-left-color: #cc99c2!important;
                    overflow: hidden;
                }
                .woocommerce-message a.button-primary {
                    background: #bb77ae;
                    border-color: #a36597;
                    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 1px 0 #a36597;
                    color: #fff;
                    text-shadow: 0 -1px 1px #a36597, 1px 0 1px #a36597, 0 1px 1px #a36597, -1px 0 1px #a36597;
                    display: inline-block;
                }
                .woocommerce-message a.button-primary:hover,
                .woocommerce-message a.button-primary:active,
                .woocommerce-message a.button-primary:focus {
                    background: #a36597;
                    border-color: #a36597;
                    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 1px 0 #a36597;
                }
                .woocommerce-message .twitter-share-button {
                    margin-top: -3px;
                    margin-left: 3px;
                    vertical-align: middle;
                }
                .hcc-dismiss-notice {
                    position: absolute;
                    top: 15px;
                    right: 12px;
                    text-decoration: none;
                    color: inherit;
                }
                </style>';

                echo '<div id="message" class="updated woocommerce-message wc-connect gb-wc-hcc-activated">
                        <div class="squeezer">
                            <h4>
                                <strong>' . esc_html__( 'WooCommerce Handsome Checkout Pages installed', 'gb-wc-hcc' ) . '</strong> – <em>' . esc_html__( 'You\'re ready to create new beautiful checkout pages!', 'gb-wc-hcc' ) . '</em>
                            </h4>
                            <p class="submit">
                                <a href="post-new.php?post_type=handsome-checkout&hcc_pointers=true&hcc_hide_wm=1" class="button button-primary">Add a Handsome Checkout Page</a>
                                <a href="admin.php?page=wc-settings&tab=hcc_settings_tab&hcc_hide_wm=1" class="docs button button-primary">Settings</a>
                                <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" style="position: static; visibility: visible; width: 76px; height: 28px;" title="Twitter Tweet Button" src="https://platform.twitter.com/widgets/tweet_button.2e9f365dae390394eb8d923cba8c5b11.en.html#dnt=false&id=twitter-widget-0&lang=en&size=l&text=Woot!%20I%20have%20Handsome%20Checkout%20Pages%20with%20%23WooCommerce%20now&time=' . esc_attr( time() ) . '&type=share&url=https%3A%2F%2Fbogdanfix.com%2Fhandsome-checkout-pages-for-woocommerce%2F&via=bogdanfix" data-url="https://bogdanfix.com/handsome-checkout-pages-for-woocommerce/"></iframe>
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </p>
                            <a href="admin.php?page=wc-settings&tab=hcc_settings_tab&hcc_hide_wm=1" class="hcc-dismiss-notice">&times;</a>
                        </div>
                    </div>';
            }
        }
    }

    // duplicate page and display the notice

    if( !empty( $screen ) && $screen == 'edit-handsome-checkout' )
    {
        if( !empty( $_GET['hcc_duplicate_created'] ) )
        {
            echo '<div class="notice updated"><p>' . esc_html__( 'Duplicate created!', 'gb-wc-hcc' ) . '</p></div>';
        }
    }
}
add_action( 'admin_notices', 'gb_hcc_admin_display_message' );

/**
 * Handle WP pointer hints
 *
 * @since 1.5.3
 */
 
function gb_hcc_wp_pointer()
{ 
    if( get_bloginfo( 'version' ) < '3.3' )
    {
        return;
    }

    $screen = get_current_screen();
    $screen_id = $screen->id;

    $pointers = array();

    if( $screen_id == 'handsome-checkout' )
    {
        $pointers['hccp1'] = array(
            'target' => '#gb-hcc-post-type',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Choose Type of Checkout', 'gb-wc-hcc' ),
                    __( 'The Handsome Checkout Pages extension allows to create two types of checkouts - Checkout Page and Embed Checkout Form (that you can insert into any page).<br /><br /> Let\'s go through the minimal setup and create your first page!', 'gb-wc-hcc' )
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp2'] = array(
            'target' => '#wc-hcc-select-product',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Pick a Product', 'gb-wc-hcc' ),
                    __( 'With Handsome Checkout you can create product-specific checkout pages. Start typing in the product name to pick one.<br /><br />Also you can replace your default WooCommerce checkout with custom checkout page in Handsome Checkout menu > Settings.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'left'
                ),
            ),
        );

        $pointers['hccp3'] = array(
            'target' => '#wc-hcc-product-title',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Enter a Product Title', 'gb-wc-hcc' ),
                    __( 'Basically, it is a title of your checkout page.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp4'] = array(
            'target' => '#wc-hcc-product-description',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Enter a Product Description', 'gb-wc-hcc' ),
                    __( 'It can be a short text describing your product.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp5'] = array(
            'target' => '#wc-hcc-template-name',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Pick a Template', 'gb-wc-hcc' ),
                    __( 'Select one of the checkout page templates we have for you.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp6'] = array(
            'target' => '#wc-hcc-fields-billing',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Select Checkout Fields', 'gb-wc-hcc' ),
                    __( 'Pick the fields you want to see on your checkout page. By default, you will have only "Email" field added.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp7'] = array(
            'target' => '#wc-hcc-trustpoints-fill',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Add Trust Points', 'gb-wc-hcc' ),
                    __( 'Click the "fill in" button to add the default trust points. Usually, trust points are used to talk about guarantee, privacy, security.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'bottom', 
                    'align' => 'left'
                ),
            ),
        );

        $pointers['hccp8'] = array(
            'target' => '#wc-hcc-save-btn',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Save Your Checkout', 'gb-wc-hcc' ),
                    __( 'Click "Update" to save the changes and see your new Handsome Checkout :)','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'top', 
                    'align' => 'middle'
                ),
            ),
        );

        $pointers['hccp9'] = array(
            'target' => '#post-body-content',
            'options' => array(
                'content' => sprintf( '<h3>%s</h3><p>%s</p>',
                    __( 'Done!', 'gb-wc-hcc' ),
                    __( 'Congratulations! Your first Handsome Checkout is done. Click on the link above to see what you have created.','gb-wc-hcc')
                ),
                'position' => array( 
                    'edge' => 'top', 
                    'align' => 'left'
                ),
            ),
        );
    }

    if( empty( $pointers ) )
    {
        return;
    }

    // get dismissed pointers

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', TRUE ) );
    $valid_pointers = array();

    // check pointers and remove dismissed ones

    foreach( $pointers as $pointer_id => $pointer )
    {
        // sanity check

        if( 
            in_array( $pointer_id, $dismissed ) || 
            empty( $pointer ) || 
            empty( $pointer_id ) || 
            empty( $pointer['target'] ) || 
            empty( $pointer['options'] ) 
        )
        {
            continue;
        }

        $pointer['pointer_id'] = $pointer_id;

        // add the pointer to $valid_pointers array

        $valid_pointers['pointers'][] = $pointer;
    }

    // no valid pointers? stop here

    if( empty( $valid_pointers ) )
    {
        return;
    }

    wp_enqueue_style( 'wp-pointer' );

    wp_enqueue_script( 'gb-wc-hcc-pointer', plugins_url( 'assets/js/gb-wc-hcc-pointer.js', __FILE__ ), array( 'wp-pointer' ) );

    wp_localize_script( 'gb-wc-hcc-pointer', 'hccPointer', $valid_pointers );
}
add_action( 'admin_enqueue_scripts', 'gb_hcc_wp_pointer', 1000 );

/**
 * Register custom post type
 *
 * @since 1.0.0
 */

function gb_hcc_register_cpt()
{
    $options = get_option( 'gb_wc_hcc_options' );

    $slug = 'handsome-checkout';

    if( !empty( $options['alternative_url'] ) )
    {
        $options['alternative_url'] = intval( $options['alternative_url'] );

        if( $options['alternative_url'] === 2 )
        {
            $slug = 'checkout-hs';
        }
        elseif( $options['alternative_url'] === 3 )
        {
            $slug = 'checkouts';
        }
        else // 1
        {
            $slug = 'checkout-handsome';
        }
    }

    if( !empty( $options['custom_url'] ) )
    {
        $slug = mb_strtolower(
            str_replace( 
                array( ' !@#$%^&*()_=+-"\'.,/?><\\|{}[]' ), 
                array( '-' ), 
                $options['custom_url'] 
            )
        );
    }

    $labels = array(
        'name'               => 'Handsome Checkout by BogdanFix',
        'singular_name'      => 'Handsome Checkout',
        'all_items'          => __( 'All Checkouts', 'gb-wc-hcc' ),
        'edit_item'          => __( 'Edit Checkout', 'gb-wc-hcc' ),
        'view_item'          => __( 'View Checkout', 'gb-wc-hcc' ),
        'update_item'        => __( 'Update Checkout', 'gb-wc-hcc' ),
        'add_new_item'       => __( 'Create New Checkout', 'gb-wc-hcc' ),
        'new_item_name'      => __( 'New Checkout Name', 'gb-wc-hcc' ),
        'add_new'            => __( 'Create New Checkout', 'gb-wc-hcc' ),
        'new_item'           => __( 'New Handsome Checkout', 'gb-wc-hcc' ),
        'search_items'       => __( 'Search Checkout Pages', 'gb-wc-hcc' ),
        'not_found'          => __( 'No checkout pages found', 'gb-wc-hcc' ),
        'not_found_in_trash' => __( 'No checkout pages found in trash', 'gb-wc-hcc' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Handsome Checkout',
    );

    if( defined( 'GB_BRM_REMOVE' ) )
    {
        $labels['name'] = 'Handsome Checkout';
    }

    $args = array(
        'labels'        => $labels,
        'description'   => 'Handsome Checkout Pages',
        'public'        => TRUE,
        'exclude_from_search' => TRUE,
        'publicly_queryable' => TRUE,
        'show_ui' => TRUE,
        'show_in_admin_bar' => TRUE,
        'menu_position' => 50,
        'menu_icon'   => 'dashicons-cart',
        'hierarchical' => FALSE,
        'supports'      => array( 'title' ),
        'rewrite'   => array(
            'slug' => $slug,
            'with_front' => FALSE,
            'pages' => FALSE,
        ),
    );

    register_post_type( 'handsome-checkout', $args );
}
add_action( 'init', 'gb_hcc_register_cpt' );

/**
 * Register custom "post updated" messages
 *
 * @since 1.4.0
 */

function gb_hcc_post_updated_messages( $msg )
{
    $msg[ 'handsome-checkout' ] = array(

        1 => __( 'Checkout updated.', 'gb-wc-hcc' ),

        2 => __( 'Custom field updated.' ),
        3 => __( 'Custom field deleted.' ),

        4 => __( 'Checkout updated.', 'gb-wc-hcc' ),
        5 => __( 'Checkout restored to revision', 'gb-wc-hcc' ),
        6 => __( 'Checkout published.', 'gb-wc-hcc' ),

        7 => __( 'Checkout saved.', 'gb-wc-hcc' ),
        8 => __( 'Checkout submitted.', 'gb-wc-hcc' ),
        9 => __( 'Checkout scheduled.', 'gb-wc-hcc' ),
        10 => __( 'Checkout draft updated.', 'gb-wc-hcc' ),
    );

    return $msg;
}
add_filter( 'post_updated_messages', 'gb_hcc_post_updated_messages', 10, 1 );

/**
 * Flush the rewrite rules
 *
 * @since 1.1.0
 */

function gb_hcc_flush_rewrite_rules()
{
    // CPT registration func

    gb_hcc_register_cpt();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gb_hcc_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Add the preconfigured product to cart
 *
 * @param NULL $ref_array grabs all default parameters from do_action_ref_array, 
 *  otherwise $force can be overwritten with wrong value
 * @param bool $force add products to cart even if DOING_AJAX is true
 *
 * @since 1.0.0
 */

function gb_hcc_add_product_to_cart( $ref_array = NULL, $force = FALSE )
{
    global $post;

    if( is_admin() && $force === FALSE )
    {
        return;
    }

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        // check that's NOT the Order Bump request

        if( defined( 'DOING_AJAX' ) && DOING_AJAX && $force === FALSE )
        {
            return;
        }
        else
        {
            $product_id = get_post_meta( $post->ID, 'hcc_product_id', TRUE );

            if( !empty( $_GET['hcc-add-to-cart'] ) )
            {
                $product_id = intval( $_GET['hcc-add-to-cart'] );
            }

            $vq_options = get_post_meta( $post->ID, 'hcc_vq_options', TRUE );
            $vq_options_mode = get_post_meta( $post->ID, 'hcc_vq_options_mode', TRUE );

            if( !empty( $product_id ) )
            {
                WC()->cart->empty_cart();

                $product_id = explode( ',', $product_id );

                if( !empty( $vq_options ) && $vq_options_mode == 'simple_products' )
                {
                    $product_id = array( $product_id[0] );
                }

                foreach( $product_id as $id )
                {
                    $_product = wc_get_product( $id );

                    if( !empty( $_product ) )
                    {
                        if( $_product->is_type( 'variable' ) )
                        {
                            $variation_id = 0;
                            $default_attributes = $_product->get_default_attributes();

                            if( !empty( $default_attributes ) )
                            {
                                $attributes = $_product->get_attributes();

                                foreach( $_product->get_available_variations() as $variation_values )
                                {
                                    foreach( $variation_values['attributes'] as $key => $attribute_value )
                                    {
                                        $attribute_name = str_replace( 'attribute_', '', $key );
                                        $default_value = $_product->get_variation_default_attribute( $attribute_name );

                                        if( $default_value == $attribute_value )
                                        {
                                            $variation_id = $variation_values['variation_id'];
                                        }
                                        elseif( empty( $attribute_value ) )
                                        {
                                            if( !empty( $attributes[ $attribute_name ] ) && $attributes[ $attribute_name ]->get_variation() )
                                            {
                                                if( in_array( $default_value, $attributes[ $attribute_name ]->get_options() ) )
                                                {
                                                    $variation_id = $variation_values['variation_id'];
                                                }
                                            }
                                        }
                                        else
                                        {
                                            break;
                                        }
                                    }
                                }
                            }

                            if( $variation_id == 0 )
                            {
                                WC()->cart->add_to_cart( $_product->get_children()[0], $quantity = 1 );
                            }
                            else
                            {
                                WC()->cart->add_to_cart( $id, $quantity = 1, $variation_id, $default_attributes );
                            }
                        }
                        else
                        {
                            WC()->cart->add_to_cart( $id, $quantity = 1 );
                        }

                        WC()->cart->calculate_totals();
                    }
                }
            }

            // if cart empty, and there are deals, add first item from deals

            else
            {
                global $woocommerce;

                if( $woocommerce->cart->cart_contents_count == 0 )
                {
                    $deals = get_post_meta( $post->ID, 'hcc_deals', TRUE );

                    if( !empty( $deals ) )
                    {
                        foreach( $deals as $d )
                        {
                            if( !empty( $d['product_id'] ) )
                            {
                                WC()->cart->add_to_cart( intval( $d['product_id'] ), $quantity = 1 );

                                break;
                            }
                        }
                    }
                }
            }
        }
    }
}
add_action( 'wp', 'gb_hcc_add_product_to_cart', 1 );

/**
 * DISABLED: Prevent caching of HC pages or Embed Forms
 *
 * @since 1.4.0
 */

function gb_hcc_prevent_caching()
{
    global $post;

    if( is_admin() )
    {
        return;
    }

    if( empty( $_SERVER['REQUEST_URI'] ) )
    {
        return;
    }

    $uri = esc_url_raw( $_SERVER['REQUEST_URI'] );

    if(
        // standalone pages

        is_handsome_checkout_url( $uri ) ||

        // embed forms

        !empty( $post ) && gb_hcc_form_get_embed_shortcode( $post->post_content )
    )
    {
        wc_maybe_define_constant( 'DONOTCACHEPAGE', TRUE );
        wc_maybe_define_constant( 'DONOTCACHEOBJECT', TRUE );
        wc_maybe_define_constant( 'DONOTCACHEDB', TRUE );

        nocache_headers();
    }
}
// add_action( 'wp', 'gb_hcc_prevent_caching', 1 );

/**
 * Modify the default WP nocache_headers response
 *
 * @since 1.4.0
 */

function gb_hcc_wp_headers( $headers )
{
    if( !empty( $headers ) )
    {
        $headers['Cache-Control'] = 'no-cache, no-store, must-revalidate, max-age=0';
    }

    return $headers;
}
// add_filter( 'nocache_headers', 'gb_hcc_wp_headers', 9999 );

/**
 * Validate cart contents before the payment processing
 *
 * @since 1.4.0
 */

function gb_hcc_validate_cart_contents()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $product_ids = get_post_meta( $post->ID, 'hcc_product_id', TRUE );

        if( empty( $product_ids ) )
        {
            return;
        }

        $product_ids = explode( ',', $product_ids );

        $ok = FALSE;

        // cart should contain at least one product from this HCC page

        foreach( WC()->cart->cart_contents as $item )
        {
            if(
                in_array( $item['product_id'], $product_ids ) ||

                (
                    isset( $item['variation_id'] ) &&
                    in_array( $item['variation_id'], $product_ids )
                )
            )
            {
                $ok = TRUE;

                break;
            }
        }

        if( !$ok )
        {
            $result = array(
                'result' 	=> 'fail',
                'reload' 	=> TRUE,
                'refresh' 	=> FALSE,
                'messages' 	=> '<ul class="woocommerce-error" role="alert"><li>' .
                    __( 'Oops, your cart contents have changed.', 'gb-wc-hcc' ) .
                    ' <a href="' . get_permalink( $post->ID ) . '" class="wc-backward">' .
                    __( 'Please, reload the page', 'gb-wc-hcc' ) . '</a></li></ul>',
            );

            wc_add_notice( __( 'The page was reloaded, because your cart contents have changed. Please, complete your purchase below.', 'gb-wc-hcc' ), 'error' );

            wp_send_json( $result );

            die();
        }
    }
}
add_action( 'woocommerce_checkout_order_processed', 'gb_hcc_validate_cart_contents', 1 );

/**
 * Add custom body classes and remove undesirable ones
 *
 * @since 1.0.0
 */

function gb_hcc_add_body_class( $classes )
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $classes[] = 'page';

        if( !empty( $_GET['1cu'] ) && !empty( $_GET['1cu_n'] ) && !empty( $_GET['1cu_s'] ) )
        {
            $classes[] = 'page-gb-wc-hcc-upsell';
        }

        $classes = array_diff( $classes, array( 'tve-woo-minicart', 'tve-woocommerce' ) );
    }

    return $classes;
}
add_filter('body_class', 'gb_hcc_add_body_class', 9999);

/**
 * Display custom CSS in footer for easy customizations
 *
 * @since 1.0.1
 */

function gb_hcc_embed_custom_css()
{
    global $post;

    if( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
    {
        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['replace_page_order_review'] ) )
        {
            $post_id = intval( $options['replace_page_order_review'] );

            $revert_post = $post;

            $post = get_post( $post_id );
        }
    }

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        // individual

        $custom_css = get_post_meta( $post->ID, 'hcc_custom_css', TRUE );

        if( !empty( $custom_css ) )
        {
            echo PHP_EOL . '<style type="text/css">' . wp_strip_all_tags( $custom_css ) . '</style>' . PHP_EOL;
        }

        // global

        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['custom_css'] ) )
        {
            echo PHP_EOL . '<style type="text/css">' . wp_strip_all_tags( $options['custom_css'] ) . '</style>' . PHP_EOL;
        }

        // custom styles

        echo PHP_EOL . '<style type="text/css">';

        echo wp_strip_all_tags( gb_hcc_apply_style( '.woocommerce-billing-fields h3', $post->ID, 'hcc_title_text_step1', TRUE ) );

        echo wp_strip_all_tags( gb_hcc_apply_style( 'h3#order_review_heading', $post->ID, 'hcc_title_text_step2', TRUE ) );

        echo wp_strip_all_tags( gb_hcc_apply_style( '.btn-block.btn-step1', $post->ID, 'hcc_button_text_step1', TRUE ) );

        echo wp_strip_all_tags( gb_hcc_apply_style( '.woocommerce-checkout .form-row #place_order', $post->ID, 'hcc_button_text', TRUE ) );

        echo '</style>' . PHP_EOL;
    }

    if( !empty( $revert_post ) )
    {
        $post = $revert_post;

        $revert_post = FALSE;
    }

    /**
     * Embedded forms
     *
     * @since 1.4.0
     */

    if( !empty( $post ) && $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

        if( !empty( $post_id ) )
        {
            $form_custom_css = get_post_meta( $post_id, 'hcc_form_custom_css', TRUE );

            if( !empty( $form_custom_css ) )
            {
                echo PHP_EOL . '<style type="text/css">' . wp_strip_all_tags( $form_custom_css ) . '</style>' . PHP_EOL;
            }
        }
    }
}
add_action('wp_footer', 'gb_hcc_embed_custom_css');

/**
 * Display custom CSS in footer for easy customizations
 *
 * @since 1.1.0
 */

function gb_hcc_embed_custom_js()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        ob_start();
        ?>
        <script type="text/javascript">
        jQuery(document).ready( function()
        {
            var init_checkout = 0;

            var nonce_updated = 0;

            jQuery(document).ajaxSend( function( e, request, options ) {

                if( init_checkout == 0 && nonce_updated == 0 )
                {
                    if( options.url.indexOf('wc-ajax=update_order_review') > 0 )
                    {
                        request.abort();

                        jQuery( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).unblock();

                        init_checkout = 1;
                    }
                }
            });

            jQuery(document).ajaxComplete( function( e, request, options ) {

                if( options.url.indexOf('wc-ajax=update_order_review') > 0 )
                {
                    if( request.status == 403 )
                    {
                        window.location.reload( true );
                    }
                }
            });

            var data = {
                'action': 'hcc_page_validate_nonce',
                'nonce': wc_checkout_params.update_order_review_nonce,
                'rnd': Math.floor( Math.random() * 1000 ) + 100
            };

            jQuery.post( wc_checkout_params.ajax_url, data, function( response )
            {
                response = jQuery.parseJSON( response );

                if( typeof response == 'object' && typeof response.result != 'undefined' )
                {
                    wc_checkout_params.update_order_review_nonce = response.result;

                    nonce_updated = 1;

                    jQuery( document.body ).trigger('update_checkout');
                }
            });

            jQuery('.hcc-deal-add-to-cart').on( 'click', function(){

                var $this = jQuery(this);

                window.location.href = '?hcc-add-to-cart=' + $this.val();
            });
        });
        </script>
        <?php

        $nonce_workaround_and_deal = ob_get_clean();

        echo $nonce_workaround_and_deal;

        // Custom JS for individual pages

        $custom_js = get_post_meta( $post->ID, 'hcc_custom_js', TRUE );

        if( !empty( $custom_js ) )
        {
            echo PHP_EOL . '<script type="text/javascript">' . $custom_js . '</script>' . PHP_EOL;
        }

        // Custom JS global

        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['custom_js'] ) )
        {
            echo PHP_EOL . '<script type="text/javascript">' . $options['custom_js'] . '</script>' . PHP_EOL;
        }
    }

    /**
     * Embedded forms
     *
     * @since 1.4.0
     */

    if( !empty( $post ) && $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        // individual

        $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

        if( !empty( $post_id ) )
        {
            $form_custom_js = get_post_meta( $post_id, 'hcc_form_custom_js', TRUE );

            if( !empty( $form_custom_js ) )
            {
                echo PHP_EOL . '<script type="text/javascript">' . $form_custom_js . '</script>' . PHP_EOL;
            }
        }

        // global

        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['custom_js'] ) )
        {
            echo PHP_EOL . '<script type="text/javascript">' . $options['custom_js'] . '</script>' . PHP_EOL;
        }
    }
}
add_action('wp_footer', 'gb_hcc_embed_custom_js');

/**
 * Display custom CSS in footer for easy customizations
 *
 * @since 1.5.0
 */

function gb_hcc_embed_custom_js_head()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        // Custom JS (Header) for individual pages

        $custom_js_head = get_post_meta( $post->ID, 'hcc_custom_js_head', TRUE );

        if( !empty( $custom_js_head ) )
        {
            echo PHP_EOL . '<script type="text/javascript">' . $custom_js_head . '</script>' . PHP_EOL;
        }
    }
}
add_action('wp_head', 'gb_hcc_embed_custom_js_head');

/**
 * Validate & replace the "update_order_review" nonce
 * for cached Handsome Checkout pages Forms to fix
 * greyed out forms / stuck on loading
 *
 * @since 1.5.0
 */

function gb_hcc_page_validate_nonce()
{
    $nonce = FALSE;

    if( !empty( $_POST['nonce'] ) )
    {
        $nonce = sanitize_text_field( $_POST['nonce'] );
    }

    $rnd = FALSE;

    if( !empty( $_POST['rnd'] ) )
    {
        $rnd = intval( $_POST['rnd'] );
    }

    $result = $nonce;

    if( !wp_verify_nonce( $nonce, 'update-order-review' ) )
    {
        $result = wp_create_nonce( 'update-order-review' );
    }

    if( WC()->cart->is_empty() )
    {
       global $post;

       $url = wp_get_referer();
       $post_id = url_to_postid( $url );
       $post = get_post( $post_id );

       gb_hcc_add_product_to_cart( null, $force = TRUE );
    }

    echo json_encode(
        array(
            'result' => $result,
            'rnd' => $rnd,
        )
    );

    die();
}
add_action( 'wp_ajax_hcc_page_validate_nonce', 'gb_hcc_page_validate_nonce' );
add_action( 'wp_ajax_nopriv_hcc_page_validate_nonce', 'gb_hcc_page_validate_nonce' );

/**
 * Redirect back to Handsome Checkout page after login
 *
 * @since 1.0.1
 */

function gb_hcc_wc_login_redirect( $redirect_to )
{
    if( !empty( $_SERVER['HTTP_REFERER'] ) )
    {
        $referer = esc_url_raw( $_SERVER['HTTP_REFERER'] );

        if( is_handsome_checkout_url( $referer ) )
        {
            $redirect_to = $referer;
        }
    }

    return $redirect_to;
}
add_filter('woocommerce_login_redirect', 'gb_hcc_wc_login_redirect');

/**
 * Register admin page
 *
 * @since 1.0.0
 */

function gb_hcc_add_page()
{
    add_submenu_page( 'edit.php?post_type=handsome-checkout', GB_HCC_NAME, __( 'Settings', 'gb-wc-hcc' ), 'manage_options', GB_HCC_ID, 'gb_hcc_do_page' );
}
add_action('admin_menu', 'gb_hcc_add_page');

/**
 * Display admin page
 *
 * @since 1.0.0
 */

function gb_hcc_do_page()
{
    if( !current_user_can( 'manage_options' ) )
    {
        wp_die( __( 'Oops, you can\'t access this page.', 'gb-wc-hcc' ) );
    }

    include_once 'gb-wc-hcc-admin.php';
}

/**
 * Initialize options for the admin page
 *
 * @since 1.0.0
 */

function gb_hcc_init()
{
    register_setting( 'gb_wc_hcc_options', 'gb_wc_hcc_options' );
}
add_action( 'admin_init', 'gb_hcc_init' );

/**
 * Load custom template for c/o page
 *
 * @since 1.0.0
 */

function gb_hcc_load_template( $template )
{
    global $post;

    // load HCC template for checkout page

    if( !empty( $post ) )
    {
        if( $post->post_type == 'handsome-checkout' )
        {
            $template_name = get_post_meta( $post->ID, 'hcc_template_name', TRUE );

            if( !empty( $template_name ) )
            {
                $template_name = 'templates/' . $template_name . '.php';
            }
            else
            {
                $template_name = 'templates/gb-wc-hcc-template.php';
            }

            $plugin_path = plugin_dir_path( __FILE__ );

            if( file_exists( $plugin_path . $template_name ) )
            {
                return $plugin_path . $template_name;
            }
        }
    }

    /**
     * Embedded forms: iframe mode
     * 
     * @since 1.5.4
     */

    if( !empty( $_GET['hcc_form_loader'] ) && !empty( $_GET['hcc_form_id'] ) )
    {
        $template_name = 'templates/gb-wc-hcc-template-clean.php';

        $plugin_path = plugin_dir_path( __FILE__ );

        if( file_exists( $plugin_path . $template_name ) )
        {
            return $plugin_path . $template_name;
        }
    }

    /**
     * Replace order-review page with Handsome Checkout template
     *
     * @since 1.5.4
     */

    if( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
    {
        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['replace_page_order_review'] ) )
        {
            $template_name = 'templates/gb-wc-hcc-template-6.php';

            $plugin_path = plugin_dir_path( __FILE__ );

            if( file_exists( $plugin_path . $template_name ) )
            {
                return $plugin_path . $template_name;
            }
        }
    }

    return $template;
}
add_filter( 'template_include', 'gb_hcc_load_template', 9999 );

/**
 * Revert customized theme /woocommerce/ templates to default ones
 *
 * @since 1.0.1
 */

function gb_hcc_revert_templates_to_default( $template, $template_name, $template_path )
{
    global $post;

    if( !empty( $post ) )
    {
        if( $post->post_type == 'handsome-checkout' )
        {
            if( apply_filters( 'gb_hcc_revert_templates_to_default', TRUE ) )
            {
                $default_path = WC()->plugin_path() . '/templates/' . $template_name;

                if( file_exists( $default_path ) )
                {
                    // override all the templates back to defaults

                    $template = $default_path;
                }
            }
        }
    }

    return $template;
}
add_filter( 'woocommerce_locate_template', 'gb_hcc_revert_templates_to_default', 9999, 3 );

/**
 * Load custom template for simple page
 *
 * @since 1.0.0
 */

function gb_hcc_load_page_template( $template )
{
    global $post;

    // load HCC template for simple page

    if( !empty( $post ) )
    {
        if( $post->post_type == 'page' )
        {
            $template_name = get_post_meta( $post->ID, 'hcc_template_name', TRUE );

            if( !empty( $template_name ) )
            {
                $template_name = 'templates/' . $template_name . '.php';

                $plugin_path = plugin_dir_path( __FILE__ );

                if( file_exists( $plugin_path . $template_name ) )
                {
                    return $plugin_path . $template_name;
                }
            }
        }
    }

    return $template;
}
add_filter( 'page_template', 'gb_hcc_load_page_template', 1 );

/**
 * Get post type
 *
 * @since 1.0.0
 */

function gb_hcc_template_get_post_type()
{
    global $post;

    $result = FALSE;

    if( !empty( $post ) )
    {
        $result = $post->post_type;
    }

    return $result;
}

/**
 * Wrapper to display all the custom fields
 *
 * @since 1.0.0
 */

function gb_hcc_template_field( $field = '', $echo = TRUE, $template = '' )
{
    global $post;

    $result = '';

    $post_id = $post->ID;

    // pull appropriate page id for order-review page

    if( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
    {
        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['replace_page_order_review'] ) )
        {
            $post_id = intval( $options['replace_page_order_review'] );

            /**
             * WPML integration: if translation exists, load translated post
             *
             * @since 1.5.4
             */

            if( function_exists( 'icl_object_id' ) )
            {
                $post_id = icl_object_id( $post_id, 'handsome-checkout', TRUE, ICL_LANGUAGE_CODE );
            }
        }
    }

    // display field contents

    if( !empty( $field ) && !empty( $post_id ) )
    {
        // check if this page has linked page

        $linked_page = get_post_meta( $post_id, 'hcc_linked_page', TRUE );

        if( !empty( $linked_page ) )
        {
            $post_id = intval( $linked_page );
        }

        // proceed...

        $options = get_option( 'gb_wc_hcc_options' );

        if( $field == 'support_email' )
        {
            $result = get_post_meta( $post_id, 'hcc_support_email', TRUE );

            if( empty( $result ) && !empty( $options['support_email'] ) )
            {
                $result = $options['support_email'];
            }
        }
        elseif( $field == 'support_phone' )
        {
            if( !empty( $options['support_phone'] ) )
            {
                $result = $options['support_phone'];
            }
        }
        elseif( $field == 'product_title' )
        {
            if( mb_strpos( $field, 'hcc_' ) === FALSE )
            {
                $field = 'hcc_' . $field;
            }

            $result = get_post_meta( $post_id, $field, TRUE );

            if( empty( $result ) )
            {
                $result = get_the_title();
            }
        }
        elseif( $field == 'product_image' )
        {
            $meta = get_post_meta( $post_id, 'hcc_product_image', TRUE );

            if( !empty( $meta ) )
            {
                $product_image = wp_get_attachment_image_src( $meta, 'full' );

                if( !empty( $product_image[0] ) )
                {
                    $result = '<img class="product-image resize" src="' . $product_image[0] . '" width="' . $product_image[1] . '" height="' . $product_image[2] . '" />';
                }
            }
        }
        elseif( $field == 'marketplace_image' )
        {
            $meta = get_post_meta( $post_id, 'hcc_marketplace_image', TRUE );

            if( !empty( $meta ) )
            {
                $product_image = wp_get_attachment_image_src( $meta, 'full' );

                if( !empty( $product_image[0] ) )
                {
                    $result = '<img class="marketplace-image logotuck" src="' . $product_image[0] . '" width="' . $product_image[1] . '" height="' . $product_image[2] . '" />';
                }
            }
        }
        elseif( $field == 'bullet_points' )
        {
            $meta = get_post_meta( $post_id, 'hcc_bullet_points', TRUE );

            if( !empty( $meta ) )
            {
                $result = '';

                $template_bullet = '';

                foreach( $meta as $bullet )
                {
                    if( !empty( $bullet ) )
                    {
                        $template_bullet = $template;

                        $template_bullet = str_replace( '[bullet_text]', $bullet, $template_bullet );

                        $result .= $template_bullet;
                    }
                }
            }
        }
        elseif( $field == 'testimonials' )
        {
            $meta = get_post_meta( $post_id, 'hcc_testimonials', TRUE );

            if( !empty( $meta ) )
            {
                $result = '';

                $template_testimonial = '';

                foreach( $meta as $testimonial )
                {
                    if( !empty( $testimonial['text'] ) )
                    {
                        $template_testimonial = $template;

                        if( empty( $testimonial['title'] ) )
                        {
                            $testimonial['title'] = '';
                        }

                        // testimonial with image

                        if( !empty( $testimonial['image'] ) )
                        {
                            $product_image = wp_get_attachment_image_src( $testimonial['image'], 'full' );

                            if( !empty( $product_image[0] ) )
                            {
                                $template_testimonial = str_replace(
                                    '[testimonial_image]',
                                    '<img src="' . $product_image[0] . '" width="' . $product_image[1] . '" height="' . $product_image[2] . '" />',
                                    $template_testimonial
                                );
                            }
                            else
                            {
                                $template_testimonial = str_replace(
                                    '[testimonial_image]',
                                    '',
                                    $template_testimonial
                                );
                            }
                        }

                        // testimonial with no image

                        else
                        {
                            $template_testimonial = str_replace(
                                '[testimonial_image]',
                                '',
                                $template_testimonial
                            );
                        }

                        $template_testimonial = str_replace( '[testimonial_title]', nl2br( $testimonial['title'] ), $template_testimonial );

                        $template_testimonial = str_replace( '[testimonial_text]', nl2br( $testimonial['text'] ), $template_testimonial );

                        $result .= $template_testimonial;
                    }
                }
            }
        }
        elseif( $field == 'trustpoints' )
        {
            $meta = get_post_meta( $post_id, 'hcc_trustpoints', TRUE );

            if( !empty( $meta ) )
            {
                $result = '';

                $template_trustpoint = '';

                foreach( $meta as $trustpoint )
                {
                    if( !empty( $trustpoint['text'] ) )
                    {
                        $template_trustpoint = $template;

                        // trustpoint with image

                        if( !empty( $trustpoint['image'] ) )
                        {
                            $product_image = wp_get_attachment_image_src( $trustpoint['image'], 'full' );

                            if( !empty( $product_image[0] ) )
                            {
                                $template_trustpoint = str_replace(
                                    '[trustpoint_image]',
                                    '<img src="' . $product_image[0] . '" width="' . $product_image[1] . '" height="' . $product_image[2] . '" />',
                                    $template_trustpoint
                                );
                            }
                        }

                        // trustpoint with no image

                        else
                        {
                            $template_trustpoint = str_replace(
                                '[trustpoint_image]',
                                '',
                                $template_trustpoint
                            );
                        }

                        // conditional class

                        $template_class = 'condition-default';

                        if(
                            mb_strpos( mb_strtolower( $trustpoint['title'] ), 'money' ) !== FALSE ||
                            mb_strpos( mb_strtolower( $trustpoint['title'] ), 'back' ) !== FALSE ||
                            mb_strpos( mb_strtolower( $trustpoint['title'] ), 'guarantee' ) !== FALSE
                        )
                        {
                            $template_class = 'condition-guarantee';
                        }

                        $template_trustpoint = str_replace( '[class]', $template_class, $template_trustpoint );

                        //

                        $template_trustpoint = str_replace( '[trustpoint_title]', $trustpoint['title'], $template_trustpoint );

                        $template_trustpoint = str_replace( '[trustpoint_text]', nl2br( $trustpoint['text'] ), $template_trustpoint );

                        $result .= $template_trustpoint;
                    }
                }
            }
        }

        /* custom template options */

        elseif( $field == 'ingredients_image' )
        {
            $meta = get_post_meta( $post_id, 'hcc_ingredients_image', TRUE );

            if( !empty( $meta ) )
            {
                $ingredients_image = wp_get_attachment_image_src( $meta, 'full' );

                if( !empty( $ingredients_image[0] ) )
                {
                    $result = 
                        '<a href="#" class="ingredients-btn">' . __( 'Ingredients', 'gb_wc_hcc' ) . '</a>
                        <div class="ingredients-overlay" style="opacity: 0; display: none;">
                            <img class="ingredients-image" src="' . $ingredients_image[0] . '" width="' . $ingredients_image[1] . '" height="' . $ingredients_image[2] . '" style="margin-top: -' . ( intval( $ingredients_image[2] / 2 ) ) . 'px;" />
                        </div>';
                }
            }
        }

        elseif( $field == 'deals' )
        {
            $meta = get_post_meta( $post_id, 'hcc_deals', TRUE );

            if( !empty( $meta ) )
            {
                $result = '';

                $template_deal = '';

                foreach( $meta as $deal )
                {
                    if( !empty( $deal['product_id'] ) )
                    {
                        $template_deal = $template;

                        $template_deal = str_replace(
                            '[deal_type]',
                            $deal['type'],
                            $template_deal
                        );

                        $template_deal = str_replace(
                            '[deal_title]',
                            $deal['title'],
                            $template_deal
                        );

                        $template_deal = str_replace( 
                            '[deal_description]', 
                            nl2br( $deal['description'] ), 
                            $template_deal 
                        );

                        $product = wc_get_product( $deal['product_id'] );

                        $template_deal = str_replace( 
                            '[deal_product]', 
                            '<label>' . 
                                '<input type="checkbox" class="hcc-deal-add-to-cart" value="' . intval( $deal['product_id'] ) . '" ' . ( gb_hcc_is_product_in_cart( $deal['product_id'] ) ? 'checked="checked"' : '' ) . '/> ' .
                                $product->get_title() . ' ' . wc_price( $product->get_price() ) . 
                            '</label>',
                            $template_deal
                        );

                        $result .= $template_deal;
                    }
                }
            }
        }

        elseif( $field == 'trustseal_top_image' )
        {
            $meta = get_post_meta( $post_id, 'hcc_trustseal_top_image', TRUE );

            if( !empty( $meta ) )
            {
                $ts_top_image = wp_get_attachment_image_src( $meta, 'full' );

                if( !empty( $ts_top_image[0] ) )
                {
                    $result = '<img class="trustseal-top-image" src="' . $ts_top_image[0] . '" width="' . $ts_top_image[1] . '" height="' . $ts_top_image[2] . '" />';
                }
            }
        }

        elseif( $field == 'trustseal_bottom_image' )
        {
            $meta = get_post_meta( $post_id, 'hcc_trustseal_bottom_image', TRUE );

            if( !empty( $meta ) )
            {
                $ts_bottom_image = wp_get_attachment_image_src( $meta, 'full' );

                if( !empty( $ts_bottom_image[0] ) )
                {
                    $result = '<img class="trustseal-bottom-image" src="' . $ts_bottom_image[0] . '" width="' . $ts_bottom_image[1] . '" height="' . $ts_bottom_image[2] . '" />';
                }
            }
        }

        // other options

        else
        {
            if( mb_strpos( $field, 'hcc_' ) === FALSE )
            {
                $field = 'hcc_' . $field;
            }

            $result = get_post_meta( $post_id, $field, TRUE );
        }
    }

    // apply the styles if available

    $result = gb_hcc_apply_style( $result, $post_id, $field );

    // return the result

    if( !empty( $echo ) )
    {
        echo $result;
    }
    else
    {
        return $result;
    }
}

/**
 * Display slug metabox
 *
 * @since 1.0.0
 */

function gb_hcc_display_slug_metabox( $hidden, $screen )
{
    $post_type = $screen->id;

    if( $post_type == 'handsome-checkout' )
    {
        $hidden = array_diff( $hidden, array( 'slugdiv', 'gb_hcc_select_metabox', 'gb_hcc_display_metabox', 'gb_hcc_display_form_metabox' ) );
    }

    return $hidden;
}
add_filter( 'hidden_meta_boxes', 'gb_hcc_display_slug_metabox', 10, 2 );

/**
 * Register custom metaboxes
 *
 * @since 1.0.0
 */

function gb_hcc_register_metaboxes()
{
    /**
     * HCC choose between page/form
     *
     * @since 1.4.0
     */

    add_meta_box(
        'gb_hcc_select_metabox',
        __( 'Choose Checkout Mode', 'gb-wc-hcc' ),
        'gb_hcc_display_mode_metabox',
        'handsome-checkout',
        'normal',
        'high'
    );

    /**
     * HCC page settings
     *
     * @since 1.0.0
     */

    add_meta_box(
        'gb_hcc_metabox',
        __( 'Page Settings', 'gb-wc-hcc' ),
        'gb_hcc_display_metabox',
        'handsome-checkout',
        'normal',
        'high'
    );

    /**
     * HCC form settings
     *
     * @since 1.4.0
     */

    add_meta_box(
        'gb_hcc_shortcode_metabox',
        __( 'Embed Form Settings', 'gb-wc-hcc' ),
        'gb_hcc_display_form_metabox',
        'handsome-checkout',
        'normal',
        'high'
    );

    // HCC template box for simple pages

    if( defined('GB_OCU_ID') )
    {
        add_meta_box(
            'gb_hcc_template_metabox',
            __( 'Handsome Checkout Template', 'gb-wc-hcc' ),
            'gb_hcc_display_template_metabox',
            'page',
            'side',
            'default'
        );
    }

}
add_action( 'admin_init', 'gb_hcc_register_metaboxes' );

/**
 * Display page options metabox
 *
 * @since 1.0.0
 */

function gb_hcc_display_metabox( $post )
{
    if( $post->post_status == 'shortcode' )
    {
        return;
    }

    $product_id = get_post_meta( $post->ID, 'hcc_product_id', TRUE );

    $product_id_json = array();

    if( !empty( $product_id ) )
    {
        $product_id = explode( ',', $product_id );

        foreach( $product_id as $id )
        {
            $product = wc_get_product( $id );

            if( !empty( $product ) )
            {
                $product_id_json[] = array(
                    'id' => $id,
                    'text' => '#' . $id . ' &ndash; ' . $product->get_name(),
                );
            }
        }

        $product_id_json = json_encode( $product_id_json );
    }

    $product_title =        get_post_meta( $post->ID, 'hcc_product_title', TRUE );

    $product_title_style =  get_post_meta( $post->ID, 'hcc_product_title_style', TRUE );

    $product_description =  get_post_meta( $post->ID, 'hcc_product_description', TRUE );

    $product_description_style = get_post_meta( $post->ID, 'hcc_product_description_style', TRUE );

    $product_image =        get_post_meta( $post->ID, 'hcc_product_image', TRUE );

    // Order Bump

    $order_bump =            get_post_meta( $post->ID, 'hcc_order_bump', TRUE );

    $order_bump_product_id = get_post_meta( $post->ID, 'hcc_order_bump_product_id', TRUE );

    $order_bump_product_id_json = array();

    if( !empty( $order_bump_product_id ) )
    {
        $order_bump_product_id = explode( ',', $order_bump_product_id );

        foreach( $order_bump_product_id as $id )
        {
            $product = wc_get_product( $id );

            if( !empty( $product ) )
            {
                $order_bump_product_id_json[] = array(
                    'id' => $id,
                    'text' => '#' . $id . ' &ndash; ' . $product->get_title(),
                );
            }
        }

        $order_bump_product_id_json = json_encode( $order_bump_product_id_json );
    }

    $order_bump_label =         get_post_meta( $post->ID, 'hcc_order_bump_label', TRUE );
    $order_bump_highlight =     get_post_meta( $post->ID, 'hcc_order_bump_highlight', TRUE );
    $order_bump_description =   get_post_meta( $post->ID, 'hcc_order_bump_description', TRUE );

    // Order Bump END

    // Variations & Quantity Options

    $vq_options                      = get_post_meta( $post->ID, 'hcc_vq_options', TRUE );
    $vq_options_main_title           = get_post_meta( $post->ID, 'hcc_vq_options_main_title', TRUE );
    $vq_options_main_title_style     = get_post_meta( $post->ID, 'hcc_vq_options_main_title_style', TRUE );
    $vq_options_item_title           = get_post_meta( $post->ID, 'hcc_vq_options_item_title', TRUE );
    $vq_options_price_title          = get_post_meta( $post->ID, 'hcc_vq_options_price_title', TRUE );
    $vq_options_mode                 = get_post_meta( $post->ID, 'hcc_vq_options_mode', TRUE );
    $vq_option_highlight_show        = get_post_meta( $post->ID, 'hcc_vq_option_highlight_show', TRUE );
    $vq_option_highlight             = get_post_meta( $post->ID, 'hcc_vq_option_highlight', TRUE );
    $vq_option_highlight_inscription = get_post_meta( $post->ID, 'hcc_vq_option_highlight_inscription', TRUE );
    $vq_options_table                = get_post_meta( $post->ID, 'hcc_vq_options_sq_table', TRUE );
    $vq_option_selected              = get_post_meta( $post->ID, 'hcc_vq_option_selected', TRUE );

    // Variations & Quantity Options END

    $template_name =            get_post_meta( $post->ID, 'hcc_template_name', TRUE );

    if( empty( $template_name ) )
    {
        $template_name = 'gb-wc-hcc-template';
    }

    $color_header =         get_post_meta( $post->ID, 'hcc_color_header', TRUE );
    $color_footer = 		get_post_meta( $post->ID, 'hcc_color_footer', TRUE );
    $color_bg =             get_post_meta( $post->ID, 'hcc_color_bg', TRUE );
    $color_headline =       get_post_meta( $post->ID, 'hcc_color_headline', TRUE );
    $color_button_1 = 		get_post_meta( $post->ID, 'hcc_color_button_1', TRUE );
    $color_button_2 = 		get_post_meta( $post->ID, 'hcc_color_button_2', TRUE );

    $fields_billing_email_first = get_post_meta( $post->ID, 'hcc_fields_billing_email_first', TRUE );
    $fields_shipping_first = get_post_meta( $post->ID, 'hcc_fields_shipping_first', TRUE );

    $fields_billing =           get_post_meta( $post->ID, 'hcc_fields_billing', TRUE );
    $fields_billing_2 =         get_post_meta( $post->ID, 'hcc_fields_billing_2', TRUE );
    $fields_billing_names =     get_post_meta( $post->ID, 'hcc_fields_billing_names', TRUE );
    $fields_billing_company =   get_post_meta( $post->ID, 'hcc_fields_billing_company', TRUE );
    $fields_shipping =          get_post_meta( $post->ID, 'hcc_fields_shipping', TRUE );
    $fields_shipping_2 =        get_post_meta( $post->ID, 'hcc_fields_shipping_2', TRUE );
    $fields_coupon =            get_post_meta( $post->ID, 'hcc_fields_coupon', TRUE );
    $fields_phone =             get_post_meta( $post->ID, 'hcc_fields_phone', TRUE );
    $fields_order_comments =    get_post_meta( $post->ID, 'hcc_fields_order_comments', TRUE );
    $fields_guarantee =         get_post_meta( $post->ID, 'hcc_fields_guarantee', TRUE );

    $order_details =            get_post_meta( $post->ID, 'hcc_order_details', TRUE );
    $order_total_hide =         get_post_meta( $post->ID, 'hcc_order_total_hide', TRUE );

    $marketplace_image =        get_post_meta( $post->ID, 'hcc_marketplace_image', TRUE );
    $marketplace_image_align =  get_post_meta( $post->ID, 'hcc_marketplace_image_align', TRUE );

    $title_text_step1 =         get_post_meta( $post->ID, 'hcc_title_text_step1', TRUE );
    $title_text_step1_style =   get_post_meta( $post->ID, 'hcc_title_text_step1_style', TRUE );
    $title_text_step2 =         get_post_meta( $post->ID, 'hcc_title_text_step2', TRUE );
    $title_text_step2_style =   get_post_meta( $post->ID, 'hcc_title_text_step2_style', TRUE );
    $button_text_step1 =        get_post_meta( $post->ID, 'hcc_button_text_step1', TRUE );
    $button_text_step1_style =  get_post_meta( $post->ID, 'hcc_button_text_step1_style', TRUE );
    $button_text =              get_post_meta( $post->ID, 'hcc_button_text', TRUE );
    $button_text_style =        get_post_meta( $post->ID, 'hcc_button_text_style', TRUE );

    $bullet_points_title =      get_post_meta( $post->ID, 'hcc_bullet_points_title', TRUE );
    $bullet_points_title_style= get_post_meta( $post->ID, 'hcc_bullet_points_title_style', TRUE );
    $bullet_points =            get_post_meta( $post->ID, 'hcc_bullet_points', TRUE );

    $testimonials_title =       get_post_meta( $post->ID, 'hcc_testimonials_title', TRUE );
    $testimonials_title_style = get_post_meta( $post->ID, 'hcc_testimonials_title_style', TRUE );
    $testimonials =             get_post_meta( $post->ID, 'hcc_testimonials', TRUE );

    $trustpoints =              get_post_meta( $post->ID, 'hcc_trustpoints', TRUE );

    $custom_css =               get_post_meta( $post->ID, 'hcc_custom_css', TRUE );
    $custom_js_head =           get_post_meta( $post->ID, 'hcc_custom_js_head', TRUE );
    $custom_js =                get_post_meta( $post->ID, 'hcc_custom_js', TRUE );

    $custom_html_footer =       get_post_meta( $post->ID, 'hcc_custom_html_footer', TRUE );

    $support_email =            get_post_meta( $post->ID, 'hcc_support_email', TRUE );

    // custom template options

    $headline =                 get_post_meta( $post->ID, 'hcc_headline', TRUE );              
    $headline_style =           get_post_meta( $post->ID, 'hcc_headline_style', TRUE );              
    $subheadline =              get_post_meta( $post->ID, 'hcc_subheadline', TRUE );              
    $subheadline_style =        get_post_meta( $post->ID, 'hcc_subheadline_style', TRUE );              

    $ingredients_image =        get_post_meta( $post->ID, 'hcc_ingredients_image', TRUE );

    $deals =                    get_post_meta( $post->ID, 'hcc_deals', TRUE );

    if( !empty( $deals ) )
    {
        foreach( $deals as $k => $d )
        {
            if( !empty( $d['product_id'] ) )
            {
                $product = wc_get_product( $d['product_id'] );

                if( !empty( $product ) )
                {
                    $d['product_id_json'] = array(
                        'id' => $d['product_id'],
                        'text' => '#' . $d['product_id'] . ' &ndash; ' . $product->get_title(),
                    );
                }

                $d['product_id_json'] = json_encode( $d['product_id_json'] );

                $deals[ $k ] = $d;
            }
        }
    }

    $trustseal_top_image =      get_post_meta( $post->ID, 'hcc_trustseal_top_image', TRUE );
    $trustseal_bottom_image =   get_post_meta( $post->ID, 'hcc_trustseal_bottom_image', TRUE );

    ?>
    <style type="text/css">
    .postbox#gb_hcc_metabox .inside {
        padding: 0 !important;
        margin: 0 !important;
    }

    .postbox#gb_hcc_metabox .handlediv,
    .postbox#gb_hcc_metabox .hndle,
    .postbox#gb_hcc_metabox .postbox-header {
        display: none !important;
    }

    #poststuff .wc-hcc-tabs {
        border-bottom: 1px solid #eee;
    }

    #poststuff .wc-hcc-tabs h2 {
        display: block;
        width: auto;
        float: left;
        padding: 15px;
        margin-bottom: -1px;
        font-size: 17px;
        border: 1px solid #EEE;
        border-width: 0 1px 1px 0;
        background: #FFF;
        cursor: pointer;
    }

    #poststuff .wc-hcc-tabs h2.wc-hcc-tab-selected {
        font-weight: bold;
        border-bottom: 0;
        margin-top: 1px;
    }

    .wc-hcc-inner {
        padding: 12px;
        margin: 0;
    }

    .tip {
        color: #888;
        font-style: italic;
        vertical-align: middle;
    }

    table.wc-hcc-table {
        width: 100%;
    }

    table.wc-hcc-table > tr > td:first-child {
        width: 30%;
        vertical-align: top;
        line-height: 30px;
    }

    table.wc-hcc-table tr td {
        padding: 20px 0;
    }

    table.wc-hcc-table tr td .wc-hcc-field-label {
        font-size: 16px;
    }

    table.wc-hcc-table tr td .wc-hcc-field-rs {
        font-size: 12px;
        color: #999;
    }

    table.wc-hcc-table textarea {
        width: 100%;
        min-height: 100px;
    }

    table.wc-hcc-table input[type="text"] {
        width: 60%;
    }

    table.wc-hcc-table input[type="checkbox"] {
        margin: 0;
    }

    table.wc-hcc-table-inner tr td:first-child {
        width: auto;
        vertical-align: middle;
        line-height: normal;
    }

    table.wc-hcc-table-inner tr td {
        padding: 0;
    }

    .wc-hcc-left {
        width: 50%;
        float: left;
    }

    .wc-hcc-left-30 {
        width: 30%;
        float: left;
    }

    .wc-hcc-right {
        width: 50%;
        float: right;
    }

    .wc-hcc-right-70 {
        width: 70%;
        float: right;
    }

    .wc-hcc-image-wrapper {
        width: 100%;
        height: 200px;
        background: #f8f8f8;
        padding: 5px;
    }

    .wc-hcc-image-wrapper img {
        max-width: 100%;
        max-height: 200px;
    }

    .wc-hcc-image-controls {
        text-align: center;
        margin: 72px 0;
    }

    .wc-hcc-image-controls .wc-hcc-image-select {
        margin-bottom: 10px;
    }

    .wc-hcc-bullet .wc-hcc-bullet-remove {
        vertical-align: sub;
        cursor: pointer;
    }

    .wc-hcc-bullet-controls {
        margin-top: 10px;
    }

    .wc-hcc-testimonial {
        margin-bottom: 20px;
    }

    .wc-hcc-testimonial input[type="text"] {
        margin: 0 0 10px 0;
    }

    .wc-hcc-testimonials-controls {
        /*margin-top: 10px;*/
    }

    .wc-hcc-testimonial-image {
        text-align: center;
    }

    .wc-hcc-testimonial-image-wrapper img {
        max-width: 100px;
        max-height: 100px;
    }

    .wc-hcc-testimonial-remove-wrap {
        text-align: right;
    }

    .wc-hcc-testimonial-remove {
        vertical-align: sub;
        cursor: pointer;
    }

    .wc-hcc-trustpoint {
        margin-bottom: 20px;
    }

    .wc-hcc-trustpoint input[type="text"] {
        margin: 0 0 10px 0;
    }

    .wc-hcc-trustpoints-controls {
        /*margin-top: 10px;*/
    }

    .wc-hcc-trustpoint-image {
        text-align: center;
    }

    .wc-hcc-trustpoint-image-wrapper img {
        max-width: 100px;
        max-height: 100px;
    }

    .wc-hcc-trustpoint-remove-wrap {
        text-align: right;
    }

    .wc-hcc-trustpoint-remove {
        vertical-align: sub;
        cursor: pointer;
    }

    .wc-hcc-templates-wrap {
        position: relative;
    }

    .wc-hcc-templates-wrap .wc-hcc-template-wrap {
        float: left;
        height: 315px;
        margin: 0 10px 10px 0;
    }

    .wc-hcc-templates-wrap .wc-hcc-template {
        width: 175px;
        opacity: 0.8;
        padding: 10px;
        border: 1px solid #EEE;
        cursor: pointer;
    }

    .wc-hcc-templates-wrap .wc-hcc-template:hover {
        opacity: 1;
    }

    .wc-hcc-templates-wrap .wc-hcc-template.wc-hcc-template-selected {
        background: #d9edff;
        border: 1px solid #98ddff;
        opacity: 1;
    }

    .wc-hcc-templates-wrap .wc-hcc-template img {
        width: 100%;
    }

    .wc-hcc-template-preview {
        width: 390px;
        position: absolute;
        z-index: 10;
        top: -70px;
        left: 215px;
        background: #FFF;
        padding: 10px;
        box-shadow: -1px 0px 15px 0px #AAA;
    }

    .wc-hcc-template-preview img {
        width: 100%;
        height: auto;
    }

    .wc-hcc-template-name {
        text-align: center;
        font-style: italic;
    }

    .select2-container {
        width: 100% !important;
    }

    .wc-hcc-save-bar {
        display: none;
        position: fixed;
        top: 30px;
        left: 0;
        right: 0;
        width: 100%;
        background: rgba( 230, 230, 230, 0.7 );
        text-align: center;
    }

    .wc-hcc-save-bar > div {
        padding: 10px;
    }

    @media screen and (max-width: 782px) {

        .wc-hcc-save-bar {
            top: 46px;
        }
    }

    table.wc-hcc-vq-options-table {
        width: 100%;
        border-left: 1px solid #f1f1f1;
        border-spacing: 0;
    }

    table.wc-hcc-vq-options-table tr td {
        text-align: left;
        padding: 8px 10px;
    }

    table.wc-hcc-vq-options-table td {
        border-bottom: 1px solid #f1f1f1;
    }

    td.vq-options-table-header {
        border-top: 1px solid #f1f1f1;
    }

    td.vq-option-radio {
        border-right: 1px solid #f1f1f1;
    }

    table.wc-hcc-vq-options-table td.vq-options-table-trash {
        border: 0;
    }

    table.wc-hcc-vq-options-table input[type="text"] {
        width: 100%;
    }

    input.vq-option-qty {
        width: 4em;
    }

    .wc-hcc-vq-option-controls {
        margin-top: 10px;
    }

    .wc-hcc-vq-option-remove {
        cursor: pointer;
    }

    /* custom template options */

    .wc-hcc-deals-wrap .wc-hcc-deal {
        padding: 10px;
        margin: 0 0 10px 0;
        background: #f8f8f8;
    }

    .wc-hcc-deals-wrap .wc-hcc-deal > div {
        margin: 0 0 10px 0;
    }

    .wc-hcc-deal-remove-wrap {
        text-align: right;
    }

    .wc-hcc-deal-remove {
        cursor: pointer;
    }

    .wc-hcc-deal-remove:first-child {
        cursor: disabled;
    }

    .wc-hcc-config-style-wrap {
        padding: 10px;
        background: #f5f5f5;
        display: block;
        margin-top: 5px;
    }

    .wc-hcc-config-style-wrap > p {
        border-bottom: 1px solid #DDD;
        font-style: italic;
        margin: 0 0 1em 0;
    }

    .wc-hcc-config-style-wrap input,
    .wc-hcc-config-style-wrap select {
        vertical-align: middle;
    }

    table.wc-hcc-table .wc-hcc-config-style-wrap input[type="text"] {
        width: 100px;
        height: 28px;
    }

    .wc-hcc-config-style-wrap div > label {
        display: inline-block;
        line-height: 26px;
        height: 26px;
        width: 26px;
        text-align: center;
        border: 1px solid #F4F4F4;
        font-size: 18px;
    }

    .wc-hcc-config-style-wrap div > label:hover {
        border-color: #CDCDCD;
        background: #F1F1F1;
    }

    .wc-hcc-config-style-wrap div > label.selected {
        border-color: #989898;
        background: #DDD;
    }

    .wc-hcc-config-style-wrap div > label input[type="checkbox"] {
        display: none;
    }

    .wc-hcc-config-style-wrap .wp-picker-container {
        margin-top: 10px;
    }

    .wc-hcc-config-style span.dashicons {
        vertical-align: -4px;
        opacity: 0.7;
        cursor: pointer;
    }

    .wc-hcc-config-style span.dashicons:hover {
        opacity: 0.9;
    }

    .wp-picker-holder {
        position: absolute;
    }

    .gb-wc-hcc-fa-select2-wrap {
        display: inline-block;
        width: 210px;
        margin-bottom: 15px;
    }
    </style>

    <div class="wc-hcc-tabs">

        <h2 data-tab="page-settings" class="wc-hcc-page-settings-tab wc-hcc-tab-selected"><?php esc_html_e( 'General', 'gb-wc-hcc' ); ?></h2>

        <h2 data-tab="advanced-settings" class="wc-hcc-advanced-settings-tab"><?php esc_html_e( 'Advanced', 'gb-wc-hcc' ); ?></h2>

        <h2 data-tab="custom-settings" class="wc-hcc-custom-settings-tab"><?php esc_html_e( 'Custom', 'gb-wc-hcc' ); ?></h2>

        <div class="clear"></div>

    </div>

    <div class="wc-hcc-inner">

        <input type="hidden" name="hcc_editor" value="1" />

        <table class="wc-hcc-table wc-hcc-table-page-settings" style="display: block;">

            <tr>
                <td style="width: 30%;">
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Select Product', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <select
                            name="hcc_product_id[]"
                            class="wc-hcc-product-search"
                            id="wc-hcc-select-product"
                            multiple="multiple"
                            data-selected="<?php if( !empty( $product_id_json ) ) echo esc_textarea( $product_id_json ); ?>"
                            data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                            data-action="woocommerce_json_search_products_and_variations"
                            data-sortable="true">
                    </select>
                </td>
            </tr>

            <tr class="wc-hcc-product-title-field">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Product Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input id="wc-hcc-product-title" type="text" name="hcc_product_title" value="<?php echo esc_textarea( $product_title ); ?>" />
                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>
                    <br />
                    <span class="tip"><?php esc_html_e( 'used instead of the page title', 'gb-wc-hcc' ); ?></span>

                    <?php echo gb_hcc_config_style( 'product_title', $product_title_style ); ?>
                </td>
            </tr>

            <tr class="wc-hcc-product-description-field">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Product Description', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea id="wc-hcc-product-description" name="hcc_product_description"><?php echo esc_textarea( $product_description ); ?></textarea>
                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'product_description', $product_description_style ); ?>
                </td>
            </tr>

            <tr class="wc-hcc-product-image-field">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Product Image', 'gb-wc-hcc' ); ?></div>
                    <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 300&times;300px</div>
                </td>
                <td class="wc-hcc-image-field-wrapper">
                    <div class="wc-hcc-left">
                        <div class="wc-hcc-image-wrapper">
                            <?php

                            if( !empty( $product_image ) )
                            {
                                $product_image_src = wp_get_attachment_image_src( $product_image, 'full' );

                                if( !empty( $product_image_src[0] ) )
                                {
                                    echo '<img src="' . esc_url( $product_image_src[0] ) . '" />';
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="wc-hcc-right">
                        <div class="wc-hcc-image-controls">
                            <input type="hidden" name="hcc_product_image" value="<?php if( !empty( $product_image ) ) echo esc_html( $product_image ); ?>" class="wc-hcc-image-id" />

                            <div>
                                <button id="wc-hcc-product-image" class="wc-hcc-image-select button" data-uploader-title="<?php esc_html_e( 'Select product image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                            </div>

                            <div>
                                <a href="#" class="wc-hcc-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="checkbox" name="hcc_order_bump" value="1" <?php checked( $order_bump, '1' ) ?> />
                    <span class="tip"><?php esc_html_e( 'enable', 'gb-wc-hcc' ); ?></span>
                </td>
            </tr>

            <tr class="order-bump" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Product', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <select
                            name="hcc_order_bump_product_id[]"
                            class="wc-hcc-product-search"
                            data-selected="<?php if( !empty( $order_bump_product_id_json ) ) echo esc_textarea( $order_bump_product_id_json ); ?>"
                            data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                            data-action="woocommerce_json_search_products_and_variations">
                    </select>
                </td>
            </tr>

            <tr class="order-bump" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Checkbox Label', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_order_bump_label" value="<?php echo esc_textarea( $order_bump_label ); ?>" placeholder="<?php esc_html_e( 'Yes, I will take it!', 'gb-wc-hcc' ); ?>" />
                </td>
            </tr>

            <tr class="order-bump" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Highlighted Text', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_order_bump_highlight" value="<?php echo esc_textarea( $order_bump_highlight ); ?>" placeholder="<?php esc_html_e( 'ONE TIME OFFER', 'gb-wc-hcc' ); ?>" />
                </td>
            </tr>

            <tr class="order-bump" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Bump Main Description', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea name="hcc_order_bump_description"><?php echo esc_textarea( $order_bump_description ); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Variations & Quantity Options', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="checkbox" name="hcc_vq_options" value="1" <?php checked( $vq_options, '1' ) ?> />
                    <span class="tip"><?php esc_html_e( 'enable', 'gb-wc-hcc' ); ?></span>
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Main Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_vq_options_main_title" value="<?php echo esc_textarea( $vq_options_main_title ); ?>" placeholder="<?php esc_html_e( 'Product Selection', 'gb-wc-hcc' ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'vq_options_main_title', $vq_options_main_title_style ); ?>
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Item Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_vq_options_item_title" value="<?php echo esc_textarea( $vq_options_item_title ); ?>" placeholder="<?php esc_html_e( 'Item', 'gb-wc-hcc' ); ?>" />
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Price Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_vq_options_price_title" value="<?php echo esc_textarea( $vq_options_price_title ); ?>" placeholder="<?php esc_html_e( 'Price', 'gb-wc-hcc' ); ?>" />
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Highlight Inscription', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_vq_option_highlight_inscription" value="<?php echo esc_textarea( $vq_option_highlight_inscription ); ?>" placeholder="<?php esc_html_e( 'MOST POPULAR!', 'gb-wc-hcc' ); ?>" />
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Mode', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <select name="hcc_vq_options_mode">
                        <option value="simple_quantity" <?php if( $vq_options_mode == 'simple_quantity' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simple quantity', 'gb-wc-hcc' ); ?></option>
                        <option value="simple_variations" <?php if( $vq_options_mode == 'simple_variations' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simple variations', 'gb-wc-hcc' ); ?></option>
                        <option value="dropdown_quantity_and_variations" <?php if( $vq_options_mode == 'dropdown_quantity_and_variations' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dropdown quantity and variations', 'gb-wc-hcc' ); ?></option>
                        <option value="simple_products" <?php if( $vq_options_mode == 'simple_products' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simple products', 'gb-wc-hcc' ); ?></option>
                    </select>
                </td>
            </tr>

            <tr class="vq-options" style="display: none;">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Options Table', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <div class="wc-hcc-vq-options-wrap wc-hcc-vq-options-table-simple" data-count="1" style="display: none;">
                        <table class="wc-hcc-vq-options-table">
                            <tr>
                                <td class="vq-options-table-header">
                                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Def.', 'gb-wc-hcc' ); ?></div>
                                </td>
                                <td class="vq-options-table-header">
                                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Qty', 'gb-wc-hcc' ); ?></div>
                                </td>
                                <td class="vq-options-table-header" style="width: 42%;">
                                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom Name', 'gb-wc-hcc' ); ?></div>
                                </td>
                                <td class="vq-options-table-header" style="width: 16%;">
                                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Price', 'gb-wc-hcc' ); ?></div>
                                </td>
                                <td class="vq-options-table-header vq-option-radio">
                                    <div class="wc-hcc-field-label">
                                        <?php esc_html_e( 'Highlight', 'gb-wc-hcc' ); ?>&nbsp;
                                        <input type="checkbox" name="hcc_vq_option_highlight_show" value="1" <?php checked( $vq_option_highlight_show, '1' ) ?> />
                                    </div>
                                </td>
                                <td class="vq-options-table-trash"></td>
                            </tr>
                            <?php

                            if( !empty( $vq_options_table ) && is_array( $vq_options_table ) )
                            {
                                $counter = 0;

                                foreach( $vq_options_table as $k => $vq_o_t )
                                {
                                    ?>
                                    <tr class="wc-hcc-vq-option simple-quantity">
                                        <td style="text-align: center;">
                                            <input type="radio"
                                                   name="hcc_vq_option_selected"
                                                   class="vq-option-selected"
                                                <?php checked( $vq_option_selected, $counter ); ?>
                                                   value="<?php echo esc_html( $counter ); ?>" />
                                        </td>
                                        <td>
                                            <input type="number"
                                                   name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][qty]"
                                                   min="0"
                                                   step="1"
                                                   pattern="[0-9]*"
                                                   class="vq-option-qty"
                                                   value="<?php echo esc_html( $vq_o_t['qty'] ); ?>" />
                                        </td>
                                        <td>
                                            <input type="text"
                                                   name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][name]"
                                                   placeholder="<?php esc_html_e('optional', 'gb-wc-hcc'); ?>"
                                                   class="vq-option-name"
                                                   value="<?php echo esc_textarea( $vq_o_t['name'] ); ?>" />
                                        </td>
                                        <td>
                                            <input type="text"
                                                   name="hcc_vq_options_sq_table[<?php echo esc_html( $counter ); ?>][price]"
                                                   placeholder="<?php esc_html_e('optional', 'gb-wc-hcc'); ?>"
                                                   min="0"
                                                   step="0.01"
                                                   class="vq-option-price"
                                                   value="<?php echo esc_textarea( $vq_o_t['price'] ); ?>" />
                                        </td>
                                        <td class="vq-option-radio" style="text-align: center;">
                                            <input type="radio"
                                                   name="hcc_vq_option_highlight"
                                                   class="vq-option-highlight"
                                                <?php checked( $vq_option_highlight, $counter ); ?>
                                                   value="<?php echo esc_html( $counter ); ?>" />
                                        </td>
                                        <td class="vq-options-table-trash">
                                            <span class="dashicons dashicons-trash wc-hcc-vq-option-remove"></span>
                                        </td>
                                    </tr>
                                    <?php

                                    $counter++;
                                }
                            }
                            else
                            {
                                ?>
                                <tr class="wc-hcc-vq-option">
                                    <td style="text-align: center;">
                                        <input type="radio"
                                               name="hcc_vq_option_selected"
                                               class="vq-option-selected"
                                               value="0"/>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="hcc_vq_options_sq_table[0][qty]"
                                               min="0"
                                               step="1"
                                               pattern="[0-9]*"
                                               class="vq-option-qty"
                                               value="1" />
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="hcc_vq_options_sq_table[0][name]"
                                               placeholder="<?php esc_html_e( 'optional', 'gb-wc-hcc' ); ?>"
                                               class="vq-option-name"
                                               value="" />
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="hcc_vq_options_sq_table[0][price]"
                                               placeholder="optional"
                                               min="0"
                                               step="0.01"
                                               class="vq-option-price"
                                               value="" />
                                    </td>
                                    <td class="vq-option-radio" style="text-align: center;">
                                        <input type="radio"
                                               name="hcc_vq_option_highlight"
                                               class="vq-option-highlight"
                                               value="0" />
                                    </td>
                                    <td class="vq-options-table-trash">
                                        <span class="dashicons dashicons-trash wc-hcc-vq-option-remove"></span>
                                    </td>
                                </tr>
                                <?php
                            }

                            ?>
                        </table>

                    </div>
                    <div class="wc-hcc-vq-option-controls wc-hcc-vq-options-table-simple" style="display: none;">
                        <button class="button wc-hcc-vq-option-add"><?php esc_html_e( '+ add option', 'gb-wc-hcc' ); ?></button>
                    </div>
                    <div class="wc-hcc-vq-options-table-variation" style="display: none;">
                        <?php esc_html_e( 'Options will be taken from variations of the selected product(s)', 'gb-wc-hcc' ); ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Template', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                </td>
            </tr>

            <tr>
                <td colspan="2" id="wc-hcc-template-name">
                    <input type="hidden" name="hcc_template_name" value="<?php echo esc_html( $template_name ); ?>" class="gb-wc-hcc-template-id" />
                    <div class="wc-hcc-templates-wrap">
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template" data-disabled-options="custom-settings-tab">
                                <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template.jpg', __FILE__ ) ); ?>" />
                                <div class="wc-hcc-template-name">WebCart Original</div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-2' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-2" data-disabled-options="custom-settings-tab">
                                <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-2.jpg', __FILE__ ) ); ?>" />
                                <div class="wc-hcc-template-name">WebCart Blue</div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-3' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-3" data-disabled-options="custom-settings-tab">
                                <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-3.jpg', __FILE__ ) ); ?>" />
                                <div class="wc-hcc-template-name">Marketing Pro</div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-4' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-4" data-disabled-options="custom-settings-tab">
                                <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-4.jpg', __FILE__ ) ); ?>" />
                                <div class="wc-hcc-template-name">Minimalist</div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-5' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-5" data-disabled-options="custom-settings-tab">
                                <div class="wc-hcc-template-inner">
                                    <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-5.jpg', __FILE__ ) ); ?>" />
                                    <div class="wc-hcc-template-name">Two Step</div>
                                </div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-6' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-6" data-disabled-options="custom-settings-tab,product-title-field,product-description-field,product-image-field">
                                <div class="wc-hcc-template-inner">
                                    <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-6.jpg', __FILE__ ) ); ?>" />
                                    <div class="wc-hcc-template-name">Multi Product</div>
                                </div>
                            </div>
                        </div>
                        <div class="wc-hcc-template-wrap">
                            <div class="wc-hcc-template <?php if( $template_name == 'gb-wc-hcc-template-7' ){ echo 'wc-hcc-template-selected'; } ?>" data-template="gb-wc-hcc-template-7" data-disabled-options="color-header-field,color-headline-field">
                                <div class="wc-hcc-template-inner">
                                    <img src="<?php echo esc_url( plugins_url( 'templates/gb-wc-hcc-template-7.jpg', __FILE__ ) ); ?>" />
                                    <div class="wc-hcc-template-name">Vitamin (check the Custom tab at the top)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Template Colors', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <table class="wc-hcc-table-inner">

                        <tr class="wc-hcc-color-header-field">
                            <td style="min-width: 125px;"><?php esc_html_e( 'Header Color', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="text" name="hcc_color_header" value="<?php echo esc_textarea( $color_header ); ?>" class="wc-hcc-color-picker" />
                            </td>
                        </tr>

                        <tr class="wc-hcc-color-footer-field">
                            <td style="min-width: 125px;"><?php esc_html_e( 'Footer Color', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="text" name="hcc_color_footer" value="<?php echo esc_textarea( $color_footer ); ?>" class="wc-hcc-color-picker" />
                            </td>
                        </tr>

                        <tr class="wc-hcc-color-bg-field">
                            <td style="min-width: 125px;"><?php esc_html_e( 'Background Color', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="text" name="hcc_color_bg" value="<?php echo esc_textarea( $color_bg ); ?>" class="wc-hcc-color-picker" />
                            </td>
                        </tr>

                        <tr class="wc-hcc-color-headline-field">
                            <td style="min-width: 125px;"><?php esc_html_e( 'Headline Color', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="text" name="hcc_color_headline" value="<?php echo esc_textarea( $color_headline ); ?>" class="wc-hcc-color-picker" />
                            </td>
                        </tr>

                        <tr class="wc-hcc-color-button-field">
                            <td style="min-width: 125px;"><?php esc_html_e( 'Button Color', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="text" name="hcc_color_button_1" value="<?php echo esc_textarea( $color_button_1 ); ?>" class="wc-hcc-color-picker" />
                                <input type="text" name="hcc_color_button_2" value="<?php echo esc_textarea( $color_button_2 ); ?>" class="wc-hcc-color-picker" />
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Fields', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <table class="wc-hcc-table-inner">
                        <tr>
                            <td style="min-width: 150px;"><?php esc_html_e( 'Email field first', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_billing_email_first" value="1" <?php checked( $fields_billing_email_first, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Swap billing & shipping', 'gb-wc-hcc' ); ?>&nbsp;</td>
                            <td>
                                <input type="checkbox" name="hcc_fields_shipping_first" value="1" <?php checked( $fields_shipping_first, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Billing Address', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input id="wc-hcc-fields-billing" type="checkbox" name="hcc_fields_billing" value="1" <?php checked( $fields_billing, '1' ) ?> />&nbsp;
                                <span class="tip"><?php esc_html_e( 'important: should be disabled only for "virtual" products', 'gb-wc-hcc' ); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'First + Last Name', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_billing_names" value="1" <?php checked( $fields_billing_names, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Company name', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_billing_company" value="1" <?php checked( $fields_billing_company, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Phone Number', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_phone" value="1" <?php checked( $fields_phone, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Shipping Address', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_shipping" value="1" <?php checked( $fields_shipping, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Guarantee Image', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_guarantee" value="1" <?php checked( $fields_guarantee, '1' ) ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Coupon Box', 'gb-wc-hcc' ); ?></td>
                            <td>
                                <input type="checkbox" name="hcc_fields_coupon" value="1" <?php checked( $fields_coupon, '1' ) ?> />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Order Details', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="checkbox" name="hcc_order_details" value="1" <?php checked( $order_details, '1' ) ?> />
                    <span class="tip"><?php esc_html_e( 'display order details table', 'gb-wc-hcc' ); ?></span>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Hide Order Total', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="checkbox" name="hcc_order_total_hide" value="1" <?php checked( $order_total_hide, '1' ) ?> />
                    <span class="tip"><?php esc_html_e( 'hide order total box (above the "place order" button)', 'gb-wc-hcc' ); ?></span>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 1 Title Text', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_title_text_step1" value="<?php echo esc_textarea( $title_text_step1 ); ?>" placeholder="<?php esc_html_e( 'Step 1: Billing Details', 'gb-wc-hcc' ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'title_text_step1', $title_text_step1_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 2 Title Text', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_title_text_step2" value="<?php echo esc_textarea( $title_text_step2 ); ?>" placeholder="<?php esc_html_e( 'Step 2: Payment Information', 'gb-wc-hcc' ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'title_text_step2', $title_text_step2_style ); ?>
                </td>
            </tr>

            <tr class="gb-wc-hcc-template-5">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Step 1 Button Text', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_button_text_step1" value="<?php echo esc_textarea( $button_text_step1 ); ?>" placeholder="<?php esc_html_e( 'Next Step', 'gb-wc-hcc' ); ?>" />
                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>
                    <br />
                    <span class="tip"><?php esc_html_e( 'used in multistep templates only', 'gb-wc-hcc' ); ?></span>

                    <?php echo gb_hcc_config_style( 'button_text_step1', $button_text_step1_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Checkout Button Text', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_button_text" value="<?php echo esc_textarea( $button_text ); ?>" placeholder="<?php esc_html_e( 'Place order', 'woocommerce' ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'button_text', $button_text_style, 'button' ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Company Logo', 'gb-wc-hcc' ); ?></div>
                    <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 250&times;50px</div>
                </td>
                <td class="wc-hcc-image-field-wrapper">
                    <div class="wc-hcc-left">
                        <div class="wc-hcc-image-wrapper">
                            <?php

                            if( !empty( $marketplace_image ) )
                            {
                                $marketplace_image_src = wp_get_attachment_image_src( $marketplace_image, 'full' );

                                if( !empty( $marketplace_image_src[0] ) )
                                {
                                    echo '<img src="' . esc_url( $marketplace_image_src[0] ) . '" />';
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="wc-hcc-right">
                        <div class="wc-hcc-image-controls">
                            <input type="hidden" name="hcc_marketplace_image" value="<?php if( !empty( $marketplace_image ) ) echo esc_textarea( $marketplace_image ); ?>" class="wc-hcc-image-id" />

                            <div>
                                <button class="wc-hcc-image-select button" data-uploader-title="<?php esc_html_e( 'Select company logo', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                            </div>

                            <div>
                                <a href="#" class="wc-hcc-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Company Logo Alignment', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <select name="hcc_marketplace_image_align">
                        <option value="left" <?php if( $marketplace_image_align == 'left' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'left', 'gb-wc-hcc' ); ?></option>
                        <option value="center" <?php if( $marketplace_image_align == 'center' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'center', 'gb-wc-hcc' ); ?></option>
                        <option value="right" <?php if( $marketplace_image_align == 'right' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'right', 'gb-wc-hcc' ); ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Bullet Points Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_bullet_points_title" value="<?php echo esc_textarea( $bullet_points_title ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'bullet_points_title', $bullet_points_title_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Bullet Points', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <div class="wc-hcc-bullets-wrap" data-count="1">
                        <?php

                        if( !empty( $bullet_points ) && is_array( $bullet_points ) )
                        {
                            $counter = 0;

                            foreach( $bullet_points as $k => $b_p )
                            {
                                ?>
                                <div class="wc-hcc-bullet">
                                    <input type="text" name="hcc_bullet_points[<?php echo esc_html( $counter ); ?>]" value="<?php echo esc_textarea( $b_p ); ?>" />

                                    <span class="dashicons dashicons-trash wc-hcc-bullet-remove"></span>
                                </div>
                                <?php

                                $counter++;

                            }
                        }
                        else
                        {
                            ?>
                            <div class="wc-hcc-bullet">
                                <input type="text" name="hcc_bullet_points[0]" value="" />

                                <span class="dashicons dashicons-trash wc-hcc-bullet-remove"></span>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="wc-hcc-bullet-controls">
                        <button class="button wc-hcc-bullet-add"><?php esc_html_e( '+ add bullet', 'gb-wc-hcc' ); ?></button>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Testimonials Title', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input type="text" name="hcc_testimonials_title" value="<?php echo esc_textarea( $testimonials_title ); ?>" />

                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'testimonials_title', $testimonials_title_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Testimonials', 'gb-wc-hcc' ); ?></div>
                    <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 100&times;100px</div>
                </td>
                <td>
                    <div class="wc-hcc-testimonials-wrap" data-count="1">
                        <?php

                        if( !empty( $testimonials ) && is_array( $testimonials ) )
                        {
                            $counter = 0;

                            foreach( $testimonials as $k => $t )
                            {
                                if( empty( $t['title'] ) )
                                {
                                    $t['title'] = '';
                                }

                                ?>
                                <div class="wc-hcc-testimonial">
                                    <div class="wc-hcc-left-30">
                                        <div class="wc-hcc-testimonial-image">
                                            <input type="hidden" name="hcc_testimonials[<?php echo esc_html( $counter ); ?>][image]" value="<?php echo esc_html( $t['image'] ); ?>" class="wc-hcc-testimonial-image-id" />

                                            <div class="wc-hcc-testimonial-image-wrapper">
                                                <?php

                                                if( !empty( $t['image'] ) )
                                                {
                                                    $t['image'] = wp_get_attachment_image_src( $t['image'], 'full' );

                                                    if( !empty( $t['image'][0] ) )
                                                    {
                                                        echo '<img src="' . esc_url( $t['image'][0] ) . '" />';
                                                    }
                                                }

                                                ?>
                                            </div>

                                            <div>
                                                <button class="wc-hcc-testimonial-image-select button" data-uploader-title="<?php esc_html_e( 'Select testimonial image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                                            </div>

                                            <div>
                                                <a href="#" class="wc-hcc-testimonial-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wc-hcc-right-70">
                                        <input type="text" name="hcc_testimonials[<?php echo esc_html( $counter ); ?>][title]" value="<?php echo esc_textarea( $t['title'] ); ?>" placeholder="<?php esc_html_e( 'Testimonial title...', 'gb-wc-hcc' ); ?>" />
                                        <textarea name="hcc_testimonials[<?php echo esc_html( $counter ); ?>][text]" placeholder="<?php esc_html_e( 'Testimonial text...', 'gb-wc-hcc' ); ?>"><?php echo $t['text']; ?></textarea>
                                        <div class="wc-hcc-testimonial-remove-wrap">
                                            <span class="dashicons dashicons-trash wc-hcc-testimonial-remove"></span>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php

                                $counter++;

                            }
                        }
                        else
                        {
                            ?>
                            <div class="wc-hcc-testimonial">
                                <div class="wc-hcc-left-30">
                                    <div class="wc-hcc-testimonial-image">
                                        <input type="hidden" name="hcc_testimonials[0][image]" value="" class="wc-hcc-testimonial-image-id" />

                                        <div class="wc-hcc-testimonial-image-wrapper">

                                        </div>

                                        <div>
                                            <button class="wc-hcc-testimonial-image-select button" data-uploader-title="<?php esc_html_e( 'Select testimonial image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                                        </div>

                                        <div>
                                            <a href="#" class="wc-hcc-testimonial-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="wc-hcc-right-70">
                                    <input type="text" name="hcc_testimonials[0][title]" value="" placeholder="<?php esc_html_e( 'Testimonial title...', 'gb-wc-hcc' ); ?>" />
                                    <textarea name="hcc_testimonials[0][text]" placeholder="<?php esc_html_e( 'Testimonial text...', 'gb-wc-hcc' ); ?>"></textarea>
                                    <div class="wc-hcc-testimonial-remove-wrap">
                                        <span class="dashicons dashicons-trash wc-hcc-testimonial-remove"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="wc-hcc-testimonials-controls">
                        <button class="button wc-hcc-testimonials-add"><?php esc_html_e( '+ add testimonial', 'gb-wc-hcc' ); ?></button>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Trust Points', 'gb-wc-hcc' ); ?></div>
                    <div class="button button-small wc-hcc-trustpoints-fill" id="wc-hcc-trustpoints-fill"><?php esc_html_e( 'fill in the default points', 'gb-wc-hcc' ); ?></div>
                    <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 100&times;100px</div>
                </td>
                <td>
                    <div class="wc-hcc-trustpoints-wrap" data-count="1">
                        <?php

                        if( !empty( $trustpoints ) && is_array( $trustpoints ) )
                        {
                            $counter = 0;

                            foreach( $trustpoints as $k => $t )
                            {
                                ?>
                                <div class="wc-hcc-trustpoint">
                                    <div class="wc-hcc-left-30">
                                        <div class="wc-hcc-trustpoint-image">
                                            <input type="hidden" name="hcc_trustpoints[<?php echo esc_html( $counter ); ?>][image]" value="<?php echo esc_html( $t['image'] ); ?>" class="wc-hcc-trustpoint-image-id" />

                                            <div class="wc-hcc-trustpoint-image-wrapper">
                                                <?php

                                                if( !empty( $t['image'] ) )
                                                {
                                                    $t['image'] = wp_get_attachment_image_src( $t['image'], 'full' );

                                                    if( !empty( $t['image'][0] ) )
                                                    {
                                                        echo '<img src="' . esc_url( $t['image'][0] ) . '" />';
                                                    }
                                                }

                                                ?>
                                            </div>

                                            <div>
                                                <button class="wc-hcc-trustpoint-image-select button" data-uploader-title="<?php esc_html_e( 'Select trust point image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                                            </div>

                                            <div>
                                                <a href="#" class="wc-hcc-trustpoint-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wc-hcc-right-70">
                                        <input type="text" name="hcc_trustpoints[<?php echo esc_html( $counter ); ?>][title]" value="<?php echo esc_textarea( $t['title'] ); ?>" placeholder="<?php esc_html_e( 'Trust point title...', 'gb-wc-hcc' ); ?>" />
                                        <textarea name="hcc_trustpoints[<?php echo esc_html( $counter ); ?>][text]" placeholder="<?php esc_html_e( 'Trust point text...', 'gb-wc-hcc' ); ?>"><?php echo ( $t['text'] ); ?></textarea>
                                        <div class="wc-hcc-trustpoint-remove-wrap">
                                            <span class="dashicons dashicons-trash wc-hcc-trustpoint-remove"></span>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <?php

                                $counter++;

                            }
                        }
                        else
                        {
                            ?>
                            <div class="wc-hcc-trustpoint">
                                <div class="wc-hcc-left-30">
                                    <div class="wc-hcc-trustpoint-image">
                                        <input type="hidden" name="hcc_trustpoints[0][image]" value="" class="wc-hcc-trustpoint-image-id" />

                                        <div class="wc-hcc-trustpoint-image-wrapper">

                                        </div>

                                        <div>
                                            <button class="wc-hcc-trustpoint-image-select button" data-uploader-title="<?php esc_html_e( 'Select trust point image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                                        </div>

                                        <div>
                                            <a href="#" class="wc-hcc-trustpoint-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="wc-hcc-right-70">
                                    <input type="text" name="hcc_trustpoints[0][title]" value="" placeholder="<?php esc_html_e( 'Trust point title...', 'gb-wc-hcc' ); ?>" />
                                    <textarea name="hcc_trustpoints[0][text]" placeholder="<?php esc_html_e( 'Trust point text...', 'gb-wc-hcc' ); ?>"></textarea>
                                    <div class="wc-hcc-trustpoint-remove-wrap">
                                        <span class="dashicons dashicons-trash wc-hcc-trustpoint-remove"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="wc-hcc-trustpoints-controls">
                        <button class="button wc-hcc-trustpoints-add"><?php esc_html_e( '+ add trust point', 'gb-wc-hcc' ); ?></button>
                    </div>
                </td>
            </tr>

        </table>

        <table class="wc-hcc-table wc-hcc-table-advanced-settings" style="display: none;">

            <tr>
                <td style="width: 30%;">
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom CSS', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea name="hcc_custom_css"><?php echo $custom_css; ?></textarea>
                    <p class="description"><?php esc_html_e( 'Custom CSS from this field will be applied only to the current page.', 'gb-wc-hcc' ); ?></p>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom JS (Header)', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea name="hcc_custom_js_head"><?php echo $custom_js_head; ?></textarea>
                    <p class="description"><?php esc_html_e( 'Custom JS from this field will be applied only to the header of current page. &#60;script&#62; tags are not required.', 'gb-wc-hcc' ); ?></p>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom JS (Footer)', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea name="hcc_custom_js"><?php echo $custom_js; ?></textarea>
                    <p class="description"><?php esc_html_e( 'Custom JS from this field will be applied only to the footer of current page. &#60;script&#62; tags are not required.', 'gb-wc-hcc' ); ?></p>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Custom Footer HTML', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <textarea name="hcc_custom_html_footer"><?php echo $custom_html_footer; ?></textarea>
                    <p class="description"><?php esc_html_e( 'Custom HTML from this field will be displayed in the footer of the current page.', 'gb-wc-hcc' ); ?></p>
                </td>
            </tr>

            <tr class="wc-hcc-product-title-field">
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Support Email', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input id="wc-hcc-product-title" type="text" name="hcc_support_email" value="<?php echo esc_textarea( $support_email ); ?>" />
                    <br />
                    <span class="tip"><?php esc_html_e( 'used instead of the global support email', 'gb-wc-hcc' ); ?></span>
                </td>
            </tr>

        </table>

        <table class="wc-hcc-table wc-hcc-table-custom-settings" style="display: none;">

            <tr>
                <td style="width: 30%;">
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Main Headline', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input id="wc-hcc-headline" type="text" name="hcc_headline" value="<?php echo esc_textarea( $headline ); ?>" />
                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'headline', $headline_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Sub Headline', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <input id="wc-hcc-subheadline" type="text" name="hcc_subheadline" value="<?php echo esc_textarea( $subheadline ); ?>" />
                    <span class="wc-hcc-config-style">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </span>

                    <?php echo gb_hcc_config_style( 'subheadline', $subheadline_style ); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Ingredients Image', 'gb-wc-hcc' ); ?></div>
                    <!-- <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 250&times;50px</div> -->
                </td>
                <td class="wc-hcc-image-field-wrapper">
                    <div class="wc-hcc-left">
                        <div class="wc-hcc-image-wrapper">
                            <?php

                            if( !empty( $ingredients_image ) )
                            {
                                $ingredients_image_src = wp_get_attachment_image_src( $ingredients_image, 'full' );

                                if( !empty( $ingredients_image_src[0] ) )
                                {
                                    echo '<img src="' . esc_url( $ingredients_image_src[0] ) . '" />';
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="wc-hcc-right">
                        <div class="wc-hcc-image-controls">
                            <input type="hidden" name="hcc_ingredients_image" value="<?php if( !empty( $ingredients_image ) ) echo esc_html( $ingredients_image ); ?>" class="wc-hcc-image-id" />

                            <div>
                                <button class="wc-hcc-image-select button" data-uploader-title="<?php esc_html_e( 'Select ingredients image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                            </div>

                            <div>
                                <a href="#" class="wc-hcc-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Deals', 'gb-wc-hcc' ); ?></div>
                    <div class="wc-hcc-field-rs"><?php esc_html_e( 'Deals are used to offer different tiers of the same product right on the checkout page. For example: 1 bottle of Vitamins as Trial, 3 bottles as Good Deal, 3 bottles each 3 months subscription as Great Deal and 6 bottles as Best Deal.', 'gb-wc-hcc' ); ?></div>
                </td>
                <td>
                    <div class="wc-hcc-deals-wrap" data-count="1">
                        <?php

                        if( !empty( $deals ) && is_array( $deals ) )
                        {
                            $counter = 0;

                            foreach( $deals as $k => $d )
                            {
                                ?>
                                <div class="wc-hcc-deal">

                                    <div>
                                        <select name="hcc_deals[<?php echo esc_html( $counter ); ?>][type]">
                                            <option><?php esc_html_e( 'Select deal type...', 'gb-wc-hcc' ); ?></option>
                                            <option value="best" <?php selected( $d['type'], 'best' ); ?>><?php esc_html_e( 'Best deal', 'gb-wc-hcc' ); ?></option>
                                            <option value="great" <?php selected( $d['type'], 'great' ); ?>><?php esc_html_e( 'Great deal', 'gb-wc-hcc' ); ?></option>
                                            <option value="good" <?php selected( $d['type'], 'good' ); ?>><?php esc_html_e( 'Good deal', 'gb-wc-hcc' ); ?></option>
                                            <option value="trial" <?php selected( $d['type'], 'trial' ); ?>><?php esc_html_e( 'Trial', 'gb-wc-hcc' ); ?></option>
                                        </select>
                                    </div>

                                    <div>
                                        <input type="text" name="hcc_deals[<?php echo esc_html( $counter ); ?>][title]" value="<?php echo esc_textarea( $d['title'] ); ?>" placeholder="<?php esc_html_e( 'Enter deal title...', 'gb-wc-hcc' ); ?>" />
                                    </div>

                                    <div>
                                        <textarea name="hcc_deals[<?php echo esc_html( $counter ); ?>][description]" placeholder="<?php esc_html_e( 'Enter deal description...', 'gb-wc-hcc' ); ?>"><?php echo $d['description']; ?></textarea>
                                    </div>

                                    <div>
                                        <select
                                            name="hcc_deals[<?php echo esc_html( $counter ); ?>][product_id]"
                                            class="wc-hcc-product-search"
                                            data-selected="<?php if( !empty( $d['product_id_json'] ) ) echo esc_textarea( $d['product_id_json'] ); ?>"
                                            data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                                            data-action="woocommerce_json_search_products_and_variations">
                                        </select>
                                    </div>

                                    <div class="wc-hcc-deal-remove-wrap">
                                        <span class="dashicons dashicons-trash wc-hcc-deal-remove"></span>
                                    </div>
                                </div>
                                <?php

                                $counter++;
                            }
                        }
                        else
                        {
                            ?>
                            <div class="wc-hcc-deal">

                                <div>
                                    <select name="hcc_deals[0][type]">
                                        <option><?php esc_html_e( 'Select deal type...', 'gb-wc-hcc' ); ?></option>
                                        <option value="best"><?php esc_html_e( 'Best deal', 'gb-wc-hcc' ); ?></option>
                                        <option value="great"><?php esc_html_e( 'Great deal', 'gb-wc-hcc' ); ?></option>
                                        <option value="good"><?php esc_html_e( 'Good deal', 'gb-wc-hcc' ); ?></option>
                                        <option value="trial"><?php esc_html_e( 'Trial', 'gb-wc-hcc' ); ?></option>
                                    </select>
                                </div>

                                <div>
                                    <input type="text" name="hcc_deals[0][title]" value=""  placeholder="<?php esc_html_e( 'Enter deal title...', 'gb-wc-hcc' ); ?>" />
                                </div>

                                <div>
                                    <textarea name="hcc_deals[0][description]" placeholder="<?php esc_html_e( 'Enter deal description...', 'gb-wc-hcc' ); ?>"></textarea>
                                </div>

                                <div>
                                    <select
                                        name="hcc_deals[0][product_id]"
                                        class="wc-hcc-product-search"
                                        data-selected=""
                                        data-placeholder="<?php esc_html_e( 'search for a product&hellip;', 'gb-wc-hcc' ); ?>"
                                        data-action="woocommerce_json_search_products_and_variations">
                                    </select>
                                </div>

                                <div class="wc-hcc-deal-remove-wrap">
                                    <span class="dashicons dashicons-trash wc-hcc-deal-remove"></span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="wc-hcc-deals-controls">
                        <button class="button wc-hcc-deals-add"><?php esc_html_e( '+ add deal', 'gb-wc-hcc' ); ?></button>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Trust Seal Top Image', 'gb-wc-hcc' ); ?></div>
                    <!-- <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 250&times;50px</div> -->
                </td>
                <td class="wc-hcc-image-field-wrapper">
                    <div class="wc-hcc-left">
                        <div class="wc-hcc-image-wrapper">
                            <?php

                            if( !empty( $trustseal_top_image ) )
                            {
                                $trustseal_top_image_src = wp_get_attachment_image_src( $trustseal_top_image, 'full' );

                                if( !empty( $trustseal_top_image_src[0] ) )
                                {
                                    echo '<img src="' . esc_url( $trustseal_top_image_src[0] ) . '" />';
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="wc-hcc-right">
                        <div class="wc-hcc-image-controls">
                            <input type="hidden" name="hcc_trustseal_top_image" value="<?php if( !empty( $trustseal_top_image ) ) echo esc_html( $trustseal_top_image ); ?>" class="wc-hcc-image-id" />

                            <div>
                                <button class="wc-hcc-image-select button" data-uploader-title="<?php esc_html_e( 'Select trust seal top image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                            </div>

                            <div>
                                <a href="#" class="wc-hcc-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="wc-hcc-field-label"><?php esc_html_e( 'Trust Seal Bottom Image', 'gb-wc-hcc' ); ?></div>
                    <!-- <div class="wc-hcc-field-rs"><?php esc_html_e( 'Recommended image size', 'gb-wc-hcc' ); ?>: 250&times;50px</div> -->
                </td>
                <td class="wc-hcc-image-field-wrapper">
                    <div class="wc-hcc-left">
                        <div class="wc-hcc-image-wrapper">
                            <?php

                            if( !empty( $trustseal_bottom_image ) )
                            {
                                $trustseal_bottom_image_src = wp_get_attachment_image_src( $trustseal_bottom_image, 'full' );

                                if( !empty( $trustseal_bottom_image_src[0] ) )
                                {
                                    echo '<img src="' . esc_url( $trustseal_bottom_image_src[0] ) . '" />';
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="wc-hcc-right">
                        <div class="wc-hcc-image-controls">
                            <input type="hidden" name="hcc_trustseal_bottom_image" value="<?php if( !empty( $trustseal_bottom_image ) ) echo esc_html( $trustseal_bottom_image ); ?>" class="wc-hcc-image-id" />

                            <div>
                                <button class="wc-hcc-image-select button" data-uploader-title="<?php esc_html_e( 'Select trust seal bottom image', 'gb-wc-hcc' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Select', 'gb-wc-hcc' ); ?>"><?php esc_html_e( 'Select image', 'gb-wc-hcc' ); ?></button>
                            </div>

                            <div>
                                <a href="#" class="wc-hcc-image-remove"><?php esc_html_e( 'remove', 'gb-wc-hcc' ); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>

        </table>

    </div>

    <div class="wc-hcc-save-bar">
        <div>
            <div class="button button-primary button-large" id="wc-hcc-save-btn"><?php esc_html_e( 'Update' ); ?></div>
        </div>
    </div>

    <script type="text/javascript">
        var hcc_media_frame;

        var $hcc_testimonial;

        jQuery(document).ready(function(){

            jQuery('.wc-hcc-tabs h2').on('click', function(){

                var $this = jQuery(this);

                jQuery('.wc-hcc-tabs h2').removeClass('wc-hcc-tab-selected');

                $this.addClass('wc-hcc-tab-selected');

                jQuery('.wc-hcc-table').hide();

                jQuery('.wc-hcc-table-' + $this.data('tab') ).show();
            });

            // remove hide-if-js class from container

            if( jQuery('#gb_hcc_metabox').hasClass('hide-if-js') )
            {
                jQuery('#gb_hcc_metabox').removeClass('hide-if-js');
            }

            // product image select

            jQuery('.wc-hcc-image-field-wrapper').on('click', '.wc-hcc-image-select', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );
                var $wrap = $this.closest('.wc-hcc-image-field-wrapper');

                hcc_media_frame = wp.media.frames.file_frame = wp.media({

                    title: jQuery( this ).data( 'uploader-title' ),
                    button: {
                        text: jQuery( this ).data( 'uploader-button-text' ),
                    },
                    multiple: false
                });

                hcc_media_frame.on( 'select', function(){

                    attachment = hcc_media_frame.state().get('selection').first().toJSON();

                    if( attachment )
                    {
                        $wrap.find('.wc-hcc-image-id').val( attachment.id );

                        $wrap.find('.wc-hcc-image-wrapper').empty().append( jQuery('<img>').attr( 'src', attachment.sizes.full.url ) );
                    }
                });

                hcc_media_frame.open();
            });

            // product image remove

            jQuery('.wc-hcc-image-remove').on('click', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );
                var $wrap = $this.closest('.wc-hcc-image-field-wrapper');

                $wrap.find('.wc-hcc-image-id').val( '' );

                $wrap.find('.wc-hcc-image-wrapper').empty();
            });

            // order bump

            jQuery('input[name="hcc_order_bump"]').on('change', function(){

                var $this = jQuery(this);

                var $order_bump = jQuery('.order-bump');

                if( $this.is(':checked') )
                {
                    $order_bump.show();
                }
                else
                {
                    $order_bump.hide();
                }

            }).trigger( 'change' );

            // variations & quantity options

            jQuery( 'input[name="hcc_vq_options"]' ).on( 'change', function() {
                var $this = jQuery( this );

                var $vq_options = jQuery( '.vq-options' );

                if( $this.is( ':checked' ) ) {
                    $vq_options.show();
                } else {
                    $vq_options.hide();
                }
            }).trigger( 'change' );

            // toggle vq options mode

            jQuery( 'select[name="hcc_vq_options_mode"]' ).on( 'change', function() {
                var $this = jQuery( this );

                if ( $this.val() === 'simple_quantity' ) {
                    jQuery( '.wc-hcc-vq-options-table-variation' ).hide();
                    jQuery( '.wc-hcc-vq-options-table-simple' ).show();
                } else {
                    jQuery( '.wc-hcc-vq-options-table-simple' ).hide();
                    jQuery( '.wc-hcc-vq-options-table-variation' ).show();
                }
            }).trigger( 'change' );

            // add option item

            jQuery( '.wc-hcc-vq-option-add' ).on( 'click', function( event ) {
                event.preventDefault();

                var $hcc_vq_options_table = jQuery( '.wc-hcc-vq-options-table' );

                var $hcc_option = jQuery( '.wc-hcc-vq-option' ).clone().css( { 'display': 'none' } );

                var hcc_option_count = $hcc_vq_options_table.find( '.wc-hcc-vq-option' ).length;

                $hcc_option.find( '.vq-option-qty' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][qty]' ).attr( 'value', 1 );

                $hcc_option.find( '.vq-option-price' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][price]' ).attr( 'value', '' );

                $hcc_option.find( '.vq-option-name' ).attr( 'name', 'hcc_vq_options_sq_table[' + hcc_option_count + '][name]' ).attr( 'value', '' );

                $hcc_option.find( '.vq-option-highlight' ).attr( 'name', 'hcc_vq_option_highlight' ).attr( 'value', hcc_option_count );

                $hcc_option.find( '.vq-option-selected' ).attr( 'name', 'hcc_vq_option_selected' ).attr( 'value', hcc_option_count );

                $hcc_vq_options_table.append( $hcc_option[0].outerHTML );

                $hcc_vq_options_table.find( '.wc-hcc-vq-option' ).last().slideDown( 300 );

            });

            // remove option item

            jQuery( '.wc-hcc-vq-options-table' ).on( 'click', '.wc-hcc-vq-option-remove', function( event ) {
                event.preventDefault();

                if( confirm('<?php esc_html_e( 'Are you sure?', 'gb-wc-hcc' ); ?>') != true )
                {
                    return false;
                }

                var $this = jQuery( event.target );

                var $hcc_vq_options_table = jQuery( '.wc-hcc-vq-options-table' );

                if( $hcc_vq_options_table.find('.wc-hcc-vq-option').length == 1 ) {
                    //
                } else {
                    $this.closest( '.wc-hcc-vq-option' ).slideUp( 300, function() {
                        jQuery( this ).remove();
                    });
                }
            });

            // select template

            jQuery('.wc-hcc-template').click(function(){

                var $this = jQuery(this);

                jQuery('.wc-hcc-template').removeClass('wc-hcc-template-selected');

                $this.addClass('wc-hcc-template-selected');

                jQuery('.gb-wc-hcc-template-id').val( $this.data('template') );

                // hide unused options

                jQuery('.wc-hcc-hidden-field').removeClass('wc-hcc-hidden-field').show();

                if( typeof $this.data('disabled-options') !== 'undefined' )
                {
                    jQuery.each( $this.data('disabled-options').split(','), function( index, value ){

                        jQuery('.wc-hcc-' + value).addClass('wc-hcc-hidden-field').hide();
                    });
                }
            });

            jQuery('.wc-hcc-template-selected').trigger('click');

            var $preview = jQuery('.wc-hcc-template-preview');

            // init color picker

            jQuery('.wc-hcc-color-picker').wpColorPicker();

            // set default count for bullets

            jQuery('.wc-hcc-bullets-wrap').data('count', jQuery('.wc-hcc-bullets-wrap').find('.wc-hcc-bullet').length );

            // add bullet item

            jQuery('.wc-hcc-bullet-add').on('click', function( event ){

                event.preventDefault();

                var $hcc_bullets_wrap = jQuery('.wc-hcc-bullets-wrap');

                var $hcc_bullet = jQuery('.wc-hcc-bullet').clone().css({'display': 'none'});

                var hcc_bullet_count = $hcc_bullets_wrap.data('count');

                $hcc_bullet.find('input').attr('name', 'hcc_bullet_points[' + hcc_bullet_count + ']').attr('value','');

                $hcc_bullets_wrap.append( $hcc_bullet[0].outerHTML );

                $hcc_bullets_wrap.find('.wc-hcc-bullet').last().slideDown( 300 );

                // increase counter

                $hcc_bullets_wrap.data('count', hcc_bullet_count + 1);
            });

            // remove bullet item

            jQuery('.wc-hcc-bullets-wrap').on('click', '.wc-hcc-bullet-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_bullets_wrap = jQuery('.wc-hcc-bullets-wrap');

                if( $hcc_bullets_wrap.find('.wc-hcc-bullet').length == 1 )
                {
                    //
                }
                else
                {
                    $this.closest('.wc-hcc-bullet').slideUp( 300, function(){

                        jQuery(this).remove();
                    });
                }
            });

            // set default count for testimonials

            jQuery('.wc-hcc-testimonials-wrap').data('count', jQuery('.wc-hcc-testimonials-wrap').find('.wc-hcc-testimonial').length );

            // add testimonial

            jQuery('.wc-hcc-testimonials-add').on('click', function( event ){

                event.preventDefault();

                var $hcc_testimonials_wrap = jQuery('.wc-hcc-testimonials-wrap');

                var $hcc_testimonial = jQuery('.wc-hcc-testimonial').clone().css({'display': 'none'});

                var hcc_testimonial_count = $hcc_testimonials_wrap.data('count');

                $hcc_testimonial.find('input').attr('name', 'hcc_testimonials[' + hcc_testimonial_count + '][image]').attr('value','');
                $hcc_testimonial.find('textarea').attr('name', 'hcc_testimonials[' + hcc_testimonial_count + '][text]').text('');
                $hcc_testimonial.find('.wc-hcc-testimonial-image-wrapper').empty();

                $hcc_testimonials_wrap.append( $hcc_testimonial[0].outerHTML );

                $hcc_testimonials_wrap.find('.wc-hcc-testimonial').last().slideDown( 300 );

                // increase counter

                $hcc_testimonials_wrap.data('count', hcc_testimonial_count + 1);
            });

            // remove testimonial

            jQuery('.wc-hcc-testimonials-wrap').on('click', '.wc-hcc-testimonial-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_testimonials_wrap = jQuery('.wc-hcc-testimonials-wrap');

                if( $hcc_testimonials_wrap.find('.wc-hcc-testimonial').length == 1 )
                {
                    //
                }
                else
                {
                    $this.closest('.wc-hcc-testimonial').slideUp( 300, function(){

                        jQuery(this).remove();
                    });
                }
            });

            // add testimonial image

            jQuery('.wc-hcc-testimonials-wrap').on('click', '.wc-hcc-testimonial-image-select', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                $hcc_testimonial = $this.closest('.wc-hcc-testimonial'); // <- uses global var to play in events correctly

                hcc_media_frame = wp.media.frames.file_frame = wp.media({

                    title: jQuery( this ).data( 'uploader-title' ),
                    button: {
                        text: jQuery( this ).data( 'uploader-button-text' ),
                    },
                    multiple: false
                });

                hcc_media_frame.on( 'select', function(){

                    attachment = hcc_media_frame.state().get('selection').first().toJSON();

                    if( attachment )
                    {
                        $hcc_testimonial.find('.wc-hcc-testimonial-image-id').val( attachment.id );

                        $hcc_testimonial.find('.wc-hcc-testimonial-image-wrapper').empty();

                        var $testimonial_image = jQuery('<img>').attr( 'src', attachment.sizes.full.url );

                        $hcc_testimonial.find('.wc-hcc-testimonial-image-wrapper').append( $testimonial_image );
                    }
                });

                hcc_media_frame.open();
            });

            // remove testimonial image

            jQuery('.wc-hcc-testimonials-wrap').on('click', '.wc-hcc-testimonial-image-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_testimonial = $this.closest('.wc-hcc-testimonial');

                $hcc_testimonial.find('.wc-hcc-testimonial-image-id').val( '' );

                $hcc_testimonial.find('.wc-hcc-testimonial-image-wrapper').empty();
            });

            // set default count for trustpoints

            jQuery('.wc-hcc-trustpoints-wrap').data('count', jQuery('.wc-hcc-trustpoints-wrap').find('.wc-hcc-trustpoint').length );

            // add trustpoint

            jQuery('.wc-hcc-trustpoints-add').on('click', function( event ){

                event.preventDefault();

                var $hcc_trustpoints_wrap = jQuery('.wc-hcc-trustpoints-wrap');

                var $hcc_trustpoint = jQuery('.wc-hcc-trustpoint').clone().css({'display': 'none'});

                var hcc_trustpoint_count = $hcc_trustpoints_wrap.data('count');

                $hcc_trustpoint.find('input[type="hidden"]').attr('name', 'hcc_trustpoints[' + hcc_trustpoint_count + '][image]').attr('value','');
                $hcc_trustpoint.find('input[type="text"]').attr('name', 'hcc_trustpoints[' + hcc_trustpoint_count + '][title]').attr('value','');
                $hcc_trustpoint.find('textarea').attr('name', 'hcc_trustpoints[' + hcc_trustpoint_count + '][text]').text('');
                $hcc_trustpoint.find('.wc-hcc-trustpoint-image-wrapper').empty();

                $hcc_trustpoints_wrap.append( $hcc_trustpoint[0].outerHTML );

                $hcc_trustpoints_wrap.find('.wc-hcc-trustpoint').last().slideDown( 300 );

                // increase counter

                $hcc_trustpoints_wrap.data('count', hcc_trustpoint_count + 1);
            });

            // remove trustpoint

            jQuery('.wc-hcc-trustpoints-wrap').on('click', '.wc-hcc-trustpoint-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_trustpoints_wrap = jQuery('.wc-hcc-trustpoints-wrap');

                if( $hcc_trustpoints_wrap.find('.wc-hcc-trustpoint').length == 1 )
                {
                    //
                }
                else
                {
                    $this.closest('.wc-hcc-trustpoint').slideUp( 300, function(){

                        jQuery(this).remove();
                    });
                }
            });

            // add trustpoint image

            jQuery('.wc-hcc-trustpoints-wrap').on('click', '.wc-hcc-trustpoint-image-select', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                $hcc_trustpoint = $this.closest('.wc-hcc-trustpoint'); // <- uses global var to play in events correctly

                hcc_media_frame = wp.media.frames.file_frame = wp.media({

                    title: jQuery( this ).data( 'uploader-title' ),
                    button: {
                        text: jQuery( this ).data( 'uploader-button-text' ),
                    },
                    multiple: false
                });

                hcc_media_frame.on( 'select', function(){

                    attachment = hcc_media_frame.state().get('selection').first().toJSON();

                    if( attachment )
                    {
                        $hcc_trustpoint.find('.wc-hcc-trustpoint-image-id').val( attachment.id );

                        $hcc_trustpoint.find('.wc-hcc-trustpoint-image-wrapper').empty();

                        var $trustpoint_image = jQuery('<img>').attr( 'src', attachment.sizes.full.url );

                        $hcc_trustpoint.find('.wc-hcc-trustpoint-image-wrapper').append( $trustpoint_image );
                    }
                });

                hcc_media_frame.open();
            });

            // remove trustpoint image

            jQuery('.wc-hcc-trustpoints-wrap').on('click', '.wc-hcc-trustpoint-image-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_trustpoint = $this.closest('.wc-hcc-trustpoint');

                $hcc_trustpoint.find('.wc-hcc-trustpoint-image-id').val( '' );

                $hcc_trustpoint.find('.wc-hcc-trustpoint-image-wrapper').empty();
            });

            // add the default trust points

            jQuery('.wc-hcc-trustpoints-fill').on('click', function(){

                var $wrap = jQuery('.wc-hcc-trustpoints-wrap');

                var $defaults = {

                    0: {
                        "title": "<?php esc_html_e( '100% Money Back Guarantee', 'gb-wc-hcc' ); ?>",
                        "text": "<?php esc_html_e( 'If you\'re not completely satisfied, get your money back.', 'gb-wc-hcc' ); ?>"
                    },
                    1: {
                        "title": "<?php esc_html_e( 'We Protect Your Privacy', 'gb-wc-hcc' ); ?>",
                        "text": "<?php esc_html_e( 'We will not share or trade online information that you provide us.', 'gb-wc-hcc' ); ?>"
                    },
                    2: {
                        "title": "<?php esc_html_e( 'Your Information is Secure', 'gb-wc-hcc' ); ?>",
                        "text": "<?php esc_html_e( 'All personal information is encrypted and secure.', 'gb-wc-hcc' ); ?>"
                    }
                };

                if( $wrap.find('input[name="hcc_trustpoints[0][title]"]').val().length == 0 )
                {
                    // fill #0 first point

                    $wrap.find('input[name="hcc_trustpoints[0][title]"]').val( $defaults[0]['title'] );

                    $wrap.find('textarea[name="hcc_trustpoints[0][text]"]').text( $defaults[0]['text'] );

                    // fill #1 second point

                    if( $wrap.find('input[name="hcc_trustpoints[1][title]"]').length == 0 )
                    {
                        jQuery('.wc-hcc-trustpoints-add').trigger('click');
                    }

                    $wrap.find('input[name="hcc_trustpoints[1][title]"]').val( $defaults[1]['title'] );

                    $wrap.find('textarea[name="hcc_trustpoints[1][text]"]').text( $defaults[1]['text'] );

                    // fill #2 third point

                    if( $wrap.find('input[name="hcc_trustpoints[2][title]"]').length == 0 )
                    {
                        jQuery('.wc-hcc-trustpoints-add').trigger('click');
                    }

                    $wrap.find('input[name="hcc_trustpoints[2][title]"]').val( $defaults[2]['title'] );

                    $wrap.find('textarea[name="hcc_trustpoints[2][text]"]').text( $defaults[2]['text'] );
                }
                else
                {
                    alert("<?php esc_html_e( 'You already have your trust points filled in.', 'gb-wc-hcc' ); ?>");
                }
            });

            /*
                custom template options
            */

            // set default count for deals

            jQuery('.wc-hcc-deals-wrap').data('count', jQuery('.wc-hcc-deals-wrap').find('.wc-hcc-deal').length );

            // add deal

            jQuery('.wc-hcc-deals-add').on('click', function( event ){

                event.preventDefault();

                var $hcc_deals_wrap = jQuery('.wc-hcc-deals-wrap');

                var $hcc_deal = jQuery('.wc-hcc-deal').clone().css({'display': 'none'});

                var hcc_deal_count = $hcc_deals_wrap.data('count');

                $hcc_deal.find('select[name*="[type]"]').attr('name', 'hcc_deals[' + hcc_deal_count + '][type]').val('').find('option').removeAttr('selected');

                $hcc_deal.find('input').attr('name', 'hcc_deals[' + hcc_deal_count + '][title]').attr('value','');

                $hcc_deal.find('textarea').attr('name', 'hcc_deals[' + hcc_deal_count + '][description]').text('');

                $hcc_deal.find('.select2-container').remove();

                $hcc_deal.find('select[name*="[product_id]"]')
                    .empty()
                    .removeClass()
                    .addClass('wc-hcc-product-search')
                    .data('selected', '')
                    .attr('data-selected', '')
                    .attr('name', 'hcc_deals[' + hcc_deal_count + '][product_id]')
                    .attr('value','');

                $hcc_deals_wrap.append( $hcc_deal[0].outerHTML );

                $hcc_deals_wrap.find('.wc-hcc-deal').last().slideDown( 300 );

                // increase counter

                $hcc_deals_wrap.data('count', hcc_deal_count + 1 );

                //

                var timer = setTimeout( hcc_init_product_search, 100 );
            });

            // remove deal

            jQuery('.wc-hcc-deals-wrap').on('click', '.wc-hcc-deal-remove', function( event ){

                event.preventDefault();

                var $this = jQuery( event.target );

                var $hcc_deals_wrap = jQuery('.wc-hcc-deals-wrap');

                if( $hcc_deals_wrap.find('.wc-hcc-deal').length == 1 )
                {
                    //
                }
                else
                {
                    $this.closest('.wc-hcc-deal').slideUp( 300, function(){

                        jQuery(this).remove();
                    });
                }
            });

            /*
                custom template options END
            */

            // sticky "save" button

            jQuery(window).scroll(function(){

                if( jQuery(this).scrollTop() > 300 )
                {
                    jQuery('.wc-hcc-save-bar').slideDown();
                }
                else
                {
                    jQuery('.wc-hcc-save-bar').slideUp();
                }
            });

            jQuery('#wc-hcc-save-btn').on('click', function(){

                jQuery('input#publish').trigger('click');

                jQuery(this).attr( 'disabled', true ).text('<?php esc_html_e( 'Updating...' ); ?>');
            });

            jQuery('.wc-hcc-config-style').on('click', function(){

                var $wrap = jQuery(this).closest('tr').find('.wc-hcc-config-style-wrap');

                if( $wrap.is(':visible') )
                {
                    $wrap.slideUp(300);
                }
                else
                {
                    $wrap.slideDown(300);
                }
            });

            jQuery('.wc-hcc-config-style-wrap').find('input[type="checkbox"]').on('change', function(){

                var $this = jQuery(this);

                if( $this.is(':checked') )
                {
                    $this.parent().addClass('selected');
                }
                else
                {
                    $this.parent().removeClass('selected');
                }

            }).trigger('change');

            // Font Awesome icon selector

            if( jQuery('.gb-wc-hcc-fa-select2').length > 0 )
            {
                jQuery('.gb-wc-hcc-fa-select2').select2({
                    width: "100%",
                    templateSelection: hcc_iformat,
                    templateResult: hcc_iformat,
                    allowHtml: true
                });
            }

            function hcc_iformat(icon)
            {
                var originalOption = icon.element;

                return jQuery('<span><i class="fa ' + jQuery( originalOption ).data('icon') + '"></i> ' + icon.text + '</span>');
            }

            // fill the select product field

            function hcc_init_product_search()
            {
                var $product_search = jQuery('.wc-hcc-product-search').not('.wc-product-search');

                if( $product_search.length > 0 )
                {
                    $product_search.addClass('wc-product-search');

                    $product_search.each(function(){

                        var $this = jQuery(this);

                        jQuery( $this.data('selected') ).each(function( index, product ){

                            $this.append('<option value="' + product['id'] + '" selected="selected">' + product['text'] + '</option>');
                        });
                    });

                    jQuery(document.body).trigger('wc-enhanced-select-init');
                }
            }

            hcc_init_product_search();
        });
    </script>
    <?php
}

/**
 * Display select template metabox
 *
 * @since 1.0.0
 */

function gb_hcc_display_template_metabox( $post )
{
    $template_name = esc_html( get_post_meta( $post->ID, 'hcc_template_name', TRUE ) );

    $page_id = esc_html( get_post_meta( $post->ID, 'hcc_linked_page', TRUE ) );

    // display templates to choose from

    ?>
    <div>
        <p><strong><?php esc_html_e( 'Select template', 'gb-wc-hcc' ); ?>:</strong></p>
        <select name="hcc_template_name">
            <option value=""><?php esc_html_e( 'none', 'gb-wc-hcc' ); ?></option>
            <option value="gb-wc-hcc-template" <?php if( $template_name == 'gb-wc-hcc-template' ){ echo 'selected="selected"'; } ?>>WebCart Original</option>
            <option value="gb-wc-hcc-template-2" <?php if( $template_name == 'gb-wc-hcc-template-2' ){ echo 'selected="selected"'; } ?>>WebCart Blue</option>
            <option value="gb-wc-hcc-template-3" <?php if( $template_name == 'gb-wc-hcc-template-3' ){ echo 'selected="selected"'; } ?>>Marketing Pro</option>
            <option value="gb-wc-hcc-template-4" <?php if( $template_name == 'gb-wc-hcc-template-4' ){ echo 'selected="selected"'; } ?>>Minimalist</option>
            <option value="gb-wc-hcc-template-5" <?php if( $template_name == 'gb-wc-hcc-template-5' ){ echo 'selected="selected"'; } ?>>Two Step</option>
        </select>
    </div>
    <?php

    // display pages to link

    $pages_available = get_posts(array(
        'posts_per_page' => -1,
        'post_type' => 'handsome-checkout',
        'post_status' => 'publish',
        'orderby' => 'ID',
        'order' => 'ASC',
    ));

    $pages_available_html = '';

    foreach( $pages_available as $page )
    {
        if( !empty( $page_id ) && $page_id == $page->ID )
        {
            $pages_available_html .= '<option value="' . $page->ID . '" selected="selected">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
        }
        else
        {
            $pages_available_html .= '<option value="' . $page->ID . '">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
        }
    }

    ?>
    <div>
        <p><strong><?php esc_html_e( 'Copy page settings from', 'gb-wc-hcc' ); ?>:</strong></p>
        <select name="hcc_linked_page">
            <option value=""><?php esc_html_e( 'none', 'gb-wc-hcc' ); ?></option>
            <?php

            echo $pages_available_html;

            ?>
        </select>
    </div>
    <?php
}

/**
 * Process custom metaboxes data
 *
 * @since 1.0.0
 */

function gb_hcc_process_metabox( $post_id, $post )
{
    if( !empty( $post ) )
    {
        // save HCC page settings

        if( $post->post_type == 'handsome-checkout' && $post->post_status != 'shortcode' )
        {
            $fields = array(
                'hcc_product_id',

                'hcc_product_title',
                'hcc_product_title_style',
                'hcc_product_description',
                'hcc_product_description_style',
                'hcc_product_image',

                'hcc_order_bump',
                'hcc_order_bump_product_id',
                'hcc_order_bump_label',
                'hcc_order_bump_highlight',
                'hcc_order_bump_description',

                'hcc_vq_options',
                'hcc_vq_options_main_title',
                'hcc_vq_options_main_title_style',
                'hcc_vq_options_item_title',
                'hcc_vq_options_price_title',
                'hcc_vq_options_mode',
                'hcc_vq_option_highlight_show',
                'hcc_vq_option_highlight',
                'hcc_vq_option_highlight_inscription',
                'hcc_vq_option_selected',
                'hcc_vq_options_sq_table',

                'hcc_template_name',

                'hcc_color_header',
                'hcc_color_footer',
                'hcc_color_bg',
                'hcc_color_headline',
                'hcc_color_button_1',
                'hcc_color_button_2',

                'hcc_fields_billing_email_first',
                'hcc_fields_shipping_first',

                'hcc_fields_billing',
                'hcc_fields_billing_2',
                'hcc_fields_billing_names',
                'hcc_fields_billing_company',
                'hcc_fields_shipping',
                'hcc_fields_shipping_2',
                'hcc_fields_coupon',
                'hcc_fields_phone',
                'hcc_fields_order_comments',
                'hcc_fields_guarantee',

                'hcc_order_details',
                'hcc_order_total_hide',

                'hcc_marketplace_image',
                'hcc_marketplace_image_align',

                'hcc_title_text_step1',
                'hcc_title_text_step1_style',
                'hcc_title_text_step2',
                'hcc_title_text_step2_style',
                'hcc_button_text_step1',
                'hcc_button_text_step1_style',
                'hcc_button_text',
                'hcc_button_text_style',

                'hcc_bullet_points_title',
                'hcc_bullet_points_title_style',
                'hcc_bullet_points',

                'hcc_testimonials_title',
                'hcc_testimonials_title_style',
                'hcc_testimonials',

                'hcc_trustpoints',

                'hcc_custom_css',
                'hcc_custom_js_head',
                'hcc_custom_js',
                'hcc_custom_html_footer',

                'hcc_support_email',

                /* custom template options */

                'hcc_headline',
                'hcc_headline_style',
                'hcc_subheadline',
                'hcc_subheadline_style',
                'hcc_ingredients_image',
                'hcc_deals',
                'hcc_trustseal_top_image',
                'hcc_trustseal_bottom_image',
            );

            foreach( $fields as $f )
            {
                // anti quick edit check

                if( !empty( $_POST['hcc_editor'] ) )
                {
                    if( isset( $_POST[ $f ] ) )
                    {
                        if( $f == 'hcc_product_id' || $f == 'hcc_order_bump_product_id' )
                        {
                            $_POST[ $f ] = implode( ',', $_POST[ $f ] );
                        }

                        update_post_meta( $post_id, $f, $_POST[ $f ] );
                    }
                    else
                    {
                        update_post_meta( $post_id, $f, '' );
                    }
                }
            }
        }

        /**
         * Embedded forms
         *
         * @since 1.4.0
         */

        if( $post->post_type == 'handsome-checkout' && $post->post_status == 'shortcode' )
        {
            $fields = array(
                'hcc_form_product_id',

                'hcc_form_order_bump',
                'hcc_form_order_bump_product_id',
                'hcc_form_order_bump_label',
                'hcc_form_order_bump_highlight',
                'hcc_form_order_bump_description',

                'hcc_vq_options',
                'hcc_vq_options_main_title',
                'hcc_vq_options_item_title',
                'hcc_vq_options_price_title',
                'hcc_vq_options_mode',
                'hcc_vq_option_highlight_show',
                'hcc_vq_option_highlight',
                'hcc_vq_option_highlight_inscription',
                'hcc_vq_option_selected',
                'hcc_vq_options_sq_table',

                'hcc_form_color_background',
                'hcc_form_color_text',
                'hcc_form_color_links',
                'hcc_form_color_payment',
                'hcc_form_color_button_1',
                'hcc_form_color_button_2',

                'hcc_form_fields_billing_email_first',
                'hcc_form_fields_shipping_first',

                'hcc_form_fields_billing',
                'hcc_form_fields_billing_names',
                'hcc_form_fields_billing_company',
                'hcc_form_fields_shipping',
                'hcc_form_fields_coupon',
                'hcc_form_fields_phone',

                'hcc_form_order_details',
                'hcc_form_order_total_hide',

                'hcc_form_title_text_step1',
                'hcc_form_title_text_step2',
                'hcc_form_button_text_step1',
                'hcc_form_button_text',

                'hcc_form_custom_css',
                'hcc_form_custom_js',
                'hcc_form_custom_html_footer',

                'hcc_form_shortcode_steps',
                'hcc_form_shortcode_fieldnames',
                'hcc_form_popup_mode',
            );

            foreach( $fields as $f )
            {
                // anti quick edit check

                if( !empty( $_POST['hcc_form_editor'] ) )
                {
                    if( isset( $_POST[ $f ] ) )
                    {
                        if( $f == 'hcc_form_product_id' || $f == 'hcc_form_order_bump_product_id' )
                        {
                            $_POST[ $f ] = implode( ',', $_POST[ $f ] );
                        }

                        update_post_meta( $post_id, $f, $_POST[ $f ] );
                    }
                    else
                    {
                        update_post_meta( $post_id, $f, '' );
                    }
                }
            }
        }

        // save HCC template name for simple page

        if( $post->post_type == 'page' )
        {
            if( isset( $_POST['hcc_template_name'] ) )
            {
                update_post_meta( $post_id, 'hcc_template_name', sanitize_text_field( $_POST['hcc_template_name'] ) );
            }

            if( isset( $_POST['hcc_linked_page'] ) )
            {
                update_post_meta( $post_id, 'hcc_linked_page', sanitize_text_field( $_POST['hcc_linked_page'] ) );
            }
        }
    }
}
add_action( 'save_post', 'gb_hcc_process_metabox', 10, 2 );

/**
 * Enqueue & deregister scripts on c/o pages
 *
 * @since 1.0.0
 */

function gb_hcc_enqueue_scripts()
{
    global $post, $wp_styles, $wp_scripts;

    if( is_admin() )
    {
        return;
    }

    $revert_post = FALSE;

    // pull appropriate page id for order-review page

    if( is_checkout() && is_wc_endpoint_url( 'order-pay' ) )
    {
        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['replace_page_order_review'] ) )
        {
            $post_id = intval( $options['replace_page_order_review'] );

            $revert_post = $post;

            $post = get_post( $post_id );
        }
    }

    // enqueue scripts

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        /**
         * Remove the theme's Additional CSS block
         *
         * @since 1.1.0
         */

        remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

        remove_action( 'wp_head', 'et_divi_add_customizer_css' );

        // remove prev/next posts links

        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

        // remove coupon field optionally

        $fields_coupon = get_post_meta( $post->ID, 'hcc_fields_coupon', TRUE );

        if( empty( $fields_coupon ) )
        {
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
        }

        // deregister natives:

        // if Handsome template is used for upsell page

        if( empty( $_GET['1cu'] ) )
        {
            wp_deregister_style( 'woocommerce-general' );
        }

        wp_deregister_style( 'woocommerce-layout' );

        wp_deregister_style( 'woocommerce-smallscreen' );

        wp_deregister_style( 'select2' );

        wp_deregister_script( 'select2' );

        wp_deregister_script( 'selectWoo' );

        wp_deregister_script( 'wc-password-strength-meter' );

        // deregister custom

        wp_deregister_style( 'woo-style' );

        // exclude required styles from deregistration queue

        $excluded_styles = apply_filters( 'gb_hcc_preserve_styles', array() );

        // deregister theme styles

        foreach( $wp_styles->registered as $k => $s )
        {
            if(
                mb_strpos( $s->src, 'wp-includes/' ) === FALSE &&

                mb_strpos( $s->src, 'stripe' ) === FALSE &&

                mb_strpos( $s->src, 'paypal' ) === FALSE &&

                $k !== 'woocommerce-general' &&

                !in_array( $k, $excluded_styles )
            )
            {
                wp_deregister_style( $k );
            }
        }

        // exclude required scripts from deregistration queue

        $excluded_scripts = apply_filters( 'gb_hcc_preserve_scripts', array() );

        // deregister scripts

        foreach( $wp_scripts->registered as $k => $s )
        {
            if(
                mb_strpos( $s->src, 'wp-content/themes/' ) !== FALSE &&

                $k !== 'square' && $k !== 'woocommerce-square' &&

                !in_array( $k, $excluded_scripts )
            )
            {
                wp_deregister_script( $k );
            }
        }

        // fallback for deregistered or deenqueued jQuery

        if( !wp_script_is( 'jquery', 'registered' ) )
        {
            wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', FALSE, NULL, FALSE );
            wp_enqueue_script( 'jquery' );
        }

        // fallback for deregistered or deenqueued wc-checkout script

        if( !wp_script_is( 'wc-checkout', 'registered' ) )
        {
            wp_register_script( 'wc-checkout', WC()->plugin_url() . '/assets/js/frontend/checkout.min.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), WC_VERSION, TRUE );
        }

        if( !wp_script_is( 'wc-checkout', 'enqueued' ) )
        {
            wp_enqueue_script( 'wc-checkout' );
        }

        $template_name = get_post_meta( $post->ID, 'hcc_template_name', TRUE );

        if( $template_name == 'gb-wc-hcc-template-6' )
        {
            wp_register_script(
                'wc-hcc-template-6',
                plugins_url( 'templates/assets/js/gb-wc-hcc-template-6.js', __FILE__ ),
                array( 'jquery' ),
                GB_HCC_VER,
                TRUE
            );

            wp_enqueue_script( 'wc-hcc-template-6' );

            $template_localization = array(
                'transactions_subheading' => __( 'All transactions are secure and encrypted', 'gb-wc-hcc' ),
                'transactions_heading' => __( 'Payment method', 'gb-wc-hcc' ),
                'show_order_summary' => __( 'Show order summary', 'gb-wc-hcc' ),
                'hide_order_summary' => __( 'Hide order summary', 'gb-wc-hcc' ),
                'back_to_step2' => __( '< back to the previous step', 'gb-wc-hcc' ),
            );

            wp_localize_script( 'wc-hcc-template-6', 'hcc_localize', $template_localization );
        }
    }

    if( !empty( $revert_post ) )
    {
        $post = $revert_post;

        $revert_post = FALSE;
    }

    /**
     * Embedded forms
     *
     * @since 1.4.0
     */

    if( !empty( $post ) && gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        // disable some scripts

        wp_deregister_style( 'select2' );

        wp_deregister_script( 'select2' );

        wp_deregister_script( 'selectWoo' );

        wp_deregister_script( 'wc-password-strength-meter' );
    }
}
add_action( 'wp_enqueue_scripts', 'gb_hcc_enqueue_scripts', 9999 );

/**
 * Enqueue & deregister scripts in admin
 *
 * @since 1.0.0
 */

function gb_hcc_admin_enqueue_scripts( $hook )
{
    global $post;

    if( is_admin() && ( $hook == 'post.php' || $hook == 'post-new.php' ) )
    {
        if(
            ( !empty( $post ) && $post->post_type == 'handsome-checkout' ) ||
            ( !empty( $_GET['post_type'] ) && $_GET['post_type'] == 'handsome-checkout' )
        )
        {
            wp_enqueue_media();

            wp_enqueue_style( 'woocommerce_admin_styles' );

            wp_enqueue_script( 'select2' );
            wp_enqueue_script( 'wc-enhanced-select' );

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );

            wp_enqueue_style( 'font-awesome', plugins_url( 'templates/assets/css/font-awesome.css', __FILE__ ) );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'gb_hcc_admin_enqueue_scripts', 9999 );

/**
 * Move billing and shipping fields from default
 * actions to "woocommerce_checkout_after_order_review"
 *
 * @since 1.2.0
 */

function gb_hcc_woocommerce_checkout_init( $WC_Checkout )
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $template_name = get_post_meta( $post->ID, 'hcc_template_name', TRUE );

        if(
            !empty( $template_name ) &&
            (
                $template_name == 'gb-wc-hcc-template-5'
            )
        )
        {
            // remove shipping fields from step 1
            remove_action( 'woocommerce_checkout_shipping', array( $WC_Checkout, 'checkout_form_shipping' ) );

            // add duplicate fields to step 2
            add_action( 'woocommerce_checkout_before_order_review', 'gb_hcc_woocommerce_checkout_forms_start', 11 );
            add_action( 'woocommerce_checkout_before_order_review', array( $WC_Checkout, 'checkout_form_billing' ), 12 );
            add_action( 'woocommerce_checkout_before_order_review', array( $WC_Checkout, 'checkout_form_shipping' ), 13 );
            add_action( 'woocommerce_checkout_before_order_review', 'gb_hcc_woocommerce_checkout_forms_end', 14 );

            // switch subheaders
            remove_action( 'woocommerce_before_checkout_billing_form', 'gb_hcc_display_billing_subheader', 1 );
            add_action( 'woocommerce_before_checkout_billing_form', 'gb_hcc_display_order_review_subheader', 1 );

            // filter billing fields for step 1 and step 2
            $field_types = array(
                'country',
                'state',
                'textarea',
                'checkbox',
                'password',
                'text',
                'email',
                'tel',
                'number',
                'select',
                'radio',
            );

            foreach( $field_types as $ft )
            {
                add_filter( 'woocommerce_form_field_' . $ft, 'gb_hcc_form_field_handler', 20, 2 );
            }
        }
    }
}
add_action( 'woocommerce_checkout_init', 'gb_hcc_woocommerce_checkout_init' );

/**
 * Wrap the fields into the special div added to
 * "woocommerce_checkout_before_order_review" hook (start)
 *
 * @since 1.2.0
 */

function gb_hcc_woocommerce_checkout_forms_start()
{
    echo '<div id="step2_details">';
}

/**
 * Wrap the fields into the special div added to
 * "woocommerce_checkout_before_order_review" hook (end)
 *
 * @since 1.2.0
 */

function gb_hcc_woocommerce_checkout_forms_end()
{
    echo '</div>';
}

/**
 * Manages output for first_name, last_name, email fields
 * on "step 1" and other fields on "step 2"
 * (multistep templates only)
 *
 * @since 1.2.0
 */

$hcc_field_count = 0;
$hcc_first_field = '';

function gb_hcc_form_field_handler( $field, $key )
{
    global $hcc_field_count, $hcc_first_field;

    $fields = array(
        'billing_first_name',
        'billing_last_name',
        'billing_email',
    );

    if( $hcc_field_count == 0 )
    {
        $hcc_first_field = $key;
    }

    if( $key == $hcc_first_field )
    {
        ++$hcc_field_count;
    }

    if( $hcc_field_count == 1 && !in_array( $key, $fields ) )
    {
        $field = '';
    }
    elseif( $hcc_field_count == 2 && in_array( $key, $fields ) )
    {
        $field = '';
    }

    return $field;
}

/**
 * Remove any custom inline CSS output added by
 * other themes or plugins
 *
 * @since 1.2.0
 */

function gb_hcc_remove_thirdparty_inline_css()
{
    global $post, $wp_filter;

    if( is_admin() )
    {
        return;
    }

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        foreach( $wp_filter['wp_head'] as $p => $actions ) // priority
        {
            foreach( $actions as $key => $action )
            {
                if(
                    (
                        mb_strpos( $key, 'css' ) !== FALSE ||
                        mb_strpos( $key, 'style' ) !== FALSE
                    ) &&
                    (
                        mb_strpos( $key, 'gb_hcc' ) === FALSE &&
                        mb_strpos( $key, 'gb_ocu' ) === FALSE &&
                        mb_strpos( $key, 'wp_print_styles' ) === FALSE &&
                        mb_strpos( $key, 'locale_stylesheet' ) === FALSE &&
                        mb_strpos( $key, '_custom_logo_header_styles' ) === FALSE

                    )
                )
                {
                    if( is_object( $wp_filter[ 'wp_head' ] ) )
                    {
                        $wp_filter[ 'wp_head' ]->remove_filter( 'wp_head', $key, $p );
                    }
                    else
                    {
                        unset( $wp_filter[ 'wp_head' ][ $p ][ $key ] );
                    }
                }
            }
        }
    }
}
add_action( 'wp_head', 'gb_hcc_remove_thirdparty_inline_css', 1 );

/**
 * Displays some general service CSS,
 * applicable to any template
 *
 * @since 1.3.0
 */

function gb_hcc_display_general_service_css()
{
    global $post;

    if( is_admin() )
    {
        return;
    }

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        echo '
			<style type="text/css">
			.hcc-form-row-hide { display: none !important; }
			</style>';
    }
}
add_action( 'wp_head', 'gb_hcc_display_general_service_css', 2 );

/**
 * is_checkout should return true for HCC pages
 *
 * @since 1.0.0
 */

function gb_hcc_is_checkout_too( $is_checkout )
{
    global $post;

    if( is_admin() )
    {
        return $is_checkout;
    }

    if( empty( $_SERVER['REQUEST_URI'] ) )
    {
        return $is_checkout;
    }

    $uri = esc_url_raw( $_SERVER['REQUEST_URI'] );

    if( is_handsome_checkout_url( $uri ) )
    {
        $is_checkout = TRUE;
    }

    /**
     * Embedded forms
     *
     * @since 1.4.0
     */

    if( !empty( $post ) && gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $is_checkout = TRUE;
    }

    return $is_checkout;
}
add_filter( 'woocommerce_is_checkout', 'gb_hcc_is_checkout_too', 9999 );

/**
 * Define "is_checkout" via JS wc_checkout_params
 *
 * @since 1.3.2
 */

function gb_hcc_filter_wc_checkout_params( $params )
{
    if(
        !empty( $params ) &&
        isset( $params['is_checkout'] ) &&
        get_post_type() == 'handsome-checkout'
    )
    {
        $params['is_checkout'] = 1;
    }

    return $params;
}
add_filter( 'woocommerce_get_script_data', 'gb_hcc_filter_wc_checkout_params', 10, 1 );

/**
 * Hide unsupported gateways
 *
 * @since 1.0.0
 * @todo Add this to changeable options
 */

function gb_hcc_hide_distracting_gateways( $available_gateways )
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $allowed_gateways = apply_filters( 'wc_hcc_allowed_gateways', array() );

        $allowed_gateways = array_merge(
            $allowed_gateways,
            array( 'stripe', 'ocustripe', 'paypal', 'ocupaypal', 'ocuauthnet', 'ocubraintree' )
        );

        foreach( $available_gateways as $key => $value )
        {
            if( !in_array( $key, $allowed_gateways ) )
            {
                unset( $available_gateways[ $key ] );
            }
        }
    }

    return $available_gateways;
}
// add_filter( 'woocommerce_available_payment_gateways', 'gb_hcc_hide_distracting_gateways', 9999 );

/**
 * Disable shipping fields by default
 *
 * @since 1.0.0
 */

function gb_hcc_disable_shipping_fields_optionally( $enabled )
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $enabled = 0;
    }

    return $enabled;
}
add_filter( 'woocommerce_ship_to_different_address_checked', 'gb_hcc_disable_shipping_fields_optionally' );

/**
 * Override default checkout fields
 *
 * @since 1.0.0
 */

function gb_hcc_override_checkout_fields( $fields )
{
    global $post;

    // get correct post object

    if( !empty( $_SERVER['HTTP_REFERER'] ) && !empty( $_GET['wc-ajax'] ) && $_GET['wc-ajax'] == 'checkout' )
    {
        $_SERVER['HTTP_REFERER'] = esc_url_raw( $_SERVER['HTTP_REFERER'] );

        $uri = explode( '?', $_SERVER['HTTP_REFERER'] );
        $uri = $uri[0];

        $slug = basename( untrailingslashit( $uri ) );

        // get post object of HC page

        if( is_handsome_checkout_url( $_SERVER['HTTP_REFERER'] ) )
        {
            $post = gb_hcc_get_post_by_slug( $slug );
        }

        // get post object that might contain shortcode

        else
        {
            $post = gb_hcc_get_post_by_slug( $slug, 'any' );
        }
    }

    // override fields

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        // individual for current page

        $fields_billing_email_first = get_post_meta( $post->ID, 'hcc_fields_billing_email_first', TRUE );
        $fields_billing = get_post_meta( $post->ID, 'hcc_fields_billing', TRUE );
        $fields_billing_2 = get_post_meta( $post->ID, 'hcc_fields_billing_2', TRUE );
        $fields_billing_names = get_post_meta( $post->ID, 'hcc_fields_billing_names', TRUE );
        $fields_billing_company = get_post_meta( $post->ID, 'hcc_fields_billing_company', TRUE );
        
        $fields_shipping = get_post_meta( $post->ID, 'hcc_fields_shipping', TRUE );
        $fields_shipping_2 = get_post_meta( $post->ID, 'hcc_fields_shipping_2', TRUE );
        
        $fields_phone = get_post_meta( $post->ID, 'hcc_fields_phone', TRUE );
        $fields_order_comments = get_post_meta( $post->ID, 'hcc_fields_order_comments', TRUE );

        // add placeholders

        foreach( $fields['billing'] as $k => $f )
        {
            if( empty( $fields['billing'][ $k ]['placeholder'] ) )
            {
                $fields['billing'][ $k ]['placeholder'] = $fields['billing'][ $k ]['label'];
            }
        }

        foreach( $fields['shipping'] as $k => $f )
        {
            if( empty( $fields['shipping'][ $k ]['placeholder'] ) )
            {
                $fields['shipping'][ $k ]['placeholder'] = $fields['shipping'][ $k ]['label'];
            }
        }

        // disable fields

        if( empty( $fields_billing ) )
        {
            foreach( $fields['billing'] as $k => $f )
            {
                if(
                    $k === 'billing_email' ||
                    $k === 'billing_phone' ||
                    $k === 'billing_company' ||
                    ( !empty( $fields_billing_names ) && ( $k === 'billing_first_name' || $k === 'billing_last_name' ) ) || 
                    $k === 'billing_address_2'
                )
                {
                    // skip the field
                }
                else
                {
                    unset( $fields['billing'][ $k ] );
                }
            }
        }

        if( empty( $fields_billing_company ) )
        {
            unset( $fields['billing']['billing_company'] );
        }

        if( empty( $fields_phone ) )
        {
            $fields['billing']['billing_phone']['required'] = FALSE;
            unset( $fields['billing']['billing_phone'] );
        }

        if( empty( $fields_billing_2 ) )
        {
            unset( $fields['billing']['billing_address_2'] );
        }

        if( empty( $fields_shipping ) )
        {
            foreach( $fields['shipping'] as $k => $f )
            {
                if(
                    $k === 'shipping_address_2'
                )
                {
                    // skip the field
                }
                else
                {
                    unset( $fields['shipping'][ $k ] );
                }
            }
        }

        if( empty( $fields_shipping_2 ) )
        {
            unset( $fields['shipping']['billing_address_2'] );
        }

        // if( empty( $fields_order_comments ) )
        // {
            unset( $fields['order']['order_comments'] );
        // }

        // put email first in enabled

        if( !empty( $fields_billing_email_first ) )
        {
            $reorder = array();

            if( !empty( $fields['billing']['billing_email'] ) )
            {
                $fields['billing']['billing_email']['priority'] = 1;
                $fields['billing']['billing_email']['autofocus'] = TRUE;

                if( isset( $fields['billing']['billing_first_name']['autofocus'] ) )
                {
                    unset( $fields['billing']['billing_first_name']['autofocus'] );
                }

                $reorder['billing_email'] = $fields['billing']['billing_email'];

                foreach( $fields['billing'] as $k => $f )
                {
                    if( $k != 'billing_email' )
                    {
                        $reorder[ $k ] = $f;
                    }
                }

                $fields['billing'] = $reorder;

                unset( $reorder );
            }
        }
    }

    /**
     * Embedded forms
     *
     * @since 1.4.0
     */

    if( !empty( $post ) && $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

        if( !empty( $post_id ) )
        {
            $form_fields_billing_email_first = get_post_meta( $post_id, 'hcc_form_fields_billing_email_first', TRUE );
            $form_fields_billing             = get_post_meta( $post_id, 'hcc_form_fields_billing', TRUE );
            $form_fields_billing_names       = get_post_meta( $post_id, 'hcc_form_fields_billing_names', TRUE );
            $form_fields_billing_company     = get_post_meta( $post_id, 'hcc_form_fields_billing_company', TRUE );
            $form_fields_shipping            = get_post_meta( $post_id, 'hcc_form_fields_shipping', TRUE );
            $form_fields_shipping_first      = get_post_meta( $post_id, 'hcc_form_fields_shipping_first', TRUE );
            $form_fields_phone               = get_post_meta( $post_id, 'hcc_form_fields_phone', TRUE );

            // general

            unset( $fields['billing']['billing_address_2'] );

            unset( $fields['shipping']['shipping_address_2'] );

            unset( $fields['order']['order_comments'] );

            // disable fields

            if( empty( $form_fields_billing ) )
            {
                foreach( $fields['billing'] as $k => $f )
                {
                    if(
                        $k === 'billing_email' ||
                        $k === 'billing_phone' ||
                        $k === 'billing_company' ||
                        ( !empty( $form_fields_billing_names ) && ( $k === 'billing_first_name' || $k === 'billing_last_name' ) )
                    )
                    {
                        // skip the field
                    }
                    else
                    {
                        unset( $fields['billing'][ $k ] );
                    }
                }

            }

            if( empty( $form_fields_billing_company ) )
            {
                unset( $fields['billing']['billing_company'] );
            }

            if( empty( $form_fields_phone ) )
            {
                $fields['billing']['billing_phone']['required'] = FALSE;

                unset( $fields['billing']['billing_phone'] );
            }

            if( empty( $form_fields_shipping ) )
            {
                foreach( $fields['shipping'] as $k => $f )
                {
                    unset( $fields['shipping'][ $k ] );
                }
            }
            else
            {
                // force shipping fields display

                if( !empty( $form_fields_shipping_first ) )
                {
                    add_filter( 'woocommerce_cart_needs_shipping', '__return_true' );
                }
            }

            // prevent autofocus / scrolling the page to the embed form

            if( isset( $fields['billing']['billing_first_name']['autofocus'] ) )
            {
                unset( $fields['billing']['billing_first_name']['autofocus'] );
            }

            // put email first in enabled

            if( !empty( $form_fields_billing_email_first ) )
            {
                $reorder = array();

                if( !empty( $fields['billing']['billing_email'] ) )
                {
                    $fields['billing']['billing_email']['priority']  = 1;
                    $fields['billing']['billing_email']['autofocus'] = FALSE;

                    $reorder['billing_email'] = $fields['billing']['billing_email'];

                    foreach( $fields['billing'] as $k => $f )
                    {
                        if( $k != 'billing_email' )
                        {
                            $reorder[ $k ] = $f;
                        }
                    }

                    $fields['billing'] = $reorder;

                    unset( $reorder );
                }
            }
        }
    }

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'gb_hcc_override_checkout_fields', 99 );

/**
 * Display subheader for billing fields
 *
 * @since 1.0.0
 */

function gb_hcc_display_billing_subheader()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        echo '<p class="woocommerce-checkout-subtitle">' . esc_html__( 'Fields marked with * are mandatory', 'gb-wc-hcc' ) . '</p>';
    }
}
add_action( 'woocommerce_before_checkout_billing_form', 'gb_hcc_display_billing_subheader', 1 );

/**
 * Display subheader for order review section
 *
 * @since 1.0.0
 */

function gb_hcc_display_order_review_subheader()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        echo '<p class="woocommerce-checkout-subtitle">' . __( 'All fields are mandatory', 'gb-wc-hcc' ) . '</p>';
    }
}
add_action( 'woocommerce_checkout_before_order_review', 'gb_hcc_display_order_review_subheader', 1 );

/**
 * Display html after order review section
 *
 * @since 1.0.0
 */

function gb_hcc_display_after_order_review()
{
    global $post;

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        echo '<div class="clear"></div>';
    }
}
add_action( 'woocommerce_checkout_after_order_review', 'gb_hcc_display_after_order_review' );
add_action( 'woocommerce_after_checkout_billing_form', 'gb_hcc_display_after_order_review' );
add_action( 'woocommerce_after_checkout_shipping_form', 'gb_hcc_display_after_order_review' );

/**
 * Display order total box and Order Bump
 *
 * @since 1.0.0
 */

function gb_hcc_before_place_order()
{
    global $post;

    $result = $order_bump = $order_bump_product_id = '';

    if( !empty( $post ) )
    {
        // get options

        if( $post->post_type == 'handsome-checkout' )
        {
            // order bump

            $order_bump = get_post_meta( $post->ID, 'hcc_order_bump', TRUE );

            $order_bump_product_id = get_post_meta( $post->ID, 'hcc_order_bump_product_id', TRUE );

            $order_bump_label = get_post_meta( $post->ID, 'hcc_order_bump_label', TRUE );

            $order_bump_highlight = get_post_meta( $post->ID, 'hcc_order_bump_highlight', TRUE );

            $order_bump_description = get_post_meta( $post->ID, 'hcc_order_bump_description', TRUE );

            // order total

            $order_total_hide = get_post_meta( $post->ID, 'hcc_order_total_hide', TRUE );
        }

        /**
         * Embedded forms
         *
         * @since 1.4.0
         */

        elseif( $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
        {
            $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

            if( !empty( $post_id ) )
            {
                // order bump

                $order_bump = get_post_meta( $post_id, 'hcc_form_order_bump', TRUE );

                $order_bump_product_id = get_post_meta( $post_id, 'hcc_form_order_bump_product_id', TRUE );

                $order_bump_label = get_post_meta( $post_id, 'hcc_form_order_bump_label', TRUE );

                $order_bump_highlight = get_post_meta( $post_id, 'hcc_form_order_bump_highlight', TRUE );

                $order_bump_description = get_post_meta( $post_id, 'hcc_form_order_bump_description', TRUE );

                // order total

                $order_total_hide = get_post_meta( $post_id, 'hcc_form_order_total_hide', TRUE );
            }
        }
        // check if Order Bump should remain checked or not
        // in the update_order_review request

        $order_bump_checked = FALSE;

        if( !empty( $_POST['post_data'] ) )
        {
            $post_data = array();

            parse_str( $_POST['post_data'], $post_data );

            if( !empty( $post_data['wc-hcc-order-bump'] ) )
            {
                $order_bump_checked = TRUE;
            }

            $post_data = NULL;
        }

        // display Order Bump

        if( !empty( $order_bump ) && !empty( $order_bump_product_id ) )
        {
            $result .= PHP_EOL .
                '<style type="text/css">
				.wc-hcc-order-bump {
					display: block;
					clear: both;
					padding: 10px;
					border: 2px dashed #000;
					background: #FDF9E0;
					margin: 15px 0;
				}
				.wc-hcc-order-bump-checkbox {
					text-align: center;
					background: #FFFF95;
					margin-bottom: 10px;
				}
				.wc-hcc-order-bump-checkbox label {
					display: inline !important;
					float: none;
					width: 100% !important;
					text-align: center;
					cursor: pointer;
				}
				.wc-hcc-order-bump-label {
					font-size: 18px;
					color: #27b318;
					font-weight: bold;
				}
				.wc-hcc-order-bump-checkbox input[type="checkbox"] {
					width: auto;
					float: none;
					display: inline;
					border: 0;
					padding: 5px;
					outline: none;
					box-shadow: none;
				}
				.wc-hcc-order-bump-description {
					color: #000;
					font-size: 15px;
					line-height: 20px;
				}
				.wc-hcc-order-bump-highlight {
					color: #e67725;
					font-weight: bold;
					text-decoration: underline;
				}
				</style>
				<div class="wc-hcc-order-bump" title="Click to add this to your order!">
					<div class="wc-hcc-order-bump-checkbox">
						<label>
							<input type="checkbox" name="wc-hcc-order-bump" value="' . $order_bump_product_id . '" id="wc-hcc-order-bump-cb" ' . checked( $order_bump_checked, TRUE, FALSE ) . ' />
							<input type="hidden" name="wc-hcc-order-bump-request" value="1" />
							<span class="wc-hcc-order-bump-label">' . apply_filters( 'wc_hcc_order_bump_label', $order_bump_label ) . '</span>
						</label>
					</div>
					<div class="wc-hcc-order-bump-description">
						<span class="wc-hcc-order-bump-highlight">' . apply_filters( 'wc_hcc_order_bump_highlight', $order_bump_highlight ) . '</span>&nbsp;' .
                        
                        apply_filters( 'wc_hcc_order_bump_description', $order_bump_description ) .

                '</div>
				</div>
				<script type="text/javascript">
				jQuery(document).ready(function(){

					var hcc_order_bump_checked;

	                var hcc_order_bump_clicked = 0;

					jQuery(document).on( "change", "#wc-hcc-order-bump-cb", function()
					{
						if( hcc_order_bump_clicked == 1 )
						{
						    return false;
						}

						jQuery(".wc-hcc-order-bump").block({
							message: null,
							overlayCSS: {
								background: "#fff",
								opacity: 0.6
							}
						});

						hcc_order_bump_clicked = 1;

						var $this = jQuery(this);
						var product_id = $this.val();

						if( $this.is(":checked") )
						{
							hcc_order_bump_checked = 1;
						}
						else
						{
							hcc_order_bump_checked = 0;
						}

						var data = {
							action: "hcc_order_bump",
							product_id: product_id,
							checked: hcc_order_bump_checked,
							hcc_order_bump: 1
						};

						jQuery.post( woocommerce_params.ajax_url, data, function( response )
						{
							if( typeof response === "object" && response.fragments )
							{
								jQuery.each( response.fragments, function( key, fragment ){

									if( jQuery( key ).length > 0 )
									{
										jQuery( key ).replaceWith( fragment );
									}
								});

								jQuery( ".woocommerce-checkout-total-price" ).html( response["cart_total"] );

								jQuery( "#wc-hcc-order-bump-cb" ).prop( "checked", response["checked"] );

								jQuery(".wc-hcc-order-bump").unblock();

		                    	hcc_order_bump_clicked = 0;
		                    }
						});

						return false;
					});
				});
				</script>' .
                PHP_EOL;
        }

        // order total

        if( isset( $order_total_hide ) && empty( $order_total_hide ) )
        {
            // display "$0.00 today (...)" only if
            // cart contains only 1 subscription with trial & no sign-up fee

            if(
                class_exists( 'WC_Subscriptions_Cart' ) &&
                WC_Subscriptions_Cart::cart_contains_subscription() &&
                WC()->cart->get_cart_contents_count() == 1
            )
            {
                $price_reccuring = WC()->cart->get_total();

                foreach( WC()->cart->cart_contents as $item_key => $item )
                {
                    $product = wc_get_product( $item['product_id'] );

                    // custom price text if trial with 0 sign-up fee

                    $sign_up_fee = WC_Subscriptions_Product::get_sign_up_fee( $product );
                    $has_trial = ( WC_Subscriptions_Product::get_trial_length( $product ) > 0 ) ? TRUE : FALSE;

                    // just sign up fee (0 or more)

                    if( 
                        ( $has_trial && !empty( $sign_up_fee ) ) ||
                        ( $has_trial && empty( $sign_up_fee ) )
                    )
                    {
                        $price_reccuring = $product->get_price_html();

                        $price_reccuring = sprintf(
                            __( '%s today (%s)', 'gb-wc-hcc' ),
                            strip_tags( wc_price( floatval( $sign_up_fee ) ) ),
                            $price_reccuring
                        );
                    }

                    // price + sign up fee

                    elseif( !$has_trial && !empty( $sign_up_fee ) )
                    {
                        $sign_up_fee = WC()->cart->total;

                        $price_reccuring = $product->get_price_html();

                        $price_reccuring = sprintf(
                            __( '%s today (%s)', 'gb-wc-hcc' ),
                            strip_tags( wc_price( floatval( $sign_up_fee ) ) ),
                            $price_reccuring
                        );
                    }

                    // just price

                    elseif( !$has_trial && empty( $sign_up_fee ) )
                    {
                        ob_start();

                        wcs_cart_totals_order_total_html( WC()->cart );

                        $price_reccuring = ob_get_clean();

                        // remove first renewal date

                        $price_reccuring = mb_substr( $price_reccuring, 0, mb_strpos( $price_reccuring, '<div class="first-payment-date">' ) );
                        $price_reccuring = strip_tags( $price_reccuring );
                    }
                }

                // recurring total

                $result .= PHP_EOL .
                    '<div class="woocommerce-checkout-price">
						<div class="woocommerce-checkout-total-label">' . __( 'Total', 'gb-wc-hcc' ) . ':</div>
						<div class="woocommerce-checkout-total-price" style="float: none; display: inline;">' . $price_reccuring . '</div>
						<div class="clear"></div>
					</div>';
            }

            // in other cases display cart grand total

            else
            {
                // main total

                $result .= PHP_EOL .
                    '<div class="woocommerce-checkout-price">
						<div class="woocommerce-checkout-total-label">' . __( 'Total', 'gb-wc-hcc' ) . ':</div>
						<div class="woocommerce-checkout-total-price">' . WC()->cart->get_total() . '</div>
						<div class="clear"></div>
					</div>';
            }
        }
    }

    echo $result;
}
add_action( 'woocommerce_review_order_before_submit', 'gb_hcc_before_place_order' );

/**
 * Process the AJAX Order Bump request
 *
 * @since 1.1.0
 */

function gb_hcc_order_bump_process()
{
    if( !isset( $_POST['product_id'] ) )
    {
        $_POST['product_id'] = 0;
    }

    if( !isset( $_POST['checked'] ) )
    {
        $_POST['checked'] = 0;
    }

    $product_id = intval( $_POST['product_id'] );
    $checked = intval( $_POST['checked'] );

    $found_item_key = $found_item = NULL;

    foreach( WC()->cart->get_cart() as $key => $item )
    {
        if( $item['product_id'] === $product_id )
        {
            $found_item_key = $key;
            $found_item = $item;

            break;
        }
    }

    // add - if not found, remove/reduce - if found

    if( $checked === 1 && $found_item_key === NULL )
    {
        WC()->cart->add_to_cart( $product_id, 1 );

        do_action( 'gb_hcc_order_bump_item_added', $product_id );
    }
    elseif( $checked === 0 && $found_item_key != NULL )
    {
        $new_qty = $found_item['quantity'] - 1;

        WC()->cart->remove_cart_item( $found_item_key );

        do_action( 'gb_hcc_order_bump_item_removed', $product_id );

        if( $new_qty > 0 )
        {
            WC()->cart->add_to_cart( $product_id, $new_qty );
        }
    }

    WC()->cart->calculate_totals();

    // get refreshed review order element fragment and send it back

    ob_start();
    woocommerce_order_review();
    $woocommerce_order_review = ob_get_clean();

    // get custom fragments

    $fragments = apply_filters( 'woocommerce_update_order_review_fragments', array() );

    // get updated order review table

    $fragments['.woocommerce-checkout-review-order-table'] = $woocommerce_order_review;

    // send json

    wp_send_json( array(
        'result'     => 'success',
        'checked'    => $checked,
        'cart_total' => wc_price( WC()->cart->total ),
        'fragments'  => $fragments,
    ));
}
add_action( 'wp_ajax_hcc_order_bump', 'gb_hcc_order_bump_process' );
add_action( 'wp_ajax_nopriv_hcc_order_bump', 'gb_hcc_order_bump_process' );

/**
 * Fix the AJAX endpoint urls on Handsome Checkout pages
 *
 * @since 1.2.0
 */

function gb_hcc_ajax_get_endpoint( $endpoint_url )
{
    global $post;

    if( empty( $_SERVER['REQUEST_URI'] ) )
    {
        return $endpoint_url;
    }

    if( !empty( $post ) && !empty( $_SERVER['REQUEST_URI'] ) )
    {
        if(
            // page

            $post->post_type == 'handsome-checkout' ||

            // Embedded form

            gb_hcc_form_get_embed_shortcode( $post->post_content )
        )
        {
            if( mb_strpos( $endpoint_url, 'checkout' ) === FALSE )
            {
                $query_args = array(
                    'wc-ajax' => '%%endpoint%%',
                );

                $uri = explode( '?', $_SERVER['REQUEST_URI'], 2 );
                $uri = $uri[0];

                $endpoint_url = esc_url( add_query_arg( $query_args, $uri ) );
            }
        }

        // Embedded form: iframe mode

        if( !empty( $_GET['hcc_form_loader'] ) && !empty( $_GET['hcc_form_id'] ) )
        {
            if( mb_strpos( $endpoint_url, 'checkout' ) === FALSE )
            {
                $query_args = array(
                    'wc-ajax' => '%%endpoint%%',
                    'hcc_form_loader' => '1',
                    'hcc_form_id' => intval( $_GET['hcc_form_id'] ),
                );

                $uri = explode( '?', $_SERVER['REQUEST_URI'], 2 );
                $uri = $uri[0];

                $endpoint_url = esc_url_raw( add_query_arg( $query_args, $uri ) );
            }
        }
    }

    return $endpoint_url;
}
add_filter( 'woocommerce_ajax_get_endpoint', 'gb_hcc_ajax_get_endpoint' );

/**
 * Display the Variations & Quantity form
 *
 * @since 1.4.0
 */

function gb_hcc_after_checkout_billing_form_vq_options()
{
    global $post;

    $result = '';

    // Variations & Quantity Options

    if( !empty( $post ) )
    {
        if( $post->post_type == 'handsome-checkout' )
        {
            $post_id = $post->ID;
        }

        elseif( $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
        {
            $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );
        }

        if( empty( $post_id ) )
        {
        	return;
        }

        $vq_options = get_post_meta( $post_id, 'hcc_vq_options', TRUE );

        if( !empty( $vq_options ) )
        {
            $vq_options_main_title = get_post_meta( $post_id, 'hcc_vq_options_main_title', TRUE );
            $vq_options_main_title_style = get_post_meta( $post_id, 'hcc_vq_options_main_title_style', TRUE );
            $vq_options_item_title = get_post_meta( $post_id, 'hcc_vq_options_item_title', TRUE );
            $vq_options_price_title = get_post_meta( $post_id, 'hcc_vq_options_price_title', TRUE );
            $vq_options_mode = get_post_meta( $post_id, 'hcc_vq_options_mode', TRUE );
            $vq_option_highlight_show = get_post_meta( $post_id, 'hcc_vq_option_highlight_show', TRUE );
            $vq_option_highlight = get_post_meta( $post_id, 'hcc_vq_option_highlight', TRUE );
            $vq_option_highlight_inscription = get_post_meta( $post_id, 'hcc_vq_option_highlight_inscription', TRUE );
            $vq_option_selected = get_post_meta( $post_id, 'hcc_vq_option_selected', TRUE );

            $products = array();

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
            {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key))
                {
                    $products[] = $_product;
                }
            }

            $result .= PHP_EOL .
                '<style type="text/css">
                .vq-options-main-title {
				    line-height: 3em;
				}
				table.wc-hcc-vq-options-table {
				    width: 100%;
				    border-spacing: 0;
				}
				table.wc-hcc-vq-options-table tr td {
				    padding: 8px 10px;
				    font-weight: inherit;
				}
				.wc-hcc-vq-options-item-wrap {
				    display: inline-block;
				    padding-left: 10px;
				}
				.wc-hcc-vq-options-item {
					font-weight: normal;
				}
				.wc-hcc-vq-options-header {
					border-bottom: 1px solid #f1f1f1;
					border-top: 1px solid #f1f1f1;
				}
				.wc-hcc-vq-options-item label {
					width: 100%;
				}
				.wc-hcc-vq-options-price {
					text-align: right;
				}
				input.wc-hcc-vq-option-selection {
					float: none;
				}
				.wc-hcc-vq-option-highlight {
					background-color: #F6E100 !important;
					font-weight: bold;
					border: 1px solid;
					box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
				}
				.wc-hcc-vq-option-highlight-inscription {
				    color: red;
					font-size: 15px;
					line-height: 20px;
				}
                .wc-hcc-vq-option-highlight td::before {
                    height: inherit;
                    background-color: transparent !important;
                    border: 0 !important;
                }
                .wc-hcc-vq-option-highlight td {
                    height: 54px !important;
                }

                input.wc-hcc-vq-option-selection + div.wc-hcc-vq-options-item-wrap {
                    width: 90% !important;
                }
				</style>' .
                PHP_EOL;

            $vq_options_main_title = ( empty( $vq_options_main_title ) ? __( 'Product Selection', 'gb-wc-hcc' ) : $vq_options_main_title );

            $vq_options_main_title = gb_hcc_apply_style( $vq_options_main_title, $post_id, 'hcc_vq_options_main_title' );

            $result .= PHP_EOL .
                '<div class="wc-hcc-vq-options">
                    <h3 class="vq-options-main-title">' . $vq_options_main_title . '</h3>' .
                PHP_EOL;

            $product_id = $products[0]->get_id();

            $product_name = $products[0]->get_name();

            switch ($vq_options_mode)
            {
                case 'simple_quantity':

                    $vq_options_table = get_post_meta( $post_id, 'hcc_vq_options_sq_table', TRUE );

                    $result .= PHP_EOL .
                        '<table id="wc-hcc-vq-options-table" class="wc-hcc-vq-options-table simple_quantity_table">
                            <tr>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-item">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_item_title) ? __('Item', 'gb-wc-hcc') : $vq_options_item_title) . '</h4></div>
                                </td>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-price">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_price_title) ? __('Price', 'gb-wc-hcc') : $vq_options_price_title) . '</h4></div>
                                </td>
                            </tr>' .
                        PHP_EOL;

                    $counter = 0;

                    if( !empty( $vq_options_table ) )
                    {
                        foreach( $vq_options_table as $k => $vq_o_t )
                        {
                            $result .= PHP_EOL .
                                '<tr ' . (($vq_option_highlight == $counter && $vq_option_highlight_show) ? 'class="wc-hcc-vq-option-highlight"' : 'class="wc-hcc-vq-option"') . '>
                                    <td class="wc-hcc-vq-options-item">
                                        <label for="' . md5($k . $product_name) . '" style="width: 100%; margin: 0; font-weight: inherit;">
                                            <input id="' . md5($k . $product_name) . '" type="radio" name="vq_option_selection"
                                            ' . (($vq_o_t['qty'] == 1) ? 'checked' : '') . '
                                                value="' . esc_textarea(json_encode(array('product_id' => $product_id, 'products_qty' => $vq_o_t['qty'], 'products_price' => $vq_o_t['price'], 'mode' => $vq_options_mode))) . '"
                                                class="wc-hcc-vq-option-selection wc-hcc-vq-option-selection-' . intval($k) . '"/>
                                            <div class="wc-hcc-vq-options-item-wrap">' .
                                ((($vq_option_highlight == $counter && $vq_option_highlight_show) ? '<div class="wc-hcc-vq-option-highlight-inscription">' . (empty($vq_option_highlight_inscription) ? __('MOST POPULAR!', 'gb-wc-hcc') : $vq_option_highlight_inscription) . '</div>' : '')) .
                                '<div class="wc-hcc-vq-options-product-name">' . (empty($vq_o_t['name']) ? ($vq_o_t['qty'] . ' ' . $product_name) : $vq_o_t['name']) . '</div>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="wc-hcc-vq-options-price">
                                        <div class="wc-hcc-field-label">' . (empty($vq_o_t['price']) ? wc_price($vq_o_t['qty'] * $products[0]->get_price()) : wc_price($vq_o_t['price'])) . '</div>
                                    </td>' .
                                PHP_EOL;

                            $counter++;
                        }
                    }

                    $result .= PHP_EOL .
                        '</table>' .
                        PHP_EOL;

                    break;

                case 'simple_variations':

                    $result .= PHP_EOL .
                        '<table id="wc-hcc-vq-options-table" class="wc-hcc-vq-options-table simple_variations_table">
                            <tr>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-item">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_item_title) ? __('Item', 'gb-wc-hcc') : $vq_options_item_title) . '</h4></div>
                                </td>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-price">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_price_title) ? __('Price', 'gb-wc-hcc') : $vq_options_price_title) . '</h4></div>
                                </td>
                            </tr>' .
                        PHP_EOL;

                    $_product = $products[0];

                    if ($_product->is_type('variation'))
                    {
                        $current_variation_id = $_product->get_id();
                        $_product = wc_get_product($_product->get_parent_id());

                        foreach ($_product->get_children() as $value) {
                            $single_variation = new WC_Product_Variation($value);

                            if ($single_variation->is_in_stock())
                            {
                                $result .= PHP_EOL .
                                    '<tr>
                                    <td class="wc-hcc-vq-options-item">
                                        <label for="' . md5($value) . '" style="width: 100%; margin: 0; font-weight: inherit;">
                                        <input id="' . md5($value) . '" type="radio" name="vq_option_selection"
                                            value="' . esc_textarea(json_encode(array('product_id' => $_product->get_id(), 'variation_id' => $value, 'mode' => $vq_options_mode))) . '"
                                            ' . (($current_variation_id == $value) ? 'checked' : '') . '
                                            class="wc-hcc-vq-option-selection"/>
                                            <div class="wc-hcc-vq-options-item-wrap">' . $single_variation->get_name() . '</div>
                                        </label>
                                    </td>
                                    <td class="wc-hcc-vq-options-price">
                                        <div class="wc-hcc-field-label">' . wc_price($single_variation->get_price()) . '</div>
                                    </td>
                                </tr>' .
                                    PHP_EOL;
                            }
                        }
                    }

                    $result .= PHP_EOL .
                        '</table>' .
                        PHP_EOL;

                    break;

                case 'dropdown_quantity_and_variations':

                    $result .= PHP_EOL .
                        '<table id="wc-hcc-vq-options-table" class="wc-hcc-vq-options-table">
                            <tr>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-qty">
                                    <div class="wc-hcc-field-label"><h4>' . __('Qty', 'gb-wc-hcc') . '</h4></div>
                                </td>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-item">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_item_title) ? __('Item', 'gb-wc-hcc') : $vq_options_item_title) . '</h4></div>
                                </td>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-price">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_price_title) ? __('Price', 'gb-wc-hcc') : $vq_options_price_title) . '</h4></div>
                                </td>
                            </tr>' .
                        PHP_EOL;

                    foreach ($products as $product)
                    {
                        if ($product->is_type('variation'))
                        {
                            $current_variation_id = $product->get_id();
                            $_product = wc_get_product($product->get_parent_id());

                            foreach ($_product->get_children() as $value)
                            {
                                $single_variation = new WC_Product_Variation($value);

                                if ($single_variation->is_in_stock())
                                {
                                    $result .= PHP_EOL .
                                        '<tr>
                                    <td class="wc-hcc-vq-options-qty">
                                        <select name="vq_option_selection" class="wc-hcc-vq-option-selection">' . PHP_EOL;
                                    for ($i = 0; $i <= 99; $i++)
                                    {
                                        $result .= PHP_EOL . '
                                        <option value="' . esc_textarea(json_encode(array('product_id' => $_product->get_id(), 'products_qty' => $i, 'variation_id' => $value, 'mode' => $vq_options_mode))) . '"' .
                                            (($current_variation_id == $value && $i == 1) ? ' selected="selected"' : '') . '>' . $i . '</option>' . PHP_EOL;
                                    }

                                    $result .= PHP_EOL . '</select>
                                    </td>
                                    <td class="wc-hcc-vq-options-item">
                                        <div class="wc-hcc-vq-options-item-wrap">' . $single_variation->get_name() . '</div>
                                    </td>
                                    <td class="wc-hcc-vq-options-price">
                                        <div class="wc-hcc-field-label">' . wc_price($single_variation->get_price()) . '</div>
                                    </td>
                                </tr>' .
                                        PHP_EOL;
                                }
                            }
                        }
                        else
                        {
                            $result .= PHP_EOL .
                                '<tr>
                                    <td class="wc-hcc-vq-options-qty">
                                        <select name="vq_option_selection" class="wc-hcc-vq-option-selection">' . PHP_EOL;
                            for ($i = 0; $i <= 99; $i++)
                            {
                                $result .= PHP_EOL . '
                                    <option value="' . esc_textarea(json_encode(array('product_id' => $product->get_id(), 'products_qty' => $i, 'mode' => $vq_options_mode))) . '"' .
                                    (($i == 1) ? ' selected="selected"' : '') . '>' . $i . '</option>' . PHP_EOL;
                            }

                            $result .= PHP_EOL . '</select>

                                    </td>
                                    <td class="wc-hcc-vq-options-item">
                                        <div class="wc-hcc-vq-options-item-wrap">' . $product->get_name() . '</div>
                                    </td>
                                    <td class="wc-hcc-vq-options-price">
                                        <div class="wc-hcc-field-label">' . wc_price($product->get_price()) . '</div>
                                    </td>
                                </tr>' .
                                PHP_EOL;
                        }
                    }

                    $result .= PHP_EOL .
                        '</table>' .
                        PHP_EOL;

                    break;

                case 'simple_products':

                    $products = get_post_meta( $post->ID, 'hcc_product_id', TRUE );
                    $products = explode( ',', $products );

                    $result .= PHP_EOL .
                        '<table id="wc-hcc-vq-options-table" class="wc-hcc-vq-options-table simple_quantity_table">
                            <tr>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-item">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_item_title) ? __('Item', 'gb-wc-hcc') : $vq_options_item_title) . '</h4></div>
                                </td>
                                <td class="wc-hcc-vq-options-header wc-hcc-vq-options-price">
                                    <div class="wc-hcc-field-label"><h4>' . (empty($vq_options_price_title) ? __('Price', 'gb-wc-hcc') : $vq_options_price_title) . '</h4></div>
                                </td>
                            </tr>' .
                        PHP_EOL;

                    $counter = 0;

                    foreach( $products as $id )
                    {
                        $product = wc_get_product( $id );

                        $result .= PHP_EOL .
                            '<tr>
                            <td class="wc-hcc-vq-options-item">
                                <label for="' . md5( $product->get_id() ) . '" style="width: 100%; margin: 0; font-weight: inherit;">
                                <input id="' . md5( $product->get_id() ) . '" type="radio" name="vq_option_selection"
                                    value="' . esc_textarea( json_encode(array('product_id' => $product->get_id(), 'mode' => $vq_options_mode))) . '"
                                    ' . (( $id == reset( $products ) ) ? 'checked' : '') . '
                                    class="wc-hcc-vq-option-selection"/>
                                    <div class="wc-hcc-vq-options-item-wrap">' . $product->get_name() . '</div>
                                </label>
                            </td>
                            <td class="wc-hcc-vq-options-price">
                                <div class="wc-hcc-field-label">' . wc_price( $product->get_price() ) . '</div>
                            </td>
                        </tr>' .
                            PHP_EOL;
                    }

                    $result .= PHP_EOL .
                        '</table>' .
                        PHP_EOL;

                    break;
            }

            $result .= PHP_EOL .
                '</div>
                <script type="text/javascript">
                    jQuery( document ).ready( function() {
                        var option_id = "";

                        jQuery( document ).on( "change", ".wc-hcc-vq-option-selection", function() {
                            var $this     = jQuery( this );

                            var option    = jQuery.parseJSON( $this.val() );

                            var data      = {
                                action:         "hcc_vq_options",
                                option:         option,
                                hcc_vq_options: 1
                            };

                            jQuery.post( woocommerce_params.ajax_url, data, function( response ) {
                                jQuery( "body" ).trigger( "update_checkout" );
                            });

                            return false;
                        });

                        jQuery( document.body ).bind( "updated_checkout", function() {
                            jQuery( option_id ).prop( "checked", 1 );
                        });

                        ' . (isset($vq_option_selected) ? 'jQuery( document ).find(".wc-hcc-vq-option-selection-' . intval($vq_option_selected) . '").attr( "checked", true ).trigger( "change" );' : '') . '

                    });
                </script>' .
                PHP_EOL;
        }
    }

    echo $result;
}
add_action( 'woocommerce_checkout_after_customer_details', 'gb_hcc_after_checkout_billing_form_vq_options' );

/**
 * Process the AJAX Variations and Quantity options request
 *
 * @since 1.4.0
 */

function gb_hcc_vq_options_process()
{
    if( empty( $_POST['option'] ) )
    {
        return;
    }

    $option = $_POST[ 'option' ];

    $product_id = intval( $option[ 'product_id' ] );

    $mode = $option[ 'mode' ];

    switch( $mode )
    {
        case 'simple_quantity':

            $qty = intval( $option[ 'products_qty' ] );

            $custom_price = floatval( $option[ 'products_price' ] );

            foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
            {
                if( $product_id == $cart_item['data']->get_id() && $cart_item['quantity'] != $qty )
                {
                    if( !empty( $custom_price ) )
                    {
                        $cart_item_data = array( 'custom_price' => $custom_price / $qty );
                        WC()->cart->add_to_cart( $product_id, $qty, 0, array(), $cart_item_data );
                        WC()->cart->remove_cart_item( $cart_item_key );
                    }
                    else
                    {
                        if( isset( $cart_item[ 'custom_price' ] ) )
                        {
                            WC()->cart->add_to_cart( $product_id, $qty );
                            WC()->cart->remove_cart_item( $cart_item_key );
                        }
                        else
                        {
                            WC()->cart->set_quantity( $cart_item_key, $qty );
                        }
                    }
                }
            }

            break;

        case 'simple_variations':

            $variation_id = intval( $option[ 'variation_id' ] );

            foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
            {
                WC()->cart->remove_cart_item( $cart_item_key );
            }
            
            WC()->cart->add_to_cart( $product_id, 1, $variation_id );

            break;

        case 'dropdown_quantity_and_variations':

            $qty = intval( $option[ 'products_qty' ] );

            $variation_id = intval( $option[ 'variation_id' ] );

            $variations = array();

            foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
            {
                $variations[ $cart_item[ 'variation_id' ] ] = $cart_item_key;
            }

            if( !isset( $variations[ $variation_id ] ) )
            {
                WC()->cart->add_to_cart( $product_id, $qty, $variation_id );
            }

            foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
            {
                if( isset( $variations[ $variation_id ] ) && ( $cart_item['quantity'] != $qty ) && ( $cart_item[ 'variation_id' ] == $variation_id ) )
                {
                    WC()->cart->set_quantity( $cart_item_key, $qty );
                }

                if( isset( $variations[ $variation_id ] ) && ( $qty == 0 ) && ( $cart_item[ 'variation_id' ] == $variation_id ) )
                {
                    WC()->cart->remove_cart_item( $cart_item_key );
                }
            }

            break;

        case 'simple_products':

            foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
            {
                WC()->cart->remove_cart_item( $cart_item_key );
            }
            
            WC()->cart->add_to_cart( $product_id, 1 );

            break;
    }
}
add_action( 'wp_ajax_hcc_vq_options', 'gb_hcc_vq_options_process', 999 );
add_action( 'wp_ajax_nopriv_hcc_vq_options', 'gb_hcc_vq_options_process', 999 );

/**
 * Preserve the custom item price added by Variations & Quantity feature
 *
 * @since 1.4.0
 */

function gb_hcc_vq_custom_price_to_cart_item( $cart_object )
{
    if ( is_admin() && !defined( 'DOING_AJAX' ) )
    {
        return;
    }

    if( !WC()->session->__isset( 'reload_checkout' ))
    {
        foreach ( $cart_object->cart_contents as $key => $value )
        {
            if( isset( $value[ 'custom_price' ] ) && !empty( $value[ 'custom_price' ] ) )
            {
                $value[ 'data' ]->set_price( $value[ 'custom_price' ] );
            }
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'gb_hcc_vq_custom_price_to_cart_item', 9999 );

/**
 * Update our custom order items & totals list in the sidebar
 *
 * @since 1.3.0
 */

function gb_hcc_order_fragments_sidebar( $order_fragments )
{
    $options = get_option( 'gb_wc_hcc_options' );

    // if it is order-review page, pull data from order

    if( 
        is_checkout() &&
        is_wc_endpoint_url( 'order-pay' ) && 
        !empty( $options['replace_page_order_review'] )
    )
    {
        global $wp;

        $order_id = $wp->query_vars['order-pay'];
        $order = new WC_Order( $order_id );

        $subtotal = $order->get_subtotal();
        $coupons = $order->get_coupons();
        $needs_shipping = $order->needs_shipping_address();
        $shipping_total = $order->get_shipping_total();
        $cart_tax = $order->get_cart_tax();
        $total = $order->get_total();

        $items = $order->get_items();

        $cart_items_mode = FALSE;
    }

    // else pull data from cart

    else
    {
        $subtotal = WC()->cart->get_cart_subtotal();
        $coupons = WC()->cart->get_coupons();
        $needs_shipping = WC()->cart->needs_shipping();
        $shipping_total = WC()->cart->get_cart_shipping_total();
        $cart_tax = WC()->cart->get_cart_tax();
        $total = WC()->cart->total;

        $items = WC()->cart->get_cart();

        $cart_items_mode = TRUE;
    }

    $order_fragments['.hcc-subtotal-price'] = '<td class="total-line__price hcc-subtotal-price"><span class="order-summary__emphasis">' .
        $subtotal .
        '</span></td>';

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

                $coupon_html          = ' <a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . __( '[Remove]', 'woocommerce' ) . '</a>';

            }
            elseif( $coupon->get_free_shipping() )
            {
                $discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
            }

            $order_fragments['.total-line--coupon'] = '<tr class="total-line total-line--coupon"><td class="total-line__name">' . $coupon_label . '</td>' .
                '<td class="total-line__price hcc-coupon-price"><span class="order-summary__emphasis">' . $discount_amount_html . '</span><span>' . $coupon_html . '</span></td></tr>';
        }
    }

    if( $needs_shipping )
    {
        $order_fragments['.total-line--shipping'] = '<tr class="total-line total-line--shipping"><td class="total-line__name">' . __( 'Shipping', 'gb-wc-hcc' ) . '</td><td class="total-line__price hcc-shipping-price"><span class="order-summary__emphasis">' .
            $shipping_total .
            '</span></td></tr>';
    }

	if( $cart_tax )
	{
		$order_fragments['.total-line--tax'] =
			'<tr class="total-line total-line--tax">' .
				'<td class="total-line__name">' . __( 'Tax', 'gb-wc-hcc' ) . '</td>' .
				'<td class="total-line__price hcc-tax-price">' .
					'<span class="order-summary__emphasis">' .
						$cart_tax .
					'</span>' .
				'</td>' .
			'</tr>';
	}
	else
	{
		$order_fragments['.total-line--tax'] =
			'<tr class="total-line total-line--tax"></tr>';
	}

    $order_fragments['.hcc-total-price'] = '<td class="total-line__price hcc-total-price payment-due"><span class="payment-due__price">' .
        wc_price( $total ) .
        '</span></td>';

    $order_fragments['.hcc-total-recap'] = '<div class="hcc-order-summary-toggle__total-recap hcc-total-recap"><span class="hcc-total-recap__final-price">' .
        wc_price( $total ) .
        '</span></div>';

    $items = $items;

    $table_html = '<div class="order-summary__section__content">';

    foreach( $items as $item_key => $item )
    {
        $_product_id = $item['product_id'];

        if( !empty( $item['variation_id'] ) )
        {
            $_product_id = $item['variation_id'];
        }

        if( $cart_items_mode )
        {
            $_product = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $item_key );

            $item_visible = apply_filters( 'woocommerce_checkout_cart_item_visible', TRUE, $item, $item_key );

            $price = apply_filters( 
                'woocommerce_cart_item_subtotal', 
                WC()->cart->get_product_subtotal( $_product, $item['quantity'] ), 
                $item, 
                $item_key 
            );
        }
        else
        {
            $_product = $item->get_product();

            $item_visible = $_product->is_visible();

            $price = wc_price( get_post_meta( $_product_id, '_price', TRUE ) );
        }

        if( $_product instanceof WC_Product_Variation )
        {
            $variables = $_product->get_attributes();
            $variables_output = ucfirst( implode( ', ', $variables ) );
        }
        else
        {
            $variables_output = '';
        }

        $getProductDetail = wc_get_product( $_product_id );

        if(
            $_product &&
            $_product->exists() &&
            $item['quantity'] > 0 &&
            $item_visible
        )
        {
            $table_html .= '<table class="product-table">
								<tbody data-order-summary-section="line-items" class="line-items-changed">
									<tr class="product ' . esc_attr( apply_filters( 'woocommerce_cart_item_class', '', $item, $item_key ) ) . '">
										<td class="product__image">
											<div class="product-thumbnail">
												<div class="product-thumbnail__wrapper">'. $getProductDetail->get_image( 'thumbnail' ) .'</div>
												<span class="product-thumbnail__quantity" aria-hidden="true">' . $item['quantity'] . '</span>
											 </div>
										</td>
										<td class="product__description"><span class="product__description__name order-summary__emphasis">'. $_product->get_title(). '</span>
											<p>'. $variables_output .'</p>
										</td>
										<td class="product__price"><span class="order-summary__emphasis">' . $price . '</td>
									</tr>
								</tbody>
							</table>';
        }
    }

    $table_html .= '<div class="order-summary__scroll-indicator"><span>' . __( 'Scroll for more items', 'gb-wc-hcc' ) . '</span>
										<svg xmlns="http://www.w3.org/2000/svg" width="10" height="12" viewBox="0 0 10 12">
											<path d="M9.817 7.624l-4.375 4.2c-.245.235-.64.235-.884 0l-4.375-4.2c-.244-.234-.244-.614 0-.848.245-.235.64-.235.884 0L4.375 9.95V.6c0-.332.28-.6.625-.6s.625.268.625.6v9.35l3.308-3.174c.122-.117.282-.176.442-.176.16 0 .32.06.442.176.244.234.244.614 0 .848"></path>
										</svg>
									</div>
						  </div></div>';

    $order_fragments['.order-summary__section__content'] = $table_html;

    return $order_fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'gb_hcc_order_fragments_sidebar', 10, 1 );

/**
 * Modify order button text
 *
 * @since 1.0.0
 */

function gb_hcc_order_button_text( $text )
{
    global $post;

    if( !empty( $post ) )
    {
        if( $post->post_type == 'handsome-checkout' )
        {
            $button_text = get_post_meta( $post->ID, 'hcc_button_text', TRUE );

            if( !empty( $button_text ) )
            {
                $text = $button_text;
            }
        }
        elseif( $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
        {
            $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

            if( !empty( $post_id ) )
            {
                $button_text = get_post_meta( $post_id, 'hcc_form_button_text', TRUE );

                if( !empty( $button_text ) )
                {
                    $text = $button_text;
                }
            }
        }
    }

    return $text;
}
add_filter( 'woocommerce_order_button_text', 'gb_hcc_order_button_text', 9999 );

/**
 * Add hidden field that is used to save hcc
 * template name in the order meta to use later
 *
 * @since 1.0.0
 */

function gb_hcc_checkout_field_add( $checkout )
{
    global $post;

    if( empty( $_SERVER['REQUEST_URI'] ) )
    {
        return;
    }

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        echo '<div style="display: none;">';

        woocommerce_form_field(
            'gb_hcc_referer',
            array(
                'type' => 'text',
            ),
            esc_url( remove_query_arg( 'add-to-cart', $_SERVER['REQUEST_URI'] ) )
        );

        echo '</div>';
    }
}
add_action( 'woocommerce_after_order_notes', 'gb_hcc_checkout_field_add' );

/**
 * Save the value of tracking field if specified
 * and switch billing & shipping fields places
 *
 * @since 1.0.0
 * @updated 1.4.0
 */

function gb_hcc_checkout_field_save( $order_id )
{
    if( !empty( $_POST['gb_hcc_referer'] ) && !empty( $_SERVER['HTTP_REFERER'] ) )
    {
        // save hcc referer url

        update_post_meta( $order_id, '_hcc_referer', sanitize_text_field( $_POST['gb_hcc_referer'] ) );

        // optionally set shipping address first, switch billing <-> shipping

        $fields_shipping_first = FALSE;

        $_SERVER['HTTP_REFERER'] = esc_url_raw( $_SERVER['HTTP_REFERER'] );

        if( is_handsome_checkout_url( $_SERVER['HTTP_REFERER'] ) )
        {
            $uri = explode( '?', $_SERVER['HTTP_REFERER'] );
            $uri = $uri[0];

            $slug = basename( untrailingslashit( $uri ) );

            $post = gb_hcc_get_post_by_slug( $slug );
        }

        if( !empty( $post ) )
        {
            // default

            if( $post->post_type == 'handsome-checkout' )
            {
                $fields_shipping_first = get_post_meta( $post->ID, 'hcc_fields_shipping_first', TRUE );
            }

            // Embedded forms

            elseif( $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
            {
                $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

                if( !empty( $post_id ) )
                {
                    $fields_shipping_first = get_post_meta( $post_id, 'hcc_form_fields_shipping_first', TRUE );
                }
            }
        }

        if( !empty( $fields_shipping_first ) )
        {
            $order = wc_get_order( $order_id );

            $billing_address_1 = $order->get_billing_address_1();
            $shipping_address_1 = $order->get_shipping_address_1();

            // copy billing to shipping

            if( !empty( $billing_address_1 ) && empty( $shipping_address_1 ) )
            {
                $order->set_shipping_first_name( $order->get_billing_first_name() );
                $order->set_shipping_last_name( $order->get_billing_last_name() );
                $order->set_shipping_company( $order->get_billing_company() );
                $order->set_shipping_address_1( $order->get_billing_address_1() );
                $order->set_shipping_address_2( $order->get_billing_address_2() );
                $order->set_shipping_city( $order->get_billing_city() );
                $order->set_shipping_state( $order->get_billing_state() );
                $order->set_shipping_postcode( $order->get_billing_postcode() );
                $order->set_shipping_country( $order->get_billing_country() );
            }

            // switch billing <-> shipping

            elseif( !empty( $billing_address_1 ) && !empty( $shipping_address_1 ) )
            {
                $shipping_first_name = $order->get_shipping_first_name();
                $shipping_last_name = $order->get_shipping_last_name();
                $shipping_company = $order->get_shipping_company();
                $shipping_address_1 = $order->get_shipping_address_1();
                $shipping_address_2 = $order->get_shipping_address_2();
                $shipping_city = $order->get_shipping_city();
                $shipping_state = $order->get_shipping_state();
                $shipping_postcode = $order->get_shipping_postcode();
                $shipping_country = $order->get_shipping_country();

                $order->set_shipping_first_name( $order->get_billing_first_name() );
                $order->set_shipping_last_name( $order->get_billing_last_name() );
                $order->set_shipping_company( $order->get_billing_company() );
                $order->set_shipping_address_1( $order->get_billing_address_1() );
                $order->set_shipping_address_2( $order->get_billing_address_2() );
                $order->set_shipping_city( $order->get_billing_city() );
                $order->set_shipping_state( $order->get_billing_state() );
                $order->set_shipping_postcode( $order->get_billing_postcode() );
                $order->set_shipping_country( $order->get_billing_country() );

                $order->set_billing_first_name( $shipping_first_name );
                $order->set_billing_last_name( $shipping_last_name );
                $order->set_billing_company( $shipping_company );
                $order->set_billing_address_1( $shipping_address_1 );
                $order->set_billing_address_2( $shipping_address_2 );
                $order->set_billing_city( $shipping_city );
                $order->set_billing_state( $shipping_state );
                $order->set_billing_postcode( $shipping_postcode );
                $order->set_billing_country( $shipping_country );
            }

            $order->save();
        }
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'gb_hcc_checkout_field_save' );

/**
 * Save the value of tracking field if specified
 *
 * @since 1.0.1
 */

function gb_hcc_custom_admin_bar()
{
    global $post;

    if( is_user_logged_in() && current_user_can('edit_posts') )
    {
        if( !empty( $post ) )
        {
            echo '
				<style type="text/css">#wpadminbar { text-align: center; } #wpadminbar a { color: #FFF; }</style>

				<div id="wpadminbar"><a href="' . esc_html( get_edit_post_link( $post->ID ) ) . '">' . esc_html__( 'edit this page', 'gb-wc-hcc' ) . '</a></div>';
        }
    }
}

/**
 * Change some default texts on WooCommerce Checkout page
 *
 * @since 1.1.0
 */

function gb_hcc_filter_default_texts( $translated, $text, $domain )
{
    global $post;

    if( is_admin() )
    {
        return $translated;
    }

    if( $domain != 'woocommerce' )
    {
        return $translated;
    }

    if( !empty( $post ) )
    {
        /**
         * Standalone pages
         *
         * @since 1.2.0
         */

        if( $post->post_type == 'handsome-checkout' )
        {
            if( $text == 'Billing details' )
            {
                $title_text_step1 = get_post_meta( $post->ID, 'hcc_title_text_step1', TRUE );

                if( !empty( $title_text_step1 ) )
                {
                    return $title_text_step1;
                }
                else
                {
                    return __( 'Step 1: Billing Details', 'gb-wc-hcc' );
                }
            }
            elseif( $text == 'Billing &amp; Shipping' )
            {
                $title_text_step1 = get_post_meta( $post->ID, 'hcc_title_text_step1', TRUE );

                if( !empty( $title_text_step1 ) )
                {
                    return $title_text_step1;
                }
                else
                {
                    return __( 'Step 1: Billing &amp; Shipping', 'gb-wc-hcc' );
                }
            }
            elseif( mb_strpos( $text, 'Billing &amp; Shipping section' ) !== FALSE )
            {
                $title_text_step1 = get_post_meta( $post->ID, 'hcc_title_text_step1', TRUE );

                if( empty( $title_text_step1 ) )
                {
                    $title_text_step1 = __( 'Step 1', 'gb-wc-hcc' );
                }

                $text = str_replace( 'Billing &amp; Shipping', $title_text_step1, $text );

                return $text;
            }
            elseif( $text == 'Your order' )
            {
                $title_text_step2 = get_post_meta( $post->ID, 'hcc_title_text_step2', TRUE );

                if( !empty( $title_text_step2 ) )
                {
                    return $title_text_step2;
                }
                else
                {
                    return __( 'Step 2: Payment Information', 'gb-wc-hcc' );
                }
            }
            elseif( $text == 'Ship to a different address?' )
            {
                $fields_shipping_first = get_post_meta( $post->ID, 'hcc_fields_shipping_first', TRUE );

                if( !empty( $fields_shipping_first ) )
                {
                    return __( 'Use a different billing address', 'gb-wc-hcc' );
                }
            }
        }

        /**
         * Embedded forms
         *
         * @since 1.4.0
         */

        elseif( $shortcode = gb_hcc_form_get_embed_shortcode( $post->post_content ) )
        {
            $post_id = gb_hcc_form_get_shortcode_id( $post->post_content, $shortcode );

            if( !empty( $post_id ) )
            {
                $form_fields_shipping_first = get_post_meta( $post_id, 'hcc_form_fields_shipping_first', TRUE );

                if( !empty( $form_fields_shipping_first ) )
                {
                    if( $text == 'Ship to a different address?' )
                    {
                        return __( 'Use a different billing address', 'gb-wc-hcc' );
                    }
                    elseif( $text == 'Billing details' )
                    {
                        return __( 'Shipping details', 'gb-wc-hcc' );
                    }
                }

                // make Card Expiry label shorter

                if( $text == 'Card Expiry (MM/YY)' || $text == 'Expiry (MM/YY)' )
                {
                    return __( 'Expiry', 'gb-wc-hcc' );
                }
            }
        }
    }

    return $translated;
}
add_filter( 'gettext', 'gb_hcc_filter_default_texts', 10, 3 );

/**
 * Redirect from default to the Handsome checkout page
 *
 * @since 1.1.0
 */

function gb_hcc_template_redirect()
{
	if( !function_exists( 'is_checkout' ) )
	{
	    return;
	}

    if( !is_checkout() )
    {
        return;
    }

    if( empty( $_SERVER['REQUEST_URI'] ) )
    {
        return;
    }

    // check if current page contains the Embed Form shortcode

    $is_checkout_embed = FALSE;

    $uri = explode( '?', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
    $uri = $uri[0];

    $slug = basename( untrailingslashit( $uri ) );

    $post = gb_hcc_get_post_by_slug( $slug, 'any' );

    if( !empty( $post ) && gb_hcc_form_get_embed_shortcode( $post->post_content ) )
    {
        $is_checkout_embed = TRUE;
    }

    // redirect only from any non HC checkout pages

    $order_pay_endpoint = get_option( 'woocommerce_checkout_order_pay_endpoint', 'order-pay' );
    $order_received_endpoint = get_option( 'woocommerce_checkout_order_received_endpoint', 'order-received' );

    if(
        is_checkout() &&
        $is_checkout_embed === FALSE &&

        // ignore on HC pages
        mb_strpos( $_SERVER['REQUEST_URI'], '/handsome-checkout/' ) === FALSE &&
        mb_strpos( $_SERVER['REQUEST_URI'], '/checkout-handsome/' ) === FALSE &&
        mb_strpos( $_SERVER['REQUEST_URI'], '/checkout-hs/' ) === FALSE && 
        mb_strpos( $_SERVER['REQUEST_URI'], '/checkouts/' ) === FALSE && 

        // ignore on order-review
        mb_strpos( $_SERVER['REQUEST_URI'], '/' . $order_pay_endpoint . '/' ) === FALSE && 
        !isset( $_GET['pay_for_order'] ) &&

        // ignore on TY page
        mb_strpos( $_SERVER['REQUEST_URI'], '/' . $order_received_endpoint . '/' ) === FALSE 
    )
    {
        $options = get_option( 'gb_wc_hcc_options' );

        if( !empty( $options['replace_page'] ) )
        {
            $link = get_permalink( $options['replace_page'] );

            if( !empty( $link ) )
            {
                wp_redirect( $link );

                die();
            }
        }
    }
}
add_action( 'template_redirect', 'gb_hcc_template_redirect', 1 );

/**
 * Get template's predefined text string or customized string by name
 *
 * @since 1.1.0
 */

function gb_hcc_template_predefined_text( $id = '', $template = '' )
{
    global $post;

    $text = '';

    $predefined = array(

        // Template #1

        'gb-wc-hcc-template' => array(
            '' => '',
        ),

        // Template #2

        'gb-wc-hcc-template-2' => array(
            '' => '',
        ),
    );

    // default

    if( !empty( $predefined[ $template ] ) && !empty( $predefined[ $template ][ $id ] ) )
    {
        $text = $predefined[ $template ][ $id ];
    }

    // customized

    if( !empty( $post ) && $post->post_type == 'handsome-checkout' )
    {
        $custom = get_post_meta( $post->ID, 'hcc_custom_texts', TRUE );

        if( !empty( $custom ) && !empty( $custom[ $id ] ) )
        {
            $text = $custom[ $id ];
        }
    }

    return $text;
}

/**
 * Adds link to support page in installed plugins list
 *
 * @since 1.0.1
 */

function gb_hcc_plugin_row_meta( $links, $file )
{
    if( mb_strpos( $file, GB_HCC_FILE ) !== FALSE )
    {
        if( !empty( $links[2] ) && mb_strpos( $links[2], 'View details' ) !== FALSE )
        {
            unset( $links[2] );
        }

        $links[] = '<a href="https://bogdanfix.com/documentation/" target="_blank">' . __( 'Docs & FAQs', 'gb-wc-hcc' ) . '</a>';
        $links[] = '<a href="https://bogdanfix.com/downloads/hcc/changelog.txt" target="_blank">' . __( 'Changelog', 'gb-wc-hcc' ) . '</a>';
        $links[] = '<a href="https://bogdanfix.com/contact-us/" target="_blank">' . __( 'Support', 'gb-wc-hcc' ) . '</a>';
    }

    return $links;
}
add_filter( 'plugin_row_meta', 'gb_hcc_plugin_row_meta', 10, 2 );

/**
 * Adds link to "Duplicate" page in the pages list
 *
 * @since 1.2.0
 */

function gb_hcc_row_actions( $actions, WP_Post $post )
{
    if( $post->post_type == 'handsome-checkout' )
    {
        $nonce = wp_create_nonce( 'hcc-duplicate-' . $post->ID );

        $post_status = ( $post->post_status == 'shortcode' ) ? '&post_status=shortcode' : '';

        $actions['gb-wc-hcc-duplicate'] = '<a href="edit.php?post_type=handsome-checkout' . $post_status . '&duplicate=' . $post->ID . '&_wpnonce=' . $nonce . '" onclick="return confirm(\'' . __( 'Are you sure?', 'gb-wc-hcc' ) . '\');">' . __( 'Duplicate', 'gb-wc-hcc' ) . '</a>';
    }

    return $actions;
}
add_filter( 'post_row_actions', 'gb_hcc_row_actions', 10, 2 );

/**
 * Process the duplicate page creation
 *
 * @since 1.2.0
 */

function gb_hcc_duplicate_process()
{
    if( !empty( $_GET['post_type'] ) && $_GET['post_type'] == 'handsome-checkout' )
    {
        if( !empty( $_GET['duplicate'] ) && !empty( $_GET['_wpnonce'] ) )
        {
            $post_id = intval( $_GET['duplicate'] );

            $_GET['_wpnonce'] = sanitize_text_field( $_GET['_wpnonce'] );

            if( wp_verify_nonce( $_GET['_wpnonce'], 'hcc-duplicate-' . $post_id ) )
            {
                if( gb_hcc_duplicate_page( $post_id ) )
                {
                    $post_status = ( !empty( $_GET['post_status'] ) && $_GET['post_status'] == 'shortcode' ) ? '&post_status=shortcode' : '';

                    wp_redirect( admin_url( '/edit.php?post_type=handsome-checkout' . $post_status . '&hcc_duplicate_created=true' ) );

                    die();
                }
            }
            else
            {
                wp_nonce_ays();
            }
        }
    }
}
add_action( 'admin_init', 'gb_hcc_duplicate_process' );

/**
 * HELPER: Duplicates the Handsome Checkout page
 *
 * @since 1.2.0
 */

function gb_hcc_duplicate_page( $post_id )
{
    $result = FALSE;

    if( $post_id )
    {
        $original = get_post( $post_id );

        $copy = array();

        $copy['ID'] = null;
        $copy['post_type'] = $original->post_type;
        $copy['post_parent'] = $original->ID;
        $copy['post_status'] = ( $original->post_status == 'shortcode' ) ? 'shortcode' : 'draft';

        $copy['post_name'] = $original->post_name . '-copy';
        $copy['post_title'] = $original->post_title . ' ' . __( 'Copy', 'gb-wc-hcc' );

        $copy_id = wp_insert_post( $copy );

        if( $copy_id )
        {
            $meta = get_post_meta( $post_id );

            foreach( $meta as $key => $value )
            {
                if( mb_strpos( $key, 'hcc_' ) === 0 )
                {
                    $value = get_post_meta( $post_id, $key, TRUE );

                    update_post_meta( $copy_id, $key, $value );
                }
            }

            $result = TRUE;
        }
    }

    return $result;
}

/**
 * HELPER: Get post by it's slug
 *
 * @since 1.3.0
 */

function gb_hcc_get_post_by_slug( $slug = '', $post_type = 'handsome-checkout' )
{
    $posts = get_posts( array(
        'name' => $slug,
        'posts_per_page' => 1,
        'post_type' => $post_type,
        'post_status' => 'any',
    ));

    if( !empty( $posts ) )
    {
        return $posts[0];
    }
    else
    {
        return FALSE;
    }
}

/**
 * HELPER: Check if the url belongs to HC page
 *
 * @since 1.4.0
 */

function is_handsome_checkout_url( $url = '' )
{
    $result = FALSE;

    if(
        mb_strpos( $url, '/handsome-checkout/' ) !== FALSE ||
        mb_strpos( $url, '/checkout-handsome/' ) !== FALSE ||
        mb_strpos( $url, '/checkout-hs/' ) !== FALSE ||
        mb_strpos( $url, '/checkouts/' ) !== FALSE
    )
    {
        $result = TRUE;
    }

    return $result;
}

/**
 * HELPER: Called when WooCommerce is inactive to display an inactive notice.
 *
 * @since 1.5.4
 */

function gb_hcc_inactive_notice()
{
    if( current_user_can( 'activate_plugins' ) ) :

        if( !gb_hcc_is_wc_active() ) : 

    ?>
        <div id="message" class="error">
            <p><?php
                // translators: 1$-2$: opening and closing <strong> tags, 3$-4$: link tags, takes to woocommerce plugin on wp.org, 5$-6$: opening and closing link tags, leads to plugins.php in admin
                printf( 
                    esc_html__( 
                        '%1$sHandsome Checkout is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for Handsome Checkout to work. Please %5$sinstall & activate WooCommerce &raquo;%6$s', 
                        'gb-wc-hcc' 
                    ), 
                    '<strong>', 
                    '</strong>', 
                    '<a href="http://wordpress.org/extend/plugins/woocommerce/">', 
                    '</a>', 
                    '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', 
                    '</a>' ); 
                ?>
            </p>
        </div>
    <?php

        endif;

    endif;
}

/**
 * HELPER: Display the style configuration box for 
 * a certain option (text field)
 *
 * @since 1.5.4
 */

function gb_hcc_config_style( $option = '', $style = array(), $type = 'text' )
{
    $result = '';

    if( empty( $option ) )
    {
        return;
    }

    if( empty( $style ) )
    {
        $style = array();
    }

    if( empty( $style['font'] ) )
    {
        $style['font'] = '';
    }

    if( empty( $style['style'] ) )
    {
        $style['style'] = '';
    }

    if( empty( $style['size'] ) )
    {
        $style['size'] = '';
    }

    if( empty( $style['color'] ) )
    {
        $style['color'] = '';
    }

    if( empty( $style['width'] ) )
    {
        $style['width'] = '';
    }

    if( empty( $style['height'] ) )
    {
        $style['height'] = '';
    }

    if( empty( $style['code_icon'] ) )
    {
        $style['code_icon'] = '';
    }

    if( empty( $style['color_icon'] ) )
    {
        $style['color_icon'] = '';
    }

    if( empty( $style['bold'] ) )
    {
        $style['bold'] = '';
    }

    if( empty( $style['italic'] ) )
    {
        $style['italic'] = '';
    }

    if( empty( $style['underline'] ) )
    {
        $style['underline'] = '';
    }

    $fonts = array(
        'Arial',
        'Arial Black',
        'Courier',
        'Courier New',
        'Georgia',
        'Times New Roman',
        'Trebuchet MS',
        'Verdana',
        'Helvetica',
        'Times',
        'Palatino',
        'Garamond',
        'Bookman',
        'Comic Sans MS',
        'Impact',
    );

    $result = ob_start();
?>
<div class="wc-hcc-config-style-wrap" style="display: none;">

    <p><?php esc_html_e( 'Text style:', 'gb-wc-hcc' ); ?></p>

    <div>

        <select name="hcc_<?php echo esc_html( $option ); ?>_style[font]">

            <option value="" <?php selected( $style['font'], '' ); ?>><?php esc_html_e( 'default', 'gb-wc-hcc' ); ?></option>
        <?php

            foreach( $fonts as $f )
            {
                echo '<option value="' . esc_html( $f ) . '" ' . selected( $style['font'], $f ) . '>' . esc_html( $f ) . '</option>';
            }
        ?>

        </select>

        <input type="number" name="hcc_<?php echo esc_html( $option ); ?>_style[size]" value="<?php echo esc_html( $style['size'] ); ?>" min="1" max="250" placeholder="<?php esc_html_e( 'size', 'gb-wc-hcc' ); ?>" />

        <label><input type="checkbox" name="hcc_<?php echo esc_html( $option ); ?>_style[bold]" value="1" <?php checked( $style['bold'], '1' ); ?> /><b>B</b></label>

        <label><input type="checkbox" name="hcc_<?php echo esc_html( $option ); ?>_style[italic]" value="1" <?php checked( $style['italic'], '1' ); ?> /><i>I</i></label>

        <label><input type="checkbox" name="hcc_<?php echo esc_html( $option ); ?>_style[underline]" value="1" <?php checked( $style['underline'], '1' ); ?> /><u>U</u></label>

    </div>

    <div>

        <input type="text" name="hcc_<?php echo esc_html( $option ); ?>_style[color]" value="<?php echo esc_html( $style['color'] ); ?>" class="wc-hcc-color-picker" />

    </div>

    <?php
        if( $type === 'button' ):
    ?>

    <br />

    <p><?php esc_html_e( 'Button style:', 'gb-wc-hcc' ); ?></p>

    <div class="gb-wc-hcc-fa-select2-wrap">

        <select name="hcc_<?php echo esc_html( $option ); ?>_style[code_icon]" class="gb-wc-hcc-fa-select2">
        <?php

            $fa_icons = gb_hcc_fa_icons();

            echo '<option value="">' . esc_html__( 'pick icon...', 'gb-wc-hcc' ) . '</option>';

            foreach( $fa_icons as $name => $k )
            {
                $k = trim( $k, '\\' );

                echo '<option value="' . esc_html( $k ) . '" data-icon="' . esc_html( $name ) . '" ' . ( ( $style['code_icon'] == $k ) ? 'selected="selected"' : '' ) . '>' . esc_html( substr( $name, 3 ) ) . '</option>';
            }

        ?>
        </select>
        
    </div>

    <div>

        <input type="text" name="hcc_<?php echo esc_html( $option ); ?>_style[width]" value="<?php echo esc_html( $style['width'] ); ?>" placeholder="<?php esc_html_e( 'width', 'gb-wc-hcc' ); ?>" />

        <input type="text" name="hcc_<?php echo esc_html( $option ); ?>_style[height]" value="<?php echo esc_html( $style['height'] ); ?>" placeholder="<?php esc_html_e( 'height', 'gb-wc-hcc' ); ?>" />

        <span class="tip">px <u>or</u> %</span>

    </div>

    <div>

        <input type="text" name="hcc_<?php echo esc_html( $option ); ?>_style[color_icon]" value="<?php echo esc_html( $style['color_icon'] ); ?>" class="wc-hcc-color-picker" />

    </div>
    <?php
        endif;
    ?>

</div>
<?php

    $result = ob_get_clean();

    return $result;
}

/**
 * HELPER: Apply the styles from the style 
 * configuration box to the certain option value
 *
 * @since 1.5.4
 */

function gb_hcc_apply_style( $result = '', $post_id = 0, $field = '', $only_css = FALSE )
{
    if( empty( $post_id ) || empty( $field ) )
    {
        return FALSE;
    }

    $style = get_post_meta( $post_id, $field . '_style', TRUE );

    if( !empty( $style ) )
    {
        $style_css = $style_css_icon = '';

        if( !empty( $style['font'] ) )
        {
            $style_css .= 'font-family: ' . $style['font'] . ', sans-serif;';
        }

        if( !empty( $style['size'] ) )
        {
            $style_css .= 'font-size: ' . $style['size'] . 'px;';
        }

        if( !empty( $style['bold'] ) )
        {
            $style_css .= 'font-weight: bold;';
        }

        if( !empty( $style['italic'] ) )
        {
            $style_css .= 'font-style: italic;';
        }

        if( !empty( $style['underline'] ) )
        {
            $style_css .= 'text-decoration: underline;';
        }

        if( !empty( $style['color'] ) )
        {
            $style_css .= 'color: ' . $style['color'] . ';';
        }

        if( !empty( $style['width'] ) )
        {
            if( 
                strpos( $style['width'], 'px' ) === FALSE &&
                strpos( $style['width'], '%' ) === FALSE
            )
            {
                $style['width'] = $style['width'] . 'px';
            }

            $style_css .= 'width: ' . $style['width'] . ';';
        }

        if( !empty( $style['height'] ) )
        {
            if( 
                strpos( $style['height'], 'px' ) === FALSE &&
                strpos( $style['height'], '%' ) === FALSE
            )
            {
                $style['height'] = $style['height'] . 'px';
            }

            $style_css .= 'height: ' . $style['height'] . ';';

            if( $field === 'hcc_button_text' )
            {
                $style_css .= 'padding: 0;';
            }
        }

        // button icon styles

        if( !empty( $style['code_icon'] ) )
        {
            $style_css_icon .= 'content: "\\' . $style['code_icon'] . '";';
        }

        if( !empty( $style['color_icon'] ) )
        {
            $style_css_icon .= 'color: ' . $style['color_icon'] . ';';
        }

        // prepare result

        if( $only_css === FALSE )
        {
            $result = '<span style="' . $style_css . '">' . $result . '</span>';
        }
        else
        {
            $selector = $result;

            $result = $selector . '{' . $style_css . '}' . PHP_EOL . PHP_EOL;

            if( !empty( $style_css_icon ) )
            {
                $result .= $selector . ':before{
                    display: inline-block;
                    font-style: normal;
                    font-variant: normal;
                    text-rendering: auto;
                    font-family: "FontAwesome";
                    -webkit-font-smoothing: antialiased;
                    padding-right: 5px;' . 
                    $style_css_icon . 
                '}' . PHP_EOL . PHP_EOL;
            }
        }
    }

    // if only_css mode is enabled and there is no style, return empty string

    if( $only_css && empty( $style ) )
    {
        $result = '';
    }

    return $result;
}

/**
 * HELPER: Font Awesome icons list 4.6.3
 *
 * @since 1.5.4
 */

function gb_hcc_fa_icons()
{
    $fa_icons = array(
        'fa-glass'                               => '\f000',
        'fa-music'                               => '\f001',
        'fa-search'                              => '\f002',
        'fa-envelope-o'                          => '\f003',
        'fa-heart'                               => '\f004',
        'fa-star'                                => '\f005',
        'fa-star-o'                              => '\f006',
        'fa-user'                                => '\f007',
        'fa-film'                                => '\f008',
        'fa-th-large'                            => '\f009',
        'fa-th'                                  => '\f00a',
        'fa-th-list'                             => '\f00b',
        'fa-check'                               => '\f00c',
        'fa-times'                               => '\f00d',
        'fa-search-plus'                         => '\f00e',
        'fa-search-minus'                        => '\f010',
        'fa-power-off'                           => '\f011',
        'fa-signal'                              => '\f012',
        'fa-cog'                                 => '\f013',
        'fa-trash-o'                             => '\f014',
        'fa-home'                                => '\f015',
        'fa-file-o'                              => '\f016',
        'fa-clock-o'                             => '\f017',
        'fa-road'                                => '\f018',
        'fa-download'                            => '\f019',
        'fa-arrow-circle-o-down'                 => '\f01a',
        'fa-arrow-circle-o-up'                   => '\f01b',
        'fa-inbox'                               => '\f01c',
        'fa-play-circle-o'                       => '\f01d',
        'fa-repeat'                              => '\f01e',
        'fa-refresh'                             => '\f021',
        'fa-list-alt'                            => '\f022',
        'fa-lock'                                => '\f023',
        'fa-flag'                                => '\f024',
        'fa-headphones'                          => '\f025',
        'fa-volume-off'                          => '\f026',
        'fa-volume-down'                         => '\f027',
        'fa-volume-up'                           => '\f028',
        'fa-qrcode'                              => '\f029',
        'fa-barcode'                             => '\f02a',
        'fa-tag'                                 => '\f02b',
        'fa-tags'                                => '\f02c',
        'fa-book'                                => '\f02d',
        'fa-bookmark'                            => '\f02e',
        'fa-print'                               => '\f02f',
        'fa-camera'                              => '\f030',
        'fa-font'                                => '\f031',
        'fa-bold'                                => '\f032',
        'fa-italic'                              => '\f033',
        'fa-text-height'                         => '\f034',
        'fa-text-width'                          => '\f035',
        'fa-align-left'                          => '\f036',
        'fa-align-center'                        => '\f037',
        'fa-align-right'                         => '\f038',
        'fa-align-justify'                       => '\f039',
        'fa-list'                                => '\f03a',
        'fa-outdent'                             => '\f03b',
        'fa-indent'                              => '\f03c',
        'fa-video-camera'                        => '\f03d',
        'fa-picture-o'                           => '\f03e',
        'fa-pencil'                              => '\f040',
        'fa-map-marker'                          => '\f041',
        'fa-adjust'                              => '\f042',
        'fa-tint'                                => '\f043',
        'fa-pencil-square-o'                     => '\f044',
        'fa-share-square-o'                      => '\f045',
        'fa-check-square-o'                      => '\f046',
        'fa-arrows'                              => '\f047',
        'fa-step-backward'                       => '\f048',
        'fa-fast-backward'                       => '\f049',
        'fa-backward'                            => '\f04a',
        'fa-play'                                => '\f04b',
        'fa-pause'                               => '\f04c',
        'fa-stop'                                => '\f04d',
        'fa-forward'                             => '\f04e',
        'fa-fast-forward'                        => '\f050',
        'fa-step-forward'                        => '\f051',
        'fa-eject'                               => '\f052',
        'fa-chevron-left'                        => '\f053',
        'fa-chevron-right'                       => '\f054',
        'fa-plus-circle'                         => '\f055',
        'fa-minus-circle'                        => '\f056',
        'fa-times-circle'                        => '\f057',
        'fa-check-circle'                        => '\f058',
        'fa-question-circle'                     => '\f059',
        'fa-info-circle'                         => '\f05a',
        'fa-crosshairs'                          => '\f05b',
        'fa-times-circle-o'                      => '\f05c',
        'fa-check-circle-o'                      => '\f05d',
        'fa-ban'                                 => '\f05e',
        'fa-arrow-left'                          => '\f060',
        'fa-arrow-right'                         => '\f061',
        'fa-arrow-up'                            => '\f062',
        'fa-arrow-down'                          => '\f063',
        'fa-share'                               => '\f064',
        'fa-expand'                              => '\f065',
        'fa-compress'                            => '\f066',
        'fa-plus'                                => '\f067',
        'fa-minus'                               => '\f068',
        'fa-asterisk'                            => '\f069',
        'fa-exclamation-circle'                  => '\f06a',
        'fa-gift'                                => '\f06b',
        'fa-leaf'                                => '\f06c',
        'fa-fire'                                => '\f06d',
        'fa-eye'                                 => '\f06e',
        'fa-eye-slash'                           => '\f070',
        'fa-exclamation-triangle'                => '\f071',
        'fa-plane'                               => '\f072',
        'fa-calendar'                            => '\f073',
        'fa-random'                              => '\f074',
        'fa-comment'                             => '\f075',
        'fa-magnet'                              => '\f076',
        'fa-chevron-up'                          => '\f077',
        'fa-chevron-down'                        => '\f078',
        'fa-retweet'                             => '\f079',
        'fa-shopping-cart'                       => '\f07a',
        'fa-folder'                              => '\f07b',
        'fa-folder-open'                         => '\f07c',
        'fa-arrows-v'                            => '\f07d',
        'fa-arrows-h'                            => '\f07e',
        'fa-bar-chart'                           => '\f080',
        'fa-twitter-square'                      => '\f081',
        'fa-facebook-square'                     => '\f082',
        'fa-camera-retro'                        => '\f083',
        'fa-key'                                 => '\f084',
        'fa-cogs'                                => '\f085',
        'fa-comments'                            => '\f086',
        'fa-thumbs-o-up'                         => '\f087',
        'fa-thumbs-o-down'                       => '\f088',
        'fa-star-half'                           => '\f089',
        'fa-heart-o'                             => '\f08a',
        'fa-sign-out'                            => '\f08b',
        'fa-linkedin-square'                     => '\f08c',
        'fa-thumb-tack'                          => '\f08d',
        'fa-external-link'                       => '\f08e',
        'fa-sign-in'                             => '\f090',
        'fa-trophy'                              => '\f091',
        'fa-github-square'                       => '\f092',
        'fa-upload'                              => '\f093',
        'fa-lemon-o'                             => '\f094',
        'fa-phone'                               => '\f095',
        'fa-square-o'                            => '\f096',
        'fa-bookmark-o'                          => '\f097',
        'fa-phone-square'                        => '\f098',
        'fa-twitter'                             => '\f099',
        'fa-facebook'                            => '\f09a',
        'fa-github'                              => '\f09b',
        'fa-unlock'                              => '\f09c',
        'fa-credit-card'                         => '\f09d',
        'fa-rss'                                 => '\f09e',
        'fa-hdd-o'                               => '\f0a0',
        'fa-bullhorn'                            => '\f0a1',
        'fa-bell'                                => '\f0f3',
        'fa-certificate'                         => '\f0a3',
        'fa-hand-o-right'                        => '\f0a4',
        'fa-hand-o-left'                         => '\f0a5',
        'fa-hand-o-up'                           => '\f0a6',
        'fa-hand-o-down'                         => '\f0a7',
        'fa-arrow-circle-left'                   => '\f0a8',
        'fa-arrow-circle-right'                  => '\f0a9',
        'fa-arrow-circle-up'                     => '\f0aa',
        'fa-arrow-circle-down'                   => '\f0ab',
        'fa-globe'                               => '\f0ac',
        'fa-wrench'                              => '\f0ad',
        'fa-tasks'                               => '\f0ae',
        'fa-filter'                              => '\f0b0',
        'fa-briefcase'                           => '\f0b1',
        'fa-arrows-alt'                          => '\f0b2',
        'fa-users'                               => '\f0c0',
        'fa-link'                                => '\f0c1',
        'fa-cloud'                               => '\f0c2',
        'fa-flask'                               => '\f0c3',
        'fa-scissors'                            => '\f0c4',
        'fa-files-o'                             => '\f0c5',
        'fa-paperclip'                           => '\f0c6',
        'fa-floppy-o'                            => '\f0c7',
        'fa-square'                              => '\f0c8',
        'fa-bars'                                => '\f0c9',
        'fa-list-ul'                             => '\f0ca',
        'fa-list-ol'                             => '\f0cb',
        'fa-strikethrough'                       => '\f0cc',
        'fa-underline'                           => '\f0cd',
        'fa-table'                               => '\f0ce',
        'fa-magic'                               => '\f0d0',
        'fa-truck'                               => '\f0d1',
        'fa-pinterest'                           => '\f0d2',
        'fa-pinterest-square'                    => '\f0d3',
        'fa-google-plus-square'                  => '\f0d4',
        'fa-google-plus'                         => '\f0d5',
        'fa-money'                               => '\f0d6',
        'fa-caret-down'                          => '\f0d7',
        'fa-caret-up'                            => '\f0d8',
        'fa-caret-left'                          => '\f0d9',
        'fa-caret-right'                         => '\f0da',
        'fa-columns'                             => '\f0db',
        'fa-sort'                                => '\f0dc',
        'fa-sort-desc'                           => '\f0dd',
        'fa-sort-asc'                            => '\f0de',
        'fa-envelope'                            => '\f0e0',
        'fa-linkedin'                            => '\f0e1',
        'fa-undo'                                => '\f0e2',
        'fa-gavel'                               => '\f0e3',
        'fa-tachometer'                          => '\f0e4',
        'fa-comment-o'                           => '\f0e5',
        'fa-comments-o'                          => '\f0e6',
        'fa-bolt'                                => '\f0e7',
        'fa-sitemap'                             => '\f0e8',
        'fa-umbrella'                            => '\f0e9',
        'fa-clipboard'                           => '\f0ea',
        'fa-lightbulb-o'                         => '\f0eb',
        'fa-exchange'                            => '\f0ec',
        'fa-cloud-download'                      => '\f0ed',
        'fa-cloud-upload'                        => '\f0ee',
        'fa-user-md'                             => '\f0f0',
        'fa-stethoscope'                         => '\f0f1',
        'fa-suitcase'                            => '\f0f2',
        'fa-bell-o'                              => '\f0a2',
        'fa-coffee'                              => '\f0f4',
        'fa-cutlery'                             => '\f0f5',
        'fa-file-text-o'                         => '\f0f6',
        'fa-building-o'                          => '\f0f7',
        'fa-hospital-o'                          => '\f0f8',
        'fa-ambulance'                           => '\f0f9',
        'fa-medkit'                              => '\f0fa',
        'fa-fighter-jet'                         => '\f0fb',
        'fa-beer'                                => '\f0fc',
        'fa-h-square'                            => '\f0fd',
        'fa-plus-square'                         => '\f0fe',
        'fa-angle-double-left'                   => '\f100',
        'fa-angle-double-right'                  => '\f101',
        'fa-angle-double-up'                     => '\f102',
        'fa-angle-double-down'                   => '\f103',
        'fa-angle-left'                          => '\f104',
        'fa-angle-right'                         => '\f105',
        'fa-angle-up'                            => '\f106',
        'fa-angle-down'                          => '\f107',
        'fa-desktop'                             => '\f108',
        'fa-laptop'                              => '\f109',
        'fa-tablet'                              => '\f10a',
        'fa-mobile'                              => '\f10b',
        'fa-circle-o'                            => '\f10c',
        'fa-quote-left'                          => '\f10d',
        'fa-quote-right'                         => '\f10e',
        'fa-spinner'                             => '\f110',
        'fa-circle'                              => '\f111',
        'fa-reply'                               => '\f112',
        'fa-github-alt'                          => '\f113',
        'fa-folder-o'                            => '\f114',
        'fa-folder-open-o'                       => '\f115',
        'fa-smile-o'                             => '\f118',
        'fa-frown-o'                             => '\f119',
        'fa-meh-o'                               => '\f11a',
        'fa-gamepad'                             => '\f11b',
        'fa-keyboard-o'                          => '\f11c',
        'fa-flag-o'                              => '\f11d',
        'fa-flag-checkered'                      => '\f11e',
        'fa-terminal'                            => '\f120',
        'fa-code'                                => '\f121',
        'fa-reply-all'                           => '\f122',
        'fa-star-half-o'                         => '\f123',
        'fa-location-arrow'                      => '\f124',
        'fa-crop'                                => '\f125',
        'fa-code-fork'                           => '\f126',
        'fa-chain-broken'                        => '\f127',
        'fa-question'                            => '\f128',
        'fa-info'                                => '\f129',
        'fa-exclamation'                         => '\f12a',
        'fa-superscript'                         => '\f12b',
        'fa-subscript'                           => '\f12c',
        'fa-eraser'                              => '\f12d',
        'fa-puzzle-piece'                        => '\f12e',
        'fa-microphone'                          => '\f130',
        'fa-microphone-slash'                    => '\f131',
        'fa-shield'                              => '\f132',
        'fa-calendar-o'                          => '\f133',
        'fa-fire-extinguisher'                   => '\f134',
        'fa-rocket'                              => '\f135',
        'fa-maxcdn'                              => '\f136',
        'fa-chevron-circle-left'                 => '\f137',
        'fa-chevron-circle-right'                => '\f138',
        'fa-chevron-circle-up'                   => '\f139',
        'fa-chevron-circle-down'                 => '\f13a',
        'fa-html5'                               => '\f13b',
        'fa-css3'                                => '\f13c',
        'fa-anchor'                              => '\f13d',
        'fa-unlock-alt'                          => '\f13e',
        'fa-bullseye'                            => '\f140',
        'fa-ellipsis-h'                          => '\f141',
        'fa-ellipsis-v'                          => '\f142',
        'fa-rss-square'                          => '\f143',
        'fa-play-circle'                         => '\f144',
        'fa-ticket'                              => '\f145',
        'fa-minus-square'                        => '\f146',
        'fa-minus-square-o'                      => '\f147',
        'fa-level-up'                            => '\f148',
        'fa-level-down'                          => '\f149',
        'fa-check-square'                        => '\f14a',
        'fa-pencil-square'                       => '\f14b',
        'fa-external-link-square'                => '\f14c',
        'fa-share-square'                        => '\f14d',
        'fa-compass'                             => '\f14e',
        'fa-caret-square-o-down'                 => '\f150',
        'fa-caret-square-o-up'                   => '\f151',
        'fa-caret-square-o-right'                => '\f152',
        'fa-eur'                                 => '\f153',
        'fa-gbp'                                 => '\f154',
        'fa-usd'                                 => '\f155',
        'fa-inr'                                 => '\f156',
        'fa-jpy'                                 => '\f157',
        'fa-rub'                                 => '\f158',
        'fa-krw'                                 => '\f159',
        'fa-btc'                                 => '\f15a',
        'fa-file'                                => '\f15b',
        'fa-file-text'                           => '\f15c',
        'fa-sort-alpha-asc'                      => '\f15d',
        'fa-sort-alpha-desc'                     => '\f15e',
        'fa-sort-amount-asc'                     => '\f160',
        'fa-sort-amount-desc'                    => '\f161',
        'fa-sort-numeric-asc'                    => '\f162',
        'fa-sort-numeric-desc'                   => '\f163',
        'fa-thumbs-up'                           => '\f164',
        'fa-thumbs-down'                         => '\f165',
        'fa-youtube-square'                      => '\f166',
        'fa-youtube'                             => '\f167',
        'fa-xing'                                => '\f168',
        'fa-xing-square'                         => '\f169',
        'fa-youtube-play'                        => '\f16a',
        'fa-dropbox'                             => '\f16b',
        'fa-stack-overflow'                      => '\f16c',
        'fa-instagram'                           => '\f16d',
        'fa-flickr'                              => '\f16e',
        'fa-adn'                                 => '\f170',
        'fa-bitbucket'                           => '\f171',
        'fa-bitbucket-square'                    => '\f172',
        'fa-tumblr'                              => '\f173',
        'fa-tumblr-square'                       => '\f174',
        'fa-long-arrow-down'                     => '\f175',
        'fa-long-arrow-up'                       => '\f176',
        'fa-long-arrow-left'                     => '\f177',
        'fa-long-arrow-right'                    => '\f178',
        'fa-apple'                               => '\f179',
        'fa-windows'                             => '\f17a',
        'fa-android'                             => '\f17b',
        'fa-linux'                               => '\f17c',
        'fa-dribbble'                            => '\f17d',
        'fa-skype'                               => '\f17e',
        'fa-foursquare'                          => '\f180',
        'fa-trello'                              => '\f181',
        'fa-female'                              => '\f182',
        'fa-male'                                => '\f183',
        'fa-gratipay'                            => '\f184',
        'fa-sun-o'                               => '\f185',
        'fa-moon-o'                              => '\f186',
        'fa-archive'                             => '\f187',
        'fa-bug'                                 => '\f188',
        'fa-vk'                                  => '\f189',
        'fa-weibo'                               => '\f18a',
        'fa-renren'                              => '\f18b',
        'fa-pagelines'                           => '\f18c',
        'fa-stack-exchange'                      => '\f18d',
        'fa-arrow-circle-o-right'                => '\f18e',
        'fa-arrow-circle-o-left'                 => '\f190',
        'fa-caret-square-o-left'                 => '\f191',
        'fa-dot-circle-o'                        => '\f192',
        'fa-wheelchair'                          => '\f193',
        'fa-vimeo-square'                        => '\f194',
        'fa-try'                                 => '\f195',
        'fa-plus-square-o'                       => '\f196',
        'fa-space-shuttle'                       => '\f197',
        'fa-slack'                               => '\f198',
        'fa-envelope-square'                     => '\f199',
        'fa-wordpress'                           => '\f19a',
        'fa-openid'                              => '\f19b',
        'fa-university'                          => '\f19c',
        'fa-graduation-cap'                      => '\f19d',
        'fa-yahoo'                               => '\f19e',
        'fa-google'                              => '\f1a0',
        'fa-reddit'                              => '\f1a1',
        'fa-reddit-square'                       => '\f1a2',
        'fa-stumbleupon-circle'                  => '\f1a3',
        'fa-stumbleupon'                         => '\f1a4',
        'fa-delicious'                           => '\f1a5',
        'fa-digg'                                => '\f1a6',
        'fa-pied-piper-pp'                       => '\f1a7',
        'fa-pied-piper-alt'                      => '\f1a8',
        'fa-drupal'                              => '\f1a9',
        'fa-joomla'                              => '\f1aa',
        'fa-language'                            => '\f1ab',
        'fa-fax'                                 => '\f1ac',
        'fa-building'                            => '\f1ad',
        'fa-child'                               => '\f1ae',
        'fa-paw'                                 => '\f1b0',
        'fa-spoon'                               => '\f1b1',
        'fa-cube'                                => '\f1b2',
        'fa-cubes'                               => '\f1b3',
        'fa-behance'                             => '\f1b4',
        'fa-behance-square'                      => '\f1b5',
        'fa-steam'                               => '\f1b6',
        'fa-steam-square'                        => '\f1b7',
        'fa-recycle'                             => '\f1b8',
        'fa-car'                                 => '\f1b9',
        'fa-taxi'                                => '\f1ba',
        'fa-tree'                                => '\f1bb',
        'fa-spotify'                             => '\f1bc',
        'fa-deviantart'                          => '\f1bd',
        'fa-soundcloud'                          => '\f1be',
        'fa-database'                            => '\f1c0',
        'fa-file-pdf-o'                          => '\f1c1',
        'fa-file-word-o'                         => '\f1c2',
        'fa-file-excel-o'                        => '\f1c3',
        'fa-file-powerpoint-o'                   => '\f1c4',
        'fa-file-image-o'                        => '\f1c5',
        'fa-file-archive-o'                      => '\f1c6',
        'fa-file-audio-o'                        => '\f1c7',
        'fa-file-video-o'                        => '\f1c8',
        'fa-file-code-o'                         => '\f1c9',
        'fa-vine'                                => '\f1ca',
        'fa-codepen'                             => '\f1cb',
        'fa-jsfiddle'                            => '\f1cc',
        'fa-life-ring'                           => '\f1cd',
        'fa-circle-o-notch'                      => '\f1ce',
        'fa-rebel'                               => '\f1d0',
        'fa-empire'                              => '\f1d1',
        'fa-git-square'                          => '\f1d2',
        'fa-git'                                 => '\f1d3',
        'fa-hacker-news'                         => '\f1d4',
        'fa-tencent-weibo'                       => '\f1d5',
        'fa-qq'                                  => '\f1d6',
        'fa-weixin'                              => '\f1d7',
        'fa-paper-plane'                         => '\f1d8',
        'fa-paper-plane-o'                       => '\f1d9',
        'fa-history'                             => '\f1da',
        'fa-circle-thin'                         => '\f1db',
        'fa-header'                              => '\f1dc',
        'fa-paragraph'                           => '\f1dd',
        'fa-sliders'                             => '\f1de',
        'fa-share-alt'                           => '\f1e0',
        'fa-share-alt-square'                    => '\f1e1',
        'fa-bomb'                                => '\f1e2',
        'fa-futbol-o'                            => '\f1e3',
        'fa-tty'                                 => '\f1e4',
        'fa-binoculars'                          => '\f1e5',
        'fa-plug'                                => '\f1e6',
        'fa-slideshare'                          => '\f1e7',
        'fa-twitch'                              => '\f1e8',
        'fa-yelp'                                => '\f1e9',
        'fa-newspaper-o'                         => '\f1ea',
        'fa-wifi'                                => '\f1eb',
        'fa-calculator'                          => '\f1ec',
        'fa-paypal'                              => '\f1ed',
        'fa-google-wallet'                       => '\f1ee',
        'fa-cc-visa'                             => '\f1f0',
        'fa-cc-mastercard'                       => '\f1f1',
        'fa-cc-discover'                         => '\f1f2',
        'fa-cc-amex'                             => '\f1f3',
        'fa-cc-paypal'                           => '\f1f4',
        'fa-cc-stripe'                           => '\f1f5',
        'fa-bell-slash'                          => '\f1f6',
        'fa-bell-slash-o'                        => '\f1f7',
        'fa-trash'                               => '\f1f8',
        'fa-copyright'                           => '\f1f9',
        'fa-at'                                  => '\f1fa',
        'fa-eyedropper'                          => '\f1fb',
        'fa-paint-brush'                         => '\f1fc',
        'fa-birthday-cake'                       => '\f1fd',
        'fa-area-chart'                          => '\f1fe',
        'fa-pie-chart'                           => '\f200',
        'fa-line-chart'                          => '\f201',
        'fa-lastfm'                              => '\f202',
        'fa-lastfm-square'                       => '\f203',
        'fa-toggle-off'                          => '\f204',
        'fa-toggle-on'                           => '\f205',
        'fa-bicycle'                             => '\f206',
        'fa-bus'                                 => '\f207',
        'fa-ioxhost'                             => '\f208',
        'fa-angellist'                           => '\f209',
        'fa-cc'                                  => '\f20a',
        'fa-ils'                                 => '\f20b',
        'fa-meanpath'                            => '\f20c',
        'fa-buysellads'                          => '\f20d',
        'fa-connectdevelop'                      => '\f20e',
        'fa-dashcube'                            => '\f210',
        'fa-forumbee'                            => '\f211',
        'fa-leanpub'                             => '\f212',
        'fa-sellsy'                              => '\f213',
        'fa-shirtsinbulk'                        => '\f214',
        'fa-simplybuilt'                         => '\f215',
        'fa-skyatlas'                            => '\f216',
        'fa-cart-plus'                           => '\f217',
        'fa-cart-arrow-down'                     => '\f218',
        'fa-diamond'                             => '\f219',
        'fa-ship'                                => '\f21a',
        'fa-user-secret'                         => '\f21b',
        'fa-motorcycle'                          => '\f21c',
        'fa-street-view'                         => '\f21d',
        'fa-heartbeat'                           => '\f21e',
        'fa-venus'                               => '\f221',
        'fa-mars'                                => '\f222',
        'fa-mercury'                             => '\f223',
        'fa-transgender'                         => '\f224',
        'fa-transgender-alt'                     => '\f225',
        'fa-venus-double'                        => '\f226',
        'fa-mars-double'                         => '\f227',
        'fa-venus-mars'                          => '\f228',
        'fa-mars-stroke'                         => '\f229',
        'fa-mars-stroke-v'                       => '\f22a',
        'fa-mars-stroke-h'                       => '\f22b',
        'fa-neuter'                              => '\f22c',
        'fa-genderless'                          => '\f22d',
        'fa-facebook-official'                   => '\f230',
        'fa-pinterest-p'                         => '\f231',
        'fa-whatsapp'                            => '\f232',
        'fa-server'                              => '\f233',
        'fa-user-plus'                           => '\f234',
        'fa-user-times'                          => '\f235',
        'fa-bed'                                 => '\f236',
        'fa-viacoin'                             => '\f237',
        'fa-train'                               => '\f238',
        'fa-subway'                              => '\f239',
        'fa-medium'                              => '\f23a',
        'fa-y-combinator'                        => '\f23b',
        'fa-optin-monster'                       => '\f23c',
        'fa-opencart'                            => '\f23d',
        'fa-expeditedssl'                        => '\f23e',
        'fa-battery-full'                        => '\f240',
        'fa-battery-three-quarters'              => '\f241',
        'fa-battery-half'                        => '\f242',
        'fa-battery-quarter'                     => '\f243',
        'fa-battery-empty'                       => '\f244',
        'fa-mouse-pointer'                       => '\f245',
        'fa-i-cursor'                            => '\f246',
        'fa-object-group'                        => '\f247',
        'fa-object-ungroup'                      => '\f248',
        'fa-sticky-note'                         => '\f249',
        'fa-sticky-note-o'                       => '\f24a',
        'fa-cc-jcb'                              => '\f24b',
        'fa-cc-diners-club'                      => '\f24c',
        'fa-clone'                               => '\f24d',
        'fa-balance-scale'                       => '\f24e',
        'fa-hourglass-o'                         => '\f250',
        'fa-hourglass-start'                     => '\f251',
        'fa-hourglass-half'                      => '\f252',
        'fa-hourglass-end'                       => '\f253',
        'fa-hourglass'                           => '\f254',
        'fa-hand-rock-o'                         => '\f255',
        'fa-hand-paper-o'                        => '\f256',
        'fa-hand-scissors-o'                     => '\f257',
        'fa-hand-lizard-o'                       => '\f258',
        'fa-hand-spock-o'                        => '\f259',
        'fa-hand-pointer-o'                      => '\f25a',
        'fa-hand-peace-o'                        => '\f25b',
        'fa-trademark'                           => '\f25c',
        'fa-registered'                          => '\f25d',
        'fa-creative-commons'                    => '\f25e',
        'fa-gg'                                  => '\f260',
        'fa-gg-circle'                           => '\f261',
        'fa-tripadvisor'                         => '\f262',
        'fa-odnoklassniki'                       => '\f263',
        'fa-odnoklassniki-square'                => '\f264',
        'fa-get-pocket'                          => '\f265',
        'fa-wikipedia-w'                         => '\f266',
        'fa-safari'                              => '\f267',
        'fa-chrome'                              => '\f268',
        'fa-firefox'                             => '\f269',
        'fa-opera'                               => '\f26a',
        'fa-internet-explorer'                   => '\f26b',
        'fa-television'                          => '\f26c',
        'fa-contao'                              => '\f26d',
        'fa-500px'                               => '\f26e',
        'fa-amazon'                              => '\f270',
        'fa-calendar-plus-o'                     => '\f271',
        'fa-calendar-minus-o'                    => '\f272',
        'fa-calendar-times-o'                    => '\f273',
        'fa-calendar-check-o'                    => '\f274',
        'fa-industry'                            => '\f275',
        'fa-map-pin'                             => '\f276',
        'fa-map-signs'                           => '\f277',
        'fa-map-o'                               => '\f278',
        'fa-map'                                 => '\f279',
        'fa-commenting'                          => '\f27a',
        'fa-commenting-o'                        => '\f27b',
        'fa-houzz'                               => '\f27c',
        'fa-vimeo'                               => '\f27d',
        'fa-black-tie'                           => '\f27e',
        'fa-fonticons'                           => '\f280',
        'fa-reddit-alien'                        => '\f281',
        'fa-edge'                                => '\f282',
        'fa-credit-card-alt'                     => '\f283',
        'fa-codiepie'                            => '\f284',
        'fa-modx'                                => '\f285',
        'fa-fort-awesome'                        => '\f286',
        'fa-usb'                                 => '\f287',
        'fa-product-hunt'                        => '\f288',
        'fa-mixcloud'                            => '\f289',
        'fa-scribd'                              => '\f28a',
        'fa-pause-circle'                        => '\f28b',
        'fa-pause-circle-o'                      => '\f28c',
        'fa-stop-circle'                         => '\f28d',
        'fa-stop-circle-o'                       => '\f28e',
        'fa-shopping-bag'                        => '\f290',
        'fa-shopping-basket'                     => '\f291',
        'fa-hashtag'                             => '\f292',
        'fa-bluetooth'                           => '\f293',
        'fa-bluetooth-b'                         => '\f294',
        'fa-percent'                             => '\f295',
        'fa-gitlab'                              => '\f296',
        'fa-wpbeginner'                          => '\f297',
        'fa-wpforms'                             => '\f298',
        'fa-envira'                              => '\f299',
        'fa-universal-access'                    => '\f29a',
        'fa-wheelchair-alt'                      => '\f29b',
        'fa-question-circle-o'                   => '\f29c',
        'fa-blind'                               => '\f29d',
        'fa-audio-description'                   => '\f29e',
        'fa-volume-control-phone'                => '\f2a0',
        'fa-braille'                             => '\f2a1',
        'fa-assistive-listening-systems'         => '\f2a2',
        'fa-american-sign-language-interpreting' => '\f2a3',
        'fa-deaf'                                => '\f2a4',
        'fa-glide'                               => '\f2a5',
        'fa-glide-g'                             => '\f2a6',
        'fa-sign-language'                       => '\f2a7',
        'fa-low-vision'                          => '\f2a8',
        'fa-viadeo'                              => '\f2a9',
        'fa-viadeo-square'                       => '\f2aa',
        'fa-snapchat'                            => '\f2ab',
        'fa-snapchat-ghost'                      => '\f2ac',
        'fa-snapchat-square'                     => '\f2ad',
        'fa-pied-piper'                          => '\f2ae',
        'fa-first-order'                         => '\f2b0',
        'fa-yoast'                               => '\f2b1',
        'fa-themeisle'                           => '\f2b2',
        'fa-google-plus-official'                => '\f2b3',
        'fa-font-awesome'                        => '\f2b4',
        'fa-handshake-o'                         => '\f2b5',
        'fa-envelope-open'                       => '\f2b6',
        'fa-envelope-open-o'                     => '\f2b7',
        'fa-linode'                              => '\f2b8',
        'fa-address-book'                        => '\f2b9',
        'fa-address-book-o'                      => '\f2ba',
        'fa-address-card'                        => '\f2bb',
        'fa-address-card-o'                      => '\f2bc',
        'fa-user-circle'                         => '\f2bd',
        'fa-user-circle-o'                       => '\f2be',
        'fa-user-o'                              => '\f2c0',
        'fa-id-badge'                            => '\f2c1',
        'fa-id-card'                             => '\f2c2',
        'fa-id-card-o'                           => '\f2c3',
        'fa-quora'                               => '\f2c4',
        'fa-free-code-camp'                      => '\f2c5',
        'fa-telegram'                            => '\f2c6',
        'fa-thermometer-full'                    => '\f2c7',
        'fa-thermometer-three-quarters'          => '\f2c8',
        'fa-thermometer-half'                    => '\f2c9',
        'fa-thermometer-quarter'                 => '\f2ca',
        'fa-thermometer-empty'                   => '\f2cb',
        'fa-shower'                              => '\f2cc',
        'fa-bath'                                => '\f2cd',
        'fa-podcast'                             => '\f2ce',
        'fa-window-maximize'                     => '\f2d0',
        'fa-window-minimize'                     => '\f2d1',
        'fa-window-restore'                      => '\f2d2',
        'fa-window-close'                        => '\f2d3',
        'fa-window-close-o'                      => '\f2d4',
        'fa-bandcamp'                            => '\f2d5',
        'fa-grav'                                => '\f2d6',
        'fa-etsy'                                => '\f2d7',
        'fa-imdb'                                => '\f2d8',
        'fa-ravelry'                             => '\f2d9',
        'fa-eercast'                             => '\f2da',
        'fa-microchip'                           => '\f2db',
        'fa-snowflake-o'                         => '\f2dc',
        'fa-superpowers'                         => '\f2dd',
        'fa-wpexplorer'                          => '\f2de',
        'fa-meetup'                              => '\f2e0',
    );

    return $fa_icons;
}

/**
 * HELPER: Check if product exists in cart by id
 *
 * @since 1.5.4
 */

function gb_hcc_is_product_in_cart( $product_id = 0 )
{
    global $woocommerce;

    if( $woocommerce->cart->cart_contents_count > 0 )
    {
        foreach( $woocommerce->cart->get_cart() as $p )
        {
            $_product = $p['data'];

            if( $product_id == $_product->get_id() )
            {
                return TRUE;
            }
        }
    }

    return FALSE;
}

/**
 * HELPER: Simple logging function, useful to debug
 *
 * @since 1.0.0
 */

function gb_hcc_log( $data = array(), $file = '' )
{
    if( empty( $file ) )
    {
        $file = 'gb_hcc.log';
    }

    if( !empty( $data ) )
    {
        $data['_time'] = date( 'H:i:s d.m.y' );

        return @file_put_contents( dirname( __FILE__ ) . '/' . $file, json_encode( $data ) . PHP_EOL . PHP_EOL, FILE_APPEND );
    }
}

?>
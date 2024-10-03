jQuery(document).ready(function($) {

    $('body').trigger('update_checkout');

    $('#shipping_method').find('input[type="radio"]').each(function()
    {
        $(this).addClass('input-radio');
    });

    $(document).on('click', '.woocommerce-remove-coupon', function(e)
    {
        e.preventDefault();

        $('.total-line--coupon').remove();
    });

    var prodCount = $('.order-summary__section__content > table').length,
        prodHeight = $('.product-table').innerHeight();

    if( $(window).width() > 999 )
    {
        if( prodCount > 5 )
        {
            $('.order-summary__scroll-indicator').css('opacity', '1');
        }

        $( '.order-summary__section__content' ).scroll( function()
        {
            $('.order-summary__scroll-indicator').css('opacity', '0');
        });
    }

    $('.woocommerce-checkout-payment').prepend("<span class='transaction-desc'>" + hcc_localize.transactions_subheading + "</span>");

    $('.woocommerce-checkout-payment').prepend("<h2 style='margin-left:4px;margin-top:-5px;'>" + hcc_localize.transactions_heading + "</h2>");

    if( prodCount > 5 )
    {
        $('.order-summary__section--product-list').css({
            height: 64 * 6.6 + "px",
            minHeight: "auto",
            overflowY: "unset"
        });

        if( $(window).width() <= 999 )
        {
            $('.order-summary__section--product-list').css({ height: "auto" });
        }

    }
    else if( prodCount <= 5 )
    {
        $('.order-summary__section--product-list').css({ height: "auto", minHeight: "max-content" });
    }

    $(window).resize(function()
    {
        if( prodCount > 5 )
        {
            if( $(window).width() > 999 )
            {
                $('.order-summary__scroll-indicator').css('opacity', '1');
            }

            $('.order-summary__section--product-list').css({
                height: 64 * 6.6 + "px",
                minHeight: "auto",
                overflowY: "unset"
            });

            if( $(window).width() <= 999 )
            {
                $('.order-summary__section--product-list').css({ height: "auto" });
            }
            else if( $(window).width() > 999 )
            {
                $('.order-summary__section--product-list').css({
                    height: 64 * 6.6 + "px",
                    minHeight: "auto",
                    overflowY: "unset"
                });
            }
        }
    });

    $(document).ajaxSuccess(function()
    {
        if( $(window).width() > 999 )
        {
            if( prodCount > 5 )
            {
                $('.order-summary__scroll-indicator').css('opacity', '1');
            }

            $( '.order-summary__section__content' ).scroll( function()
            {
                $('.order-summary__scroll-indicator').css('opacity', '0');
            });
        }

        if( $('.woocommerce-checkout-payment-title').length === 0 )
        {
            $('.woocommerce-checkout-payment').prepend("<span class='transaction-desc'>" + hcc_localize.transactions_subheading + "</span>");

            $('.woocommerce-checkout-payment').prepend("<h2 class='woocommerce-checkout-payment-title'>" + hcc_localize.transactions_heading + "</h2>");
        }
    });

    $('input[name="login"]').addClass( 'btn' );

    var step1 = $('.woocommerce-billing-fields__field-wrapper, .woocommerce-checkout-subtitle , .woocommerce-billing-fields > h3');

    var step2 = $('.wc-hcc-vq-options');
    var step3 = $('#order_review_heading, #order_review, #order_review_heading + .woocommerce-checkout-subtitle'); //#step2_details, .woocommerce-checkout > .woocommerce-checkout-subtitle'

    var btn1 = $('.btn-step1');
    var btn2 = $('.btn-step2');

    if( !jQuery('body').hasClass('woocommerce-order-pay') )
    {
        // init step 1

        jQuery('body').addClass('hcc-t6-step-1');

        step2.hide();
        step3.hide();
        btn2.hide();

        $('.link-back1').hide();
        $('.link-back2').hide();

        $('#customer_details .woocommerce-checkout-subtitle').show();

        $('#step2_details .woocommerce-billing-fields').find('h3, .woocommerce-checkout-subtitle').hide();

        $('.col-2').hide();
    }

    // show step 2

    btn1.on('click', function(){

        if( requiredFieldsFilled() == false )
        {
            return false;
        }

        if( typeof extendHCC === 'object' && typeof extendHCC.validate === 'function' )
        {
            if( extendHCC.validate() == false )
            {
                return false;
            }
        }

        $('#order_review').prepend( $('.col-2') );

        $('.col-2').show();
        $('.woocommerce-form-login').hide();

        btn1.hide();

        if( jQuery('*').is('.step2') )
        {
            jQuery('body')
                .removeClass('hcc-t6-step-1')
                .addClass('hcc-t6-step-2')
                .removeClass('hcc-t6-step-3');

            $('.link-back1').show();

            btn2.show();

            step1.slideUp();

            step2.slideDown();

            $('.steps .step2').addClass('.step3 breadcrumb__item breadcrumb__item--current');

            $('.steps .step1').removeClass('breadcrumb__item--current');
            $('.steps .step3').removeClass('breadcrumb__item--current');

            $('.steps .step2 .badge').addClass('badge-info');

            $('html, body').animate(
                {
                    scrollTop: $('.steps').offset().top
                },
                'slow'
            );
        }
        else
        {
            jQuery('body')
                .removeClass('hcc-t6-step-1')
                .removeClass('hcc-t6-step-2')
                .addClass('hcc-t6-step-3');
            
            $('.link-back2').show();

            $('.link-back2').text( hcc_localize.back_to_step2 );

            step1.slideUp();

            step3.slideDown();

            $('.steps .step3').addClass('.step3 breadcrumb__item breadcrumb__item--current');

            $('.steps .step1').removeClass('breadcrumb__item--current');
            $('.steps .step2').removeClass('breadcrumb__item--current');

            $('.steps .step3 .badge').addClass('badge-info');

            $('html, body').animate(
                {
                    scrollTop: $('.steps').offset().top
                },
                'slow'
            );
        }
    });

    btn2.on('click', function() {

        jQuery('body')
            .removeClass('hcc-t6-step-1')
            .removeClass('hcc-t6-step-2')
            .addClass('hcc-t6-step-3');

        $('.link-back2').show();

        btn1.hide();

        btn2.hide();

        $('.woocommerce-form-login').hide();

        step1.slideUp();

        step2.slideUp();

        step3.slideDown();

        $('.steps .step3').addClass('.step3 breadcrumb__item breadcrumb__item--current');

        $('.steps .step1').removeClass('breadcrumb__item--current');
        $('.steps .step2').removeClass('breadcrumb__item--current');
        $('.steps .step3 .badge').addClass('badge-info');

        $('html, body').animate(
            {
                scrollTop: $('.steps').offset().top
            },
            'slow'
        );

        $('#order_review').prepend( $('.col-2') );

        $('.col-2').show();

        $('.link-back1').hide();
    });

    // show step 1

    $('.steps .step1, .link-back1').on('click', function(e) { 

        jQuery('body')
            .addClass('hcc-t6-step-1')
            .removeClass('hcc-t6-step-2')
            .removeClass('hcc-t6-step-3');

        $('.link-back2').hide();
        $('.link-back1').hide();

        e.preventDefault();

        if( !jQuery('*').is('.step2') )
        {
            $('.link-back2').hide();
            $('.link-back1').hide();
        }

        $('.steps .step1').addClass('.step3 breadcrumb__item breadcrumb__item--current');

        $('.steps .step3').removeClass('breadcrumb__item--current');
        $('.steps .step2').removeClass('breadcrumb__item--current');

        step1.slideDown();

        step2.slideUp();
        step3.slideUp();

        $('html, body').animate(
            {
                scrollTop: $('.steps').offset().top
            },
            'slow'
        );

        btn1.show();
        btn2.hide();

    });

    $('.steps .step2, .link-back2').on('click', function(e)
    {
        e.preventDefault();

        if( jQuery('*').is('.step2') )
        {
            jQuery('body')
                .removeClass('hcc-t6-step-1')
                .addClass('hcc-t6-step-2')
                .removeClass('hcc-t6-step-3');

            $('.steps .step2').addClass('.step3 breadcrumb__item breadcrumb__item--current');

            $('.steps .step3').removeClass('breadcrumb__item--current');
            $('.steps .step1').removeClass('breadcrumb__item--current');

            step1.slideUp();

            step2.slideDown();

            step3.slideUp();

            $('html, body').animate(
                {
                    scrollTop: $('.steps').offset().top
                },
                'slow'
            );

            btn2.show();

            btn1.hide();

            $('.link-back1').show();

            $('.link-back2').hide();
        }
        else
        {
            jQuery('body')
                .addClass('hcc-t6-step-1')
                .removeClass('hcc-t6-step-2')
                .removeClass('hcc-t6-step-3');

            $('.steps .step1').addClass('.step3 breadcrumb__item breadcrumb__item--current');

            $('.steps .step3').removeClass('breadcrumb__item--current');
            $('.steps .step2').removeClass('breadcrumb__item--current');

            step2.slideUp();

            step1.slideDown();

            step3.slideUp();

            $('html, body').animate(
                {
                    scrollTop: $('.steps').offset().top
                },
                'slow'
            );

            btn2.hide();

            btn1.show();

            $('.link-back1').hide();

            $('.link-back2').hide();
        }
    });

    if( $('#wpadminbar').length > 0 && $(window).width() <= 600 )
    {
        $('body').css("padding-top", "4vh");

        $('#hcc-order-summary-toggle').css("margin-top", "25px");
    }
    else
    {
        $('#hcc-order-summary-toggle').css("margin-top", "0");

        $('body').css("padding-top", "0vh");
    }

    if( $(window).width() > 999 )
    {
        $('.order-summary--is-collapsed').removeAttr('style');
    }

    $('#hcc-order-summary-toggle').on( 'click', function()
    {
        if( ! $('.order-summary--is-collapsed').is(':visible') )
        {
            $('.order-summary--is-collapsed').slideDown( 500 );

            $('.hcc-order-summary-toggle__text--show span').text( hcc_localize.hide_order_summary );

            $(this).find('i').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        }
        else
        {
            $('.order-summary--is-collapsed').slideUp( 500 );

            $('.hcc-order-summary-toggle__text--show span').text( hcc_localize.show_order_summary );

            $(this).find('i').toggleClass('fa fa-chevron-up fa fa-chevron-down');
        }
    });

    $(window).resize(function()
    {
        if( $('#wpadminbar').length > 0 && $(window).width() <= 600 )
        {
            $('body').css("padding-top", "4vh");

            $('#hcc-order-summary-toggle').css("margin-top", "25px");
        }
        else
        {
            $('#hcc-order-summary-toggle').css("margin-top", "0");

            $('body').css("padding-top", "0vh");
        }

        if( $(window).width() > 999 )
        {
            $('.order-summary--is-collapsed').removeAttr('style');
        }
    });

    if( $(window).width() <= 999 )
    {
        $('#sidebar-content').removeClass('row').appendTo('.main__content');
    }
    else
    {
        $('#sidebar-content').appendTo('.sidebar');
    }

    $(window).resize(function()
    {
        if ($(window).width() <= 999)
        {
            $('#sidebar-content').removeClass('row').appendTo('.main__content');
        }
        else
        {
            $('#sidebar-content').appendTo('.sidebar');
        }
    });

    $('.woocommerce-billing-fields__field-wrapper .form-row label').each(function()
    {
        $(this).clone().insertAfter( $(this).next() );

        $(this).remove();
    });

    $('.woocommerce-shipping-fields__field-wrapper .form-row label').each(function()
    {
        $(this).clone().insertAfter( $(this).next() );

        $(this).remove();
    });

    $('.create-account .form-row label').each(function()
    {
        $(this).clone().insertAfter( $(this).next() );

        $(this).remove();
    });

    $('.login .form-row label:not(.inline)').each(function()
    {
        $(this).clone().insertAfter( $(this).next() );

        $(this).remove();
    });

    $('#account_password').keypress(function()
    {
        $('#account_password_field label').insertAfter( $(this) );

        if( $('#account_password').val().length >= 0 )
        {
            $('.woocommerce-password-strength').insertAfter( $('#account_password_field label') );
        }
    });

    $('input[autofocus="autofocus"]').focus();

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
                        if( 
                            $row.find('input').attr('name') === 'account_username' ||
                            $row.find('input').attr('name') === 'account_password' 
                        )
                        {
                            if( jQuery('#createaccount').is(':checked') )
                            {
                                result = false;

                                $row.addClass('form-row-error');
                            }
                            else
                            {
                                // #createaccount unchecked
                            }
                        }
                        else
                        {
                            result = false;

                            $row.addClass('form-row-error');
                        }
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
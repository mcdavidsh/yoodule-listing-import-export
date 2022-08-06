<?php

/*
Plugin Name: Yoodule Listing Import Export
Plugin URI: https://github.com/mcdavidsh/yd-listing-import-export
Description: A brief description of the Plugin.
Version: 1.0
Author: Mcdavid Obioha
Author URI: https://github.com/mcdavidsh
License: A "Slug" license name e.g. GPL2
*/

include plugin_dir_path( __FILE__ ) . 'includes/menu.php';
include plugin_dir_path( __FILE__ ) . 'includes/page-settings.php';

if ( !class_exists( 'YooduleListImportExport' ) ) {
	define( 'YD_PLUGIN_FILE', __FILE__ );
	require( plugin_dir_path( __FILE__ ) . 'functions.php' );
}

if ( !function_exists( "htb_scripts" ) ) {
	add_action( "wp_enqueue_scripts", "htb_scripts" );
	function htb_scripts() {
		wp_enqueue_style( "htb_css", plugins_url( "assets/css/listing.css", __FILE__ ) );
		wp_enqueue_style( "htb_css_detail", plugins_url( "assets/css/details.css", __FILE__ ) );
        wp_enqueue_script( 'htb_js_script', plugin_dir_url(__FILE__). 'assets/js/main.js', array(), '1.0', true );
	}
}

if ( !function_exists( "htb_admin_scripts" ) ) {

    function htb_admin_scripts(){
        wp_enqueue_style("htb_admin_style", plugins_url("assets/css/admin-style.css", __FILE__));
    }
    add_action("admin_enqueue_scripts","htb_admin_scripts");
}



function test_form(){
    ob_start();
    ?>
    <style>
        /*! CSS Used from: Embedded */
        h3{--fontWeight:700;--fontSize:30px;--lineHeight:1.5;}
        h4{--fontWeight:700;--fontSize:25px;--lineHeight:1.5;}
        form textarea{--formInputHeight:170px;}
        /*! CSS Used from: https://luton.ae/wp-content/plugins/mhb-style-addon/assets/css/listing.css?ver=5.9.3 ; media=all */
        @media all{
            *{box-sizing:border-box;}
        }
        /*! CSS Used from: https://luton.ae/wp-content/plugins/mhb-style-addon/assets/css/details.css?ver=5.9.3 ; media=all */
        @media all{
            *{box-sizing:border-box;}
        }
        /*! CSS Used from: https://luton.ae/wp-content/plugins/motopress-hotel-booking/assets/css/mphb.min.css?ver=3.8.7 ; media=all */
        @media all{
            .mphb-hide{display:none!important;}
            .mphb-preloader{background-image:url(https://luton.ae/wp-content/plugins/motopress-hotel-booking/images/loading.gif?ver=3.8.7);width:20px;height:20px;display:inline-block;}
            .mphb_sc_checkout-form>.mphb-checkout-section:not(:first-of-type){margin-top:4em;}
            .mphb_sc_checkout-form .mphb_checkout-service-quantity{width:100px;display:inline-block;}
            .mphb_sc_checkout-form .mphb-price-breakdown .mphb-price-breakdown-expand>.mphb-inner-icon{font-family:sans-serif;font-size:1em;margin-right:.75em;border:1px solid currentColor;width:1em;height:1em;line-height:1em;display:inline-block;text-align:center;box-sizing:content-box;pointer-events:none;}
            .mphb_sc_checkout-form .mphb-price-breakdown .mphb-table-price-column{width:33%;}
            .mphb_sc_checkout-form .mphb-gateways-list{list-style:none;}
            .mphb_sc_checkout-form .mphb-gateways-list>li{margin-top:1em;}
            .mphb_sc_checkout-form .mphb-billing-fields{margin-bottom:1em;border:none;}
            .mphb_sc_checkout-form .mphb-billing-fields-hidden{display:none;}
            .mphb_sc_checkout-form .mphb-terms-and-conditions{margin-bottom:0;padding-left:2em;padding-right:2em;max-height:0;overflow:hidden;transition:margin-bottom .2s linear,padding .2s linear,max-height .4s linear;background:rgba(0,0,0,.05);}
            .mphb_checkout-services-list,.mphb_sc_checkout-services-list{list-style:none;}
        }
        /*! CSS Used from: https://luton.ae/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=3.0.13 ; media=all */
        @media all{
            .elementor *,.elementor :after,.elementor :before{-webkit-box-sizing:border-box;box-sizing:border-box;}
            .elementor a{-webkit-box-shadow:none;box-shadow:none;text-decoration:none;}
            .elementor-element .elementor-widget-container{-webkit-transition:background .3s,border .3s,-webkit-border-radius .3s,-webkit-box-shadow .3s;transition:background .3s,border .3s,-webkit-border-radius .3s,-webkit-box-shadow .3s;-o-transition:background .3s,border .3s,border-radius .3s,box-shadow .3s;transition:background .3s,border .3s,border-radius .3s,box-shadow .3s;transition:background .3s,border .3s,border-radius .3s,box-shadow .3s,-webkit-border-radius .3s,-webkit-box-shadow .3s;}
        }
        /*! CSS Used from: https://luton.ae/wp-content/themes/blocksy/static/bundle/main.css?ver=1.7.51 ; media=all */
        @media all{
            *,*::before,*::after{box-sizing:border-box;}
            p,h3,h4,em,ul,li,tr,th,td,form,small,label,table,button,fieldset{margin:0;padding:0;border:none;font-size:inherit;text-align:inherit;line-height:inherit;}
            input,textarea,select{margin:0;}
            strong{font-weight:bold;}
            em{font-style:italic;}
            small{font-size:80%;}
            abbr{cursor:help;}
            a{transition:var(--transition);}
            a:focus,button:focus{transition:none;outline-offset:3px;outline-color:var(--paletteColor2);}
            ul{list-style-type:var(--listStyleType, disc);}
            table{border-collapse:collapse;border-spacing:0;empty-cells:show;width:100%;max-width:100%;}
            table,th,td{border-width:var(--table-border-width, 1px);border-style:var(--table-border-style, solid);border-color:var(--table-border-color, #e0e5eb);}
            th,td{padding:var(--table-padding, 0.7em 1em);}
            th{font-weight:600;}
            body ::-moz-selection{color:var(--selectionTextColor);background-color:var(--selectionBackgroundColor);}
            body ::selection{color:var(--selectionTextColor);background-color:var(--selectionBackgroundColor);}
            h3,h4{color:var(--headingColor);}
            a{color:var(--linkInitialColor);}
            a:hover{color:var(--linkHoverColor);}
            h3,h4,label{font-family:var(--fontFamily);font-size:var(--fontSize);font-weight:var(--fontWeight);font-style:var(--fontStyle, inherit);line-height:var(--lineHeight);letter-spacing:var(--letterSpacing);text-transform:var(--textTransform);-webkit-text-decoration:var(--textDecoration);text-decoration:var(--textDecoration);}
            p{margin-bottom:var(--contentSpacing);}
            h3,h4{margin-bottom:20px;}
            ul{padding-left:var(--listIndent);margin-bottom:var(--contentSpacing);}
            ul li{margin-bottom:var(--listItemSpacing);}
            ul li:last-child{margin-bottom:0;}
            a{-webkit-text-decoration:var(--textDecoration, none);text-decoration:var(--textDecoration, none);}
            :target:before{content:"";display:block;height:calc(var(--admin-bar, 0px) + var(--frame-size, 0px) + var(--headerStickyHeight, 0px));margin-top:calc(var(--admin-bar, 0px) + var(--frame-size, 0px) + var(--headerStickyHeight, 0px) * -1);}
            select,textarea,input[type='text'],input[type='email'],input[type='number']{width:var(--formWidth, 100%);height:var(--formInputHeight);color:var(--formTextInitialColor);font-family:inherit;font-size:var(--formFontSize);padding:var(--formPadding, 0 13px);-webkit-appearance:none;-moz-appearance:none;appearance:none;border-radius:var(--formBorderRadius, 3px);border:var(--formBorderSize) var(--formBorderStyle, solid) var(--formBorderInitialColor);transition:all 0.12s cubic-bezier(0.455, 0.03, 0.515, 0.955);}
            select:focus,textarea:focus,input[type='text']:focus,input[type='email']:focus,input[type='number']:focus{outline:none;color:var(--formTextFocusColor);border-color:var(--formBorderFocusColor);}
            select:-moz-placeholder,textarea:-moz-placeholder,input[type='text']:-moz-placeholder,input[type='email']:-moz-placeholder,input[type='number']:-moz-placeholder{opacity:0.6;color:inherit;}
            select::-moz-placeholder,textarea::-moz-placeholder,input[type='text']::-moz-placeholder,input[type='email']::-moz-placeholder,input[type='number']::-moz-placeholder{opacity:0.6;color:inherit;}
            select:-ms-input-placeholder,textarea:-ms-input-placeholder,input[type='text']:-ms-input-placeholder,input[type='email']:-ms-input-placeholder,input[type='number']:-ms-input-placeholder{opacity:0.6;color:inherit;}
            select::-webkit-input-placeholder,textarea::-webkit-input-placeholder,input[type='text']::-webkit-input-placeholder,input[type='email']::-webkit-input-placeholder,input[type='number']::-webkit-input-placeholder{opacity:0.6;color:inherit;}
            select{padding-right:30px;background-image:url("data:image/svg+xml,%3Csvg width='21' height='13' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M18.5.379L20.621 2.5 10.5 12.621.379 2.5 2.5.379l8 8z' fill='%234F5D6D' fill-rule='nonzero'/%3E%3C/svg%3E");background-repeat:no-repeat, repeat;background-size:8px auto, 100%;}
            textarea{--formPadding:15px;line-height:1.5;}
            fieldset{padding:30px;border:1px dashed #dce0e6;}
            [data-forms='classic'] select,[data-forms='classic'] textarea,[data-forms='classic'] input[type='text'],[data-forms='classic'] input[type='email'],[data-forms='classic'] input[type='number']{background-color:var(--formBackgroundInitialColor);}
            [data-forms='classic'] select:focus,[data-forms='classic'] textarea:focus,[data-forms='classic'] input[type='text']:focus,[data-forms='classic'] input[type='email']:focus,[data-forms='classic'] input[type='number']:focus{background-color:var(--formBackgroundFocusColor);}
            [data-forms='classic'] textarea{--formPadding:15px;}
            [data-forms='classic'] select{background-position:right 15px top 50%, 0 0;}
            label{--fontSize:15px;--lineHeight:inherit;cursor:pointer;margin:0 0 0.5em 0;}
            label:last-child{margin-bottom:0;}
            .button,input[type="submit"]{display:var(--display, inline-flex);align-items:center;justify-content:center;min-height:var(--buttonMinHeight);padding:var(--padding, 5px 25px);border:none;-webkit-appearance:none;-moz-appearance:none;appearance:none;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;text-align:center;border-radius:var(--buttonBorderRadius);transition:all 0.2s ease;}
            .button:disabled,input[type="submit"]:disabled{opacity:.5;pointer-events:none;}
            @media (max-width: 479.98px){
                .button,input[type="submit"]{--padding:5px 15px;}
            }
            .button,input[type="submit"]{position:relative;z-index:1;color:var(--buttonTextInitialColor);background-color:var(--buttonInitialColor);}
            .button:hover,input[type="submit"]:hover{color:var(--buttonTextHoverColor);background-color:var(--buttonHoverColor);}
            .button,input[type="submit"]{font-family:var(--buttonFontFamily, var(--fontFamily));font-size:var(--buttonFontSize);font-weight:var(--buttonFontWeight);font-style:var(--buttonFontStyle);line-height:var(--buttonLineHeight);letter-spacing:var(--buttonLetterSpacing);text-transform:var(--buttonTextTransform);-webkit-text-decoration:var(--buttonTextDecoration);text-decoration:var(--buttonTextDecoration);}
            [data-elementor-type] p:last-child{--contentSpacing:0px;}
        }
        /*! CSS Used from: Embedded */
        p.mphb--fields-tip{display:none;}
        input.button{background:#a9823b!important;color:black!important;}
        .mphb_sc_checkout-form>.mphb-checkout-section:not(:first-of-type){margin-top:2em;}
        input.button{margin-top:30px;}
    </style>
    <div class="elementor-widget-container">
        <div class="elementor-shortcode"><div class="mphb_sc_checkout-wrapper ">		<form class="mphb_sc_checkout-form" method="POST">

                    <input type="hidden" id="mphb-checkout-nonce" name="mphb-checkout-nonce" value="7e79d0d258"><input type="hidden" name="_wp_http_referer" value="/booking-confirmation/">
                    <input type="hidden" name="mphb-booking-checkout-id" value="683e0d58e439468d81a70ae400065b7c">
                    <input type="hidden" name="mphb_check_in_date" value="2022-08-06">
                    <input type="hidden" name="mphb_check_out_date" value="2022-08-08">
                    <input type="hidden" name="mphb_checkout_step" value="booking">

                    <section id="mphb-booking-details" class="mphb-booking-details mphb-checkout-section">
                        <h3 class="mphb-booking-details-title">
                            Booking Details			</h3>
                        <p class="mphb-check-in-date">
                            <span>Check-in:</span>
                            <time datetime="2022-08-06">
                                <strong>
                                    August 6, 2022				</strong>
                            </time>,
                            <span>
				from			</span>
                            <time datetime="11:00:00">
                                11:00 am			</time>
                        </p>
                        <p class="mphb-check-out-date">
                            <span>Check-out:</span>
                            <time datetime="2022-08-07">
                                <strong>
                                    August 7, 2022				</strong>
                            </time>,
                            <span>
				until			</span>
                            <time datetime="10:00:00">
                                10:00 am			</time>
                        </p>
                        <div class="mphb-reserve-rooms-details">
                            <div class="mphb-room-details" data-index="0">
                                <input type="hidden" name="mphb_room_details[0][room_type_id]" value="315">

                                <h3 class="mphb-room-number">
                                    Accommodation #1		</h3>
                                <p class="mphb-room-type-title">
			<span>
				Accommodation Type:			</span>
                                    <a href="https://luton.ae/accommodation/beach-house/" target="_blank">
                                        Beach House			</a>
                                </p>
                                <p class="mphb-adults-chooser">
                                    <label for="mphb_room_details-0-adults">
                                        Adults                    <abbr title="">*</abbr>
                                    </label>
                                    <select name="mphb_room_details[0][adults]" id="mphb_room_details-0-adults" class="mphb_sc_checkout-guests-chooser mphb_checkout-guests-chooser" ="" data-max-allowed="5" data-max-total="10">
                                        <option value="">— Select —</option>
                                        <option value="1">
                                            1                        </option>
                                        <option value="2">
                                            2                        </option>
                                        <option value="3">
                                            3                        </option>
                                        <option value="4">
                                            4                        </option>
                                        <option value="5">
                                            5                        </option>
                                    </select>
                                </p>

                                <p class="mphb-children-chooser">
                                    <label for="mphb_room_details-0-children">
                                        Children                     <abbr title="">*</abbr>
                                    </label>
                                    <select name="mphb_room_details[0][children]" id="mphb_room_details-0-children" class="mphb_sc_checkout-guests-chooser mphb_checkout-guests-chooser" ="" data-max-allowed="5" data-max-total="10">
                                        <option value="">— Select —</option>
                                        <option value="0">
                                            0                        </option>
                                        <option value="1">
                                            1                        </option>
                                        <option value="2">
                                            2                        </option>
                                        <option value="3">
                                            3                        </option>
                                        <option value="4">
                                            4                        </option>
                                        <option value="5">
                                            5                        </option>
                                    </select>
                                </p>

                                <p class="mphb-guest-name-wrapper">
                                    <label for="mphb_room_details-0-guest-name">
                                        Full Guest Name            </label>
                                    <input type="text" name="mphb_room_details[0][guest_name]" id="mphb_room_details-0-guest-name" value="">
                                </p>
                                <input type="hidden" name="mphb_room_details[0][rate_id]" value="323">
                                <section id="mphb-services-details-0" class="mphb-services-details mphb-checkout-item-section">
                                    <h4 class="mphb-services-details-title">
                                        Choose Additional Services            </h4>

                                    <ul class="mphb_sc_checkout-services-list mphb_checkout-services-list">
                                        <li>
                                            <label for="mphb_room_details-0-service-811-id" class="mphb-checkbox-label">
                                                <input type="checkbox" id="mphb_room_details-0-service-811-id" name="mphb_room_details[0][services][0][id]" class="mphb_sc_checkout-service mphb_checkout-service" value="811">
                                                test                            <em>(<span class="mphb-price"><span class="mphb-currency">د.إ</span>50</span> / Per Instance)</em>
                                            </label>

                                            <input type="hidden" name="mphb_room_details[0][services][0][adults]" value="1">

                                            × <input type="number" name="mphb_room_details[0][services][0][quantity]" class="mphb_sc_checkout-service-quantity mphb_checkout-service-quantity" value="1" min="1" max="1" step="1"> time(s)                                            </li>
                                    </ul>
                                </section>
                            </div>
                        </div>
                    </section>
                    <section id="mphb-coupon-details" class="mphb-coupon-code-wrapper mphb-checkout-section">



                        <p>
                            <label for="mphb_coupon_code" class="mphb-coupon-code-title">
                                Coupon Code:				</label>



                            <input type="hidden" id="mphb_applied_coupon_code" name="mphb_applied_coupon_code">
                            <input type="text" id="mphb_coupon_code" name="mphb_coupon_code">

                        </p>
                        <p>
                            <button class="button btn mphb-apply-coupon-code-button">
                                Apply			</button>

                        </p>
                        <p class="mphb-coupon-message mphb-hide"></p>


                    </section>
                    <section id="mphb-price-details" class="mphb-room-price-breakdown-wrapper mphb-checkout-section">
                        <h4 class="mphb-price-breakdown-title">
                            Price Breakdown			</h4>
                        <table class="mphb-price-breakdown" cellspacing="0">
                            <tbody>
                            <tr class="mphb-price-breakdown-booking mphb-price-breakdown-group">
                                <td colspan="1">
                                    <a href="#" class="mphb-price-breakdown-accommodation mphb-price-breakdown-expand" title="Expand"><span class="mphb-inner-icon ">+</span><span class="mphb-inner-icon mphb-hide">−</span>#1 Beach House</a>									<div class="mphb-price-breakdown-rate mphb-hide">Rate: Beach Houses</div>
                                </td>
                                <td class="mphb-table-price-column"><span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span></td>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-adults">
                                <td colspan="1">Adults</td>
                                <td>5</td>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-children">
                                <td colspan="1">Children</td>
                                <td>5</td>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-nights">
                                <td colspan="1">Nights</td>
                                <td>1</td>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-dates">
                                <th colspan="1">Dates</th>
                                <th class="mphb-table-price-column">Amount</th>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-date">
                                <td colspan="1">August 6, 2022</td>
                                <td class="mphb-table-price-column"><span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span></td>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-dates-subtotal">
                                <th colspan="1">Dates Subtotal</th>
                                <th class="mphb-table-price-column"><span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span></th>
                            </tr>
                            <tr class="mphb-hide mphb-price-breakdown-accommodation-subtotal">
                                <th colspan="1">Accommodation Subtotal</th>
                                <th class="mphb-table-price-column"><span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span></th>
                            </tr>




                            <tr class="mphb-hide mphb-price-breakdown-subtotal">
                                <th colspan="1">Subtotal</th>
                                <th class="mphb-table-price-column"><span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span></th>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="mphb-price-breakdown-total">
                                <th colspan="1">
                                    Total						</th>
                                <th class="mphb-table-price-column">
                                    <span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span>						</th>
                            </tr>
                            </tfoot>
                        </table>
                    </section>
                    <section id="mphb-customer-details" class="mphb-checkout-section mphb-customer-details">
                        <h3 class="mphb-customer-details-title">Your Information</h3>
                        <p class="mphb--fields-tip">
                            <small>
                                 fields are followed by <abbr title="">*</abbr>				</small>
                        </p>
                        <p class="mphb-customer-name">
                            <label for="mphb_first_name">
                                First Name					<abbr title="">*</abbr>				</label>
                            <br>
                            <input type="text" id="mphb_first_name" name="mphb_first_name" ="">
                        </p>
                        <p class="mphb-customer-last-name">
                            <label for="mphb_last_name">
                                Last Name					<abbr title="">*</abbr>				</label>
                            <br>
                            <input type="text" name="mphb_last_name" id="mphb_last_name" ="">
                        </p>
                        <p class="mphb-customer-email">
                            <label for="mphb_email">
                                Email					<abbr title="">*</abbr>				</label>
                            <br>
                            <input type="email" name="mphb_email" ="" id="mphb_email">
                        </p>
                        <p class="mphb-customer-phone">
                            <label for="mphb_phone">
                                Phone					<abbr title="">*</abbr>				</label>
                            <br>
                            <input type="text" name="mphb_phone" ="" id="mphb_phone">
                        </p>
                        <p class="mphb-customer-country">
                            <label for="mphb_country">
                                Country of residence						<abbr title="">*</abbr>					</label>
                            <br>
                            <select name="mphb_country" ="" id="mphb_country">
                                <option value=""></option>
                                <option value="AX">
                                    Åland Islands							</option>
                                <option value="AF">
                                    Afghanistan							</option>
                                <option value="AL">
                                    Albania							</option>
                                <option value="DZ">
                                    Algeria							</option>
                                <option value="AS">
                                    American Samoa							</option>
                                <option value="AD">
                                    Andorra							</option>
                                <option value="AO">
                                    Angola							</option>
                                <option value="AI">
                                    Anguilla							</option>
                                <option value="AQ">
                                    Antarctica							</option>
                                <option value="AG">
                                    Antigua and Barbuda							</option>
                                <option value="AR">
                                    Argentina							</option>
                                <option value="AM">
                                    Armenia							</option>
                                <option value="AW">
                                    Aruba							</option>
                                <option value="AU">
                                    Australia							</option>
                                <option value="AT">
                                    Austria							</option>
                                <option value="AZ">
                                    Azerbaijan							</option>
                                <option value="BS">
                                    Bahamas							</option>
                                <option value="BH">
                                    Bahrain							</option>
                                <option value="BD">
                                    Bangladesh							</option>
                                <option value="BB">
                                    Barbados							</option>
                                <option value="BY">
                                    Belarus							</option>
                                <option value="PW">
                                    Belau							</option>
                                <option value="BE">
                                    Belgium							</option>
                                <option value="BZ">
                                    Belize							</option>
                                <option value="BJ">
                                    Benin							</option>
                                <option value="BM">
                                    Bermuda							</option>
                                <option value="BT">
                                    Bhutan							</option>
                                <option value="BO">
                                    Bolivia							</option>
                                <option value="BQ">
                                    Bonaire, Saint Eustatius and Saba							</option>
                                <option value="BA">
                                    Bosnia and Herzegovina							</option>
                                <option value="BW">
                                    Botswana							</option>
                                <option value="BV">
                                    Bouvet Island							</option>
                                <option value="BR">
                                    Brazil							</option>
                                <option value="IO">
                                    British Indian Ocean Territory							</option>
                                <option value="VG">
                                    British Virgin Islands							</option>
                                <option value="BN">
                                    Brunei							</option>
                                <option value="BG">
                                    Bulgaria							</option>
                                <option value="BF">
                                    Burkina Faso							</option>
                                <option value="BI">
                                    Burundi							</option>
                                <option value="KH">
                                    Cambodia							</option>
                                <option value="CM">
                                    Cameroon							</option>
                                <option value="CA">
                                    Canada							</option>
                                <option value="CV">
                                    Cape Verde							</option>
                                <option value="KY">
                                    Cayman Islands							</option>
                                <option value="CF">
                                    Central African Republic							</option>
                                <option value="TD">
                                    Chad							</option>
                                <option value="CL">
                                    Chile							</option>
                                <option value="CN">
                                    China							</option>
                                <option value="CX">
                                    Christmas Island							</option>
                                <option value="CC">
                                    Cocos (Keeling) Islands							</option>
                                <option value="CO">
                                    Colombia							</option>
                                <option value="KM">
                                    Comoros							</option>
                                <option value="CG">
                                    Congo (Brazzaville)							</option>
                                <option value="CD">
                                    Congo (Kinshasa)							</option>
                                <option value="CK">
                                    Cook Islands							</option>
                                <option value="CR">
                                    Costa Rica							</option>
                                <option value="HR">
                                    Croatia							</option>
                                <option value="CU">
                                    Cuba							</option>
                                <option value="CW">
                                    Curaçao							</option>
                                <option value="CY">
                                    Cyprus							</option>
                                <option value="CZ">
                                    Czech Republic							</option>
                                <option value="DK">
                                    Denmark							</option>
                                <option value="DJ">
                                    Djibouti							</option>
                                <option value="DM">
                                    Dominica							</option>
                                <option value="DO">
                                    Dominican Republic							</option>
                                <option value="EC">
                                    Ecuador							</option>
                                <option value="EG">
                                    Egypt							</option>
                                <option value="SV">
                                    El Salvador							</option>
                                <option value="GQ">
                                    Equatorial Guinea							</option>
                                <option value="ER">
                                    Eritrea							</option>
                                <option value="EE">
                                    Estonia							</option>
                                <option value="ET">
                                    Ethiopia							</option>
                                <option value="FK">
                                    Falkland Islands							</option>
                                <option value="FO">
                                    Faroe Islands							</option>
                                <option value="FJ">
                                    Fiji							</option>
                                <option value="FI">
                                    Finland							</option>
                                <option value="FR">
                                    France							</option>
                                <option value="GF">
                                    French Guiana							</option>
                                <option value="PF">
                                    French Polynesia							</option>
                                <option value="TF">
                                    French Southern Territories							</option>
                                <option value="GA">
                                    Gabon							</option>
                                <option value="GM">
                                    Gambia							</option>
                                <option value="GE">
                                    Georgia							</option>
                                <option value="DE">
                                    Germany							</option>
                                <option value="GH">
                                    Ghana							</option>
                                <option value="GI">
                                    Gibraltar							</option>
                                <option value="GR">
                                    Greece							</option>
                                <option value="GL">
                                    Greenland							</option>
                                <option value="GD">
                                    Grenada							</option>
                                <option value="GP">
                                    Guadeloupe							</option>
                                <option value="GU">
                                    Guam							</option>
                                <option value="GT">
                                    Guatemala							</option>
                                <option value="GG">
                                    Guernsey							</option>
                                <option value="GN">
                                    Guinea							</option>
                                <option value="GW">
                                    Guinea-Bissau							</option>
                                <option value="GY">
                                    Guyana							</option>
                                <option value="HT">
                                    Haiti							</option>
                                <option value="HM">
                                    Heard Island and McDonald Islands							</option>
                                <option value="HN">
                                    Honduras							</option>
                                <option value="HK">
                                    Hong Kong							</option>
                                <option value="HU">
                                    Hungary							</option>
                                <option value="IS">
                                    Iceland							</option>
                                <option value="IN">
                                    India							</option>
                                <option value="ID">
                                    Indonesia							</option>
                                <option value="IR">
                                    Iran							</option>
                                <option value="IQ">
                                    Iraq							</option>
                                <option value="IE">
                                    Ireland							</option>
                                <option value="IM">
                                    Isle of Man							</option>
                                <option value="IL">
                                    Israel							</option>
                                <option value="IT">
                                    Italy							</option>
                                <option value="CI">
                                    Ivory Coast							</option>
                                <option value="JM">
                                    Jamaica							</option>
                                <option value="JP">
                                    Japan							</option>
                                <option value="JE">
                                    Jersey							</option>
                                <option value="JO">
                                    Jordan							</option>
                                <option value="KZ">
                                    Kazakhstan							</option>
                                <option value="KE">
                                    Kenya							</option>
                                <option value="KI">
                                    Kiribati							</option>
                                <option value="KW">
                                    Kuwait							</option>
                                <option value="KG">
                                    Kyrgyzstan							</option>
                                <option value="LA">
                                    Laos							</option>
                                <option value="LV">
                                    Latvia							</option>
                                <option value="LB">
                                    Lebanon							</option>
                                <option value="LS">
                                    Lesotho							</option>
                                <option value="LR">
                                    Liberia							</option>
                                <option value="LY">
                                    Libya							</option>
                                <option value="LI">
                                    Liechtenstein							</option>
                                <option value="LT">
                                    Lithuania							</option>
                                <option value="LU">
                                    Luxembourg							</option>
                                <option value="MO">
                                    Macao S.A.R., China							</option>
                                <option value="MK">
                                    Macedonia							</option>
                                <option value="MG">
                                    Madagascar							</option>
                                <option value="MW">
                                    Malawi							</option>
                                <option value="MY">
                                    Malaysia							</option>
                                <option value="MV">
                                    Maldives							</option>
                                <option value="ML">
                                    Mali							</option>
                                <option value="MT">
                                    Malta							</option>
                                <option value="MH">
                                    Marshall Islands							</option>
                                <option value="MQ">
                                    Martinique							</option>
                                <option value="MR">
                                    Mauritania							</option>
                                <option value="MU">
                                    Mauritius							</option>
                                <option value="YT">
                                    Mayotte							</option>
                                <option value="MX">
                                    Mexico							</option>
                                <option value="FM">
                                    Micronesia							</option>
                                <option value="MD">
                                    Moldova							</option>
                                <option value="MC">
                                    Monaco							</option>
                                <option value="MN">
                                    Mongolia							</option>
                                <option value="ME">
                                    Montenegro							</option>
                                <option value="MS">
                                    Montserrat							</option>
                                <option value="MA">
                                    Morocco							</option>
                                <option value="MZ">
                                    Mozambique							</option>
                                <option value="MM">
                                    Myanmar							</option>
                                <option value="NA">
                                    Namibia							</option>
                                <option value="NR">
                                    Nauru							</option>
                                <option value="NP">
                                    Nepal							</option>
                                <option value="NL">
                                    Netherlands							</option>
                                <option value="NC">
                                    New Caledonia							</option>
                                <option value="NZ">
                                    New Zealand							</option>
                                <option value="NI">
                                    Nicaragua							</option>
                                <option value="NE">
                                    Niger							</option>
                                <option value="NG">
                                    Nigeria							</option>
                                <option value="NU">
                                    Niue							</option>
                                <option value="NF">
                                    Norfolk Island							</option>
                                <option value="KP">
                                    North Korea							</option>
                                <option value="MP">
                                    Northern Mariana Islands							</option>
                                <option value="NO">
                                    Norway							</option>
                                <option value="OM">
                                    Oman							</option>
                                <option value="PK">
                                    Pakistan							</option>
                                <option value="PS">
                                    Palestinian Territory							</option>
                                <option value="PA">
                                    Panama							</option>
                                <option value="PG">
                                    Papua New Guinea							</option>
                                <option value="PY">
                                    Paraguay							</option>
                                <option value="PE">
                                    Peru							</option>
                                <option value="PH">
                                    Philippines							</option>
                                <option value="PN">
                                    Pitcairn							</option>
                                <option value="PL">
                                    Poland							</option>
                                <option value="PT">
                                    Portugal							</option>
                                <option value="PR">
                                    Puerto Rico							</option>
                                <option value="QA">
                                    Qatar							</option>
                                <option value="RE">
                                    Reunion							</option>
                                <option value="RO">
                                    Romania							</option>
                                <option value="RU">
                                    Russia							</option>
                                <option value="RW">
                                    Rwanda							</option>
                                <option value="ST">
                                    São Tomé and Príncipe							</option>
                                <option value="BL">
                                    Saint Barthélemy							</option>
                                <option value="SH">
                                    Saint Helena							</option>
                                <option value="KN">
                                    Saint Kitts and Nevis							</option>
                                <option value="LC">
                                    Saint Lucia							</option>
                                <option value="SX">
                                    Saint Martin (Dutch part)							</option>
                                <option value="MF">
                                    Saint Martin (French part)							</option>
                                <option value="PM">
                                    Saint Pierre and Miquelon							</option>
                                <option value="VC">
                                    Saint Vincent and the Grenadines							</option>
                                <option value="WS">
                                    Samoa							</option>
                                <option value="SM">
                                    San Marino							</option>
                                <option value="SA">
                                    Saudi Arabia							</option>
                                <option value="SN">
                                    Senegal							</option>
                                <option value="RS">
                                    Serbia							</option>
                                <option value="SC">
                                    Seychelles							</option>
                                <option value="SL">
                                    Sierra Leone							</option>
                                <option value="SG">
                                    Singapore							</option>
                                <option value="SK">
                                    Slovakia							</option>
                                <option value="SI">
                                    Slovenia							</option>
                                <option value="SB">
                                    Solomon Islands							</option>
                                <option value="SO">
                                    Somalia							</option>
                                <option value="ZA">
                                    South Africa							</option>
                                <option value="GS">
                                    South Georgia/Sandwich Islands							</option>
                                <option value="KR">
                                    South Korea							</option>
                                <option value="SS">
                                    South Sudan							</option>
                                <option value="ES">
                                    Spain							</option>
                                <option value="LK">
                                    Sri Lanka							</option>
                                <option value="SD">
                                    Sudan							</option>
                                <option value="SR">
                                    Suriname							</option>
                                <option value="SJ">
                                    Svalbard and Jan Mayen							</option>
                                <option value="SZ">
                                    Swaziland							</option>
                                <option value="SE">
                                    Sweden							</option>
                                <option value="CH">
                                    Switzerland							</option>
                                <option value="SY">
                                    Syria							</option>
                                <option value="TW">
                                    Taiwan							</option>
                                <option value="TJ">
                                    Tajikistan							</option>
                                <option value="TZ">
                                    Tanzania							</option>
                                <option value="TH">
                                    Thailand							</option>
                                <option value="TL">
                                    Timor-Leste							</option>
                                <option value="TG">
                                    Togo							</option>
                                <option value="TK">
                                    Tokelau							</option>
                                <option value="TO">
                                    Tonga							</option>
                                <option value="TT">
                                    Trinidad and Tobago							</option>
                                <option value="TN">
                                    Tunisia							</option>
                                <option value="TR">
                                    Turkey							</option>
                                <option value="TM">
                                    Turkmenistan							</option>
                                <option value="TC">
                                    Turks and Caicos Islands							</option>
                                <option value="TV">
                                    Tuvalu							</option>
                                <option value="UG">
                                    Uganda							</option>
                                <option value="UA">
                                    Ukraine							</option>
                                <option value="AE" selected="selected">
                                    United Arab Emirates							</option>
                                <option value="GB">
                                    United Kingdom (UK)							</option>
                                <option value="US">
                                    United States (US)							</option>
                                <option value="UM">
                                    United States (US) Minor Outlying Islands							</option>
                                <option value="VI">
                                    United States (US) Virgin Islands							</option>
                                <option value="UY">
                                    Uruguay							</option>
                                <option value="UZ">
                                    Uzbekistan							</option>
                                <option value="VU">
                                    Vanuatu							</option>
                                <option value="VA">
                                    Vatican							</option>
                                <option value="VE">
                                    Venezuela							</option>
                                <option value="VN">
                                    Vietnam							</option>
                                <option value="WF">
                                    Wallis and Futuna							</option>
                                <option value="EH">
                                    Western Sahara							</option>
                                <option value="YE">
                                    Yemen							</option>
                                <option value="ZM">
                                    Zambia							</option>
                                <option value="ZW">
                                    Zimbabwe							</option>
                            </select>
                        </p>
                        <p class="mphb-customer-note">
                            <label for="mphb_note">Notes</label><br>
                            <textarea name="mphb_note" id="mphb_note" rows="4"></textarea>
                        </p>
                    </section>
                    <section id="mphb-billing-details" class="mphb-checkout-section">
                        <h3 class="mphb-gateway-chooser-title">
                            Payment Method            </h3>

                        <ul class="mphb-gateways-list"><li class="mphb-gateway mphb-gateway-test">                    <input type="radio" name="mphb_gateway_id" id="mphb_gateway_test" value="test" checked="checked">

                                <label for="mphb_gateway_test" class="mphb-gateway-title mphb-radio-label">
                                    <strong>Test Payment</strong>
                                </label>

                            </li><li class="mphb-gateway mphb-gateway-cash">                    <input type="radio" name="mphb_gateway_id" id="mphb_gateway_cash" value="cash">

                                <label for="mphb_gateway_cash" class="mphb-gateway-title mphb-radio-label">
                                    <strong>Pay on Arrival</strong>
                                </label>

                                <p class="mphb-gateway-description">
                                    Pay with cash on arrival.                        </p>
                            </li></ul>
                        <fieldset class="mphb-billing-fields mphb-billing-fields-hidden">
                        </fieldset>
                    </section>
                    <p class="mphb-total-price">
                        <output>
                            Total Price:				<strong class="mphb-total-price-field">
                                <span class="mphb-price"><span class="mphb-currency">د.إ</span>1,000</span>				</strong>
                            <span class="mphb-preloader mphb-hide"></span>
                        </output>
                    </p>
                    <p class="mphb-errors-wrapper mphb-hide"></p>
                    <section class="mphb-checkout-terms-wrapper mphb-checkout-section">
                        <div class="mphb-terms-and-conditions">
                            <p>Something notices</p>
                        </div>
                        <p class="mphb-terms-and-conditions-accept">
                            <label>
                                <input type="checkbox" id="mphb_accept_terms" name="mphb_accept_terms" value="1" ="">
                                I've read and accept the <a class="mphb-terms-and-conditions-link" href="https://luton.ae/hotel-term-condition/" target="_blank">terms &amp; conditions</a>						<abbr title="">*</abbr>
                            </label>
                        </p>
                    </section>

                    <p class="mphb_sc_checkout-submit-wrapper">
                        <input type="submit" class="button" value="Book Now">
                    </p>
                </form>
            </div></div>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode('test_form','test_form');



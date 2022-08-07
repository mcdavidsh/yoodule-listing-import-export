<?php

require_once(ABSPATH . 'wp-config.php');
require_once(ABSPATH . 'wp-includes/wp-db.php');
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
require plugin_dir_path(__FILE__) . 'includes/options.php';

//add_action( 'plugins_loaded', array( 'YooduleListImportExport', 'init' ));
class YooduleListImportExport extends options
{


    protected static $instance = NULL;

    public static function get_instance()
    {

        // create an object
        NULL === self::$instance and self::$instance = new self;

        return self::$instance; // return the object
    }

    public function create_options()
    {

        if (!get_option($this->agentIdRow) && !get_option($this->clientIdRow) && !get_option($this->clientPassword) && !get_option($this->useTrainingDatabase) && !get_option($this->moduleType)) {
            add_option($this->agentIdRow, '', 'no');
            add_option($this->clientIdRow, '', 'no');
            add_option($this->clientPassword, '', 'no');
            add_option($this->useTrainingDatabase, '', 'no');
            add_option($this->moduleType, '', 'no');
        }
    }


    function update_accomodation_type()
    {
        global $wpdb;

        $key = "mphb_room";
        $post = get_posts([
            'post_type' => $key,
            'post_status' => 'publish',
            'numberposts' => -1,
            'order' => 'ASC'

        ]);


        for ($x = 0; $x <= count($post); $x++):
            $pstmeta = get_post_meta($post[$x]->ID, 'rms_property_id');
            foreach ($pstmeta as $key => $val):

                $prop_id = intval($val);

                $response = $this->curl_remote_get($this->endpoint . "categories?propertyId=$prop_id&modelType=full", $this->token);

                $decode = json_decode($response);
                foreach ($decode as $item):
                    $arr = [
                        "allowMultipleReservationsOverTheSameTime" => $item->allowMultipleReservationsOverTheSameTime,
                        "allowOverbooking" => $item->allowOverbooking,
                        "availableToKiosk" => $item->availableToKiosk,
                        "categoryManagerId" => $item->categoryManagerId,
                        "categoryTypeGrouping" => $item->categoryTypeGrouping,
                        "code" => $item->code,
                        "code2" => $item->code2,
                        "code3" => $item->code3,
                        "dynamicPricingGroupingId" => $item->dynamicPricingGroupingId,
                        "guestDescription" => $item->guestDescription,
                        "includeOnHouseKeepersReport" => $item->includeOnHouseKeepersReport,
                        "modifiedDate" => $item->modifiedDate,
                        "minNightlyRate" => $item->minNightlyRate,
                        "numberOfOverbookingsAllowed" => $item->numberOfOverbookingsAllowed,
                        "userDefined1" => $item->userDefined1,
                        "userDefined2" => $item->userDefined2,
                        "userDefined3" => $item->userDefined3,
                        "userDefined4" => $item->userDefined4,
                        "userDefined5" => $item->userDefined5,
                        "userDefined6" => $item->userDefined6,
                        "userDefined7" => $item->userDefined7,
                        "userDefined8" => $item->userDefined8,
                        "userDefined9" => $item->userDefined9,
                        "headline" => $item->headline,
                        "minimumOccupantsPerRoom" => $item->minimumOccupantsPerRoom,
                        "numberOfBedrooms" => $item->numberOfBedrooms,
                        "numberOfFullBaths" => $item->numberOfFullBaths,
                        "numberOfHalfBaths" => $item->numberOfHalfBaths,
                        "numberOfRoomsToHoldFromIBE" => $item->numberOfRoomsToHoldFromIBE,
                        "numberOfShowers" => $item->numberOfShowers,
                        "allowBookingByCategory" => $item->allowBookingByCategory,
                        "availableToIbe" => $item->availableToIbe,
                        "categoryClass" => $item->categoryClass,
                        "categoryTypeGroupingId" => $item->categoryTypeGroupingId,
                        "glCodeId" => $item->glCodeId,
                        "inactive" => $item->inactive,
                        "interconnecting" => $item->interconnecting,
                        "longDescription" => $item->longDescription,
                        "numberOfAreas" => $item->numberOfAreas,
                        "maxOccupantsPerCategory" => $item->maxOccupantsPerCategory,
                        "maxOccupantsPerCategoryType" => $item->maxOccupantsPerCategoryType,
                        "defaultArrivalTime" => $item->defaultArrivalTime,
                        "defaultDepartureTime" => $item->defaultDepartureTime,
                        "id" => $item->id,
                        "name" => $item->name,
                        "propertyId" => $item->propertyId
                    ];

                    if (!is_user_logged_in() && !is_user_admin()) {
                        return false;
                    } else {
                        $cat_id = wp_create_term($arr["categoryClass"], "mphb_room_type_category");
                        if ($cat_id) {

                            $post_id = wp_insert_post(
                                [
                                    "post_title" => $arr["categoryTypeGrouping"],
                                    "post_status" => "publish",

                                ]);

                        }
                        if ($post_id) {
                            $args = [
                                "object_id" => $post_id,
                                "term_taxonomy_id" => $cat_id
                            ];
                            $term_rel = $wpdb->prefix . 'term_relationships';
                            $wpdb->insert($term_rel, $args);

                        }
                    }
                endforeach;

            endforeach;


        endfor;


    }

    function update_listing()
    {

        if (isset($_GET['mphb_check_in_date']) && isset($_GET['mphb_check_out_date']) && isset($_GET['mphb_adults']) && isset($_GET['mphb_children'])) {


            $checkInDate = $_GET['mphb_check_in_date'];
            $checkOutDate = $_GET['mphb_check_out_date'];
            $adult = $_GET['mphb_adults'];
            $children = $_GET['mphb_children'];
            $amenities = $_GET['mphb_children'];
            $auth = get_option($this->clientIdRow);
            $data = array(
                "adults" => $adult,
                "agentId" => 11281,
                "categoryIds" => array(1),
                "children" => $children,
                "dateFrom" => $checkInDate,
                "dateTo" => $checkOutDate,
                "infants" => $children,
                "propertyId" => 1,
            );
            $response = $this->curl_remote_get($this->endpoint . "properties?modelType=full", $this->token);

            $decode = json_decode($response);


            for ($x = 0; $x < count($decode); $x++):
                $arr = array(
                    "abn" => $decode[$x]->abn,
                    "addressLine1" => $decode[$x]->addressLine1,
                    "addressLine2" => $decode[$x]->addressLine2,
                    "addressLine3" => $decode[$x]->addressLine3,
                    "addressLine4" => $decode[$x]->addressLine4,
                    "city" => $decode[$x]->city,
                    "countryId" => $decode[$x]->countryId,
                    "email" => $decode[$x]->email,
                    "groupId" => $decode[$x]->groupId,
                    "latitude" => $decode[$x]->latitude,
                    "longitude" => $decode[$x]->latitude,
                    "mobile" => $decode[$x]->mobile,
                    "phone" => $decode[$x]->phone,
                    "postCode" => $decode[$x]->postCode,
                    "state" => $decode[$x]->state,
                    "accountingDate" => $decode[$x]->accountingDate,
                    "code" => $decode[$x]->code,
                    "clientId" => $decode[$x]->clientId,
                    "timeZone" => $decode[$x]->timeZone,
                    "useSecondaryCurrency" => $decode[$x]->useSecondaryCurrency,
                    "id" => $decode[$x]->id,
                    "name" => $decode[$x]->name,
                    "inactive" => $decode[$x]->inactive
                );


                $post_id = wp_insert_post([
                    "post_title" => $arr["name"],
                    "post_type" => "mphb_room",
                    "post_status" => "publish",
                    'post_category' => array(8, 39)
                ]);
                if ($post_id) {
                    $post = get_post($post_id);
                    $post->guid = get_option('siteurl') . '/?post_type=mphb_room&p=' . $post_id;
                    add_post_meta($post_id, 'mphb_room_type_id', $arr["groupId"]);
                    add_post_meta($post_id, 'rms_property_id', $arr["id"]);
                    wp_update_post($post);
                }

            endfor;
        }
    }

    function post_booking()


    {



//        if (isset($_POST['action']) && isset($_POST['check_out_data'])) {
        if ($_SERVER['REQUEST_METHOD'] ==='POST' && basename($_SERVER['REQUEST_URI']) === 'booking-confirmation' ) {
            echo basename($_SERVER['REQUEST_URI']);
//            reservation-received/?payment_id=861&payment_key=payment_861_62edb16e214c66.40970860&mphb_payment_status=mphb-p-on-hold
            $req = $_POST['check_out_data'];

//            $authKey = mphb_generate_uid();
            $data = array(
                "mphb-checkout-nonce" => $req["mphb-checkout-nonce"],
                "_wp_http_referer" => $req["_wp_http_referer"],
                "mphb-booking-checkout-id" => $req["mphb-booking-checkout-id"],
                "mphb_check_in_date" => $req["mphb_check_in_date"],
                "mphb_check_out_date" => $req["mphb_check_out_date"],
                "mphb_checkout_step" => $req["mphb_checkout_step"],
                "mphb_room_details[0" =>
                    [
                        "room_type_id" => $req["room_type_id"],
                        "adults" => $req["adults"],
                        "children" => $req["children"],
                        "guest_name" => $req["guest_name"],
                        "rate_id" => $req["323"],
                        "services" => [
                            "adults" => $req["adults"],
                            "quantity" => $req["quantity"]
                        ]
                ],
                "mphb_applied_coupon_code" => $req["mphb_applied_coupon_code"],
                "mphb_coupon_code" => $req["mphb_coupon_code"],
                "mphb_first_name" => $req["mphb_first_name"],
                "mphb_last_name" => $req["mphb_last_name"],
                "mphb_email" => $req["mphb_email"],
                "mphb_phone" => $req["mphb_phone"],
                "mphb_country" => $req["mphb_phone"],
                "mphb_note" => $req["mphb_note"],
                "mphb_gateway_id" => $req["mphb_gateway_id"],
                "mphb_accept_terms" => $req["mphb_accept_terms" ]
            );

            $param =[
                "id" => 0,
                "accountId" => 1,
                "adults" => $req["mphb_room_details[0"]["adults"],
                "areaId" => 111,
                "bookerContactId"=> 4,
    "bookingSourceId"=> 0,
    "bookingSourceName"=> "Online",
                "arrivalDate" => $req["mphb_check_in_date"],
//                "baseRateOverride" => 0,
                "categoryId"=> 9,
                "children" => $req["mphb_room_details"]["children"],
//                "companyId" => 5,
                "departureDate" => $req["mphb_check_out_date"],
                "discountId" => 22,
                "groupAllotmentId" => 0,
                "groupReservationId" => 0,
                "guestId" => 134541,
                "infants" =>  $req["mphb_room_details[0"]["children"],
                    "notes" => $data["mphb_note"],
//                    "onlineConfirmationId" => $data["mphb-checkout-nonce"],
//                    "otaNotes" => $data["mphb_note"],
//                "otaRef1" => "V5986985s9",
//                "otaRef2" => "BCOM-8976958",
//                "otaRef3" => "89869858896",
//                "rateTypeId" => 1,
//                "resTypeId" => 0,
                "status" => "Confirmed",
//                "subMarketSegmentId" => 5,
                "userDefined1" => $data["mphb_email"],
                    "userDefined2" => $data["mphb_last_name"],
                    "userDefined3" =>$data["mphb_first_name"]  ,
                    "userDefined4" => $data["mphb_phone"],
                    "userDefined5" => $data["mphb_country"],
//                "userDefined6" => "String 20",
//                "userDefined7" => "String 20",
//                "userDefined8" => "String 20",
//                "userDefined9" => "String 20",
//                "userDefined10" => "String 50",
//                "userDefined11" => true,
//                "userDefined12" => true,
//                "userDefined13" => true,
//                "userDefined14" => "2016-08-29 09:25:00",
//                "userDefined15" => "2016-08-29 09:25:00",
//                "travelAgentId" => 1,
//                "voucherId" => "B4569856985",
            ];


            $result = $this->curl_remote_post($this->endpoint.'reservations?ignoreMandatoryFieldWarnings=true',$param,$this->token);

//            wp_send_json($result);

            if (isset($_POST['submit'])) {

                return false;
            }
        }


    }


}

register_activation_hook(YD_PLUGIN_FILE, array('YooduleListImportExport', 'activate'));
register_deactivation_hook(YD_PLUGIN_FILE, array('YooduleListImportExport', 'deactivate'));
YooduleListImportExport::get_instance();
$yt = new YooduleListImportExport();
add_action('init', [$yt, 'update_listing']);
add_action('init', [$yt, 'create_options']);
add_action('init', [$yt, 'post_booking']);

add_action('wp_ajax_post_booking', [$yt, 'post_booking']); //logged in
add_action('wp_ajax_no_priv_post_booking',[$yt, 'post_booking']); //not logged in

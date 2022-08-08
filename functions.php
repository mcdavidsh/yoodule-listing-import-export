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


        foreach ($this->rms_options() as $key => $value):

            if (!get_option($key)){
                add_option($key, $value );
            }
         if (empty(get_option($key))){
             update_option($key, $value);
             }
            endforeach;
    }


    function get_areas(){
        $para = $this->curl_remote_get($this->endpoint.'areas?limit=100&modelType=basic&offset=0&propertyId=1', $this->token);
//        $decode = wp_json_file_decode();

    }

    function available_facilities(){
        if (isset($_GET['mphb_check_in_date']) && isset($_GET['mphb_check_out_date']) && isset($_GET['mphb_adults']) && isset($_GET['mphb_children'])) {

            $body = [
                "adults" => 2,
                "agentId" => 1,
                "categoryIds" => [
                    9
                ],
                "children" => 0,
                "dateFrom" => "2023-02-10 06:00:00",
                "dateTo" => "2023-02-10 06:30:00",
                "infants" => 0,
                "propertyId" => 1
            ];

        }

    }

    function property_rates(){


    }

    function import_categories()
    {
        global $wpdb;


        set_time_limit(3600);

        $roomsTable = $wpdb->prefix."vikbooking_rooms";

     $post = $wpdb->get_results("select * from $roomsTable");

     if (!empty($post)) {

         for ($x = 0; $x <= count($post); $x++):
             $prop_id = intval($post[$x]->id);
                 $response = $this->curl_remote_get( "categories?propertyId=$prop_id&modelType=full");
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
                     $catTable = $wpdb->prefix . "vikbooking_categories";
                     $catName = $arr["categoryClass"];
                     if (!is_user_logged_in() && !is_user_admin()) {
                        wp_die( "Not Allowed");
                     } else {
                         $check_cat = $wpdb->query("select * from $catTable where name = '$catName'");
                         if (!empty($check_cat)) {
                             continue;
                         } else {
                             $row = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$catTable' AND column_name = 'catId'");

                             if (empty($row)) {
                                 $wpdb->query("ALTER TABLE $catTable ADD catId INT(1) NOT NULL DEFAULT 0 ");
                                 $cat_id = $wpdb->insert($catTable,
                                     [
                                         "name" => $arr["categoryClass"],
                                         "descr" => $arr["longDescription"],
                                         "catId" => $arr["id"],
                                     ]
                                 );
                                 if ($cat_id) {
                                     $wpdb->update(
                                         $roomsTable, [
                                         "idcat" => $arr["id"],
                                         "totpeople" => $arr["maxOccupantsPerCategory"],
                                         "mintotpeople" => $arr["minimumOccupantsPerRoom"],

                                     ], ["propsId" => $arr["propertyId"]]);

                                     return true;
                                 }

                             }
                         }
                     }

             endforeach;


         endfor;

 return  true;
     }
    }
    function import_properties()
    {
     global  $wpdb;
        set_time_limit(3600);

 if (isset($_POST['importRooms'])) {

            if (!is_user_admin() && !is_user_logged_in())
            {
                 wp_die('User must be admin');
            }

            $response = $this->curl_remote_get( "properties?modelType=full");

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


            $roomsTable = $wpdb->prefix.'vikbooking_rooms';
            $name = $arr['name'];


                    $check = $wpdb->query("select * from $roomsTable where name = '$name'");
                    if (!empty($check)){
                       continue;
                    }else {
                        $row = $wpdb->get_results(  "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$roomsTable' AND column_name = 'propsId'"  );
                        if(empty($row)){
                            $wpdb->query("ALTER TABLE $roomsTable ADD propsId INT(1) NOT NULL DEFAULT 0  AFTER idcat");
                            continue;
                        }

                        $wpdb->insert($roomsTable,
                            [
                                'propsId' => $arr["id"],
                                'name' => $arr["name"],
                                'alias' => sanitize_title($arr["name"]),
                            ]);
                        if ($wpdb->insert_id) {

                            add_action('init', [$this, 'import_categories']);
                            add_action('admin_notices', [$this, '_success_notice']);

                            echo "
                            <script>
                            alert('Imported')
                            </script>
                            ";
                        }
                    }
            endfor;
        }

    }

    function _success_notice(){
        ?>
        <div class="notice notice-success is-dismissible">
        <p>Imported successfully!</p>
    </div>
<?php
    }


/**@var YooduleListImportExport **/

    function post_booking()

    {
        if (isset($_POST['action']) && isset($_POST['check_out_data']))
        {


//            reservation-received/?payment_id=861&payment_key=payment_861_62edb16e214c66.40970860&mphb_payment_status=mphb-p-on-hold
                $req = $_POST['check_out_data'];

                $data =  array (
                        'couponcode' => '',
                        'task' =>
                            array (
                                0 => 'oconfirm',
                                1 => 'saveorder',
                            ),
                        'days' =>
                            array (
                                0 => '1',
                                1 => '1',
                            ),
                        'roomsnum' =>
                            array (
                                0 => '1',
                                1 => '1',
                            ),
                        'checkin' =>
                            array (
                                0 => '1660132800',
                                1 => '1660132800',
                            ),
                        'checkout' =>
                            array (
                                0 => '1660212000',
                                1 => '1660212000',
                            ),
                        'Itemid' =>
                            array (
                                0 => '8',
                                1 => '8',
                            ),
                        'priceid1' => '1',
                        'roomid[]' => '1',
                        'adults[]' =>
                            array (
                                0 => '1',
                                1 => '1',
                            ),
                        'children[]' =>
                            array (
                                0 => '0',
                                1 => '0',
                            ),
                        'vbf2' => 'Mcdavid Obioha',
                        'vbf3' => 'Obioha',
                        'vbf4' => 'mcdave92@gmail.com',
                        'vbf5' => '0908 334 4553',
                        'vbf6' => 'No 6 Chukwuma Close, 18',
                        'vbf7' => '961105',
                        'vbf8' => 'Karu',
                        'vbf9' => 'NGA::Nigeria',
                        'vbf10' => 'Yoodule',
                        'vbf11' => '',
                        'vbf12' => 'thanks',
                        'vbf13' => 'Yes',
                        'totdue' => '120',
                        'prtar[]' => '1',
                        'priceid[]' => '1',
                        'rooms[]' => '1',
                        'optionals' => '',
                    );

                $param =[
                    "id" => 0,
                    "accountId" => 1,
//                    "adults" => $req["adults[]"][0],
                    "areaId" => 111,
                    "bookerContactId"=> 4,
                    "bookingSourceId"=> 0,
//                    "bookingSourceName"=> "Online",
//                    "arrivalDate" => $req["checkin"][0],
//                "baseRateOverride" => 0,
                    "categoryId"=> 9,
//                    "children" => $req["children[]"][0],
////                "companyId" => 5,
//                    "departureDate" => $req["checkout"][0],
//                    "discountId" => 22,
//                    "groupAllotmentId" => 0,
//                    "groupReservationId" => 0,
//                    "guestId" => 134541,
//                    "infants" => $req["children[]"][0],
//                    "notes" => $req["vbf12"],
//                    "onlineConfirmationId" => $data["mphb-checkout-nonce"],
//                    "otaNotes" => $data["mphb_note"],
//                "otaRef1" => "V5986985s9",
//                "otaRef2" => "BCOM-8976958",
//                "otaRef3" => "89869858896",
//                "rateTypeId" => 1,
//                "resTypeId" => 0,
                    "status" => "Confirmed",
//                "subMarketSegmentId" => 5,
//                    "userDefined1" => $data["vbf4"],
//                    "userDefined2" => $req["vbf3"],
//                    "userDefined3" =>$req["vbf2"]  ,
//                    "userDefined4" => $data["mphb_phone"],
//                    "userDefined5" => $data["vbf9"],
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


                $result = $this->curl_remote_post('reservations?ignoreMandatoryFieldWarnings=true',$param);

            wp_send_json($result);
            }

    }


}

register_activation_hook(YD_PLUGIN_FILE, array('YooduleListImportExport', 'activate'));
register_deactivation_hook(YD_PLUGIN_FILE, array('YooduleListImportExport', 'deactivate'));
$yt = new YooduleListImportExport();
add_action('init', [$yt, 'import_properties']);
add_action('init', [$yt, 'create_options']);
add_action('init', [$yt, 'post_booking']);

add_action('wp_ajax_post_booking', [$yt, 'post_booking']); //logged in
add_action('wp_ajax_no_priv_post_booking',[$yt, 'post_booking']); //not logged in

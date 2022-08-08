<?php


add_action( 'admin_menu', 'yoodule_admin_menu' );
if (!function_exists('yoodule_admin_menu')) {
    function yoodule_admin_menu() {

        $icon_url   = 'dashicons-rest-api';
        $position   = 5;
        add_menu_page( '', 'YD RMS Import Export', '', 'yd_intro', '', $icon_url, $position );
        add_submenu_page( 'yd_intro', 'Import Rooms', 'Import Rooms', 'manage_options', 'yd_import', 'yd_import_rooms' );
        add_submenu_page( 'yd_intro', 'YD RMS Import Export Settings', 'Settings','manage_options' , 'yd_settings', 'yd_settings' );
    }
}


//Import Page

if( !function_exists("yd_import_rooms") ) {

 function yd_import_rooms(){

     ?>
     <h1>Import Rooms From RMSCLoud</h1>

     <form method="post">
         <input class="button-primary" type="submit" name="importRooms" value="<?php esc_attr_e( 'Import' ); ?>" />
     </form>

         <?php
 }

}



    if( !function_exists("yd_settings") ) {

        function yd_settings(){
            if ( !current_user_can( 'manage_options' ) && !is_user_admin())  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            ?>
                <div class="yd-header">
                    <h1>Yoodule Import Export Settings</h1> <span role="alert" class="button-primary"><?php echo get_option("rms_mode") ?></span>
                </div>
            <div class="yd-container">

                <form method="post">

                    <div class="input-row">
                        <label >RMS Agent ID</label>
                        <input type="text" name="rms-agent-id" value="<?php echo get_option("rms_agent_id");?>" class="regular-text yd-form-input" placeholder="Enter RMS Agent ID">
                    </div>
                    <div class="input-row">
                        <label >RM API Client ID</label>

                        <input type="text" name="rms-client-id" value="<?php echo get_option("rms_client_id");?>" class="regular-text" placeholder="Enter API Client ID">
                    </div>
                    <div class="input-row">
                        <label >RMS Agent Password</label>
                        <input type="text" name="rms-agent-password" value="<?php echo get_option("rms_agent_password");?>" class="regular-text yd-form-input" placeholder="Enter RMS Agent Password">
                    </div>
                    <div class="input-row">
                        <label >RMS Client Password</label>
                        <input type="text" name="rms-client-password" value="<?php echo get_option("rms_client_password");?>" class="regular-text yd-form-input" placeholder="Enter Client Password">
                    </div>
                    <div class="input-row">
                        <label >RMS Module Type</label>

                        <input type="text" name="rms-module-type" value="<?php echo get_option("rms_module_type");?>" class="regular-text" placeholder="Enter RMS Module Type">
                    </div>
                    <div class="input-row">
                        <label >RMS API Token</label>

                        <input type="text" name="rms-module-type" value="<?php echo get_option("rms_token");?>" class="regular-text" placeholder="Enter RMS API Token">
                    </div>

                    <div class="input-row">
                        <label >RMS Mode</label>

                        <input type="text" name="rms-mode" value="<?php echo get_option("rms_mode");?>" class="regular-text" placeholder="Enter RMS Mode">
                    </div>
                    <small>Please change mode to live or test.</small>
                    <div class="input-row">
                        <input class="button-primary" type="submit" name="submit" value="<?php esc_attr_e( 'Submit' ); ?>" />
                    </div>
                </form>
            </div>
            <?php
        }
        if (isset($_POST["submit"])){

            $secret = $_POST["stripe-secret"];
            $client = $_POST["stripe-client"];

            if (!get_option("stripe_api_stripe") && !get_option("stripe_api_client")){
                add_option( 'stripe_api_client' , $client , '' , 'no');
                add_option( 'stripe_api_secret' , $secret , '' , 'no');
                add_action('admin_notices', 'insert_notice');
            }else {
                update_option( 'stripe_api_client' , $client );
                update_option( 'stripe_api_secret' , $secret );
                add_action('admin_notices', 'update_notice');
            }

        }



}
function insert_notice(){

	?>
    <div class="notice notice-success is-dismissible">
        <p>API Keys Saved successfully!</p>
    </div>
<?php }
function update_notice(){

	?>
    <div class="notice notice-success is-dismissible">
        <p>API Keys Updated successfully!</p>
    </div>
<?php }
function failure_notice(){

	?>
    <div class="notice notice-error is-dismissible">
        <p>API Keys failed successfully!</p>
    </div>
<?php }



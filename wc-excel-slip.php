<?php
/*
Plugin Name:  WooCommerce Excel Slip
Plugin URI:   https://iotech.co.th/wp/plugins/excel-slip
Description:  Create Woocommerce order slip in excel format.
Version:      1.2
Author:       Apinan CEO@iOTech
Author URI:   https://apinu.com
License:      MIT
Text Domain:  wc_excel_slip
Domain Path:  /languages

WC Excel Slip is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WC Excel Slip is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WC Excel Slip. If not, see http://www.gnu.org/licenses/gpl.html.
*/
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

defined ( 'ABSPATH' ) or die ( "No direct script access allowed." );

if ( !class_exists( 'WC_Excel_Slip' ) ) {

    class WC_Excel
    {
        public $plugin = array();

        /*
        * Initialize the plugin process.
        */
        public static function wc_excel_slip_init() 
        {
            $this->plugin = [
                'slug' => 'wc_excel_slip',
                'dir' => plugin_dir_path( __FILE__ ),
                'url' => plugins_url() . 'wc_excel_slip',
                'name' => 'WooCommerce Excel Slip',
                'version' => '1.0',
            ];

            require_once( dirname( __FILE__ )  . '/admin/admin-option.php' );
            require_once( dirname( __FILE__ )  . '/admin/admin-function.php' );
        }
    }

}

// Check woocommerce plugin is exists and activated.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    WC_Excel_Slip::wc_excel_slip_init();
} else {

    if ( is_admin() ) {
        // Do notification in admin mode.
        require_once( dirname( __FILE__ ) . '/admin/admin-notification.php' );
    }
}



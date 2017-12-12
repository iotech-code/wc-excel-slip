<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

new WC_Xls_Slip_Template;

class WC_Xls_Slip_Template extends WC_Excel {
    public $spreadsheet;
    public $template;
    
    function __construct() {
        global $wpdb;
        $this->template = $wpdb->get_results("SELECT * FROM ".$wpdb->options ." WHERE option_name = '".$this->plugin['slug']."_template' ");
        $this->generate_output('', $_GET['order_id']);
    }

    public static function get_order_data( $order_id ) {
        return wc_get_order( $order_id );
    }

    public static function get_xls_option () {
        global $wpdb; // this is how you get access to the database
        $option = $wpdb->get_results("SELECT * FROM ".$wpdb->options ." WHERE option_name LIKE '%" . $this->plugin['slug'] . "%' ");
        for($o=0;$o<count($option);$o++) {
            $option_res[$option[$o]->option_name] = $option[$o]->option_value;
        }
        return (object)$option_res;
    }

    public function generate_output( $title, $order_id ) {
        include( $this->plugin['dir'] . 'includes/PHPReport.php' );
        $filename = "Order_Slip_".$order_id;
        $option = $this->get_xls_option();
        $order = $this->get_order_data($order_id);
        include_once( $this->plugin['dir'] . 'templates/'.$this->template[0]->option_value.'/template.php' );

        exit(0);

    }

    
    
}


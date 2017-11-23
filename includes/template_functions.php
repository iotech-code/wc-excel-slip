<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

new WC_Xls_Slip_Template;

class WC_Xls_Slip_Template {
    public $spreadsheet;
    public $template;
    function __construct() {
        global $wpdb;
        $this->template = $wpdb->get_results("SELECT * FROM ".$wpdb->options ." WHERE option_name = '".plugin_slug."_template' ");

        $this->generate_output('', $_GET['order_id']);
    }

    public static function get_order_data( $order_id ) {
        return wc_get_order( $order_id );
    }

    public static function get_xls_option () {
        global $wpdb; // this is how you get access to the database
        $option = $wpdb->get_results("SELECT * FROM ".$wpdb->options ." WHERE option_name LIKE '%" . plugin_slug . "%' ");
        for($o=0;$o<count($option);$o++) {
            $option_res[$option[$o]->option_name] = $option[$o]->option_value;
        }
        return (object)$option_res;
    }

    public function generate_output( $title, $order_id ) {

        $filename = "Order_Slip_".$order_id;
        $option = $this->get_xls_option();
        $order = $this->get_order_data($order_id);


        $items = $order->get_items(); 
        
        // Iterating through each "line" items in the order
        foreach ($order->get_items() as $item_id => $item_data) {
            $_product = wc_get_product( $item_data->get_product_id() );
            // Get an instance of corresponding the WC_Product object
            $product = $item_data->get_product();
            $product_name = $product->get_name(); // Get the product name
            $item_sku = "SKU:". $_product->get_sku(); // Get the item price
            $item_quantity = $item_data->get_quantity(); // Get the item quantity
            $item_price = $_product->get_price(); // Get the item price
            $item_total = $item_data->get_total(); // Get the item line total
            // $item_sku = $item_data->get_sku();
            $products[] = [
                'description' => $product_name . ' ' . $item_sku,
                'qty' => $item_quantity,
                'price' => $item_price,
                'total' => $item_total
            ];
        }

        include_once( plugin_dir . 'templates/'.$this->template[0]->option_value.'/template.php' );

        exit(0);

    }

    
    
}


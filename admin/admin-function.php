<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

new WC_Excel_Common;
class WC_Excel_Common  extends WC_Excel{
    
    function __construct() {
        add_action( 'wp_ajax_excel_slip', array( $this,  'excel_slip' ) );
        add_filter( 'woocommerce_admin_order_actions', array( $this, 'download_button'), 100, 2 );
        add_action( 'admin_head', array( $this, 'xls_button_css') );
        add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'wc_add_order_meta_xls_action') );

        if (!isset($_GET['my_nonce']) || !wp_verify_nonce($_GET['my_nonce'], 'doing_something')) {

        }
    }
    
    function excel_slip() {
        if(!empty($_GET['order_id'])) {
            include( $this->plugin['dir'] . 'includes/template_functions.php' );
            die();
        }
    }


    function download_button( $actions, $order ) {
        // Display the button for all orders that have a 'processing' status
        // if ( $order->has_status( array( 'processing' ) ) ) {

            // Get Order ID (compatibility all WC versions)
            $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
            // Set the action button
            $actions['parcial'] = array(
                'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=excel_slip&order_id=' . $order_id ), 'download_excel_slip' ),
                'name'      => __( 'โหลดสลิป', 'woocommerce' ),
                'action'    => "view download", // keep "view" class for a clean button CSS
            );
        // }
        return $actions;
    }

    
    function xls_button_css() {
        echo '<link rel="stylesheet" href="' . plugins_url() . '/wc-excel-slip/assets/style.css" />';
    }

    /**
     * Add a custom action to order actions select box on edit order page
     * Only added for paid orders that haven't fired this action yet
     *
     * @param array $actions order actions array to display
     * @return array - updated actions
     */
    function wc_add_order_meta_xls_action( $order ){  
        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
        ?>
        <p class="form-field form-field-wide wc-customer-user"></p>
        <p class="form-field form-field-wide wc-customer-user">
            <h4><?php _e( 'Excel Slip' ); ?></h4>
            <?php 
                echo '<a href="'.wp_nonce_url( admin_url( 'admin-ajax.php?action=excel_slip&order_id=' . $order_id ), 'download_excel_slip' ).'"><span class="dashicons dashicons-media-spreadsheet"></span> ดาวน์โหลด excel slip</a>';
            ?>
        </p>
    <?php }

}

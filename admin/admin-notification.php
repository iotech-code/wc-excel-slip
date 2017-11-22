<?php

function wc_rq_notification() {
    echo '<div class="notice notice-error">
        <p>You must activate <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> before use Woocommerce Excel Slip.</p>
    </div>';
    
}

add_action('admin_notices', 'wc_rq_notification');

<?php
/* 
name: default
author: iOTech
author_url: https://iotech.co.th
version: 1.0
 */


//set config for report
$config=array(
    'template' => 'invoice.xls',
    'templateDir'=> __DIR__ . '/'
);

foreach ($order->get_items() as $item_id => $item_data) {

	$_product = wc_get_product( $item_data->get_product_id() );
	// $product = $item_data->get_product(); // Get an instance of corresponding the WC_Product object
	$product_name = $_product->get_name(); // Get the product name
	$item_quantity = $item_data->get_quantity(); // Get the item quantity
	$item_price = $_product->get_price(); // Get the item price
	$item_total = $item_data->get_total(); // Get the item line total
	$item_sku = $_product->get_sku(); // Get th item SKU

	$products[] = [
		'description' => $product_name,
		'sku' => $item_sku,
		'qty' => $item_quantity,
		'price' => $item_price,
		'total' => $item_total
	];
}

if($option->wc_excel_slip_company_tel != '') {
    $option->wc_excel_slip_company_tel = "โทร. ".$option->wc_excel_slip_company_tel;
}
if($option->wc_excel_slip_company_vatcode != '') {
    $option->wc_excel_slip_company_vatcode = "เลขที่ผู้เสียภาษี. ".$option->wc_excel_slip_company_vatcode;
}

$R=new PHPReport($config);
$R->load(array(
			array(
				'id'=>'inv',
				'data'=>array(
						'date'=>$order->date_created->date('d/m/Y H:i'),
						'number'=>$order->id,
						'customer_id'=>$order->data['customer_id'],
						'order_id'=>$order->id,
						'customer_name'=>$order->data['billing']['first_name'] .' '. $order->data['billing']['last_name'],
						'cus_address_1'=>$order->data['billing']['address_1'] .' '. $order->data['billing']['address_2'],
						'cus_address_2'=>$order->data['billing']['state'] .' '. $order->data['billing']['postcode'],
						'phone'=>$order->data['billing']['phone'], 
						'email' => $order->data['billing']['email'], 
						'shipping' => $order->get_shipping_method(), 
						'payment' => $order->payment_method_title
					)
                ),
            array(
                'id' => 'opt',
                'data' => array(
						'shopname' => $option->wc_excel_slip_shop_name, 
						'shop_address' => $option->wc_excel_slip_company_name . " " . $option->wc_excel_slip_company_address_1 . " " . $option->wc_excel_slip_company_address_2 . " " . $option->wc_excel_slip_company_province . " " . $option->wc_excel_slip_company_postcode, 
						'shop_address_2' => $option->wc_excel_slip_company_vatcode . " " . $option->wc_excel_slip_company_tel
					)
            ),
			array(
				'id'=>'prod',
				'repeat'=>true,
				'data'=>$products,
				'minRows'=>2,
				'format'=>array(
						'price'=>array('number'=>array('sufix'=>'฿','decimals'=>2)),
						'total'=>array('number'=>array('sufix'=>'฿','decimals'=>2))
					)
				),
			array(
				'id'=>'total',
				'data'=>array('price'=>$order->data['total'],'shipping'=>$order->data['shipping_total']),
				'format'=>array(
                        'price'=>array('number'=>array('sufix'=>'฿','decimals'=>2)),
                        'shipping'=>array('number'=>array('sufix'=>'฿','decimals'=>2))
					)
                ),
            array(
                'id' => 'user',
                'data' => array('comment'=>$order->data['customer_note'])
            )    
            
			)
        );
// Can render html, excel, excel2003 or PDF
echo $R->render('excel2003', $filename);
exit();
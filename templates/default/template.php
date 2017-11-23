<?php
/* 
name: default
author: iOTech
author_url: https://iotech.co.th
version: 1.0
 */

 // Must import this file.
include( plugin_dir . 'includes/PHPReport.php' );

//which template to use
$template='invoice.xls';

//set absolute path to directory with template files
$templateDir= __DIR__ . '/';

//set config for report
$config=array(
    'template'=>$template,
    'templateDir'=>$templateDir
);

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
				'data'=>array('date'=>$order->date_created->date('d/m/Y H:i'),'number'=>$order->id,'customerid'=>$order->data['customer_id'],'orderid'=>$order->id,'customer_name'=>$order->data['billing']['first_name'] .' '. $order->data['billing']['last_name'],'address'=>$order->data['billing']['address_1'] .' '. $order->data['billing']['address_2'],'city'=>$order->data['billing']['state'] .' '. $order->data['billing']['postcode'],'phone'=>$order->data['billing']['phone'], 'email' => $order->data['billing']['email'], 'shipping' => $order->get_shipping_method(), 'payment' => $order->get_payment_method())
                ),
            array(
                'id' => 'opt',
                'data' => array('shopname' => $option->wc_excel_slip_shop_name, 'shop_address' => $option->wc_excel_slip_company_name . " " . $option->wc_excel_slip_company_address_1 . " " . $option->wc_excel_slip_company_address_2 . " " . $option->wc_excel_slip_company_province . " " . $option->wc_excel_slip_company_postcode, 'shop_address_2' => $option->wc_excel_slip_company_vatcode . " " . $option->wc_excel_slip_company_tel)
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
//we can render html, excel, excel2003 or PDF
echo $R->render('excel2003', $filename);
exit();
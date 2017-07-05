<?php


	$refno = $_POST['refno'];
	$increment_id = substr($refno, 1); 
    $status = 'Complete';



require_once 'app/Mage.php';
Mage::app('default');

//database read adapter
$read = Mage::getSingleton('core/resource')->getConnection('core_read');
//database write adapter 
$write = Mage::getSingleton('core/resource')->getConnection('core_write'); 



$data = array("status" => "$status"); 
$where = "increment_id = '$increment_id'"; $write->update("sales_flat_order_grid", $data, $where);    // แก้ไขตรงนี้     sales_flat_order_grid  แก้ไขให้เป็น  Tables Prefix ที่ได้ตั้งค่าไว้  เช่น  ในตัวอย่าง เป็น  sales_flat_order_grid แก้ไขเป็น  mg_sales_flat_order_grid



$data_order = array("status" => "$status"); 
$where = "increment_id = '$increment_id'"; $write->update("sales_flat_order", $data_order, $where);   // แก้ไขตรงนี้     sales_flat_order  แก้ไขให้เป็น  Tables Prefix ที่ได้ตั้งค่าไว้  เช่น  ในตัวอย่าง เป็น  sales_flat_order แก้ไขเป็น  mg_sales_flat_order 

?>
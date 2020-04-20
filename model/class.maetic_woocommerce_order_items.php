<?php
class MaeTick_Woocommerce_Order_Items {
    public static function init($order_id) {
    }

    public static function get_order_items_id($order_id){
	    $order = wc_get_order($order_id);
	    $order_items = $order->get_items();
	    $order_items_id =[];

	    foreach ($order_items as $order_item_id => $order_item) {
	    	array_push($order_items_id,$order_item_id);
	    }
	    return $order_items_id;
    }
}
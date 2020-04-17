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



//	    // Iterating through each WC_Order_Item_Product objects
//	    foreach ($order->get_items() as $item_key => $item ):
//
//		    ## Using WC_Order_Item methods ##
//
//		    // Item ID is directly accessible from the $item_key in the foreach loop or
//		    $item_id = $item->get_id();
//
//		    ## Using WC_Order_Item_Product methods ##
//
//		    $product      = $item->get_product(); // Get the WC_Product object
//
//		    echo $product_id   = $item->get_product_id(); // the Product id
//		    $variation_id = $item->get_variation_id(); // the Variation id
//
//		    $item_type    = $item->get_type(); // Type of the order item ("line_item")
//
//		    echo $item_name    = $item->get_name(); // Name of the product
//		    $quantity     = $item->get_quantity();
//		    $tax_class    = $item->get_tax_class();
//		    $line_subtotal     = $item->get_subtotal(); // Line subtotal (non discounted)
//		    $line_subtotal_tax = $item->get_subtotal_tax(); // Line subtotal tax (non discounted)
//		    $line_total        = $item->get_total(); // Line total (discounted)
//		    $line_total_tax    = $item->get_total_tax(); // Line total tax (discounted)
//
//		    ## Access Order Items data properties (in an array of values) ##
//		    $item_data    = $item->get_data();
//
//		    $product_name = $item_data['name'];
//		    $product_id   = $item_data['product_id'];
//		    $variation_id = $item_data['variation_id'];
//		    $quantity     = $item_data['quantity'];
//		    $tax_class    = $item_data['tax_class'];
//		    $line_subtotal     = $item_data['subtotal'];
//		    $line_subtotal_tax = $item_data['subtotal_tax'];
//		    $line_total        = $item_data['total'];
//		    $line_total_tax    = $item_data['total_tax'];
//
//		    // Get data from The WC_product object using methods (examples)
//		    $product        = $item->get_product(); // Get the WC_Product object
//
//		    $product_type   = $product->get_type();
//		    $product_sku    = $product->get_sku();
//		    $product_price  = $product->get_price();
//		    $stock_quantity = $product->get_stock_quantity();
//
//	    endforeach;
//	    return $order = wc_get_order( $order_id );

<?php 
class ModelPaymentAlipayDual extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/alipay_dual');
		
		if ($this->config->get('alipay_dual_status')) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'alipay_dual',
        		'title'      => $this->language->get('text_title'),
        		'terms'      => '',
						'sort_order' => $this->config->get('alipay_dual_sort_order')
      		);
    	}
	
    	return $method_data;
  	}
  	
	public function getOrderProduct($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {			

			return array(
				'order_id'                => $order_query->row['order_id'],
				'product_id'                => $order_query->row['product_id'],
				'product_name'                => $order_query->row['name'],
				'product_model'               => $order_query->row['model'],
				'product_quantity'           => $order_query->row['quantity'],
				'product_total'                => $order_query->row['total']				
			);
		} else {
			return false;
		}
		
	}
}
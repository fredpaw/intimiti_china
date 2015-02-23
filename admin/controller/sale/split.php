<?php
class ControllerSaleSplit extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/split');

		$this->load->model('sale/split');
		
		if(!isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = '';
		}
		
		$order = $this->model_sale_split->getOrder($order_id);
		$order_products = $this->model_sale_split->getOrderProducts($order_id);
		$order_option = $this->model_sale_split->getOrder($order_id);
	}
}
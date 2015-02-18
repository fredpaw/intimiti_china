<?php
class ControllerModuleCategoryproduct extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('module/categoryproduct');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('categoryproduct', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
						
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_choose_from']	= $this->language->get('entry_choose_from');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$data['entry_custom_title'] = $this->language->get('entry_custom_title');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/categoryproduct', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/categoryproduct', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);		
		}
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/categoryproduct', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/categoryproduct', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['choose_from'])) {
			$data['choose_from'] = $this->request->post['choose_from'];
		} elseif (!empty($module_info)) {
			$data['choose_from'] = $module_info['choose_from'];
		} else {
			$data['choose_from'] = '';
		}
		
		$this->load->model('catalog/product');
		
		$data['products'] = array();
		
		if (isset($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($module_info)) {
			$products = $module_info['product'];
		} else {
			$products = array();
		}	
		
		if($products != null) {
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		}
		
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['category'])) {
			$data['category_id'] = $this->request->post['category'];
		} elseif (!empty($module_info)) {
			$data['category_id'] = $module_info['category'];
		} else {
			$data['category_id'] = '';
		}
		
		$categories_info = $this->model_catalog_category->getcategories();
		
		foreach ($categories_info as $category_info) {
			if ($category_info) {
				$data['categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'    => $category_info['name']
				);
			}
		}
		
		if (isset($this->request->post['sort_by'])) {
			$data['sort_by'] = $this->request->post['sort_by'];
		} elseif (!empty($module_info)) {
			$data['sort_by'] = $module_info['sort_by'];
		} else {
			$data['sort_by'] = '';
		}
		
		$data['sorts'] = array();
		
		$data['sorts'][] = array(
			'text' => 'name',
			'value' => 'pd.name'
		);
		
		$data['sorts'][] = array(
			'text' => 'model',
			'value' => 'p.model'
		);
		
		$data['sorts'][] = array(
			'text' => 'quantity',
			'value' => 'p.quantity'
		);
		
		$data['sorts'][] = array(
			'text' => 'price',
			'value' => 'p.price'
		);
		
		$data['sorts'][] = array(
			'text' => 'rating',
			'value' => 'rating'
		);
		
		$data['sorts'][] = array(
			'text' => 'order',
			'value' => 'p.sort_order'
		);
		
		$data['sorts'][] = array(
			'text' => 'date',
			'value' => 'p.date_added'
		);
		
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 4;
		}
		
		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}
		
		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}
		
		if (isset($this->request->post['custom_title'])) {
			$data['custom_title'] = $this->request->post['custom_title'];
		} elseif (!empty($module_info)) {
			$data['custom_title'] = $module_info['custom_title'];
		} else {
			$data['custom_title'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('module/categoryproduct.tpl', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/categoryproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		return !$this->error;
	}
}
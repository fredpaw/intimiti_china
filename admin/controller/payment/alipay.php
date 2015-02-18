<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 支付宝接口 - alipay payment
 *
 * Since   Version 2.0.0.0
 * Author  Woods Zhang       -> HECart Dev Team
 * Link    hoojar@163.com    -> http://www.hecart.com/
 *
 * 版权 2006 - 2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006 - 2014, Hoojar Studio All Rights Reserved.
 *
 */
class ControllerPaymentAlipay extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('payment/alipay');
		$this->document->settitle($this->language->get('heading_title'));

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate())
		{
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('alipay', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['breadcrumbs']   = array();
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_payment'),
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('payment/alipay', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$data['heading_title']  = $this->language->get('heading_title');
		$data['text_edit']      = $this->language->get('text_edit');
		$data['text_enabled']   = $this->language->get('text_enabled');
		$data['text_disabled']  = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_seller_email']  = $this->language->get('entry_seller_email');
		$data['entry_security_code'] = $this->language->get('entry_security_code');
		$data['entry_partner']       = $this->language->get('entry_partner');
		$data['entry_trade_type']    = $this->language->get('entry_trade_type');
		$data['entry_trade_type1']   = $this->language->get('entry_trade_type1');
		$data['entry_trade_type2']   = $this->language->get('entry_trade_type2');
		$data['entry_trade_type3']   = $this->language->get('entry_trade_type3');
		$data['entry_anti_phishing'] = $this->language->get('entry_anti_phishing');
		$data['entry_total']         = $this->language->get('entry_total');
		$data['entry_order_status']  = $this->language->get('entry_order_status');
		$data['entry_geo_zone']      = $this->language->get('entry_geo_zone');
		$data['entry_status']        = $this->language->get('entry_status');
		$data['entry_sort_order']    = $this->language->get('entry_sort_order');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['help_status'] = $this->language->get('help_status');
		$data['help_total']  = $this->language->get('help_total');

		$data['error_warning']      = isset($this->error['warning']) ? $this->error['warning'] : '';
		$data['error_trade_type']   = isset($this->error['trade_type']) ? $this->error['trade_type'] : '';
		$data['error_partner']      = isset($this->error['partner']) ? $this->error['partner'] : '';
		$data['error_secrity_code'] = isset($this->error['secrity_code']) ? $this->error['secrity_code'] : '';
		$data['error_email']        = isset($this->error['email']) ? $this->error['email'] : '';

		$data['button_save']   = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['action']        = $this->url->link('payment/alipay', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']        = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['alipay_seller_email']))
		{
			$data['alipay_seller_email'] = $this->request->post['alipay_seller_email'];
		}
		else
		{
			$data['alipay_seller_email'] = $this->config->get('alipay_seller_email');
		}

		if (isset($this->request->post['alipay_security_code']))
		{
			$data['alipay_security_code'] = $this->request->post['alipay_security_code'];
		}
		else
		{
			$data['alipay_security_code'] = $this->config->get('alipay_security_code');
		}

		if (isset($this->request->post['alipay_partner']))
		{
			$data['alipay_partner'] = $this->request->post['alipay_partner'];
		}
		else
		{
			$data['alipay_partner'] = $this->config->get('alipay_partner');
		}

		if (isset($this->request->post['alipay_trade_type']))
		{
			$data['alipay_trade_type'] = $this->request->post['alipay_trade_type'];
		}
		else
		{
			$data['alipay_trade_type'] = $this->config->get('alipay_trade_type');
		}

		if (isset($this->request->post['alipay_order_status_id']))
		{
			$data['alipay_order_status_id'] = $this->request->post['alipay_order_status_id'];
		}
		else
		{
			$data['alipay_order_status_id'] = $this->config->get('alipay_order_status_id');
		}
		if (isset($this->request->post['alipay_total']))
		{
			$data['alipay_total'] = $this->request->post['alipay_total'];
		}
		else
		{
			$data['alipay_total'] = $this->config->get('alipay_total');
		}
		if (isset($this->request->post['alipay_geo_zone_id']))
		{
			$data['alipay_geo_zone_id'] = $this->request->post['alipay_geo_zone_id'];
		}
		else
		{
			$data['alipay_geo_zone_id'] = $this->config->get('alipay_geo_zone_id');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['alipay_status']))
		{
			$data['alipay_status'] = $this->request->post['alipay_status'];
		}
		else
		{
			$data['alipay_status'] = $this->config->get('alipay_status');
		}

		if (isset($this->request->post['alipay_sort_order']))
		{
			$data['alipay_sort_order'] = $this->request->post['alipay_sort_order'];
		}
		else
		{
			$data['alipay_sort_order'] = $this->config->get('alipay_sort_order');
		}

		$data['user']        = $this->user;
		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/alipay.tpl', $data));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/alipay'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['alipay_partner'])
		{
			$this->error['partner'] = $this->language->get('error_partner');
		}

		if (!$this->request->post['alipay_security_code'])
		{
			$this->error['secrity_code'] = $this->language->get('error_secrity_code');
		}

		if (!$this->request->post['alipay_seller_email'])
		{
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!isset($this->request->post['alipay_trade_type']))
		{
			$this->error['trade_type'] = $this->language->get('error_trade_type');
		}

		return !$this->error;
	}
}
?>
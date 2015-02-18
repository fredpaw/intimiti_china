<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 支付宝接口 - alipay payment
 *
 * Since   Version 1.1.2
 * Author  Woods Zhang       -> HECart Dev Team
 * Link    hoojar@163.com    -> http://www.hecart.com/
 *
 * 版权 2006 - 2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006 - 2014, Hoojar Studio All Rights Reserved.
 *
 */
class ModelPaymentAlipay extends Model
{
	public function getMethod($address, $total)
	{
		$this->load->language('payment/alipay');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '{$this->config->get('alipay_geo_zone_id')}' AND country_id = '{$address['country_id']}' AND (zone_id = '{$address['zone_id']}' OR zone_id = '0')");

		if ($this->config->get('alipay_total') > $total)
		{
			$status = false;
		}
		elseif (!$this->config->get('alipay_geo_zone_id'))
		{
			$status = true;
		}
		elseif ($query->num_rows)
		{
			$status = true;
		}
		else
		{
			$status = false;
		}

		$method_data = array();
		if ($status)
		{
			$method_data = array(
				'code'       => 'alipay',
				'title'      => $this->language->get('text_title'),
				'terms'      => $this->language->get('text_image'),
				'sort_order' => $this->config->get('alipay_sort_order')
			);
		}

		return $method_data;
	}

	public function getOrderTotal($order_id)
	{
		$result  = $this->db->query("SELECT `code`, `value` FROM " . DB_PREFIX . "order_total WHERE order_id = '{$order_id}'");
		$tmp_arr = array();
		foreach ($result->rows as $v)
		{
			$tmp_arr[$v['code']] = $v['value'];
		}

		return $tmp_arr;
	}
}
?>
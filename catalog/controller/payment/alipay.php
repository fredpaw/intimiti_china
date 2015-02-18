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
class ControllerPaymentAlipay extends Controller
{
	/**
	 * 数据传输协议：http https
	 *
	 * @var string
	 */
	public $protocol = 'https';

	/**
	 * 数据传输方式：get post
	 *
	 * @var string
	 */
	public $method = 'post';

	/**
	 * 字符集
	 *
	 * @var string
	 */
	public $charset = 'utf-8';

	/**
	 * 加密方式 系统默认(不要修改)
	 *
	 * @var string
	 */
	private $_sign_type = 'MD5';

	/**
	 * 支付接口
	 *
	 * @var string
	 */
	private $_gateway = 'mapi.alipay.com/gateway.do?';

	/**
	 * 展示支付页面
	 */
	public function index()
	{
		if (empty($this->session->data['order_id']))
		{
			exit('Can not get order id');
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if (empty($order_info))
		{
			exit('Can not get order info');
		}

		/**
		 * 组合商品信息
		 */
		$product_name = $product_desc = '';
		foreach ($this->cart->getProducts() as $product)
		{
			$product_name .= "{$product['name']}|";
			$product_price = $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']);
			$product_desc .= "Name:{$product['name']}, Quantity:{$product['quantity']}, Price:{$order_info['currency_code']} {$product_price}|";
		}

		/**
		 * 同步消息通知地址
		 */
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$notify_url = HTTPS_SERVER . 'alipay_notify.php';
		}
		else
		{
			$notify_url = HTTP_SERVER . 'alipay_notify.php';
		}

		/**
		 * 交易数据
		 */
		$trade_type                  = $this->config->get('alipay_trade_type');
		$parameter                   = array();
		$parameter['service']        = $trade_type; //交易类型
		$parameter['payment_type']   = 1; //默认为1;不需要修改
		$parameter['partner']        = $this->config->get('alipay_partner'); //合作商户号
		$parameter['seller_email']   = $this->config->get('alipay_seller_email'); //卖家邮箱，必填
		$parameter['return_url']     = $this->url->link('payment/alipay/result'); //同步返回消息通知页面，用于提示商户订单状态
		$parameter['notify_url']     = $notify_url; //异步返回消息通知页面，用于告知商户订单状态
		$parameter['show_url']       = $this->url->link('checkout/cart'); //商品相关网址
		$parameter['_input_charset'] = $this->charset; //字符集，默认为UTF-8
		$parameter['out_trade_no']   = "hecart-{$order_info['order_id']}"; //商家订单编号，必填保证唯一性
		$parameter['subject']        = $this->special_chars(substr($product_name, 0, -1)); //商品名称，必填
		$parameter['body']           = $this->special_chars(substr($product_desc, 0, -1)); //商品描述，必填

		/**
		 * 判断交易类型是否为即时交易,是则只需填写订单总金额其他参数无需填写
		 */
		if ($trade_type == 'create_direct_pay_by_user')
		{
			$parameter['total_fee'] = $this->currency->format($order_info['total'], 'CNY', '', false); //商品总金额
		}
		else
		{
			/**
			 * 获取订单详细金额信息
			 */
			$this->load->model('payment/alipay');
			$order_total       = $this->model_payment_alipay->getOrderTotal($order_info['order_id']);
			$logistics_fee     = isset($order_total['shipping']) ? $order_total['shipping'] : 0; //物流费用
			$logistics_payment = ($logistics_fee > 0) ? 'BUYER_PAY' : 'SELLER_PAY'; //运费支付者

			$parameter['price']             = $this->currency->format($order_total['total'] - $logistics_fee, 'CNY', '', false); //商品单价，必填（价格不能为0）
			$parameter['quantity']          = 1; //商品数量，必填
			$parameter['paymethod']         = 'directPay'; //默认支付方式，四个值可选：bankPay(网银); cartoon(卡通); directPay(余额); CASH(网点支付)
			$parameter['logistics_fee']     = $this->currency->format($logistics_fee, 'CNY', '', false); //物流配送费用
			$parameter['logistics_type']    = 'EXPRESS'; //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
			$parameter['logistics_payment'] = $logistics_payment; //SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		}

		/**
		 * 组合交易数据
		 */
		ksort($parameter);
		reset($parameter); //排序与重置指针
		$pay_data = $this->url2input($parameter);
		$my_sign  = $this->get_sign(substr($pay_data['sign'], 1) . $this->config->get('alipay_security_code')); //生成签名

		/**
		 * 分析交易数据传输所采用的方式
		 */
		$pay_url = "{$this->protocol}://{$this->_gateway}";
		if (strtolower($this->method) == 'get')
		{
			$this->data['payurl'] = "{$pay_url}sign={$my_sign}&sign_type={$this->_sign_type}{$pay_data['url']}";
		}
		else
		{
			$data['action'] = "{$pay_url}_input_charset={$this->charset}";
			$data['alipay'] = "<input type='hidden' name='sign' value='{$my_sign}' />";
			$data['alipay'] .= "<input type='hidden' name='sign_type' value='{$this->_sign_type}' />{$pay_data['input']}";
		}

		$data['button_confirm'] = $this->language->get('button_confirm');
		$template               = $this->config->get('config_template') . '/template/payment/alipay.tpl';
		if (!file_exists(DIR_TEMPLATE . $template))
		{
			$template = 'default/template/payment/alipay.tpl';
		}

		return $this->load->view($template, $data);
	}

	/**
	 * 获取签名
	 *
	 * @param string $sign_str 要签名的字符串
	 * @return string 已签好名的字符串
	 */
	public function get_sign($sign_str)
	{
		$mysign = '';
		switch (strtoupper($this->_sign_type))
		{
			case 'MD5':
				$mysign = md5($sign_str);
				break;
			case 'DSA':
				exit('DSA 签名方法待后续开发，请先使用MD5签名方式');
				break;
			default:
				exit("暂不支持{$this->_sign_type}类型的签名方式");
				break;
		}

		return $mysign;
	}

	/**
	 * 支付成功后对订单的处理
	 *
	 */
	public function callback()
	{
		if (empty($_POST))
		{
			$_POST = $_GET;
		}

		/**
		 * 获取参数
		 */
		$this->load->language('payment/alipay');
		$order_id               = substr($this->get_val($_POST, 'out_trade_no'), 7); //获取传递过来的订单号
		$trade_status           = $this->get_val($_POST, 'trade_status'); //支付状态结果
		$trade_money            = floatval($this->get_val($_POST, 'total_fee')); //获取传递过来的总价格
		$config_order_status_id = $this->config->get('config_order_status_id'); //系统默认的订单状态
		$alipay_order_status_id = $this->config->get('alipay_order_status_id'); //alipay设置的订单状态

		/**
		 * 支付失败，保存日志与修改订单失败状态
		 */
		if (!$this->notify_verify($_POST))
		{
			$comment = $this->language->get('text_title') . $this->language->get('text_fail');
			$this->log->write("Order ID: {$order_id} {$comment}, Trade status:{$trade_status}");

			exit('fail'); //输入结果告之ALIPAY异步通知处理失败
		}

		/**
		 * 支付成功，确定订单没有重复支付
		 */
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id, false);
		if ($order_info && $order_info['order_status_id'] != $alipay_order_status_id) //判断订单与金额是否正确 确定支付和订单额度一致
		{
			$order_total = $this->currency->format($order_info['total'], 'CNY', '', false);
			if ($trade_money < floatval($order_total))
			{
				$comment = $this->language->get('text_title') . " paid:{$trade_money} < total:{$order_total}";
				$this->log->write("Order ID: {$order_id} {$comment}");
				$this->model_checkout_order->addOrderHistory($order_id, $config_order_status_id, $comment);
			}
			else //支付金额符合订单金额，修改订单状态
			{
				if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS' || $trade_status == 'WAIT_SELLER_SEND_GOODS')
				{
					$comment = $this->language->get('text_title') . $this->language->get('text_success');
					$this->log->write("Order ID: {$order_id} {$comment}, Trade status:{$trade_status}");
					$this->model_checkout_order->addOrderHistory($order_id, $alipay_order_status_id, $comment, true); //交易完成
				}
				else
				{
					$comment = $this->language->get('text_title') . $this->language->get('text_waiting');
					$this->log->write("Order ID: {$order_id} {$comment}, Trade status:{$trade_status}");
				}
			}
		}

		exit('success'); //输入结果告之ALIPAY异步通知处理完成
	}

	/**
	 * 判断是否支付成功
	 */
	public function result()
	{
		if (empty($_POST))
		{
			$_POST = $_GET;
		}

		/**
		 * 判断签名是否正确
		 */
		if ($this->notify_verify($_POST))
		{
			$this->response->redirect($this->url->link('checkout/success'));
			exit();
		}

		/**
		 * 支付失败提示
		 */
		$this->load->language('payment/alipay');
		$data['breadcrumbs']     = array();
		$data['heading_title']   = $this->language->get('text_title');
		$data['text_error']      = $data['heading_title'] . ' : ' . $this->language->get('text_fail');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue']        = $this->url->link('common/home');

		$data['column_left']    = $this->load->controller('common/column_left');
		$data['column_right']   = $this->load->controller('common/column_right');
		$data['content_top']    = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer']         = $this->load->controller('common/footer');
		$data['header']         = $this->load->controller('common/header');

		$template = $this->config->get('config_template') . '/template/error/not_found.tpl';
		if (!file_exists(DIR_TEMPLATE . $template))
		{
			$template = 'default/template/error/not_found.tpl';
		}
		$this->response->setOutput($this->load->view($template, $data));
	}

	/**
	 * 校验支付通知
	 *
	 * @param array $pdata
	 * @return boolean 数据完整正确返回true否则为false
	 */
	public function notify_verify($pdata)
	{
		//组合数据
		$parameter = array();
		if (is_array($pdata))
		{
			foreach ($pdata as $k => $v)
			{
				if ($k == 'sign' || $k == 'sign_type' || $k == 'act' || $k == 'route' || $v == '')
				{
					continue;
				}
				$parameter[$k] = $this->get_val($pdata, $k);
			}
		}

		/**
		 * 生成签名并校验
		 */
		ksort($parameter); //排序
		reset($parameter); //重置指针
		$pdata['sign'] = isset($pdata['sign']) ? $pdata['sign'] : '';
		$pay_data      = $this->url2input($parameter);
		$my_sign       = $this->get_sign(substr($pay_data['sign'], 1) . $this->config->get('alipay_security_code')); //生成签名
		return (strtoupper($my_sign) != strtoupper($pdata['sign'])) ? false : true; //签名结果
	}

	/**
	 * 生成URL的GET格式数据与hidden表单
	 *
	 * @param array $data 数组
	 * @return array (sign, url, input)
	 */
	protected function &url2input($data)
	{
		$arr = array(
			'sign'  => '',
			'url'   => '',
			'input' => ''
		);
		if (is_array($data) && !empty($data))
		{
			foreach ($data as $k => $v)
			{
				$arr['input'] .= "<input type='hidden' name='{$k}' value=\"{$v}\" />";
				if ($v !== '') //值不为空的则连接
				{
					$arr['sign'] .= "&{$k}={$v}"; //签名数据内容
					$v = urlencode($v); //此处主要为了兼容不同浏览器GET方式
					$arr['url'] .= "&{$k}={$v}"; //GET值数据内容
				}
			}
		}

		return $arr;
	}

	/**
	 * 获取支付接口所返回的数据
	 *
	 * @param array  $data    数组
	 * @param string $name    对象名称
	 * @param string $default 默认值
	 * @return string
	 */
	protected function get_val($data, $name, $default = '')
	{
		if (!is_array($data))
		{
			return $default;
		}

		return isset($data[$name]) ? trim($data[$name]) : $default;
	}

	/**
	 * 特殊字符处理
	 *
	 * @param string $str 字符串
	 * @return mixed 字符串
	 */
	protected function special_chars($str)
	{
		$search  = array(
			'&',
			'"'
		);
		$replace = array(
			'%26',
			'%22'
		);

		return str_replace($search, $replace, $str);
	}
}
?>
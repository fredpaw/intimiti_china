<?php
// Version By www.opencart.cn
/*
版权所有：www.opencart.cn
这不是一个免费的版本，购买者可以修改调试，严禁传播复制。
自己修改或者传播带来的一切后果自负。
*/
require_once("alipay_submit.class.php");
require_once("alipay_notify.class.php");

class ControllerPaymentAlipayDual extends Controller {
 public function index() {
		// 为 alipay.tpl 准备数据
    $data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');

		// url

		$data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$data['custom'] = $encryption->encrypt($this->session->data['order_id']);
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		// 获取订单数据
		$this->load->model('checkout/order');
		$this->load->model('payment/alipay_dual');

		$order_id = $this->session->data['order_id'];		
		$order_info = $this->model_checkout_order->getOrder($order_id);
	  $order_product_info = $this->model_payment_alipay_dual->getOrderProduct($order_id);

		// 计算提交地址
		//商户信息
					//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
					
					//合作身份者id，以2088开头的16位纯数字							
				  	$alipay_config['partner']		= trim($this->config->get('alipay_dual_partner'));				//合作伙伴ID					
					//安全检验码，以数字和字母组成的32位字符
			  	  $alipay_config['key']			= trim($this->config->get('alipay_dual_security_code'));	//安全检验码key					
					
					//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
					
					
					//签名方式 不需修改
					$alipay_config['sign_type']    = strtoupper('MD5');
					
					//字符编码格式 目前支持 gbk 或 utf-8
					$alipay_config['input_charset']= strtolower('utf-8');
					
					//ca证书路径地址，用于curl中ssl校验
					//请保证cacert.pem文件在当前文件夹目录中
					$alipay_config['cacert']    = getcwd().'\\cacert.pem';
					
					//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
					$alipay_config['transport']    = 'http';
	
	//获取环境				
    		$currency_code = $this->config->get('alipay_dual_currency_code');				//人民币代号（CNY）
				$item_name = $this->config->get('config_store');
				$first_name = $order_info['payment_firstname'];	
				$last_name = $order_info['payment_lastname'];
    		
				$total = $order_info['total'];
				if($currency_code == ''){
					$currency_code = 'CNY';
				}
				$currency_value = $this->currency->getValue($currency_code);
				$amount = $total * $currency_value;
				$amount = number_format($amount,2,'.','');
				
							
		
        
         //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = HTTP_SERVER . 'catalog/controller/payment/alipay_dual_notify_url.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
         $return_url = $this->url->link('checkout/success');                //成功后返回页面
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        //$seller_email = $_POST['WIDseller_email'];
         $seller_email = $this->config->get('alipay_dual_seller_email');		// 商家邮箱
        //必填

        //商户订单号
        //$out_trade_no = $_POST['WIDout_trade_no'];
        $out_trade_no = $order_id;
       
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        //$subject = $_POST['WIDsubject'];
        $subject = $order_product_info['product_name'] ; 
        //必填

        //付款金额
        //$price = $_POST['WIDprice'];
         $price = $amount;
        //必填

        //商品数量
        $quantity = "1";
        //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
        //物流费用
        $logistics_fee = "0.00";
        //必填，即运费
        //物流类型
        $logistics_type = "EXPRESS";
        //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        //物流支付方式
        $logistics_payment = "SELLER_PAY";
        //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
        
        //订单描述
        //$body = $_POST['WIDbody'];
        $body = $order_id;
        
        //商品展示地址
        //$show_url = $_POST['WIDshow_url'];
         $show_url       = HTTPS_SERVER . 'index.php';
        //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html

        //收货人姓名
        $receive_name = "sunboy";
        //如：张三

        //收货人地址
        $receive_address = "四川省成都市人民南路";
        //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号

        //收货人邮编
        $receive_zip = "611731";
        //如：123456

        //收货人电话号码
        $receive_phone = "18030492239";
        //如：0571-88158090

        //收货人手机号码
        $receive_mobile = "18030492239";
        //如：13312341234
        
  
  $parameter = array(
		"service" => "trade_create_by_buyer",
		"partner" => trim($alipay_config['partner']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"seller_email"	=> $seller_email,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"price"	=> $price,
		"quantity"	=> $quantity,
		"logistics_fee"	=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		"body"	=> $body,
		"show_url"	=> $show_url,
		"receive_name"	=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"	=> $receive_zip,
		"receive_phone"	=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
   );
      

			
	//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter,"get");
	
		$action=$html_text;
		$data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay_dual.tpl')) {

			return $this->load->view($this->config->get('config_template') . '/template/payment/alipay_dual.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/alipay_dual.tpl', $data);
		}	
		
		
	}

	
	// 支付返回后的处理,支付宝异步通知
	public function callback() {
	logResult("进入 public function callback()");
	
		// 获取商家信息
		$this->load->library('encryption');
    //商户信息
					//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		  			$alipay_config['partner']		= trim($this->config->get('alipay_dual_partner'));				//合作伙伴ID

					  $alipay_config['key']			= trim($this->config->get('alipay_dual_security_code'));	//安全检验码key
					
						$alipay_config['sign_type']    = strtoupper('MD5'); 	//签名方式 不需修改
										
						$alipay_config['input_charset']= strtolower('utf-8'); //字符编码格式 目前支持 gbk 或 utf-8
					 //ca证书路径地址，用于curl中ssl校验
					//请保证cacert.pem文件在当前文件夹目录中
					 $alipay_config['cacert']    = getcwd().'\\cacert.pem';					
					//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
					 $alipay_config['transport']    = 'http';

		//计算得出通知验证结果
		logResult("==开始认证====");
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		logResult("==认证完成====".$verify_result);
		
		
		if($verify_result) {   //认证合格
			
			logResult("==认证合格====");

		 //获取支付宝的反馈参数
			$order_id   = $_POST['out_trade_no'];   //获取支付宝传递过来的订单号
			
			logResult("==支付宝反馈参数，外部 out_trade_no====".$order_id);

			$this->load->model('checkout/order');
			
			
			// 获取订单ID
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
		
			// 存储订单至系统数据库
			if ($order_info) {
				
				$order_status_id = $order_info["order_status_id"];

				$alipay_dual_order_status_id = $this->config->get('alipay_dual_order_status_id');
				$alipay_dual_wait_buyer_pay = $this->config->get('alipay_dual_wait_buyer_pay');
				$alipay_dual_wait_seller_send = $this->config->get('alipay_dual_wait_seller_send');
				$alipay_dual_wait_buyer_confirm = $this->config->get('alipay_dual_wait_buyer_confirm');
				$alipay_dual_trade_finished = $this->config->get('alipay_dual_trade_finished');
				
       
				
				// 避免处理已完成的订单,判断订单状态是否已经处理过。
				logResult('order_id=' . $order_id . ' order_status_id=' . $order_status_id);
				
				if ($order_status_id != $alipay_dual_trade_finished) {
					logResult("No finished.");	
					
					if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
						

						//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
						  $this->model_checkout_order->addOrderHistory($order_id, $alipay_dual_wait_buyer_pay);
							echo "success";		//请不要修改或删除
							logResult('==alipay_dual_wait_buyer_pay==');
					

					}
					else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
						
							$this->model_checkout_order->addOrderHistory($order_id, $alipay_dual_wait_seller_send);

							echo "success";		//请不要修改或删除
							logResult('==alipay_dual_wait_seller_send_goods==');
						
					}
				  else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
						
							$this->model_checkout_order->addOrderHistory($order_id, $alipay_dual_wait_buyer_confirm);

							echo "success";		//请不要修改或删除
							logResult('==alipay_dual_wait_confirm_goods==');
						
					}				
					
					
					else if($_POST['trade_status'] == 'TRADE_FINISHED') {					    					
				
						
						$this->model_checkout_order->addOrderHistory($order_id, $alipay_dual_trade_finished);
						
						echo "success";		//请不要修改或删除
						
						//调试用，写文本函数记录程序运行情况是否正常
						logResult('success - alipay_dual_trade_finished');						
				    }										
					else {
						echo "success";
						logResult ("verify_ok");
					}					
					
				}  //订单状态需要修改的
				
			
			}  //订单存在的
		}//认证合格
		
	}  //callback()
}
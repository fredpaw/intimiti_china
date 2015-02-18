<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 支付宝接口 - alipay payment
 *
 * Since   Version 2.0.*.*
 * Author  Woods Zhang       -> HECart Dev Team
 * Link    hoojar@163.com    -> http://www.hecart.com/
 *
 * 版权 2006 - 2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006 - 2014, Hoojar Studio All Rights Reserved.
 *
 */
if (file_exists('config.php'))
{
	require_once('config.php');
}

if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1')))
{
	$server = HTTPS_SERVER;
}
else
{
	$server = HTTP_SERVER;
}

if (empty($_POST))
{
	$_POST = $_GET;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$server}index.php?route=payment/alipay/callback");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //POST数据
curl_setopt($ch, CURLOPT_POST, 1); //POST的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
$output = curl_exec($ch);
curl_close($ch);
exit($output);
?>
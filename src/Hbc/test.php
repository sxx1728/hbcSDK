<?php

/**
 * Created by sxx.
 * User:Sxx 
 * Date: 2018/07/16
 * Time: 15:39
 */

namespace Hbc;

require_once './SDK.php'; 


$sdk = new SDK('34342fsdfsdf', '45948422f7bd06e68c77811fdba6787d');
$sdk->desc();
echo $sdk->timestamp()."\n";
echo $sdk->nonceStr()."\n";
echo $sdk->sign('asdfasdf','1233244sdfsdf')."\n";

//echo $sdk->queryOpenId('asdfasdf');
//echo $sdk->prePay('asdfasdf', '10.0', 'USDT', 'http://www.baidu.com', 'asdfasdf234234', 'true', 'asdfasdf');

var_dump($sdk->verifyNotifySign('asdf','1234.1000','USDT', '2018-01-01 12:22:22', 'asdf2342sdfs', 'asdfa3242341234', 'asdfasdfasdf2341234sadferqpweir'));
?>

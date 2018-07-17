<?php

/**
 * Created by sxx.
 * User: Lingan
 * Date: 2018/07/16
 * Time: 15:39
 */

namespace Hbc;

class SDK{


  private $appUid;
  private $appSecret;
  private $client;
  private $headers;

  public function __construct($appUid, $appSecret)
  {
    $this->appUid = $appUid;
    $this->appSecret = $appSecret;
    $this->client = new \GuzzleHttp\Client([
      'base_uri' => 'http://open.api.test.hbtalk.org/',
      'timeout'  => 20.0,
    ]);


    $this->headers = [
      'Content-type' => 'application/json; charset=utf-8',
      'Accept' => 'application/json',
    ];
  }

  /**
   * 通过code获取用户信息
   * @param {String} code H5客户端通过login接口获取的code
   */
  public function queryOpenId($code) {

    $data = [
      'appUid' => $this->appUid,
      'code' => $code,
      'timeStamp' => $this->timestamp(),
      'nonceStr' => $this->nonceStr(),
    ];

    $objs = [
      "code=" . $data['code'],
      "app_uid=" . $data['appUid'],
      "timestamp=" . $data['timeStamp'],
      "nonce_str=" . $data['nonceStr'],
      "app_secret=" . $this->appSecret
    ];

    asort($objs);
    $string = join('&', $objs);
    $sign = $this->sign($string, $this->appSecret);

    try{

      $response = $this->client->request('POST', '/appUsers/queryOpenId', $this->headers, json_encode($data));
      if($response->getStatusCode() == 200){
        $res = json_decode($response->getBody());
        if($res->{'code'} == '0'){
          return $res->{'open_id'};
        }
      }
    }catch(\Exception $e){
      echo var_dump($e);
    }

    return "error";
  }


  /**
   * 生成预支付订单功能
   * @param {String} openUid 要成成订单Uid
   */
  public function prePay($openUid, $totalFee, $feeType, $notifyUrl, $outTradeNo, $exchange, $body) {

    $data = [
      'appUid' => $this->appUid,
      'timeStamp' => $this->timestamp(),
      'nonceStr' => $this->nonceStr(),
      'totalFee' => $totalFee,
      'feeType' => $feeType,
      'exchange' => $exchange,
      'notifyUrl' => $notifyUrl,
      'openUid' => $openUid,
      'outTradeNo' => $outTradeNo,
      'body' => $body
    ];

    $objs = [
      "app_uid=" . $data['appUid'],
      "time_stamp=" . $data['timeStamp'], 
      "nonce_str=" . $data['nonceStr'],
      "total_fee=" . $data['totalFee'],
      "fee_type=" . $data['feeType'],
      "exchange=" . $data['exchange'],
      "notify_url=" . $data['notifyUrl'],
      "open_uid=" . $data['open_uid'],
      "out_trade_no=" . $data['outTradeNo'],
      "app_secret=" . $this->appSecret
    ];

    asort($objs);
    $string = join('&', $objs);
    $sign = $this->sign($string, $this->appSecret);

    try{
      $response = $this->client->request('POST', '/payment/prepay', $this->headers, json_encode($data));
      if($response->getStatusCode() == 200){
        $resJson = json_decode($response->getBody());
        var_dump($resJson);
        if($resJson->{'code'} == '0'){
          return $resJson->{'data'};
        }
      }
    }catch(\Exception $e){
      echo var_dump($e);
    }
    return "error";
  }

  /**
   * 通过code获取用户信息
   * @param {String} code H5客户端通过login接口获取的code
   */
  public function verifyNotifySign($outTradeNo, $totalFee, $feeType, $payAt, $timestamp, $scode, $sign) {

    $objs = [
      "app_uid=" . $this->appUid,
      "time_stamp=" . $timestamp,
      "total_fee=" . $totalFee,
      "fee_type=" . $feeType,
      "out_trade_no=" . $outTradeNo,
      "payAt=" . $payAt,
      "scode=" . $scode,
      "app_secret=" . $this->appSecret,
    ];

    asort($objs);
    $string = join('&', $objs);

    $computedSign = $this->sign($string, $this->appSecret);

    return  $computedSign == $sign;
  }



  /*
   * 生成随机字符串
  */
  public function nonceStr() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $result = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < 16; $i++) {
        $result .= $chars[rand(0, $max)];
    }
    return $result;
  }

  /*
  * 生成时间戳
  */
  public function timestamp(){
    return date_timestamp_get(date_create());
  }

  /*
   * 签名
   */
  public function sign($string, $app_secret) {
    return hash_hmac('sha256', $string, $app_secret);
  }

  public function desc(){
    echo "this is my Hbc";
  }
}
?>

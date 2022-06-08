<?php

namespace Home\Controller;


use Think\Exception;

class BianController extends HomeController
{
    private static  $url = "https://api.bscscan.com/api";
    private static  $key = "99R4KKSP349682RD9K2I2KW4AWJK6M9UJG";
    
    public function test(){
        echo 1111;
    }
    public function curlRecharge(){
        $address          = '0xc2ca0eDd5D156ED45429605fb40aa23F6893E91f';
        $contractaddress  = '0xcc28a76d6530388b7a1dd585136f8be5c9033cef';
        $url = self::$url .'?module=account&action=tokentx&contractaddress='.$contractaddress.'&address='.$address.'&page=1&offset=10&startblock=0&endblock=999999999&sort=desc&apikey='.self::$key;
        
           $list = file_get_contents($url);
       
           $listArr = json_decode( $list,true);
          
           if(empty($listArr['status']) || empty($listArr['result'])){
               echo '没有数据或者状态错误'.PHP_EOL;
               die();
           }
           $resultArr = $listArr['result'];
           $modeRrechage = M('Rechage');
           $modelUser = M('User');
           $time = 86400/24*8;//时差8小时
           $rate = 1000000000000000000;
           $startTime = 1648473921;//旧的订单不处理
           //注意，启用之前，判断时间
           echo '进入便利'.PHP_EOL;
           foreach ($resultArr as $result){
              
               //如果是更早的订单，直接丢弃
               if($result['timeStamp'] < $startTime){
                  echo  date('Y-m-d H:i:s',$result['timeStamp']).'过期订单，跳过'.$result['hash'].PHP_EOL;
                   continue;
               }
             
               //发送人自己，跳过
               if($address == $result['from']){
                   echo  '自己的订单，跳过'.$result['hash'].PHP_EOL;
                   continue;
               }
             
               //校验订单是否存过
               $ret = $modeRrechage->where(['txid' => $result['hash']])->find();
               if(!empty( $ret )){
                    echo date('Y-m-d H:i:s',$result['timeStamp']).'已经充值，跳过'.$result['hash'].PHP_EOL;
                   continue;
               }
           
               //没有存过就开始入库
              $model_user = M('User');
              $model_config = M('Config');
              $model_rechage = M('Rechage');

             $address = $result['from'];
             $address = strtoupper($address);
             $txid = $result['hash'];
             $amount  =  bcdiv($result['value'],$rate);
             //校验用户是否存在，不存在的跳过
             $user_info = $model_user->where(['address' => $address])->find();
            if (!$user_info) {
                continue;
            }
            $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
            $model_config->where(['id' => 1])->setInc('now', intval($amount));
            $res = $model_rechage->add(['uid' => $user_info['id'],'txid'=>$txid, 'address'=>$address,"create_time" => time(), "rechage" => intval($amount)]);
            echo  '充值成功'.$result['hash'].PHP_EOL;

           }
      }
}
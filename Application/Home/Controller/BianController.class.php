<?php

namespace Home\Controller;


use Think\Exception;

class BianController extends HomeController
{
    private static  $url = "https://api.bscscan.com/api";
    private static  $key = "99R4KKSP349682RD9K2I2KW4AWJK6M9UJG";

    /**
     * 充值MPC
     */
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
           echo '进入收款遍历'.PHP_EOL;
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
              $model_BalanceLog = M('BalanceLog');
              $model_rechage = M('Rechage');
             $ordernum = createOrdernum();
             $address = $result['from'];
             $address = strtoupper($address);
             $txid = $result['hash'];
             $amount  =  bcdiv($result['value'],$rate,6);
             //校验用户是否存在，不存在的跳过
             $user_info = $model_user->where(['address' => $address])->find();
            if (!$user_info) {
                continue;
            }
            //修改余额
            $balance_before = $user_info['recharge'];
            $belance_after = $user_info['recharge']+$amount;
            $model_user->where(['id' => $user_info['id']])->save(['recharge'=>$belance_after]);
            $model_rechage->add([
                'uid' => $user_info['id'],
                'txid'=>$txid,
                'address'=>$address,
                'ordernum'=>$ordernum,
                "create_time" => time(),
                "rechage" => $amount
            ]);
            //插入流水
               $model_BalanceLog->add([
                   'uid' => $user_info['id'],
                   'amount'=>$amount,
                   'type'=>1,
                   'wallet'=>'mpc',
                   'balance_before'=>$balance_before,
                   'belance_after'=>$belance_after,
                   'ordernum'=>$ordernum,
                   "create_time" => time(),
               ]);
            echo  '充值成功'.$result['hash'].PHP_EOL;

           }
      }

    /**
     * 铸币商订单
     */
    public function curlZhubishang(){
        $address          = '0xc2ca0eDd5D156ED45429605fb40aa23F6893E91f';
        $contractaddress  = '0x55d398326f99059ff775485246999027b3197955';
        $url = self::$url .'?module=account&action=tokentx&contractaddress='.$contractaddress.'&address='.$address.'&page=1&offset=10&startblock=0&endblock=999999999&sort=desc&apikey='.self::$key;
        $list = file_get_contents($url);
        $listArr = json_decode( $list,true);
        if(empty($listArr['status']) || empty($listArr['result'])){
            echo '没有数据或者状态错误'.PHP_EOL;
            die();
        }
        $resultArr = $listArr['result'];
        $modelUser = M('User');
        $modelZhubishang = M('ZhubishangPlan');
        $modelZhubishangOrder = M('ZhubishangOrder');
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
            $ret = $modelZhubishangOrder->where(['txid' => $result['hash']])->find();
            if(!empty( $ret )){
                echo date('Y-m-d H:i:s',$result['timeStamp']).'已经充值，跳过'.$result['hash'].PHP_EOL;
                continue;
            }

            //没有存过就开始入库
            $address = $result['from'];
            $address = strtoupper($address);
            $txid = $result['hash'];
            $amount  =  bcdiv($result['value'],$rate);
            //校验用户是否存在，不存在的跳过
            $user_info = $modelUser->where(['address' => $address])->find();
            if (!$user_info) {
                continue;
            }
            $where = [
                'status'=>1,
                'remain_times'=>['gt',0]
            ];
            $currentPlan = $modelZhubishang->where($where)->order('amount asc')->find();
            if(empty($currentPlan)){
                echo date('Y-m-d H:i:s').'没有合适的铸币商套餐，跳过'.PHP_EOL;
                continue;
            }
            //
            if($currentPlan['amount']>$amount){
                echo date('Y-m-d H:i:s').'用户充值金额匹配不到套餐，跳过'.PHP_EOL;
                continue;
            }
            //修改用户状态
            $update = [
                'zhubishang_amount'=>$amount,
                'zhubishang'=>1,
            ];
            $modelUser->where(['id' => $user_info['id']])->save($update);
            //加入流水
            $data = [
                'uid' => $user_info['id'],
                'txid'=>$txid,
                'address'=>$address,
                "create_time" => time(),
                "amount" => intval($amount),
                'state'=>1,
            ];
            $modelZhubishangOrder->add($data);
            //修改铸币商名额
            //修改用户状态
            $update = [
                'remain_times'=>$currentPlan['remain_times']-1,
            ];
            $modelZhubishang->where(['id' => $currentPlan['id']])->save($update);
            echo  '铸币商设置成功'.$result['hash'].PHP_EOL;
        }
    }
}
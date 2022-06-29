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
                "rechage" => $amount,
                'is_recharge'=>1
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
        $time = 86400/24*8;//时差8小时
        $rate = 1000000000000000000;
        $startTime = 1655998252;//旧的订单不处理
        //注意，启用之前，判断时间
        echo '进入遍历'.PHP_EOL;
        foreach ($resultArr as $result){
            if($result['timeStamp']<=$startTime){
                echo '旧的订单跳过'.PHP_EOL;
                continue;
            }
            $modelUser = M('User');
            $modelZhubishang = M('ZhubishangPlan');
            $modelZhubishangOrder = M('ZhubishangOrder');
            //如果是更早的订单，直接丢弃
            if($result['timeStamp'] < $startTime){
                echo  date('Y-m-d H:i:s',$result['timeStamp']).'过期订单，跳过'.$result['hash'].PHP_EOL;
                continue;
            }
            echo '转账来自'.$result['from'].PHP_EOL;
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
            $amount  =  bcmul($result['value']/$rate,1,0);
            //校验用户是否存在，不存在的跳过
            $user_info = $modelUser->where(['address' => $address])->find();
            if (empty($user_info)) {
                echo date('Y-m-d H:i:s').'用户不存在，跳过'.PHP_EOL;
                continue;
            }
            if($user_info['zhubishang']==1){
                echo date('Y-m-d H:i:s').'用户已经是铸币商，跳过'.PHP_EOL;
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
            $ordernum = createOrdernum();
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
                'ordernum'=>$ordernum
            ];
            $modelZhubishangOrder->add($data);
            //修改铸币商名额
            $update = [
                'remain_times'=>$currentPlan['remain_times']-1,
            ];
            $modelZhubishang->where(['id' => $currentPlan['id']])->save($update);

            //推10%和间推5%
            $remainAmount = getAdmount('BEP20',$currency='USDT');
            if($remainAmount<$currentPlan['amount']*0.1){
                echo  '铸币商设置成功'.$result['hash'].PHP_EOL;
                continue;
            }
            //修改分红状态
            $update = [
                'is_fenhong'=>1,
            ];
            $modelZhubishangOrder = M('ZhubishangOrder');
            $modelZhubishangOrder->where(['ordernum' => $ordernum])->save($update);
            echo '修改分红';
            if($user_info['pid'] == 0){
                continue;
            }
            $this->give($user_info,$currentPlan,$remainAmount);
        }
    }

    /**分红
     * @param $user_info
     * @param $currentPlan
     * @param $remainAmount
     * @return bool
     */
    public function give($user_info,$currentPlan,$remainAmount){
        $modelUser = M('User');
        //一级分红
        $pUserInfo = $modelUser->where(['id'=>$user_info['pid']])->find();
        if(empty($pUserInfo) || $pUserInfo['zhubishang']==0){
            logInfo($user_info['address'].'铸币商：一级分红，没有上级或者上级不是铸币商'.json_encode($pUserInfo));
            return false;
        }
        sendTransaction($pUserInfo['address'],$currentPlan['amount']*0.1,'USDT');
        //二级分红
        if($pUserInfo['pid'] == 0){
            logInfo($user_info['address'].'铸币商：二级分红，没有上级或者上级不是铸币商'.json_encode($pUserInfo));
            return false;
        }
        if($remainAmount<$currentPlan['amount']*0.15){
            logInfo($user_info['address'].'铸币商：二级分红，余额不足'.$remainAmount);
            return false;
        }
        $modelUser = M('User');
        $pUserInfo = $modelUser->where(['id'=>$pUserInfo['pid']])->find();
        if(empty($pUserInfo) ||  $pUserInfo['zhubishang']==0){
            logInfo($user_info['address'].'铸币商：二级分红，没有上级或者上级不是铸币商'.json_encode($pUserInfo));
            return false;
        }
        sendTransaction($pUserInfo['address'],$currentPlan['amount']*0.05,'USDT');
        return true;
    }

    /**
     * 分红补发
     */
    public function fenghong(){
        $modelZhubishangOrder = M('ZhubishangOrder');
        $list = $modelZhubishangOrder->where(['is_fenhong'=>0])->order('id desc')->limit(10)->select();
        if(empty( $list)){
            echo '没有订单，补发结束';die;
        }
        foreach (  $list as $item){
            $modelUser = M('User');
            $user_info = $modelUser->where(['id'=>$item['uid']])->find();
            //推10%和间推5%
            $remainAmount = getAdmount('BEP20',$currency='USDT');
            if($remainAmount<$item['amount']*0.1){
                echo   $remainAmount.'分红失败，余额不足'.PHP_EOL;
                continue;
            }
            //修改分红状态
            $update = [
                'is_fenhong'=>1,
            ];
            $modelZhubishangOrder = M('ZhubishangOrder');
            $modelZhubishangOrder->where(['ordernum' => $item['ordernum']])->save($update);
            echo '修改分红';
            //一级分红
            $pUserInfo = $modelUser->where(['id'=>$user_info['pid']])->find();
            if(empty($pUserInfo) ||  $pUserInfo['zhubishang']==0){
                echo $user_info['id'].'没有一级上级，不分红'.PHP_EOL;
                continue;
            }
            sendTransaction($pUserInfo['address'],$item['amount']*0.1,'USDT');
            //二级分红
            if($remainAmount<$item['amount']*0.15){
                echo  '分红失败，余额不足'.PHP_EOL;
                continue;
            }
            $pUserInfo = $modelUser->where(['id'=>$pUserInfo['pid']])->find();
            if(empty($pUserInfo) ||  $pUserInfo['zhubishang']==0){
                continue;
            }
            sendTransaction($pUserInfo['address'],$item['amount']*0.05,'USDT');
        }
    }


}
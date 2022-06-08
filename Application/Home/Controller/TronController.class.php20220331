<?php

namespace Home\Controller;


use Think\Exception;

class TronController extends HomeController
{

    protected function _initialize()
    {
        parent::_initialize();

        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Headers: *');
        header("Content-type: text/json; charset=utf-8");
          
        $model_config = M('Config');
        $config_info = $model_config->where(['id' => 1])->find();
        if ($config_info['status'] >= 1) {
            echo json_encode(array('status' => 0, 'info' => "本轮已结束"));
            exit();
        }
    }

    private static $url = "https://bifrostadmin.shanwaapp.com/";

    public function verify()
    {
        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address = $_GET['unknown'];
        $amount = intval($_GET['amount']);

        if (intval($amount) < 1) {
            echo json_encode(array('status' => 0, 'info' => '最少质押1MPC'));
            exit();
        }


        echo json_encode(array('status' => 1, 'info' => "验证通过"));
        exit();
    }

    // 获取榜十
    public function getRank()
    {
        $model_rank = M('Rank');
        $model_user = M('User');

        $list = [];

        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $list = $model_rank->where(['create_time' => ['gt', $beginToday]])->select();
        foreach ($list as $k => $v) {
            $user_info = $model_user->where(['id' => $v['uid']])->find();
            $list[$k]['address'] = $user_info['address'];
        }

        echo json_encode(array('status' => 1, 'info' => $list));
        exit();
    }

    // 获取用户个人数据
    public function getUserInfo2()
    {
        $model_user = M('User');
        $model_earnings = M('UserEarnings');

        $user_info = [];

        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();

        $down_ids = self::_downIds([$user_info['id']], []);

        $yh = 0;
        if ($user_info['recharge'] >= 500) {
            $yh = 9;
        }
        if ($user_info['recharge'] >= 2000) {
            $yh = 12;
        }
        if ($user_info['recharge'] >= 5000) {
            $yh = 15;
        }
        if ($user_info['recharge'] >= 10000) {
            $yh = 18;
        }

        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $user_info['kjtotal'] = intval($user_info['recharge']); // 质押总值
        $user_info['start_time'] = $user_info['redeem_time'] ? date('Y-m-d H:i:s', $user_info['redeem_time']) : '---'; // 质押起始日
        $user_info['end_time'] = $user_info['redeem_time'] ? date('Y-m-d H:i:s', ($user_info['redeem_time'] + (90 * 86400))) : '---'; // 质押到期日
        $user_info['dyday'] = 90; // 质押周期
        $user_info['yields'] = $yh; // 月化收益率
        $user_info['ysy'] = floor(($user_info['recharge'] * $yh / 100) * 10000) / 10000;
        $user_info['total_hs'] = (int)$model_earnings->where(['uid' => $user_info['id']])->sum('amount'); // 历史总收益
        $user_info['total_wk'] = (int)$model_earnings->where(['uid' => $user_info['id'], 'type' => 0])->sum('amount'); // 挖矿总收益
        $user_info['total_node'] = (int)$model_earnings->where(['uid' => $user_info['id'], 'type' => 1])->sum('amount'); // 节点总收益
        $user_info['total_today'] = (int)$model_earnings->where(['uid' => $user_info['id'], 'create_time' => ['egt', $beginToday]])->sum('amount'); // 今日总收益
        $user_info['total_today_wk'] = (int)$model_earnings->where(['uid' => $user_info['id'], 'type' => 0, 'create_time' => ['egt', $beginToday]])->sum('amount'); // 今日挖矿收益
        $user_info['total_today_node'] = (int)$model_earnings->where(['uid' => $user_info['id'], 'type' => 1, 'create_time' => ['egt', $beginToday]])->sum('amount'); // 今日节点收益
        $user_info['payouts'] = $user_info['withdraw']; // 已提币总数

        $user_info['no_bonus'] = $user_info['no_withdraw']; // 未提币总数

        $user_info['level'] = self::_getJd($user_info['level']); // 节点等级
        $user_info['zt_num'] = $model_user->where(['pid' => $user_info['id']])->count(); // 直推成员
        $user_info['referrals'] = count($down_ids); // 节点成员

        // 推荐人地址
        if ($user_info['pid'] > 0) {
            $p_info = $model_user->where(['id' => $user_info['pid']])->find();
        }

        $user_info['upline'] = $p_info ? $p_info['address'] : '';

        echo json_encode(array('status' => 1, 'info' => $user_info));
        exit();
    }

    // 总数据显示
    public function getTotalInfo()
    {
        $model_user = M('User');

        // 全球会员数
        $total_users = $model_user->count();

        // 总提XLG币的数量
        $payouts = $model_user->sum('withdraw');

        // 总存XLG币的数量
        $total_deposited = $model_user->sum('recharge');

        $contract = [
            'total_users' => $total_users,
            'payouts' => $payouts,
            'total_deposited' => $total_deposited
        ];
        echo json_encode(array('status' => 1, 'info' => $contract));
        exit();
    }

    // 钱包地址关联数据库
    public function register()
    {
        $model_user = M('User');

        $address = $_GET['unknown'];
        //   $p_address = self::_decode($_GET['p_unknown']);
        $p_address = $_GET['p_unknown'];
        // 推荐人是自己，去除
        $puser = $model_user->where(['address' => $p_address])->find();

        if (!$address) {
            echo json_encode(array('status' => 1, 'info' => 'Address error'));
            exit();
        }

        $user = $model_user->where(['address' => $address])->find();

        $condition = [
            "address" => $address,
            "create_time" => time(),
            'ip'=>$_SERVER['REMOTE_ADDR']
        ];

        if ($puser) {
            $condition['pid'] = $puser['id'];
        }

        if (!$user) {
            $model_user->add($condition);
            $user = $model_user->where(['address' => $address])->find();
        }else{
$model_user->where(['address' => $address])->update(['ip'=>$_SERVER['REMOTE_ADDR']]);
}
        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();
    }
    //取消铸币商
    public function zhubishang()

    {

        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address = $_GET['unknown'];



        $user_info = $model_user->where(['address' => $address])->find();
        if (!$user_info) {
            echo json_encode(array('status' => 0, 'info' => '参数错误'));
            exit();
        }
        if ($user_info['zhubishang']==0) {
            echo json_encode(array('status' => 0, 'info' => '该用户不是铸币商'));
            exit();
        }
        // if (bcadd($user_info['redeem_time'],bcmul(86400,30))<time()) {
        //     echo json_encode(array('status' => 0, 'info' => '该用户铸币商还在30天质押时间'));
        //     exit();
        // }
        if (bcadd($user_info['redeem_time'],bcmul(86400,90))<time()) {
            echo json_encode(array('status' => 0, 'info' => '该用户铸币商还在90天质押时间'));
            exit();
        }

        $model_user->where(['id' => $user_info['id']])->setInc('usdt',3000);

        $model_user->where(['id' => $user_info['id']])->save([ 'zhubishang'=> 0]);

        // $model_config->where(['id' => 1])->setInc('now', intval($amount));
        // $res = $model_rechage->add(['uid' => $user_info['id'], "create_time" => time(), "rechage" => intval($amount)]);

        // self::_buyPledge($user_info['id'],intval($amount),intval($day),$txid);

        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();


        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
    }
    public function rechange()

        // 充值
    {

        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        $model_pledge = M('Pledge');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address = $_GET['unknown'];
        $amount = $_GET['amount'];
        $day= intval($_GET['day']);
        $txid = $_GET['txid'];
        // echo $address;
        if ($amount < 1) {
            echo json_encode(array('status' => 0, 'info' => '最少质押1MPC'));
            exit();
        }

        $user_info = $model_user->where(['address' => $address])->find();
        if (!$user_info) {
            echo json_encode(array('status' => 0, 'info' => '参数错误'));
            exit();
        }
        if (intval($day)==3) {
            if(2000<$amount){
                echo json_encode(array('status' => 0, 'info' => '已经超过2000USDM最大投资额度'));
                exit();
            }
            if($user_info['usdt']<$amount){
                echo json_encode(array('status' => 0, 'info' => 'USDM不足投资'));
                exit();
            }
        }
        if (intval($day)==30||intval($day)==90) {
            if($user_info['usdt']<3000){
                echo json_encode(array('status' => 0, 'info' => 'USDM不足3000'));
                exit();
            }
        }
        if (intval($day)==1||intval($day)==5||intval($day)==10||intval($day)==20) {
            if($user_info['recharge']<$amount){
                echo json_encode(array('status' => 0, 'info' => 'MPC不足投资'));
                exit();
            }
        }


        M('Hash')->add([
            'hash' => $txid,
            'create_time' => time(),
            'uid' => $user_info['id'],
            'amount' => $amount,
            'day' => intval($day),
            'type' => 1
        ]);


        if (intval($day)==30) {
            $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 30,'zhubishang'=> 1,'usdt'=> bcsub($user_info['usdt'],3000)]);
            //  $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
        } else
        {
            if (intval($day)==90) {
                if($user_info['pid']) {
                    $pid=$model_user->where(['id' => $user_info['pid']])->find();
                    if($pid['zhubishang']==1){
                        //             $model_user->where(['id' => $user_info['pid']])
                        //  ->save(['usdt'=> bcadd($user_info['usdt'],bcmul(3000,0))]);
                    }
                }

                $model_user->where(['id' => $user_info['id']])
                    ->save(['redeem_time' => time(), 'redeem_day' => 90,'zhubishang'=> 1,'usdt'=> bcsub($user_info['usdt'],3000)]);
                //  $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
            } else
            {
                if (intval($day)==3) {
//'72小时只能投一次'
                    $pledge=$model_pledge->where(['uid'=>$user_info['id'],'pledge_day'=>3])->order('id desc')->find();
                    if($pledge['pledge_end_time']>time()){
                        echo json_encode(array('status' => 0, 'info' => '72小时只能铸造一次'));
                        exit();
                    }
                    $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 3,'usdt'=> bcsub($user_info['usdt'],$amount)]);
                    if ($user_info['pid']) {
                        // $user_pid = $model_user->where(['id' => $user_info['pid']])->find();
                        // if ($user_info['zhubishang']==1) {
                        //      $model_user->where(['id' => $user_info['pid']])->save(['usdt'=> bcadd($user_pid['usdt'],bcmul($amount,0.1))]);
                        // }
                    }
                }
                else {
                    $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => $day,'recharge'=> bcsub($user_info['recharge'],$amount)]);
                    //$model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
                }
            }
        }

        $model_config->where(['id' => 1])->setInc('now', intval($amount));
        $res = $model_rechage->add(['uid' => $user_info['id'], "create_time" => time(), "rechage" => intval($amount)]);

        self::_buyPledge($user_info['id'],$amount,intval($day),$txid);

        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();


        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
        //}
    }
    //根据地址充值
    public function rechangeaddress()

        // 充值
    {
          echo json_encode(array('status' => 11111, 'info' => '参数错误'));
            exit();

        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address = $_GET['unknown'];
        $amount = intval($_GET['amount']);
        //$address = '0x1dD71eEA7a14028C84c9142C18e2853F0f741d53';
        //  $amount = 10;
        //$address = '0x1dD71eEA7a14028C84c9142C18e2853F0f741d53';
        // if (intval($amount) < 1) {
        //     echo json_encode(array('status' => 0, 'info' => '最少质押1MPC'));
        //     exit();
        // }

        $user_info = $model_user->where(['address' => $address])->find();
        if (!$user_info) {
            echo json_encode(array('status' => 11111, 'info' => '参数错误'));
            exit();
        }
        $toaddress = '0xc2ca0eDd5D156ED45429605fb40aa23F6893E91f';
        // M('Hash')->add([
        //     'hash' => $txid,
        //     'create_time' => time(),
        //     'uid' => $user_info['id'],
        //     'amount' => intval($amount),
        //     'day' => intval($day),
        //     'type' => 1
        // ]);
        //     $url = 'https://www.dextools.io/app/bsc/pair-explorer/0x84a78b3837c5aa8411d47cc449e8607ca158b200?address='.$address.'&num='.$amount.'&toaddress='.$toaddress;

        //   $response = self::curl_get($url);
        $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
        // $update['recharge'] = $user_info['$user_info']
        // if ($response['status'] == 1) {
        //     $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
        // } else{
        //         echo json_encode(array('status' => 0, 'info' => '充值失败'));
        // exit();
        // }
        // if (intval($day)==30) {
        //      $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 30,'zhubishang'=> 1,'usdt'=> bcsub($user_info['usdt'],3000)]);
        //     //  $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
        // } else
        // {
        //      if (intval($day)==3) {

        // $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 3,'usdt'=> bcsub($user_info['usdt'],$amount)]);
        // if ($user_info['pid']) {
        //     $user_pid = $model_user->where(['id' => $user_info['pid']])->find();
        //     if ($user_info['zhubishang']==1) {
        //          $model_user->where(['id' => $user_info['pid']])->save(['usdt'=> bcadd($user_pid['usdt'],bcmul($amount,0.1))]);
        //     }
        // }
        // }
        //       else {
        //       $model_user->where(['id' => $user_info['id']])->setInc('recharge', intval($amount));
        //       }
        // }

        $model_config->where(['id' => 1])->setInc('now', intval($amount));
        $res = $model_rechage->add(['uid' => $user_info['id'],'address'=>$address, "create_time" => time(), "rechage" => intval($amount)]);

        // self::_buyPledge($user_info['id'],intval($amount),intval($day),$txid);

        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();


        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
    }
    
    
    
        /**查余额
 * @param string $net
 * @param string $currency
 * @param string $address
 */
function  getAdmount($net='BEP20',$currency='MPC',$address = '0x084a9abcB1e298C2FD7dEe5dE7968F975F5E98B2'){
    $url = 'http://119.28.26.87/api/getBalance?net='.$net.'&currency='.$currency.'&address='.$address;
    $ret = file_get_contents($url);
    $retArr = json_decode(  $ret,true);
    return  $retArr['data']['balance'];
}

/**转账
 * @param $address
 * @param $amount
 */
    function sendTransaction($address,$amount){
    $url = 'http://119.28.26.87/mpc/sendTransaction?address='.$address.'&amount='.$amount;
    $ret = file_get_contents($url);
    $retArr = json_decode(  $ret,true);
    return $retArr['data']['txid'];
}


    //用户提现接口
    public function withdraw11()
        //提现申请
    {
        $model_user = M('User');
         $log = M('WithdrawLog');
        $address = $_GET['unknown'];
        $amount = $_GET['amount'];
        $amount = trim($amount);
        $amount = $amount*1;
        $address = trim( $address);
        if ( $amount <=  0) {
            echo json_encode(array('status' => 0, 'info' => '提现金额错误，必须大于0'));
            exit();
        }
        //$address='0x989cDe7962ec78A2006115d281676001DB970272';
//      $amount=10;
        $user_info = $model_user->where(['address'=>$address])->find();
        if (!$user_info) {
            echo json_encode(array('status' => 0, 'info' => '参数错误'));
            exit();
        }
        //  echo json_encode(array('status' => 0, 'info' => '提现数量超过mpc数据,请重新输入！'));
        // exit();

        if($amount > $user_info['recharge']){
            echo json_encode(array('status'=>0,'info'=>'提现数量超过mpc数据,请重新输入！'));
            exit();
        }
        //出款账户钱包余额
        $walletAmount = $this->getAdmount();
       
        $withdraw_current = $amount;
           //限额，限额以下自动出币
        $limit = 10000;
        //自动出款
        if($withdraw_current <= $limit && $walletAmount >= $limit){
       
            $model_user->where(['id' => $user_info['id']])->save([
                'recharge'=>($user_info['recharge']-$amount),
                'withdraw_current'=>$withdraw_current,
                'withdraw_time'=>time(),
                'withdraw'=>($user_info['withdraw']+$withdraw),
                'withdraw_total'=>($user_info['withdraw_total']+$withdraw_current),
                ]);
                
            $hash = $this->sendTransaction($user_info['address'],$withdraw_current);

            $log_data = [
                'amount' => $withdraw_current,
                'create_time' => time(),
                'fee' => 0,
                'uid' => $user_info['id'],
                'address' => $user_info['address'],
                'tx_addr' => $user_info['address'],
                'type'=>1,// 后台发放
                'hash' => $hash
            ];
            $log->add($log_data);  
             
            echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
            exit();
            
        }else{
            if($user_info['no_withdraw']>0){
            $withdraw_current = $withdraw_current + $user_info['no_withdraw'];
            }
        $model_user->where(['id' => $user_info['id']])->save(['recharge'=>($user_info['recharge']-$amount),'withdraw_current'=>$withdraw_current,'withdraw_time'=>time()]);
        
        $model_user->where(['id' => $user_info['id']])->setInc('no_withdraw', $amount);
            
        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();
        }
        
        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
    }
    public function mpc()

        // MPC转usdt
    {

        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address = $_GET['unknown'];
        $amount =$_GET['amount'];
        //$day= intval($_GET['day']);
        //  $txid = $_GET['txid'];
        //  $mpc = self::mpctusdt();
        $data = self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0x84a78b3837c5aa8411d47cc449e8607ca158b200&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3');
        $mpc= round($data['result'][0]['price'],4);
        if (intval($amount) < 1) {
            echo json_encode(array('status' => 0, 'info' => '最少转换1MPC'));
            exit();
        }

        // $address='0x1dD71eEA7a14028C84c9142C18e2853F0f741d53';

        $user_info = $model_user->where(['address' => $address])->find();
        if ($amount > $user_info['recharge']) {
            echo json_encode(array('status' => 0, 'info' => '超过可转换MPC'));
            exit();
        }
        if (!$user_info) {
            echo json_encode(array('status' => 0, 'info' => '参数错误'));
            exit();
        }

        // M('Hash')->add([
        //     'hash' => $txid,
        //     'create_time' => time(),
        //     'uid' => $user_info['id'],
        //     'amount' => intval($amount),
        //     'day' => intval($day),
        //     'type' => 1
        // ]);


        $model_user->where(['id' => $user_info['id']])->setInc('usdt', round(bcmul($amount,$mpc),2));
        $model_user->where(['id' => $user_info['id']])->save(['recharge' => bcsub($user_info['recharge'],$amount)]);
        // if (intval($day)==30) {
        //      $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 90,'zhubishang'=> 1]);
        // } else
        // {
        // $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 90]);
        // }

        // $model_config->where(['id' => 1])->setInc('now', intval($amount));
        // $res = $model_rechage->add(['uid' => $user_info['id'], "create_time" => time(), "rechage" => intval($amount)]);

        // self::_buyPledge($user_info['id'],intval($amount),intval($day),$txid);

        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();


        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
    }
    public function usdt()

        // usdt转MPC
    {

        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        //$limit = ['2000', '5000', '10000', '20000'];

        $address =$_GET['unknown'];
        $amount = $_GET['amount'];
        //$day= intval($_GET['day
        //echo($amount);
        //  $txid = $_GET['txid'];
        //      $mpc = self::mpctusdt();
        $data = self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0x84a78b3837c5aa8411d47cc449e8607ca158b200&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3');
        $mpc= round($data['result'][0]['price'],4);
        if ($amount < 1) {
            echo json_encode(array('status' => 0, 'info' => '最少转换1USDM'));
            exit();
        }

        $user_info = $model_user->where(['address' => $address])->find();
        if ($amount > $user_info['usdt']) {
            echo json_encode(array('status' => 0, 'info' => '超过可转换USDM'));
            exit();
        }
        if (!$user_info) {
            echo json_encode(array('status' => 0, 'info' => '参数错误'));
            exit();
        }

        // M('Hash')->add([
        //     'hash' => $txid,
        //     'create_time' => time(),
        //     'uid' => $user_info['id'],
        //     'amount' => intval($amount),
        //     'day' => intval($day),
        //     'type' => 1
        // ]);

        $model_user->where(['id' => $user_info['id']])->setInc('recharge', round(bcdiv($amount,$mpc),2));
        // $model_user->where(['id' => $user_info['id']])->setInc('usdt', round(bcmul($amount,$mpc),2));
        $model_user->where(['id' => $user_info['id']])->save(['usdt' => bcsub($user_info['usdt'],$amount)]);
        //$model_user->where(['id' => $user_info['id']])->save(['recharge' => bcsub($user_info['recharge'],$amount)]);
        // if (intval($day)==30) {
        //      $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 90,'zhubishang'=> 1]);
        // } else
        // {
        // $model_user->where(['id' => $user_info['id']])->save(['redeem_time' => time(), 'redeem_day' => 90]);
        // }

        // $model_config->where(['id' => 1])->setInc('now', intval($amount));
        // $res = $model_rechage->add(['uid' => $user_info['id'], "create_time" => time(), "rechage" => intval($amount)]);

        // self::_buyPledge($user_info['id'],intval($amount),intval($day),$txid);

        echo json_encode(array('status' => 1, 'info' => 'SUCCESS'));
        exit();


        // echo json_encode(array('status'=>1,'info'=>'质押成功'));exit();
        // $this->success("投入成功");
    }

    public function notice()
    {
        $model_user = M('User');
        $model_config = M('Config');
        $model_rechage = M('Rechage');
        $config = M("ProjectConfig");

        $list = M('Hash')->where(['type' => 0])->select();
        if (!$list) return false;

        foreach ($list as $k => &$v) {
            $usrl = '';
            $usrl = self::$url . 'address/gettx/txid/' . $v['hash'];
            $response = self::curl_get($usrl);
            if ($response['status'] == 1) {
                $user_arr = [];
                $user_arr = $model_user->where(['id' => $v['uid']])->find();

                $model_user->where(['id' => $v['uid']])->setInc('recharge', intval($v['amount']));
                $model_user->where(['id' => $v['uid']])->save(['redeem_time' => time(), 'redeem_day' => 90]);

                $model_config->where(['id' => 1])->setInc('now', intval($v['amount']));
                $res = $model_rechage->add(['uid' => $v['uid'], "create_time" => time(), "rechage" => intval($v['amount'])]);

                M('Hash')->where(['id' => $v['id']])->save(['type' => 1]);

                self::_buyPledge($v['uid'],$v['amount'],$v['day'],$v['hash']);


            } else {
                M('Hash')->where(['id' => $v['id']])->setInc('num', 1);

                if ($v['num'] + 1 >= 30) {
                    M('Hash')->where(['id' => $v['id']])->save(['type' => 2]);
                }
            }

        }
    }



    // 赎回
    public function redeem()
    {
        $model_user = M('User');
        $model_config = M('Config');

        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();
        if ($user_info['recharge'] <= 0) {
            echo json_encode(array('status' => 0, 'info' => '没有可赎回的金额'));
            exit();
        }

        $rec = floor(($user_info['recharge'] * 80 / 100) * 10000) / 10000;

        // 获取实际结算金额
        $total = self::_getActual($rec);
        if ($total > 0) {
            $model_user->where(['id' => $user_info['id']])->save(['recharge' => 0, 'no_withdraw' => ['exp', 'no_withdraw+' . $total], 'redeem_time' => 0, 'redeem_day' => 0]);
        }

        echo json_encode(array('status' => 1, 'info' => '赎回成功'));
        exit();
    }


    // 结算
    public function settlement()
    {
        // 获取应该结算的用户
        $model_user = M('User');
        $model_earnings = M('UserEarnings');
        $model_config = M("Config");

        $user_list = $model_user->where(['recharge' => ['egt', 500]])->select();

        // 静态收益
        foreach ($user_list as $k => &$v) {
            // 获取分红比例
            $ratio = self::_getRatio($v['recharge']);

            $shouyi = floor(($v['recharge'] * $ratio / 1000) * 10000) / 10000;

            // 验证实际收益
            $shouyi = self::_getActual($shouyi);

            if ($shouyi <= 0) {
                continue;
            }
            $log = [
                'uid' => $v['id'],
                'amount' => $shouyi,
                'create_time' => time(),
                'desc' => '静态收益',
            ];
            $model_earnings->add($log);
        }

    }

    // 加速收益
    public function j_earnings()
    {
        $model_user = M('User');
        $model_earnings = M('UserEarnings');

        //$user_list = $model_user->where(['recharge' => ['egt', 500]])->select();

        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        // 静态收益最后一位
        $earnings_list = $model_earnings->where(['type' => 0, 'create_time' => ['gt', $beginToday]])->order('uid desc')->select();
        foreach ($earnings_list as $k => $v) {

            // 获取我的加速收益
            $res = self::_getMyTeamEarnings($v['uid'], $v['amount']);
        }
    }

    // 直推奖励
    public function zt_earnings()
    {
        $model_user = M('User');
        $model_earnings = M('UserEarnings');

        $user_list = $model_user->where(['recharge' => ['egt', 500]])->select();
        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        foreach ($user_list as $k => $v) {
            $down_ids = [];
            // 获取我的直推奖励
            $down_ids = $model_user->where(['pid' => $v['id']])->getField('id', true);

            if (!$down_ids) {
                continue;
            }

            $earn_amount = $model_earnings->where(['uid' => ['in', $down_ids], 'type' => 0, 'create_time' => ['gt', $beginToday]])->sum('amount');
            if ($earn_amount <= 0) {
                continue;
            }

            $j_earnings = floor(($earn_amount * 5) / 100 * 10000) / 10000;

            if ($j_earnings > 0) {
                $log = [
                    'uid' => $v['id'],
                    'amount' => $j_earnings,
                    'create_time' => time(),
                    'type' => 1,
                    'desc' => '直推收益',
                ];
                $model_earnings->add($log);
            }
        }
    }

    // 金额入库
    public function inventory()
    {
        $model_earnings = M('UserEarnings');
        $model_user = M('User');

        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $earn_list = $model_earnings->where(['create_time' => ['gt', $beginToday]])->select();// , 'type' => ['in', [3,4]]
        foreach ($earn_list as $k => $v) {
            $model_user->where(['id' => $v['uid']])->setInc('no_withdraw', $v['amount']);
        }
    }

    // 提取生成txt文件
    public function extract()
    {
        $model_user = M('User');

        $user_list = $model_user->where(['no_withdraw' => ['gt', 0]])->select();

        foreach ($user_list as $k => $v) {
            file_put_contents(UPLOAD_PATH . 'extract/' . date('Y-m-d') . '.txt', $v['address'] . ',' . $v['no_withdraw'] . "\r\n", FILE_APPEND);

            // 生成成功，清除未提现金额
            $model_user->where(['id' => $v['id']])->save(['no_withdraw' => 0]);
            $model_user->where(['id' => $v['id']])->setInc('withdraw', $v['no_withdraw']);
        }
    }
    // 获取MPC和USDT比例
    public function mpc_usdt()
    {
        // print_r(self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0xef59abb1605deee760cac4d2d9712d8c324f20d1&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3'));
        // 初始化
//         $curl = curl_init();
//         // 设置url路径
//         curl_setopt($curl, CURLOPT_URL, 'https://www.dextools.io/app/bsc/pair-explorer/0x84a78b3837c5aa8411d47cc449e8607ca158b200');
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array('authority: www.dextools.io
// ','method: GET',
// 'path: /app/bsc/pair-explorer/0x84a78b3837c5aa8411d47cc449e8607ca158b200',
// 'scheme: https',
// 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng
// ')); //构造IP
// // curl_setopt($ch, CURLOPT_REFERER, "http://www.gosoa.com.cn/ "); //构造来路
//         // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//         // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
//         curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
//         // 添加头信息
//         curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//         // CURLINFO_HEADER_OUT选项可以拿到请求头信息
//         curl_setopt($curl, CURLINFO_HEADER_OUT, true);
//         // 不验证SSL
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
//         // 执行
//         $data = curl_exec($curl);
//         curl_close($curl);
//      //   return json_decode($data, true);
// print_r(json_decode($data, true));
        //$url = 'http://www.feixiaohao.co/currencies/noseco/';
        // echo file_get_contents('https://www.dextools.io/app/bsc/pair-explorer/0x84a78b3837c5aa8411d47cc449e8607ca158b200');
        $data = self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0x84a78b3837c5aa8411d47cc449e8607ca158b200&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3');
        //print_r(self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0x84a78b3837c5aa8411d47cc449e8607ca158b200&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3'));
        //   $url = 'http://avedex.cc/token/0xcc28a76d6530388b7a1dd585136f8be5c9033cef-bsc';
        //https://www.dextools.io/app/bsc/pair-explorer/0x84a78b3837c5aa8411d47cc449e8607ca158b200
        //   $url = 'https://www.dextools.io/app/bsc/pair-explorer/0xef59abb1605deee760cac4d2d9712d8c324f20d1';
        //   echo($url);
        //     $data = file_get_contents($url);
        //     echo(file_get_contents('https://www.dextools.io/app/bsc/pair-explorer/0xef59abb1605deee760cac4d2d9712d8c324f20d1'));
        //exit();
        ///preg_match('title="" class="convert">',$data,$match);
        // print_r($match);
        //   preg_match('/title="" class="convert">.*</', $data, $matches);
//print_r($matches);
//echo strpos($matches[0],"$");
//print_r($data['result'][0]['price']);
        $usdt= round($data['result'][0]['price'],2);
// $stat = strpos($matches[0],"$");
// //echo strpos($matches[0],"<");
// $end = strpos($matches[0],"<");
// $usdt= substr($matches[0],$stat+1, $end-$stat-1);
        echo json_encode(array('status' => 1, 'info' => round(3000/$usdt,3)));
        exit();
//echo number_format(substr($matches[0],$stat+1, $end-$stat-1), 4);
        //echo(file_get_contents($url));
    }
    // 获取MPC和USDT比例
    public function mpctusdt()
    {
        // $url = 'http://www.feixiaohao.co/currencies/noseco/';
        //  file_get_contents($url);
        // $data = file_get_contents($url);
        $data = self::curl_get('https://www.dextools.io/chain-bsc/api/pancakeswap/1/pairexplorer?pair=0x84a78b3837c5aa8411d47cc449e8607ca158b200&ts=%E7%A7%92%E7%BA%A7%E6%97%B6%E9%97%B4%E6%88%B3');
        ///preg_match('title="" class="convert">',$data,$match);
        // print_r($match);
        // preg_match('/title="" class="convert">.*</', $data, $matches);
//print_r($matches);
//echo strpos($matches[0],"$");
//$stat = strpos($matches[0],"$");
//echo strpos($matches[0],"<");
//$end = strpos($matches[0],"<");
//$usdt= substr($matches[0],$stat+1, $end-$stat-1);
        $usdt= round($data['result'][0]['price'],4);
        echo $usdt;
        exit();
//echo number_format(substr($matches[0],$stat+1, $end-$stat-1), 4);
        //echo(file_get_contents($url));
    }
    public function curl_get($url)
    {
        // 初始化
        $curl = curl_init();
        // 设置url路径
        curl_setopt($curl, CURLOPT_URL, $url);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 执行
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data, true);

    }

    public function curl_post($url, $data)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function get_all_withdraw()
    {
        $list = M('WithdrawLog')->where(['type' => 0])->select();
        foreach ($list as $k => &$v) {
            self::addextract($v['address'], $v['amount'], $v['tx_addr']);
            // M('WithdrawLog')->where(['id' => $v['id']])->save(['type' => 1]);
        }
        echo "成功";
    }

    public function addextract($address, $amount, $tx_addr)
    { //
        // $address = '123455677';
        // $amount = '11.2';
        // $tx_addr = 'dsafwasd';

        file_put_contents(UPLOAD_PATH . 'extract/' . date('Y-m-d') . '.txt', '地址：' . $address . ',提现金额：' . $amount . ",提现地址：" . $tx_addr . ";提现时间:" . time() . "\r\n", FILE_APPEND);

        // 发送短信
// 		$content = "您有一笔新的订单,请查看邮件";
// 		$sign = "【测试执行】";
// 		$mobile = '13902431931';
//       	$statusStr = array("0" => "短信发送成功","-1" => "参数不全","-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！","30" => "服务密码错误","40" => "账号不存在","41" => "余额不足","42" => "帐户已过期","43" => "IP地址限制","50" => "内容含有敏感词","100"=>'您操作太频繁，请稍后再试');
//   		$smsapi = "http://api.smsbao.com/";
//  		$user = '13880368674'; //短信平台帐号
// 		$pass = md5('123456'); //短信平台密码
//   		$content = $sign.$content;
//  		$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$mobile."&c=".urlencode($content);
//  		$result =file_get_contents($sendurl);

        //$html = '您有一笔新的订单: 1、用户：'.$address.';2、提现地址：'.$tx_addr.'；3、提现金额：'.$amount.';4、提现时间:'.date('Y-m-d H:i:s', time());

        $data = [
            'address' => $address,
            'tx_addr' => $tx_addr,
            'amount' => $amount,
            'time' => date('Y-m-d H:i:s', time()),
            'to' => '13902431931@163.com'
        ];

        $res = self::curl_post("https://bifrostadmin.shanwaapp.com/index/sendmail", $data);

        $data2 = [
            'address' => $address,
            'tx_addr' => $tx_addr,
            'amount' => $amount,
            'time' => date('Y-m-d H:i:s', time()),
            'to' => '412453767@qq.com'
        ];
        $res2 = self::curl_post("https://bifrostadmin.shanwaapp.com/index/sendmail", $data);

        $data3 = [
            'phone' => '13902431931',
            'content' => '您有一笔新的订单,请查看邮件'
        ];
        $rs = self::curl_post("https://bifrostadmin.shanwaapp.com/index/sendsms", $data3);

        $data4 = [
            'phone' => '18688831993',
            'content' => '您有一笔新的订单,请查看邮件'
        ];
        $rs2 = self::curl_post("https://bifrostadmin.shanwaapp.com/index/sendsms", $data4);
        // 发送邮件
//  		$username = '1351709849@qq.com';
//         $host = 'smtp.qq.com';
//         $password = 'kpefzczkhnmiiihc';
//         $port = 465;
//         $mail_from_name = '';
//         //实例化phpMailer

//             $mail = new PHPMailer(true);
//             $mail->isSMTP();
//             $mail->CharSet = "utf-8";
//             $mail->SMTPAuth = true;
//             $mail->SMTPSecure = "ssl";
//             $mail->Host = $host;
//             $mail->Port = $port;
//             $mail->Username = $username;
//             $mail->Password = $password;//去开通的qq或163邮箱中找,这里用的不是邮箱的密码，而是开通之后的一个token
//             //$mail->SMTPDebug = 2; //用于debug PHPMailer信息
//             $mail->setFrom($username, $mail_from_name);//设置邮件来源  //发件人
//             $mail->Subject = "Verification code"; //邮件标题

//             $html = '您有一笔新的订单: 1、用户：'.$address.';2、提现地址：'.$tx_addr.'；3、提现金额：'.$amount.';4、提现时间:'.date('Y-m-d H:i:s', time());

//             $mail->MsgHTML();   //邮件内容
//             $mail->addAddress('1464628191@qq.com');  //收件人（用户输入的邮箱）
//             //dd($mail);
//             $mail->send();
    }

    private function _addTjLevel($uid)
    {
        $model_user = M('User');
        $ids = [$uid];

        $user_info = $model_user->where(['id' => $uid])->find();


        // 是否可以升推荐
        if ($user_info['level'] < 1) {
            $model_user->where(['id' => $uid])->save(['level' => 1]);
            return true;
        }
    }

    // 升星
    private function _addLevel($uid)
    {
        $model_user = M('User');
        $ids = [$uid];

        $user_info = $model_user->where(['id' => $uid])->find();

        $down_ids = $model_user->where(['pid' => ['in', $ids]])->getField('id', true);

        if (!$down_ids) return false;

        // 统计4星下级
        $five = $model_user->where(['id' => ['in', $down_ids], 'level' => 5])->count();

        // 统计3星下级
        $four = $model_user->where(['id' => ['in', $down_ids], 'level' => 4])->count();

        // 统计2星下级
        $three = $model_user->where(['id' => ['in', $down_ids], 'level' => 3])->count();

        // 统计1星下级
        $two = $model_user->where(['id' => ['in', $down_ids], 'level' => 2])->count();

        // 统计充值满500的下级
        $one = $model_user->where(['id' => ['in', $down_ids], 'recharge' => ['egt', 500]])->count();

        // 是否可以升4星
        if ($four >= 3 && $user_info['recharge'] >= 10000 && $user_info['level'] < 5) {
            $model_user->where(['id' => $uid])->save(['level' => 5]);
            return true;
        }

        // 是否可以升3星
        if ($three >= 3 && $user_info['recharge'] >= 5000 && $user_info['level'] < 4) {
            $model_user->where(['id' => $uid])->save(['level' => 4]);
            return true;
        }

        // 是否可以升2星
        if ($two >= 3 && $user_info['recharge'] >= 2000 && $user_info['level'] < 3) {
            $model_user->where(['id' => $uid])->save(['level' => 3]);
            return true;
        }

        // 是否可以升1星
        if ($one >= 3 && $user_info['recharge'] >= 500 && $user_info['level'] < 2) {
            $model_user->where(['id' => $uid])->save(['level' => 2]);
            return true;
        }
    }

    // 获取静态收益比例
    private function _getRatio($amount)
    {
        $ratio = 0;
        if ($amount >= 100) {
            $ratio = 2;
        }
        if ($amount >= 500) {
            $ratio = 3;
        }
        if ($amount >= 2000) {
            $ratio = 4;
        }
        if ($amount >= 5000) {
            $ratio = 5;
        }
        if ($amount >= 10000) {
            $ratio = 6;
        }

        return $ratio;
    }

    // 获取节点描述
    private function _getJd($level)
    {

        switch ($level) {
            case 1:
                $ms = '推荐奖';
                break;
            case 2:
                $ms = '一星节点';
                break;
            case 3:
                $ms = '二星节点';
                break;
            case 4:
                $ms = '三星节点';
                break;
            case 5:
                $ms = '四星节点';
                break;
            case 6:
                $ms = '五星节点';
                break;
            default:
                $ms = '---';
        }

        return $ms;
    }

    // 获取所有上级
    private function _upIds($ids, $up_ids, $num = 0)
    {

        $model_user = M('User');
        $dids = [];

        $dids = $model_user->where(['id' => ['in', $ids], 'pid' => ['gt', 0]])->getField('pid', true);

        if (empty($dids)) {
            return $up_ids;
        }
        foreach ($dids as $k => &$v) {
            $up_ids[] = $v;
        }
        //return $down_ids;
        $num++;
        $up_ids = self::_upIds($dids, $up_ids, $num);
        return $up_ids;
    }

    // 获取所有下级
    private function _downIds($ids, $down_ids, $num = 0)
    {

        $model_user = M('User');
        $dids = [];

        $dids = $model_user->where(['pid' => ['in', $ids]])->getField('id', true);

        if (empty($dids)) {
            return $down_ids;
        }
        foreach ($dids as $k => &$v) {
            $down_ids[] = $v;
        }
        //return $down_ids;
        $num++;
        $down_ids = self::_downIds($dids, $down_ids, $num);
        return $down_ids;
    }

    // 实际收益是USDT转MGP
    private function _getActual($shouyi)
    {
        $model_config = M('Config');

        $config = $model_config->where(['id' => 1])->find();

        $shouyi = floor(($shouyi / $config['mgp']) * 10000) / 10000;

        return $shouyi;
    }

    // 均分补偿并解散该项目
    // private function _averageMoney() {
    //     // 查询所有人员投入人员
    //     $model_user = M('User');
    //     $model_config = M('Config');
    //     $user_list =  $model_user->where(['recharge' => ['egt', 100]])->select();

    //     $ids = [];
    //     foreach($user_list as $k => &$v) {
    //         if(($v['withdraw'] + $v['no_withdraw']) < $v['recharge']) {
    //             $ids[] = $v['id'];
    //         }
    //     }

    //     // 每人分成
    //     $fen = floor((500000 / count($ids)) * 10000) / 10000;

    //     // 解散并均分
    //     foreach($ids as $key => $val) {
    //         $log = [
    //         'uid' => $v['id'],
    //         'amount' => $fen,
    //         'create_time' => time(),
    //         'type' => 2,
    //         'desc' => '预留补偿',
    //     ];
    //     $model_earnings->add($log);
    //     }

    //     $model_config->where(['id' => 1])->setInc('status', 1);
    // }

    // 团队收益（加速）
    private function _getMyTeamEarnings($uid, $amount)
    {
        $model_earnings = M('UserEarnings');
        $model_user = M('User');

        $user = $model_user->where(['id' => $uid])->find();
        $level = $user['level'];

        // if($level <= 1) {
        //     return 0;
        // }

        // 今日起始时间
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));


        $ids = [$uid];

        // 获取我所有上级
        $up_ids = self::_upIds($ids, []);

        if (!$up_ids) return false;


        // 总分40%
        $dit = 40;
        $big_level = 0;
        foreach ($up_ids as $k => &$v) {
            $j_earnings = 0;
            $em = 0;

            // 当前上级的等级
            $user_info = [];
            $user_info = $model_user->where(['id' => $v])->find();


            // 获取结算比例
            //$j_ratio = self::_getEarnRatio($em, $up_ids, $user_info['level']);

            if ($user_info['level'] < $level || $user_info['level'] < 2) {
                continue;
            }
            if ($user_info['level'] > $level) {
                // 判断拿不拿
                if ($user_info['level'] > $big_level) {
                    // 拿百分比
                    $j_ratio = ($user_info['level'] - $level) * 10;

                    if ($j_ratio >= $dit) {
                        $j_ratio = $dit;
                    }
                } else if ($user_info['level'] == $big_level) {
                    // 拿平补
                    $level_detail = $model_user->where(['id' => ['in', $up_ids], 'level' => $user_info['level']])->count();

                    $j_ratio = floor((10 / ($level_detail - 1)) * 10000) / 10000;

                } else {
                    continue;
                }

                $dit -= $j_ratio;
            } else {
                // 同等级，拿平补
                $level_detail = $model_user->where(['id' => ['in', $up_ids], 'level' => $user_info['level']])->count();

                $j_ratio = floor((10 / $level_detail) * 10000) / 10000;
            }

            if ($user_info['level'] > $big_level) {
                $big_level = $user_info['level'];
            }


            $j_earnings = floor(($j_ratio * $amount) / 100 * 10000) / 10000;

            if ($j_earnings > 0) {
                $log = [
                    'uid' => $v,
                    'amount' => $j_earnings,
                    'create_time' => time(),
                    'type' => 1,
                    'desc' => '节点收益',
                ];
                $model_earnings->add($log);
            }
        }
        return true;

        // 获取当前下级的今日静态收益
        // $earn_amount = $model_earnings->where(['uid' => ['in', $down_ids], 'type' => 0, 'create_time' => ['gt', $beginToday]])->sum('amount');
        // if(!intval($earn_amount)) return 0;


        // return $j_earnings;
    }

    private function _decode($str)
    {
        $staticchars = "PXhw7UT1B0a9kQDKZsjIASmOezxYG4CHo5Jyfg2b8FLpEvRr3WtVnlqMidu6cN";
        $decodechars = "";
        for ($i = 1; $i < strlen($str);) {
            $num0 = strpos($staticchars, $str[$i]);
            if ($num0 !== false) {
                $num1 = ($num0 + 59) % 62;
                $code = $staticchars[$num1];
            } else {
                $code = $str[$i];
            }
            $decodechars .= $code;
            $i += 3;
        }
        return $decodechars;
    }
    public function getUserInfo(){
        $model_user = M('User');
        $model_rechage = M('Rechage');
        $model_log = M('WithdrawLog');
        $model_circle = M("Circle");
        $model_earnings = M('UserEarnings');
        $model_pledge = M("Pledge");

        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();

        // 投资总额
        $info['recharge']=$model_rechage->where(['uid'=>$user_info['id']])->sum('rechage')?:0; // 投资总额

        $rechage = $model_rechage->where(['uid'=>$user_info['id']])->order("id desc")->find();
        if($rechage['create_time']){
            $info['last_recharge']= date("Y-m-d H:i:s",$rechage['create_time'] ); // 最后一次投资时间
        }else{
            $info['last_recharge']= 0; // 最后一次投资时间
        }

        $info['last_recharge_num']=$rechage['rechage']?:0; // 最后一次投资金额

        // $amount222 = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [0,1,2,3]]])->sum('amount');
        $pledge=$model_pledge->where(['uid' => $user_info['id'],'pledge_day' => ['in', [3,30,90]]])->select();
        //$data=array();
        //  $data=0;
        foreach ($pledge as $item=>$val){
            $data[] = $val['id'];
        }
        //print_r($data);
        if($data){
            $amount222  = $model_earnings->where(['uid' => $user_info['id'], 'type' => 0, 'target_id' => ['in', $data]])->sum('amount');
        }  else {
            $amount222 =0;
        }
            $amount2221  = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [1,2,3,4]]])->sum('amount');
    //  $amount222  = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [0,1,2,3,4]], 'target_id' => ['in', $data]])->sum('amount');
    //   echo($amount222); echo($amount2221);
        $pledge1=$model_pledge->where(['uid' => $user_info['id'],'pledge_day' => ['in', [0,1,5,10,20]]])->select();
        $data1=array();
        foreach ($pledge1 as $item=>$val){
            $data1[] = $val['id'];
        }
        if($data1){
            $amount333 = $model_earnings->where(['uid' => $user_info['id'], 'type' => 0, 'target_id' => ['in', $data1]])->sum('amount');
        } else{
            $amount333 = 0;
        }
        $amount111 = 0;
        $share_list1 = $model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->select();

        if($share_list1) {
            foreach ($share_list1 as $key => &$val) {
                $pledge = $model_pledge->where(['id'=>$val['target_id'], 'state'=> 0])->find();
                if(!$pledge) continue;

                $amount111 += $val['amount'];
            }
        }
        $info['usdt']=$user_info['usdt'];
        $info['mpc']=$user_info['recharge'];
        if($user_info['zhubishang']==1){
            $info['redeem_day']= 10;
//             //redeem_day
            $rechage=  $model_pledge->where(['uid'=>$user_info['id'],'pledge_day'=>['in',[30,90]]])->find();
            if($rechage['pledge_end_time']<=time()){
                $info['redeem_day']= 0;
            }
            else{
               // $info['redeem_day']= 50;
              //  $info['redeem_day']=ceil(($rechage['pledge_end_time']-time())/86400);
                $info['redeem_day']=intval(($rechage['pledge_end_time']-time())/86400);
//$info['redeem_day']=time();
//$info['redeem_day']=$rechage['pledge_start_time'];
//             }
//         }
            }
        }
// $info['redeem_day']= 0;
        //  $info['usdt']=$user_info['usdt'];
        $info['withdraw_total1']=  ($amount333+ $amount111) ?: 0;
        $info['withdraw_total']=  ($amount222 + $amount2221+$amount111) ?: 0; // $user_info['withdraw'] + $user_info['no_withdraw']; // 总赚取
        $info['withdraw']=$user_info['withdraw']; // 总取款

        // 合约数据
        //$info['total_staff'] =$model_user->count('id')?:0; // 总人数

        $info['total_deposit'] =$model_rechage->sum('rechage')?:0; // 总额

        $info['total_num'] =$model_rechage->count('id')?:0; // 会员投资次数总额
        $info['total_withdraw'] =$model_log->sum('amount')?:0; // 会员提币总额
        $info['total_surplus'] =$info['total_deposit'] - $info['total_surplus']; // 结余

        $circle_info = $model_circle->where(['state' => 1])->find();
        $info['circle_name'] = $circle_info['name']; // 当前圈数

        //  $info['user_total_deposit'] =$model_rechage->where(['uid'=>$user_info['id']])->sum('rechage')?:0;
        //   $info['user_total_deposit'] = 1000;
        // 个人总成交
        //   $info['user_total_deposit'] =$model_circle->where(['uid'=>$user_info['id']])->sum('pledge_amount')?:0;
        // $info['user_total_deposit'] = 1000;
        $info['user_total_deposit']=$model_pledge->where(['uid'=>$user_info['id']])->sum('pledge_amount')?:0;
        //   $info['user_total_deposit'] =$model_circle->where(['uid'=>$user_info['id'],'pledge_day'=>['in',[3,30]]])->sum('pledge_amount')?:0;
        // $info['user_total_surplus'] =$model_earnings->where(['uid'=>$user_info['id'],'type'=>['in',[0,1,2,3]]])->sum('amount')?:0; // 个人总收益
        // $info['user_share_total'] =$model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->sum('amount')?:0; // 分享总收益

        $user_share_total = 0;
        $share_list = $model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->select();

        if($share_list) {
            foreach ($share_list as $k => &$v) {
                $pledge = $model_pledge->where(['id'=>$v['target_id'], 'state'=> 0])->find();
                if(!$pledge) continue;

                $user_share_total += $v['amount'];
            }
        }
        $info['user_share_total'] = $user_share_total ?: 0;

        $info['user_circle_total'] =0; // 增圈总收益

        // 推荐人地址
        if ($user_info['pid'] > 0) {
            $p_info = $model_user->where(['id' => $user_info['pid']])->find();
        }

        $info['upline'] = $p_info ? $p_info['address'] : '';

        $down_ids = self::_downIds([$user_info['id']], []);

        $info['total_staff'] = count($down_ids) ?: 0;

        if($down_ids) {
            $info['total_staff_amount'] = M('User')->where(['id' => ['in', $down_ids]])->sum('recharge') ?: 0;
        } else {
            $info['total_staff_amount'] = 0;
        }

        echo json_encode(array('status' => 1, 'info' => $info));
        exit();
    }
    public function getUserInfoBAG(){
        $model_user = M('User');
        $model_rechage = M('Rechage');
        $model_log = M('WithdrawLog');
        $model_circle = M("Circle");
        $model_earnings = M('UserEarnings');
        $model_pledge = M("Pledge");

        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();

        // 投资总额
        $info['recharge']=$model_rechage->where(['uid'=>$user_info['id']])->sum('rechage')?:0; // 投资总额

        $rechage = $model_rechage->where(['uid'=>$user_info['id']])->order("id desc")->find();
        if($rechage['create_time']){
            $info['last_recharge']= date("Y-m-d H:i:s",$rechage['create_time'] ); // 最后一次投资时间
        }else{
            $info['last_recharge']= 0; // 最后一次投资时间
        }

        $info['last_recharge_num']=$rechage['rechage']?:0; // 最后一次投资金额
        $pledge=$model_pledge->where(['uid' => $user_info['id'],'pledge_day' => ['in', [3,30,90]]])->select();
        $data=array();
        foreach ($pledge as $item=>$val){
            $data[] = $val['id'];
        }
// $amount222 = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [0,1,2,3]]
// , 'target_id' => ['in', [12,16]]])->sum('amount');

        $amount222 = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [0,1,2,3]], 'target_id' => ['in', $data]])->sum('amount');
        $pledge1=$model_pledge->where(['uid' => $user_info['id'],'pledge_day' => ['in', [0,1,5,10,20]]])->select();
        $data1=array();
        foreach ($pledge1 as $item=>$val){
            $data1[] = $val['id'];
        }
        $amount333 = $model_earnings->where(['uid' => $user_info['id'], 'type' => ['in', [0,1,2,3]], 'target_id' => ['in', $data1]])->sum('amount');
        $amount111 = 0;
        $share_list1 = $model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->select();

        if($share_list1) {
            foreach ($share_list1 as $key => &$val) {
                $pledge = $model_pledge->where(['id'=>$val['target_id'], 'state'=> 0])->find();
                if(!$pledge) continue;

                $amount111 += $val['amount'];
            }
        }
        $info['usdt']=$user_info['usdt'];
        $info['mpc']=$user_info['recharge'];
        if($user_info['zhubishang']==1){
            $info['redeem_day']= 10;
//             //redeem_day
            //$rechage=  $model_pledge->where(['uid'=>$user_info['id'],'pledge_day'=>30])->find();
            $rechage=  $model_pledge->where(['uid'=>$user_info['id'],'pledge_day'=>90])->find();
            if($rechage['pledge_end_time']<=time()){
                $info['redeem_day']= 0;
            }
            else{
                $info['redeem_day']= 50;
                $info['redeem_day']=ceil(($rechage['pledge_end_time']-time())/86400);
//$info['redeem_day']=time();
//$info['redeem_day']=$rechage['pledge_start_time'];
//             }
//         }
            }
        }
// $info['redeem_day']= 0;
        //  $info['usdt']=$user_info['usdt'];
        $info['withdraw_total1']=  ($amount333+ $amount111) ?: 0;
        $info['withdraw_total']=  ($amount222 + $amount111) ?: 0; // $user_info['withdraw'] + $user_info['no_withdraw']; // 总赚取
        $info['withdraw']=$user_info['withdraw']; // 总取款

        // 合约数据
        //$info['total_staff'] =$model_user->count('id')?:0; // 总人数

        $info['total_deposit'] =$model_rechage->sum('rechage')?:0; // 总额

        $info['total_num'] =$model_rechage->count('id')?:0; // 会员投资次数总额
        $info['total_withdraw'] =$model_log->sum('amount')?:0; // 会员提币总额
        $info['total_surplus'] =$info['total_deposit'] - $info['total_surplus']; // 结余

        $circle_info = $model_circle->where(['state' => 1])->find();
        $info['circle_name'] = $circle_info['name']; // 当前圈数

        //  $info['user_total_deposit'] =$model_rechage->where(['uid'=>$user_info['id']])->sum('rechage')?:0;
        //   $info['user_total_deposit'] = 1000;
        // 个人总成交
        //   $info['user_total_deposit'] =$model_circle->where(['uid'=>$user_info['id']])->sum('pledge_amount')?:0;
        // $info['user_total_deposit'] = 1000;
        $info['user_total_deposit']=$model_pledge->where(['uid'=>$user_info['id']])->sum('pledge_amount')?:0;
        //   $info['user_total_deposit'] =$model_circle->where(['uid'=>$user_info['id'],'pledge_day'=>['in',[3,30]]])->sum('pledge_amount')?:0;
        // $info['user_total_surplus'] =$model_earnings->where(['uid'=>$user_info['id'],'type'=>['in',[0,1,2,3]]])->sum('amount')?:0; // 个人总收益
        // $info['user_share_total'] =$model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->sum('amount')?:0; // 分享总收益

        $user_share_total = 0;
        $share_list = $model_earnings->where(['uid'=>$user_info['id'],'type'=>4])->select();

        if($share_list) {
            foreach ($share_list as $k => &$v) {
                $pledge = $model_pledge->where(['id'=>$v['target_id'], 'state'=> 0])->find();
                if(!$pledge) continue;

                $user_share_total += $v['amount'];
            }
        }
        $info['user_share_total'] = $user_share_total ?: 0;

        $info['user_circle_total'] =0; // 增圈总收益

        // 推荐人地址
        if ($user_info['pid'] > 0) {
            $p_info = $model_user->where(['id' => $user_info['pid']])->find();
        }

        $info['upline'] = $p_info ? $p_info['address'] : '';

        $down_ids = self::_downIds([$user_info['id']], []);

        $info['total_staff'] = count($down_ids) ?: 0;

        if($down_ids) {
            $info['total_staff_amount'] = M('User')->where(['id' => ['in', $down_ids]])->sum('recharge') ?: 0;
        } else {
            $info['total_staff_amount'] = 0;
        }

        echo json_encode(array('status' => 1, 'info' => $info));
        exit();
    }


    public function myOrder()
    {
        $pledge = M("Pledge");
        $model_user = M('User');
        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();
        //过滤掉已经结束的
    
        $where['uid'] =  $user_info['id'];
        $where['state'] = 1;
       //$list = $pledge->where(['uid' => $user_info['id']])->select();
       $list = $pledge->where($where)->select();
        $model_circle = M("Circle");

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        foreach ($list as $key => &$val) {
            // 获取返利百分比
            $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);
            $val['percent']=$return_percent;
            $val['url_hash']='https://tronscan.org/#/transaction/'.$val['hash'];

            $val['time_cha'] = $val['pledge_start_time']+($val['pledge_day']*24*60*60)-time();
        }

        echo json_encode(array('status' => 1, 'info' => $list));
        exit();
    }


    public function myOrderV2()
    {
        $pledge = M("Pledge");
        $model_user = M('User');
        $address = $_GET['unknown'];
        $user_info = $model_user->where(['address' => $address])->find();

        $list = M('Hash')->where(['uid' =>$user_info['id']])->select();

        // $list = $pledge->where(['uid' => $user_info['id']])->select();
        $model_circle = M("Circle");

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        foreach ($list as $key => &$val) {
            // 获取返利百分比
            $return_percent = self::_getReturnPercent($val['day'], $circle_info);
            $val['percent']=$return_percent;
            $val['url_hash']='https://tronscan.org/#/transaction/'.$val['hash'];

            $val['time_cha'] = $val['create_time']+($val['day']*24*60*60)-time();
        }

        // foreach ($list as $key => &$val) {
        //     // 获取返利百分比
        //     $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);
        //     $val['percent']=$return_percent;
        //     $val['url_hash']='https://tronscan.org/#/transaction/'.$val['hash'];

        //     $val['time_cha'] = $val['pledge_start_time']+($val['pledge_day']*24*60*60)-time();
        // }

        echo json_encode(array('status' => 1, 'info' => $list));
        exit();
    }

    public function returnProject(){
        $config = M("ProjectConfig");
        $info['project_name'] = $config->where(['name' => 'project_name'])->getField('value');
        $project_logo = $config->where(['name' => 'project_logo'])->getField('value');
        $info['project_logo'] = "https://admin.shero.vip". $project_logo;
        echo json_encode(array('status' => 1, 'info' => $info));
        exit();
    }

    /**
     *最后5个哈希充值
     */
    public function lastDeposits(){
        echo 1111;die;
        $model = M('Hash');
        $list = $model->limit(5)->order('id desc')->select();
        foreach ( $list as $key=>&$val){
            $val['url_hash']='https://tronscan.org/#/transaction/'.$val['hash'];
        }
        echo json_encode(array('status' => 1, 'info' => $list ?: []));
        exit();
    }


    /**
     * 购买质押套餐
     * @param $uid
     * @param $day
     * @param $amount
     */
    private function _buyPledge($uid,$amount, $day, $hash)
    {
        try {
            M()->startTrans();
            $pledge = M("Pledge");
          
                $data = [
                    'uid' => $uid,
                    'pledge_day' => $day,
                    'pledge_amount' => $amount,
                    'pledge_start_time' => time(),
                    'create_time' => time(),
                    'state' => 1,
                    'hash' => $hash,
                    'pledge_end_time' => time() + 86400 * $day,
                ];
            

            $res = $pledge->add($data);
            self::_addParentEarnings($uid, $res, $amount, $day);
            M()->commit();
        } catch (Exception $e) {
            M()->rollback();
        }

        return $this;
    }


    /**
     * @param $uid
     * @param $target_id
     * @param $amount
     * @param $day
     * @throws Exception
     */
    private function _addParentEarnings($uid, $target_id, $amount, $day){
        $model_circle = M("Circle");
        $user = M("User");
        $pledge = M("Pledge");
        $earnings = M('UserEarnings');

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        // 获取返利百分比
        $return_percent = self::_getReturnPercent($day, $circle_info);

        // 获取利息
        $interests = $amount * $return_percent / 100;

        // 需要返利上级的列表
        $p_ids = self::_getParentIds($uid);

        $log_array = [];

        if ($p_ids) {
            foreach ($p_ids as $k1 => $v1) {
                // 获取我的流通量
                $recharge = $user->where(['id' => $v1])->getField('recharge');

                $level = $k1 + 1; //我的层级

                $is_check = self::_isCheckTeamAward($recharge, $level);
                $team_award = 0;
                if ($is_check) {
                    //$team_percent = self::_getTeamReturnPercent($recharge, $circle_info);
                    $team_percent = self::_getTeamReturnPercentByLevel($level,$circle_info);

                    $team_award = $interests * $team_percent / 100;
                    $team_award = substr(sprintf("%.5f", $team_award), 0, -1);// 0.12

                    if ($team_award <= 0) {
                        continue;
                    }
                    // $res = $user->where(['id' => $v1])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $team_award]]);
                    // if (!$res) {
                    //     throw new Exception('生成团队奖励失败！');
                    // }

                    // // 添加日志
                    // $log_array[] = [
                    //     'uid' => $v1,
                    //     'amount' => $team_award,
                    //     'create_time' => time(),
                    //     'type' => 4,
                    //     'desc' => '团队奖励',
                    //     'target_id'=>$target_id,
                    // ];
                }
            }

            if($log_array){
                $res1 = $earnings->addAll($log_array);
                if (!$res1) {
                    throw new Exception('生成日志失败');
                }
            }


        }

        return $this;
    }

    /**
     *每日执行质押
     */
    public function dayExecute()
    {
        $pledge = M("Pledge");
        $model_circle = M("Circle");
        $user = M("User");
        $earnings = M('UserEarnings');
        $list = $pledge->where(['state' => 1])->select();
     
        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();
        if(empty($list)){
            echo json_encode(array('status' => 0, 'info' => '执行成功'));die;
        }
        foreach ($list as $key => $val) {
            $end_time =$val['pledge_end_time'];
            //忽略时间未到的
            if ($end_time > time()) {
                continue;
            }
            // 获取返利百分比
            $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);
            // 获取利息
            $interests = $val['pledge_amount'] * $return_percent / 100;
            try {
                M()->startTrans();
                //20220321注四掉
               /* $earnings_list =  $earnings->where(['target_id'=>$val['id']])->select();
                if($earnings_list){
                    foreach ($earnings_list as $k2=>$v2){
                        $user->where(['id' => $v2['uid']])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $v2['amount']]]);
                    }
                }*/
                $log_array = [];
                // 返利并结束当前质押
                $total = $interests + $val['pledge_amount'];
              /*  if($val['pledge_day']==3){
                $user->where(['id' => $val['uid']])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $total], 'circulate' => ['exp', 'circulate + ' . $val['pledge_amount']],'usdt' => ['exp', 'usdt + ' . $total]]);
                } else{
                    $user->where(['id' => $val['uid']])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $total], 'circulate' => ['exp', 'circulate + ' . $val['pledge_amount']],'recharge' => ['exp', 'recharge + ' . $total]]);
                }*/
                  if($val['pledge_day']==3){
                     $user->where(['id' => $val['uid']])->save( [
                    'circulate' => ['exp', 'circulate + ' . $val['pledge_amount']]
                    ,'usdt' => ['exp', 'usdt + ' . $total]
                    ]);
                } else{
                    $user->where(['id' => $val['uid']])->save(['circulate' => ['exp', 'circulate + ' . $val['pledge_amount']],'recharge' => ['exp', 'recharge + ' . $total]]);
                }
                $users = $user->where(['id' => $val['uid']])->find();
                echo '当前id'.  $users['id'].PHP_EOL;
                //没有上级，直接跳过
                if(empty($users['pid'])){
                    // 添加日志
                    $log_array[] = [
                        'uid' => $val['uid'],
                        'amount' => $val['pledge_amount'],
                        'create_time' => time(),
                        'type' => 5,
                        'desc' => '到期返还本金',
                        'target_id'=>$val['id'],
                    ];
                    // 添加日志
                    $log_array[] = [
                        'uid' => $val['uid'],
                        'amount' => $interests,
                        'create_time' => time(),
                        'type' => 0,
                        'desc' => '静态奖励',
                        'target_id'=>$val['id'],
                    ];
                  /*  if($interests<=0){
                        echo 'pledge_amount='.$val['pledge_amount'].'===return_percent=='. $return_percent;die;
                    }*/

                    $res1=  $earnings->addAll($log_array);
                    if(!$res1){
                        throw new Exception('生成日志失败');
                    }

                    // 结束当前质押
                    $res2= $pledge->where(['id' => $val['id']])->save(['state' => 0]);
                    if(!$res2){
                        throw new Exception('结束质押失败');
                    }
                    M()->commit();
                     echo 'uid='.$users['id'].'没有父id'.PHP_EOL;
                    continue;
                }
                //有上级
                $pidusers = $user->where(['id' => $users['pid']])->find();
                if($val['pledge_day']>0){
                 
                    $pledge_amount = $pledge->where(['uid'=>$users['id'],'pledge_day'=>3])->sum('pledge_amount');
                    echo 'pledge_amount='.$pledge_amount.PHP_EOL;
                    if ($pledge_amount>=100 ) {
                        $i = 1;
                        while ($i<20){
                            echo 'uid='.$users['id'].'i='.$i.'父id='.$pidusers['id'].PHP_EOL;
                            //推荐人总额度
                            $pledge_amount = $pledge->where(['uid'=>$pidusers['id'],'pledge_day'=>3])->sum('pledge_amount');
                            //如果没有90天铸币的，直接条下一级
                            $pledge90 = $pledge->where(['uid'=>$pidusers['id'],'pledge_day'=>90,'state'=>1])-->order('id desc')->find();
                            if(empty($pledge90)){
                                $i = $i+1;
                                continue;
                            }
                            if($i == 1){
                                $rate = 0.25;
                            }elseif($i == 2){
                                $rate = 0.2;
                            }elseif($i == 3){
                                $rate = 0.15;
                            }elseif($i>=4 && $i <=10){
                                $rate = 0.1;
                            }else{
                                $rate = 0.01;
                            }
                          
                            if($pledge_amount < $val['pledge_amount']){
                                 $amount = $pledge_amount* $return_percent / 100*$rate;
                            }else{
                                 $amount = $interests*$rate;
                            }
                            $foor = floor($pledge_amount/100);
                            //层级受限，直接跳出循环
                            if($foor < $i){
                               $amount = 0;
                            }
                         
                            $type = 1;
                            $desc = '直推奖励'.$users['id'].'-'.$i;
                            if($i!=1){
                                $type = 1;
                                $desc = '团队收益'.$users['id'].'-'.$i;
                            }
                            $log_array[] = [
                                'uid' => $pidusers['id'],
                                'amount' => $amount,
                                'create_time' => time(),
                                'type' => $type,
                                'desc' => $desc,
                                'target_id'=>$val['id'],
                            ];
                            $user->where(['id'=>$pidusers['id']])->save(['usdt'=>['exp','usdt + '.$amount]]);
                            
                            $pidusers = $user->where(['id' => $pidusers['pid']])->find();
                            if(empty($pidusers)){
                                 $i = 21;
                               //  continue;
                            }else{
                                $i = $i +1;
                            }
                            
                        }
                    }
                }
                // 添加日志
                $log_array[] = [
                    'uid' => $val['uid'],
                    'amount' => $val['pledge_amount'],
                    'create_time' => time(),
                    'type' => 5,
                    'desc' => '到期返还本金',
                    'target_id'=>$val['id'],
                ];
                // 添加日志
                $log_array[] = [
                    'uid' => $val['uid'],
                    'amount' => $interests,
                    'create_time' => time(),
                    'type' => 0,
                    'desc' => '静态奖励',
                    'target_id'=>$val['id'],
                ];
                $res1=  $earnings->addAll($log_array);

                // 结束当前质押
                $res2= $pledge->where(['id' => $val['id']])->save(['state' => 0]);

                M()->commit();
            } catch (Exception $e) {
                M()->rollback();
            }
        }
        echo json_encode(array('status' => 0, 'info' => '执行成功'));


    }
    public function dayExecutebg()
    {
        $pledge = M("Pledge");
        $model_circle = M("Circle");
        $user = M("User");
        $earnings = M('UserEarnings');
        $list = $pledge->where(['state' => 1])->select();

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        if ($list) {
            foreach ($list as $key => $val) {

                //$end_time = $val['pledge_start_time'] + $val['pledge_day'] * 86400;
                $end_time =$val['pledge_end_time'];
                if ($end_time > time()) {
                    continue;
                }

                // 获取返利百分比
                $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);

                // 获取利息
                $interests = $val['pledge_amount'] * $return_percent / 100;


                try {
                    M()->startTrans();

                    $earnings_list =  $earnings->where(['target_id'=>$val['id']])->select();
                    if($earnings_list){
                        foreach ($earnings_list as $k2=>$v2){
                            $user->where(['id' => $v2['uid']])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $v2['amount']]]);
                        }
                    }


                    $log_array = [];

                    // 返利并结束当前质押
                    $total = $interests + $val['pledge_amount'];

                    $user->where(['id' => $val['uid']])->save(['no_withdraw' => ['exp', 'no_withdraw + ' . $total], 'circulate' => ['exp', 'circulate + ' . $val['pledge_amount']],'usdt' => ['exp', 'usdt + ' . $total]]);
                    $users = $user->where(['id' => $val['uid']])->find();
                    if($users['pid']){
                        $pidusers = $user->where(['id' => $users['pid']])->find();
                        if($pidusers['zhubishang']==1){
                            if($val['pledge_day']==3){
                                $pledge_amount = $pledge->where(['uid'=>$users['pid'],'pledge_day'=>3])->sum('pledge_amount');

                                if ($pledge_amount>=100) {
                                    //   $total = 0;
                                    // code...

                                    // 添加日志
                                    // $log_array[] = [
                                    //     'uid' => $pidusers['uid'],
                                    //     'amount' => $interests*0.1,
                                    //     'create_time' => time(),
                                    //     'type' => 1,
                                    //     'desc' => '直推奖励',
                                    //     'target_id'=>$val['id'],
                                    // ];
                                    // $total = $interests*0.1 + $pidusers['pledge_amount'];
                                    //  $user->where(['id' => $users['pid']])->save(['usdt' => ['exp', 'usdt + ' . $total]]);

                                    //添加日志
                                    if($val['pledge_amount']<=100){
                                        $usdtinterests = $interests*0.2;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=200&&$val['pledge_amount']>100){
                                        $usdtinterests = $interests*0.15;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=300&&$val['pledge_amount']>200){
                                        $usdtinterests = $interests*0.1;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=1000&&$val['pledge_amount']>300){
                                        $usdtinterests = $interests*0.05;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=2000&&$val['pledge_amount']>1000){
                                        $usdtinterests = $interests*0.02;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    $update1['usdt'] = $total+ $pidusers['usdt'];
                                    //$user->where(['id'=>$user['pid']])->save(['usdt'=>['exp','usdt + '.$total]]);
                                    //$user->where(['id'=>$user['pid']])->update($update1);
                                    // $user->where(['id'=>$pidusers['id']])->update($update1);
                                    $user->where(['id'=>$pidusers['id']])->save(['usdt'=>['exp','usdt + '.$total]]);
                                }
                            }
                        }else{
                            if($val['pledge_day']==1){
                                // 添加日志
                                // $log_array[] = [
                                //     'uid' => $pidusers['uid'],
                                //     'amount' => $interests*0.5*0.01,
                                //     'create_time' => time(),
                                //     'type' => 1,
                                //     'desc' => '直推奖励',
                                //     'target_id'=>$val['id'],
                                // ];
                                //  $total = $interests*0.5*0.01 + $pidusers['pledge_amount'];
                            }
                            if($val['pledge_day']==5){
                                // 添加日志
                                //             // $log_array[] = [
                                //             //     'uid' => $pidusers['uid'],
                                //             //     'amount' => $interests*3.5*0.01,
                                //             //     'create_time' => time(),
                                //             //     'type' => 1,
                                //             //     'desc' => '直推奖励',
                                //             //     'target_id'=>$val['id'],
                                //             // ];
                                //             // $total = $interests*3.5*0.01 + $pidusers['pledge_amount'];
                                // }
                                //  if($val['pledge_day']==10){
                                //   // 添加日志
                                //             $log_array[] = [
                                //                 'uid' => $pidusers['uid'],
                                //                 'amount' => $interests*9*0.01,
                                //                 'create_time' => time(),
                                //                 'type' => 1,
                                //                 'desc' => '直推奖励',
                                //                 'target_id'=>$val['id'],
                                //             ];
                                //             $total = $interests*9*0.01 + $pidusers['pledge_amount'];
                            }
                            if($val['pledge_day']==20){
                                // 添加日志
                                // $log_array[] = [
                                //     'uid' => $pidusers['uid'],
                                //     'amount' => $interests*21*0.01,
                                //     'create_time' => time(),
                                //     'type' => 1,
                                //     'desc' => '直推奖励',
                                //     'target_id'=>$val['id'],
                                // ];
                                // $total = $interests*21*0.01 + $pidusers['pledge_amount'];
                            }
                            $user->where(['id' => $users['pid']])->save(['recharge' => ['exp', 'recharge + ' . $total]]);
                            if($val['pledge_day']==3){
                                $pledge_amount = $pledge->where(['uid'=>$users['pid'],'pledge_day'=>3])->sum('pledge_amount');
                                if ($pledge_amount>=100) {
                                    //添加日志
                                    if($val['pledge_amount']<=100){
                                        $usdtinterests = $interests*0.2;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=200&&$val['pledge_amount']>100){
                                        $usdtinterests = $interests*0.15;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=300&&$val['pledge_amount']>200){
                                        $usdtinterests = $interests*0.1;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=1000&&$val['pledge_amount']>300){
                                        $usdtinterests = $interests*0.05;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    if($val['pledge_amount']<=2000&&$val['pledge_amount']>1000){
                                        $usdtinterests = $interests*0.02;
                                        $log_array[] = [
                                            'uid' => $pidusers['uid'],
                                            'amount' => $usdtinterests,
                                            'create_time' => time(),
                                            'type' => 1,
                                            'desc' => '直推奖励',
                                            'target_id'=>$val['id'],
                                        ];
                                        $total = $usdtinterests + $pidusers['pledge_amount'];
                                    }
                                    $user->where(['id'=>$user['pid']])->save(['usdt'=>['exp','usdt + '.$total]]);
                                }
                            }

                        }
                    }
                    // 添加日志
                    $log_array[] = [
                        'uid' => $val['uid'],
                        'amount' => $val['pledge_amount'],
                        'create_time' => time(),
                        'type' => 5,
                        'desc' => '到期返还本金',
                        'target_id'=>$val['id'],
                    ];
                    // 添加日志
                    $log_array[] = [
                        'uid' => $val['uid'],
                        'amount' => $interests,
                        'create_time' => time(),
                        'type' => 0,
                        'desc' => '静态奖励',
                        'target_id'=>$val['id'],
                    ];

                    $res1=  $earnings->addAll($log_array);
                    if(!$res1){
                        throw new Exception('生成日志失败');
                    }

                    // 结束当前质押
                    $res2= $pledge->where(['id' => $val['id']])->save(['state' => 0]);
                    if(!$res2){
                        throw new Exception('结束质押失败');
                    }
                    M()->commit();

                    // 收益发放
                    // self::withdraw($val['uid']);
                } catch (Exception $e) {
                    M()->rollback();
                }
            }
        }
        echo json_encode(array('status' => 0, 'info' => '执行成功'));


    }

    public function width_test() {
        //  self::withdraw(6);
    }

    /**
     * 获取返利百分比
     * @param $pledge_day
     * @param $circle_info
     * @return int
     */
    private function _getReturnPercent($pledge_day, $circle_info)
    {
        $percent = 0;

        switch ($pledge_day) {
            case 1:
                $percent = $circle_info['one_day'];
                break;
            case 3:
                $percent = 3;
                break;
            case 5:
                $percent = $circle_info['five_day'];
                break;
            case 10:
                $percent = $circle_info['ten_day'];
                break;
            case 20:
                $percent = $circle_info['twenty_day'];
                break;
        }

        return $percent;
    }

    /**
     * 获取我的上级的IDS
     * @param $uid
     */
    private function _getParentIds($uid)
    {
        $user = M("User");

        $p_ids = [];
        $level = 0;
        do {
            $pid = $user->where(['id' => $uid])->getField('pid');
            if ($pid) {
                $uid = $pid;
                $p_ids[] = $pid;
            } else {
                $level = 21;
            }

        } while ($level < 20);

        return $p_ids;
    }

    /**
     * 检查是否可以拿到团队奖励
     * @param $recharge
     * @param $level
     * @return bool
     */
    private function _isCheckTeamAward($recharge, $level)
    {

        $is_check = false;
        switch ($level) {
            case 1:
                if ($recharge >= 1000) {
                    $is_check = true;
                }
                break;
            case 2:
                if ($recharge >= 2000) {
                    $is_check = true;
                }
                break;
            case 3:
                if ($recharge >= 3000) {
                    $is_check = true;
                }
                break;
            case 4:
                if ($recharge >= 4000) {
                    $is_check = true;
                }
                break;
            case 5:
                if ($recharge >= 5000) {
                    $is_check = true;
                }
                break;
            case 6:
                if ($recharge >= 6000) {
                    $is_check = true;
                }
                break;
            case 7:
                if ($recharge >= 7000) {
                    $is_check = true;
                }
                break;
            case 8:
                if ($recharge >= 8000) {
                    $is_check = true;
                }
                break;
            case 9:
                if ($recharge >= 9000) {
                    $is_check = true;
                }
                break;
            case 10:
                if ($recharge >= 10000) {
                    $is_check = true;
                }
                break;
            case 11:
                if ($recharge >= 11000) {
                    $is_check = true;
                }
                break;
            case 12:
                if ($recharge >= 12000) {
                    $is_check = true;
                }
                break;
            case 13:
                if ($recharge >= 13000) {
                    $is_check = true;
                }
                break;
            case 14:
                if ($recharge >= 14000) {
                    $is_check = true;
                }
                break;
            case 15:
                if ($recharge >= 15000) {
                    $is_check = true;
                }
                break;
            case 16:
                if ($recharge >= 16000) {
                    $is_check = true;
                }
                break;
            case 17:
                if ($recharge >= 17000) {
                    $is_check = true;
                }
                break;
            case 18:
                if ($recharge >= 18000) {
                    $is_check = true;
                }
                break;
            case 19:
                if ($recharge >= 19000) {
                    $is_check = true;
                }
                break;
            case 20:
                if ($recharge >= 20000) {
                    $is_check = true;
                }
                break;
        }

        return $is_check;

    }

    /**
     * 获取团队奖励百分比
     * @param $recharge
     * @param $circle_info
     * @return int
     */
    private function _getTeamReturnPercent($recharge, $circle_info)
    {
        $team_percent = 0;

        if ($recharge >= 100 && $recharge < 200) {
            $team_percent = $circle_info['one_level'];
        }

        if ($recharge >= 200 && $recharge < 300) {
            $team_percent = $circle_info['two_level'];
        }

        if ($recharge >= 300 && $recharge < 400) {
            $team_percent = $circle_info['three_level'];
        }

        if ($recharge >= 400 && $recharge < 1100) {
            $team_percent = $circle_info['four_level'];
        }

        if ($recharge >= 1100) {
            $team_percent = $circle_info['five_level'];
        }
        return $team_percent;
    }

    /**
     * 获取团队奖励百分比
     * @param $recharge
     * @param $circle_info
     * @return int
     */
    private function _getTeamReturnPercentByLevel($level, $circle_info)
    {
        $team_percent = 0;
        switch ($level) {
            case 1:
                $team_percent = $circle_info['one_level'];
                break;
            case 2:
                $team_percent = $circle_info['two_level'];
                break;
            case 3:
                $team_percent = $circle_info['three_level'];
                break;
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
                $team_percent = $circle_info['four_level'];
                break;
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
                $team_percent = $circle_info['five_level'];
                break;
        }

        return $team_percent;
    }

}


?>
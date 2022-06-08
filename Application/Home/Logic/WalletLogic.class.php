<?php
/**
 *  源计划
 *  为用户带来更加安全、便捷、极致的操作体验一直是技术团队的不懈追求
 * ------------------------------
 *  Author: dashuaibi
 * --------------------------------
 *  Copyright (c) 2020~2030 主旋律网络科技有限公司 All rights reserved.
 * --------------------------------------------------------
 */
use GuzzleHttp\Client;

class WalletLogic
{
    private static $url = "https://sapi.ztpay.org/api/v2";
    public static $appId = "ztpayutnc3imsilrhh";
    private static $appSecret = "9aDPvmSAqclf8R1U6NS4kn8fKOTP4zkF";
    private static $eth_address = '0x42db7a22080aa26df5e762a726061faf205e9e7c';
    private static $bit_address = '19f3DJBhpck5GMDdwMApWDasNwd4AVRXmr';
    private static $safe_pwd = "Aa123123";
    private static $charge_fee = 0.0022;
    private static $charge_fee_btc = 0.0000098;
    private static $gas = 30000;
    private static $gas_erc20 = 71000;
    private static $mtc_address = 'TG8T8cFa6qEqXKP9UFE18ZT6jmzHwGkZQh';
    public static function getAddress($currencyName){
        if($currencyName == 'USDT'){
            $currencyName = $currencyName.'_ERC20';
        }
        if($currencyName == 'MTC') {
            $currencyName = $currencyName.'_TRC20';
        }
        $data = [
            'appid' => self::$appId,
            'method' => 'get_address',
            'name' => $currencyName,
        ];

        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            throw new \Exception('请求地址接口出错');
        }else{
            return $result->data->address;
        }
    }

    public static function getGasPrice(){

        $data = [
            'appid' => self::$appId,
            'method' => 'get_eth_gasprice',

        ];
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            return 400;
        }else{
            return $result->data->fastest;
        }
    }

    public static function isChangeAddress($address){
        if(in_array($address,[
            self::$eth_address,
            self::$bit_address
        ])){
            return true;
        }else{
            return false;
        }
    }

    public static function chargeEth($address){

        $data = [
            'appid' => self::$appId,
            'method' => 'transfer',
            'name' => 'ETH',
            'from' => self::$eth_address,
            'to' => $address,
            'gas' => self::$gas,
            'amount' => self::getEthFee()
        ];
        $data['gasPrice'] = self::getGasPrice();
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            return false;
        }else{
            return true;
        }
    }

    public static function chargeBit($address){
        $data = [
            'appid' => self::$appId,
            'method' => 'transfer',
            'name' => 'BTC',
            'from' => self::$bit_address,
            'to' => $address,
            'amount' => self::$charge_fee_btc
        ];
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            return false;
        }else{
            return true;
        }
    }

    public static function withdraw($address,$amount = null){
        $wallet = UsersWallet::join('currency','currency','=','currency.id')->where('address',$address)->orWhere('address_2',$address)->first();
        if($wallet->currency == 3){
            if($address == $wallet->address){
                $wallet->name = $wallet->name.'_ERC20';
                $to = config('app.usdt_erc20');
                $is_erc = true;
            }else{
                $wallet->name = $wallet->name.'_OMNI';
                $to = config('app.usdt_omni');
                $is_omni = true;
            }

        }
        if($wallet->currency == 2){
            $is_eth = true;
        }

        $balance = self::getBalance($address);
        $fee = 0;
        if($wallet->currency == 3){
            $amount = $balance->USDT;
            if($amount<=0){
                echo sprintf('地址%s余额为0 不提现',$address).PHP_EOL;
                return true;
            }
            $compare = false;
            //判断账号手续费够不
            if(isset($is_erc)){
                $fee = self::$charge_fee;
                $remain_balance = $balance->ETH;
                $compare = $remain_balance>=self::getEthFee()?true:false;
            }
            if(isset($is_omni)){
                $fee = self::$charge_fee_btc;
                $remain_balance = $balance->BTC;
                $compare = $remain_balance>=self::$charge_fee_btc?true:false;
            }

            if($compare){

            }else{
                //充钱 然后进队列等提现
                if(!self::isInChargeQueue($address)){
                    echo sprintf('充值手续费地址%s ',$address).PHP_EOL;
                    $res = isset($is_erc)?self::chargeEth($address):self::chargeBit($address);
                    $res && self::addChargingQueue($address);
                }
                WalletLogic::addWithdrawQueue($address);
                //如果queue 设置的是同步  则不会延迟
//                ZTPayAddressWithdraw::dispatch($address)->delay(Carbon::now()->addMinutes(10));
                return false;
            }
        }else{
            $amount = $balance;
        }


        if ($wallet->currency == 1) {
            $to = config('app.btc');
        } elseif ($wallet->currency == 3) {
            $to = $to;
        } elseif ($wallet->currency == 2) {
            $to = config('app.eth');
            $fee = self::$charge_fee;
        } else{
            throw new \Exception('官方账户不存在'.$wallet->name);
        }
        if($amount<=0){
            echo sprintf('地址%s余额为0 不提现',$address).PHP_EOL;
//             log_exception('账户余额为0','');
            return true;
        }
        $data = [
            'appid' => self::$appId,
            'method' => 'transfer',
            'name' => $wallet->name,
            'from' => $address,
            'to' => $to,
            'amount' => $amount
        ];
        if(isset($is_erc) || isset($is_eth)){
            $data['gas'] = $is_erc?self::$gas_erc20:self::$gas;
            $data['gasPrice'] = self::getGasPrice();
            if(isset($is_eth)){
                //余额减去手续费 再减去0.0001 避免余额不够
                $data['amount'] = bc_sub($data['amount'],bc_add(bc_div(bc_mul($data['gas'],$data['gasPrice'],8),1000000000,8),0.0001,8),8);
            }
        }
        else if($fee){
            $data['amount'] = bc_sub($amount,$fee);
//             $data['fee_amount'] = $fee;
        }
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            $res = json_encode([
                'result' => $result,
                'data' => $data,
            ]);
            log_exception('转账失败:'.$result->message,$res);
            throw new \Exception('转账失败:'.$result->message);
        }else{
            return true;
        }
    }

    public static function getBalance($address ,$return_eth = false){
        //先获取余额
        $wallet = UsersWallet::join('currency','currency','=','currency.id')->where('address',$address)->orWhere('address_2',$address)->first();
        if($wallet->currency == 3){
            if($address == $wallet->address){
                $wallet->name = $wallet->name.'_ERC20';
            }else{
                $wallet->name = $wallet->name.'_OMNI';
            }
        }
        $data = [
            'appid' => self::$appId,
            'method' => 'get_balance',
            'name' => $wallet->name,
            'address' => $address
        ];
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            throw new \Exception('请求余额错误'.$result->message);
        }else{
            if($wallet->currency == 3){
                return $result->data;
            }
            return $result->data->{$wallet->name};
        }
    }

    public function getPrivate($address){
        $wallet = UsersWallet::join('currency','currency','=','currency.id')->where('address',$address)->orWhere('address_2',$address)->first();
        if($wallet->currency == 3){
            if($address == $wallet->address){
                $wallet->name = $wallet->name.'_ERC20';

            }else{
                $wallet->name = $wallet->name.'_OMNI';

            }
        }
        $data = [
            'appid' => self::$appId,
            'method' => 'get_privatekey',
            'name' => $wallet->name,
            'address' => $address,
            'security_pwd' => self::$safe_pwd,
        ];
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {

            throw new \Exception('获取私钥错误');
        }else{
            return $result->data->privatekey;
        }

    }

    public static function getEthFee(){
        return bc_div(bc_mul(self::$gas_erc20,self::getGasPrice(),8),1000000000,8);
    }


    public static function getSign($data) {
        $signPars = "";
        ksort($data);
        foreach($data as $k => $v) {
            if("sign" != $k && "" != $v && $v!="0") {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars .= "key=" . self::$appSecret;
        return strtoupper(md5($signPars));
    }

    public static function addChargingQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $redis->zAdd('charge_address',['NX'],time(),$address);
    }

    public static function delChargingQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $redis->zRem('charge_address',$address);
    }

    public static function isInChargeQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $res = $redis->zRank('charge_address',$address);
        if($res === false) {
            return false;
        } else {
            return true;
        }
    }
    public static function getChargingQueue(){
        $redis = \App\Service\RedisService::getInstance();
        $res = $redis->zRange('charge_address',0,-1);
        return $res;
    }

    public static function addWithdrawQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $redis->zAdd('withdraw_address',['NX'],time(),$address);
    }

    public static function delWithdrawQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $redis->zRem('withdraw_address',$address);
    }

    public static function isPro(){
        $redis = \App\Service\RedisService::getInstance();
//        $redis->set('production_1',1);
        $res = $redis->get('production_1');
        if(!$res){
            return false;
        }else{
            return true;
        }
    }

    public static function isInQueue($address){
        $redis = \App\Service\RedisService::getInstance();
        $res = $redis->zRank('withdraw_address',$address);
        if($res === false) {
            return false;
        } else {
            return true;
        }
    }

    public static function getQueue(){
        if(self::isPro()){

        }else{
            return [];
        }
        $redis = \App\Service\RedisService::getInstance();
        return $redis->zRange('withdraw_address',0,-1);
    }

    /**
     * 转账
     * @param string $address 地址
     * @param int $curr 币种ID
     * @param $amount
     * @return bool
     */
    public static function charge($address, $curr, $amount) {
        switch ($curr) {
            case 31:
                $name = 'MTC_TRC20';
                break;
            case 32:
                $name = 'OTC_TRC20';
                break;
            case 33:
                $name = 'RTC_TRC20';
                break;
            case 34:
                $name = 'PTC_TRC20';
                break;
            default:
                $name = 'MTC_TRC20';
        }
        $res = self::chargeMorp($address,$name, $amount);
        return $res;
    }

    // 转账四币
    public static function chargeMorp($address, $name, $amount){
        $data = [
            'appid' => self::$appId,
            'method' => 'transfer',
            'name' => $name,
            'from' => self::$mtc_address,
            'to' => $address,
            'amount' => $amount
        ];
        $data['sign'] = self::getSign($data);
        $http_client = new Client();
        $response = $http_client->post(self::$url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->code != 0) {
            return false;
        }else{
            return true;
        }
    }

}

<?php


namespace Admin\Controller;

use Common\Logic\PledgeLogic;
use Think\Upload;

class IndexController extends BaseController
{

    public function index()
    {

        $this->display('index');
    }

    /**
     *圈子获取首页
     */
    public function welcome()
    {
        $rechage = M("Rechage");
        $withdraw = M('WithdrawLog');
        $admin = M('Admin');
        $pledge = new PledgeLogic();
        $income_total = $rechage->sum('rechage');
        $out_total = $withdraw->sum('amount');
       $admin_info = $admin->where(['name'=>'admin'])->find();
        // 今天的开始和结束时间
        $day_start = mktime('0', '0', '0', date("m", time()), date("d", time()), date('Y', time()));
        $day_income_total = $rechage->where(['create_time' => ['egt', $day_start]])->sum('rechage');
        $day_out_total = $withdraw->where(['create_time' => ['egt', $day_start]])->sum('amount');

        // 下一天要出币数量
        $next_out_total = $pledge->getNextTotal();

        $address_total = $income_total - $out_total;

        $info = [
            'income_total' => $income_total ?: 0,
            'out_total' => $out_total ?: 0,
            'day_income_total' => $day_income_total ?: 0,
            'day_out_total' => $day_out_total ?: 0,
            'next_out_total' => $next_out_total ?: 0,
            'address_total' => $address_total ?: 0,
            'ip' => $admin_info['ip'] ?: '127.0.0.1',
        ];

        $this->assign('info', $info);
        $this->display('welcome');
    }
    
    /**
     *修改密码
     */
    public function saveEditPassword()
    {
        $admin = M("Admin");

            $admin_info = $admin->where(['id' => $_SESSION['admin_id']])->find();

            if (!$admin_info) {
 
                $this->ajaxReturn(['msg' => '账户有误', 'code' => 0]);
            }

            if ($admin_info['password'] != md5(trim($_POST['old_password']))) {

                $this->ajaxReturn(['msg' => '旧密码有误', 'code' => 0]);
            }

            if ($_POST['old_password'] == $_POST['new_password']) {

                $this->ajaxReturn(['msg' => '新密码不能和旧密码一致', 'code' => 0]);
            }

            if ($_POST['again_password'] != $_POST['new_password']) {

                $this->ajaxReturn(['msg' => '俩次新密码不一致', 'code' => 0]);
            }

            $res = $admin->where(['id' => $_SESSION['admin_id']])->save(['password' => md5(trim($_POST['new_password']))]);
            if (!$res) {
                $this->ajaxReturn(['msg' => '密码修改失败', 'code' => 0]);
            }
            $this->ajaxReturn(['msg' => '修改成功！', 'code' => 1]);

    }
    
    
    public function circle()
    {
        $this->display('circle');
    }

    /**
     *获取圈子列表
     */
    public function getList()
    {
        $circle = M("Circle");
        $list = $circle->page($_GET['page'], $_GET['limit'])->select();

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $circle->count(),
        ];
        $this->ajaxReturn($data);
    }

    /**
     *添加活动
     */
    public function add()
    {
        $this->display('add');
    }

    /**
     *保存活动
     */
    public function saveAdd()
    {
        $circle = M("Circle");

        $res =$circle->where(['state'=>1])->find();

        if($res && $_POST['state']==1){
            $this->ajaxReturn(['msg' => '存在已开启的圈层，不能再次添加开启圈层，请关闭', 'code' => 0]);
        }

        $data = [
            'name' => trim($_POST['name']),
            'state' => trim($_POST['state']),
            'one_day' => trim($_POST['one_day']),
            'five_day' => trim($_POST['five_day']),
            'ten_day' => trim($_POST['ten_day']),
            'twenty_day' => trim($_POST['twenty_day']),
            'one_level' => trim($_POST['one_level']),
            'two_level' => trim($_POST['two_level']),
            'three_level' => trim($_POST['three_level']),
            'four_level' => trim($_POST['four_level']),
            'five_level' => trim($_POST['five_level']),
            'create_time' => time(),
        ];
        $circle->add($data);
        $this->ajaxReturn(['msg' => '添加成功', 'code' => 1]);
    }

    /**
     *编辑活动
     */
    public function edit()
    {
        $circle = M("Circle");
        $list = $circle->where(['id' => $_GET['id']])->find();
        $this->assign('info', $list);
        $this->display('edit');
    }

    public function saveEdit()
    {
        $circle = M("Circle");
        $pledge = M("Pledge");

        $info = $circle->where(['id' => $_POST['id']])->find();
        if (!$info) {
            $this->ajaxReturn(['msg' => '未查询到对应数据', 'code' => 0]);
        }

        $res =$circle->where(['state'=>1])->find();
        if($res && $_POST['state']==1 && $res['id']!=$_POST['id']){
            $this->ajaxReturn(['msg' => '存在已开启的圈层，不能开启圈层，请先关闭开启圈层', 'code' => 0]);
        }

        $data = [
            'name' => trim($_POST['name']),
            'state' => trim($_POST['state']),
            'one_day' => trim($_POST['one_day']),
            'five_day' => trim($_POST['five_day']),
            'ten_day' => trim($_POST['ten_day']),
            'twenty_day' => trim($_POST['twenty_day']),
            'one_level' => trim($_POST['one_level']),
            'two_level' => trim($_POST['two_level']),
            'three_level' => trim($_POST['three_level']),
            'four_level' => trim($_POST['four_level']),
            'five_level' => trim($_POST['five_level']),
            'create_time' => time(),
        ];

        $res2 = $circle->where(['id' => $_POST['id']])->save($data);

        // 开启其他圈层，
        if ($_POST['state'] == 1 && $res2 && $res['id'] != $_POST['id']) {
            // 第二天的开始时间
            $time = mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()));
            $pledge->where(['state' => 1])->select(['pledge_start_time' => $time]);
        }

        $this->ajaxReturn(['msg' => '修改成功', 'code' => 1]);
    }

    public function del()
    {
        $circle = M("Circle");
        $circle->where(['id' => $_GET['id']])->delete();
        $this->ajaxReturn(['msg' => '删除成功', 'code' => 1]);
    }

    public function user()
    {
        $this->display('user');
    }
     public function withdraw()
    {
        $this->display('withdraw');
    }
     public function endWithdraw()
    {
        $this->display('end_withdraw');
    }
    /**
     *获取用户列表
     */
    public function getUserList()
    {
        $user = M("User");
        $rechage = M("Rechage");
        $withdraw = M('WithdrawLog');
        $where=[];
        if($_GET['address']){
            $address= trim($_GET['address']);
            $where['address']=['like',"$address%"];
        }
        if($_GET['address_child']){
            $address_child= trim($_GET['address_child']);
            $parent = $user->where(['address'=>$address_child])->find();
            $where['pid'] = $parent['id'];
        }

        $list = $user->where($where)->page($_GET['page'], $_GET['limit'])->select();
        foreach ($list as $key=>&$val){
            $val['income'] =$rechage->where(['uid'=>$val['id']])->sum('rechage')?:0;
            $val['out']=$withdraw->where(['uid'=>$val['id']])->sum('amount')?:0;
           // $val['withdraw_time'] = date("Y-m-d H:i:s", $val['withdraw_time']);
             if($val['withdraw_time']!=0){
            $val['withdraw_time'] = date("Y-m-d H:i:s", $val['withdraw_time']);
            }else{
                 $val['withdraw_time'] ='';
            }
            $val['parent'] = '';
            if($val['pid']!=0){
                $parent = $user->where(['id'=>$val['pid']])->find();
                $val['parent'] = $parent['address']?$parent['address']:'上级被删除';
            }
        }

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $user->where($where)->count(),
        ];
        $this->ajaxReturn($data);
    }
    /**
     *获取用户申请提现
     */
    public function getWithdrawList()
    {
        $user = M("User");
        $rechage = M("Rechage");
        $withdraw = M('WithdrawLog');
        $where=[];
        if($_GET['address']){
            $address= trim($_GET['address']);
            $where['address']=['like',"$address%"];
        }
   
        $list = $user->where($where)->where(['no_withdraw'=> ['gt', 0]])->order('withdraw_time desc')->page($_GET['page'], $_GET['limit'])->select();
        foreach ($list as $key=>&$val){
            $val['income'] =$rechage->where(['uid'=>$val['id']])->sum('rechage')?:0;
            $val['out']=$withdraw->where(['uid'=>$val['id']])->sum('amount')?:0;
            if($val['withdraw_time']!=0){
            $val['withdraw_time'] = date("Y-m-d H:i:s", $val['withdraw_time']);
            }else{
                 $val['withdraw_time'] ='';
            }
        }

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $user->where($where)->where(['no_withdraw'=> ['gt', 0]])->count(),
        ];
        $this->ajaxReturn($data);
    }
/**
     *获取用户已经结束提现
     */
    public function getEndWithdrawList()
    {
        $user = M("User");
        $rechage = M("Rechage");
        $withdraw = M('WithdrawLog');
        $where=[];
        if($_GET['address']){
            $address= trim($_GET['address']);
            $where['address']=['like',"$address%"];
        }
   
        $list = $user->where($where)->where(['no_withdraw'=> 0,'withdraw'=>['gt',0]])->order('withdraw_time desc')->page($_GET['page'], $_GET['limit'])->select();
        foreach ($list as $key=>&$val){
            $val['income'] =$rechage->where(['uid'=>$val['id']])->sum('rechage')?:0;
            $val['out']=$withdraw->where(['uid'=>$val['id']])->sum('amount')?:0;
            if($val['withdraw_time']!=0){
            $val['withdraw_time'] = date("Y-m-d H:i:s", $val['withdraw_time']);
            }else{
                 $val['withdraw_time'] ='';
            }
        }

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $user->where($where)->where(['no_withdraw'=> 0,'withdraw'=>['gt',0]])->count(),
        ];
        $this->ajaxReturn($data);
    }

    public function setting()
    {
        $config = M("ProjectConfig");
        $info['project_name'] = $config->where(['name' => 'project_name'])->getField('value');
        $info['project_logo'] = $config->where(['name' => 'project_logo'])->getField('value');
        $this->assign('info', $info);

        $this->display('setting');
    }

    public function saveSetting()
    {
        $config = M("ProjectConfig");
        unset($_POST['file']);
        foreach ($_POST as $key => $val) {
            $config->where(['name' => $key])->save(['value' => $val]);
        }

        $data = [
            'msg' => '设置成功',
            'code' => 0,
        ];
        $this->ajaxReturn($data);
    }

    public function uploadLogo()
    {
        $upload = new Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Upload/'; // 设置附件上传根目录
        $upload->savePath = 'logo/'; // 设置附件上传（子）目录

        $info = $upload->uploadOne($_FILES['file']);
        $data = [
            'img_path' => '/Upload/' . $info['savepath'] . $info['savename'],
            'code' => 0,
        ];

        $this->ajaxReturn($data);

    }

    public function order(){
        $this->display('order');
    }
    public function endOrder(){
        $this->display('end_order');
    }
    public function orderList(){
        $pledge = M("Pledge");
        $earnings = M('UserEarnings');
        $model_circle = M("Circle");

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        $where =[];
        if($_GET['address']){
            $address= trim($_GET['address']);
            $where['address']=['like',"$address%"];
        }
        $field="a.*,b.address";
        $list = $pledge->alias('a')->field($field)->join('__USER__ b ON b.id= a.uid')->where(['a.pledge_end_time' => ['gt', time()]])->order('a.id desc')->page($_GET['page'], $_GET['limit'])->select();


        foreach ($list as $key => &$val) {
            // 获取返利百分比
            $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);
          
            $val['create_time'] = date("Y-m-d H:i:s", $val['create_time']);
            $val['pledge_end_time'] = date("Y-m-d H:i:s", $val['pledge_end_time']);

            // 获取利息
            $interests = $val['pledge_amount'] * $return_percent / 100;
        
            $val['static_amount'] = $interests + $val['pledge_amount'];
            $val['dynamic_amount'] = $earnings->where(['target_id'=>$val['id']])->sum('amount');
        }

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $pledge->alias('a')->join('__USER__ b ON b.id= a.uid')->where($where)->count(),
        ];
        $this->ajaxReturn($data);
    }
    
    public function endOrderList(){
        $pledge = M("Pledge");
        $earnings = M('UserEarnings');
        $model_circle = M("Circle");

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        $where =[];
        if($_GET['address']){
            $address= trim($_GET['address']);
            $where['address']=['like',"$address%"];
        }
        $field="a.*,b.address";
        $list = $pledge->alias('a')->field($field)->join('__USER__ b ON b.id= a.uid')->where(['a.pledge_end_time' => ['elt', time()]])->page($_GET['page'], $_GET['limit'])->order('a.pledge_end_time')->select();


        foreach ($list as $key => &$val) {

            // 获取返利百分比
            $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);

            $val['create_time'] = date("Y-m-d H:i:s", $val['create_time']);
            $val['pledge_end_time'] = date("Y-m-d H:i:s", $val['pledge_end_time']);

            // 获取利息
            $interests = $val['pledge_amount'] * $return_percent / 100;

            $val['static_amount'] = $interests + $val['pledge_amount'];
            $val['dynamic_amount'] = $earnings->where(['target_id'=>$val['id']])->sum('amount');
        }

        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $pledge->alias('a')->join('__USER__ b ON b.id= a.uid')->where($where)->count(),
        ];
        $this->ajaxReturn($data);
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
                $percent = $circle_info['three_day'];
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
    
    
    /**查余额
 * @param string $net
 * @param string $currency
 * @param string $address
 */
function  getAdmount($net='BEP20',$currency='MPC',$address = '0xc0354b09842408BaA38754f7D75e6aBc16b3250B'){
    $url = 'https://api.duobifu.com/mpc/getBalance?net='.$net.'&currency='.$currency.'&address='.$address;
    $ret = file_get_contents($url);
    $retArr = json_decode(  $ret,true);
    return  $retArr['data']['balance'];
}

/**转账
 * @param $address
 * @param $amount
 */
    function sendTransaction($address,$amount){
    $url = 'https://api.duobifu.com/mpc/sendTransaction?address='.$address.'&amount='.$amount;
    $ret = file_get_contents($url);
    $retArr = json_decode(  $ret,true);
    return $retArr['data']['txid'];
}



    public function provide(){
        $model_user  =M("User");
        $log = M('WithdrawLog');

        $id = $_GET['id'];
        $user_info = $model_user->where(['id'=>$id])->find();
        if(!$user_info){
            $this->ajaxReturn(['msg' => '信息有误！', 'code' => 0]);
        }

        $withdraw = $user_info['no_withdraw'];
        $withdraw_current = $user_info['withdraw_current'];
        
        $walletAmount = $this->getAdmount();
        if($walletAmount < $withdraw_current){
            $this->ajaxReturn(['msg' => '出款账户余额不足！', 'code' => 0]);
        }
        
        if($withdraw_current==0){
            $this->ajaxReturn(['msg' => '没有可结算金额！', 'code' => 0]);
        }

        $update=[
            'withdraw'=>['exp','withdraw + '.$withdraw],
            'no_withdraw'=>0,
            'withdraw_total'=>['exp','withdraw_total + '.$withdraw_current],
        ];

        $res = $model_user->where(['id' => $id])->save($update);
        if (!$res) {
            $this->ajaxReturn(['msg' => '发放失败！', 'code' => 0]);
        }
        $fee = $withdraw_current*0.01;//手续费
        $hash = $this->sendTransaction($user_info['address'],$withdraw_current-$fee);

        $log_data = [
            'amount' => $withdraw_current,
            'create_time' => time(),
            'fee' => $fee,
            'uid' => $user_info['id'],
            'address' => $user_info['address'],
            'tx_addr' => $user_info['address'],
            'type'=>1,// 后台发放
            'hash' => $hash
        ];
        $log->add($log_data);

        $this->ajaxReturn(['msg' => '发放成功', 'code' => 1]);
    }

    public function detail(){
        $id = $_GET['id'];

        $this->assign('id', $id);
        $this->display('detail');
    }

    public function getUserOrderList(){
        $earnings = M('UserEarnings');
        $model_pledge = M("Pledge");
        $model_user = M("User");
        $uid = $_GET['uid'];
        $list = $earnings->where(['uid'=>$uid])->page($_GET['page'], $_GET['limit'])->select();
        $user_info = $model_user->where(['id'=>$uid])->find();


        foreach ($list as $key=>&$val){
           $val['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
           $pledge = $model_pledge->where(['id'=>$val['target_id']])->find();
           $val['pledge_amount'] = $pledge['pledge_amount'];
           $val['p_uid'] = $pledge['uid'];

           if($val['type']==4){
               $val['type_desc']="动态收益";
           }else if($val['type']==5){
               $val['type_desc']="静态收益";
           }else if($val['type']==0){
               $val['type_desc']="本金返还";
           }

           $val['n_address'] = $model_user->where(['id'=>$pledge['uid']])->getField('address');
           $val['address'] = $user_info['address'];

           $val['state_desc'] = $pledge['state']==1?"未到期":"已到期";
           
           $val['end_time']  = date("Y-m-d H:i:s",$pledge['pledge_end_time']);


        }
        $data = [
            'code' => 0,
            'data' => $list,
            'count' => $earnings->where(['uid'=>$uid])->count(),
        ];
        $this->ajaxReturn($data);
    }

    public function three()
    {
        $this->assign('id', $_GET['id']);
        $this->display('three');
    }

    public function nextList(){
        $user_info = M('User')->where(['id' => $_GET['id']])->find();
        $uids = $this->getBottomUsers($_GET['id'], '');

        $uids .= $uids . ','.$_GET['id'];
        $user_total = M('User')->where(['id' => ['in', $uids]])->sum('recharge');

        $list = M('User')->where(['id' => ['in', $uids]])->select();
        $new_array=[];

        // 伞下总业绩
        foreach ($list as $key => &$val) {
            $new_array[]=[
                'authorityId'=>$val['id'],
                'authorityName'=>$val['address'],
                'orderNumber'=>$val['id'],
                'menuUrl'=>null,
                'menuIcon'=>"layui-icon-set",
                'createTime'=>date("Y/m/d H:i:s",time()),
                'authority'=>null,
                'checked'=> 0,
                'updateTime'=>date("Y/m/d H:i:s",time()),
                'isMenu'=> 0,
                'parentId'=>$val['id']==$_GET['id']?-1:$val['pid'],
            ];
        }

        $data = [
            'code' => 0,
            'msg' => "",
            'count'=>count($new_array),
            'data' => $new_array,
        ];
        $this->ajaxReturn($data);
    }

    /**
     * @param $uid
     * @param string $uids
     * @return mixed|string
     */
    public function getBottomUsers($uid, $uids = '')
    {
        $userList = M('User')->field('id,pid')->where(array('pid' => $uid))->select();

        foreach ($userList as $key => &$value) {
            $uids .= $value['id'] . ',';
            $user = M('User')->field('id,pid')->where(array('pid' => $value['id']))->select();
            if ($user) {
                $uids = $this->getBottomUsers($value['id'], $uids);
            }
        }
        return $uids;
    }

}
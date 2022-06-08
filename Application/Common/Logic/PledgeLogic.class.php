<?php
namespace Common\Logic;
use Think\Exception;

class PledgeLogic
{

    /**
     *每日执行质押
     */
    public function getNextTotal()
    {
        $pledge = M("Pledge");
        $model_circle = M("Circle");
        $user = M("User");
        $model_earnings = M('UserEarnings');

        // 获取返利比例
        $circle_info = $model_circle->where(['state' => 1])->find();

        // 第二天的开始时间
        $second_start_time = mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time()));
        $second_end_time = mktime(23, 59, 59, date("m", time()), date("d", time()), date("Y", time()));

        $list = $pledge->where(['pledge_end_time' => ['between',[$second_start_time,$second_end_time]]])->select();
        
        
        $total = 0;
        if ($list) {
            foreach ($list as $key => $val) {
                
                    // 获取返利百分比
                    $return_percent = self::_getReturnPercent($val['pledge_day'], $circle_info);

                    // 获取利息
                    $s_interests = $val['pledge_amount'] * $return_percent / 100;
                   
            
                    $d_interests = $model_earnings->where(['target_id'=>$val['id'],'type'=>4])->sum('amount')?:0;
                  
                  
                    $interests = $s_interests+$d_interests;
                      
                    // 返利并结束当前质押
                    $total += $interests;
                    $total += $val['pledge_amount'];
                }
            }
            
        
        
        return $total;
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
                if ($recharge >= 100) {
                    $is_check = true;
                }
                break;
            case 2:
                if ($recharge >= 200) {
                    $is_check = true;
                }
                break;
            case 3:
                if ($recharge >= 300) {
                    $is_check = true;
                }
                break;
            case 4:
                if ($recharge >= 400) {
                    $is_check = true;
                }
                break;
            case 5:
                if ($recharge >= 500) {
                    $is_check = true;
                }
                break;
            case 6:
                if ($recharge >= 600) {
                    $is_check = true;
                }
                break;
            case 7:
                if ($recharge >= 700) {
                    $is_check = true;
                }
                break;
            case 8:
                if ($recharge >= 800) {
                    $is_check = true;
                }
                break;
            case 9:
                if ($recharge >= 900) {
                    $is_check = true;
                }
                break;
            case 10:
                if ($recharge >= 1000) {
                    $is_check = true;
                }
                break;
            case 11:
                if ($recharge >= 1100) {
                    $is_check = true;
                }
                break;
            case 12:
                if ($recharge >= 1200) {
                    $is_check = true;
                }
                break;
            case 13:
                if ($recharge >= 1300) {
                    $is_check = true;
                }
                break;
            case 14:
                if ($recharge >= 1400) {
                    $is_check = true;
                }
                break;
            case 15:
                if ($recharge >= 1500) {
                    $is_check = true;
                }
                break;
            case 16:
                if ($recharge >= 1600) {
                    $is_check = true;
                }
                break;
            case 17:
                if ($recharge >= 1700) {
                    $is_check = true;
                }
                break;
            case 18:
                if ($recharge >= 1800) {
                    $is_check = true;
                }
                break;
            case 19:
                if ($recharge >= 1900) {
                    $is_check = true;
                }
                break;
            case 20:
                if ($recharge >= 2000) {
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

}
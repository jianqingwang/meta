<?php
namespace Common\Model;

class TradeModel extends \Think\Model
{
	protected $keyS = 'Trade';

	public function hangqing($market = NULL)
	{
		if (empty($market)) {
			return null;
		}

		$timearr = array(1, 3, 5, 10, 15, 30, 60, 120, 240, 360, 720, 1440, 10080);
		foreach ($timearr as $k => $v) {
			$tradeJson = M('TradeJson')->where(array('market' => $market, 'type' => $v))->order('id desc')->find();

			if ($tradeJson) {
				$addtime = $tradeJson['addtime'];
			} else {
				$addtime = M('TradeLog')->where(array('market' => $market))->order('id asc')->getField('addtime');
			}

			if ($addtime) {
				$youtradelog = M('TradeLog')->where('addtime >=' . $addtime . '  and market =\'' . $market . '\'')->sum('num');
			}

			if ($youtradelog) {
				if ($v == 1) {
					$start_time = $addtime;
				} else {
					$start_time = mktime(date('H', $addtime), floor(date('i', $addtime) / $v) * $v, 0, date('m', $addtime), date('d', $addtime), date('Y', $addtime));
				}

				$x = 0;

				for (; $x <= 20; $x++) {
					$na = $start_time + (60 * $v * $x);
					$nb = $start_time + (60 * $v * ($x + 1));

					if (time() < $na) {
						break;
					}

					$sum = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum) {
						$sta = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->max('price');
						$min = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->min('price');
						$end = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						$d = array($na, $sum, $sta, $max, $min, $end);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $na, 'type' => $v))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $na, 'type' => $v))->save(array('data' => json_encode($d)));
							M('TradeJson')->execute('commit');
						} else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d), 'addtime' => $na, 'type' => $v));
							M('TradeJson')->execute('commit');
							M('TradeJson')->where(array('market' => $market, 'data' => '', 'type' => $v))->delete();
							M('TradeJson')->execute('commit');
						}
					} else {
						M('TradeJson')->add(array('market' => $market, 'data' => '', 'addtime' => $na, 'type' => $v));
						M('TradeJson')->execute('commit');
					}
				}
			}
		}
	}

	public function chexiao($id = NULL){
		if (!check($id, 'd')) {
			return array('0', '????????????');
		}
		$trade = M('Trade')->where(array('id' => $id))->find();
		if (!$trade) {
			return array('0', '???????????????');
		}
		if ($trade['status'] != 0) {
			return array('0', '??????????????????');
		}
		$xnb = explode('_', $trade['market'])[0];
		$rmb = explode('_', $trade['market'])[1];
		if (!$xnb) {
			return array('0', '??????????????????');
		}
		if (!$rmb) {
			return array('0', '??????????????????');
		}
      	$userid=userid();
		$usercoin = M('UserCoin')->where(array('userid' => $trade['userid']))->find();
		if($trade['sta_bian']==1){
			$chexiao_num=$trade['num']-$trade['deal'];
			//????????????
			if($trade['num']>$trade['deal']){
				if ($trade['type'] == 1) {
					$money_turnover=intval($chexiao_num*$trade['price']*100000000)/100000000;
					$money_fee=intval($money_turnover*$trade['fee_bl']*100000000)/100000000;
					$money_zong=$money_turnover+$money_fee;
					try{
						$mo = M();
						$mo->startTrans();
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setInc($xnb, $money_zong);
                      	if($usercoin[$xnb.'d']>0){
                            if($usercoin[$xnb.'d']>$money_zong){
                                $rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setDec($xnb . 'd', $money_zong);
                            }else{
                                $rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setDec($xnb . 'd', 0);
                            }
                        }
						$rs[] = $mo->table('tw_trade')->where('id='.$trade['id'])->save(array('status' => 2, 'time_cx' => time()));
						if(check_arr($rs)) {
							$mo->table('tw_finance')->add(array('userid' => $userid,'type'=>1, 'coin' => $xnb, 'coinname' => strtoupper($xnb),'remark'=>'????????????','remark2'=>'???'.strtoupper($rmb),'num'=>$usercoin[$xnb],'fee'=>$money_turnover,'mum'=>($usercoin[$xnb]+$money_turnover), 'addtime' => time()));
							if($money_fee>0){
								$mo->table('tw_finance')->add(array('userid' => $userid,'type'=>1, 'coin' => $xnb, 'coinname' => strtoupper($xnb),'remark'=>'????????????[?????????]','remark2'=>'???'.strtoupper($rmb),'num'=>($usercoin[$xnb]+$num),'fee'=>$money_fee,'mum'=>($usercoin[$xnb]+$money_turnover+$money_fee), 'addtime' => time()));
							}
							//???????????????
							$orders = $api->cancel($trade['market2'], $trade['orderid']);
							if($order_status['symbol']==$bian_coin){
								$mo->commit();
								return array('1', '????????????');
							}else{
								$mo->rollback();
								$this->error(L('????????????'));
								throw new \Think\Exception(L('???????????????'));
							}
						} else {
							$mo->rollback();
							$this->error(L('????????????'));
							throw new \Think\Exception(L('?????????2??????'));
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error('????????????');
					}
				}else{
					$num_fee=intval($chexiao_num*$trade['fee_bl']*100000000)/100000000;
					$num_zong=$chexiao_num+$num_fee;
					try{
						$mo = M();
						$mo->startTrans();
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setInc($rmb, $num_zong);
                      	if($usercoin[$rmb.'d']>0){
                            if($usercoin[$rmb.'d']>$num_zong){
                                $rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setDec($rmb . 'd', $num_zong);
                            }else{
                                $rs[] = $mo->table('tw_user_coin')->where(array('userid' => $userid))->setDec($rmb . 'd', 0);
                            }
                        }
						$rs[] = $mo->table('tw_trade')->where('id='.$trade['id'])->save(array('status' => 2, 'time_cx' => time()));
						if(check_arr($rs)) {
							$mo->table('tw_finance')->add(array('userid' => $userid,'type'=>1, 'coin' => $rmb, 'coinname' => strtoupper($rmb),'remark'=>'????????????','remark2'=>'???'.strtoupper($rmb),'num'=>$usercoin[$rmb],'fee'=>$chexiao_num,'mum'=>($usercoin[$rmb]+$chexiao_num), 'addtime' => time()));
							if($num_fee>0){
								$mo->table('tw_finance')->add(array('userid' => $userid,'type'=>1, 'coin' => $rmb, 'coinname' => strtoupper($rmb),'remark'=>'????????????[?????????]','remark2'=>'???'.strtoupper($rmb),'num'=>($usercoin[$rmb]+$num),'fee'=>$num_fee,'mum'=>($usercoin[$rmb]+$chexiao_num+$num_fee), 'addtime' => time()));
							}
							//???????????????
                            $con_fig=M('Config')->field('api_key,api_secret')->where('id=1')->find();
                            $apikey = $con_fig['api_key'];
                            $apisecret = $con_fig['api_secret'];
                            require_once './vendor/autoload.php';
                            $api = new \Binance\API($apikey,$apisecret);
							$orders = $api->cancel($trade['market2'], $trade['orderid']);
							if($orders['symbol']==$trade['market2']){
								$mo->commit();
								return array('1', '????????????');
							}else{
								$mo->rollback();
								$this->error(L('????????????'));
								throw new \Think\Exception(L('????????????'));
							}
						} else {
							$mo->rollback();
							$this->error(L('????????????'));
							throw new \Think\Exception(L('?????????2??????'));
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error('????????????');
					}
				}
			}else{
				$chexiao_ok=M('Trade')->where('id='.$trade['id'])->save(array('status'=>2,'time_cx'=>time()));
				if($chexiao_ok){
					return array('1', '????????????');
				}
				return array('0', '????????????');
			}
		}else{
			$fee_buy = C('market')[$trade['market']]['fee_buy'];
			$fee_sell = C('market')[$trade['market']]['fee_sell'];
			if ($fee_buy < 0) {
				return array('0', '?????????????????????');
			}
			if ($fee_sell < 0) {
				return array('0', '?????????????????????');
			}
			try{
				$user_coin = M('UserCoin')->where(array('userid' => $trade['userid']))->find();
				$mo = M();
				$mo->execute('set autocommit=0');
				// $mo->execute('lock tables tw_user_coin write  , tw_trade write ,tw_finance write');
				$mo->execute('lock tables tw_user_coin write  , tw_trade write ,tw_finance write,tw_finance_log write,tw_user write,tw_auth_group_access write,tw_admin write');//????????????????????????

				$rs = array();
				$user_coin = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();

				if ($trade['type'] == 1) {
					$user_buy = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
					$buyuser = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
					if($buyuser['lv']==1){
						$fee_buy=0;
					}
					
					$mun = round(((($trade['num'] - $trade['deal']) * $trade['price']) / 100) * (100 + $fee_buy), 8);
					if ($mun <= round($user_buy[$rmb . 'd'], 8)) {
						$save_buy_rmb = $mun;
					} else if ($mun <= round($user_buy[$rmb . 'd'], 8) + 1) {
						$save_buy_rmb = $user_buy[$rmb . 'd'];
					} else {
						throw new \Think\Exception('????????????1');
					}

					$finance = $mo->table('tw_finance')->where(array('userid' => $trade['userid']))->order('id desc')->find();
					$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setInc($rmb, $save_buy_rmb);
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setDec($rmb . 'd', $save_buy_rmb);
					$finance_nameid = $trade['id'];

					$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();

					
					// ????????????????????????--------????????????---------S
					
					$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
					if (session('userId') > 0) {
						$position = 1;
						// ??????????????????
						$user_info = $mo->table('tw_user')->where(array('id' => session('userId')))->find();

						$uu_name = $user_2_info['username'];
						$aa_name = $user_info['username'];
						$uu_id = $trade['userid'];
						$aa_id = session('userId');
					} elseif (session('admin_id') > 0) {
						$position = 0;
						$uu_name = $user_2_info['username'];
						$aa_name = session('admin_username');
						$uu_id = $trade['userid'];
						$aa_id = session('admin_id');
					} else {
						$admin_group = $mo->table('tw_auth_group_access')->where(array('group_id' => '3'))->find();
						$admin_info = $mo->table('tw_admin')->where(array('id' => $admin_group['uid']))->find();
						$position = 0;
						$uu_name = $user_2_info['username'];
						$aa_name = $admin_info['username'];
						$uu_id = $trade['userid'];
						$aa_id = $admin_info['id'];
					}

					// optype 10 ??????-???????????? 'cointype' => ???????????? 'plusminus' => 1????????????
					$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => $aa_name, 'addtime' => time(), 'plusminus' => 1, 'amount' => $save_buy_rmb, 'optype' => 16, 'cointype' => 1, 'old_amount' => $finance_num_user_coin[$rmb], 'new_amount' => $finance_mum_user_coin[$rmb], 'userid' => $uu_id, 'adminid' => $aa_id,'addip'=>get_client_ip(),'position'=>$position));
					
					// ????????????????????????---------????????????--------E


					$finance_hash = md5($trade['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb . 'd'] . $save_buy_rmb . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb . 'd'] . MSCODE . 'tp3.net.cn');
					$finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'];

					if ($finance['mum'] < $finance_num) {
						$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
					} else {
						$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
					}

					$rs[] = $mo->table('tw_finance')->add(array('userid' => $trade['userid'], 'coinname' => $rmb, 'num_a' => $finance_num_user_coin[$rmb], 'num_b' => $finance_num_user_coin[$rmb . 'd'], 'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'], 'fee' => $save_buy_rmb, 'type' => 1, 'name' => 'trade', 'nameid' => $finance_nameid, 'remark' => '????????????-????????????' . $trade['market'], 'mum_a' => $finance_mum_user_coin[$rmb], 'mum_b' => $finance_mum_user_coin[$rmb . 'd'], 'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb . 'd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));
					$rs[] = $mo->table('tw_trade')->where(array('id' => $trade['id']))->setField('status', 2);
					$you_buy = $mo->table('tw_trade')->where(array(
						'market' => array('eq', $trade['market']),
						'status' => 0,
						'userid' => $trade['userid']
						))->find();

					if (!$you_buy) {
						$you_user_buy = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();

						if (0 < $you_user_buy[$rmb . 'd']) {
							$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setField($rmb . 'd', 0);

							// ????????????????????????-----------------S
							$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
							if (session('userId') > 0) {
								$position = 1;
								$uu_name = $user_2_info['username'];
								$uu_id = $trade['userid'];
							} else {
								$position = 0;
								$uu_name = $user_2_info['username'];
								$uu_id = $trade['userid'];
							}

							// optype???????????? 'cointype' => ???????????? 'plusminus' => 1????????????
							$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => '??????', 'addtime' => time(), 'plusminus' => 0, 'amount' => $you_user_buy[$rmb . 'd'], 'optype' => 17, 'cointype' => 1, 'old_amount' => $you_user_buy[$rmb . 'd'], 'new_amount' => '0', 'userid' => $uu_id,'addip'=>get_client_ip(),'position'=>$position));

							// ????????????????????????-----------------E
						}
					}
				} else if ($trade['type'] == 2) {
					$mun = round($trade['num'] - $trade['deal'], 8);
					$user_sell = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
					if ($mun <= round($user_sell[$xnb . 'd'], 8)) {
						$save_sell_xnb = $mun;
					} else if ($mun <= round($user_sell[$xnb . 'd'], 8) + 1) {
						$save_sell_xnb = $user_sell[$xnb . 'd'];
					} else {
						throw new \Think\Exception('????????????2');
					}

					if (0 < $save_sell_xnb) {
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setInc($xnb, $save_sell_xnb);
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setDec($xnb . 'd', $save_sell_xnb);

						$user_sell_f = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();

						// ????????????????????????-----------------S

						switch ($xnb) {
							case 'hyjf':
								$cointype = 2;
								break;

							default:
								$cointype = 3;
								break;
						}

						$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
						if (session('userId') > 0) {
							$position = 1;
							// ??????????????????
							$user_info = $mo->table('tw_user')->where(array('id' => session('userId')))->find();

							$uu_name = $user_2_info['username'];
							$aa_name = $user_info['username'];
							$uu_id = $trade['userid'];
							$aa_id = session('userId');
						} elseif(session('admin_id') > 0) {
							$position = 0;
							$uu_name = $user_2_info['username'];
							$aa_name = session('admin_username');
							$uu_id = $trade['userid'];
							$aa_id = session('admin_id');
						} else {
							$admin_group = $mo->table('tw_auth_group_access')->where(array('group_id' => '3'))->find();
							$admin_info = $mo->table('tw_admin')->where(array('id' => $admin_group['uid']))->find();
							$position = 0;
							$uu_name = $user_2_info['username'];
							$aa_name = $admin_info['username'];
							$uu_id = $trade['userid'];
							$aa_id = $admin_info['id'];
						}

						// optype???????????? 'cointype' => ???????????? 'plusminus' => 1????????????
						$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => $aa_name, 'addtime' => time(), 'plusminus' => 1, 'amount' => $save_sell_xnb, 'optype' => 17, 'cointype' => $cointype, 'old_amount' => $user_sell[$xnb], 'new_amount' => $user_sell_f[$xnb], 'userid' => $uu_id, 'adminid' => $aa_id,'addip'=>get_client_ip(),'position'=>$position));

						// ????????????????????????-----------------E
					}

					$rs[] = $mo->table('tw_trade')->where(array('id' => $trade['id']))->setField('status', 2);
					$you_sell = $mo->table('tw_trade')->where(array(
						'market' => array('eq', $trade['market']),
						'status' => 0,
						'userid' => $trade['userid']
						))->find();

					if (!$you_sell) {
						$you_user_sell = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();

						if (0 < $you_user_sell[$xnb . 'd']) {
							$mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setField($xnb . 'd', 0);

							// ????????????????????????-----------------S

							switch ($xnb) {
								case 'hyjf':
									$cointype = 2;
									break;

								default:
									$cointype = 3;
									break;
							}

							$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
							if (session('userId') > 0) {
								$position = 1;
								$uu_name = $user_2_info['username'];
								$uu_id = $trade['userid'];
							} else {
								$position = 0;
								$uu_name = $user_2_info['username'];
								$uu_id = $trade['userid'];
							}

							// optype???????????? 'cointype' => ???????????? 'plusminus' => 1????????????
							$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => '??????', 'addtime' => time(), 'plusminus' => 0, 'amount' => $you_user_sell[$xnb . 'd'], 'optype' => 17, 'cointype' => $cointype, 'old_amount' => $you_user_sell[$xnb . 'd'], 'new_amount' => '0', 'userid' => $uu_id,'addip'=>get_client_ip(),'position'=>$position));

							// ????????????????????????-----------------E
						}
					}
				} else {
					throw new \Think\Exception('????????????3');
				}
			} catch(\Think\Exception $e) {
				if ($e == '????????????3') {
					$mo->execute('rollback');
					$mo->execute('unlock tables');
					return array('0', '????????????3');
				} else {
					$mo->execute('rollback');
					$mo->execute('unlock tables');
					// M('Trade')->where(array('id' => $id))->setField('status', 2);
					$mo->execute('commit');
					return array('0', '????????????');
				}
			}

			if (check_arr($rs)) {
				$mo->execute('commit');
				$mo->execute('unlock tables');
				S('getDepth', null);
				return array('1', '????????????');
			} else {
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				return array('0', '????????????4|' . implode('|', $rs));
			}
		}
	}
}
?>
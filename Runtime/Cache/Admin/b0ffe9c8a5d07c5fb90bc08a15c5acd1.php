<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>后台 | 管理中心</title>
	<!-- Loading Bootstrap -->
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/default_color.css" media="all">
	<script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/layer/layer.js"></script>
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/flat-ui.css">
	<script src="/Public/Admin/js/flat-ui.min.js"></script>
	<script src="/Public/Admin/js/application.js"></script>
	<style>
		.glyphicon-home:before{content: initial;}
		.navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus {color: #fff;background-color: #1BB495;}
		.navbar-nav > .active > a {font-size: 16px;padding: 6px 25px;line-height: 35px;margin-top: 10px;margin-left: 11px;border-radius: 3px;}
		.navbar-brand{padding-left: 15px;}
		.navbar-inverse {float: left;width: 100%;}
		.to-qingc{background: #1bb495;color: #fff;padding: 4px 10px;border-radius: 3px;font-size: 13px;	margin-top: -3px;cursor: pointer;}
		.to-pisd{background: #f74242;color: #fff;padding: 4px 10px;border-radius: 3px;font-size: 13px;	margin-top: -3px;cursor: pointer;}
		.i-54 {background: #F48E2A;width: 31px;height: 31px;color: #fff;border-radius: 50%;display: inline-block;float: left;margin-right: 10px;margin-top: -2px;font-style: normal;text-align: center;line-height: 26px;font-size: 18px;}
		.navbar-rights {float: right;margin-top: 18px;}
		.navbar-collapse {padding-left: 4px;}
		.open .dropdown-toggle, .open:hover .dropdown-toggle{border-color:0!important;background-color:0!important; box-shadow:none; }
	</style>
</head>
<body>
<div class="navbar navbar-inverse" role="navigation">
	<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<!-- 主导航 -->
			<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li <?php if(($menu["class"]) == "current"): ?>class="active"<?php endif; ?> > 
					<a href="<?php echo (U($menu["url"])); ?>">
						<?php if(empty($menu["ico_name"])): ?><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
						<?php else: ?>
							<span class="glyphicon glyphicon-<?php echo ($menu["ico_name"]); ?>" aria-hidden="true"></span><?php endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='首页'){ ?><img style="width:20px;" src="/Public/Admin/images/sysy.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='首页'){ ?><img style="width:20px;" src="/Public/Admin/images/sysy2.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='财务'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/cw.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='财务'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/cw1.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='交易'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/trade.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='交易'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/trade1.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='矿机'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/kji.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='矿机'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/kji1.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='LVLC'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/lvlc_1.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='LVLC'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/lvlc_2.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) == "current"): if($menu['title']=='虚拟币'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/xnb1.png"/> <?php } endif; ?>
						<?php if(($menu["class"]) != "current"): if($menu['title']=='虚拟币'){ ?><img style="width:20px;margin-top: -4px;" src="/Public/Admin/menu/xnb.png"/> <?php } endif; ?>
						<?php echo ($menu["title"]); ?> 
					</a>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<ul class="nav navbar-nav navbar-rights" style="margin-right:10px;">
			<li class="dropdown">
				<p href="#" class="dropdown-toggle" data-toggle="dropdown">
				  <i class="i-54"><?php echo substr(session('admin_username'),0,1) ?></i>	 <?php echo session('admin_username');?>
				</p>
			</li>
			<li class="center">
				<p class="to-qingc" onclick="qingchu()" >
				    清除缓存
				</p>
			</li>
			<li>
				<p class="to-pisd" class="dropdown-toggle" onclick="tuichu()" >
			    	安全退出
				</p>
			</li>
		</ul>
	</div>
</div>
<!-- 边栏 -->
<div class="sidebar">
	<div style="color: #fff; font-size: 20px;margin-bottom: 19px;text-align: center;">
			<?php echo ($ht_web_title); ?>
	</div>
	<!-- 子导航 -->
	
		<div id="subnav" class="subnav" style="min-height: 100%;overflow-x: hidden;overflow-y: auto;">
			<?php if(!empty($_extra_menu)): ?> <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
			<?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
				<?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold <?php if($key=='币安K线图'){ echo 'k-xian';}else if($key=='执行文件'){ echo 'zhi-xing';}else if($key=='用户'){ echo 'yonghu';}else if($key=='管理员管理'){ echo 'guanliyuan';}else if($key=='图谱'){ echo 'tupu';} ?>"></i><?php echo ($key); ?></h3><?php endif; ?>
					<ul class="side-sub-menu">
						<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
								<a class="item" href="<?php echo (U($menu["url"])); ?>">
								<!--	<?php if(empty($menu["ico_name"])): ?>1
										<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
										<?php else: ?>
										<span class="glyphicon glyphicon-<?php echo ($menu["ico_name"]); ?>" aria-hidden="true"></span><?php endif; ?>-->
									<?php if($menu['title']=='后台首页'){ if($sy_class=='index'){ ?>
										<?php }else{ ?>
										<?php
 }} ?>
									<?php if($menu['title']=='币种统计'){ if($sy_class=='bizhong'){ ?>
										<?php }else{ ?>
										<?php
 }} ?>
									<?php if($menu['title']=='市场统计'){ if($sy_class=='shichang'){ ?>
										<img style="width:15px;" src="/Public/Admin/images/shic.png"/>
										<?php }else{ ?>
										<img style="width:15px;" src="/Public/Admin/images/shic.png"/>
										<?php
 }} ?>
									<?php echo ($menu["title"]); ?>
								</a>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul><?php endif; ?>
				<!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	
	<!-- /子导航 -->
</div>
<!-- /边栏 -->
<script>
	
		function qingchu(){
			window.location.href = "<?php echo U('Tools/delcache');?>";
		}
		function tuichu(){
			window.location.href = "<?php echo U('AdmiRniooTstration/loginout');?>";
		}
</script>
<?php if(($versionUp) == "1"): ?><script type="text/javascript" charset="utf-8">
		/**顶部警告栏*/
		var top_alert = $('#top-alerta');
		top_alert.find('.close').on('click', function () {
			top_alert.removeClass('block').slideUp(200);
			// content.animate({paddingTop:'-=55'},200);
		});
	</script><?php endif; ?>
<style>
	.hoh td.item-label,.hoh td.item-note{height:80px;line-height:80px;}
	.gezibg {padding:5px;width:168px;background:url('/Public/Admin/ecshe_img/imgbg.png');}
	p {font-size: 14px;line-height: 1.72222;margin: 0 0 2px;}
	.form-horizontal .controls {overflow: hidden;padding: 5px 5px 5px 0;height: 68px;display: flex;float: left;}
	.input-10x {width: 115px;margin-top: -5px;margin-left: 9px;}
	
	.main{overflow: auto;min-height: 740px;}
	.btn-success {color: #ffffff;background-color: #5688CD;}
	.main-title-h {margin-bottom: 12px;`padding-bottom: 0;line-height: 65px;height: 66px;border-bottom: 1px solid #f1f0f0;padding-bottom: 36px;}
	.main-title-h:after{display:block;clear:both;content:"";visibility:hidden;height:0;}
	.table-striped tbody .zhengcc{color:#22C6AB;}
	.btn-xs, .btn-group-xs > .btn {padding: 0px 0px; font-size: 12px; line-height: 1.083; border-radius: 3px; background:none;}
	.main-title-h {margin-bottom: 20px;padding-bottom: 0;line-height: 65px;height: 65px;}
	.main {padding-top: 0px;}
</style>
<div id="main-content">
	<div id="top-alert" class="fixed alert alert-error" style="display: none;">
		<button class="close fixed" style="margin-top: 4px;">&times;</button>
		<div class="alert-content">警告内容</div>
	</div>
	<div id="main" class="main">
		<div class="main-title-h"><span class="h1-title">资产充值</span></div>
		<div class="tab-wrap">
			<div class="tab-content">
				<form id="form" action="<?php echo U('User/chongzhi_ajax');?>" method="post" class="form-horizontal" enctype="multipart/form-data">
					<div id="tab" class="tab-pane in tab" style="height:55px;border-bottom: 1px solid #f1f0f0;">
						<div class="form-item cf">
							<div class="controls">
								<p>充值用户ID :</p>
								<div style="width: 128px;"><input type="text" class="form-control input-10x" name="id" value="" style="width: 120px;"></div>
								<div class="item-note"></div>
								<p>充值手机号 :</p>
								<div><input type="text" class="form-control input-10x" name="zhanghu" maxlength="50" value="" style="    width: 120px;"></div>
							</div>
							<div class="controls">
								<p>充值类型 :</p>
								<p>
									<select style="width:160px;" name="cz_type" class="form-control input-10x">
										<option value="jyzhi" >经验值</option>
										<?php $_result=C('coin');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['name']!='cny'){ ?>
											<option value="<?php echo ($v['name']); ?>" ><?php echo strtoupper($v['name']); ?></option>
											<option value="<?php echo ($v['name']); ?>d" >冻结<?php echo strtoupper($v['name']); ?></option>
                                                <?php } endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </p>
                                <div class="item-note"></div>
                            </div>
                            <div class="controls">
                            	<p>操作类型:</p>
                            	<div>
                            		增加 <input name="type" value="1" title="增加" <?php if(@$_GET['type']!=2){ echo 'checked'; } ?> type="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            		减少 <input name="type" value="2" <?php if(@$_GET['type']==2){ echo 'checked'; } ?> title="减少" type="radio">
                            	</div>
                            	<div class="item-note"></div>
                            </div>
                            <div class="controls">
                            	<td class="item-label">数量额度:</td>
                            	<td><input type="text" class="form-control input-10x" name="money" value=""></td>
                            	<td class="item-note"></td>
                            </div>
                            <div class="controls" style="padding-top:0;">
                            	<div class="form-item cf" style="margin-top:0;">
                            		<button class="btn submit-btn ajax-post" target-form="form-horizontal" id="submit" type="submit">提交</button>
                            	</div>
                            </div>
						</div>
					</div>
				</form>
				<script type="text/javascript">
					//提交表单
					$('#submit').click(function () {
						$('#form').submit();
					});
				</script>
			</div>
		</div>
		<div style="margin-top:-20px;">
			<div class="cf">
				<div class="search-form fr cf" style="padding-top: 0px;">
					<div class="sleft">
						<form name="formSearch" id="formSearch" method="get" name="form1">
	                        <script type="text/javascript" src="/Public/layer/laydate/laydate.js"></script>
	                        <input type="text" class="form-control" style=" width: 170px; float: left; margin-right: 10px;" name="starttime" value="<?php echo I('get.starttime');?>" placeholder="开始日期" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
	                        <input type="text" class="form-control" style=" width: 170px; float: left; margin-right: 10px;" name="endtime" value="<?php echo I('get.endtime');?>" placeholder="结束日期" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
							<select style=" width: 100px; float: left; margin-right: 10px;" name="field" class="form-control">
								<option value="userid" <?php if(($_GET['field']) == "userid"): ?>selected<?php endif; ?> >会员ID</option>
								<option value="mobile" <?php if(($_GET['field']) == "mobile"): ?>selected<?php endif; ?> >手机号</option>
							</select>
							<input type="text" name="name" class="search-input form-control  " value="<?php echo ($_GET['name']); ?>" placeholder="请输入查询内容" style="">
							<a class="sch-btn" href="javascript:;" id="search"> <i class="btn-search"></i> </a>
						</form>
						<script>
							//搜索功能
							$(function () {
								$('#search').click(function () {
									$('#formSearch').submit();
								});
							});
							//回车搜索
							$(".search-input").keyup(function (e) {
								if (e.keyCode === 13) {
									$("#search").click();
									return false;
								}
							});
						</script>
					</div>
				</div>
			</div>
		</div>
		<div class="data-table table-striped">
			<table class="">
				<thead>
				<tr>
					<th class="">会员ID</th>
					<th class="">手机号</th>
					<th class="">充值类型</th>
					<th class="">操作类型</th>
					<th class="">操作前</th>
					<th class="">操作数量</th>
					<th class="">操作后</th>
					<th class="">时间</th>
				</tr>
				</thead>
				<tbody>
				<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["userid"]); ?></td>
							<td><?php echo ($vo['mobile']); ?></td>
							<td><?php echo ($vo['coinname']); ?></td>
							<td><?php if($vo['type']==1){ echo '增加';}else{ echo '减少';} ?></td>
							<td><?php echo ($vo['num']*1); ?></td>
							<td><?php echo ($vo['fee']*1); ?></td>
							<td><?php echo ($vo['mum']*1); ?></td>
							<td><?php echo (addtime($vo["addtime"])); ?></td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php else: ?>
					<td colspan="12" class="text-center empty-info"><i class="glyphicon glyphicon-exclamation-sign"></i>暂无数据</td><?php endif; ?>
				</tbody>
			</table>
			<div class="page">
				<div><?php echo ($page); ?></div>
			</div>
          	<br />
		</div>
	</div>
</div>
<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
<script type="text/javascript">
	+function(){
		//$("select").select2({dropdownCssClass: 'dropdown-inverse'});//下拉条样式
		//layer.config({
		//	extend: 'extend/layer.ext.js'
		//});

		var $window = $(window), $subnav = $("#subnav"), url;
		$window.resize(function(){
			//$("#main").css("min-height", $window.height() - 90);
		}).resize();

		/* 左边菜单高亮 */
		url = window.location.pathname + window.location.search;

		url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
		$subnav.find("a[href='" + url + "']").parent().addClass("current");

		/* 左边菜单显示收起 */
		$("#subnav").on("click", "h3", function(){
			var $this = $(this);
			$this.find(".icon").toggleClass("icon-fold");
			$this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
			prev("h3").find("i").addClass("icon-fold").end().end().hide();
		});

		$("#subnav h3 a").click(function(e){e.stopPropagation()});

		/* 头部管理员菜单 */
		$(".user-bar").mouseenter(function(){
			var userMenu = $(this).children(".user-menu ");
			userMenu.removeClass("hidden");
			clearTimeout(userMenu.data("timeout"));
		}).mouseleave(function(){
			var userMenu = $(this).children(".user-menu");
			userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
			userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
		});

		/* 表单获取焦点变色 */
		$("form").on("focus", "input", function(){
			$(this).addClass('focus');
		}).on("blur","input",function(){
			$(this).removeClass('focus');
		});
		$("form").on("focus", "textarea", function(){
			$(this).closest('label').addClass('focus');
		}).on("blur","textarea",function(){
			$(this).closest('label').removeClass('focus');
		});

		// 导航栏超出窗口高度后的模拟滚动条
		var sHeight = $(".sidebar").height();
		var subHeight  = $(".subnav").height();
		var diff = subHeight - sHeight; //250
		var sub = $(".subnav");
		if(diff > 0){
//			$(window).mousewheel(function(event, delta){
//				if(delta>0){
//					if(parseInt(sub.css('marginTop'))>-10){
//						sub.css('marginTop','0px');
//					}else{
//						sub.css('marginTop','+='+10);
//					}
//				}else{
//					if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
//						sub.css('marginTop','-'+(diff-10));
//					}else{
//						sub.css('marginTop','-='+10);
//					}
//				}
//			});
		}
	}();

	//导航高亮
	function highlight_subnav(url){
		$('.side-sub-menu').find('a[href="'+url+'"]').closest('li').addClass('current');
	}

	function lockscreen(){
		layer.prompt({
			title: '输入一个锁屏密码',
			formType: 1,
			btn: ['锁屏','取消'] //按钮
		}, function(pass){
			if(!pass){
				layer.msg('需要输入一个密码!');
			}else{
				$.post("<?php echo U('Login/lockScreen');?>",{pass:pass},function(data){
					layer.msg(data.info);
					layer.close();
					if(data.status){
						window.location.href = "<?php echo U('Login/lockScreen');?>";
					}
				},'json');
			}
		});
	}
</script>
<div style="display:none;">

</div>
</body>
</html>
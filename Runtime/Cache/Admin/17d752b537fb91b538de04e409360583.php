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
<div id="main-content" style="width:100%;">
	<div id="top-alert" class="fixed alert alert-error" style="display: none;">
		<button class="close fixed" style="margin-top: 4px;">&times;</button>
		<div class="alert-content">警告内容</div>
	</div>
	<style>
		.main{overflow: auto;min-height: 740px;}
		.btn-success {color: #ffffff;background-color: #5688CD;}
		.main-title-h {margin-bottom: 12px;`padding-bottom: 0;line-height: 65px;height: 66px;border-bottom: 1px solid #f1f0f0;padding-bottom: 36px;}
		.main-title-h:after{display:block;clear:both;content:"";visibility:hidden;height:0;}
		.table-striped tbody .zhengcc{color:#22C6AB;}
		.btn-xs, .btn-group-xs > .btn {padding: 0px 0px; font-size: 12px; line-height: 1.083; border-radius: 3px; background:none;}
		.main-title-h {margin-bottom: 20px;padding-bottom: 0;line-height: 65px;height: 65px;}
		.main {padding-top: 0px;}
	</style>
	<div id="main" class="main">
		<div class="main-title-h">
			<span class="h1-title">会员管理</span>
			<div style="float:right;">
				<button class="ajax-post btn  btn-info " url="<?php echo U('User/status',array('type'=>'resume'));?>" target-form="ids">开启登录</button>
				<button style="background: #1BB495;" class="btn btn-success" url="<?php echo U('Finance/userExcel');?>" target-form="ids" id="submit" type="submit">导出选中</button>
			</div>
		</div>
		<div class="cf">
			<div class="search-form fr cf">
				<div class="sleft">
					<form name="formSearch" id="formSearch" method="get" name="form1">
                        <!-- 时间筛选 -->
						<script type="text/javascript" src="/Public/layer/laydate/laydate.js"></script>
						<input type="text" class="form-control" style=" width: 170px; float: left; margin-right: 10px;" name="starttime" value="<?php echo I('get.starttime');?>" placeholder="开始日期" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
						<input type="text" class="form-control" style=" width: 170px; float: left; margin-right: 10px;" name="endtime" value="<?php echo I('get.endtime');?>" placeholder="结束日期" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
						<select style="width: 150px;float:left;margin-right:10px;  border-radius: 3px;" name="field" class="form-control">
							<option value="id" <?php if(empty($_GET['field'])): ?>selected<?php endif; ?> >用户ID</option>
							<option value="mobile" <?php if(($_GET['field']) == "mobile"): ?>selected<?php endif; ?> >手机号</option>
							<option value="zt_mobile" <?php if(($_GET['field']) == "zt_mobile"): ?>selected<?php endif; ?> >推荐人手机号</option>
							<option value="r_nums" <?php if(($_GET['field']) == "r_nums"): ?>selected<?php endif; ?> >直推人数</option>
							<option value="xq_nums" <?php if(($_GET['field']) == "xq_nums"): ?>selected<?php endif; ?> >团队人数</option>
							<?php if(is_array($coincoin)): $i = 0; $__LIST__ = $coincoin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo2['name']); ?>" <?php if(($_GET['field']) == $vo2['name']): ?>selected<?php endif; ?>> <?php echo ($vo2['js_yw']); ?> 地址</option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						<script type="text/javascript" src="/Public/layer/laydate/laydate.js"></script>
						<input type="text" name="name" style=" border-radius: 3px; width:400px;" class="search-input form-control" value="<?php echo ($_GET['name']); ?>" placeholder="请输入查询内容" style="">
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
		<div class="data-table table-striped">
			<form id="form" action="<?php echo U('User/user2Excel');?>" method="post" class="form-horizontal">
				<table class="" >
					<thead>
					<tr>
						<th class="row-selected row-selected">
							<input class="check-all" type="checkbox"/>
						</th>
						<th style="width: 5px">用户ID</th>
						<th class="">手机号</th>
						<th class="">实名认证</th>
						<th class="">个人等级</th>
						<th class="">团队等级</th>
						<th class="">推荐人</th>
						<th class="">邀请码</th>
						<th class="" style="width:114px;"><span style="display: inline-block;">直推人数</span>
					     	<span style="float:right;">
							<a style="display:block;height: 9px;line-height: 9px;margin-top: 2px;"  href="<?php echo U('User/index_wjh?type=r_nums&px=asc');?>"><?php if($type=='r_nums' && $px=='asc'){ ?> <img title="升序" src="/Public/Admin/menu/shang2.png" alt="" style="width:1rem;"/> <?php }else{ ?> <img title="升序" src="/Public/Admin/menu/shang1.png" alt="" style="width:1rem;"/><?php } ?></a>
							<a  style="display:block;height: 9px;line-height: 9px;" href="<?php echo U('User/index_wjh?type=r_nums&px=desc');?>"><?php if($type=='r_nums' && $px=='desc'){ ?> <img title="降序" src="/Public/Admin/menu/xia2.png" alt="" style="width:1rem;"/> <?php }else{ ?> <img title="降序" src="/Public/Admin/menu/xia1.png" alt="" style="width:1rem;"/><?php } ?></a>
							</span>
						</th>
						<th class="" style="width:114px;"><span style="display: inline-block;">团队人数</span>
							<span style="float:right;">
							<a style="display:block;height: 9px;line-height: 9px;margin-top: 2px;"  href="<?php echo U('User/index_wjh?type=xq_nums&px=asc');?>"><?php if($type=='xq_nums' && $px=='asc'){ ?> <img title="升序" src="/Public/Admin/menu/shang2.png" alt="" style="width:1rem;"/> <?php }else{ ?> <img title="升序" src="/Public/Admin/menu/shang1.png" alt="" style="width:1rem;"/><?php } ?></a>
							<a  style="display:block;height: 9px;line-height: 9px;" href="<?php echo U('User/index_wjh?type=xq_nums&px=desc');?>"><?php if($type=='xq_nums' && $px=='desc'){ ?> <img title="降序" src="/Public/Admin/menu/xia2.png" alt="" style="width:1rem;"/> <?php }else{ ?> <img title="降序" src="/Public/Admin/menu/xia1.png" alt="" style="width:1rem;"/><?php } ?></a>
								</span>
						</th>
						<th class="">经验值</th>
						<th class="">大神矿力</th>
						<th class="">直推矿力</th>
						<th class="">团队矿力</th>
						<th class="">注册时间</th>
						<th class="">操作管理</th>
					</tr>
					</thead>
					<tbody>
					<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"/></td>
								<td><?php echo ($vo["id"]); ?></td>
								<td><?php echo ($vo["mobile"]); ?></td>
								<td><?php if($vo['is_detele']==2){ echo '已认证'; }else if($vo['is_detele']==1){ echo '未审核'; }else if($vo['is_detele']==8){ echo '未通过'; }else{ echo '未认证';} ?></td>
								<td><?php echo ($vo["level_user"]); ?></td>
								<td><?php echo ($vo["level_tuandui"]); ?></td>
                              	<td ><?php echo ($vo["zt_username"]); ?></td>
                              	<td><span class="bg-1"><?php echo ($vo["invit"]); ?></span></td>
                              	<td><span class="bg-2"><?php echo ($vo["r_nums"]); ?></span></td>
                              	<td><span class="bg-3"><?php echo ($vo["xq_nums"]); ?></span></td>
                              	<td><span class="bg-1"><?php echo ($vo['jyzhi']*1); ?></span></td>
                              	<td><span class="bg-2"><?php echo ($vo['ww_yx']*1); ?></span></td>
                              	<td><span class="bg-2"><?php echo ($vo['ww_zt']*1); ?></span></td>
                              	<td><span class="bg-2"><?php echo ($vo['ww_td']*1); ?></span></td>
								<td><?php echo (addtime($vo["addtime"])); ?></td>
								<td>
									<a href="<?php echo U('User/edit2?id='.$vo['id']);?>" class="btn-xs" >
										<img style="width: 22px;margin-bottom: 3px;margin-right: 4px;" title="编辑" src="/Public/Admin/images/c-ic3.png"/>
									</a>
                                </td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<?php else: ?>
						<td colspan="12" class="text-center empty-info"><i class="glyphicon glyphicon-exclamation-sign"></i>暂无数据</td><?php endif; ?>
					</tbody>
				</table>
			</form>
			<div class="page"><div style="float:left;"><?php echo ($page); ?></div><br /></div>
	</div>
</div>
<style type="text/css">
	.user_tan{position: fixed;top: 0;background: rgba(0,0,0,0.5);width: 100%;height: 100%;left: 0;z-index: 999;}
    .user_kuang{background: #fff;width: 550px;margin: 300px auto;position: relative;}
    .layui-layer-title{color: #fff!important;background-color: #4b89fc!important;}
    .qx{position: absolute;top: 6px;right: 20px;background: none;border: none;color: #fff;font-size: 20px;}
    .user_kuang_div{padding:20px;}
    .user_kuang .layui-input{width:263px;margin-bottom: 8px;height:47px;}
    .prompt_box {position: fixed;width: 100%;text-align: center;display: none;font-size: 13px;top: 46%;left: 0%;z-index: 9999;}
    .prompt_box span{background-color: rgba(0, 0, 0, .6);color: #FFFFFF;padding: 6px 12px;border-radius: 3px;}
    .rec_fu{font-size: 13px;float: right;}
</style>
<div class="user_tan"  style="display:none;">
								<div class="user_kuang">
		                          <div class="layui-layer-title">钱包地址</div>
		                          <div class="address_mas">
		                          		<div class="user_kuang_div">LVLVC地址：<span id="lvlc"></span> <span class="rec_fu btn btn-info lvlc2" data-clipboard-text="">复制</span></div>
		                          		<div class="user_kuang_div">USDT地址：<span id="usdt"></span> <span class="rec_fu btn btn-info usdt2" data-clipboard-text="">复制</span></div>
		                          </div>
									<button type="button" class="qx">x</button>
								</div>
							</div>
<div class="prompt_box">
	<span>复制成功</span>
</div>							
	<script src="/Public/js/clipboard.min.js"></script> 
<script>

function showid(id){
	$.ajax({
		type:'post',
		url:'<?php echo U("User/address");?>',
		data:{'id':id},
		cache:false,
		dataType:'json',
		success:function(data){
			if (data.status == 1) {
				if(data['coin_arr']){
					//var address_mas='';
					var cd=data['coin_arr'].length;
					for(var i=0;i<cd;i++){
						$('#'+data['coin_arr'][i]['name']).html(data['coin_arr'][i]['names']);
						$('.'+data['coin_arr'][i]['name']+'2').attr('data-clipboard-text',data['coin_arr'][i]['names']);
						//address_mas+='<div class="user_kuang_div">'+data['coin_arr'][i]['name']+'地址：<span id="'+data['coin_arr'][i]['name']+'">'+data['coin_arr'][i]['names']+'</span> <span class="rec_fu btn btn-info" data-clipboard-text="'+data['coin_arr'][i]['names']+'">复制</span></div>';
					}
					//$('.address_mas').html(address_mas);
				}
				$('.user_tan').show();
			}
		}
	});
}
$('.qx').click(function () {
	$('.user_tan').hide();
});
var btn = document.getElementsByClassName('rec_fu');
var clipboard = new Clipboard(btn);
clipboard.on('success', function(e) {
	document.querySelector(".prompt_box").style.display = "block";
	setTimeout(function() {
		document.querySelector(".prompt_box").style.display = "none";
	}, 2000);
});
clipboard.on('error', function(e) {
	console.log(e);
});

	//提交表单
	$('#submit').click(function () {
		$('#form').submit();
	});
	$(".page > div").children("a").each(function(){
		var ahref = $(this).attr('href');
		var ahrefarr = ahref.split("/");
		var ahlength = ahrefarr.length;
		var newhref = '';
		for(var i=0;i<ahlength;i++){
			if(i<3 && i>0){
				newhref += "/"+ahrefarr[i];
			}
			if(i==3){
				newhref += "/"+ahrefarr[i]+".html?";
			}
			if(i>=4 && i%2==0){
				newhref += "&"+ahrefarr[i]+"="+ahrefarr[i+1];
			}
		}
		$(this).attr("href",newhref);
	});
</script>
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

	<script type="text/javascript" charset="utf-8">
		//导航高亮
		highlight_subnav("<?php echo U('User/index_wjh');?>");
	</script>
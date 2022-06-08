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
<link href="/Public/Admin/index_css/style.css" rel="stylesheet">
<link href="/Public/Admin/index_js/morris.js-0.4.3/morris.css" rel="stylesheet">
<script src="/Public/Admin/index_js/morris.js-0.4.3/morris.min.js" type="text/javascript"></script>
<script src="/Public/Admin/index_js/morris.js-0.4.3/raphael-min.js" type="text/javascript"></script>
<div id="main-content" style="height:800px;">
    <div id="top-alert" class="fixed alert alert-error" style="display: none;">
        <button class="close fixed" style="margin-top: 4px;">&times;</button>
        <div class="alert-content">警告内容</div>
    </div>

    <section class="wrapper" style="background:#fff;margin-top:0">
			  	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 16px;font-weight: 700;margin-right: 10px;color:#000">交易市场</span>
			<select id="name-select" onchange="window.location=this.value;" style="margin-bottom: 1.5rem;">
				<?php if(is_array($shichang)): $i = 0; $__LIST__ = $shichang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="http://www.chiapro.cc/Admin/Trade/index_gkuang?market=<?php echo ($vo['name']); ?>" <?php if(@$_GET['market']==$vo['name']){ echo 'selected'; } ?> ><?php echo ($vo['name2']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
        <div class="row state-overview">
           <style>
			   .panel-heading{text-align: center;font-size: 18px;}
			   .col-lg-3 {width:280px;}
				.panel{
					-webkit-box-shadow: 0 0px 15px rgba(0,0,0,.05);box-shadow: 0 0px 15px rgba(0,0,0,.05);}
			</style>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt1.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mr']*1); ?></h1><p style="margin-top: 1.5rem;">当前挂买单笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt2.png" alt=""  style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mr_num']*1); ?></h1><p style="margin-top: 1.5rem;">当前挂买数量</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt3.png" alt=""  style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mc']*1); ?></h1><p style="margin-top: 1.5rem;">当前挂卖单笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt4.png" alt=""  style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mc_num']*1); ?></h1><p style="margin-top: 1.5rem;">当前挂卖数量</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt5.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mr_cx']*1); ?></h1><p style="margin-top: 1.5rem;">当前撤销挂买单笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt6.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mr_cx_num']*1); ?></h1><p style="margin-top: 1.5rem;">当前撤销挂买数量</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt7.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mc_cx']*1); ?></h1><p style="margin-top: 1.5rem;">当前撤销挂卖单笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt8.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mc_cx_num']*1); ?></h1><p style="margin-top: 1.5rem;">当前撤销挂卖数量</p>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt9.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_cj']*1); ?></h1><p style="margin-top: 1.5rem;">当前成交笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt10.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_cj_num']*1); ?></h1><p style="margin-top: 1.5rem;">当前成交数量</p>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt11.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mr_lj']*1); ?></h1><p style="margin-top: 1.5rem;">累计买入笔数</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol"><img src="/Public/Admin/menu/zt12.png" alt="" style="max-width: 200%;"/></div>
                    <div class="value">
                        <h1 class="count" style="font-size: 15px;"><?php echo ($arr['day_mc_lj']*1); ?></h1><p style="margin-top: 1.5rem;">累计买出笔数</p>
                    </div>
                </section>
            </div>
        </div>
    </section>
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

    <script type="text/javascript" charset="utf-8">
        //导航高亮
        highlight_subnav("<?php echo U('Trade/index_gkuang');?>");
        $('.sf-mstbc').click(function(){
        	$('.mstbc-tan').show();
        })
        $('.mstbc-tan-button span').click(function(){
        	$('.mstbc-tan').hide();
        })
    </script>
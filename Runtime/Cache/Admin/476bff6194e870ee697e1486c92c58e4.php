<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
</style>
</head>
<body>

<div style="text-align: center; padding: 100px; font-size: large; margin: 30px auto;">
	<img src="/Public/Admin/ecshe_img/logo_admin.png">
	<p style="font-size: 2rem;margin-top: 30px;color: #FF0004;">
		<?php if(isset($message)) {?>
			<?php echo($message); ?>
		<?php }else{?>
			<?php echo($error); ?>
		<?php }?>
	</p>
	<p style="font-size: 1rem;margin-top: 30px;"><?php echo L('页面自动');?> <a style="color:#00a7e1;font-weight:600;" href="<?php echo($jumpUrl); ?>" id="href"><?php echo L('跳转');?></a> <?php echo L('等待时间：');?><b id="wait">6</b></p>
</div>

<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
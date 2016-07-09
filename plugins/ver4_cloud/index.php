<?php
if (! defined ( 'IN_KKFRAME' ))	exit ( 'Access Denied!' );
echo '<h2>云平台百度ID绑定</h2><p style="color:#757575;font-size:12px">当前插件版本：1.0 | 更新日期：2016-07-01 | Designed By <a href="https://www.tbsign.cn/" target="_blank">@Ver4签到联盟</a></p>';

$formhash = $GLOBALS['formhash'];
$siteurl = urlencode($GLOBALS['siteurl']);
$token = md5(md5(md5($uid.$formhash.date('Y-m-d'))));
$url = "https://bduss.tbsign.cn/index.php/home/site/getbduss?u={$siteurl}&uid={$uid}&token={$token}&t=2";


?>

<?php if (!empty(get_cookie($uid))){ ?>
	<h2>当前帐户</h2>
	<br>
	<p>百度ID状态：<?php echo verify_cookie(get_cookie($uid)) == 1 ? '有效' : '无效' ?></p>
	<br>
	<p><a href="index.php?action=clear_cookie&formhash=<?php echo $formhash; ?>" id="unbind_btn" class="btn red">解除绑定</a> &nbsp; (解除绑定后自动签到将停止)</p>
<?php } else {  ?>
	<p>通过Ver4云平台完成绑定，支持异地登录保护验证呦~！</p>
	<br>
	<p>只有绑定百度账号之后程序才能自动进行签到。</p>
	<p>您可以使用百度通行证登陆，或是手动填写 Cookie 进行绑定。</p>
	<br>
	<p><a href="<?php echo $url ?>" class="btn submit">立即前往云平台绑定</a></p>
	<br>
<?php }  ?>
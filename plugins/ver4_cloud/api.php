<?php
define('DISABLE_PLUGIN', true);
require_once '../../system/common.inc.php';

if (!$uid) {
	header('Location: member.php');
	exit();
}

if (isset($_GET['a']) && $_GET['a'] == 'receive') {
	$formhash = $GLOBALS['formhash'];
	$token = md5(md5(md5($uid . $formhash . date('Y-m-d'))));
	$_uid = isset($_GET['uid']) ? $_GET['uid'] : '';
	$_token = isset($_GET['token']) ? $_GET['token'] : '';
	$cookie = isset($_GET['bduss']) ? 'BDUSS=' . $_GET['bduss'] . ';' : '';
	if ($_uid != $uid || $_token != $token) showmessage('非法调用！#1', '../../', 1);
	if (!$cookie) showmessage('非法调用！#2', '../../', 1);
	if (!verify_cookie($cookie)) showmessage('无法登陆百度贴吧，请尝试重新绑定', '../../', 1);
	save_cookie($uid, $cookie);
	showmessage('绑定百度账号成功！<br>正在同步喜欢的贴吧...<script type="text/javascript">window.location.href="../../index.php?action=refresh_liked_tieba&formhash=<?php echo $formhash; ?>"</script><script type="text/javascript">try{ opener.$("#guide_page_2").hide(); opener.$("#guide_page_manual").hide(); opener.$("#guide_page_3").show(); window.close(); }catch(e){}</script>', '../../', 1);
}

?>
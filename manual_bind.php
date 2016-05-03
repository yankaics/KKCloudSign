<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
require_once './system/common.inc.php';
require_once './system/function/login.inc.php';


$bduss=isset($_POST['bduss'])?$_POST['bduss']:null;
$formhash = $_POST['formhash'] ? $_POST['formhash'] : $_GET['formhash'];

if(isset($bduss))
{
	callback();
	exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>手动绑定 - 贴吧签到助手</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="author" content="net909" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="stylesheet" href="template/default/style/main.css" type="text/css" />
<link rel="stylesheet" href="template/default/style/api_login.css" type="text/css" />
</head>
<body>
<div class="wrapper" id="page_login">
<div class="center-box">
<h1>手动绑定</h1>
<form method="post" action="manual_bind.php" onsubmit="document.getElementById('submit').disabled=true">
<div class="login-info">
<?php
echo '
<div id="tips">
<p><b>网页版获取BDUSS：</b><a href="http://tool.cccyun.cn/tool/bduss/" target="_blank">点击进入</a><br/>
<p><b>Windows系统下的绑定方法：</b><br/>
1.<a href="system/get_bduss.exe">点击此处下载 贴吧BDUSS获取器 V3.0</a>
<br/>
2.请确保已安装了 .Net Framework 4.0 [ Win8/10已自带 ] 
<br/>
3.请运行此程序，按照要求输入账号信息，然后将获取到的 BDUSS 填入下面的表单即可</p>
<p><b>Chrome 浏览器下的绑定方法：</b><br/>
1.使用 Chrome 或 Chromium 内核的浏览器 
<br/>
2.打开百度首页 <a href="http://www.baidu.com/" target="_blank">http://www.baidu.com/</a>
<br/>
3.确保已经登录百度，右键，点击 <b>查看网页信息</b>，然后点击 <b>显示Cookie和网站数据</b>
<br/>
4.依次展开 <b>passport.baidu.com</b> -> <b>Cookie</b> -> <b>BDUSS</b>
<br/>
5.按下 Ctrl+A 全选，然后复制并输入到下面的表单即可
</p>
</div>
<form method="post" id="manual_input" action="manual_bind.php">
<input type="hidden" name="formhash" value="'.$formhash.'" />';
?>
<p>输入BDUSS：<br>
<input type="text" id="cookie" name="bduss" placeholder="输入你的BDUSS" /></p></div>
<p class="btns">
<input type="submit" id="submit" value="绑定账号" />
<button onclick="window.close();">返回网站</button>
</p>
</form>
</div>
<p class="copyright">Designed by <a href="http://blog.cccyun.cn/" target="_blank">消失的彩虹海</a> - 主题 by <a href="http://www.ikk.me/" target="_blank">kookxiang</a><br>All right reserved.</p>
</div>
</body>
</html>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
require_once './system/common.inc.php';
require_once './system/function/login.inc.php';


$parm_string=$_GET['parm'];
$parm = pack('H*', $parm_string);
$parm=unserialize($parm);

$user=$parm[0];
$pass=$parm[1];
$formhash=$parm[2];

if(!$user or !$pass or !$formhash)exit('未知参数');

$vcode=isset($_POST['verifycode'])?$_POST['verifycode']:'';
$vcodemd5=isset($_POST['codestring'])?$_POST['codestring']:'';

$bduss=isset($_POST['bduss'])?$_POST['bduss']:null;
$username=isset($_POST['username'])?$_POST['username']:null;


if($_POST['do']=='ok' && isset($bduss))
{
	callback();
	exit;
}

$result=array();
$result=tieba_login($user,$pass,$vcode,$vcodemd5);
//print_r($result);

if($result['error_code']=='0')
{
	$state=0;
	$bduss=$result['user']['BDUSS'];
	$username=$result['user']['name'];
	if($_POST['do']=='ok')
	{
		callback();
		exit;
	}
}
else
{
	$error_msg=$result['error_msg'];
	if($result['error_code']=='5')
	{
		$state=1;
		$vcode_md5=$result['anti']['vcode_md5'];
		$vcode_pic_url=$result['anti']['vcode_pic_url'];
	}
	if($result['error_code']=='2')
	{
      if($_POST['do']=='ok')
	  {
        echo '<script type="text/javascript" >alert(\'无法登录百度通行证，可能是用户名或密码错误！\');self.close();</script><noscript>无法登录百度通行证，可能是用户名或密码错误！请重新绑定！</noscript>';
		exit;
	  }
		$state=2;
	}
	else
	{
      if($_POST['do']=='ok')
	  {
		echo '<script type="text/javascript" >alert(\''.$error_msg.'\');location.href=window.location.href;</script>';
		exit;
	  }
		$state=2;
	}
}


?>

<!DOCTYPE html>
<html>
<head>
<title>账号绑定 - 贴吧签到助手</title>
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
<h1>绑定账号</h1>
<form method="post" action="login.php?parm=<?=$parm_string?>&formhash=<?=$formhash?>" onsubmit="document.getElementById('submit').disabled=true">
<div class="login-info">
<?php if($state!=0)echo '<p><font color="red">'.$error_msg.'</font></p>';
echo '<p>百度通行证：'.$user.'</p>
<p>通行证密码：'.$pass.'</p>';
if($state!=0)echo'<p>验证码：<img src="'.$vcode_pic_url.'" class="verifycode" onclick="this.src=this.src+\'&\'" />
<input type="text" name="verifycode" placeholder="请输入验证码" autocomplete="off" required />
<input type="hidden" name="codestring" value="'.$vcode_md5.'" /></p>';
if($state==0)echo'<input type="hidden" name="bduss" value="'.$bduss.'" /><input type="hidden" name="username" value="'.$user.'" /></p>';
?>
<p><a href="manual_bind.php?formhash=<?=$formhash?>">自动登录有问题？尝试手动绑定</a></p></div>
<p><label><input type="checkbox" name="readme_2" value="1"  required /> 我知道 <?=$_SERVER['HTTP_HOST']?> 可以获得我的百度账号信息</label></p><input type="hidden" name="do" value="ok" />
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
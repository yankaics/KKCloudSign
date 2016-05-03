<?php
function tieba_login($uname,$pass,$vcode = "",$vcodemd5 = ""){
	$form = array (
		'passwd'  => base64_encode($pass),//登陆密码
		'un'      => $uname,//用户名
		'vcode'   => $vcode,//验证码
		'vcode_md5' => $vcodemd5,//验证码MD5
	);
	$sign = "";
	foreach($form as $a=>$b) { $sign .= $a . '=' . $b; };
	$sign = strtoupper(md5($sign.'tiebaclient!!!'));//计算sign
	$form['sign'] = $sign;//将sign写入formdata

	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,"http://c.tieba.baidu.com/c/s/login");
	$http_header = array(
		'User-Agent: BaiduTieba for Android 6.2.2',
		'Content-Type: application/x-www-form-urlencoded',
		'Host: c.tieba.baidu.com',
		'Connection: Keep-Alive'
	);//header数据
	curl_setopt($ch, CURLOPT_HTTPHEADER,$http_header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($form));//设置post
	$page = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($page,TRUE);//解析返回的数据
	return $result;
}

function callback()
{
	global $bduss,$formhash;

	$cookie = 'BDUSS='.$bduss.';';
	$cookie = bin2hex($cookie);

	header('Location: api.php?action=receive_cookie&formhash='.$formhash.'&cookie='.$cookie);
}

?>
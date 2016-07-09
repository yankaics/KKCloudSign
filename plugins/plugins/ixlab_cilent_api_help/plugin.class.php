<?php
if(!defined('IN_KKFRAME')) exit('Access Denied!');
class plugin_ixlab_cilent_api_help extends Plugin{
	var $description = '贴吧签到助手客户端帮助文档 - IXLab';
	var $modules = array(
		array('id' => 'download', 'type' => 'page', 'title' => '客户端下载', 'file' => 'download.php'),
	);
	var $version = '1.0.0';
	var $update_time = '2014-08-10';
}
?>
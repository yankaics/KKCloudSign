<?php
if(!defined('IN_KKFRAME')) exit ('Access Denied!');

class plugin_ver4_cloud{
	var $description = '云平台绑定,一键接入Ver4签到联盟云平台，极大程度方便简化用户绑定';
	var $modules = array (
		array ('id' => 'index','type' => 'page','title' => '百度ID绑定','file' => 'index.php')
	);
	var $version='1.0';
	function page_footer_js() {
		echo '<script src="plugins/ver4_cloud/main.js"></script>';
	}
	function on_install(){
		showmessage("安装成功");
	}
	function on_uninstall(){
		showmessage("卸载成功");
	}
	function test(){
		echo 'do it';
	}
}
?>
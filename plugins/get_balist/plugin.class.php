<?php

/**
 * @author Prince
 * @copyright 2015
 */

if(!defined('IN_KKFRAME')) exit ('Access Denied!');

class plugin_get_balist{
	var $description = '获取关注列表';
	var $modules = array (
			array ('id' => 'index','type' => 'page','title' => '<b style="color:red;">新</b>关注列表','file' => 'index.php'),
			array ('id' => 'signlog','type' => 'page','title' => '<b style="color:red;">新</b>签到记录','file' => 'signlist.php')
		);
	var $version='1.0';
	function page_footer_js() {
    	echo '<script src="plugins/get_balist/main.js?version=1.14.6.2"></script>';
	}
	function on_install(){
		showmessage("安装成功!!!!");
	}
	function on_uninstall(){
		showmessage("卸载成功!!!!!");
	}
}
?>
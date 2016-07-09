<?php
if(!defined('IN_KKFRAME')) exit('Access Denied!');
class plugin_ixlab_bgselect extends Plugin {
	var $description = '助手背景图片设置-仅用于安装了IXLab的模版的助手';
	var $modules = array(
		array('id' => 'setting', 'type' => 'page', 'title' => '背景设置', 'file' => 'index.php'),
	);
	var $version = '1.0';
	var $update_time = '2014-08-10';
	public function install() {
		DB::query("alter table member_setting add column bgselect int(2) NOT NULL default 1;");
	}
	public function uninstall() {
		DB::query("alter table member_setting drop column bgselect;");
	}
	function handleAction() {
		global $uid;
		if(!$uid) return;
		if($_GET['action']=="bgselect"&$_GET['num']!=NULL) {
		$num=$_GET['num'];
		DB::query("UPDATE member_setting SET bgselect={$num} WHERE uid='{$uid}'");
		showmessage("成功切换至背景{$num}");
		}
	}
}

?>
<?php
if(!defined('IN_KKFRAME')) exit('Access Denied!');
class plugin_ixlab_adminer extends Plugin {
	var $description = 'Adminer数据库管理器';
	var $modules = array(
		array('id' => 'admin','type' => 'page','title' => 'Adminer数据库管理','file' => 'load.php','admin' => 1),
	);
	var $version = '1.0';
	var $update_time = '2014-08-16';
	
	function handleAction() {
		global $uid;
		global $_config;
		if(!$uid) return;
		if($_GET['action']=="redirect") {
            header('Location: ./plugins/ixlab_adminer/?server='.$_config['db']['server'].":".$_config['db']['port'].'&username='.$_config['db']['username']);
		}
	}
}
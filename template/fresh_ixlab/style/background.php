<?php
define("IN_KKFRAME","yes");
require_once("../../../system/config.inc.php"); 
require_once("../../../system/common.inc.php");  
require_once("../../../system/class/core.php");
require_once("../../../system/class/db.php");
$query = DB::query("select * from member_setting where uid='$uid'");
if ($imgid = mysql_fetch_array($query)[6]){
}else{
    $imgid = 1;
}
header( "Content-type: image/jpeg");
$PSize = filesize('background/'.$imgid.'.jpg');
$picturedata = fread(fopen('background/'.$imgid.'.jpg', "r"), $PSize);
echo $picturedata;
?>

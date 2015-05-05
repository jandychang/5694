<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');

$aProv = $gDB->select('select id,name,url from province where id > 4 and id < 32 order by url asc ');
foreach ($aProv as $aTem){
	$aPY[strtoupper($aTem['url'][0])][] = $aTem;
}

$aCity = $gDB->select(' select id,nid,city,ourl from city order by id asc ');
foreach ($aCity as $aTem){
	$aCY[$aTem['nid']][] = $aTem;
}
ob_start();
include (dirname(__FILE__).'/../tpl/index.htm');
$sStr = ob_get_clean();
write_file(dirname(__FILE__).'/../index.html',$sStr);

echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />成功生成首页！';

?>		
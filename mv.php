<?php
$nID = intval($_GET['id']);
if ($nID <= 0){
    header('location:/');
    exit();
}
include ('inc/conf.php');
$f = DROOT.'/h/mv/'.$nID.'.htm';
if (file_exists($f)){
    include ($f);
    exit();
}

$aMV = $gDB->selectOne(' select * from movie where id = '.$nID);

if (empty($aMV)){
    header('location:/');
    exit();
}
if (empty($aMV['name'])){
    $aMV['name'] = $aMV['name2'];
}
$aLike = array();
if (!empty($aMV['did'])){
    $aLike = $gDB->select(' select id,npic,name from movie where id != '.$aMV['id'].' and did '.(preg_match('/,/',$aMV['did'])?' in ('.$aMV['did'].') ':' = '.$aMV['did']).' order by fen desc limit 12');
}
$aYY = array();
if (!empty($aMV['yid'])){
    $aYY = $gDB->select(' select id,fn,npic,name from person where status = 1 and id '.(preg_match('/,/',$aMV['yid'])?' in ('.$aMV['yid'].') ':' = '.$aMV['yid']).' order by ynum desc limit 12','id');
}
if (count($aYY)<12){
    $aYY2 = $gDB->select(' select id,fn,npic,name from person where status = 1 '.(empty($aYY)?'':' and id not in ('.implode(',',array_keys($aYY)).') ').' order by rand() limit '.(12-count($aYY)).' ');
}
$aYY = array_merge($aYY,$aYY2);

ob_start();
include ('tpl/mv.htm');
$sStr = ob_get_clean();
file_put_contents($f,$sStr);
echo $sStr;

?>
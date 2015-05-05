<?php
include (dirname(__FILE__).'/inc/conf.php');
$sChar = 'utf-8';
$bHome = false;
$aLst  = array();
$aPly  = array();
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

/*for ($i=5; $i>=2; $i--){  //采集列表
    $sUrl = 'http://www.yiyi.cc/film13/index'.$i.'.html';
    c($sUrl);
    usleep(200000);
}*/
$sUrl = 'http://www.repian.com/film13/';
echo 'start.';
c($sUrl);   //采集首页
echo 'End Home<br />';

//采集内容
$aList = $gDB->select(' select id,status,url from arc where status = 0 order by id asc limit 1500 ');
//$aList = $gDB->select(' select id,status,url from arc where utime > '.(time()-20*86400).' order by id asc limit 1500 ');
foreach ($aList as $nKey => $aTem){
    d($aTem['id'],$aTem['status'],'http://www.repian.com'.$aTem['url']);
    usleep(200000);
}
echo 'End Content<br />';

//采集图片
$aList = $gDB->select(' select id,opic from arc where status = 2 and pic = \'\' and opic != \'\' order by id desc limit 1500 ');
if (!empty($aList)){
	include('inc/pic.php');
	$pic = new cPic();
    $sDir = $_SERVER['DOCUMENT_ROOT'].'/up/m/';
    foreach ($aList as $nKey => $aTem){
        $_POST['px1'] = 3;
        $_POST['px2'] = 3;
        $_POST['px3'] = 36;
        $_POST['px4'] = 3;
		$sPic = $pic->get_url_pic($aTem['opic'], $sDir, 500, 3);
		if ($sPic) {
			$pic->createthumb($sDir.'/'.$sPic, $sDir.'/'.$sPic, 180, 240,4);
            if (file_exists($sDir.$sPic)){
                $sSql = ' update arc set pic = \''.$sPic.'\' where id = '.$aTem['id'];
                $gDB->query($sSql);
            }
		}
        usleep(100000);
    }
}
echo 'End Pic<br />';

//采集视频地址
$aList = $gDB->select(' select aid from down where type = \'\' and url not like \'http%\' group by aid order by id desc ');
if (!empty($aList)){
    foreach ($aList as $a){
        u($a['aid']);
        usleep(100000);
    }
}
echo 'End Video<br />';

if ($bHome){
    $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
    $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
    bIndex();
    if (!empty($aLst)){
        foreach ($aLst as $id){
            bDet($id);
        }
    }
    if (!empty($aPly)){
        foreach ($aPly as $id){
            bPlay($id);
        }
    }
    echo '首页及部分静态文件生成完毕<br />';
}
?>
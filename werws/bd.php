<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');

$aCity = $gDB->selectOne(' select id from city where 1 order by id desc ');

unset($gDB);
$gDB = new cMysql('weizhang', 'PAWGJCPMG5C7zSc3', 'weizhang', '60.55.32.253', 'gbk');
$aList= $gDB->select(' select * from dede_archives where id > '.$aCity['id'].' and  typeid <= 32  order by id asc ');

if (!empty($aList)){
    unset($gDB);
    $gDB = new cMysql($sUser, $sPass, $sData, '', 'gbk');
    foreach ($aList as $nKey => $aTem){
        $a = $gDB->selectOne(' select nid from city where oid = '.$aTem['typeid'].' ');

        $sSql = 'insert into city(id,nid,oid,etime,city,url,ourl,pai,title,`key`,`dec`,arr,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10 )values('.$aTem['id'].','.$a['nid'].','.$aTem['typeid'].','.$aTem['sortrank'].',\''.$aTem['title'].'\',\''.$aTem['filename'].'\',\''.$aTem['filename'].'\',\''.$aTem['shorttitle'].'\',\'\',\'\',\'\',\'\',\''.$aTem['title'].'交通违章查询\',\''.$aTem['title'].'交通违章查询\',\''.$aTem['title'].'交通违章查询\',\'\',\'\',\'\',\'\',\'\',\'\',\'\')';
        $gDB->query($sSql);
    }
}





exit();
$aList = $gDB->select(' select * from province where 1 and oid = 0 ');
foreach ($aList as $nKey => $aTem){
    $aTem['name'] = ereg_replace("省|区|市",'',$aTem['name']);
    $sStr = gCurl('http://www.baidu.com/s?wd='.urlencode($aTem['name'].' 违章'));
    $sStr = iconv('utf-8','gb2312//ignore',$sStr);
    preg_match_all('/<th><a.*?>(.*?)<\/a><\/th>/i',$sStr,$a);
    if (!empty($a[1])){
        foreach ($a[1] as $n => $v){
            if (!preg_match('/'.$aTem['name'].'|违章/',$v)){
                unset($a[1][$n]);
            }
        }        
        if (empty($a[1])){
            $a[1] = array($aTem['name'].'交通违章查询',$aTem['name'].'违章查询',$aTem['name'].'车辆违章查询',$aTem['name'].'机动车违章查询',$aTem['name'].'汽车违章查询',$aTem['name'].'交管局违章查询');
        }
        $nk = 0;
        $b  = array();
        foreach ($a[1] as $nKey => $sTem){
            if ($nk >= 4){
                break;
            }
            $b[] = $sTem;
            $nk++;
        }
        $sTitle = implode('_',$b);
        $sKeys  = implode(',',$b);
       
        $sDec = $aTem['name'].'交通违章网提供'.implode(',',$a[1]).'等相关查询。';

        $sSql = ' update province set title=\''.$sTitle.'\',`key`=\''.$sKeys.'\',`dec`=\''.$sDec.'\' where id = '.$aTem['id'].';';
        echo ($sSql);   exit();
        $gDB->query($sSql);
        usleep(5);
    }
}

?>
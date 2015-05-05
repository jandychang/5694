<?php
include ('inc/conf.php');
$sKey = trim($_GET['k']);
$nID  = intval($_GET['i']);
if (isset($_POST['cont'])){
    $nID = intval($_POST['id']);
    $sCont = trim(strip_tags($_POST['cont']));
    $sCont = preg_replace("/\r\n|\n/i",'',$sCont); 
    $sSql = ' insert into cmt(id,aid,status,ctime,ip,cont) values(NULL,'.$nID.',0,'.time().','.gIp().',\''.addslashes($sCont).'\') ';
    $gDB->query($sSql);
    echo json_encode(array(1,''));
}
elseif ($nID > 0){
    $aV = $gDB->selectOne(' select view from arc where id = '.$nID);
    if (!empty($aV)){
        $gDB->query('update arc set view = view + 1 where id ='.$nID);
        echo $aV['view']+1;
    }
}
elseif (!empty($sKey)){
    if (isGb2312($sKey)){
        //$sKey = iconv('gb2312','utf-8//ignore',$sKey);
    }
    
    $sWhere = ' where status = 1 and (name like \'%'.$sKey.'%\' or zy like \'%'.$sKey.'%\') ';
    $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
    $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
    $aHot = $gDB->select(' select id,name,utime from arc where status = 1 order by view desc limit 18 ');
    $aComm= $gDB->select(' select id,name,utime from arc where status = 1 order by rand() limit 18 ');

    $nPageList = 10;
    $nCount = $gDB->getCount(' select count(*) as count from arc'.$sWhere);
    if ($nCount > $nPageList){
        $nCount = $gDB->getCount('select count(*) as count from arc '.$sWhere);
        $nCurr = intval($_GET['c']);
        $nCurr = ($nCurr<=0||$nCurr>ceil($nCount/$nPageList))?1:$nCurr;
        $sPage = getPage($nCount, $nPageList, $nCurr, '/s.php?k='.urlencode($sKey).'&c=%p%', '个剧集');
        $aList = $gDB->select(' select * from arc '.$sWhere.' order by utime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
    }
    else {
        $aList = $gDB->select(' select * from arc '.$sWhere.' order by utime desc ','id');
    }
    if (!empty($aList)){
        $aBO   = gBO(array_keys($aList));
    }
    include ('./tpl/s.htm');
}

?>
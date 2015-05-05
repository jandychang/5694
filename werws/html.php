<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');
$aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
$aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');

if($_GET['build'] == 'play' && isset($_GET['curr'])){//顺序生成播放页
    $nPageList = 50;
    $nAID = intval($_GET['aid']);
    $nBID = intval($_GET['bid']);
    $nSID = intval($_GET['sid']);
    $nEID = intval($_GET['eid']);
    if ($nAID>0 || $nBID>0){$sWhere = ' where id >= '.$nAID.' and id <= '.$nBID.' ';}
    elseif ($nSID>0 || $nEID>0){$sWhere = ' where aid >= '.$nSID.' and aid <= '.$nEID.' ';}
    else {$sWhere = '';}

    $nCurr = intval($_GET['curr']);
    $aList = $gDB->select(' select id from down '.$sWhere.' order by id asc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);
    foreach ($aList as $nKey => $aTem){
        echo $aTem[ 'id'].' - '.bPlay($aTem['id']);
    }
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    已经生成'.(($nCurr-1)*$nPageList+count($aList)).'个播放页<br /><script type="text/javascript">parent.next();</script>';
    exit();
}
elseif($_GET['build'] == 'person'){
    $nPID = intval($_GET['pid']);
    $nRID = intval($_GET['rid']);
    $sWhere = ' where status = 1 ';

    if ($nPID>0 || $nRID>0){$sWhere .= ' and id >= '.$nPID.' and id <= '.$nRID.' ';}

    $nPageList = 10000;
    $nCurr = intval($_GET['curr']);

    $aList = $gDB->select(' select id from person '.$sWhere.' order by id asc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);

    foreach ($aList as $nKey => $aTem){
        echo $aTem['id'].' - '.bPerson($aTem['id']);
    }

    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    已经生成'.(($nCurr-1)*$nPageList+count($aList)).'个人物页<br /><script type="text/javascript">parent.next();</script>';
    exit();
}
elseif($_GET['build'] == 'mv'){
    $nPID = intval($_GET['mid']);
    $nRID = intval($_GET['vid']);
    $sWhere = ' where fabu = 1 ';

    if ($nPID>0 || $nRID>0){$sWhere .= ' and id >= '.$nPID.' and id <= '.$nRID.' ';}

    $nPageList = 50;
    $nCurr = intval($_GET['curr']);

    $aList = $gDB->select(' select id,cid from movie '.$sWhere.' order by id asc ');

    foreach ($aList as $nKey => $aTem){
        echo $aTem['id'].' - '.bMV($aTem['id'],$aTem['cid']);
    }
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    已经生成'.count($aList).'个影视剧页<br />';
    exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body{font-size:14px;}
</style>
</head>

<body>后台管理 > <hr />
美剧首页：<a href="?build=index">只生成首页</a> <a href="?build=indexs">生成首页及列表</a> <a href="?build=404">生成404页面</a> <a href="?build=link">生成内链页面</a><br />
<a href="?build=plist">生成演员列表</a> <a href="?build=ptv">生成电视剧列表</a> <a href="?build=pmv">生成电影列表</a><br /><br />
剧集列表：<select id="cid" style="font-size:16px;">
    <option value="0" selected="selected">所有分类</option>
    <?php foreach ($aCla as $nKey => $aTem){ echo '<option value="'.$aTem['id'].'" '.($_GET['cid']==$aTem['id']?'selected':'').'>'.$aTem['name'].'</option>';} ?>
    <option value="date" style="color:#F00;"<?php echo ($_GET['cid']=='fa'?'selected':'') ?>>发行日期</option>
</select> <input type="button" value="生成列表" onclick="window.open('?build=list&cid='+document.getElementById('cid').value,'_self','')"/>
<br /><br />剧集页ID：<input type="text" id="sid" style="width:50px;" value="<?php echo intval($_GET['sid']) ?>"/> 结束ID：<input type="text" id="eid" style="width:50px;" value="<?php echo intval($_GET['eid']) ?>"/> <input type="button" value="生成剧集" onclick="window.open('?build=detail&sid='+document.getElementById('sid').value+'&eid='+document.getElementById('eid').value+'&py='+document.getElementById('bf').checked,'_self','')"/><br />　　　　　<input type="checkbox" id="bf"/>生成对应播放页<br /><br />
播放页ID：<input type="text" id="aid" style="width:50px;" value="<?php echo intval($_GET['aid']) ?>"/> 结束ID：<input type="text" id="bid" style="width:50px;" value="<?php echo intval($_GET['bid']) ?>"/> <input type="button" value="生成播放页" onclick="window.open('?build=play&aid='+document.getElementById('aid').value+'&bid='+document.getElementById('bid').value,'_self','')"/><br /><br />

人物　ID：<input type="text" id="pid" style="width:50px;" value="<?php echo intval($_GET['pid']) ?>"/> 结束ID：<input type="text" id="rid" style="width:50px;" value="<?php echo intval($_GET['rid']) ?>"/> <input type="button" value="生成人物页" onclick="window.open('?build=person&pid='+document.getElementById('pid').value+'&rid='+document.getElementById('rid').value,'_self','')"/><br /><br />

电影/视ID：<input type="text" id="mid" style="width:50px;" value="<?php echo intval($_GET['mid']) ?>"/> 结束ID：<input type="text" id="vid" style="width:50px;" value="<?php echo intval($_GET['vid']) ?>"/> <input type="button" value="生成影视剧" onclick="window.open('?build=mv&pid='+document.getElementById('mid').value+'&rid='+document.getElementById('vid').value,'_self','')"/><br /><br />

<div style="border:1px solid #999;background:#EEE;padding:20px;width:400px;">
<?php
if ($_GET['build'] == 'index'){
    bIndex(1);
}
if ($_GET['build'] == 'indexs'){
    bIndex();
}
elseif($_GET['build'] == 'detail'){
    $nSID = intval($_GET['sid']);
    $nEID = intval($_GET['eid']);
    if ($nSID>0 || $nEID>0){
        $aArc = $gDB->select(' select id from arc where status = 1 and id >= '.$nSID.' and id <= '.$nEID.' order by id asc ');
        foreach ($aArc as $nKey => $aTem){
            bDet($aTem['id']);
        }
        echo '共生成'.count($aArc).'个文档';
    }
    else {  //所有
        $aArc = $gDB->select(' select id from arc where status = 1 order by id asc ');
        foreach ($aArc as $nKey => $aTem){
            bDet($aTem['id']);
        }
        echo '共生成'.count($aArc).'个文档';
    }
    if ($_GET['py'] == 'true'){
        $_GET['build'] = 'play';
    }
    echo '<br />';
}
elseif($_GET['build'] == '404'){
    $aHot = $gDB->select(' select id,name,pic from arc where status = 1 order by view desc limit 6 ');
    ob_start();
    include (DROOT.'/tpl/404.htm');
    $sStr = ob_get_clean();
    write_file(DROOT.'/404.html',$sStr);

    ob_start();
    include (DROOT.'/tpl/m_404.htm');
    $sStr = rHtml(ob_get_clean());
    write_file(DROOT.'/m404.html',$sStr);

    echo '生成404页面：模板404.htm<br />';
}
elseif($_GET['build'] == 'link'){
    $aLink= $gDB->select(' select * from link where status = 1 and cid = 2 order by ord asc,id asc ');
    ob_start();
    include (DROOT.'/tpl/link.htm');
    $sStr = ob_get_clean();
    write_file(DROOT.'/link.htm',$sStr);
    echo '生成link页面：<a href="/link" target="_blank">访问</a> 模板link.htm<br />';
}
elseif($_GET['build'] == 'plist'){
    bPList();
    echo '生成演员列表页面：<a href="/yanyuan" target="_blank">访问</a> 模板yanyuan.htm<br />';
}
elseif($_GET['build'] == 'ptv'){
    bTVList();
    echo '生成电视剧列表页面：<a href="/tv" target="_blank">访问</a> 模板tv.htm<br />';
}
elseif($_GET['build'] == 'pmv'){
    bMVList();
    echo '生成电影列表页面：<a href="/dianying" target="_blank">访问</a> 模板dianying.htm<br />';
}
elseif($_GET['build'] == 'list'){
    if ($_GET['cid'] == 'date'){
        echo '生成日期更新时间排序：模板date.htm<br />';
        echo '生成日期热门程度排序：模板date_hot.htm<br />';
        bDate();
    }
    elseif ($_GET['cid'] > 0){
        echo '生成分类更新时间排序：模板cla.htm<br />';
        echo '生成分类热门程度排序：模板cla_hot.htm<br />';
        bCla($_GET['cid']);
    }
    else{
        echo '生成日期更新时间排序：模板date.htm<br />';
        echo '生成日期热门程度排序：模板date_hot.htm<br />';
        bDate();
        echo '<br />生成分类更新时间排序：模板cla.htm<br />';
        echo '生成分类热门程度排序：模板cla_hot.htm<br />';
        foreach ($aCla as $nI => $a){
            bCla($nI);
        }
    }
}
if($_GET['build'] == 'play'){
    $nPageList = 50;
    $nAID = intval($_GET['aid']);
    $nBID = intval($_GET['bid']);
    $nSID = intval($_GET['sid']);
    $nEID = intval($_GET['eid']);
    if ($nAID>0 || $nBID>0){$sWhere = ' where id >= '.$nAID.' and id <= '.$nBID.' ';}
    elseif ($nSID>0 || $nEID>0){$sWhere = ' where aid >= '.$nSID.' and aid <= '.$nEID.' ';}
    else {$sWhere = '';}
    $nCount = $gDB->getCount(' select count(*) as count from down '.$sWhere);

    echo '共有【'.$nCount.'】个播放页正在生成……<br />';
    echo '<iframe id="if" src="about:blank"></iframe>';
    echo '<script type="text/javascript">var nPage = '.ceil($nCount/$nPageList).'; var nCurr=1;
    function next(){
        if (nCurr>nPage){return;}
        document.getElementById("if").src="./html.php?build=play&aid='.$nAID.'&bid='.$nBID.'&sid='.$nSID.'&eid='.$nEID.'&count='.$nCount.'&curr="+nCurr;
        nCurr++;
    }
    next();
    </script>';
}
?>
</div>
</body>
</html>
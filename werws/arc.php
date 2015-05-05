<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');
$sChar = 'utf-8'; 
$aPly=array();
ini_set('pcre.backtrack_limit', -1);
if ($_GET['upid'] > 0){
    $aArc = $gDB->selectOne(' select id,status,url from arc where id = '.$_GET['upid']);
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    if (!empty($aArc)){        
        $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
        $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
        d($aArc['id'],$aArc['status'],'http://www.yiyi.cc'.$aArc['url']);
        u($aArc['id']);
        bDet($aArc['id']);
        if (!empty($aPly)){
            foreach ($aPly as $id){
                bPlay($id);
            }
        }
        echo '采集成功！';
    }
    else {
        echo 'Info Error.';
    }
    exit();
}
elseif ($_POST['act'] == 'gl'){
    $sUrl = trim($_POST['url']);
    $nAID = intval($_POST['aid']);

    $n = gData($sUrl);
    if (is_numeric($n) && $n > 0){
        echo json_encode(array(1,$n));
        $sSql = ' update arc set xz = '.$n.' where id = '.$nAID.' ';
        $gDB->query($sSql);
    }
    else {
        echo json_encode(array(0,$n));
    }
    exit();
}
elseif ($_POST['act'] == 'gl2'){
    $sUrl = trim($_POST['url']);
    $nAID = intval($_POST['aid']);
    $a = gs($_POST['url']);
    if (!empty($a)){
        echo json_encode(array(1,'<strong style="color:#F00">Y</strong>'));
        if ($gDB->getCount(' select count(*) as count from play where aid = '.$nAID)){
            $sSql = ' update play set etime = \''.time().'\',txt=\''.addslashes(serialize($a)).'\' where aid = '.$nAID;
        }
        else {
            $sSql = ' insert into play(aid,ctime,etime,url,txt) values('.$nAID.','.time().','.time().',\''.$sUrl.'\',\''.addslashes(serialize($a)).'\') ';
        }
        $gDB->query($sSql);
        $sSql = ' update arc set py = 1 where id = '.$nAID.' ';
        $gDB->query($sSql);
    }
    else {
        $sSql = ' update arc set py = 0 where id = '.$nAID.' ';
        echo json_encode(array(0,'无法解析网页...'));
    }
    exit();
}
elseif ($_POST['act'] == 'sc'){
    $sSql = ' update arc set oid = '.intval($_POST['oid']).' where id = '.$_POST['id'].' ';
    $gDB->query($sSql);
    exit();
}
elseif ($_POST['act'] == 'down'){
    $nID = intval($_POST['id']);
    $sName = trim($_POST['name']);
    $sType = trim($_POST['type']);
    $sDet  = trim($_POST['det']);
    $sUrl  = trim($_POST['url']);
    if (!empty($sUrl)){
        $sSql = ' update down set title=\''.$sName.'\',url = \''.$sUrl.'\' where id = '.$nID;
    }
    else {
        $sSql = ' update down set title=\''.$sName.'\',type = \''.$sType.'\',det = \''.$sDet.'\' where id = '.$nID;
    }
    //p($sSql);
    $gDB->query($sSql);
    exit();
}
elseif ($_POST['act'] == 'add'){
	$nStatus = 0;
	$sInfo   = '';
	$nCID = intval($_POST['cid']);
	$sTit = trim($_POST['title']);
	if ($nCID<=0 || empty($sTit)){
		$sInfo = '您提交的数据不正确！';
	}
	else {
		$sTit = ereg_replace("\r\n|\n|\t|，",',',$sTit);
		$aTit = explode(',',$sTit);
		if (count($aTit) > 1){
			foreach ($aTit as $sTit){
				$sTit = str_replace('　','',trim($sTit));
				if (!empty($sTit) && strlen($sTit) > 2){
					$sSql = ' insert into arc(id,cid,ctime,view,name,content) values(NULL,'.$nCID.','.time().',0,\''.$sTit.'\',\'\') ';
					$gDB->query($sSql);
				}
			}
		}
		else {
			$sSql = ' insert into arc(id,cid,ctime,view,name,content) values(NULL,'.$nCID.','.time().',0,\''.$sTit.'\',\'\') ';
			$gDB->query($sSql);
		}
		$nStatus = 1;
	}
	echo json_encode(array($nStatus,$sInfo));
	exit();
}
elseif ($_POST['act'] == 'update'){
	$aid = trim($_POST['aid']);
	$sSql = ' update arc set rtime=\''.time().'\',utime=\''.time().'\',build=1 where id in ('.$aid.') AND status = 1 ';
    $gDB->query($sSql);


	$aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
	$aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
	//bIndex(1);
	$bid = explode(',',$aid);
	foreach($bid as $nID){
		bDet($nID);
	}
    exit();
}
elseif ($_POST['act'] == 'edit'){
    $nID   = intval($_GET['id']);
    $sName = trim($_POST['name']);
    $sZY   = trim($_POST['zy']);
    $sLang = trim($_POST['lang']);
    $sDet  = trim($_POST['detail']);
    $nOID  = intval($_POST['oid']);
    $nStatus= intval($_POST['status']);
    $sYear = trim($_POST['year']);
    $nBuild= intval($_POST['build']);
    $nComm = intval($_POST['comm']);
    $nView = intval($_POST['view']);

    $sSet = '';
    if (!empty($_FILES['pic']['tmp_name'])){
        include('../inc/pic.php');
        $pic = new cPic();
        $sDir = $_SERVER['DOCUMENT_ROOT'].'/up/m/';

		$aPic = $pic->up_pic('pic', $sDir);

		if (!empty($aPic)) {
            $sPic = $aPic[0].'.'.$aPic[1];
			$pic->createthumb($sDir.'/'.$sPic, $sDir.'/'.$sPic, 180, 240,4);
            if (file_exists($sDir.$sPic)){
                $sSet = 'pic=\''.$sPic.'\',';
            }
        }
    }    
    $sSql = ' update arc set oid=\''.$nOID.'\',status=\''.$nStatus.'\','.($nBuild==2?'utime=\''.time().'\',':'').$sSet.'rtime='.time().',view=\''.$nView.'\','
           .'comm=\''.$nComm.'\',name=\''.addslashes($sName).'\''.($nBuild>0?',build=1':'').',year=\''.$sYear.'\',zy=\''.addslashes($sZY).'\',lang=\''.$sLang.'\',detail=\''.addslashes($sDet).'\' where id = '.$nID;
    $gDB->query($sSql);
    if ($nBuild==1){
        $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
        $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
        bDet($nID);
    }
    else if ($nBuild==2){
        $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
        $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
        bIndex(1);
        bDet($nID);
    }
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("编辑成功");location.href="./arc.php?id='.$nID.'";</script>';
    exit();
}

function gs($sUrl){
    global $gDB;
    $aArr = array();
    if (preg_match('/hao123/i',$sUrl)){
        preg_match('/\/(\d+)\.htm/i',$sUrl,$arr);
        if (empty($arr[1])){
            preg_match('/^http:\/\/v\.hao123\.com.*?page=\d+&id=(\d+)$/i',$sUrl,$arr);
        }
        if ($arr[1] > 0){
            $sUrl = 'http://v.hao123.com/dianshi_intro/?dtype=tvPlayUrl&service=json&id='.$arr[1].'&callback=';
            $sStr = json_decode(iconv('gb2312','utf-8//ignore',gCurl($sUrl,'v.hao123.com')));
            if (!empty($sStr)){
                foreach ($sStr as $n => $a){
                    $info = (array)$a->site_info;
                    //echo '<br />'.$info['name'].'<br />';
                    //p($a->episodes);
                    foreach ($a->episodes as $b){
                        $b = (array)$b;
                        $b['url'] = preg_replace('/#from_baidu.*?/i','',$b['url']);
                        $aArr[$info['name']][intval($b['episode'])] = $b['url'];
                        //echo $b['url'].' | 第'.$b['episode'].'集<br />';
                    }
                }
            }
        }
    }
    elseif (preg_match('/v.360\.cn/i',$sUrl)) {
        $sStr = gCurl($sUrl,'v.360.cn');
        preg_match_all('/<li[^>]+site="([^"]+)"[^>]+><span[^>]+><a[^>]+><em>(.*?)<\/em><\/a><\/span><\/li>/i',$sStr,$a);

        if (empty($a[1])){
            preg_match_all('/<i[^>]+>播放：<\/i><a class="gico-([^"]+)"><em>(.*?)<\/em><\/a>/i',$sStr,$a);
        }
        if (!empty($a[1])){
            foreach ($a[1] as $k => $s){
                //echo '<br />'.$a[2][$k].'<br />';
                preg_match_all('/<a[^>]+sitename="'.$s.'"[^>]+href="([^"]+)">(\d+)集<\/a>/i',$sStr,$ar);
                if (!empty($ar[1])){
                    foreach ($ar[1] as $n => $ur){
                        $ur = preg_replace('/\?tpa=[a-z0-9]+/i','',$ur);
                        //echo $ur.' | 第'.$ar[2][$n].'集<br />';
                        $aArr[$a[2][$k]][intval($ar[2][$n])] = $ur;
                    }
                }
            }
        }
    }
    return $aArr;
}

$aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');

if (isset($_GET['id']) && $_GET['id'] > 0){
	$nID = intval($_GET['id']);
	$aArc = $gDB->selectOne(' select * from arc where id = '.$nID);
	$aDown= $gDB->select(' select * from down where aid = '.$nID.' order by id asc ');
}else{
    $sPre = '?cid='.$_GET['cid'].'&k='.$_GET['k'].'&ord='.$_GET['ord'].'&build='.$_GET['build'].'&ed='.$_GET['ed'].'&d='.$_GET['d'];

	$sWhere = ' where 1 ';
	if ($_GET['cid'] > 0){
		$sWhere .= ' and oid = '.intval($_GET['cid']);
	}
	elseif($_GET['cid'] == -1){
		$sWhere .= ' and oid = 0';
	}
    if($_GET['py'] == 1){
		$sWhere .= ' and py = 0 ';
	}
    elseif($_GET['py'] == 2){
		$sWhere .= ' and py = 1 ';
	}

    if($_GET['xz'] == 1){
		$sWhere .= ' and xz = 0 ';
	}
    elseif($_GET['xz'] == 2){
		$sWhere .= ' and xz > 0 ';
	}
	if (!empty($_GET['k'])){
		$sWhere .= ' and name like \'%'.trim($_GET['k']).'%\' ';
	}
    if (!empty($_GET['d'])){
		$sWhere .= ' and detail like \'%'.trim($_GET['d']).'%\' ';
	}
    if ($_GET['comm'] == 1){
		$sWhere .= ' and comm = 1 ';
	}
	if ($_GET['ed'] == 1){
		$sWhere .= ' and rtime > 0 ';
	}
	if ($_GET['ed'] == 2){
		$sWhere .= ' and rtime = 0 ';
	}
	if ($_GET['build'] == 1){
		$sWhere .= ' and build = 0 ';
	}
	if ($_GET['build'] == 2){
		$sWhere .= ' and build = 1 ';
	}
    if ($_GET['pic'] == 1){
		$sWhere .= ' and pic = \'\' ';
	}
    if (isset($_GET['status'])&&$_GET['status'] == '0'){
		$sWhere .= ' and status = 0 ';
	}
	$nPageList = 25; //每页显示多少条.
	
	$nCount = $gDB->getCount('select count(*) as count from arc '.$sWhere);
	$nCurr = intval($_GET['curr']);
	$nCurr = ($nCurr<=0||$nCurr>ceil($nCount/$nPageList))?1:$nCurr;
	$sPage = getPage($nCount, $nPageList, $nCurr, $sPre.'&comm='.$_GET['comm'].'&curr=%p%', '个剧集');

	$aList = $gDB->select(' select * from arc '.$sWhere.' order by '.(isset($_GET['ord'])&&!empty($_GET['ord'])?$_GET['ord']:'utime').' desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);
    if (!empty($aList)){
        foreach ($aList as $nKey => $aTem){
            $aXID[$aTem['xz']] = $aTem['xz'];
            if ($aTem['py'] == 1){
                $aPY[] = $aTem['id'];
            }
        }
        unset($aXID[0]);
        if (!empty($aXID)){
            $aXZ = $gDB->select(' select id,url from xiazai where id in ('.implode(',',$aXID).') ','id');
            !empty($aPY) && $aPY = $gDB->select(' select aid,url from play where aid in ('.implode(',',$aPY).') ','aid');
        }      
        
    }
    
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="s.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/i/j.js"></script>
<script language="JavaScript" type="text/javascript">
function ec(id,t){
    if (t==1){
        $.post('arc.php', {act:'down',id:id,name:$('#n'+id).val(),url:$('#u'+id).val()}, function(data){
                alert('编辑成功！');});
    }
    else {
        $.post('arc.php', {act:'down',id:id,name:$('#n'+id).val(),type:$('#t'+id).val(),det:$('#c'+id).val()}, function(data){
                alert('编辑成功！');});
    }
}
function sc(ob){
    $.post('./arc.php', {act:'sc','oid':ob.val(),id:ob.attr('i')},function(date){},'json');
}
$(function(){
	$('tr').hover(function(){$(this).addClass('c');},function(){$(this).removeClass('c');});
});
</script>
</head>

<body><form id="sdw" style="border:5px solid #BBB;padding:5px;background:#EEE;width:370px;color:#555;position:absolute;display:none;">
<strong>剧集名称：</strong><span></span><br /><input type="hidden" id="aid"  name="aid"/><input type="hidden" id="act"  name="act" value="gl"/>
<strong>下载地址：</strong><input type="text" id="url"  name="url" value="http://www.yyets.com/..." style="border:1px solid #999;width:280px;"/><br />
　　　　　 <input type="button" value="提交"onclick="l();"/> <input type="button" value="取消"onclick="$('#sdw').hide();"/>
</form>
<form id="sdw2" style="border:5px solid #BBB;padding:5px;background:#EEE;width:370px;color:#555;position:absolute;display:none;">
<strong>剧集名称：</strong><span></span><br /><input type="hidden" id="aid"  name="aid"/><input type="hidden" id="act"  name="act" value="gl2"/>
<strong>播放地址：</strong><input type="text" id="url"  name="url" value="http://v.hao123.com/..." style="border:1px solid #999;width:280px;"/><br />
　　　　　 <input type="button" value="提交"onclick="l2();"/> <input type="button" value="取消"onclick="$('#sdw2').hide();"/>
</form>
后台管理 >
<hr />
<?php if (isset($_GET['id']) && $_GET['id'] > 0){ ?>
<form action="" method="post" enctype="multipart/form-data" id="fo" style="display:nosne;">
<img src="/up/m/<?php echo $aArc['pic'] ?>" style="float:right;width:180px;height:240px;margin:0 5px 0 0;border:1px solid #000;padding:2px;background:#FFF;"/>
<input name="act" type="hidden" value="edit" />
编辑剧集 > <a href="" onclick="history.go(-1);return false;">返回上一页</a><br />
名称：
<input name="name" type="text" class="txt" id="name" value="<?php echo $aArc['name'] ?>"/>
<br />
主演：
<input name="zy" type="text" class="txt" id="zy" value="<?php echo $aArc['zy'] ?>"/>
<br />
分类： <select name="oid" id="oid">
    <option value="0">选择影片类型</option><?php
foreach ($aCla as $aTem){
	echo '<option value="'.$aTem['id'].'" '.($aArc['oid']==$aTem['id']?'selected':'').'>'.$aTem['name'].'片</option>';
}
$sCla .= '<select>';
?></select>
<br /> 
图片：
<input name="pic" type="file" id="pic" />
<br />
状态： <input name="status" type="radio" value="0" <?php echo $aArc['status']==0?'checked':'' ?>/>未发布 <input name="status" type="radio" value="1" <?php echo $aArc['status']==1?'checked':'' ?>/>已经发布 
<br />
推荐： <input name="comm" type="radio" value="1" <?php echo $aArc['comm']==1?'checked':'' ?>/>推荐 <input name="comm" type="radio" value="0" <?php echo $aArc['comm']==0?'checked':'' ?>/>不推荐 
<br />
浏览： <input name="view" type="text" id="lang" value="<?php echo $aArc['view'] ?>" style="width:60px"/> 人次
<br />
语言： <input name="lang" type="text" id="lang" value="<?php echo $aArc['lang'] ?>" />
<br />
年份： <input name="year" type="text" id="year" value="<?php echo $aArc['year'] ?>" />
<br />
剧情介绍：
<br />
<textarea name="detail" id="detail" style="width:800px;height:200px;"><?php echo $aArc['detail'] ?></textarea>
<input name="build" type="radio" value="0" checked/>不更新生成 
<input name="build" type="radio" value="1" />生成展示页 
<input name="build" type="radio" value="2" />更新编辑时间并生成静态及首页<br />
<input name="" type="submit" value=" 编  辑 "/>
</form>

<form action="" method="post" enctype="multipart/form-data" id="fo" style="display:nosne;">
<?PHP
$aDW = unserialize($aArc['down']);
foreach ($aDW as $nKey => $sTem){
    echo $sTem.'：<br />';
    foreach ($aDown as $n => $a){
        if ($a['oid']==$nKey){
            if (preg_match('/^(http|ftp)/i',$a['url'])){
                echo '名称：<input type="text" id="n'.$a['id'].'" style="width:70px;" value="'.$a['title'].'"/> 链接：<input type="text" id="u'.$a['id'].'" style="width:550px;" value="'.$a['url'].'"/>　<button onclick="ec('.$a['id'].',1)">编辑</button><br />';
            }
            else {
                echo '名称：<input type="text" id="n'.$a['id'].'" style="width:70px;" value="'.$a['title'].'"/> 类型：<input type="text" id="t'.$a['id'].'" style="width:60px;" value="'.$a['type'].'"/> 内容：<input type="text" id="c'.$a['id'].'" value="'.$a['det'].'" style="width:435px;" />　<button onclick="ec('.$a['id'].',2)">编辑</button><br />';
            }
        }
    }
    echo '--------------------------------------------------------------------------------------------------------------<br />';
}
?>
</form>
<?php }else { ?>
<form action="" method="get" id="f1" style="width:980px;">
分类：<select name="cid"><option value="0">选择栏目分类</option><option value="-1" style="color:#F00"<?php echo $_GET['cid']=='-1'?'selected':'' ?>>未区分</option><?php
$sCla = '<select onchange="sc($(this));"><option value="0">选择类型</option>';
foreach ($aCla as $aTem){
	echo '<option value="'.$aTem['id'].'" '.($_GET['cid']==$aTem['id']?'selected':'').'>'.$aTem['name'].'</option>';
    $sCla .= '<option value="'.$aTem['id'].'">'.$aTem['name'].'</option>';
}
$sCla .= '<select>';
?></select><select name="ed"><option value="0">所有编辑</option><option value="1"<?php echo $_GET['ed']==1?' selected':'' ?>>人工编辑</option><option value="2"<?php echo $_GET['ed']==2?' selected':'' ?>>未作编辑</option></select><select name="build"><option value="0">所有</option><option value="1"<?php echo $_GET['build']==1?' selected':'' ?>>未生成</option><option value="2"<?php echo $_GET['build']==2?' selected':'' ?>>已生成</option></select>
   <select name="py"><option value="0">所有播放</option><option value="1"<?php echo $_GET['py']==1?' selected':'' ?> style="color:#F00;">未关联播放</option><option value="2"<?php echo $_GET['py']==2?' selected':'' ?>>已关联播放</option></select>
    <select name="xz"><option value="0">所有关联</option><option value="1"<?php echo $_GET['xz']==1?' selected':'' ?> style="color:#F00;">未关联</option><option value="2"<?php echo $_GET['xz']==2?' selected':'' ?>>已关联</option></select>
  <input type="checkbox" name="pic" <?php echo $_GET['pic']==1?'checked':'' ?> value="1"/>无图 <input type="checkbox" name="comm" <?php echo $_GET['comm']==1?'checked':'' ?> value="1"/>推荐  <input type="checkbox" name="status" <?php echo $_GET['status']=='0'?'checked':'' ?> value="0"/>未发布 标题：<input name="k" type="text" class="txt" value="<?php echo $_GET['k'] ?>" style="width:90px;"/> 内容：<input name="d" type="text" class="txt" value="<?php echo $_GET['d'] ?>" style="width:90px;"/> <input name="" type="submit" value="搜索"/> <!-- <a href="" onclick="$(this).blur();$('#fo').slideToggle();return false;">点击添加</a> -->
</form>
<table width="1000" border="0" cellspacing="1">
    <tr>
        <th width="54">ID</th>
        <th width="267">名称</th>
        <th width="60">状态</th>
        <th width="105">分类</th>
        <th width="141"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=utime&',$sPre) ?>">更新时间</a></th>
        <th width="141"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=rtime&',$sPre) ?>">人工编辑</a></th>
		<th width="40">生成</th><th width="40">播放</th><th width="40">下载</th>
        <th width="58"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=view&',$sPre) ?>">浏览</a></th>
        <th width="74">操作</th>
    </tr>
	<?php
	if (empty($aList)){
		echo '<tr><td colspan="11">无相关剧集……</td></tr>';
	}
	else {
		foreach($aList as $aTem){
			echo '<tr><td><input name="" type="checkbox" value="'.$aTem['id'].'" class="cd"/>'.$aTem['id'].'</td><td class="l"><a href="/'.$aTem['id'].'" target="_blank">'.($aTem['comm']==1?'<span style="color:#F00;">[推荐]</span>':'').'<span id="s'.$aTem['id'].'">'.$aTem['name'].'</span></a></td><td>'.($aTem['status']==1?'发布':'<span style="color:#F00;">未发布</span>').'</td><td>'.str_replace(array('select','value="'.$aTem['oid'].'"'),array('select i="'.$aTem['id'].'"','value="'.$aTem['oid'].'" selected=""'),$sCla).'</td><td>'.date('Y-m-d H:i',$aTem['utime']).'</td><td>'.($aTem['rtime']>0?date('Y-m-d H:i',$aTem['rtime']):'-').'</td><td>'.$aTem['build'].'</td><td><a href="" onclick="return gl2('.$aTem['id'].',$(this),\''.$aPY[$aTem['id']]['url'].'\');">'.($aTem['py']>0?'<strong style="color:#F00">Y</strong>':'n').'</a> <a href="http://v.hao123.com/v?word='.$aTem['name'].'&fr=video" target="_blank">></a></td><td><a href="" onclick="return gl('.$aTem['id'].',$(this),\''.$aXZ[$aTem['xz']]['url'].'\');">'.($aTem['xz']>0?$aTem['xz']:'关联').'</a></td><td>'.$aTem['view'].'</td><td><a href="./arc.php?id='.$aTem['id'].'">编辑</a> <a href="./arc.php?upid='.$aTem['id'].'" onclick="if(!confirm(\'需要手动去采集剧集信息？\')){return false;}">更新</a></td></tr>';
		}
		echo '<tr><td colspan="11" id="pg"><span style="float:left;"><a href="" onclick="$(\'.cd\').attr(\'checked\',true);return false;">全选</a><a href=""onclick="$(\'.cd\').click();return false;">反选</a><input name="" type="button" value="更新并生成" onclick="g()"/></span>'.$sPage.'</td></tr>';
	}
	?>
</table>
<script type="text/javascript">
var oo;
function gl(id,o,u){
    oo = o;
    $('#sdw2').hide();
    $('#sdw').show();
    $('#sdw #aid').val(id);
    $('#sdw span').text($('#s'+id).text());
    $('#sdw #url').val(u==''?'http://www.yyets.com/...':u).select();
    $('#sdw').css('margin',o.offset().top+'px 0 0 '+(o.offset().left-$('#sdw').outerWidth())+'px');
    return false;
}
function gl2(id,o,u){
    oo = o;
    $('#sdw').hide();
    $('#sdw2').show();
    $('#sdw2 #aid').val(id);
    $('#sdw2 span').html('<a href="http://v.hao123.com/v?word='+$('#s'+id).text()+'&fr=video" target="_blank">'+$('#s'+id).text()+'</a>');
    $('#sdw2 #url').val(u==''?'http://v.hao123.com/...':u).select();
    $('#sdw2').css('margin',o.offset().top+'px 0 0 '+(o.offset().left-$('#sdw').outerWidth())+'px');
    return false;
}
function l2(){
    $('#sdw2 > #url').val($('#sdw2 > #url').val().replace(/v\.baidu\.com/i,'v.hao123.com'));
    if (!/^http:\/\/v\.(hao123|360)/i.test($('#sdw2 > #url').val())){
        alert('暂时只支持v.hao123.com/v.360.cn的地址，请填写正确的关联播放地址！');
        $('#sdw2 > #url').focus();
        return false;
    }
    $.post('arc.php', $('#sdw2').serialize(), 
        function(data){
            if (data[0] == 1){
                $('#sdw2').hide();
                alert('关联成功！');
                oo.html(data[1]);
            }
            else {
                alert(data[1]);
            }
            
    },'json');
}
function l(){
    if (!/^http:\/\/www\.yyets\.com\/[\w\/\.]{10,}/i.test($('#sdw > #url').val())){
        alert('请填写正确的关联下载地址！');
        $('#sdw > #url').focus();
        return false;
    }
    $.post('arc.php', $('#sdw').serialize(), 
        function(data){
            if (data[0] == 1){
                $('#sdw').hide();
                alert('关联成功！');
                oo.text(data[1]);
            }
            else {
                alert(data[1]);
            }
            
    },'json');
}
function g(){
	if($('.cd:checked').length<=0)
	{alert('请选择要更新的剧集');}
	else{
		var a = [];
		$('.cd:checked').each(function(i){
			a[i] = $(this).val();
		});
		$.post('arc.php', {act:'update',aid:a.join(',')}, function(data){
                alert("更新成功");location.reload();});
	}
}
</script>
<?php } ?>
</body>
</html>
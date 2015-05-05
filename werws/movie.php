<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');
$sChar = 'utf-8'; 

$nCID = 2;
$sPre = '?';
$nPageList = 25; //每页显示多少条.

/*$aList = $gDB->select(' select id,dy,yy from movie ');
if (!empty($aList)){ 
    foreach($aList as $a){
        $sSql = ' update movie set dy = \''.addslashes(preg_replace('/[ |\t]+/',',',$a['dy'])).'\',yy=\''.addslashes(preg_replace('/[ |\t]+/',',',$a['yy'])).'\' where id = '.$a['id'];
        $gDB->query($sSql);
    }
}*/

if ($_POST['act'] == 'edit'){
    $sSet = '';
    if (!empty($_FILES['pic']['tmp_name'])){
        include('../inc/pic.php');
        $pic = new cPic();
        $sDir = $_SERVER['DOCUMENT_ROOT'].'/u/'.($nCID==1?'t':'m').'/';

		$aPic = $pic->up_pic('pic', $sDir);

		if (!empty($aPic)) {
            $sPic = $aPic[0].'.'.$aPic[1];
            $sNP  = intval($_GET['id']).'.'.$aPic[1];
			$pic->createthumb($sDir.'/'.$sPic, $sDir.'/'.$sNP, 180, 240,4);
            if (file_exists($sDir.$sNP)){
                $sSet = 'npic=\''.'/u/'.($nCID==1?'t':'m').'/'.$sNP.'\',';
            }
        }
    }
    $sSql = ' update movie set name=\''.addslashes(trim($_POST['name'])).'\','.$sSet.'name2=\''.addslashes(trim($_POST['name2'])).'\',k=\''.addslashes(trim($_POST['k'])).'\',addr=\''.addslashes(trim($_POST['addr'])).'\',ji=\''.addslashes(trim($_POST['ji'])).'\',fabu=\''.intval($_POST['fabu']).'\',fn=\''.trim($_POST['fn']).'\',view=\''.intval($_POST['view']).'\',year=\''.addslashes(trim($_POST['year'])).'\',`long`=\''.addslashes(trim($_POST['long'])).'\',fen=\''.(empty($_POST['fen'])?'0':floatval($_POST['fen'])).'\',txt=\''.addslashes(trim($_POST['txt'])).'\',etime=\''.time().'\' where id = '.$_GET['id'];

    if ($gDB->query($sSql)){
        bMV($_GET['id'],2);
        $sTS = '编辑成功！';
    }
}


if (isset($_GET['id']) && $_GET['id'] > 0){
    $nID = intval($_GET['id']);
    $aArc = $gDB->selectOne(" select * from movie where id = ".$nID);
}
else {
    $sPre = '?ord='.trim($_GET['ord']).'&';
    $sWhere = ' where xid=-1 and cid = '.$nCID.' ';
    if (!empty($_GET['name'])){
        $sPre .= 'name='.trim($_GET['name']).'&';
        $sWhere .= ' and name like \'%'.addslashes(trim($_GET['name'])).'%\' ';
    }
    if ($_GET['fabu']==2){
        $sPre .= 'fabu=2&';
        $sWhere .= ' and fabu = 1';
    }
    elseif ($_GET['fabu']==1){
        $sPre .= 'fabu=1&';
        $sWhere .= ' and fabu = 0 ';
    }
    if ($_GET['etime']==2){
        $sPre .= 'etime=2&';
        $sWhere .= ' and etime > 0';
    }
    elseif ($_GET['etime']==1){
        $sPre .= 'etime=1&';
        $sWhere .= ' and etime = 0 ';
    }
    if ($_GET['status']==2){
        $sPre .= 'status=2&';
        $sWhere .= ' and status = 1';
    }
    elseif ($_GET['status']==1){
        $sPre .= 'status=1&';
        $sWhere .= ' and status = 0 ';
    }
    if (isset($_GET['ord']) && !empty($_GET['ord'])){
        $ord .= ' order by '.trim($_GET['ord']).' desc ';
    }
    else {
        $ord .= ' order by id asc ';
    }

    $nCount = $gDB->getCount('select count(*) as count from movie '.$sWhere);
    $nCurr = intval($_GET['curr']);
    $nCurr = ($nCurr<=0||$nCurr>ceil($nCount/$nPageList))?1:$nCurr;
    $sPage = getPage($nCount, $nPageList, $nCurr, $sPre.'curr=%p%', '部电视');
    $aList = $gDB->select(' select * from movie '.$sWhere.$ord.' limit '. ($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="s.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/i/j.js"></script>
<script language="JavaScript" type="text/javascript">
<?php echo empty($sTS)?'':'alert(\''.$sTS.'\');
'; ?>
$(function(){
    if ($('#fn').length > 0){
        setInterval('cku(<?php echo $_GET['id']; ?>,2,$(\'#fn\').val())',500);
    }
});
</script>
</head>

<body>后台管理 > 电视列表管理
<hr />
<?php if (isset($_GET['id']) && $_GET['id'] > 0){ ?>
<form action="" method="post" enctype="multipart/form-data" id="fo" style="display:nosne;">
<img src="<?php echo $aArc['npic'].'?'.time(); ?>" style="float:right;width:180px;height:240px;margin:0 5px 0 0;border:1px solid #000;padding:2px;background:#FFF;"/>
<input name="act" type="hidden" value="edit" />
编辑剧集 > <a href="" onclick="history.go(-1);return false;">返回上一页</a><br />
片名： <input name="name" type="text" id="name" value="<?php echo $aArc['name'] ?>"/><br />
别名： <input name="name2" type="text" id="name2" value="<?php echo $aArc['name2'] ?>"/><br />
导演： <?php echo $aArc['dy'] ?><!-- <input name="dy" type="text" id="dy" value="<?php echo $aArc['dy'] ?>"/> --><br />
主演： <?php echo $aArc['yy'] ?><!-- <input name="yy" type="text" id="yy" value="<?php echo $aArc['yy'] ?>" style="width:300px;" /> --><br />
标签： <input name="k" type="text" id="k" value="<?php echo $aArc['k'] ?>" style="width:300px;" /><br />
地区： <input name="addr" type="text" id="addr" value="<?php echo $aArc['addr'] ?>"/><br />

<?php
if ($aArc['cid'] == 1){
    echo '集数： <input name="ji" type="text" id="ji" value="'.$aArc['ji'].'"/><br />';
}
else {
    echo '年份： <input name="year" type="text" id="year" value="'.$aArc['year'].'"/><br />
时长： <input name="long" type="text" id="long" value="'.$aArc['long'].'"/><br />
评分： <input name="fen" type="text" id="fen" value="'.$aArc['fen'].'"/><br />';
}
?>
图片：
<input name="pic" type="file" id="pic" />
<br />
状态： <input name="fabu" type="radio" value="0" <?php echo $aArc['fabu']==0?'checked':'' ?>/>未发布 <input name="fabu" type="radio" value="1" <?php echo $aArc['fabu']==1?'checked':''; echo $aArc['status']==1?'':'disabled="disabled" title="未通过审核"' ?>/>已发布 
<br />
浏览： <input name="view" type="text" id="view" value="<?php echo $aArc['view'] ?>" style="width:60px"/> 人次
<br />
地址：/<input name="fn" type="text" id="fn" value="<?php echo $aArc['fn'] ?>"/> <s id="fns"></s><br />
<br />
剧情：
<br />
<textarea name="txt" id="txt" style="width:870px;height:100px;"><?php echo $aArc['txt'] ?></textarea><br />
<input name="" type="submit" value=" 编  辑 " onclick="if($('#fn').val()==''||$('#fns').hasClass('c')){alert('地址不可用，请选择其它再提交！');$('#fn').focus();return false;}"/>
</form>
<?php }else { ?>
<form action="" method="get" id="f1" style="width:980px;">
片名：<input type="text" id="name"  name="name" value="<?php echo $_GET['name']; ?>" />
状态：<select id="fabu" name="fabu">
        <option value="0">已/未发</option>
        <option value="1"<?php echo $_GET['fabu']=='1'?'selected':'' ?> style="color:#F00;">未发布</option>
        <option value="2"<?php echo $_GET['fabu']=='2'?'selected':'' ?> style="color:green;">已发布</option>
    </select>
编辑：<select id="etime" name="etime">
        <option value="0">已/未编辑</option>
        <option value="1"<?php echo $_GET['etime']=='1'?'selected':'' ?> style="color:#F00;">未编辑</option>
        <option value="2"<?php echo $_GET['etime']=='2'?'selected':'' ?> style="color:green;">已编辑</option>
    </select>
审核：<select id="status" name="status">
        <option value="0">已/未审核</option>
        <option value="1"<?php echo $_GET['status']=='1'?'selected':'' ?> style="color:#F00;">未通过</option>
        <option value="2"<?php echo $_GET['status']=='2'?'selected':'' ?> style="color:green;">已通过</option>
    </select>
<input type="submit" value="搜索" />
</form>
<table width="1000" border="0" cellspacing="1">
    <tr>
        <th width="54"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=id&',$sPre) ?>">ID</a></th>
        <th width="300">片名/别名</th>
        <th width="50">状态</th><th width="50">审核</th>
        <th width="120"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=etime&',$sPre) ?>">最后编辑</a></th>
        <th width="120">集数</th>
        <th width="105">导演</th>
        <th width="60">年份</th>
        <th width="60">时长</th>
        <th width="80">地点</th>
        <th width="50">操作</th>
    </tr>
	<?php
	if (empty($aList)){
		echo '<tr><td colspan="10">无相关演员信息……</td></tr>';
	}
	else {
		foreach($aList as $aTem){
			echo '<tr><td>'.$aTem['id'].'</td><td class="l">'.($aTem['fabu']==1?'<a href="/'.$aTem['fn'].'" target="_blank">'.$aTem['name'].(empty($aTem['name2'])?'':' / '.$aTem['name2']).'</a>':($aTem['name'].(empty($aTem['name2'])?'':' / '.$aTem['name2']))).'</span></a> <a href="http://v.baidu.com/'.($aTem['cid']==1?'tv':'movie').'/'.$aTem['oid'].'.htm" style="color:green;" target="_blank">原址</a></td><td>'.($aTem['fabu']==0?'<s style="color:#F00;">未发</s>':'<s style="color:green;">已发</s>').'</td><td>'.($aTem['status']==1?'<s style="color:green;">通过</s>':'<s style="color:#F00;">未过</s>').'</td><td>'.($aTem['etime']>0?date('Y-m-d',$aTem['etime']):'-').'</td><td>'.$aTem['ji'].'</td><td>'.$aTem['dy'].'</td><td>'.$aTem['year'].'</td><td>'.$aTem['long'].'</td><td>'.$aTem['addr'].'</td><td><a href="?id='.$aTem['id'].'">编辑</a></td></tr>';
		}
		echo '<tr><td colspan="11" id="pg">'.$sPage.'</td></tr>';
	}
	?>
</table>
<?php } ?>
</body>
</html>
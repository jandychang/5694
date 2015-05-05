<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');

if ($_POST['act'] == 'edit'){
	$nID = intval($_POST['id']);
	$nStatus = intval($_POST['status']);
	$sName= trim($_POST['name']);
	$sTit= trim($_POST['title']);
	$sKey= trim($_POST['key']);
	$sKW = trim($_POST['kw']);
	$sDes= trim($_POST['des']);
	$sPY = trim($_POST['url']);
	$sSql = ' update cla set status='.$nStatus.',name=\''.$sName.'\',title=\''.$sTit.'\',keyword=\''.$sKey.'\',des=\''.$sDes.'\',pinyin=\''.$sPY.'\',kw=\''.$sKW.'\' where id = '.$nID;
	$gDB->query($sSql);
	echo json_encode(array(1));
	exit();
}
elseif ($_POST['act'] == 'add'){
	$nID = intval($_POST['id']);
	$nStatus = intval($_POST['status']);
	$sName= trim($_POST['name']);
	$sTit= trim($_POST['title']);
	$sKey= trim($_POST['key']);
	$sKW = trim($_POST['kw']);
	$sDes= trim($_POST['des']);
	$sPY = trim($_POST['url']);
	$sSql = ' insert into cla(id,status,name,title,keyword,des,pinyin,kw) values(NULL,'.$nStatus.', \''.$sName.'\', \''.$sTit.'\', \''.$sKey.'\', \''.$sDes.'\', \''.$sPY.'\', \''.$sKW.'\')';
	//$sSql = ' update cla set status='.$nStatus.',name=\''.$sName.'\',title=\''.$sTit.'\',keyword=\''.$sKey.'\',des=\''.$sDes.'\',pinyin=\''.$sPY.'\' where id = '.$nID;
	$gDB->query($sSql);
	echo json_encode(array(1));
	exit();
}

$nID = intval($_GET['id']);
if ($nID <= 1){
	$aInfo = $gDB->selectOne(' select * from cla where id = '.$nID);
}
else {
	$aList = $gDB->select(' select * from cla where ID > 1 ');
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="s.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/i/j.js"></script>
<script type="text/javascript">
function actCla(obj){
	if (obj.parent().children('input[name="name"]').length>0 && obj.parent().children('input[name="name"]').val() == ''){
		alert('name不能为空');
		obj.parent().children('input[name="name"]').focus();
		return false;
	}
	if (obj.parent().children('input[name="url"]').length>0 && obj.parent().children('input[name="url"]').val() == ''){
		alert('url不能为空');
		obj.parent().children('input[name="url"]').focus();
		return false;
	}
	if (obj.parent().children('input[name="title"]').val() == ''){
		alert('title不能为空');
		obj.parent().children('input[name="title"]').focus();
		return false;
	}
	
	$.post('cla.php', obj.parents('form').serialize(), function(data){
			if(data[0] == 1){
				if (obj.parent().children('input[name="act"]').val()=='add'){
					alert('添加成功');
					location.reload();
				}
				else{
					alert('编辑成功');
				}
			}
	},'json');
}
$(function(){
	$('.ls a').hover(function(){
		$(this).parent().children('div').slideToggle(function(){if($(this).css('display')=='block'){$(this).parent().children('a').text('<<收缩');}else{$(this).parent().children('a').text('<<展开');}});
	},function(){
	
	});
});
</script>
</head>

<body>
后台管理 > 
<hr />
<?php if ($nID <= 1){ ?>
<form class="s"><input name="act" type="hidden" value="edit" />
	<input name="id" type="hidden" value="1" /><input name="status" type="hidden" value="1" />
	<input name="url" type="hidden" value="index" />
	<b>name</b><input name="name" type="text" value="<?php echo $aInfo['name'] ?>" class="w100"/><br />
	<b>title</b><input name="title" type="text" value="<?php echo $aInfo['title'] ?>"/><br />
	<b>keyword</b><input name="key" type="text" value="<?php echo $aInfo['keyword'] ?>"/><br />
	<b>description</b><input name="des" type="text" class="w700" value="<?php echo $aInfo['des'] ?>"/><br />
	<b>导航下链接</b><textarea name="kw" style="width:650px;height:60px;"><?php echo $aInfo['kw'] ?></textarea>
	<br />
	<b></b><input name="" type="button" value="编辑" class="w80" onclick="actCla($(this))"/>
</form>
<?php }else{
if (!empty($aList)){
	foreach ($aList as $aInfo){
?>
<form class="s ls"><input name="act" type="hidden" value="edit" /><a href="" style="float:right;" onclick="return false;"><<展开</a>
	<input name="id" type="hidden" value="<?php echo $aInfo['id'] ?>" /><input name="status" type="hidden" value="1" />
	<b>name</b><input name="name" type="text" value="<?php echo $aInfo['name'] ?>" class="w100"/>
	<b class="r">url</b><input name="url" type="text" value="<?php echo $aInfo['pinyin'] ?>" class="w100"/>
	<input name="" type="button" value="编辑" class="w80" onclick="actCla($(this))"/><div>
	<b>title</b><input name="title" type="text" value="<?php echo $aInfo['title'] ?>"/><br />
	<b>keyword</b><input name="key" type="text" value="<?php echo $aInfo['keyword'] ?>"/><br />
	<b>description</b><input name="des" type="text" class="w700" value="<?php echo $aInfo['des'] ?>"/>
	<b>导航下链接</b><textarea name="kw" style="width:650px;height:60px;"><?php echo $aInfo['kw'] ?></textarea>
	</div>
</form>
<?php }} ?>

<form class="s ls lss "><input name="act" type="hidden" value="add" />添加新的栏目分类：<br />
	<input name="id" type="hidden" value="<?php echo $aInfo['id'] ?>" /><input name="status" type="hidden" value="1" />
	<b>name</b><input name="name" type="text" value="" class="w100"/>
	<b class="r">url</b><input name="url" type="text" value="" class="w100"/>
	<input name="" type="button" value="添加" class="w80" onclick="actCla($(this))"/><br />
	<b>title</b><input name="title" type="text" value=""/><br />
	<b>keyword</b><input name="key" type="text" value=""/><br />
	<b>Description</b><input name="des" type="text" class="w700" value=""/><br />
	<b>导航下链接</b><textarea name="kw" style="width:650px;height:60px;"></textarea>
</form>



<?php } ?>
</body>
</html>

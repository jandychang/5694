<?php
include ('./top.php');
if ($_POST['act'] == 'edit'){
	$nID = intval($_POST['id']);
    $nCID= intval($_POST['cid']);
	$nStatus = intval($_POST['status']);
	$sName= trim($_POST['name']);
	$sTit= trim($_POST['title']);
	$sKey= trim($_POST['key']);
	$sKW = trim($_POST['kw']);
	$sDes= trim($_POST['des']);
	$sPY = trim($_POST['url']);
    $sMB = trim($_POST['mb']);
    $nHide= intval($_POST['hide']);

        $sSql = ' update clas set cid='.$nCID.',status='.$nStatus.',hide='.$nHide.',name=\''.$sName.'\',title=\''.$sTit.'\',keyword=\''.$sKey.'\',des=\''.$sDes.'\',url=\''.$sPY.'\',mb=\''.$sMB.'\',kw=\''.$sKW.'\' where id = '.$nID;
        $gDB->query($sSql);
        echo json_encode(array(1));

	exit();
}
elseif ($_POST['act'] == 'add'){
	$nID = intval($_POST['id']);
    $nCID= intval($_POST['cid']);
	$nStatus = intval($_POST['status']);
	$sName= trim($_POST['name']);
	$sTit= trim($_POST['title']);
	$sKey= trim($_POST['key']);
	$sKW = trim($_POST['kw']);
	$sDes= trim($_POST['des']);
	$sPY = trim($_POST['url']);
    $sMB = trim($_POST['mb']);
    $nHide= intval($_POST['hide']);

        $sSql = ' insert into clas(id,cid,status,hide,name,title,keyword,des,url,mb,kw) values(NULL,'.$nCID.','.$nStatus.','.$nHide.',\''.$sName.'\', \''.$sTit.'\', \''.$sKey.'\', \''.$sDes.'\', \''.$sPY.'\', \''.$sMB.'\', \''.$sKW.'\')';
        //$sSql = ' update cla set status='.$nStatus.',name=\''.$sName.'\',title=\''.$sTit.'\',keyword=\''.$sKey.'\',des=\''.$sDes.'\',pinyin=\''.$sPY.'\' where id = '.$nID;
        $gDB->query($sSql);
        echo json_encode(array(1));

	exit();
}


$_GET['pos'] = $_GET['pos']>1?$_GET['pos']:1;
if ($_SESSION['group'] != 1 && $_SESSION['tid']<=0){
    $_GET['pos'] = 1;
}else if($_SESSION['group'] != 1) {
    $_GET['pos'] = $_SESSION['tid'];    
}

$aList = $gDB->select(' select * from clas '.($_GET['pos']>0?'where cid = '.$_GET['pos']:'').' order by id asc');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript" src="j/jq.js"></script>
<script type="text/javascript">
function actCla(obj){
	if (obj.find('input[name="name"]').length>0 && obj.find('input[name="name"]').val() == ''){
		alert('name不能为空');
		obj.find('input[name="name"]').focus();
		return false;
	}
	
	$.post('clas.php', obj.serialize(), function(data){
			if(data[0] == 1){
				if (obj.find('input[name="act"]').val()=='add'){
					alert('添加成功');
					location.reload();
				}
				else{
					alert('编辑成功');
				}
			}
            else {
                alert(data[1]);
            }
	},'json');
}
$(function(){
	$('.s input[type="text"]').focus(function(){
        $(this).addClass('c');
    }).blur(function(){$(this).removeClass('c');});;
});
</script>
<link href="./c.css" rel="stylesheet" type="text/css" />
<style>
.s td{padding:3px;}
.s b{display:inline-block;width:80px;margin:5px 10px 0 0;text-align:right;}
.s input{border:1px solid #EEE;width:400px;color:#444;}
.s input.c{border:1px solid #F60;color:#000;}
.s input.w80{width:80px;line-height:16px;}
.s input.w40{width:40px;}
.s input.w700{width:700px;}
.s input.w100{width:100px;}
.s input.w250{width:250px;}

input[type="text"], select,textarea{
border: 1px solid #EEE;
}
</style>
</head>

<body>
系统后台管理 > 文章栏目管理
<hr />
<?php
    if ($_SESSION['group'] == 1){
        echo '<p id="me">';
        foreach ($aType as $c => $s){
            echo '<a href="?pos='.$c.'"'.($_GET['pos']==$c?'class="c"':'').'>'.$s.'</a>';
        }echo '</p>';
    }
    echo '<table cellspacing="0" width="100%">
    <tr>
        <th>名称(name)</th>
        <th><div style="width:500px"></div></th><th>隐藏(暂不收文章)</th>
        <th>操作</th>
    </tr>';
    if (!empty($aList)){
        foreach ($aList as $aInfo){
            echo '<tr><form class="s"><input name="act" type="hidden" value="edit" /><input name="id" type="hidden" value="'.$aInfo['id'].'" /><input name="status" type="hidden" value="1" /><input name="cid" type="hidden" value="'.$aInfo['cid'].'" /><th><input name="name" type="text" value="'.$aInfo['name'].'" class="w100"/></th><td claspan="6"></td><td><input type="checkbox" id="hide"  name="hide" value="1"'.($aInfo['hide']==1?'checked="checked"':'').' style="width:auto;"/></td><td><input name="" type="button" value="编辑" class="w40" onclick="actCla($(this).parents(\'form\'))"/></td>
            </tr>
            <tr><td colspan="4" style="border-bottom:2px solid #f60"><textarea id="kw" name="kw" style="width:98%;height:60px;border:1px solid #EEE;">'.$aInfo['kw'].'</textarea></td></form></tr>';
        }
    }else {
        echo '<tr><td colspan="4" style="border-bottom:2px solid #f60">暂无相关分类</td></form></tr>';
    }
    if ($_GET['pos']==1 || $_SESSION['group'] == 1){
        echo '<tr><form class="s"><input name="act" type="hidden" value="add" /><input name="id" type="hidden" value="" />
            <input name="status" type="hidden" value="1" /><input name="cid" type="hidden" value="'.$_GET['pos'].'" />
            <td><input name="name" type="text" value="" class="w100"/></td>
            <td><div style="width:500px"></div></td>
            <td><input type="checkbox" id="hide" value="1" name="hide" style="width:auto;"/></td>
            <td><input name="" type="button" value="添加" class="w40" onclick="actCla($(this).parents(\'form\'))"/></td></tr>
        <tr><td colspan="4" style="border-bottom:10px solid #6CB6F2"><textarea id="kw" name="kw" style="width:98%;height:60px;border:1px solid #EEE;"></textarea></td>
        </tr>';
    }
    echo '</table>';
?>
</body>
</html>

<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');

$sAct = trim($_POST['act']);
if ($sAct == 'add'){
    $nCID   = intval($_POST['cid']);
	$sName = trim($_POST['name']);
	$sLink = trim($_POST['link']);
	$nOrd  = intval($_POST['ord']);
	if (!empty($sName) && !empty($sLink)){
		$sSql = ' insert into link(id,cid,ord,status,name,link)values(NULL,'.$nCID.','.$nOrd.',1,\''.$sName.'\',\''.$sLink.'\') ';
		$gDB->query($sSql);
	}
}
elseif ($sAct == 'edit'){
	$sName = trim($_POST['name']);
	$sLink = trim($_POST['link']);
	$nID   = intval($_POST['id']);
    $nCID   = intval($_POST['cid']);
	$nOrd  = intval($_POST['ord']);
	$nSta  = intval($_POST['status']);
	$sSql = ' update link set cid='.$nCID.',ord = \''.$nOrd .'\', status = \''.$nSta .'\', name = \''.$sName .'\', link = \''.$sLink .'\' where id = '.$nID;
	$gDB->query($sSql);
}
elseif ($_GET['del'] > 0){
	$gDB->query(' delete from link where id = '.$_GET['del']);
}

$aPG = array(1=>'首页','内页');

$nID  = intval($_GET['id']);
$sSql = ' select * from link order by status desc,ord asc,id asc ';
$aLink = $gDB->select($sSql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript" src="/i/j.js"></script>
<link href="s.css" rel="stylesheet" type="text/css" />
<style>
.n,.n input,.n select{background:#CCC;}
form{margin:0 0 1px;}
</style>
</head>

<body style="width:auto;">
后台管理 > 
<hr />
添加友情链接<br />
<form action="" method="post"><input name="act" type="hidden" value="add" />
位置：<select id="cid" name="cid"><?php
        foreach ($aPG as $n => $s){
            echo '<option value="'.$n.'">'.$s.'</option>';
        }
    ?>
</select>
网站名称：<input name="name" type="text" id="name" /> 
链接：<input name="link" type="text" id="link" value="http://"/> 
顺序：<input type="text" name="ord" value="1" size="3" maxlength="3" /> 
<input name="" type="submit" value="添加"/></form>
<br /><br />
已有友情链接：
<?php
foreach ($aPG as $c => $s){
    echo '<a href="" onclick="$(\'.dssd\').hide();$(\'#d'.$c.'\').show();return false;">'.$s.'</a> ';
}
echo '<br />';
if (!empty($aLink)) {
   
	foreach ($aLink as $n => $aTem){
		 $a[$aTem['cid']] .= '<form action="" method="post"'.($aTem['status']==0?' class="n" style="width:870px;"':'').'><input name="act" type="hidden" value="edit" /><input name="id" type="hidden" value="'.$aTem['id'].'" />
'.(count($a[$aTem['cid']])+1).' : 位置：<select id="cid" name="cid">';
        
        foreach ($aPG as $c => $s){
            $a[$aTem['cid']] .= '<option value="'.$c.'" '.($c==$aTem['cid']?'selected="selected"':'').'>'.$s.'</option>';
        }
        
        $a[$aTem['cid']] .= '</select> 网站名称：<input name="name" type="text" id="name" value="'.$aTem['name'].'"/> 
链接：<input name="link" type="text" id="link" value="'.$aTem['link'].'"/> 
顺序：<input type="text" name="ord" value="'.$aTem['ord'].'" size="3" maxlength="3" /> 状态：<input type="text" name="status" value="'.$aTem['status'].'" size="3" maxlength="3" /> 
<input name="" type="submit" value="编辑"/> <input name="" type="submit" value="删除" onclick="if(confirm(\'你确定要删除此链接？\')){location.href=\'link.php?del='.$aTem['id'].'\'}return false;"/> </form>';
	}
    foreach ($aPG as $c => $s){
        echo '<div id="d'.$c.'" class="dssd" '.($c==1?'':'style="display:none;"').'>'.$a[$c].'</div> ';
    }
}
else {
	echo '无...';
}

?>
</body>
</html>
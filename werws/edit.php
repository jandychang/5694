<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������ر༭</title>
<style>
body{font-size:13px;}
a{text-decoration:none;color:#06c}
a:hover{color:#F60; text-decoration:underline;}
dl,dt,dd{margin:0;padding:0;}
dl{border:1px solid #999;padding:1px;margin-bottom:5px;}
dt{padding:5px;background:#DDD;}
dd a{display:block;height:20px;line-height:20px;text-indent:10px;border-bottom:1px solid #EEE;}
.comm input[type="text"]{border:1px solid #999;width:400px;}
.comm strong{display:inline-block;width:100px;text-align:right;}
</style>
<?php
include ('../inc/conf.php');
$nID = intval($_GET['id']);

if ($_GET['act'] == 'bd'){
    $aInfo = $gDB->selectOne(' select * from city where id = '.$nID);
    gK($aInfo);
    echo '<script type="text/javascript">alert("��ȡ�ɹ���");location.href="edit.php?id='.$nID.'";</script>';
    exit();
}
elseif ($_POST['act'] == 'edit'){
	$aCity['city'] = trim($_POST['city']);
	$aCity['pai'] = trim($_POST['pai']);
	$aCity['path'] = trim($_POST['path']);
	$aCity['title'] = trim($_POST['title']);
	$aCity['keywords'] = trim($_POST['keywords']);
	$aCity['desc'] = trim($_POST['desc']);
	$aCity['t1'] = trim($_POST['t1']);
	$aCity['t2'] = trim($_POST['t2']);
	$aCity['t4'] = trim($_POST['t4']);
	$aCity['t3'] = trim($_POST['t3']);
	$aCity['t6'] = trim($_POST['t6']);
	$aCity['t5'] = trim($_POST['t5']);
	$aCity['t7'] = trim($_POST['t7']);
	$aCity['t9'] = trim($_POST['t9']);
	$aCity['t8'] = trim($_POST['t8']);
	$aCity['t10'] = trim($_POST['t10']);
	foreach ($aCity as $n => $v){
		$aCity[$n] = ($v);
	}
	$sSql = " update city set city='".$aCity['city']."', ourl='".$aCity['path']."', url='".$aCity['path']."', title='".$aCity['title']."', pai='".$aCity['pai']."', `key`='".$aCity['keywords']."', `dec`='".$aCity['desc']."',`t1` = '".$aCity['t1']."',`t2` = '".$aCity['t2']."',`t3` = '".$aCity['t3']."',`t4` = '".$aCity['t4']."',`t5` = '".$aCity['t5']."',`t6` = '".$aCity['t6']."',`t7` = '".$aCity['t7']."',`t8` = '".$aCity['t8']."',`t9` = '".$aCity['t9']."',`t10` = '".$aCity['t10']."' WHERE id = ".$nID;
	$gDB->query($sSql);
	echo '<script>alert("�༭�ɹ���");</script>';
}

$aInfo = $gDB->selectOne(' select * from city where id = '.$nID);
$aProv = $gDB->select(' select id,name,url from province where oid = 0 order by id asc ','id');
?>
</head>

<body>
<iframe src="./gkey.php?city=<?php echo $aInfo['city'] ?>&key=<?php echo $aInfo['city'] ?> Υ��" scrolling="no" frameborder="0" style="position:absolute;width:420px;margin:0 0 0 780px;height:600px;"></iframe>
<form action="" method="post"><input name="act" type="hidden" value="edit" />
<div class="comm">
<strong>���У�</strong><input name="city" type="text" id="city" value="<?php echo $aInfo['city'] ?>" style="width:130px;"/><br />
<strong>���ƣ�</strong><input name="pai" type="text" id="pai" value="<?php echo $aInfo['pai'] ?>" style="width:130px;"/>
<br />
<strong>����·����</strong>/<?php echo $aProv[$aInfo['nid']]['url'] ?>/<?php if(!in_array($nID,array(5,22,13,14)))
echo '<input name="path" type="text" id="path" value="'.$aInfo['ourl'].'" style="width:150px;"/>/' ?>index.html
<br />
<strong>���⣺</strong><input name="title" type="text" id="title" value="<?php echo $aInfo['title'] ?>"/> <a href="edit.php?id=<?php echo $nID ?>&act=bd" onclick="if(!confirm('ȷ��Ҫ���»�ȡ�ؼ��ָ������еģ�')){return false;}else{return true;}">�Զ���ȡ�ٶȹؼ���</a>
<br />
<strong>keywords��</strong><input name="keywords" type="text" id="keywords" value="<?php echo $aInfo['key'] ?>"/>
<br />
<strong>description��</strong><input name="desc" type="text" id="desc" value="<?php echo $aInfo['dec'] ?>"/> 
<input name="" type="submit" value="����༭" style="background:#F60;color:#FFF;border-color:#09C"/> <input name="" onclick="window.open('/view.php?id=<?php echo $aInfo['id'] ?>','_blank','')" type="button" value="��̬�鿴"/> <input name="" type="button" value="���ɾ�̬" onclick="window.open('/view.php?act=build&id=<?php echo $aInfo['id'] ?>','_blank','')"/><br />
</div>
<div id="dett" style="background:url(../i/bg.png);width:767px;height:912px;border:2px solid #333;">
<input name="t1" type="text" id="t1" style="position:absolute; margin:5px 0 0 45px;width: 201px; height: 21px;"  value="<?php echo $aInfo['t1'] ?>"/>
<input name="t2" type="text" id="t2" style="position:absolute; margin:62px 0 0 96px;width: 132px; height: 17px;" value="<?php echo $aInfo['t2'] ?>" />

<input name="t3" type="text" id="t3" style="position:absolute; margin:168px 0 0 258px;width: 122px; height: 17px;" value="<?php echo $aInfo['t3'] ?>"/>
<textarea name="t4" id="t4" style="position:absolute;margin:192px 0 0 11px; width: 744px; height: 35px;"><?php echo $aInfo['t4'] ?></textarea>

<input name="t5" type="text" id="t5" style="position:absolute;margin:285px 0 0 258px;width: 122px; height: 17px;" value="<?php echo $aInfo['t5'] ?>"/>
<textarea name="t6" id="t6" style="position:absolute;margin:314px 0 0 11px;width: 741px; height: 107px;"><?php echo $aInfo['t6'] ?></textarea>

<input name="t7" type="text" id="t7" style="position:absolute;margin:510px 0 0 11px;width: 735px; height: 17px;" value="<?php echo $aInfo['t7'] ?>"/>
<input name="t8" type="text" id="t8" style="position:absolute;margin:543px 0 0 250px;width: 122px; height: 17px;" value="<?php echo $aInfo['t8'] ?>"/>

<textarea name="t9" id="t9" style="position:absolute;margin:575px 0 0 9px;width: 439px; height: 206px;"><?php echo $aInfo['t9'] ?></textarea>
<textarea name="t10" id="t10" style="position:absolute;margin:825px 0 0 9px;width: 750px; height: 77px;"><?php echo $aInfo['t10'] ?></textarea>
</div>
</form>
</body>
</html>

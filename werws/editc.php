<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>城市相关编辑</title>
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
    $aInfo = $gDB->selectOne(' select * from province where id = '.$nID);
    gK($aInfo);
    echo '<script type="text/javascript">alert("获取成功！");location.href="editc.php?id='.$nID.'";</script>';
    exit();
}
elseif ($_POST['act'] == 'edit'){
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
	$aCity['t8'] = trim($_POST['t8']);
	foreach ($aCity as $n => $v){
		$aCity[$n] = addslashes($v);
	}
	$sSql = " update province set title='".$aCity['title']."',url='".$aCity['path']."', `key`='".$aCity['keywords']."', `dec`='".$aCity['desc']."',`t1` = '".$aCity['t1']."',`t2` = '".$aCity['t2']."',`t3` = '".$aCity['t3']."',`t4` = '".$aCity['t4']."',`t5` = '".$aCity['t5']."',`t6` = '".$aCity['t6']."',`t7` = '".$aCity['t7']."',`t8` = '".$aCity['t8']."'  WHERE id = ".$nID;
	
	$gDB->query($sSql);
	echo '<script>alert("编辑成功！");</script>';
}

$aInfo = $gDB->selectOne(' select * from province where id = '.$nID);

?>
</head>

<body>
<iframe src="./gkey.php?city=<?php echo ereg_replace("省|区",'',$aInfo['name']) ?>&key=<?php echo ereg_replace("省|区",'',$aInfo['name']) ?> 违章" scrolling="no" frameborder="0" style="position:absolute;width:420px;margin:0 0 0 780px;height:600px;"></iframe>
<form action="" method="post"><input name="act" type="hidden" value="edit" />
省份信息填写
<div class="comm">
<strong>省份/区：</strong><input name="city" type="text" id="city" value="<?php echo $aInfo['name'] ?>" style="width:130px;"/>　<a href="/prov.php?act=build&b=all&id=<?php echo $aInfo['id'] ?>" target="_blank">生成全省静态</a><br />
<strong>保存路径：</strong>/<input name="path" type="text" id="path" value="<?php echo $aInfo['url'] ?>" style="width:150px;"/>/index.htm
<br />
<strong>标题：</strong><input name="title" type="text" id="title" value="<?php echo $aInfo['title'] ?>"/> <a href="editc.php?id=<?php echo $nID ?>&act=bd" onclick="if(!confirm('确定要重新获取关键字覆盖现有的？')){return false;}else{return true;}">自动获取百度关键字</a>
<br />
<strong>keywords：</strong><input name="keywords" type="text" id="keywords" value="<?php echo $aInfo['key'] ?>"/>
<br />
<strong>description：</strong><input name="desc" type="text" id="desc" value="<?php echo $aInfo['dec'] ?>"/> 
<input name="" type="submit" value="保存编辑" style="background:#F60;color:#FFF;border-color:#09C"/> <input name="" type="button" value="动态查看" onclick="window.open('/prov.php?id=<?php echo $aInfo['id'] ?>','_blank','')"/> <input name="" type="button" value="生成静态" onclick="window.open('/prov.php?act=build&id=<?php echo $aInfo['id'] ?>','_blank','')"/><br />
</div>
<div id="dett" style="background:url(../i/bg2.png);width:767px;height:665px;border:2px solid #333;">
<input name="t1" type="text" id="t1" style="position:absolute; margin:5px 0 0 45px;width: 201px; height: 21px;"  value="<?php echo $aInfo['t1'] ?>"/>
<input name="t2" type="text" id="t2" style="position:absolute; margin:62px 0 0 96px;width: 132px; height: 17px;" value="<?php echo $aInfo['t2'] ?>" />

<input name="t3" type="text" id="t3" style="position:absolute; margin:168px 0 0 258px;width: 122px; height: 17px;" value="<?php echo $aInfo['t3'] ?>"/>
<textarea name="t4" id="t4" style="position:absolute;margin:192px 0 0 11px; width: 744px; height: 35px;"><?php echo $aInfo['t4'] ?></textarea>

<input name="t5" type="text" id="t5" style="position:absolute;margin:285px 0 0 258px;width: 122px; height: 17px;" value="<?php echo $aInfo['t5'] ?>"/>
<textarea name="t6" id="t6" style="position:absolute;margin:314px 0 0 11px;width: 741px; height: 107px;"><?php echo $aInfo['t6'] ?></textarea>

<input name="t7" type="text" id="t7" style="position:absolute;margin:510px 0 0 11px;width: 735px; height: 17px;" value="<?php echo $aInfo['t7'] ?>"/>
<input name="t8" type="text" id="t8" style="position:absolute;margin:543px 0 0 250px;width: 122px; height: 17px;" value="<?php echo $aInfo['t8'] ?>"/>

</div>
</form>
</body>
</html>

<?php session_start(); 
ini_set('session.cookie_domain', '.5694.com');
error_reporting(0);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
</head> 

<?php 

if ($_SESSION['login'] != 'YES' && isset($_POST['name']) && strtoupper(md5($_POST['name']))=='21232F297A57A5A743894A0E4A801FC3' && strtoupper(md5($_POST['pass'])) == 'E10ADC3949BA59ABBE56E057F20F883E'){
	$_SESSION['login'] = 'YES';
}

if (isset($_SESSION['login']) && $_SESSION['login'] == 'YES'){
	echo '<frameset rows="*" cols="183,*" framespacing="0" frameborder="no" border="0"><frame src="left.php" name="left" scrolling="auto" id="left"/><frame src="main.php" name="main" id="mainFrame"/></frameset><noframes><body></body></noframes>';
}
else {
	echo '<body><form action="" method="post">帐号：<input name="name" type="text" /><br />密码：<input name="pass" type="text" /><br /><input name="" type="submit" /></form></body>';
}
?>
</html>

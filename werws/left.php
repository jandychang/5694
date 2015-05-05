<?php
session_start(); error_reporting(0);if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<style>
body{font-size:13px;}
a{text-decoration:none;color:#06c}
a:hover{color:#F60; text-decoration:underline;}
dl,dt,dd{margin:0;padding:0;}
dl{border:1px solid #999;padding:1px;margin-bottom:5px;width:130px;margin:0 auto;}
dt{height:26px;line-height:26px;;background:#DDD;text-align:center;border-bottom:1px solid #FFF;}
dd{display:none;}
dd a{display:block;height:20px;line-height:20px;text-indent:25px;border-bottom:1px solid #EEE;}
a.g{color:green;}
</style>
<base target="main" />
</head>

<body><br />
<center><a href="/" target="_blank">美剧后台管理</a></center><br />
<dl>
<dt><a href="cla.php?id=1">网站首页</a></dt>
<dt><a href="cla.php?id=2">栏目分类</a></dt>
<dt><a href="arc.php">美剧列表</a></dt>



<dt><a href="cmt.php">评论列表</a></dt>
<dt><a href="html.php">生成静态</a></dt>
<dt><a href="link.php">友情链接</a></dt>

<dt><a href="person.php" class="g">演员列表</a></dt>
<dt><a href="tv.php" class="g">电视列表</a></dt>
<dt><a href="movie.php" class="g">电影列表</a></dt>
</dl><br />

<center style="line-height:20px;">
<a href="http://riju.5694.com/werws/" target="_top">日剧</a> | <a href="http://www.5694.com/werws/" target="_top">美剧</a>
</center>
</body>
</html>

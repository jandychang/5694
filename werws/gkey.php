<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} include('../inc/conf.php');?>
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

</head>

<body>
<form action="" method="get">
    百度关键字查询:<br />
    <input type="text" id="key"  name="key" value="<?php echo $_GET['key'] ?>"/> 包含<input type="text" value="<?php echo $_GET['h'] ?>" name="h" style="width:80px;"/> 
    <input type="hidden" id="city"  name="city" value="<?php echo $_GET['city'] ?>"/>
    <input type="submit" value="查询"/>
</form>
<?php
if (isset($_GET['key']) && !empty($_GET['key'])){
    $sStr = gCurl('http://www.baidu.com/s?wd='.urlencode($_GET['key']));
	
	$sStr = iconv('utf-8','gb2312//IGNORE',$sStr);
    preg_match_all('/<th><a.*?>(.*?)<\/a><\/th>/i',$sStr,$a);

    if (!empty($a[1])){
        if (!empty($_GET['h'])){
            foreach ($a[1] as $nKey => $sTem){
                if (!preg_match('/'.trim($_GET['h']).'/i',$sTem)){
                    unset($a[1][$nKey]);
                }
            }
        }
        $sTit = implode('_',$a[1]);
        $sKey = implode(',',$a[1]);
        $sDec = $_GET['city'].'交通违章网提供'.implode(',',$a[1]).'等相关查询。';
    }

    echo '<strong>title:</strong><br /><textarea style="width:400px;height:60px;">'.$sTit.'</textarea><br /><br />
<strong>k e y:</strong><br /><textarea style="width:400px;height:60px;">'.$sKey.'</textarea><br /><br />
<strong>d e c:</strong><br /><textarea style="width:400px;height:80px;">'.$sDec.'</textarea>';

}
?>

</body>
</html>

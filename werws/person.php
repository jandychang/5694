<?php session_start(); 
if ($_GET['act']=='builds' && !empty($_GET['aid'])){
    include ('../inc/conf.php');
    $aid = explode(',',$_GET['aid']);
    if (!empty($aid)){ 
        foreach($aid as $pid){
            if ($pid > 0){
                bPerson($pid);
            }
        }
        bIndex(1);
    }
    exit();
}
if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');
$sChar = 'utf-8'; 

set_time_limit(0);

if ($_POST['act'] == 'update'){
    $sSet = '';
    if ($_POST['tt'] == 1){
        $sSet = ' comm = 1 ';
    }
    elseif ($_POST['tt'] == 2){
        $sSet = ' comm = 0 ';
    }
    elseif ($_POST['tt'] == 3){
        $sSet = ' status = 1 ';

    }
    
    if (!empty($sSet)){
        $gDB->query('update person set '.$sSet.' where id in ('.$_POST['aid'].') ');
        if ($_POST['tt'] == 3){
            $aPID = explode(',',$_POST['aid']);
            if (!empty($aPID)){ 
                foreach($aPID as $pid){
                    if ($pid > 0){
                        bPerson($pid);
                    }
                }
            }
            
        }
    }
    
    echo 1;
    exit();
}
elseif ($_POST['act'] == 'edit' && intval($_GET['id']) > 0){
    $sSet = '';
    if (!empty($_FILES['pic']['tmp_name'])){
        include('../inc/pic.php');
        $pic = new cPic();
        $sDir = $_SERVER['DOCUMENT_ROOT'].'/u/p/';

		$aPic = $pic->up_pic('pic', $sDir);

		if (!empty($aPic)) {
            $sPic = $aPic[0].'.'.$aPic[1];
            $sNP  = intval($_GET['id']).'.'.$aPic[1];
			$pic->createthumb($sDir.'/'.$sPic, $sDir.'/'.$sNP, 180, 240,4);
            if (file_exists($sDir.$sNP)){
                $sSet = 'npic=\''.'/u/p/'.$sNP.'\',';
            }
        }
    }

    $sSql = ' update person set status=\''.intval($_POST['status']).'\',comm=\''.intval($_POST['comm']).'\',fn=\''.$_POST['fn'].'\','.$sSet.'view=\''.intval($_POST['view']).'\',name=\''.addslashes(trim($_POST['name'])).'\',name2=\''.addslashes(trim($_POST['name2'])).'\',sex=\''.addslashes(trim($_POST['sex'])).'\',bir=\''.addslashes(trim($_POST['bir'])).'\',addr=\''.addslashes(trim($_POST['addr'])).'\',pos=\''.addslashes(trim($_POST['pos'])).'\',det=\''.addslashes(trim($_POST['det'])).'\',etime=\''.time().'\' where id = '.intval($_GET['id']);

    if ($gDB->query($sSql)){
        bPerson(intval($_GET['id']));
        $sTS = '编辑成功';
    }
}
elseif ($_GET['act'] == 'cku'){
    $nID = intval($_GET['id']);
    $t   = intval($_GET['t']);
    $sFile = trim($_GET['fn']); 
    //p(' select count(*) as count from movie where fn = \''.$sFile.'\''.($t==2&&$nID>0?' and id != '.$nID:''));
    if ($gDB->getCount(' select count(*) as count from person where fn = \''.$sFile.'\' and status = 1'.($t==1&&$nID>0?' and id != '.$nID:''))){
        echo 0;
    }
    elseif($gDB->getCount(' select count(*) as count from movie where fn = \''.$sFile.'\' and status = 1'.($t==2&&$nID>0?' and id != '.$nID:''))){
        echo 0;
    }
    else {
        echo 1;
    }
    exit();
}
if (isset($_GET['id']) && $_GET['id'] > 0){
	$nID = intval($_GET['id']);
	$aArc = $gDB->selectOne(' select * from person where id = '.$nID);
    if (!empty($aArc)){
        $aTT = explode(',',$aArc['did']);
        if (!empty($aTT)){ 
            foreach($aTT as $i){
                if ($i > 0){
                    $aDID[$i] = $i;
                }
            }
        }
        $aTT = explode(',',$aArc['yid']);
        if (!empty($aTT)){ 
            foreach($aTT as $i){
                if ($i > 0){
                    $aYID[$i] = $i;
                }
            }
        }
        if (!empty($aDID)){
            $aMo[0][0] = $gDB->select(' select id,cid,oid,name from movie where id in ('.(implode(',',$aDID)).') and cid = 1 order by cid,id asc ','id');
            $aMo[0][1] = $gDB->select(' select id,cid,oid,name from movie where id in ('.(implode(',',$aDID)).') and cid = 2 order by cid,id asc ','id');
        }
        if (!empty($aYID)){
            $aMo[1][0] = $gDB->select(' select id,cid,oid,name from movie where id in ('.(implode(',',$aYID)).') and cid = 1 order by cid,id asc ','id');
            $aMo[1][1] = $gDB->select(' select id,cid,oid,name from movie where id in ('.(implode(',',$aYID)).') and cid = 2 order by cid,id asc ','id');
        }
    }
    
}
else {
    $sPre = '?ord='.trim($_GET['ord']).'&';
    $nPageList = 25; //每页显示多少条.

    $sWhere = ' where 1 ';
    if (!empty($_GET['name'])){
        $sPre .= 'name='.trim($_GET['name']).'&';
        $sWhere .= ' and name like \'%'.addslashes(trim($_GET['name'])).'%\' ';
    }
    if (!empty($_GET['fn'])){
        $sPre .= 'fn='.trim($_GET['fn']).'&';
        $sWhere .= ' and fn like \'%'.addslashes(trim($_GET['fn'])).'%\' ';
    }
    if ($_GET['pid'] > 0){
        $sPre .= 'pid='.$_GET['pid'].'&';
        $sWhere .= ' and id = '.$_GET['pid'].' ';
    }
    if ($_GET['status'] == 1){
        $sPre .= 'status=1&';
        $sWhere .= ' and status = 0 ';
    }
    elseif ($_GET['status'] == 2){
        $sPre .= 'status=2&';
        $sWhere .= ' and status = 1 ';
    }
    if ($_GET['comm'] == 1){
        $sPre .= 'comm=0&';
        $sWhere .= ' and comm = 0 ';
    }
    elseif ($_GET['comm'] == 2){
        $sPre .= 'comm=1&';
        $sWhere .= ' and comm = 1 ';
    }
    if (isset($_GET['ord']) && !empty($_GET['ord'])){
        $ord .= ' order by '.trim($_GET['ord']).' desc ';
    }
    else {
        $ord .= ' order by id asc ';
    }
    
    $nCount = $gDB->getCount('select count(*) as count from person '.$sWhere);
    $nCurr = intval($_GET['curr']);
    $nCurr = ($nCurr<=0||$nCurr>ceil($nCount/$nPageList))?1:$nCurr;
    $sPage = getPage($nCount, $nPageList, $nCurr, $sPre.'curr=%p%', '个人物');
    $aList = $gDB->select(' select * from person '.$sWhere.$ord.' limit '. ($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);
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
	$('tr').hover(function(){$(this).addClass('c');},function(){$(this).removeClass('c');});
    setInterval('cku(<?php echo $_GET['id']; ?>,1,$(\'#fn\').val())',500);
});
</script>
<style>
form i{display:inline-block;width:125px;overflow:hidden;font-size:12px;height:14px;}
form a.c{color:#666;}
form b{display:block;margin:20px 0 5px;color:#F60;}
#dy{line-height:14px;}

</style>
</head>

<body>
后台管理 > 人物管理
<hr />
<?php if (isset($_GET['id']) && $_GET['id'] > 0){ ?>
<form action="" method="post" enctype="multipart/form-data" id="fo" style="display:nosne;">
<img src="<?php echo $aArc['npic'].'?'.time(); ?>" style="float:right;margin:0 5px 0 0;border:1px solid #000;padding:2px;background:#FFF;"/>
<input name="act" type="hidden" value="edit" />
人物信息 > <a href="" onclick="history.go(-1);return false;">返回上一页</a><br />
姓名： <input name="name" type="text" id="name" value="<?php echo $aArc['name'] ?>"/><?php echo $aArc['status']==1?' <a href="/'.$aArc['fn'].'" target="_blank">浏览>></a>':''; ?>
<br />
别名： <input name="name2" type="text" id="name2" value="<?php echo $aArc['name2'] ?>"/>
<br />
性别： <input name="sex" type="text" id="sex" value="<?php echo $aArc['sex'] ?>"/>
<br /> 
生日： <input name="bir" type="text" id="bir" value="<?php echo $aArc['bir'] ?>"/>
<br />
籍贯： <input name="addr" type="text" id="addr" value="<?php echo $aArc['addr'] ?>"/>
<br />
职业： <input name="pos" type="text" id="pos" value="<?php echo $aArc['pos'] ?>"/>
<br />
地址：/<input name="fn" type="text" id="fn" value="<?php echo $aArc['fn'] ?>"/> <s id="fns"></s><br />
图片：
<input name="pic" type="file" id="pic" />
<br />
状态： <input name="status" type="radio" value="0" <?php echo $aArc['status']==0?'checked':'' ?>/>未发布 <input name="status" type="radio" value="1" <?php echo $aArc['status']==1?'checked':'' ?>/>已经发布 <br />
推荐： <input name="comm" type="radio" value="0" <?php echo $aArc['comm']==0?'checked':'' ?>/>未推荐 <input name="comm" type="radio" value="1" <?php echo $aArc['comm']==1?'checked':'' ?>/>已推荐 
<br />
浏览： <input name="view" type="text" id="view" value="<?php echo $aArc['view'] ?>" style="width:60px"/> 人次<br />


介绍：
<br />
<textarea name="det" id="det" style="width:870px;height:150px;"><?php echo stripslashes($aArc['det']) ?></textarea><br />
<!-- <input name="build" type="radio" value="0" checked/>不更新生成 
<input name="build" type="radio" value="1" />生成展示页 
<input name="build" type="radio" value="2" />更新编辑时间并生成静态及首页<br /> -->
<input name="" type="submit" value=" 编  辑 " onclick="if($('#fns').hasClass('c')){alert('地址不可用，请选择其它再提交！');$('#fn').focus();return false;}"/><br /><br /><br />

<div id="dy">
<br /><?php 
if (!empty($aMo[0][0])){ 
    echo '<b>导过的电视：</b>';
    foreach($aMo[0][0] as $a){
        if ($i > 0){
            echo '<i><a href="http://v.baidu.com/'.($a['cid']==1?'tv':'movie').'/'.$a['oid'].'.htm" target="_blank" class="c">'.$a['oid'].'</a>/<a href="?m'.$a['id'].'">'.$a['name'].'</a></i>';
        }
    }
}  
if (!empty($aMo[1][0])){ 
    echo '<b>演过的电视：</b>';
    foreach($aMo[1][0] as $a){
        if ($i > 0){
            echo '<i><a href="http://v.baidu.com/'.($a['cid']==1?'tv':'movie').'/'.$a['oid'].'.htm" target="_blank" class="c">'.$a['oid'].'</a>/<a href="?m'.$a['id'].'">'.$a['name'].'</a></i>';
        }
    }
} 

if (!empty($aMo[0][1])){ 
    echo '<b>导过的电影：</b>';
    foreach($aMo[0][1] as $a){
        if ($i > 0){
            echo '<i><a href="http://v.baidu.com/'.($a['cid']==1?'tv':'movie').'/'.$a['oid'].'.htm" target="_blank" class="c">'.$a['oid'].'</a>/<a href="?m'.$a['id'].'">'.$a['name'].'</a></i>';
        }
    }
}  
if (!empty($aMo[1][1])){ 
    echo '<b>演过的电影：</b>';
    foreach($aMo[1][1] as $a){
        if ($i > 0){
            echo '<i><a href="http://v.baidu.com/'.($a['cid']==1?'tv':'movie').'/'.$a['oid'].'.htm" target="_blank" class="c">'.$a['oid'].'</a>/<a href="?m'.$a['id'].'">'.$a['name'].'</a></i>';
        }
    }
} 
?>
</div>
</form>
<?php }else { ?>
<form action="" method="get" id="f1" style="width:980px;">
    ID：<input type="text" id="pid"  name="pid" value="<?php echo $_GET['pid']; ?>" style="width:60px;" /> 
    姓名：<input type="text" id="name"  name="name" value="<?php echo $_GET['name']; ?>" /> 
    文件名：<input type="text" id="fn"  name="fn" value="<?php echo $_GET['fn']; ?>" style="width:100px;" /> 
    <select id="status" name="status">
        <option value="0">已/未发</option>
        <option value="1"<?php echo $_GET['status']=='1'?'selected':'' ?> style="color:#F00;">未发布</option>
        <option value="2"<?php echo $_GET['status']=='2'?'selected':'' ?> style="color:green;">已发布</option>
    </select>

    <select id="comm" name="comm">
        <option value="0">推荐否</option>
        <option value="1"<?php echo $_GET['comm']=='1'?'selected':'' ?> style="color:#F00;">未推荐</option>
        <option value="2"<?php echo $_GET['comm']=='2'?'selected':'' ?> style="color:green;">推荐</option>
    </select>
    <input type="submit" value="搜索" />
</form>
<table width="1000" border="0" cellspacing="1">
    <tr>
        <th width="54"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=id&',$sPre) ?>">ID</a></th>
        <th width="200">姓名/别名</th>
        <th width="100">文件名</th>
        <th width="60">状态</th>
        <th width="60">性别</th>
        <th width="105">生日</th>
        <th width="105"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=etime&',$sPre) ?>">编辑日期</a></th>
        <th width="80"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=ynum&',$sPre) ?>">演过片数</a></th>
        <th width="80"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=dnum&',$sPre) ?>">导过片数</a></th>
        <th width="58"><a href="<?php echo preg_replace('/ord=[a-z]{0,}&/i','ord=view&',$sPre) ?>">浏览</a></th>
        <th width="74">操作</th>
    </tr>
	<?php
	if (empty($aList)){
		echo '<tr><td colspan="10">无相关演员信息……</td></tr>';
	}
	else {
		foreach($aList as $aTem){
			echo '<tr><td><input name="" type="checkbox" value="'.$aTem['id'].'" class="cd"/>'.$aTem['id'].'</td><td class="l">'.($aTem['status']==0?$aTem['name']:'<a href="/'.$aTem['fn'].'" target="_blank">'.'<span id="s'.$aTem['id'].'">'.$aTem['name'].'</span></a>').($aTem['comm']==0?'':' <s style="color:green;">[推荐]</s>').'</td><td>'.$aTem['fn'].'</td><td>'.($aTem['status']==0?'<s style="color:#F00;">未发</s>':'<s style="color:green;">已发</s>').'</td><td>'.$aTem['sex'].'</td><td>'.$aTem['bir'].'</td><td>'.($aTem['etime']>0?date('Y-m-d',$aTem['etime']):'-').'</td><td>'.$aTem['ynum'].'</td><td>'.$aTem['dnum'].'</td><td>'.$aTem['view'].'</td><td><a href="?id='.$aTem['id'].'">编辑</a></td></tr>';
		}
		echo '<tr><td colspan="10" id="pg"><span style="float:left;"><a href="" onclick="$(\'.cd\').attr(\'checked\',true);return false;">全选</a><a href=""onclick="$(\'.cd\').click();return false;">反选</a><input name="" type="button" value="推荐" onclick="g(1)"/> <input name="" type="button" value="取消推荐" onclick="g(2)"/> <input name="" type="button" value="发布" style="color:#F00;" onclick="g(3)"/></span>'.$sPage.'</td></tr>';
	}
	?>
</table>
<script type="text/javascript">
<!--
function g(j){
	if($('.cd:checked').length<=0)
	{alert('请选择要操作的演员');}
	else{
		var a = [];
		$('.cd:checked').each(function(i){
			a[i] = $(this).val();
		});
		$.post('person.php', {act:'update',tt:j,aid:a.join(',')}, function(data){
                alert("操作成功");location.reload();});
	}
}
//-->
</script>
<?php } ?>
</body>
</html>
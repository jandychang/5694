<?php session_start(); if ($_SESSION['login'] != 'YES'){header("HTTP/1.0 404 Not Found");exit();} ?><?php
include ('../inc/conf.php');
if ($_GET['act'] == 'status'){
    $s = intval($_GET['s']);
    $id= trim($_GET['id']);
    if ($s == 3){
        $gDB->query(' delete from cmt where id in ('.$id.') ');
    }
    else {
        $gDB->query(' update cmt set status = '.$s.' where id in ('.$id.') ');
    }
    $aA = $gDB->select(' select aid from cmt where id in ('.$id.') ','aid');
    $aID = array_keys($aA);
    if (!empty($aID)){
        $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
        $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
        foreach ($aID as $i){
            if ($i > 0){
                bDet($i);
            }
        }
    }
    echo 1;
    exit();
}

$nPageList = 10; //每页显示多少条.
$sWhere = ' where cid = 1 ';

if (!empty($_GET['name'])){
    $aArc = $gDB->select(' select id,name from arc where name like \'%'.trim($_GET['name']).'%\' ','id');
    if (!empty($aArc)){
        $sWhere .= ' and aid in ('.implode(',',array_keys($aArc)).') ';
    }
}

if (!empty($_GET['name']) && empty($aArc)){
    $aList = array();
    $sPage = '';
}
else {
    if ($_GET['status'] >0 ){
        $sWhere .= ' and status = '.($_GET['status']-1);
    }

    $nCount = $gDB->getCount('select count(*) as count from cmt '.$sWhere);
    $nCurr = intval($_GET['curr']);
    $nCurr = ($nCurr<=0||$nCurr>ceil($nCount/$nPageList))?1:$nCurr;
    $sPage = getPage($nCount, $nPageList, $nCurr, 'cmt.php?name='.$_GET['name'].'&curr=%p%', '个剧集');

    $aList = $gDB->select(' select * from cmt '.$sWhere.' order by id desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList);
    if (!empty($aList)){
        foreach ($aList as $nKey => $aTem){
            $aAID[$aTem['aid']] = $aTem['aid'];
        }
        $aArc = $gDB->select(' select id,name from arc where id in ('.implode(',',$aAID).') ','id');
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="s.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/i/j.js"></script>
<script language="JavaScript" type="text/javascript">
function ec(id,t){
    if (t==1){
        $.post('arc.php', {act:'down',id:id,name:$('#n'+id).val(),url:$('#u'+id).val()}, function(data){
                alert('编辑成功！');});
    }
    else {
        $.post('arc.php', {act:'down',id:id,name:$('#n'+id).val(),type:$('#t'+id).val(),det:$('#c'+id).val()}, function(data){
                alert('编辑成功！');});
    }
}
function sc(ob){
    $.post('./arc.php', {act:'sc','oid':ob.val(),id:ob.attr('i')},function(date){},'json');
}
$(function(){
	$('tr').hover(function(){$(this).addClass('c');},function(){$(this).removeClass('c');});
});
</script>
</head>

<body>
后台管理 > 评论管理
<hr />

<table width="830" border="0" cellspacing="1">
<tr><td colspan="5"><form method="get" action="">
    名称：<input type="text" id="name"  name="name" value="<?php echo $_GET['name'] ?>"/> 状态：<select id="status" name="status">
    <option value="0" selected="selected">所有状态</option>
    <option value="1">未审核</option>
    <option value="2">已审核</option>
</select> <input type="submit" value="搜索"/>
<script type="text/javascript">
<!--
    $('#status').val(<?php echo $_GET['status'] ?>);
//-->
</script>
</form></td></tr>
    <tr>
        <th width="54">ID</th>
        <th width="400">名称</th>
        <th width="80">状态</th>
        <th width="120">IP</th>
        <th width="145">发布时间</th>
    </tr>
	<?php
	if (empty($aList)){
		echo '<tr><td colspan="5">无相关评论……</td></tr>';
	}
	else {
		foreach($aList as $aTem){
			echo '<tr><td><input name="" type="checkbox" value="'.$aTem['id'].'" class="cd"/>'.$aTem['id'].'</td><td class="l"><a href="/'.$aTem['aid'].'" target="_blank">'.$aArc[$aTem['aid']]['name'].'</a> <a href="cmt.php?name='.urlencode($aArc[$aTem['aid']]['name']).'&status=0"><img src="/i/s.gif" style="vertical-align:middle;border:0;"/></a></td><td>'.($aTem['status']==1?'<span style="color:green">已审核</span>':'<span style="color:#F00">未审核</span>').'</td><td>'.long2ip($aTem['ip']).'</td><td>'.date('Y-m-d H:i',$aTem['ctime']).'</td></tr><tr><td colspan="5" style="text-align:left;padding:2px 2px 10px;text-indent:2em;">'.$aTem['cont'].'</td></tr>';
		}
		echo '<tr><td colspan="5" id="pg"><span style="float:left;"><a href="" onclick="$(\'.cd\').attr(\'checked\',true);return false;">全选</a><a href=""onclick="$(\'.cd\').click();return false;">反选</a><input name="" type="button" value="审核" onclick="g(1)"/> <input name="" type="button" value="取消审核" onclick="g(2)"/> <input name="" type="button" value="删除" onclick="g(3)" style="del('.$aTem['id'].');"/></span>'.$sPage.'</td></tr>';
	}
	?>
</table>
<script type="text/javascript">
<!--
    function g(i){
        
        if($('.cd:checked').length<=0){
            alert('请选中要操作的评论！');
            return false;
        }
        var id = $('.cd:checked').map(function(){return $(this).val();}).get().join(",");;
        if (i==3 && !confirm('您确定要删除选中的评论吗？')){
            return false;
        }
        $.get('cmt.php?act=status&s='+i+'&id='+id,function(){
            location.reload();
        });
    }
//-->
</script>
</body>
</html>
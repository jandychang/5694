<?php
//SELECT * FROM `person` WHERE etime < unix_timestamp(now())-86400 and etime > 0 and uid > 0 and status != 1
$nLong=500;
include ('./top.php');
include ('./xx.php');
$nProID = 10;
if ($_POST['act'] == 'arc'){
	$nID  = intval($_POST['id']);
    $nXID = intval($_POST['xid']);
	$sTit = trim($_POST['title']);
	$nCla = intval($_POST['cla']);
    $sCont= addslashes(det($_POST['content']));
    $sPic = trim($_POST['ipic']);
	$sFile= trim($_POST['url']);
	$nStatus= intval($_POST['status']);
    $sBei = trim($_POST['bei']);
    $sKey = trim($_POST['keys']);

    $nTui = intval($_POST['tui']);
    if ($nCla == 1){
        $nProvID = intval($_POST['provid']);
        $nCityID = intval($_POST['cityid']);
    }
    elseif ($nCla == $nProID){
        $nProvID = intval($_POST['pid']);
        $nCityID = 0;
    }
    elseif (isset($aTo[$nCla])){
        $nProvID = intval($_POST['l'.$nCla]);
        $nCityID = 0;
    }
    else {
        $nProvID = $nCityID = 0;
    }

    if (!empty($_POST['tag'])){
        $sTag  = serialize(array($_POST['tid'],$_POST['tag']));
    }
    else {
        $sTag = '';
    }
    $nNum = strlen(preg_replace('/[\x00-\x7F]/','',strip_tags($sCont)))/3;
	if ($nID > 0){	//ecit
        if ($nTui==2){
            $sSql = ' update arc set status=2,bei = \''.addslashes($sBei).'\' where id = '.$nID;
            $gDB->query($sSql);
            $gDB->query(' delete from fu where cid = 2 and status <= 0 and oid = '.$nID);	
        }
        elseif ($nTui==1){
            $sSql = ' update arc set cid = '.$nCla.',xid = '.$nXID.',provid = '.$nProvID.',cityid = '.$nCityID.',status=1,num=\''.$nNum.'\',title=\''.addslashes($sTit).'\',`keys`=\''.addslashes($sKey).'\',tag=\''.$sTag.'\',pic=\''.$sPic.'\',content=\''.$sCont.'\',bei = \''.addslashes($sBei).'\' where id = '.$nID;
            $a = $gDB->selectOne(' select id,lk,uid from arc where id = '.$nID);
            $als = $gDB->selectOne(' select id,oid,status from fu where cid = 2 and oid = '.$nID);
            if (!empty($a) && $gDB->query($sSql)){
                $yc = 100-$a['lk'];
                if (empty($als) && $yc >=50){
                    if ($yc >= 60){
                        $nMoney = 4+intval(($yc-50)/10);
                    }else {
                        $nMoney = 4;
                    }
                    $sSql = ' insert into fu(id,uid,aid,cid,oid,status,ctime,money)values(NULL,'.$a['uid'].','.$_SESSION['id'].',2,'.$a['id'].',0,'.time().','.$nMoney.');';
                    $gDB->query($sSql);
                }
            }
        }
        else {
		    $sSql = ' update arc set cid = '.$nCla.',xid = '.$nXID.',provid = '.$nProvID.',cityid = '.$nCityID.',status='.($nNum<$nLong?2:0).',lk=0,etime=\''.time().'\',num=\''.$nNum.'\',title=\''.addslashes($sTit).'\',`keys`=\''.addslashes($sKey).'\',tag=\''.$sTag.'\',pic=\''.$sPic.'\',content=\''.$sCont.'\',url=\''.$sFile.'\',bei = \''.addslashes($sBei).'\' where id = '.$nID;
    
            $gDB->query($sSql);	
            $gDB->query(' delete from fu where cid = 2 and status <= 0 and oid = '.$nID);
            if ($nID > 0 && !empty($_POST['tid'])){
                $aTG = $gDB->select(' select * from tagi where aid = '.$nID.' ','tid');
                $aSql = array();
                foreach ($_POST['tid'] as $n){
                    if (isset($aTG[$n])){
                        unset($aTG[$n]);
                    }
                    else {
                        $aSql[] = '('.$nID.','.$n.')';
                    }
                }
                if (!empty($aTG)){
                    $gDB->query(' delete from tagi where aid = '.$nID.' and tid in ('.implode(',',array_keys($aTG)).') ');
                }
                if (!empty($aSql)){
                    $gDB->query(' insert into tagi(aid,tid)values'.implode(',',$aSql));
                }
                
            }
        }
        //bArc($nID);
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>alert("编辑成功！");history.go(-1);</script>';
        //bArc($nID);
	}
	exit();
}
elseif($_GET['act'] == 'gl'){
    $sStr = iconv('gb2312','utf-8',trim($_GET['str']));
    $nPg  = intval($_GET['p']);
    $nList= 10;

    $aList = $gDB->select(' select id,title from xyx where status = 1 and title like \'%'.$sStr.'%\' order by id desc limit '.($nPg>1?(($nPg-1)*$nList).',':'').' '.$nList);
    if (!empty($aList)){
        
        $nCount = $gDB->getCount(' select count(*) as count from xyx where status = 1 and title like \'%'.$sStr.'%\' ');
        $nPage  = ceil($nCount/$nList);
        foreach ($aList as $n => $a){
            echo '<a href="" onclick="$(\'#ss\').val(\''.$a['title'].'\');$(\'#xid\').val('.$a['id'].');$(\'#gl\').slideUp();return false;">'.$a['id'].' - '.$a['title'].'</a>';
        }
        echo '<p>';
        if ($nPg>1){
            echo '<a href="" onclick="gl(\''.$sStr.'\','.($nPg-1).');return false;">上一页</a>';
        }
        if ($nPg < $nPage){
            echo '<a href="" onclick="gl(\''.$sStr.'\','.($nPg+1).');return false;">下一页</a>';
        }
        
        echo '</p>';
    }
    else {
        echo '暂无相关游戏...';
    }
    exit();
}
elseif(isset($_POST['ck'])){
    $sCK = trim($_POST['ck']);
    echo $gDB->getCount(' select count(*) as count from arc where title = \''.$sCK.'\' ');
    exit();
}
elseif ($_POST['act'] == 'ck'){
    $nID = intval($_POST['id']);
    $sFile = trim($_POST['file']); 
    if ($gDB->getCount(' select count(*) as count from province where url = \''.$sFile.'\' ')){
        echo 1;
    }
    elseif($gDB->getCount(' select count(*) as count from city where url = \''.$sFile.'\' or ourl = \''.$sFile.'\' ')){
        echo 1;
    }
    elseif($gDB->getCount(' select count(*) as count from arc where filename = \''.$sFile.'\' '.($nID>0?' and id != '.$nID:'').' ')){
        echo 1;
    }
    else {
        echo 0;
    }
    exit();
}
$nID = intval($_GET['id']);
$sWhere = '';
$aCla = $gDB->select(' select id,cid,name from clas where 1 order by id asc ','id');
foreach ($aCla as $a){
    $aCID[$a['cid']][] = $a['id'];
}
if ($_SESSION['group'] != 1 && $_SESSION['tid']<=0){
    $nCID = 1;
    $sWhere .= ' and cid in ('.implode(',',$aCID[$nCID]).') ';
}else if($_SESSION['group'] != 1){
    $nCID = $_SESSION['tid']; 
    $sWhere .= ' and cid in ('.implode(',',$aCID[$nCID]).') ';
}
if ($nID > 0){
	$ss = '编辑文章';
	$aArc = $gDB->selectOne('select * from arc where id ='.$nID.$sWhere);
    if (empty($aArc)){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="c.css" rel="stylesheet"/>抱歉，无相关文章！';
        exit();
    }
}
else {
	$ss = '发布新文章';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="c.css" rel="stylesheet"/>
    <style>
    .cts{left:110px;padding:3px;display:block;}
    #bq{display:inline-block;border:1px solid #CCC;width:300px;height:25px;margin:0 5px 0 0;padding:0 5px;line-height:25px;}
    #cts,#pros{position:absolute;margin:35px 0 0;background:#FFF;padding:10px;border:2px solid #F60;z-index:100;line-height:20px;display:none;}
    #cts b{display:inline-block;margin:0 10px 0 0;}
    #cts a{display:inline-block;margin:0 0 0 5px;color:#0F67A1;text-decoration:none;}
    #cts a:hover{color:#F00;text-decoration:underline;font-weight:bold;}
    #ct{display:none;}
    #pros{width:1050px;left:5px;line-height:15px;}
    #pros a{display:inline-block;text-decoration:none;width:55px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;color:#09C;padding:0 0 0 2px;}
    #pros a:hover,#pros a.c{color:#FFF;background:#F60;}
    #pros p{display:none;}
    #pros b{display:block;color:#F60;}
    </style>
</head>

<body>系统后台管理 >  <?php echo  $ss ?><hr />

<script charset="utf-8" src="j/jq.js"></script>
<script charset="utf-8" src="j/kindeditor-min.js"></script>
<script charset="utf-8" src="j/zh_CN.js"></script>
<script type="text/javascript">
function gn(){
    $('#nn').text($('#content').val().replace(/[\x00-\x7F]/g,'').length);
}
function ckTit(s,o){
    if (s.val() == ''){o.html('');return;}    
    $.post('arc.php',{ck:s.val()},function(data){if(data=='1'){o.html('<span style="color:red">已经存在相同标题，不可用</span>')}
    else{o.html('<span style="color:green">此标题可用</span>')}});
}
function setc(i,j,s){
    $('#provid').val(i);
    $('#cityid').val(j);
    $('#cis').val(s);
    $('#cts').hide();
    return false;
}
var editor;
KindEditor.ready(function(K){editor = K.create('textarea[name="content"]', {allowFileManager : true,afterChange: function(){gn();this.sync()}});});
var nPID = <?php echo $nProID; ?>;
function a(){
	if ($('#title').val() == ''){
		alert('请填写文章标题');
		$('#title').focus();
		return false;
	}    
	if ($('#cla').val() <= 0){
		alert('请选择所属栏目');
		$('#cla').focus();
		return false;
	}
    if ($('#cla').val() == 1 && $('#provid').val()<=0){
        alert('请选择违章文章对应的城市！');
        $('#cis').click();
        return false;
    }
    if ($('#cla').val() == nPID && $('#pid').val()<=0){
        alert('请选择违章文章对应的商品！');
        $('#pro').click();
        return false;

    }
	if (editor.isEmpty()){
		alert('请填写文章内容');
		return false;
	}
    /*if ($('#tui').val()==1 && !confirm('你确定要编辑并【通过审核】此篇文章吗？')){
        return false;
    }
    if ($('#tui').val()==2 && !confirm('你确定要编辑并【直接退回】此篇文章吗？')){
        return false;
    }*/    
	return true;
}
function ck(o){
	if(o.val()!=''){
        $.post('arc.php', {act:'ck',id:$('#id').val(),file:o.val()}, function(data){
                if(data == 1){
                    $('#cbc').html('<i style="color:red">此文件名已被占用</i>');
                    $('#filename').select();
                }
                else{
                    $('#cbc').html('<i style="color:green">此文件名可用</i>');
                }
        });
    }
}
function chkk(o){
    sStr = o.val();
    if (sStr != ''){
        $.post('chk.php', {k:sStr}, function(data){
                if(data[0] == 1){
                    if(data[1].length > 2 || /肏|屄|氰|铊|氟|氯|胂|磷|GPS|砷|TNT|PCP|肝|肾|肼|膦|淫|奸|骚|嫖|PPK|穴/i.test(data[1])){
                        alert('文本不允许包含敏感关键词“'+data[1]+'”');
                        //o.val(o.val().replace(data[1],''));
                    }
                    else {
                        alert('文本包含敏感关键词“'+data[1]+'”可能有风险，请确认是否可用！');
                    }
                }
        },'json');
    }
    return false;
}

function gl(str,p){
    $.get('arc.php?act=gl&str='+str+'&p='+p,function(data){$('#gl').html('<input type="text" id="sh" style="width:100px;" value="'+str+'" /> <button onclick="gl($(\'#sh\').val(),1);return false;">搜索</button> <button onclick="$(\'#gl\').slideUp();return false;">取消</button>'+data).slideDown();});
}
var opid = <?php echo $nProID==$aArc['cid']?intval($aArc['provid']):0; ?>;
</script>
<table cellspacing="0" class="s"><form action="" method="post"><input name="act" type="hidden" value="arc" /><input name="id" type="hidden" id="id" value="<?php echo $nID; ?>" /><tr>
        <th>文章标题</th>
        <td><input type="text" id="title" onfocus="$('#sk').html('请输入标题...');" onblur="ckTit($(this),$('#sk'));chkk($(this));" name="title" class="t" value="<?php echo $aArc['title']; ?>" style="width:300px;"/> <span id="sk"></span></td>
    </tr>
    <tr>
        <th>所属分类</th>
        <td>
        <script type="text/javascript" src="j/pzh.js"></script>
        <script type="text/javascript">
        <!--
        $.each(apro,function(i,s){
            //document.write('<a href="" i="'+i+'" '+(opid==i?'class="c"':'')+' onclick="sps($(this));return false;">'+s+'</a>');
        });
        if (opid>0){
            $('#pros a[i="'+opid+'"]').addClass('c');
        }
        $('#pros a').each(function(){$(this).attr('title',$(this).text());})
        document.write('<p>不无关商品</p></div>');
        $('#pros').hover(function(){},function(){$(this).hide();});
        function sps(o){
            $('#pro').val(o.text());
            $('#pid').val(o.attr('i'));
            $('#pros a.c').removeClass('c');
            o.addClass('c');
            $('#pros').hide();
        }
        function so(){            
            $('#pros p').hide();
            $('#pros a.c').removeClass('c');
            var s = $('#pro').val();
            
            if (s==''){
                $('#pros a').show();
            }
            else if ($('#pros a:contains("'+s+'")').length>0){
                $('#pros a').hide();
                $('#pros a:contains("'+s+'")').show();
            }
            else {
                $('#pros a').hide();
                $('#pros p').show();
            }
        }
        function se(i){
            $('#ct,#pt,#pros,.opc').hide();
            $('.lss').val(0);
            if(i==1){   //车辆类
                $('#ct').show();
            }
            else if (i==nPID){  //商品
                $('#pt').show();
            }
            else if ($('#ld'+i).length > 0){  //商品
                $('#ld'+i).show();
            }
            else{
                $('#cis').val('');
                $('#cityid,#provid').val(0);
            }
        }
        //-->
        </script>
        
        
        <?php 
        echo '<div id="cts">';
        include ('./inc/city.php');
        foreach ($aCity as $id => $a){
            $aProvs[$a[0]] .= '<a href="" onclick="return setc('.$a[0].','.$id.',\''.$aProv[$a[0]][0].' - '.$a[1].'\');">'.$a[1].'</a>';
        }
        echo '<a href="" onclick="$(\'#cts\').hide();return false;" style="float:right;color:#FFF;background:#F00;padding:0 5px;">X 取消</a><b>直辖市</b>';
        $a = $b = array();
        foreach ($aProv as $n => $ar){
            if (preg_match('/,/',$ar[2])){
                $a[] = strtoupper($ar[1][0]);
                $b[] = '<br /><b>'.strtoupper($ar[1][0]).'<a href="" onclick="return setc('.$n.',0,\''.$ar[0].'\');"> - '.$ar[0].'</a></b>'.$aProvs[$n];
            }
            else {
                echo $aProvs[$n];
            }
        }
        asort($a);
        foreach ($a as $n => $c){
            echo $b[$n];
        }
        ?>
        </div>
        <input type="hidden" id="provid"  name="provid" value="<?php echo intval($aArc['provid']); ?>"/><input type="hidden" id="cityid"  name="cityid" value="<?php echo intval($aArc['cityid']); ?>"/>
        <select id="cla" name="cla" onchange="se($(this).val())">
    <option value="0" selected="selected">请选择所属分类</option>
	<?php foreach ($aCla as $a){echo '<option value="'.$a['id'].'" '.($aArc['cid']==$a['id']?'selected=""':'').'>'.$a['name'].'</option>';} ?>    
</select> 
    <?php
        //调用二级分类
        foreach ($aTo as $n => $s){
            echo '<span class="opc" id="ld'.$n.'"'.($aArc['cid']==$n?'':' style="display:none"').'>选择分类：<select id="l'.$n.'" class="lss" name="l'.$n.'"><option value="0">请选择栏目</option>'.$s.'</select></span>';
            if ($aArc['cid'] == $n){
                echo '<script type="text/javascript">$(\'#l'.$n.'\').val('.$aArc['provid'].');</script>';
            }
        }
    ?>
    
    <span id="ct"<?php echo $aArc['cid']==1?' style="display:inline-block;"':''; ?>>城市：<input type="text" id="cis" style="width:120px;"<?php echo $aArc['status']!=1?' onclick="$(\'#cts\').show();"':''; ?> readonly="" value="<?php echo ($aArc['provid']>0?$aProv[$aArc['provid']][0].($aArc['cityid']>0?' - '.$aCity[$aArc['cityid']][1]:''):''); ?>"/></span>
    
    <span id="pt"<?php echo $aArc['cid']==$nProID?' style="display:inline-block;"':'style="display:none"'; ?>>商品：<input type="text" id="pro"  name="pro" style="width:120px;" onkeyup="so();" onchange="$('#pid').val(0);" value="" onclick="$('#pros').show();" /></span>

    <script type="text/javascript">
    <?php echo ($aArc['provid']>0?'$(\'#pro\').val(apro['.$aArc['provid'].']);':''); ?>
    </script>
    *点击▼选择您擅长的领域撰写文章吧　<a href="fenlei.htm" target="_blank">关于分类提纲?</a></td>
    </tr>
    <tr>
        <th>关键词</th>
        <td><input type="text" id="keys" name="keys" style="width:200px;" value="<?php echo $aArc['keys']; ?>" /> <i>审核人员填写，可填项</i></td>
    </tr>
    <tr>
        <th>缩略图</th>
        <td id="spc"><?php echo empty($aArc['pic'])?'':'<img src="'.$aArc['pic'].'" style="position:absolute;margin:-100px 0 0 400px;width:200px;height:150px;z-index:0;" id="simg"/>'; ?><input type="text" id="ipic" name="ipic" value="<?php echo empty($aArc['pic'])?'':$aArc['pic']?>"/><span id="upbtn" style="vertical-align:top;"></span></td>
    </tr>
    <tr>
        <th>文章内容</th>
        <td class="r"><i style="display:none;"><input type="checkbox" name="cai" value="1" checked=""/>自动采集图片到本地 <input type="checkbox" name="1tu" checked="" value="ipic"/>取第一张图片设为缩略图 <input type="checkbox" name="olink" checked="" value="1"/>自动删除外链<br /></i>
        <div class="r">中文字符不能少于 <b><?php echo $nLong; ?></b> 字 - <i>当前 <s id="nn" style="color:green;"></s> 个中文字符</i></div>
        <textarea id="content" name="content"style="width:700px;height:400px;visibility:hidden;"><?php echo $aArc['content']; ?></textarea></td>
    </tr><tr>
        <th>&nbsp;</th>
        <td>
        <script type="text/javascript">
        var ts = '文章分段、分句问题|多处明显的错别字|语句重复多余或者逻辑不通|内容不符合要求|标题与内容关联性低|内容出现本质性错误思想|内容不完整|语句白话文居多|开篇导语过长|字数不足'.split('|');
        document.write('<div id="tssb">');
        $.each(ts,function(i,n){
            document.write('<a href="" onclick="$(\'#bei\').val(($(\'#bei\').val()==\'\'?\'\':$(\'#bei\').val()+\'，\')+\''+n+'\');$(\'#tssb\').hide();return false;">'+n+'</a>');
        }); 
        document.write('</div>');
        </script>
        备注：<input type="text" id="bei"  name="bei" style="width:600px;" value="<?php echo $aArc['bei']; ?>" onclick="sts();"/><br /><br />
        <input type="hidden" id="tui"  name="tui" value="0"/>
        <input type="submit" value=" <?php echo $ss; ?> " onclick="$('#tui').val(0);return a();"/><?php echo $aArc['lk']>0&&$aArc['lk']<=50?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value=" 编辑并通过审核 " onclick="$(\'#tui\').val(1);return a();" style="background:green;color:#FFF;"/>':'...未测原创...'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value=" 编辑并直接退回 " onclick="$('#tui').val(2);return a();" style="background:#F00;color:#FFF;"/>
        <?php $as = array('待审', '已通过审核', '已经退回');echo ' - 当前状态：<b>'.$as[$aArc['status']].'</b>'; ?>
        </td>
    </tr></form>
</table>
<?php echo EMPTY($aArc['txt'])||$aArc['bdtime']<=$aArc['etime']?'':'<div id="ts">原创判断：<br />'.preg_replace('/<b>(.*?)<\/b>(.*?)<br \/>/','<b>$1</b>$2 - <a href="/b.php?t=1&k=$1" target="_blank">BD</a> <a href="/b.php?t=2&k=$1" target="_blank">SO</a> <a href="/b.php?t=3&k=$1" target="_blank">SG</a> <a href="/b.php?t=4&k=$1" target="_blank">GG</a> <br />',$aArc['txt']).'</div>'; ?>
<script type="text/javascript" src="./j/swf.js"></script>
<script type="text/javascript">
function sts(){
    $('#tssb').css({left:$('#bei').offset().left+'px',top:($('#bei').offset().top-$('#tssb').outerHeight())+'px'}).show();
}
$('#tssb').hover(function(){},function(){$(this).hide();})
var u = location.href.replace(/[^\/]+$/,'');
swfobject.embedSWF("./j/u.swf","upbtn","200","28","9.0.0","./j/e.swf",{serverUrl:u+"up.php?act=1",jsFunction:"re"},{wmode:'transparent'});
function re(type, str){if (type=='upload_complete'&&str!='400'){var swf=swfobject.getObjectById("upbtn");swf.uploadReset();$('#ipic').val(str);if ($('#simg').length<=0){$('#spc').prepend('<img src="'+str+'" style="position:absolute;margin:-100px 0 0 400px;z-index:0;width:200px;height:150px;" id="simg"/>');}else{$('#simg').attr('src',str);}}}
</script>
</body>
</html>

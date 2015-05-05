<?php
//SELECT * FROM `person` WHERE etime < unix_timestamp(now())-86400 and etime > 0 and uid > 0 and status != 1
$nLong=500;
include ('./top.php');
$nProID = 10;
if ($_POST['act'] == 'arc'){
	$nID  = intval($_POST['id']);
    $nXID = intval($_POST['xid']);
	$sTit = trim($_POST['title']);
	$nCla = intval($_POST['cla']);
    $sCont= det($_POST['content']);
    $sPic = trim($_POST['ipic']);
	$sFile= trim($_POST['url']);
	$nStatus= intval($_POST['status']);

    if ($nCla == 1){
        $nProvID = intval($_POST['provid']);
        $nCityID = intval($_POST['cityid']);
    }
    elseif ($nCla == $nProID){
        $nProvID = intval($_POST['pid']);
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
	if ($nID <= 0){	//add
		$sSql = ' insert into arc(id,cid,uid,xid,provid,cityid,status,ctime,etime,bdtime,lk,view,num,title,pic,tag,content,url,txt) values(NULL,'.$nCla.','.$_SESSION['id'].','.$nXID.','.$nProvID.','.$nCityID.','.($nNum<$nLong?2:0).','.time().',0,0,0,0,'.$nNum.',\''.addslashes($sTit).'\',\''.$sPic.'\',\''.$sTag.'\',\''.addslashes($sCont).'\',\''.$sFile.'\',\'\')';
        $gDB->query($sSql);
        $nID = mysql_insert_id();
        if ($nID > 0 && !empty($_POST['tid'])){
            $aSql = array();
            foreach ($_POST['tid'] as $n){
                $aSql[] = '('.$nID.','.$n.')';
            }
            $sSql = ' insert into tagi(aid,tid)values'.implode(',',$aSql);
            $gDB->query($sSql);
        }
        if ($nStatus == 1){
            //bArc($nID);
        }
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>alert("发布成功！");location.href="arc.php";</script>';
	}
	else {
		$sSql = ' update arc set cid = '.$nCla.',xid = '.$nXID.',provid = '.$nProvID.',cityid = '.$nCityID.',status='.($nNum<$nLong?2:0).',lk=0,bei=\'\',etime=\''.time().'\',num=\''.$nNum.'\',title=\''.addslashes($sTit).'\',tag=\''.$sTag.'\',pic=\''.$sPic.'\',content=\''.addslashes($sCont).'\',url=\''.$sFile.'\' where id = '.$nID.' and uid = '.$_SESSION['id'].' and status != 1 ';
		$gDB->query($sSql);
        //bGame($nXID,0,0);
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
        //bArc($nID);
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>alert("编辑成功！");history.go(-2);</script>';
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
if ($nID > 0){
	$ss = '编辑文章';
	$aArc = $gDB->selectOne('select * from arc where id ='.$nID.' and status!= 1 and uid = '.$_SESSION['id']);
    $aCla = $gDB->select(' select id,hide,name,kw from clas where 1 order by id asc ','id');
    if (empty($aArc)){
        exit();
    }
}
else {
	$ss = '发布新文章';
    $aCla = $gDB->select(' select id,name,kw from clas where 1 and hide=0 order by id asc ','id');
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
    #tsc{border:solid 2px #F00;background:#FFC;padding:5px;color:#F00;display:none;}
    #tsc b{display:block;border-bottom:1px solid #F00;padding:0 0 5px;margin:0 0 5px;color:green;}
    #tsc em{font-size:14px;}
    #tsc a{text-decoration:none;}
    </style>
</head>

<body>系统后台管理 >  <?php echo  $ss ?>　　*<span style="color:red">文章内容拒绝涉及政治、反动、低俗、色情、黄色等。</span><hr />
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
		alert('请选择所属分类');
		$('#cla').focus();
		return false;
	}
    if ($('#cla').val() == 1 && $('#provid').val()<=0){
        alert('请选择违章文章对应的城市！');
        $('#cis').click();
        return false;
    }
    if ($('#cla').val() == nPID && $('#pid').val()<=0){
        alert('请选择文章所属对应的商品！');
        $('#pro').click();
        return false;

    }
    
	if (editor.isEmpty()){
		alert('请填写文章内容');
		return false;
	}
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
            $('#ct,#pt,#pros').hide();
            if (i>0){
                $('#tsc em').html(ax[i]);
                $('#tsc').show();
            }else {
                $('#tsc').hide();
            }
            
            if(i==1){
                $('#ct').show();
            }
            else if (i==nPID){
                $('#pt').show();
            }
            else{
                $('#cis').val('');
                $('#cityid,#provid').val(0);
            }
        }
        <?php 
        $ax = array();
        foreach ($aCla as $a){$ax[$a['id']] = preg_replace("/\r\n|\n/",'<br />',$a['kw']);}
        echo 'var ax = '.json_encode($ax).';';
        ?>
        //-->
        </script>
        <div id="cts">
        <?php 
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
        <select id="cla" name="cla"<?php echo $aArc['cid']>0?' onchange="$(this).val('.$aArc['cid'].')"':' onchange="se($(this).val())"'; ?>>
    <option value="0" selected="selected">请选择所属分类</option>
	<?php foreach ($aCla as $a){echo '<option value="'.$a['id'].'" '.($aArc['cid']==$a['id']&&$aCla[$aArc['cid']]['hide']==1?' style="color:#999;"':'').($aArc['cid']==$a['id']?'selected=""':'').'>'.$a['name'].'</option>';} ?>    
</select> 
    <span id="ct"<?php echo $aArc['cid']==1?' style="display:inline-block;"':''; ?>>城市：<input type="text" id="cis" style="width:120px;"<?php echo $aArc['status']!=1?' onclick="$(\'#cts\').show();"':''; ?> readonly="" value="<?php echo ($aArc['provid']>0?$aProv[$aArc['provid']][0].($aArc['cityid']>0?' - '.$aCity[$aArc['cityid']][1]:''):''); ?>"/></span>
    <span id="pt"<?php echo $aArc['cid']==$nProID?' style="display:inline-block;"':'style="display:none"'; ?>>商品：<input type="text" id="pro"  name="pro" style="width:120px;" onkeyup="so();" onchange="$('#pid').val(0);" value="" onclick="$('#pros').show();" /></span>
    <script type="text/javascript">
    <?php echo ($aArc['provid']>0?'$(\'#pro\').val(apro['.$aArc['provid'].']);':''); ?>
    </script>
    *点击▼选择您擅长的领域撰写文章吧　<a href="fenlei.htm" target="_blank">关于分类提纲?</a>
    <div id="tsc"><b>注意：为了便于准确快速审核，小伙伴们请按下面的要求撰写文章，有不明白之处可咨询客服！</b><em></em></div>
    </td>
    </tr>
    <tr>
        <th>缩略图</th>
        <td id="spc"><?php echo empty($aArc['pic'])?'':'<img src="'.$aArc['pic'].'" style="position:absolute;margin:-100px 0 0 400px;width:200px;height:150px;z-index:0;" id="simg"/>'; ?><input type="text" id="ipic" name="ipic" value="<?php echo empty($aArc['pic'])?'':$aArc['pic']?>"/><?php echo $aArc['status']!=1?'<span id="upbtn" style="vertical-align:top;"></span>':''; ?> *非必选项</td>
    </tr>
    <tr>
        <th>文章内容</th>
        <td class="r"><i style="display:none;"><input type="checkbox" name="cai" value="1" checked=""/>自动采集图片到本地 <input type="checkbox" name="1tu" checked="" value="ipic"/>取第一张图片设为缩略图 <input type="checkbox" name="olink" checked="" value="1"/>自动删除外链<br /></i>
        <div class="r">在下框撰写纯原创文章、不限题材，要求中文字符不少于 <b><?php echo $nLong; ?></b> 字，不足会直接被退回重改。 - <i>当前 <s id="nn" style="color:green;"></s> 个中文字符</i>　<a href="yuanchuang.htm" target="_blank">什么是原创？</a></div>
        <textarea id="content" name="content"style="width:700px;height:400px;visibility:hidden;"><?php echo $aArc['content']; ?></textarea></td>
    </tr><?php echo $aArc['status']!=1?'<tr><th>&nbsp;</th><td><input type="submit" value=" '.$ss.' " onclick="return a();"/>　　　*长时间不操作或网络不稳定可能造成提交失败，请尽量选择记事本编辑，而不是在线撰写。</td></tr>':''; ?></form>
</table>
<?php echo $aArc['lk']>=51 && $aArc['lk']<=60?'<div id="ts">原创判断：<br />'.$aArc['txt'].'</div>':''; ?>
<script type="text/javascript" src="./j/swf.js"></script>
<script type="text/javascript">
var u = location.href.replace(/[^\/]+$/,'');
swfobject.embedSWF("./j/u.swf","upbtn","200","28","9.0.0","./j/e.swf",{serverUrl:u+"up.php?act=1",jsFunction:"re"},{wmode:'transparent'});
function re(type, str){if (type=='upload_complete'&&str!='400'){var swf=swfobject.getObjectById("upbtn");swf.uploadReset();$('#ipic').val(str);if ($('#simg').length<=0){$('#spc').prepend('<img src="'+str+'" style="position:absolute;margin:-100px 0 0 400px;z-index:0;width:200px;height:150px;" id="simg"/>');}else{$('#simg').attr('src',str);}}}
</script><br />请认真撰写，不要直接采集他人的复制/粘贴，检查确认无误后在提交，低于<span style="color:red">50%</span>的原创度，会直接被系统退回要求重新修改。<br />　　　　　　单价采用阶梯式计价，原创度 <span style="color:red">≥50%</span> 及格计4元，原创 <span style="color:red">≥60%</span> 计5元，原创 <span style="color:red">≥70%</span> 计6元，原创 <span style="color:red">≥80%</span> 计7元，以此类推。<br />
</body>
</html>

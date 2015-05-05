function killerrors(){return true;}
window.onerror = killerrors;

var adsPage="/i/load.htm";//视频播放前广告页路径
var adsTime=5;//视频播放前广告时间，单位秒

var w='720';//播放器宽度
var h='480';//播放器高度
var htm = '';
function viewplay(para1,para2){
	var urlAndFrom,url,wd
	urlAndFrom=handleParas(para1,para2);
	wd=urlAndFrom[2];
    if(wd=="qvod"){wdplay(para1,para2)}
    else{
        document.writeln("<span id=\"loadimg\"><iframe src=\""+adsPage+"\" frameborder=\"0\" scrolling=\"no\"  width=\""+w+"\" height=\""+h+"\"><\/iframe><\/span>");
        document.writeln("<span id=\"wdswf\"  style=\"display:none;\">");
        wdplay(wd_i,wd_zu);
        document.writeln("<\/span>");
        setTimeout('document.getElementById("loadimg").style.display="none"',adsTime*1000);
        setTimeout('document.getElementById("wdswf").style.display="block"',adsTime*1000);
    }
}

if (0 && $('#m dd.l i:first').attr('t') == 'gvod'){
    viewgvod($('#m dd.l i:first').attr('u'),w,h);
}
else {
    if ($('i[u]').length > 0){
        //$('#m p i:first').before('<iframe src="/i/load.htm" id="ad" style="width:700px;height:450px;position:absolute;margin:10px;" frameborder="0" scrolling="no"/>');
        $('#m p i:first').before('<iframe src="/i/error2.htm" id="ad" style="z-index:1;width:700px;height:450px;position:absolute;margin:10px;" frameborder="0" scrolling="no"/>');
    }
    
    //setTimeout("$('#ad').remove();wdplay($('#m dd.l i:first').attr('u'),$('#m dd.l i:first').attr('t'))",6000);
}
function wdplay(url,wd){
	if(wd=="qvod"){viewqvod(url,w,h)}
	else if(wd=="youku"){viewyouku(url,w,h)}
	else if(wd=="tudou"){viewtudou(url,w,h)}
	else if(wd=="gvod"){viewgvod(url,w,h)}
	else if(wd=="ppvod"){viewppvod(url,w,h)}
	else if(wd=="iask"){viewiask(url,w,h)}
	else if(wd=="swf"){viewswf(url,w,h)}
	else if(wd=="hd_tudou"){viewhd_tudou(url,w,h)}
	else if(wd=="cc"){viewcc(url,w,h)}
	else if(wd=="hd_iask"){viewhd_iask(url,w,h)}
	else if(wd=="hd_56"){viewhd_56(url,w,h)}
	else if(wd=="6rooms"){view6rooms(url,w,h)}
	else if(wd=="qq"){viewqq(url,w,h)}
	else if(wd=="ku6"){viewku6(url,w,h)}
	else if(wd=="flv"){viewflv(url,w,h)}
	else if(wd=="pvod"){viewpvod(url,w,h)}
	else if(wd=="baidu"){viewbaidu(url,w,h)}
	else if(wd=="baibu"){viewbaidu(url,w,h)}
	else if(wd=="bdhd"){viewbaidu(url,w,h)}
	else if(wd=="qiyi"){viewqiyi(url,w,h)}
    
    $('#m dd.l i:first').html(htm);
}


function showswfab(offest){
    var h='495';//播放器高度
    if(document.getElementById('QvodPlayer').PlayState==3){
        document.getElementById('ad').style.display='none';
        document.getElementById('QvodPlayer').style.height = h;
    }else if(document.getElementById('QvodPlayer').PlayState==0){
        document.getElementById('ad').style.display='';
        document.getElementById('QvodPlayer').style.height='63';
    }
}

//调用pvod播放
function viewpvod(url,w,h){
    htm = '<iframe marginWidth="0" marginHeight="0" src="/i/pvod.htm?a='+escape(url)+'&w='+w+'&h='+h+'" frameBorder="0" width="'+w+'" scrolling="no" height="'+h+'" id="pvod" name="pvod"></iframe>';
}

//调用youku播放
function viewyouku(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://static.youku.com/v1.0.0296/v/swf/qplayer.swf" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" flashvars="isShowRelatedVideo=false&showAd=0&show_pre=1&show_next=1&VideoIDS='+url+'&isAutoPlay=true&isDebug=false&UserID=&winType=interior&playMovie=true&MMControl=false&MMout=false&RecordCode=1001,1002,1003,1004,1005,1006,2001,3001,3002,3003,3004,3005,3007,3008,9999" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewqiyi(url,w,h){
    url=url.replace(/-/g,"&");
    htm = '<embed bgcolor="#000000" type="application/x-shockwave-flash" src="http://www.qiyi.com/player/20110714102816/qiyi_n_player.swf" width="'+w+'" height="'+h+'" quality="high" allowfullscreen="true" allowscriptaccess="always" wmode="Opaque" flashvars="'+url+'&coop=qc_200010_300010">';
}
//QVOD代码
function viewqvod(url,w,h){
    htm += '<iframe marginWidth="0" marginHeight="0" src="/i/ad.htm" frameBorder="0" width="'+w+'" scrolling="no" height="'+h+'" id="wdqad" name="wdqad"></iframe>';
    htm += "<object classid='clsid:F3D0D36F-23F8-4682-A195-74C92B03D4AF' width='"+w+"' height='63' id='QvodPlayer' name='QvodPlayer' onerror=\"document.getElementById('QvodPlayer').style.display='none';document.getElementById('wdqad').src='/i/qvod.htm';\"><PARAM NAME='URL' VALUE='"+url+"'><param name='Autoplay' value='1'><PARAM NAME='QvodAdUrl' VALUE='/i/qvod.htm'></object>";
    setInterval('showswfab()','1000');
}

function view123tudou(url,w,h){
    htm = '<div style=" width:'+w+'px;height:490px;background:#000;text-align:center;"><div style="overflow: hidden; position: relative;width: 585px; height: 490px;top: 0;margin-left:auto;margin-right:auto;"><div style="left: -200px; top:-12px; width: 1000px; height: 490px; position: absolute;"><embed height="100%" width="100%" flashvars="iid='+url+'" wmode="Opaque" allowscriptaccess="never" allowNetworking="internal" allowfullscreen="true" quality="high"  src="http://js.tudouui.com/bin/player_online/TudouVideoPlayer_Homer_169.swf" type="application/x-shockwave-flash"></embed></div></div></div>';
}

//调用土豆播放器
function viewtudou(url,w,h){
    htm = '<iframe width="'+w+'" height="'+h+'" id="tudouplay_iframe" frameborder="0" src="/i/tudou.htm?'+url+'" SCROLLING=no name="maxz_mplay"></iframe>';
}


function viewgvod(url,w,h){
    document.write('<script type="text/javascript" src="/i/gvod.js" type="text/javascript" ></script>');
}

function viewppvod(url,w,h){
    htm = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+w+'" height="'+h+'" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab"> <param name="movie" value="http://play.ppvod.cn/OpenUI.swf"  /> <param name="wmode" value="window" /> <param name="quality" value="high" /> <param name="bgcolor" value="#000000" /> <param name="allowFullScreen" value="true" /> <param name="allowScriptAccess" value="sameDomain" /> <param name="flashvars" value="url='+url+'&mode=direct|ppva&coded=true" /> <embed src="http://play.ppvod.cn/OpenUI.swf" quality="high" bgcolor="#000000" flashvars="url='+url+'&mode=direct|ppva&coded=true" width="'+w+'" height="'+h+'" name="OpenPlayer" align="middle" play="true" loop="false" wmode="window" quality="high" allowscriptaccess="sameDomain" allowfullscreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer"> </embed> </object>';
}

function viewiask(url,w,h){
    htm = '<embed width="'+w+'" height="'+h+'" src="http://p.you.video.sina.com.cn/player/player.swf?auto=1&vid='+url+'" allowfullscreen="true"> </embed>';
}
function viewswf(url,w,h){
    htm = '<embed width="'+w+'" height="'+h+'" src="'+url+'"> </embed>';
}

function showswfbaidu(offest){
    var h='468';//播放器高度
    if(BaiduPlayer.IsPlaying()){
        document.getElementById('ad').style.display='none';
        document.getElementById('BaiduPlayer').style.height=h;
    }else{
        if(BaiduPlayer.IsBuffing()){
        document.getElementById('ad').style.display='';
        document.getElementById('wdqad').style.height=h-80;
        document.getElementById('BaiduPlayer').style.height='80';


        }else{
        document.getElementById('ad').style.display='';
        document.getElementById('wdqad').style.height=h-60;
        document.getElementById('BaiduPlayer').style.height='60';
        }
    }
}

var BDUrlBefore="[yyse]";
var BDUrlLast="";
var buffer="/js/bd/bhc.html"; 
var pause="/js/bd/szt.html";  
var Url = $('#m dd.l i:first').attr('u');
function viewbaidu(url,w,h){
    htm = '<iframe border="0" name="qireplay" id="qireplay" src="/i/bud.htm" marginwidth="0" framespacing="0" marginheight="0" noresize="" vspale="0" style="z-index: 9998;" frameborder="0" height="'+h+'" scrolling="no" width="'+w+'"></iframe>';
    return;
}
function bdi(w,h){
    $('#m dd.l i:first').html('<iframe border="0" src="/i/bd.htm" marginWidth="0" frameSpacing="0" marginHeight="0" frameBorder="0" noResize scrolling="no" width="'+w+'" height="'+h+'" vspale="0"></iframe>');
} 

//调用hd_tudou播放
function viewhd_tudou(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://js.tudouui.com/bin/kili/player/TudouVideoPlayer_kili_6.swf?iid='+url+'&default_skin=http://js.tudouui.com/bin/kili/player/Main_7.swf&lid=1&uid=0&safekey=IAlsoNeverKnow&allow=1" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

//调用cc播放
function viewcc(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://union.bokecc.com/flash/pocle/player.swf?siteid=maxuser&vid='+url+'&autoStart=true" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewhd_iask(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://you.video.sina.com.cn/swf/topic/topicPlay.swf?auto=1&vid='+url+'" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewhd_56(url,w,h){
url=(url.substr(0,3)!='n_v'?'n_v163_/':'')+url;
var val=url.substr(2).split('_/');
for(var i=0;i<9;i++){val[i]=val[i]||''}
document.write('<embed type="application/x-shockwave-flash" src="http://www.56.com/flashApp/56.swf?host='+val[1]+'.56.com&pURL='+val[2]+'&sURL='+val[3]+'&user='+val[4]+'&URLid='+val[5]+'" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">');
}
function view6rooms(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://6.cn/player.swf?vid='+url+'&flag=1" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewqq(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://cache.tv.qq.com/qqplayerout.swf?v='+url+'" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewku6(url,w,h){
    htm = '<embed type="application/x-shockwave-flash" src="http://player.ku6.com/refer/'+url+'/p.swf" id="movie_player" name="movie_player" bgcolor="#FFFFFF" quality="high" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="'+w+'" height="'+h+'">';
}

function viewflv(url,w,h){
    htm = '<embed src="/js/vcastr22.swf?vcastr_file='+url+'" allowFullScreen="true" FlashVars="vcastr_file='+url+'&DefaultVolume=100&IsAutoPlay=1&LogoUrl=http://www.yiyi.cc/js/logo.swf&IsShowBar=2&BarColor=0xFF6600" width="'+w+'" height="'+h+'" type="application/x-shockwave-flash"></embed>'; 
}
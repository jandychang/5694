function a(i){
   switch (i){
    case 0:
        document.write('<script type="text/javascript">/*女性*/var cpro_id = "u1808115";</script><script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>');
        break;
    case 1:
        document.write('<script type="text/javascript">/*视频*/var cpro_id = "u1808118";</script><script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>');
        break;
    case 2:
        document.write('<script type="text/javascript">/*明星*/var cpro_id = "u1808120";</script><script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>');
        break;
    }
}
function f(){
    document.write('<p id="f">5694粉丝网 Copyright © 2012-2013 冀ICP备13007039号-1 <br />联系我们：meiju5694@163.com</p>');
    document.write('<script type="text/javascript">/*娱乐*/var cpro_id = "u1509684";</script><script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>');
    document.getElementById('t').innerHTML = '<form method="get" action="/s.php"><div><input type="text" id="k" name="k" value="输入关键字" onfocus="if(document.getElementById(\'k\').value==\'输入关键字\'){document.getElementById(\'k\').value=\'\';}" onblur="if(document.getElementById(\'k\').value==\'\'){document.getElementById(\'k\').value=\'输入关键字\';}"/></div><input type="submit" id="sh" value="" onclick="return ser();"/></form>'+document.getElementById('t').innerHTML;

    if (document.getElementById('tp') != undefined){
        var tt = document.title;
         var u = navigator.userAgent;
         if (u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1 || u.indexOf('iPad') > -1){
            var sur = 'http://www.haima.me/tg/000000223';//'http://m.video.baidu.com/?src=video#search/'+tt.replace(/^([^_]+)_.*$/,'$1'); 
         }else {
            var sur = 'http://www.973.com/u/w/ChangeDress-android-6804.apk'; 
         }
        document.getElementById('tp').innerHTML = '<a href="'+sur+'" target="_blank">点击播放>></a>';
    }

    var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cspan id='cnzz_stat_icon_30065507'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/c.php%3Fid%3D30065507' type='text/javascript'%3E%3C/script%3E"));
}
function ser(){
    document.getElementById('k').focus();
    if (document.getElementById('k').value==''){
        alert('请输入要搜索的关键字！');
        return false;
    }
    return true;
}
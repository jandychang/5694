var sby = '';
function a1(){
    if(sby==''){
        if (/\d+p$/.test(location.href) && !/页面不存在/.test(document.title)){
            document.write('<script type="text/javascript">/*美剧*/ var cpro_id = "u1376138";</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>');
        }
        document.write('<a href="http://www.5694.com" target="_blank"><img src="/i/mj.jpg" border="0" /></a>');
        //a5();
    }
    else {
        document.body.style.display="none";
    }
}
function a5(){
    document.write('<script type="text/javascript">/*好看的美剧*/ var cpro_id = "u357328";</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>');
}
function a2(){
    //sby=='' && document.write('<a href="http://cnrdn.com/hFT6" target="_blank"><img src="/i/960.gif" border="0"/></a>');
    sby=='' && document.write('<script type="text/javascript">/*美剧排行榜*/ var cpro_id = "u1052922";</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>');
}
function a3(){
    //document.write('<iframe frameborder="0" scrolling="no" id="fra" name="fra" src="/i/zo.htm" style="width:300px;height:250px;" ></iframe>');
    sby=='' && document.write('<script type="text/javascript">/*电影*/ var cpro_id = "u1321891";</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>');
}
function b(){
    document.write('<p id="o">免责声明：5694美剧网提供的美剧资源均自动搜索采集引用自各大视频网站，本站仅提供Web页面服务，不储存任何资源，且不参与录制、上传。<br />若本站收录的节目无意侵犯到贵司版权，请通过底部邮件联系我们删除，对此本站不承担任何法律责任，版权归原影音公司所有。<br />Copyright © 2012-2013 冀ICP备13007039号-1 联系我们：meiju5694@163.com</p>');
    document.write('<script src="/i/j.js" language="JavaScript" type="text/javascript"></script>');
    sby=='' && document.write('<script type="text/javascript">/*美剧*/ var cpro_id = "u937644";</script><script src="http://cpro.baidu.com/cpro/ui/f.js" type="text/javascript"></script>');
    document.write('<script src="http://w.cnzz.com/c.php?id=30065507" language="JavaScript"></script>');
}
if (/\/\d+p$/.test(location.href)){
    var nDID = location.href.replace(/^.*?\/(\d+)p$/,'$1');
    if (nDID > 0){
        document.write('<script src="/p.php?did='+nDID+'" language="JavaScript" type="text/javascript"></script>');
    }
}
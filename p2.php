<?php 
include ('inc/conf.php');
$aDo = $gDB->selectOne(' select aid,title from down where id = '.intval($_GET['did']));
if (empty($aDo) || !($a = $gDB->selectOne(' select * from play where aid = '.$aDo['aid']))){
    exit('sby = \'\';');
}
else {
    preg_match('/\d+/',$aDo['title'],$aJI);
    $ass  = $gDB->selectOne(' select name from arc where id = '.$aDo['aid']);
    $aArr = unserialize($a['txt']);
    $aOne = $aOne = array();
    if (!empty($aArr)){ 
        foreach($aArr as $k => $a){
            //echo '<i>'.$k.'</i>';
            foreach ($a as $n => $u){
                if (empty($aOne[$k])){
                    $aOne[$k] = $u;
                }
                if (empty($aHref[$k]) && $n==intval($aJI[0])){
                    //$aHref[$k] = $u;
                    //echo '<a href="'.$u.'" class="c" target="player">第'.$n.'集</a>';
                }
                else {
                    //echo '<a href="'.$u.'" target="player">第'.$n.'集</a>';
                }
            }
        }
    }
    p($aOne);
    if (empty($aHref)){
        $aHref = $aOne;
    }
    
    if (!empty($aHref)){
        $s = '';
        foreach ($aHref as $k => $v){
            $s .= '<a href="'.$v.'" target="_blank">'.$k.'</a> ';
        }
        echo 'gou = \''.$s.'\'';
    }
    else {
        echo 'gou = \'<a href="'.(empty($sHref)?$sOne:$sHref).'">影音播放源</a>\';';
    }

    exit();
    preg_match('/\d+/',$aDo['title'],$aJI);
    $ass  = $gDB->selectOne(' select name from arc where id = '.$aDo['aid']);
    $aArr = unserialize($a['txt']);
        echo 'sby = \'';?><style>body{margin:0;font:12px/1.5 "宋体";color:#555;}b,a,i,u,s,em{display:block;text-align:center;}p a{border:1px solid #999;padding:5px 10px;margin:0 10px 3px;width:auto;background:#FFF;text-decoration:none;color:#09C;}p a.c,p a:hover{border:1px solid #F60;background:#333;color:#F60;font-weight:bold;}b{margin:0 0 10px;color:#F60;}img{border:0;}i{font-style:normal;font-weight:bold;margin:15px 0 3px;color:#CCC;}dl,dd,dt,p,ul,li{margin:0;padding:0;list-style:none;}dt{overflow:hidden;position:absolute;top:0;left:0;width:100px;height:100%;background:#323232;}dd{height:100%;margin-left:100px;overflow:hidden;position:absolute;}#player {width:100%;height:100%;border:0;vertical-align:top;}em{background:url("/i/ls.gif") 0 -158px;height:12px;width:12px;margin:3px 0;cursor:pointer;}em.do{background:url("/i/ls.gif") 0 -170px;}s{position:absolute;width:12px;z-index:10000;display:none;height:110px;}u{background:url("/i/ls.gif") 0 -76px;height:76px;cursor:pointer;}</style><dl id="bofang"><s><em onclick="go(1)"></em><u title="5694美剧"></u><em class="do" onclick="go(2)"></em></s><dt><a href="http://www.5694.com"><img src="/i/lgs.png" /></a><p><b><?php echo $ass['name']; ?></b><?php 
        $sHref = $sOne = '';
        if (!empty($aArr)){ 
            foreach($aArr as $k => $a){
                echo '<i>'.$k.'</i>';
                foreach ($a as $n => $u){
                    if (empty($sOne)){
                        $sOne = $u;
                    }
                    if (((count($aArr)>1 && $k != '优酷') || count($aArr)==1) && empty($sHref) && $n==intval($aJI[0])){
                        $sHref = $u;
                        echo '<a href="'.$u.'" class="c" target="player">第'.$n.'集</a>';
                    }
                    else {
                        echo '<a href="'.$u.'" target="player">第'.$n.'集</a>';
                    }
                }
            }
        }
        ?></p><br /></dt><dd><iframe id="player" name="player" frameborder="0" src="<?php echo empty($sHref)?$sOne:$sHref; ?>"></iframe></dd></dl><?php echo '\';';}?>
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
                    $aHref[$k] = $u;
                    //echo '<a href="'.$u.'" class="c" target="player">第'.$n.'集</a>';
                }
                else {
                    //echo '<a href="'.$u.'" target="player">第'.$n.'集</a>';
                }
            }
        }
    }
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
        echo 'gou = \'\';';
    }
}
?>
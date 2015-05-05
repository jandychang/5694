<?php
$nID = intval($_GET['id']);
if (!empty($nID)){
    include ('./conf.php');
    $a = $gDB->selectOne(' select aid,title,type,det from down where id = '.$nID);
    if (!empty($a)){
        $b = $gDB->selectOne('select name from arc where id ='.$a['aid']);
    }
    
    echo $a['type'].'@@@'.$a['det'].'@@@'.$b['name'].'_'.$a['title'];
}
?>
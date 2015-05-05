<?php
include ('../inc/conf.php');
set_time_limit(0);
while(1){
    $al = $gDB->selectOne(' select id,cid,name,dy from movie where xid=0 order by id asc ');
    if (!empty($al)){
        $gDB->query(' update movie set xid=-1 where id ='.$al['id']);
        $gDB->query(' update movie set xid='.$al['id'].' where id > '.$al['id'].' and cid='.$al['cid'].' and name=\''.addslashes($al['name']).'\' and dy=\''.addslashes($al['dy']).'\' and xid = 0 ');
    }
    else {
        break;
    }
}
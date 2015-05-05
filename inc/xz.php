<?php
include ('./conf.php');
$sChar = 'utf-8';
ini_set('pcre.backtrack_limit', -1);

$aList = $gDB->select(' select id,url from xiazai where etime < '.(time()-3*86400).' order by id asc limit 5 ');
if (!empty($aList)){
    foreach ($aList as $nKey => $aTem){
        gData($aTem['url'], true);
    }    
}

echo 'cai - '.count($aList).'.';

?>
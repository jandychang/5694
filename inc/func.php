<?php

//输入字串到页面的函数
function p($str)
{
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '<br />---------------------------------------<br />';
    if (is_array($str)){
        echo '<pre>';
        print_r($str);
        echo '</pre>';
    }
    else{
        echo $str;
    }
    echo '<br />---------------------------------------<br />';
}

//页码函数
function getPage($nCount, $nList, $nCurr, $sUrl, $sUnit = '', $nGroup = 5){
    if (empty($sUrl)){
        $sUrl = $_SERVER['REQUEST_URI'];
    }
    $nPage = ceil($nCount/$nList);
    $nCurr = $nCurr<1||$nCurr>$nPage?1:$nCurr;    
    $sPage = '';
    if ($nCount > 0){
        $nAt = floor(($nCurr-1)/$nGroup);
        for ($i=1+$nGroup*$nAt; $i<=$nGroup*$nAt+$nGroup&&$i<=$nPage; $i++){
            $sPage .= ($nCurr==$i?'<s>'.$i.'</s>':'<a href="'.str_replace(array('_%p%','%p%'),array(($i>1?'_'.$i:''),($i>1?$i:'')),$sUrl).'">'.$i.'</a>');
        }
        $sPage = ($nCurr>1?'<a href="'.str_replace(array('_%p%','%p%'),array(($nCurr-1>1?'_'.($nCurr-1):''),($nCurr-1>1?$nCurr-1:'')),$sUrl).'">上一页</a>':'<u>上一页</u>').$sPage.($nCurr<$nPage?'<a href="'.str_replace('%p%',$nCurr+1,$sUrl).'">下一页</a>':'<u>下一页</u>');
    }
    return '共'.$nCount.''.$sUnit.($nCurr>2?'<a href="'.str_replace('_%p%','',$sUrl).'">首页</a>':'').$sPage.($nPage>$nCurr+1?'<a href="'.str_replace('%p%',$nPage,$sUrl).'">末页</a>':'').($nPage>5&&preg_match('/\?/i',$sUrl)?'<input id="gp" m="'.$nPage.'" style="width:30px;text-align:center;" value="'.($nCurr<$nPage?$nCurr+1:$nCurr-1).'"/><button onclick="gp()" style="height:22px;line-height:16px;">GO</button>':'');
}
//页码函数
function getPage2($nCount, $nList, $nCurr, $sUrl, $sUnit = '', $nGroup = 5){
    if (empty($sUrl)){
        $sUrl = $_SERVER['REQUEST_URI'];
    }
    $nPage = ceil($nCount/$nList);
    $nCurr = $nCurr<1||$nCurr>$nPage?1:$nCurr;    
    $sPage = '';
    if ($nCount > 0){
        $nAt = floor(($nCurr-1)/$nGroup);
        for ($i=1+$nGroup*$nAt; $i<=$nGroup*$nAt+$nGroup&&$i<=$nPage; $i++){
            $sPage .= ($nCurr==$i?'<s>'.$i.'</s>':'<a href="'.str_replace(array('_%p%','%p%'),array(($i>1?'_'.$i:''),($i>1?$i:'')),$sUrl).'">'.$i.'</a>');
        }
        $sPage = ($nCurr>1?'<a href="'.str_replace(array('_%p%','%p%'),array(($nCurr-1>1?'_'.($nCurr-1):''),($nCurr-1>1?$nCurr-1:'')),$sUrl).'">上一页</a>':'').$sPage.($nCurr<$nPage?'<a href="'.str_replace('%p%',$nCurr+1,$sUrl).'">下一页</a>':'');
    }
    return $sPage;
}
function get_ip()
{
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
    {
	    define('USERIP', getenv('HTTP_CLIENT_IP'));
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
    {
        define('USERIP', getenv('HTTP_X_FORWARDED_FOR'));
    }
    elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
    {
        define('USERIP', getenv('REMOTE_ADDR'));
    }
    elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
    {
       define('USERIP', $_SERVER['REMOTE_ADDR']);
    }
}

function cut($str, $lenth, $flag='...', $start=0)
{
    $str = str_replace(array('\'', '"'), '', $str);
    $len = strlen($str);
    $r = array();
    $n = 0;
    $m = 0;
    for ($i = 0; $i < $len; $i++)
    {
        $x = substr($str, $i, 1);
        $a  = base_convert(ord($x), 10, 2);
        $a = substr('00000000'.$a, -8);
        if ($n < $start)
        {
            if (substr($a, 0, 1) == 0)
            {
            }elseif (substr($a, 0, 3) == 110)
            {
                $i += 1;
            }
            elseif (substr($a, 0, 4) == 1110)
            {
                $i += 2;
            }
            $n++;
        }
        else
        {
            $k = 1;
            if (substr($a, 0, 1) == 0)
            {
                $r[] = substr($str, $i, 1);
            }
            elseif (substr($a, 0, 3) == 110)
            {
                $r[] = substr($str, $i, 2);
                $i += 1;
            }
            elseif (substr($a, 0, 4) == 1110)
            {
                $r[] = substr($str, $i, 3);
                $i += 2;
                $k = 2;
            }
            else
            {
                $r[] = '';
            }
            $m += $k;
            if ($m >= $lenth)
            {
                if ($flag == '...' && $i<$len)
                {
                    $r[count($r)] .= '...';
                }
                elseif($i<$len)
                {
                    $r[count($r)] .= $flag;
                }
                break;
            }
        }
    }
    return join('',$r);
} // End subString_UTF8

//在明确文件夹已经存在的情况下，可以使用此函数生成文件
function write_file ($sFile='', $sContent, $sMode='w'){
    if (empty($sFile)){
        return false;
    }

    if(!$fp = fopen($sFile, $sMode))
        return false;
    else{
        if (false === fwrite($fp, $sContent)){
            fclose($fp);
            return false;
        }
        else{
            fclose($fp);
            return true;
        }
        chmod($sFile,0777);
    }
}
function shtml($sStr)
{
	$aStr = explode("\r\n",$sStr);
	$sStr = '';
	if (!empty($aStr)){
		foreach ($aStr as $sTem){
			$sTem = preg_replace("/[ ]+/",' ',$sTem);
			$sStr .= trim($sTem);
		}
	}
	$sStr = preg_replace('/(style)=".*?"/i','',$sStr);
	$sStr = ereg_replace("\r\n|\r|\n|name=\"\"",'',$sStr);
	return $sStr;
}
function br2nl($sStr)
{
    return preg_replace('/<br[\s|\/]{0,2}>/', "\n",$sStr);
}
function isGb2312($str)
{
	for($i=0; $i<strlen($str); $i++) {
		$v = ord( $str[$i] );
		if( $v > 127) {
			if( ($v >= 228) && ($v <= 233) )
			{
				if( ($i+2) >= (strlen($str) - 1)) return true;  // not enough characters
				$v1 = ord( $str[$i+1] );
				$v2 = ord( $str[$i+2] );
				if( ($v1 >= 128) && ($v1 <=191) && ($v2 >=128) && ($v2 <= 191) ) // utf±à
					return false;
				else
					return true;
			}
		}
	}
	return true;
}

function gPinyin($str, $ishead=0, $isclose=1)
{
    global $pinyins;
    $str = iconv('utf-8','gb2312',$str);
    $restr = '';
    $str = trim($str);
    $slen = strlen($str);
    if($slen < 2)
    {
        return $str;
    }
    if(count($pinyins) == 0)
    {
        $fp = fopen(dirname(__FILE__).'/pinyin.dat', 'r');
        while(!feof($fp))
        {
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0; $i<$slen; $i++)
    {
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c]))
            {
                if($ishead==0)
                {
                    $restr .= $pinyins[$c];
                }
                else
                {
                    $restr .= $pinyins[$c][0];
                }
            }else
            {
                $restr .= "_";
            }
        }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
        {
            $restr .= $str[$i];
        }
        else
        {
            $restr .= "_";
        }
    }
    if($isclose==0)
    {
        unset($pinyins);
    }
    $restr = preg_replace('/(^_+|_+$)/i','',$restr);
    return $restr;
}

function gImg ($sImg,$sPaths,$sRe='',$sName=''){
    $aImg = getimagesize($sImg);
    $aT = array('image/jpg'=>'jpg','image/jpeg'=>'jpg','image/png'=>'png','image/pjpeg'=>'jpeg','image/gif'=>'gif','image/bmp'=>'bmp','image/x-png'=>'png');
    if (!empty($aImg) && isset($aT[$aImg['mime']])){
        $sPath = DROOT.$sPaths;
        /*if (!file_exists($sPath)){
            if(!is_dir($sPath)) {
                mkdir($sPath, 0777);
            }
            if (!file_exists($sPath)){
                return $sImg;
            }
        }*/
        if (empty($sName)){
            $sName = time().substr(microtime(), 2, 3);
        }
        
        $sFile = $sPaths.$sName.'.'.$aT[$aImg['mime']];
        $sFiles= DROOT.$sFile;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sImg);
        @curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt ($ch, CURLOPT_REFERER, $sRe); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);   
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);   
        $sData = curl_exec($ch);   
        curl_close($ch);

        $tp = @fopen($sFiles, 'wb');
        fwrite($tp, $sData);
        fclose($tp);
        
        if (file_exists($sFiles)){
            return $sFile;
        }
        else $sImg;
    }
    else{
        return $sImg;
    }
    
}

function gCurl ($sUrl,$re='',$u=''){
    if (empty($u)){
        $u = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; Alexa Toolbar; .NET CLR 2.0.50727; .NET CLR 3.5.30729)';
    }    
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    @curl_setopt($ch, CURLOPT_GET, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, $u);
    if (!empty($re)){
        curl_setopt ($ch, CURLOPT_REFERER, $re); 
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);             
    $sStr = preg_replace("/\r\n|\n/i",'',curl_exec($ch));   
    curl_close($ch); 
    return $sStr;
}

function bIndex($bInd=0){
    global $gDB,$aCla,$aIndex;

    if (empty($aIndex)){
        $aIndex = $gDB->selectOne(' select * from cla where id = 1 ');
    }
    
    $aHot = $gDB->select(' select id,name,pic,utime from arc where status = 1 order by view desc limit 21 ');
    $aComm= $gDB->select(' select id,name,pic,utime from arc where status = 1 and comm= 1 order by view desc limit 21 ');
    $aLink= $gDB->select(' select * from link where status = 1 and cid = 1 order by ord asc,id asc ');

    $nPageList = 10; //每页显示多少条.
    $sWhere = ' where status = 1 ';

    $nCount = $gDB->getCount('select count(*) as count from arc '.$sWhere);
    $nAll = ceil($nCount/$nPageList);
    
    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/new_%p%', '部美剧');
        $sPage = str_replace('"/new"','/',$sPage);
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/new_%p%', '');
        $aList = $gDB->select(' select * from arc '.$sWhere.' order by utime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        
        $aBO   = gBO(array_keys($aList));

        //echo $sStr;exit();
        if ($nCurr <= 1){
            $aPers  = $gDB->select(' select id,name,name2,npic,fn,sex,pos,bir,addr from person where status = 1 order by etime desc limit 6 ');

            foreach ($aPers as $n => $a){
                echo $a['id'].'-';
                bPerson($a['id']);
            }
            echo '<br />';

            $arNew = $gDB->select(' select id,name,name2,npic,fn,etime,sex,pos,bir,addr from person where status = 1 order by etime desc limit 100 ');

    $sStr = '<?xml  version="1.0" encoding="utf-8"?>
<urlset>
    <url>
        <loc>http://www.5694.com/</loc>
        <lastmod>'.date('Y-m-d').'</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>';
        foreach ($arNew as $n => $a){
            $sStr .= '
    <url>
        <loc>http://www.5694.com/'.$a['fn'].'</loc>
        <lastmod>'.date('Y-m-d',$a['etime']).'</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
';
        }  
        $sStr .= '</urlset>';
        file_put_contents(DROOT.'/sitemap.xml',$sStr);

            ob_start();        
            include (DROOT.'/tpl/index.htm');
            $sStr  = ob_get_clean();
            write_file(DROOT.'/index.htm',$sStr);

            ob_start();        
            include (DROOT.'/tpl/m_index.htm');
            $sStr  = rHtml(ob_get_clean());
            write_file(DROOT.'/h/m/index.htm',$sStr);

        }
        else {
            ob_start();        
            include (DROOT.'/tpl/index.htm');
            $sStr  = ob_get_clean();
            write_file(DROOT.'/h/l/new_'.$nCurr.'.htm',$sStr);
            ob_start();        
            include (DROOT.'/tpl/m_new.htm');
            $sStr  = ob_get_clean();
            write_file(DROOT.'/h/m/new_'.$nCurr.'.htm',rHtml($sStr));
        }
        if ($bInd == 1){
            echo ' Build Homepage End. <br />';
            return true;
        }
        
    }
    //热门程度
    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/hot_%p%', '部美剧');
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/hot_%p%');
        $aList = $gDB->select(' select * from arc '.$sWhere.' order by view desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        $aBO   = gBO(array_keys($aList));
        ob_start();
        include (DROOT.'/tpl/hot.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/l/hot'.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

        ob_start();
        include (DROOT.'/tpl/m_hot.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/m/hot'.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
    }
    echo ' Build Homepage and List End. <br />';
}
function gBO($aid){
    global $gDB;
    $aBO = $gDB->select(' select aid,id from down where aid in('.implode(',',$aid).') group by aid order by id asc ','aid');
    return $aBO;
}
function rHtml($sStr){
    $sStr = preg_replace("/&nbsp;| target=\"_blank\"|　/i",' ',$sStr);
    $sStr = preg_replace("/\r\n|\r|\n/",'',$sStr);
    $sStr = preg_replace("/>[ \t]+</",'><',$sStr);
    $sStr = preg_replace("/>[ \t]+/",'>',$sStr);
    $sStr = preg_replace("/[ \t]+</",' <',$sStr);
    return $sStr;
}
function bDet($nID){
    global $gDB,$aCla,$aIndex;
    $aDet = $gDB->selectOne(' select * from arc where id = '.$nID.' and status = 1 ');
    if (empty($aDet)){
        return false;
    }
    $aHot = $gDB->select(' select id,name,pic from arc where status = 1 '.($aDet['oid']>0?' and oid = '.$aDet['oid']:'').' and id != '.$nID.' order by view desc limit 6 ');
    if (count($aHot)<6){
        $aHot = $gDB->select(' select id,name,pic from arc where status = 1 and id != '.$nID.' order by view desc limit 6 ');
    }
    $aOrd = unserialize($aDet['down']);
    $aTem = $gDB->select(' select id,oid,title,url from down where aid = '.$nID.' order by id asc ');
    $nCmt = $gDB->getCount(' select count(*) as count from cmt where cid = 1 and aid = '.$nID.' and status = 1 ');
    $aCmt = $gDB->select(' select * from cmt where cid = 1 and aid = '.$nID.' and status = 1 order by id desc');
    foreach ($aTem as $nKey => $aTem){
        $aDown[$aTem['oid']][] = $aTem;
    }
    $aLike = rLike($nID,$aDet['name']);
    ob_start();
    include (DROOT.'/tpl/detail.htm');
    $sStr = ob_get_clean();
    write_file(DROOT.'/h/a/'.$nID.'.htm',$sStr);

    ob_start();
    include (DROOT.'/tpl/m_detail.htm');
    $sStr = ob_get_clean();
    write_file(DROOT.'/h/m/'.$nID.'.htm',rHtml($sStr));
    //echo $sStr;
}
function rLike($id,$k){
    global $gDB;
    $str = preg_replace('/第[^第]+$/u','',$k);

    $str = iconv('utf-8','gb2312',$str);
    $str=preg_replace("/[\x80-\xff]{2}/","\\0".chr(0x00),$str);
    $search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】",);
    $str = str_replace($search,' ',$str);
    preg_match_all("/[\x80-\xff]?./",$str,$ar);$ar=$ar[0];
    for ($i=0;$i<count($ar);$i++) if ($ar[$i]!=chr(0x00)) $ar_new[]=$ar[$i];
    $ar=$ar_new;unset($ar_new);$oldsw=0;
    $an = array();
    for ($i=0; $i<=5&&$i<count($ar)-2; $i++){
        if (isset($ar[$i])){
            $an[] = ' name like \'%'.iconv('gb2312','utf-8',$ar[$i].$ar[$i+1].$ar[$i+2]).'%\'';
        }
    }
    if (!empty($an)){
        $ls = $gDB->select(' select id,name from arc where status = 1 and id != '.$id.' and ('.implode(' or ',$an).') limit 4');
        return $ls;
    }
    else {
        return ;
    }
}
function bPlay($nPID){
    global $gDB,$aCla,$aIndex;
    $aDw  = $gDB->selectOne(' select * from down where id = '.$nPID.' ');
    $nID  = $aDw['aid'];
    $aDet = $gDB->selectOne(' select * from arc where id = '.$nID.' and status = 1 ');
    if (empty($aDet)){
        return false;
    }
    /*if (preg_match('/^ftp/i',$aDw['det'])){
        $sStr = 'FTP下载地址：<a href="'.$aDw['det'].'">'.$aDw['det'].'</a> 可右下另存为或选择下载工具下载！';
    }
    else*/
    if (0 && preg_match('/^http/i',$aDw['det'])){
        $sStr = '<script type="text/javascript">document.location.href="'.$aDw['det'].'";</script>';
        write_file(DROOT.'/h/p/'.$nPID.'.htm',$sStr);
        write_file(DROOT.'/h/mp/'.$nPID.'.htm',$sStr);
    }
    else{
        $aHot = $gDB->select(' select id,name,utime from arc where status = 1 '.($aDet['oid']>0?' and oid = '.$aDet['oid']:'').' order by view desc limit 18 ');
        $aComm= $gDB->select(' select id,name,utime from arc where status = 1 '.($aDet['oid']>0?' and oid = '.$aDet['oid']:'').' order by comm limit 18 ');

        $aGuan= $gDB->select(' select id,name,pic from arc where status = 1 '.($aDet['oid']>0?' and oid = '.$aDet['oid']:'').' and id != '.$nID.' order by rand() limit 4 ');
        if (count($aGuan)<4){
            $aHot = $gDB->select(' select id,name,pic from arc where status = 1 and id != '.$nID.' order by rand() limit 4 ');
        }    
        $aOrd = unserialize($aDet['down']);
        $aTem = $gDB->select(' select id,oid,title,url from down where aid = '.$nID.' order by id asc ');
        foreach ($aTem as $nKey => $aTem){
            $aDown[$aTem['oid']][] = $aTem;
        }    
        ob_start();
        include (DROOT.'/tpl/play.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/p/'.$nPID.'.htm',$sStr);

        ob_start();
        include (DROOT.'/tpl/m_play.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/mp/'.$nPID.'.htm',$sStr);
    }
    
    //echo $sStr;
}
function bCla($nOID){
    global $gDB,$aCla,$aIndex;
    $sName = $aCla[$nOID]['name'];
    $sPY   = $aCla[$nOID]['pinyin'];

    $aHot = $gDB->select(' select id,name,utime from arc where status = 1 and oid = '.$nOID.' order by view desc limit 18 ');
    $aComm= $gDB->select(' select id,name,utime from arc where status = 1 and oid = '.$nOID.' order by comm limit 18 ');

    $nPageList = 10; //每页显示多少条.
    $sWhere = ' where status = 1 and oid = '.$nOID.' ';
    $nCount = $gDB->getCount('select count(*) as count from arc '.$sWhere);
    $nAll = ceil($nCount/$nPageList);
    $nAll = $nAll<=1?1:$nAll;

    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部美剧');
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%');
        $aList= $gDB->select(' select * from arc '.$sWhere.' order by utime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        $aBO   = gBO(array_keys($aList));
        ob_start();
        include (DROOT.'/tpl/cla.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);
        ob_start();
        include (DROOT.'/tpl/m_cla.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
    }
    
    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_hot_%p%', '部美剧');
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_hot_%p%');
        $aList= $gDB->select(' select * from arc '.$sWhere.' order by view desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        $aBO   = gBO(array_keys($aList));
        ob_start();
        include (DROOT.'/tpl/cla_hot.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/l/'.$sPY.'_hot'.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

        ob_start();
        include (DROOT.'/tpl/m_cla_hot.htm');
        $sStr = ob_get_clean();
        write_file(DROOT.'/h/m/'.$sPY.'_hot'.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
    }
    echo '生成【'.$sName.'】分类<br />';
}
function bDate(){
    global $gDB,$aCla,$aIndex;
    $aArr = $gDB->select(' select year from arc where year > 0 group by year order by year asc ');
    foreach ($aArr as $z){
        $nYear = $z['year'];

        $aHot = $gDB->select(' select id,name,utime from arc where status = 1 and year = '.$nYear.' order by view desc limit 18 ');
        $aComm= $gDB->select(' select id,name,utime from arc where status = 1 and year = '.$nYear.' order by rand() limit 18 ');

        $nPageList = 10; //每页显示多少条.
        $sWhere = ' where status = 1 and year = '.$nYear.' ';
        $nCount = $gDB->getCount('select count(*) as count from arc '.$sWhere);
        $nAll = ceil($nCount/$nPageList);
        
        for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
            $sPage = getPage($nCount, $nPageList, $nCurr, '/d'.$nYear.'_%p%', '部美剧');
            $sPage2= getPage2($nCount, $nPageList, $nCurr, '/d'.$nYear.'_%p%');
            $aList = $gDB->select(' select * from arc '.$sWhere.' order by utime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
            $aBO   = gBO(array_keys($aList));
            ob_start();
            include (DROOT.'/tpl/date.htm');
            $sStr = ob_get_clean(); //echo $sStr; exit();
            write_file(DROOT.'/h/l/d'.$nYear.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

            ob_start();
            include (DROOT.'/tpl/m_date.htm');
            $sStr = ob_get_clean(); //echo $sStr; exit();
            write_file(DROOT.'/h/m/d'.$nYear.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
        }
        
        for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
            $sPage = getPage($nCount, $nPageList, $nCurr, '/h'.$nYear.'_%p%', '部美剧');
            $sPage2= getPage($nCount, $nPageList, $nCurr, '/h'.$nYear.'_%p%');
            $aList = $gDB->select(' select * from arc '.$sWhere.' order by view desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
            $aBO   = gBO(array_keys($aList));
            ob_start();
            include (DROOT.'/tpl/date_hot.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/l/h'.$nYear.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);
            ob_start();
            include (DROOT.'/tpl/m_date_hot.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/m/h'.$nYear.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
        }
    }
    echo '生成 年份列表<br />';
}
function gCla($nCla=1,$dd=1){
    global $gDB,$aCla,$aIndex;
    $sCla = '';
    if (empty($aCla)){
        $aCla = $gDB->select(' select * from cla where id > 1 order by id asc ','id');
    }
    
    foreach ($aCla as $nKey => $aTem){
        $sCla .= '<a href="/'.$aTem['pinyin'].'"'.($aTem['id']==$nCla?' class="c"':'').'>'.$aTem['name'].'</a>';
    }
    
    return '<p id="e"><a href="/"'.($nCla==1?' class="c"':'').'>5694</a>'.$sCla.'</p>'.($dd==1?'<p id="d">'.($nCla==1?$aIndex['kw']:$aCla[$nCla]['kw']).'</p>':'');
}
function gCla2($nCla=1){
    global $aCla;
    $sCla = '';
    foreach ($aCla as $nKey => $aTem){
        $sCla .= '<a href="/'.$aTem['pinyin'].'"'.($aTem['id']==$nCla?' class="c"':'').'>'.$aTem['name'].'</a>';
    }
    return '<i><a href="/"'.($nCla==1?' class="c"':'').'>全部</a>'.$sCla.'</i>';
}
function gCla3($nDate){
    global $aCla;
    $sCla = '';
    for ($i=date('Y'); $i>=2002; $i--){
        $sCla .= '<a href="/d'.$i.'"'.($nDate==$i?' class="c"':'').'>'.$i.'</a>';
    }
    return '<i><a href="/"'.($nDate==0?' class="c"':'').'>全部</a>'.$sCla.'</i>';
}
function c($sUrl){
    global $sChar,$gDB,$bHome,$aLst;
    $sStr = gCurl($sUrl,'http://www.repian.com/');
    preg_match('/charset=([^"]+)/i',$sStr,$a);
    //p($a);
    if (strtolower($sChar) != strtolower($a[1])){
        $sStr = iconv($a[1],$sChar.'//ignore',$sStr);
    }
    preg_match('/<div class="channel-content">(.*?)<\/div>/i',$sStr,$aArr);
    //p($aArr[1]);
    if (!empty($aArr[1])){
        preg_match_all('/<a href="([^"]+)" title="([^"]+)"[^>]+><img src="([^"]+)"[^>]+>/i',$aArr[1],$aBrr);
        preg_match_all('/<em>年份：(.*?)<\/em>/i',$aArr[1],$aYear);
        preg_match_all('/<p><b>时间：<\/b>(\d{2}-\d{2})<\/p>/i',$aArr[1],$aDay);
        //unset($aBrr[0]);
        if (!empty($aBrr[3])){
            foreach ($aBrr[3] as $nKey => $sTem){
                if (preg_match('/nopic\.gif$/i',$sTem)){
                    $aBrr[3][$nKey] = '';
                }
                elseif (!preg_match('/^http/i',$sTem)){
                    $aBrr[0][$nKey] = str_replace($sTem,'http://www.repian.com'.$sTem,$aBrr[0][$nKey]);
                    $aBrr[3][$nKey] = 'http://www.repian.com'.$sTem;
                }
                //echo '<img src="'.$aBrr[3][$nKey].'" />';
            }

            foreach ($aBrr[1] as $n => $s){
                $s = trim($s);
                $aT = $gDB->selectOne(' select id,utime,status from arc where url = \''.$s.'\' ');
                p($aT);
                if (!empty($aT) && date('m-d',$aT['utime'])!= $aDay[1][$n]){
                    d($aT['id'], $aT['status'], 'http://www.repian.com'.$s);
                    echo $aBrr[2][$n].' - '.date('m-d',$aT['utime']).' | '.$aDay[1][$n].'<br />';
                    $bHome = true;
                    $aLst[] = $aT['id'];
                }
                elseif (empty($aT)){
                    $sSql = ' insert into arc(id,cid,oid,status,stime,utime,view,comm,name,year,url,pic,opic)values(NULL,13,0,0,'.time().',0,0,0,\''.addslashes($aBrr[2][$n]).'\',\''.trim($aYear[1][$n]).'\',\''.$s.'\',\'\',\''.$aBrr[3][$n].'\') ';
                    $gDB->query($sSql);
                    $bHome = true;
                    $aLst[] = mysql_insert_id();
                }
            }
        }
        else {
            echo '无法取电影列表.<br />';
        }
    }
    else {
        echo '无法取首页列表.<br />';
    }
    echo 'Homepage End. '.$sUrl.' -> <br />';
}
function d($nID, $nStatus, $sUrl){
    global $sChar,$gDB;

    $sStr = gCurl($sUrl,'http://www.repian.com/');

    preg_match('/charset=([^"]+)/i',$sStr,$a);

    if (strtolower($sChar) != strtolower($a[1])){
        $sStr = iconv($a[1],$sChar.'//ignore',$sStr);
    }
    preg_match('/<dt>主演：<\/dt>[^<]?<dd>(.*?)<\/dd>.*?<dt>语言：<\/dt><dd[^>]+>(.*?)<\/dd>.*?<dt>时间：<\/dt><dd>(.*?)<\/dd>/i',$sStr,$aB);
    preg_match('/<div class="introduction" itemprop="description">(.*?)<\/div>/i',$sStr,$aD);
    $sDet = trim(strip_tags($aD[1]));
    preg_match_all('/<div class="play-list b mb">(.*?)<\/ul>[^<]+<\/div>/i',$sStr,$aP);
    $aDown = array();

    if (!empty($aP[0])){
        foreach ($aP[0] as $n => $s){
            preg_match('/<h3><span[^>]+><\/span>([^<]+).*?<\/h3>/i',$s,$aN);
            preg_match_all('/<li><a.*?href=\'(.*?)\'>(.*?)<\/a><\/li>/i',$s,$aLK);

            $sName = trim($aN[1]);
            if (empty($sName)){
                '<br />ERROR: '.$sUrl.'<br />';
                exit();
            }
            $nOID = $n+1;
            $aDown[$nOID] = $sName;

            foreach ($aLK[1] as $m => $k){
                if (!$gDB->getCount(' select count(*) as count from down where (oid = '.$nOID.' and url = \''.$k.'\')or(oid = '.$nOID.' and title = \''.addslashes(trim($aLK[2][$m])).'\' and aid='.$nID.') ')){
                    $sSql = ' insert into down(id,aid,oid,status,title,url,type,det) values(NULL,'.$nID.','.$nOID.','.(!preg_match('/^http/i',$k)||preg_match('/yiyi\.cc|repian\.com/i',$k)?'0':'1').',\''.addslashes(trim($aLK[2][$m])).'\',\''.$k.'\',\'\',\'\') ';
                    $gDB->query($sSql);
                }
            }
        }
    }
    if (!empty($aB)){
        if ($nStatus < 1){
            $sSql = ' update arc set status=2,utime='.strtotime($aB[3]).',zy=\''.addslashes(trim($aB[1]))      
                    .'\',lang=\''.addslashes(trim($aB[2])).'\',down=\''.serialize($aDown).'\',detail=\''.addslashes($sDet).'\' where id = '.$nID;
        }
        else {
            $sSql = ' update arc set utime='.strtotime($aB[3]).',down=\''.serialize($aDown).'\' where id = '.$nID;
        }
        $gDB->query($sSql);
    }
}

function u($nID,$url=''){
    global $gDB,$aPly;
    $aArc = $gDB->selectOne(' select id,down,url from arc where id = '.$nID);
    if (empty($aArc)){
        return false;
    }    

    $sUrl = empty($url)?'http://www.repian.com'.$aArc['url'].preg_replace('/^.*\/([^\/]+)\/$/i','$1',$aArc['url']).'.js':$url;
    $sStr = gCurl($sUrl,'http://www.repian.com/');
    $aUrl = array();

    if (preg_match('/(404 错误|404错误|404.0 - Not Found)/i',$sStr)){
        $aTem = $gDB->selectOne(' select id,url from down where aid = '.$aArc['id'].' and type = \'\' order by id desc ');
        if (!empty($aTem)){
            $sUrl = 'http://www.repian.com'.preg_replace('/\/(\d+)\/.*?\.html$/i','/$1/$1.js',$aTem['url']);
        }
        $sStr = gCurl($sUrl,'http://www.repian.com/');
    }


    if (!empty($sStr)){
        if (empty($url) && preg_match('/404错误/i',$sStr)){
            $aTem = $gDB->selectOne(' select url from down where aid = '.$nID.' and url not like \'http%\' ');
            if (empty($aTem)){
                echo 'Error : '.$nID.' = '.$sUrl.' 页面不存在<br />'; exit();
            }
            else {
                u($nID,preg_replace('/^(.*\/\d+)_[^\.]+\.html$/i','http://www.repian.com$1.js',$aTem['url']));
                return false;
            }
        }
        else if(!empty($url) && preg_match('/404错误/i',$sStr)){
            echo 'Error : '.$nID.' = '.$sUrl.' 页面不存在<br />'; exit();
        }
        echo $nID.' = <br />';  //'.$sUrl.'

        $sStr = str_replace("'+'",'',$sStr);
        $sStr = preg_replace("/unescape\('([^']+)'\)\+?/i",'$1',$sStr);
        preg_match("/stringReplaceAll\('(.*?)','(\d+)',([^\)]+)\)\)/i",$sStr,$arr);

        $sStr = unescape(preg_replace('/'.$arr[2].'/i',unescape($arr[3]),$arr[1]));
        //$sStr = iconv('GB2312','UTF-8//ignore',$sStr);

        //$sStr = preg_replace('/阿加莎(.*?)克里斯蒂/','阿加莎・克里斯蒂',$sStr);
        //p($sStr);
        

        if (!empty($arr[1])){
            $aArr = explode('$$$',$sStr);
            foreach ($aArr as $n => $s){
                $aBrr = explode('$$',$s);
                if (!empty($aBrr)){
                    $aCrr = explode('#',$aBrr[1]);

                    foreach ($aCrr as $e){
                        $e = trim($e);
                        preg_match('/^(.*?)\$(.*?)\|?\$([a-z]+|未知)/i',$e,$aDrr);
                        if (preg_match('/缺/',$e)){
                            echo 'ID:<b>'.$nID.'</b>  Error:'.$e.' --- 缺<br />';
                        }
                        else{
                            if (!empty($e) && !empty($aDrr) && preg_match('/:\/\/|tudou|youku|iask|qiyi|qq/i',$e)){
                                $aUrl[$aBrr[0]][$aDrr[1]] = array($aDrr[2],$aDrr[3]);
                            }
                            elseif(!empty($e)) {
                                echo 'ID:<b>'.$nID.'</b>  Error:'.$e.'<br />';
                            }
                        }
                    }
                }
            }
            //p($aUrl); exit();
        }
    }
    else {
        echo 'Error : '.$nID.' = '.$sUrl.'<br />';
        return;
        exit();
    }
    //p($aUrl);
    if(!empty($aUrl)){
        if (empty($aArc['down'])){
            $n=1; $aDW=array();
            foreach ($aUrl as $s => $a){
                $aDW[$n++] = $s;
            }
            $gDB->query(' update arc set down = \''.serialize($aDW).'\' where id = '.$nID);
        }
        else {
            $aDW   = unserialize($aArc['down']);
        }
        $aDown = $gDB->select(' select id,oid,title from down where aid = '.$nID.' '); // and type = \'\' 
        $aArr  = array();
        foreach ($aDown as $n => $a){
            if (isset($aUrl[$aDW[$a['oid']]]) && isset($aUrl[$aDW[$a['oid']]][$a['title']])){
                $sD = preg_replace('/第十二季(.*?)三幕悲剧/','第十二季・三幕悲剧',$aUrl[$aDW[$a['oid']]][$a['title']][0]);
                $sSql = ' update down set type = \''.$aUrl[$aDW[$a['oid']]][$a['title']][1].'\', det=\''.$sD.'\' where id = '.$a['id'];
                $gDB->query($sSql);
                $aPly[] = $a['id'];
            }
        }
        return;
        exit();
    }
    else {
        p($sStr);
        echo 'Error : '.$nID.' = '.$sUrl.'<br />';
        return;
        exit();
    }
}
function unescape($str){ 
    $ret = ''; 
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++){ 
        if ($str[$i] == '%' && $str[$i+1] == 'u'){ 
            $val = hexdec(substr($str, $i+2, 4));
            if ($val < 0x7f) $ret .= chr($val); 
            else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
            else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 
            $i += 5; 
        } 
        else if ($str[$i] == '%'){ 
            $ret .= urldecode(substr($str, $i, 3)); 
            $i += 2; 
        } 
        else $ret .= $str[$i]; 
    }
    return $ret; 
}
function gIp(){
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
	    $sIP = getenv('HTTP_CLIENT_IP');
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
        $sIP =getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
        $sIP =getenv('REMOTE_ADDR');
    }
    elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
       $sIP =$_SERVER['REMOTE_ADDR'];
    }
    return sprintf("%u", ip2long($sIP));
}

function gData($sUrl,$bRE=false){
    global $gDB;
    if (!$bRE){    
        $aXZ = $gDB->selectOne(' select id,etime from xiazai where url = \''.$sUrl.'\' ');
        if (!empty($aXZ) && $aXZ['etime'] > time()-3*86400){
            return $aXZ['id'];
        }
    }
    $s = gCurl($sUrl);
    $s = preg_replace('/<a class="f5" style="cursor: text;">\|<\/a>/i','',$s);
    preg_match_all('/<li format="all" season="(\d+)"><a>(.*?)<\/a>/i',$s,$m);
    preg_match('/<h2[^>]+>.*?《(.*?)》/u',$s,$t);
    if (!empty($m[1])){
        $aArr = array();
        foreach ($m[1] as $k => $n){
            preg_match('/<ul[^>]+season="'.$n.'"[^>]+>(.*?)<\/ul>/i',$s,$x);
            //p($x);
            if (!empty($x[1])){
                preg_match_all('/<li itemid="(\d+)"[ ]+format="([^"]+)">.*?<span[^>]+>(.*?)<\/span>.*?<font class="f5">(.*?)<\/font>.*?(.*?)播/i',$x[1],$a);
                if (empty($a[5])){
                    preg_match_all('/<li itemid="(\d+)"[ ]+format="([^"]+)">.*?<span[^>]+>(.*?)<\/span>.*?(.*?)播/i',$x[1],$a);
                    $a[5] = $a[4];
                    $a[4] = array();
                }
                if (!empty($a[5])){
                    //p($a);
                    foreach ($a[5] as $i => $c){
                        //p($a[0][$i]);
                        $aDZ = array('驴'=>'','磁'=>'','迅'=>'','车'=>'','盘'=>'');
                        preg_match_all('/<a.*?([^"]+\/\/[^"]+).*?>(.*?)<\/a>/i',$c,$b);
                        if (!empty($b[1])){
                            foreach ($b[1] as $p => $q){
                                $aDZ[trim($b[2][$p])] = $q;
                            }
                        }
                        //p($aDZ);
                        $aArr[$m[2][$k]][] = array($a[1][$i],trim(strtoupper($a[2][$i])),trim(strip_tags($a[3][$i])),trim($a[4][$i]),$aDZ,(preg_match('/<img/i',$a[3][$i])?1:0));
                    }
                }
                else {
                    //wf('./Logs.txt',date('Y-m-d H:i:s > 无法解析列表 > '.$sUrl));
                    return '无法解析列表';
                }
            }
            else {
                //wf('./Logs.txt',date('Y-m-d H:i:s > 无法解析分栏 > '.$sUrl));
                return '无法解析分栏';
            }
            //p($aArr);
        }
        if (!empty($aArr)){
            //wf('./Logs.txt',date('Y-m-d H:i:s > 更新成功！ > '.$sUrl));
            $aXZ = $gDB->selectOne(' select id from xiazai where url = \''.$sUrl.'\' ');
            $sTXT= addslashes(serialize($aArr));
            if (empty($aXZ)){
                $sSql = ' insert into xiazai(id,ctime,etime,title,url,text)VALUES(NULL,'.time().','.time().',\''.$t[1].'\',\''.$sUrl.'\',\''.$sTXT.'\'); ';
                $gDB->query($sSql);
                $aXZ['id'] = mysql_insert_id();
            }
            else {
                $sSql = ' update xiazai set title=\''.$t[1].'\',text = \''.$sTXT.'\',etime = \''.time().'\' where id = '.$aXZ['id'];
                $gDB->query($sSql);
                
            }
            bData($aXZ['id']);
            if (!file_exists(DROOT.'/h/x/'.$aXZ['id'].'.htm')){
                return '生成静态文件失败';
            }
            return $aXZ['id'];
        }
    }
    else {
        //wf('./Logs.txt',date('Y-m-d H:i:s > 无法读取文件 > '.$sUrl));
        return '无法读取文件';
    }
}
function bData($nID){
    global $gDB;
    $aXZ = $gDB->selectOne(' select * from xiazai where id = '.$nID);
    if (empty($aXZ)){
        return false;
    }
    $ax = unserialize($aXZ['text']);
    $sStr = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>《'.$aXZ['title'].'》剧集下载</title><link href="/i/x.css" rel="stylesheet" type="text/css" /><script type="text/javascript" src="/i/j.js"></script><base target="_blank" /></head><body><dl id="ls"><dt>';
    $n=0;$t1=$t2=$t3='';
    foreach ($ax as $s => $aTem){
        $t1 .= '<a href="#"  class="t0 s2'.$n.($n==0?' c':'').'" onclick="s2('.$n.',$(this));return false;">'.$s.'</a>';
        $t3 .= '<dd'.($n==0?'':' style="display:none"').'>';
        foreach ($aTem as $k => $aTem2){
            $t4[$n][$aTem2[1]] = '<a href="#" class="'.$aTem2[1].'" onclick="s1('.$n.',\''.$aTem2[1].'\',$(this));return false;">'.$aTem2[1].'</a>';

            $t2[$aTem2[1]] = '<a href="#" class="'.$aTem2[1].'" onclick="s1(\''.$aTem2[1].'\',$(this));return false;">'.$aTem2[1].'</a>';
            $t3 .= '<p class="'.$aTem2[1].($aTem2[5]==1?' n':'').'"><b>'.$aTem2[1].'</b><i>'.$aTem2[2].'</i><u>'.$aTem2[3].'</u><s>';
            $a3 = array();
            foreach ($aTem2[4] as $o => $p){
                if (!empty($p)){
                    $a3[] ='<a href="'.(preg_match('/ConvertURL2FG/i',$p)?'" onclick="'.$p.';return false':$p).'">'.$o.'</a>';
                }
                else {
                    $a3[] =$o;
                }
            }
            $t3 .= implode('|',$a3);;
            $t3 .= '</s></p>';
        }
        $t3 .= '</dd>';
        $n++;
    }
    $sStr .= $t1;
    foreach ($t4 as $n => $aTem){
        $sStr .= '<p '.($n>0?'style="display:none"':'').'><a href="#" onclick="s1('.$n.',0,$(this));return false;" class="c all">全部</a>'.implode('',$aTem).'</p>';
    }
    $sStr .= '</dt>'.$t3.'</dl><div style="display:none;"><script>x();</script></div></body></html>';
    write_file(DROOT.'/h/x/'.$nID.'.htm',$sStr);
}
function bPList(){
    global $gDB,$aCla,$aIndex;

    $nPageList = 20; //每页显示多少条.
    $sWhere = ' where status = 1 ';
    $nCount = $gDB->getCount('select count(*) as count from person '.$sWhere);
    $nAll = ceil($nCount/$nPageList);
    $nAll = $nAll<=1?1:$nAll;
    $sPY  = 'yanyuan';
    $aComm= $gDB->select(' select id,name,fn,ynum from person '.$sWhere.' and comm = 1 order by id desc limit 20');
    $aHot = $gDB->select(' select id,name,fn,ynum from person '.$sWhere.' order by ynum desc limit 20');

    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '个演员');
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%');

        $aList= $gDB->select(' select id,name,name2,npic,fn,sex,pos,bir,addr from person '.$sWhere.' order by etime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        ob_start();
        include (DROOT.'/tpl/yanyuan.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

        ob_start();
        include (DROOT.'/tpl/m_yanyuan.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
    }
    return true;
}
function bTVList(){
    global $gDB,$aCla,$aIndex;

    $nPageList = 20; //每页显示多少条.
    $sWhere = ' where fabu = 1 and cid = 1 and xid=-1 ';
    $nCount = $gDB->getCount('select count(*) as count from movie '.$sWhere);
    $nAll = ceil($nCount/$nPageList);
    $nAll = $nAll<=1?1:$nAll;
    $sPY  = 'tv';
    $aComm= $gDB->select(' select id,name,fn,etime from movie '.$sWhere.' order by rand() limit 20');
    $aHot = $gDB->select(' select id,name,fn,etime from movie '.$sWhere.' order by view desc limit 20');

    for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
        $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部电视剧');
        $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%');
        $aList= $gDB->select(' select id,name,fn,npic,name2,ji,dy,addr,k from movie '.$sWhere.' order by etime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
        ob_start();
        include (DROOT.'/tpl/tv.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);
        ob_start();
        include (DROOT.'/tpl/m_tv.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
    }
    return true;
}
function bMVList(){
    global $gDB,$aCla,$aIndex;

    $nPageList = 10; //每页显示多少条.
    $sWhere = ' where fabu = 1 and cid = 2 and xid=-1 ';
    $nCount = $gDB->getCount('select count(*) as count from movie '.$sWhere);
    $nAll = ceil($nCount/$nPageList);
    $nAll = $nAll<=1?1:$nAll;
    $sPY  = 'dianying';
    $aComm= $gDB->select(' select id,name,fn,etime from movie '.$sWhere.' order by rand() limit 20');
    $aHot = $gDB->select(' select id,name,fn,etime from movie '.$sWhere.' order by view desc limit 20');
    
    if ($nAll>0){
        for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
            $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部电影');
            $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部电影');
            $aList= $gDB->select(' select id,name,fn,npic,name2,year,`long`,dy,addr,k from movie '.$sWhere.' order by etime desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
            ob_start();
            include (DROOT.'/tpl/dianying.htm');
            $sStr = ob_get_clean(); 
            write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);
            ob_start();
            include (DROOT.'/tpl/m_dianying.htm');
            $sStr = ob_get_clean(); 
            write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
        }
    }else{
        ob_start();
        include (DROOT.'/tpl/dianying.htm');
        $sStr = ob_get_clean(); 
        write_file(DROOT.'/h/l/'.$sPY.'.htm',$sStr);
    }
    return true;
}
function bMV($nID,$nType=1){
    global $gDB,$aCla,$aIndex;
	$aArc = $gDB->selectOne(' select * from movie where cid = '.$nType.' and id = '.$nID);
    $f = DROOT.'/h/l/'.$aArc['fn'].'.htm';
    if (empty($aArc) || $aArc['fabu'] != 1){
        @unlink($f);
        return false;
    }
    if (!empty($aArc)){        
        $aUID = array();
        $aDID = explode(',',$aArc['did']);
        $aYID = explode(',',$aArc['yid']);

        if (!empty($aDID)){ 
            foreach($aDID as $n => $id){
                if (empty($id)){
                    unset($aDID[$n]);
                }else {
                    $aUID[$id] = $id;
                }
            }
        }
        if (!empty($aYID)){ 
            foreach($aYID as $n => $id){
                if (empty($id)){
                    unset($aYID[$n]);
                }else {
                    $aUID[$id] = $id;
                }
            }
        }
        if (!empty($aUID)){ 
            $aUser = $gDB->select(' select id,oid,fn,npic,status,name from person where oid in('.implode(',',$aUID).') ','oid');
        }
        if (!empty($aArc['did'])){
            $aLike = $gDB->select(' select id,fn,npic,name,fabu,ji,year from movie where id != '.$aArc['id'].' and (did '.(preg_match('/,/',$aArc['did'])?' in ('.$aArc['did'].') ':' = '.$aArc['did']).(empty($aArc['yid'])?'':' or yid '.(preg_match('/,/',$aArc['yid'])?' in ('.$aArc['yid'].') ':' = '.$aArc['yid'])).') order by fabu desc,fen desc limit 12');
        }else {
            $aLike = $gDB->select(' select id,fn,npic,name,fabu,ji,year from movie where cid = '.$nType.' and xid = -1 and id != '.$nID.' order by rand() limit 12 ');
        }
        ob_start();
        if ($nType==1){
            include (DROOT.'/tpl/tv2.htm');
        }
        else {
            include (DROOT.'/tpl/mv2.htm');
        }
        $sStr = ob_get_clean();
        //echo $sStr;
        write_file($f,$sStr);
        //exit();
        return true;
    }
    return false;
}
function bPerson($nID){
    global $gDB,$aCla,$aIndex;
	$aArc = $gDB->selectOne(' select * from person where id = '.$nID);
    $f = DROOT.'/h/l/'.$aArc['fn'].'.htm';
    $f2= DROOT.'/h/m/'.$aArc['fn'].'.htm';
    if (empty($aArc) || $aArc['status'] != 1){
        @unlink($f);
        return false;
    }
    if (!empty($aArc)){        
        $aTT = explode(',',$aArc['did']);
        if (!empty($aTT)){ 
            foreach($aTT as $i){
                if ($i > 0){
                    $aDID[$i] = $i;
                }
            }
        }
        $aTT = explode(',',$aArc['yid']);
        if (!empty($aTT)){ 
            foreach($aTT as $i){
                if ($i > 0){
                    $aYID[$i] = $i;
                }
            }
        }
        if (!empty($aDID)){
            $aMo[0][0] = $gDB->select(' select id,cid,oid,name,npic from movie where id in ('.(implode(',',$aDID)).') and cid = 1 order by cid,id asc ','id');
            $aMo[0][1] = $gDB->select(' select id,cid,oid,name,npic from movie where id in ('.(implode(',',$aDID)).') and cid = 2 order by cid,id asc ','id');
        }
        if (!empty($aYID)){
            $aMo[1][0] = $gDB->select(' select id,cid,oid,name,npic,ji from movie where id in ('.(implode(',',$aYID)).') and cid = 1 order by view,id desc limit 48 ','id');
            $aMo[1][1] = $gDB->select(' select id,cid,oid,name,npic,year from movie where id in ('.(implode(',',$aYID)).') and cid = 2 order by fen,id desc limit 48 ','id');
            
            $aMo[2][0] = $gDB->getCount(' select count(*) as count from movie where id in ('.(implode(',',$aYID)).') and cid = 1 ');
            $aMo[2][1] = $gDB->getCount(' select count(*) as count from movie where id in ('.(implode(',',$aYID)).') and cid = 2 ');
        }
        $aLike = $gDB->select(' select id,fn,npic,name from person where status = 1 and id != '.$nID.' order by rand() limit 6 ');

        ob_start();
        include (DROOT.'/tpl/person.htm');
        $sStr = ob_get_clean();
        write_file($f,$sStr);
        ob_start();
        include (DROOT.'/tpl/m_person.htm');
        $sStr = ob_get_clean();
        write_file($f2,rHtml($sStr));

        $nPageList = 30; //每页显示多少条.
        $sWhere = ' where '.(empty($aYID)?' 0 ':'id in ('.(implode(',',$aYID)).') ').' and cid = 2 ';
        $nCount = $gDB->getCount('select count(*) as count from movie '.$sWhere);
        $nAll = ceil($nCount/$nPageList);
        $nAll = $nAll<=1?1:$nAll;
        $sPY  = $aArc['fn'].'_dianying';
        for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
            $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部');
            $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%');
            $aList= $gDB->select(' select * from movie '.$sWhere.' order by id desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
            ob_start();
            include (DROOT.'/tpl/person_dianying.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

            ob_start();
            include (DROOT.'/tpl/m_person_dianying.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));

        }
        $sWhere = ' where '.(empty($aYID)?' 0 ':'id in ('.(implode(',',$aYID)).') ').' and cid = 1 ';
        $nCount = $gDB->getCount('select count(*) as count from movie '.$sWhere);
        $nAll = ceil($nCount/$nPageList);
        $nAll = $nAll<=1?1:$nAll;
        $sPY  = $aArc['fn'].'_tv';
        for ($nCurr=1; $nCurr<=$nAll; $nCurr++){
            $sPage = getPage($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%', '部');
            $sPage2= getPage2($nCount, $nPageList, $nCurr, '/'.$sPY.'_%p%');
            $aList= $gDB->select(' select * from movie '.$sWhere.' order by id desc limit '.($nCurr>1?($nCurr-1)*$nPageList.',':'').' '.$nPageList,'id');
            ob_start();
            include (DROOT.'/tpl/person_tv.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/l/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',$sStr);

            ob_start();
            include (DROOT.'/tpl/m_person_tv.htm');
            $sStr = ob_get_clean();
            write_file(DROOT.'/h/m/'.$sPY.($nCurr>1?'_'.$nCurr:'').'.htm',rHtml($sStr));
        }
        return true;
    }
    return false;
}
?>
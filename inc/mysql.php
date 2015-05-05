<?php
/*
* 功能：创建一个PHP操作MYSQL的类
* 编者：HUI
* 日期：2007年1月4日
*/
class cMysql
{
    var $sHost = 'localhost'; //数据库服务器
    var $sName = 'root';		  //数据库账号
    var $sPass = 'password';		  //数据库密码
    var $sData = '5694';		  //数据库名称
    var $sConn = '';		  //数据库连接
    var $sChar = 'utf8';	  //数据库编码

    var $bOutput = true;	  // halt()是否输出mysql的提示错误
    var $bLog = true;		  // query_log()是否把数据库操作语句写入自定义的日志
    var $result = '';
    /*
    * 功能：构造函数，
    * 参数：$sName->用户名
    		$sPass->密码
    		$sData->数据库名
    		$sHost->数据库服务器
    		$sChar->数据编码
    */
    function cMysql ($sName, $sPass, $sData, $sHost, $sChar)
    {
        $this->sName = $sName;
        $this->sPass = $sPass;
        $this->sData = $sData;

        if (!empty($sHost))
        {
            $this->sHost = $sHost;
        }
        if (!empty($sChar))
        {
            $this->sChar = $sChar;
        }
        @$this->sConn = mysql_connect($this->sHost, $this->sName, $this->sPass)
                        or die($this->halt('数据库连接出错'));
        mysql_select_db($this->sData, $this->sConn)
        or die($this->halt('数据库名称出错'));
        mysql_query("SET NAMES '".$this->sChar."'")or die ($this->halt('数据库编码出错'));
    }

    /*
    * 功能：错误输出
    * 参数：$sMess->错误的信息
    */
    function halt ($sMess)
    {
        if (empty($sMess))
        {
            return;
        }
        if ($this->bOutput)
        {
            $sMess.= ' -> '.mysql_errno().' : '.mysql_error();
            $sMess = '<font color="#FFFFFF">'.$sMess.'</font>';
            $sMess = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />对不起，无法连接到数据库，请稍后重试<br />'.$sMess;
        }
        else
        {
            $sMess = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />对不起，无法连接到数据库，请稍后重试<br />';
        }

        p($sMess);
        exit(0);
    }


    /*
    * 功能：数据库操作
    * 参数：$sSql->数据库操作语句
    */
    function query ($sSql)
    {
        if (empty($sSql))
        {
            return;
        }
        $this->result = mysql_query ($sSql) or die ($this->query_log ($sSql));	//对于操作出错的语句写入日志文件
        return $this->result;
    }

	/*
	*返回：INSERT, UPDATE, REPLACE or DELETE 操作的行数
	*/
	function execute($sSql)
	{
        if (empty($sSql))
        {
            return false;
        }

		//$querytime_before = array_sum(explode(' ', microtime()));
        $this->result = mysql_query ($sSql) or die ($this->query_log ($sSql));	//对于操作出错的语句写入日志文件
		//$querytime_after = array_sum(explode(' ', microtime()));
		//$nTime = $querytime_after - $querytime_before;
		//p('运行时间'.$nTime);
        return mysql_affected_rows();
	}

    /*
    * 功能：查询操作
    */
    function select ($sSql, $sKey='')
    {
        if (is_null($sSql))
        {
            return false;
        }
        else
        {
            $aTem = array();
            $this->query($sSql);
            for ($i=0; $i<mysql_num_rows($this->result); $i++)
            {
                $rs = mysql_fetch_assoc($this->result);
                if (empty($sKey))
                {
                    $aTem[] = $rs;
                }
                else
                {
                    $aTem[$rs[$sKey]] = $rs;
                }
            }
            return $aTem;
        }
    }

    /*
    * 查询单条数据操作
    */
    function selectOne ($sSql)
    {
        if (is_null($sSql))
        {
            return false;
        }
        else
        {
            $aTem = array();
            $this->query($sSql.' limit 1');
            if (mysql_num_rows($this->result) > 0)
            {
                $rs = mysql_fetch_assoc($this->result);
                return $rs;
            }
            else
            {
                return ;
            }
        }
    }

    function getCount($sSql)
    {
        if (is_null($sSql))
        {
            return false;
        }
        $re = $this->selectOne($sSql);
        return $re['count'];
    }

    //使用例子
    //$sLimit = true;
    //$nCurrPage = true;
    //$nPageList = 20;
    //$nCount = $gDBR->get_table_count( 'id', INFO_SOU, $sWhere,$nPageList, $sLimit, $nCurrPage);
    function get_table_count($sKey, $sTable, $sWhere = ' ',$nPageList=20,  &$sLimit, &$nCurrPage, $sCurrPage='nPage')
    {
        $sSql = ' select count('.$sKey.') as count from '.$sTable.' '.$sWhere;
        $nCount = $this->getCount($sSql);
        if ($sLimit || $nCurrPage)
        {
            $nCurr = (int)$_GET[$sCurrPage];

            $nCurr = ($nCurr>1&&$nCurr<=ceil($nCount/$nPageList))?$nCurr:1;
            $sLimit = $nCurr==1?' limit '.$nPageList:' limit '.(($nCurr-1)*$nPageList).', '.$nPageList;
            $nCurrPage = $nCurr;
        }
        return $nCount;
    }

    function close()
    {
        if ($this->sConn)
        {
            @mysql_close($this->sConn);
        }
    }

    /*
    * 功能：对特定的操作语句记录入日志
    */
    function query_log ($sSql)
    {
        if (empty($sSql))
        {
            return;
        }

        p($sSql);	//出错时输出SQL语句

        if ($this->bLog)
        {
            $sFile = $_SERVER['DOCUMENT_ROOT'].'/log/query_error_'.date('Y-m-d').'.txt';  //日志文件
            $sClientIP = $_SERVER["REMOTE_ADDR"];
            if (!file_exists($sFile))
            {
                $fp = fopen($sFile, 'w');
                fwrite($fp, $sClientIP.' ['.date('y-m-d H:i:s', time()).'] {'.$sSql.
                       '}  Error>>'.mysql_errno().' : '.mysql_error()."\r\n");
                fclose($fp);
            }
            else
            {
                $fp = fopen($sFile, 'a');
                fwrite($fp, $sClientIP.' ['.date('y-m-d H:i:s', time()).'] {'.$sSql.
                       '}  Error>>'.mysql_errno().' : '.mysql_error()."\r\n");
                fclose($fp);
            }
        }

        $this->halt('数据操作出错');
    }
}

$sUser = 'root';  $sPass = 'password';  $sData = '5694';
$gDB = new cMysql($sUser, $sPass, $sData, '', 'utf8');
?>
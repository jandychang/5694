<?php
/*
* 程序功能：
* 创建时间：
* 创建作者：hui
*/

class cPic
{
    var $oDBR;
	var $oDBW;
	var $sError;

	function cPic()
	{
		global $gDBR, $gDBW;
		$this->oDBR = $gDBR;
		$this->oDBW = $gDBW;
	}

    /*
	* 功能：创建图片目录
	* 函数：$sFile->绝对路径及文件名
	*/
	function create_dir ($sDir='')
	{


		$sDir = str_replace('\\', '/', $sDir);
		$sDir = str_replace(DROOT.'/', '', $sDir);
		$aDir = explode('/', $sDir);

        $sPath = DROOT.'/';
        foreach($aDir as $sDir)
        {
            $sPath .= $sDir.'/';
            if(!is_dir($sPath)) {
                mkdir($sPath, 0777);
            }

            if (!file_exists($sPath))
            {
                mkdir($sPath, 0755);
            }
        }
		return true;
	}


	/*
	功能：上传图片
	参数：$pic->控件名称， $path->存储路径， $nn->文件名称的后面加上
	*/
	function up_pic($pic, $path, $nn = '')
	{

		if (empty($path))
		{
			$this->sError = '错误的图片路径';
			return false;
		}
        if (substr($path, -1) != '/')
        {
            $path .= '/';
        }
		$uptypes=array(			//可上传的类型
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/pjpeg',
			'image/gif',
			'image/bmp',
			'image/x-png',
			'application/x-shockwave-flash'
		);

        $uptypes2=array(			//可上传的类型
			'jpg',
			'jpeg',
			'png',
			'jpeg',
			'gif',
			'bmp',
			'png',
			'swf'
		);

		$max_file_size=800000; 	//上传大小控制
		$destination_folder=$path;

		if (POST)
		{
			if (!is_uploaded_file($_FILES[$pic][tmp_name]))  //是否存在文件
			{
				//echo $_FILES[$pic][tmp_name];
				//echo "图片不存在!";
				$this->sError = '图片不存在!';
				return false;
			}
			$file = $_FILES[$pic];
			if($max_file_size < $file["size"]) //检查文件大小
			 {
				  //alert('图片太大，无法上传');
				  //exit();
				 $this->sError = '图片太大，无法上传!';
				return false;
			 }
			if(!in_array($file["type"], $uptypes)) //检查文件类型
			{
				 //alert('文件类型不符');
				 //exit();
				 $this->sError = '文件类型不符!';
				 return false;
			}

			if(!file_exists($destination_folder)) //不存在目录
			{
				//@mkdir($destination_folder);
	  			$this->create_dir($destination_folder);
				//echo $destination_folder;
			}


			$filename=$file["tmp_name"];
			$image_size = getimagesize($filename);
			$pinfo=pathinfo($file["name"]);

            $ftype = $uptypes2[array_search($file["type"], $uptypes)];
			//$ftype=strtolower($pinfo[extension]);

			$ccc = time().$nn;
			$destination = $destination_folder.$ccc.".".$ftype;
			if (file_exists($destination) && $overwrite != true)
			{    //echo "同名文件已经存在了";
				 return ;
			}

			if(!move_uploaded_file ($filename, $destination))
			{   //echo "移动文件出错";
				return ;
			}
			$pinfo=pathinfo($destination);
			//$fname=$pinfo[basename]; 	//返回文件名及后缀组拿的字串
			//return $fname;

			$fname[0] = $ccc;		//返回，文件名、后缀数组

            $fname[1] = $ftype;

            if ($ftype == 'bmp')
            {
                $img = $this->imagecreatefrombmp($destination);  //bmp转JPG
                if ($img)
                {
                    imagejpeg($img, $destination_folder.$ccc.".jpg");
                    @unlink($destination);
                    $fname[1] = 'jpg';
                }
            }

            $fname[2] = $destination_folder;
			return $fname;
		}
	}

    /*
    功能：根据URL获取图片
    */
    function get_url_pic($old_name, $sPath = '', $nWidth = 200, $nFlag = 1, $time = 0, $nMinWidth=100)
    {
        if (substr($sPath, -1) != '/')
        {
            $sPath .= '/';
        }
        if(!file_exists($sPath)) //不存在目录
        {
            $this->create_dir($sPath);
        }



        $stype=explode(".", $old_name);
        $type = strtolower($stype[count($stype)-1]);

        if (!in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'bmp')))
        {
            @$aImg = getimagesize($old_name);
            $sEx  = $aImg['mime'];
        }

        if ('gif' == $type || $sEx == 'image/gif')
        {
            $src_img=@imagecreatefromgif($old_name);
            if (!$src_img)
            {
                $src_img = @imagecreatefrompng($old_name);
            }
            $type = 'gif';
        }
        if ('jpg' == $type || 'jpeg' == $type || $sEx == 'image/jpg'  || $sEx == 'image/jpeg'){$src_img=@imagecreatefromjpeg($old_name); $type = 'jpg';}
        if ('png' == $type || $sEx == 'image/x-png'){$src_img=@imagecreatefrompng($old_name); $type = 'png';}
        if ('bmp' == $type || $sEx == 'image/bmp') { $src_img = @imagecreatefromwbmp($old_name); $type = 'bmp';}

        if (!$src_img)
        {
            return false;
        }    



        $old_x=imageSX($src_img);
        $old_y=imageSY($src_img);

        if ($old_x < $nMinWidth)
        {
            return false;
        }
       

        if ($nFlag == 1)
        {
            //按比例取
            $s_img=imagecreatetruecolor($nWidth,$nWidth);
            $white = imagecolorallocate($s_img, 255, 255, 255);
            imagefill($s_img, 0, 0, $white);

            if ($old_x < $nWidth && $old_y < $nWidth )
            {
                //$dst_img=imagecreatetruecolor($old_x,$old_y);
                imagecopyresampled($s_img,$src_img,0,0, 0,0,$old_x,$old_y,$old_x,$old_y);
            }
            else
            {
                //以下是宽高都限制
                if ($old_x > $old_y)
                {
                    $d_x = (float)($nWidth/$old_x);
                    $n_w = $nWidth;
                    $n_h = $old_y * $d_x;

                    $nx = 0;
                    $ny = ($nWidth - $n_h)/2;
                }
                elseif ($old_x < $old_y)
                {
                    $d_y = (float)($nWidth/$old_y);
                    $n_h = $nWidth;
                    $n_w = $old_x * $d_y;

                    $ny = 0;
                    $nx = ($nWidth - $n_w)/2;
                }
                else
                {
                    $n_h = $n_w = $new_w;
                    $ny = $nx = 0;
                }
                imagecopyresampled($s_img,$src_img, $nx, $ny, 0, 0,$n_w,$n_h,$old_x,$old_y);
            }
        }
        elseif ($nFlag == 2)
        {
            //创建小图  //取中间的宽度
            $small_w = $small_h = $nWidth;

            if ($old_x < $small_w && $old_y < $small_h )
            {
                $s_img=imagecreatetruecolor($small_w, $small_h);
                imagecopyresampled($s_img,$src_img,0,0, 0,0, $small_w, $small_h, $old_x, $old_y);
            }
            else
            {
                $s_img=imagecreatetruecolor($small_w, $small_h);
                if ($old_x > $old_y)
                {
                    $d_x = (int)(($old_x - $old_y)/2);
                    $d_y = 0;
                    $dx = $old_y;
                }
                if ($old_x < $old_y)
                {
                    $d_y = (int)(($old_y - $old_x)/2);
                    $d_x = 0;
                    $dx = $old_x;
                }
                if ($old_x == $old_y)
                {
                    $d_x =$d_y = 0;
                    $dx = $old_y;
                }
                imagecopyresampled($s_img,$src_img,0,0, $d_x,$d_y,$small_w,$small_h,$dx,$dx);
            }

        }
        else
        {
            $aPx = array(intval($_POST['px1']),intval($_POST['px2']),intval($_POST['px3']),intval($_POST['px4']));

            $old_y = $old_y - $aPx[0] - $aPx[2];
            $old_x = $old_x - $aPx[1] - $aPx[3];
            $nx = $aPx[3];
            $ny = $aPx[0];

 
            $s_img=imagecreatetruecolor($old_x,$old_y);
            $white = imagecolorallocate($s_img, 255, 255, 255);
            imagecopyresampled($s_img,$src_img, 0, 0, $nx,$ny, $old_x,$old_y,$old_x,$old_y);
        }


        $time==0 && $time = time().substr(microtime(), 2, 3);
        $sOut = $sPath.$time.'.'.$type;

        switch ($type)
        {
            case 'gif':
                imagegif($s_img, $sOut);
                break;
            case 'jpg':
                imagejpeg($s_img, $sOut);
                break;
            case 'png':
                imagepng($s_img, $sOut);
                break;
            case 'bmp':
                imagewbmp($s_img, $sOut);
                break;
        }
        $sOutPic = $time.'.'.$type;
        imagedestroy($src_img);
        return $sOutPic;
    }


	//处理图像缩放功能的函数
	//////参数( 1.旧文件名称, 2.新文件名称, 3.新宽度, 4.新高度, 5.是否按比例<1为宽高固定,0为按比例> )
	function createthumb($old_name,$new_name,$new_w,$new_h,$flag = 0)
	{
		$aTem=explode(".",$old_name);
		$sPostfix = strtolower(trim($aTem[count($aTem)-1]));

		if ('gif' == $sPostfix){$src_img=imagecreatefromgif($old_name);}
		if ($sPostfix == 'jpg' || $sPostfix == 'jpeg'){$sPostfix='jpg';$src_img=imagecreatefromjpeg($old_name);}
		if ('png' == $sPostfix){$src_img=imagecreatefrompng($old_name);}
		if ('bmp' == $sPostfix) { $src_img = imagecreatefromwbmp($old_name); }

		if (!$src_img){
			return false;
		}

		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
	//	echo $old_x.'<br />';
	//	echo $old_y.'<br />';


		$thumb_w=$new_w;
		$thumb_h=$new_h;

		if ($flag == 0)		//按比例生成全部显示的小图或大图,得正方形图片
		{

			$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
			$white = imagecolorallocate($dst_img, 255, 255, 255);
			imagefill($dst_img, 0, 0, $white);

			if ($old_x < $thumb_w && $old_y < $thumb_h )
			{
				//$dst_img=imagecreatetruecolor($old_x,$old_y);
				imagecopyresampled($dst_img,$src_img,0,0, 0,0,$old_x,$old_y,$old_x,$old_y);
			}
			else
			{
				//以下是宽高都限制
				if ($old_x > $old_y)
				{
					$d_x = (float)($thumb_w/$old_x);
					$n_w = $thumb_w;
					$n_h = $old_y * $d_x;

					$nx = 0;
					$ny = ($thumb_h - $n_h)/2;
				}
				elseif ($old_x < $old_y)
				{
					$d_y = (float)($thumb_h/$old_y);
					$n_h = $thumb_h;
					$n_w = $old_x * $d_y;

					$ny = 0;
					$nx = ($thumb_w - $n_w)/2;
				}
				else
				{
					$n_h = $n_w = $new_w;
					$ny = $nx = 0;
				}
				imagecopyresampled($dst_img,$src_img, $nx, $ny, 0, 0,$n_w,$n_h,$old_x,$old_y);
			}
		}
		elseif($flag == 1)	//拉伸宽高为指定值
		{
			$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0, 0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		}
		elseif($flag == 2)	//按比例生成全部显示的小图或大图,并且大图加水印，得原图片比例图片
		{					//加水印把$this->nWaterStation这个值改成对应的水印位置就可以添加水印

			if ($old_x < $new_w && $old_y < $new_h )
			{
				$dst_img=imagecreatetruecolor($old_x,$old_y);
				$white = imagecolorallocate($dst_img, 255, 255, 255);
				imagefill($dst_img, 0, 0, $white);

				imagecopyresampled($dst_img,$src_img,0,0, 0,0,$old_x,$old_y,$old_x,$old_y);
			}
			else
			{
				if ((int)($old_y/$old_x) >= 2)
				{
					if ($new_w >= $old_x)	//这里是只限制宽度
					{
						$n_w = $old_x;
						$n_h = $old_y;
					}
					else
					{
						$n_w = $new_w;
						$n_h = (float)($new_w/$old_x)*$old_y;
					}
				}
				else
				{
					//以下是宽高都限制
					if ($old_x > $old_y)
					{
						$d_x = (float)($new_w/$old_x);
						$n_w = $new_w;
						$n_h = $old_y * $d_x;
					}
					elseif ($old_x < $old_y)
					{
						$d_y = (float)($new_h/$old_y);
						$n_h = $new_h;
						$n_w = $old_x * $d_y;
					}
					else
					{
						$n_h = $n_w = $old_x;
					}
				}

				$dst_img=imagecreatetruecolor($n_w,$n_h);
				$white = imagecolorallocate($dst_img, 255, 255, 255);
				imagefill($dst_img, 0, 0, $white);

				imagecopyresampled($dst_img,$src_img,0,0, 0,0,$n_w,$n_h,$old_x,$old_y);
			}
			//$this->watermark($dst_img);
		}
        elseif ($flag==3)	//按比倒截取中间一块
		{
			$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
			$white = imagecolorallocate($dst_img, 255, 255, 255);
			imagefill($dst_img, 0, 0, $white);
			
            if ($thumb_w != $thumb_h)   //宽高不一致时
            {
                $d_x = $d_y = 0;
                if ($thumb_w/$old_x > $thumb_h/$old_y)  //根据高来取
                {
                    //$dy  = $old_y;//$old_x*$thumb_h/$thumb_w; //宽度按比例取
                    //$d_x      = intval((($thumb_w/$old_x)*$old_y-$thumb_h)*($old_x/$thumb_w)/2);
                    $thumb_w1 = intval($old_x/($old_y/$thumb_h));
                    $d_x      = intval(($thumb_w - $thumb_w1)/2);
                    $thumb_w  = $thumb_w1;
                }
                else    //根据宽来取
                {
                    //$dx  = $old_y*$thumb_w/$thumb_h; //高度按比例取
                    //$d_y      = intval((($thumb_h/$old_y)*$old_x-$thumb_w)*($old_y/$thumb_h)/2);
                    $thumb_h1 = intval($old_y/($old_x/$thumb_w));
                    $d_y      = intval(($thumb_h - $thumb_h1)/2);
                    $thumb_h  = $thumb_h1;
                }
                imagecopyresampled($dst_img,$src_img, $d_x, $d_y, 0,0,$thumb_w,$thumb_h,$old_x,$old_y);
            }
            elseif ($old_x < $new_w && $old_y < $new_h )
			{
				imagecopyresampled($dst_img,$src_img,0,0, 0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			}
			else
			{
				if ($old_x > $old_y)
				{
					$d_x = (int)(($old_x - $old_y)/2);
					$d_y = 0;
					$dx = $old_y;
				}
				if ($old_x < $old_y)
				{
					$d_y = (int)(($old_y - $old_x)/2);
					$d_x = 0;
					$dx = $old_x;
				}
				if ($old_x == $old_y)
				{
					$d_x =$d_y = 0;
					$dx = $old_y;
				}
				imagecopyresampled($dst_img,$src_img,0,0, $d_x,$d_y,$thumb_w,$thumb_h,$dx,$dx);
			}
		}
		else	//按比倒截取中间一块
		{
			$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
			$white = imagecolorallocate($dst_img, 255, 255, 255);
			imagefill($dst_img, 0, 0, $white);
			
            if ($thumb_w != $thumb_h)   //宽高不一致时
            {
                $d_x = $d_y = 0;
                if ($thumb_w/$old_x > $thumb_h/$old_y)  //根据高来取
                {
                    $dy  = $old_x*$thumb_h/$thumb_w; //宽度按比例取
                    $dx  = $old_x;
                    $d_y = intval((($thumb_w/$old_x)*$old_y-$thumb_h)*($old_x/$thumb_w)/2);
                }
                else    //根据宽来取
                {
                    $dx  = $old_y*$thumb_w/$thumb_h; //高度按比例取
                    $dy  = $old_y;//$old_x;
                    $d_x = intval((($thumb_h/$old_y)*$old_x-$thumb_w)*($old_y/$thumb_h)/2);
                }
                imagecopyresampled($dst_img,$src_img,0,0, $d_x,$d_y,$thumb_w,$thumb_h,$dx,$dy);
            }
            elseif ($old_x < $new_w && $old_y < $new_h )
			{
				imagecopyresampled($dst_img,$src_img,0,0, 0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			}
			else
			{
				if ($old_x > $old_y)
				{
					$d_x = (int)(($old_x - $old_y)/2);
					$d_y = 0;
					$dx = $old_y;
				}
				if ($old_x < $old_y)
				{
					$d_y = (int)(($old_y - $old_x)/2);
					$d_x = 0;
					$dx = $old_x;
				}
				if ($old_x == $old_y)
				{
					$d_x =$d_y = 0;
					$dx = $old_y;
				}
				imagecopyresampled($dst_img,$src_img,0,0, $d_x,$d_y,$thumb_w,$thumb_h,$dx,$dx);
			}
		}


		if (preg_match("/png/",$system[1]))
		{
			imagepng($dst_img,$new_name);
		}
		else
		{
			imagejpeg($dst_img,$new_name,85);
		}
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}

	function watermark(&$dst_img)
	{
		if ($this->nWaterStation <= 0)
		{
			return false;
		}

		$nPic_w=imageSX($dst_img);
		$nPic_h=imageSY($dst_img);

		$nWater_w = 123;	//水印的宽
		$nWater_h = 53;		//水印的高
		$nMargin = 2;		//水印离边距

		if ($nPic_w < 300 || $nPic_h < 300)
		{
			return ;	//图片太小不加水印
		}

		//水印的位置 1(其它)=>左上,2=>右上,3=>左下,4=>右下,5=>中
		switch ($this->nWaterStation)
		{
			case 2:
				$nNew_x = $nPic_w - $nMargin - $nWater_w;
				$nNew_y = $nMargin;
				break;

			case 3:
				$nNew_x = $nMargin;
				$nNew_y = $nPic_h - $nMargin - $nWater_h;
				break;

			case 4:
				$nNew_x = $nPic_w - $nMargin - $nWater_w;
				$nNew_y = $nPic_h - $nMargin - $nWater_h;
				break;

			case 5:
				$nNew_x = ($nPic_w - $nWater_w)/2;
				$nNew_y = ($nPic_h - $nWater_h)/2;
				break;

			default:		//默认水印位置左上角
				$nNew_x = $nMargin;
				$nNew_y = $nMargin;
				break;
		}
		imagealphablending($dst_img, true);
		$im2 = imagecreatefrompng(DROOT.'/www/img/log.png');
		imagecopy($dst_img, $im2, $nNew_x, $nNew_y, 0, 0, $nWater_w, $nWater_h);
	}



	function up_custom_pic($pic, $path, $nSmallWidth = 100, $nBigWidth = 800, $bBigType = 0, $bSmallType = 3, $bLimit=true) //$bLimit是否压缩
	{
		global $gUser;
		$sName = $this->up_pic($pic,$path);
		if (!is_array($sName))
		{
			return false;
		}
		$sBigName = $sName[0].'.'.$sName[1];
		$sSmallName = 's'.$sName[0].'.'.$sName[1];
        $time = time();
        $user = (int)$gUser['id'];
        if ($bLimit)
        {
            $this->createthumb($path.$sBigName, $path.$sSmallName, $nSmallWidth, $nSmallWidth, $bSmallType);	//小图
            $this->createthumb($path.$sBigName, $path.$sBigName, $nBigWidth, $nBigWidth, $bBigType);	//大图
            $sValue = ',(\''.$this->oDBW->get_next_id(UP_PIC).'\', \''.$user.'\', \'1\', \''.(int)$sName[0].'\', \''.$sBigName.'\', \''.$path.'\', \''.$time.'\')';
        }

		$sSql = 'INSERT INTO `'.UP_PIC.'` (`tup_id`, `tup_userid`, `tup_flag`, `tup_name_int`, `tup_name`, `tup_path`, `tup_utime`)'.
				' VALUES (\''.$this->oDBW->get_next_id(UP_PIC).'\', \''.$user.'\', \'1\', \''.(int)$sName[0].'\', \''.$sSmallName.'\', \''.$path.'\', \''.$time.'\')'.$sValue;

		$this->oDBW->query($sSql);

		return $sBigName;
	}

    //定制上传图片
    function up_custom_pic2($sPic, $sPath, $nWidth = 900, $nType=2)
    {
        $aName = $this->up_pic($sPic,$sPath);
		if (!is_array($aName))
		{
			return false;
		}
        $sPicName = $aName[0].'.'.$aName[1];
        $sPath = $aName[2];
        $this->createthumb($sPath.$sPicName, $sPath.$sPicName, $nWidth, $nWidth, $nType);
        $this->reg_pic($sPicName, $sPath);

        return $sPicName;
    }

    function up_info_pic($pic, $sPath, $nFlag = 1)
    {

        if ($nFlag == 2)    //直接通过PIC的URL生成图片
        {
            $sPicName = $this->get_url_pic($pic, $sPath);
        }
        else
        {
            $aName = $this->up_pic($pic,$sPath);
            if (!is_array($aName))
            {
                return false;
            }
            $sPicName = $aName[0].'.'.$aName[1];
            $sPath = $aName[2];
            $this->createthumb($sPath.$sPicName, $sPath.$sPicName, 200, 200, 0);	//生成长宽100的小图
        }
        $this->reg_pic($sPicName, $sPath);
        return $sPicName;
    }


    function reg_pic($sPicName, $sPath)
    {
        global $gUser;
        $aValues = array();
        $sValues = '';
        $nTime = time();
        if (is_array($sPicName))
        {
            foreach($sPicName as $nKey => $sPic)
            {
                if (!empty($sPic))
                {
                    $aValues[] = '(\''.$this->oDBW->get_next_id(UP_PIC).'\', \''.intval($gUser['id']).'\', \'1\', \''.pic_number($sPic).'\', \''.$sPic.'\', \''.$sPath[$nKey].'\', \''.$nTime.'\')';
                }
            }
            $sValues = implode(', ', $aValues);
        }
        elseif (!empty($sPicName))
        {
            $sValues = '(\''.$this->oDBW->get_next_id(UP_PIC).'\', \''.intval($gUser['id']).'\', \'1\', \''.pic_number($sPicName).'\', \''.$sPicName.'\', \''.$sPath.'\', \''.$nTime.'\')';
        }
        else
        {
            $this->sError = '提交的图片文件名不正确';
            return false;
        }
        $sSql = 'insert into '.UP_PIC.' (`tup_id`, `tup_userid`, `tup_flag`, `tup_name_int`, `tup_name`, `tup_path`, `tup_utime`) values '.$sValues;
        $this->oDBW->query($sSql);
    }

	//功能：把图片表里的图片置为已删除状态
	function del_pic($aPicName)
	{
		if (is_array($aPicName))
		{
            $aPic = array();
            foreach($aPicName as $sTem)
            {
                $aPic[] = pic_number($sTem);
            }
			$sWhere = ' and  tup_name_int in ('.implode(',', $aPic).') ';
		}
		else
		{
			$sWhere = ' and tup_name_int = '.pic_number($aPicName);
		}
		$sSql = ' update '.UP_PIC.' set tup_flag = 0 and tup_dtime = '.time().' where tup_id > 0 '.$sWhere;
		$this->oDBW->query($sSql);
	}


	//功能：直接删除数据库里和空间里的图片
	function del_pic_true($aPicName, $sPicPath)
	{
        if (empty($aPicName))
        {
            return false;
        }
		elseif (is_array($aPicName))
		{
            $aPic = array();
            foreach($aPicName as $sTem)
            {
                $aPic[] = pic_number($sTem);
            }
			$sWhere = ' where  tup_name_int in ('.implode(',', $aPic).') ';
		}
		else
		{
			$sWhere = ' where tup_name_int = '.pic_number($aPicName);
		}

		$sSql = ' delete from '.UP_PIC.$sWhere;
        if ($this->oDBW->execute($sSql))
        {
            if (is_array($aPicName))
            {
                foreach($aPicName as $sTem)
                {
                    @unlink($sPicPath.$sTem);
                }
            }
            else
            {
                @unlink($sPicPath.$aPicName);
                @unlink($sPicPath.'s'.$aPicName);
            }

        }
	}











    function get_img_array($str = '', $url = '')
    {
        if ($str == '' && $url == '')
        {
            return '';
        }
        $str = stripslashes(trim($str));
        if ($str == '')
        {
            $base=parse_url($url);
            //if(!empty($base["path"]) && (strpos($base["path"],".")!==false || strpos($base["path"],"?")!==false))
            //{
                //$pathpos=strripos($base["path"],"/");
                //$base["path"]=substr($base["path"],0,$pathpos+1);
            //}
            $host=$base["scheme"]."://".$base["host"];
			$aPatch = pathinfo($base["path"]);
            $path=$aPatch['dirname'];
            $str = @file_get_contents($url);
            if (!$str)
            {
                return false;
            }
        }

		foreach (array('src', 'file') as $sTem)
		{
			preg_match_all("/<(IMG|img)(\s+|)(.*?)".$sTem."(\s+|)=(\s+|)(“|‘|'|\"|)(\s+|)([^'\"\s>]+)(.*?)>/i", trim($str), $matches);
			//preg_match_all("/<(IMG|img)(\s+|)(.*?)src(\s+|)=(\s+|)(“|‘|'|\"|)(\s+|)(.*?)>/i", trim($str), $matches);
	
			if (!empty($matches[8]))
			{
				foreach($matches[8] as $key => $aTem)
				{
	
					$imgpath = $aTem;
					if(strpos($imgpath,"http")!==0)
					{
						//判断链接是否为相对目录 并计算出完整url
						$path1=strpos($imgpath,"../",0);
						while($path1!==false)
						{
							if(substr($path,strlen($path)-1,strlen($path))=="/")
								$path=substr($path,0,strlen($path)-1);
							$imgpath=substr($imgpath,strpos($imgpath,"../")+3,strlen($imgpath));
							//echo $imgpath.strripos($path,"/")."\n";
							$path=substr($path,0,strripos($path,"/"));
							$path1=strpos($imgpath,"../",0);
						}
						if($path!='/')
							$imgpath = str_replace($path,"",$imgpath);
						if(strpos($imgpath,'/')!==0)
							$imgpath= str_replace("//","/",$path."/".$imgpath);
						$imgpath= str_replace(array("//", '\\'),array("/", ''),$imgpath);
						$imgpath=$host.$imgpath;
					}
					$aSrc[$imgpath] = $imgpath;
				}
			}
		}
        return $aSrc;
    }

    //bmp 转 JPG
	function ConvertBMP2GD($src, $dest = false) {
        if (!($src_f = fopen($src, "rb"))) {
            return false;
        }
        if (!($dest_f = fopen($dest, "wb"))) {
            return false;
        }
        $header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f, 14));
        $info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant", fread($src_f, 40));
        extract($info);
        extract($header);
        if ($type != 0x4D42) { // signature "BM"
            return false;
        }
        $palette_size = $offset -54;
        $ncolor = $palette_size / 4;
        $gd_header = "";
        // true-color vs. palette
        $gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
        $gd_header .= pack("n2", $width, $height);
        $gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
        if ($palette_size) {
            $gd_header .= pack("n", $ncolor);
        }
        // no transparency
        $gd_header .= "\xFF\xFF\xFF\xFF";
        fwrite($dest_f, $gd_header);
        if ($palette_size) {
            $palette = fread($src_f, $palette_size);
            $gd_palette = "";
            $j = 0;
            while ($j < $palette_size) {
                $b = $palette {
                    $j++ };
                $g = $palette {
                    $j++ };
                $r = $palette {
                    $j++ };
                $a = $palette {
                    $j++ };
                $gd_palette .= "$r$g$b$a";
            }
            $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
            fwrite($dest_f, $gd_palette);
        }
        $scan_line_size = (($bits * $width) + 7) >> 3;
        $scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;
        for ($i = 0, $l = $height -1; $i < $height; $i++, $l--) {
            // BMP stores scan lines starting from bottom
            fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
            $scan_line = fread($src_f, $scan_line_size);
            if ($bits == 24) {
                $gd_scan_line = "";
                $j = 0;
                while ($j < $scan_line_size) {
                    $b = $scan_line {
                        $j++ };
                    $g = $scan_line {
                        $j++ };
                    $r = $scan_line {
                        $j++ };
                    $gd_scan_line .= "\x00$r$g$b";
                }
            } else
                if ($bits == 8) {
                    $gd_scan_line = $scan_line;
                } else
                    if ($bits == 4) {
                        $gd_scan_line = "";
                        $j = 0;
                        while ($j < $scan_line_size) {
                            $byte = ord($scan_line {
                                $j++ });
                            $p1 = chr($byte >> 4);
                            $p2 = chr($byte & 0x0F);
                            $gd_scan_line .= "$p1$p2";
                        }
                        $gd_scan_line = substr($gd_scan_line, 0, $width);
                    } else
                        if ($bits == 1) {
                            $gd_scan_line = "";
                            $j = 0;
                            while ($j < $scan_line_size) {
                                $byte = ord($scan_line {
                                    $j++ });
                                $p1 = chr((int) (($byte & 0x80) != 0));
                                $p2 = chr((int) (($byte & 0x40) != 0));
                                $p3 = chr((int) (($byte & 0x20) != 0));
                                $p4 = chr((int) (($byte & 0x10) != 0));
                                $p5 = chr((int) (($byte & 0x08) != 0));
                                $p6 = chr((int) (($byte & 0x04) != 0));
                                $p7 = chr((int) (($byte & 0x02) != 0));
                                $p8 = chr((int) (($byte & 0x01) != 0));
                                $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
                            }
                            $gd_scan_line = substr($gd_scan_line, 0, $width);
                        }
            fwrite($dest_f, $gd_scan_line);
        }
        fclose($src_f);
        fclose($dest_f);
        return true;
    }
    function imagecreatefrombmp($filename) {
        $tmp_name = tempnam("tmp", "GD");
        if ($this->ConvertBMP2GD($filename, $tmp_name)) {
            $img = imagecreatefromgd($tmp_name);
            @unlink($tmp_name);
            return $img;
        }
        return false;
    }

}

?>
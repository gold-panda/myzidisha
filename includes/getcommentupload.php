<?php
//include("../library/constant.php");
include_once("../library/session.php");
$Imgid=$_GET['imgid'];
$width=$_GET['width'];
$height=$_GET['height'];
$path=UPLOAD_COMMENT_IMAGE_DIR; //$_GET['path'];
getImage($Imgid,$path,'',$width,$height);
function getImage($id, $path, $defaultImage, $width, $height){
		
		define ('FILEEXT' , 'jpg, jpeg, gif');
		define ('WATERMARK_SNAPS' , 0);
		Define ('WATERMARK_TEXT_FONT',	'1'); // font 1 / 2 / 3 / 4 / 5
		Define ('TEXT_SHADOW',			'0'); // 1 - yes / 0 - no
		Define ('TEXT_COLOR', '#000000');
		Define ('WATERMARK_ALIGN_H','right'); // left / right / center
		Define ('WATERMARK_ALIGN_V', 'bottom'); // top / bottom / center
		Define ('WATERMARK_MARGIN',		10);
		Define ('WATERMARK_TEXT',		'Travelpal.co.in');

		$USER_IMAGE_DIR =  $path;
		$DEFAULT_IMAGE = $defaultImage;

		$img = null;
		//$id = $_REQUEST['id'];
		$picture = '';
		if(isset($id))
		{
			
			$picture = 'file:'. $id ;
		}

		if (substr_count($picture, 'file:') > 0 && file_exists( str_replace('file:', $USER_IMAGE_DIR, $picture)))
		{
			list($width_orig, $height_orig, $image_type) = getimagesize(str_replace('file:', $USER_IMAGE_DIR, $picture));
			switch ($image_type) 
			{
				case 1: $img = imagecreatefromgif(ltrim(rtrim(str_replace('file:', $USER_IMAGE_DIR, $picture)))); break;
				case 2: $img = imagecreatefromjpeg(ltrim(rtrim(str_replace('file:', $USER_IMAGE_DIR, $picture)))); break;
				case 3: $img = imagecreatefrompng(ltrim(rtrim(str_replace('file:', $USER_IMAGE_DIR, $picture)))); break;
				default:   break;
			}			
			
		}

		if(!$img){
			$img = imagecreatefromjpeg(ltrim(rtrim(str_replace('file:', $USER_IMAGE_DIR, $DEFAULT_IMAGE))));
		}

		$w_org = imagesx($img);
		$h_org = imagesy($img);
		$w = ($width !='') ? $width : $w_org;
		$h = ($height!='') ? $height : $h_org;
		if (($w_org == $w && $h_org <= $h) || ($h_org == $h && $w_org <= $w))
		{
			// no resampling needed
			$img2 = $img;
			$h = $h_org;
			$w = $w_org;
		}
		else
		{
			// resampling
			$ratio = $w_org / $h_org;
			
			if ($w / $h > $ratio)
			{
				$w = $h * $ratio;
			}
			else
			{
				$h = $w / $ratio;
			}
			
			$img2 = imagecreatetruecolor($w, $h);
			$white = imagecolorallocate($img2, 255, 255, 255);
			imagefilledrectangle($img2, 0, 0, $w, $h, $white);
			$trans_colour = imagecolorallocatealpha($img2, 255, 255, 255, 127);
			imagefill($img2, 0, 0, $trans_colour);
			
			imagecopyresampled($img2, $img, 0, 0, 0, 0, $w, $h, $w_org, $h_org);
			
			imagedestroy($img);
		}


		if (WATERMARK_SNAPS != '')
				{
					// Watermark the picture with text
					
					$color = eregi_replace("#","", TEXT_COLOR);
					$red = hexdec(substr($color,0,2));
					$green = hexdec(substr($color,2,2));
					$blue = hexdec(substr($color,4,2));
					
					$text_color = imagecolorallocate($img2, $red, $green, $blue);
					
					$text_height = imagefontheight(WATERMARK_TEXT_FONT);
					$text_width = strlen(WATERMARK_TEXT) * imagefontwidth(WATERMARK_TEXT_FONT);
					
					$wt_y = WATERMARK_MARGIN;
					
					if (WATERMARK_ALIGN_V == 'top')
					{
						$wt_y = WATERMARK_MARGIN;
					}
					elseif (WATERMARK_ALIGN_V == 'bottom')
					{
						$wt_y = $h - $text_height - WATERMARK_MARGIN;
					}
					elseif (WATERMARK_ALIGN_V == 'center')
					{
						$wt_y = (int)($h / 2 - $text_height / 2);
					}
					
					$wt_x = WATERMARK_MARGIN;
					
					if (WATERMARK_ALIGN_H == 'left')
					{
						$wt_x = WATERMARK_MARGIN;
					}
					elseif (WATERMARK_ALIGN_H == 'right')
					{
						$wt_x = $w-$text_width-WATERMARK_MARGIN;
					}
					elseif (WATERMARK_ALIGN_H == 'center')
					{
						$wt_x = (int)($w/2-$text_width/2);
					}
					
					if (TEXT_SHADOW == '1')
					{
						imagestring($img2, WATERMARK_TEXT_FONT, $wt_x+1, $wt_y+1, WATERMARK_TEXT, 0);
					}
					imagestring($img2, WATERMARK_TEXT_FONT, $wt_x, $wt_y, WATERMARK_TEXT, $text_color);
				}
			//header("Pragma: public");
			switch ($image_type) 
			{
				case 1: 
					header("Content-Type: image/gif");
					imagegif($img2);
					imagedestroy($img2);
					break;
				case 2: 
					header("Content-Type: image/jpg");
					imagejpeg($img2);
					imagedestroy($img2);
					break;
				case 3: 
					header("Content-Type: image/png");
					imagepng($img2);
					imagedestroy($img2);
					break;
				default:   break;
			}			
			
			exit;
			header("Content-Transfer-Encoding: binary");
			header("Cache-Control: must-revalidate");
			header("Expires: " . gmdate("D, d M Y H:i:s", time() - 30) . " GMT");
			header("Content-Disposition: attachment; filename=profile.jpg");
			imagejpeg($img2);
			imagedestroy($img2);
				
	}
?>
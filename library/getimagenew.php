<?php
require_once('constant.php');
$img = null;
$id = $_REQUEST['id'];
$picture = '';
if(isset($_REQUEST['id']))
{
	
	$picture = 'file:'. $id .'.jpg';
}

if (substr_count($picture, 'file:') > 0 && file_exists( str_replace('file:', USER_IMAGE_DIR, $picture)))
{
	$img = imagecreatefromjpeg(ltrim(rtrim(str_replace('file:', USER_IMAGE_DIR, $picture))));
	
}

if(!$img){
	$img = imagecreatefromjpeg(ltrim(rtrim(str_replace('file:', USER_IMAGE_DIR, DEFAULT_IMAGE))));
}

$w_org = imagesx($img);
$h_org = imagesy($img);
$w = ($_REQUEST['width']!='') ? $_REQUEST['width'] : $w_org;
$h = ($_REQUEST['height']!='') ? $_REQUEST['height'] : $h_org;
if (($w_org == $w && $h_org <= $h) || ($h_org == $h && $w_org <= $w))
{
	// no resampling needed
	$img2 = $img;
	$h = $h_org;
	$w = $w_org;
}
else if($w >= $w_org && $h >= $h_org)
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
	header("Pragma: public");
	header("Content-Type: image/jpg");
	header("Content-Transfer-Encoding: binary");
	header("Cache-Control: must-revalidate");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() - 30) . " GMT");
	header("Content-Disposition: attachment; filename=profile.jpg");
	imagejpeg($img2);
	imagedestroy($img2);
		
?>
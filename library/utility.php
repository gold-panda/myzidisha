<?php
    require_once('constant.php');
    $gLogError = 1;
    $gLogCall = 0;
    $gLogErrorFile = LOG_PATH.'logs.txt';
    $gLogCallFile = LOG_PATH.'calls.txt';


function traceCalls($method, $line){
    global $gLogCall;
    global $gLogCallFile ;
    if($gLogCall) {
        $text = date('D M j G:i:s T Y') . "   " . $method . " line: ".$line . "\n";
        $file = $gLogCallFile ;
        ErrorLog( $text, $file );
    }

}
function Logger( $text , $level = null) {
    global $gLogError;
    global $gLogErrorFile ;
    if($gLogError) {
        $text = date('D M j G:i:s T Y') . "     $text". "\n";
        $file = $gLogErrorFile ;
        ErrorLog( $text, $file );
    }
}
function Logger_Array($pointer,$arr1,$arr2=-5,$arr3=-5,$arr4=-5,$arr5=-5,$arr6=-5,$arr7=-5,$arr8=-5,$arr9=-5,$arr10=-5,$arr11=-5,$arr12=-5)
{
    $arr[0] = $arr1;
    $k=1;
    if($arr2 !=-5){
        $arr[1] = $arr2; $k++;}
    if($arr3 !=-5){
        $arr[2] = $arr3; $k++;}
    if($arr4 !=-5){
        $arr[3] = $arr4; $k++;}
    if($arr5 !=-5){
        $arr[4] = $arr5; $k++;}
    if($arr6 !=-5){
        $arr[5] = $arr6; $k++;}
    if($arr7 !=-5){
        $arr[6] = $arr7; $k++;}
    if($arr8 !=-5){
        $arr[7] = $arr8; $k++;}
    if($arr9 !=-5){
        $arr[8] = $arr9; $k++;}
    if($arr10 !=-5){
        $arr[9] = $arr10; $k++;}
    if($arr11 !=-5){
        $arr[10] = $arr11; $k++;}
    if($arr12 !=-5){
        $arr[11] = $arr12; $k++;}

    global $gLogError;
    global $gLogErrorFile ;
    if($gLogError)
    {
        $text = date('D M j G:i:s T Y') . "     ".$pointer."   ";
        $file = $gLogErrorFile ;
        ErrorLog( $text, $file );
        for($j=0; $j<$k; $j++)
        {
            $text="    ";
            if(is_array($arr[$j]))
            {
                if(is_array($arr[$j][0]))
                {
                    for($i=0; $i<count($arr[$j]); $i++)
                    {
                        foreach ($arr[$j][$i] as $key => $value)
                            $text .=$value."   ";
                        $text .="\n";
                    }
                }
                else
                    for($i=0; $i<count($arr[$j]); $i++)
                        $text .=$arr[$j][$i]."   ";
                    $text .="\n";
            }
            else
                $text =$arr[$j]."\n";

            $file = $gLogErrorFile ;
            ErrorLog( $text, $file );
        }
    }
}


function ErrorLog( $text, $file ) {

    $exists = file_exists( $file );
    $size = $exists ? filesize( $file ) : false;
    if ( !$exists || ( $size !== false && $size + strlen( $text ) < 0x7fffffff ) ) {
        error_log( $text, 3, $file );
    }

}

function imageUpload($img_file, $ext, $userid)
{
/*
retvals 0 Success
        1 wrong file type
        2 file size exceeded
*/

    $picext = strtolower($ext[1]);

    if( $picext == 'pjpeg' || $picext == 'jpeg'){

        $picext = 'jpg';
    }

    if( $picext == 'x-png' ) {
        $picext= 'png';
    }
    $ext_ok = '1';


    $fileE = explode(',', FILEEXT);
    foreach ($fileE as $ex) {


        if ( $ex == $picext ) $ext_ok++;

    }

/* bmp is removed as valid source time being */
    if ( $ext_ok <= '0' or $picext == 'bmp') {


        return 1; //wrong file type

    }

    clearstatcache();

    $fstats= stat($img_file);

    $picsize = $fstats[7];

    $handle = fopen ($img_file, 'rb');

    /* Get current picture size and allowed size. If pic size is more than the allowed size, flag error.. */


    if ($picsize > ALLWDSIZE) {

                return 2; //file size exceeded

    }

    $orgimg = fread($handle, $picsize);

    fclose ($handle);


    if ( $picext != 'jpg' ) {
    /* convert the picture to jpg. This is to enable picture editing  */


        //$jpgfile = createThumb($orgimg, 'N');
        $img_tmp=createImg($picext,$img_file);
        $jpgfile = createJpeg($img_tmp, 'N');
        $newimg = file_get_contents($jpgfile);

    } else {

        $newimg = $orgimg;
    }

    $img_tmp=createImg($picext,$img_file);

    $tnimg_file = createJpeg($img_tmp,'Y');

    $tnimg = file_get_contents($tnimg_file);

    $tnext = 'jpg';

    $picext = 'jpg';

    if (1) {

        $imgfile = writeImageToFile($newimg, $userid, '','');

        $newimg = 'file:'.$imgfile;
        sleep(5);

        $tnimgfile = writeImageToFile($tnimg, $userid, 'tn','');

        $tnimg = 'file:'.$tnimgfile;


    } else {

        $newimg = base64_encode($newimg);

        $tnimg = base64_encode($tnimg);
        //save in DB
    }

    return 0;
}




function createImg($type,$file) {
    $type=strtolower($type);
    if($type == 'bmp') $img=imagecreatefromwbmp($file);
    else if($type == 'png') $img=imagecreatefrompng($file);
    else if($type == 'gif') $img=imagecreatefromgif($file);
    else if($type == 'jpg') $img=imagecreatefromjpeg($file);
    return $img;
}



function createJpeg( $img , $reduce='Y') {

    global $userid;
    global $ext;
    //global $tnsize;

    //$img = imagecreatefrompng($org);

    $w = imagesx( $img );

    $h = imagesy( $img );

    if ($reduce == 'Y' && ($w > TNSIZE || $h > TNSIZE)) {
        if( $w > $h ) {
            $ratio = $w / $h;
            $nw = TNSIZE;
            $nh = $nw / $ratio;
        } else {
            $ratio = $h / $w;
            $nh = TNSIZE;
            $nw = $nh /$ratio;
        }
    } else {

        $nh = $h;
        $nw = $w;
    }

    $img2 = imagecreatetruecolor( $nw, $nh );

    imagecopyresampled ( $img2, $img, 0, 0, 0 , 0, $nw, $nh, $w, $h );

    $fimg = 'img_' . time().$userid . '.jpg';

    $real_tpath = realpath ("temp");

    if( $HTTP_ENV_VARS['OS'] == 'Windows_NT'){

        $real_tpath= str_replace( "\\", "\\\\", $real_tpath);

        $file = $real_tpath . "\\" . $fimg;

    }else{

        $file = $real_tpath . "/" . $fimg;

    }

    imagejpeg( $img2, $file );

    imagedestroy($img2);

    imagedestroy($img);

    return $file;
}



function writeImageToFile($img, $userid, $picno, $file="") {
/* This routine will create an image file */
    if ($file == '') {
        $filename= $userid.$picno.'.jpg';
    } else {
        $filename = $file;
    }

    $img = imagecreatefromstring( $img );
    imagejpeg($img, USER_IMAGE_DIR.$filename);

    return ($filename);
}

//rate is always native equivalet of dollar
function convertToDollar($localAmount, $rate){
    return round ($localAmount/$rate, 3);
    //return number_format(round ($localAmount/$rate, 3), 3, ".", ",");

}

//rate is always native equivalet of dollar
function convertToNative($dollarAmount, $rate){
    return round((double)$dollarAmount * (double)$rate, 3);
    //return number_format(round((double)$dollarAmount * (double)$rate, 3), 3, ".", ",");
}

function dateFromStr1($strDate){// function for date to time stamp
            $d=explode('/',$strDate);
            $year = $d[2];
            $month = $d[0];
            $day = $d[1];
            return mktime(0,0,0,$month,$day,$year);

}

 /*
Input: data to be sanitized
Output: sanitized data
*/
function cleanInput($input) {

    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
}


 /*
Input: data to be sanitized
Output: sanitized data

*/

function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        $input = trim($input);
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $output  = strip_tags($input);
    }
    return $output;
}


function datecompare($date1,$date2)    /*   created by chetan    */
{
       $dateArr1  = explode("/",$date1);
       $dateArr2  = explode("/",$date2);
       $date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
       $date4=mktime(0,0,0,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
        if($date3 < $date4)
            return true;
        else
            return false;

 }
function truncate_num($num,$decimal_place)    /*   created by chetan    */
{
    $mul_no=1;
    for($i=0; $i<$decimal_place; $i++)
        $mul_no = $mul_no * 10;
	$num = bcmul($num ,$mul_no,2); 
	$num =floor($num);
	$num = bcdiv($num , $mul_no,2);
    return $num;
}
function getCardCode16($date,$no=0)    /*   created by chetan    */
{
    /* please do not modify anything below and do not remove any space*/
    $charMapping = "CHETANVRSY";
    $char =" ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $date = (string)$date;
    $rand1=$charMapping[rand(0,9)];
    $rand2=$charMapping[rand(0,9)];
    $rand3=$charMapping[rand(0,9)];
    $rand4=$charMapping[rand(0,9)];
    $rand5=$charMapping[rand(0,9)];
    $rand6=$charMapping[rand(0,9)];
    if($no==1 || $no==5)
        $code = "Z$date[0]$date[1]$rand1-$date[2]$rand2$date[3]$date[4]-$date[5]$date[6]$rand3$date[7]-$rand4$date[8]$date[9]$char[$no]";
    else if($no==2 || $no==6)
        $code = "Z$date[0]$rand1$date[1]-$rand2$date[2]$date[3]$date[4]-$date[5]$rand3$date[6]$date[7]-$date[8]$date[9]$rand4$char[$no]";
    else if($no==3 || $no==7)
        $code = "Z$rand1$date[0]$date[1]-$date[2]$date[3]$date[4]$rand2-$rand3$date[5]$date[6]$date[7]-$date[8]$rand4$date[9]$char[$no]";
    else if($no==4  || $no==8)
        $code = "Z$date[0]$date[1]$rand1-$date[2]$date[3]$rand2$date[4]-$date[5]$date[6]$date[7]$rand3-$rand4$date[8]$date[9]$char[$no]";
    else
        $code = "$rand1$date[0]$date[1]$date[2]-$date[3]$rand2$date[4]$rand3-$rand4$date[5]$rand5$date[6]-$date[7]$date[8]$date[9]$rand6";

    return $code;
}
function getCardCode12($card_no) {

// Updated 18-12-2013. Take the current time in microseconds, append a "pepper" (random string that is hard to guess), and make a hash (jumbling the letters and numbers in a deterministic way).  Then make the base-16 number base-36 (0-9A-F becomes 0-9A-Z).  Then take the first 12 characters and add dashes in between.

$code = strtoupper(str_replace('0', 'O', base_convert(md5(microtime().$card_no.'random pepper32490238590435657'), 16, 36)));
return substr($code, 0, 3)."-".substr($code, 3, 3)."-".substr($code, 6, 3)."-".substr($code, 9, 3);
}

function getEditablePath($filename,$language=0)    /*   created by chetan    */
{
    global $database;
    if($language===0)
    {
        if(isset($_GET["language"]))
        {
        $language = $_GET["language"];
        $act= $database->isActiveLanguage($language);
        if($act==0)
            return $filename;
        $res1 = is_dir("editables/".$language);
        if($res1==1)
        {
            $res2 = file_exists("editables/".$language."/".$filename);
            if($res2==1)
                $path=$language."/".$filename;
            else
                $path=$filename;
        }
        else
            $path=$filename;
        }
        else
            $path=$filename;
        return $path;
    }
    else
    {
        if($language=="en")
            return $filename;
        else
        {
            $act= $database->isActiveLanguage($language);
            if($act==0)
                return $filename;
            $res1 = is_dir("editables/".$language);
            if($res1==1)
            {
                $res2 = file_exists("editables/".$language."/".$filename);
                if($res2==1)
                    $path=$language."/".$filename;
                else
                    $path=$filename;
            }
            else
                $path=$filename;
        }
        return $path;
    }
}
function convertNumber2word($num)
{
    $numText = array('Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen','Twenty','Twenty one','Twenty two','Twenty three','Twenty four','Twenty five','Twenty six','Twenty seven','Twenty eight','Twenty nine','Thirty','Thirty one','Thirty two','Thirty three','Thirty four','Thirty five','Thirty six','Thirty seven','Thirty eight','Thirty nine','Forty','Forty one','Forty two','Forty three','Forty four','Forty five','Forty six','Forty seven','Forty eight','Forty nine','Fifty','Fifty one','Fifty two','Fifty three','Fifty four','Fifty five','Fifty six','Fifty seven','Fifty eight','Fifty nine','Sixty','Sixty one','Sixty two','Sixty three','Sixty four','Sixty five','Sixty six','Sixty seven','Sixty eight','Sixty nine','Seventy','Seventy one','Seventy two','Seventy three','Seventy four','Seventy five','Seventy six','Seventy seven','Seventy eight','Seventy nine','Eighty','Eighty one','Eighty two','Eighty three','Eighty four','Eighty five','Eighty six','Eighty seven','Eighty eight','Eighty nine','Ninety','Ninety one','Ninety two','Ninety three','Ninety four','Ninety five','Ninety six','Ninety seven','Ninety eight','Ninety nine','One hundred');
    return $numText[$num];
}
function round_up($value, $places=0)
{
    if($places < 0)
    {
        $places = 0;
    }
    $mult = pow(10, $places);
    return ($value >= 0 ? ceil($value * $mult):floor($value * $mult)) / $mult;
}

function round_down($value, $places=0)
{
    if($places < 0)
    {
        $places = 0;
    }
    $mult = pow(10, $places);
    return ($value >= 0 ? floor($value * $mult):ceil($value * $mult)) / $mult;
}
function round_local($value)
{
    $value = ceil($value);
    return $value;
}
function pr($data=NULL)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "<br/><br/>";
}
function sanitize_custom($post=array())
{
    foreach($post as $key=>$value)
    {
        $post[$key]=sanitize($value);
    }
    return $post;
}
function getCountryCodeByIP(){
    $country['code'] = '';
    $country['name'] = '';
    $ip='';
    if(isset($_SERVER['REMOTE_ADDR'])) {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    if(!empty($ip)) {
        require_once('./extlibs/geoip/geoip.inc');
        $gi = geoip_open("./extlibs/geoip/GeoIP.dat",GEOIP_STANDARD);
        $country = array();
        $country['code'] = geoip_country_code_by_addr($gi, $ip);
        $country['name'] =geoip_country_name_by_addr($gi, $ip);
        geoip_close($gi);
    }
    return $country;
}
//12-5-2012 Anupam , return user profile url as per Url rewrite requirement
function getUserProfileUrl($userid){
	global $database;
	$url = 'index.php?p=12&u='.$userid;
	$username = $database->getUserNameById($userid);
	$username = str_replace(' ','-',$username);
	if(empty($username)) {
		Logger("uname_empty_url_rewrite".$_SERVER['HTTP_REFERER']);
	}else {
		$url = "microfinance/profile/$username.html";
	}
	return $url;
}
//12-5-2012 Anupam , return loan profile url as per Url rewrite requirement
function getLoanprofileUrl($userid, $loanid){
	global $database;
	$username = $database->getUserNameById($userid);
	if(empty($username)) {
		Logger("uname_empty_url_rewrite_loanprofile".$_SERVER['HTTP_REFERER']." ".$userid." ".$loanid);
	}
	$username = str_replace(' ','-',$username);
	$url = "microfinance/loan/$username/$loanid.html";
	return $url;
}
//12-17-2012 Anupam Moved permanently old loan profile to new SEO friendly url
function RedirectLoanprofileurl() {
	$RequestUrl = $_SERVER['REQUEST_URI'];
	$parsedurl = parse_url($RequestUrl);
	if(isset($parsedurl['query'])) {
		parse_str($parsedurl['query'], $qryStr); 
		if(isset($qryStr['p']) && $qryStr['p']==14 && isset($qryStr['u']) && isset($qryStr['l'])) {
			$loanprurl = getLoanprofileUrl($qryStr['u'], $qryStr['l']);
			unset($qryStr['p']);
			unset($qryStr['u']);
			unset($qryStr['l']);
			$qrystrToAppnd = http_build_query($qryStr);
			if(!empty($qrystrToAppnd)) {
				$urlMovedto = SITE_URL.$loanprurl."?".$qrystrToAppnd;
			}else {
				$urlMovedto = SITE_URL.$loanprurl;
			}
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$urlMovedto);
			exit;
		}
	}
}
//12-17-2012 Anupam, Moved permanently old user profile to new SEO friendly url
function RedirectUserprofileurl() {
	$reqUrl = $_SERVER['REQUEST_URI'];
	$parsedurl = parse_url($reqUrl);
		if(isset($parsedurl['query'])) {
		parse_str($parsedurl['query'], $qryStr); 
		if(isset($qryStr['p']) && $qryStr['p']==12 && isset($qryStr['u'])) {
			$prurl = getUserProfileUrl($qryStr['u']);
			unset($qryStr['p']);
			unset($qryStr['u']);
			$qrystrToAppnd = http_build_query($qryStr);
			if(!empty($qrystrToAppnd)) {
				$urlMovedto = SITE_URL.$prurl."?".$qrystrToAppnd;
			}else {
				$urlMovedto = SITE_URL.$prurl;
			}
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$urlMovedto);
			exit;
		}
	}
}
function array_sort($array, $psort, $order='SORT_DESC',$s_sort='project_no'){
  $new_array = array();
  $sortable_array = array();
  $sortable_array_name = array();
  if (count($array) > 0) {
    foreach ($array as $k => $v) {
      if (is_array($v)) {
        foreach ($v as $k2 => $v2) {
          if ($k2 == $psort) {
           $sortable_array[$k] = $v2;
          }
          if ($k2 == $s_sort && $psort != $s_sort) {
           $sortable_array_name[$k] = $v2;
          }
        }
      }
      else {
       $sortable_array[$k] = $v;
      }
    }
    switch ($order) {
     case 'SORT_ASC':{
       if(isset($sortable_array_name) && count($sortable_array_name) > 0 )
        array_multisort($sortable_array, SORT_ASC, $sortable_array_name, SORT_ASC, $array);
       else 
        array_multisort($sortable_array, SORT_ASC,$array);
       break;
     }
     case 'SORT_DESC':{
      if(isset($sortable_array_name) && count($sortable_array_name) > 0 )
        array_multisort($sortable_array, SORT_DESC, $sortable_array_name, SORT_ASC, $array);
      else 
        array_multisort($sortable_array, SORT_DESC,$array);
      break;
     }
    }
  }
  return $array;
 }

function fbshare_url($url, $image, $summary = null, $title = null) {
    $fburl = 'https://www.facebook.com/sharer/sharer.php?s=100';
    $fburl .= '&p[url]=' . urlencode($url);
    $fburl .= '&p[images][0]=' . urlencode($image);
    if ($summary !== null) {
        $fburl .= '&p[summary]=' . urlencode($summary);
    }
    if ($title !== null) {
        $fburl .= '&p[title]=' . urlencode($title);
    }
    
    return $fburl;
}
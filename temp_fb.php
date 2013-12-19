<?php
include("library/session.php");
global $database,$session;
echo time();
$fb_data=getFbAccountUserId();

if(count($fb_data)>0){
	foreach($fb_data as $data){
			$fbuname=array();
			$userid=$data['userid'];
		
			$fb_frnd_data=unserialize(base64_decode($data['fb_data']));
		
			if(!empty($fb_frnd_data['user_friends']['data']))
			{
			$user_fb_frnd=$fb_frnd_data['user_friends']['data'];
			}else{
			$user_fb_frnd=$fb_frnd_data['user_friends'];
			}				
			
			foreach($user_fb_frnd as $fbfrnd){
			
				$fb_id=$fbfrnd['id'];			
			
				$username=get_fb_username($fb_id);
		
				if(!empty($username))
					$fbuname[]=$username;
				else
					$fbuname[]=$fb_id;		
			}
			
			print_r($fbuname);
			//echo "</pre>";

			//$all_fb_uname=base64_encode(serialize($fbuname));
			//$q="UPDATE ! SET fb_frnd_uname= ? WHERE userid=?";
			//$result= $db->query($q,array('borrowers_extn',$all_fb_uname,$userid));
		}
}			

function getFbAccountUserId()
{
global $db;
$q="select userid,fb_data  from ! where fb_data IS NOT NULL AND OCTET_LENGTH(fb_data)>0 limit 0,5";
$result=$db->getAll($q, array('borrowers_extn'));
return $result;
}

 function get_fb_username($fb_id) {
		
	  sleep(1);	
	  $str=number_format( $fb_id, 0, '', '' );
	  $fb_url="https://graph.facebook.com/".$str;
	  $user_name='';
	  $useragent="Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)";
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_setopt($ch, CURLOPT_FAILONERROR, 0);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	  curl_setopt($ch, CURLOPT_URL, $fb_url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	  curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  $fb_res = curl_exec($ch);
	  if(!empty($fb_res)) {
	   if(preg_match('/( "username":(.*))/', $fb_res, $username)) {
	    $user=isset($username['2']) ? trim($username['2']) : '';
	    $user_name=str_replace(array('"',','),'', $user);
	   }
	  }
	  curl_close ($ch);
	  if(isset($user_name)){
	  return $user_name;
	  } 
}
echo time();
?>
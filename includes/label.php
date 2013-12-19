<?php
class Label
{
	function loadFile($filename)
	{
		global $db;		
		$language = "en";
		include("editables/".$filename.".php");
		$count1 = count($lang[$filename]);
		foreach($lang[$filename] as $key => $value)
		{
		   	
			$p = "SELECT id from ! where mainkey= ? and subkey=? and lang=?";
			$id = $db->getOne($p, array('labels',$filename, $key , $language));
			if(empty($id))
			{
				$q = "INSERT into ! (mainkey, subkey, lang, text) values (?,?,?,?)";
				$res = $db->query($q, array('labels',$filename, $key , $language, $value));
				if($res!==1){
					echo $res;
					return 0;
				}
			}
			else
			{
				$q = "UPDATE ! set text= ? where mainkey=? and subkey=? and lang=?";
				$res = $db->query($q, array('labels',$value, $filename, $key , $language ));
				if($res!==1){
					echo $res;
					return 0;
				}
			}
		}
		$r = "SELECT count(id) from ! where mainkey=? and lang=?";
		$count2 = $db->getOne($r, array('labels', $filename, $language ));
		if($count1 <= $count2)
			return 1;
		else
			return 0;
	}
	function getLabels($filename, $language)
	{
		global $db;		
		include("editables/".$filename.".php");
		$count = count($lang[$filename]);
		$p = "SELECT * from ! where mainkey=? and lang=?";
		$data1 = $db->getAll($p, array('labels', $filename, 'en'));
		$count1= count($data1);
		if($count1==0)
			return 0;
		else if($count1 < $count)
			return -1;
		else
		{
			$q = "SELECT count(id) from ! where mainkey=? and lang=?";
			$count2 = $db->getOne($q, array('labels', $filename, $language));
			
			if($count2==0)
			{
				for($i=0; $i<$count1; $i++)
					$data1[$i]['transText']=$data1[$i]['text'];
				return $data1;
			}
			else
			{
				$i=0;
				foreach($data1 as $row)
				{
					$r = "SELECT text from ! where mainkey=? and subkey=? and lang=?";
					$data3 = $db->getOne($r, array('labels', $filename, $row['subkey'], $language));
					if($data3=="" || empty($data3))
						$data1[$i]['transText']=$data1[$i]['text'];
					else
						$data1[$i]['transText']=$data3;
					$i++;
				}
				return $data1;
			}
		}
	}
	function saveLabels($labels)
	{
		global $db;		

		$language=$labels[0]['language'];
		foreach($labels as $row)
		{
			$r = "SELECT mainkey, subkey from ! where id=?";
			$res1 = $db->getRow($r, array('labels',$row['id']));
			$p = "SELECT id from ! where mainkey= ? and subkey=? and lang=?";
			$id = $db->getOne($p, array('labels',$res1['mainkey'], $res1['subkey'] , $language));
			$res=0;
			$action='';
			if(empty($id))
			{
				$action='insert';
				$q = "INSERT into ! (mainkey, subkey, lang, text) values (?,?,?,?)";
				$res = $db->query($q, array('labels',$res1['mainkey'], $res1['subkey'], $language, $row['text']));
				if($res!==1){
					echo $res;
				return 0;
				}
			}
			else
			{
				$action='update';
				$q = "UPDATE ! set text= ? where mainkey=? and subkey=? and lang=?";
				$res = $db->query($q, array('labels',$row['text'], $res1['mainkey'], $res1['subkey'], $language ));
				if($res!==1){
					echo $res;
				return 0;
				}
			}
			Logger_Array("Label translation",'action, mainkey, subkey, language, text', $action, $res1['mainkey'], $res1['subkey'], $language, $row['text']);
		}
		return 1;
	}
	function writeFile($filename, $language)
	{
		global $db;		
		$q= "SELECT count(id) from ! where mainkey=? and lang=?";
		$count= $db->getOne($q, array('labels',$filename,'en'));
		if($count==0)
			return -2;
		$p = "SELECT * from ! where mainkey=? and lang=?";
		$data = $db->getAll($p, array('labels',$filename,$language));
		if($count !=count($data))
			return -1;
		$res1 = is_dir("editables/".$language);
		if($res1 != 1)
			$res2= mkdir("editables/".$language, 0777, true);
		
		$fp = fopen("editables/".$language."/".$filename.".php", "w"); 

		$String = "<?php";
		
		foreach($data as $row)
		{
			$String .= "\n$"."lang['".$row['mainkey']."']['".$row['subkey']."']='".addslashes(stripslashes(str_replace('"',"'",$row['text'])))."';";
		}
		$String .= "\n?>";
		fwrite($fp, $String);
		fclose($fp); 
		if(file_exists("editables/".$language."/".$filename.".php"))
		{
			Logger_Array("Label file download",'mainkey, subkey, language', $row['mainkey'], $row['subkey'], $language);
			return 1;
		}
		else
			return 0;
	}
	function getAllPages()
	{
		global $db;		
		$p = "SELECT * from ! where 1";
		$data = $db->getAll($p,array('pages'));
		return $data;
	}
	function getAllLanguages()
	{
		global $db;		
		$p = "SELECT * from ! where 1";
		$data = $db->getAll($p,array('language'));
		return $data;
	}
	function getLanguagesByCode($code)
	{
		global $db;		
		$p = "SELECT lang from ! where langcode=?";
		$data = $db->getOne($p,array('language',$code));
		return $data;
	}
	function getPageByfilename($filename)
	{
		global $db;		
		$p = "SELECT pagename from ! where filename=?";
		$data = $db->getOne($p,array('pages',$filename));
		return $data;
	}
}		
?>
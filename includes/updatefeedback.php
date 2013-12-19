<?php
include_once("../library/session.php");
		
if(!isset($_POST["MessType"]) && $_POST["MessType"] !="Reply" && $_POST["MessType"] !="Update" && $_POST["MessType"] !="Delete" && $_POST["MessType"] !="Insert" & $_REQUEST["MessType"] !="ImgDel")
{
	if(isset($_POST['feedback'])&& !empty($_POST['txtcomment'])){	
			$userid=$_POST['userid'];
			$prurl = getUserProfileUrl($userid);
			$senderid=$_POST['senderid'];
			$comment=stripslashes($_POST['txtcomment']);
			$type=0;
			$reply=0;
			if(!empty($_POST['type'])){ 
				$type=$_POST['type'];
				$divid=$_POST['divid'];
				$reply=1;
			}
			$result=$database->subFeedback($userid,$senderid,$comment,$type,$reply);
			if($result==0){
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
			if(!empty($_POST['type']))
				header("Location: ../$prurl?fdb=2");
			else
				header("Location: ../$prurl#e4");
			}
			if($result==1){
				if(!empty($_POST['type']))
				header("Location: ../$prurl?fdb=2#$divid");
				else
				header("Location: ../$prurl#e4");
			}	

	}else{
		$userid=$_POST['userid'];
		$prurl = getUserProfileUrl($userid);
		header("Location: ../$prurl?fdb=2&err=1003");
	}
}
else{
	
	$senderid=$_POST["Senderid1"];
	$receiverid=$_POST["receiverid"];
	$subject=$_POST["subject"];
	$message=$_POST["message"];
	$parentid=$_POST["parentid"];
	$forumid=$_POST["forumid"];
	$thread=$_POST["thread"];
	if($_REQUEST["MessType"]=="Reply")
	{
		$FlagForuploadError=0;
		$FlagforMultiFile=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES[file1][name] as $key => $value) {

			$prefix = substr ( md5(uniqid(rand(),1)), 3, 10);
			$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix. basename($_FILES[file1][name][$key]);
			$uploadfile1 = $prefix. basename($_FILES[file1][name][$key]);
			$ext = explode(".",basename($_FILES[file1][name][$key]));
			
			if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && $_FILES['file1']['tmp_name'][$key] !="" && $FlagforMultiFile < count($_FILES[file1][name]) && (strtolower($ext[1])=="jpg" || strtolower($ext[1])=="gif"))
			{ 
				if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile)) { 					
					$FileName[]=$uploadfile;
					$FilePath[]=$uploadfile1;
				}
				else
				{
					$FlagForuploadError=1;
					break;//error
				}
			}
			$FlagforMultiFile++;
		}
		if($FlagForuploadError!=1)
		{
			$result=$database->forumFeedback($senderid,$receiverid,$subject,$message,$parentid,$thread,$forumid);		
			rebuildTree($thread,1,$thread,$db);
			$res=$database->ForumId($senderid, $receiverid, $forumid);
			
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				$resul=$database->insertCommentFile($res, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
			}
		}
	}else if($_REQUEST["MessType"]=="Update"){
			
		$FlagForuploadError=0;
		$FlagforMultiFile=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES[file1][name] as $key => $value) {

			$prefix = substr ( md5(uniqid(rand(),1)), 3, 10);
			$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix. basename($_FILES[file1][name][$key]);
			$uploadfile1 = $prefix. basename($_FILES[file1][name][$key]);
			$ext = explode(".",basename($_FILES[file1][name][$key]));
			
			if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && $_FILES['file1']['tmp_name'][$key] !="" && $FlagforMultiFile < count($_FILES[file1][name]) && (strtolower($ext[1])=="jpg" || strtolower($ext[1])=="gif"))
			{ 
				if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile)) { 
					
					$FileName[]=$uploadfile;
					$FilePath[]=$uploadfile1;
				}
				else
				{
					$FlagForuploadError=1;
					break;//error
				}
			}
			$FlagforMultiFile++;
		}

		if($FlagForuploadError!=1)
		{
			$result=$database->updateComment($message,$parentid, $senderid, $forumid);
			$res=$database->getCommentFile($senderid,$receiverid,$parentid);
			
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				if(count($res)==3)
				{
					$result=$database->updateCommentFile($FilePath[$filecount],$res[$filecount]['id']);
				}else{
				    $resul=$database->insertCommentFile($parentid, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
				}
			}
		}

	}else if($_REQUEST["MessType"]=="Delete")
	{
        $result=$database->deleteComment($parentid, $senderid, $forumid);
		
	}else if($_REQUEST["MessType"]=="Insert")
	{

		$FlagForuploadError=0;
		$FlagforMultiFile=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES[file1][name] as $key => $value) {

			$prefix = substr ( md5(uniqid(rand(),1)), 3, 10);
			$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix. basename($_FILES[file1][name][$key]);
			$uploadfile1 = $prefix. basename($_FILES[file1][name][$key]);
			$ext = explode(".",basename($_FILES[file1][name][$key]));
			
			if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && $_FILES['file1']['tmp_name'][$key] !="" && $FlagforMultiFile < count($_FILES[file1][name]) && (strtolower($ext[1])=="jpg" || strtolower($ext[1])=="gif"))
			{ 
				if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile)) { 
					
					$FileName[]=$uploadfile;
					$FilePath[]=$uploadfile1;
				}
				else
				{
					$FlagForuploadError=1;
					break;//error
				}
			}
			$FlagforMultiFile++;
		}

		if($FlagForuploadError!=1)
		{
			$userid=$_POST['userid'];
			$receiverid=$_POST['userid'];
			$senderid=$_POST['senderid'];
			$comment=stripslashes($_POST['txtcomment']);
			$type=0;
			$reply=0;
			if(!empty($_POST['type'])){ 
				$type=$_POST['type'];
				$divid=$_POST['divid'];
				$reply=1;
			}
			$result=$database->subFeedback1($userid,$senderid,$comment,$type,$reply);
			$res=$database->getCommentFile($senderid,$receiverid,0);
            
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				if(count($res)==3)
				{
                    $result=$database->updateCommentFile($FilePath[$filecount],$res[$filecount]['id']);					
				}else{
                    
					$resultForum=$database->nextForumId($senderid,$userid);
                    $resParent=$database->nextCommentId($senderid,$userid,0);
                    
				    $resul=$database->insertCommentFile($resParent, $senderid, $receiverid, $resultForum, $FilePath[$filecount]);
				}
			}
		}
	}else if($_REQUEST["MessType"]=="ImgDel")
	{
        $receiverid=$_REQUEST['userid'];
        $result=$database->deleteCommentFile($_REQUEST['ImgFile'], $_REQUEST['imgID']);
    }
    else if($_REQUEST["MessType"]=="DeleteReal")
	{
         $result=$database->deleteCommentReal($parentid, $senderid, $forumid);
    }
		if($result==0){
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
			if(!empty($_POST['MessType']))
				header("Location: ../index.php?p=12&u=$receiverid&fdb=2");
			else
				header("Location: ../index.php?p=12&u=$senderid#e4");
			}
			if($result==1){
               
				$divid=$_POST['divid'];
                $ld   =$_REQUEST['loanid'];
				if(!empty($_REQUEST['MessType']))
				{	
					$loanprurl = getLoanprofileUrl($receiverid, $ld);
					if($_REQUEST['return']=="down")
						header("Location: ../index.php?p=12&u=$receiverid");
					else if($_REQUEST['return']=="up")
						header("Location: ../index.php?p=12&u=$receiverid&fdb=1");
					else if($_REQUEST['return']=="down1")
                        header("Location: ../$loanprurl");
                    else
						header("Location: ../index.php?p=12&u=$receiverid");
				}
				else
				{
                    echo "error";exit;
					header("Location: ../index.php?p=12&u=$senderid#e4");
				}
			}	
}

 function rebuildTree($parentId, $left, $thread, $db = null)
    {
        $right = $left + 1;
        
        $query = 'SELECT a.id
                  FROM zi_comment AS a
                  WHERE a.parentId = '.(int)$parentId.'
                  ORDER BY a.left'
        ;
       
        $childIds = ($childIds =$db->getAll($query))?$childIds:array();
	
	$i=0;
        foreach ($childIds as $childId) {
			//echo $childId['id'];
            $right = rebuildTree($childId['id'], $right, $thread, $db);
			$i++;
        }

        $query = 'UPDATE zi_comment AS a
                  SET a.left = '.(int)$left.', a.right = '.(int)$right.', a.thread = '.(int)$thread.'
                  WHERE a.id = '.(int)$parentId
        ;
				 
        
        $result=$db->query($query);
		 
        return $right + 1;
    }
?>
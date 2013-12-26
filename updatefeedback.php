<?php
$backComment='';
if(isset($_POST['backComment']))
	$backComment='#'.$_POST['backComment'];
include_once("library/session.php");
global $session;
$userid=$_POST['userid'];
$loanid=$_POST['loanid'];
$return =$_POST['return'];
if(isset($_POST['feedback']))
{
	if($_POST["MessType"]=="Insert")
	{
		$FlagForuploadError=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES['file1']['name'] as $key => $value)
		{
			if(!empty($value)) {
				$path_info = pathinfo($value);
				$ext=$path_info['extension'];
				$prefix = md5(uniqid(rand(),1));
				$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix.".".strtolower($ext);
				$filename = $prefix.".".strtolower($ext);

				if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && !empty($_FILES['file1']['tmp_name'][$key]) && (strtolower($ext)=="jpeg" || strtolower($ext)=="jpg" || strtolower($ext)=="png" || strtolower($ext)=="gif"))
				{
					if(move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile))
					{
						$FileName[]=$uploadfile;
						$FilePath[]=$filename;
					}
					else
					{
						$FlagForuploadError=1;
						break;//error
					}
				}
			}
		}

		if($FlagForuploadError!=1)
		{
			$receiverid=$_POST['userid'];
			$senderid=$_POST['senderid'];
            if(!isset($loanid) || empty($loanid))
				$loanid = $database->getUNClosedLoanid($receiverid);

			$comment=stripslashes(strip_tags(trim($_POST['txtcomment'])));
			$type=0;
			$reply=0;
			if(!empty($_POST['type']))
			{
				$type=$_POST['type'];
				$divid=$_POST['divid'];
				$reply=1;
			}
			$commentid=$database->subFeedback1($userid,$senderid,$comment,$type,$reply);
			
			/**** Integration with shift science on date 26-12-2013******/
			$session->invoiceShiftScience('borrower_comments',$userid,'','','','','','',$comment,'',$senderid);
			
			$res=$database->getCommentFile($senderid,$receiverid,0);
            $resParent=$database->nextCommentId($senderid,$userid,0);

			for($filecount=0; $filecount < count($FilePath); $filecount++)
			{
				if(count($res)==3)
				{
                    $result=$database->updateCommentFile($FilePath[$filecount],$res[$filecount]['id']);
				}
				else
				{
					$resultForum=$database->nextForumId($senderid,$userid);
                    $resParent=$database->nextCommentId($senderid,$userid,0);
					$resul=$database->insertCommentFile($resParent, $senderid, $receiverid, $resultForum, $FilePath[$filecount]);
				}
			}
			$commentlength = strlen($comment);
			$database->setcommentcredit($userid, $commentlength, $loanid, $commentid);
			
			//$session->sendCommentMails($loanid, $userid, $comment, $resParent); Comment by mohit 22-10-13

			// added by mohit 22-10-13
			$emailContAttr=array();
			$emailContAttr[]=$loanid;
			$emailContAttr[]= $userid;
			$emailContAttr[]=$comment;
			$emailContAttr[]=$resParent;
			$database->addEvent(COMMENT_POST_EVENT, $emailContAttr);
			}
	}
	else if($_POST["MessType"]=="Update")
	{
		$FlagForuploadError=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES['file1']['name'] as $key => $value)
		{
			$prefix = md5(uniqid(rand(),1));
			$path_info = pathinfo($value);
			$ext=$path_info['extension'];
			$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix.".".strtolower($ext);
			$filename = $prefix.".".strtolower($ext);

			if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && !empty($_FILES['file1']['tmp_name'][$key]) && (strtolower($ext)=="jpeg" || strtolower($ext)=="jpg" || strtolower($ext)=="png" || strtolower($ext)=="gif"))
			{
				if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile))
				{
					$FileName[]=$uploadfile;
					$FilePath[]=$filename;
				}
				else
				{
					$FlagForuploadError=1;
					break;//error
				}
			}
		}
		if($FlagForuploadError!=1)
		{
			$senderid=$_POST["Senderid1"];
			$receiverid=$_POST["receiverid"];
			$subject=$_POST["subject"];
			$message=stripslashes(strip_tags(trim($_POST["message"])));
			$parentid=$_POST["parentid"];
			$forumid=$_POST["forumid"];
			$thread=$_POST["thread"];
			$loanid = $_POST["loanid"];
			$comntuserid = $_POST["userid"];
			$lastcCmntLength = $database->getPreviousCommentLength($parentid);
			$result=$database->updateComment($message,$parentid, $senderid, $forumid);
			$res=$database->getCommentFile($senderid,$receiverid,$parentid);
			if($result) {
				$updatedlength = strlen($message);
				$crditLimit = $database->getcreditLimitbyuser($comntuserid, 1);
				if($updatedlength >= $crditLimit['character_limit'] && $lastcCmntLength < $crditLimit['character_limit']) {
					$database->setcommentcredit($comntuserid, $updatedlength, $loanid, $parentid);
				}else if($lastcCmntLength  >= $crditLimit['character_limit'] && $crditLimit['character_limit'] > $updatedlength ) {
					$database->deletecredit($parentid, $comntuserid);
				}
			}
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				if(count($res)==3)
				{
					$result=$database->updateCommentFile($FilePath[$filecount],$res[$filecount]['id']);
				}
				else
				{
				    $resul=$database->insertCommentFile($parentid, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
				}
			}
		}
	}
	else if($_POST["MessType"]=="Reply")
	{
		$FlagForuploadError=0;
		$FileName=array();
		$FilePath=array();
		foreach ($_FILES['file1']['name'] as $key => $value)
		{
			$prefix = md5(uniqid(rand(),1));
			$path_info = pathinfo($value);
			$ext=$path_info['extension'];
			$uploadfile = UPLOAD_COMMENT_IMAGE_DIR.$prefix.".".strtolower($ext);
			$filename = $prefix.".".strtolower($ext);

			if (is_uploaded_file($_FILES['file1']['tmp_name'][$key]) && !empty($_FILES['file1']['tmp_name'][$key]) && (strtolower($ext)=="jpeg" || strtolower($ext)=="jpg" || strtolower($ext)=="png" || strtolower($ext)=="gif"))
			{
				if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploadfile))
				{
					$FileName[]=$uploadfile;
					$FilePath[]=$filename;
				}
				else
				{
					$FlagForuploadError=1;
					break;//error
				}
			}
		}
		if($FlagForuploadError!=1)
		{
			$senderid=$_POST["Senderid1"];
			$receiverid=$_POST["receiverid"];
			$subject=$_POST["subject"];
			$message=stripslashes(strip_tags(trim($_POST["message"])));
			$parentid=$_POST["parentid"];
			$forumid=$_POST["forumid"];
			$thread=$_POST["thread"];
			if(!isset($loanid) || empty($loanid))
				$loanid = $database->getUNClosedLoanid($receiverid);
			$replyid=$database->forumFeedback($senderid,$receiverid,$subject,$message,$parentid,$thread,$forumid);
			
			/**** Integration with shift science on date 26-12-2013******/
			$session->invoiceShiftScience('comments_reply',$receiverid,'','','','','','',$message,$subject,$senderid);
			
			rebuildTree($parentid,1,$thread,$db); // added by mohit $thread replaceby $parentid on date 21-10-13
			$res=$database->ForumId($senderid, $receiverid, $forumid);
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				$resul=$database->insertCommentFile($res, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
			}
		}

		$commentlength = strlen($message);
		$database->setcommentcredit($userid, $commentlength, $loanid, $replyid);
		
		//$session->sendCommentMails($loanid, $receiverid, $message, $res); Comment by mohit 22-10-13
		
		// added by mohit 22-10-13
		$emailContAttr=array();
		$emailContAttr[]=$loanid;
		$emailContAttr[]= $receiverid;
		$emailContAttr[]=$message;
		$emailContAttr[]=$res;
		$database->addEvent(COMMENT_POST_EVENT, $emailContAttr);
	}
	else if($_POST["MessType"]=="Feedback")
	{
		$senderid=$_POST['senderid'];
		$comment=stripslashes(strip_tags(trim($_POST['txtcomment'])));
		$type=0;
		$reply=0;
		if(!empty($_POST['type']))
		{
			$type=$_POST['type'];
			$reply=1;
		}
		if(!empty($comment))
		{
			$result=$database->subFeedback($userid,$senderid,$comment,$type,$reply);
		}
		/*if($result==0){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
		if(!empty($_POST['type']))
			header("Location: index.php?p=12&u=$userid&fdb=2");
		else
			header("Location: index.php?p=12&u=$userid#e4");
		}
		if($result==1){
			if(!empty($_POST['type']))
			header("Location: index.php?p=12&u=$userid&fdb=2#$divid");
			else
			header("Location: index.php?p=12&u=$userid#e4");
		}*/
	}
}
else if($_POST["MessType"]=="ImgDel")
{
	$receiverid=$_POST['receiverid'];
	$imgID=$_POST['imgID'];
	$ImgFile=$_POST['ImgFile'];
	if($session->userid==$receiverid || $session->userid == ADMIN_ID)
	{
		Logger_Array("deleteCommentFile-MessType-ImgDel",$session->userid,$ImgFile,$imgID,$receiverid);
		$result=$database->deleteCommentFile($ImgFile, $imgID);
	}
}
else if($_POST["MessType"]=="Delete")
{

	$senderid=$_POST["Senderid1"];
	$parentid=$_POST["parentid"];
	$forumid=$_POST["forumid"];
	if($session->userid==$senderid || $session->userid == ADMIN_ID)
	{
		Logger_Array("deleteComment-MessType-Delete",$session->userid,$parentid,$senderid, $forumid);
		$result=$database->deleteComment($parentid, $senderid, $forumid);
		if($result) {
			$database->deletecredit($parentid, $senderid);
		}
	}
}
else if($_POST["MessType"]=="DeleteReal")
{
	$senderid=$_POST["Senderid1"];
	$parentid=$_POST["parentid"];
	$forumid=$_POST["forumid"];
	if($session->userid==$senderid || $session->userid == ADMIN_ID)
	{
		Logger_Array("deleteComment-MessType-DeleteReal",$session->userid, $parentid,$senderid,$forumid);
		$result=$database->deleteCommentReal($parentid, $senderid, $forumid);
		if($result) {
			$database->deletecredit($parentid, $senderid);
		}
	}
}
else if($_POST["MessType"]=="Feature on homepage")
{
	$result=$database->publishComment($_POST['PublishID']);
}
else if($_POST["MessType"]=="Remove from homepage")
{
	$result=$database->UnpublishComment($_POST['PublishID']);
}
if($return==14)
{
	$loanprurl = getLoanprofileUrl($userid,$loanid);
	header("Location: $loanprurl".$backComment);
}
else if($return==12)
{
	$prurl = getUserProfileUrl($userid);
	if($_POST["MessType"]=="Feedback")
	{
		header("Location: $prurl?fdb=2".$backComment);
	}
	else if($_POST["fb"]=="1") 
	{
		header("Location: $prurl?fdb=1".$backComment);
	}
	else
	{
		header("Location: $prurl".$backComment);
	}
}
function rebuildTree($parentId, $left, $thread, $db = null)
{
	$right = $left + 1;
	$query = 'SELECT a.id FROM zi_comment AS a WHERE a.parentId = '.(int)$parentId.' ORDER BY a.left';
	$childIds = ($childIds =$db->getAll($query))?$childIds:array();
	$i=0;
	foreach ($childIds as $childId)
	{
		$right = rebuildTree($childId['id'], $right, $thread, $db);
		$i++;
	}
	$query = 'UPDATE zi_comment AS a SET a.left = '.(int)$left.', a.right = '.(int)$right.', a.thread = '.(int)$thread.' WHERE a.id = '.(int)$parentId;
	$result=$db->query($query);
	return $right + 1;
}
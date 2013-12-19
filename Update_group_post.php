<?php
$backComment='';
if(isset($_POST['backComment']))
	$backComment='#'.$_POST['backComment'];
include_once("library/session.php");
global $session;
$grp_id=$_POST['group_id'];
$return =$_POST['return'];
if(isset($_POST['group_post']))
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
		{	$receiverid=$grp_id;
			$senderid=$_POST['senderid'];
			$comment=stripslashes(strip_tags(trim($_POST['txtcomment'])));
			$type=0;
			$reply=0;
			if(!empty($_POST['type']))
			{
				$type=$_POST['type'];
				//$divid=$_POST['divid'];
				$reply=1;
			}
			$commentid=$database->group_post($grp_id,$senderid,$comment,$type,$reply, $grp_id);
			$res=$database->getGrpCommentFile($senderid,$receiverid,0);
			$resParent=$database->nextGrpCommentId($senderid,$grp_id,0);

			for($filecount=0; $filecount < count($FilePath); $filecount++)
			{
				if(count($res)==3)
				{
					$result=$database->updateGrpCommentFile($FilePath[$filecount],$res[$filecount]['id']);
				}
				else
				{
					$resultForum=$database->nextGrpForumId($senderid,$grp_id);
					$resParent=$database->nextGrpCommentId($senderid,$grp_id,0);
					$result=$database->insertGrpCommentFile($resParent, $senderid, $receiverid, $resultForum, $FilePath[$filecount]);
				}
			}
			//$session->sendCommentMails($loanid, $grp_id, $comment, $resParent);
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
			$comntuserid = $grp_id;
			$result=$database->updateGrpComment($message,$parentid, $senderid, $forumid);
			$res=$database->getGrpCommentFile($senderid,$receiverid,$parentid);
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				if(count($res)==3)
				{
					$result=$database->updateGrpCommentFile($FilePath[$filecount],$res[$filecount]['id']);
				}
				else
				{
					$resul=$database->insertGrpCommentFile($parentid, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
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
			rebuildTree($thread,1,$thread,$db);
			$replyid=$database->forumgroup_post($senderid,$receiverid,$subject,$message,$parentid,$thread,$forumid,$grp_id);
			rebuildTree($thread,1,$thread,$db);
			$res=$database->ForumId($senderid, $receiverid, $forumid);
			for($filecount=0;$filecount < count($FilePath);$filecount++)
			{
				$resul=$database->insertGrpCommentFile($res, $senderid, $receiverid, $forumid, $FilePath[$filecount]);
			}
		}
		
	}
	
	
}
else if($_POST["MessType"]=="ImgDel") {
	$receiverid=$_POST['receiverid'];
	$imgID=$_POST['imgID'];
	$ImgFile=$_POST['ImgFile'];
	if($session->userid==$receiverid || $session->userid == ADMIN_ID)
	{
		Logger_Array("deletegrpCommentFile-MessType-ImgDel",$session->userid,$ImgFile,$imgID,$receiverid);
		$result=$database->deleteGrpCommentFile($ImgFile, $imgID);
	}
} 
else if($_POST["MessType"]=="Delete") {
		$senderid=$_POST["Senderid1"];
		$parentid=$_POST["parentid"];
		$forumid=$_POST["forumid"];
		if($session->userid==$senderid || $session->userid == ADMIN_ID)
		{
			Logger_Array("deletegrpComment-MessType-Delete",$session->userid,$parentid,$senderid, $forumid);
			$result=$database->deletegrpComment($parentid, $senderid, $forumid);
		}
	}
else if($_POST["MessType"]=="DeleteReal")
{
	$senderid=$_POST["Senderid1"];
	$parentid=$_POST["parentid"];
	$forumid=$_POST["forumid"];
	if($session->userid==$senderid || $session->userid == ADMIN_ID)
	{
		Logger_Array("deletegrpComment-MessType-DeleteReal",$session->userid, $parentid,$senderid,$forumid);
		$result=$database->deleteGrpCommentReal($parentid, $senderid, $forumid);
	}
}
else if($_POST["MessType"]=="Publish")
{
	if($session->userid == ADMIN_ID)
	{
		$result=$database->publishComment($_POST['PublishID']);
	}
}
else if($_POST["MessType"]=="UnPublish")
{
	if($session->userid == ADMIN_ID)
	{
		$result=$database->UnpublishComment($_POST['PublishID']);
	}
}
if($return==82) {
	header("Location: index.php?p=82&gid=$grp_id".$backComment);
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

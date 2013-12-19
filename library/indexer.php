<?php 
// Add your vendor directory to the includepath. ZF needs this. 
ini_set('include_path', ini_get('include_path') . ':'.FULL_PATH.'library/'); 
require_once('Zend/Search/Lucene.php'); 

function createIndex()
{
	global $db;	
	$dir=FULL_PATH.'Zend/app/tmp/cache';
	$iterator = new RecursiveDirectoryIterator($dir);
	foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) 
	{
		if ($file->isDir()){
			rmdir($file->getPathname());
		}else{
			unlink($file->getPathname());
		}
	}
	$indexPath1 = FULL_PATH.'Zend/app/tmp/cache/index1'; 
	$index1 = new Zend_Search_Lucene($indexPath1, true); 
	$q="SELECT userid, FirstName, LastName, About, tr_About, BizDesc, tr_BizDesc  FROM ! where 1";	
	$res1=$db->getAll($q, array(TBL_BORROWER));
	foreach($res1 as $row1)
	{
		$doc1 = new Zend_Search_Lucene_Document(); 
		// Add some information 
		$doc1->addField(Zend_Search_Lucene_Field::Keyword('pk', $row1['userid']));
		$doc1->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $row1['userid'])); 
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_FirstName', $row1['FirstName']), 'utf-8');
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_LastName', $row1['LastName']), 'utf-8');
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_About', $row1['About']), 'utf-8'); 
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_tr_About', $row1['tr_About']), 'utf-8');
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_BizDesc', $row1['BizDesc']), 'utf-8');
		$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_tr_BizDesc', $row1['tr_BizDesc']), 'utf-8');
		$index1->addDocument($doc1); 
	}
	$index1->commit(); 

	$indexPath2 = FULL_PATH.'Zend/app/tmp/cache/index2'; 
	$index2 = new Zend_Search_Lucene($indexPath2, true); 
	$p="SELECT borrowerid,loanid, loanuse,tr_loanuse FROM ! where 1";	
	$res2=$db->getAll($p, array(TBL_LOANAPPLIC));
	foreach($res2 as $row2)
	{
		$doc2 = new Zend_Search_Lucene_Document(); 
		// Add some information 
		$doc2->addField(Zend_Search_Lucene_Field::Keyword('pk', $row2['loanid']));
		$doc2->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $row2['borrowerid'])); 
		$doc2->addField(Zend_Search_Lucene_Field::UnStored('document_loanuse', $row2['loanuse']), 'utf-8'); 
		$doc2->addField(Zend_Search_Lucene_Field::UnStored('document_tr_loanuse', $row2['tr_loanuse']), 'utf-8');
		$index2->addDocument($doc2); 
	}

	$index2->commit(); 

	$indexPath3 = FULL_PATH.'Zend/app/tmp/cache/index3'; 
	$index3 = new Zend_Search_Lucene($indexPath3, true); 
	$r="SELECT id,receiverid, message, tr_message FROM ! where 1";	
	$res3=$db->getAll($r, array('zi_comment'));
	foreach($res3 as $row3)
	{
		$doc3 = new Zend_Search_Lucene_Document(); 
		// Add some information 
		$doc3->addField(Zend_Search_Lucene_Field::Keyword('pk',$row3['id']));
		$doc3->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $row3['receiverid'])); 
		$doc3->addField(Zend_Search_Lucene_Field::UnStored('document_message', $row3['message']), 'utf-8'); 
		$doc3->addField(Zend_Search_Lucene_Field::UnStored('document_tr_message', $row3['tr_message']), 'utf-8');
		$index3->addDocument($doc3); 
	}

	$index3->commit(); 

	/*$queryStr= "use";
	$query = Zend_Search_Lucene_Search_QueryParser::parse($queryStr);
	$index = Zend_Search_Lucene::open(FULL_PATH.'Zend/app/tmp/cache/index3');
	$results = $index->find($query);
	//print_r($results);
	$count = 0;
	foreach ($results as $result)
	{
		$data[$count]["userid"] = $result->document_id;
		$count++;
	}
	print_R($data);exit;*/
	$count=0;
	foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) 
	{	
		$count++;
	}
	return $count;
}
function updateIndex1($userid)
{
	global $db;	
	$q="SELECT FirstName, LastName, About, tr_About, BizDesc, tr_BizDesc  FROM ! where userid=?";	
	$row1=$db->getRow($q, array(TBL_BORROWER,$userid));
	$index = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index1");
	foreach($index->find('pk:'.$userid) as $hit){
		$index->delete($hit->id);		
	}
	$doc1 = new Zend_Search_Lucene_Document(); 
	// Add some information 
	$doc1->addField(Zend_Search_Lucene_Field::Keyword('pk', $userid));
	$doc1->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $userid)); 
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_FirstName', $row1['FirstName']), 'utf-8'); 
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_LastName', $row1['LastName']), 'utf-8'); 
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_About', $row1['About']), 'utf-8'); 
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_tr_About', $row1['tr_About']), 'utf-8');
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_BizDesc', $row1['BizDesc']), 'utf-8');
	$doc1->addField(Zend_Search_Lucene_Field::UnStored('document_tr_BizDesc', $row1['tr_BizDesc']), 'utf-8');
	$index->addDocument($doc1); 
	$index->commit(); 
}
function updateIndex2($loanid)
{
	global $db;	
	$q="SELECT borrowerid,loanuse, tr_loanuse FROM ! where loanid=?";	
	$row2=$db->getRow($q, array(TBL_LOANAPPLIC,$loanid));

	$index = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index2");
	foreach($index->find('pk:'.$loanid) as $hit){
		$index->delete($hit->id);		
	}
	$doc2 = new Zend_Search_Lucene_Document(); 
	// Add some information 
	$doc2->addField(Zend_Search_Lucene_Field::Keyword('pk', $loanid));
	$doc2->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $row2['borrowerid'])); 
	$doc2->addField(Zend_Search_Lucene_Field::UnStored('document_loanuse', $row2['loanuse']), 'utf-8'); 
	$doc2->addField(Zend_Search_Lucene_Field::UnStored('document_tr_loanuse', $row2['tr_loanuse']), 'utf-8');
	$index->addDocument($doc2); 
	$index->commit(); 
	
}
function updateIndex3($commentid)
{
	global $db;
	$q="SELECT receiverid, message, tr_message FROM ! where id=?";	
	$row3=$db->getRow($q, array('zi_comment',$commentid));

	$index = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index3");
	foreach($index->find('pk:'.$commentid) as $hit){
		$index->delete($hit->id);		
	}
	$doc3 = new Zend_Search_Lucene_Document(); 
	// Add some information 
	$doc3->addField(Zend_Search_Lucene_Field::Keyword('pk',$commentid));
	$doc3->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $row3['receiverid'])); 
	$doc3->addField(Zend_Search_Lucene_Field::UnStored('document_message', $row3['message']), 'utf-8'); 
	$doc3->addField(Zend_Search_Lucene_Field::UnStored('document_tr_message', $row3['tr_message']), 'utf-8');
	$index->addDocument($doc3); 
	$index->commit(); 
}
function deleteIndex3($commentid)
{
	global $db;
	$index = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index3");
	foreach($index->find('pk:'.$commentid) as $hit){
		$index->delete($hit->id);		
	}
}
?>
<?php

include("../library/session.php");
	$key = key($_GET);
	if($key=='language') {
		unset($_GET[$key]);
		$key = key($_GET);
	}
	$value = $_GET[$key];
	$borrowerid = $_GET['borrowerid'];	
	if($key == 'bpostaddr'){
		if(!$value || strlen($value=trim($value))<0){
			echo 1; //error
		}

		$result= $database->setbrwrdetailByadmin('PAddress', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'btelmobile'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
		$result= $database->setbrwrdetailByadmin('TelMobile', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'bfamily1'){
		if(empty($value) || strlen($value=trim($value))<0){
			echo 1; //error
		}

		$result= $database->setbrwrExtndetailByadmin('family_member1', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'bfamily2'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrExtndetailByadmin('family_member2', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	
	else if($key == 'bfamily3'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrExtndetailByadmin('family_member3', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'neighbor1'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrExtndetailByadmin('neighbor1', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'neighbor2'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrExtndetailByadmin('neighbor2', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'neighbor3'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrExtndetailByadmin('neighbor1', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'refername'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
						
		$result= $database->setbrwrdetailByadmin('refer_member_name', $value, $borrowerid);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'mentor'){
		
		$result= $database->setbrwrExtndetailByadmin('mentor_id', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'bfirstname'){
		
		$result= $database->setbrwrdetailByadmin('FirstName', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'blastname'){
		
		$result= $database->setbrwrdetailByadmin('LastName', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'recomName'){
		
		$result= $database->setbrwrExtndetailByadmin('rec_form_offcr_name', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'recoNumber'){
		
		$result= $database->setbrwrExtndetailByadmin('rec_form_offcr_num', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'bemail'){
		
		$result= $database->setbrwrdetailByadmin('Email', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'bcity'){ 
		
		$result= $database->setbrwrdetailByadmin('City', $value, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	else if($key == 'bcountry'){ 
		
		$result= $database->setbrwrdetailByadmin('Country', $value, $borrowerid);
		$currency= $database->getCurrencyIdByCountryCode($value);
		$res= $database->setbrwrdetailByadmin('currency', $currency, $borrowerid);
		if($result){
			echo 0;
			exit;
		}
		else{
			echo 1;
			exit;
		}
	}
	
?>
<?php
	include("library/session.php");
	
	processAllHistoricalData('create_account_history');
	
	function processAllHistoricalData($event_type){
			
			global $database,$session;
			
			if($event_type=='login_history'){		/* Login history */
				
				$lastLoginProcessId=$database->getLastProcessId('login_event');
				$result=$database->getloginHistOfAllBorrower($lastLoginProcessId);
				
				foreach($result as $key=>$value)
				{
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'user_name' => $value['username'],
						  'last_login' => $value['last_login']
						);
				
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					
					$responce=$session->sendPostData($url_send, $str_data);
					
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['userid']."' where shift_event_type='login_event'";
						mysql_query($q);	
					}
				} // end of foreach
			}else if($event_type=='create_account_history'){		/* Create Account history */				
				$lastLoginProcessId=$database->getLastProcessId('create_account_event');
				$result=$database->getBorrowerAccountHist($lastLoginProcessId);
				foreach($result as $key=>$value)
				{	
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'first_name' => $value['FirstName'],
						  'last_name' => $value['LastName'],
						  'account_created_on' => $value['Created'],
						  'account_completed_on' => $value['completed_on'],
						  'aboutme' => $value['About'],
						  'aboutbusiness' => $value['BizDesc'],
						  'hearaboutzidisha' => $value['reffered_by']
						);
					
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					$responce=$session->sendPostData($url_send, $str_data);
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['userid']."' where 
								 shift_event_type='create_account_event'";
						mysql_query($q);	
					}
				} // end of foreach
			}else if($event_type=='edit_account_history'){		/* Edit Account history */				
				$lastLoginProcessId=$database->getLastProcessId('edit_account');
				$result=$database->getBorrowerAccountHist($lastLoginProcessId);
				foreach($result as $key=>$value)
				{	
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'first_name' => $value['FirstName'],
						  'last_name' => $value['LastName'],
						  'last_modify_on' => $value['LastModified'],
						  'aboutme' => $value['About'],
						  'aboutbusiness' => $value['BizDesc'],
						  'hearaboutzidisha' => $value['reffered_by']
						);
					
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					$responce=$session->sendPostData($url_send, $str_data);
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['userid']."' where 
								 shift_event_type='edit_account'";
						mysql_query($q);	
					}
				} // end of foreach
			}else if($event_type=='facebook_connect_history'){		/* Facebook Connect history */				
				$lastLoginProcessId=$database->getLastProcessId('facebook_connect');
				$result=$database->getFacebookConnectHist($lastLoginProcessId);
				foreach($result as $key=>$value)
				{						
					if($value['accept']==1){
					$status='success';} else{ $status='failed';
					}
					
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'facebook_id' => $value['facebook_id'],
						  'login_status' => $status,
						  'login_date' => $value['date'],
						  'ip_address' => $value['ip_address']
						);
					
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					$responce=$session->sendPostData($url_send, $str_data);
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['id']."' where 
								 shift_event_type='facebook_connect'";
						mysql_query($q);	
					}
				} // end of foreach
			}else if($event_type=='loan_reypayment_history'){		/* Loan Reypayment history */				
				$lastLoginProcessId=$database->getLastProcessId('loan_repay_event');
				$result=$database->getloanReypayHist($lastLoginProcessId);
				foreach($result as $key=>$value)
				{													
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'loan_id' => $value['loanid'],
						  'paid_amount' => $value['paidamt'],
						  'paid_date' => $value['paiddate']
						);
					
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					$responce=$session->sendPostData($url_send, $str_data);
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['id']."' where 
								 shift_event_type='loan_repay_event'";
						mysql_query($q);	
					}
				} // end of foreach
			}else if($event_type=='loan_disbursement_history'){		/* Loan Disbursement history */				
				$lastLoginProcessId=$database->getLastProcessId('loan_disburse_event');
				$result=$database->getloanDisbursHist($lastLoginProcessId);
				foreach($result as $key=>$value)
				{	
					$amount=str_replace("-","",$value['amount']);	
					$data = array(
						  '$type' => $event_type,
						  '$api_key' => SHIFT_SCIENCE_KEY,
						  '$user_id' => $value['userid'],
						  'loan_id' => $value['loanid'],
						  'disbursement_amount' => $amount,
						  'disbursement_date' => $value['TrDate']
						);
					
					$url_send ="https://api.siftscience.com/v203/events"; 
					$str_data = json_encode($data);	
					$responce=$session->sendPostData($url_send, $str_data);
					$record=json_decode($responce);
					
					if($record->status==0){
						$q="UPDATE temp_shiftevent SET processed_userid='".$value['id']."' where 
								 shift_event_type='loan_disburse_event'";
						mysql_query($q);	
					}
				} // end of foreach
			}
			
	}		
?>
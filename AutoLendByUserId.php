<?php
include("library/session.php");
global $database,$session;?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
ProcessAutoBidding();

function ProcessAutoBidding(){
		global $database;
		$result=$database->getAllLenderForAutoLend();
		if(!empty($result)){
			foreach($result as $key=>$value) {				
				$database->updateAutoLend($value['lender_id']);
				AutoLendByUserId($value['lender_id']);							
			}
		}else{
			echo "No Lender is available for autolending.";
		}	
}

	function AutoLendByUserId($userid)
	{	
		global $database,$session;
		$GLOBALS['loanArray']=array();
		$fullyFundedAll = array();
		$lenders=$database->getAllLenderForAutoLend($userid);
		$GLOBALS['loanArray']=$database->getAllLoansForAutoLend();
		foreach($GLOBALS['loanArray'] as $key => $row) {
			$status = $session->getStatusBar($row['borrowerid'],$row['loanid'],5);						
			if($status >=100) {
				unset($GLOBALS['loanArray'][$key]);
			}
		}
		if(!empty($GLOBALS['loanArray'])) {
			shuffle($GLOBALS['loanArray']);
		}
		if(!empty($lenders)){
			shuffle($lenders);
			foreach($lenders as $lender) {
			Logger_Array("AutoBid---LOG",'lender ID',$lender['lender_id']);
				$possibleLoans=0;
				$loansToAutolend=array();
				$availAmount=$session->amountToUseForBid($lender['lender_id']);

				if ($availAmount >= AUTO_LEND_AMT) {
					if($lender['current_allocated']==0) {
						$amounToAutoLend=bcsub($availAmount, $lender['lender_credit'],2);
						if($amounToAutoLend >=AUTO_LEND_AMT) {
						$possibleLoans=floor($amounToAutoLend/AUTO_LEND_AMT);
						}
					} else {
						$possibleLoans=floor($availAmount/AUTO_LEND_AMT);
					}	
						Logger_Array("AutoBid---LOG",'$possibleLoans =',$possibleLoans);
					if($possibleLoans) {						
						$loansToAutolend = $database->getSortedLoanForAutoBid($lender['preference'] ,$GLOBALS['loanArray'], $lender['desired_interest'], $lender['max_desired_interest'], $fullyFundedAll);
						
						Logger_Array("AutoBid---LOG",'Possible loan','sorted Loan'.$possibleLoans,count($loansToAutolend));
						
						if($possibleLoans < count($loansToAutolend)) {
						$loansToAutolend=array_slice($loansToAutolend, 0, $possibleLoans);
						}
						Logger_Array("AutoBid---LOG",'$loansToAutolend','loanid','reqdamt','interest','interest','WebFee','applydate','borrowerid','intOffer',$loansToAutolend);
						
						if(!empty($loansToAutolend)) {					
						$fullyFundedAll = placeAutobid($lender['preference'], $loansToAutolend, $possibleLoans, $lender['lender_id'], $lender['desired_interest'], $lender['max_desired_interest']);
						}
					}
			   } 
			}
		}
	}
	
// Place Bid
	
function placeAutobid($preference, $loansToAutolend, $possibleBids, $lenderId, $desiredInt, $MaxdesiredInt)
	{		
		global $database,$session,$form;
		$amntToLend=0;
		$amountTobid=0;
		$processed=array();				
		Logger_Array("AutoBid---LOG",'laon count line no 74',count($loansToAutolend));
		$loans = $session->getLoansForBid($preference, $loansToAutolend, $processed);
		Logger_Array("AutoBid---LOG",'No of Loans line no 76',count($loans));
		$fullyFunded=array();
		if(!empty($loans)) {
			while(1) {
				if(count($loans)==1){
					Logger_Array("AutoBid---LOG",'Log only one loan left Line No','84');
					if($possibleBids) {
						$totBid=$database->getTotalBid($loans[0]['borrowerid'],$loans[0]['loanid']);
						$reqdamt=$loans[0]['reqdamt'];
						$StillNeeded=bcsub($reqdamt, $totBid, 2);
						$amountcanBid=AUTO_LEND_AMT*$possibleBids;
						if($StillNeeded > 0) {
							if($StillNeeded >= $amountcanBid) {
								$amountTobid = $amountcanBid;
							}else if($StillNeeded < $amountcanBid){
								$amountTobid = $StillNeeded;
							}
							if($MaxdesiredInt < $loans[0]['intOffer']) {
								$intToPlaceBid = $MaxdesiredInt;
							}else {
								$intToPlaceBid = $loans[0]['intOffer'];
							}
							
							/* Added By Mohit 20-01-14 To get Last manully Bid Detail*/
									if($preference==6){										
										$status = $session->getStatusBar($loans[0]['borrowerid'],$loans[0]['loanid'],5);
										$lastBid=$database->lastBidDetail($loans[0]['loanid']);
										if(array_filter($lastBid)){
											    $lastBidAmnt=$lastBid['amnt'];
											    $lastBidIntr=$lastBid['intr'];
												if($desiredInt < $lastBidIntr && $lastBidIntr<$MaxdesiredInt){
													$intToPlaceBid=$lastBidIntr;
												}elseif ($desiredInt > $lastBidIntr){
													$intToPlaceBid=$desiredInt;
												}else{
													$intToPlaceBid=$MaxdesiredInt;
												}												
												$biddedAmnt=($loans[0]['reqdamt']*$status)/100;
												$reqAmnt=$loans[0]['reqdamt']-$biddedAmnt;
												$amountTobid = min($lastBidAmnt, $reqAmnt);
												
										}
									}/***** End here *****/
									$LoanbidId=$session->placebid($loans[0]['loanid'], $loans[0]['borrowerid'], $amountTobid, $intToPlaceBid, 1, true,$lenderId);
							if(is_array($LoanbidId)) {
									$database->addAutoLoanBid($LoanbidId['loanbid_id'], $lenderId, $loans[0]['borrowerid'], $loans[0]['loanid'], $amountTobid,$intToPlaceBid);
									Logger_Array("Entry in Autolend Table IF loan==1",'Loan BidID','LenderId','Loan id', 'BorrowerId','Amnt to lend','Intrest',$LoanbidId['loanbid_id'],$lenderId,$loans[0]['loanid'],$loans[0]['borrowerid'],$amountTobid,$intToPlaceBid);
									$possibleBids-=$amountTobid/AUTO_LEND_AMT;
									$processed[]=$loans[0]['loanid'];
							} else {
									$processed[]=$loans[0]['loanid'];
									unset($loans[0]);
							}
						}
					}
				}
				$loans = $session->getLoansForBid($preference, $loansToAutolend, $processed);
				if(empty($loans)) {
					Logger_Array("AutoBid---LOG",'No Loan Line No','141');
					break;
				}
				if(!$possibleBids) {
					Logger_Array("AutoBid---LOG",'No possible bids','143');
					break;
				}
				if(count($loans) > 1){	
						foreach($loans as $key=>$loan) {						
						if($possibleBids) {						
							$status = $session->getStatusBar($loan['borrowerid'],$loan['loanid'],5);
							if($status >=100) {
								unset($loans[$key]);
								$processed[]=$loan['loanid'];
								$fullyFunded[] = $loan['loanid'];
							} else {									
									if($MaxdesiredInt < $loan['intOffer']) {
										$intToPlaceBid = $MaxdesiredInt;
									}else{
										$intToPlaceBid = $loan['intOffer'];
									}
									/* Added By Mohit 20-01-14 To get Last manully Bid Detail*/
									if($preference==6){
										$lastBid=$database->lastBidDetail($loan['loanid']);
										if(array_filter($lastBid)){
											    $lastBidAmnt=$lastBid['amnt'];
											    $lastBidIntr=$lastBid['intr'];

												if($desiredInt < $lastBidIntr && $lastBidIntr<$MaxdesiredInt){
													$intToPlaceBid=$lastBidIntr;
												}elseif ($desiredInt > $lastBidIntr){
													$intToPlaceBid=$desiredInt;
												}else{
													$intToPlaceBid=$MaxdesiredInt;
												}
												
												$biddedAmnt=($loan['reqdamt']*$status)/100;
												$reqAmnt=$loan['reqdamt']-$biddedAmnt;
												$amntToLend = min($lastBidAmnt, $reqAmnt, AUTO_LEND_AMT);
										}else{
											$amntToLend=AUTO_LEND_AMT;
											}
									}else{
										 $amntToLend=AUTO_LEND_AMT;
									} /***** End here *****/	
									$LoanbidId=$session->placebid($loan['loanid'], $loan['borrowerid'], $amntToLend, $intToPlaceBid, 1, true,$lenderId);
							
									if(is_array($LoanbidId)) {
										$database->addAutoLoanBid($LoanbidId['loanbid_id'], $lenderId, $loan['borrowerid'], $loan['loanid'], $amntToLend,$intToPlaceBid);	
										Logger_Array("Entry in Autolend Table",'Loan BidID','LenderId','Loan id', 'BorrowerId','Amnt to lend','Intrest',$LoanbidId['loanbid_id'],$lenderId,$loan['loanid'],$loan['borrowerid'],$amntToLend,$intToPlaceBid);
										$possibleBids--;
									} else {
										$form->num_errors = 0;									
										unset($loans[$key]);										
										$processed[]=$loan['loanid'];
									}
							}
						}
					}
				}
				if(!$possibleBids) {
					Logger_Array("AutoBid---LOG",'No possible bids2','5921');
					break;
				}
				if(empty($loans)) {
					$loans = $session->getLoansForBid($preference, $loansToAutolend, $processed);
					if(empty($loans)) {
						Logger_Array("AutoBid---LOG",'Log Endign here Lien no','5928');
						break;
					}
				}
			}
		}
		Logger_Array("AutoBid---LOG",'fully funded loan ID line 5935',$fullyFunded);
		return $fullyFunded;
	}		
?>
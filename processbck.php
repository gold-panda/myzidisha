<?php
include("library/session.php");

class Process{
	
	function Process(){
		if(isset($_POST["reg-borrower"])){
			$this->subRegBorrower();
		}
		else if(isset($_POST["reg-lender"])){
			$this->subRegLender();
		}
		else if(isset($_POST['reg-partner'])){
			$this->subRegPartner();
		}
		else if(isset($_POST["userlogin"])){
			$this->subLogin();
		}
		else if(isset($_POST['activateBorrower'])){
			$this->activateBorrower();
		}
		else if(isset($_POST['loanapplication'])){
			$this->loanApplication();
		}
		else if(isset($_POST['exrate'])){
			$this->exchangeRate();
		}
		else if(isset($_POST['confirmApplication'])){
			$this->confirmLoan();
		}
		else if(isset($_POST['lenderbid'])){
			$this->placeBid();
		}
		else if(isset($_POST['minfundamount'])){
			$this->setMinFund();
		}
		else if(isset($_POST['acceptbids'])){
			$this->acceptBids();
		}
		else if(isset($_POST['activatePartner'])){
			$this->activatePartner();
		}
		else if(isset($_POST['deactivatePartner'])){
			$this->deactivatePartner();
		}
		else{
			$this->subLogout();
		}
	}
	function subLogout(){
		global $session;
		$session->logout();
		header("Location:index.php");
	}
	function subLogin(){
		global $session, $form;
		
		$username=$_POST["username"];
		$pass=$_POST["password"];
		$remember=false;
		if(isset($_POST["remember"])){
			$remember=true;
		}
		
		$result=$session->login($username, $pass, $remember);
		if($result==0){
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location:index.php");
		}
		else if($result==1){
			header("Location:index.php");
		}
	}
	function subRegLender(){
		global $session, $form;
		
		$username=$_POST["lusername"];
		$pass1=$_POST["lpass1"];
		$pass2=$_POST["lpass2"];
		$email=$_POST["lemail"];
		$fname=$_POST["lfname"];
		$lname=$_POST["llname"];
		$about=$_POST["labout"];
		$photo=$_POST["lphoto"];
		
		$result=$session->register_l($username,$pass1,$pass2,$email,$fname,$lname,$about,$photo);
		
		
		if($result==0){
			header("Location: index.php?p=1&sel=4");
		}
		else{
			$_SESSION['value_array'] = $_POST;
			 $_SESSION['error_array'] = $form->getErrorArray();
			 header("Location: index.php?p=1&sel=2");
		}
		//else{
//			header("Location: index.php?p=3");//2$sel=4");
//		}
	}
	
	function subRegPartner(){
		global $session, $form;
		
		$username=$_POST['pusername'];
		$pass1=$_POST['ppass1'];
		$pass2=$_POST['ppass2'];
		$pname=$_POST['pname'];
		$address=$_POST['paddress'];
		$city=$_POST['pcity'];
		$country=$_POST['pcountry'];
		$website=$_POST['pwebsite'];
		$desc=$_POST['pdesc'];
		
		$result=$session->register_p($username, $pass1, $pass2, $pname, $address, $city, $country, $website, $desc);
		if($result){
			header("Location:index.php");
		}
		else{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=1&sel=4");
		}
	}
	
	function subRegBorrower(){
		global $session, $form;
		
		$username=$_POST["busername"];
		$bpass1=$_POST["bpass1"];
		$bpass2=$_POST["bpass2"];
		$bfname=$_POST["bfname"];
		$blname=$_POST["blname"];
		$bcity=$_POST["bcity"];
		$bcountry=$_POST["bcountry"];
		$bpostadd=$_POST["bpostadd"];
		$bmobile=$_POST["bmobile"];
		$bincome=$_POST["bincome"];
		$babout=$_POST["babout"];
		$bizdesc=$_POST["bbizdesc"];
		$bphoto=$_FILES['bphoto']['name'];
		
		$result = $session->register_b($username,$bfname,$blname,$bpass1,$bpass2,$bpostadd,$bcity,$bcountry,$bmobile,$bincome,$babout,$bizdesc,$bphoto);
		
		if($result==0){header('Location: index.php?p=1&sel=4');}
		else if($result==1){
			 $_SESSION['value_array'] = $_POST;
			 $_SESSION['error_array'] = $form->getErrorArray();
			 header('Location: index.php?p=1&sel=1');
		}
		else{
			 $_SESSION['value_array'] = $_POST;
			 $_SESSION['error_array'] = $form->getErrorArray();
			 header('Location: index.php?p=1&sel=1');
		}
	}
	
	function activateBorrower(){
		global $session, $form;
		$userid=$_POST['userid'];
		$ptrans=$_POST['partnertrans'];
		$pcomment=$_POST['partnercomment'];
		
		$result=$session->activateBorrower($userid, $ptrans, $pcomment);
		
		if($result==1){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=7&id=$userid");
		}
		else if($result==0){
			header("Location: index.php?p=7&s=1");
		}
	}
	
	function loanApplication(){
		global $session, $form;
		$amount=$_POST['amount'];
		$interest=$_POST['interest'];
		$period=$_POST['period'];
		$gperiod=$_POST['gperiod'];
		$loanuse=$_POST['loanuse'];
		
		$result=$session->loanApplication($amount, $interest, $period, $gperiod, $loanuse);
		
		if($result==0){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=9");
		}
		else if($result==1){
			if(isset($_SESSION['loanapp'])){
				unset($_SESSION['loanapp']);
			}
			$loan=array();
			$loan['amount']=$amount;
			$loan['interest']=$interest;
			$loan['period']=$period;
			$loan['grace']=$gperiod;
			$loan['loanuse']=$loanuse;
			$_SESSION['loanapp']=$loan;
			
			header("Location: index.php?p=9&s=1");
		}
	}
	
	function exchangeRate(){
		global $session, $form;
		
		$rate=$_POST['exrateamt'];
		$result=$session->setExchangeRate($rate);
		if($result==0){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=11&a=4");
		}
		if($result==1){
			header("Location: index.php?p=11&a=4");
		}
	}
	
	function confirmLoan(){
		global $database, $session;
		$loan=$_SESSION['loanapp'];
		$amount=$loan['amount'];
		$interest=$loan['interest'];
		$period=$loan['period'];
		$grace=$loan['grace'];
		$loanuse=$loan['loanuse'];
		$result=$session->confirmLoanApp($amount, $interest, $period, $grace, $loanuse);
		if($result){
			header("Location:index.php?p=9&s=4");
		}
		else{
			header("Location: index.php?p=9&s=1");
		}
	}
	
	function placeBid(){
		global $session, $form;
		$amount=$_POST['pamount'];
		$interest=$_POST['pinterest'];
		$bid=$_POST['bid'];
		$lid=$_POST['lid'];
		
		$result=$session->placeBid($lid, $bid, $amount, $interest); //echo $result;
		if($result==99){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=2&s=1&u=$bid");
		}
		else if($result==6){
			header("Location: index.php?p=2&s=2");
		}
		else if($result==5){
			header("Location: index.php?p=2&s=1&u=$bid");
		}
	}
	
	function setMinFund(){
		global $session, $form;
		$amount=$_POST['mamount'];
		$result=$session->setMinFund($amount);
		
		if($result==3){
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=11&a=6");
		}
		else{
			header("Location: index.php?p=11");
		}
	}
	
	function acceptBids(){
		global $session, $form;
		
		$result=$session->acceptBids();
		
		$r2=$session->processBids($result);
	}
	
	function activatePartner(){
		global $session;
		$partnerid=$_POST['partnerid'];
		$result=$session->activatePartner($partnerid);
		if($result){
			header("Location: index.php?p=11&a=8");
		}
		else{
			header("Location: index.php?p=11&a=7");
		}
	}
	function deactivatePartner(){
		global $session;
		$partnerid=$_POST['partnerid'];
		$result=$session->deactivatePartner($partnerid);
		if($result){
			header("Location: index.php?p=11&a=9");
		}
		else{
			header("Location: index.php?p=11&a=7");
		}
	}

};
$process=new Process;


?>
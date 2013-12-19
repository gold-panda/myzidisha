<?php
include("init.php");
class genericClass
{
	var $connection;         //The MySQL database connection
	var $num_active_guests;  //Number of active guests viewing site
	var $num_members;        //Number of signed-up users
	var $errorcatcher;
	/* Class constructor */
	var $flag=0;
	function MySQLDB()
	{
		traceCalls(__METHOD__, __LINE__);
	}
	/**
	* confirmUserPass - Checks whether or not the given
	* username is in the database, if so it checks if the
	* given password is the same password in the database
	* for that user. If the user doesn't exist or if the
	* passwords don't match up, it returns an error code
	* (1 or 2). On success it returns 0.
	*/

	/* -------------------General Section Start----------------------- */

	function confirmUserPass($username, $password)
	{
		traceCalls(__METHOD__, __LINE__);
		global $db;
		$passw=md5("$password");
		$q="SELECT password, salt FROM ! WHERE username=?";
		$res = $db->getRow($q, array('users',$username));
		if(empty($res))
			return false;
		else {
			if(empty($res['salt'])) {
				if($res['password']==$passw) {
					$salt = $this->makeSalt();
					$newpass= $this->makePassword($password, $salt);
					$q="UPDATE ! SET password=?, salt=? WHERE username=?";
					$db->query($q, array('users', $newpass, $salt, $username));
					return true;
				}
				else {
					return false;
				}
			}
			else {
				$passw=$this->makePassword($password, $res['salt']);
				if($res['password']==$passw)
					return true;
				else
					return false;
			}
		}
		return false;
	}
	function makePassword($password, $salt)
	{
		$newpass= md5(md5($password).md5($salt));
		return $newpass;
	}
	function makeSalt()
	{
		$rand = mt_rand(0, 32);
		$salt = md5($rand . time());
		return $salt;
	}
   /**
	* confirmUserID - Checks whether or not the given
	* username is in the database, if so it checks if the
	* given userid is the same userid in the database
	* for that user. If the user doesn't exist or if the
	* userids don't match up, it returns an error code
	* (1 or 2). On success it returns 0.
	*/
	function confirmUserID($username, $userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		/* Verify that user is in database */
		$q = "SELECT userid FROM ! WHERE username = ?";
		$id = $db->getOne($q, array('users',$username));
		if(empty($id) ){
			return false;
		}
		/* Validate that userid is correct */
		if($userid == $id){
			return true;
		}
		return false;
	}

	/**
	* usernameTaken - Returns true if the username has
	* been taken by another user, false otherwise.
	*/
	function usernameTaken($username)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q = "SELECT COUNT(*) FROM ! WHERE username = ?";
		$result=$db->getOne($q, array('users', $username));
		return ($result > 0);
	}
	function getUserId($username)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$dbarray=array();
		$q = "SELECT userid FROM ! WHERE username = ?";
		return $db->getOne($q, array('users', $username)) ;
	}
	function getUserNameById($id)
	{
		global $db;
		$q='SELECT username from ! WHERE userid=?';
		$result=$db->getOne($q,array('users',$id));
		return $result;
	}
	function getNameById($id)
	{
		global $db;
		$q= 'SELECT userlevel FROM ! where userid = ?';
		$r1 = $db->getOne($q, array('users',$id));
		$result = '';
		$result1 = '';
		if(!empty($r1))
		{
			if($r1==ADMIN_LEVEL)
			{
				$q="SELECT username FROM ".TBL_USERS." WHERE userid='$id'";
				$result=$db->getOne($q);
			}
			else if($r1==PARTNER_LEVEL)
			{
				$q="SELECT name FROM ".TBL_PARTNER." WHERE userid='$id'";
				$result=$db->getOne($q);
			}
			else if($r1==LENDER_LEVEL)
			{
				$q="SELECT  FirstName, LastName  FROM ".TBL_LENDERS." WHERE userid='$id'";
				$result1=$db->getRow($q);
			}
			else if($r1==BORROWER_LEVEL)
			{
				$q="SELECT FirstName,LastName FROM ".TBL_BORROWER." WHERE userid='$id'";
				$result1=$db->getRow($q);
			}
			if(!empty($result))
				return $result;
			if(!empty($result1)){
				$result=$result1['FirstName']." ".$result1['LastName'];
				return $result;
			}
		}
		return "no name";
	}
	function getUserById($id)
	{
		global $db;
		$q= 'SELECT * FROM ! where userid = ?';
		$res = $db->getRow($q, array('users',$id));
		$rtn=array();
		if(!empty($res))
		{
			$rtn['username']=$res['username'];
			$rtn['userlevel']=$res['userlevel'];
			$rtn['lastname']="";
			$rtn['subscribe_newsletter']=0;
			if($res['userlevel']==ADMIN_LEVEL)
			{
				$rtn['name']=$res['username'];
				$rtn['firstname']=$res['username'];
				$rtn['email']=ADMIN_EMAIL_ADDR;
				$rtn['userlevel']==ADMIN_LEVEL;
			}
			else if($res['userlevel']==PARTNER_LEVEL)
			{
				$q="SELECT name, email, City FROM ! WHERE userid=?";
				$result=$db->getRow($q, array('partners', $id));
				$rtn['name']=$result['name'];
				$rtn['firstname']=$result['name'];
				$rtn['email']=$result['email'];
				$rtn['City']=$result['City'];
				$rtn['TelMobile']='';
				$rtn['userlevel']==PARTNER_LEVEL;
			}
			else if($res['userlevel']==LENDER_LEVEL)
			{
				$q="SELECT FirstName, LastName, Email, subscribe_newsletter, City FROM ! WHERE userid=?";
				$result=$db->getRow($q, array('lenders', $id));
				$rtn['firstname']=$result['FirstName'];
				$rtn['lastname']=$result['LastName'];
				$rtn['subscribe_newsletter']=$result['subscribe_newsletter'];
				$rtn['name']=$result['FirstName']." ".$result['LastName'];
				$rtn['email']=$result['Email'];
				$rtn['City']=$result['City'];
				$rtn['TelMobile']='';
				$rtn['userlevel']==LENDER_LEVEL;
			}
			else if($res['userlevel']==BORROWER_LEVEL)
			{
				$q="SELECT FirstName, LastName, Email, City ,TelMobile FROM ! WHERE userid=?";
				$result=$db->getRow($q, array('borrowers', $id));
				$rtn['firstname']=$result['FirstName'];
				$rtn['lastname']=$result['LastName'];
				$rtn['name']=$result['FirstName']." ".$result['LastName'];
				$rtn['email']=$result['Email'];
				$rtn['City']=$result['City'];
				$rtn['TelMobile']=$result['TelMobile'];
				$rtn['userlevel']==BORROWER_LEVEL;
			}
		}
		return $rtn;
	}
	function getActivationKey($id)
	{
		global $db;
		$q='SELECT password, salt from ! WHERE userid=?';
		$res=$db->getRow($q,array('users',$id));
		return md5(md5($res['password']).$res['salt']);
	}
	function emailVerified($id)
	{
		global $db;
		$q='UPDATE ! set emailVerified = ? WHERE userid=?';
		$res=$db->query($q,array('users',1, $id));
		$user_level = $this->getUserLevelbyid($id);
		if($user_level==BORROWER_LEVEL) {
			$q1='UPDATE ! set completed_on = ? WHERE userid=?';
			$res1=$db->query($q1,array('borrowers',time(), $id));
		}
		if($res===DB_OK)
			return true;
		else
			return false;
	}
	function query($query)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		return (($db->query($query))===DB_OK);
	}
	function setLoginTime($userid, $timer)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="UPDATE ".TBL_USERS." SET last_login=? WHERE userid=?";
		$db->query($q, array($timer, $userid));
	}
	function countryList($activated=false)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$result1=array();
		if($activated)
		{
			$qq="SELECT * FROM ! WHERE name <> ? AND code IN (SELECT country_code FROM ! WHERE active=?) order by name";
			$result1=$db->getAll($qq,array('countries','All Countries', 'currency', 1));
		}
		else
		{
			$qq="SELECT * FROM ! WHERE name <> ? order by name";
			$result1=$db->getAll($qq,array('countries','All Countries'));
		}
		return $result1;
	}
	function countryListWithAll($activated=false)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		if($activated) {
			$qq="SELECT * FROM ! WHERE code IN (SELECT country_code FROM ! WHERE active=?) OR name = ? order by name";
			$result1=$db->getAll($qq,array('countries', 'currency', 1,'All Countries'));
		} else {
			$qq="SELECT * FROM ! order by name";
			$result1=$db->getAll($qq,array('countries'));
		}
		return $result1;
	}
	function stateList()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$qq="SELECT * FROM ! where enabled =? order by name";
		$result1=$db->getAll($qq,array('state','Y'));
		return $result1;
	}
	function getUserLevel($username)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT userlevel FROM ".TBL_USERS." WHERE username='$username'";
		$r= $db->getOne($q);
		return $r;
	}
	function getUserLevelbyid($uid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT userlevel FROM ".TBL_USERS." WHERE userid='$uid'";
		$r= $db->getOne($q);
		return $r;
	}
	function getUserInfo($username)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$dbarray=array();
		$q = "SELECT userid, username, userlevel, lang, salt, emailVerified,sublevel FROM ".TBL_USERS." WHERE username = '$username'";
		$result = $db->getRow($q) or die();
		if(!$result )
		{
			return NULL;
		}
		$dbarray=$result;
		$userlevel=$dbarray['userlevel'];
		$userid=$dbarray['userid'];
		$username=$dbarray['username'];
		$dbarray['country']='';
		$dbarray['email']='';

		if($userlevel==PARTNER_LEVEL)
		{
			$p="SELECT name, email FROM ".TBL_PARTNER." WHERE userid=$userid";
			$result=$db->getRow($p);
			$dbarray['name']=$result['name'];
			$dbarray['email']=$result['email'];
		}
		if($userlevel==LENDER_LEVEL)
		{
			$l="SELECT firstname, lastname, Email FROM ".TBL_LENDERS." WHERE userid=$userid";
			$result=$db->getRow($l);
			$larray=$result;
			$name=$larray['firstname']." ".$larray['lastname'];
			$dbarray['name']=$name;
			$dbarray['email']=$result['Email'];
		}
		if($userlevel==BORROWER_LEVEL)
		{
			$b="SELECT firstname, lastname, Country, Email FROM ".TBL_BORROWER." WHERE userid=$userid";
			$result=$db->getRow($b);
			$barray=$result;
			$name=$barray['firstname']." ".$barray['lastname'];
			$dbarray['name']=$name;
			$dbarray['country']=$barray['Country'];
			$dbarray['email']=$result['Email'];
		}
		if($userlevel==ADMIN_LEVEL)
		{
			$dbarray['name']=$username;
		}
		if($userlevel==GUEST_LEVEL)
		{
			$dbarray['name']="GUEST";
		}
		return $dbarray;
	}
	function forgotPassword($submail, $subuser)
	{
		global $db;
		if($subuser===0)
		{
			$p="SELECT username FROM ! WHERE userid IN (SELECT userid from ! where Email=? UNION SELECT userid from ! where Email=? UNION SELECT userid from ! where Email=?)";
			$usernames = $db->getAll($p, array(TBL_USERS,TBL_LENDERS,$submail,TBL_PARTNER,$submail,TBL_BORROWER,$submail));
			if(count($usernames)==0)
				return 0; /* user does not exist for this email id*/
			else if(count($usernames) >1)
				return 2;  /* more than one user exist for this email id*/
			else
				$subuser = $usernames[0]['username'];
		}
		$q="SELECT userid FROM ! WHERE username=?";
		$userid = $db->getOne($q, array(TBL_USERS, $subuser));
		$uname = $this->getNameById($userid);
		$r1['userid']=$userid;
		$r1['username']=$subuser;
		$r1['uname'] = $uname;
		$rand = mt_rand(0, 32);
		$newpass = md5($rand . time());
		$newpass = substr($newpass, 0,7);
		$salt = $this->makeSalt();
		$mdpass=$this->makePassword($newpass, $salt);
		$sql2="UPDATE ! SET password =?, salt =? WHERE username = ? LIMIT 1";
		$r3 = $db->query($sql2, array('users', $mdpass, $salt, $subuser));
		if($r3===DB_OK)
		{
			$r1['email']=$submail;
			$r1['pass']=$newpass;
			return $r1;
		}
		else
			return 3;//db error
	}
	function getUserNamesByEmail($submail)
	{
		global $db;
		$usernames=array();
		if(!empty($submail)) {
			$p="SELECT username FROM ! WHERE userid IN (SELECT userid from ! where Email=? UNION SELECT userid from ! where Email=? UNION SELECT userid from ! where Email=?)";
			$usernames = $db->getAll($p, array(TBL_USERS,TBL_LENDERS,$submail,TBL_PARTNER,$submail,TBL_BORROWER,$submail));
		}
		return $usernames;
	}
	function totalAmountLent()
	{
		global $db;
		$q= 'select Sum(givenamount) from ! where active = ? AND loanid IN (select l.loanid from ! as l where l.adminDelete = ? )';
		$sum1 = $db->getOne($q, array('loanbids',1, 'loanapplic', 0));
		return $sum1;
	}
	function businessFinanced($uid = 0)
	{
		global $db;
		if($uid == 0)
		{
			$q= 'select count(borrowerid) from loanapplic where active in (?, ? , ?, ?) AND adminDelete = ?';
			return $db->getOne($q, array(LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED,LOAN_FUNDED, 0));
		}
		else
		{
			$q= 'select count(distinct (loanid)) from loanbids where active = 1 and lenderid = ? ';
			return $db->getOne($q, array($uid));
		}
	}
	function avgLendRate()
	{
		global $db;
		$q= 'select avg(finalrate) from loanapplic where active in (?, ? , ?) AND adminDelete = ?';
		return $db->getOne($q, array(LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED , 0));
	}
	function getAllCurrency1()
	{
		global $db;
		$q="SELECT *  FROM currency";
		$result=$db->getAll($q );
		return $result;
	}
	function getAllCurrency($active=1)
	{
		global $db;
		if(empty($active))
		{
			$q="SELECT *  FROM currency order by active desc, country";
			$result=$db->getAll($q );
		}
		else
		{
			$q="SELECT *  FROM currency WHERE active = ? ";
			$result=$db->getAll($q ,array($active));
		}
		return $result;
	}
	function mysetCurrency($id)
	{
		global $db;
		$q="SELECT currencyname FROM currency WHERE id = ? ";
		$result=$db->getOne($q ,array($id));
		if(empty($result)){
			return "Not set yet";
		}
		return $result;
	}
	function mysetCountry($id)
	{
		global $db;
		$q="SELECT name FROM countries WHERE code = ? ";
		$result=$db->getOne($q ,array($id));
		if(empty($result)){
			return "Not set yet";
		}
		return $result;
	}
	function mysetState($id)
	{
		global $db;
		$q="SELECT name FROM state WHERE code = ? ";
		$result=$db->getOne($q ,array($id));
		if(empty($result)){
			return "Not set yet";
		}
		return $result;
	}
	function getUserCityCountry($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$dbarray=array();
		$q = "SELECT userid, username, userlevel FROM ".TBL_USERS." WHERE userid = '$userid'";
		$result = $db->getRow($q) or die();
		if(!$result ){
			return NULL;
		}
		$dbarray=$result;
		$userlevel=$dbarray['userlevel'];
		$userid=$dbarray['userid'];
		$username=$dbarray['username'];
		if($userlevel==PARTNER_LEVEL)
		{
			$p="SELECT City,Country FROM ".TBL_PARTNER." WHERE userid=$userid";
			$result=$db->getRow($p);
			return $result;
		}
		if($userlevel==LENDER_LEVEL)
		{
			$l="SELECT City,Country FROM ".TBL_LENDERS." WHERE userid=$userid";
			$result=$db->getRow($l);
			return $result;
		}
		if($userlevel==BORROWER_LEVEL)
		{
			$b="SELECT City,Country FROM ".TBL_BORROWER." WHERE userid=$userid";
			$result=$db->getRow($b);
			return $result;
		}
		if($userlevel==ADMIN_LEVEL)
		{
			$a="SELECT City,Country FROM ".TBL_PARTNER." WHERE userid=$userid";
			$result=$db->getRow($a);
			return $result;
		}
	}
	function getCountryCodeById($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);

		$dbarray=array();
		$q = "SELECT userid, username, userlevel FROM ".TBL_USERS." WHERE userid = '$userid'";
		$result = $db->getRow($q) or die();
		if(!$result ){
			return NULL;
		}
		$dbarray=$result;
		$userlevel=$dbarray['userlevel'];
		$userid=$dbarray['userid'];
		if($userlevel==PARTNER_LEVEL)
		{
			$p="SELECT Country FROM ".TBL_PARTNER." WHERE userid=$userid";
			$result=$db->getOne($p);
			return $result;
		}
		if($userlevel==LENDER_LEVEL)
		{
			$l="SELECT Country FROM ".TBL_LENDERS." WHERE userid=$userid";
			$result=$db->getOne($l);
			return $result;
		}
		if($userlevel==BORROWER_LEVEL)
		{
			$b="SELECT Country FROM ".TBL_BORROWER." WHERE userid=$userid";
			$result=$db->getOne($b);
			return $result;
		}
		if($userlevel==ADMIN_LEVEL)
		{
			$a="SELECT Country FROM ".TBL_PARTNER." WHERE userid=$userid";
			$result=$db->getOne($a);
			return $result;
		}
	}
	function getPreferredLang($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT lang FROM ! WHERE userid=?";
		$res= $db->getOne($q , array('users', $userid));
		if(is_string($res))
			return $res;
		else
			return 0;
	}
	function getActiveLanguages()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT * FROM ! WHERE active=?";
		$res= $db->getAll($q , array('language',1));
		return $res;
	}
	function getAllLanguages()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT * FROM ! WHERE 1 order by active desc,id asc";
		$res= $db->getAll($q , array('language'));
		return $res;
	}
	function setActiveLanguage($id, $act)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="UPDATE  ! set active=? WHERE id=?";
		$res= $db->query($q , array('language',$act,$id));
		if($res===DB_OK)
			return 1;
		else
			return 0;
	}
	function isActiveLanguage($language)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT active FROM ! WHERE langcode=?";
		$res= $db->getOne($q , array('language',$language));
		if($res==1)
				return 1;
		else
			return 0;
	}
	function getLanguageByCode($langcode)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT lang FROM ! WHERE langcode=?";
		$res= $db->getOne($q , array('language',$langcode));
		return $res;
	}
	function getCurrencyIdByCountryCode($countryCode)
	{
		global $db,$session;
		$q="SELECT * FROM ! WHERE country_code =? ";
		$res = $db->getRow($q, array('currency', $countryCode));
		if(!empty($res))
			return $res['id'];
		else
			return -1;
	}
	function getCurrencyNameByCurrencyId($currency)
	{
		global $db;
		$q="SELECT Currency FROM ! WHERE id =? ";
		$res = $db->getOne($q, array('currency', $currency));
		return $res;
	}
	function getCurrentRateByCurrency($currency)
	{
		global $db;
		$q="SELECT rate FROM excrate WHERE start=(SELECT max(start) FROM excrate where currency = ?)";
		$result=$db->getOne($q , array($currency));
		if(empty($result)){
			return 0;
		}
		return $result;
	}
	function getRepayRate($country='')
	{
		global $db;
		$repayRate=100;
		$defaultRate=0;
		$sixMonthsAgoDate=strtotime(date("Y-m-d H:i:s",time())." -6 month");
		$loanids=array();
		if($country=='')
		{
			$t="SELECT group_concat(loanid) as loanid from (SELECT loanid, max(duedate) as date from ! group by loanid) as r where r.date < ?";
			$loanids=$db->getRow($t,array('repaymentschedule', $sixMonthsAgoDate));
		}
		else
		{
			$t="SELECT group_concat(loanid) as loanid from (SELECT loanid, max(duedate) as date from ! as rp join ! as br on rp.userid= br.userid where br.Country=?  group by loanid) as r where r.date < ?";
			$loanids=$db->getRow($t,array('repaymentschedule', 'borrowers', $country, $sixMonthsAgoDate));
		}
		if($loanids['loanid'])
		{
			$t="SELECT loanid, borrowerid from ! where active = ? AND loanid IN (".$loanids['loanid'].") ";
			$defaultedLoanids=$db->getAll($t,array('loanapplic', LOAN_DEFAULTED));
			if(!empty($defaultedLoanids))
			{
				$AmountGot=0;
				$s="SELECT AmountGot, loanid, borrowerid from ! where loanid IN (".$loanids['loanid'].") ";
				$loanData=$db->getAll($s,array('loanapplic'));
				foreach($loanData as $row)
				{
					$exrate=$this->getCurrentRate($row['borrowerid']);
					$AmountGot +=($row['AmountGot']/$exrate);
				}
				$defaultAmountUsd=0;
				foreach($defaultedLoanids as $row)
				{
					$res=$this->getTotalPayment($row['borrowerid'], $row['loanid']);
					if($res['amttotal'] > $res['paidtotal'])
					{
						$amount=$res['amttotal'] - $res['paidtotal'];
						$ratio=$this->getPrincipalRatio($row['loanid']);
						$exrate=$this->getCurrentRate($row['borrowerid']);
						$defaultAmount =($amount * $ratio);
						$defaultAmountUsd += ($defaultAmount/$exrate);
						$forgiveAmount=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
						if($forgiveAmount)
						{
							$AmountGot -= ($forgiveAmount * $ratio);
						}
					}
				}
				$defaultRate=($defaultAmountUsd / $AmountGot) * 100;
			}
		}
		return ($repayRate - $defaultRate);
	}
	function getVerifiedPartnerCountries()
	{
		global $db;
		$q = "SELECT DISTINCT(b.Country), c.Currency, cc.name,cc.code  from ! b join ! c on b.currency=c.id join ! as cc on b.Country=cc.code join ! as la on la.borrowerid =b.userid Where b.Active=?";
		$result = $db->getAll($q, array('borrowers','currency','countries','loanapplic',1));
		$contries=array();
		foreach($result as $row)
		{
			$contries[$row['code']]=$row['name'];
		}
		return $contries;
	}
	function getCumulativeLoanStatistics($country)
	{
		global $db;
		$cumStat=array();
		if($country=='')
		{
			$q= 'select count(borrowerid) from ! where active in (?, ? , ?, ?) AND adminDelete = ?';
			$count1=$db->getOne($q, array('loanapplic',LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED,LOAN_FUNDED, 0));
			$cumStat['bfinanced']=$count1;

			$q= 'select Sum(givenamount) from ! where active = ? AND loanid IN (select l.loanid from ! as l  where l.adminDelete = ? )';
			$sum1= $db->getOne($q, array('loanbids',1, 'loanapplic', 0));
			$cumStat['lraised']=$sum1;
			
			$q= 'select avg(finalrate) from loanapplic where active in (?, ? , ?) AND adminDelete = ?';
			$avg= $db->getOne($q, array(LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED , 0));
			$cumStat['avgintr']=$avg;

			$q= 'select count(userid) from ! where userid NOT IN(SELECT userid from ! where emailVerified=?)';
			$count2=$db->getOne($q,array('lenders', 'users', 0));
			$cumStat['lenders']=$count2;

			$q="select count(borrowers.userid) from !, ! where borrowers.partnerid=partners.userid";
			$cumStat['borrower']=$result=$db->getOne($q, array('borrowers', 'partners'));

			$q= 'select DISTINCT Country from ! where Active = ? UNION select DISTINCT Country from ! where Active = ?';
			$count3=count($db->getAll($q, array('borrowers', 1, 'lenders',1)));
			$cumStat['countries']=$count3;

			$repayRate= $this->getRepayRate();
			$cumStat['repayRate']=$repayRate;

			$cumStat['defaultRate']=100-$repayRate;
		}
		else
		{
			$q= 'select count(l.borrowerid) from ! as l join ! as b on l.borrowerid= b.userid where l.active in (?, ? , ?, ?) AND l.adminDelete = ? AND b.Country=?';
			$count1=$db->getOne($q, array('loanapplic','borrowers',LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED,LOAN_FUNDED, 0, $country));
			$cumStat['bfinanced']=$count1;

			$q= 'select Sum(givenamount) from ! where active = ? AND loanid IN (select l.loanid from ! as l join ! as b on l.borrowerid= b.userid where l.adminDelete = ? AND b.Country=?)';
			$sum1= $db->getOne($q, array('loanbids',1, 'loanapplic','borrowers', 0, $country));
			$cumStat['lraised']=$sum1;

			$q= 'select avg(l.finalrate) from ! as l join ! as b on l.borrowerid= b.userid where l.active in (?, ? , ?) AND l.adminDelete = ? AND b.Country=?';
			$avg=$db->getOne($q, array('loanapplic','borrowers',LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED, 0, $country));
			$cumStat['avgintr']=$avg;

			$repayRate= $this->getRepayRate($country);
			$cumStat['repayRate']=$repayRate;

			$cumStat['defaultRate']=100-$repayRate;
		}
		return $cumStat;
	}
	function getActiveLoanStatistics($country)
	{
		/* This code is taken from pfreportnew function please take care of this thing */
		global $db;
		$date1='01/01/2009';
		$dateArr1  = explode("/",$date1);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));

		/* We have excluded five loan ids (39,42,44,59,60) from portfolio report because these loan amount was theft by someone. Please do not change the code without permission */
		if($country=='') {
			$q="SELECT  t.id, l.loanid,  l.borrowerid, l.AmountGot, b.FirstName, b.LastName, b.Country, b.Currency from transactions t join loanapplic l on t.loanid = l.loanid join borrowers b on  b.userid = t.userid where t.txn_type  =6 AND l.active  =2 AND l.adminDelete =0 AND b.active !=0 AND t.trDate >=$date3 AND t.trDate <=$date4 AND l.loanid NOT IN (39,42,44,59,60) order by b.Country ";
		} else {
			$q="SELECT  t.id, l.loanid,  l.borrowerid, l.AmountGot, b.FirstName, b.LastName, b.Country, b.Currency from transactions t join loanapplic l on t.loanid = l.loanid join borrowers b on  b.userid = t.userid where t.txn_type  =6 AND l.active  =2 AND l.adminDelete =0 AND b.active !=0 AND t.trDate >=$date3 AND t.trDate <=$date4 AND l.loanid NOT IN (39,42,44,59,60) AND b.Country='$country'";
		}

		$result=$db->getAll($q);
		$pfReport=array();
		$threshold=10; /* here is the threshold USD 10 fixed */
		$pfReport[1]['prinOut']=0;
		$pfReport[2]['prinOut']=0;
		$pfReport[3]['prinOut']=0;
		$pfReport[4]['prinOut']=0;
		$pfReport[5]['prinOut']=0;
		$pfReport[1]['prinOutResch']=0;
		$pfReport[2]['prinOutResch']=0;
		$pfReport[3]['prinOutResch']=0;
		$pfReport[4]['prinOutResch']=0;
		$pfReport[5]['prinOutResch']=0;
		$pfReport['totPrinOut']=0;
		$pfReport['totPrinOutResch']=0;
		$pfReport['allTotPrinOut']=0;
		$pfReport['allTotPrinOutResch']=0;
		$rate=array();
		foreach($result as $row)
		{
			$o="SELECT max(id) from ! where loanid = ? AND userid=?";
			$maxid=$db->getOne($o,array('repaymentschedule',$row['loanid'],$row['borrowerid']));

			$p="SELECT SUM(amount) as totamt from ! where loanid = ? AND userid=? AND duedate < ?";
			$totamt=$db->getOne($p,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$date4));

			$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ?";
			$totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));

			$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? order by id desc";
			$rid=$db->getOne($r,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));
			if(!empty($rid)) {
				$s="SELECT SUM(amount) from ! where loanid = ? AND userid=? AND id <= ?";
				$totAmtToRid=$db->getOne($s,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$rid));
			}

			$t="SELECT sum(amount) from ! where loan_id = ? AND borrower_id=? AND date < ?";
			$forgiveAmount=$db->getOne($t,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));

			$ratio = $this->getPrincipalRatio($row['loanid']);
			$rate=$this->getCurrentRateByCurrency($row['Currency']);
			$thresholdNative=$threshold * $rate;
			if($forgiveAmount) {
				$forgivePrinAmount=$forgiveAmount * $ratio;
				$row['prinAmount']=$row['AmountGot']-$forgivePrinAmount;
			} else {
				$row['prinAmount']=$row['AmountGot'];
			}
			$row['principlePaid']= ($totpaidamt * $ratio);
			$row['principleOutstanding']= $row['prinAmount']-$row['principlePaid'];
			$row['dueAmount']= $totamt-$totpaidamt;
			$row['dueAmountUSD']= ($row['dueAmount'] / $rate);

			$country = $row['Country'];
			$resch=$this->isRescheduledBeforeDate($row['loanid'], $date4);
			if($resch) {
				$pfReport['allTotPrinOutResch'] +=$row['principleOutstanding'] / $rate;
			} else {
				$pfReport['allTotPrinOut'] +=$row['principleOutstanding'] / $rate;
			}
			if($row['dueAmountUSD'] <$threshold) {
				// this amount will not considor in repayrepot using threshold functionality
				continue;
			}
			$duedate='';
			if($rid==$maxid) {
				$r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=?";
				$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['borrowerid']));
			} elseif(empty($rid)) {
				$r="SELECT duedate from ! where loanid = ? AND userid=? AND amount > ? AND paidamt is NULL order by id";
				$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['borrowerid'],0));
			} else {
				$flag=0;
				if(($totpaidamt + $thresholdNative) < $totAmtToRid) {
					$r="SELECT duedate from ! where loanid = ? AND userid=? AND id=?";
					$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$rid));
				} else {
					$r="SELECT * from ! where loanid = ? AND userid=?";
					$repayAll=$db->getAll($r,array('repaymentschedule',$row['loanid'],$row['borrowerid']));
					$reducedAmount=$totAmtToRid - $totpaidamt;
					foreach($repayAll as $repay) {
						if($repay['id'] >$rid) {
							if(($repay['amount']) >($thresholdNative -$reducedAmount)) {
								$duedate=$repay['duedate'];
								break;
							} else {
								$duedate=$repay['duedate'];
								$reducedAmount += $repay['amount'];
							}
						}
					}
				}
			}
			$dateDiff = $date4 - $duedate;
			$fullDays = floor($dateDiff/(60*60*24));
			$j=0;
			if($fullDays <0) {
				continue;
			}
			if($fullDays <31) {
				$j=1;
			} else if($fullDays <91) {
				$j=2;
			} else if($fullDays <181) {
				$j=3;
			} else {
				$j=4;
			}
			if($resch) {
				$pfReport[$j]['prinOutResch'] += $row['principleOutstanding'] / $rate;
				$pfReport['totPrinOutResch'] +=$row['principleOutstanding'] / $rate;
			} else {
				$pfReport[$j]['prinOut'] += $row['principleOutstanding'] / $rate;
				$pfReport['totPrinOut'] +=$row['principleOutstanding'] / $rate;
			}
		}
		return $pfReport;
	}
	function getLastLogin($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT last_login FROM ! where userid=?";
		$result=$db->getOne($q, array('users', $userid));
			if(empty($result)){
			return 0;
		}
		return  $result ;

	}
	function getUserSublevelById($id)
	{
		global $db;
		$q='SELECT sublevel from ! WHERE userid=?';
		$result=$db->getOne($q,array('users',$id));
		return $result;
	}
	function confirmUserEmailPass($useremail, $password)
	{
		traceCalls(__METHOD__, __LINE__);
		global $db;
		$passw=md5("$password");
		$q="select * from ! where userid IN (select userid from lenders where email='$useremail') OR userid IN (select userid from borrowers where email='$useremail') OR userid IN (select userid from partners where email='$useremail')";
		$results = $db->getAll($q, array('users'));

		if(empty($results))
			return false;
		else {
			foreach($results  as $res){
			if(empty($res['salt'])) {
				if($res['password']==$passw) {
					$username=$res['username'];
					$salt = $this->makeSalt();
					$newpass= $this->makePassword($password, $salt);
					$q="UPDATE ! SET password=?, salt=? WHERE username=?";
					$db->query($q, array('users', $newpass, $salt, $username));
					return $res['username'];
				}
			}
	else {
				$passw=$this->makePassword($password, $res['salt']);
				if($res['password']==$passw)
					return $res['username'];
			}
		}
	}
		return false;
	}
	function IsEmailExist($useremail, $userid = 0)
	{
		global $db;
		$where='';
		if($userid>0) {
			$where="AND userid<>$userid";
		}
		$q="select count(*) exist  from ! where (userid IN (select userid from lenders where email='$useremail') OR userid IN (select userid from borrowers where email='$useremail') OR userid IN (select userid from partners where email='$useremail')) $where";
		$result=$db->getOne($q, array('users'));
		return $result;

	}
	function loanAlreadyInForgiveness($loanid)
	{
		global $db;
		$q="select count(*) exist  from ! where loanid=?";
		$result=$db->getOne($q, array('loans_to_forgive',$loanid));
		return $result;

	}
	function getAllregFee()
	{
		global $db;
		$q="SELECT cr.country, rf.currency, rf.currency_name, rf.Amount FROM ! as cr join ! as rf on cr.id = rf.currency_id where cr.Active =?";
		$result = $db->getAll($q,array('currency', 'registration_fee', 1));
		return $result;

	}
	function deletecredit($parentid, $senderid)
	{
		global $db;
		$p="DELETE from ! where ref_id = ? AND borrower_id = ?";
		$res1=$db->query($p, array('credits_earned',$parentid, $senderid));
		return $res1;

	}
	function IsMobileExist($mobile,$countrycode)
	{
		global $db;
		$q="select count(*)   from !  where TelMobile=$mobile AND iscomplete_later = ? AND Country = ?";
		$result=$db->getOne($q, array('borrowers',0, $countrycode));
		return $result;
	}
	function IsUserinvited($userid) {
		
		global $db;
		if(isset($_COOKIE["invtduserjoins"])) {
			$cookie_val = $_COOKIE["invtduserjoins"];
			$q="select id,userid from ! where cookie_value = ?";
			$result=$db->getRow($q, array('invites',$cookie_val));
			$q1="UPDATE invites SET invitee_id = ? Where cookie_value = ? LIMIT 1";
			$res=$db->query($q1, array($userid, $cookie_val));
			setcookie ("invtduserjoins", "", time() - 3600);
		}
	}
function getco_Organizers_Country() {
		global $db;
		$q = 'select distinct(country),c.name from ! as co,! as c where co.country=c.code and co.status=1 ORDER BY c.name';
		$countries = $db->getAll($q, array('community_organizers','countries')); 
		foreach($countries as $key => $country) {
			$q1 = 'select user_id from ! where country = ? and status=?';
			$countries[$key]['co'] = $db->getAll($q1, array('community_organizers',$country['country'], 1)); 
		}
		return $countries;
	}
	function getUserStatus($userid){
		global $db,$session;
		$user_level = $this->getUserLevelbyid($userid);
		if($user_level==BORROWER_LEVEL){
			$q= "select Active from ! where userid=?";
			$status=$db->getOne($q, array('borrowers', $userid));
		}
		if($user_level==LENDER_LEVEL){
			$q= "select Active from ! where userid=?";
			$status=$db->getOne($q, array('lenders', $userid));
		}
		if($user_level==PARTNER_LEVEL){ 
			$q= "select Active from ! where userid=?";
			$status=$db->getOne($q, array('partners', $userid));
		}
		if($session->userlevel == ADMIN_LEVEL){
			$status=1;
		}
		return $status;
	}

	function IsNationIdExist($bnationid, $country, $userid){
		global $db, $session;
		$where='';
		if(!empty($country)){
			$where= " AND Country =$country";
		}
		if($userid>0){ 
			$where .= " AND userid<>$userid ";
		}
		$q="select count(*) from ! where nationId=$bnationid $where";
		$result=$db->getOne($q, array('borrowers'));
		return $result;
	}
	/* -------------------General Section End----------------------- */

	/* -------------------Admin Section Start----------------------- */

	function getMinimumFund()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT value FROM settings where name='minAmount'";
		$result=$db->getOne($q);
		if(empty($result)){
			return 0;
		}
		return  $result ;
	}
	function setMinFund($amount)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="UPDATE settings SET value=? where name=?";
		return (($db->query($q, array($amount, 'minAmount')))===DB_OK);
	}
	/*if second argument ie $addCreditearned is set to true we will return max amount with addition of credit earned by borrower on his previous loan. if third argument ie $nextloan we assume borrower current loan is on time and return expected max borrow amount on his next loan*/
	function getAdminSetting($name, $addCreditearned=true, $nextloan=false, $amtthrshldchek = 0)
	{
		global $db,$session;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT value FROM ! where name=?";
		$result=$db->getOne($q, array('settings', $name));
		if(empty($result)){
			return 0;
		}
		else
		{
			if($name=='maxBorrowerAmt')
			{
				/* Please note only max borrower amount is returning in native currency */

				if($session->userlevel == ADMIN_LEVEL)
				{
					return  $result;
				}
				else
				{
					$loancountdecreases= false;
					$borrowerid=  $session->userid;
					$loanCountArray=$this->getLoanCount($borrowerid,true); 
					$loanCount= $loanCountArray[0]; 
					if($loanCount > 0) {
						$isfogven=0;
						$last_loanid = $this->getLastRepaidloanId($borrowerid);
						$disbdate = $this->getLoanDisburseDate($last_loanid);
						$currenttime = time();
						$months = $this->IntervalMonths($disbdate, $currenttime);
						if($amtthrshldchek==1){
							$timethrshld = $this->getAdminSetting('TimeThrshld_above');
							if($months < $timethrshld) {
								if(!$loancountdecreases){
									$loancountdecreases= true;
								}
							}
						}else{
							$timethrshld = $this->getAdminSetting('TimeThrshld');
							if($months < $timethrshld) {
								if(!$loancountdecreases){
									$loancountdecreases= true;
								}
							}
						}
						$ontime = $this->isRepaidOntime($borrowerid, $last_loanid);
						if(!$ontime){
							$loancountdecreases = true;
						}
						$adminRepayRate= $this->getAdminSetting('MinRepayRate'); 
						$borrowRepayRate= $session->RepaymentRate($borrowerid); 						
						if($adminRepayRate > $borrowRepayRate){
							if(!$loancountdecreases){
								$loancountdecreases = true;
							}
						}
					}
					if($nextloan) {
						$loan_status = $this->getLoanStatus($borrowerid);
						if($loan_status == LOAN_ACTIVE || $loan_status == LOAN_FUNDED || $loan_status == LOAN_OPEN) {
							$loanCount++;
						}
					}
					$excludeLoanIds = $loanCountArray[1];
					$amount=$this->getPreviousLoanAmount($borrowerid,$loanCountArray, $excludeLoanIds); 
					$rate=$this->getCurrentRate($borrowerid);
					$q="SELECT applydate from ! where borrowerid = ? AND active = ? AND adminDelete =? order by loanid desc";
					$applydate=$db->getOne($q,array('loanapplic',$borrowerid, LOAN_OPEN,0));
					if(!empty($applydate))
					{
						$rate=$this->getExRateById($applydate, $borrowerid);
					}
					
					$resultNative=convertToNative($result, $rate);
					if($loanCount==0)
					{
						$val=$this->getAdminSetting('firstLoanValue');
						$resultNative=convertToNative($val, $rate);
						/* it means it is first loan */
						/*$per=$this->getAdminSetting('firstLoanPercentage');
						$ramount=($amount * $per)/100;
						$ramount=number_format($ramount, 2, ".", "");
						 if($resultNative >= $ramount)
							return $ramount;
						else*/
							return $resultNative;
					}
					else if($loanCount==1 || $loanCount==2)
					{	
						/* it means it is second loan or third loan*/
						$val=$this->getAdminSetting('secondLoanValue');
						$resultNative=convertToNative($val, $rate);
						if($loancountdecreases) { 
							$resultNative = $this->getLastRepaidAmount($borrowerid, true);
						}
						$per=$this->getAdminSetting('secondLoanPercentage');
						$ramount=($amount * $per)/100;
						$ramount=number_format($ramount, 2, ".", "");
						
						if($addCreditearned) {
							$creditearned = $this->getCreditEarned($borrowerid);
							$resultNative = $resultNative + $creditearned;
							$ramount = $ramount + $creditearned;
						}
						if($resultNative >= $ramount) 
							return $ramount;
						else
							return $resultNative;
					}
					else
					{	
						/* it means it is not next loan */
						$val=$this->getAdminSetting('nextLoanValue');
						$resultNative=convertToNative($val, $rate);
						if($loancountdecreases) {
							$resultNative = $this->getLastRepaidAmount($borrowerid, true);
						}
						$per=$this->getAdminSetting('nextLoanPercentage');
						$ramount=($amount * $per)/100;
						
						$ramount=number_format($ramount, 2, ".", "");
						if($addCreditearned) {
							$creditearned = $this->getCreditEarned($borrowerid);
							$resultNative = $resultNative + $creditearned;
							$ramount = $ramount + $creditearned;
						}
						if($resultNative >= $ramount) {
							return $ramount;
						}
						else { 
							return $resultNative;
						}
					}
				}
			}
			return $result;
		}
	}
	function setAdminSetting($name, $value)
	{
		global $db,$session;
		traceCalls(__METHOD__, __LINE__);
		if($session->userlevel == ADMIN_LEVEL) {
			$q="UPDATE ! SET value=? where name=?";
			return (($db->query($q, array('settings', $value, $name)))===DB_OK);
		}
		return 0;
	}
	function getEditAmount($currencyid)
	{
		global $db;
		$q="SELECT Amount FROM ! WHERE currency_id=? ";
		$result=$db->getOne($q,array('registration_fee',$currencyid));
		if(empty($result)){
			return 0;
		}
		else{
			return $result;
		}
	}
	function setEditAmount($amount,$currencyid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="UPDATE ! SET Amount=? WHERE currency_id=?";
		$result=$db->query($q,array('registration_fee',$amount,$currencyid));
		if($result)
		{
			//$qq="SELECT Amount FROM registration_fee WHERE currency_id=$currencyid ";
			return 1;
		}
	}
	function getInactiveBorrowers($sort, $ord, $userlevel, $userid)
	{
		global $db;
		$result=array();
		if($sort=='FirstName')
			$sort='name ';
		if($userlevel==ADMIN_LEVEL)
		{
			$q="SELECT b.*, u.regdate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as name, onb.name as postedby, bext.rec_form_offcr_name, bext.rec_form_offcr_num FROM ! as b join ! as u on b.userid= u.userid left join ! as onb on b.borrower_behalf_id= onb.id join ! as bext on b.userid = bext.userid WHERE (b.active=? || b.active=?) AND b.Assigned_status<>? AND u.emailVerified=? AND b.iscomplete_later=? order by $sort  $ord";
			$result = $db->getAll($q, array('borrowers', 'users','on_borrower_behalf','borrowers_extn',0,-1, 2, 1,0));
			return $result;
		}
		elseif($userlevel==PARTNER_LEVEL)
		{
			$q="SELECT b.*,u.regdate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as name, onb.name as postedby, bext.rec_form_offcr_name, bext.rec_form_offcr_num  FROM ! as b join ! as u on b.userid= u.userid left join ! as onb on b.borrower_behalf_id= onb.id join ! as bext on b.userid = bext.userid  WHERE b.active=? AND b.Assigned_status<>? AND u.emailVerified=? AND b.Assigned_to =? AND b.iscomplete_later=? order by $sort  $ord";
			$result = $db->getAll($q, array('borrowers', 'users','on_borrower_behalf','borrowers_extn', 0,2, 1,$userid,0));
			return $result;
		}
		else
			return $result;
	}
	function activateBorrower($partid, $borrowerid, $pcomment, $addmore, $cid, $ofclName, $OfclNumber, $complete_later)
	{	
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$date1 =time();
		$rate = $this->getCurrentRate($borrowerid);
		if($cid==0)
		{
			
			$q2="INSERT INTO comments (partid, userid, feedback, editDate, rate, refOfficial_name, refOfficial_number)"." VALUES (?,?,?,?,?,?,?)";
			$res1=$db->query($q2, array($partid,$borrowerid, 2, $date1,$rate,$ofclName,$OfclNumber));
			if($res1===DB_OK)
			{
				$q = 'SELECT id from ! where partid =? and  userid = ? and editDate = ?';
				$feedbackid = $db->getOne($q , array('comments',$partid,$borrowerid,$date1));
				if($complete_later!=1){
					$qb="UPDATE borrowers SET Active=?, PartnerId=?, Assigned_date=?, Assigned_status =? WHERE userid=?";
					$resultb= $db->query($qb, array(1, $partid, $date1, 1, $borrowerid));
				}
				$this->subFeedback($borrowerid,$partid,$pcomment, $feedbackid,0);
				return true;
			}
			return false;
		}
		else
		{
			$q2="UPDATE comments SET editDate=?,refOfficial_name=?,refOfficial_number=?"." WHERE id=?";
			$res1=$db->query($q2, array($date1, $ofclName, $OfclNumber,$cid)); 
			if($res1==DB_OK)
			{
				
				if($complete_later!=1){
					$qb="UPDATE borrowers SET Active=?, PartnerId=?, Assigned_date=?, Assigned_status =? WHERE userid=?";
					$resultb= $db->query($qb, array(1, $partid, $date1, 1, $borrowerid));
				}
				$q3="UPDATE b_comments SET comment = ?, editdate= ? WHERE type = ? AND reply = ? LIMIT 1";
				$res2=$db->query($q3, array($pcomment, $date1, $cid, 0));
				if($res2===DB_OK)
					return true;
			}
			return false;
		}
	}
	function getAllBorrowers($sort='FirstName', $dir='asc', $start=0, $limit='')
	{
		global $db;
		if($sort=='FirstName')
			$sort='sortname';
		$q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
		$result=$db->getAll($q, array('borrowers','partners')); 
		return $result;
	}
	function getAllBorrowersCount()
	{
		global $db;
		$q="select count(borrowers.userid) from !, ! where borrowers.partnerid=partners.userid";
		$result=$db->getOne($q, array('borrowers', 'partners'));
		return $result;
	}
	function getAllBorroweres_Active_Deactive()
	{
		global $db;
		$q="SELECT b.FirstName, b.LastName, l.loanid, l.borrowerid, l.Amount, l.active, l.reqdamt, l.adminDelete FROM ! as b, ! as l WHERE b.userid = l.borrowerid AND l.active = ?";
		$result=$db->getAll($q, array('borrowers', 'loanapplic', LOAN_OPEN));
		return $result;
	}
	function getAllEmails()
	{
		global $db;
		$q="SELECT Email FROM ! UNION SELECT Email FROM ! UNION SELECT Email FROM !";
		$resultAll=$db->getAll($q,array('borrowers','lenders','partners'));
		$emails="select emails_notify from ! where emails_notify is NOT NULL AND emails_notify <> ''";
		$resultEmails=$db->getAll($emails,array('partners'));
		foreach($resultEmails as $row)
		{
			$nf_emails=explode(',',$row['emails_notify']);
			foreach($nf_emails as $email)
			{
				$resultAll[]['Email']=$email;
			}
		}
		return $resultAll;
	}
	function getAllLender($sort='FirstName', $dir='asc', $start=0, $limit='')
	{
		global $db;
		if($sort=='FirstName')
			$sort='sortname';
		$q="SELECT lenders.userid, lenders.FirstName, lenders.LastName, lenders.City, lenders.Country, lenders.Email, lenders.Active, concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as sortname, users.username  FROM ! join users on users.userid = lenders.userid where users.userid NOT IN(SELECT userid from ! where emailVerified=?) order by ".$sort." ".$dir." limit ".$start.", ".$limit;
		$result=$db->getAll($q,array('lenders', 'users', 0));	
			
		return $result;
	}

	function getAllLenderCount()
	{
		global $db;
		$q="SELECT count(*) FROM ! where userid NOT IN(SELECT userid from ! where emailVerified=?)";
		$result=$db->getOne($q,array('lenders', 'users', 0));
		return $result;
	}
	function getAllPartners()
	{
		global $db;
		$q="SELECT userid, name, postaddress, city, country, website, active FROM ! where userid NOT IN(SELECT userid from ! where emailVerified=?) order by active";
		$result=$db->getAll($q,array('partners', 'users', 0));
		return $result;
	}
	function getRegistrationFeeData()
	{
		global $db;
		$q="SELECT * FROM !";
		$result=$db->getAll($q,array('registration_fee'));
		return $result;
	}
	function addRates($amount,$currency, $time)
	{
		global $db;
		$q="SELECT max(id) FROM excrate WHERE currency =?";
		$q1="UPDATE excrate SET stop= ? WHERE id=?";
		$q2="INSERT INTO excrate (rate, start, currency) VALUES (?, ?, ?)";
		$r1=$db->getOne($q,array($currency));
		if(empty($r1))
		{
			$r2= $db->query($q2, array($amount , $time, $currency));
			if($r2===DB_OK)
				return 1;
		}
		else
		{
			$db->query($q2, array($amount , $time, $currency));
			$r2= $db->query($q1, array($time , $r1));
			if($r2===DB_OK)
				return 1;
		}
		return 0;
	}
	function activeBorrowers($sort, $ord, $userlevel, $userid, $start=0, $limit='', $prt=0)
	{
		global $db;
		$country = $this->getUserCityCountry($userid);
		if($sort=='FirstName')
			$sort='sortname ';
		else if($sort =='Country')
			$sort='Country, City';
		$result=array();
		if($userlevel==ADMIN_LEVEL)
		{
			$q="SELECT b.*, c.editDate ,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as c on b.userid=c.userid WHERE active=? group by c.userid order by $sort  $ord";
			$result=$db->getAll($q,array('borrowers', 'comments', 1));
		}elseif($prt==1){
			$q="SELECT b.*, c.editDate ,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as c on b.userid=c.userid WHERE active=? AND b.Country=? group by c.userid order by $sort  $ord";
			$result=$db->getAll($q,array('borrowers', 'comments', 1, $country['Country']));
		}
		else if($userlevel==PARTNER_LEVEL || $userlevel==BORROWER_LEVEL || $userlevel==LENDER_LEVEL)
		{
			$q="SELECT b.*, c.editDate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as c on b.userid=c.userid join ! as bext on b.userid = bext.userid WHERE active=? AND b.Country=? AND bext.mentor_id = ? group by c.userid order by $sort  $ord ";
			$result=$db->getAll($q,array('borrowers', 'comments', 'borrowers_extn',1, $country['Country'], $userid));
		
		}
		for ($i = 0; $i < count($result) ; $i++) {
			$q="SELECT lastVisited, note from ! WHERE borrowerid = ?";
			$res=$db->getRow($q,array('repay_report_detail',$result[$i]['userid']));
			$result[$i]['admin_notes'] = $res['note'];
			$result[$i]['lastVisited'] = $res['lastVisited'];
		}
		return $result;
	}
	function activeBorrowersCount($userlevel, $userid, $prt=0)
	{
		global $db;
		$result=0;
		$country = $this->getUserCityCountry($userid);
		if($userlevel==ADMIN_LEVEL)
		{
			$q="SELECT count(*)  FROM ( SELECT b.*, c.editDate FROM ! as b join ! as c on b.userid=c.userid WHERE active=? group by c.userid) as d";
			$result=$db->getOne($q,array('borrowers', 'comments', 1));
		}elseif($prt==1){
			$q="SELECT count(*)  FROM ( SELECT b.*, c.editDate FROM ! as b join ! as c on b.userid=c.userid WHERE active=? AND b.Country =? group by c.userid) as d";
			$result=$db->getOne($q,array('borrowers', 'comments', 1, $country['Country']));
		}
		else if($userlevel==PARTNER_LEVEL || $userlevel==BORROWER_LEVEL || $userlevel==LENDER_LEVEL )
		{
			$q="SELECT count(*)  FROM ( SELECT b.*, c.editDate FROM ! as b join ! as c on b.userid=c.userid WHERE active=? AND b.Country =? group by c.userid) as d";
			$result=$db->getOne($q,array('borrowers', 'comments', 1, $country['Country']));

		}
		return $result;
	}
	function activateLender($id)
	{
		global $db;
		$q="UPDATE ! SET Active=1 WHERE userid=?";
		return $db->query($q, array('lenders',$id));
	}
	function deactivateLender($id)
	{
		global $db;
		$q="UPDATE ! SET Active=0 WHERE userid=?";
		return $db->query($q, array('lenders',$id));
	}
	function activatePartner($id)
	{
		global $db;
		$adate=time();
		$q="UPDATE ! SET active=1, activedate=? WHERE userid=?";
		return $db->query($q, array('partners',$adate,$id));
	}
	function deactivatePartner($id)
	{
		global $db;
		$q="UPDATE ! SET active=0, activedate=0 WHERE userid=?";
		return $db->query($q, array('partners', $id));
	}
	function deactivateBorrower($id,$set)
	{
		global $db;
		$q="UPDATE ! SET Active=? WHERE userid=?";
		return $db->query($q, array('borrowers', $set, $id));
	}
	function getExchangeRate($currency=0)
	{
		global $db;
		$q="SELECT rate, date(from_unixtime(start)) as start, date(from_unixtime(stop)) as stop FROM excrate  WHERE currency = ? ORDER BY id DESC";
		$result=$db->getAll($q, array($currency));
		return $result;
	}
	function madePayment($id,$amount, $paidd)
	{
		global $db;
		if(!empty($id) && !empty($amount))
		{
			$p = "select paidamt from ! where id = ?";
			$paidamt = $db->getOne($p, array('repaymentschedule', $id));
			if(empty($paidamt))
				$q = "UPDATE ! SET paiddate = ?, paidamt = ? WHERE id = ? LIMIT 1";
			else
				$q = "UPDATE ! SET paiddate = ?, paidamt = paidamt + ? WHERE id = ? LIMIT 1";

			$data = $db->query($q, array('repaymentschedule',$paidd, $amount , $id));
			if($data===DB_OK)
				return 1;
		}
		return 0; //added by pranjal
	}
	function madePayment_Actual($amount, $paidd, $loanid, $borrowerid,$rid)
	{
		global $db;
		$q = "INSERT into ! (rid, userid, loanid, paiddate, paidamt) values (?,?,?,?,?)";
		$data = $db->query($q, array('repaymentschedule_actual',$rid, $borrowerid,$loanid,$paidd,$amount));
		if($data===DB_OK)
			return 1;
		return false;
	}
	function loanpaidback($borrowerid,$loanid)
	{
		global $db;
		$q= 'update ! set active = ? where loanid = ? and borrowerid = ?'; //AND b.adminDelete = ?
		$res1= $db->query($q, array('loanapplic', LOAN_REPAID ,$loanid, $borrowerid));
		if($res1=== DB_OK){
				$startdate=time();
				$this->setLoanStage($loanid, $borrowerid, LOAN_REPAID, $startdate, LOAN_ACTIVE);
		}
		$q= 'update ! set activeLoanID = ?, activeLoan = ? where userid = ?';
		$res2= $db->query($q, array('borrowers', 0, NO_LOAN , $borrowerid));
		if($res1=== DB_OK && $res2=== DB_OK)
			return 1;
		else
			return 0;
	}
	function setExpireInLoan()
	{
		global $db, $session;
		$deadline=$this->getAdminSetting('deadline');
		$expireTime= time() - ($deadline * 3600 *24);
		$q="SELECT loanid, borrowerid, reqdamt  FROM ! WHERE active =? AND applydate < ? AND adminDelete = ?";
		$result=$db->getAll($q, array('loanapplic ' ,LOAN_OPEN, $expireTime, 0));
		foreach($result as $row) {
			$status = $session->getStatusBar($row['borrowerid'],$row['loanid'],5);
			if($status < 100) {
				$this->setExpired($row['borrowerid'],$row['loanid']);
			}
		}
	}
	function setExpired($borrowerid,$loanid)
	{
		$today=time();
		global $db, $session;
		$loanstatus = $this->getBorrowerCurrentLoanStatus($borrowerid);
		$sql1 = "UPDATE ! SET active =?, expires = ? WHERE loanid = ? AND borrowerid =? limit 1";
		$r1= $db->query($sql1 , array('loanapplic', LOAN_EXPIRED , $today ,$loanid,$borrowerid));
		if($r1===DB_OK) {
			$sql2= 'update ! set activeLoanID = ?, activeLoan = ? where userid = ?';
			$r2= $db->query($sql2, array('borrowers', 0, NO_LOAN , $borrowerid));
			if($r2===DB_OK) {
				$session->sendLoanExpiredMail($borrowerid,$loanid);
				$this->setLoanStage($loanid, $borrowerid, LOAN_EXPIRED, $today, LOAN_OPEN);
				$r3= $this->creditBackOfLoan($loanid, LOAN_EXPIRED);
				if($r3==1) {
					/*if loan has been funded ie bids accepted by borrower and if admin wants to close that loan and credit back to lenders we should also update loanbids ,givenamount to 0*/
					if($loanstatus == LOAN_FUNDED) {
						$q="UPDATE ! SET active = ?, givenamount =? WHERE loanid = ?";
						$result=$db->query($q, array('loanbids',0,0,$loanid));
					}
					return 1;
				}
			}
		}
		return 0;
	}
	function creditBackOfLoan($loanid, $status=LOAN_EXPIRED) {
		global $db;
		$txn_type=LOAN_OUTBID;
		$txn_sub_type=LOAN_BID_EXPIRED;
		$desc = 'Loan bid expired';
		if($status==LOAN_CANCELED) {
			$txn_sub_type=LOAN_BID_CANCELED;
			$desc = 'Loan bid cancelled';
		}
		$q="SELECT userid, sum(amount) as amount FROM ! WHERE loanid = ? AND txn_type IN (?, ?) group by userid";
		$result=$db->getAll($q, array('transactions', $loanid, LOAN_BID, LOAN_OUTBID));
		foreach($result as $row) {
			if($row['amount'] < 0) {
				$txnAmt = $row['amount'] * -1;
				$ret=$this->setTransaction($row['userid'],$txnAmt,$desc,$loanid, 0,$txn_type, 0, 0, $txn_sub_type);
				if(!$ret) {
					return 0;
				}
			}
		}
		return 1;
	}
	function setDefultInLoan($borrowerid,$loanid)
	{

		global $db;
		$today=time();
		$sql2 = 'update ! set activeLoanID = ?, activeLoan = ? where userid = ?';
		$r1 = $db->query($sql2, array('borrowers', 0, NO_LOAN , $borrowerid));
		if($r1===DB_OK)
		{
			$sql1 = "UPDATE ! SET active =?, expires = ? WHERE (loanid = ? AND borrowerid =?  )LIMIT 1";//AND b.adminDelete = ?
			$r2 = $db->query($sql1 , array('loanapplic', LOAN_DEFAULTED , $today ,$loanid,$borrowerid));
			if($r2===DB_OK){
				$this->setLoanStage($loanid, $borrowerid, LOAN_DEFAULTED, $today, LOAN_ACTIVE);
				return 1;
			}
		}
		return 0;
	}
	function undoDefultInLoan($borrowerid,$loanid)
	{
		global $db;
		$sql2 = 'update ! set activeLoanID = ?, activeLoan = ? where userid = ?';
		$r1 = $db->query($sql2, array('borrowers', $loanid, LOAN_ACTIVE , $borrowerid));
		if($r1===DB_OK)
		{
			$sql1 = "UPDATE ! SET active =?, expires = ? WHERE (loanid = ? AND borrowerid =?  )LIMIT 1";//AND b.adminDelete = ?
			$r2 = $db->query($sql1 , array('loanapplic', LOAN_ACTIVE , NULL ,$loanid,$borrowerid));
			if($r2===DB_OK){
				$startdate=time();
				$this->revertLoanStage($loanid, $borrowerid, LOAN_DEFAULTED, LOAN_ACTIVE);
				return 1;
			}
		}
		return 0;
	}
	function setCancelInLoan($borrowerid,$loanid)
	{
		$today=time();
		global $db;
		$sql1 = "UPDATE ! SET active =?, expires = ? WHERE loanid = ? AND borrowerid =? limit 1";
		$r1= $db->query($sql1 , array('loanapplic', LOAN_CANCELED , $today ,$loanid,$borrowerid));
		if($r1===DB_OK) {
			$sql2= 'update ! set activeLoanID = ?, activeLoan = ? where userid = ?';
			$db->query($sql2, array('borrowers', 0, NO_LOAN , $borrowerid));
			$this->setLoanStage($loanid, $borrowerid, LOAN_CANCELED, $today, LOAN_OPEN);
			$r3= $this->creditBackOfLoan($loanid, LOAN_CANCELED);
			if($r3==1) {
				return 1;
			}
		}
		return 0;
	}
	function pendingPartners()
	{
		global $db;
		$q= 'select count(*) from partners where active = 0';
		return $db->getOne($q);
	}
	function totalPartners()
	{
		global $db;
		$q= 'select count(*) from partners ';
		return $db->getOne($q);
	}
	function updateGotAmount($loanid, $amount)
	{
		global $db;
		$q1="UPDATE ! SET AmountGot = ? WHERE loanid=?";
		$result= $db->query($q1,array('loanapplic',$amount,$loanid));
		if($result===DB_OK){
			return $result;
		}
		return 0 ;
	}
	function saveRegistrationFee($currency_id,$currency_name,$currency,$amount)
	{
		global $db;
		$qq="SELECT currency_id FROM ! WHERE currency_id=? ";
		$result1=$db->getAll($qq,array('registration_fee',$currency_id));
		if($result1){
			return 2;//if currency is already addede
		}
		if(empty($result1))
		{
			$q="INSERT INTO registration_fee (currency_id , currency , currency_name , Amount) VALUES (?, ?, ?, ?)";
			$result=$db->query($q,array($currency_id,$currency_name,$currency,$amount));
			if($result===DB_OK)
				return 1;
		}
		return 0;
	}
	function getAllLenders()
	{
		global $db;
		$sql = 'select username, userid from ! where userlevel = ?';
		$r1 = $db->getAll($sql, array('users',LENDER_LEVEL));
		return $r1;
	}
	function getAllUsers()
	{
		global $db;
		$sql = 'select username, userid from ! order by username';
		$r1 = $db->getAll($sql, array('users'));
		return $r1;
	}
	function activateNewCurrency($id,$active)
	{

		global $db;
		$sql = 'UPDATE currency SET active = ? WHERE id = ? LIMIT 1;';
		$result=$db->query($sql, array($active, $id));
		if($result===DB_OK){
			return 1;
		}

		return 0;
	}
	function ac_detivateLoan($lid,$deactive,$loanstatus,$borrowerid)
	{
		global $db;
		if($loanstatus==LOAN_OPEN || $loanstatus==LOAN_FUNDED)
		{
			$activelid = $this->getUNClosedLoanid($borrowerid);
			if($deactive)
			{
				if($activelid == $lid)
				{
					$sql = 'UPDATE ! SET ActiveLoan = ? , activeLoanID = ? WHERE userid = ? LIMIT 1;';
					$result=$db->query($sql, array( TBL_BORROWER , NO_LOAN , 0  ,$borrowerid));
				}
				else
					return false;
			}
			else
			{
				if($activelid == 0)
				{
					$sql = 'UPDATE ! SET  ActiveLoan = ? , activeLoanID = ? WHERE userid  = ? LIMIT 1;';
					$result=$db->query($sql, array( TBL_BORROWER , LOAN_OPEN , $lid ,$borrowerid));
				}
				else
					return false;
			}
		}
		$sql1 = 'UPDATE ! SET adminDelete = ? WHERE loanid  = ? LIMIT 1;'; //AND b.adminDelete = ?
		$result1=$db->query($sql1, array('loanapplic', $deactive, $lid));
		if($result1===DB_OK)
			return 1;
		return 0;
	}
	function registerEmail($email)
	{
		global $db;
		$q="INSERT INTO email_register (email, posted) VALUES  (?,?)";
		$r=$db->query($q, array($email,0));
		if($r===DB_OK){
			return 1;
		}
		return 0;
	}
	function getRegisteredEmails()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT * from email_register ";
		return $db->getAll($q);
	}
	function registerEmailSent($id)
	{
		global $db;
		$q="Update email_register set posted = ? where id = ?";
		$db->query($q, array(1, $id));
		if($r===DB_OK){
			return 1;
		}
		return 0;
	}
	function pfreport($date1, $date2)
	{
		global $db;
		$dateArr1  = explode("/",$date1);
		$dateArr2  = explode("/",$date2);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(0,0,0,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT  l.userid borrowerid, l.amount AmountGot, l.trDate AcceptDate, b.Country, b.Currency from transactions l join borrowers b on l.userid = b.userid where l.txn_type =6 AND b.active !=0 AND l.trDate >=$date3 AND l.trDate <=$date4 order by b.Country ";

		$result=$db->getAll($q);
		$p="SELECT  b.userid,r.paiddate, r.paidamt, b.Country, b.currency, r.loanid from repaymentschedule r join borrowers b on r.userid = b.userid where b.active !=0 AND r.paiddate >= $date3 AND r.paiddate <= $date4 order by b.Country";
		$result1=$db->getAll($p);

		$country = '';
		$prevCountry = '';
		$pa = array();
		$arr = array();
		$arr['Amt'] = 0;
		$arr['UsAmt'] = 0;
		$arr['AmtNew'] = 0;
		$arr['UsAmtNew'] = 0;
		$arr['CN'] = '';

		for ($i = 0; $i < count($result) ; $i++)
		{
			$country = $result[$i]['Country'];
			if($prevCountry == '')
				$prevCountry = $country;
			if($prevCountry != $country)
			{
				$pa[$arr['CN']] = $arr;
				$arr = array();
				$arr['Amt'] = 0;
				$arr['UsAmt'] = 0;
				$arr['AmtNew'] = 0;
				$arr['UsAmtNew'] = 0;
				$arr['CR'] = '';
				$prevCountry = $country;
			}
			if($prevCountry == $country)
			{

				$arr['CN'] = $result[$i]['Country'];
				$arr['Amt'] += $result[$i]['AmountGot'];
				/*$arr['UsAmt'] += ($result[$i]['AmountGot'] /   $this->getExRateByDate($result[$i]['AcceptDate'], $result[$i]['Currency']) );*/
				$arr['UsAmt'] += ($result[$i]['AmountGot'] /   $this->getCurrentRate($result[$i]['borrowerid']));
				if($arr['CR'] == '')
					$arr['CR'] = $this->getUserCurrency($result[$i]['borrowerid']);
				$prevCountry = $country;
			}
		}
		if($arr['CN'] != '')
			$pa[$arr['CN']] = $arr;
		$country = '';
		$prevCountry = '';
		$panew = array();
		$arr = array();
		$arr['AmtNew'] = 0;
		$arr['UsAmtNew'] = 0;
		$arr['CNNew'] = '';
		for ($i = 0; $i < count($result1) ; $i++)
		{
			$country = $result1[$i]['Country'];
			if($prevCountry == '')
				$prevCountry = $country;
			if($prevCountry != $country)
			{
				if($pa[$arr['CNNew']]){
					$pa[$arr['CNNew']]['AmtNew'] = $arr['AmtNew'];
					$pa[$arr['CNNew']]['UsAmtNew'] = $arr['UsAmtNew'];

				}else{
					$pa[$arr['CNNew']]['AmtNew'] = $arr['AmtNew'];
					$pa[$arr['CNNew']]['UsAmtNew'] = $arr['UsAmtNew'];
					$pa[$arr['CNNew']]['UsAmt'] = 0;
					$pa[$arr['CNNew']]['Amt'] = 0;
					$pa[$arr['CNNew']]['CR'] = $this->getUserCurrency($result1[$i-1]['userid']);
					$pa[$arr['CNNew']]['CN'] = $arr['CNNew'];

				}
				$prevCountry = $country;
			}
			if($prevCountry == $country)
			{
				$ratio = $this->getPrincipalRatio($result1[$i]['loanid']);
				$arr['CNNew'] = $result1[$i]['Country'];
				$arr['AmtNew'] += ($result1[$i]['paidamt'] * $ratio);
				/*$arr['UsAmtNew'] += ( ($result1[$i]['paidamt'] * $ratio ) /  $this->getExRateByDate($result1[$i]['paiddate'], $result1[$i]['currency']) );*/
				$arr['UsAmtNew'] += ( ($result1[$i]['paidamt'] * $ratio ) /  $this->getCurrentRate($result1[$i]['userid']));
				$prevCountry = $country;
			}
		}
		if($pa[$arr['CNNew']])
		{
			$pa[$arr['CNNew']]['AmtNew'] = $arr['AmtNew'];
			$pa[$arr['CNNew']]['UsAmtNew'] = $arr['UsAmtNew'];

		}
		else if(count($result1)!=0 && $arr['CNNew']!="")
		{
			$pa[$arr['CNNew']]['AmtNew'] = $arr['AmtNew'];
			$pa[$arr['CNNew']]['UsAmtNew'] = $arr['UsAmtNew'];
			$pa[$arr['CNNew']]['AmtNew'] = $arr['AmtNew'];
			$pa[$arr['CNNew']]['UsAmtNew'] = $arr['UsAmtNew'];
			$pa[$arr['CNNew']]['UsAmt'] = 0;
			$pa[$arr['CNNew']]['Amt'] = 0;
			$pa[$arr['CNNew']]['CR'] = $this->getUserCurrency($result1[$i -1]['userid']);
			$pa[$arr['CNNew']]['CN'] = $arr['CNNew'];

		}
		return $pa;
	}
	function pfreportnew($date1, $date2)
	{
		global $db;
		$dateArr1  = explode("/",$date1);
		$dateArr2  = explode("/",$date2);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(0,0,0,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);

		/*$date1='01/01/2009';
		$dateArr1  = explode("/",$date1);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));*/

		/* We have excluded five loan ids (39,42,44,59,60) from portfolio report because these loan amount was theft by someone. Please do not change the code without permission */

		$q="SELECT  ls.loanid, ls.borrowerid, l.AmountGot, b.FirstName, b.LastName, b.Country, b.Currency from loanstage ls join loanapplic l on ls.loanid = l.loanid join borrowers b on ls.borrowerid = b.userid where l.adminDelete =0 AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date4." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date4.")) AND l.loanid NOT IN (39,42,44,59,60) order by b.Country,ls.loanid";

		$result=$db->getAll($q);
		$pfReport=array();
		/*
			$pfReport[$country][1]=> Report for PAR 0-30 DAYS of a country
			$pfReport[$country][2]=> Report for PAR 31-60 DAYS of a country
			$pfReport[$country][3]=> Report for PAR 61-90 DAYS of a country
			$pfReport[$country][4]=> Report for PAR 91-180 DAYS of a country
			$pfReport[$country][5]=> Report for PAR OVER 180 DAYS of a country
		*/
		$threshold=$this->getAdminSetting('LATENESS_THRESHOLD');

		foreach($result as $row) {
			$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
			$forgivenResult=$db->getRow($p,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));

			$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
			$rescheduleResult=$db->getRow($p,array('reschedule',$row['loanid'],$row['borrowerid'], $date4));

			$p="SELECT SUM(amount) as totamt from ! where loanid = ? AND userid=? AND duedate < ?";
			$totamt=$db->getOne($p,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$date4));

			$repayTable = 'repaymentschedule';
			$repayTableQuery1 = 'id <= ? ';
			$repayTableQuery2 = '';
			$repayTableQuery3 = 'id = ? ';
			$reschedule_id=0;
			$rid=0;
			if(!empty($forgivenResult) || !empty($rescheduleResult)) {
				$flag1=0;/* if this flag goes 1 we will use repay history with forgiven_loans_id*/
				$flag2=0;/* if this flag goes 1 we will use repay history with reschedule_id*/
				if(!empty($forgivenResult) && !empty($rescheduleResult)) {
					if($forgivenResult['date'] < $rescheduleResult['date']) {
						$flag1=1;
					} else {
						$flag2=1;
					}
				} elseif(!empty($forgivenResult)) {
					$flag1=1;
				} else {
					$flag2=1;
				}
				if($flag1==1) {
					$forgiven_loans_id=$forgivenResult['id'];
					$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND duedate < ?";
					$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$forgiven_loans_id, $date4));
					$repayTableQuery1='repaymentschedule_id <= ? AND forgiven_loans_id = '.$forgiven_loans_id;
					$repayTableQuery2='AND forgiven_loans_id = '.$forgiven_loans_id;
					$repayTableQuery3='repaymentschedule_id = ? AND forgiven_loans_id = '.$forgiven_loans_id;
					$repayTable = 'repaymentschedule_history';
				} elseif($flag2==1) {
					$reschedule_id=$rescheduleResult['id'];
					$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND reschedule_id = ? AND duedate < ?";
					$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$reschedule_id, $date4));
					$repayTableQuery1='repaymentschedule_id <= ? AND reschedule_id = '.$reschedule_id;
					$repayTableQuery2='AND reschedule_id = '.$reschedule_id;
					$repayTableQuery3='repaymentschedule_id = ? AND reschedule_id = '.$reschedule_id;
					$repayTable = 'repaymentschedule_history';
				}
			}
			$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ?";
			$totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));
			if($reschedule_id) {
				$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? AND reschedule_id =? order by id desc";
				$rid=$db->getOne($r,array('repaymentschedule_actual_history',$row['loanid'],$row['borrowerid'],$date4,$reschedule_id));
			}
			if(!$rid) {
				$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? order by id desc";
				$rid=$db->getOne($r,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));
			}
			if(!empty($rid)) {
				$s="SELECT SUM(amount) from ! where loanid = ? AND userid=? AND ".$repayTableQuery1;
				$totAmtToRid=$db->getOne($s,array($repayTable,$row['loanid'],$row['borrowerid'],$rid));
			}
			$t="SELECT sum(amount) from ! where loan_id = ? AND borrower_id=? AND date < ?";
			$forgiveAmount=$db->getOne($t,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));

			$ratio = $this->getPrincipalRatio($row['loanid'], $date4);
			$rate=$this->getExRateByDate($date4,$row['Currency']);
			$thresholdNative= round(($threshold * $rate),4);
			if($forgiveAmount) {
				$forgivePrinAmount=round(($forgiveAmount * $ratio),4);
				$row['prinAmount']=round(($row['AmountGot']-$forgivePrinAmount),4);
			} else {
				$row['prinAmount']=$row['AmountGot'];
			}
			$row['principlePaid']= round(($totpaidamt * $ratio),4);
			$row['principleOutstanding']= round(($row['prinAmount']-$row['principlePaid']),4);
			$row['dueAmount']= round(($totamt-$totpaidamt),4);
			$row['dueAmountUSD']= round(($row['dueAmount'] / $rate),4);

			$country = $row['Country'];
			if(!isset($pfReport[$country])) {
				$pfReport[$country][1]['prinOut']=0;
				$pfReport[$country][2]['prinOut']=0;
				$pfReport[$country][3]['prinOut']=0;
				$pfReport[$country][4]['prinOut']=0;
				$pfReport[$country][5]['prinOut']=0;
				$pfReport[$country]['currency']=$row['Currency'];
				$pfReport[$country]['totPrinOut']=0;
				$pfReport[$country]['allTotPrinOut']=0;
			}
			$pfReport[$country]['allTotPrinOut'] +=$row['principleOutstanding'];
			if($row['dueAmountUSD'] <$threshold) {
				// this amount will not considor in repayrepot using threshold functionality
				continue;
			}
			$o="SELECT max(id) from ! where loanid = ? AND userid=? ".$repayTableQuery2;
			$maxid=$db->getOne($o,array($repayTable,$row['loanid'],$row['borrowerid']));

			$duedate='';
			if($rid==$maxid) {
				$r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=? ".$repayTableQuery2;
				$duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid']));
			} elseif(empty($rid)) {
				$r="SELECT duedate from ! where loanid = ? AND userid=? AND amount > ? ".$repayTableQuery2." order by id";
				$duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid'],0));
			} else {
				$flag=0;
				if(($totpaidamt + $thresholdNative) < $totAmtToRid) {
					$r="SELECT duedate from ! where loanid = ? AND userid=? AND ".$repayTableQuery3;
					$duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid'],$rid));
				} else {
					$r="SELECT * from ! where loanid = ? AND userid=? ".$repayTableQuery2;
					$repayAll=$db->getAll($r,array($repayTable,$row['loanid'],$row['borrowerid']));
					$reducedAmount=round(($totAmtToRid - $totpaidamt),4);
					foreach($repayAll as $repay) {
						if($repay['id'] >$rid) {
							if(($repay['amount']) >($thresholdNative -$reducedAmount)) {
								$duedate=$repay['duedate'];
								break;
							} else {
								$duedate=$repay['duedate'];
								$reducedAmount += $repay['amount'];
							}
						}
					}
				}
			}
			$dateDiff = $date4 - $duedate;
			$fullDays = floor($dateDiff/(60*60*24));
			$j=0;
			if($fullDays <0 ) {
				continue;
			}
			if($fullDays <31) {
				$j=1;
			} else if($fullDays <61) {
				$j=2;
			} else if($fullDays <91) {
				$j=3;
			} else if($fullDays <181) {
				$j=4;
			} else {
				$j=5;
			}
			$aboutLoan=array();
			$aboutLoan['bname']=$row['FirstName'].' '.$row['LastName'];
			$aboutLoan['days']=$fullDays;
			$aboutLoan['userid']=$row['borrowerid'];
			$aboutLoan['prinOut']=$row['principleOutstanding'];
			$pfReport[$country][$j]['prinOut'] +=$row['principleOutstanding'];
			$pfReport[$country][$j]['loans'][] =$aboutLoan;
			$pfReport[$country]['totPrinOut'] +=$row['principleOutstanding'];
		}
		return $pfReport;
	}
	function trhistory($date1, $date2,$ord,$opt)
	{
		global $db;
		$dateArr1  = explode("/",$date1);
		$dateArr2  = explode("/",$date2);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT t.id, t.TrDate, t.userid, t.amount, t.txn_desc, t.txn_sub_type, t.loanid, t.conversionrate, t.txn_type, u.userlevel from ! t LEFT OUTER JOIN ! u on t.userid = u.userid where t.TrDate >=? AND t.TrDate <=? AND t.txn_type NOT IN (?,?,?,?) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true)  order by t.$opt $ord";
		$result=$db->getAll($q, array('transactions', 'users', $date3, $date4, LOAN_BACK_LENDER, LOAN_BID,LOAN_SENT_LENDER,LOAN_OUTBID, REGISTRATION_FEE, ADMIN_ID, DONATION, ADMIN_ID, PAYPAL_FEE, ADMIN_ID, GIFT_PURCHAGE, ADMIN_ID));
		if(!empty($result)) {
			$names=array();
			$currencies=array();
			$countries=array();
			$bids=array();
			$count_result = count($result);
			for ($i = 0; $i < $count_result ; $i++) {
				if(array_key_exists($result[$i]['userid'], $names)) {
					$name=$names[$result[$i]['userid']];
				} else {
					$name=$this->getNameById($result[$i]['userid']);
					$names[$result[$i]['userid']]=$name;
				}
				if(array_key_exists($result[$i]['userid'], $currencies)) {
					$currency=$currencies[$result[$i]['userid']];
				} else {
					$currency=$this->getUserCurrency($result[$i]['userid']);
					$currencies[$result[$i]['userid']]=$currency;
				}
				$result[$i]['username']=$name;
				if($result[$i]['txn_type']==FEE || $result[$i]['txn_type']==REFERRAL_DEBIT) {
					if(array_key_exists($result[$i]['loanid'], $bids)) {
						$brid=$bids[$result[$i]['loanid']];
					} else {
						$brid=$this->getBorrowerId($result[$i]['loanid']);
						$bids[$result[$i]['loanid']]=$brid;
					}
					if(array_key_exists($brid, $currencies)) {
						$currency=$currencies[$brid];
					} else {
						$currency=$this->getUserCurrency($brid);
						$currencies[$brid]=$currency;
					}
					$result[$i]['userid'] = $brid;
				}
				$result[$i]['currency'] = $currency;
				if(array_key_exists($result[$i]['userid'], $countries)) {
					$country=$countries[$result[$i]['userid']];
				} else {
					$country='';
					if($result[$i]['userlevel']==BORROWER_LEVEL || $result[$i]['txn_type'] == FEE || $result[$i]['txn_type'] == REFERRAL_DEBIT) {
						$country=$this->getCountryCodeById($result[$i]['userid']);
					}
					$countries[$result[$i]['userid']]=$country;
				}
				$result[$i]['country'] = $country;
				if($result[$i]['txn_type']==FUND_UPLOAD) {
					$temp = 0;
					$temp2 = 0; 
					$temp1 = $i+1;
					$temp2 = $i+2;
					if($result[$temp1]['txn_type'] == PAYPAL_FEE && $result[$temp2]['txn_type']==DONATION) {
							$j = count($result);
							$result[$j]['id'] = $result[$i]['id'];
							$result[$j]['amount'] = $result[$i]['amount']+$result[$temp1]['amount']+$result[$temp2]['amount']; 
							$result[$j]['txn_type'] = 'AmountCredited';
							$result[$j]['TrDate'] = $result[$i]['TrDate'];
							$result[$j]['userid'] = $result[$i]['userid'];
							$result[$j]['txn_desc'] = 'Lending credit to';
							$result[$j]['userlevel'] = $result[$i]['userlevel'];
							$result[$j]['username'] = $result[$i]['username'];
				}else if($result[$temp1]['txn_type'] == PAYPAL_FEE || $result[$temp1]['txn_type'] == DONATION) {
						$j = count($result);
							$result[$j]['id'] = $result[$i]['id'];
							$result[$j]['amount'] = $result[$i]['amount']+$result[$temp1]['amount']; 
							$result[$j]['txn_type'] = 'AmountCredited';
							$result[$j]['TrDate'] = $result[$i]['TrDate'];
							$result[$j]['userid'] = $result[$i]['userid'];
							$result[$j]['txn_desc'] = 'Lending credit to';
							$result[$j]['userlevel'] = $result[$i]['userlevel'];
							$result[$j]['username'] = $result[$i]['username'];
					}else {
							$j = count($result);
							$result[$j]['id'] = $result[$i]['id'];
							$result[$j]['amount'] = $result[$i]['amount']; 
							$result[$j]['txn_type'] = 'AmountCredited';
							$result[$j]['TrDate'] = $result[$i]['TrDate'];
							$result[$j]['userid'] = $result[$i]['userid'];
							$result[$j]['txn_desc'] = 'Lending credit to';
							$result[$j]['userlevel'] = $result[$i]['userlevel'];
							$result[$j]['username'] = $result[$i]['username'];
					}
				}
				
			}
			return $result;
		}
		else
			return 0;
	}
	function trhistorytotal($date1, $date2)
	{
		global $db;
		$dateArr1  = explode("/",$date1);
		$dateArr2  = explode("/",$date2);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
		traceCalls(__METHOD__, __LINE__);

		$q="SELECT t.id, t.TrDate, t.userid, b.Country, t.amount, t.txn_desc, t.txn_sub_type, t.loanid, t.conversionrate, t.txn_type, u.userlevel from ! t LEFT OUTER JOIN ! u on t.userid = u.userid LEFT OUTER JOIN ! b on t.userid = b.userid where t.TrDate >=? AND t.TrDate <=? order by t.TrDate";
		$result=$db->getAll($q, array('transactions', 'users', 'borrowers', $date3, $date4));
		return $result;
	}
	function getTranslate($id, $loanid, $cmntid, $lcid=0)
	{
		global $db;
		if($id !=0)
		{
			$p="SELECT About, tr_About, BizDesc, tr_BizDesc from ! where userid = ?";
			$result1=$db->getRow($p, array('borrowers',$id));

			$q="SELECT loanuse, tr_loanuse from ! where borrowerid = ? AND loanid =  ?";
			$result2=$db->getRow($q,  array('loanapplic', $id, $loanid));

			$result1['loanuse'] = $result2['loanuse'];
			$result1['tr_loanuse'] = $result2['tr_loanuse'];

			return $result1;
		}
		else if($cmntid !=0)
		{
			$p="SELECT message, tr_message from ! where id = ?";
			$result=$db->getRow($p,array('zi_comment', $cmntid));
			return $result;
		}
		else
		{
			$p="SELECT comment, tr_comment from ! where id = ?";
			$result=$db->getRow($p,array('b_comments', $lcid));
			return $result;
		}
	}
	function upadateTranslate($bizdesc, $about, $loanuse, $cmnt, $id, $up_id, $loanid, $lcid=0)
	{
		global $db;
		if($up_id==1)
		{
			$q="UPDATE ! set tr_BizDesc = ?, tr_About = ? where userid = ?";
			$res=$db->query($q, array('borrowers',$bizdesc, $about , $id));
			if($res===1)
			{
				$q="UPDATE  ! set tr_loanuse = ? where borrowerid = ? AND loanid = ?";
				$res=$db->query($q, array('loanapplic',$loanuse, $id, $loanid));
				include_once("indexer.php");
				updateIndex1($id);
				updateIndex2($loanid);
				return $res;
			}
			else
				return 0;
		}
		if($up_id==2)
		{
			$q="UPDATE ! set tr_message = ? where id = ?";
			$res=$db->query($q, array('zi_comment',$cmnt, $id));
			include_once("indexer.php");
			updateIndex3($id);
			return $res+2;
		}
		if($up_id==3)
		{
			$q="UPDATE ! set tr_comment = ? where id = ?";
			$res=$db->query($q, array('b_comments',$cmnt, $lcid));
			return $res+4;
		}
	}
	function getAllLenderTrans($start=0, $limit='')
	{
		global $db;
		$q="SELECT userid, FirstName, LastName, Email, isTranslator, trans_Lang from lenders limit ".$start.", ".$limit;
		$result=$db->getAll($q);
		return $result;
	}
	function changeTranslator($uid, $active)
	{
		global $db;
		if($active==0)
			$active = 1;
		else
			$active = 0;
		$q="UPDATE lenders set isTranslator = '$active' where userid = '$uid'";
		$res=$db->query($q);
		return $res;
	}
	function changeTranslatorLang($uid, $translang)
	{
		global $db;
		$q="UPDATE lenders set trans_Lang = '$translang' where userid = '$uid'";
		$res=$db->query($q);
		return $res;
	}
	function isTranslator($uid)
	{
		global $db;
		$q="SELECT isTranslator from lenders where userid = '$uid'";
		$result=$db->getOne($q);
		return $result;
	}
	function isDeleteableBorrower($uid)
	{
		global $db;
		$q="SELECT borrowerid from ! where  borrowerid = ? AND adminDelete=?";
		$result=$db->getOne($q, array('loanapplic',$uid, 0));
		if($uid !=$result)
			return true;
		else
			return false;
	}
	function deleteBorrower($uid)
	{
		global $db;
		$q="SELECT borrowerid from ! where  borrowerid = ? AND adminDelete=?";
		$result=$db->getOne($q, array('loanapplic',$uid,0));
		if($uid !=$result)
		{
			$p="DELETE from ! where userid =?";
			$res1=$db->query($p, array('users',$uid));

			$q="DELETE from ! where userid =?";
			$res2=$db->query($q, array('borrowers',$uid));

			if(!empty($res1) && !empty($res2))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function isDeleteablePartner($uid)
	{
		global $db;
		$q="SELECT Active from ! where  userid = ?";
		$result=$db->getOne($q, array('partners',$uid));
		if($result == 0)
			return true;
		else
			return false;
	}
	function deletePartner($uid)
	{
		global $db;
		$q="SELECT Active from ! where  userid = ?";
		$result=$db->getOne($q, array('partners',$uid));
		if($result == 0)
		{
			$p="DELETE from ! where userid =?";
			$res1=$db->query($p, array('users',$uid));

			$q="DELETE from ! where userid =?";
			$res2=$db->query($q, array('partners',$uid));

			if(!empty($res1) && !empty($res2))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function isDeleteableLender($uid)
	{
		global $db;
		$q="SELECT userid from ! where  userid = ?";
		$result=$db->getOne($q, array('transactions',$uid));
		if($uid !=$result)
			return true;
		else
			return false;
	}
	function deleteLender($uid)
	{
		global $db;
		$q="SELECT userid from ! where  userid = ?";
		$result=$db->getOne($q, array('transactions',$uid));
		if($uid !=$result)
		{
			$p="DELETE from ! where userid =?";
			$res1=$db->query($p, array('users',$uid));

			$q="DELETE from ! where userid =?";
			$res2=$db->query($q, array('lenders',$uid));

			if(!empty($res1) && !empty($res2))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function repay_report($date, $country)
	{
		global $db, $session;
		traceCalls(__METHOD__, __LINE__);
		$dateArr1  = explode("/",$date);
		$date1=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$threshholdAmt= $this->getAdminSetting('RepaymentReportThrshld');
		if($country=='AA')
		{
			$query="SELECT DISTINCT(b.Country) FROM ! as b, ! as l WHERE l.active=".LOAN_ACTIVE." AND  b.userid=l.borrowerid AND l.adminDelete = ?";
			$countries=$db->getAll($query, array('borrowers','loanapplic',0));
			$j=0;
			foreach($countries as $row){
				$country1[$j]=$row['Country'];
				$j++;
			}
		}
		else
			$country1[0]=$country;
		$is_mentor= $this->isBorrowerAlreadyAccess($session->userid);
		$allReport=array();
		for($j=0; $j<count($country1); $j++)
		{
			if($is_mentor){
				$q="SELECT b.userid, b.FirstName, b.LastName, b.TelMobile, b.City, b.PAddress, l.loanid FROM ! as b, ! as l, ! as ls, ! as bext WHERE ls.loanid=l.loanid AND b.userid=ls.borrowerid AND b.userid=bext.userid AND l.adminDelete = ? AND b.Country = ? AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date1." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date1.")) AND bext.mentor_id=$session->userid";
				$result=$db->getAll($q, array('borrowers','loanapplic','loanstage','borrowers_extn',0,$country1[$j]));  
				
			}else{
				$q="SELECT b.userid, b.FirstName, b.LastName, b.TelMobile, b.City, b.PAddress, l.loanid FROM ! as b, ! as l, ! as ls WHERE ls.loanid=l.loanid AND b.userid=ls.borrowerid AND l.adminDelete = ? AND b.Country = ? AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date1." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date1."))";
				$result=$db->getAll($q, array('borrowers','loanapplic','loanstage',0,$country1[$j]));
			}
			$report=array();
			$duedateArr=array();
			$i=0;
			foreach($result as $row)
			{
				$CurrencyRate = $this->getCurrentRate($row['userid']);
				$amtThreshhold = convertToNative($threshholdAmt, $CurrencyRate);
				$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
				$forgivenResult=$db->getRow($p,array('forgiven_loans',$row['loanid'],$row['userid'], $date1));

				$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
				$rescheduleResult=$db->getRow($p,array('reschedule',$row['loanid'],$row['userid'], $date1));

				$p="SELECT SUM(amount) as totamt from ! where loanid = ? AND userid=? AND duedate < ?";
				$totamt=$db->getOne($p,array('repaymentschedule',$row['loanid'],$row['userid'],$date1));
				$dueDateFromHistory=0;
				$forgiven_loans_id=0;
				$reschedule_id=0;
				if(!empty($forgivenResult) || !empty($rescheduleResult))
				{
					$flag1=0;/* if this flag goes 1 we will use repay history with forgiven_loans_id*/
					$flag2=0;/* if this flag goes 1 we will use repay history with reschedule_id*/
					if(!empty($forgivenResult) && !empty($rescheduleResult))
					{
						$forgiveDate=$forgivenResult['date'];
						$rescheduleDate=$rescheduleResult['date'];
						if($forgiveDate < $rescheduleDate)
							$flag1=1;
						else
							$flag2=1;
						$dueDateFromHistory=1;
					}
					elseif(!empty($forgivenResult))
					{
						$flag1=1;
						$dueDateFromHistory=1;
					}
					else
					{
						$flag2=1;
						$dueDateFromHistory=1;
					}
					if($flag1==1)
					{
						$forgiven_loans_id=$forgivenResult['id'];
						$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND duedate < ?";
						$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['userid'],$forgiven_loans_id, $date1));
					}
					elseif($flag2==1)
					{
						$reschedule_id=$rescheduleResult['id'];
						$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND reschedule_id = ? AND duedate < ?";
						$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['userid'],$reschedule_id, $date1));
					}
				}

				$duedate='';

				if($dueDateFromHistory==0)
				{
					$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ?";
					$totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['userid'],$date1));

					$o="SELECT max(id) from ! where loanid = ? AND userid=?";
					$maxid=$db->getOne($o,array('repaymentschedule',$row['loanid'],$row['userid']));

					$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? order by id desc";
					$rid=$db->getOne($r,array('repaymentschedule_actual',$row['loanid'],$row['userid'],$date1));
					if(!empty($rid))
					{
						$s="SELECT SUM(amount) from ! where loanid = ? AND userid=? AND id <= ?";
						$totAmtToRid=$db->getOne($s,array('repaymentschedule',$row['loanid'],$row['userid'],$rid));
					}

					if($rid==$maxid)
					{
						$r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=?";
						$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['userid']));
					}
					elseif(empty($rid))
					{
						$r="SELECT duedate from ! where loanid = ? AND userid=? AND amount > ? AND paidamt is NULL order by id";
						$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['userid'],0));
					}
					else
					{
						$flag=0;
						
						if(($totpaidamt +$amtThreshhold )< $totAmtToRid)
						{	
							$r="SELECT duedate from ! where loanid = ? AND userid=? AND id=?";
							$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['userid'],$rid));
						}
						else
						{
							$r="SELECT duedate from ! where loanid = ? AND userid=? AND id > ? AND amount > 0 order by id";
							$duedate=$db->getOne($r,array('repaymentschedule',$row['loanid'],$row['userid'],$rid));
						}
					}
				}
				else
				{
					if($forgiven_loans_id!=0)
					{
						if(!empty($rescheduleResult))
						{
							$reschedule_id=$rescheduleResult['id'];
							$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ? AND reschedule_id = ?";
							$totpaidamt=$db->getOne($q,array('repaymentschedule_actual_history',$row['loanid'],$row['userid'],$date1, $reschedule_id));

							$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? AND reschedule_id = ? order by id desc";
							$rid=$db->getOne($r,array('repaymentschedule_actual_history',$row['loanid'],$row['userid'], $date1, $reschedule_id));
						}
						else
						{
							$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ? ";
							$totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['userid'],$date1));

							$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ?  order by id desc";
							$rid=$db->getOne($r,array('repaymentschedule_actual',$row['loanid'],$row['userid'], $date1));
						}

						$o="SELECT max(repaymentschedule_id) from ! where loanid = ? AND userid=? AND forgiven_loans_id = ?";
						$maxid=$db->getOne($o,array('repaymentschedule_history',$row['loanid'],$row['userid'], $forgiven_loans_id));

						if(!empty($rid))
						{
							$s="SELECT SUM(amount) from ! where loanid = ? AND userid=? AND repaymentschedule_id <= ? AND forgiven_loans_id = ?";
							$totAmtToRid=$db->getOne($s,array('repaymentschedule_history',$row['loanid'],$row['userid'],$rid, $forgiven_loans_id));
						}

						if($rid==$maxid)
						{
							$r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=? AND forgiven_loans_id = ?";
							$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$forgiven_loans_id));
						}
						elseif(empty($rid))
						{
							$r="SELECT duedate from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND amount > ? AND paidamt is NULL order by id";
							$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$forgiven_loans_id,0));
						}
						else
						{
							$flag=0;
							if(($totpaidamt +$amtThreshhold )< $totAmtToRid)
							{
								$r="SELECT duedate from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND repaymentschedule_id=?";
								$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$forgiven_loans_id,$rid));
							}
							else
							{
							$r="SELECT duedate from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND repaymentschedule_id > ? AND amount > 0 order by id";
								$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$forgiven_loans_id,$rid));
							}
						}
					}
					else if($reschedule_id!=0)
					{
						$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ? AND reschedule_id = ?";
						$totpaidamt=$db->getOne($q,array('repaymentschedule_actual_history',$row['loanid'],$row['userid'],$date1, $reschedule_id));

						$o="SELECT max(id) from ! where loanid = ? AND userid=? AND reschedule_id = ?";
						$maxid=$db->getOne($o,array('repaymentschedule_history',$row['loanid'],$row['userid'], $reschedule_id));

						$r="SELECT rid from ! where loanid = ? AND userid=? AND paiddate < ? AND reschedule_id = ? order by id desc";
						$rid=$db->getOne($r,array('repaymentschedule_actual_history',$row['loanid'],$row['userid'], $date1, $reschedule_id));
						if(!empty($rid))
						{
							$s="SELECT SUM(amount) from ! where loanid = ? AND userid=? AND id <= ? AND reschedule_id = ?";
							$totAmtToRid=$db->getOne($s,array('repaymentschedule_history',$row['loanid'],$row['userid'],$rid, $reschedule_id));
						}

						if($rid==$maxid)
						{
							$r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=? AND reschedule_id = ?";
							$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$reschedule_id));
						}
						elseif(empty($rid))
						{
							$r="SELECT duedate from ! where loanid = ? AND userid=? AND reschedule_id = ? AND amount > ? AND paidamt is NULL order by id";
							$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$reschedule_id,0));
						}
						else
						{
							$flag=0;
							if(($totpaidamt +$amtThreshhold )< $totAmtToRid)
							{
								$r="SELECT duedate from ! where loanid = ? AND userid=? AND reschedule_id = ? AND repaymentschedule_id=?";
								$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$reschedule_id,$rid));
							}
							else
							{
								$r="SELECT duedate from ! where loanid = ? AND userid=? AND reschedule_id = ? AND repaymentschedule_id > ? AND amount > 0 order by id";
								$duedate=$db->getOne($r,array('repaymentschedule_history',$row['loanid'],$row['userid'],$reschedule_id,$rid));
							}
						}
					}
				}
				$r="SELECT rec_form_offcr_name, rec_form_offcr_num, mentor_id from ! where userid = ?";
				$refdetail = $db->getRow($r,array('borrowers_extn',$row['userid'])); 

				
				$query="SELECT expected_repaydate, note FROM ! WHERE borrowerid = ?";
				$repaydetails = $db->getRow($query, array('repay_report_detail',$row['userid']));
				
				$res= array();
				$res['bname']=$row['FirstName']." ".$row['LastName'];
				$res['duedate']=$duedate;
				$res['userid']=$row['userid'];
				$res['loanid']=$row['loanid'];
				$res['TelMobile'] = $row['TelMobile'];
				$res['totamt']=$totamt;
				$res['totpaidamt']=$totpaidamt;
				$res['rec_form_offcr_num'] = $refdetail['rec_form_offcr_num'];
				$res['rec_form_offcr_name'] = $refdetail['rec_form_offcr_name'];
				$res['mentor_id'] = $refdetail['mentor_id'];
				//$res['commentid'] = $refdetail['commentid'];
				$res['expected_repaydate'] = $repaydetails['expected_repaydate'];
				$res['note'] = $repaydetails['note'];
				$res['city']= $row['City'];
				$res['Paddress']= $row['PAddress'];
				$duedateArr[$i]=$duedate;
				$report[$i]=$res;
				$i++;
			}
			if(!empty($report)) {
				array_multisort($duedateArr, SORT_ASC, $report);
				$allReport[$j]=$report;
				$allReport[$j][0]['country']=$country1[$j];
			}
		}
		return $allReport;
	}
	function addpaymenttolender($userid,$amount,$donation)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$amount1=$amount + $donation;
		$txn_sub_type = UPLOADED_BY_ADMIN;
		$res1= $this->setTransaction($userid,$amount1,'Funds upload to lender account',0,0,FUND_UPLOAD,0,0, $txn_sub_type);
		sleep(1);
		if($res1===1)
		{
			if($donation >0)
			{
				$donationamt= $donation *-1;
				$res2= $this->setTransaction($userid,$donationamt,'Donation to Zidisha',0,0,DONATION);
				if($res2===1)
				{
					$res3= $this->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION);
					if($res3===1)
						return 1;
					else
						return 0;
				}
				else
					return 0;
			}
			else
				return 1;
		}
		else
			return 0;
	}
	function adddonationtolender($name, $email, $donationamt)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);

		$transaction_id= $this->setTransaction(ADMIN_ID,$donationamt,'Donation from lender',0,0,DONATION,1);
		$q="INSERT INTO ! (transaction_id, name, email, donation_amt, payment_type) VALUES "."(?, ?, ?, ?, ?)";
		$res3= $db->query($q,array('donation',$transaction_id, $name, $email, $donationamt, PAYPAL));
		if($res3===1)
			return 1;
		else
			return 0;
	}
	function changePassword($userid,$password)
	{
		global $db;
		$salt = $this->makeSalt();
		$newpass= $this->makePassword($password, $salt);

		$q="UPDATE ! SET password=?, salt=? WHERE userid=?";
		$r3 = $db->query($q, array('users', $newpass, $salt, $userid));
		if($r3===DB_OK)
			return 1;
		return 0;
	}
	function getAllActivePartners()
	{
		global $db;
		$q="SELECT * FROM ! where Active=? order by userid desc";
		$result=$db->getAll($q,array('partners', 1, ));
		if(empty($result)){
			return false;
		}
		return $result;
	}
	function assignedPartner($partnerid,$borrowerid)
	{
		global $db;
		$time=time();
		$q = "UPDATE ! set Assigned_to=?, Assigned_date=?, Assigned_status=? where userid=?";
		$res1=$db->query($q, array('borrowers', $partnerid, $time, -2, $borrowerid));
		if($res1===DB_OK)
			return 1;
		else
			return 0;
	}
	function declinedBorrower($borrowerid,$dreason, $userid)
	{
		global $db;
		$time=time();
		$q = "UPDATE ! set Active = ?, declined_reason=?, Assigned_to=?, Assigned_date=?, Assigned_status=? where userid=?";
		$res1=$db->query($q, array('borrowers', 0, $dreason, $userid, $time, 2, $borrowerid));
		if($res1===DB_OK)
			return 1;
		else
			return 0;
	}
	function isBorrowerAssignedToThisPartner($bid, $pid)
	{
		global $db,$session;
		if($session->userlevel == ADMIN_LEVEL)
			return true;
		else
		{
			$q="SELECT count(userid) FROM ! WHERE userid=? and (Assigned_to =? OR PartnerId=?)";
			$count = $db->getOne($q, array('borrowers', $bid,$pid,$pid));
			if($count==0)
				return false;
			else
				return true;
		}
	}
	function referral($country,$refCommission, $refPercent)
	{
		global $db;
		$time=time();
		$q="SELECT max(id) FROM referrals WHERE country =? AND status = 1";

		$last=0;
		$q1="UPDATE referrals SET stop= ?, Status = ? WHERE id=?";

		$q2="INSERT INTO referrals (start, country, ref_commission, percent_repay, status) VALUES (?, ?, ?, ?, ?)";

		$id=$db->getOne($q,array($country));
		$res=0;
		if(empty($id))
		{
			$res= $db->query($q2, array($time , $country, $refCommission, $refPercent, 1));
		}
		else
		{
			$r2=$db->query($q2, array($time , $country, $refCommission, $refPercent, 1));
			if($r2===DB_OK)
			{
				$res= $db->query($q1, array($time , 0, $id));
			}
		}
		if($res===DB_OK)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function getReferrals($country, $all=true)
	{
		global $db;
		if($all){
			$q="SELECT * FROM referrals WHERE country =? order by id desc";
			$res=$db->getAll($q,array($country));
		}
		else {
			$q="SELECT * FROM referrals WHERE country =? AND status = ? order by id desc";
			$res=$db->getRow($q,array($country, 1));

		}
		return $res;
	}
	function checkReferrer($referrer)
	{
		global $db;
		$q="SELECT count(u.userid) FROM ! as b join ! as u on b.userid= u.userid WHERE b.active=? and u.username=?";
		$result = $db->getOne($q, array('borrowers', 'users',1, $referrer));
		return $result;
	}
	function addCommission($applicant_id, $referrer_id, $referral_id)
	{
		global $db;
		$time=time();
		$q="INSERT INTO ! (applicant_id, referrer_id, referral_id, date) VALUES (?, ?, ?, ?)";
		$res=$db->query($q, array('commissions', $applicant_id , $referrer_id, $referral_id, $time));
		if($res===DB_OK)
			return 1;
		else
			return 0;
	}
	function updateCommission($id, $amount, $time, $loanid)
	{
		global $db;
		$time=time();
		$q="UPDATE ! set paid_amt =?, paid_date=?, loan_id =? where id=?";
		$res=$db->query($q, array('commissions', $amount , $time, $loanid, $id));
		if($res===DB_OK)
			return 1;
		else
			return 0;
	}
	function updateCommissionFailed($id, $reason)
	{
		global $db;
		$time=time();
		$q="UPDATE ! set failed_reason  =? where id=?";
		$res=$db->query($q, array('commissions', $reason, $id));
		if($res===DB_OK)
			return 1;
		else
			return 0;
	}
	function getPendingCommissions($userid=0)
	{
		global $db;
		if(empty($userid))
		{
			$q="SELECT c.*, r.ref_commission, r.percent_repay FROM commissions as c join referrals as r on c.referral_id = r.id WHERE c.paid_amt IS NULL order by c.id desc";
		}
		else
		{
			$q="SELECT c.*, r.ref_commission, r.percent_repay FROM commissions as c join referrals as r on c.referral_id = r.id WHERE c.referrer_id = $userid AND c.paid_amt IS NULL order by c.id desc";
		}
		$res=$db->getAll($q);
		return $res;
	}
	function getPaidCommissions($userid=0)
	{
		global $db;
		if(empty($userid))
		{
			$q="SELECT c.*, r.ref_commission, r.percent_repay FROM commissions as c join referrals as r on c.referral_id = r.id WHERE c.paid_amt IS NOT NULL order by c.id desc";
		}
		else
		{
			$q="SELECT c.*, r.ref_commission, r.percent_repay FROM commissions as c join referrals as r on c.referral_id = r.id WHERE c.referrer_id = $userid AND c.paid_amt IS NOT NULL order by c.id desc";
		}

		$res=$db->getAll($q,array());
		return $res;
	}
	function getPendingCommissionByApplicantId($applicant_id)
	{
		global $db;
		$q="SELECT c.*, r.ref_commission, r.percent_repay FROM commissions as c join referrals as r on c.referral_id = r.id WHERE c.applicant_id = $applicant_id AND c.paid_amt IS NULL";
		$res=$db->getRow($q);
		return $res;
	}
	function getAllRepayment_Instructions()
	{
		global $db;
		$q="SELECT c.name, rpi.description, rpi.id FROM ! as c, ! as rpi WHERE c.code = rpi.country_code AND rpi.active=? order by rpi.id desc";
		$result=$db->getAll($q, array('countries', 'repayment_instructions', 1));
		$q1="SELECT description,id FROM ! WHERE country_code='ALL' AND active=?";
		$defaultinstruction=$db->getALL($q1, array('repayment_instructions', 1));
		
		if(!empty($defaultinstruction)) {
			$result = array_merge($result,$defaultinstruction);
		}
		return $result;
	}
	function getRepayment_InstructionsById($id)
	{
		global $db;
		$q="SELECT c.name, rpi.description, rpi.id, rpi.country_code FROM ! as c, ! as rpi WHERE c.code = rpi.country_code AND rpi.active=? AND rpi.id=?";
		$result=$db->getAll($q, array('countries', 'repayment_instructions', 1,$id));
		return $result;
	}
	function addRePaymentInstruction($country_code, $description)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$date=date('Y-m-d h:i:s');
		$q="INSERT INTO ! (country_code, description, created) VALUES (?, ?, ?)";
		$res = $db->query($q, array('repayment_instructions', $country_code, $description, $date));
		if($res === DB_OK)
			return 1;//successful insert
		else
			return 0;//cannot insert
	}
	function updateRePaymentInstruction($country_code, $description, $id)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$date=date('Y-m-d h:i:s');
		$q = "UPDATE ! SET country_code = ?, description= ? WHERE id = ?";
		$res = $db->query($q, array('repayment_instructions', $country_code, $description, $id));
		if($res === DB_OK)
			return 1;//successful insert
		else
			return 0;//cannot insert
	}
	function deleteRePaymentInstruction($id)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q = "delete from ! where id = ?";
		$res = $db->query($q, array('repayment_instructions', $id));
		if($res === DB_OK)
			return 1;//successful insert
		else
			return 0;//cannot insert
	}
	function getRepayment_InstructionsByCountryCode($country_code)
	{
		global $db;
		$q="SELECT c.name, rpi.description, rpi.id, rpi.country_code FROM ! as c, ! as rpi WHERE c.code = rpi.country_code AND rpi.active=? AND rpi.country_code=? limit 1";
		$result=$db->getRow($q, array('countries', 'repayment_instructions', 1,$country_code));
		return $result;
	}
	function IsActiveCampaign()
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT id,max_use FROM ! WHERE active=?";
		$campaigns= $db->getAll($q , array('campaign',1));
		$isCampaign=false;
		foreach($campaigns as $campaign)
				{
					$camp_id=$campaign['id'];
					$isMax=$this->checkMaxUseCampaign($camp_id);
					if($isMax<$campaign['max_use'])
						$isCampaign=true;
				}
		return $isCampaign;
	}

	function checkMaxUseCampaign($camp_id)
	{

		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q = "SELECT COUNT(*) FROM ! WHERE campaign_id = ?";
		$Ismax=$db->getOne($q, array('referral_codes', $camp_id));
		return $Ismax;
	}
	function addCampaign($code,$value, $max_use, $message,$active, $time)
	{

		global $db;

		$q = "INSERT into ! (code,value,max_use, message,active,date) values (?,?,?,?,?,?)";
		$data = $db->query($q, array('campaign', $code,$value,$max_use,$message,$active,$time));
		if($data===DB_OK)
			return 1;
		return false;
	}
	function getCampaign()
	{
		global $db;
		$q="SELECT * FROM campaign ORDER BY id ASC";
		$result=$db->getAll($q);
		return $result;
	}
	function getCampaignById($id)
	{
		global $db;
		$q="SELECT * FROM ! WHERE id=?";
		$result=$db->getRow($q, array('campaign',$id));
		return $result;
	}
	function updateCampaign($code, $value,$max_use, $message, $active,$id)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$date=date('Y-m-d h:i:s');
		$q = "UPDATE ! SET code = ?, value= ? ,max_use=? ,message=?,active=? Where id = ?";
		$res = $db->query($q, array('campaign', $code, $value,$max_use, $message, $active,$id));
		if($res === DB_OK)
			return 1;//successful insert
		else
			return 0;//cannot insert
	}
	function deletecampaign($id)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q = "delete from ! where id = ?";
		$res = $db->query($q, array('campaign', $id));
		if($res === DB_OK)
			return 1;//successful delete
		else
			return 0;//cannot delete
	}
	function ConverToDonation($userid,$donation,$cron=false)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		if(!$cron){
			$q="UPDATE ! SET Active=0 ,admin_donate=1 WHERE userid=?";
			$data= $db->query($q, array('lenders',$userid));
		}
		if($data==DB_OK || $cron==true) {
			$donationamt= $donation *-1;
			$res3= $this->setTransaction($userid,$donationamt,'Donation to Zidisha',0,0,DONATION,'','',DONATE_BY_ADMIN);
			if($res3===1) {
				$res4= $this->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION,'','',DONATE_BY_ADMIN);
				if($res4===1)
					return 1;
			}
			return 0;
		}
		return 0;
	}
	function checkDeactivatedAndDonate()
	{
		global $db;
		$q="SELECT userid FROM ! WHERE admin_donate=?";
		$result=$db->getAll($q, array('lenders',1));
		return $result;
	}
	function SetBorrowerReports($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage)
	{

		global $db;
		$time=time();
		$q = "INSERT into ! (borrower_id,recipient, cc,replyto,subject,message,sent_on) values (?,?,?,?,?,?,?)";
		$data = $db->query($q, array('borrower_reports', $borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage,$time));
		if($data===DB_OK)
			return 1;
		return false;
	}
	function getBorrowerReports($userid)
	{
		global $db;
		$q="SELECT * FROM ! WHERE borrower_id=? ORDER BY sent_on DESC";
		$result=$db->getRow($q, array('borrower_reports',$userid));
		return $result;
	}
	function getBorrowerAllReports($userid)
	{
		global $db;
		$q="SELECT * FROM ! WHERE borrower_id=?";
		$result=$db->getAll($q, array('borrower_reports',$userid));
		return $result;
	}
	function outstandinReports( $date2)
	{
		global $db;
		//$date2='12/31/2011';
		$dateArr2  = explode("/",$date2);
		$date4=mktime(0,0,0,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);

		/* We have excluded five loan ids (39,42,44,59,60) from outstanding report because these loan amount was theft by someone. Please do not change the code without permission */
		//$q="SELECT ls.loanid, ls.borrowerid , b.FirstName, b.LastName, b.City, b.Country , la.finalrate, la.webfee, la.AmountGot, t.TrDate from loanstage ls join borrowers b on ls.borrowerid = b.userid join loanapplic la on ls.loanid=la.loanid join transactions t on t.loanid=ls.loanid where t.txn_type= ".DISBURSEMENT." AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date4." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date4.")) AND ls.borrowerid IN (627, 316, 1277, 1214, 130, 131, 453, 683, 758, 918, 1341, 1021, 915) order by ls.loanid";

		$q="SELECT ls.loanid, ls.borrowerid , b.FirstName, b.LastName, b.City, b.Country , la.finalrate, la.webfee, la.AmountGot, t.TrDate from loanstage ls join borrowers b on ls.borrowerid = b.userid join loanapplic la on ls.loanid=la.loanid join transactions t on t.loanid=ls.loanid where t.txn_type= ".DISBURSEMENT." AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date4." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date4.")) order by ls.loanid";

		$result=$db->getAll($q);
		$OutstndReport=array();
		$i=0;
		foreach($result as $row)
		{
			$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
			$forgivenResult=$db->getRow($p,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));

			$p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
			$rescheduleResult=$db->getRow($p,array('reschedule',$row['loanid'],$row['borrowerid'], $date4));

			$p="SELECT SUM(amount) as totamt from ! where loanid = ? AND userid=? AND duedate < ?";
			$totamt=$db->getOne($p,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$date4));
			if(!empty($forgivenResult) || !empty($rescheduleResult))
			{
				$flag1=0;/* if this flag goes 1 we will use repay history with forgiven_loans_id*/
				$flag2=0;/* if this flag goes 1 we will use repay history with reschedule_id*/
				if(!empty($forgivenResult) && !empty($rescheduleResult))
				{
					$forgiveDate=$forgivenResult['date'];
					$rescheduleDate=$rescheduleResult['date'];
					if($forgiveDate < $rescheduleDate)
						$flag1=1;
					else
						$flag2=1;
				}
				elseif(!empty($forgivenResult))
				{
					$flag1=1;
				}
				else
				{
					$flag2=1;
				}

				if($flag1==1)
				{
					$forgiven_loans_id=$forgivenResult['id'];
					$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND duedate < ?";
					$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$forgiven_loans_id, $date4));
				}
				elseif($flag2==1)
				{
					$reschedule_id=$rescheduleResult['id'];
					$p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND reschedule_id = ? AND duedate < ?";
					$totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$reschedule_id, $date4));
				}

			}

			$q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ?";
			$totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));
			$ratio = $this->getPrincipalRatio($row['loanid']);
			$rate=$this->getExRateById($date4,$row['borrowerid']);
			$balance=$totamt-$totpaidamt;
			if($balance >0){
				$principle= ($balance* $ratio);
				$interest=$balance-$principle;
				$finalrate=$row['finalrate'];
				$webfee=$row['webfee'];
				$value = $interest/($finalrate + $webfee);
				$fee=$value * $webfee;
				$int=$value * $finalrate;
				$OutstndReport[$i]['AmountDisb']=$row['AmountGot'];
				$OutstndReport[$i]['AmountDisbUsd']=convertToDollar($row['AmountGot'],$rate);
				$OutstndReport[$i]['CombinedAmt']=$balance;
				$OutstndReport[$i]['CombinedAmtUsd']=convertToDollar($balance,$rate);
				$OutstndReport[$i]['principleAmt']=$principle;
				$OutstndReport[$i]['principleAmtUsd']=convertToDollar($principle,$rate);
				$OutstndReport[$i]['InterestAmt']=$int;
				$OutstndReport[$i]['InterestAmtUsd']=convertToDollar($int,$rate);
				$OutstndReport[$i]['feeAmt']=$fee;
				$OutstndReport[$i]['feeAmtUsd']=convertToDollar($fee,$rate);
				$OutstndReport[$i]['loanid']=$row['loanid'];
				$OutstndReport[$i]['borrowerid']=$row['borrowerid'];
				$OutstndReport[$i]['FirstName']=$row['FirstName'];
				$OutstndReport[$i]['LastName']=$row['LastName'];
				$OutstndReport[$i]['City']=$row['City'];
				$OutstndReport[$i]['DisbDate']=$row['TrDate'];
				$OutstndReport[$i]['Country']=$this->mysetCountry($row['Country']);
				$i++;
			}
			//$OutstndReport[$i]['amtPaid']=convertToDollar($totpaidamt,$rate);
		}
		return $OutstndReport;
	}
	function StopRefferalCommision($country){
		global $db;
		$time=time();
		$q = "UPDATE ! SET status = ?, stop = ? WHERE country = ? AND stop = ?";
		$res = $db->query($q, array('referrals', 0, $time, $country, 0));
		return $result;
	}
	function getLoansToExpire($timeTocompare){
		global $db;
		$q="SELECT loanid, borrowerid, applydate FROM ! WHERE active=? AND adminDelete = ? AND expiry_mail = ? AND applydate <= ?";
		$result= $db->getAll($q, array('loanapplic', LOAN_OPEN, 0, 0, $timeTocompare));
		return $result;
	}
	function getExpiredLenderAccounts(){
		global $db;
		$yearago = strtotime("-".ACCOUNT_EXPIRE_MAIL_DURATION." months");
		$q="SELECT users.userid, lenders.email,lenders.FirstName, lenders.LastName FROM ! join lenders on lenders.userid = users.userid WHERE last_login < $yearago AND accountExpiedMail = 0 AND lenders.Active = 1";
		$lenders= $db->getAll($q, array('users'));
		return $lenders;
	}
	function getFeeByAnonymous($userid, $date1, $date2){
		global $db;
		$dateArr1  = explode("/",$date1);
		$dateArr2  = explode("/",$date2);
		$date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
		$date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
		$q="SELECT distinct loanid FROM ! WHERE lenderid = ? AND active = ? ";
		$loanids = $db->getAll($q, array('loanbids', $userid, 1));
		if($loanids) {
			foreach($loanids as $lid) {
				$lids[] = $lid['loanid'];
			}
			$ids = implode(',', $lids);
			$q="SELECT SUM(`amount`) as TrFee FROM ! WHERE TrDate >=? AND TrDate <=? AND loanid in ( ".$ids." ) AND txn_type = ?";
			$feeamount = $db->getOne($q, array('transactions', $date3, $date4, FEE));
		}
		if(!$feeamount) {
			return 0;
		}
		return $feeamount;
	}
	
	function saveCreditSetting($country, $loanamtlimit, $charlimit, $commentlimit, $type)
	{
		global $db;
		$time=time();

		$q="SELECT count(*) as comment_credit FROM ! WHERE country_code = ? AND type= ?";
		$alreadyset = $db->getOne($q, array('credit_setting', $country, $type));
		if($alreadyset >0) {
			$q = "UPDATE ! SET  loanamt_limit = ?, character_limit = ?, comments_limit = ?, modified = ? WHERE country_code = ? AND type = ? ";
			$res = $db->query($q, array('credit_setting', $loanamtlimit,$charlimit,$commentlimit, $time, $country, $type));
		}else {
			$q = "INSERT into ! (country_code,loanamt_limit, character_limit ,comments_limit, type, created) values (?,?,?,?,?,?)";
			$res = $db->query($q, array('credit_setting', $country, $loanamtlimit, $charlimit, $commentlimit, $type, $time));
		}
		if($res===DB_OK)
			return 1;
		return 0;
	}
	function updateloanbidonExpire($loanid, $borrowerid){
		global $db;
		$time=time();
		$q = "UPDATE ! SET status = ?, stop = ? WHERE country = ? AND stop = ?";
		$res = $db->query($q, array('referrals', 0, $time, $country, 0));
		return $result;
	}
	function referraldetailByborrower($borrowerid){
		global $db;
		$r="SELECT refOfficial_number, refOfficial_name ,b_comments.comment from ! join b_comments on b_comments.type = comments.id where comments.userid = ?  ORDER BY comments.id DESC";
		$refdetail = $db->getRow($r,array('comments',$borrowerid));
		return $refdetail;
	}
	function getLoanForgiveDetails($loanid) {
		global $db;
		$q="select comment, validation_code  from ! where loanid=?";
		$result=$db->getRow($q, array('loans_to_forgive',$loanid)); 
		return $result;
	}
	function updateLoanForgiveDetails($loanid, $comment, $validation_code){
		global $db;
		$q="UPDATE ! SET comment=?, `validation_code` =?  WHERE loanid=?";
		$result=$db->query($q, array('loans_to_forgive',$comment, $validation_code, $loanid)); 
		
	}
	function checkValidationCode($loanid, $id){
		global $db; 
		$q="SELECT validation_code FROM ! WHERE loanid=? AND borrowerid=?";
		$result=$db->getOne($q, array('loans_to_forgive',$loanid, $id));
		return $result;
	}
	function getLoansForForgiveReminder(){
		global $db;
		$q= "SELECT * FROM ! as lf join ! as la on lf.loanid=la.loanid where la.active = ?";
		$result= $db->getAll($q, array('loans_to_forgive', 'loanapplic', LOAN_ACTIVE));
		return $result;
	}
	function getLendersForForgive($loanid, $deniedlender){
		global $db;
		if(empty($deniedlender)) {
			$deniedlender = 0;
		}
		$q="SELECT distinct(userid), Email , FirstName, LastName FROM ! as l, ! as lb  WHERE  lb.loanid =? AND lb.active=? AND l.userid=lb.lenderid  AND userid NOT IN ( SELECT lender_id FROM ! WHERE loan_id =? ) AND userid NOT IN ( ".$deniedlender." )";
		$r1= $db->getAll($q, array('lenders', 'loanbids' , $loanid, 1, 'forgiven_loans',$loanid));
		return $r1;
	}
	function updateForgiveReminder($reminder_sent, $loanid){
		global $db;
		$q= "UPDATE ! SET reminder_sent=?, time = ? WHERE loanid= ?";
		$r= $db->query($q, array('loans_to_forgive', $reminder_sent, time(), $loanid));
	}
	function grantAccessCo($userid){
		global $db;
		$time=time();
		$q1="SELECT count(user_id) FROM  ! WHERE user_id=?";
		$count=$db->getOne($q1, array('community_organizers', $userid));
		if($count==0){
			$country = $this->getUserCityCountry($userid);
			$q="INSERT INTO ! (user_id, country, grant_date, status) VALUES (?, ?, ?, ?)";
			$res = $db->query($q, array('community_organizers',$userid, $country['Country'], $time, 1));
		}else{
			$q2="update ! set status=? where user_id=?";
			$res = $db->query($q2, array('community_organizers',1,$userid));
		}
		if($res===DB_OK)
			return 1;
			return 0;
	}
	function isBorrowerAlreadyAccess($userid)
	{
		global $db;
		$q="SELECT status FROM  ! WHERE user_id=?";
		$status=$db->getOne($q, array('community_organizers', $userid));
		if($status ==0)
			return false;
		else
			return true;
	}
	function getAllCoOrgBorrowers($countrycode){
		global $db;
		$where = '';
		if($countrycode!='AA'){
			$where = 'where Country='."'".$countrycode."'";
		}

		$q="SELECT * from ! $where and status=?";
		$res= $db->getAll($q, array('community_organizers', 1));

		return $res;
	}
	function grantRemoveCo($userid){ 
		global $db;
		$q= "update ! set status=? WHERE user_id=?";
		$res=$db->query($q, array('community_organizers',0, $userid)); 
		if($res===DB_OK)
			return 1;
		return 0;
	}
	function getDefaultRepayment_Instructions() {
		global $db;
		$q="SELECT description,id FROM ! WHERE country_code='ALL' AND active=? order by ID desc";
		$result=$db->getRow($q, array('repayment_instructions', 1));
		return $result;
	}
	function getPendingEmailBorrower() {
		global $db;
		$q= 'select brwr.userid, FirstName,LastName,City,Country,Email from ! as brwr join ! as u on brwr.userid=u.userid where u.emailVerified =? AND iscomplete_later = ?';
		$pending = $db->getALL($q, array('borrowers', 'users', 0, 0));
		return $pending;
	}
	function getlenderdenied($loanid) {
		global $db;
		$q1= "SELECT lender_denied FROM ! where loanid=?";
		$result=$db->getOne($q1, array('loans_to_forgive',$loanid));
		return $result;
	}
function review_borrower($is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name, $borrowerid) {
		global $db, $session;
		$q1= "SELECT count(*) FROM ! where borrower_id=?";
		$result=$db->getOne($q1, array('borrower_review',$borrowerid));
		if($result > 0) {
			$q = "UPDATE ! SET is_photo_clear=?, is_desc_clear =?, is_addr_locatable=?, is_number_provided=?, is_nat_id_uploaded=?, is_rec_form_uploaded=?, is_rec_form_offcr_name = ?, modified = ?, modified_by = ? WHERE borrower_id = ?";
			$res = $db->query($q, array('borrower_review', $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name, time(), $session->userid, $borrowerid));
			
		}else { 
			$q="INSERT INTO ! (borrower_id,is_photo_clear,is_desc_clear, is_addr_locatable,is_number_provided,is_nat_id_uploaded,is_rec_form_uploaded,is_rec_form_offcr_name,created	,created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$res = $db->query($q, array('borrower_review',$borrowerid, $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name,time(), $session->userid)); 
			
		}
		$review = $this->is_borrowerReviewComplete($borrowerid);
		if(empty($review)) {
			$this->updateReviewBorrower($borrowerid, -1);
			return 1;
		}else{
			$this->updateReviewBorrower($borrowerid, 0);
			return 0;
		}
		
	}
	function is_borrowerReviewComplete($borrowerid) {
		global $db;
		$q1= "SELECT * FROM ! where borrower_id=? AND (is_photo_clear=? || is_desc_clear = ? || is_addr_locatable = ? || is_number_provided = ? || is_nat_id_uploaded = ? || is_rec_form_uploaded = ? || is_rec_form_offcr_name = ?)";
		$result=$db->getRow($q1, array('borrower_review',$borrowerid, '0','0','0','0','0','0','0'));
		return $result;
	}
	// 18-Jan-2013 Anupam if all review in positive we set -1 in active column in borrower table to indicate that review has been completed for this borrower, if any of review is in negative we set 0 for that
	function updateReviewBorrower($borrowerid, $status) {
		global $db;
		if($status==0){
			$q = 'update ! set Assigned_status = ?, Assigned_to=? where  userid = ? ';
			$r = $db->query($q, array('borrowers', $status, 0, $borrowerid));
			if($r=== DB_OK) {
				return 1;
			}
		}else{
			$q = 'update ! set Assigned_status = ? where  userid = ? ';
			$r = $db->query($q, array('borrowers', $status, $borrowerid));
			if($r=== DB_OK) {
				return 1;
			}
		}
		return 0;
	}
	// 29-Jan-2013 Anupam added this function for developer use only.where we can remove wrong repyments posted by repayment_actual id except if loan did not forgiven ,rescheduled and no other repayments added after wrong one.
	function remove_payment($repayment_id) {
		global $db;
		$q = 'select * from ! where id = ?';
		$repay_actual = $db->getRow($q, array('repaymentschedule_actual', $repayment_id));
		$paidinstallment = $repay_actual['paidamt'];
		if(!empty($repay_actual) && $repay_actual['loanid']>0) {
			$t="SELECT count(*) from ! where loan_id = ? AND borrower_id=?";
			$forgiven=$db->getOne($t,array('forgiven_loans',$repay_actual['loanid'],$repay_actual['userid']));
			$rescheduled = $this->getRescheduleDataByLoanId($repay_actual['loanid']);
				if(empty($rescheduled) && empty($forgiven)) {

					$q = "delete from ! where id = ? limit 1";
					$res1 = $db->query($q, array('repaymentschedule_actual', $repayment_id));
					if(!empty($res1)) {
						$q = 'select * from ! where loanid = ? ORDER BY id DESC';
						$repayments = $db->getAll($q, array('repaymentschedule', $repay_actual['loanid'])); 
						$cont = 1;
						foreach($repayments as $repayment) {
							if($repayment['paidamt']< $paidinstallment && $paidinstallment >0) {
								$q = "UPDATE ! SET paiddate = ?, paidamt=? WHERE id = ?";
								$res = $db->query($q, array('repaymentschedule', null, null, $repayment['id']));
								$paidinstallment = bcsub($paidinstallment,$repayment['paidamt'], 6);
								if(!$res) {
									$cont = 0;
									break;
								}
								if($paidinstallment<=1) {
									$paidinstallment = null;
								}
								
							}else {
								$amtToupdate = bcsub($repayment['paidamt'], $paidinstallment, 6);
								$dateToupdate = null;
								if($amtToupdate > 0) {
									$q = 'select paiddate from ! where id = ?';
									$dateToupdate = $db->getOne($q,array('repaymentschedule', $repayment['id']-1)); 
								}else {
									$amtToupdate = null;
								}
								$q = "UPDATE ! SET paiddate = ?, paidamt=? WHERE id = ?";
								$res = $db->query($q, array('repaymentschedule', $dateToupdate, $amtToupdate, $repayment['id']));
								if(!$res) {
									$cont = 0;
								}
								break;

							}
						}

					}
					if($cont==1) {
						$loanstatus = $this->getLoanStatus($repay_actual['userid']);
						if($loanstatus==LOAN_REPAID && $repay_actual['paidamt'] > 1) {
							$q2 = "UPDATE ! SET ActiveLoan = ? WHERE userid = ?";
							$res2 = $db->query($q2, array('borrowers', LOAN_ACTIVE));
							$q2 = "UPDATE ! SET active = ? WHERE borrowerid = ? AND loanid = ?";
							$res2 = $db->query($q2, array('loanapplic', LOAN_ACTIVE, $repay_actual['userid'], $repay_actual['loanid']));
						}
						$q = 'select id from ! where amount = ? AND txn_type = ? AND TrDate = ? AND loanid = ? ORDER BY id DESC';
						$install_trid = $db->getOne($q,array( 'transactions',$repay_actual['paidamt'], LOAN_BACK ,$repay_actual['paiddate'], $repay_actual['loanid']));
						$q1 = 'select id from ! where  txn_type = ? AND id < ? ORDER BY id DESC';
						$Feetrid = $db->getOne($q1, array('transactions', FEE , $install_trid));
						$res1 = 0;
						if($install_trid > 0 && $Feetrid > 0) {
							$q = "delete from ! where id <= ? AND id >= ? AND loanid = ?";
							$res1 = $db->query($q, array('transactions', $install_trid, $Feetrid, $repay_actual['loanid']));
						}
						if($res1!==DB_OK) {
							return 5;
						}else {
							return 1;
						}
					}else {
						return 2;
					}
				}
				else {
					return 3;
				}
		}else {
			return 4;
		}
	}

	function add_verify_borrower($partid,$complete_later, $identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid){
		global $db, $session;
		$created= time();
		$modified= time();
		$is_eligible_ByAdmin='';
		if($session->userlevel== ADMIN_LEVEL){
			$is_eligible_ByAdmin=2;
		}
		if($identity_verify=='-1'){
			$identity_verify= $identity_verify_other;
		}
		if($participate_verification=='-1'){
			$participate_verification= $participate_verification_other;
		}
		if($app_know_zidisha=='-1'){
			$app_know_zidisha= $app_know_zidisha_other;
		}
		if($how_contact=='-1'){
			$how_contact= $how_contact_other;
		}
		if($recomnd_addr_locatable=='-1'){
			$recomnd_addr_locatable= $recomnd_addr_locatable_other;
		}
		if($commLead_know_applicant=='-1'){
			$commLead_know_applicant= $commLead_know_applicant_other;
		}
		if($commLead_recomnd_sign=='-1'){
			$commLead_recomnd_sign= $commLead_recomnd_sign_other;
		}
		if($commLead_mediate=='-1'){
			$commLead_mediate= $commLead_mediate_other;
		}
		$q1= "select * from ! where borrower_id=?";
		$res= $db->getRow($q1, array('borrower_verification', $borrowerid));
		if(empty($res)){
			$q1= "insert into ! (borrower_id, complete_later, is_identity_verify, is_participate_verification, is_app_know_zidisha, is_how_contact, is_recomnd_addr_locatable, is_commLead_know_applicant, is_commLead_recomnd_sign, is_commLead_mediate, is_eligible, is_eligible_ByAdmin, additional_comments, created, created_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$result1= $db->query($q1, array('borrower_verification', $borrowerid, $complete_later, $identity_verify, $participate_verification, $app_know_zidisha, $how_contact, $recomnd_addr_locatable, $commLead_know_applicant, $commLead_recomnd_sign, $commLead_mediate, $eligible, $is_eligible_ByAdmin, $additional_comments, $created, $partid));

			$res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, 0, '', '', $complete_later); 
			
			if($res1)
				return 1;
			else
				return 0;
		}
		else{ 
				$q1= "update ! set complete_later=?, is_identity_verify=?, is_participate_verification=?, is_app_know_zidisha=?, is_how_contact=?, is_recomnd_addr_locatable=?, is_commLead_know_applicant=?, is_commLead_recomnd_sign=?, is_commLead_mediate=?, is_eligible=?, is_eligible_ByAdmin, additional_comments=?, modified=?, modified_by=? where borrower_id=?";
				$result1= $db->query($q1, array('borrower_verification', $complete_later, $identity_verify, $participate_verification, $app_know_zidisha, $how_contact, $recomnd_addr_locatable, $commLead_know_applicant, $commLead_recomnd_sign, $commLead_mediate, $eligible, $is_eligible_ByAdmin, $additional_comments, $modified, $partid, $borrowerid));
				
				$q= "select id from ! where userid=? and loneid=? LIMIT 1";
				$cid= $db->getOne($q, array('comments', $borrowerid, 0)); 
				$res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, $cid, '', '', $complete_later); 
				if($res1)
					return 1;
				else
					return 0;
		}

	}
	function get_bverification_detail($userid){
		global $db;
		$q= "select * from ! where borrower_id=?";
		$result= $db->getRow($q, array('borrower_verification', $userid));
		return $result;
	}

	function get_loan_amount($userid){
		global $db;
		$q= "select amount from ! where userid=?";
		$result= $db->getOne($q, array('comments', $userid));
		return $result;
	}
	function getAssignedStatus($brwrid) {
		global $db;
		$q= "select Assigned_status from ! where userid=?";
		$result= $db->getOne($q, array('borrowers', $brwrid));
		return $result;
	}
	function getDeclinedBorrowers(){
		global $db;
		$q="SELECT b.*, u.regdate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as name, onb.name as postedby, bext.rec_form_offcr_name FROM ! as b join ! as u on b.userid= u.userid left join ! as onb on b.borrower_behalf_id= onb.id join ! as bext on b.userid = bext.userid WHERE b.active=? AND b.Assigned_status=? order by name";
		$result = $db->getAll($q, array('borrowers', 'users','on_borrower_behalf','borrowers_extn',0,2));
		return $result;
	}
	function setbrwrdetailByadmin($name, $value, $brwrid)
	{
		global $db,$session;
		if($session->userlevel == ADMIN_LEVEL) {
			logger('setbrwrdetailByadmin '.$name." ".$value." ".$brwrid." ".$session->userid);
			$q="UPDATE ! SET $name=? where userid=?";
			$res = $db->query($q, array('borrowers', $value, $brwrid));
			return $res;
		}

		return 0;
	}
	function setbrwrExtndetailByadmin($name, $value, $brwrid)
	{
		global $db,$session;
		if($session->userlevel == ADMIN_LEVEL) {
		logger('setbrwrExtndetailByadmin '.$name." ".$value." ".$brwrid." ".$session->userid);

			$q="UPDATE ! SET $name=? where userid=?";
			$res = $db->query($q, array('borrowers_extn', $value, $brwrid));
			return $res;
		}
		return 0;
	}
	function getBAssignStatus($borrowerid){
		global $db;
		$q= "select Assigned_to, Assigned_date, Assigned_status from ! where userid=?";
		$result= $db->getRow($q, array('borrowers', $borrowerid));
		return $result;
	}
	function getBorrowerReviewDetail($borrowerid){
		global $db;
		$q= "select * from ! where borrower_id=?";
		$result= $db->getRow($q, array('borrower_review', $borrowerid));
		return $result;
	}
	function BReviewComplt($userid){
		global $db;
		$review_exist= $this->getBorrowerReviewDetail($userid);
		if(!empty($review_exist)){
			$q1="select * from ! where borrower_id=? AND (is_photo_clear=? || is_desc_clear = ? || is_addr_locatable = ? || is_number_provided = ? || is_nat_id_uploaded = ? || is_rec_form_uploaded = ? || is_rec_form_offcr_name = ?)";
			$res= $db->getRow($q1, array('borrower_review',$userid, '0','0','0','0','0','0','0'));
			if(empty($res))
				return True;
			else
				return false;
		}
		else
			return false;
	}
	function co_org_note($id, $note){
		global $db;
		$q1 = "UPDATE ! set note = ? where user_id = ?";
		$res1 = $db->query($q1, array('community_organizers', $note, $id )); 
		return $res1;
			
	}
	function IntervalMonths($time1,$time2){
		 $interval =($time2 - $time1)/(3600*24*30); // to converting into months we devided by 30 as round calculation
		// returns numberofmonths
		return  floor($interval) ;

	}
	function getTotalInstalToday($loanid, $userid){
		global $db;
		$q1="SELECT COUNT( id ) FROM  ! WHERE duedate <= ? AND loanid =? AND userid =? AND amount >?";
		$res=$db->getOne($q1, array('repaymentschedule', time(), $loanid, $userid, 0));
		return $res;
	}
	function getLastRepaidAmount($brwrid, $checkOnTime=false) {
		global $db;
		$lid=0;
		if($checkOnTime) {
			$q="SELECT loanid, active FROM ! WHERE borrowerid=? AND (active=3 OR active=5) order by loanid desc";
			$loanids=$db->getAll($q,array('loanapplic',$brwrid));
			foreach($loanids as $row) {
				$ontime = $this->isRepaidOntime($brwrid, $row['loanid']);
				if($ontime && $row['active']!=LOAN_DEFAULTED) {
					$lid= $row['loanid'];
					break;
				} 
			}
		}else{
			$lid = $this->getLastRepaidloanId($brwrid);
		} 
		$rate = $this->getCurrentRate($brwrid);
		$val=$this->getAdminSetting('firstLoanValue');
		$firstloanamount=convertToNative($val, $rate);
		if($lid==0){
			return $firstloanamount;
		}else{
			$q1="SELECT AmountGot FROM  ! WHERE loanid = ?";
			$result=$db->getOne($q1, array('loanapplic', $lid ));
			if($firstloanamount>$result)
				return $firstloanamount;
			else
				return $result;
		}
	}

	function getSettingsValue($name){
		global $db,$session;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT value FROM ! where name=?";
		$result=$db->getOne($q, array('settings', $name));
		if(empty($result)){
			return 0;
		}
		else
		{
			return $result;
		}
	}

	function verify_borrower_ByAdmin($partid, $is_eligible_ByAdmin, $borrowerid){
		global $db,$session;
		traceCalls(__METHOD__, __LINE__);
		$created= time();
		$modified= time();
		$additional_comments='';
		$q1= "select * from ! where borrower_id=?";
		$res= $db->getRow($q1, array('borrower_verification', $borrowerid));
		if(empty($res)){
				$q1= "insert into ! (borrower_id, complete_later, is_identity_verify, is_participate_verification, is_app_know_zidisha, is_how_contact, is_recomnd_addr_locatable, is_commLead_know_applicant, is_commLead_recomnd_sign, is_commLead_mediate, is_eligible, is_eligible_ByAdmin, created, created_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$result1= $db->query($q1, array('borrower_verification', $borrowerid, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1,$is_eligible_ByAdmin, $created, $partid));
				$res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, 0, '', '', 0); 
				if($res1)
					return 1;
				else
					return 0;
			}
		else{ 
				$q1= "update ! set complete_later=?, is_identity_verify=?, is_participate_verification=?, is_app_know_zidisha=?, is_how_contact=?, is_recomnd_addr_locatable=?, is_commLead_know_applicant=?, is_commLead_recomnd_sign=?, is_commLead_mediate=?, is_eligible=?, is_eligible_ByPartner=?, modified=?, modified_by=? where borrower_id=?";
				$result1= $db->query($q1, array('borrower_verification', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, $is_eligible_ByPartner, $modified, $partid, $borrowerid));

				$q= "select id from ! where userid=? and loneid=? LIMIT 1";
				$cid= $db->getOne($q, array('comments', $borrowerid, 0)); 
				$res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, $cid, '', '', 0); 
				if($res1)
					return 1;
				else
					return 0;
			}
	}
	/* -------------------Admin Section End----------------------- */


	/* -------------------Borrower Section Start----------------------- */

		function addBorrower($uname, $namea, $nameb, $pass1, $post, $city, $country, $email, $mobile, $income, $about, $bizdesc, $bnationid, $language, $community_name_no,$documents,$repaidPast, $debtFree, $share_update, $completeLater, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member,$volunteer_mentor)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$time=time();
		$salt = $this->makeSalt();
		$pass= $this->makePassword($pass1, $salt);
		$currency=$this->getCurrencyIdByCountryCode($country);
		$q="INSERT INTO ! (username, password, salt, userlevel, regdate,  lang,emailVerified) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$res = $db->query($q, array('users', $uname, $pass, $salt, BORROWER_LEVEL, $time, $language,0));
		if($res === DB_OK)
		{	$onbehalfId = 0;
			if($onbehalf) {
					$query="INSERT INTO ! (name, contact_no, email, town) VALUES (?, ?, ?, ?)";
					$res = $db->query($query, array('on_borrower_behalf', $behalf_name, $behalf_number, $behalf_email, $behalf_town));
					$onbehalfId = mysql_insert_id();
			}
			$q="SELECT userid FROM ! WHERE username=?";
			$userid=$db->getOne($q, array('users', $uname));
			$q="INSERT INTO ! (userid, FirstName, LastName, PAddress, City, Country, TelMobile, Email, AnnualIncome, About, BizDesc, Active, activeloan, currency, nationId, communityNameNo, borrower_behalf_id, islastrepaid, isdebtfree, share_update, iscomplete_later, Created,completed_on, LastModified,home_location, refer_member_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?)";
			$res1=$db->query($q, array('borrowers', $userid, $namea, $nameb, $post, $city, $country, $mobile, $email, $income, $about, $bizdesc, 0, NO_LOAN, $currency, $bnationid, $community_name_no, $onbehalfId, $repaidPast, $debtFree, $share_update, $completeLater, $time ,$time ,$time, $home_no, $refer_member)); 
			if($res1=== DB_OK)
			{	
				$q="INSERT INTO ! (userid, family_member1, family_member2, family_member3, neighbor1, neighbor2, neighbor3, rec_form_offcr_name, rec_form_offcr_num, created, mentor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res2 = $db->query($q, array('borrowers_extn', $userid, $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$rec_form_offcr_name, $rec_form_offcr_num, time(), $volunteer_mentor)); 
				include_once("indexer.php");
				updateIndex1($userid);
				$this->setTelephoneNo($userid, $mobile);
				return 0;//successful insert
			}
			else{
				return 1;//unsuccessful insert
			}
		}
		else{
			return 3;//cannot create user
		}
	}
	/**
	* updateBorrower - update the given (username, password, email)
	* info into the database. Appropriate user level is set.
	* Returns true on success, false otherwise.
	*/
		function updateBorrower($uname, $namea, $nameb, $pass1, $post, $city, $country, $email, $mobile, $income, $about, $bizdesc, $id, $bnationid,  $language, $community_name_no, $repaidPast, $debtFree,$share_update,$onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town,$borrower_behalf_id, $completeLater, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3, $home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member,  $volunteer_mentor)
	{
		global $db, $session;
		traceCalls(__METHOD__, __LINE__);
		$time=time();
		$res = DB_OK;
		$old_status = $this->getBorrowerActive($id);
		$currency=$this->getCurrencyIdByCountryCode($country);
		$q="SELECT TelMobile FROM ! WHERE userid=?";
		$oldTelMobile=$db->getOne($q,array('borrowers',$id));
		if(!empty($pass1))
		{
			$salt = $this->makeSalt();
			$pass= $this->makePassword($pass1, $salt);
			$q = "UPDATE ! SET password = ?, salt=?, lang= ? WHERE userid = ?";
			$res = $db->query($q, array('users', $pass, $salt, $language, $id));
		}
		else
		{
			$q = "UPDATE ! SET lang= ? WHERE userid = ?";
			$r=$db->query($q, array('users', $language, $id ));
		}
		if($res == DB_OK)
		{	
			if($onbehalf) {
				if($borrower_behalf_id == '0') {
					$query = "INSERT INTO ! (name, contact_no, email, town) VALUES (?, ?, ?, ?)";
					$res = $db->query($query, array('on_borrower_behalf', $behalf_name, $behalf_number, $behalf_email, $behalf_town));
					$onbehalfId = mysql_insert_id();
				} else {
					$q = "UPDATE ! SET name= ?,contact_no = ?,email = ?,town = ? WHERE id = ?";
					$r=$db->query($q, array('on_borrower_behalf', $behalf_name, $behalf_number, $behalf_email, $behalf_town, $borrower_behalf_id));
					$onbehalfId = $borrower_behalf_id;
				}
			}else{
				$onbehalfId = 0;
			}
			$completedon = $time;
			$q="SELECT iscomplete_later, completed_on FROM ! WHERE userid=?";
			$oldstatus = $db->getRow($q,array('borrowers',$id));
			if($oldstatus['iscomplete_later']==$completeLater) {
				$completedon = $oldstatus['completed_on'];
			}
			if(empty($bnationid)) {
				$t = "select nationId from ! where userid = ?";
				$bnationid = $db->getOne($t, array('borrowers', $id));
			}
			$t = "select Active from ! where userid = ?";
			$isActive = $db->getOne($t, array('borrowers', $id));
			if(!$isActive) {
				$q = "UPDATE ! SET FirstName=?, LastName=?, PAddress=? ,City=?, Country=?, TelMobile=?, Email=?, AnnualIncome= ?, About=?, BizDesc=?, currency=?, nationId=?, communityNameNo=?, islastrepaid=?,isdebtfree=?,iscomplete_later=?,borrower_behalf_id=?,share_update=?, completed_on = ?, LastModified=?, home_location=?, refer_member_name=? WHERE userid = ?";
				$r=$db->query($q, array('borrowers', $namea, $nameb, $post, $city, $country, $mobile, $email, $income, $about, $bizdesc, $currency, $bnationid, $community_name_no, $repaidPast, $debtFree, $completeLater,$onbehalfId, $share_update, $completedon, $time, $home_no, $refer_member, $id));
			}else {
				
				$q = "UPDATE ! SET  About=?, BizDesc=?,borrower_behalf_id=?, LastModified=? WHERE userid = ?";
				$r=$db->query($q, array('borrowers', $about, $bizdesc, $onbehalfId, $time, $id));
			}
			if($r=== DB_OK)
			{
				if(!$isActive) {
					$q = "UPDATE ! SET family_member1=?,family_member2=?,family_member3=?, neighbor1=?, neighbor2=?, neighbor3=?, rec_form_offcr_name = ?, rec_form_offcr_num = ?, modified=?, mentor_id=? WHERE userid = ?";
					$r=$db->query($q, array('borrowers_extn', $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3, $rec_form_offcr_name, $rec_form_offcr_num, time(),$volunteer_mentor, $id));
				}else {
					$q = "UPDATE ! SET rec_form_offcr_name = ?, rec_form_offcr_num = ?, modified=?, mentor_id=? WHERE userid = ?";
					$r=$db->query($q, array('borrowers_extn', $rec_form_offcr_name, $rec_form_offcr_num, time(),$volunteer_mentor, $id));
				}
				include_once("indexer.php");
				updateIndex1($id);
				$iscompltlater = $this->getiscompleteLater($id);
				$updated_status = $this->getBorrowerActive($id);
				if($oldTelMobile !=$mobile) {
					$this->setTelephoneNo($id, $mobile);
				}
				// Anupam 28-Feb-2013 we are no more sending alerts for mobile change for inactive borrowers and we have restrict borrower to change their mobile for active borrower.So over all we do not need to send alert.
				/*if($oldTelMobile !=$mobile && $updated_status!=$old_status)
				{	
					if(!$iscompltlater) {
						$session->sendMobileChangeMail($id);
					}
				}*/
				return 0;//successful insert
			}
			else{
				return 1;//unsuccessful insert
			}
		}
		else{
			return 1;//cannot create user
		}
	}
	function updateBorrowerDocument($id, $field, $name)
	{
		global $db, $session;
		$q = "UPDATE ! SET ".$field."=? WHERE userid = ?";
		$r=$db->query($q, array('borrowers', $name, $id));
		if($r=== DB_OK) {
			return 1;
		}
		return 0;
	}
	function getBorrowerFirstLoan($borrowerid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT loanid FROM ! WHERE borrowerid=? AND (active=3 OR active=5) ";
		$result=$db->getOne($q,array('loanapplic',$borrowerid));
		$lid=$result['loanid'];
		if($lid)
		{
			return 1;//IF 1 means it has loanstatus REPAID OR DEFAULT
		}
		else
		{
			return 0;//IF 0 means it has never loanstatus REPAID OR DEFAULT
		}
	}
	function getLoanCount($borrowerid, $checkOnTime=false)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT count(loanid) FROM ! WHERE borrowerid=? AND (active=3 OR active=5) ";
		$count=$db->getOne($q,array('loanapplic',$borrowerid));
		$excludeLoanIds=array();
		if($checkOnTime) {
			$q="SELECT loanid, active FROM ! WHERE borrowerid=? AND (active=3 OR active=5) order by loanid desc";
			$loanids=$db->getAll($q,array('loanapplic',$borrowerid));
			foreach($loanids as $row) {
				$ontime = $this->isRepaidOntime($borrowerid, $row['loanid']);
				if($ontime && $row['active']!=LOAN_DEFAULTED) {
					break;
				} else {
					//$count--;
					// 25-Feb-2013 commented "$count--" Anupam since we have a saperate function to check if all loan is repaid on time or not.
					$excludeLoanIds[]=$row['loanid'];
				}
			}
		}
		if($checkOnTime) {
			return array($count, $excludeLoanIds);
		}
		return $count;
	}
	function getReg_CurrencyAmount($borrowerid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT currency FROM ! WHERE userid=?";
		$result=$db->getOne($q,array('borrowers',$borrowerid));
		$qq="SELECT currency,Amount FROM ! WHERE currency_id=? ";
		$result1=$db->getAll($qq,array('registration_fee',$result));
		return $result1;
	}
	function getBorrowerById($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT * FROM ! as brw LEFT JOIN ! as bext on brw.userid=bext.userid WHERE brw.userid=?";
		$result=$db->getRow($q, array('borrowers','borrowers_extn',  $userid));
		if(empty($result)){
			return false;
		}
		else{
			return $result;
		}
	}
	function getBorrowerActive($userid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT active FROM ! WHERE userid=?";
		$result=$db->getOne($q, array('borrowers', $userid));
		return $result;
	}
	function getLoanStatus($uid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT activeloan FROM ! WHERE userid=?";
		$result = $db->getOne($q, array('borrowers', $uid));
		return $result;
	}
	function loanApplication($borrowerid, $amount, $interest, $period, $gperiod, $loanuse, $tnc,$loan_installmentDate)
	{
		global $db;
		$time=time();
		$webfee=$this->getAdminSetting('fee');//website fee rate
		traceCalls(__METHOD__, __LINE__);
		$damt = round($amount/$this->getCurrentRate($borrowerid));
		$q="INSERT INTO ! (borrowerid, Amount, interest, period, grace, loanuse, active, applydate, tnc , AmountGot, WebFee, reqdamt, installment_day) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?)";
		$result=$db->query($q,array('loanapplic', $borrowerid, $amount, $interest, $period, $gperiod, $loanuse, LOAN_OPEN, $time, $tnc, $amount , $webfee, $damt, $loan_installmentDate) );
		$q="SELECT loanid from ! where borrowerid = ? and Amount = ? and interest =? and period =? and grace =?  and active= ? and applydate = ?";
		$loanid=$db->getOne($q, array('loanapplic', $borrowerid, $amount, $interest, $period, $gperiod,  LOAN_OPEN, $time ));

		$q1="UPDATE ! SET activeloan=?, activeloanid = ? WHERE userid=?";
		$db->query($q1, array('borrowers', LOAN_OPEN, $loanid, $borrowerid));
		if($result===DB_OK)
		{
			$this->setLoanStage($loanid, $borrowerid, LOAN_OPEN, $time) ;
			include_once("indexer.php");
			updateIndex2($loanid);
			return $loanid;
		}
		return 0 ;
	}
	function getLoanDetails($loanid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT l.*,b.borrower_behalf_id,b.iscomplete_later, b.FirstName, b.LastName FROM ! l join ! as b on b.userid= l.borrowerid WHERE loanid=? AND adminDelete = ?";
		$row= $db->getRow($q , array('loanapplic', 'borrowers', $loanid,0));
		return $row;
	}
	function getBorrowerPartner($userid)
	{
		global $db;
		$q="SELECT p.userid, p.name, p.email, p.postcomment, p.website FROM ! as p WHERE p.userid=(SELECT partnerid FROM borrowers WHERE userid=?)";
		$result=$db->getRow($q, array('partners', $userid));
		return $result;
	}
	function isMyPartner($userid, $partnerid)
	{
		global $db;
		$q="SELECT count(partnerid) FROM ! WHERE userid=? and partnerid=?";
		$count=$db->getOne($q, array('borrowers', $userid, $partnerid));
		if($count) {
			return true;
		}
		return false;
	}
	function getBorrowersEmail()
	{
		global $db;
		$q="select Email from !";
		$resultBorrower=$db->getAll($q,array('borrowers'));
		return $resultBorrower;
	}

	function getExRateByDate($date, $currency)
	{
		global $db;
		$q="SELECT rate FROM excrate WHERE currency = $currency AND start <= $date AND (stop >= $date || stop IS NULL || stop=0) order by start";
		$result=$db->getOne($q);
		return $result;
	}
	function getExRateById($date, $id)
	{
		global $db;
		$p = "select currency from ! where userid = ?";
		$currency=$db->getOne($p, array('borrowers',$id));
		$result = $this->getExRateByDate($date,$currency);
		return $result;
	}
	function getBorrowerId($loanid)
	{
		global $db;
		$q="SELECT borrowerid FROM ! WHERE loanid = ?";
		$result=$db->getOne($q, array('loanapplic', $loanid));
		return $result;
	}
	function getCurrentRate($userid)
	{
		global $db;
		$q = "select currency from ! where userid = ?";
		$result1=$db->getOne($q, array('borrowers',$userid));
		$q="SELECT rate FROM excrate WHERE start=(SELECT max(start) FROM excrate where currency = ?)";
		$result=$db->getOne($q , array($result1));
		return $result;
	}
	function getOpenBorrowers($start=0,$rowcount=0,$type=1,$sort=1,$searchLoan='', $randomLoans='')
	{
		global $db;
		/* NOTE: Important      please donot modify following queries if it is required then check indexer.php in library for the same queries*/
		$limit = rand(400000,1000000);
		$oftype= "loanapplic.active=".LOAN_OPEN." AND "  ;
		if($type==2)
			$oftype= "(loanapplic.active=".LOAN_FUNDED." OR loanapplic.active=".LOAN_ACTIVE." ) AND ";
		if($type==3)
			$oftype="(loanapplic.active=".LOAN_REPAID." OR loanapplic.active=".LOAN_DEFAULTED." OR loanapplic.active=".LOAN_CANCELED.") AND ";

		if(trim($searchLoan)=='')
		{
			$q="SELECT * FROM ".TBL_BORROWER.", ".TBL_LOANAPPLIC." WHERE ".$oftype." borrowers.userid=loanapplic.borrowerid AND loanapplic.adminDelete = ? ORDER BY RAND()";

			$result=$db->getAll($q, array(0));
		}
		else
		{
			include_once('indexer.php');
			//createIndex();
			$query = Zend_Search_Lucene_Search_QueryParser::parse($searchLoan);
			$index1 = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index1");
			$indexResults1 = $index1->find($query);
			$index2 = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index2");
			$indexResults2 = $index2->find($query);
			$index3 = Zend_Search_Lucene::open(FULL_PATH."Zend/app/tmp/cache/index3");
			$indexResults3 = $index3->find($query);
			if(!empty($indexResults1) || !empty($indexResults2) || !empty($indexResults3))
			{
				$count=0;
				$ids="";
				if(!empty($indexResults1))
				{
					foreach ($indexResults1 as $res1){
						if($count==0)
							$ids=$res1->document_id;
						else
							$ids.=",".$res1->document_id;
						$count++;
					}
				}
				if(!empty($indexResults2))
				{
					foreach ($indexResults2 as $res2){
						if($count==0)
							$ids=$res2->document_id;
						else
							$ids.=",".$res2->document_id;
						$count++;
					}
				}
				if(!empty($indexResults3))
				{
					foreach ($indexResults3 as $res3){
						if($count==0)
							$ids=$res3->document_id;
						else
							$ids.=",".$res3->document_id;
						$count++;
					}
				}
				$q="SELECT * FROM ! left outer join ! on borrowers.userid=loanapplic.borrowerid left outer join ! on borrowers.userid=zi_comment.receiverid WHERE ".$oftype." loanapplic.adminDelete = ?  AND borrowers.userid IN (".$ids.") GROUP BY zi_comment.receiverid ORDER BY RAND()";

				$result=$db->getAll($q, array(TBL_BORROWER,TBL_LOANAPPLIC,'zi_comment',0));
			}
			else
				return false;
		}
		if(empty($result)){
			return false;
		}
		else
		{
			if($type==1)
			{
				if($sort==2)
				{
					$i=0;
					foreach($result as $row)
					{
						$totBid=$this->getTotalBid($row['userid'],$row['loanid']);
						if($row['reqdamt'] > $totBid)
							$stilneed=$row['reqdamt']-$totBid;
						else
							$stilneed=0;
						$result[$i]['amtStilNeed']=$stilneed;
						$still[$i]=$stilneed;
						$i++;
					}
					array_multisort($still, SORT_ASC, $result);
				}
				else if($sort==3)
				{
					foreach($result as $key=>$row)
						$applydate[$key]=$row['applydate'];
					array_multisort($applydate, SORT_ASC, $result);
				}
				else if($sort==4)
				{
					$i=0;
					foreach($result as $row)
					{
						$totBid=$this->getTotalBid($row['userid'],$row['loanid']);
						if($totBid >= $row['reqdamt'])
							$int = $this->getAvgBidInterest($row['userid'],$row['loanid']);
						else
							$int = $row['interest'] - $row['WebFee'];
						$result[$i]['intOffer']=$int;
						$intOffer[$i]=$int;
						$i++;
					}
					array_multisort($intOffer, SORT_DESC, $result);
				}
				else if($sort==5)
				{
					$i=0;
					foreach($result as $row)
					{
						$report=$this->loanReport($row['userid']);
						$f=$report['feedback'];
						$tf=$report['Totalfeedback'];
						$result[$i]['feedback']=$tf*$f;
						$feedback[$i]=$f;
						$totalfeedback[$i]=$tf;
						$i++;
					}
					array_multisort($feedback, SORT_DESC, $totalfeedback, SORT_DESC, $result);
				}
				else if($sort==6)
				{
					foreach($result as $key=>$row)
						$applydate[$key]=$row['applydate'];
					array_multisort($applydate, SORT_DESC, $result);
				}
			}
			else if($type==2)
			{
				if($sort==2)
				{
					$i=0;
					foreach($result as $row)
					{
						$res= $this->getTotalPayment($row['userid'], $row['loanid']);
						if($res['amttotal']==0) /*  this case is when loan is funded and schedule is not generaetd */
							$repaidPercent= 0;
						else
							$repaidPercent= $res['paidtotal']/$res['amttotal']*100;
						$result[$i]['repaidPercent']=$repaidPercent;
						$repaid[$i]=$repaidPercent;
						$i++;
					}
					array_multisort($repaid, SORT_DESC, $result);
				}
				else if($sort==3)
				{
					$i=0;
					foreach($result as $row)
					{
						$query = "select count(id) from ! where receiverid = ?";
						$count=$db->getOne($query,array('zi_comment',$row['userid']));
						$result[$i]['totComment']=$count;
						$totComm[$i]=$count;
						$i++;
					}
					array_multisort($totComm, SORT_DESC, $result);
				}
				else if($sort==4)
				{
					$i=0;
					foreach($result as $row)
					{
						$report=$this->loanReport($row['userid']);
						$f=$report['feedback'];
						$tf=$report['Totalfeedback'];
						$result[$i]['feedback']=$tf*$f;
						$feedback[$i]=$f;
						$totalfeedback[$i]=$tf;
						$i++;
					}
					array_multisort($feedback, SORT_DESC, $totalfeedback, SORT_DESC, $result);
				}
				else if($sort==5)
				{
					foreach($result as $key=>$row)
						$disbdate[$key]=$row['AcceptDate'];
					array_multisort($disbdate, SORT_ASC, $result);
				}
				else if($sort==6)
				{
					foreach($result as $key=>$row)
						$disbdate[$key]=$row['AcceptDate'];
					array_multisort($disbdate, SORT_DESC, $result);
				}
			}
			else
			{
				if($sort==2)
				{
					$i=0;
					foreach($result as $row)
					{
						$query = "select count(id) from ! where receiverid = ?";
						$count=$db->getOne($query,array('zi_comment',$row['userid']));
						$result[$i]['totComment']=$count;
						$totComm[$i]=$count;
						$i++;
					}
					array_multisort($totComm, SORT_DESC, $result);
				}
				else if($sort==3)
				{
					$i=0;
					foreach($result as $row)
					{
						$report=$this->loanReport($row['userid']);
						$f=$report['feedback'];
						$tf=$report['Totalfeedback'];
						$result[$i]['feedback']=$tf*$f;
						$feedback[$i]=$f;
						$totalfeedback[$i]=$tf;
						$i++;
					}
					array_multisort($feedback, SORT_DESC, $totalfeedback, SORT_DESC, $result);
				}
				else if($sort==4)
				{
					$i=0;
					foreach($result as $row)
					{
						$rpddate=$this->getRepaidDate($row['userid'], $row['loanid']);
						$result[$i]['Rdate']=$rpddate;
						$Rdate[$i]=$rpddate;
						$i++;
					}
					array_multisort($Rdate, SORT_ASC, $result);
				}
				else if($sort==5)
				{
					$i=0;
					foreach($result as $row)
					{
						$rpddate=$this->getRepaidDate($row['userid'], $row['loanid']);
						$result[$i]['Rdate']=$rpddate;
						$Rdate[$i]=$rpddate;
						$i++;
					}
					array_multisort($Rdate, SORT_DESC, $result);
				}
			}
			$cont=count($result);
			if($rowcount >0)
			{
				$loanids=array();
				if($randomLoans !='')
					$loanids= explode(',',$randomLoans);
				if($sort==1)
				{
					if(empty($loanids))
					{
						foreach($result as $row)
						{
							$loanids[]=$row['loanid'];
						}
					}
					$searchArray= array_slice($loanids, $start, 20);
					for($i=0,$j=0; $j<$rowcount; $i++)
					{
						if(empty($result[$i]))
							break;
						if(in_array($result[$i]['loanid'], $searchArray))
						{
							$result1[$j]=$result[$i];
							$j++;
						}
					}
					if($result1)
						shuffle($result1);
				}
				else
				{
					for($i=$start,$j=0; $j<$rowcount; $i++,$j++)
					{
						if(empty($result[$i]))
							break;
						else
							$result1[$j]=$result[$i];
					}
				}
				$result1[0]['randomLoans']=$loanids;
				$result1[0]['count']=$cont;
				return $result1;
			}
			else{
				$result[0]['count']=$cont;
				return $result;
			}
		}
	}
	function getLoanBids($user, $loanid = 0)
	{
		global $db;
		if($loanid == 0)
		{
			$q="SELECT l.amount, l.loanid, l.borrowerid, u.username, bidid, biddate, lenderid, Firstname, LastName, email, bidamount, bidint FROM loanapplic as l, loanbids as lb, lenders as le, users as u WHERE l.borrowerid=$user AND le.userid=u.userid AND l.loanid=lb.loanid AND lb.lenderid=le.userid AND l.adminDelete = 0 ORDER BY bidint, bidid ASC";
		}
		else
		{
			$q="SELECT l.amount, l.loanid, l.borrowerid,u.username, bidid , biddate , lenderid, Firstname, LastName, email, bidamount, bidint FROM loanapplic as l, loanbids as lb, lenders as le, users as u WHERE l.borrowerid=$user AND le.userid=u.userid AND l.loanid=lb.loanid AND lb.lenderid=le.userid and l.loanid= $loanid AND l.adminDelete = 0 ORDER BY  lb.bidint ,lb.bidid ASC";
		}
		$result=$db->getAll($q);
		return $result;
	}
	function getLoanAmount($user, $loanid =0)
	{
		global $db;
		if($loanid == 0)
		{
			$q="SELECT l.amount, l.loanid, l.borrowerid, bidid,lenderid, Email, Firstname, LastName, bidamount, bidint, username, givenamount FROM loanapplic as l, loanbids as lb,users as u, lenders as le WHERE u.userid=le.userid AND l.active=".LOAN_FUNDED." AND l.borrowerid=$user AND l.adminDelete = 0 AND l.loanid=lb.loanid AND lb.Active=".LOAN_FUNDED." AND lb.lenderid=le.userid AND lb.active =1 ORDER BY bidint ASC";
		}
		else
		{
			$q="SELECT l.amount, l.loanid, l.borrowerid, bidid,lenderid, Email, Firstname, LastName, bidamount, bidint,username,givenamount".
			" FROM loanapplic as l, loanbids as lb,users as u, lenders as le WHERE l.loanid = ". $loanid." AND u.userid=le.userid AND l.adminDelete = 0 AND l.borrowerid=". $user. " AND lb.active =1 AND lb.loanid = ". $loanid." AND lb.lenderid=le.userid ORDER BY bidint ASC";
		}
		$result=$db->getAll($q);
		if(empty($result) ){
			return false;
		}
		return $result;
	}
	function getLoanfund($user, $loanid =0)
	{
		global $db;
		if($loanid == 0)
			$q="SELECT * FROM loanapplic WHERE active=".LOAN_FUNDED." AND borrowerid=$user AND adminDelete = 0";
		else
			$q="SELECT * FROM loanapplic WHERE borrowerid=$user  AND loanid = " .$loanid." AND adminDelete = 0" ;
		$result=$db->getRow($q);
		return $result;
	}
	function getCurrentLoanid($userid)
	{
		global $db;
		$q="select loanid from ! as l where l.borrowerid =? AND l.adminDelete = ? and l.active IN(?, ?, ?)";
		$loanid=$db->getOne($q, array('loanapplic', $userid, 0, LOAN_OPEN, LOAN_FUNDED, LOAN_ACTIVE));
		return $loanid;
	}
	function getActiveLoanid($userid)
	{
		global $db;
		$q="select loanid from ! as l where l.borrowerid =? AND l.adminDelete = ? and l.active IN(?)";
		$loanid=$db->getOne($q, array('loanapplic', $userid, 0, LOAN_ACTIVE));
		return $loanid;
	}
	function getUNClosedLoanid($userid)
	{
		global $db;
		$q="select activeLoanID from borrowers where borrowers.userid=? ";
		$result=$db->getOne($q , array($userid));
		return $result;
	}
	function getTotalPayment($userid, $loanid)
	{
		global $db;
		$q="SELECT sum(amount) as amttotal, sum(paidamt) as paidtotal FROM  ! WHERE loanid = ? AND userid = ?";
		$result=$db->getRow($q, array('repaymentschedule', $loanid, $userid));
		if(empty($result)){
			return 0;
		}
		return $result;
	}
	function getTotalPaymentbydate($userid, $loanid,$Txnid)
	{
		global $db;
		
		$q="SELECT sum(amount) as paidtotal FROM  ! WHERE loanid = ? AND userid = ? AND id < ? AND txn_type=?";
		$result=$db->getRow($q, array('transactions', $loanid, $userid, $Txnid, LOAN_BACK));
		if(empty($result)){
			return 0;
		}
		return $result;
	}
	function getOpenLoanAmount($userid, $loanid = 0, $native=true)
	{
		global $db;
		if($loanid == 0)
			$q="SELECT reqdamt FROM loanapplic WHERE borrowerid=$userid AND adminDelete = 0 ORDER BY loanid DESC ";
		else
			$q="SELECT reqdamt FROM loanapplic WHERE borrowerid=$userid AND adminDelete = 0 AND loanid = $loanid";
		$result=$db->getOne($q);
		if(empty($result) ){
			return 0;
		}
		if($native) {
			$rate = $this->getCurrentRate($userid);
			$amt = round_local(($result * $rate));
			return $amt;
		} else {
			return $result;
		}
	}
	function getBorrowerDetails($id)
	{
		global $db;
		$q="SELECT * FROM ! LEFT JOIN borrowers_extn as bext on bext.userid=borrowers.userid, ! WHERE borrowers.userid=? AND borrowers.userid=users.userid ";
		$result=$db->getRow($q, array('borrowers', 'users', $id));
		return $result;
	}
	function getLoanDetailbyLoneID($loanid)
	{
		global $db;
		$q="SELECT * FROM ! WHERE loanid=? AND adminDelete = ?";
		$result=$db->getRow($q , array('loanapplic', $loanid , 0));
		return $result;
	}
	function getUserCurrency($userid)
	{
		global $db;
		$q="SELECT currency FROM ! WHERE userid =?";
		$cid=$db->getOne($q, array('borrowers', $userid));
		$q = "SELECT Currency from ! where id = ?";
		return $db->getOne($q, array('currency', $cid ));
	}
	function getUserCurrencyName($userid)
	{
		global $db;
		$q="SELECT currency FROM ! WHERE userid =?";
		$cid=$db->getOne($q, array('borrowers', $userid));
		$q = "SELECT currencyname from ! where id = ?";
		return $db->getOne($q, array('currency', $cid ));
	}
	function setSchedule($userid, $loanid, $details)
	{
		global $db;
		if(isset($userid) && isset($loanid) && $userid != 0 && $loanid != 0)
		{
			$q = "select count(*) from ! where userid = ? and loanid = ?";
			$count = $db->getOne($q, array('repaymentschedule', $userid, $loanid));
			if($count != 0)
			{
				$q = "delete from ! where userid = ? and loanid = ?";
				$count = $db->query($q, array('repaymentschedule', $userid, $loanid));
			}
			for($i = 0 ; $i < count($details) ; $i++)
			{
				$q = "insert into ! (userid, loanid, duedate, amount ) values (?, ?, ? ,? )";
				$res= $db->query($q, array('repaymentschedule', $userid, $loanid, $details[$i]['date'],$details[$i]['total'] ));
				if($res !==DB_OK)
				{
					return 0;
				}
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function getSchedulefromDB($userid, $loanid)
	{
		global $db;
		$q = "select * from ! where userid = ? and loanid = ? order by id";
		$data = $db->getAll($q, array('repaymentschedule', $userid, $loanid));
		return $data;
	}
	function getRepaySchedulefromDB($userid, $loanid)
	{
		global $db;
		$q = "select * from ! where userid = ? and loanid = ? order by id";
		$data = $db->getAll($q, array('repaymentschedule_actual', $userid, $loanid));
		return $data;
	}
	function getActualSchedulefromDB($userid, $loanid)
	{
		global $db;
		$data=array();
		if(isset($userid) && isset($loanid) && $userid != 0 && $loanid != 0)
		{
			$q = "select a.id, a.duedate, a.amount, b.paiddate, b.paidamt from ! as a left outer join ! as b on a.id = b.rid where a.userid = ? and a.loanid = ? order by a.id, b.id";
			$data = $db->getAll($q, array('repaymentschedule','repaymentschedule_actual', $userid, $loanid));
		}
		return $data;
	}
	function loanReport($brid)
	{
		global $db;
		$time=time();
		if(!empty($brid))
		{
			$report=array();
			$result1=$this->getPartnerComment($brid);
			$lamount=0;
			$countt=0;
			$CPaid=0;   /*count for no of loan paid, given by partner   */
			$f=0;
			$CPaidLate=0;
			$PaidLate=0;
			$PaidLate_us=0;
			$CPaidOntime=0;
			$PaidOntime=0;
			$PaidOntime_us=0;
			$defltdAmt=0;
			$defltdAmt_us=0;
			$rate = $this->getCurrentRate($brid);
			if($result1)
			{
				foreach($result1 as $profile1)
				{
					if($profile1['loneid']==0)
					{
						$ldate=$this->getborrowerActivatedDate($brid);
						$lamount+=$profile1['amount'];
						$lpaid=$profile1['lpaid'];
						if($lpaid==1) $CPaid += 1;
						$ontime=$profile1['ontime'];
						if($profile1['rate']!=null && $profile1['rate']!="" && $profile1['rate']!=0)
							$rate= $profile1['rate'];
						if($ontime==1){
							$CPaidOntime += 1;
							$PaidOntime=$PaidOntime+$profile1['amount'];
							$PaidOntime_us=$PaidOntime_us + convertToDollar($profile1['amount'],$rate);
						}else{
							$CPaidLate += 1;
							$PaidLate=$PaidLate+$profile1['amount'];
							$PaidLate_us=$PaidLate_us + convertToDollar($profile1['amount'],$rate);
						}
						$countt++;
					}
					$Pfeedback=$profile1['feedback'];
					if($Pfeedback==2) $f += 1;
				}
				$partid=$profile1['partid'];
				$f=($f*100)/count($result1);
				$report['sincedate']=$ldate;
				$report['feedback']=$f;
				$report['Totalfeedback']=count($result1);
			}
			$sql = "SELECT Amount, AcceptDate FROM ! WHERE active=?  AND borrowerid = ? AND adminDelete = ?";
			$data = $db->getAll($sql, array('loanapplic',LOAN_DEFAULTED,$brid, 0));

			if(count($data))
			{
				for($i=0; $i<count($data); $i++)
				{
					$defltdAmt += $data[$i]['Amount'];
					$rate = $this->getExRateById($data[$i]['AcceptDate'],$brid);
					$defltdAmt_us += convertToDollar($data[$i]['Amount'],$rate);
				}
				$report['Deflted']=$i;
				$report['AmtDeflted']=$defltdAmt;
				$report['AmtDeflted_us']=$defltdAmt_us;
			}

			$sql = "SELECT loanid, AmountGot, period, ActiveDate, RepaidDate,AcceptDate FROM ! WHERE (active =? OR active =?) AND borrowerid = ? AND adminDelete = ?";
			$data = $db->getAll($sql, array('loanapplic',LOAN_REPAID,LOAN_ACTIVE ,$brid , 0));
			foreach($data as $row)
			{
				$period=$row['period'];
				$loanDate= $this->getMaxLoanDate($brid, $row['loanid']);
				$loneFundDate=$row['ActiveDate'];
				$expectedPayDate= $loanDate['duedateMax'];
				$lonePayDate= $loanDate['paiddateMax'];
				$rate = $this->getExRateById($row['AcceptDate'],$brid);
				if((empty($lonePayDate))&&($expectedPayDate < $time))
				{
					//late
					$PaidLate += $row['AmountGot'];
					$PaidLate_us += convertToDollar($row['AmountGot'],$rate);
					$CPaidLate += 1;

				}
				else if((!empty($lonePayDate))&&($expectedPayDate < $lonePayDate))
				{
					//late
					$PaidLate += $row['AmountGot'];
					$PaidLate_us += convertToDollar($row['AmountGot'],$rate);
					$CPaidLate += 1;
				}
				else if((!empty($lonePayDate))&&($expectedPayDate>=$lonePayDate))
				{
					//ontime
					$PaidOntime += $row['AmountGot'];
					$PaidOntime_us += convertToDollar($row['AmountGot'],$rate);
					$CPaidOntime += 1;
				}
				$countt++;
			}
			$Deflted = (isset($report['Deflted'])) ? $report['Deflted'] : 0;
			$report['NoOfLone']=$countt + $Deflted;
			$AmtDeflted = (isset($report['AmtDeflted'])) ? $report['AmtDeflted'] : 0;
			$report['Total']=$PaidLate+$PaidOntime+$AmtDeflted;
			$AmtDeflted_us = (isset($report['AmtDeflted_us'])) ? $report['AmtDeflted_us'] : 0;
			$report['Total_us']=$PaidLate_us+$PaidOntime_us+$AmtDeflted_us;
			$report['late']=$CPaidLate;
			$report['Amtlate']=$PaidLate;
			$report['Amtlate_us']=$PaidLate_us;
			$report['PaidonTime']=$CPaidOntime;
			$report['AmtPaidonTime']=$PaidOntime;
			$report['AmtPaidonTime_us']=$PaidOntime_us;
			return $report;
		}
		else{
			echo "error";
		}
	}
	function getWebsiteFeeTotal($loanid)
	{
		global $db;
		$q = "select sum(amount * conversionrate) as fee from ! where  loanid = ? AND txn_type = ?";
		$fee = $db->getOne($q, array('transactions', $loanid, FEE));
		return $fee;
	}
	function getWebsiteFeeTotalbydate($loanid, $txnid)
	{
		global $db;
		$q = "select sum(amount * conversionrate) as fee from ! where  loanid = ? AND txn_type = ? AND id <?";
		$fee = $db->getOne($q, array('transactions', $loanid, FEE, $txnid));
		return $fee;
	}
	function getEmailB($useid)
	{
		global $db;
		$sql = "SELECT Email, FirstName , LastName  FROM ! WHERE userid = ? ";
		$r3 = $db->getRow($sql, array('borrowers', $useid));
		$r['name']=$r3['FirstName']." ".$r3['LastName'];
		$r['email']=$r3['Email'];
		return $r;
	}
	function getLastloan($borrowerid)
	{
		global $db;
		$sql = "SELECT * FROM ! WHERE borrowerid  = ? AND adminDelete = ? ORDER BY loanid DESC ";
		$r3 = $db->getRow($sql, array('loanapplic', $borrowerid, 0));
		return $r3;
	}
	function getLastRepaidloanId($borrowerid)
	{
		global $db;
		$sql = "SELECT loanid FROM ! WHERE borrowerid  = ? AND adminDelete = ? AND (active=? OR active=?) ORDER BY loanid DESC ";
		$r3 = $db->getOne($sql, array('loanapplic', $borrowerid, 0, LOAN_REPAID, LOAN_DEFAULTED)); 
		return $r3;
	}
	function getUserLoanStatus($ud,$ld)
	{
		global $db;
		$q="SELECT active, reqdamt, Amount  FROM loanapplic WHERE loanid=$ld AND borrowerid = $ud AND adminDelete = ?";
		$result=$db->getRow($q , array(0));
		return $result;
	}
	function getLoanDetailsNew($borrowerid, $loanid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT sum(amount) as totalAmt, sum(paidamt) as totalPaidAmt FROM ! WHERE userid=? AND loanid=?";
		$row= $db->getRow($q , array('repaymentschedule', $borrowerid, $loanid));
		return $row;
	}
	function getMaxLoanDate($borrowerid, $loanid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$q="SELECT Max(duedate) as duedateMax, Max(paiddate) as paiddateMax FROM ! WHERE userid=? AND loanid=?";
		$row= $db->getRow($q , array('repaymentschedule', $borrowerid, $loanid));
		return $row;
	}
	function isRepaidOntime($borrowerid, $loanid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$p="SELECT MAX(id) FROM ! WHERE userid=? and loanid=?";
		$id1= $db->getOne($p , array('repaymentschedule',$borrowerid,$loanid));
		$q="SELECT duedate FROM ! WHERE id=?";
		$duedate= $db->getOne($q , array('repaymentschedule',$id1));

		$r="SELECT MAX(id) FROM ! WHERE userid=? and loanid=?";
		$id2= $db->getOne($r , array('repaymentschedule_actual',$borrowerid,$loanid));
		$s="SELECT paiddate FROM ! WHERE id=?";
		$paiddate= $db->getOne($s , array('repaymentschedule_actual',$id2));
		if(($paiddate - $duedate) <=  86400*REPAYMENT_THRESHOLD)   /* grace time is 10 days that is (86400*10) seconds*/
			return 1;
		else
			return 0;
	}
	function getRepaidDate($borrowerid, $loanid)
	{
		global $db;
		traceCalls(__METHOD__, __LINE__);
		$p="SELECT MAX(paiddate) FROM ! WHERE userid=? and loanid=?";
		$date= $db->getOne($p , array('repaymentschedule_actual',$borrowerid,$loanid));
		return $date;
	}
	function getPreviousLoanAmount($borrowerid,$loanCount, $excludeLoanIds)
	{
		global $db;
		if($loanCount==0)
		{
			/* it means it is first loan */
			$val=$this->getAdminSetting('firstLoanValue');
			$resultNative=convertToNative($val, $rate);
			return $resultNative;
		}
		else
		{
			/* it means it is next loan */

			/*$p="SELECT max(amount) from ! where userid = ?";
			$amount1=$db->getOne($p,array('comments',$borrowerid));*/
			$q="SELECT max(AmountGot) from ! where borrowerid = ? AND active > ? AND adminDelete =?";
			if(!empty($excludeLoanIds)) {
				$excludeLoanIds = implode(',', $excludeLoanIds);
				$q="SELECT max(AmountGot) from ! where borrowerid = ? AND active > ? AND adminDelete =? AND loanid NOT IN(".$excludeLoanIds.")";
			}
			$amount=$db->getOne($q,array('loanapplic',$borrowerid, LOAN_OPEN, 0));
			return $amount;
			
		}
	}
	function setTelephoneNo($userid, $phone)
	{
		global $db;
		$date=time();
		$q = "insert into ! (userid, phoneno, date) values (?, ?, ?)";
		$res=$db->query($q, array('phone_log', $userid, $phone, $date));
		if($res===DB_OK)
			return 1;
		else
			return 0;
	}
	function getTelephoneNoByUserId($userid)
	{
		global $db;
		$q = "SELECT * from ! Where userid=? order by id";
		$res=$db->getAll($q, array('phone_log', $userid));
		return $res;
	}
	function updateTelephoneNo()
	{
		global $db;
		$q="SELECT u.userid, b.TelMobile, u.regdate FROM ! as b join ! as u on b.userid=u.userid";
		$res= $db->getAll($q, array('borrowers','users'));
		$q = "SELECT * from ! Where 1";
		$res1=$db->getAll($q, array('phone_log'));
		if(empty($res1))
		{
			foreach($res as $row)
			{
				$q = "insert into ! (userid, phoneno, date) values (?, ?, ?)";
				$db->query($q, array('phone_log', $row['userid'], $row['TelMobile'], $row['regdate']));
			}
		}
	}
	function getBorrowerCountries()
	{
		global $db;
		$q = "SELECT DISTINCT(b.Country), c.Currency, cc.name  from ! b join ! c on b.currency=c.id join ! as cc on b.Country=cc.code Where b.Active=?";
		$res=$db->getAll($q, array('borrowers','currency','countries',1));
		return $res;
	}
	function getBorrowerCountryByLoanid($loanid)
	{
		global $db;
		$q = "SELECT b.Country from ! b join ! l on b.userid = l.borrowerid  Where l.loanid=?";
		$res=$db->getOne($q, array('borrowers','loanapplic', $loanid));
		return $res;
	}
	function getExRateByLoanId($loan_id)
	{
		global $db;
		$p = "select b.currency, l.AcceptDate from ! as b join ! a
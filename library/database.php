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
        $q = "SELECT userid, username, userlevel, lang, salt, emailVerified,sublevel FROM ".TBL_USERS." WHERE username = ?";
        $result = $db->getRow($q, array($username)) or die();
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
        $result = $db->getRow($q);
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
            if($country==''){
                $t="SELECT loanid, borrowerid from ! where active = ? ";//AND loanid IN (".$loanids['loanid'].") ";
                $defaultedLoanids=$db->getAll($t,array('loanapplic', LOAN_DEFAULTED));
            }else{
                $t="SELECT loanid, borrowerid from ! as l join ! as br on l.borrowerid=br.userid where l.active = ? AND br.Country=?";//AND loanid IN (".$loanids['loanid'].") ";
                $defaultedLoanids=$db->getAll($t,array('loanapplic', 'borrowers', LOAN_DEFAULTED, $country));
            }
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
        $repay['repayRate']= ($repayRate - $defaultRate);
        $repay['default_amount']= $defaultAmountUsd ;
        return $repay;
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
        $date= time();
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

            $repay= $this->getRepayRate();
            $cumStat['default_amount']=$repay['default_amount'];
            $cumStat['repayRate']=$repay['repayRate'];

            $q='SET SESSION group_concat_max_len = 12000;';
            $db->query($q);

            $t="SELECT group_concat(loanid) as loanid from (SELECT loanid, max(duedate) as date from ! group by loanid) as r where r.date <=?";
            $loanids=$db->getRow($t, array('repaymentschedule', $date));

            $q="SELECT AmountGot, loanid, borrowerid from ! where active in (?, ? , ?) AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $disb_data=$db->getAll($q, array('loanapplic', LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED , 0));
            $disb_amount=0;
            foreach($disb_data as $row){
                $exrate=$this->getCurrentRate($row['borrowerid']);
                $disb_amount +=($row['AmountGot']/$exrate);
            }
            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $repaid_data=$db->getAll($q, array('loanapplic', LOAN_REPAID, 0));
            $repaid_amount=0;
            $forgiveAmountUsd=0;
            $forgiveAmount=0;
            foreach($repaid_data as $row){
                $exrate=$this->getCurrentRate($row['borrowerid']);
                $isforgive=$this->loanAlreadyInForgiveness($row['loanid']);
                if($isforgive){
                    $forgiveAmount+=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                }
                $repaid_amount +=($row['AmountGot']/$exrate);
            }
            $forgiveAmountUsd+=$forgiveAmount;
            $repaid_amountUsd=($repaid_amount- $forgiveAmount);

            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $due_data=$db->getAll($q, array('loanapplic', LOAN_ACTIVE, 0));
            $due_forgiveAmount=0;
            $due_paidamountUsd=0;
            $due_amtUsd=0;
            foreach($due_data as $row){
                $res=$this->getTotalPayment($row['borrowerid'], $row['loanid']);
                if($res['amttotal'] > $res['paidtotal'])
                {
                    $due_amount=$res['amttotal'] - $res['paidtotal'];
                    $exrate=$this->getCurrentRate($row['borrowerid']);
                    $ratio=$this->getPrincipalRatio($row['loanid']);
                    $due_paidamount=($res['paidtotal']*$ratio);
                    $due_paidamountUsd += ($due_paidamount/$exrate);
                    $due_amt=($due_amount * $ratio);
                    $due_amtUsd+= ($due_amt/$exrate);
                    $isforgive=$this->loanAlreadyInForgiveness($row['loanid']);
                    if($isforgive){
                        $due_forgiveAmount+=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                    }
                }
            }
            $forgiveAmountUsd+=$due_forgiveAmount;

            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $writeoff_data=$db->getAll($q, array('loanapplic', LOAN_DEFAULTED, 0));
            $defaultAmountUsd=0;
            $defaultpaidamountUsd =0;
            foreach($writeoff_data as $row){
                $res=$this->getTotalPayment($row['borrowerid'], $row['loanid']);
                if($res['amttotal'] > $res['paidtotal'])
                {
                    $amount=$res['amttotal'] - $res['paidtotal'];
                    $ratio=$this->getPrincipalRatio($row['loanid']);
                    $paidamount=$res['paidtotal'];
                    $exrate=$this->getCurrentRate($row['borrowerid']);
                    $defaultAmount =($amount * $ratio);
                    $defaultAmountUsd += ($defaultAmount/$exrate);
                    $defaultpaidamount =($paidamount* $ratio);
                    $defaultpaidamountUsd += ($defaultpaidamount/$exrate);
                    $forgiveAmount=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                    $netforgiveAmount=($forgiveAmount* $ratio);
                    $forgiveAmountUsd+=$netforgiveAmount;

                }
            }

            /*	$amount_total=0;
			$due_amt=0;
			$loanid_forlate=explode(',', $loanids['loanid']); 
			foreach($loanid_forlate as $loanid){
				$result=$this->getTotalPayment($userid=0, $loanid); 
				$due_amt+=$result['amttotal']-$result['paidtotal'];
			}*/
            //$cumStat['defaultRate']=100-$repay['repayRate'];
            $cumStat['end_repaid_rate']=(($repaid_amountUsd+$defaultpaidamountUsd+$due_paidamountUsd)/$disb_amount)*100;
            $cumStat['hist_loss']= ($defaultAmountUsd/$disb_amount)*100;
            $cumStat['repay_late_rate']=($due_amtUsd/$disb_amount)*100;
            $cumStat['end_forgive_rate']=($forgiveAmountUsd/$disb_amount)*100;
            $cumStat['hist_loss_amt']= $defaultAmountUsd;
            $cumStat['disb_amt']= $disb_amount;
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

            $repay= $this->getRepayRate($country);
            $cumStat['default_amount']=$repay['default_amount'];
            $cumStat['repayRate']=$repay['repayRate'];

            $t="SELECT group_concat(loanid) as loanid from (SELECT loanid, max(duedate) as date from ! as rp join ! as br on rp.userid= br.userid where br.Country=?  group by loanid) as r where r.date < ?";
            $loanids=$db->getRow($t, array('repaymentschedule', 'borrowers', $country, $date));

            $q="SELECT AmountGot, loanid, borrowerid from ! where active in (?, ? , ?) AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $disb_data=$db->getAll($q, array('loanapplic', LOAN_ACTIVE, LOAN_REPAID, LOAN_DEFAULTED , 0));
            $disb_amount=0;
            foreach($disb_data as $row){
                $exrate=$this->getCurrentRate($row['borrowerid']);
                $disb_amount +=($row['AmountGot']/$exrate);
            }

            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $repaid_data=$db->getAll($q, array('loanapplic', LOAN_REPAID, 0));
            $repaid_amount=0;
            $forgiveAmountUsd=0;
            $forgiveAmount=0;
            foreach($repaid_data as $row){
                $exrate=$this->getCurrentRate($row['borrowerid']);
                $isforgive=$this->loanAlreadyInForgiveness($row['loanid']);
                if($isforgive){
                    $forgiveAmount+=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                }
                $repaid_amount +=($row['AmountGot']/$exrate);
            }
            $forgiveAmountUsd+=$forgiveAmount;
            $repaid_amountUsd=($repaid_amount- $forgiveAmount);

            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $due_data=$db->getAll($q, array('loanapplic', LOAN_ACTIVE, 0));
            $due_forgiveAmount=0;
            $due_paidamountUsd=0;
            $due_amtUsd=0;
            foreach($due_data as $row){
                $res=$this->getTotalPayment($row['borrowerid'], $row['loanid']);
                if($res['amttotal'] > $res['paidtotal'])
                {
                    $due_amount=$res['amttotal'] - $res['paidtotal'];
                    $exrate=$this->getCurrentRate($row['borrowerid']);
                    $ratio=$this->getPrincipalRatio($row['loanid']);
                    $due_paidamount=($res['paidtotal']*$ratio);
                    $due_paidamountUsd += ($due_paidamount/$exrate);
                    $due_amt=($due_amount * $ratio);
                    $due_amtUsd+= ($due_amt/$exrate);
                    $isforgive=$this->loanAlreadyInForgiveness($row['loanid']);
                    if($isforgive){
                        $due_forgiveAmount+=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                    }
                }
            }
            $forgiveAmountUsd+=$due_forgiveAmount;

            $q="SELECT AmountGot, loanid, borrowerid from ! where active=? AND adminDelete = ? AND loanid IN (".$loanids['loanid'].")";
            $writeoff_data=$db->getAll($q, array('loanapplic', LOAN_DEFAULTED, 0));
            $defaultAmountUsd=0;
            $defaultpaidamountUsd =0;
            foreach($writeoff_data as $row){
                $res=$this->getTotalPayment($row['borrowerid'], $row['loanid']);
                if($res['amttotal'] > $res['paidtotal'])
                {
                    $amount=$res['amttotal'] - $res['paidtotal'];
                    $ratio=$this->getPrincipalRatio($row['loanid']);
                    $paidamount=$res['paidtotal'];
                    $exrate=$this->getCurrentRate($row['borrowerid']);
                    $defaultAmount =($amount * $ratio);
                    $defaultAmountUsd += ($defaultAmount/$exrate);
                    $defaultpaidamount =($paidamount* $ratio);
                    $defaultpaidamountUsd += ($defaultpaidamount/$exrate);
                    $forgiveAmount=$this->getForgiveAmountUsd($row['borrowerid'], $row['loanid']);
                    $netforgiveAmount=($forgiveAmount* $ratio);
                    $forgiveAmountUsd+=$netforgiveAmount;

                }
            }

            /*	$amount_total=0;
			$due_amt=0;
			$loanid_forlate=explode(',', $loanids['loanid']); 
			foreach($loanid_forlate as $loanid){
				$result=$this->getTotalPayment($userid=0, $loanid); 
				$due_amt+=$result['amttotal']-$result['paidtotal'];
			}*/
            //$cumStat['defaultRate']=100-$repay['repayRate'];
            $cumStat['end_repaid_rate']=(($repaid_amountUsd+$defaultpaidamountUsd+$due_paidamountUsd)/$disb_amount)*100;
            $cumStat['hist_loss']= ($defaultAmountUsd/$disb_amount)*100;
            $cumStat['repay_late_rate']=($due_amtUsd/$disb_amount)*100;
            $cumStat['end_forgive_rate']=($forgiveAmountUsd/$disb_amount)*100;
            $cumStat['hist_loss_amt']= $defaultAmountUsd;
            $cumStat['disb_amt']= $disb_amount;
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
            $where="AND userid<>$userid AND userid NOT IN(select endorserid from endorser where borrowerid=$userid)";
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
    function IsUserinvited($userid, $email) {

        global $db;
        if(isset($_COOKIE["invtduserjoins"])) {
            $cookie_val = $_COOKIE["invtduserjoins"];
            $q="select id,userid from ! where cookie_value = ?";
            $result=$db->getRow($q, array('invites',$cookie_val));
            $q1="UPDATE invites SET invitee_id = ? Where cookie_value = ? LIMIT 1";
            $res=$db->query($q1, array($userid, $cookie_val));
            setcookie ("invtduserjoins", "", time() - 3600);
        
//uses email to record invite, for case where invited user joins without cookie set
        }else{
            $q="select id,userid from ! where email = ?";
            $result=$db->getRow($q, array('invites',$email));
            $q1="UPDATE invites SET invitee_id = ? Where email = ? LIMIT 1";
            return $db->query($q1, array($userid, $email));
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
            $where= " AND Country ='$country' ";
        }
        if($userid>0){
            $where .= " AND userid<>$userid ";
        }
        $q="select count(*) from ! where nationId='$bnationid' $where";
        $result=$db->getOne($q, array('borrowers'));
        return $result;
    }

    function IsFacebookIdExist($facebook_id, $userid=0){
        global $db;
        $where='';
        if(!empty($userid)){
            $where="AND userid<>$userid";
        }
        $q="select count(*) from ! where facebook_id='$facebook_id' $where";
        $result=$db->getOne($q, array('users'));
        return $result;
    }

    function setFBPost($id, $post_id){
        global $db;
        $q="update ! set fb_post=? where userid=?";
        $result= $db->query($q, array('users', $post_id, $id));
        return $result;
    }

    function getFBPost($id){
        global $db;
        $q="select fb_post from ! where userid=?";
        $result= $db->getOne($q, array('users', $id));
        return $result;
    }

    function setStatistics($name, $value, $country=''){
        global $db;
        $date=time();
        $q="INSERT INTO ! (Name, value, date, country) VALUES (?,?,?,?)";
        $result=$db->query($q, array('statistics', $name, $value, $date, $country));
        return 1;
    }


    function getStatistics($name, $date, $country=''){
        global $db;
        $q="select max(date) as date from ! where Name=? and country=?";
        $maxdate=$db->getOne($q, array('statistics', $name, $country));
        if($date-$maxdate> 24*60*60){
            return false;
        }else{
            $q="select value from ! where Name=? and country=? and date=?";
            $result=$db->getOne($q, array('statistics', $name, $country,$maxdate));
            return $result;
        }
    }

    function getEndorserForEmail($id, $endorser_name, $endorser_email){
        global $db;
        $q="select id, validation_code, message from ! where borrowerid=? and ename=? and e_email=?";
        $res= $db->getRow($q, array('endorser', $id, $endorser_name, $endorser_email));
        return $res;
    }

    function updateEndorserAfterEmail($id, $endorser_name, $endorser_email, $message){
        global $db;
        $q="update ! set message=? where borrowerid=? and ename=? and e_email=?";
        $res= $db->query($q, array('endorser', $message, $id, $endorser_name, $endorser_email));
        return $res;
    }

    function IsEndorseEmailExist($endorser_email, $userid=0){
        global $db;
        $where='';
        if($userid>0) {
            $where="AND borrowerid<>$userid";
        }
        $q="select count(*) exist  from !  where e_email='$endorser_email' $where";
        $result=$db->getOne($q, array('endorser'));
        return $result;
    }
    function getEndorserForResendMail($id){
        global $db;
        $q="select * from ! where id=?";
        $result=$db->getRow($q, array('endorser', $id));
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
    function getAdminSetting($name, $addCreditearned=true, $nextloan=false, $amtthrshldchek = 0,$thrshld_limit=null)
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
                    if($loanCount >= 0) {
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
                            if(!empty($thrshld_limit)){
                                if($thrshld_limit=='below_limit'){
                                    $timethrshld = $this->getAdminSetting('TimeThrshld');
                                    if($months < $timethrshld) {
                                        if(!$loancountdecreases){
                                            $loancountdecreases= true;
                                        }
                                    }
                                }
                                else if($thrshld_limit=='threshold_mid_limit1'){
                                    $timethrshld = $this->getAdminSetting('TimeThrshldMid1');
                                    if($months < $timethrshld) {
                                        if(!$loancountdecreases){
                                            $loancountdecreases= true;
                                        }
                                    }
                                }
                                else if($thrshld_limit=='threshold_mid_limit2'){
                                    $timethrshld = $this->getAdminSetting('TimeThrshldMid2');
                                    if($months < $timethrshld) {
                                        if(!$loancountdecreases){
                                            $loancountdecreases= true;
                                        }
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
                        $creditearned= $this->getCreditEarned($borrowerid);
                        $resultNative=($resultNative+$creditearned);
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
                        if($loanCount==1){
                            $val=$this->getAdminSetting('secondLoanValue');
                            $resultNative=convertToNative($val, $rate);
                        }else{
                            $val=$this->getAdminSetting('thirdLoanValue');
                            $resultNative=convertToNative($val, $rate);
                        }
                        if($loancountdecreases) {
                            //$resultNative = $this->getLastRepaidAmount($borrowerid, true);
                            $resultNative= $amount;
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
                            //$resultNative = $this->getLastRepaidAmount($borrowerid, true);
                            $resultNative= $amount;
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

//modified by Julia to allow staff lender accounts to view 4-11-2013

        if($userlevel==LENDER_LEVEL || $userlevel==ADMIN_LEVEL)
        {
            $q="SELECT b.*, u.regdate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as name, onb.name as postedby, bext.rec_form_offcr_name, bext.rec_form_offcr_num, bext.fb_data FROM ! as b join ! as u on b.userid= u.userid left join ! as onb on b.borrower_behalf_id= onb.id join ! as bext on b.userid = bext.userid WHERE (b.active=? || b.active=?) AND b.Assigned_status<>? AND u.emailVerified=? AND b.iscomplete_later=? order by $sort  $ord";
            $result = $db->getAll($q, array('borrowers', 'users','on_borrower_behalf','borrowers_extn',0,-1, 2, 1,0));
            return $result;
        }
        elseif($userlevel==PARTNER_LEVEL)
        {
            $q="SELECT b.*,u.regdate, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as name, onb.name as postedby, bext.rec_form_offcr_name, bext.rec_form_offcr_num, bext.fb_data FROM ! as b join ! as u on b.userid= u.userid left join ! as onb on b.borrower_behalf_id= onb.id join ! as bext on b.userid = bext.userid  WHERE b.active=? AND b.Assigned_status<>? AND u.emailVerified=? AND b.Assigned_to =? AND b.iscomplete_later=? order by $sort  $ord";
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
    function getAllBorrowers($sort='FirstName', $dir='asc', $start=0, $limit='', $country, $brwr_type, $search)
    {
        global $db;
        if($sort=='FirstName')
            $sort='sortname';
        if($country=='AA'){
            if($brwr_type=='all'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners'));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%'  || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners'));
                }
            }
            elseif($brwr_type=='endorser'){
                if(empty($search)){

                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.endorser=?  order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.endorser=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concatr(b.lastname, ' ',  b.firstname) like '%$search%'  || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1));
                }
            }
            elseif($brwr_type=='pndng_sub'){
                if(empty($search)){

                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.iscomplete_later=? AND b.endorser<>?  order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1, 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.iscomplete_later=? AND b.endorser<>? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1, 1));
                }
            }
            elseif($brwr_type=='pndng_act'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p, ! as u where b.partnerid=p.userid AND  b.userid=u.userid  AND  b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 'users', 0, 1, 2, 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p, as u where b.partnerid=p.userid AND  b.userid=u.userid  AND  b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 'users', 0, 1, 2, 1));
                }
            }

            elseif($brwr_type=='decline'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.Assigned_status=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 2));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.Assigned_status=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 2));
                }
            }
            elseif($brwr_type=='active'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.Active=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.Active=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 1));
                }
            }
        }else{
            if($brwr_type=='all'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND (concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%'  || b.TelMobile like '%$search%' || b.email like '%$search%') group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country));
                }
            }

            elseif($brwr_type=='endorser'){
                if(empty($search)){

                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.endorser=?  order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.endorser=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 1));
                }
            }
            elseif($brwr_type=='pndng_sub'){
                if(empty($search)){

                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.iscomplete_later=? AND b.endorser<>?  order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 1, 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.iscomplete_later=? AND b.endorser<>? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 1, 1));
                }
            }
            elseif($brwr_type=='pndng_act'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p, ! as u where b.partnerid=p.userid AND  b.userid=u.userid  AND b.country=? AND  b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 'users', $country, 0, 1, 2, 1));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p, as u where b.partnerid=p.userid AND  b.userid=u.userid AND b.country=?  AND  b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%'  || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', 'users',$country, 0, 1, 2, 1));
                }
            }

            elseif($brwr_type=='decline'){
                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.Assigned_status=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 2));
                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.Assigned_status=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners',$country, 2));
                }
            }
            elseif($brwr_type=='active'){

                if(empty($search)){
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.Active=? order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners', $country, 1));

                }else{
                    $q="select b.userid, b.Active, firstname, lastname, paddress, b.city, b.country, telmobile, b.email, p.name AS pname, activeloan AS loanactive, activeloanid,  concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname  from ! as b, ! as p where b.partnerid=p.userid AND b.country=? AND b.Active=? AND concat(b.firstname, ' ',  b.lastname) like '%$search%' ||  concat(b.lastname, ' ',  b.firstname) like '%$search%' || b.TelMobile like '%$search%' || b.email like '%$search%'group by b.userid order BY ".$sort. " " .$dir." limit ".$start. ", " .$limit;
                    $result=$db->getAll($q, array('borrowers','partners',$country, 1));
                }
            }

        }
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
// added by Julia 23 Oct 2013

    function getActiveLenderCount()
    {
        global $db;
        $q="SELECT count(*) FROM ! where userid NOT IN(SELECT userid from ! where emailVerified=?) AND Active=1";
        $result=$db->getOne($q,array('lenders', 'users', 0));
        return $result;
    }

    function getRecentLenderCount()
    {
        global $db;

        /* Added by Julia & comment by mohit on date 24-10-13
		$since_login=time() - strtotime(last_login);  
		$q="SELECT count(*) FROM ! where userid NOT IN(SELECT userid from ! where emailVerified=?) AND Active=1 AND $since_login<(60*24*60*60)";
		$result=$db->getOne($q,array('lenders', 'users', 0));
		*/
        $q="SELECT count(*) FROM ! as l inner join ! as u on u.userid=l.userid where u.emailVerified=? AND l.Active=? AND u.last_login>(UNIX_TIMESTAMP() -60*24*60*60)";
        $result=$db->getOne($q,array('lenders', 'users',1,1));
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
        logger('call in database');
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
        logger('result generated');
        $pfReport=array();
        /*
			$pfReport[$country][1]=> Report for PAR 0-30 DAYS of a country
			$pfReport[$country][2]=> Report for PAR 31-60 DAYS of a country
			$pfReport[$country][3]=> Report for PAR 61-90 DAYS of a country
			$pfReport[$country][4]=> Report for PAR 91-180 DAYS of a country
			$pfReport[$country][5]=> Report for PAR OVER 180 DAYS of a country
		*/
        $threshold=$this->getAdminSetting('LATENESS_THRESHOLD');
        logger('threshold amount got');
        foreach($result as $row) {
            $p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
            $forgivenResult=$db->getRow($p,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));
            logger('forgive result');
            $p="SELECT id,date from ! where loan_id = ? AND borrower_id=? AND date >= ? order by id asc";
            $rescheduleResult=$db->getRow($p,array('reschedule',$row['loanid'],$row['borrowerid'], $date4));
            logger('reschdule result');
            $p="SELECT SUM(amount) as totamt from ! where loanid = ? AND userid=? AND duedate < ?";
            $totamt=$db->getOne($p,array('repaymentschedule',$row['loanid'],$row['borrowerid'],$date4));
            logger('total amount got');
            $repayTable = 'repaymentschedule';
            $repayTableQuery1 = 'id <= ? ';
            $repayTableQuery2 = '';
            $repayTableQuery3 = 'id = ? ';
            $reschedule_id=0;
            $rid=0;
            logger('repaytable variable');
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
                    logger('flag 1 set');
                    $forgiven_loans_id=$forgivenResult['id'];
                    $p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND forgiven_loans_id = ? AND duedate < ?";
                    $totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$forgiven_loans_id, $date4));
                    $repayTableQuery1='repaymentschedule_id <= ? AND forgiven_loans_id = '.$forgiven_loans_id;
                    $repayTableQuery2='AND forgiven_loans_id = '.$forgiven_loans_id;
                    $repayTableQuery3='repaymentschedule_id = ? AND forgiven_loans_id = '.$forgiven_loans_id;
                    $repayTable = 'repaymentschedule_history';
                    logger('flag1 complete');
                } elseif($flag2==1) {
                    logger('flag2 set');
                    $reschedule_id=$rescheduleResult['id'];
                    $p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND userid=? AND reschedule_id = ? AND duedate < ?";
                    $totamt=$db->getOne($p,array('repaymentschedule_history',$row['loanid'],$row['borrowerid'],$reschedule_id, $date4));
                    $repayTableQuery1='repaymentschedule_id <= ? AND reschedule_id = '.$reschedule_id;
                    $repayTableQuery2='AND reschedule_id = '.$reschedule_id;
                    $repayTableQuery3='repaymentschedule_id = ? AND reschedule_id = '.$reschedule_id;
                    $repayTable = 'repaymentschedule_history';
                    logger('flag2 complete');
                }
            }
            $q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=? AND paiddate < ?";
            $totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$row['loanid'],$row['borrowerid'],$date4));
            logger('total paid amt');
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
            logger('reschedule id');
            $t="SELECT sum(amount) from ! where loan_id = ? AND borrower_id=? AND date < ?";
            $forgiveAmount=$db->getOne($t,array('forgiven_loans',$row['loanid'],$row['borrowerid'], $date4));
            logger('forgive amount');
            $ratio = $this->getPrincipalRatio($row['loanid'], $date4);
            $rate=$this->getExRateByDate($date4,$row['Currency']);
            $thresholdNative= round(($threshold * $rate),4);
            if($forgiveAmount) {
                $forgivePrinAmount=round(($forgiveAmount * $ratio),4);
                $row['prinAmount']=round(($row['AmountGot']-$forgivePrinAmount),4);
                logger('prinAmount in forgive');
            } else {
                $row['prinAmount']=$row['AmountGot'];
                logger('prinAmount');
            }
            $row['principlePaid']= round(($totpaidamt * $ratio),4);
            logger('row principlePaid');
            $row['principleOutstanding']= round(($row['prinAmount']-$row['principlePaid']),4);
            logger('row principleOutstanding');
            $row['dueAmount']= round(($totamt-$totpaidamt),4);
            logger('row dueAmount');
            $row['dueAmountUSD']= round(($row['dueAmount'] / $rate),4);
            $country = $row['Country'];
            logger('row country');
            if(!isset($pfReport[$country])) {
                logger('pfreport country not set');
                $pfReport[$country][1]['prinOut']=0;
                $pfReport[$country][2]['prinOut']=0;
                $pfReport[$country][3]['prinOut']=0;
                $pfReport[$country][4]['prinOut']=0;
                $pfReport[$country][5]['prinOut']=0;
                $pfReport[$country]['currency']=$row['Currency'];
                $pfReport[$country]['totPrinOut']=0;
                $pfReport[$country]['allTotPrinOut']=0;
                logger('pfreport country not set complete');
            }
            $pfReport[$country]['allTotPrinOut'] +=$row['principleOutstanding'];
            if($row['dueAmountUSD'] <$threshold) {
                logger('in continue condition');
                // this amount will not considor in repayrepot using threshold functionality
                continue;
            }
            $o="SELECT max(id) from ! where loanid = ? AND userid=? ".$repayTableQuery2;
            $maxid=$db->getOne($o,array($repayTable,$row['loanid'],$row['borrowerid']));
            logger('maxid');
            $duedate='';
            if($rid==$maxid) {
                $r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=? ".$repayTableQuery2;
                $duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid']));
                logger('duedate rid equal maxid');
            } elseif(empty($rid)) {
                $r="SELECT duedate from ! where loanid = ? AND userid=? AND amount > ? ".$repayTableQuery2." order by id";
                $duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid'],0));
                logger('duedate rid not empty');
            } else {
                logger('in else with rid');
                $flag=0;
                if(($totpaidamt + $thresholdNative) < $totAmtToRid) {
                    $r="SELECT duedate from ! where loanid = ? AND userid=? AND ".$repayTableQuery3;
                    $duedate=$db->getOne($r,array($repayTable,$row['loanid'],$row['borrowerid'],$rid));
                    logger('duedate less totamtrid');
                } else {
                    $r="SELECT * from ! where loanid = ? AND userid=? ".$repayTableQuery2;
                    $repayAll=$db->getAll($r,array($repayTable,$row['loanid'],$row['borrowerid']));
                    $reducedAmount=round(($totAmtToRid - $totpaidamt),4);
                    foreach($repayAll as $repay) {
                        logger('duedate in else foreach');
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
                    logger('duedate in else out foreach');
                }
            }
            $dateDiff = $date4 - $duedate;
            $fullDays = floor($dateDiff/(60*60*24));
            logger('full days');
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
            logger('j generated');
            $aboutLoan=array();
            $aboutLoan['bname']=$row['FirstName'].' '.$row['LastName'];
            $aboutLoan['days']=$fullDays;
            $aboutLoan['userid']=$row['borrowerid'];
            $aboutLoan['prinOut']=$row['principleOutstanding'];
            logger('aboutloans');
            $pfReport[$country][$j]['prinOut'] +=$row['principleOutstanding'];
            $pfReport[$country][$j]['loans'][] =$aboutLoan;
            $pfReport[$country]['totPrinOut'] +=$row['principleOutstanding'];
        }
        logger('pfreportget');
        return $pfReport;
    }
    function trhistory($date1, $date2,$ord,$opt)
    {
        global $db;
        logger('cal in trfunction');
        $dateArr1  = explode("/",$date1);
        $dateArr2  = explode("/",$date2);
        $date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
        $date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
        traceCalls(__METHOD__, __LINE__);
        $q="SELECT t.id, t.TrDate, t.userid, t.amount, t.txn_desc, t.txn_sub_type, t.loanid, t.conversionrate, t.txn_type, u.userlevel from ! t LEFT OUTER JOIN ! u on t.userid = u.userid where t.TrDate >=? AND t.TrDate <=? AND t.txn_type NOT IN (?,?,?,?) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true) AND IF(t.txn_type = ? AND  t.userid = ?, false, true)  order by t.$opt $ord";
        $result=$db->getAll($q, array('transactions', 'users', $date3, $date4, LOAN_BACK_LENDER, LOAN_BID,LOAN_SENT_LENDER,LOAN_OUTBID, REGISTRATION_FEE, ADMIN_ID, DONATION, ADMIN_ID, PAYPAL_FEE, ADMIN_ID, GIFT_PURCHAGE, ADMIN_ID));
        logger('result generated');
        //Logger_Array('alltransaction', 'get all transaction', $result);
        if(!empty($result)) {
            $names=array();
            $currencies=array();
            $countries=array();
            $bids=array();
            $count_result = count($result);
            for ($i = 0; $i < $count_result ; $i++) {
                logger('in for loop');
                if(array_key_exists($result[$i]['userid'], $names)) {
                    $name=$names[$result[$i]['userid']];
                    logger('name array exist');
                } else {
                    $name=$this->getNameById($result[$i]['userid']);
                    $names[$result[$i]['userid']]=$name;
                    logger('name array not exist');
                }
                if(array_key_exists($result[$i]['userid'], $currencies)) {
                    $currency=$currencies[$result[$i]['userid']];
                    logger('currency array exist');
                } else {
                    $currency=$this->getUserCurrency($result[$i]['userid']);
                    $currencies[$result[$i]['userid']]=$currency;
                    logger('currency array not exist');
                }
                //logger('username'.$name);
                $result[$i]['username']=$name;
                if($result[$i]['txn_type']==FEE || $result[$i]['txn_type']==REFERRAL_DEBIT) {
                    if(array_key_exists($result[$i]['loanid'], $bids)) {
                        $brid=$bids[$result[$i]['loanid']];
                        logger('brid array exist fee or referal debit');
                    } else {
                        $brid=$this->getBorrowerId($result[$i]['loanid']);
                        $bids[$result[$i]['loanid']]=$brid;
                        logger('brid array not exist fee or referal debit');
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
                logger('country get');
                $result[$i]['country'] = $country;
                if($result[$i]['txn_type']==FUND_UPLOAD) {
                    logger('txn fund upload');
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
                        logger('temp1 & temp2');
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
                        logger('temp1 and temp2');
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
                        logger('no temp');
                    }
                }

            }
            logger('result get');
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
        global $db, $session;
        if($up_id==1)
        {
            $q="UPDATE ! set tr_BizDesc = ?, tr_About = ? where userid = ?";
            $res=$db->query($q, array('borrowers',$bizdesc, $about , $id));
            if($res===1)
            {
                $q="UPDATE  ! set tr_loanuse = ?, tr_user=? where borrowerid = ? AND loanid = ?";
                $res=$db->query($q, array('loanapplic',$loanuse, $session->userid, $id, $loanid));
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
            $q="UPDATE ! set tr_message = ?, tr_user=? where id = ?";
            $res=$db->query($q, array('zi_comment',$cmnt, $session->userid, $id));
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

//added by Julia 3-11-2013

    function getAllStaff()
    {
        global $db;
        $q="SELECT userid, FirstName, LastName, Email, trans_Lang from lenders WHERE isTranslator='1' ORDER BY LastName";
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
    function repay_report($date, $country, $assignedto)
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
            /*   commented by Mohit 14-11-13
                          if($assignedto!=0 && $assignedto!=''){
				$q="SELECT b.userid, b.FirstName, b.LastName, b.TelMobile, b.City, b.PAddress, l.loanid FROM ! as b, ! as l, ! as ls, ! as bext WHERE ls.loanid=l.loanid AND b.userid=ls.borrowerid AND b.userid=bext.userid AND l.adminDelete = ? AND b.Country = ? AND bext.mentor_id= ? AND ls.status=".LOAN_ACTIVE." AND ls.startdate < ".$date1." AND (ls.enddate is NULL OR (ls.enddate is NOT NULL AND ls.enddate >=".$date1."))";
				$result=$db->getAll($q, array('borrowers','loanapplic','loanstage','borrowers_extn',0,$country1[$j]));  
				
			} else */
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

                $res= array();
                $res['bname']=$row['FirstName']." ".$row['LastName'];
                $res['duedate']=$duedate;
                $res['userid']=$row['userid'];
                $res['loanid']=$row['loanid'];
                $res['TelMobile'] = $row['TelMobile'];
                $res['totamt']=$totamt;
                $res['totpaidamt']=$totpaidamt;
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

    function getRepayDynamicData($userid){
        global $db;

        $r="SELECT rec_form_offcr_name, rec_form_offcr_num, mentor_id from ! where userid = ?";
        $refdetail = $db->getRow($r,array('borrowers_extn',$userid));

        $query="SELECT expected_repaydate, note FROM ! WHERE borrowerid = ?";
        $repaydetails = $db->getRow($query, array('repay_report_detail',$userid));
        $res= array();
        $res['rec_form_offcr_num'] = $refdetail['rec_form_offcr_num'];
        $res['rec_form_offcr_name'] = $refdetail['rec_form_offcr_name'];
        $res['mentor_id'] = $refdetail['mentor_id'];
        $res['expected_repaydate'] = $repaydetails['expected_repaydate'];
        $res['note'] = $repaydetails['note'];
        return $res;
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

//modified by Julia to allow staff lender accounts to view 4-11-2013

        if($session->userlevel == LENDER_LEVEL || $session->userlevel == ADMIN_LEVEL)
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
    function SetBorrowerReports($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage,$sendername)
    {

        global $db;
        $time=time();
        $message= $emailmessage."<br/>".$sendername."<br/>Zidisha";
        $q = "INSERT into ! (borrower_id,recipient, cc,replyto,subject,message,sent_on) values (?,?,?,?,?,?,?)";
        $data = $db->query($q, array('borrower_reports', $borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$message,$time));
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
    function getExpiredLenderAccounts($duration){
        global $db;
        $accountExpiedMail=0;
        if($duration==LENDER_ACCOUNT_EXPIRE_DURATION){
            $accountExpiedMail=1;
        }
        $yearago = strtotime("-".$duration." months");
        $q="SELECT users.userid, lenders.email,lenders.FirstName, lenders.LastName FROM ! join lenders on lenders.userid = users.userid WHERE last_login < $yearago AND accountExpiedMail = ? AND lenders.Active = 1";
        $lenders= $db->getAll($q, array('users', $accountExpiedMail));
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
    function review_borrower($is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name, $borrowerid, $is_pending_mediation) {
        global $db, $session;
        $q1= "SELECT count(*) FROM ! where borrower_id=?";
        $result=$db->getOne($q1, array('borrower_review',$borrowerid));
        if($result > 0) {
            $q = "UPDATE ! SET is_photo_clear=?, is_desc_clear =?, is_addr_locatable=?, is_number_provided=?, is_nat_id_uploaded=?, is_rec_form_uploaded=?, is_rec_form_offcr_name = ?, modified = ?, modified_by = ?, is_pending_mediation=? WHERE borrower_id = ?";
            $res = $db->query($q, array('borrower_review', $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name, time(), $session->userid, $is_pending_mediation, $borrowerid));

        }else {
            $q="INSERT INTO ! (borrower_id,is_photo_clear,is_desc_clear, is_addr_locatable,is_number_provided,is_nat_id_uploaded,is_rec_form_uploaded,is_rec_form_offcr_name,created	,created_by, is_pending_mediation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $res = $db->query($q, array('borrower_review',$borrowerid, $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_nat_id_uploaded, $is_rec_form_uploaded, $is_rec_form_offcr_name,time(), $session->userid, $is_pending_mediation));

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
        $q1= "SELECT * FROM ! where borrower_id=? AND (is_photo_clear=? || is_desc_clear = ? || is_addr_locatable = ? || is_pending_mediation = ? || is_nat_id_uploaded = ? || is_rec_form_uploaded = ? || is_pending_mediation = ?)";
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

    function add_verify_borrower($partid,$complete_later, $identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid, $verifier_name){
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
            $q1= "insert into ! (borrower_id, complete_later, is_identity_verify, is_participate_verification, is_app_know_zidisha, is_how_contact, is_recomnd_addr_locatable, is_commLead_know_applicant, is_commLead_recomnd_sign, is_commLead_mediate, is_eligible, is_eligible_ByAdmin, additional_comments, verifier_name, created, created_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $result1= $db->query($q1, array('borrower_verification', $borrowerid, $complete_later, $identity_verify, $participate_verification, $app_know_zidisha, $how_contact, $recomnd_addr_locatable, $commLead_know_applicant, $commLead_recomnd_sign, $commLead_mediate, $eligible, $is_eligible_ByAdmin, $additional_comments, $verifier_name, $created, $partid));
            if(!isset($_SESSION['Declined'])){
                $res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, 0, '', '', $complete_later);
            }

            if($result1)
                return 1;
            else
                return 0;
        }
        else{
            $q1= "update ! set complete_later=?, is_identity_verify=?, is_participate_verification=?, is_app_know_zidisha=?, is_how_contact=?, is_recomnd_addr_locatable=?, is_commLead_know_applicant=?, is_commLead_recomnd_sign=?, is_commLead_mediate=?, is_eligible=?, is_eligible_ByAdmin=?, additional_comments=?, verifier_name=?, modified=?, modified_by=? where borrower_id=?";
            $result1= $db->query($q1, array('borrower_verification', $complete_later, $identity_verify, $participate_verification, $app_know_zidisha, $how_contact, $recomnd_addr_locatable, $commLead_know_applicant, $commLead_recomnd_sign, $commLead_mediate, $eligible, $is_eligible_ByAdmin, $additional_comments, $verifier_name, $modified, $partid, $borrowerid));
            if(!isset($_SESSION['Declined'])){
                $q= "select id from ! where userid=? and loneid=? LIMIT 1";
                $cid= $db->getOne($q, array('comments', $borrowerid, 0));
                $res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, $cid, '', '', $complete_later);
            }
            if($result1)
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
            $q1="select * from ! where borrower_id=? AND (is_photo_clear=? || is_desc_clear = ? || is_addr_locatable = ? || is_pending_mediation = ? || is_nat_id_uploaded = ? || is_rec_form_uploaded = ? || is_number_provided = ?)";
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
                //$ontime = $this->isRepaidOntime($brwrid, $row['loanid']);
                if($row['active']!=LOAN_DEFAULTED) {
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

    function verify_borrower_ByAdmin($partid, $is_eligible_ByAdmin, $borrowerid, $verifier_name){
        global $db,$session;
        traceCalls(__METHOD__, __LINE__);
        $created= time();
        $modified= time();
        $additional_comments='';
        $q1= "select * from ! where borrower_id=?";
        $res= $db->getRow($q1, array('borrower_verification', $borrowerid));
        if(empty($res)){
            $q1= "insert into ! (borrower_id, complete_later, is_identity_verify, is_participate_verification, is_app_know_zidisha, is_how_contact, is_recomnd_addr_locatable, is_commLead_know_applicant, is_commLead_recomnd_sign, is_commLead_mediate, is_eligible, is_eligible_ByAdmin, verifier_name, created, created_by) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $result1= $db->query($q1, array('borrower_verification', $borrowerid, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1,$is_eligible_ByAdmin, $verifier_name, $created, $partid));
            $res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, 0, '', '', 0);
            if($res1)
                return 1;
            else
                return 0;
        }
        else{
            $q1= "update ! set complete_later=?, is_identity_verify=?, is_participate_verification=?, is_app_know_zidisha=?, is_how_contact=?, is_recomnd_addr_locatable=?, is_commLead_know_applicant=?, is_commLead_recomnd_sign=?, is_commLead_mediate=?, is_eligible=?, is_eligible_ByPartner=?, verifier_name=?, modified=?, modified_by=? where borrower_id=?";
            $result1= $db->query($q1, array('borrower_verification', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, $is_eligible_ByPartner, $verifier_name, $modified, $partid, $borrowerid));

            $q= "select id from ! where userid=? and loneid=? LIMIT 1";
            $cid= $db->getOne($q, array('comments', $borrowerid, 0));
            $res1=$this->activateBorrower($partid, $borrowerid, $additional_comments, 0, $cid, '', '', 0);
            if($res1)
                return 1;
            else
                return 0;
        }
    }

    function getPendingEndorsementBorrower(){
        global $db;
        $q= 'select b.userid, b.FirstName,b.LastName,b.City,b.Country, b.LastModified, b.Created from ! as b join ! as b_ext on b.userid=b_ext.userid where b.Active=? AND b.iscomplete_later = ? AND b.Assigned_status=? AND b_ext.fb_data<>?';
        $pending = $db->getALL($q, array('borrowers', 'borrowers_extn', 0, 0, 0, ''));
        return $pending;
    }

    function getEndorserRecived($borrowerid){
        global $db;
        $q="select endorserid, ename from ! where borrowerid=? and endorserid<>?";
        $endorser= $db->getAll($q, array('endorser', $borrowerid, ''));
        return $endorser;
    }

//added by Julia 15-11-2013

    function canDisplayEndorser($borrowerid){
        global $db;
        $q="select count(*) from ! where borrowerid=? and e_candisplay>0";
        $res= $db->getOne($q, array('endorser', $borrowerid));
        return $res;
    }

    function getEndorser(){
        global $db;
        $q="select brwr.userid, brwr.FirstName,brwr.LastName,brwr.Country, brwr.LastModified, brwr.Created from ! as brwr join ! as en on brwr.userid= en.endorserid";
        $endorser= $db->getAll($q, array('borrowers', 'endorser'));
        return $endorser;
    }
    function getFacebookInfo($date1, $date2){
        global $db;
        $dateArr1  = explode("/",$date1);
        $dateArr2  = explode("/",$date2);
        $date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
        $date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
        $q="select * from ! where date >=? AND date <=? order by id desc";
        $fbusers= $db->getAll($q, array('facebook_info', $date3, $date4));
        return $fbusers;
    }

//added by Julia 22-11-2013

    function getActivationInfo($date1, $date2){
        global $db;
        $dateArr1  = explode("/",$date1);
        $dateArr2  = explode("/",$date2);
        $date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
        $date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
        $q="select * from ! where date >=? AND date <=? order by date";
        $fbusers= $db->getAll($q, array('facebook_info', $date3, $date4));
        return $fbusers;
    }


    function getActivatedBorrowers($date1, $date2, $fb, $invite, $text, $firstpmt){
        global $db;

//date selection
        $dateArr1  = explode("/",$date1);
        $dateArr2  = explode("/",$date2);
        $date3=mktime(0,0,0,(int)$dateArr1[0],(int)$dateArr1[1],(int)$dateArr1[2]);
        $date4=mktime(23,59,59,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);

        if($firstpmt==1){


            $q="SELECT repaymentschedule.userid FROM repaymentschedule INNER JOIN (SELECT userid, min(duedate) as min_duedate FROM repaymentschedule GROUP BY userid) AS table_mindue ON repaymentschedule.userid = table_mindue.userid JOIN ! ON borrowers.userid=repaymentschedule.userid WHERE active = 1 AND completed_on >=? AND completed_on <=?";
          
   
        }

//Facebook link status
        elseif ($fb==1){

            $q="SELECT * FROM ! LEFT JOIN borrowers_extn as bext on bext.userid=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND bext.fb_data IS NULL order by completed_on";
         
   
        }elseif ($fb==2){

            $q="SELECT * FROM ! LEFT JOIN borrowers_extn as bext on bext.userid=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND bext.fb_data IS NOT NULL order by completed_on";
            
   
        }

//invite status
        elseif ($invite==1){

            $q="SELECT * FROM ! LEFT JOIN invites as inv on inv.invitee_id=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND inv.invitee_id IS NOT NULL order by completed_on";
            
   
        }elseif ($invite==2){

            $q="SELECT borrowers.userid, borrowers.Country, borrowers.completed_on FROM ! LEFT JOIN invites as inv on inv.invitee_id=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND inv.invitee_id IS NULL order by completed_on";
           
   
        }elseif ($invite==3){

            $q="SELECT DISTINCT borrowers.userid, borrowers.Country, borrowers.completed_on FROM ! LEFT JOIN invites as inv on inv.userid=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND inv.userid IS NOT NULL order by completed_on";
            
   
        }elseif ($invite==4){

            $q="SELECT borrowers.userid, borrowers.Country, borrowers.completed_on FROM ! LEFT JOIN invites as inv on inv.userid=borrowers.userid WHERE active = 1 AND completed_on >=? AND completed_on <=? AND inv.userid IS NULL order by completed_on";
            
   
        }

//"how did you hear about zidisha" text field length
        elseif ($text==1){

            $q="SELECT * FROM ! WHERE active = 1 AND completed_on >=? AND completed_on <=? AND length(reffered_by) > 0 AND length(reffered_by) < 10 order by completed_on";
            
   
        }elseif ($text==2){

            $q="SELECT * FROM ! WHERE active = 1 AND completed_on >=? AND completed_on <=? AND length(reffered_by) >= 10 AND length(reffered_by) < 50 order by completed_on";
            
   
        }elseif ($text==3){

            $q="SELECT * FROM ! WHERE active = 1 AND completed_on >=? AND completed_on <=? AND length(reffered_by) >= 50 AND length(reffered_by) < 100 order by completed_on";
            
   
        }elseif ($text==4){

            $q="SELECT * FROM ! WHERE active = 1 AND completed_on >=? AND completed_on <=? AND length(reffered_by) >= 100 order by completed_on";
            

        }


//if no other filter selected, show all results within date range
        else {

            $q="SELECT * FROM ! WHERE active = 1 AND completed_on >=? AND completed_on <=? order by completed_on";
            
        }
        
        $res= $db->getAll($q, array('borrowers', $date3, $date4));
        return $res;
    }


    function getAcceptBidNote($loanid){
        global $db;
        $q="select accept_bid_note from ! where loanid=?";
        $note= $db->getOne($q, array('loanapplic', $loanid));
        return $note;
    }
    function UpdateExpectedRepayDate($userid,$loanid){
        global $db;
        $q="update ! set expected_repaydate=? where borrowerid=?";
        $res=$db->query($q, array('repay_report_detail', NULL, $userid));
        return $res;
    }

    function getAllFundedLoans($country){
        global $db;
        if($country=='AA'){
            $q="select l.loanid, l.borrowerid,l.p_amount, l.AcceptDate, b.FirstName, b.LastName, b.City, b.nationId, b.TelMobile, b.Country, l.accept_bid_note from ! as l join ! as b on l.borrowerid=b.userid where l.active=? AND l.adminDelete=?";
            $res= $db->getAll($q, array('loanapplic', 'borrowers', LOAN_FUNDED, 0));
        }else{
            $q="select l.loanid, l.borrowerid, l.AcceptDate,l.p_amount, b.FirstName, b.LastName, b.City, b.nationId, b.TelMobile, b.Country, l.accept_bid_note from ! as l join ! as b on l.borrowerid=b.userid where l.active=? AND b.Country=? AND l.adminDelete=?";
            $res= $db->getAll($q, array('loanapplic', 'borrowers', LOAN_FUNDED, $country, 0));
        }
        return $res;
    }
    function getDisbursedNotes($loanid){
        global $db;
        $q="select disbursement_notes from ! where loanid=?";
        $notes=$db->getOne($q, array('loan_notes', $loanid));
        return $notes;
    }
    function savedisbursednote($userid, $loanid, $note){
        global $db;
        $q="select id from ! where loanid=? AND userid=?";
        $id= $db->getOne($q, array('loan_notes', $loanid, $userid));
        if(!empty($id)){
            $q1="UPDATE ! set disbursement_notes=? where id=?";
            $result= $db->query($q1, array('loan_notes', $note, $id));
        }else{
            $q2="insert into !(loanid, userid, disbursement_notes) values(?,?,?)";
            $result=$db->query($q2, array('loan_notes', $loanid, $userid, $note));
        }
        return $result;
    }
//added by Julia 21-10-2013
    function find_lenders($sort='FirstName', $dir='asc', $start=0, $limit='', $country, $search)
    {
        global $db;
        if($sort=='FirstName')
            $sort='sortname';
        if($country=='AA'){
            if(empty($search)){
                $q="SELECT lenders.userid, regdate, last_login, lenders.FirstName, lenders.LastName, lenders.City, lenders.Country, lenders.Email, lenders.Active, concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as sortname, users.username  FROM ! join users on users.userid = lenders.userid where users.userid NOT IN(SELECT userid from ! where emailVerified=?) order by ".$sort." ".$dir." limit ".$start.", ".$limit;
                $result=$db->getAll($q,array('lenders', 'users', 0));
            }else{
                $q="SELECT lenders.userid, regdate, last_login, lenders.FirstName, lenders.LastName, lenders.City, lenders.Country, lenders.Email, lenders.Active, concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as sortname, users.username  FROM ! join users on users.userid = lenders.userid where users.userid NOT IN(SELECT userid from ! where emailVerified=?) AND (users.username like '%$search%' OR concat(lenders.FirstName, ' ', lenders.Lastname) like '%$search%' OR concat(lenders.LastName, ' ', lenders.FirstName) like '%$search%' OR lenders.City like '%$search%' OR lenders.Country like '%$search%' OR lenders.Email like '%$search%') order by ".$sort." ".$dir." limit ".$start.", ".$limit;
                $result=$db->getAll($q,array('lenders', 'users', 0));
            }
        }else{
            if(empty($search)){
                $q="SELECT lenders.userid, regdate, last_login, lenders.FirstName, lenders.LastName, lenders.City, lenders.Country, lenders.Email, lenders.Active, concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as sortname, users.username  FROM ! join users on users.userid = lenders.userid where users.userid NOT IN(SELECT userid from ! where emailVerified=?) AND (lenders.Country like '$country') order by ".$sort." ".$dir." limit ".$start.", ".$limit;
                $result=$db->getAll($q,array('lenders', 'users', 0));
            }else{
                $q="SELECT lenders.userid, regdate, last_login, lenders.FirstName, lenders.LastName, lenders.City, lenders.Country, lenders.Email, lenders.Active, concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as sortname, users.username  FROM ! join users on users.userid = lenders.userid where users.userid NOT IN(SELECT userid from ! where emailVerified=?) AND (lenders.Country like '$country') AND (users.username like '%$search%' OR concat(lenders.FirstName, ' ', lenders.Lastname) like '%$search%' OR concat(lenders.LastName, ' ', lenders.FirstName) like '%$search%' OR lenders.City like '%$search%' OR lenders.Country like '%$search%' OR lenders.Email like '%$search%') order by ".$sort." ".$dir." limit ".$start.", ".$limit;
                $result=$db->getAll($q,array('lenders', 'users', 0));
            }
        }
        return $result;
    }


    function find_borrowers($country, $brwr_type, $search){
        global $db;
        if($country=='AA'){
            if($brwr_type=='all'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b group by b.userid";
                    $result=$db->getAll($q,array('borrowers'));
                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%' group by b.userid";
                    $result=$db->getAll($q,array('borrowers'));
                }
            }
            elseif($brwr_type=='endorser'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.endorser=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1));
                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.endorser=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1));
                }
            }
            elseif($brwr_type=='pndng_sub'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, u.emailVerified, b.iscomplete_later, u.facebook_id, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.endorser<>? group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 1));
                    foreach($result1 as $row){
                        $endorser_count= $this->IsEndorsedComplete($row['userid']);
                        if(($row['Created']>'1370995200' && $row['facebook_id']!='' && $endorser_count<$this->getAdminSetting('MinEndorser')) || $row['iscomplete_later']==1 || $row['emailVerified']==0){
                            $result[]=$row;
                        }
                    }

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, u.emailVerified, b.iscomplete_later, u.facebook_id, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.endorser<>? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 1));
                    foreach($result1 as $row){
                        $endorser_count= $this->IsEndorsedComplete($row['userid']);
                        if(($row['Created']>'1370995200' && $row['facebook_id']!='' && $endorser_count<$this->getAdminSetting('MinEndorser')) || $row['iscomplete_later']==1 || $row['emailVerified']==0){
                            $result[]=$row;
                        }
                    }
                }
            }
            elseif($brwr_type=='pndng_act'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 0, 1, 2, 1));
                    foreach($result1 as $row){
                        if($row['Created']>'1370995200'){
                            $user_fb= $this->IsFacebook_connect($row['userid']);
                            if($user_fb){
                                $endorser_count= $this->IsEndorsedComplete($row['userid']);
                                if($endorser_count>=$this->getAdminSetting('MinEndorser')){
                                    $result[]=$row;
                                }
                            }else{
                                $result[]= $row;
                            }
                        }else{
                            $result[]= $row;
                        }
                    }

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 0, 1, 2, 1));
                    foreach($result1 as $row){
                        if($row['Created']>'1370995200'){
                            $user_fb= $this->IsFacebook_connect($row['userid']);
                            if($user_fb){
                                $endorser_count= $this->IsEndorsedComplete($row['userid']);
                                if($endorser_count>=$this->getAdminSetting('MinEndorser')){
                                    $result[]=$row;
                                }
                            }else{
                                $result[]= $row;
                            }
                        }else{
                            $result[]= $row;
                        }
                    }

                }
            }
            elseif($brwr_type=='decline'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Assigned_status=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 2));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Assigned_status=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 2));

                }
            }
            elseif($brwr_type=='active'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Active=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Active=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1));

                }
            }
        }else{
            if($brwr_type=='all'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Country=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', $country));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Country=? AND concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%' group by b.userid";
                    $result=$db->getAll($q,array('borrowers', $country));

                }
            }
            elseif($brwr_type=='endorser'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.endorser=? AND b.Country=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1, $country));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.endorser=? AND b.Country=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1, $country));

                }
            }
            elseif($brwr_type=='pndng_sub'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, u.emailVerified, b.iscomplete_later, u.facebook_id, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.endorser<>? AND b.Country=? group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 1, $country));
                    foreach($result1 as $row){
                        $endorser_count= $this->IsEndorsedComplete($row['userid']);
                        if(($row['Created']>'1370995200' && $row['facebook_id']!='' && $endorser_count<$this->getAdminSetting('MinEndorser')) || $row['iscomplete_later']==1 || $row['emailVerified']==0){
                            $result[]=$row;
                        }

                    }
                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, u.emailVerified, b.iscomplete_later, u.facebook_id, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.endorser<>? AND b.Country=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 1, $country));
                    foreach($result1 as $row){
                        $endorser_count= $this->IsEndorsedComplete($row['userid']);
                        if(($row['Created']>'1370995200' && $row['facebook_id']!='' && $endorser_count<$this->getAdminSetting('MinEndorser')) || $row['iscomplete_later']==1 || $row['emailVerified']==0){
                            $result[]=$row;
                        }

                    }
                }
            }
            elseif($brwr_type=='pndng_act'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? AND b.Country=? group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 0, 1, 2, 1, $country));
                    foreach($result1 as $row){
                        if($row['Created']>'1370995200'){
                            $user_fb= $this->IsFacebook_connect($row['userid']);
                            if($user_fb){
                                $endorser_count= $this->IsEndorsedComplete($row['userid']);
                                if($endorser_count>=$this->getAdminSetting('MinEndorser')){
                                    $result[]=$row;
                                }
                            }else{
                                $result[]= $row;
                            }
                        }else{
                            $result[]= $row;
                        }
                    }

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, b.Created, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b join ! as u on b.userid=u.userid where b.iscomplete_later=? AND b.Active<>? AND b.Assigned_status<>? AND u.emailVerified=? AND b.Country=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result1=$db->getAll($q,array('borrowers', 'users', 0, 1, 2, 1, $country));
                    foreach($result1 as $row){
                        if($row['Created']>'1370995200'){
                            $user_fb= $this->IsFacebook_connect($row['userid']);
                            if($user_fb){
                                $endorser_count= $this->IsEndorsedComplete($row['userid']);
                                if($endorser_count>=$this->getAdminSetting('MinEndorser')){
                                    $result[]=$row;
                                }
                            }else{
                                $result[]= $row;
                            }
                        }else{
                            $result[]= $row;
                        }
                    }

                }
            }
            elseif($brwr_type=='decline'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Assigned_status=? AND b.Country=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 2, $country));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Assigned_status=? AND b.Country=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 2, $country));

                }
            }
            elseif($brwr_type=='active'){
                if(empty($search)){
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Active=? AND b.Country=? group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1, $country));

                }else{
                    $q="SELECT b.userid, b.FirstName, b.LastName, b.PAddress, b.City, b.Country, b.TelMobile, b.Email,b.Active, b.lastVisited, b.admin_notes, concat(REPLACE(b.`FirstName`,' ',''), REPLACE(b.`LastName`,' ','')) as sortname FROM ! as b where b.Active=? AND b.Country=? AND (concat(b.FirstName, ' ', b.Lastname) like '%$search%' || concat(b.LastName, ' ', b.FirstName) like '%$search%' || b.TelMobile like '%$search%' || b.Email like '%$search%') group by b.userid";
                    $result=$db->getAll($q,array('borrowers', 1, $country));

                }
            }

        }
        for ($i = 0; $i < count($result) ; $i++) {
            $q="SELECT lastVisited, note from ! WHERE borrowerid = ?";
            $res=$db->getRow($q,array('repay_report_detail',$result[$i]['userid']));
            $result[$i]['admin_notes'] = $res['note'];
            $result[$i]['lastVisited'] = $res['lastVisited'];
        }
        return $result;
    }

    function getTranslateUser($loanid){
        global $db;
        $q="select tr_user from ! where loanid=?";
        $user= $db->getOne($q, array('loanapplic', $loanid));
        return $user;
    }
    function updateAuthStatus($auth_date,$borrowerid,$loanid,$pamount)
    {
        global $db;

        $r = "UPDATE ! set  auth_date=?,p_amount=? WHERE loanid = ? and borrowerid =?";
        $res1=$db->query($r, array('loanapplic',$auth_date,$pamount,$loanid,$borrowerid));
        return $res1;
    }

    function getAuthActive($loanid)
    {
        global $db;
        $q= "select auth_date from ! where loanid=?";
        $result= $db->getOne($q, array('loanapplic', $loanid));
        return $result;
    }
    /* -------------------Admin Section End----------------------- */


    /* -------------------Borrower Section Start----------------------- */

    function addBorrower($uname, $namea, $nameb, $pass1, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $bnationid, $language, $community_name_no,$documents,$repaidPast, $debtFree, $share_update, $completeLater, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member,$volunteer_mentor, $fb_data, $endorser_name, $endorser_email)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        if(!empty($fb_data['user_profile'])){
            $facebook_id= $fb_data['user_profile']['id'];
            $fb_data=  base64_encode(serialize($fb_data));
        }else{
            $facebook_id= '';
            $fb_data='';
        }
        $salt = $this->makeSalt();
        $pass= $this->makePassword($pass1, $salt);
        $currency=$this->getCurrencyIdByCountryCode($country);
        $q="INSERT INTO ! (username, password, salt, userlevel, regdate, lang,emailVerified, facebook_id) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
        $res = $db->query($q, array('users', $uname, $pass, $salt, BORROWER_LEVEL, $time, $language,0, $facebook_id));
        if($res === DB_OK)
        {	$onbehalfId = 0;
            if($onbehalf) {
                $query="INSERT INTO ! (name, contact_no, email, town) VALUES (?, ?, ?, ?)";
                $res = $db->query($query, array('on_borrower_behalf', $behalf_name, $behalf_number, $behalf_email, $behalf_town));
                $onbehalfId = mysql_insert_id();
            }
            $q="SELECT userid FROM ! WHERE username=?";
            $userid=$db->getOne($q, array('users', $uname));
            $q="INSERT INTO ! (userid, FirstName, LastName, PAddress, City, Country, TelMobile,Email, AnnualIncome, About, BizDesc, Active, activeloan, currency, nationId, communityNameNo, borrower_behalf_id, islastrepaid, isdebtfree, share_update, iscomplete_later, Created,completed_on, LastModified,home_location, refer_member_name,reffered_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?)";

            $res1=$db->query($q, array('borrowers', $userid, $namea, $nameb, $post, $city, $country, $mobile,$email, $income, $about, $bizdesc, 0, NO_LOAN, $currency, $bnationid, $community_name_no, $onbehalfId, $repaidPast, $debtFree, $share_update, $completeLater, $time ,$time ,$time, $home_no, $refer_member,$reffered_by));

            if($res1=== DB_OK)
            {
                $q="INSERT INTO ! (userid, family_member1, family_member2, family_member3, neighbor1, neighbor2, neighbor3, rec_form_offcr_name, rec_form_offcr_num, created, mentor_id, fb_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $res2 = $db->query($q, array('borrowers_extn', $userid, $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$rec_form_offcr_name, $rec_form_offcr_num, time(), $volunteer_mentor, $fb_data));

                if(!empty($endorser_name) && !empty($endorser_email)){
                    for($i=0; $i<10; $i++){
                        if(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){
                            $v_code= mt_rand(0, 32).time();
                            $q2="insert into ! (borrowerid, ename, e_email, validation_code) values(?, ?, ?, ?)";
                            $res3=$db->query($q2, array('endorser', $userid, $endorser_name[$i], $endorser_email[$i], $v_code));
                            $q4= "SELECT id FROM ! where borrowerid=? and e_email=? and validation_code=?";
                            $row_id= $db->getOne($q4, array('endorser', $userid, $endorser_email[$i], $v_code));
                            $q5="select validation_code from ! where id=?";
                            $res4= $db->getOne($q5, array('endorser', $row_id));
                            $uniq_code= md5($res4).$row_id;
                            $q6="update ! set validation_code=? where id=?";
                            $res5=$db->query($q6, array('endorser', $uniq_code, $row_id));
                        }
                    }
                }
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

    function updateBorrower($uname, $namea, $nameb, $pass1, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $id, $bnationid,  $language, $community_name_no, $repaidPast, $debtFree,$share_update,$onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town,$borrower_behalf_id, $completeLater, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3, $home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member,  $volunteer_mentor, $fb_data, $endorser_name, $endorser_email, $endorser_id)
    {
        global $db, $session;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        $res = DB_OK;
        $old_status = $this->getBorrowerActive($id);
		// beacause country name disable on edit profile page by mohit 19-12-13
		$qry="SELECT Country FROM ! WHERE userid=?";
        $country=$db->getOne($qry,array('borrowers',$id));
        $currency=$this->getCurrencyIdByCountryCode($country);
        if(!empty($fb_data['user_profile'])){
            $facebook_id= $fb_data['user_profile']['id'];
            $fb_data= base64_encode(serialize($fb_data));
        }else{
            $facebook_id= '';
            $fb_data='';
        }
        $q="SELECT TelMobile FROM ! WHERE userid=?";
        $oldTelMobile=$db->getOne($q,array('borrowers',$id));
        if(!empty($pass1))
        {
            $salt = $this->makeSalt();
            $pass= $this->makePassword($pass1, $salt);
            $q = "UPDATE ! SET password = ?, salt=?, lang= ?, facebook_id=? WHERE userid = ?";
            $res = $db->query($q, array('users', $pass, $salt, $language, $facebook_id, $id));
        }
        else
        {
            $q = "UPDATE ! SET lang= ?, facebook_id=? WHERE userid = ?";
            $r=$db->query($q, array('users', $language, $facebook_id, $id ));
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
                $q = "UPDATE ! SET FirstName=?, LastName=?, PAddress=? ,City=?, Country=?, TelMobile=?, reffered_by=?, Email=?, AnnualIncome= ?, About=?, BizDesc=?, currency=?, nationId=?, communityNameNo=?, islastrepaid=?,isdebtfree=?,iscomplete_later=?,borrower_behalf_id=?,share_update=?, completed_on = ?, LastModified=?, home_location=?, refer_member_name=?, endorser=? WHERE userid = ?";
                $r=$db->query($q, array('borrowers', $namea, $nameb, $post, $city, $country, $mobile, $reffered_by,$email, $income, $about, $bizdesc, $currency, $bnationid, $community_name_no, $repaidPast, $debtFree, $completeLater,$onbehalfId, $share_update, $completedon, $time, $home_no, $refer_member, 0, $id));

            }else {

                $q = "UPDATE ! SET  About=?, BizDesc=?,borrower_behalf_id=?, LastModified=?,reffered_by=?, Email=? WHERE userid = ?";
                $r=$db->query($q, array('borrowers', $about, $bizdesc, $onbehalfId, $time,$reffered_by, $email, $id));
            }
            if($r=== DB_OK)
            {
                if(!$isActive) {
                    $q = "UPDATE ! SET family_member1=?,family_member2=?,family_member3=?, neighbor1=?, neighbor2=?, neighbor3=?, rec_form_offcr_name = ?, rec_form_offcr_num = ?, modified=?, mentor_id=?, fb_data=? WHERE userid = ?";
                    $r=$db->query($q, array('borrowers_extn', $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3, $rec_form_offcr_name, $rec_form_offcr_num, time(),$volunteer_mentor, $fb_data, $id));

                    if(!empty($endorser_name) && !empty($endorser_email)){
                        for($i=0; $i<10; $i++){
                            if(!empty($endorser_id[$i])){
                                $q3="update ! set e_email=?, ename=? where id=?";								$r=$db->query($q3, array('endorser', $endorser_email[$i], $endorser_name[$i], $endorser_id[$i]));
                            }elseif(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){
                                $v_code=mt_rand(0, 32).time();
                                $q2="insert into ! (borrowerid, ename, e_email, validation_code) values(?, ?, ?, ?)";
                                $res3=$db->query($q2, array('endorser', $id, $endorser_name[$i], $endorser_email[$i], $v_code));
                                $q4= "SELECT id FROM ! where borrowerid=? and e_email=? and validation_code=?";
                                $row_id= $db->getOne($q4, array('endorser', $id, $endorser_email[$i], $v_code));
                                $q5="select validation_code from ! where id=?";
                                $res4= $db->getOne($q5, array('endorser', $row_id));
                                $uniq_code= md5($res4).$row_id;
                                $q6="update ! set validation_code=? where id=?";
                                $res5=$db->query($q6, array('endorser', $uniq_code, $row_id));
                            }
                        }
                    }
                }else {
                    $q = "UPDATE ! SET rec_form_offcr_name = ?, rec_form_offcr_num = ?, modified=?, mentor_id=?, fb_data=? WHERE userid = ?";
                    $r=$db->query($q, array('borrowers_extn', $rec_form_offcr_name, $rec_form_offcr_num, time(),$volunteer_mentor, $fb_data, $id));
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

       function additionalVerification($id, $fb_data, $endorser_name, $endorser_email, $endorser_id)
    {
        global $db, $session;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        $res = DB_OK;
        $old_status = $this->getBorrowerActive($id);
        if(!empty($fb_data['user_profile'])){
            $facebook_id= $fb_data['user_profile']['id'];
            $fb_data= base64_encode(serialize($fb_data));
        }else{
            $facebook_id= '';
            $fb_data='';
        }
        
        $q = "UPDATE ! SET facebook_id=? WHERE userid = ?";
        $r=$db->query($q, array('users', $facebook_id, $id));
                
        if($res == DB_OK)
        {

            $q = "UPDATE ! SET fb_data=? WHERE userid = ?";
            $r=$db->query($q, array('borrowers_extn', $fb_data, $id));

            if(!empty($endorser_name) && !empty($endorser_email)){
                for($i=0; $i<10; $i++){
                    if(!empty($endorser_id[$i])){
                        $q3="update ! set e_email=?, ename=? where id=?";                               
                        $r=$db->query($q3, array('endorser', $endorser_email[$i], $endorser_name[$i], $endorser_id[$i]));
                    }elseif(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){
                        $v_code=mt_rand(0, 32).time();
                        $q2="insert into ! (borrowerid, ename, e_email, validation_code) values(?, ?, ?, ?)";
                        $res3=$db->query($q2, array('endorser', $id, $endorser_name[$i], $endorser_email[$i], $v_code));
                        $q4= "SELECT id FROM ! where borrowerid=? and e_email=? and validation_code=?";
                        $row_id= $db->getOne($q4, array('endorser', $id, $endorser_email[$i], $v_code));
                        $q5="select validation_code from ! where id=?";
                        $res4= $db->getOne($q5, array('endorser', $row_id));
                        $uniq_code= md5($res4).$row_id;
                        $q6="update ! set validation_code=? where id=?";
                        $res5=$db->query($q6, array('endorser', $uniq_code, $row_id));
                    }
                }
            }
                
            include_once("indexer.php");
            updateIndex1($id);

            return 0;//successful insert
        }
        else{
            return 1;//unsuccessful insert
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
        $q="SELECT loanid FROM ! WHERE borrowerid=? AND (active=3 OR active=5 OR active=2 ) "; // Added OR active=2 by mohit to use in current credit limit
        $result=$db->getOne($q,array('loanapplic',$borrowerid));
        //$lid=$result['loanid']; // Commented by Pranjal 02/10/13
        $lid=$result;

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
                //$ontime = $this->isRepaidOntime($borrowerid, $row['loanid']);
                if($row['active']!=LOAN_DEFAULTED) {
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
    function loanApplication($borrowerid, $amount, $interest, $period, $gperiod, $loanuse, $tnc, $weekly_inst, $loan_installmentDate, $loan_installmentDay)
    {
        global $db;
        $time=time();
        $webfee=$this->getAdminSetting('fee');//website fee rate
        traceCalls(__METHOD__, __LINE__);
        $damt = round($amount/$this->getCurrentRate($borrowerid));
        $q="INSERT INTO loanapplic (borrowerid, Amount, interest, period, grace, loanuse, active, applydate, tnc, AmountGot, WebFee, reqdamt, weekly_inst, installment_day, installment_weekday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result=$db->query($q,array($borrowerid, $amount, $interest, $period, $gperiod, $loanuse, LOAN_OPEN, $time, $tnc, $amount , $webfee, $damt, $weekly_inst, $loan_installmentDate, $loan_installmentDay) );
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
        global $db, $session;
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
                else if($sort==7)
                {
                    foreach($result as $key=>$row)
                    {
                        $repayrate[$key]=$session->RepaymentRate($row['userid']);

                        $totalTodayinstallment[$key]=$session->totalTodayinstallment($row['userid']);

                        $bfrstloan=$this->getBorrowerFirstLoan($row['userid']);
                        if($bfrstloan==0){
                            $repayrate[$key]=0;
                        }
                    }
                    array_multisort($repayrate, SORT_DESC, $totalTodayinstallment, SORT_DESC, $result);
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
    function getTotalPayment($userid=0, $loanid)
    {
        global $db;
        $where='';
        if(!empty($userid)){
            $where="AND userid='$userid'";
        }
        $q="SELECT sum(amount) as amttotal, sum(paidamt) as paidtotal FROM  ! WHERE loanid = ? $where";
        $result=$db->getRow($q, array('repaymentschedule', $loanid));
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
            $activation_comment= $this->getActivationComment($brid);
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
                $count=count($result1);
                if(!$activation_comment){
                    $f=($f-1);
                    $count=(count($result1)-1);
                    if($f==0){
                        $report['feedback']='';
                    }else{
                        $f=($f*100)/$count;
                        $report['feedback']=$f;
                    }
                }else{
                    $f=($f*100)/$count;
                    $report['feedback']=$f;
                }
                $partid=$profile1['partid'];
                $report['sincedate']=$ldate;
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
        $sql = "SELECT loanid FROM ! WHERE borrowerid  = ? AND adminDelete = ? AND (active=? OR active=?) AND expires is NULL AND RepaidDate IS NOT NULL ORDER BY loanid DESC ";
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


    function getPreviousLoanAmount($borrowerid, $excludeLoanIds)
    {
        global $db;
        $rate=$this->getCurrentRate($borrowerid);
        $firstloan=$this->getBorrowerFirstLoan($borrowerid);
        if($firstloan==0)
        {
            /* it means it is first loan */
            $val=$this->getAdminSetting('firstLoanValue');
            $resultNative=convertToNative($val, $rate);
            return $resultNative;
        }
        else
        {
            /* it means it is next loan */

            // update by mohit on date 3-11-13 to fix get max loan amount credit from last loan  set expires=NULL 

            if(!empty($excludeLoanIds)) {

                $excludeLoanIds = implode(',', $excludeLoanIds);
                $q="SELECT max(AmountGot) from ! where borrowerid = ? AND active = ? AND adminDelete =? AND expires is NULL AND loanid NOT IN(".$excludeLoanIds.")";
                $amount=$db->getOne($q,array('loanapplic',$borrowerid, LOAN_REPAID, 0));
            
                if(!empty($amount) && $amount > 1){

                    $resultNative=$amount;

                }else{
                    $val=$this->getAdminSetting('firstLoanValue');
                    $resultNative=convertToNative($val, $rate);
                    
                }

            } else {

                $q="SELECT max(AmountGot) from ! where borrowerid = ? AND active = ? AND adminDelete =? AND expires is NULL";
                $resultNative=$db->getOne($q,array('loanapplic',$borrowerid, LOAN_REPAID, 0));
  
            }
            
            return $resultNative;

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
        $p = "select b.currency, l.AcceptDate from ! as b join ! as l on b.userid= l.borrowerid where l.loanid = ?";
        $res=$db->getRow($p, array('borrowers','loanapplic', $loan_id));
        $rate=$this->getExRateByDate($res['AcceptDate'], $res['currency']);
        return $rate;
    }
    function isAnyLoanIsNonFunded()
    {
        global $db, $session;
        /* NOTE: Important      please donot modify following queries if it is required then check indexer.php in library for the same queries*/
        $limit = rand(400000,1000000);
        $oftype= "loanapplic.active=".LOAN_OPEN." AND "  ;
        $q="SELECT loanapplic.borrowerid, loanapplic.loanid FROM ".TBL_BORROWER.", ".TBL_LOANAPPLIC." WHERE ".$oftype." borrowers.userid=loanapplic.borrowerid AND loanapplic.adminDelete = ? ORDER BY RAND()";
        $result=$db->getAll($q, array(0));
        if(empty($result))
        {
            return false;
        }
        else
        {
            $flag=0;
            foreach($result as $row)
            {
                $percentFunded=$session->getStatusBar($row['borrowerid'],$row['loanid'],3);
                if($percentFunded !='100%')
                    $flag=1;
            }
            if($flag==1)
                return true;
            else
                return false;
        }
    }
    function getBorrowerCurrentLoanStatus($userid)
    {
        global $db;
        $q="SELECT active from ! where borrowerid =? AND adminDelete =? order by loanid desc";
        $loan_status =$db->getOne($q,array('loanapplic',$userid, 0));
        if(!empty($loan_status))
        {
            return $loan_status;
        }
        return NO_LOAN;
    }
    function reschedule($loan_id,$borrower_id,$reschedule_reason, $period)
    {
        global $db;
        $date=time();
        $q = "insert into ! (loan_id, borrower_id,reschedule_reason,period,date) values (?, ?, ?, ?, ?)";
        $res1=$db->query($q, array('reschedule', $loan_id, $borrower_id, $reschedule_reason, $period, $date));

        if($res1===DB_OK)
        {
            $q="SELECT id FROM ! WHERE borrower_id =? AND loan_id= ? AND date=?";
            $id=$db->getOne($q, array('reschedule', $borrower_id,$loan_id, $date));
            return $id;
        }
        else
            return 0;
    }
    function setReschedule($borrower_id, $loanid,$reschedule_id,$reSchedule)
    {
        global $db;
        $p="SELECT * FROM ! WHERE userid=? AND loanid= ?";
        $res1=$db->getAll($p, array('repaymentschedule', $borrower_id,$loanid));
        if(!empty($res1))
        {
            foreach($res1 as $row)
            {
                $q = "insert into ! (repaymentschedule_id, reschedule_id, userid, loanid,duedate, amount, paiddate, paidamt) values (?, ?, ?, ?, ?, ?, ?, ?)";
                $res2=$db->query($q, array('repaymentschedule_history', $row['id'], $reschedule_id, $row['userid'], $row['loanid'], $row['duedate'], $row['amount'], $row['paiddate'], $row['paidamt']));
            }
            $q="SELECT count(*) FROM ! WHERE reschedule_id =? AND loanid= ? AND userid=?";
            $count1=$db->getOne($q, array('repaymentschedule_history', $reschedule_id,$loanid, $borrower_id));
            if(!empty($reSchedule) && $count1==count($res1))
            {

                $q="SELECT count(*) FROM ! WHERE loanid= ? AND userid=?";
                $count2=$db->getOne($q, array('repaymentschedule',$loanid, $borrower_id));
                if($count2 > count($reSchedule)) {
                    foreach($reSchedule as $row1) {
                        $repaySchdIds[] = $row1['id'];
                        $repayids = implode(',',$repaySchdIds);
                    }
                    $repayids = implode(',',$repaySchdIds);
                    $q = "DELETE FROM ! WHERE userid=? AND loanid = ? AND id NOT IN ( ".$repayids." )";
                    $res3=$db->query($q, array('repaymentschedule',$borrower_id,$loanid));
                    //Anupam 1-7-2012 no more deleting rows from history table since when we calculate principal/interest and other calculation it will directly effect and may lead to wrong data. 
                    //$q = "DELETE FROM ! WHERE userid=? AND loanid = ? AND id NOT IN ( ".$repayids." )";
                    //$res3=$db->query($q, array('repaymentschedule_history', $borrower_id, $loanid));

                }
                foreach($reSchedule as $row1)
                {
                    if(isset($row1['id']) && isset($row1['update']) && $row1['update']==1)
                    {
                        $r = "UPDATE ! set amount=?, paidamt=?, paiddate=? WHERE userid=? AND loanid= ? AND id=?";
                        $res3=$db->query($r, array('repaymentschedule', $row1['amount'],$row1['paidamt'],$row1['paiddate'],$borrower_id,$loanid,$row1['id']));
                    }
                    if(!isset($row1['id']))
                    {
                        $s = "insert into ! (userid, loanid, duedate, amount, paiddate, paidamt) values (?, ?, ?, ?, ?, ?)";
                        $res4=$db->query($s, array('repaymentschedule', $borrower_id,$loanid, $row1['duedate'], $row1['amount'], $row1['paiddate'], $row1['paidamt']));
                    }

                }
                if(1)
                {
                    $q="SELECT * FROM ! WHERE loanid= ? AND userid=? order by id";
                    $oldRepayActual=$db->getAll($q, array('repaymentschedule_actual',$loanid, $borrower_id));

                    $q="SELECT * FROM ! WHERE loanid= ? AND userid=? order by id desc";
                    $res5=$db->getRow($q, array('repaymentschedule_actual',$loanid, $borrower_id));
                    if(!empty($res5))
                    {

                        foreach ($oldRepayActual as $oldRepay)
                        {
                            $q = "insert into ! (repaymentschedule_actual_id, rid, reschedule_id, userid, loanid, paiddate, paidamt) values (?, ?, ?, ?, ?, ?, ?)";
                            $res6=$db->query($q, array('repaymentschedule_actual_history', $oldRepay['id'], $oldRepay['rid'], $reschedule_id, $oldRepay['userid'], $oldRepay['loanid'], $oldRepay['paiddate'], $oldRepay['paidamt']));
                        }
                        $q="SELECT id FROM ! WHERE loanid= ? AND userid=? AND paiddate=?  order by id desc";
                        $rid=$db->getOne($q, array('repaymentschedule',$loanid, $borrower_id, $res5['paiddate']));

                        $r = "UPDATE ! set rid=? WHERE userid=? AND loanid= ? AND id=?";
                        $res7=$db->query($r, array('repaymentschedule_actual', $rid,$borrower_id,$loanid,$res5['id']));
                        if($res7===DB_OK)
                        {
                            $q="SELECT * FROM ! WHERE loanid= ? AND userid=? AND rid=? AND id <? order by id desc";
                            $res8=$db->getAll($q, array('repaymentschedule_actual',$loanid, $borrower_id, $res5['rid'],$res5['id']));
                            if(!empty($res8))
                            {
                                $q="SELECT * FROM ! WHERE loanid= ? AND userid=? AND paidamt IS NOT NULL AND paidamt >=?  order by id desc";
                                $res9=$db->getAll($q, array('repaymentschedule',$loanid, $borrower_id, 0));

                                $amount=$res5['paidamt'];
                                foreach($res8 as $row8)
                                {
                                    $amount1=0;
                                    foreach($res9 as $row9)
                                    {
                                        $amount1 +=$row9['paidamt'];
                                        if($amount1 >=$amount)
                                        {
                                            $r = "UPDATE ! set rid=? WHERE userid=? AND loanid= ? AND id=?";
                                            $res11=$db->query($r, array('repaymentschedule_actual', $row9['id'],$borrower_id,$loanid,$row8['id']));
                                            $amount +=$row8['paidamt'];
                                            break;
                                        }
                                    }
                                }
                            }
                            return 1;
                        }
                        else
                            return 0;
                    }
                    return 1;
                }
            }
        }
        return 0; /* reschedule failed*/
    }
    function getTotalPaid($loanid,$borrowerid)
    {
        global $db;
        $q="SELECT SUM(paidamt) as totpaidamt from ! where loanid = ? AND userid=?";
        $totpaidamt=$db->getOne($q,array('repaymentschedule_actual',$loanid,$borrowerid));
        return $totpaidamt;
    }
    function canBorrowerReSchedule($borrowerid, $loanid)
    {
        global $db;
        $q="SELECT count(*) from ! where loan_id = ? AND borrower_id=?";
        $count=$db->getOne($q,array('reschedule',$loanid,$borrowerid));
        $reschAllow=$this->getAdminSetting('RescheduleAllow');
        if($count < $reschAllow)
        {
            return true;
        }
        return false;
    }
    function getRescheduleDataByLoanId($loan_id)
    {
        global $db;
        $p="SELECT * from ! where loan_id = ? Order by id desc";
        $rescheduleResult=$db->getRow($p,array('reschedule',$loan_id));
        return $rescheduleResult;
    }
    function getRescheduleDateByLoan($loan_id)
    {
        global $db;
        $p="SELECT date from ! where loan_id = ? Order by id desc";
        $rescheduleResult=$db->getOne($p,array('reschedule',$loan_id));
        return $rescheduleResult;
    }
    function updateLoanPeriod($borrower_id, $loanid, $period, $new_period)
    {
        global $db;
        $q="SELECT * from ! where loanid = ? AND borrowerid=?";
        $res=$db->getRow($q,array('loanapplic',$loanid,$borrower_id));
        $res1=false;
        if($res['original_period']==0)
        {
            $r = "UPDATE ! set period=?, original_period=? where loanid = ? AND borrowerid=?";
            $res1=$db->query($r, array('loanapplic', $new_period, $period, $loanid,$borrower_id));
        }
        else
        {
            $r = "UPDATE ! set period=? where loanid = ? AND borrowerid=?";
            $res1=$db->query($r, array('loanapplic', $new_period, $loanid,$borrower_id));
        }
        if($res1)
            return true;
        else
            return false;
    }
    function getForgiveAmount($borrower_id, $loanid)
    {
        global $db;
        $t="SELECT sum(amount) from ! where loan_id = ? AND borrower_id=?";
        $forgiveAmount=$db->getOne($t,array('forgiven_loans',$loanid,$borrower_id));
        return $forgiveAmount;
    }
    function getForgiveAmountbydate($borrower_id, $loanid, $date)
    {
        global $db;
        $t="SELECT sum(amount) from ! where loan_id = ? AND borrower_id=? AND date < ?";
        $forgiveAmount=$db->getOne($t,array('forgiven_loans',$loanid,$borrower_id,$date));
        return $forgiveAmount;
    }
    function getForgiveAmountUsd($borrower_id, $loanid)
    {
        global $db;
        $t="SELECT sum(damount) from ! where loan_id = ? AND borrower_id=?";
        $forgiveAmount=$db->getOne($t,array('forgiven_loans',$loanid,$borrower_id));
        return $forgiveAmount;
    }
    function getPrincipalRatio($loanid, $date=0)
    {
        global $db;
        $q="SELECT sum(amount) amt from repaymentschedule where loanid = $loanid";
        $all=$db->getOne($q);

        $forgiveAmount=0;
        if($date) {
            $p="SELECT id,date from ! where loan_id = ? AND date >= ? order by id asc";
            $forgivenResult=$db->getRow($p,array('forgiven_loans',$loanid, $date));

            $p="SELECT id,date from ! where loan_id = ? AND date >= ? order by id asc";
            $rescheduleResult=$db->getRow($p,array('reschedule',$loanid,$date));
            if(!empty($forgivenResult) || !empty($rescheduleResult)) {
                $flag1=$flag2=0;
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
                    $p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND forgiven_loans_id = ?";
                    $all=$db->getOne($p,array('repaymentschedule_history',$loanid,$forgivenResult['id']));
                } elseif($flag2==1) {

                    $p="SELECT SUM(amount) as totamount1 from ! where loanid = ? AND reschedule_id = ?";
                    $all=$db->getOne($p,array('repaymentschedule_history',$loanid,$rescheduleResult['id']));

                }
            }
            $s="SELECT sum(amount) from ! where loan_id = ? AND date < ?";
            $forgiveAmount=$db->getOne($s,array('forgiven_loans',$loanid, $date));
        } else {
            $s="SELECT sum(amount) from ! where loan_id = ? ";
            $forgiveAmount=$db->getOne($s,array('forgiven_loans',$loanid));
        }
        if($forgiveAmount) {
            $all +=$forgiveAmount;
        }
        $q="SELECT amountGot from loanapplic where loanid = $loanid ";
        $principal =$db->getOne($q);
        $ratio = $principal/$all;
        return round($ratio, 4);
    }
    function getDefaultedLoanid($borrowerid)
    {
        global $db;
        $p = "SELECT loanid from ! where borrowerid =? AND active =? AND adminDelete =?";
        $res = $db->getOne($p, array('loanapplic', $borrowerid, LOAN_DEFAULTED , 0));
        return $res;
    }
    function getRescheduleIdFromComment($id)
    {
        global $db;
        $p = "SELECT reschedule_id from ! where id =? AND reschedule_id >=?";
        $res = $db->getOne($p, array('zi_comment', $id, 0));
        return $res;
    }
    function getRescheduleData($id)
    {
        global $db;
        $p="SELECT * from ! where id = ? ";
        $rescheduleResult=$db->getRow($p,array('reschedule',$id));
        return $rescheduleResult;
    }
    function getRandomOpenBorrower()
    {
        global $db, $session;
        $limit = rand(400000,1000000);
        $q="SELECT * FROM ! as b join ! as l on b.userid=l.borrowerid  WHERE l.active=? AND l.adminDelete = ? ORDER BY applydate ";
        $result=$db->getAll($q, array('borrowers','loanapplic',LOAN_OPEN,0));
        if(count($result)>2)
        {
            $rtn=array();
            $backup=array();
            $rtn1 = array();
            foreach($result as $row)
            {
                $status = $session->getStatusBar($row['userid'],$row['loanid'], 5);
                $islastloanid = $this->getLastRepaidloanId($row['userid']);
                //31-Jan-2013 Anupam added for non-repeat borrowers selected at random
                if($status < 100 && $islastloanid)
                    $rtn[]=$row;
                else if($status < 100 && !$islastloanid) {
                    $rtn1[] = $row;
                }else {
                    if(empty($backup))
                        $backup=$row;
                }
                if(count($rtn)==2)
                    break;
            }
            if(empty($rtn)&& !empty($rtn1) && count($rtn1) >=2 ) {
                return $rtn1;
            }
            else if(!empty($rtn))
            {
                if(count($rtn)==1 && !empty($backup))
                    $rtn[]=$backup;
                return $rtn;
            }

        }
        return $result;
    }
    function updateLoanApp($userid, $loanid, $amount, $interest, $loanuse, $inst_day, $gperiod, $weekly_inst, $inst_weekday)
    {
        global $db;
        $p="SELECT applydate  from ! where loanid = ? AND borrowerid=? ";
        $applydate=$db->getOne($p,array('loanapplic',$loanid, $userid));
        $rate=$this->getExRateById($applydate, $userid);
        $damount=$amount/$rate;
        $r = "UPDATE ! set Amount =?, interest =?, loanuse =?, reqdamt =?,  installment_day=?, grace =?, weekly_inst=?, installment_weekday=? where loanid = ? AND borrowerid=?";
        $res=$db->query($r, array('loanapplic', $amount, $interest,$loanuse, $damount,$inst_day, $gperiod, $weekly_inst, $inst_weekday, $loanid, $userid));
        return $res;
    }
    function getMinMaxBidIntr($loanid)
    {
        global $db;
        $p="SELECT MAX(bidint) from ! where loanid = ? ";
        $maxBidIntr=$db->getOne($p,array('loanbids',$loanid));
        $p="SELECT MIN(bidint) from ! where loanid = ? ";
        $minBidIntr=$db->getOne($p,array('loanbids',$loanid));
        $bidsIntr= array();
        $bidsIntr['min']=$minBidIntr;
        $bidsIntr['max']=$maxBidIntr;
        return $bidsIntr;
    }
    function getTotalBidAmount($loanid)
    {
        global $db;
        $p="SELECT SUM(bidamount) from ! where loanid = ? ";
        $bidamount=$db->getOne($p,array('loanbids',$loanid));
        return $bidamount;
    }
    function isRescheduledBeforeDate($loanid, $date)
    {
        global $db;
        $p="SELECT count(id) from ! where loan_id = ? AND date < ?";
        $count=$db->getOne($p,array('reschedule',$loanid,$date));
        if($count)
            return true;
        else
            return false;
    }
    function getRescheduledLoans()
    {
        global $db;
        $p="SELECT * from ! where 1 order by loan_id, date";
        $res=$db->getAll($p,array('reschedule'));
        return $res;
    }
    function getOriginalLoanPeriod($borrower_id, $loanid)
    {
        global $db;
        $r = "Select original_period FROM ! where loanid = ? AND borrowerid=?";
        $res1=$db->getOne($r, array('loanapplic', $loanid, $borrower_id));
        return $res1;
    }
    function gerGracePeriod($loanid)
    {
        global $db;
        $r = "Select grace FROM ! where loanid =? ";
        $grace=$db->getOne($r, array('loanapplic', $loanid));
        return $grace;
    }
    function getLastDueDate($loanid)
    {
        global $db;
        $q = "select MAX(duedate) from ! where loanid = ?";
        $duedate = $db->getOne($q, array('repaymentschedule', $loanid));
        return $duedate;
    }
    function getNextDueDate($loanid)
    {
        global $db;
        $time=time();
        $q = "select duedate from ! where loanid = ? AND duedate >? AND paidamt IS NULL AND amount > ?";
        $duedate = $db->getOne($q, array('repaymentschedule', $loanid, $time, 0));
        return $duedate;
    }
    function getLoanDisburseDate($loanid)
    {
        global $db;
        $q = "select TrDate from ! where loanid = ? AND txn_type =?";
        $date = $db->getOne($q, array('transactions', $loanid, DISBURSEMENT));
        return $date;
    }
    function setLoanStage($loanid, $borrowerid, $status, $startdate, $oldstatus=null, $enddate=null, $createddate=null)
    {

        global $db;
        $created = date('Y-m-d G:i:s', time());
        $modified = date('Y-m-d G:i:s', time());
        if(!empty($createddate)) {
            $created = date('Y-m-d G:i:s', $createddate);
        }
        $q = "select id from ! where loanid = ? AND borrowerid =? AND status =?";
        $id = $db->getOne($q, array('loanstage', $loanid, $borrowerid, $status));
        if(empty($id)) {
            $q = "insert into ! (loanid, borrowerid, status, startdate, enddate, created) values (?, ?, ?, ?, ?, ?)";
            $res1=$db->query($q, array('loanstage', $loanid, $borrowerid, $status, $startdate, $enddate, $created));
        }
        else {
            $q = "update ! set startdate=?, enddate=?, modified=? where id =?";
            $res1=$db->query($q, array('loanstage', $startdate, $enddate, $modified, $id));
        }
        if($oldstatus !==null) {
            $q = "update ! set enddate=?, modified=? where loanid = ? AND borrowerid =? AND status =?";
            $res1=$db->query($q, array('loanstage', $startdate, $created, $loanid, $borrowerid, $oldstatus));
        }
    }
    function revertLoanStage($loanid, $borrowerid, $status, $oldstatus=null)
    {
        global $db;
        $query="delete from ! where loanid = ? AND borrowerid =? AND status =?";
        $result=$db->query($query,array('loanstage', $loanid, $borrowerid, $status));
        if($oldstatus !==null) {
            $q = "update ! set enddate=? where loanid = ? AND borrowerid =? AND status =?";
            $res1=$db->query($q, array('loanstage', null, $loanid, $borrowerid, $oldstatus));
        }
    }
    function getAllActiveBorrowers()
    {
        global $db;
        $q="SELECT b.userid, b.FirstName, b.LastName ,l.loanid  FROM ".TBL_BORROWER." as b, ".TBL_LOANAPPLIC." as l WHERE l.active=".LOAN_ACTIVE. " AND b.userid=l.borrowerid AND l.adminDelete = ? ORDER BY Firstname";
        $result=$db->getAll($q, array(0));
        return $result;

    }
    function getBorrowerIdByloanid($loanid)
    {
        global $db;
        $q="SELECT borrowerid  FROM ! WHERE loanid=?";
        $result=$db->getOne($q, array('loanapplic',$loanid));
        return $result;

    }
    function setForgiveLoan($loanid , $borrowerId , $comment, $validation_code) {
        global $db;
        $time=time();
        $q = "insert into ! (borrowerid, loanid, comment, time, validation_code, reminder_sent) values (?, ?, ?, ?, ?, ?)";
        $res1=$db->query($q, array(' loans_to_forgive', $borrowerId, $loanid, $comment, $time, $validation_code, 0));
        return $res1;
    }
    function isInForgiveLoan($loanid)
    {
        global $db;
        $time=time();
        $q="SELECT count(*) as count  FROM ! WHERE loanid=?";
        $result=$db->getOne($q, array('loans_to_forgive',$loanid));
        return $result;
    }
    function getAllLoanForgiveness()
    {
        global $db;
        $time=time();
        $q="SELECT b.userid, b.FirstName, b.LastName,l.loanid,l.comment,l.time  FROM ! as l , ! as b where l.borrowerid=b.userid";
        $result=$db->getAll($q, array('loans_to_forgive',' borrowers'));
        return $result;
    }
    function getInstallmentDate($loanid)
    {
        global $db;
        $q="SELECT installment_day  FROM ! WHERE loanid=?";
        $result=$db->getOne($q, array('loanapplic',$loanid));
        return $result;
    }
    function getLoanExtraPeriod($uid, $loanid)
    {
        global $db;
        $extraPeriod=0;
        $q="SELECT extra_days FROM ! WHERE loanid=? AND borrowerid=? AND adminDelete = ?";
        $result=$db->getOne($q , array('loanapplic', $loanid , $uid, 0));
        if($result!=null && $result >0) {
            $extraPeriod=round(($result/30), 4);
        }
        return $extraPeriod;
    }

    function getInstallmentByLoanid($loanid)
    {
        global $db;
        $q="SELECT  sum(amount) as amount  , sum(paidamt) as paidamount FROM ! WHERE loanid=? AND amount > ? AND paidamt is NULL";
        $results=$db->getRow($q , array('repaymentschedule', $loanid , 0));

        $q1="SELECT count(*) as numrows FROM ! WHERE loanid=? AND amount > ? AND paidamt is NULL";
        $numrows=$db->getOne($q1 , array('repaymentschedule', $loanid , 0));
        // 21 Mar 2013 khushboo strange mail :profile Guinea . 
        if(empty($results)) {
            $q="SELECT  * FROM ! WHERE loanid=? order by id desc";
            $results=$db->getRow($q , array('repaymentschedule', $loanid));
            $TotalRemainingAmt = ceil($results['amount']-$results['paidamt']);
            $numrows=1;
        } else {
            //  21 Mar 2013 khushboo strange mail :profile Guinea . 
            $q="select amount, paidamt from ! where paidamt is NOT NULL AND loanid=? AND amount>? order by id desc";
            $res= $db->getRow($q, array('repaymentschedule', $loanid , 0));
            $remainingAmt=0;
            if($res['amount']> $res['paidamt']){
                $remainingAmt= $res['amount']- $res['paidamt'];
            }
            //$TotalRemainingAmt=ceil($results['amount']-$results['paidamount']);
            $TotalRemainingAmt=ceil($results['amount']+$remainingAmt);
        }
        return $TotalRemainingAmt/$numrows;
    }
    function getNonDisbursedLoanIds()
    {
        global $db;
        $q="SELECT group_concat(loanid) as loanids from ! WHERE active IN (?, ?) AND adminDelete=?";
        $loanIds=$db->getOne($q , array('loanapplic', LOAN_OPEN, LOAN_FUNDED, 0));
        $loanIds = explode(',', $loanIds);
        return $loanIds;
    }

    function getLoansToExpireMail() {
        global $db;
        $q="SELECT loanid, reqdamt, interest, WebFee, applydate, borrowerid FROM ! WHERE active=? AND adminDelete = ?";
        $result= $db->getAll($q, array('loanapplic', LOAN_OPEN, 0));
        return $result;
    }
    function setExpirymailSent($loanid) {
        global $db;
        $q = "update ! set expiry_mail = ? where loanid = ?";
        $res1=$db->query($q, array('loanapplic', 1, $loanid));
    }
    function getReferralCountries() {
        global $db;
        $q="SELECT group_concat(country) FROM ! WHERE status=? ";
        $countries= $db->getOne($q, array('referrals', 1));
        if(!$countries) {
            return 0;
        }

        return $countries;
    }
    function setcommentcredit($userid, $commentlength, $loanid, $commentid) {
        global $db,$database;
        $commentcreditdetail = $this->getcreditLimitbyuser($userid, 1);
        if(!empty($commentcreditdetail)) {
            if($commentcreditdetail['character_limit'] <= $commentlength) {
                $commentcount = $database->getcommentpostedcount($userid, $loanid);
                if($commentcount < $commentcreditdetail['comments_limit']) {
                    $res = $this->setCreditearned($userid, $loanid, 1, $commentid, $commentcreditdetail['loanamt_limit']);/* for comment credit type we take 1 as third argument*/
                }
            }
        }

        return 1;
    }
    function getcreditLimitbyuser($userid, $type) {
        global $db;
        $countrycode = $this->getCountryCodeById($userid);
        $q="SELECT id, loanamt_limit, character_limit, comments_limit from ! WHERE country_code = ? AND type = ?";
        $detail= $db->getRow($q, array('credit_setting', $countrycode, $type));
        if(!$detail) {
            return 0;
        }
        return $detail;
    }
    function getcommentpostedcount($userid, $loanid) {
        global $db;
        $query = "select count(id) from ! where borrower_id = ? AND  loan_id = ? AND  credit_type = ?";
        $commentcount = $db->getOne($query,array('credits_earned',$userid, $loanid, 1));
        return $commentcount;
    }	/**/
    function setCreditearned($userid, $loanid, $credit_type, $ref_id, $credit) {
        global $db;
        $time=time();
        $q = "insert into ! (borrower_id, loan_id, credit_type, ref_id, credit, created) values (?, ?, ?, ?, ?, ?)";
        $res1=$db->query($q, array('credits_earned', $userid, $loanid, $credit_type, $ref_id, $credit, $time));
        if($res1==DB_OK)
            return 1;
        return 0;
    }

    function setOntimeRepayCredit($repayids, $userid, $amount) {
        global $db,$database;
        $credit_amount = 0;
        $time = time();
        if(!empty($repayids)) {
            $Ontimecreditdetail = $this->getcreditLimitbyuser($userid, 2);
            $credit_amount = $Ontimecreditdetail['loanamt_limit'];
            $CurrencyRate = $this->getCurrentRate($userid);
            $thresholdamount = convertToNative(REPAYMENT_AMT_THRESHOLD, $CurrencyRate);
            $loanid = $this->getCurrentLoanid($userid);
            $isontime = $this->isAllInstallmentOnTime($userid, $loanid);
            if($isontime['missedInst'] == 0) {
                $query = "SELECT count(*) FROM ! WHERE borrower_id = ? AND loan_id = ? AND credit_type = 2";
                $count = $db->getOne($query, array('credits_earned', $userid, $loanid));
                if($count == 0) {
                    $this->setCreditearned($userid, $loanid, 2, end($repayids), $credit_amount);
                }

            }else if($isontime['missedInst'] > 0){
                $query = "SELECT count(*) FROM ! WHERE borrower_id = ? AND loan_id = ? AND credit_type = 2";
                $count = $db->getOne($query, array('credits_earned', $userid, $loanid));
                if($count == 0) {
                    $this->setCreditearned($userid, $loanid, 2, end($repayids), 0);
                }else {
                    $q1 = "UPDATE ! SET  credit = ?, modified = ? WHERE   borrower_id = ? AND loan_id = ? AND credit_type = ?";
                    $res = $db->query($q1, array('credits_earned', 0,$time, $userid, $loanid, 2));
                }
            }
        }
    }
    function isInstallmentOnTime($userid, $loanid, $paiddate, $amount) {
        global $db,$database;
        $q="SELECT duedate, amount FROM ! WHERE userid = ? AND loanid = ? AND (paidamt NOT  NULL OR paidamt > ?) order by id DESC";
        $ontime= $db->getRow($q, array('repaymentschedule', $userid, $loanid, 0));
        //if($paiddate >$ontime['duedate']);
    }
    function isAllInstallmentOnTime($userid, $loanid) {
        global $db,$database;
        $schedule = $this->getSchedulefromDB($userid, $loanid);
        $actualSchedule = $this->getRepaySchedulefromDB($userid, $loanid);
        $paidBalance = 0;
        $today= time();
        for($i = 0, $j=0; $i < count($schedule); $i++) {
            $flag=0;
            $printSchedule[$i]['dueAmt']=$schedule[$i]['amount'];
            $printSchedule[$i]['dueDate']=$schedule[$i]['duedate'];
            $inst=0;
            $inst=$schedule[$i]['amount'];
            while($paidBalance >0)
            {
                if($inst >0)
                {
                    if($inst <= $paidBalance)
                    {
                        $printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$inst;
                        $paidBalance=number_format(($paidBalance-$inst), 6, '.', '');
                        $inst=0;
                        break;
                    }
                    else
                    {
                        $printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$paidBalance;
                        $inst = number_format(($inst - $paidBalance), 6, '.', '');
                        $paidBalance=0;
                    }
                }
                else
                    break;
            }
            if($paidBalance==0)
            {
                for($k=0; $j < count($actualSchedule); $j++)
                {
                    if($inst >0)
                    {
                        if($inst <= $actualSchedule[$j]['paidamt'])
                        {
                            $printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$inst;
                            $paidBalance=number_format(($actualSchedule[$j]['paidamt']-$inst), 6, '.', '');
                            $j++;
                            $flag=1;
                            break;
                        }
                        else
                        {

                            $printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$actualSchedule[$j]['paidamt'];
                            $inst = number_format(($inst - $actualSchedule[$j]['paidamt']), 6, '.', '');
                            $flag=1;
                        }
                    }
                    else
                    {
                        break;
                    }
                }
                if($inst >0 && $flag!=1 &&($today - $printSchedule[$i]['dueDate']) > 86400*REPAYMENT_THRESHOLD){
                    $printSchedule[$i]['sub'][][$today]=$inst;
                }
            }
        }
        $totalinstall =0;
        $missedInst = 0;
        if(!empty($printSchedule)) {
            foreach($printSchedule as $repayschedule) {
                if(isset($repayschedule['sub'])) {
                    $thresholdamount = 0;
                    $multipartInst = 0;
                    $totpaidins = 0;
                    for($l=0; $l < count($repayschedule['sub']);$l++) {
                        $paid_date = key($repayschedule['sub'][$l]);
                        $paid_amt = $repayschedule['sub'][$l][$paid_date];
                        $CurrencyRate = $this->getExRateById($paid_date,$userid);
                        $thresholdamount = convertToNative(REPAYMENT_AMT_THRESHOLD, $CurrencyRate);
                        if(($paid_date - $repayschedule['dueDate']) > 86400*REPAYMENT_THRESHOLD) {
                            $multipartInst+=$paid_amt;

                        }
                        $totpaidins+=$paid_amt;
                    }
                    $due_amt = $repayschedule['dueAmt'];
                    if($due_amt - $totpaidins < $thresholdamount) {
                        $totalinstall++;
                    }
                    if($multipartInst > $thresholdamount) {
                        $missedInst++;
                    }
                }
            }
        }
        /* Comment by mohit 18-10-13
		As per Julia mail The percentage is not supposed to go down due to forgiveness. Can you please repair such that the on time repayment is always calculated accurately regardless of whether the loan is forgiven?

		$isforgiven = $this->totalForgivenLendersThisLoan($loanid);
		if($isforgiven > 0) {
			$missedInst++;
		}*/
        $totalTodayinstallment= $this->getTotalInstalToday($loanid, $userid);

        $retarr['loanid'] = $loanid;
        $retarr['totalTodayinstallment'] = $totalTodayinstallment;
        $retarr['TotalInstlment'] = $totalinstall;
        $retarr['missedInst'] = $missedInst;
        return $retarr;
    }



    function RepaymentOverduedetail($userid, $loanid) {
        global $db,$database;
        $ret = array();
        $schedule = $this->getSchedulefromDB($userid, $loanid);
        $actualSchedule = $this->getRepaySchedulefromDB($userid, $loanid);
        $paidBalance = 0;
        for($i = 0, $j=0; $i < count($schedule); $i++) {
            $printSchedule[$i]['dueAmt']=$schedule[$i]['amount'];
            $printSchedule[$i]['dueDate']=$schedule[$i]['duedate'];
            $inst=0;
            $inst=$schedule[$i]['amount'];
            while($paidBalance >0)
            {
                if($inst >0)
                {
                    if($inst <= $paidBalance)
                    {
                        $printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$inst;
                        $paidBalance=number_format(($paidBalance-$inst), 6, '.', '');
                        $inst=0;
                        break;
                    }
                    else
                    {
                        $printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$paidBalance;
                        $inst = number_format(($inst - $paidBalance), 6, '.', '');
                        $paidBalance=0;
                    }
                }
                else
                    break;
            }
            if($paidBalance==0)
            {
                for($k=0; $j < count($actualSchedule); $j++)
                {
                    if($inst >0)
                    {
                        if($inst <= $actualSchedule[$j]['paidamt'])
                        {
                            $printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$inst;
                            $paidBalance=number_format(($actualSchedule[$j]['paidamt']-$inst), 6, '.', '');
                            $j++;
                            break;
                        }
                        else
                        {
                            $printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$actualSchedule[$j]['paidamt'];
                            $inst = number_format(($inst - $actualSchedule[$j]['paidamt']), 6, '.', '');
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }

        }
        if(!empty($printSchedule)) {
            $currtime = time();
            $overdueInst = 0;
            $overdueAmt = 0;
            $dueamt = 0;
            foreach($printSchedule as $repayschedule) {
                $totalamtpaid = 0;
                if(isset($repayschedule['sub'])) {
                    for($l=0; $l < count($repayschedule['sub']);$l++) {
                        $paid_date = key($repayschedule['sub'][$l]);
                        $paid_amt = $repayschedule['sub'][$l][$paid_date];
                        $totalamtpaid+=$paid_amt;

                    }
                    if($repayschedule['dueDate'] < $currtime) {
                        $dueamt += $repayschedule['dueAmt'] - $totalamtpaid;
                    }

                }
                if(!isset($repayschedule['sub']) && $repayschedule['dueDate'] < $currtime && $repayschedule['dueAmt'] > 0) {
                    $overdueAmt += $repayschedule['dueAmt'];
                    $overdueInst++;

                }

            }
            $ret['overdueAmt'] = bcadd($overdueAmt,$dueamt,0);
            $ret['overdueInst'] = $overdueInst;
        }

        return $ret;
    }
    function CreditonCurrentLoan($userid, $loanid, $type) {
        global $db,$database;
        $q="SELECT count(id) as commentposted, SUM(credit) as credit FROM ! WHERE borrower_id = ? AND loan_id = ? AND credit_type = ?";
        $creditearned = $db->getRow($q, array('credits_earned', $userid, $loanid, $type));
        if($type==2) {
            $isforgiven = $this->totalForgivenLendersThisLoan($loanid);
            if($isforgiven > 0) {
                $creditearned['credit']=0;
            }
        }
        return $creditearned;
    }
    function getLastduedatebyloanid($userid, $loanid) {
        global $db,$database;
        $r="SELECT max(duedate) as duedate from ! where loanid = ? AND userid=?";
        $duedate=$db->getOne($r,array('repaymentschedule', $loanid, $userid));
        return $duedate;
    }
    function getCreditEarned($borrowerid) {
        global $db,$database, $session;
        $loanid = $this->getLastRepaidloanId($borrowerid);
        $creditearned=0;
        if(!empty($loanid)){
            $r="SELECT SUM(credit) as creditearned from ! where loan_id = ? AND borrower_id=?";
            $creditearned = $db->getOne($r,array('credits_earned', $loanid, $borrowerid));
            $isforgiven = $this->totalForgivenLendersThisLoan($loanid);
            if($isforgiven > 0) {
                $q="SELECT SUM(credit) as credit FROM ! WHERE borrower_id = ? AND loan_id = ? AND credit_type = ?";
                $ontimecreditearned = $db->getOne($q, array('credits_earned', $borrowerid, $loanid, 2));
                $creditearned=$creditearned-$ontimecreditearned;
            }
        }
        $invitees= $this->getInvitedMember($borrowerid);
        foreach($invitees as $invite){
            $inviterepayrate= $session->RepaymentRate($invite['invitee_id']);
            $invite_lastloan= $this->getLastloan($invite['invitee_id']);
            if(empty($invite_lastloan)){
                $inviterepayrate=0;
            }
            $minrepayrate=$this->getAdminSetting('MinRepayRate');
            if($inviterepayrate>=$minrepayrate){
                $country=$this->getCountryCodeById($borrowerid);
                $binvitecredit=$this->getcreditsettingbyCountry($country,3);
                $creditearned+=$binvitecredit['loanamt_limit'];
            }
        }
        return $creditearned;
    }



    function getCurrentCreditEarned($borrowerid, $loanid, $type) {
        global $db,$database;
        if($type == 'Total') {	/* update on 12-12-13 by Mohit As per julia email: we are no longer offering any bonuses for 100% on-time repayment */
            $r="SELECT SUM(credit) as creditearned from ! where loan_id = ? AND borrower_id=? AND credit_type NOT In(?)";
            $creditearned = $db->getOne($r,array('credits_earned', $loanid, $borrowerid, 2));
        }else {
            $r="SELECT SUM(credit) as creditearned from ! where loan_id = ? AND borrower_id=? AND credit_type = ?";
            $creditearned = $db->getOne($r,array('credits_earned', $loanid, $borrowerid, $type));
        }
        if($type=='Total') {
            $isforgiven = $this->totalForgivenLendersThisLoan($loanid);
            if($isforgiven > 0) {
                $q="SELECT SUM(credit) as credit FROM ! WHERE borrower_id = ? AND loan_id = ? AND credit_type = ?";
                $ontimecreditearned = $db->getOne($q, array('credits_earned', $borrowerid, $loanid, 2));
                $creditearned=$creditearned-$ontimecreditearned;
            }
        }
        return $creditearned;
    }
    function getPreviousCommentLength($parentid) {
        global $db,$database;
        $r="SELECT CHAR_LENGTH(message) from ! where id = ?";
        $msglngth = $db->getOne($r,array('zi_comment', $parentid));
        return $msglngth;
    }
    function getcreditsettingbyCountry($countrycode, $type) {
        global $db;
        $q="SELECT * from ! WHERE country_code = ? AND type = ?";
        $detail= $db->getRow($q, array('credit_setting', $countrycode, $type));
        if(!$detail) {
            return 0;
        }
        return $detail;
    }
    function getBrwrAndLoanStatus($brwrid) {
        global $db,$session;
        $brwrandLoandetail=array();
        $brwrandLoandetail['iscomplete_later']='';
        $brwrandLoandetail['brwrActive']='';
        $brwrandLoandetail['loanid']='';
        $brwrandLoandetail['loanActive']='';
        $brwrandLoandetail['percentFunded']='';
        $brwrandLoandetail['overdue']='';
        $brwrandLoandetail['overdueAmt']='';
        $brwrandLoandetail['datedisbursed']='';
        $brwrandLoandetail['repaidPercent']='';
        $brwrandLoandetail['Assigned_status']='';
        $q="SELECT iscomplete_later, Active, Assigned_status from ! WHERE userid = ? ";
        $bdetail= $db->getRow($q, array('borrowers', $brwrid));
        if(!empty($bdetail)) {
            $brwrandLoandetail['iscomplete_later']=$bdetail['iscomplete_later'];
            $brwrandLoandetail['brwrActive']=$bdetail['Active'];
            $brwrandLoandetail['Assigned_status']=$bdetail['Assigned_status'];
        }

        $sql = "SELECT loanid,active FROM ! WHERE borrowerid  = ? AND adminDelete = ? ORDER BY loanid DESC ";
        $loandetail = $db->getRow($sql, array('loanapplic', $brwrid, 0));
        if(!empty($loandetail)) {
            $brwrandLoandetail['loanid']=$loandetail['loanid'];
            $brwrandLoandetail['loanActive']=$loandetail['active'];
            $percentFunded=$session->getStatusBar($brwrid,$loandetail['loanid'],5);
            $brwrandLoandetail['percentFunded'] = $percentFunded;
        }
        if($brwrandLoandetail['loanActive'] == LOAN_ACTIVE) {
            $overdue = $this->RepaymentOverduedetail($brwrid, $loandetail['loanid']);
            $brwrandLoandetail['overdue']=$overdue['overdueInst'];
            $brwrandLoandetail['overdueAmt'] = $overdue['overdueAmt'];
            $datedisb = $this->getLoanDisburseDate($loandetail['loanid']);
            $res= $this->getTotalPayment($brwrid, $loandetail['loanid']);
            if($res['amttotal']==0) /*  this case is when loan is funded and schedule is not generaetd */
                $brwrandLoandetail['repaidPercent']= 0;
            else
                $repaidPercent = $res['paidtotal']/$res['amttotal']*100;
            $brwrandLoandetail['repaidPercent'] = round($repaidPercent,0);
            $brwrandLoandetail['datedisbursed'] = $datedisb;


        }
        return $brwrandLoandetail;
    }
    function getBorrowerbehalfdetail($id) {
        global $db;
        $q="SELECT * from ! WHERE id = ? ";
        $detail= $db->getRow($q, array('on_borrower_behalf', $id));
        if(!$detail) {
            return 0;
        }
        return $detail;

    }
    function getMaxborwrAmtForNext($amount, $borrowerid, $loanid) {
        global $db;
        $AmountWithCredit = 0;
        $resultNative = 0;
        $resultNative =0;
        $creditEarned = $this->getCurrentCreditEarned($borrowerid, $loanid, 'Total');
        $AmountWithCredit = $amount + $creditEarned;
        return $AmountWithCredit;
    }
    function getInstallmentByDate($date, $loanid) {
        global $db;

        $q = "SELECT  amount FROM ! WHERE loanid=? AND duedate > ? ORDER BY id LIMIT 1";
        $result1 = $db->getALL($q , array('repaymentschedule', $loanid, $date));

        $q1 = "SELECT  amount FROM ! WHERE loanid=? AND duedate < ? ORDER BY id DESC LIMIT 1";
        $result2 = $db->getALL($q1 , array('repaymentschedule', $loanid, $date));

        $result = array_merge($result2, $result1);
        return max($result);
    }
    function getinstallmentday($uid, $loanid) {
        global $db;

        $q = "SELECT  installment_day, installment_weekday FROM ! WHERE loanid =? AND borrowerid = ? ";
        $result1 = $db->getOne($q , array('loanapplic', $loanid, $uid));
        return $result1;
    }

//in cases where weekly repayment schedule is specified, looks up the day of week the borrower had selected to have repayments fall due
    function getinstallmentweekday($uid, $loanid) {
        global $db;

        $q = "SELECT  installment_weekday FROM ! WHERE loanid =? AND borrowerid = ? ";
        $result1 = $db->getOne($q , array('loanapplic', $loanid, $uid));
        return $result1;
    }

    function getborrowerbehalfid($brwrid) {
        global $db;

        $q = "SELECT  borrower_behalf_id FROM ! WHERE userid = ?";
        $result1 = $db->getOne($q , array('borrowers', $brwrid));
        return $result1;
    }
    function getActiveBorrowersByCountry($country){
        global $db;
        $q= "SELECT FirstName,LastName,concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as name, userid, City, TelMobile FROM ! WHERE Country=? AND Active=? order by name";
        $result= $db->getAll($q, array('borrowers', $country, 1));
        return $result;
    }
    function getUsersByCountry($country){
        global $db;
        $q= "SELECT FirstName,LastName,concat(REPLACE(`FirstName`,' ',''), ' ' , REPLACE(`LastName`,' ','')) as name, userid, City FROM ! WHERE Country=? and active=? order by name";
        $result= $db->getAll($q, array('borrowers', $country, 1));

        $q= "SELECT FirstName,LastName,concat(`FirstName`,' ', `LastName`) as name, userid, City FROM ! WHERE Country=? order by name";
        $result1= $db->getAll($q, array('lenders', $country));

        $q= "SELECT name, userid, City FROM ! WHERE Country=? order by name";
        $result2= $db->getAll($q, array('partners', $country));

        $result = array_merge($result,$result1,$result2);
        asort($result);
        return $result;
    }



    function getinactiveLendersbyloanid($loanid) {
        global $db;
        $q='SELECT userid FROM ! as l WHERE  userid IN ( SELECT lenderid FROM ! WHERE loanid =? AND active=?)  AND userid NOT IN ( SELECT lender_id FROM ! WHERE loan_id =? ) AND l.active  = ?';
        $r1= $db->getAll($q, array('lenders', 'loanbids' , $loanid, 1,'forgiven_loans',$loanid, 0));
        return $r1;
    }
    function getlendersLoantofogive($lenderid) {
        global $db;
        $q='SELECT ltf.loanid FROM ! as ltf ,! as lb WHERE  lb.loanid = ltf.loanid AND lb.active = ? AND lb.loanid NOT IN ( SELECT loan_id FROM ! WHERE lender_id =? ) AND lb.lenderid = ?';
        $r1= $db->getAll($q, array('loans_to_forgive', 'loanbids' , 1,'forgiven_loans',$lenderid, $lenderid));
        return $r1;
    }

    function getCurrentPrincipalRatio($loanid)
    {
        global $db;
        $q="SELECT sum(amount) amt from repaymentschedule where loanid = $loanid";
        $all=$db->getOne($q);

        $forgiveAmount=0;
        $s="SELECT sum(amount) from ! where loan_id = ? ";
        $forgiveAmount=$db->getOne($s,array('forgiven_loans',$loanid));
        if($forgiveAmount) {
            $all +=$forgiveAmount;
        }
        $q="SELECT amountGot from loanapplic where loanid = $loanid ";
        $principal =$db->getOne($q);
        $ratio = $principal/$all;
        return round($ratio, 4);
    }
    function getprevfeetxn($txnid) {
        global $db;
        $q="SELECT * from ! where txn_type = ? AND id< ? ORDER BY id DESC";
        $fee=$db->getRow($q, array('transactions',FEE, $txnid));
        return $fee;
    }
    function getloansbycountry($country){
        global $db;
        $q= "SELECT FirstName,LastName,concat(REPLACE(`FirstName`,' ',''), REPLACE(`LastName`,' ','')) as name, userid, City, la.loanid FROM ! as b , ! as la WHERE Country=? AND la.active = ? AND la.borrowerid =b.userid order by name";
        $result= $db->getAll($q, array('borrowers', 'loanapplic', $country, LOAN_ACTIVE));
        return $result;
    }
    function getPrevMobile($id) {
        global $db;
        $q="SELECT TelMobile FROM ! WHERE userid=?";
        $oldTelMobile=$db->getOne($q,array('borrowers',$id));
        return $oldTelMobile;
    }
    function getActiveCoOrgUsers($country){
        global $db;
        $q="select * from ! where country=? and status=?";
        $res= $db->getAll($q, array('community_organizers' , $country, 1));
        return $res;
    }


//added by Julia 5-11-2013 
// julia changes updated by mohit on date 06-11-13
    function getActiveCoOrgUsersAndStaff($country){
        global $db;
        $q="select * from ! where country=? and status=?";
        $result1= $db->getAll($q, array('community_organizers' , $country, 1));
        $r="SELECT userid as user_id from ! WHERE isTranslator=?";
        $result2=$db->getAll($r, array('lenders',1));
        $result = array_merge($result1,$result2);
        return $result;
    }

    function getAllvolunteers($country){
        global $db;
        $q1='SET SESSION group_concat_max_len = 12000;';
        $db->query($q1);

        $q="select group_concat(user_id) as volunteer_id from ! where country=? and status=?";
        $res= $db->getAll($q, array('community_organizers' , $country, 1));
        return $res;
    }
    function getLoansForRepayRate($userid){
        global $db;
        $q= "select * from ! where borrowerid=? and active IN(?, ?, ?)";
        $res= $db->getAll($q, array('loanapplic', $userid,LOAN_ACTIVE,LOAN_REPAID,LOAN_DEFAULTED));
        return $res;
    }
    function getborrowerActivatedDate($brwid) {
        global $db;
        $p="SELECT editdate FROM ! WHERE loneid= ? and userid=?";
        $res1=$db->getOne($p, array('comments', 0, $brwid));
        return $res1;
    }
//added by Julia 20-10-2013
    function getendorserActivatedDate($endorserid) {
        global $db;
        $p="SELECT editdate FROM ! WHERE loneid= ? and userid=?";
        $res1=$db->getOne($p, array('comments', 0, $endorserid));
        return $res1;
    }
    function getBorrowerRepaidLoans($userid){
        global $db;
        $q="select (select count(loanid) from loanapplic where borrowerid=$userid and adminDelete=0 and active='".LOAN_REPAID."') as loancount, loanid, AmountGot from ! where borrowerid=? and adminDelete=? and active=?";
        $result=$db->getAll($q, array('loanapplic', $userid, 0, LOAN_REPAID));
        return $result;
    }
    function getLoanRepaidDate($loanid, $userid){
        global $db;
        $q="select startdate from ! where borrowerid=? and loanid=? and status=? ";
        $result=$db->getOne($q, array('loanstage', $userid, $loanid, LOAN_REPAID));
        return $result;
    }
    function getFacebookdata($userid){
        global $db;
        $q="select be.fb_data, u.facebook_id, u.fb_post from ! as be join ! as u where be.userid=u.userid and u.userid=?";
        $result=$db->getRow($q, array('borrowers_extn', 'users', $userid));
        return $result;
    }
    function IsFacebook_connect($userid){
        global $db;
        $q="select facebook_id from ! where userid=?";
        $facebook_id= $db->getOne($q, array('users', $userid));
        if(!empty($facebook_id))
            return true;
        else
            return false;
    }
    function addEndorser($uname, $namea, $nameb, $pass1, $postadd, $city, $country, $email, $mobile, $user_guess, $id, $bnationid, $home_no, $fb_data, $validation_code, $completeLater, $babout, $bconfdnt, $e_candisplay){
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        if(!empty($fb_data['user_profile'])){
            $facebook_id= $fb_data['user_profile']['id'];
            $fb_data=  base64_encode(serialize($fb_data));
        }else{
            $facebook_id= '';
            $fb_data='';
        }
        $salt = $this->makeSalt();
        $pass= $this->makePassword($pass1, $salt);
        //$currency=$this->getCurrencyIdByCountryCode($country);
        $q="INSERT INTO ! (username, password, salt, userlevel, regdate,emailVerified, facebook_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $res = $db->query($q, array('users', $uname, $pass, $salt, BORROWER_LEVEL, $time, 0, $facebook_id));
        if($res === DB_OK)
        {
            $q="SELECT userid FROM ! WHERE username=?";
            $userid=$db->getOne($q, array('users', $uname));
            $q="INSERT INTO ! (userid, FirstName, LastName, TelMobile, Email, Active, activeloan, iscomplete_later, Created,completed_on, LastModified,home_location, endorser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $res1=$db->query($q, array('borrowers', $userid, $namea, $nameb, $mobile, $email, 0, NO_LOAN, 1, $time ,$time ,$time, $home_no, 1));
            if($res1=== DB_OK)
            {
                $q="INSERT INTO ! (userid, created, fb_data) VALUES (?, ?, ?)";
                $res2 = $db->query($q, array('borrowers_extn', $userid, time(), $fb_data));
                $q1="update ! set endorserid=?, e_know_brwr=?, e_cnfdnt_brwr=?, e_candisplay=?  where validation_code=?";
                $res3= $db->query($q1, array('endorser', $userid, $babout, $bconfdnt, $e_candisplay, $validation_code));
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

    function getBorrowerOfEndorser($userid){
        global $db;
        $q="select borrowerid from ! where endorserid=?";
        $res= $db->getOne($q, array('endorser', $userid));
        return $res;
    }

    function IsEndorser($userid){
        global $db;
        $q= "select endorser from ! where userid=?";
        $res= $db->getOne($q, array('borrowers', $userid));
        return $res;
    }

    function getEndorserDetail($id){
        global $db;
        $q="select * from ! where borrowerid=?";
        $res= $db->getAll($q, array('endorser', $id));
        return $res;
    }

    function IsEndorsedComplete($userid){
        global $db;
        $q="select count(*) from ! where borrowerid=? and endorserid<>?";
        $res= $db->getOne($q, array('endorser', $userid, ''));
        return $res;
    }

    function IsBorrowerEndorser($id){
        global $db;
        $q="select count(*) from ! where endorserid=?";
        $res=$db->getOne($q, array('endorser', $id));
        return $res;
    }
    function IsEndorserAlreadyReg($validation_code){
        global $db;
        $q="select endorserid from ! where validation_code=?";
        $res= $db->getOne($q, array('endorser', $validation_code));
        return $res;
    }

    function getBorrowerOfEndorserByCode($vd){
        global $db;
        $q="select borrowerid from ! where validation_code=?";
        $res= $db->getOne($q, array('endorser', $vd));
        return $res;
    }

    function getBrwrDetailFrmEndorser($userid){
        global $db;
        $q="select e_know_brwr, e_cnfdnt_brwr, e_candisplay, borrowerid from ! where endorserid=?";
        $res=$db->getRow($q, array('endorser', $userid));
        return $res;
    }
    function saveBInviteEmail($id,$borrower_name, $frnd_email, $date){
        global $db;
        $q = "INSERT INTO ! (userid,lender,email,date) VALUES  (?,?,?,?)";
        $res1 = $db->query($q, array('invites',$id,$borrower_name,$frnd_email,$date));
        $p="SELECT id from ! where userid = ? AND date = ? order by id";
        $id = $db->getOne($p, array('invites',$id,$date));
        return $id;
    }
    function getInvitedMember($userid){
        global $db;
        $q="select invitee_id, date, email from ! where userid=?";
        $res= $db->getAll($q, array('invites', $userid));
        return $res;
    }


    function getInviteCredit($userid){
        global $session;
        $invitees= $this->getInvitedMember($userid);
        $creditearned=0;
        foreach($invitees as $invite){
            $inviterepayrate= $session->RepaymentRate($invite['invitee_id']);
            $invite_lastloan= $this->getLastloan($invite['invitee_id']);
            if(empty($invite_lastloan)){
                $inviterepayrate=0;
            }
            $minrepayrate=$this->getAdminSetting('MinRepayRate');
            if($inviterepayrate>=$minrepayrate){
                $country=$this->getCountryCodeById($userid);
                $binvitecredit=$this->getcreditsettingbyCountry($country,3);
                $creditearned+=$binvitecredit['loanamt_limit'];
            }
        }
        return $creditearned;
    }


    function getInvitee($id){
        global $db;
        $q="select userid from ! where invitee_id=?";
        $res= $db->getOne($q, array('invites', $id));
        return $res;
    }

    function saveFacebookInfo($facebook_id, $fbData, $web_acc, $userid=0, $email='', $fail_reason='') {
        global $db,$session;
        $date= time();
        $ip=(isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : "";
        $q="select id from ! where facebook_id=? AND (userid=? OR userid=?)";
        $existid= $db->getOne($q, array('facebook_info', $facebook_id, 0, $userid));
        if(!empty($existid)){
            $q1="update ! set facebook_id=?, userid=?, facebook_data=?, accept=?, date=?, zidisha_email=?, ip_address=?, fail_reason=? where id=?";
            $res=  $db->query($q1, array('facebook_info', $facebook_id, $userid, $fbData, $web_acc, $date, $email, $ip, $fail_reason, $existid));			
        }else{
            $q1="insert into ! (facebook_id, userid, facebook_data, accept, date, zidisha_email, ip_address, fail_reason) values(?,?,?,?,?,?,?,?)";
            $res=$db->query($q1, array('facebook_info', $facebook_id, $userid, $fbData, $web_acc, $date, $email, $ip, $fail_reason));
        }
		/**** Integration with shift science on date 24-12-2013******/
		$session->invoiceShiftScience('facebook_connect',$userid,'','','',$facebook_id);
        return $res;
    }

    function getBorrowersForRepayReminder($againReminder= false){
        global $db;
        if($againReminder){
            $duedays=$this->getAdminSetting('AgainReminderDays');
            $dueamt= $this->getAdminSetting('AgainReminderAmt');
            $q="SELECT rs.userid, rs.loanid, rs.duedate, rs.amount, rs.paidamt FROM ! as rs join ! as br on rs.userid=br.userid  WHERE rs.amount > ?  AND ((rs.amount-rs.paidamt)>?*(select rate from excrate where start=(select MAX(start) from excrate where currency=br.currency)) || paidamt is null)  AND `duedate`<=(UNIX_TIMESTAMP() -?*24*60*60) AND `duedate`>(UNIX_TIMESTAMP() -(?+1)*24*60*60)";
            $repay_borrower= $db->getAll($q, array('repaymentschedule', 'borrowers', 0, $dueamt, $duedays, $duedays));
        }else{
            $q="SELECT * FROM ! WHERE amount >?  AND (paidamt IS NULL OR paidamt < amount ) AND `duedate`>= (UNIX_TIMESTAMP() + 24*60*60) AND `duedate`<= (UNIX_TIMESTAMP() + 2*24*60*60)";
            $repay_borrower= $db->getAll($q, array('repaymentschedule', 0));
        }
        return $repay_borrower;
    }
    function getDueRepaymentDetail($loanid){
        global $db;
        $q="SELECT  `duedate` , `amount`, `paidamt` FROM ! WHERE `loanid`=?  AND (`paidamt` IS NULL OR `paidamt` <`amount` ) AND  `amount`>? order by `duedate` limit 1";
        $res= $db->getRow($q, array('repaymentschedule', $loanid, 0));
        return $res;
    }
    function getAlldueAmtToday($duedate, $loanid){
        global $db;
        $q="select amount, `paidamt` from ! where duedate<=? and loanid=? and `amount`>? AND (`paidamt` IS NULL OR `paidamt` <`amount` )";
        $res= $db->getAll($q, array('repaymentschedule', $duedate, $loanid,0));
        return $res;
    }
    function updateRepaymentReminder($borrowerid, $name){
        global $db;
        $q="update ! set $name=? where userid=?";
        $res= $db->query($q, array('borrowers_extn', time(), $borrowerid));
        return $res;

    }
    function checkForReminder($userid, $againReminder=false){
        global $db;
        $q="select repayment_reminder from ! where userid=?";
        $remindertime= $db->getOne($q, array('borrowers_extn', $userid));
        $duedays=$this->getAdminSetting('AgainReminderDays');
        if($againReminder){
            if($remindertime!='' && (time()-$remindertime)>=($duedays+2)*24*60*60 && (time()-$remindertime)<($duedays+3)*24*60*60)
                return true;
            else
                return false;
        }else{
            $reminder_day=30-$duedays;
            $days= (time()-$remindertime)/(60*60*24);
            $dys=ceil($days);
            if($remindertime=='' || ($dys)>=30 || ($dys)>=$reminder_day)
                return true;
            else
                return false;
        }
    }
    function getExistFbUser($facebook_id, $userid){
        global $db;
        if(!empty($userid)){
            $where="AND userid<>$userid";
        }
        $q="select userid, username from ! where facebook_id='$facebook_id' $where order by userid limit 1";
        $result=$db->getRow($q, array('users'));
        return $result;
    }
    function getMentorAssignedmember($userid){
        global $db;
        $q="SELECT b.userid, b.FirstName, b.LastName FROM ! as b, ! as bext WHERE b.userid=bext.userid AND bext.mentor_id=?";
        $result=$db->getAll($q, array('borrowers','borrowers_extn', $userid));
        return $result;
    }

    function getLoanArrearBorrowers($duedays){
        global $db;
        $q="SELECT rs.userid, rs.loanid, rs.duedate, rs.amount, rs.paidamt FROM ! as rs join ! as br on rs.userid=br.userid  WHERE rs.amount > ?  AND (rs.paidamt IS NULL || rs.paidamt<(rs.amount-?*(select rate from excrate where start=(select MAX(start) from excrate where currency=br.currency))))  AND rs.duedate<=(UNIX_TIMESTAMP() -?*24*60*60) AND rs.duedate >(UNIX_TIMESTAMP() -(?+1)*24*60*60)";
        $borrowers= $db->getAll($q, array('repaymentschedule', 'borrowers', 0, REPAYMENT_AMT_THRESHOLD, $duedays, $duedays));
        return $borrowers;
    }
    function checkLateRepaymentReminder($duedays, $userid){
        global $db;
        if($duedays=='4' || $duedays=='14'){
            $q="select late_repayment_reminder from ! where userid=?";
            $reminder=$db->getOne($q, array('borrowers_extn', $userid));
            $min_reminder_time= FINAL_LOANARREAR_REMINDER-FIRST_LOANARREAR_REMINDER;
            if($reminder>=$min_reminder_time*24*60*60 || $reminder=='')
                return true;
            else
                return false;
        }else{
            $q="select late_repayment_reminders from ! where userid=?";
            $reminder=$db->getOne($q, array('borrowers_extn', $userid));
            if($reminder>=30*24*60*60 || $reminder=='')
                return true;
            else
                return false;
        }
    }
    function getAllLoansOfBorrower($userid){
        global $db;
        $q="select * from ! where borrowerid=? and active<>? order by loanid desc";
        $res=$db->getAll($q, array('loanapplic', $userid, LOAN_EXPIRED));
        return $res;
    }
    function getfutureRepaidDate($loanid){
        global $db;
        $q="select max(duedate) from ! where loanid=?";
        $res= $db->getOne($q, array('repaymentschedule', $loanid));
        return $res;
    }
    function getVolunteersCity($volunteer_ids){
        global $db;
        $q1="select City from ! where userid IN($volunteer_ids)";
        $borrowers=$db->getAll($q1, array('borrowers'));

        $q1="select City from ! where userid IN($volunteer_ids)";
        $lenders=$db->getAll($q1, array('lenders'));

        $q1="select City from ! where userid IN($volunteer_ids)";
        $partners=$db->getAll($q1, array('partners'));

        $volunteers=$borrowers;
        if(is_array($lenders) && is_array($volunteers))
            $volunteers= array_merge($volunteers, $lenders);
        if(is_array($partners) && is_array($volunteers))
            $volunteers= array_merge($volunteers, $partners);
        if(is_array($volunteers)){
            $volunteers=$this->array_iunique($volunteers);
            $volunteers_city=array_unique($volunteers, SORT_REGULAR);
        }else{
            $volunteers_city='';
        }

        return $volunteers_city;
    }
    function array_iunique($arr)
    {
        foreach ($arr as $key => $value) {
            if(isset($value['City'])){
                $arr[$key] = strtolower($value['City']);
            }// Or uc_first(), etc. 
        }
        return array_unique($arr);
    }
    function getVolunteersByCity($city){
        global $db;
        $q1="select userid from ! where City=? and userid IN(select user_id from community_organizers where status=?)";
        $borrowers=$db->getAll($q1, array('borrowers', $city, 1));

        $q1="select userid from ! where City=? and userid IN(select user_id from community_organizers where status=?)";
        $lenders=$db->getAll($q1, array('lenders', $city, 1));

        $q1="select userid from ! where City=? and userid IN(select user_id from community_organizers where status=?)";
        $partners=$db->getAll($q1, array('partners', $city, 1));

        $volunteers=array();
        $mentor=array();
        if(is_array($borrowers))
            $volunteers= array_merge($volunteers, $borrowers);
        if(is_array($lenders) && is_array($volunteers))
            $volunteers= array_merge($volunteers, $lenders);
        if(is_array($partners) && is_array($volunteers))
            $volunteers= array_merge($volunteers, $partners);
        for($i=0; $i<count($volunteers); $i++){
            $count_borrower= $this->getAssignBorrowerCount($volunteers[$i]['userid']);
            $volunteer[$volunteers[$i]['userid']]=$count_borrower;
        }
        asort($volunteer);
        foreach($volunteer as $key => $row){
            if($row < 50){
                $mentor[$key]=$row;
            }
        }
        return $mentor;

    }
    function getAssignBorrowerCount($userid){
        global $db;
        $q="select count(userid) from ! where mentor_id=?";
        $count= $db->getOne($q, array('borrowers_extn', $userid));
        return $count;
    }
    function getBorrowerVolnteerMentor($userid){
        global $db;
        $q="select mentor_id from ! where userid=?";
        $volunteer=$db->getOne($q, array('borrowers_extn', $userid));
        return $volunteer;
    }
    function getActivationComment($borrowerid){
        global $db;
        $q = "select a.id as id1, a.* , b.* from ! as a, ! as b   where a.userid = ? and b.userid = ? and b.reply=? and a.id = b.type and a.loneid=? and a.amount<>? ORDER BY a.id DESC";
        $result=$db->getAll($q, array('comments','b_comments',$borrowerid,$borrowerid,0,0,0));
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getPartnerVerificationComment($borrowerid){
        global $db;
        $q = "select a.id as id1, a.* , b.* from ! as a, ! as b   where a.userid = ? and b.userid = ? and b.reply=? and a.id = b.type and a.loneid=? ORDER BY a.id DESC";
        $result=$db->getAll($q, array('comments','b_comments',$borrowerid,$borrowerid,0,0));
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getAuthLoanAmount($userid, $loanid = 0, $native=true)
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
            $rate = $this->getAuthorisedRate($userid,$loanid);	// changed dby Mohit
            $amt = round_local(($result * $rate));
            return $amt;
        } else {
            return $result;
        }
    }


    function getAuthorisedRate($userid,$loanid)
    {
        global $db;

        $p = "select auth_date from ! where borrowerid = ? and loanid = ?";
        $res=$db->getOne($p, array('loanapplic',$userid,$loanid));

        $q = "select Currency from ! where userid = ?";
        $result1=$db->getOne($q, array('borrowers',$userid));

        $r="SELECT rate FROM excrate WHERE start<=? and currency = ? order by id desc limit 1";
        $result=$db->getOne($r , array($res, $result1));
        return $result;
    }
    /* 
	-------------------Borrower Section End----------------------- */


    /* -------------------Lender Section Start----------------------- */

    function addLender($username, $pass1, $email, $fname, $lname, $about, $photo, $city, $country, $hide_Amount, $loan_comment, $tnc, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter,$lwebsite,$sub_user_type)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        $salt = $this->makeSalt();
        $pass= $this->makePassword($pass1, $salt);
        $q="INSERT INTO ! (username, password, salt, userlevel, regdate, tnc, emailVerified ,sublevel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $r=$db->query($q, array('users', $username,$pass, $salt, LENDER_LEVEL, $time,$tnc, 1, $sub_user_type));
        if($r==DB_OK)
        {
            $q="SELECT userid FROM ! WHERE username=?";
            $userid=$db->getOne($q, array('users', $username));
            $q="INSERT INTO ! (userid, Email, FirstName, LastName, about, photopath, City, Country, Active, hide_Amount, emailcomment, loan_app_notify, email_loan_repayment, subscribe_newsletter, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $r=$db->query($q, array('lenders', $userid, $email, $fname, $lname, $about, $photo, $city, $country, 1, $hide_Amount, $loan_comment, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $lwebsite));
            if($r===DB_OK){
                return 0;//successful insert
            }
            else{
                return 1;//unsuccessful insert
            }
        }
        else{
            return 2;//cannot create user
        }
    }
    function updateLender($username, $pass1, $email, $fname, $lname, $about, $photo, $city, $country, $hide_Amount, $comment, $id, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $website)
    {

        global $db;
        $pass = '';
        traceCalls(__METHOD__, __LINE__);
        $res = DB_OK;
        if(!empty($pass1))
        {
            $salt = $this->makeSalt();
            $pass= $this->makePassword($pass1, $salt);
            $q = "UPDATE ! SET  password = ?, salt =? WHERE userid = ?";
            $res = $db->query($q, array('users', $pass, $salt, $id ));
        }
        if($res == DB_OK)
        {
            $q = "UPDATE ! SET Email=?, FirstName=?, LastName=?, about=?, photopath=?, City=?, Country=?, hide_Amount=?, emailcomment=?, loan_app_notify=?, email_loan_repayment=?, subscribe_newsletter=?, website=? WHERE userid = ?";
            $r=$db->query($q, array('lenders', $email, $fname, $lname, $about, $photo, $city, $country, $hide_Amount, $comment, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $website, $id));
            if($r===DB_OK){
                return 0;//successful insert
            }
            else{
                return 1;//unsuccessful insert
            }
        }
        else{
            return 2;//cannot create user
        }
    }
    function getLender_disbursedLoan($sessionuserid)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q='SET SQL_MAX_JOIN_SIZE=#';
        $db->query($q);

        $q1="SELECT l.loanid, l.active AS Loan_State, sum(lb.givenamount) AS AMT FROM ! as l join ! as lb on l.loanid=lb.loanid WHERE lb.lenderid =? and lb.active =? and l.active =? and l.loanid NOT IN(select loan_id from ! where lender_id=?) group by l.loanid";

        $result1=$db->getAll($q1, array('loanapplic','loanbids', $sessionuserid, 1, LOAN_ACTIVE, 'forgiven_loans', $sessionuserid));
        $result= array();
        $i=0;
        foreach($result1 as $row){

            $qq="SELECT t.TrDate, b.FirstName, b.LastName, b.city, b.country, b.userid from ! as b join ! as t on b.userid = t.userid WHERE b.ActiveLoan=? and t.txn_type =? and t.loanid=? order by t.TrDate";

            $res=$db->getAll($qq, array('borrowers','transactions', LOAN_ACTIVE, DISBURSEMENT, $row['loanid']));
            $result[$i]=$res[0];
            $result[$i]['loanid']= $row['loanid'];
            $result[$i]['Loan_State']= $row['Loan_State'];
            $result[$i]['AMT']= $row['AMT'];
            $i++;
        }

        $qq="SELECT  b.FirstName, b.LastName, b.city, b.country, b.userid, l.loanid, l.active AS Loan_State, l.AcceptDate as TrDate, sum(lb.givenamount) AS AMT FROM ! as l join ! as b on l.borrowerid = b.userid join ! as lb on l.loanid=lb.loanid  WHERE lb.lenderid =? and lb.active =? and l.active =? and  l.loanid NOT IN(select loan_id from ! where lender_id=?) group by l.loanid order by l.AcceptDate";

        $result2=$db->getAll($qq, array('loanapplic', 'borrowers','loanbids', $sessionuserid, 1,LOAN_FUNDED, 'forgiven_loans', $sessionuserid));
        if(!empty($result2)){
            $result=array_merge($result2,$result);
        }
        return $result;
    }
    function getLender_disbursedLoan_end($sessionuserid)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $qq="SELECT b.FirstName, b.LastName, b.city, b.country, b.userid, l.loanid, l.active AS Loan_State, sum(lb.givenamount) AS AMT FROM ! as l join ! as b on l.borrowerid = b.userid join ! as lb on l.loanid=lb.loanid WHERE lb.lenderid =? and lb.active =? and (l.active =? OR l.active =? OR l.loanid IN(select loan_id from ! where lender_id=?)) group by l.loanid order by b.FirstName";

        $result=$db->getAll($qq, array('loanapplic', 'borrowers','loanbids', $sessionuserid, 1,LOAN_REPAID, LOAN_DEFAULTED, 'forgiven_loans', $sessionuserid));
        return $result;
    }
    function repaymentfeedback($partid, $borrowerid, $loanid, $feedback, $pcomment, $addmore, $cid)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $date1 =time();
        if($cid==0)
        {
            $ontime=$this->isRepaidOntime($borrowerid, $loanid);
            $q2="INSERT INTO comments (partid, userid, date, amount, lpaid, ontime, feedback,editDate, loneid )"." VALUES (?,?,?,?,?,?,?,?,?)";
            $res1=$db->query($q2, array($partid,$borrowerid,0,0,0,$ontime,$feedback,$date1,$loanid));
            if($res1===DB_OK)
            {
                $q = 'SELECT id from ! where partid =? and  userid = ?  and editDate = ?';
                $feedbackid = $db->getOne($q , array('comments',$partid,$borrowerid,$date1));
                $this->subFeedback($borrowerid,$partid,$pcomment,$feedbackid,0);
                return true;
            }
            return false;
        }
        else
        {
            $q2="UPDATE comments SET feedback=?, editDate=? WHERE (id=? AND loneid=?)";
            $res1=$db->query($q2, array($feedback, $date1, $cid, $loanid));
            if($res1==DB_OK)
            {
                $q3="UPDATE b_comments SET comment = ?, editdate= ? WHERE type = ? AND reply = ? LIMIT 1";
                if(($db->query($q3, array($pcomment, $date1, $cid, 0 )))==DB_OK)
                    return true;
            }
            return false;
        }
    }
    function getLendersEmail()
    {
        global $db;
        $q="select Email from !";
        $resultLender=$db->getAll($q,array('lenders'));
        return $resultLender;
    }
    function getLendersEmailForLoanApp($loanid)
    {
        global $db;
        $q="select Email, FirstName, LastName from ! where loan_app_notify=? and userid IN (Select lenderid from ! where loanid =? and active=?)";
        $resultLender=$db->getAll($q,array('lenders',1, 'loanbids', $loanid, 1));
        return $resultLender;
    }
    function lenderBid($lenderid, $loanid, $brwid, $amount, $interest)
    {
        global $db, $session;
        $time = time();
        $q="INSERT INTO loanbids (loanid, lenderid, bidamount, bidint , biddate) VALUES (?, ?, ?, ?,?)";
        $result=$db->query($q, array($loanid, $lenderid, $amount, $interest , $time));
        if($result===DB_OK) {
            $bidid = mysql_insert_id();
            return $bidid;
        } else {
            return 0;
        }
    }
    function processBids($rr)
    {
        global $db;
        $result=0;
        foreach($rr as $row)
        {
            $q="UPDATE ! SET active = ?, paid = ?, givenamount =? WHERE bidid = ?";
            $result=$db->query($q, array('loanbids',1,0,$row['bidamount'],$row['bidid']));
        }
        if($result===DB_OK)
            return 1;
        return 0;
    }
    function updateActiveLoan($borrowerid,$state)
    {
        global $db;
        $q1="UPDATE ! SET activeloan= ? WHERE userid=?";
        $result= $db->query($q1,array('borrowers',$state,$borrowerid));
        if($result===DB_OK)
            return 1;
        return 0 ;
    }
    function updateLoanStatus($loanid,$state,$extradays=0)
    {
        global $db;
        $q1="UPDATE ! SET active= ? ,extra_days= ? WHERE loanid=?";
        $result = $db->query($q1,array('loanapplic',$state, $extradays, $loanid));
        if($result===DB_OK){
            $startdate=time();
            $borrowerid=$this->getBorrowerId($loanid);
            $this->setLoanStage($loanid, $borrowerid, LOAN_ACTIVE, $startdate, LOAN_FUNDED) ;
            return 1;
        }
        return 0;
    }
    function updateLoanRate($loanid, $rate, $acceptBid_note)
    {
        global $db;
        $AcceptDate=time();
        $q1="UPDATE ! SET active=? , finalrate= ?, AcceptDate=?, accept_bid_note=? WHERE loanid =?";
        $result = $db->query($q1,array('loanapplic', LOAN_FUNDED, $rate, $AcceptDate, $acceptBid_note, $loanid));
        if($result===DB_OK){
            $borrowerid=$this->getBorrowerId($loanid);
            $this->setLoanStage($loanid, $borrowerid, LOAN_FUNDED, $AcceptDate ,LOAN_OPEN) ;
            return 1;
        }
        return 0;
    }
    function getAvgBidInterest($user, $loanid)
    {
        global $db;
        $q= "SELECT reqdamt, Amount ,active from ! where loanid = ? AND adminDelete = ? ";
        $row = $db->getRow($q, array('loanapplic', $loanid , 0));
        $loanAmt = $row['Amount'];
        $active = $row['active'];
        $dollarAmt = $row['reqdamt'];
        if($active == LOAN_OPEN)
        {
            $CurrencyRate = $this->getCurrentRate($user);
            $q="SELECT bidint, bidamount  FROM  ! WHERE loanid = ? order by bidint, bidid ";
            $result=$db->getAll($q, array('loanbids', $loanid));
            if(empty($result)){
                return 0;
            }
            else
            {
                $rate=0.00;
                $amt=0.00;
                foreach($result as $row)
                {
                    if($amt+$row['bidamount'] > $dollarAmt)
                        $bidAmt = $dollarAmt - $amt;
                    else
                        $bidAmt = $row['bidamount'];
                    $rate= $rate+(($row['bidint'])*($bidAmt));
                    $amt=$amt+($bidAmt);
                }
                $r=($rate/$amt);
                return $r;
            }
        }
        else
        {
            $q="SELECT bidint, givenamount  FROM  ! WHERE loanid = ? and active = 1";
            $result=$db->getAll($q, array('loanbids', $loanid));
            if(empty($result)){
                return 0;
            }
            else
            {
                $rate=0.00;
                $amt=0.00;
                foreach($result as $row)
                {
                    $bidAmt = $row['givenamount'];
                    $rate= $rate+(($row['bidint'])*($bidAmt));
                    $amt=$amt+($bidAmt);
                }
                $r=($rate/$amt);
                return $r;
            }
        }
    }
    function getTotalBid($user, $loanid)
    {
        global $db;
        if($loanid == 0)
        {
            $q="SELECT sum(bidamount) as bidtotal FROM loanapplic, loanbids, lenders WHERE loanapplic.borrowerid=$user AND loanapplic.loanid=loanbids.loanid  AND loanbids.lenderid=lenders.userid AND loanapplic.adminDelete = 0";
        }
        else
        {
            $q="SELECT sum(bidamount) as bidtotal FROM loanbids WHERE loanid = $loanid";
        }
        $result=$db->getOne($q);
        if(empty($result)){
            return 0;
        }
        return $result;
    }
    function getLenderDetails($id)
    {
        global $db;
        $q="SELECT * FROM !, ! WHERE lenders.userid=? AND lenders.userid=users.userid";
        $result=$db->getRow($q, array('lenders', 'users', $id));
        return $result;
    }
    function getBidByBidid($bidid, $userid)
    {
        global $db;
        $data=array();
        if(!empty($bidid))
        {
            $q = "SELECT bidamount, bidint FROM ! WHERE bidid = ? AND lenderid=? LIMIT 1";
            $data = $db->getRow($q, array('loanbids', $bidid, $userid));
        }
        return $data;
    }
    function editbid($bidid,$amount,$interest)
    {
        global $db;
        $time = time();
        if(!empty($bidid) && !empty($amount))
        {
            $q = "UPDATE ! SET bidamount  = ?, bidint  = ?, modified =? WHERE bidid = ? LIMIT 1";
            $data = $db->query($q, array('loanbids',$amount, $interest, $time, $bidid));
            if($data===DB_OK)
                return 0;
        }
        return 1;
    }
    function getLendersAndAmount($loanid, $forgive=false)
    {
        global $db;
        if($forgive)
        {
            $q="SELECT lenderid, SUM(givenamount) amount, SUM(givenamount * bidint)/SUM(givenamount) intr FROM  loanbids WHERE loanid =$loanid and active = 1 and lenderid NOT IN (select lender_id from forgiven_loans where loan_id= $loanid) group by lenderid ";
        }
        else
        {
            $q="SELECT lenderid, SUM(givenamount) amount, SUM(givenamount * bidint)/SUM(givenamount) intr FROM  loanbids WHERE loanid =$loanid and active = 1 group by lenderid ";
        }
        $result=$db->getAll($q);
        return $result;
    }
    function amountInActiveBids($userid)
    {
        global $db;
        /*If loan is in status LOAN_OPEN and LOAN_FUNDED amount will not reflect in transactions
		  So first find out how much amount is in such loans and then how much of it in in
		  active bid
		*/
        $amtUsed = 0.0;
        $query = 'select distinct a.loanid as loanid, a.reqdamt as amount, a.borrowerid as bid from ! as a left join ! as b on b.loanid = a.loanid  where b.lenderid = ? and (a.active = ? or a.active = ?) AND a.adminDelete = ?';
        $loans = $db->getAll($query, array('loanapplic', 'loanbids' , $userid , LOAN_FUNDED, LOAN_OPEN, 0));
        foreach($loans as $loan)
        {
            $query = 'select bidamount, lenderid from ! where loanid = ? order by bidint, bidid';
            $bids = $db->getAll($query, array('loanbids' , $loan['loanid']));
            $totBidAmt = 0;
            for($i = 0; $i < count($bids); $i++)
            {
                $bidAmtNative = $bids[$i]['bidamount'];
                if($loan['amount'] > ($totBidAmt + $bidAmtNative))
                {
                    if($userid == $bids[$i]['lenderid'])
                        $amtUsed += $bids[$i]['bidamount'];
                    $totBidAmt += $bidAmtNative;
                }
                else
                {
                    if($userid == $bids[$i]['lenderid']){
                        $amtToThisBid = $loan['amount'] - $totBidAmt ;
                        //$amtUsed += convertToDollar($amtToThisBid,$CurrencyRate);;
                        $amtUsed += $amtToThisBid;
                    }
                    $totBidAmt += $bidAmtNative;
                    break;
                }
            }
        }
        return $amtUsed;
    }
    function isMyLender($id,$lenderid)
    {
        global $db;
        $sql = 'SELECT count(lenderid) FROM ! as a, ! as b WHERE a.loanid = b.loanid AND b.borrowerid = ? AND a.lenderid =? AND b.adminDelete = ?';
        $count = $db->getOne($sql, array('loanbids','loanapplic',$id, $lenderid, 0));
        if($count) {
            return true;
        }
        return false;
    }
    function totalAmountLend($lenderid)
    {
        global $db;
        $sql = 'SELECT SUM(givenamount) FROM ! WHERE lenderid=? AND `active`= 1';
        $r1 = $db->getOne($sql, array('loanbids',$lenderid));
        if(!empty($r1))
        {
            return $r1;
        }
        return 0;
    }
    function getEmail($useid)
    {
        global $db;
        $sql = "SELECT Email, FirstName , LastName, Country, City FROM ! WHERE userid = ? ";
        $r3 = $db->getRow($sql, array('lenders', $useid));
        $r['name']=trim($r3['FirstName']." ".$r3['LastName']);
        $r['email']=$r3['Email'];
        $r['Country']=$r3['Country'];
        $r['City']=$r3['City'];
        return $r;
    }
    function getEmailBybidid($bidid)
    {
        global $db;
        $sql = "SELECT lenderid, givenamount   FROM ! WHERE bidid = ? ";
        $r3 = $db->getRow($sql, array('loanbids', $bidid));
        $r=$this->getEmail($r3['lenderid']);
        $r['amount']=$r3['givenamount'];
        return $r;
    }
    function getLenderEmailByLoanid($loanid)
    {
        global $db;
        $q='SELECT Email , FirstName, LastName , userid FROM ! WHERE emailcomment = ? AND userid IN ( SELECT lenderid FROM ! WHERE loanid =? )';
        $r1= $db->getAll($q, array('lenders', 1, 'loanbids' , $loanid));
        return $r1;
    }
    function confirmLenderActive($id)
    {
        global $db;
        $l="SELECT Active FROM ".TBL_LENDERS." WHERE userid=$id";
        $result=$db->getOne($l);
        return $result;
    }
    function getLenderBids($lenderid)
    {
        global $db;
        $q="SELECT borrowers.FirstName, borrowers.LastName, borrowers.city, borrowers.country, borrowers.userid, loanbids.bidid, loanbids.loanid, loanbids.bidamount, loanbids.biddate, loanapplic.reqdamt from borrowers join loanapplic on borrowers.userid = loanapplic.borrowerid join loanbids on loanapplic.loanid=loanbids.loanid where loanbids.lenderid = $lenderid  and (loanapplic.active =0) and loanapplic.adminDelete =0 ORDER BY loanbids.bidint, loanbids.bidid ASC";
        $result=$db->getAll($q);

        for($i=0; $i<count($result); $i++)
        {
            $tot_bidamt =0;
            $loanid = $result[$i]['loanid'];
            $p="SELECT bidid, bidamount, lenderid from loanbids where loanid = $loanid  ORDER BY bidint, bidid ASC";
            $res=$db->getAll($p);

            for($j=0; $j<count($res); $j++)
            {
                $bidamt = $res[$j]['bidamount'];
                $tot_bidamt += $bidamt;
                if($result[$i]['bidid'] == $res[$j]['bidid'])
                {
                    if($tot_bidamt < $result[$i]['reqdamt'])
                    {
                        $result[$i]['bidstatus'] = 1;     /* 1 for active bid   */
                    }
                    else
                    {
                        $bidamt_rqrd = $result[$i]['reqdamt'] - ($tot_bidamt - $bidamt);
                        if($bidamt_rqrd >= $bidamt)
                        {
                            $result[$i]['bidstatus'] = 1;          /* 1 for active bid  */
                        }
                        else if($bidamt_rqrd < $bidamt && $bidamt_rqrd >0)
                        {
                            $result[$i]['bidstatus'] = 2;        /* 2 for bid partialy accepted   */
                            $result[$i]['bidamt_acpt'] = $bidamt_rqrd;
                        }
                        else
                        {
                            $result[$i]['bidstatus'] = 3;      /* 3 for bid is out dated    */
                        }
                    }
                }
            }
        }
        $result_arr = array();
        foreach ($result as $key => $row) {
            $result_arr[$key] = $row['biddate'];
        }
        if(!empty($result))
        {
            array_multisort($result_arr, SORT_DESC, $result);
            return $result;
        }
        else
            return false;
    }
    function saveInviteEmails($uid, $lname, $email_ids, $date)
    {
        global $db;
        for($i=0; $i<count($email_ids); $i++)
        {
            $q = "INSERT INTO ! (userid,lender,email,date) VALUES  (?,?,?,?)";
            $res1 = $db->query($q, array('invites',$uid,$lname,$email_ids[$i],$date));
        }
        $p="SELECT id from ! where userid = ? AND date = ? order by id";
        $ids = $db->getAll($p, array('invites',$uid,$date));
        $idarr = array();
        $i=0;
        foreach($ids as $row)
        {
            $idarr[$i]=$row['id'];
            $i++;
        }
        return($idarr);
    }
    function updateInviteVisitor($id)
    {
        global $db;
        $p ="SELECT email from ! where id = ?";
        $email = $db->getOne($p, array('invites',$id));
        $q = "UPDATE ! set visited = ? where email = ?";
        $res = $db->query($q, array('invites',1,$email));
        $cookoieval = uniqid(md5(time().$id));
        setcookie("invtduserjoins", $cookoieval, time()+60*60*24*100);// add cookie so that if the invited user register himself we can identify them.
        $q = "UPDATE ! set cookie_value = ? where id = ?";
        $res = $db->query($q, array('invites',$cookoieval,$id));
    }
    function reSendInviteMail()
    {
        global $db;
        $date = time();
        $date = $date - (RESENDINGDAYS * 24 * 60 * 60);
        $q = "SELECT distinct email, MIN(id) as id, lender from ! where date < ? AND visited=? AND reinvite =? group by email";
        $res = $db->getAll($q, array('invites',$date,0,0));
        if(!empty($res))
        {
            foreach($res as $row)
            {
                $p = "UPDATE ! set reinvite = ? where email = ?";
                $res1 = $db->query($p, array('invites',1,$row['email']));
            }
            return $res;
        }
        return 0;
    }
    function isLenderInThisLoan($loanid,$userid)
    {
        global $db;
        $q="SELECT count(lenderid) FROM  ! WHERE loanid = ? and lenderid = ? and active = ?";
        $count=$db->getOne($q, array('loanbids', $loanid, $userid, 1));
        if($count ==0)
            return false;
        else
            return true;
    }
    function isLenderForgivenThisLoan($loanid,$userid)
    {
        global $db;
        $q="SELECT count(lender_id) FROM  ! WHERE loan_id = ? and lender_id = ? ";
        $count=$db->getOne($q, array('forgiven_loans', $loanid, $userid));
        if($count ==0)
            return false;
        else
            return true;
    }
    function totalForgivenLendersThisLoan($loanid)
    {
        global $db;
        $q="SELECT count(lender_id) FROM ! WHERE loan_id = ? AND lender_id NOT IN(?)";
        $count=$db->getOne($q, array('forgiven_loans', $loanid, ADMIN_ID));
        return $count;
    }
    function isAllLenderForgivenThisLoan($loanid)
    {
        global $db;
        $p="SELECT count(lender_id) FROM ! WHERE loan_id = ? AND lender_id NOT IN(?)";
        $count1=$db->getOne($p, array('forgiven_loans', $loanid, ADMIN_ID));

        $q="SELECT count(Distinct(lenderid)) FROM  ! WHERE loanid = ? and active = ?";
        $count2=$db->getOne($q, array('loanbids', $loanid, 1));
        if($count1==$count2)
            return true;
        else
            return false;
    }
    function getLastForgiveDetailOfLender($userid)
    {
        global $db;
        $q="SELECT * FROM ! WHERE lender_id = ? order by id desc";
        $res=$db->getRow($q, array('forgiven_loans', $userid));
        return $res;
    }
    function getLenderReceivedAmountInThisLoan($loanid,$userid)
    {
        global $db;
        $q="SELECT sum(amount * conversionrate) as amount FROM  ! WHERE loanid = ? and userid = ? and txn_type = ?";
        $amount=$db->getOne($q, array('transactions', $loanid, $userid, LOAN_BACK_LENDER));
        return $amount;
    }
    function getLenderAmountWithInterestInThisLoan($loanid,$userid)
    {
        global $db;
        $q="SELECT SUM(givenamount) amount, SUM(givenamount * bidint)/SUM(givenamount) intr FROM ! WHERE loanid =? and active = ? and lenderid=?";
        $res=$db->getRow($q, array('loanbids', $loanid, 1, $userid));
        $q="SELECT period FROM ! WHERE loanid =?";
        $period=$db->getOne($q, array('loanapplic', $loanid));
        $extraPeriod=$this->getLoanExtraPeriod($userid, $loanid);
        $newperiod=$extraPeriod+$period;
        $intr=($res['amount'] * $res['intr'] * $newperiod)/1200;
        $total=$res['amount'] + $intr;
        $exRate=$this->getExRateByLoanId($loanid);
        $totalNative=$total * $exRate;
        return $totalNative;
    }
    function getDonationEmailByTransactionId($id)
    {
        global $db;
        $q = "SELECT email from ! Where transaction_id =?";
        $res=$db->getOne($q, array('donation', $id));
        return $res;
    }
    function updateScheduleAfterForgive($loan_id,$borrower_id,$forgiveAmount,$forgiven_loans_id)
    {
        global $db;
        $amountToReduce=0;
        $success=0;
        $totAmount=0;
        $forgiveAmountReduced=$forgiveAmount;
        $q="SELECT * FROM ! WHERE loanid =? and amount > ? and (paidamt is NULL OR paidamt < ?) order by id desc";
        $res=$db->getAll($q, array('repaymentschedule', $loan_id, 0, 10));
        if(!empty($res))
        {
            $p="SELECT * FROM ! WHERE userid=? AND loanid= ?";
            $resAll=$db->getAll($p, array('repaymentschedule', $borrower_id,$loan_id));
            if(!empty($resAll))
            {
                foreach($resAll as $row11)
                {
                    $q = "insert into ! (repaymentschedule_id, forgiven_loans_id, userid, loanid,duedate, amount, paiddate, paidamt) values (?, ?, ?, ?, ?, ?, ?, ?)";
                    $res2=$db->query($q, array('repaymentschedule_history', $row11['id'], $forgiven_loans_id, $row11['userid'], $row11['loanid'], $row11['duedate'], $row11['amount'], $row11['paiddate'], $row11['paidamt']));
                }
                $q1="SELECT count(*) FROM ! WHERE userid=? AND loanid= ? AND forgiven_loans_id=?";
                $countBk=$db->getOne($q1, array('repaymentschedule_history', $borrower_id,$loan_id, $forgiven_loans_id));
                if(count($resAll) !=$countBk) {
                    return $success;
                }
            } else {
                return $success;
            }
            $count=count($res);
            foreach($res as $row)
            {
                $totAmount +=$row['amount'];
            }
            if($totAmount >= $forgiveAmount)
            {
                $amountToReduce=number_format(($forgiveAmount/$count), 6, ".", "");
                foreach($res as $row)
                {
                    if($row['amount'] >=$amountToReduce)
                    {
                        $q = "UPDATE ! set amount= amount- ? where id=? and loanid=?";
                        $res2=$db->query($q, array('repaymentschedule', $amountToReduce, $row['id'], $row['loanid']));
                        $forgiveAmountReduced =number_format(($forgiveAmountReduced-$amountToReduce), 6, ".", "");
                    }
                    else
                    {
                        $q = "UPDATE ! set amount= ? where id=? and loanid=?";
                        $res2=$db->query($q, array('repaymentschedule', 0, $row['id'], $row['loanid']));
                        $forgiveAmountReduced =number_format(($forgiveAmountReduced-$row['amount']), 6, ".", "");
                    }
                }
            }
            else
            {
                foreach($res as $row)
                {
                    $q = "UPDATE ! set amount= ? where id=? and loanid=?";
                    $res2=$db->query($q, array('repaymentschedule', 0, $row['id'], $row['loanid']));
                    $forgiveAmountReduced =number_format(($forgiveAmountReduced-$row['amount']), 6, ".", "");
                }
            }
        }
        if($forgiveAmountReduced >=1)
        {
            $q="SELECT * FROM ! WHERE loanid =? and amount > ? and (paidamt is NOT NULL) order by id desc";
            $res=$db->getAll($q, array('repaymentschedule', $loan_id, 0));
            foreach($res as $row)
            {
                if($forgiveAmountReduced > 0)
                {
                    if($row['amount'] < $forgiveAmountReduced)
                    {
                        $q = "UPDATE ! set amount= ? where id=? and loanid=?";
                        $res2=$db->query($q, array('repaymentschedule', 0, $row['id'], $row['loanid']));
                        $forgiveAmountReduced =number_format(($forgiveAmountReduced-$row['amount']), 6, ".", "");
                        Logger_Array("forgive-log",'repayschedule row id '.$row['id'].' affected. It became zero');
                    }
                    else
                    {
                        $q = "UPDATE ! set amount= amount- ? where id=? and loanid=?";
                        $res2=$db->query($q, array('repaymentschedule', $forgiveAmountReduced, $row['id'], $row['loanid']));
                        $forgiveAmountReduced -=$forgiveAmountReduced;
                    }
                }
            }
        }
        if(abs($forgiveAmountReduced) < 1)
            $success=1;
        return $success;
    }
    function forgiveShare($loan_id,$borrower_id,$lender_id,$forgiveAmount,$damount)
    {
        global $db;
        $q = "insert into ! (loan_id, lender_id, borrower_id,amount, damount,date,tnc) values (?, ?, ?, ?, ?, ?,?)";
        $res1=$db->query($q, array('forgiven_loans', $loan_id, $lender_id, $borrower_id,$forgiveAmount,$damount, time(), 1));

        if($res1===DB_OK)
        {
            $q="SELECT id FROM ! WHERE lender_id =? AND loan_id= ?";
            $id=$db->getOne($q, array('forgiven_loans', $lender_id,$loan_id));
            return $id;
        }
        else
            return 0;
    }
    function getRepaidLoansForFeedbackReminder()
    {
        global $db;
        $date=time() -(5 * 7 * 24 * 60 *60);
        $date1=time();
        $r = "Select r.loanid, MAX(r.paiddate) as paiddate , ? - MAX(r.paiddate) as datediff, l.feedback_reminder, l.borrowerid From ! as r join ! as l on r.loanid = l.loanid Where  r.paiddate > ? AND r.loanid IN (Select loanid FROM ! where adminDelete =? AND (active = ? OR active = ?)) group by r.loanid";
        $res1=$db->getAll($r, array($date1, 'repaymentschedule', 'loanapplic', $date, 'loanapplic', 0, LOAN_REPAID, LOAN_DEFAULTED));
        return $res1;
    }
    function getLendersForFeedbackReminder($loanid)
    {
        global $db;
        $r = "Select lb.lenderid, l.Email, l.FirstName, l.LastName FROM ! as lb join ! as l on lb.lenderid=l.userid where lb.active =? AND lb.loanid= ? AND lb.lenderid NOT IN (Select partid FROM ! where loneid=?) group by lb.lenderid";
        $res1=$db->getAll($r, array('loanbids', 'lenders', 1, $loanid, 'comments', $loanid));
        return $res1;
    }
    function updateFeedbackReminder($loanid, $reminder)
    {
        global $db;
        $r = "UPDATE ! set feedback_reminder= ? where loanid = ?";
        $res1=$db->query($r, array('loanapplic', $reminder, $loanid));
        return $res1;
    }
    function isLenderGivenFeedback($loanid, $lenderid)
    {
        global $db;
        $r = "Select count(id) FROM ! where loneid =? and partid=?";
        $res1=$db->getOne($r, array('comments', $loanid, $lenderid));
        if(empty($res1))
            return false;
        else
            return true;
    }
    function getEmailAndPreference($useid)
    {
        global $db;
        $sql = "SELECT Email, FirstName , LastName,email_loan_repayment  FROM ! WHERE userid = ? ";
        $r3 = $db->getRow($sql, array('lenders', $useid));
        $r['name']=$r3['FirstName']." ".$r3['LastName'];
        $r['email']=$r3['Email'];
        $r['email_loan_repayment']=$r3['email_loan_repayment'];
        return $r;
    }
    function addBidPayment($loan_id, $lender_id, $borrower_id, $bidamt, $interest, $up)
    {
        global $db;
        $time=time();
        if(!empty($lender_id)) {
            $q = "insert into ! (lenderid, loanid, borrowerid, bidamt, bidint, bidup, date) values (?, ?, ?, ?, ?, ?, ?)";
            $res1=$db->query($q, array('bid_payment', $lender_id, $loan_id, $borrower_id, $bidamt, $interest, $up, $time));
        } else {
            if(!isset($_COOKIE['lndngcrtfrntlogdin'])) {
                setcookie("lndngcrtfrntlogdin", session_id());
            }
            $q = "insert into ! (lenderid, loanid, borrowerid, bidamt, bidint, bidup, date) values (?, ?, ?, ?, ?, ?, ?)";
            $res1=$db->query($q, array('bid_payment', $lender_id, $loan_id, $borrower_id, $bidamt, $interest, $up, $time));
        }
        if($res1===DB_OK) {
            $id = mysql_insert_id();
            if(!empty($id))
                return $id;
        }
        return 0;
    }
    function getBidAmount($id, $lenderid)
    {
        global $db;
        $r = "Select bidamt FROM ! where lenderid =? and id=?";
        $amt=$db->getOne($r, array('bid_payment', $lenderid, $id));
        return $amt;
    }
    function getBidDetail($id, $lenderid)
    {
        global $db;
        if(!empty($lenderid)) {
            $r = "Select * FROM ! where lenderid =? and id=?";
            $res=$db->getRow($r, array('bid_payment', $lenderid, $id));
        }else {
            $r = "Select * FROM ! where id=?";
            $res=$db->getRow($r, array('bid_payment', $id));
        }
        return $res;
    }
    function getBidDetailByCustom($custom)
    {
        global $db;
        $q = "select invoiceid from ! where custom = ? ";
        $invoiceid = $db->getOne($q, array('paypal_txns', $custom));
        $res=array();
        if($invoiceid) {
            $r = "Select * FROM ! where bidinvoiceid =?";
            $res=$db->getRow($r, array('bid_payment', $invoiceid));
        }
        return $res;
    }
    function setBidInvoice($id, $invoiceid)
    {
        global $db;
        $p="UPDATE ! set bidinvoiceid = ? where id = ?";
        $res=$db->query($p, array('bid_payment',$invoiceid,$id));
        if($res ===DB_OK)
            return 1;
        else
            return 0;
    }
    function CheckReferralCode($referral_code)
    {
        global $db;
        $p="SELECT count(id) from ! where code = ?";
        $count = $db->getOne($p, array('campaign',$referral_code));
        if($count < 1)
            return 0;
        return 1;
    }
    function CheckRefferalcodeMaxUsed($referral_code)
    {
        global $db;
        $p="SELECT max_use from ! where code = ?";
        $max_limit= $db->getOne($p, array('campaign',$referral_code));
        $p="SELECT count(id) from ! where referral_code = ?";
        $count = $db->getOne($p, array('referral_codes',$referral_code));
        if($max_limit<=$count )
            return 0;
        return 1;
    }
    function CheckReferralCodeStatus($referral_code)
    {
        global $db;
        $p="SELECT active from ! where code = ?";
        $status= $db->getOne($p, array('campaign',$referral_code));
        if($status==0)
            return 0;
        return 1;
    }
    function CheckReferralCodeUseRepeat($referral_code)
    {
        global $db;
        $Cookie_Refrralcode=$_COOKIE['xmtpysp'];
        if($Cookie_Refrralcode)
        {
            $p="SELECT referral_code from ! where cookie = ?";
            $code= $db->getOne($p, array('referral_codes',$Cookie_Refrralcode));
            if($code==strtoupper($referral_code))
                return 0;
        }
        return 1;
    }
    function CheckIpadress($referral_code)
    {
        global $db;
        $Ipadress=$_SERVER["REMOTE_ADDR"];
        $p="SELECT referral_code from ! where ip_address = ? order by id  DESC";
        $code= $db->getOne($p, array('referral_codes',$Ipadress));
        if(strtoupper($code)==strtoupper($referral_code))
            return 0;
        return 1;
    }

    function addReferralCode($referral_code,$user_id,$cookval,$txn_id)
    {
        global $db;
        $ip_adrr=$_SERVER["REMOTE_ADDR"];
        $p="SELECT id from ! where code = ? order by id  DESC";
        $camp_id= $db->getOne($p, array('campaign',$referral_code));
        $_SESSION['camp_id']=$camp_id;
        $q = "INSERT into ! (campaign_id,referral_code, user_id, ip_address, cookie,txn_id) values (?,?,?,?,?,?)";
        $data = $db->query($q, array('referral_codes',$camp_id,strtoupper($referral_code),$user_id,$ip_adrr,$cookval,$txn_id));
        if($data===DB_OK)
            return 1;
        return false;
    }
    function getCampMsgbyId($camp_id)
    {
        global $db;
        $p="SELECT message from ! where id = ?";
        $msg= $db->getOne($p, array('campaign',$camp_id));
        return $msg;
    }

    function getReferralCodeamount($referral_code)
    {
        global $db;
        $p="SELECT `value` from ! where `code` = ?";
        $amount= $db->getOne($p, array('campaign',$referral_code));
        if($amount>0)
            return $amount;
        return false;
    }
    function countUsedbyRefcode($referral_code)
    {
        global $db;
        $p="SELECT count(id) from ! where referral_code = ?";
        $count = $db->getOne($p, array('referral_codes',$referral_code));
        return $count;
    }
    function getAllLenderId()
    {
        global $db;
        $q="SELECT userid FROM ! where userid NOT IN(SELECT userid from ! where emailVerified=?)";
        $result=$db->getAll($q,array('lenders', 'users', 0));
        return $result;
    }
    function getFundUploaded($userid)
    {
        global $db;
        $q="SELECT SUM(amount) FROM ! WHERE userid=? AND (txn_type=? ||  txn_type=?  || txn_type=? )";
        $fund=$db->getOne($q, array('transactions', $userid,FUND_UPLOAD, DONATION, PAYPAL_FEE));

        if(empty($fund)){
            return 0;
        }

        return $fund;
    }

    function getFundDonation($userid)
    {
        global $db;
        $q="SELECT SUM(amount) FROM ! WHERE userid=? AND txn_type=?";
        $fund=$db->getOne($q, array(' transactions', $userid,DONATION));
        if(empty($fund)){
            return 0;
        }

        return $fund;
    }
    function getTransactionFee($userid)
    {
        global $db;
        $q="SELECT SUM(amount) FROM ! WHERE userid=? AND txn_type=?";
        $fund=$db->getOne($q, array(' transactions', $userid,PAYPAL_FEE));
        if(empty($fund)){
            return 0;
        }

        return $fund;
    }
    function SetAutoLendingSetting($status, $priority,$interestRate, $MaxinterestRateOther,$currnt_allocate, $userid, $availableAmt)
    {

        global $db;
        $time=date('Y-m-d G:i:s',time());
        if($currnt_allocate) {
            $availableAmt=0;
        }
        if(!$this->IsAutoLendingAlreadySet($userid,true)) {
            if($currnt_allocate) {
                $q = "INSERT into ! (lender_id,preference, current_allocated, lender_credit, desired_interest, max_desired_interest, created, Active) values (?, ?, ?, ?, ?, ?, ?, ?)";
                $result = $db->query($q, array('auto_lending', $userid, $priority, $currnt_allocate,  0, $interestRate, $MaxinterestRateOther, $time,$status));
            }else {
                $q = "INSERT into ! (lender_id,preference, current_allocated, lender_credit, desired_interest, max_desired_interest, created, Active) values (?, ?, ?, ?, ?, ?, ?, ?)";
                $result = $db->query($q, array('auto_lending', $userid, $priority, $currnt_allocate, $availableAmt, $interestRate, $MaxinterestRateOther, $time,$status));
            }
        } else {
            if($currnt_allocate) {
                $q = "UPDATE  ! set preference=?, desired_interest=?,max_desired_interest=?, current_allocated=?, lender_credit=?, modified=?, Active=? where lender_id=?";
                $result = $db->query($q, array('auto_lending', $priority,  $interestRate, $MaxinterestRateOther, $currnt_allocate, 0, $time, $status, $userid));
            }else {
                $q = "UPDATE  ! set preference=?, desired_interest=?, max_desired_interest=?,current_allocated=?, lender_credit=?, modified=?, Active=? where lender_id=? ";
                $result = $db->query($q, array('auto_lending', $priority,  $interestRate, $MaxinterestRateOther,  $currnt_allocate, $availableAmt, $time, $status, $userid));
            }

        }
        if($result===DB_OK) {
            return 1;
        }
        return 0;
    }
    function IsAutoLendingActivated($userid)
    {
        global $db;
        $q='SELECT count(*) as activated FROM ! WHERE  lender_id =? AND active=?';
        $r1= $db->getOne($q, array('auto_lending', $userid, 1));
        return $r1;
    }
    // this function is used to check if setting already set if set it takes backup of that record to		auto_lending_history table .
    function IsAutoLendingAlreadySet($userid,$update=false)
    {
        global $db;
        $q='SELECT * FROM ! WHERE  lender_id =?';
        $r1= $db->getRow($q, array('auto_lending', $userid));
        if($update && !empty($r1)) {
            $time=date('Y-m-d G:i:s',time());
            $q = "INSERT into ! (auto_lending_id, lender_id,preference, current_allocated, lender_credit, desired_interest, max_desired_interest,created, modified, Active, bk_date) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $db->query($q, array('auto_lending_history', $r1['id'],$r1['lender_id'], $r1['preference'], $r1['current_allocated'], $r1['lender_credit'],  $r1['desired_interest'], $r1['max_desired_interest'], $r1['created'], $r1['modified'], $r1['Active'], $time));
        }
        if(!empty($r1)) {
            return true;
        }
        return false;
    }
    function getAutoLendingStatus($userid){
        global $db;
        $q='SELECT Active  FROM ! WHERE  lender_id =?';
        $status= $db->getOne($q, array('auto_lending', $userid));
        if(empty($status)) {
            return 0;
        }
        return $status;
    }

    function getAutoLendingsetting($userid)
    {
        global $db;
        $q='SELECT * FROM ! WHERE  lender_id =?';
        $r1= $db->getRow($q, array('auto_lending', $userid));
        return $r1;
    }
    function getAllLenderForAutoLend()
    {
        global $db;
        $q='SELECT *  FROM ! WHERE  active=? AND bid_time < now() - INTERVAL 15 MINUTE';
        $r1= $db->getAll($q, array('auto_lending', 1));
        return $r1;
    }
    function getSortedLoanForAutoBid($preference, $result, $desired_interest, $max_desired_interest,  $fullyFundedAll)
    {
        global $db;
        $loans=array();
        $intOffer=array();
        foreach($result as $key=>$row) {
            if(in_array($row['loanid'], $fullyFundedAll)) {
                unset($result[$key]);
                $GLOBALS['loanArray'] = $result;
                continue;
            }
            $totBid=$this->getTotalBid($row['borrowerid'],$row['loanid']);
            $int = $row['interest'] - $row['WebFee'];
            if($totBid >= $row['reqdamt']) {
                $int = $this->getAvgBidInterest($row['borrowerid'],$row['loanid']);
            }
            if($desired_interest <= $int) {
                $row['intOffer'] = $int;
                $loans[] = $row;
                $intOffer[]=$int;
            }
        }
        if(!empty($loans)) {
            if($preference==HIGH_FEEDBCK_RATING) {
                $feedback=array();
                foreach($loans as $key=>$row) {
                    $report=$this->loanReport($row['borrowerid']);
                    $f=$report['feedback'];
                    $tf=$report['Totalfeedback'];
                    $loans[$key]['feedback']=$tf*$f;
                    $feedback[$key]=$f;
                    $totalfeedback[$key]=$tf;
                }
                array_multisort($feedback, SORT_DESC, $totalfeedback, SORT_DESC, $loans);
            } else if($preference==EXPIRE_SOON) {
                $applydate=array();
                foreach($loans as $key=>$row) {
                    $applydate[$key]=$row['applydate'];
                }
                array_multisort($applydate, SORT_ASC, $loans);
            } else if($preference==HIGH_OFFER_INTEREST) {
                array_multisort($intOffer, SORT_DESC, $loans);
            } else if($preference==HIGH_NO_COMMENTS) {
                $totComm=array();
                foreach($loans as $key=>$row) {
                    $query = "select count(id) from ! where receiverid = ?";
                    $count=$db->getOne($query,array('zi_comment',$row['borrowerid']));
                    $loans[$key]['totComment']=$count;
                    $totComm[$key]=$count;
                }
                array_multisort($totComm, SORT_DESC, $loans);
            } else {
                shuffle($loans);
            }
        }
        return $loans;
    }
    function addAutoLoanBid($LoanbidId, $lenderId, $brrowerid, $loanid, $Bidamount, $interestTobid)
    {
        global $db;
        $time=date('Y-m-d G:i:s',time());
        $q = "INSERT into ! (loanbid_id, lender_id, borrower_id, loan_id, amount, interest_rate, created) values (?, ?, ?, ?, ?, ?, ?)";
        $result = $db->query($q, array('auto_lendbids', $LoanbidId, $lenderId, $brrowerid, $loanid, $Bidamount, $interestTobid, $time));
        if($result===DB_OK) {
            return 1;
        }
        return 0;
    }

    /*
	Anupam 05-17-2012 , This function is copy of "amountInActiveBids" except we  are not including funded loans in this because of display purpose. we displaying amount in funded loans as invested amount not in active bids  but in calculation on site the amount in funded loans in active bids.
	we will use this function whenever we need just to display the amount in active bids.
	*/
    function amountInActiveBidsDisplay($userid)
    {
        global $db;
        $amtUsed = 0.0;
        $query = 'select sum(t.amount) from ! as t join ! as l on t.loanid = l.loanid where t.userid =? AND t.txn_type IN (?, ?) AND l.active =?';
        $res = $db->getOne($query, array('transactions', 'loanapplic', $userid, LOAN_BID, LOAN_OUTBID, LOAN_OPEN));
        if($res) {
            $amtUsed = $res * -1;
        }
        return $amtUsed;
    }
    function UpdateLenderCreditForAutoLend($userid, $amount) {
        global $db;
        $this->IsAutoLendingAlreadySet($userid, true);
        $q = "UPDATE  !  SET lender_credit= lender_credit + ? ,current_allocated = ? WHERE lender_id=?";
        $result = $db->query($q, array('auto_lending', $amount, 0, $userid ));
        if($result===DB_OK)
            return 1;
        return 0;
    }
    function getAllLoansForAutoLend() {
        global $db;
        $q="SELECT loanid, reqdamt, interest, WebFee, applydate, borrowerid FROM ! WHERE active=? AND adminDelete = ?";
        $result= $db->getAll($q, array('loanapplic', LOAN_OPEN, 0));
        return $result;
    }
    function isAutoLendCurrentCreditToUse($lenderid ) {
        global $db;
        $q="SELECT current_allocated FROM ! WHERE lender_id=?";
        $result = $db->getOne($q, array('auto_lending', $lenderid));
        if($result) {
            return 1;
        }
        return 0;
    }
    function setAccountExpiredMailSent($userid) {
        global $db;
        $q = "update ! set accountExpiedMail = ? where userid = ?";
        $res1=$db->query($q, array('users', 1, $userid));
    }
    function AddToLendingCart($refid, $type, $userid)
    {
        global $db;
        $time = time();
        if(!empty($userid)) {
            $q = "INSERT into ! (userid, ref_id,  type, status, created) values (?, ?, ?, ?, ?)";
            $result = $db->query($q, array('lending_cart',$userid, $refid, $type, 'START', $time));
        } else {
            if(isset($_COOKIE['lndngcrtfrntlogdin'])) {
                $cookival = $_COOKIE['lndngcrtfrntlogdin'];
            }else {
                $cookival = session_id();
            }
            $q = "INSERT into ! (ref_id,  type, status, session_id, created) values (?, ?, ?, ?, ?)";
            $result = $db->query($q, array('lending_cart',$refid, $type, 'START', $cookival, $time));
        }
        if($result===DB_OK) {
            return 1;
        }
        return 0;
    }
    function getLendingCart($userid=null) {
        global $db;

        if(!empty($userid)){
            $q="SELECT brwr.FirstName, brwr.LastName, brwr.City, brwr.Country ,bp.bidamt,bp.loanid, bp.borrowerid,lc.id, lc.type FROM ! lc join ! as bp on bp.id = lc.ref_id join borrowers as brwr on brwr.userid = bp.borrowerid WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY lc.created DESC";
            $result = $db->getALL($q, array('lending_cart', 'bid_payment', 1, $userid, 'START'));

            $q="SELECT lc.id, lc.type, gc.to_name, gc.card_amount FROM ! lc join ! as gc on gc.txn_id = lc.ref_id WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY created";
            $giftcards = $db->getALL($q, array('lending_cart', 'gift_cards', 2, $userid, 'START'));

            $q="SELECT lc.id, lc.type, dc.amount FROM ! lc join ! as dc on dc.id = lc.ref_id WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY created";
            $donation = $db->getALL($q, array('lending_cart', 'donation_transaction', 3, $userid, 'START'));

            $cartDetail= array_merge($result,$giftcards,$donation);
        } else {
            if(isset($_COOKIE['lndngcrtfrntlogdin'])) {
                $cookival = $_COOKIE['lndngcrtfrntlogdin'];
            }else {
                $cookival = session_id();
            }
            $q="SELECT brwr.FirstName, brwr.LastName, brwr.City, brwr.Country ,bp.bidamt,bp.loanid, bp.borrowerid,lc.id, lc.type FROM ! lc join ! as bp on bp.id = lc.ref_id join borrowers as brwr on brwr.userid = bp.borrowerid WHERE type = ? AND lc.session_id = ? AND lc.status = ? ORDER BY lc.created DESC";
            $cartbids = $db->getALL($q, array('lending_cart', 'bid_payment', 1, $cookival, 'START'));

            $q="SELECT lc.id, lc.type, gc.to_name, gc.card_amount FROM ! lc join ! as gc on gc.txn_id = lc.ref_id WHERE type = ? AND lc.session_id = ? AND lc.status = ? ORDER BY created";
            $giftcards = $db->getALL($q, array('lending_cart', 'gift_cards', 2, $cookival, 'START'));

            $q="SELECT lc.id, lc.type, dc.amount FROM ! lc join ! as dc on dc.id = lc.ref_id WHERE type = ? AND lc.session_id = ? AND lc.status = ? ORDER BY created";
            $donation = $db->getALL($q, array('lending_cart', 'donation_transaction', 3, $cookival, 'START'));

            $cartDetail= array_merge($cartbids,$giftcards,$donation);
        }

        return $cartDetail;
    }
    function GetOrderDetailForCart($id)
    {
        global $db;
        $q="SELECT * from ! where txn_id = ? order by id";
        $res = $db->getRow($q, array('gift_cards',$id));

        $q="SELECT count(*) giftcards from ! where txn_id = ?";
        $count = $db->getOne($q, array('gift_cards',$id));
        $res['count'] = $count;
        if(empty($res))
            Logger_Array("cvError_giftorderDetail",'Rows not found');
        return $res;
    }
    function amountInActiveBidsFunded($userid)
    {
        global $db;
        $amtUsed = 0.0;
        $query = 'select sum(t.amount) from ! as t join ! as l on t.loanid = l.loanid where t.userid =? AND t.txn_type IN (?, ?) AND l.active =?';
        $res = $db->getOne($query, array('transactions', 'loanapplic', $userid, LOAN_BID, LOAN_OUTBID, LOAN_FUNDED));
        if($res) {
            $amtUsed = $res * -1;
        }
        return $amtUsed;
    }
    function RemoveFromCart($cid)
    {
        global $db;
        $query="delete from ! where id = ? LIMIT 1";
        $result=$db->query($query,array('lending_cart',$cid));
        return $result;
    }
    function setLendingCartInvoice($invoiceid)
    {
        global $db;
        $p="UPDATE ! set invoiceid = ? where status = ?";
        $res=$db->query($p, array('lending_cart',$invoiceid,'START'));
        if($res ===DB_OK)
            return 1;
        else
            return 0;
    }
    function getcartDetailByCustom($custom)
    {
        global $db;
        $q = "select invoiceid from ! where custom = ? ";
        $invoiceid = $db->getOne($q, array('paypal_txns', $custom));
        $res=array();
        if($invoiceid) {
            $q="SELECT bp.bidamt,bp.loanid, bp.borrowerid, bp.bidint, lc.id, lc.type FROM ! lc join ! as bp on bp.id = lc.ref_id  WHERE type = ? AND lc.invoiceid =? AND lc.status = ? ORDER BY lc.id ASC LIMIT 1";
            $result = $db->getRow($q, array('lending_cart', 'bid_payment', 1, $invoiceid, 'COMPLETED'));
            if(empty($result)) {
                $q="SELECT count(*) as GiftCard FROM ! as lc WHERE lc.type = ? AND lc.invoiceid = ? AND lc.status = ?";
                $result = $db->getOne($q, array('lending_cart', 2, $invoiceid, 'COMPLETED'));
            }
            return $result;
        }
        return 0;
    }

    function getBidsFromCart($userid)
    {
        global $db,$session;
        $q="SELECT bp.bidamt,bp.loanid, bp.borrowerid, bp.bidint, lc.id, lc.type FROM ! lc join ! as bp on bp.id = lc.ref_id  WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY lc.id ASC";
        $result = $db->getALL($q, array('lending_cart', 'bid_payment', 1, $userid, 'START'));
        return $result;
    }
    function getGiftcardsFromCart($userid)
    {
        global $db,$session;
        $q="SELECT lc.id,lc.userid, lc.type, lc.ref_id, gc.card_amount, gc.to_name, gc.txn_id FROM ! lc join ! as gc on gc.txn_id = lc.ref_id WHERE lc.type = ? AND lc.userid = ? AND lc.status = ? ORDER BY created";
        $giftcards = $db->getALL($q, array('lending_cart', 'gift_cards', 2, $userid, 'START'));
        return $giftcards;
    }
    function updateCartStatus($id, $status)
    {
        global $db;
        $time = time();
        $p="UPDATE ! set status = ?, modified = ? where id = ?";
        $res=$db->query($p, array('lending_cart', $status, $time, $id));
        Logger("updateCartStatus".$res);
        if($res ===DB_OK)
            return 1;
        else
            return 0;
    }
    function GetOrderDetailbyCustom($custom)
    {
        global $db;
        $q = "select invoiceid from ! where custom = ?";
        $invoiceid = $db->getOne($q, array('paypal_txns', $custom));
        $res=array();
        if($invoiceid) {
            $r = "Select ref_id FROM ! where type =? AND invoiceid = ?";
            $txn_ids = $db->getAll($r, array('lending_cart', 2, $invoiceid));
            foreach($txn_ids as $txn_id) {
                $tids[] = $txn_id['ref_id'];
            }
            $ids = implode(',', $tids);
            $q="SELECT * from ! where txn_id in ( ".$ids." ) AND status = ? order by id";
            $res = $db->getAll($q, array('gift_cards',1));
        }
        return $res;
    }
    function setUseridByCookievalinCart($cookieval)
    {
        global $db, $session;
        $time = time();
        $userid = $session->userid;
        $p="UPDATE ! set userid = ? where session_id = ?";
        $res=$db->query($p, array('lending_cart', $userid, $cookieval));
        if($res ===DB_OK) {
            $q="SELECT GROUP_CONCAT(ref_id) as refids from ! where session_id = ? group by session_id";
            $refids = $db->getOne($q, array('lending_cart', $cookieval));

            $q1 = "UPDATE ! set lenderid = ? where id in ( ".$refids." )";
            $res1 = $db->query($q1, array('bid_payment', $userid));

            $q3 = "UPDATE ! set userid = ? where id in ( ".$refids." )";
            $res3 = $db->query($q3, array('donation_transaction', $userid));

            $q2 = "UPDATE ! set session_id = ? where userid = ?";
            $res2 = $db->query($q2, array('lending_cart', null, $userid));
        }
        else
            return 0;
    }
    function GetOrderDetailPaynow($cardids)
    {
        global $db;
        $gids = implode(',', $cardids);
        $res = array();
        if(!empty($gids)) {
            $r = "Select ref_id FROM ! where type =? AND id in ( ".$gids." )";
            $txn_ids = $db->getAll($r, array('lending_cart', 2));
            foreach($txn_ids as $txn_id) {
                $tids[] = $txn_id['ref_id'];
            }
            $ids = implode(',', $tids);
            $q="SELECT * from ! where txn_id in ( ".$ids." ) AND status = ? order by id";
            $res = $db->getAll($q, array('gift_cards',1));
        }
        return $res;
    }
    function getLenderImpact($userid)
    {
        global $db;
        $q="SELECT count(*) from ! where userid = ?";
        $res['invite_sent'] = $db->getOne($q, array('invites', $userid));

        $q="SELECT count(*) from ! where userid = ? AND invitee_id > ?";
        $res['invite_accptd'] = $db->getOne($q, array('invites', $userid, 0));


        $q="SELECT invitee_id from ! where userid = ? AND invitee_id > ?";
        $invitesIds = $db->getAll($q, array('invites', $userid, 0));
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $Ids[] = $row['invitee_id'];

            }
            $ids = implode(',',$Ids);
            $q= "select count(distinct (loanid)) from loanbids where lenderid IN ( ".$ids." ) ";
            //$q="SELECT count(*) FROM ! WHERE borrowerid IN ( ".$ids." ) AND adminDelete =?";
            $loanmade = $db->getOne($q);
            $res['invite_loan_made'] = $loanmade;
        }else {
            $res['invite_loan_made'] = 0;
        }

        $q="SELECT count(*) from ! as gc join ! as gt on gc.txn_id = gt.id where gt.userid = ? AND gt.status = 1";
        $res['gift_card_purchased'] = $db->getOne($q, array('gift_cards', 'gift_transaction', $userid));

        $q="SELECT count(*) from ! as gc join ! as gt on gc.txn_id = gt.id where gt.userid = ? AND gt.status = 1 AND gc.claimed = 1 AND claimed_by NOT IN ( $userid ) ";
        $res['gift_card_redeemed'] = $db->getOne($q, array('gift_cards', 'gift_transaction', $userid));

        $q="SELECT distinct(gc.claimed_by) from ! as gt join gift_cards as gc on gc.txn_id = gt.id where gt.userid = ? AND gt.status = ? AND claimed_by > ? AND claimed_by NOT IN ( $userid )";
        $GiftRecpIds = $db->getAll($q, array('gift_transaction', $userid, 1, 0 ));
        $res['giftrecp_loan_made']=0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $gIds[] = $row['claimed_by'];

            }
            $gcrids = implode(',',$gIds);
            $q= "select count(distinct (loanid)) from loanbids where lenderid IN ( ".$gcrids." ) ";
            $loanmade = $db->getOne($q);
            if(!empty($loanmade))
                $res['giftrecp_loan_made'] = $loanmade;
        }else {
            $res['giftrecp_loan_made'] = 0;
        }

        $res['invite_AmtLent'] = 0;
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $res['invite_AmtLent'] += $this->totalAmountLend($row['invitee_id'])+$this->amountInActiveBidsDisplay($row['invitee_id']);
            }

        }
        $res['Giftrecp_AmtLent'] = 0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $res['Giftrecp_AmtLent'] += $this->totalAmountLend($row['claimed_by'])+$this->amountInActiveBidsDisplay($row['claimed_by']);
            }
        }

        return $res;
    }

    function getMyImpact($userid)
    {
        global $db;

        $q="SELECT invitee_id from ! where userid = ? AND invitee_id > ?";
        $invitesIds = $db->getAll($q, array('invites', $userid, 0));

        $q="SELECT distinct(gc.claimed_by) from ! as gt join gift_cards as gc on gc.txn_id = gt.id where gt.userid = ? AND gt.status = ? AND claimed_by > ? AND claimed_by NOT IN ( $userid )";
        $GiftRecpIds = $db->getAll($q, array('gift_transaction', $userid, 1, 0));

        $res['invite_AmtLent'] = 0;
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $res['invite_AmtLent'] += $this->totalAmountLend($row['invitee_id'])+$this->amountInActiveBidsDisplay($row['invitee_id']);
            }

        }
        $res['Giftrecp_AmtLent'] = 0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $res['Giftrecp_AmtLent'] += $this->totalAmountLend($row['claimed_by'])+$this->amountInActiveBidsDisplay($row['claimed_by']);
            }
        }
        return $res;
    }
    function getLenderTotalImpact() {
        global $db;
        $lenderids = $this->getAllLenderId();
        foreach($lenderids as $lenderid) {
            $Lids[] = $lenderid['userid'];
        }
        $lids = implode(',',$Lids);

        $q="SELECT count(*) from ! where userid IN ( ".$lids." )";
        $res['invite_sent'] = $db->getOne($q, array('invites'));

        $q="SELECT count(*) from ! where userid IN ( ".$lids." ) AND invitee_id > ?";
        $res['invite_accptd'] = $db->getOne($q, array('invites', 0));

        $q="SELECT invitee_id from ! where userid IN ( ".$lids." ) AND invitee_id > ?";
        $invitesIds = $db->getAll($q, array('invites', 0));
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $Ids[] = $row['invitee_id'];

            }
            $ids = implode(',',$Ids);
            $q= "select count(distinct (loanid)) from loanbids where lenderid IN ( ".$ids." ) ";
            //$q="SELECT count(*) FROM ! WHERE borrowerid IN ( ".$ids." ) AND adminDelete =?";
            $loanmade = $db->getOne($q);
            $res['invite_loan_made'] = $loanmade;
        }else {
            $res['invite_loan_made'] = 0;
        }

        $q="SELECT count(*) from ! as gc join ! as gt on gc.txn_id = gt.id where gt.userid IN ( ".$lids." ) AND gt.status = 1";
        $res['gift_card_purchased'] = $db->getOne($q, array('gift_cards', 'gift_transaction'));

        $q="SELECT count(*) from ! as gc join ! as gt on gc.txn_id = gt.id where gt.userid IN ( ".$lids." ) AND gt.status = 1 AND gc.claimed = 1 AND claimed_by NOT IN ( ".$lids." ) ";
        $res['gift_card_redeemed'] = $db->getOne($q, array('gift_cards', 'gift_transaction'));

        $q="SELECT distinct(claimed_by) from ! where claimed_by > ?";
        $GiftRecpIds = $db->getAll($q, array('gift_cards', 0 ));
        $res['giftrecp_loan_made']=0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $gIds[] = $row['claimed_by'];

            }
            $gcrids = implode(',',$gIds);
            $q= "select count(distinct (loanid)) from loanbids where lenderid IN ( ".$gcrids." ) ";
            $loanmade = $db->getOne($q);
            if(!empty($loanmade))
                $res['giftrecp_loan_made'] = $loanmade;
        }else {
            $res['giftrecp_loan_made'] = 0;
        }
        $res['invite_AmtLent'] = 0;
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $res['invite_AmtLent'] += $this->totalAmountLend($row['invitee_id'])+$this->amountInActiveBidsDisplay($row['invitee_id']);
            }

        }
        $res['Giftrecp_AmtLent'] = 0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $res['Giftrecp_AmtLent'] += $this->totalAmountLend($row['claimed_by'])+$this->amountInActiveBidsDisplay($row['claimed_by']);
            }
        }
        return $res;


    }
    function lendergroup($name, $website, $about_grp, $createdby, $grp_leader) {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $groupid = 0;
        $q2="INSERT INTO lender_groups (`name`, `website`, `about_grp`, `created_by`, `grp_leader`)"." VALUES (?,?,?,?,?)";
        $res1=$db->query($q2, array($name, $website, $about_grp, $createdby, $grp_leader));
        $groupid = mysql_insert_id();
        $this->joinlendingGroup($groupid, $createdby);
        if($res1===DB_OK && $groupid > 0) {
            return $groupid;
        }
        return 0;
    }

//added by Julia 6-11-2013

    function bgroup($name, $website, $about_grp,  $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $createdby, $grp_leader) {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $groupid = 0;
        // Julia's changes updated by mohit on date 07-11-13
        $q2="INSERT INTO ! (name, website, about_grp, member_name1, member_email1, member_name2, member_email2, member_name3, member_email3, member_name4, member_email4, member_name5, member_email5, member_name6, member_email6, member_name7, member_email7, member_name8, member_email8, member_name9, member_email9, member_name10, member_email10, created_by, grp_leader)"." VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $res1=$db->query($q2, array('lender_groups',$name, $website, $about_grp, $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $createdby, $grp_leader));
        $groupid = mysql_insert_id();
        $this->joinlendingGroup($groupid, $createdby);
        if($res1===DB_OK && $groupid > 0) {
            return $groupid;
        }
        return 0;
    }


    function setGroupImage($group_photo, $grpId) {
        global $db;
        $modified = time();
        $q2 = "UPDATE ! set image = ?, modified = ? where id = ?";
        $res2 = $db->query($q2, array('lender_groups', $group_photo, $modified, $grpId));
        return $res2;
    }
    function getlendingGrouops($id=0) {
        global $db;
        if($id==0) {
            $q="SELECT * from ! ORDER BY name ASC";
            $groups = $db->getAll($q, array('lender_groups'));
        } else {
            $q="SELECT * from ! Where id = ?";
            $groups = $db->getRow($q, array('lender_groups',$id));
        }
        return $groups;
    }

    function joinlendingGroup($grpid, $userid) {
        global $db;
        $time = time();
        $q="SELECT count(*)  FROM ! WHERE group_id = ? AND member_id = ?";
        $isexist = $db->getOne($q, array('lending_group_members',$grpid, $userid));
        $q="SELECT count(*)  FROM ! WHERE group_id = ? AND leaved = ?";
        $activemembers = $db->getOne($q, array('lending_group_members',$grpid, 0));
        if($isexist==1){
            if($activemembers==0){
                $q = "UPDATE ! SET grp_leader = ? Where id = ?";
                $r=$db->query($q, array('lender_groups', $userid, $grpid));

            }
            $q = "UPDATE ! SET leaved = ? Where member_id = ? AND group_id = ?";
            $r=$db->query($q, array('lending_group_members', 0, $userid, $grpid));
            $result = 1;
        }else {
            if($activemembers==0){
                $q = "UPDATE ! SET grp_leader = ? Where id = ?";
                $r=$db->query($q, array('lender_groups', $userid, $grpid));
            }
            $q="INSERT INTO ! (group_id, member_id, leaved, created) VALUES (?,?,?,?)";
            $result=$db->query($q,array('lending_group_members', $grpid, $userid, 0, $time));
        }
        return $result;
    }
    function getLendingGroupMembers($grpid) {
        global $db;
        $q="SELECT u.username,lgm.id,lgm.member_id  FROM ! as lgm join ! as u on lgm.member_id	= u.userid WHERE group_id = ? AND leaved = ?";
        $members = $db->getALL($q, array('lending_group_members','users', $grpid, 0));
        return $members;
    }



    function IsmemberOfGroup($userid, $gid) {
        global $db;
        $q="SELECT count(*) from ! WHERE member_id = ? AND group_id = ? AND leaved = ?";
        $ismember = $db->getOne($q, array('lending_group_members', $userid, $gid, 0));
        if($ismember > 0) {
            return 1;
        }
        return 0;
    }


    //11-10-2012 Anupam returns the amount lent by lender and invitee 
    function getGroupImpact($ids)
    {
        global $db;

        $q="SELECT invitee_id from ! where userid IN(".$ids.") AND invitee_id > ?";
        $invitesIds = $db->getAll($q, array('invites', 0));
        $res['invite_AmtLent'] = 0;
        if(!empty($invitesIds)) {
            foreach($invitesIds as $row) {
                $res['invite_AmtLent'] += $this->totalAmountLend($row['invitee_id'])+$this->amountInActiveBidsDisplay($row['invitee_id']);
            }

        }
        $q="SELECT distinct(gc.claimed_by) from ! as gt join gift_cards as gc on gc.txn_id = gt.id where gt.userid IN(".$ids.") AND gt.status = ? AND claimed_by > ? AND claimed_by NOT IN(".$ids.")";
        $GiftRecpIds = $db->getAll($q, array('gift_transaction', 1, 0));

        $res['Giftrecp_AmtLent'] = 0;
        if(!empty($GiftRecpIds)) {
            foreach($GiftRecpIds as $row) {
                $res['Giftrecp_AmtLent'] += $this->totalAmountLend($row['claimed_by'])+$this->amountInActiveBidsDisplay($row['claimed_by']);
            }
        }
        return $res;
    }
    // 15-10-2012 Anupam user leaves the lending group
    function leavegroup($grpid, $userid) {
        global $db;
        $q = "UPDATE ! SET leaved = ? Where member_id = ? AND group_id = ?";
        $r=$db->query($q, array('lending_group_members', 1, $userid, $grpid));
        if($r===DB_OK) {
            return 1;
        }
        return 0;
    }

    function IsInvitedToGroup($useremail, $gid){
        global $db;
        $q= "select count(*) from ! where group_id=? AND (member_email1=?)";
        $res= $db->getOne($q, array('member_email1', $email, $gid));
        return $res;
    }

    //16-10-2012 Anupam return number of lenders who enables autolending
    function getAutoLendingLender(){
        global $db;
        $q="SELECT COUNT( * ) FROM ! WHERE Active=?";
        $res = $db->getOne($q, array('auto_lending', 1));
        return $res;
    }
    // 22-10-2012 Anupam returns lending group name by id
    function getLendingGroupnameByid($gid){
        global $db;
        $q="SELECT name  FROM ! WHERE id=?";
        $res = $db->getOne($q, array('lender_groups', $gid));
        return $res;
    }
    function getiscompleteLater($brwrid){
        global $db;

        $q="SELECT iscomplete_later FROM ! WHERE userid=?";
        $status = $db->getOne($q,array('borrowers',$brwrid));
        return $status;
    }
    function updategrpLeader($grpid, $grpleader){
        global $db;
        $q = "UPDATE ! SET grp_leader = ? WHERE id= ?";
        $r=$db->query($q, array('lender_groups', $grpleader, $grpid));
        return $r;
    }
    function updatelendergroup($gid, $name, $website, $about_grp, $createdby, $grp_leader){
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q = "UPDATE ! SET name=?, website=?, about_grp=?, created_by=?, grp_leader=?  WHERE id = ?";
        $r=$db->query($q, array('lender_groups', $name, $website, $about_grp, $createdby, $grp_leader, $gid));
        if($r===DB_OK) {
            return 1;
        }
        return 0;

    }
    // Added by mohit 07-11-2013
    function updatebgroup($gid, $name, $website, $about_grp, $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10,$member_email10, $createdby, $grp_leader){
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q = "UPDATE lender_groups SET name=?, website=?, about_grp=?,member_name1=?,member_email1=?,member_name2=?,member_email2=?,member_name3=?,member_email3=?,member_name4=?,member_email4=?,member_name5=?,member_email5=?,member_name6=?,member_email6=?,member_name7=?,member_email7=?,member_name8=?,member_email8=?,member_name9=?,member_email9=?,member_name10=?,member_email10=?,created_by=?, grp_leader=?  WHERE id = ?";
        $r=$db->query($q, array($name, $website, $about_grp,$member_name1, $member_email1,$member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10,$member_email10,$createdby, $grp_leader, $gid));
        if($r===DB_OK) {
            return 1;
        }
        return 0;

    }
    function lenderDenied($loanid, $lenderid){
        global $db;
        $q1= "SELECT lender_denied FROM ! where loanid=?";
        $result=$db->getOne($q1, array('loans_to_forgive',$loanid));
        if(!empty($result)){
            $lender_denied= $result.",".$lenderid;
            $q="UPDATE ! SET lender_denied=? WHERE loanid=?";
            $res=$db->query($q, array('loans_to_forgive',$lender_denied,$loanid));
        }
        else{
            $q = "UPDATE ! SET lender_denied=? WHERE loanid=?";
            $res=$db->query($q,array('loans_to_forgive', $lenderid, $loanid));
        }
        if($res) {
            return 1;
        }
        return 0;
    }
    function isLenderDeniedForgiveThisLoan($loan_id, $uid){
        global $db;
        $q1= "SELECT lender_denied FROM ! where loanid=?";
        $result=$db->getOne($q1, array('loans_to_forgive',$loan_id));
        $lids= explode(',',$result);
        $res=0;
        for($i=0; $i<sizeof($lids); $i++){
            if($uid===$lids[$i])
                $res++;
        }
        if($res===0)
            return False;
        else
            return True;
    }
    function updateGrpmsgNotify($grpid,$value,$uid){
        global $db;
        $q1= "SELECT groupmsg_notify FROM ! where userid=?";
        $grp_subscribes=$db->getOne($q1, array('lenders',$uid ));
        if($value==0) {
            $gids = explode(',',$grp_subscribes);
            if(($key = array_search($grpid, $gids)) !== false) {
                unset($gids[$key]);
            }
            $grp_subscribes = implode(',',$gids);
        }
        else {
            $gids = explode(',',$grp_subscribes);
            if(($key = array_search($grpid, $gids)) == false) {
                $grp_subscribes.=','.$value;
            }
        }
        $q = "UPDATE !  set groupmsg_notify=? where userid=?";
        $res=$db->query($q, array('lenders', $grp_subscribes,$uid ));
        if($res===0)
            return 0;
        else
            return 1;
    }
    function getlenderforgrppostemail($gid){
        global $db;
        $q1= "SELECT userid, Email, FirstName, LastName FROM ! where FIND_IN_SET( ?, groupmsg_notify )";
        $result=$db->getAll($q1, array('lenders',$gid));
        return $result;
    }
    function getlendergroupnotify($userid,$gid) {
        global $db;
        $q1= "SELECT groupmsg_notify FROM ! where userid = ? AND  FIND_IN_SET( ?, groupmsg_notify )";
        $result=$db->getOne($q1, array('lenders',$userid,$gid));
        if(empty($result))
            return 0;
        return $result;
    }
    function getAmtinLendingcart($userid) {
        global $db;
        $q="SELECT sum(bp.bidamt) as bidamt FROM ! lc join ! as bp on bp.id = lc.ref_id WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY lc.created DESC";
        $result = $db->getOne($q, array('lending_cart', 'bid_payment', 1, $userid, 'START'));

        $q="SELECT SUM(gc.card_amount) as cardamt FROM ! lc join ! as gc on gc.txn_id = lc.ref_id WHERE type = ? AND lc.userid = ? AND lc.status = ? ORDER BY created";
        $giftcards = $db->getOne($q, array('lending_cart', 'gift_cards', 2, $userid, 'START'));

        $cart['amt'] = $result+$giftcards;
        $cart['donation'] = ($cart['amt']*15)/100;
        return $cart;
    }
    function ForgivenLendersThisLoan($loanid) {
        global $db;
        $q="SELECT lender_id FROM ! WHERE loan_id = ? AND lender_id NOT IN(?) ";
        $lendersids=$db->getALL($q, array('forgiven_loans', $loanid, ADMIN_ID));
        return $lendersids;
    }
    function getoptedoutlenders($loanid) {
        global $db;
        $q1 = "SELECT lender_denied FROM ! WHERE loanid =? AND lender_denied IS NOT NULL";
        $lender_denied = $db->getOne($q1, array('loans_to_forgive', $loanid));
        $lids = $lender_denied;
        if(!empty($lender_denied)) {
            $lids = explode(',', $lender_denied);
        }
        return $lids;
    }
    function getnotrespondedlendersonforgive($loanid){
        global $db;
        $q='SELECT distinct(lenderid),l.FirstName, l.LastName, l.Email FROM ! as lb join ! as l on lb.lenderid=l.userid WHERE loanid =? AND lb.active=? AND lenderid NOT IN ( SELECT lender_id FROM ! WHERE loan_id =? )';
        $r1= $db->getAll($q, array('loanbids' , 'lenders', $loanid, 1, 'forgiven_loans',$loanid));
        $q1 = "SELECT lender_denied FROM ! WHERE loanid =?";
        $lender_denied = $db->getOne($q1, array('loans_to_forgive', $loanid));
        $lids = explode(',', $lender_denied);
        foreach($r1 as $key=>$row) {
            if(in_array($row['lenderid'], $lids)) {
                unset($r1[$key]);
            }
        }
        shuffle($r1);
        return $r1;
    }
    function getExpiredGiftCards()
    {
        global $db;
        $date= time();
        $q="SELECT id, card_code, card_amount from ! where status = ? and exp_date < ? order by id ";
        $res = $db->getAll($q, array('gift_cards',1, $date));
        return $res;
    }
    function setDonationTransaction($userid, $donation_amt, $date, $ip){
        global $db;
        if(!empty($userid)){
            $q="INSERT INTO ! (userid, amount, date, ip, txn_type) VALUES  (?,?,?,?,?)";
            $res1=$db->query($q, array('donation_transaction',$userid,$donation_amt,$date,$ip,''));
        }else{
            if(!isset($_COOKIE['lndngcrtfrntlogdin'])) {
                setcookie("lndngcrtfrntlogdin", session_id());
            }
            $q="INSERT INTO ! (amount, date, ip, txn_type) VALUES  (?,?,?,?)";
            $res1=$db->query($q, array('donation_transaction',$donation_amt, $date, $ip,''));
        }
        if(!empty($res1))
        {
            $q="SELECT id from ! where  amount = ? AND date =? AND ip =?";
            $id=$db->getOne($q, array('donation_transaction',$donation_amt,$date,$ip));
            $_SESSION['donation_id']=$id;
            $_SESSION['LendingCartDonation'] = true;
        }
        if($res1==1)
            return true;
        else
            return false;
    }
    function GetDonationDetailForCart($donation_id){
        global $db;
        $q="select * from ! where id=?";
        $res=$db->getRow($q, array('donation_transaction', $donation_id));
        return $res;
    }
    function getDonationFromCart($userid){
        global $db,$session;
        $q="SELECT lc.id,lc.userid, lc.type, lc.ref_id, dc.amount FROM ! lc join ! as dc on dc.id = lc.ref_id WHERE lc.type = ? AND lc.userid = ? AND lc.status = ? ORDER BY created";
        $donation = $db->getALL($q, array('lending_cart', 'donation_transaction', 3, $userid, 'START'));
        return $donation;
    }
    /* -------------------Lender Section End----------------------- */


    /* -------------------Partner Section Start----------------------- */

    function addPartner($username, $pass, $name, $address, $city, $country, $email, $emails_notify, $website, $desc, $language)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $time=time();
        $salt = $this->makeSalt();
        $pass= $this->makePassword($pass, $salt);
        $q="INSERT INTO ! (username, password, salt, userlevel, regdate, lang, emailVerified) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $r=$db->query($q, array('users', $username, $pass, $salt, PARTNER_LEVEL, $time, $language, 1));
        if($r===DB_OK)
        {
            $q="SELECT userid FROM ! WHERE username=?";
            $userid=$db->getOne($q, array('users', $username));
            $q="INSERT INTO ! (userid, name, postaddress, city, country, email, emails_notify, website, description, active) VALUES (?, ?, ?, ?,?,?,?, ?, ?, ?)";
            $r1=$db->query($q, array('partners', $userid, $name, $address, $city, $country, $email, $emails_notify, $website, $desc, 0));
            if($r1===DB_OK){
                return 0;
            }
            return 1;
        }
        return 1;
    }
    function updatePartner($username, $pass, $name, $address, $city, $country, $email, $emails_notify, $website, $ppostcomment, $desc, $id, $language)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $r = DB_OK;
        if(!empty($pass))
        {
            $salt = $this->makeSalt();
            $pass1= $this->make ($pass, $salt);
            $q = "UPDATE ! SET password = ?, salt=?, lang= ? WHERE userid = ?";
            $r=$db->query($q, array('users', $pass1, $salt, $language, $id ));
        }
        else
        {
            $q = "UPDATE ! SET lang= ? WHERE userid = ?";
            $r=$db->query($q, array('users', $language, $id ));
        }
        if($r===DB_OK)
        {
            $q = "UPDATE ! SET name=?, postaddress=?, city=?, country=?, email=?, emails_notify=?, website=?, postcomment=?, description=? WHERE userid = ?";
            $r1=$db->query($q, array('partners', $name, $address, $city, $country, $email, $emails_notify, $website, $ppostcomment, $desc, $id));
            if($r1===DB_OK){
                return 0;
            }
            return 1;
        }
        return 1;
    }
    function checkPartnerName($partner)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q="SELECT * FROM ".TBL_PARTNER." WHERE name='$partner'";
        $result=$db->getRow($q);
        if(empty($result)){
            return 0;
        }
        return 1;
    }
    function getPartnerSelfName($id)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q="SELECT name FROM ".TBL_PARTNER." WHERE userid='$id'";
        $result=$db->getOne($q);
        if(empty($result)){
            return 0;
        }
        return $result;
    }
    function getActiveBCount($pid)
    {
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q="SELECT COUNT(*) FROM ! WHERE PartnerId=?";
        $result=$db->getOne($q, array('borrowers', $pid));
        if(empty($result)){
            return "None";
        }
        else{
            return $result;
        }
    }
    function getPartnersEmail()
    {
        global $db;
        $q="select Email from ! ";
        $resultPartner=$db->getAll($q,array('partners'));
        $emails="select emails_notify from ! where emails_notify is NOT NULL AND emails_notify <> ''";
        $resultEmails=$db->getAll($emails,array('partners'));
        foreach($resultEmails as $row)
        {
            $nf_emails=explode(',',$row['emails_notify']);
            foreach($nf_emails as $email)
            {
                $resultPartner[]['Email']=$email;
            }
        }
        return $resultPartner;
    }
    function getPartnerStatus($pid)
    {
        global $db;
        $q="SELECT active FROM ! WHERE userid=?";
        $res=$db->getOne($q, array('partners', $pid));
        return $res;
    }
    function getPartnerDetails($id)
    {
        global $db;
        $q="SELECT * FROM !, ! WHERE partners.userid=? AND partners.userid=users.userid";
        $result=$db->getRow($q, array('partners', 'users', $id));
        return $result;
    }
    function getEmailP($useid)
    {
        global $db;
        $sql = "SELECT email, name   FROM ! WHERE userid = ?  ";
        $r3 = $db->getRow($sql, array('partners', $useid));
        $r['name']=$r3['name'];
        $r['email']=$r3['email'];
        return $r;
    }
    /* -------------------Partner Section End----------------------- */



    /* -------------------Comment Section Start----------------------- */

    function subFeedback($userid,$senderid,$comment, $type =0, $reply = 0)
    {
        global $db;
        $q="INSERT INTO ! (userid, comment, type, senderid, reply, editdate) VALUES (?,?,?,?,?, ?)";
        $result=$db->query($q,array('b_comments', $userid, $comment,  $type, $senderid, $reply, time()));
        if($result===DB_OK){
            return true;
        }
        return false;
    }
    function deleteComment($parentid, $senderid, $forumid)
    {
        global $db;
        $q="UPDATE ! set status = ? where id = ? and senderid=?  and forumid=?";
        $result=$db->query($q,array('zi_comment', 1, $parentid, $senderid, $forumid));
        return $result;
    }
    function deleteCommentReal($parentid, $senderid, $forumid)
    {
        global $db;
        $q="UPDATE ! set status = ? where id = ? and senderid=?  and forumid=?";
        $result=$db->query($q,array('zi_comment', 1, $parentid, $senderid, $forumid));
        include_once("indexer.php");
        deleteIndex3($parentid);
        return $result;
    }
    function getCommentFile($senderid, $receiverid, $commentid)
    {
        global $db;
        $query="select id, uploadfile from ! where commentid= ? order by id";
        $result=$db->getAll($query, array('zi_uploadcommentfile',$commentid));
        return $result;
    }
    function updateComment($message, $parentid, $senderid, $forumid)
    {
        global $db;
        /*Pranjal 5/8/2010 removed sender id from below query as when admin updates a comment the sender id does not match.*/
        //$q="UPDATE ! set message = ?,modified=? where id = ? and senderid=? and forumid=?";
        //$result=$db->query($q,array('zi_comment', $message,time(),$parentid, $senderid, $forumid));
        $q="UPDATE ! set message = ?,modified=? where id = ? and forumid=?";
        $result=$db->query($q,array('zi_comment', $message,time(),$parentid, $forumid));
        include_once("indexer.php");
        updateIndex3($parentid);
        return $result;
    }
    function updateCommentFile($filepath, $fileid)
    {
        global $db;
        $query="UPDATE ! set uploadfile = ? where id = ?";
        $result=$db->query($query,array('zi_uploadcommentfile', $filepath,$fileid));
        return $result;
    }
    function nextForumId($senderid, $userid)
    {
        global $db;
        $query="select max(forumid) from ! where senderid=? and receiverid=?";
        $result=$db->getOne($query,array('zi_comment', $senderid,$userid));
        return $result;
    }
    function nextCommentId($senderid, $userid, $parentid)
    {
        global $db;
        $query="select max(id) from ! where senderid=? and receiverid=? and parentid=?";
        $result=$db->getOne($query,array('zi_comment', $senderid,$userid,$parentid));
        return $result;
    }
    function insertCommentFile($resParent, $senderid, $receiverid, $resultForum, $FilePath)
    {
        global $db;
        $query="insert into ! (commentid,senderid,receiverid,forumid,uploadfile) values (?,?,?,?,?)";
        $result=$db->query($query, array('zi_uploadcommentfile', $resParent, $senderid, $receiverid, $resultForum, $FilePath));
        return $result;
    }
    function deleteCommentFile($ImgFile, $imgID)
    {
        global $db;
        $query="delete from ! where uploadfile = ? and id = ?";
        $result=$db->query($query,array('zi_uploadcommentfile',$ImgFile, $imgID));
        return $result;
    }
    function publishComment($PubID)
    {
        global $db;
        $query="update ! set publish=0 where id = ?";
        $result=$db->query($query,array('zi_comment',$PubID));
        return $result;
    }
    function UnpublishComment($PubID)
    {
        global $db;
        $query="update ! set publish=1 where id = ?";
        $result=$db->query($query,array('zi_comment',$PubID));
        return $result;
    }
    function ForumId($senderid, $receiverid, $forumid)
    {
        global $db;
        $query="select max(id) from ! where senderid=? and receiverid=? and forumid=? order by id";
        $res=$db->getOne($query,array('zi_comment',$senderid, $receiverid, $forumid));
        return $res;
    }
    function getDetailByForumId($id)
    {
        global $db;
        $query = "select forumid from zi_comment where receiverid = ".$id." union select forumid from zi_comment where senderid = ".$id." group by forumid order by forumid desc";
        $result=$db->getAll($query);
        return $result;
    }
    function getDetailCommentFile($forumid, $id)
    {
        global $db;
        $query="select * from ! where forumid=? and commentid=?";
        $result=$db->getAll($query,array('zi_uploadcommentfile',$forumid, $id));
        return $result;
    }
    function getNextDeleteId($forumid, $id)
    {
        global $db;
        $query="select * from zi_comment where id not in (SELECT a.id FROM `zi_comment` a,`zi_comment` b WHERE a.id=b.parentid) and forumid=".$forumid." and id=".$id;
        $result=$db->getOne($query);
        return $result;
    }
    function subFeedback1($userid,$senderid,$comment, $type =0, $reply = 0, $reschedule_id=0)
    {
        global $db;
        $q="INSERT INTO !  (senderid, receiverid, subject, message, parentid, thread,pub_date, reschedule_id ) VALUES (?,?,?,?,?, ?,?,?)";
        $result=$db->query($q,array('zi_comment', $senderid, $userid, $comment, $comment ,  0 , 0,time(),$reschedule_id));
        $commentid = mysql_insert_id();
        include_once("indexer.php");
        updateIndex3($commentid);

        $query="select (max(forumid)+1) from zi_comment";
        $resultForum=$db->getOne($query);

        $query = "update zi_comment set thread=$result,forumid=$resultForum where senderid=$senderid and receiverid=$userid and parentid=0 and id=$commentid";
        $result=$db->query($query);
        if($result!=DB_OK){
            return false;
        }
        else{
            return $commentid;
        }
    }
    function forumFeedback($senderid,$receiverid,$subject,$message,$parentid,$thread,$forumid, $status =0)
    {
        global $db;
        $q="INSERT INTO !  (senderid,receiverid,subject,message,parentid,forumid, thread, pub_date, modified,status) VALUES (?,?,?,?,?,?, ?,?,?,?)";
        $result=$db->query($q,array('zi_comment', $senderid , $receiverid ,  $subject , $message, $parentid,$forumid, $thread, time(),time(),$status));
        if($result!=DB_OK){
            return false;
        }
        else
        {
            $id = mysql_insert_id();
            include_once("indexer.php");
            updateIndex3($id);
            return $id;
        }
    }
    function getAllComment($userid,$start,$end)
    {
        global $db;
        $result = '';
        if(($end==0)&&($start==0)){
            $q="SELECT COUNT(*) FROM !  WHERE (userid = ? and type = ?) ORDER BY id DESC ";
            $result=$db->getOne($q, array('b_comments',$userid, 0));
        }
        else{
            $q="SELECT * FROM !  WHERE (userid = ? and type = ?) ORDER BY id DESC LIMIT ? , ?  ";
            $result=$db->getAll($q, array('b_comments',$userid, 0,$start,$end));
        }
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getAllCommentForum($userid,$senderid,$forumid,$start,$end)
    {
        global $db;
        $result = '';
        if(($end==0)&&($start==0)){
            $q="SELECT * FROM zi_comment  WHERE (receiverid = $userid and forumid=$forumid) union SELECT * FROM zi_comment  WHERE (senderid = $userid and forumid=$forumid) ORDER BY `left`";
            $result=$db->getAll($q);
        }
        else{
            $q="SELECT * FROM zi_comment  WHERE (receiverid = $userid and forumid=$forumid) union SELECT * FROM zi_comment  WHERE (senderid = $userid and forumid=$forumid) ORDER BY `left` LIMIT $start , $end  ";
            $result=$db->getAll($q);
        }
        $this->addTreeDepth($result);
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getAllCommentForLender($lenderid)
    {
        global $db;
        $q='SET SESSION group_concat_max_len = 6000;';
        $db->query($q);
        $q="SELECT group_concat(DISTINCT(CAST(la.borrowerid as CHAR))) as bid  from ! as la join ! as lb on la.loanid=lb.loanid where lb.lenderid = $lenderid  and la.active IN (?, ?, ?) and la.adminDelete =?";
        $bIds=$db->getOne($q, array('loanapplic', 'loanbids', LOAN_OPEN, LOAN_FUNDED, LOAN_ACTIVE, 0));
        $result=array();
        if(!empty($bIds)) {
            $time=time() - (30 * 24 * 60 * 60);
            $q="SELECT * FROM zi_comment  WHERE receiverid IN($bIds) AND pub_date > $time AND status = 0 union SELECT * FROM zi_comment  WHERE senderid IN($bIds) AND pub_date > $time AND status = 0 order by id desc";
            $result=$db->getAll($q);
        }
        return $result;
    }

    function addTreeDepth($result)
    {
        $right = array();
        for ($i = 0, $n = count($result); $i < $n; $i++ )
        {
            $item = $result[$i];
            $depth = $j = count($right) - 1;
            for ( ; $j >= 0; $j--)
            {
                if ($right[$j] < $item['right']) {
                    unset($right[$j]);
                    $depth--;
                }
            }
            $item['depth'] = $depth + 2;
            $right[$depth + 1] = $item['right'];
        }
    }
    function getAllreply($userid,$type,$start,$end)
    {
        global $db;
        $result = '';
        if(($end==0)&&($start==0)){
            $q="SELECT * FROM !  WHERE (userid = ? AND type = ? AND reply > 0 ) ORDER BY id ASC ";
            $result=$db->getAll($q, array('b_comments',$userid, $type));
        }
        else{
            $q="SELECT * FROM !  WHERE (userid = ? and type = ?) ORDER BY id ASC LIMIT ? , ?  ";
            $result=$db->getAll($q, array('b_comments',$userid,$type,$start,$end));
        }
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getPartnerComment($userid)
    {
        global $db;
        $q = "select a.id as id1, a.* , b.* from ! as a, ! as b   where a.userid = ? and b.userid = ? and b.reply=? and a.id = b.type ORDER BY a.id DESC";
        $result=$db->getAll($q, array('comments','b_comments',$userid,$userid,0));
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getPartnerCommentby($userid,$partid,$cid,$loanid=0)
    {
        global $db;
        $result = '';
        if($cid==0)
        {
            // get all as cid=0
            if(empty($loanid))
            {
                $q="SELECT a.id as id1, a.* , b.* FROM  ! as a, ! as b  WHERE a.userid = ? and b.userid = ? and b.reply=? and a.id = b.type and a.partid =? and a.loneid =? ORDER BY a.editDate DESC ";
                $result=$db->getAll($q, array('comments','b_comments',$userid,$userid,0,$partid,0));
            }
            else
            {
                $q="SELECT a.id as id1, a.* , b.* FROM  ! as a, ! as b  WHERE a.userid = ? and b.userid = ? and b.reply=? and a.id = b.type and a.loneid =? ORDER BY a.editDate DESC ";
                $result=$db->getAll($q, array('comments','b_comments',$userid,$userid,0,$loanid));
            }
        }
        else
        {
            //get a row as cid is given
            if(empty($loanid))
            {
                $q="SELECT a.id as id1, a.* , b.* FROM  ! as a, ! as b  WHERE a.userid = ? and b.userid = ? and b.reply=? and a.id = ? and b.type=? and a.partid =? and a.loneid =? ORDER BY a.editDate DESC ";
                $result=$db->getRow($q, array('comments','b_comments',$userid,$userid,0,$cid,$cid,$partid,0));
            }
            else
            {
                $q="SELECT a.id as id1, a.* , b.* FROM  ! as a, ! as b  WHERE a.userid = ? and b.userid = ? and b.reply=? and a.id = ? and b.type=? and a.loneid =? ORDER BY a.editDate DESC ";
                $result=$db->getRow($q, array('comments','b_comments',$userid,$userid,0,$cid,$cid,$loanid));
            }
        }
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function getRandomCommentAndUserName()
    {
        global $db;
        $query = "SELECT * FROM !  WHERE publish = ?  ORDER BY id desc limit 2" ;
        $result=$db->getAll($query,array('zi_comment',0));
        return $result;
    }
    function getCommentFromId($id)
    {
        global $db;
        $query = "SELECT * FROM !  WHERE id = ? " ;
        $result=$db->getRow($query,array('zi_comment', $id));
        return $result;
    }
    function group_post($userid,$senderid,$comment, $type =0, $reply = 0, $group_id) {
        global $db, $session;
        $q="INSERT INTO !  (senderid, receiverid, subject, message, parentid, group_id, thread,pub_date) VALUES (?,?,?,?,?,?,?,?)";
        $result=$db->query($q,array('lending_group_comment', $senderid, $userid, $comment, $comment ,  0 , $group_id, 0,time()));
        $commentid = mysql_insert_id();
        $query="select (max(forumid)+1) from lending_group_comment";
        $resultForum=$db->getOne($query);

        $query = "update lending_group_comment set thread=$result,forumid=$resultForum where id=$commentid";
        $result=$db->query($query);
        if($result!=DB_OK){
            return false;
        }
        else{
            $session->sendGrpmsgPostMailtoLenders($group_id, $comment, $commentid);
            return $commentid;
        }
    }
    function getgrpDetailByForumId($id) {
        global $db;
        $query = "select forumid from  lending_group_comment where receiverid = ".$id." union select forumid from  lending_group_comment where senderid = ".$id." group by forumid order by forumid desc";
        $result=$db->getAll($query);
        return $result;
    }
    function getAllGrpCommentForum($userid,$senderid,$forumid,$start,$end) {
        global $db;
        $result = '';
        if(($end==0)&&($start==0)){
            $q="SELECT * FROM  lending_group_comment  WHERE (receiverid = $userid and forumid=$forumid) union SELECT * FROM  lending_group_comment  WHERE (senderid = $userid and forumid=$forumid) ORDER BY `left`";
            $result=$db->getAll($q);
        }
        else{
            $q="SELECT * FROM  lending_group_comment  WHERE (receiverid = $userid and forumid=$forumid) union SELECT * FROM  lending_group_comment  WHERE (senderid = $userid and forumid=$forumid) ORDER BY `left` LIMIT $start , $end  ";
            $result=$db->getAll($q);
        }
        $this->addTreeDepth($result);
        if(empty($result)){
            return false;
        }
        return $result;
    }
    function forumgroup_post($senderid,$receiverid,$subject,$message,$parentid,$thread,$forumid, $grpid, $status =0)
    {
        global $db;
        $q="INSERT INTO !  (senderid,receiverid,subject,message,parentid,forumid, thread, pub_date, modified,status,group_id) VALUES (?,?,?,?,?,?, ?,?,?,?,?)";
        $result=$db->query($q,array('lending_group_comment', $senderid , $receiverid ,  $subject , $message, $parentid,$forumid, $thread, time(),time(),$status,$grpid));
        if($result!=DB_OK){
            return false;
        }
        else
        {
            $id = mysql_insert_id();
            return $id;
        }
    }
    function updateGrpComment($message, $parentid, $senderid, $forumid)
    {
        global $db;
        $q="UPDATE ! set message = ?,modified=? where id = ? and forumid=?";
        $result=$db->query($q,array('lending_group_comment', $message,time(),$parentid, $forumid));
        return $result;
    }
    function deleteGrpComment($parentid, $senderid, $forumid)
    {
        global $db;
        $q="UPDATE ! set status = ? where id = ? and senderid=?  and forumid=?";
        $result=$db->query($q,array('lending_group_comment', 1, $parentid, $senderid, $forumid));
        return $result;
    }
    function deleteGrpCommentReal($parentid, $senderid, $forumid)
    {
        global $db;
        $q="UPDATE ! set status = ? where id = ? and senderid=?  and forumid=?";
        $result=$db->query($q,array('lending_group_comment', 1, $parentid, $senderid, $forumid));
        return $result;
    }
    function getGrpNextDeleteId($forumid, $id)
    {
        global $db;
        $query="select * from lending_group_comment where id not in (SELECT a.id FROM `lending_group_comment` a,`lending_group_comment` b WHERE a.id=b.parentid) and forumid=".$forumid." and id=".$id;
        $result=$db->getOne($query);
        return $result;
    }
    function publishGrpComment($PubID)
    {
        global $db;
        $query="update ! set publish=0 where id = ?";
        $result=$db->query($query,array('lending_group_comment',$PubID));
        return $result;
    }
    function UnpublishGrpComment($PubID)
    {
        global $db;
        $query="update ! set publish=1 where id = ?";
        $result=$db->query($query,array('lending_group_comment',$PubID));
        return $result;
    }
    function getGrpCommentFile($senderid, $receiverid, $commentid)
    {
        global $db;
        $query="select id, uploadfile from ! where commentid= ? order by id";
        $result=$db->getAll($query, array('grp_uploadcommentfile',$commentid));
        return $result;
    }
    function insertGrpCommentFile($resParent, $senderid, $receiverid, $resultForum, $FilePath) {
        global $db;

        $query="insert into ! (commentid,senderid,receiverid,forumid,uploadfile) values (?,?,?,?,?)";
        $result=$db->query($query, array('grp_uploadcommentfile', $resParent, $senderid, $receiverid, $resultForum, $FilePath));
        return $result;
    }
    function deleteGrpCommentFile($ImgFile, $imgID) {
        global $db;
        $query="delete from ! where uploadfile = ? and id = ?";
        $result=$db->query($query,array('grp_uploadcommentfile',$ImgFile, $imgID));
        return $result;
    }
    function updateGrpCommentFile($filepath, $fileid) {
        global $db;
        $query="UPDATE ! set uploadfile = ? where id = ?";
        $result=$db->query($query,array('grp_uploadcommentfile', $filepath,$fileid));
        return $result;
    }
    function getGroupCommentFile($forumid, $id) {
        global $db;
        $query="select * from ! where forumid=? and commentid=?";
        $result=$db->getAll($query,array('grp_uploadcommentfile',$forumid, $id));
        return $result;
    }
    function nextGrpCommentId($senderid, $userid, $parentid) {
        global $db;
        $query="select max(id) from ! where senderid=? and receiverid=? and parentid=?";
        $result=$db->getOne($query,array('lending_group_comment', $senderid,$userid,$parentid));
        return $result;
    }
    function nextGrpForumId($senderid, $userid) {
        global $db;
        $query="select max(forumid) from ! where senderid=? and receiverid=?";
        $result=$db->getOne($query,array('lending_group_comment', $senderid,$userid));
        return $result;
    }
    function getgrpCommentFromId($id)
    {
        global $db;
        $query = "SELECT * FROM !  WHERE id = ? " ;
        $result=$db->getRow($query,array('lending_group_comment', $id));
        return $result;
    }

    /* -------------------Comment Section End----------------------- */

    /* -------------------Transaction Section Start----------------------- */

    function startDbTxn()
    {
        global $db;
        $db->autocommit(FALSE);
    }
    function commitTxn()
    {
        global $db;
        $db->commit();
        $db->autocommit(TRUE);
    }
    function rollbackTxn()
    {
        global $db;
        $db->rollback();
        $db->autocommit(TRUE);
    }
    function endDbTxn()
    {
        global $db;
        $db->autocommit(TRUE);
    }
    function setTransaction($userid,$amount,$txn_desc,$loanid, $rate, $txn_type, $return_id=0, $date=0, $sub_type=0, $loanbid_id=0)
    {
        global $db;
        $d=time();
        if($date !=0)
            $d=$date;
        $q="INSERT INTO ! (userid, amount, txn_desc, loanid,TrDate, conversionrate, txn_type, txn_sub_type, loanbid_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result=$db->query($q, array('transactions',$userid,$amount,$txn_desc,$loanid,$d, $rate, $txn_type, $sub_type, $loanbid_id));
        $p = $q="SELECT id FROM !  WHERE amount=? AND TrDate=?";
        $txn_id =$db->getOne($p, array('transactions',$amount,$d));
        if($result===DB_OK)
        {
            if($return_id==1) {
                return $txn_id;
            } else {
                return 1;
            }
            /*$res=$this->getTransaction($userid, 0);
			$sql = 'UPDATE ! SET T_Amount = ? WHERE userid = ? LIMIT 1';
			$res1=$db->query($sql, array('lenders',$res,$userid));
			if($res1===DB_OK)
				return 1 ;*/

        }
        return 0;
    }
    function setTransactionAmount($userid,$amount,$txn_desc,$loanid, $rate, $txn_type, $date=0)
    {
        global $db;
        $d=time();
        if($date !=0)
            $d=$date;
        $q="INSERT INTO ! (userid, amount, txn_desc, loanid,TrDate, conversionrate, txn_type) VALUES (?,?,?,?,?, ?, ?)";
        $result=$db->query($q, array('transactions',$userid,$amount,$txn_desc,$loanid,$d, $rate, $txn_type));
        if($result===DB_OK)
            return 1;
        else
            return 0;
    }
    function getTransaction($userid,$getall)
    {
        global $db;
        $result =0;
        if(empty($getall))
        {
            $q="SELECT SUM(amount) FROM !  WHERE userid=?";
            $result=$db->getOne($q,array('transactions',$userid));
        }
        else
        {
            $q="SELECT * FROM !  WHERE userid=? ORDER BY TrDate DESC ";
            $result=$db->getAll($q,array('transactions',$userid));
        }
        return $result;
    }
    function getTransactionNew($userid)
    {
        global $db;
        $result3 =0;
        $q="SELECT transactions.TrDate, transactions.amount, loanid,auto_lendbids.id as autoid,  txn_type, txn_desc, txn_sub_type FROM ! LEFT OUTER JOIN ! on  transactions.loanbid_id= auto_lendbids.loanbid_id WHERE userid=? ORDER BY TrDate";
        $result1=$db->getAll($q,array('transactions','auto_lendbids' ,$userid));
        $result1[0]['bal'] = $result1[0]['amount'];
        for($i=1; $i<count($result1); $i++)
        {
            $result1[$i]['bal'] = $result1[$i-1]['bal'] + $result1[$i]['amount'];
        }
        return array_reverse($result1);
    }
    function getTransactionTotalForLoan($userid,$loanid)
    {
        global $db;
        $q="SELECT SUM(amount) FROM !  WHERE userid=? and loanid = ?";
        $result=$db->getOne($q,array('transactions',$userid, $loanid));
        return $result;
    }
    function addNewPaySimpleTxn($userid, $amount,$txnStatus, $custom)
    {
        global $db;
        $txndate = time();
        $q = "insert into ! (status, userid, amount, txndate, updateddate, custom ) values (?, ?, ? ,?, ? , ?)";
        $res= $db->query($q, array('paysimple_txns', $txnStatus, $userid, $amount,$txndate, $txndate, $custom));
        if($res===DB_OK)
        {
            $q = "select invoiceid from ! where txndate = ? and status = ? and  userid = ? and  amount = ".$amount;
            $invoiceid = $db->getOne($q, array('paysimple_txns', $txndate, $txnStatus, $userid));
            if($invoiceid >0)
                return $invoiceid;
        }
        return 0;
    }
    function addNewPayPalTxn($userid, $amount, $paypalTranAmount, $paypal_donation, $totalAmt, $txnStatus, $custom, $txn_type='fund')
    {
        global $db;
        $txndate = time();
        $q = "insert into ! (status, userid, amount, paypal_tran_fee, donation, total_amount, txndate, updateddate, custom, txn_type) values (?, ?, ?, ? ,?, ? , ?, ?, ?, ?)";
        $res= $db->query($q, array('paypal_txns', $txnStatus, $userid, $amount, $paypalTranAmount, $paypal_donation, $totalAmt, $txndate, $txndate, $custom, $txn_type));
        $q = "select invoiceid from ! where txndate = ? and status = ? and  userid = ?";
        $invoiceid = $db->getOne($q, array('paypal_txns', $txndate, $txnStatus, $userid));
        return $invoiceid;
    }
    function updatePaySimpleRejTxn($invoiceid ,$txn_id)
    {
        global $db;
        $txndate = time();
        $txnStatus = "Rejected";
        $q = "select * from ! where invoiceid = ?";
        $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
        if($row['invoiceid'] == $invoiceid)
        {
            $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
            $db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
        }
        return $invoiceid;
    }
    function updatePaySimpleTxn($invoiceid ,$txn_id,$amount, $donation, $status)
    {
        global $db;
        /*  Note status 1 for complete, 2 for rejected*/
        $txndate = time();
        if($status==1)
        {
            $txnStatus = "Completed";
            $q = "select * from ! where invoiceid = ?";
            $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
            if($row['invoiceid'] == $invoiceid)
            {
                $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
                $res1=$db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
                if($res1===DB_OK)
                {
                    $res2= $this->setTransaction($row['userid'],$amount,'Funds upload to lender account',0,0,FUND_UPLOAD);
                    sleep(1);
                    if($res2===1)
                    {
                        if($donation >0)
                        {
                            $donationamt= $donation *-1;
                            $res3= $this->setTransaction($row['userid'],$donationamt,'Donation to Zidisha',0,0,DONATION);
                            if($res3===1)
                            {
                                $res4= $this->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION);
                                if($res4===1)
                                    return 1;
                            }
                            return 0;
                        }
                        return 1;
                    }
                }
            }
            return 0;
        }
        else if($status==2)
        {
            $txnStatus = "Rejected";
            $q = "select * from ! where invoiceid = ?";
            $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
            if($row['invoiceid'] == $invoiceid)
            {
                $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
                $res= $db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
                if($res===DB_OK)
                    return 1;
            }
            return 0;
        }
        else
            return 0;
    }
    function updatePaySimpleTxnForDonation($invoiceid ,$txn_id,$donation, $status, $name, $email)
    {
        global $db;
        /*  Note status 1 for complete, 2 for rejected*/
        $txndate = time();
        if($status==1)
        {
            $txnStatus = "Completed";
            $q = "select * from ! where invoiceid = ?";
            $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
            if($row['invoiceid'] == $invoiceid)
            {
                $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
                $res1=$db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
                if($res1===DB_OK)
                {
                    $transaction_id= $this->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION,1);
                    $q="INSERT INTO ! (transaction_id, name, email, donation_amt, payment_type) VALUES "."(?, ?, ?, ?, ?)";
                    $res3= $db->query($q,array('donation',$transaction_id, $name, $email, $donation, ECHECK));
                    if($res3===DB_OK)
                        return 1;
                }
            }
            return 0;
        }
        else if($status==2)
        {
            $txnStatus = "Rejected";
            $q = "select * from ! where invoiceid = ?";
            $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
            if($row['invoiceid'] == $invoiceid)
            {
                $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
                $res= $db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
                if($res===DB_OK)
                    return 1;
            }
            return 0;
        }
        else
            return 0;
    }
    function updatePaySimpleTxnForGift( $invoiceid ,$txn_id, $amount, $donation, $name, $email)
    {
        global $db;
        $txndate = time();
        $txnStatus = "Completed";
        $q = "select * from ! where invoiceid = ?";
        $row = $db->getRow($q, array('paysimple_txns', $invoiceid));
        if($row['invoiceid'] == $invoiceid)
        {
            $q = "update ! set txnid = ?, status = ?,  updateddate = ? where invoiceid = ? ";
            $res1= $db->query($q, array('paysimple_txns',$txn_id , $txnStatus, $txndate, $invoiceid));
            if($res1===DB_OK)
                $txn_id_trans = $this->setTransaction(0,$amount,'Gift Card Purchase',0,0,GIFT_PURCHAGE,1);
            else
                return 0;
            if($txn_id_trans!=0)
            {
                $p = "update ! set txn_id = ?, txn_type=? where invoiceid = ?";
                $res=$db->query($p, array('gift_transaction',$txn_id_trans, 'paysimple', $invoiceid));
                if($res===DB_OK)
                {
                    if($donation >0)
                    {
                        sleep(1);
                        $transaction_id= $this->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION,1);
                        $q="INSERT INTO ! (transaction_id, name, email, donation_amt, payment_type) VALUES "."(?, ?, ?, ?, ?)";
                        $res3= $db->query($q,array('donation',$transaction_id, $name, $email, $donation, ECHECK));
                        if($res3===DB_OK)
                            return 1;
                        else
                            return 0;
                    }
                    return 1;
                }
            }
        }
        return 0;
    }
    function updatePayPalTxn($txnid, $amount,$txnStatus, $custom, $invoiceid=null)
    {

        global $db, $session;
        $txndate = time();
        $row=array();
        if(!empty($invoiceid))
        {
            $q = "select * from ! where invoiceid = ? and custom = ? ";
            $row = $db->getRow($q, array('paypal_txns', $invoiceid, $custom));
        }
        else
        {
            $q = "select * from ! where custom = ? ";
            $row = $db->getRow($q, array('paypal_txns', $custom));
        }
        $rtnArr=array();
        $rtnArr['userid']=$row['userid'];
        $rtnArr['amount']=$row['amount'];
        $rtnArr['donation']=$row['donation'];
        $rtnArr['txn_type']=$row['txn_type'];
        if($row['txn_type']=='gift') {
            $order_id = $this->getOrderIdByInvoiceid($row['invoiceid']);
            $rtnArr['order_id']=$order_id;
            if(empty($row['userid'])) {
                $q = "select * from ! where txn_id = ? ";
                $giftDetail = $db->getAll($q, array('gift_cards', $order_id));
                $senderEmail='';
                $senderName='';
                foreach($giftDetail as $gift)
                {
                    if(!empty($gift['sender']) && empty($senderEmail))
                        $senderEmail=$gift['sender'];
                    if(!empty($gift['from_name']) && empty($senderName))
                        $senderName=$gift['from_name'];
                }
                $rtnArr['senderEmail']=$senderEmail;
                $rtnArr['senderName']=$senderName;
            }
        }
        if($amount == $row['total_amount'])
        {
            $q = "update ! set status = ?, updateddate = ?, txnid = ? where invoiceid = ? and custom = ?";
            $db->query($q, array('paypal_txns', $txnStatus, $txndate, $txnid, $row['invoiceid'], $custom));
            Logger_Array("cvError", 'transaction completed, invoice id, amount, status', $row['invoiceid'], $amount, $txnStatus);
            if($txnStatus == 'Completed' && $row['status'] != 'Completed')
            {
                if($row['txn_type']=='fund')
                {	$user_level = LENDER_LEVEL;
                    if(!empty($row['userid'])) {
                        $user_level = $this->getUserLevelbyid($row['userid']);
                    }
                    if(empty($row['userid']) || $user_level != LENDER_LEVEL) {
                        if($row['donation'] > 0) {
                            $res = $this->setTransaction(ADMIN_ID,$row['donation'],'Donation from non logged in user',0,0,DONATION);
                        }
                    }else {

                        $this->startDbTxn();
                        $txn_sub_type = UPLOADED_BY_PAYPAL;
                        $res1= $this->setTransaction($row['userid'],$row['total_amount'],'Funds upload to lender account',0,0,FUND_UPLOAD,0,0,$txn_sub_type);
                        $lenderid= $this->getBidPaymentidByInvoiceid($row['invoiceid']);
                        if(!empty($lenderid)) {
                            $uploadedAmount=bcsub(bcsub($row['total_amount'],$row['paypal_tran_fee'] ),$row['donation']);
                            $this->setBidInautolending($uploadedAmount, $lenderid);
                        }
                        sleep(1);
                        if($res1===1)
                        {
                            $paypal_tran_fee= $row['paypal_tran_fee'] *-1;
                            if($row['paypal_tran_fee'] > 0) {
                                $res2= $this->setTransaction($row['userid'],$paypal_tran_fee,'Paypal transaction fee',0,0,PAYPAL_FEE);
                            }else {
                                $res2=1;
                            }

                            sleep(1);
                            if($res2===1)
                            {
                                if($row['paypal_tran_fee'] > 0) {
                                    $res3= $this->setTransaction(ADMIN_ID,$row['paypal_tran_fee'],'Lender transaction fee',0,0,PAYPAL_FEE);
                                }else {
                                    $res3 = 1;
                                }
                                sleep(1);
                                if($res3===1)
                                {
                                    $return = false;
                                    if($row['donation'] >0)
                                    {

                                        $donationamt= $row['donation'] *-1;
                                        $res4= $this->setTransaction($row['userid'],$donationamt,'Donation to Zidisha',0,0,DONATION);
                                        sleep(1);
                                        if($res4===1)
                                        {
                                            $res5= $this->setTransaction(ADMIN_ID,$row['donation'],'Donation from lender',0,0,DONATION);
                                            if($res5===1)
                                            {
                                                $this->commitTxn();
                                                $return = true;
                                            }
                                            else
                                                $this->rollbackTxn();
                                        }
                                        else
                                            $this->rollbackTxn();
                                    }
                                    else
                                    {
                                        $this->commitTxn();
                                        $return = true;
                                    }
                                    $cartpayment = $this->IsLendingcartPayment($row['invoiceid'],$row['userid']);
                                    Logger("invoiceid and userid in updatePayPalTxn \n".$row['invoiceid']." ".$row['userid']);
                                    Logger("lending cart payment in  updatePayPalTxn \n".serialize($cartpayment));
                                    if($return == true && $cartpayment) {
                                        Logger("processing cart in updatePayPalTxn");
                                        $session->ProcessCart($row['userid']);
                                        return $rtnArr;
                                    }
                                }
                                else
                                    $this->rollbackTxn();
                            }
                            else
                                $this->rollbackTxn();
                        }
                        else
                            $this->rollbackTxn();
                    }
                }
                else if($row['txn_type']=='gift')
                {
                    $this->startDbTxn();
                    $txn_id_trans = $this->setTransaction(0,$row['amount'],'Gift Card Purchase',0,0,GIFT_PURCHAGE,1);
                    if($txn_id_trans!=0)
                    {
                        $p = "update ! set txn_id = ?, txn_type=? where invoiceid = ?";
                        $res1=$db->query($p, array('gift_transaction',$txn_id_trans, 'paypal', $row['invoiceid']));
                        if($res1===DB_OK)
                        {
                            $res3= $this->setTransaction(ADMIN_ID,$row['paypal_tran_fee'],'Lender transaction fee',0,0,PAYPAL_FEE);
                            if($row['donation'] >0)
                            {
                                sleep(1);
                                if(!empty($row['userid']))
                                {
                                    $userInfo=$this->getUserById($row['userid']);
                                    $transaction_id= $this->setTransaction(ADMIN_ID,$row['donation'],'Donation from lender',0,0,DONATION,1);
                                    $q="INSERT INTO ! (transaction_id, name, email, donation_amt, payment_type) VALUES "."(?, ?, ?, ?, ?)";
                                    $res3= $db->query($q,array('donation',$transaction_id, $userInfo['name'], $userInfo['email'], $row['donation'], PAYPAL));
                                }
                                else
                                {
                                    $transaction_id= $this->setTransaction(ADMIN_ID,$row['donation'],'Donation from lender',0,0,DONATION,1);
                                    $q="INSERT INTO ! (transaction_id, name, email, donation_amt, payment_type) VALUES "."(?, ?, ?, ?, ?)";
                                    $res3= $db->query($q,array('donation',$transaction_id, $senderName, $senderEmail, $row['donation'], PAYPAL));
                                }
                            }
                            if(empty($invoiceid))
                            {
                                $_SESSION['orderid']=$order_id;
                            }
                            $res2 = $this->updateGiftTransaction($order_id);
                            $this->commitTxn();
                            return $rtnArr;
                        }
                        else
                            $this->rollbackTxn();
                    }
                    else
                        $this->rollbackTxn();
                }
                else
                {
                    Logger_Array("cvError", 'transaction type mismatch while updating paypal transaction, invoice id, amount', $row['userid'], $invoiceid, $amount);
                    return array();
                }
            }
            else if(empty($invoiceid))
            {
                $rtnArr['txn_type']=$row['txn_type'];
                $order_id = $this->getOrderIdByInvoiceid($row['invoiceid']);
                if($txnStatus == 'Completed' && $row['status'] == 'Completed')
                {
                    $_SESSION['orderid']=$order_id;
                }
                return $rtnArr;
            }
        }
        else
        {
            Logger_Array("cvError", 'add paypal amount failed user id, invoice id, amount', $row['userid'], $invoiceid, $amount);
            return array();
        }
        Logger_Array("cvError", 'add paypal amount transaction failed, invoice id, amount', $row['userid'], $invoiceid, $amount);
        if($txnStatus == 'Completed') {
            return $rtnArr;
        }
        return array();
    }
    function updatePayPalTxnNonIPN($txnid, $amount,$txnStatus, $custom)
    {
        global $db;
        $txndate = time();
        $q = "select * from ! where custom = ? ";
        $row = $db->getRow($q, array('paypal_txns', $custom));
        if($amount == $row['amount'])
        {
            $q = "update ! set status = ? ,  amount = ? ,  updateddate = ? , txnid = ? where custom = ?   ";
            $db->query($q, array('paypal_txns', $txnStatus, $amount, $txndate, $txnid,  $custom));
            if($txnStatus == 'Completed' && $row['status'] != 'Completed')
            {
                $this->setTransaction($row['userid'],$amount,'Funds upload to lender account',0,0, FUND_UPLOAD);
            }
        }
        return $invoiceid;
    }
    function getAllPaypalTxn()
    {
        global $db;
        $q = "select * from ! order by txndate";
        $rows = $db->getAll($q, array('paypal_txns'));
        return $rows;
    }
    function saveRawIPNPaySimple($data)
    {
        global $db;
        $time= time();
        $q="INSERT INTO paysimple_ipn_raw_log (created_timestamp, ipn_data_serialized) VALUES (?, ?)";
        $res= $db->query($q,array($time, $data));
        if($res=== DB_OK)
        {
            $p="SELECT id from paysimple_ipn_raw_log where created_timestamp= ? and ipn_data_serialized= ?";
            $id= $db->getOne($p,array($time, $data));
            return $id;
        }
        return 0;
    }
    function saveRawIPN($data)
    {
        global $db;
        $q="INSERT INTO paypal_ipn_raw_log (created_timestamp, ipn_data_serialized) VALUES (?, ?)";
        $res1=$db->query($q, array(time(),$data));
        if($res1=== DB_OK){
            return 0;
        }
        return 1;
    }
    function PaySimplewithdraw($PaysimpleName, $PaysimpleAddress1, $PaysimpleAddress2, $PaysimpleCity, $PaysimpleState, $PaysimpleZip, $PaysimplePno, $PaysimpleAmt, $userid)
    {
        global $db;
        $q= 'INSERT INTO paysimple_withdraw ( userid , name , address1, address2, city, state, zip, phoneno, amount, date1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $result = $db->query($q, array($userid, $PaysimpleName, $PaysimpleAddress1, $PaysimpleAddress2, $PaysimpleCity, $PaysimpleState, $PaysimpleZip, $PaysimplePno, $PaysimpleAmt, time()));
        if($result===DB_OK){
            return 1;
        }
        return 0;
    }
    function otherwithdraw($OtherCurr, $OtherBname,$OtherBAddress, $OtherCity,$OtherCountry, $OtherAno, $OtherName,$amount,$userid)
    {
        global $db;
        $q= 'INSERT INTO other_withdraw( userid , bcurrency , bname, baddress, bcity, bcountry, accno, accname, amount, date1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
        $arr = array($userid, $OtherBname, $OtherBname,$OtherBAddress, $OtherCity,$OtherCountry, $OtherAno,$OtherName,$amount , time());
        $result = $db->query($q, $arr);
        if($result===DB_OK){
            return 1;
        }
        return 0;
    }
    function withdraw($amount,$userid, $paypalemail)
    {
        global $db;
        $q= 'INSERT INTO withdraw ( userid , amount , date, paypalemail) VALUES (?, ?, ?, ?)';
        $result = $db->query($q, array($userid, $amount , time(), $paypalemail));
        if($result===DB_OK){
            return 1;
        }
        return 0;
    }
    function getotherwithdraw($userid=0)
    {
        global $db;
        if(empty($userid))
        {
            $q= 'SELECT * FROM other_withdraw WHERE ( paid=? OR paid=?) ORDER BY date1 DESC';
            $result = $db->getAll($q, array(0,1));
        }
        else
        {
            $q= 'SELECT * FROM other_withdraw WHERE userid=? ORDER BY date1 DESC';
            $result = $db->getAll($q, array($userid));
        }
        if(!empty($result)){
            return $result;
        }
        return 0;
    }
    function getpaysimplewithdraw($userid=0)
    {
        global $db;
        if(empty($userid))
        {
            $q= 'SELECT * FROM paysimple_withdraw WHERE ( paid=? OR paid=?) ORDER BY date1 DESC';
            $result = $db->getAll($q, array(0,1));
        }
        else
        {
            $q= 'SELECT * FROM paysimple_withdraw WHERE userid=? ORDER BY date1 DESC';
            $result = $db->getAll($q, array($userid));
        }
        if(!empty($result)){
            return $result;
        }
        return 0;
    }
    function getwithdraw($userid=0)
    {
        global $db;
        if(empty($userid)){
            $q= 'SELECT * FROM withdraw WHERE ( paid=? OR paid=?) ORDER BY id DESC';
            $result = $db->getAll($q, array(0,1));
        }else{
            $q= 'SELECT * FROM withdraw WHERE userid=? ORDER BY date DESC';
            $result = $db->getAll($q, array($userid));
        }
        if(!empty($result)){
            return $result;
        }
        return 0;
    }
    function updateotherwithdraw($rowid)
    {
        global $db;
        $q = 'UPDATE other_withdraw SET paid = ? WHERE id = ? LIMIT 1';
        $result = $db->query($q, array(1,$rowid));
        if($result==DB_OK){
            return 1;
        }
        return 0;
    }
    function updatepaysimplewithdraw($rowid)
    {
        global $db;
        $q = 'UPDATE paysimple_withdraw SET paid = ? WHERE id = ? LIMIT 1';
        $result = $db->query($q, array(1,$rowid));
        if($result==DB_OK){
            return 1;
        }
        return 0;
    }
    function updatewithdraw($rowid)
    {
        global $db;
        $q = 'UPDATE withdraw SET paid = ? WHERE id = ? LIMIT 1';
        $result = $db->query($q, array(1,$rowid));
        if($result==DB_OK){
            return 1;
        }
        return 0;
    }
    function setGiftTransaction($userid,$order_type, $order_cost, $total_cost, $cards, $recipients, $tos, $froms, $msgs, $senders, $date, $ip)
    {
        global $db;
        Logger_Array("cvError",$userid,$order_type, $order_cost, $total_cost, $cards, $recipients, $tos, $froms, $msgs, $senders, $date, $ip);
        $exp_date=strtotime ('+1 year', $date);
        if(!empty($userid)){
            $q="INSERT INTO ! (userid,total_cards, amount, date, ip, txn_type) VALUES  (?,?,?,?,?,?)";
            $res1=$db->query($q, array('gift_transaction',$userid,$cards,$total_cost,$date,$ip,''));
        }else{
            if(!isset($_COOKIE['lndngcrtfrntlogdin'])) {
                setcookie("lndngcrtfrntlogdin", session_id());
            }
            $q="INSERT INTO ! (total_cards, amount, date, ip, txn_type) VALUES  (?,?,?,?,?)";
            $res1=$db->query($q, array('gift_transaction',$cards,$total_cost,$date,$ip,''));
        }
        Logger_Array("cvError_res1",$res1);
        $res2=null;
        if(!empty($res1))
        {
            $q="SELECT id from ! where  amount = ? AND date =? AND ip =?";
            $id=$db->getOne($q, array('gift_transaction',$total_cost,$date,$ip));
            Logger_Array("cvError_id",$id);
            $_SESSION['order_id']=$id;
            $_SESSION['LendingCartGift'] = true; //setting session so that it can be added into lending cart
            for($i=0; $i < $cards; $i++)
            {
                $card_code = getCardCode12($i+1);
                $tos[$i] = stripslashes(sanitize($tos[$i]));
                $froms[$i] = stripslashes(sanitize($froms[$i]));
                $msgs[$i] = nl2br(stripslashes(strip_tags(trim($msgs[$i]))));
                $p="INSERT INTO ! (txn_id,order_type,card_amount,recipient_email,to_name,from_name,message,sender,date,exp_date,card_code) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $res2=$db->query($p, array('gift_cards',$id,$order_type,$order_cost[$i],$recipients[$i],$tos[$i], $froms[$i], $msgs[$i], $senders[$i], $date,$exp_date, $card_code));
                Logger_Array("cvError_res2",$res2);
            }
        }
        if($res2==1)
            return true;
        else
            return false;
    }
    function updateGiftTransaction($id)
    {
        global $db;
        $p="UPDATE ! set status = ? where id = ?";
        $res1=$db->query($p, array('gift_transaction',1,$id));

        $q="UPDATE ! set status = ? where txn_id = ?";
        $res2=$db->query($q, array('gift_cards',1,$id));

        return true;
    }
    function setInvoiceId($id, $invoiceid)
    {
        global $db;
        $p="UPDATE ! set invoiceid = ? where id = ?";
        $res=$db->query($p, array('gift_transaction',$invoiceid,$id));
        if($res ===DB_OK)
            return 1;
        else
            return 0;
    }
    function setGiftDonation($id, $donation)
    {
        global $db;
        $p="UPDATE ! set donation = ? where id = ?";
        $res=$db->query($p, array('gift_transaction',$donation,$id));
        if($res ===DB_OK)
            return 1;
        else
            return 0;
    }
    function getOrderIdByInvoiceid($invoiceid)
    {
        global $db;
        $p="SELECT id from ! where invoiceid = ?";
        $res=$db->getOne($p, array('gift_transaction',$invoiceid));
        return $res;
    }
    function getTransactionStatus($invoiceid)
    {
        global $db;
        $p="SELECT status from ! where invoiceid = ?";
        $res=$db->getOne($p, array('paysimple_txns',$invoiceid));
        return $res;
    }
    function CheckGiftCardCode($card_code)
    {
        global $db;
        $p="SELECT count(id) from ! where card_code = ?";
        $count = $db->getOne($p, array('gift_cards',$card_code));
        if($count > 1)
            return 3;  /*   3 for card code is repeated so can not process further */
        $q="SELECT status from ! where card_code = ?";
        $status = $db->getOne($q, array('gift_cards',$card_code));
        if($status == 1)
            return 1;  /*   1 for card code is valid and is purchaged*/
        else if($status == 0)
            return 0;   /*   0 for card code is valid and is not purchaged*/
        else
            return 2;  /*   2 for card code is not valid  */
    }
    function CheckGiftCardClaimed($card_code)
    {
        global $db;
        $q="SELECT claimed from ! where card_code = ?";
        $res = $db->getOne($q, array('gift_cards',$card_code));
        if($res == 1)
            return 1;  /*   1 for Card is already claimed */
        else if($res == 0)
            return 0;   /*   for Card is not claimed */
    }
    function CheckGiftCardExpired($card_code)
    {
        global $db;
        $q="SELECT claimed, exp_date from ! where card_code = ?";
        $res = $db->getRow($q, array('gift_cards',$card_code));
        $date = time();
        if($res['exp_date'] < $date && $res['claimed'] == 0)
            return 1;  /*   1 for Card is Expired */
        else
            return 0;   /*   for Card is not Expired */
    }
    function donate_card($id,$card_code)
    {
        global $db;

        $q="UPDATE ! set donated = ? where id = ? AND card_code = ?";
        $res = $db->query($q, array('gift_cards',1,$id, $card_code));
        if($res == 1)
            return 1;
        else
            return 0;
    }
    // Anupam ( 19-09-2012), added second argument userid in the function so that e can track who claimed the gift card.
    function setGiftCardClaimed($card_code, $userid)
    {
        global $db,$session;
        if(empty($userid)) {
            $userid = $session->userid;
        }
        logger("claimed_by ".$userid."  card code ".$card_code);
        $q="UPDATE ! set claimed = ?, claimed_by = ? where card_code = ?";
        $res = $db->query($q, array('gift_cards',1, $userid, $card_code));

        return $res;
    }
    function getGiftCardExpireDate($card_code)
    {
        global $db;
        $q="SELECT exp_date from ! where card_code = ?";
        $res = $db->getOne($q, array('gift_cards',$card_code));
        return $res;
    }
    function GetOrderDetail($id)
    {
        global $db;
        $q="SELECT * from ! where txn_id = ? AND status = ? order by id";
        $res = $db->getAll($q, array('gift_cards',$id, 1));
        if(empty($res))
            Logger_Array("cvError_giftorderDetail",'Rows not found');
        return $res;
    }
    function GetOrderDetailSender($id)
    {
        global $db;
        $q="SELECT * from gift_cards where txn_id = $id AND status = 1 AND sender != '' order by sender, id";
        $res = $db->getAll($q);
        return $res;
    }
    function GetOrderDetailReciever($id)
    {
        global $db;
        $q="SELECT * from gift_cards where txn_id = $id AND status = 1 AND recipient_email != '' order by id";
        $res = $db->getAll($q);
        return $res;
    }
    function GetOrderDetailByCardCode($id,$card_code)
    {
        global $db;
        $q="SELECT * from gift_cards where txn_id = $id AND status = 1 AND card_code = '$card_code'";
        $res = $db->getRow($q);
        if($res)
            return $res;
        else
            false;
    }
    function GetOrderAmount($id)
    {
        global $db;
        $q="SELECT amount, donation from ! where id = ?";
        $res = $db->getRow($q, array('gift_transaction',$id));
        return $res;
    }
    function GetGiftCardAmount($card_code)
    {
        global $db;
        $q="SELECT card_amount from ! where card_code = ?";
        $res = $db->getOne($q, array('gift_cards',$card_code));
        return $res;
    }
    function getAllGiftCards($rowcount)
    {
        global $db;
        $limit=PAGINATION;
        $p="SELECT count(id) from ! where status = ?";
        $count = $db->getOne($p, array('gift_cards',1));
        if($count >0)
        {
            $q="SELECT * from ! where status = ? order by id LIMIT $rowcount, $limit";
            $res = $db->getAll($q, array('gift_cards',1));
            $res[0]['count'] = $count;
            return $res;
        }
        return false;
    }
    function addEvent($event_name, $event_fields)
    {
        global $db;
        $events=implode(',', $event_fields);
        $q = "insert into ! (event_name, event_fields,created,modified) values (?, ?, now(), now())";
        $res1=$db->query($q, array('events', $event_name, $events));
    }
    function getEvents($event_name)
    {
        global $db;
        $r = "Select * FROM ! where event_name = ? AND processed=?";
        $res1=$db->getAll($r, array('events', $event_name, 0));
        return $res1;
    }
    function updateEvent($id)
    {
        global $db;
        $r = "UPDATE ! set  processed= ?, modified=now() where id= ?";
        $res1=$db->query($r, array('events', 1, $id));
        return $res1;
    }
    function getTotalLenderAmount() {
        global $db;
        $q ="SELECT t.userid, SUM(totalamt) as totalamt, SUM(fund) as fund, SUM(donation) as donation, SUM(paypalfee) as paypalfee, SUM(donateDate) as donateDate, l.FirstName, l.LastName, l.City, l.Country, l.Email, l.Active, u.last_login, u.regdate FROM ( SELECT userid, sum(amount) as totalamt, if(txn_type = ".FUND_UPLOAD.", sum(amount) , 0) as fund, if(txn_type =".DONATION." AND txn_sub_type <>".DONATE_BY_ADMIN.", sum(amount), 0) as donation, if(txn_type =".PAYPAL_FEE.", sum(amount), 0) as paypalfee, if(txn_type =".DONATION." AND txn_sub_type=".DONATE_BY_ADMIN.", max(TrDate), 0) as donateDate FROM ! group by userid, txn_type) AS t join ! as l on t.userid = l.userid join ! as u on l.userid = u.userid group by t.userid";
        $q2="SELECT SUM(amount) as avail_amt FROM ! as t join ! as l on t.userid = l.userid";
        $totalAvail=$db->getOne($q2,array('transactions', 'lenders'));
        $total=$db->getAll($q,array('transactions', 'lenders', 'users'));
        $return = array();
        $return['total']=$totalAvail;
        $return['lenders']=$total;
        return $return;
    }
    function getBidPaymentidByInvoiceid($invoiceid) {
        global $db;
        $q2="SELECT lenderid FROM ! WHERE  bidinvoiceid = ?";
        $bidpayid=$db->getOne($q2,array('bid_payment', $invoiceid));
        if(!empty($bidpayid)) {
            return $bidpayid;
        }
        return 0;
    }
    function setBidInautolending($uploadedAmount, $lenderid)
    {
        global $db;
        $r = "UPDATE ! set  bid_amount= ?, bid_time=now() WHERE lender_id = ?";
        $res1=$db->query($r, array('auto_lending', $uploadedAmount, $lenderid));
        return $res1;
    }
    function updateGiftTransactionCart($txn_id_trans, $id)
    {
        global $db;
        $r = "UPDATE ! set  txn_id = ?, txn_type = ? , status = ? WHERE id = ?";
        $res1=$db->query($r, array('gift_transaction', $txn_id_trans, 'paypal', 1, $id));
        if($res1===1) {
            $q="UPDATE ! set status = ? where txn_id = ?";
            $res2=$db->query($q, array('gift_cards',1,$id));
            return 1;
        }
        return 0;
    }

    function IsLendingcartPayment($invoiceid, $userid) {
        global $db;
        $q2="SELECT count(*) as rows FROM ! WHERE  invoiceid = ? AND userid = ?";
        $cartid = $db->getOne($q2,array('lending_cart', $invoiceid, $userid));
        if($cartid > 0) {
            return 1;
        }
        return 0;
    }
    /* -------------------Transaction Section End----------------------- */

    /* -------------------Test case Section Start----------------------- */

    function getLoanApplic($loanid){
        global $db;
        $q="select AmountGot, active,grace, installment_day from ! where loanid=?";
        $result= $db->getRow($q, array('loanapplic', $loanid));
        return $result;
    }

    function getTransactionDetail($userid, $loanid){
        global $db;
        $q= "select amount, txn_desc, txn_type from ! where userid=? and loanid=?";
        $result= $db->getAll($q, array('transactions',$userid, $loanid ));
        return $result;
    }

    function selectLoanStatus($userid){
        global $db;
        $q= "select ActiveLoan from ! where userid= ?";
        $result=$db->getOne($q, array('borrowers', $userid));
        return $result;
    }

    function selectRepaymentSchedule($userid, $loanid){
        global $db;
        $q= "select duedate, amount from ! where loanid=? and userid=?";
        $result= $db->getAll($q, array('repaymentschedule', $loanid, $userid));
        return $result;
    }
    function CheckLoandisbursement($loanid) {
        global $db;
        $q2="SELECT count(*) as rows FROM ! WHERE  loanid = ? AND txn_type = ?";
        $rows = $db->getOne($q2,array('transactions', $loanid, DISBURSEMENT));
        if($rows > 1) {
            return 0;
        }
        return 1;
    }
    /* -------------------Test Case Section End----------------------- */

    /* -------------- added by mohit 24-10-13 ---------- */
    function saveRepayReport($id,$name,$number,$date,$note,$borrowerid ,$loanid,$isedit,$mentor)
    {
        global $db;
        $res=0;
        $res1 = 0;
        $currentdate = time();
        if((!empty($id))) {
            $q = "UPDATE ! set rec_form_offcr_name = ?, rec_form_offcr_num = ?, mentor_id=? where userid = ?";
            $res = $db->query($q, array('borrowers_extn', $name, $number, $mentor, $borrowerid ));
        }
        if(!empty($borrowerid)) {
            $p = "SELECT id, note from ! where borrowerid = ?";
            $repaydet = $db->getRow($p,array('repay_report_detail', $borrowerid));
            if(!empty($repaydet['id'])) {
                $new_note = $repaydet['note']." ".$note;
                if($isedit==1) {
                    $q1 = "UPDATE ! set expected_repaydate = ?, note = ?, modified = $currentdate where id = ?";
                    $res1 = $db->query($q1, array('repay_report_detail', $date, $note, $repaydet['id'] ));
                } else {
                    $q1 = "UPDATE ! set expected_repaydate = ?, note = ?, modified = $currentdate where id = ?";
                    $res1 = $db->query($q1, array('repay_report_detail', $date, $new_note, $repaydet['id'] ));
                }
            }else {
                $q1 = "INSERT INTO ! (borrowerid, expected_repaydate, note, created) VALUES  (?,?,?,?)";
                $res1=$db->query($q1, array('repay_report_detail', $borrowerid, $date, $note, $currentdate));
            }
        }
        else{
            Logger("REPAYRPT: blank data bid = ".$borrowerid. "loanid =".$loanid ."\n");
        }
        if($res1 === 1) {
            if($isedit ==1) {
                return 2;
            } else {
                return 1;
            }
        }
        else
            return 0;
    } // end here

    public function IsIpExist($ip,$borrowerid){	// Added By Mohit on date 28-10-13
        global $db;
        $data=array();
        $q= "select endorserid from ! where borrowerid=?";
        $result= $db->getAll($q, array('endorser',$borrowerid));
        $flag=0;
        $i=0;
        foreach($result as $row)
        {
            $p= "select ip_address from ! where userid=?";
            $rec = $db->getAll($p, array('facebook_info',$row['endorserid']));
            foreach($rec as $val)
            {
                if($val['ip_address']===$ip){

                    if($i==1){
                        $flag=1;
                    }
                    $i++;
                }
            }
        }
        return $flag;
    }

    // Added by mohit 12-11-13
    function getVolMentStaffMemList($country,$assignedto){
        global $db;
        $q="select * from ! where country=? and status=?";
        $result1= $db->getAll($q, array('community_organizers' , $country, 1));
        $r="SELECT userid as user_id from ! WHERE isTranslator=?";
        $result2=$db->getAll($r, array('lenders',1));
        $volunteers = array_merge($result1,$result2);

        foreach($volunteers as $key =>$value)
        {
            $row= $this->getUserById($value['user_id']);
            $city = '';
            $TelMobile='';
            if(!empty($row['City'])) {
                $volunteers[$key]['City'] = $row['City'];
            }else {
                $volunteers[$key]['City'] = '';
            }
        }
        $sortedvolunteers = array_sort($volunteers,'City', 'SORT_ASC','City');

        echo "<option value='0'>All</option>";
        foreach($sortedvolunteers as $volunteers){

            $rows= $this->getUserById($volunteers['user_id']);
            ?>
        <option value="<?php echo $volunteers['user_id']?>" <?php if($assignedto==$volunteers['user_id']) echo "Selected";?>>
            <?php echo $rows['City'].": ".$rows['name'].", tel ".$rows['TelMobile'];
            echo "</option>";
        }


    }

    function sharebox_off($id,$set){
        global $db;
        traceCalls(__METHOD__, __LINE__);
        $q = "UPDATE ! SET sharebox_off=? WHERE userid = ?";
        $r=$db->query($q, array('lenders', $set, $id));
        if($r===DB_OK){
            return 1;//successful insert
        }
        else{
            return 0;//unsuccessful insert
        }
    }

    function getCLBorrowers($rec_form_ofcr_no){
        global $db;
        $q="SELECT * FROM ! as b, ! as bext WHERE b.userid=bext.userid AND bext.rec_form_offcr_num=?";
        $result=$db->getAll($q, array('borrowers','borrowers_extn', $rec_form_ofcr_no));
        return $result;
    }


    function getUserIP($userid){
        global $db;
        $q= "SELECT ip_address from ! WHERE userid=?";
        $result=$db->getOne($q,array('facebook_info',$userid));
        return $result;
    }


    function getAllIPUsers($ip){
        global $db;
        $q= "SELECT * from ! WHERE ip_address=?";
        $result=$db->getAll($q,array('facebook_info',$ip));
        return $result;
    }

    // update by mohit on date 3-11-13 to prevent reschedule loan
    function LastReScheduleLimit($borrowerid, $loanid)
    {
        global $db;
        $q="SELECT count(*) from ! where loan_id = ? AND borrower_id=?";
        $count=$db->getOne($q,array('reschedule',$loanid,$borrowerid));
        return $count;
    }

    function rschdIsActive($resh_date,$loanid,$borrowerid)
    {
        global $db;
        $q="SELECT count(*) as no_of_trn from ! where TrDate>=? AND loanid = ? AND userid=?";
        $res=$db->getOne($q,array('transactions',$resh_date,$loanid,$borrowerid));
        return $res['no_of_trn'];
    }

    function tmp_getprevfeetxn($txnid) {
        global $db;
        $q="SELECT * from ! where txn_type = ? AND id < ? ORDER BY id DESC LIMIT 1 ";
        $fee=$db->getRow($q, array('transactions',FEE, $txnid));
        return $fee;
    }
	
	// beacause country name disable on edit profile page by mohit 19-12-13
	public function getCountryByBorrowerId($id){
		global $db;     
		$q="SELECT Country FROM ! WHERE userid=?";     
		$Country=$db->getOne($q,array('borrowers',$id));
		return $Country;
	}
	
	// functions used to integrate historical data with shift science
	public function getloginHistOfAllBorrower($lastProcessId=null){
        global $db;     
		if($lastProcessId!=0){
			$qry="userid>'".$lastProcessId."' AND";
		}	
		$q="SELECT username, userid, last_login FROM ! WHERE $qry userlevel=? order by userid asc";			
        $res = $db->getAll($q, array('users',1));
		return $res;
    }
	
	public function getLastProcessId($event_type){
		global $db; 
		$q= "SELECT processed_userid from ! where shift_event_type=?";		
		$processed_id=$db->getOne($q,array('temp_shiftevent',$event_type));
		return $processed_id;
	}

	public function getBorrowerAccountHist($lastProcessId=null){
		
		global $db;
		if($lastProcessId!=0){
			$qry="WHERE userid>'".$lastProcessId."'";
		}	
        $q = "select userid, FirstName, LastName, About, BizDesc, reffered_by, Created, completed_on, LastModified from ! $qry order by userid asc limit 0,500";
        $res = $db->getAll($q, array('borrowers'));
	   return $res;
	}
	
    public function getFacebookConnectHist($lastProcessId=null){
		global $db;
		if($lastProcessId!=0){
			$qry="WHERE id>'".$lastProcessId."'";
		}	
        $q = "select * from ! $qry order by id asc limit 0,500";
        $res = $db->getAll($q, array('facebook_info'));
	   return $res;
	}
	
	public function getloanReypayHist($lastProcessId=null){
		global $db;
		if($lastProcessId!=0){
			$qry="WHERE id>'".$lastProcessId."'";
		}	
        $q = "select * from ! $qry order by id asc limit 0,500";
        $res = $db->getAll($q, array('repaymentschedule_actual'));
	   return $res;
	}

	public function getloanDisbursHist($lastProcessId=null){
		global $db;
		if($lastProcessId!=0){
			$qry="AND id>'".$lastProcessId."'";
		}	
        $q = "select * from ! WHERE txn_type=? $qry order by id asc limit 0,500";
        $res = $db->getAll($q, array('transactions',DISBURSEMENT));
	   return $res;
	}	


    function inviteReport(){
        
        global $db;

        $q="SELECT DISTINCT borrowers.userid, borrowers.Country, borrowers.completed_on FROM ! LEFT JOIN invites as inv on inv.userid=borrowers.userid WHERE active = 1 AND inv.userid IS NOT NULL order by completed_on";
 
        $res= $db->getAll($q, array('borrowers'));
        
        return $res;
    }


    function getInviteesWithLoans($userid){

       global $session;

//set of all members invited by this member
        $invitees= $this->getInvitedMember($userid);
        $total_loans=0;
        foreach($invitees as $invite){

            $invite_lastloan= $this->getLastloan($invite['invitee_id']);
            
            if(!empty($invite_lastloan)){

                $loan=1;
                $total_loans+=$loan;
            }

        }

        return $total_loans;
    }


    function getSuccessfulInvitees($userid){

       global $session;

//set of all members invited by this member
        $invitees= $this->getInvitedMember($userid);
        $total_success=0;
        foreach($invitees as $invite){
//checks repayment rate of invited members
            $invite_lastloan= $this->getLastloan($invite['invitee_id']);
            
            if(!empty($invite_lastloan)){

                $inviterepayrate= $session->RepaymentRate($invite['invitee_id']);  
            }
//gets minimum on-time repayment rate needed to progress to larger loans as set by admin
            $minrepayrate=$this->getAdminSetting('MinRepayRate');

            if($inviterepayrate>=$minrepayrate){
                $success=1;
            }else{
                $success=0;
            }

            $total_success+=$success;
        }

        return $total_success;
    }


    function getInviteeRepaymentRate($userid){

        global $session;
//counts all members invited by this user who meet admin on-time repayment rate standard
        $total_success=$this->getSuccessfulInvitees($userid);

//counts total members invited by this user who have taken out loans
        $total_invitedloans=$this->getInviteesWithLoans($userid);

//calculates percentage of members invited by this user who meet repayment rate standard
        $success_rate=$total_success / $total_invitedloans;

        return $success_rate;      
    }
	
	public function loanIsAlreadyOpen($borrowerid){
		global $db;
		$q="SELECT * from ! where borrowerid = ? AND (active=? OR active=? OR active=?) AND expires is NULL AND adminDelete =?";
		$result=$db->query($q,array('loanapplic',$borrowerid, LOAN_OPEN,LOAN_FUNDED,LOAN_ACTIVE,0));
		return $result->numRows();
	} 

};
$database= new genericClass;
?>
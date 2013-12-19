<?php
include_once("library/session.php");
include_once("./editables/register.php");
$select=0;
if(isset($_GET["sel"])){
	$select=$_GET["sel"];
}
$t=0;
	if(isset($_GET["t"])){
					$t=$_GET["t"];
					}
?>
<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/utils.js"></script>
<script type="text/javascript">
	function refC() {
		document.getElementById('ci').src = 'library/captcha/captcha.php?'+Math.random();
	}
</script>
<?php if(empty($t)){?>
<b><?php echo $lang['register']['Registration'];?></b><br><br>
	<div style="width:95%; border-bottom:#669966 1px solid; ">
		<?php echo $lang['register']['user_type'];?>
		<select name="usertype" id="usertype" class="selectcmmn" onchange="window.location='index.php?p=1&sel='+(this).value">
			<option></option>
			<option value="1"<?php if($select==1)echo "Selected='true'"; ?>><?php echo $lang['register']['Borrower'];?></option>
			<option value="2"<?php if($select==2)echo "Selected='true'"; ?>><?php echo $lang['register']['Lender'];?></option>
			<option value="3"<?php if($select==3)echo "Selected='true'"; ?>><?php echo $lang['register']['Partner'];?></option>
		</select>
		<br />
		<?php echo $lang['register']['user_type_dis'];?>
		<ul>
			<li><?php echo $lang['register']['Borrower'];?></li>
			<?php echo $lang['register']['borrower_dis'];?>
			<li><?php echo $lang['register']['Lender'];?></li>
			<?php echo $lang['register']['Lender_dis'];?>
			<li><?php echo $lang['register']['Partner'];?></li>
			<?php echo $lang['register']['Partner_dis'];?>
		</ul>
		<br />
	</div>
	<?php } ?>
	<?php
		if($select==0){
	?>
	<div id="typenotify"><br /><br />
		<?echo $lang['register']['select_type'];?>
	</div>
	<?php
		}
		else if($select==1){
	?>
	<div id="typeselect1">
		<form enctype="multipart/form-data" id="sub-borrower" name="sub-borrower" method="post" action="process.php">
			<div>
				<table>
					<tr>
						<td colspan='3'><b><?php echo $lang['register']['Borrower'];?>&nbsp;<?php echo $lang['register']['Registration'];?></b></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'><p>Zidisha is a nonprofit microfinance service that allows individual American lenders to finance small business loans over the Internet.  Zidisha loans are available to a select group of Kenyan entrepreneurs who have established successful credit histories with local lending institutions.</p>  

<p>Zidisha loans do not require collateral guarantees, and lenders may choose to finance loan applications at or below the interest rate proposed by the applicant.  Interest payments are divided between Zidisha.org and the lenders who finance each loan.  Borrowers pay no fees other than the proposed interest rate.  Loan disbursements and repayments are conducted via M-PESA.</p>  

<p>Each borrower's profile information and repayment history is recorded on the Zidisha.org website.  (Please note that any defaults are also reported to relevant credit bureaus and lending institutions in Kenya, and legal suits filed against defaulting borrowers.)  Borrowers who establish successful repayment histories are expected to be able to attract progressively larger loans at lower interest rates from Zidisha lenders.</p>  

<p>Please contact Julia Blue at julia@zidisha.org for additional information.</p>  
</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td width="40%"><label for="busername"><?php echo $lang['register']['username'];?></label></td>
						<td  width="40%"><input type="text" id="busername" name="busername" maxlength="20" class="inputcmmn-1" value="<?php echo $form->value("busername"); ?>" /></td>
						<td  width="20%"><div id="bunerror"><?php echo $form->error("busername"); ?></div></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td><label for="bpass1"><?php echo $lang['register']['ppassword'];?></label></td>
						<td><input type="password" id="bpass1" name="bpass1" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bpass1"); ?>" /></td>
						<td><div id="passerror"><?php echo $form->error("bpass1"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bpass2"><?php echo $lang['register']['CPassword'];?></label></td>
						<td><input type="password" id="bpass2" name="bpass2" maxlength="15" class="inputcmmn-1" /></td>
						<td><div id="compareerror"></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td><label for="bfname"><?php echo $lang['register']['fname'];?></label></td>
						<td><input type="text" name="bfname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("bfname"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("bfname"); ?></div></td>
					</tr>
					<tr>
						<td><label for="blname"><?php echo $lang['register']['lname'];?></label></td>
						<td><input type="text" name="blname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("blname"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("blname"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bphoto"><?php echo $lang['register']['Photo'];?></label></td>
						<td><input type="file" name="bphoto" id="bphoto"   value="<?php echo $form->value("bphoto"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("bphoto"); ?></div></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td><label for="bpostadd"><?php echo $lang['register']['paddress'];?></label></td>
						<td><input type="text" name="bpostadd" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("bpostadd"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("bpostadd"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bcity"><?php echo $lang['register']['City'];?></label></td>
						<td><input type="text" name="bcity" maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bcity"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("bcity"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bcountry"><?php echo $lang['register']['Country']?></label></td>
						<td><input type="text" name="bcountry" maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bcountry"); ?>" /></td>
						<td><div id="error"><?php echo $form->error("bcountry"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bemail"><?php echo $lang['register']['email'];?></label></td>
						<td><input type="text" id="bemail" name="bemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("bemail"); ?>" /></td>
						<td><div id="emailerror"><?php echo $form->error("bemail"); ?></div></td>
					</tr>
					<tr>
						<td><label for="bmobile"><?php echo $lang['register']['tel_mob_no'];?></label></td>
						<td><input type="text" id="bmobile" name="bmobile" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bmobile"); ?>" /></td>
						<td><div id="mobileerror"><?php echo $form->error("bmobile"); ?></div></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td><label for="bincome"><?php echo $lang['register']['aincome'];?></label></td>
						<td><input type="text" id="pamount" name="bincome" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bincome"); ?>" /></td>
						<td><div id="pamounterr1"><?php echo $form->error("bincome"); ?></div></td>
					</tr>
					<tr>
						<td><label for="currency"><?php echo $lang['register']['bcurrency'];?></label></td>
						<td>
							<select name="currency" id="currency" class="selectcmmn">
								<?php 
									$currency=$database->getAllCurrency();
									$currencysel=0;
									$tempcurrency=$form->value("currency");
									if(!empty($tempcurrency))$currencysel=$tempcurrency;
									if(!empty($currency)){
										foreach($currency as $currencyrow){?>
										<option value="<?php echo $currencyrow['id']  ; ?>"<?php if($currencysel==$currencyrow['id'])echo "Selected='true'"; ?>><?php echo $currencyrow['currencyname'];?></option>	
									
								<?php 	
									}}
								?>
								
							</select>
						
						
						</td>
						<td><div id="currencyerr1"><?php echo $form->error("currency"); ?></div></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>	
					<tr>
						<td style="vertical-align:text-top"><label for="babout"><?php echo $lang['register']['A_Yourself'];?></label></td>
						<td><textarea name="babout" class="textareacmmn" > <?php echo $form->value("babout"); ?></textarea></td>
						<td><div id="error"><?php echo $form->error("babout"); ?></div></td>
					</tr>
					
					<tr><td colspan='3'>&nbsp;</td></tr>	
					<tr>
						<td style="vertical-align:text-top"><label for="bbizdesc"><?php echo $lang['register']['bdescription'];?></label></td>
						<td><textarea name="bbizdesc" class="textareacmmn" > <?php echo $form->value("bbizdesc"); ?></textarea></td>
						<td><div id="error"><?php echo $form->error("bbizdesc"); ?></div></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr>
						<td style="vertical-align:text-top"><img alt="animated captcha" id="ci" onclick="refC()" src="./library/captcha/captcha.php?i=<?php echo(md5(microtime()));?>" style="cursor:pointer" /></td>
						<td><?php echo $lang['register']['capacha'];?><input type="text" name="user_guess" autocomplete="off" class="inputcmmn-1"/><br/></td>
						<td><div id="user_guess_error"><?php echo $form->error("user_guess"); ?></div></td>
					</tr>
				</table>
				<table>
				<tr><td><b><?php echo $lang['register']['t_c'];?></b></td></tr>
				<tr>
				<td colspan=2 align="center" style="vertical-align:text-top"><div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);"><?php include_once("editables/legalagreement.html");?></div>
				</td>
						<td><div id="error"></div></td>
					</tr>
					<tr>
					<td colspan="2" align="left"><b><?php echo $lang['register']['a_a'];?></b> <INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;  
      <INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" checked /><?php echo $lang['register']['no'];?></td>
				</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="hidden" name="reg-borrower" />
							<input type="hidden" name="tnc" id="tnc" value=0 />
							<input type="reset" value="Reset" />
							<input type="submit" value="<?php echo $lang['register']['Register'];?>" onclick="return verifyTnC()" />
						</td>
						<td></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
	<?php
		}
		else if($select==2){
	?>
	<div id="typeselect2">
		<form enctype="multipart/form-data" method="post" id="sub-lender" name="sub-lender" action="process.php">
			<table>
				<tr>
					<td><b><?php echo $lang['register']['Lender'];?>&nbsp;<?php echo $lang['register']['Registration'];?></b></td>
				</tr>
				<tr>
					<td><label for="lusername"><?php echo $lang['register']['username'];?></label></td>
					<td><input type="text" id="busername" name="lusername" maxlength="20" class="inputcmmn-1" value="<?php echo $form->value("lusername"); ?>" /></td>
					<td><div id="bunerror"><?php echo $form->error("lusername"); ?></div></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><label for="bpass1"><?php echo$lang['register']['ppassword'];?></label></td>
					<td><input type="password" id="bpass1" name="lpass1" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("lpass1"); ?>" /></td>
					<td><div id="passerror"><?php echo $form->error("lpass1"); ?></div></td>
				</tr>
				<tr>
					<td><label for="bpass2"><?php echo$lang['register']['CPassword'];?></label></td>
					<td><input type="password" id="bpass2" name="lpass2" maxlength="15" class="inputcmmn-1" /></td>
					<td><div id="compareerror"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><label for="bfname"><?php echo$lang['register']['fname'];?></label></td>
					<td><input type="text" name="lfname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lfname"); ?>" /></td>
					<td><div id="error"><?php echo $form->error("lfname"); ?></div></td>
				</tr>
				<tr>
					<td><label for="blname"><?php echo $lang['register']['lname'];?></label></td>
					<td><input type="text" name="llname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("llname"); ?>" /></td>
					<td><div id="error"><?php echo $form->error("llname"); ?></div></td>
				</tr>
				<tr>
					<td><label for="bphoto"><?php echo $lang['register']['Photo'];?></label></td>
					<td><input type="file" name="lphoto"  value="<?php echo $form->value("lphoto"); ?>" /></td>
					<td><div id="error"><?php echo $form->error("lphoto"); ?></div></td>
				</tr>
				<tr>
					<td><label for="bemail"><?php echo $lang['register']['email'];?></label></td>
					<td><input type="text" id="bemail" name="lemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("lemail"); ?>" /></td>
					<td><div id="emailerror"><?php echo $form->error("lemail"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['d_total_amt'];?></td>
						<td> <INPUT TYPE="Radio" id="hide_Amount" name="hide_Amount" value="0" tabindex="3" /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;  
						<INPUT TYPE="Radio" name="hide_Amount" value="1" tabindex="4" checked /><?php echo $lang['register']['no'];?>
					</td>
					<td><div></div></td>
				</tr>
				<tr>
					<td style="vertical-align:top"><label for="labout"><?php echo $lang['register']['A_Yourself'];?></label></td>
					<td><textarea class="textareacmmn" name="labout" id="labout" ><?php echo $form->value("labout"); ?></textarea></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
					<tr>
						<td style="vertical-align:text-top"><img alt="animated captcha" id="ci" onclick="refC()" src="./library/captcha/captcha.php?i=<?php echo(md5(microtime()));?>" style="cursor:pointer" /></td>
						<td><?php echo $lang['register']['capacha'];?><input type="text" name="user_guess" autocomplete="off" class="inputcmmn-1"/><br/></td>
						<td><div id="user_guess_error"><?php echo $form->error("user_guess"); ?></div></td>
					</tr>
				</table>
				<table>
				<tr><td><b><?php echo $lang['register']['t_c'];?></b></td></tr>
				<tr>
				<td colspan=2 align="center" style="vertical-align:text-top"><div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);"><?php include_once("editables/lenderagreement.html");?></div>
				</td>
						
				<td><div id="tncerror"></div></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><b><?php echo $lang['register']['a_a'];?></b><INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;  
      <INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" checked /><?php echo $lang['register']['no'];?></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="hidden" name="reg-lender" />
						<input type="hidden" name="tnc"  id="tnc" value=0 />
						<input type="reset" />
						<input type="submit" value="<?php echo $lang['register']['Register'];?>" onclick="return verifyTnC()"  />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php
		}
		else if($select==3){
	?>
	<div id="typeselect3">
		<form enctype="multipart/form-data" method="post" action="process.php">
			<table>
				<tr>
					<td><b><?php echo $lang['register']['Partner'];?>&nbsp;<?php echo $lang['register']['Registration'];?></b></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><label for="pusername"><?php echo $lang['register']['username'] ;?></label></td>
					<td><input type="text" maxlength="20" id="busername" name="pusername" class="inputcmmn-1" value="<?php echo $form->value("pusername"); ?>" /></td>
					<td><div id="bunerror"><?php echo $form->error("pusername"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><label for="ppass1"><?php echo $lang['register']['Password'];?></label></td>
					<td><input type="password" maxlength="20" id="bpass1" name="ppass1" class="inputcmmn-1" value="<?php  echo $form->value("ppass1"); ?>" /></td>
					<td><div id="passerror"><?php echo $form->error("ppass1"); ?></div></td>
				</tr>
				<tr>
					<td><label for="ppass2"><?php echo $lang['register']['CPassword'];?></label></td>
					<td><input type="password" maxlength="20" id="bpass2" name="ppass2" class="inputcmmn-1" value="<?php  echo $form->value("ppass2"); ?>" /></td>
					<td><div id="compareerror"></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><label for="pname"><?php echo $lang['register']['pname'];?></label></td>
					<td><input type="text" maxlength="50" id="pname" name="pname" class="inputcmmn-1" value="<?php  echo $form->value("pname"); ?>" /></td>
					<td><div id="pnameerror"><?php echo $form->error("pname"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><label for="paddress"><?php echo $lang['register']['paddress'];?></label></td>
					<td><input type="text" maxlength="100" id="paddress" name="paddress" class="inputcmmn-1" value="<?php echo $form->value("paddress") ?>" /></td>
					<td><div id="paddresserror"><?php echo $form->error("paddress"); ?></div></td>
				</tr>
				<tr>
					<td><label for="pcity"><?php echo $lang['register']['City'];?></label></td>
					<td><input type="text" maxlength="50" id="pcity" name="pcity" class="inputcmmn-1" value="<?php echo $form->value("pcity"); ?>" /></td>
					<td><div id="pcityerror"><?php echo $form->error("pcity"); ?></div></td>
				</tr>
				<tr>
					<td><label for="pcity"><?php echo $lang['register']['Country'];?></label></td>
					<td><input type="text" maxlength="50" id="pcountry" name="pcountry" class="inputcmmn-1" value="<?php echo $form->value("pcountry"); ?>" /></td>
					<td><div id="pcityerror"><?php echo $form->error("pcountry"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
						<td><label for="pemail"><?php echo $lang['register']['email'];?></label></td>
						<td><input type="text" id="bemail" name="pemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("pemail"); ?>" /></td>
						<td><div id="emailerror"><?php echo $form->error("pemail"); ?></div></td>
					</tr>
				<tr>
					<td><label for="pwebsite"><?php echo $lang['register']['Website'];?></label></td>
					<td><input type="text" maxlength="100" id="pwebsite" name="pwebsite" class="inputcmmn-1" value="<?php echo $form->value("pwebsite"); ?>" /></td>
					<td><div id="pweberror"><?php echo $form->error("pwebsite"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="3"><label for="pdesc"><?php echo $lang['register']['d_org'];?></label></td>
				</tr>
				<tr>
					<td colspan="2"><textarea style="width:100%; height:150px" name="pdesc" ><?php echo $form->value("pdesc"); ?></textarea></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="vertical-align:text-top"><img alt="animated captcha" id="ci" onclick="refC()" src="./library/captcha/captcha.php?i=<?php echo(md5(microtime()));?>" style="cursor:pointer" /></td>
					<td><?php echo $lang['register']['capacha'];?><input type="text" name="user_guess" autocomplete="off" class="inputcmmn-1"/><br/></td>
					<td><div id="user_guess_error"><?php echo $form->error("user_guess"); ?></div></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="hidden" name="reg-partner" />
						<input type="reset" />
						<input type="submit" value="<?php echo $lang['register']['Register'];?>" />
					</td>
					<td></td>
				</tr>
			</table>
		</form>
	</div>
	<?php
		}else if($select==4){
			if($t==1){//borrower
	?>
	<div id="typeselect3" style="width:95%">
		<p><b><?php echo $lang['register']['t_u'];?></b></p>
		<br />
		<?php echo $lang['register']['member_of']; ?><br /><br />
		<?php echo $lang['register']['reg_as_a'];?><b><?php echo $lang['register']['Borrower'];?></b>,<?php echo $lang['register']['active_info'];?> <?php echo $lang['register']['active_info_p'];?><br />
		<?php echo $lang['register']['confirming'];?>
		<br /><?php echo $lang['register']['find_partner'];?><br /><br />
		<?php echo $lang['register']['for']; echo $lang['register']['Partner'];?>, <?php echo $lang['register']['activate_status'];?>
		<br /><br />
		<?php echo $lang['register']['t_u'];?>,<br />
		<?php echo $lang['register']['wadmin'];?>
		
	</div>
	<?php }else if($t==2){//lender	?>
	<div id="typeselect3" style="width:95%">
		<p><b><?php echo $lang['register']['t_u'];?></b></p>
		<br />
		<?php echo $lang['register']['member_of']; ?><br /><br />
		<?php echo $lang['register']['reg_as_a'];?> <b><?php echo $lang['register']['Lender'];?></b>,<?php echo$lang['register']['active_info'];?> <br />
		<?php echo $lang['register']['for_lend'];?>
		<br /><?php echo $lang['register']['bidfor_lend'];?> <br /><br />
			
		<br /><br />
		<?php echo $lang['register']['t_u'];?>,<br />
		<?php echo $lang['register']['wadmin'];?>
		
	</div>
	<?php }else if($t==3){//partner
	?>
	<div id="typeselect3" style="width:95%">
		<p><b><?php echo $lang['register']['t_u'];?></b></p>
		<br />
		<?php echo $lang['register']['member_of']; ?><br /><br />
		<?php echo $lang['register']['reg_as_a'];?> <b><?php echo $lang['register']['Partner'];?></b>,<?php echo $lang['register']['active_info']; echo $lang['register']['active_info_a'];?><br />
		<?php echo $lang['register']['confirming'];?>
		<br /><?php echo $lang['register']['bidfor_partner'];?><br /><br />
		<?php echo $lang['register']['for']; echo $lang['register']['Borrower'];?>, <?php echo $lang['register']['activate_status'];?>
		
		<br /><br />
		<?php echo $lang['register']['t_u'];?>,<br />
		<?php echo $lang['register']['wadmin'];?>
		
	</div>
	<?php }else { //error ?>
		<div id="typeselect3" style="width:95%">
		<p><b><?php echo $lang['register']['sorry'];?></b></p>
		<br />
		
		<br /><br /><br />
		<?php echo $lang['register']['error'];?>
		<br /><br />
		<?php echo $lang['register']['t_u'];?>,<br />
		<?php echo $lang['register']['wadmin'];?>
		
	</div>
	<?php }}	?>
<!-- data end -->
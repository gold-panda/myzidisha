<?php
include_once("library/session.php");
include_once("error.php");
$part=0;//sets the default part for the login to either login table or profile links
			//chnge to check for if(logged_in)
	if($session->logged_in){
		$part=1;
	}
	if($part==0){
		$select=0;
		if(isset($_GET["sel"])){
			$select=$_GET["sel"];
		}
		if($select==0){
			if(isset($_GET['err']) && $_GET['err'] >0){
								echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
			}
?>

			<form method="post" action="updateprocess.php">
			<table>
				<tr>
					<td><b>Forgot Password</b></td>
				</tr>
				<tr>
					<td>Username</td>
				</tr>
				<tr>
					<td><input  name="forgetusername" type="text"  value="<?php echo $form->value("forgetusername"); ?>" /></td>
				</tr>
				<tr>
					<td>
						<div style="font-size:9px">
							<?php
								echo $form->error("forgetusername");
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td>registered email id</td>
				</tr>
				<tr>
					<td><input  name="forgetemail" type="text"  value="<?php echo $form->value("forgetemail"); ?>" /></td>
				</tr>
				<tr>
					<td>
						<div style="font-size:9px">
							<?php
								echo $form->error("forgetemail");
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td><input type="hidden" name="forgetpassword" /><input type="submit" value="My Password" /></td>
				</tr>
			</table>		
</form>
<?php
}else if($select==1){
		echo"ur new  password is sended to ur email registered with zidisha";
}
}else if($part==1){

		echo"Sorry ur allready loged in please use <a href='index.php?p=13'>Edit profile</a>";

}
?>
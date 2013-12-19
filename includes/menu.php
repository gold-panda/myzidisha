<script type="text/javascript" >
$(document).ready(function(){	
	$("td#mbutton").mouseover(function(){
		$(this).css({'color':'red', 'font-weight':'bolder'});
	});
	$("td#mbutton").mouseout(function(){
		$(this).css({'color':'#fffffc', 'font-weight':'normal'});
	});
    $("td#mbutton1").mouseover(function(){
		$(this).css({'color':'red', 'font-weight':'bolder'});
	});
    $("td#mbutton1").mouseout(function(){
		$(this).css({'color':'#fffffc', 'font-weight':'normal'});
	});
    $("td#mbutton2").mouseover(function(){
		$(this).css({'color':'red', 'font-weight':'bolder'});
	});
	$("td#mbutton2").mouseout(function(){
		$(this).css({'color':'#fffffc', 'font-weight':'normal'});
	});
	$("td#mbutton3").mouseover(function(){
		$(this).css({'color':'red', 'font-weight':'bolder'});
	});
	$("td#mbutton3").mouseout(function(){
		$(this).css({'color':'#fffffc', 'font-weight':'normal'});
	});
	$("td#mbutton4").mouseover(function(){
		$(this).css({'color':'red', 'font-weight':'bolder'});
	});
	$("td#mbutton4").mouseout(function(){
		$(this).css({'color':'#fffffc', 'font-weight':'normal'});
	});
})
</script>

<table>	
	<tr>
<?php
		if($_GET['p']==2){
			echo "<td class='menu-button' ><a href='microfinance/lend.html' style='text-decoration:none;color:black'><b>".$lang['menu']['Lend']."</b></a></td>";
		}
		else{
			echo "<td id='mbutton' class='menu-button' ><a href='microfinance/lend.html' style='text-decoration:none;color:black'>".$lang['menu']['Lend']."</a></td>";
		}
		if($_GET['p']==3){
			echo "<td class='menu-button' ><a href='microfinance/how-it-works.html' style='text-decoration:none;color:black'><b>".$lang['menu']['h_it_w']."</b></a></td>";
		}
		else{
			echo "<td id='mbutton1' class='menu-button' ><a href='microfinance/how-it-works.html' style='text-decoration:none;color:black'>".$lang['menu']['h_it_w']."</a></td>";
		}
		if($_GET['p']==4){
			echo "<td class='menu-button' ><a href='index.php?p=4' style='text-decoration:none;color:black'><b>".$lang['menu']['FAQ']."</b></a></td>";
		}
		else{
			echo "<td id='mbutton2' class='menu-button' ><a href='index.php?p=4' style='text-decoration:none;color:black'>".$lang['menu']['FAQ']."</a></td>";
		}
		if($_GET['p']==62){
			echo "<td class='menu-button' ><a href='microfinance/team.html' style='text-decoration:none;color:black'><b>".$lang['menu']['abt_zidisha']."</b></a></td>";
		}
		else{
			echo "<td id='mbutton3' class='menu-button' ><a href='microfinance/team.html' style='text-decoration:none;color:black'>".$lang['menu']['abt_zidisha']."</a></td>";
		}
?>
		<td id='mbutton4' class='menu-button' ><a href='http://www.zidisha.org/zidisha/beehiveforum091/beehiveforum091/forum/index.php' style='text-decoration:none;color:black'><?php echo $lang['menu']['user_forum'];?></td>
	</tr>
</table>
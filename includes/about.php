<?php
include_once("./editables/about.php");
$path=	getEditablePath('about.php');
include_once("./editables/".$path);
?>

<div class="span12">
	<div id="static" style="text-align:justify;">
	<?php	
		$Community_organizers = $database->getco_Organizers_Country('AA');
		$Co_section='';
		$count=0;
		if(count($Community_organizers) > 0) {
			foreach ($Community_organizers as $key=>$Organizers) {
				
				if(count($Organizers['co']) > 0) {
					foreach($Organizers['co'] as $key1=> $co_org) {
						$userdetail = $database->getUserById($co_org['user_id']);
						$Community_organizers[$key]['co'][$key1]['name']=$userdetail['name'];
						$Community_organizers[$key]['co'][$key1]['lname']=$userdetail['lastname'];
					}
				}
			}
		}
	foreach ($Community_organizers as $key=>$Organizers) { 
		$table_class='table_sorter_co'.$key;
		?>
		<script type="text/javascript">
<!--
			$(function() {		
			$(".<?php echo $table_class?>").tablesorter({sortList:[[0,0]], widgets: ['zebra'] });
		});	

//-->
</script>
	<?php }
		if(count($Community_organizers) > 0) {
			$Co_section='';
			foreach ($Community_organizers as $key=>$Organizers) {
				$table_class='table_sorter_co'.$key;
				$Co_section.="<table style='margin-top: -14px;margin-bottom:0px;' class='detail $table_class'>";
				$Co_section.="<thead><th style='padding:1px;border-bottom:none;border-bottom-width:0px;'><h4>".$Organizers['name']."</h4></th></thead><tbody>";
				if(count($Organizers['co']) > 0) {
					foreach($Organizers['co'] as $key1=> $co_org) {
						$Co_section.="<tr>";
						$co_lname = $co_org['lname'];
							if(empty($co_lname)){
								$co_lname = end(explode(" ", $co_org['name']));
							}
							$Co_section.="<td><span style='display:none'>$co_lname</span>";
								$userdetail = $database->getUserById($co_org['user_id']);
								if($userdetail['userlevel']==BORROWER_LEVEL){
									$loanid= $database->getCurrentLoanid($co_org['user_id']); 
									if(!empty($loanid)){
										$url=getLoanprofileUrl($co_org['user_id'], $loanid);
									}else{
										$url = getUserProfileUrl($co_org['user_id']);
									}
								}else{
									$url = getUserProfileUrl($co_org['user_id']);
								}
								$Co_section.="<a href=$url target='_blank'> ".$co_org['name']."</a>";
							$Co_section.="</td>";
						$Co_section.="</tr>";
					}
				}
				$Co_section.="</tbody></table>";
			}
		}
		
		$params['co_organizers_by_cntry'] = $Co_section;
		$message = $session->formMessage($lang['about']['desc'], $params);
		echo $message;
	?>	
	</div>
</div>

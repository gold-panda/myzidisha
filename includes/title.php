<?php switch($page) {
		case 65 :
			echo "<title>Zidisha | Press</title>";
		case 64 :
				echo "<title>Zidisha | Newsletter</title>";
		case 26 :
			echo "<title>Zidisha | Gift Cards</title>";
		case 67:
			echo "<title>Zidisha | Intern / Volunteer</title>";
		case 38:
			echo "<title>Zidisha | Donate</title>";
		case 6 :
			echo "<title>Zidisha | Contact Us</title>";
		case 47 :
			echo "<title>Zidisha | Borrow</title>";
		case 48 :
			echo "<title>Zidisha | Why Support Entrepreneurs Through Peer-to-Peer Microfinance Lending?</title>";
		case 3 :
			echo "<title>Zidisha | How It Works</title>";
		case 4 :
			echo "<title>Zidisha | Frequently Asked Questions</title>";
		case 62 :
			echo "<title>Zidisha | Meet the Team</title>";
		case 69 :
			echo "<title>Zidisha | Comments</title>";
		case 14 :
				if(isset($_GET['u'])) {
					$brname = $database->getNameById($_GET['u']);
					echo "<title>Zidisha | $brname</title>";
				} else
				echo "<title>Zidisha | Join the global P2P microlending movement</title>";
		case 2 :
			if(isset($_GET['t']) && $_GET['t'] == 2) {
				echo "<title>Zidisha | Active Loans</title>";
			}else if(isset($_GET['t']) && $_GET['t'] == 3) {
				echo "<title>Zidisha | Completed Loans</title>";
			}else
			echo "<title>Zidisha | Lend</title>";
		case 79 :
			echo "<title>Zidisha | About Microfinance</title>";	
		default :
			if(isset($_GET['webtag']) && $_GET['webtag']=='FORUM') {
				echo "<title>Zidisha | User Forum</title>";
			}else 
			echo "<title>Zidisha: Join the global P2P microlending movement</title>";

	}
		
?>
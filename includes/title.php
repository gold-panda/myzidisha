<?php switch($page) {
		case 65 :
			echo "<title>Zidisha Microfinance | Press</title>";
		case 64 :
				echo "<title>Zidisha Microfinance | Newsletter</title>";
		case 26 :
			echo "<title>Zidisha Microfinance | Gift Cards</title>";
		case 67:
			echo "<title>Zidisha Microfinance | Intern / Volunteer</title>";
		case 38:
			echo "<title>Zidisha Microfinance | Donate</title>";
		case 6 :
			echo "<title>Zidisha Microfinance | Contact Us</title>";
		case 47 :
			echo "<title>Zidisha Microfinance | Borrow</title>";
		case 48 :
			echo "<title>Zidisha Microfinance | Why Support Entrepreneurs Through Peer-to-Peer Microfinance Lending?</title>";
		case 3 :
			echo "<title>Zidisha Microfinance | How It Works</title>";
		case 4 :
			echo "<title>Zidisha Microfinance | Frequently Asked Questions</title>";
		case 62 :
			echo "<title>Zidisha Microfinance | Meet the Team</title>";
		case 69 :
			echo "<title>Zidisha Microfinance | Testimonials</title>";
		case 14 :
				if(isset($_GET['u'])) {
					$brname = $database->getNameById($_GET['u']);
					echo "<title>Zidisha Microfinance | $brname</title>";
				} else
				echo "<title>Zidisha Microfinance</title>";
		case 2 :
			if(isset($_GET['t']) && $_GET['t'] == 2) {
				echo "<title>Zidisha Microfinance | Active Loans</title>";
			}else if(isset($_GET['t']) && $_GET['t'] == 3) {
				echo "<title>Zidisha Microfinance | Completed Loans</title>";
			}else
			echo "<title>Zidisha Microfinance | Lend</title>";
		case 79 :
			echo "<title>Zidisha Microfinance | About Microfinance</title>";	
		default :
			if(isset($_GET['webtag']) && $_GET['webtag']=='FORUM') {
				echo "<title>Zidisha Microfinance | User Forum</title>";
			}else 
			echo "<title>Zidisha: Peer-to-Peer Microfinance Lending</title>";

	}
		
?>
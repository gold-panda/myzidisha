<?php
	$fontsize=12;
	if(isset($_GET['size']))
		$fontsize = $_GET['size']

?>
<style type="text/css">
	p{font-size:<?php echo $fontsize?>px;}
</style>
Fonts.com supports following fonts for us<br/>
Current Font Size: <?php echo $fontsize?>px
<form action="index.php" method="get">
Enter Font size: &nbsp;&nbsp;<input type="hidden" name="p" value="100"><input type="text" name="size" style="width:50px">px &nbsp;&nbsp;<input type="submit" class="btn" value="Submit" >
</form>
<br/><br/><br/>
<table>
	<tr>
		<th>Normal</th>
		<th>Bold</th>
		<th>Italic</th>
	</tr>
	<tr>
		<td>
			Font name-Trade Gothic W01 Bold
			<p style="font-family:'Trade Gothic W01 Bold'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675334
			<p style="font-family:'TradeGothicW01-BoldCn20 675334'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675337
			<p style="font-family:'TradeGothicW01-BoldCn20 675337'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Bold 2
			<p style="font-family:'Trade Gothic W01 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Bold2Obl
			<p style="font-family:'TradeGothicW01-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldObl
			<p style="font-family:'TradeGothicW01-BoldObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Cn 18
			<p style="font-family:'Trade Gothic W01 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Cn18Obl
			<p style="font-family:'TradeGothicW01-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Light
			<p style="font-family:'Trade Gothic W01 Light'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-LightObl
			<p style="font-family:'TradeGothicW01-LightObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Obl
			<p style="font-family:'Trade Gothic W01 Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Roman
			<p style="font-family:'Trade Gothic W01 Roman'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675505
			<p style="font-family:'TradeGothicW02-BoldCn20 675505'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675508
			<p style="font-family:'TradeGothicW02-BoldCn20 675508'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Bold 2
			<p style="font-family:'Trade Gothic W02 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Bold2Obl
			<p style="font-family:'TradeGothicW02-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Cn 18
			<p style="font-family:'Trade Gothic W02 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Cn18Obl
			<p style="font-family:'TradeGothicW02-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicNextW01-BdCm
			<p style="font-family:'TradeGothicNextW01-BdCm'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Extended
			<p style="font-family:'TradeGothicW01-Extended'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldExte
			<p style="font-family:'TradeGothicW01-BoldExte'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
		</td>
		<td>
			Font name-Trade Gothic W01 Bold
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Bold'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675334
			<p style="font-weight:bold;font-family:'TradeGothicW01-BoldCn20 675334'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675337
			<p style="font-weight:bold;font-family:'TradeGothicW01-BoldCn20 675337'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Bold 2
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Bold2Obl
			<p style="font-weight:bold;font-family:'TradeGothicW01-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldObl
			<p style="font-weight:bold;font-family:'TradeGothicW01-BoldObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Cn 18
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Cn18Obl
			<p style="font-weight:bold;font-family:'TradeGothicW01-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Light
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Light'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-LightObl
			<p style="font-weight:bold;font-family:'TradeGothicW01-LightObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Obl
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Roman
			<p style="font-weight:bold;font-family:'Trade Gothic W01 Roman'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675505
			<p style="font-weight:bold;font-family:'TradeGothicW02-BoldCn20 675505'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675508
			<p style="font-weight:bold;font-family:'TradeGothicW02-BoldCn20 675508'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Bold 2
			<p style="font-weight:bold;font-family:'Trade Gothic W02 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Bold2Obl
			<p style="font-weight:bold;font-family:'TradeGothicW02-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Cn 18
			<p style="font-weight:bold;font-family:'Trade Gothic W02 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Cn18Obl
			<p style="font-weight:bold;font-family:'TradeGothicW02-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicNextW01-BdCm
			<p style="font-weight:bold;font-family:'TradeGothicNextW01-BdCm'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Extended
			<p style="font-weight:bold;font-family:'TradeGothicW01-Extended'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldExte
			<p style="font-weight:bold;font-family:'TradeGothicW01-BoldExte'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
		</td>
		<td>
			Font name-Trade Gothic W01 Bold
			<p style="font-style:italic;font-family:'Trade Gothic W01 Bold'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675334
			<p style="font-style:italic;font-family:'TradeGothicW01-BoldCn20 675334'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldCn20 675337
			<p style="font-style:italic;font-family:'TradeGothicW01-BoldCn20 675337'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Bold 2
			<p style="font-style:italic;font-family:'Trade Gothic W01 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Bold2Obl
			<p style="font-style:italic;font-family:'TradeGothicW01-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldObl
			<p style="font-style:italic;font-family:'TradeGothicW01-BoldObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Cn 18
			<p style="font-style:italic;font-family:'Trade Gothic W01 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Cn18Obl
			<p style="font-style:italic;font-family:'TradeGothicW01-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Light
			<p style="font-style:italic;font-family:'Trade Gothic W01 Light'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-LightObl
			<p style="font-style:italic;font-family:'TradeGothicW01-LightObl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Obl
			<p style="font-style:italic;font-family:'Trade Gothic W01 Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W01 Roman
			<p style="font-style:italic;font-family:'Trade Gothic W01 Roman'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675505
			<p style="font-style:italic;font-family:'TradeGothicW02-BoldCn20 675505'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-BoldCn20 675508
			<p style="font-style:italic;font-family:'TradeGothicW02-BoldCn20 675508'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Bold 2
			<p style="font-style:italic;font-family:'Trade Gothic W02 Bold 2'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Bold2Obl
			<p style="font-style:italic;font-family:'TradeGothicW02-Bold2Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-Trade Gothic W02 Cn 18
			<p style="font-style:italic;font-family:'Trade Gothic W02 Cn 18'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW02-Cn18Obl
			<p style="font-style:italic;font-family:'TradeGothicW02-Cn18Obl'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicNextW01-BdCm
			<p style="font-style:italic;font-family:'TradeGothicNextW01-BdCm'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-Extended
			<p style="font-style:italic;font-family:'TradeGothicW01-Extended'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
			Font name-TradeGothicW01-BoldExte
			<p style="font-style:italic;font-family:'TradeGothicW01-BoldExte'">The quick brown fox jumps over the lazy dog.</p>
			<br/><br/>
		</td>
	</tr>
</table>

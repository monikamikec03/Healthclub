<?php
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/putanja.php");
	require("navigacija.php");
}
else{
	 echo "<script> window.location.replace('prijava.php');</script>";
}
	
   setlocale(LC_ALL, 'hr_HR.utf-8');
	
$mjesec = $godina = "";

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
if(($_SERVER["REQUEST_METHOD"] == "POST") and (!empty($_POST['mjesec'])) and (!empty($_POST['godina']))){
	$mjesec = test_input($_POST['mjesec']);
	$godina = test_input($_POST['godina']);
}
else
{
	$godina = date('Y');
	$mjesec = date('m');
}

$dan_prvi = mktime(0,0,0, $mjesec, 1, $godina);
$dana_u_mjesecu = date("t", $dan_prvi);
$prvi_u_tjednu = getdate($dan_prvi);
$dan_u_tjednu = $prvi_u_tjednu['wday'];
if($dan_u_tjednu ==0){
	$dan_u_tjednu = 7;
	# ako je nedjelja 0 pretvaramo nedjelju u zadnji dan u tjednu
}
else{
	$dan_u_tjednu = $dan_u_tjednu;
}
?>
<div class="container-fluid">
	<form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="float:right;margin-right:10%;">
		<select name="mjesec">
			<option value="">- mjesec -</option>
			<?php
			for($mjesec1 = 1; $mjesec1 <= 12; $mjesec1++){ 
			?>
			<option value="<?php echo $mjesec1; ?>" <?php if($mjesec1 == $mjesec){ echo "
			selected";} ?>><?php echo $mjesec1 ?></option>
			<?php }?>
		</select>
		<select name="godina">
			<option value="">- godina -</option>
			<?php
			foreach( range(1900, 2050) as $godina1 ){
			echo '<option value="'.$godina1.'" '; if ($godina1 == $godina){echo "
			selected"; } echo ">$godina1</option>";
			}
			?>
		</select>
		<input style='padding:3px 30px;' type='submit' name="spremi" value='spremi'>
	</form>
	
	<table>
		<tr align='center'class='bg_blue'>
			<td align='center'>P</td>
			<td align='center'>U</td>
			<td align='center'>S</td>
			<td align='center'>Č</td>
			<td align='center'>P</td>
			<td align='center'>S</td>
			<td align='center'>N</td>
		</tr>
		<tr>
		<?php
			##########################
			# Startamo crtati kalendar
			for($start=1; $start < $dan_u_tjednu; $start++){
				echo "<td></td>";
				# ispiši prazna polja tablice na početku tjedna ako imaju
			}
			for($datum=1; $datum <= $dana_u_mjesecu; $datum++){
				if(($datum == date('d')) and ($mjesec == date('m'))){
					$color_datum = 'class="bg_blue"';
				}
				else{
					$color_datum = "";
				}
				echo "<td align='center' {$color_datum}>$datum</td>";
				# ispiši datume u polja tablice
				$dan_u_tjednu++;
				# uvečaj dan u tjednu za brojanje polja tablice do 7 i ponovo primi od 1
				if($dan_u_tjednu > 7){
					echo "</tr><tr>";
					# ako je više od sedam polja prelomi u novi red
					$dan_u_tjednu = 1;
					# ako je dan u tjednu veći od 7 počni brojati dan u tjednu od 1
				}
			}
			for($dan_u_tjednu; $dan_u_tjednu > 1 and $dan_u_tjednu <= 7; $dan_u_tjednu++){
				echo "<td></td>";
				# ispiši prazna polja tablice do kraja tjedna ako imaju
			}
		?>
		</tr>
	</table>
</div>

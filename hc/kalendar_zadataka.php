<?php

ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/putanja.php");
	
}
else{
	echo "<script> window.location.replace('prijava.php');</script>";
}
		
setlocale(LC_ALL, 'hr_HR.utf-8');	
$naziv_mjeseca[1] = 'Siječanj';
$naziv_mjeseca[2] = 'Veljača';
$naziv_mjeseca[3] = 'Ožujak';
$naziv_mjeseca[4] = 'Travanj';
$naziv_mjeseca[5] = 'Svibanj';
$naziv_mjeseca[6] = 'Lipanj';
$naziv_mjeseca[7] = 'Srpanj';
$naziv_mjeseca[8] = 'Kolovoz';
$naziv_mjeseca[9] = 'Rujan';
$naziv_mjeseca[10] = 'Listopad';
$naziv_mjeseca[11] = 'Studeni';
$naziv_mjeseca[12] = 'Prosinac';
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
<div class='container-fluid'>
	
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2>Kalendar zadataka: <b class='text-success'><?php echo $naziv_mjeseca[$mjesec] . " " .  $godina; ?></b></h2>
		<form method='post' class='d-flex flex-wrap' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			
			<select name="godina" class='form-select rounded-0 w-auto'>
				<option value="">- godina -</option>
				<?php
				foreach( range(1900, 2050) as $godina1 ){
				echo '<option value="'.$godina1.'" '; if ($godina1 == $godina){echo "
				selected"; } echo ">$godina1</option>";
				}
				?>
			</select>
			<select name="mjesec" class='form-select rounded-0 w-auto'>
				<option value="">- mjesec -</option>
				<?PHP
				for($mjesec1 = 1; $mjesec1 <= 12; $mjesec1++){ 
				?>
					<option value="<?php echo $mjesec1; ?>" <?php if ($mjesec1 == $mjesec){ echo "selected";} ?>><?php echo $naziv_mjeseca[$mjesec1]; ?></option>
				<?php
				} 
				?>
			</select>
			<a href="javascript:window.location.href=window.location.href" class='btn btn-outline-danger d-flex align-items-center'>Danas</a>
			<input class='d-none' type='submit' name="spremi" value='spremi'>
			
			<input class="form-control w-auto" id="myInput" type="text" placeholder="traži ...">
			<a href='' id='dd' class="btn btn-success border-0 d-flex align-items-center"><i class="fa">&#xf1c3;</i></a>
			
			
		</form>
	</div>
	<div class="table-responsive sihterica kalendar ">
		<table class='table table-bordered'>
			<thead>
				<tr align='center'class=''>
					<td align='center'>P</td>
					<td align='center'>U</td>
					<td align='center'>S</td>
					<td align='center'>Č</td>
					<td align='center'>P</td>
					<td align='center'>S</td>
					<td align='center'>N</td>
				</tr>
			</thead>
			<tbody id='myTable'>
			<tr>
			<?php
			
			for($start=1; $start < $dan_u_tjednu; $start++){
				echo "<td></td>"; # ispiši prazna polja tablice na početku ako imaju
			
			}
			for($datum=1; $datum <= $dana_u_mjesecu; $datum++){
				$slozi_datum = "$datum.$mjesec.$godina";
				$slozi_datum2 = date("d.m.Y", strtotime($slozi_datum));
				
				if(($slozi_datum2 == date('d.m.Y'))){
					$color_datum = "bg-danger fw-bold";
				}
				else{
					$color_datum = "";
				}
				
				?>
				<td class='p-0 m-0'>
					<div class='d-flex justify-content-end'>
					<a href="unos_aktivnost.php?datum_aktivnosti=<?php echo $slozi_datum2; ?>" class='link-dark fw-bold border-start border-bottom border-2 border-warning bg-opacity-25 <?php echo $color_datum; ?> d-flex justify-content-center align-items-center text-dark' style="width:30px;height:30px;"><?php echo $datum; ?></a>
					</div>
					<div class="d-flex flex-column text-start p-2">
						<?php
						$datum_aktivnosti2 = "$datum.$mjesec.$godina";
						$datum_aktivnosti = date("Y-m-d", strtotime($datum_aktivnosti2));
						
						
						$sql = "SELECT * FROM aktivnosti, korisnici WHERE datum_aktivnosti = '$datum_aktivnosti'
						AND korisnik_id = id_korisnik
						AND kalendar = 1
						ORDER BY vrijeme ASC";
						$res = mysqli_query($veza, $sql);
						if(mysqli_num_rows($res) > 0){
							while($red = mysqli_fetch_array($res)){
								$id_aktivnost = $red["id_aktivnost"];
								$naziv_aktivnosti = $red["naziv_aktivnosti"];
								$naziv_korisnika = $red["naziv_korisnika"];
								$napomena = $red["napomena"];
								$id_aktivnost = $red["id_aktivnost"];
								if($red['vrijeme'] == '00:00:00') $vrijeme = '';
								else $vrijeme = date("H:i", strtotime($red['vrijeme']));  
								
								?>
								<a href='prikaz_aktivnosti.php?id_aktivnost=<?php echo $id_aktivnost; ?>' class='btn btn-warning  text-start rounded py-0 px-2 my-1 w-auto shadow-sm'><small><?php echo "$vrijeme $naziv_korisnika <br> $napomena"; ?></small></a>
								<?php
								
							}
						}
						
						?>
						
		
					</div>
				
				</td>
				
				<?php
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
				echo "<td></td>"; # ispiši prazna polja tablice do kraja tjedna ako imaju
			}
			?>
			</tbody>
			</tr>
		</table>
	</div>
</div>
<script>
$("select[name='godina'], select[name='mjesec']").on("change", function(){
	$("input[name='spremi']").click();
});
</script>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
	var value = $(this).val().toLowerCase();
	$("#myTable tr").filter(function() {
	  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
  });
});
</script>
<script>
	var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";

	var blob = new Blob([html], { type: "application/vnd.ms-excel" });
	var a = document.getElementById("dd");
		a.href = URL.createObjectURL(blob);
		a.download = "hc_kalendar.xls";
</script>

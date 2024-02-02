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
date_default_timezone_set('Europe/Zagreb');

	function test_input($data) {
	  $data = trim($data);
	  $data = strip_tags($data); 
	  $data = htmlspecialchars($data);
	return $data;}


	$danas = date("d.m.Y");


	$start = '1970-01-01';	
	$end = '7777-07-07';
	$naziv_podgrupe = $aktivnost = $korisnik_naziv =  '';
	 
	if(isset($_POST['submit'])){
		$korisnik = test_input($_POST["korisnik"]);
		$aktivnost = test_input($_POST["aktivnost"]);
		$podgrupa_id = test_input($_POST["podgrupa_id"]);
		
		$sql = "SELECT naziv_podgrupe FROM podgrupe WHERE id_podgrupe = '$podgrupa_id'";
		$rez = mysqli_query($veza, $sql);
		if($red = mysqli_fetch_array($rez)){
			$naziv_podgrupe = $red["naziv_podgrupe"];
		}
		$sql = "SELECT naziv_korisnika FROM korisnici WHERE id_korisnik = '$korisnik'";
		$rez = mysqli_query($veza, $sql);
		if($red = mysqli_fetch_array($rez)){
			$korisnik_naziv = $red["naziv_korisnika"];
		}

		if(!empty($_POST["start"])){
			$start1 = test_input($_POST["start"]);
			$start2 = date("d.m.Y",strtotime($start1));					
			$start = date("Y-m-d",strtotime($start1));
		}
		if(!empty($_POST["end"])){
			$end1 = test_input($_POST["end"]);
			$end2 = date("d.m.Y",strtotime($end1));					
			$end = date("Y-m-d",strtotime($end1));	
		}
		
		if($aktivnost == 'da'){
			$odaberi = "AND CURDATE() BETWEEN datum_od AND datum_do";
			
		}
		else if($aktivnost == 'ne'){
			$odaberi = "AND CURDATE() NOT BETWEEN datum_od AND datum_do";
		}
		else $odaberi = '';
	}



	$display_block_popis = "";


	$poslovi = ("SELECT COUNT(id_clanstva) AS broj_clanova FROM clanstva, korisnici, podgrupe
	WHERE korisnici.id_korisnik = clanstva.korisnik_id 
	AND podgrupa_id = id_podgrupe
	AND podgrupe.naziv_podgrupe LIKE '%$naziv_podgrupe%'
	AND korisnici.naziv_korisnika LIKE '%$korisnik_naziv%'
	AND clanstva.datum_od >= '$start'
	AND clanstva.datum_do <= '$end'
	$odaberi");		
					
	$broj_clanova = mysqli_query($veza, $poslovi);	
	while($poslovi = mysqli_fetch_array($broj_clanova)) 
	{
		$broj= $poslovi['broj_clanova'];
	}
	$date_now = date("Y-m-d");
	$br = 0;
	$sql = ("SELECT * FROM clanstva, korisnici, podgrupe
	WHERE korisnici.id_korisnik = clanstva.korisnik_id 
	AND podgrupa_id = id_podgrupe
	AND podgrupe.naziv_podgrupe LIKE '%$naziv_podgrupe%'
	AND korisnici.naziv_korisnika LIKE '%$korisnik_naziv%'
	AND clanstva.datum_od >= '$start'
	AND clanstva.datum_do <= '$end'
	$odaberi
	ORDER BY datum_do DESC, datum_od ASC, naziv_korisnika ASC");							
	$get_rsPoslovi = mysqli_query($veza, $sql);
	if(mysqli_num_rows($get_rsPoslovi) > 0){				
		while($red = mysqli_fetch_array($get_rsPoslovi)) 
		{
			$br++;
			 $id_podgrupe = $red['id_podgrupe'];	

			$id = $red['id_korisnik'];		 
			$naziv_korisnika = $red['naziv_korisnika'];  
			$naziv_podgrupe = $red['naziv_podgrupe'];		 
			$datum_od = date("Y.m.d.",strtotime($red['datum_od']));
			if($red["datum_do"] == "7777-07-07") $datum_do = "";
			else $datum_do = date("d.m.Y", strtotime($red["datum_do"]));
			
			if ($date_now >$red["datum_do"]){
				$klasa = " opacity-50 bg-danger bg-opacity-25";
				$tekst = "";
				$trenutno_aktivan = 'NE';
			} 
			
			else if ($date_now < $red["datum_od"]){
				$klasa = "bg-success bg-opacity-25";
				$tekst = "<small class='ms-2'>- REZERVACIJA</small>";
				$trenutno_aktivan = 'NE';
			} 
			else{
				$klasa = '';
				$tekst = "";
				$trenutno_aktivan = "DA";
			}
			
			
	 
	 
			$display_block_popis .= "
				<tr class='tr $klasa'>
					<td>$br</td>		
						
					<td><a style='text-decoration:none;' class='link-secondary' href='prikaz_korisnik.php?id=$id'>$naziv_korisnika $tekst</a></td>	
					<td><a style='text-decoration:none;' class='font-weight-bold link-dark'href='prikaz_rubrika.php?id=$id_podgrupe'>$naziv_podgrupe</a></td>		
					<td>$datum_od</td>		
					<td>$datum_do</td>			
					<td>$trenutno_aktivan</td>			
				 </tr>";
							
									
		}
	}



include("zaglavlje.php"); 	
?>
	<div class="container-fluid flex-grow-1 my-2">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Upisani članovi: <?php echo $broj ?></h2>
			<div class='d-flex flex-wrap'>
				<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži...">
				
			</div>	
		</div>
	

	
	<form method="post" action="" class='mt-3'>
		<div class='d-flex flex-wrap align-items-center'>
		
			<div>
				<select  name="korisnik" class='form-control mr-1'>				
						<?php							
						$sql = ("SELECT DISTINCT(id_korisnik), naziv_korisnika, korisnik_id FROM korisnici, clanstva
						WHERE id_korisnik = korisnik_id
						ORDER BY naziv_korisnika ASC");	
						$rezultat = mysqli_query($veza, $sql);	?>
						<option value="">Svi članovi</option>	
						<?php
						while ($redak = mysqli_fetch_array($rezultat))
						{
							$id_korisnik = $redak["id_korisnik"];
							$naziv = $redak["naziv_korisnika"];	
						echo '<option value="' . $id_korisnik . '"';

							if ($id_korisnik == (int)$korisnik)
								{
								echo " selected";  
								}							

						echo ">$naziv</option>";				
						}
						?>
				</select>
			</div>
			
			<div>
				<select  name="podgrupa_id" class='form-control mr-2'>				
						<?php							
						$sql = ("SELECT * FROM podgrupe 
						WHERE aktivna_podgrupa = 1
						ORDER BY artikl_id, naziv_podgrupe");	
						$rezultat = mysqli_query($veza, $sql);	?>
						<option value="">Sve grupe</option>	
						<?php
						while ($redak = mysqli_fetch_array($rezultat))
						{
							$id_podgrupe = $redak["id_podgrupe"];
							$naziv_podgrupe = $redak["naziv_podgrupe"];	
						echo '<option value="' . $id_podgrupe . '"';

							if ($id_podgrupe == (int)$podgrupa_id)
								{
								echo " selected";  
								}							

						echo ">$naziv_podgrupe</option>";				
						}
						?>
				</select>
			</div>
			<div>
				<input placeholder="Datum početka:" class='datum_start form-control mr-1' type="text" name="start" value="<?php if (!empty($start1)){ echo $start1;} ?>" autocomplete='off'>	
			</div>
			<div>
				<input placeholder="Datum završetka:" class='datum_start form-control mr-1' type="text" name="end" value="<?php if (!empty($end1)){ echo $end1;} ?>" autocomplete='off'>
			</div>
			<div>
				<select  name="aktivnost" class='form-control mr-2'>				
						
						<option value="">Sve aktivnosti</option>	
						
						<option value="da" <?php if($aktivnost == 'da') echo 'selected'; ?>>Aktivan</option>
						<option value="ne" <?php if($aktivnost == 'ne') echo 'selected'; ?>>Neaktivan</option>
				</select>
			</div>
			<div >			
				<input type="submit" value="Filtriraj" name="submit" class='btn btn-success'>
			</div>
			<div>
				<a href='' id='dd' class="btn btn-success m-1"><i style="font-size:18px" class="fa">&#xf1c3;</i></a>
			</div>
		</div>
    </form>
	<div class='table-responsive w-100'>
		<table id="table_id" class="table table-hover table-stripped border-light w-100">  
			<thead>  
				<tr>
				  <td>ID</td>	
				  <td>Partner</td>		  
				  <td>Grupa</td>			  	  
				  <td>Datum početka</td>
				  <td>Datum završetka</td>  
				  <td>Trenutno aktivan</td>  
				</tr>
			</thead>
			<tbody id="myTable">	
				<?php echo $display_block_popis; ?> 
			</tbody>	
	  </table>
	</div>
</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
<script type="text/javascript" language="javascript">
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
			a.download = "ure.xls";
	</script>

<script>
  	$(function(){
		$(".datum_start").datepicker({
		changeMonth: true,
		changeYear: true});
		$.datepicker.regional['hr'] = {
			closeText: 'Zatvori',
			prevText: '&#x3c;',
			nextText: '&#x3e;',
			currentText: 'Danas',
			monthNames: ['Siječanj','Veljača','Ožujak','Travanj','Svibanj','Lipanj',
			'Srpanj','Kolovoz','Rujan','Listopad','Studeni','Prosinac'],
			monthNamesShort: ['Sij','Velj','Ožu','Tra','Svi','Lip',
			'Srp','Kol','Ruj','Lis','Stu','Pro'],
			dayNames: ['Nedjelja','Ponedjeljak','Utorak','Srijeda','Četvrtak','Petak','Subota'],
			dayNamesShort: ['Ned','Pon','Uto','Sri','Čet','Pet','Sub'],
			dayNamesMin: ['Ne','Po','Ut','Sr','Če','Pe','Su'],
			weekHeader: 'Tje',
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['hr']);
		jQuery.validator.addMethod("date", function(value, element) {
			return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
		}, "Please enter a correct date");
		jQuery.validator.addMethod("integer2", function(value, element) {
			return this.optional(element) || /^\d{2}$/.test(value);
		}, "A positive or negative non-decimal number please");		
	});	

</script>
<script>
	$(document).ready(function() {
		$('#table_id').DataTable({			
			"paging":   false,
			"searching": false,
			"info":     false,
			"order": [],			
		});
	});		
</script>

<script>
		var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";

		var blob = new Blob([html], { type: "application/vnd.ms-excel" });
		var a = document.getElementById("dd");
			a.href = URL.createObjectURL(blob);
			a.download = "excel.xls";
	</script>

<?php
include("podnozje.php");
?>
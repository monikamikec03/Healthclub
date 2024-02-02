<?php
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{
	require("../include/var.php");		
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
	//$end = date("Y-m-d");
	$end = '7777-07-07';
	
	$user_naziv = $aktivnost = $korisnik_naziv = '';
	 
	if(isset($_POST['submit'])){
		$korisnik = test_input($_POST["korisnik"]);
		$aktivnost = test_input($_POST["aktivnost"]);
		$user = test_input($_POST["user"]);
		
		$sql = "SELECT puno_ime_poslodavac_user FROM poslodavac_user WHERE id_user = '$user'";
		$rez = mysqli_query($veza, $sql);
		if($red = mysqli_fetch_array($rez)){
			$user_naziv = $red["puno_ime_poslodavac_user"];
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
	}



	$display_block_popis = "";


	$poslovi = ("SELECT COUNT(id_uplate) AS broj_poslova FROM clanstva_uplate, korisnici, poslodavac_user
	WHERE korisnici.id_korisnik = clanstva_uplate.clan_id 
	AND clanstva_uplate.unio = id_user
	AND poslodavac_user.puno_ime_poslodavac_user LIKE '%$user_naziv%'
	AND korisnici.naziv_korisnika LIKE '%$korisnik_naziv%'
	AND clanstva_uplate.od >= '$start'
	AND clanstva_uplate.do <= '$end'");		
					
	$broj_poslova = mysqli_query($veza, $poslovi);	
	while($poslovi = mysqli_fetch_array($broj_poslova)) 
	{
		$broj= $poslovi['broj_poslova'];
	}


	$sql = ("SELECT * FROM clanstva_uplate, korisnici, poslodavac_user
	WHERE korisnici.id_korisnik = clanstva_uplate.clan_id 
	AND clanstva_uplate.unio = id_user
	AND poslodavac_user.puno_ime_poslodavac_user LIKE '%$user_naziv%'
	AND korisnici.naziv_korisnika LIKE '%$korisnik_naziv%'
	AND clanstva_uplate.od >= '$start'
	AND clanstva_uplate.do <= '$end'
	ORDER BY clanstva_uplate.datum_unosa DESC");							
	$get_rsPoslovi = mysqli_query($veza, $sql);
	if(mysqli_num_rows($get_rsPoslovi) > 0){				
		while($poslovi = mysqli_fetch_array($get_rsPoslovi)) 
		{
			 $id_uplate = $poslovi['id_uplate'];	

			 $id = $poslovi['id_korisnik'];		 
			 $naziv_korisnika = $poslovi['naziv_korisnika'];   
			 $napomena = $poslovi['napomena']; 
			 $spremio = $poslovi['puno_ime_poslodavac_user'];
			 $datum_uplate = date("Y.m.d.",strtotime($poslovi['datum_uplate']));
			 $od = date("Y.m.d.",strtotime($poslovi['od']));
			 $do = date("Y.m.d.",strtotime($poslovi['do']));
			
	 
	 
	 
			$display_block_popis .= "
				<tr>
					<td>$id_uplate</td>		
					
					<td><a class='link-secondary' href='prikaz_korisnik.php?id=$id'>$naziv_korisnika</a></td>	
                    <td><a class='font-weight-bold'href='unos_uplate.php?id_uplate=$id_uplate'>$datum_uplate</a></td>	
					<td>$napomena</td>		
					<td>$od</td>
					<td>$do</td>

					<td>$spremio</td>
					<td>$unijeto</td>			
				 </tr>";
							
									
		}
	}



include("zaglavlje.php"); 	
?>
	<div class="container-fluid flex-grow-1 my-2">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Aktivnosti: <?php echo $broj ?></h2>
			<div class='d-flex flex-wrap'>
				<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži...">
				<?php 
				if($_SESSION["idRole"] == "15588" or $_SESSION["idRole"] == "22")
					echo "<a class='btn btn-primary m-1'  href='unos_uplate.php'> + dodaj članarinu</a>"; 
				?>
			</div>	
		</div>
	

	
	<form method="post" action="" class='mt-3'>
		<div class='d-flex flex-wrap align-items-center'>
		
			<div>
				<select  name="korisnik" class='form-control mr-1'>				
						<?php							
						$sql = ("SELECT * FROM korisnici 
						ORDER BY naziv_korisnika ASC");	
						$rezultat = mysqli_query($veza, $sql);	?>
						<option value="">Svi korisnici</option>	
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
				<select  name="user" class='form-control mr-2'>				
						<?php							
						$sql = ("SELECT * FROM poslodavac_user 
						WHERE aktivan_user = 1
						ORDER BY puno_ime_poslodavac_user");	
						$rezultat = mysqli_query($veza, $sql);	?>
						<option value="">Svi useri</option>	
						<?php
						while ($redak = mysqli_fetch_array($rezultat))
						{
							$id_user = $redak["id_user"];
							$naziv_user = $redak["puno_ime_poslodavac_user"];	
						echo '<option value="' . $id_user . '"';

							if ($id_user == (int)$user)
								{
								echo " selected";  
								}							

						echo ">$naziv_user</option>";				
						}
						?>
				</select>
			</div>
			<div>
				<input placeholder="Od:" class='datum_start form-control mr-1' type="text" name="start" value="<?php if (!empty($start1)){ echo $start1;} ?>" autocomplete='off'>	
			</div>
			<div>
				<input placeholder="Do:" class='datum_start form-control mr-1' type="text" name="end" value="<?php if (!empty($end1)){ echo $end1;} ?>" autocomplete='off'>
			</div>
			<div >			
				<input type="submit" value="Filtriraj" name="submit" class='btn btn-success'>
			</div>
		</div>
    </form>
	<div class='table-responsive w-100'>
		<table id="table_id" class="table table-hover table-stripped border-light w-100">  
			<thead>  
				<tr>
				  <td>ID</td>	
				  <td>Član</td>		  		  
				  
				  <td>Datum uplate</td>
                  <td>Napomena</td>	  
				  <td>Od</td>
				  <td>Do</td>
				  <td>Spremio</td>	
				  <td>Datum unosa</td> 	  
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
<?php
include("podnozje.php");
?>
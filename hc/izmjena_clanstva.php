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

function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data); 
  $data = htmlspecialchars($data);
return $data;}
	

if(isset($_POST['posalji'])){
	if (empty($_POST["podgrupa_id"])) {
		$podgrupa_idErr = "<p class='text-danger'>* niste odabrali podgrupu</p>";
	} 
	else {
		$podgrupa_id = test_input($_POST["podgrupa_id"]);
		if (!preg_match("/^[0-9? ]*$/", $podgrupa_id)){
			$podgrupa_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	
	if (empty($_POST["id_clanstva"])) {
		$id_clanstvaErr = "<p class='text-danger'>* niste odabrali podgrupu</p>";
	} 
	else {
		$id_clanstva = test_input($_POST["id_clanstva"]);
		if (!preg_match("/^[0-9? ]*$/", $id_clanstva)){
			$id_clanstvaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	
	if (empty($_POST["korisnik"])) {
		$korisnikErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else {
		$korisnik = test_input($_POST["korisnik"]);
		if (!preg_match("/^[0-9? ]*$/",$korisnik)){
			$korisnikErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	
	if (empty($_POST["datum_od"])) {
			$datum_odErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else {
		$datum_od1 = test_input($_POST["datum_od"]);
		$datum_od = date("d.m.Y",strtotime($datum_od1));
		$datum_od2 = date("Y-m-d",strtotime($datum_od1));					
		if (!preg_match("/\d{2}.\d{2}.\d{4}$/",$datum_od1))   
		{
			$datum_odErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
		}
	}
		
	if (empty($_POST["datum_do"])) {
			$datum_do2 = "7777-07-07";
	} 
	else {
		$datum_do1 = test_input($_POST["datum_do"]);
		$datum_do = date("d.m.Y",strtotime($datum_do1));
		$datum_do2 = date("Y-m-d",strtotime($datum_do1));					
		if (!preg_match("/\d{2}.\d{2}.\d{4}$/",$datum_do1))   
		{
			$datum_doErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
		}
	}
	
	if(empty($korisnikErr) && empty($datum_odErr) && empty($datum_doErr) && empty($podgrupa_idErr)){
		$sql = "UPDATE clanstva SET korisnik_id = '$korisnik', datum_od = '$datum_od2', datum_do = '$datum_do2' WHERE podgrupa_id = '$podgrupa_id' AND id_clanstva = $id_clanstva";
		if(mysqli_query($veza, $sql)){
			header("location:prikaz_rubrika.php?id=$podgrupa_id");
		}
		else{
			$porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Dogodila se pogreška, pokušajte ponovno.</td></tr>"; 
		}
	}
	else{
		$porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Niste ispunili sva polja.</td></tr>"; 
	}
			
	
}
		
		
if (isset($_GET["id"])){
	$id = ($_GET['id']);	
	$sql = "SELECT * FROM clanstva, korisnici
	WHERE id_clanstva = $id
	AND id_korisnik = korisnik_id";	 	
	$rezultat = mysqli_query($veza, $sql); 
	if($red = mysqli_fetch_array($rezultat))
	{
		
		$id_clanstva = $red['id_clanstva'];
		$podgrupa_id = $red['podgrupa_id'];
		$korisnik = $red['korisnik_id']; 						
		$naziv_korisnika = $red['naziv_korisnika']; 						
		$datum_od = date("d.m.Y", strtotime($red["datum_od"]));
		if($red["datum_do"] == "7777-07-07") $datum_do = "";
		else $datum_do = date("d.m.Y", strtotime($red["datum_do"]));

		$naslovStranice = "Izmjena članstva: " . $naziv_korisnika;
		$linkZaPovratak = "rubrike.php";
			
	}
}
	
?>	  

<div id="sadrzaj" class='flex-grow-1'>

	<form method="post" action="">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2><?php echo $naslovStranice; ?></h2>
			
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input type="submit" name="posalji" class='fontAwesome btn btn-primary m-1' value="&#xf00c Spremi">
				<a class='btn btn-warning m-1' href='prikaz_rubrika.php?id=<?php echo $podgrupa_id; ?>'>🡸 Natrag</a>		
			</div>			
		</div>
	
	

								
	
		<input type="text" class="d-none" name="podgrupa_id" value="<?php echo $podgrupa_id; ?>">
		<input type="text" class="d-none" name="id_clanstva" value="<?php echo $id_clanstva; ?>">
		<div class="row">
			<div class='col-md-4'>
				<select name="korisnik" class="form-control">
					<option value="" disabled selected>Odaberite korisnika</option>
					<?php
												
					$sql = ("SELECT * FROM korisnici 
					ORDER BY naziv_korisnika ASC");	
					$rezultat = mysqli_query($veza, $sql);	?>
					<option value="">Svi korisnici</option>	
					<?php
					while ($redak = mysqli_fetch_array($rezultat)){
						$id_korisnik = $redak["id_korisnik"];
						$naziv = $redak["naziv_korisnika"];	
						echo '<option value="' . $id_korisnik . '"';
						if ($id_korisnik == (int)$korisnik){
							echo " selected";  
						}							
						echo ">$naziv</option>";				
					}
					?>

				</select>
				<?php echo $korisnikErr; ?>
			</div>
			<div class='col-md-4'>
				<input type="text" class="datum_start form-control" name="datum_od" placeholder="Datum početka" value="<?php echo $datum_od; ?>" autocomplete="off">
				<?php echo $datum_odErr; ?>
			</div>
			<div class='col-md-4'>
				<input type="text" class="datum_start form-control" name="datum_do" placeholder="Datum završetka (ostaviti prazno ako je aktivni član)" value="<?php echo $datum_do; ?>" autocomplete="off">
				<?php echo $datum_doErr; ?>

			</div>

			<?php echo $porukaErr; ?>
		</div>			
					
	</form>
		
</div>
	
	


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
<?php
include("podnozje.php");
?>

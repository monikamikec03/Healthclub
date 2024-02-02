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
function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);
return $data;}	


$praznici_id = $datum_praznika = $naziv_praznika = $poruka = '';
$praznici_idErr = $datum_praznikaErr = $naziv_praznikaErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{		
	if (empty($_REQUEST["praznici_id"])) {
		$praznici_idErr = "";
		} else {						
		$praznici_id = test_input($_REQUEST['praznici_id']);
			if (!preg_match("/^[0-9 ]*$/",$praznici_id)){    
			$praznici_idErr = "<p class='text-danger d-block'>* neispravni znakovi</p>";  
			}
		}



	if (empty($_REQUEST["datum_praznika"])) {
		$datum_praznikaErr = "<p class='text-danger d-block'>* morate popuniti polje</p>";
	} 
	else {
		$datum_praznika1 = test_input($_POST["datum_praznika"]);
		$datum_praznika = date("Y-m-d",strtotime($datum_praznika1));					
		if (!preg_match("/\d{2}.\d{2}.\d{4}$/",$datum_praznika1))   
		{
			$datum_praznikaErr = "<p class='text-danger d-block'>* neispravni znakovi</p>"; 
		}
	}				

	
	
		if (empty($_REQUEST["naziv_praznika"])) {
			$naziv_praznikaErr = "<p class='text-danger d-block'>* morate popuniti polje</p>";
		} else {
			$naziv_praznika = test_input($_REQUEST["naziv_praznika"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$naziv_praznika)){								
				$naziv_praznikaErr = "<p class='text-danger d-block'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}			



							

	if(empty($datum_praznikaErr) AND empty($naziv_praznikaErr) AND empty($praznici_idErr)){			

		if(!empty($praznici_id)){				
		
			$sql = ("UPDATE praznici SET datumPraznika = '".$datum_praznika."', nazivPraznika =  '".$naziv_praznika."' WHERE praznici_id = $praznici_id");
		}
			else 						
		{			   			
			$sql =("INSERT INTO `praznici` (`datumPraznika`, `nazivPraznika`) 
			VALUES ('".$datum_praznika."', '".$naziv_praznika."');");				
		}
		
		
		if(mysqli_query($veza, $sql)){
			if(empty($praznici_id)) header("Location: praznici.php");
			else header("Location: rad_prikaz_praznici.php?praznici_id=$praznici_id");
		}
		else 
		{	
			$poruka = '<p class="text-danger d-block">* unos u bazu nije uspio, morate ispravno popuniti sva polja</p>' . mysqli_error($veza);
		}			

	}
}

	if (isset($_GET["praznici_id"]))    //prikaz podataka za izostanak
	{								
		$id = addSlashes($_GET['praznici_id']);				
		$sql =("SELECT * FROM praznici WHERE praznici_id = $id");	 
		$rezultat = mysqli_query($veza, $sql);					
		while($red = mysqli_fetch_array($rezultat)) 
		{ 
			$id = $red['praznici_id'];	
			$naziv_praznika = $red['nazivPraznika'];	
			$datum_praznika1 = date('d.m.Y', strtotime($red['datumPraznika']));	
			$naslov = 'Izmjena praznika';
			$link = "rad_prikaz_praznici.php?praznici_id=$id";
			
		}
	}else{
		$naslov = 'Unos praznika';
		$link = 'praznici.php';
	}
?>
<div class="container-fluid flex-grow-1">	
	<form method='post' action=''>
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2><?php echo $naslov; ?></h2>
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi">
				<a class='btn btn-danger text-white m-1' href='<?php echo $link; ?>'>✖ Odustani</a> 		
			</div>			
		</div>
		<?php
		if(!empty($id)){
			echo "<input type='hidden' name='praznici_id' value='$id'>";
		}
		?>
		<div class='row'>
			<div class='col-md-6'>
				<label class='m-1'>Datum praznika:</label>
				<input name='datum_praznika' class='form-control m-1 datum_start' value="<?php echo $datum_praznika1; ?>">
				<?php echo $datum_praznikaErr; ?>
			</div>		
			<div class='col-md-6'>
				<label class='m-1'>Naziv praznika:</label> 
				<input name='naziv_praznika' class='form-control m-1' value="<?php echo $naziv_praznika; ?>">
				<?php echo $naziv_praznikaErr; ?>
			</div>																
					
		</div>	
		<?php echo $poruka; ?>
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
		
	});	
  </script>
<?php
include("podnozje.php");
?>
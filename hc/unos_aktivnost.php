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

	function test_input($data) {
	  $data = trim($data);
	  $data = strip_tags($data); 
	  $data = htmlspecialchars($data);
	return $data;}

$korisnik = $aktivnost =  $napomena = "";
$korisnikErr = $aktivnostErr =  $napomenaErr = "";		

if(isset($_POST['submit']))
{
	if (empty($_POST["korisnik"])) {
		$korisnikErr = "<p class='text-danger'>* morate popuniti</p>";
		} 
		else 
		{
			$korisnik = test_input($_POST["korisnik"]);
			if (!preg_match("/^[0-9? ]*$/",$korisnik))  
			{
			$korisnikErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}

	if (empty($_POST["aktivnost"])) {
		$aktivnostErr = "<p class='text-danger'>* morate popuniti</p>";
		} 
		else 
		{
			$aktivnost = test_input($_POST["aktivnost"]);
			if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.:; ]*$/",$aktivnost))  
			{
			$aktivnostErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}	
		
	if (empty($_POST["vrijeme"])) {
		$vrijemeErr = "";
		$vrijeme = "00:00:00";
		} 
		else 
		{
			$vrijeme = test_input($_POST["vrijeme"]);
			if (!preg_match("/^[0-9?!,.:; ]*$/",$vrijeme))  
			{
				$vrijemeErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
			else{
				
			}
		}	

	if (empty($_POST["napomena"])) {
		$napomenaErr = "";
		} 
		else 
		{
			$napomena = test_input($_POST["napomena"]);
			if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.:;\\/\- ]*$/",$napomena))  
			{
			$napomenaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}
		
		if(isset($_REQUEST["kalendar"])){
			$kalendar = 1;
		}
		else
		{
			$kalendar = 0;
		} 
		if (!preg_match("/^[0-9]*$/",$kalendar)) 
		{
			$kalendarErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}	
		
	if (empty($_REQUEST["start"])) {
	$startErr = "<p class='text-danger'>* morate popuniti</p>";
	} else {
			$start1 = test_input($_POST["start"]);
			$start2 = date("d.m.Y",strtotime($start1));					
			$start = date("Y-m-d",strtotime($start1));					
			if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/",$start1))   
			{
			$startErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
			}
		}	

    if (empty($_POST["od"])) {
        $odErr = "<p class='text-danger'>* morate popuniti</p>";
    } 
    else {
        $od1 = test_input($_POST["od"]);
        $od2 = date("d.m.Y",strtotime($od1));					
        $od = date("Y-m-d",strtotime($od1));					
        if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/",$od1))   
        {
        $odErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
        }
    }	

    if (empty($_POST["do"])) {
        $doErr = "<p class='text-danger'>* morate popuniti</p>";
    } 
    else {
        $do1 = test_input($_POST["do"]);
        $do2 = date("d.m.Y",strtotime($do1));					
        $do = date("Y-m-d",strtotime($do1));					
        if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/",$do1))   
        {
        $doErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
        }
    }

	$tekst = htmlentities($_POST["tekst"]);					

	if(empty ($korisnikErr) AND empty ($aktivnostErr) AND empty ($napomenaErr) AND empty ($startErr) AND empty ($vrijemeErr))
	{				
		if(isset($_POST["id_aktivnost"])){				
			$id = ($_POST['id_aktivnost']);
		}
		if(!empty($id)){
				
			$sql = ("UPDATE `aktivnosti` SET naziv_aktivnosti = '".$aktivnost."', korisnik_id = '".$korisnik."', napomena = '".$napomena."', datum_aktivnosti = '".$start."', od = '".$od."', do = '".$do."', vrijeme = '".$vrijeme."', kalendar = '".$kalendar."', tekst = '".$tekst."', unijeo = {$_SESSION['idKorisnika']}	
			WHERE id_aktivnost = $id ");
		}
		else{		
			$sql=("INSERT INTO `aktivnosti`( `naziv_aktivnosti`, `korisnik_id`, `napomena`, `tekst`, `kalendar`, `datum_aktivnosti`, `od`, `do`, `vrijeme`, `unijeo`) 
			VALUES ('".$aktivnost."','".$korisnik."', '".$napomena."', '".$tekst."', '".$kalendar."', '".$start."', '".$od."', '".$do."', '".$vrijeme."', {$_SESSION['idKorisnika']});"); 
		}
		
		
		if (mysqli_query($veza, $sql))
		{
			header("Location:aktivnosti.php");					
		} 
		else
		{
			$porukaErr = "<p class='text-danger'>unos /izmjena aktivnosti nije uspjela.</p>"; 
		}
		
		
	}else{
		$porukaErr = "<p class='text-danger'>Niste popunili sva polja.</p>"; 
	}

}	


if(isset($_GET["id_korisnik"])){
	$korisnik = test_input($_GET["id_korisnik"]);
}

if (isset($_GET["datum_aktivnosti"])){
	$datum_aktivnosti = test_input($_GET["datum_aktivnosti"]);
	if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/",$datum_aktivnosti)){
		header("location:aktivnosti.php");
	}
	else{
		$start1 = $datum_aktivnosti;
	}
}
if (isset($_GET["id_aktivnost"]))         //prikaz podataka za postoječi izostanak
{
$id = ($_GET['id_aktivnost']);	
$sql = "SELECT*FROM aktivnosti
WHERE id_aktivnost = $id";
	$rezultat = mysqli_query($veza, $sql); 
	if($red = mysqli_fetch_array($rezultat)){
		$naslovStranice = "Izmjena aktivnosti";
		$id_aktivnost = $red['id_aktivnost'];			
		$aktivnost = $red['naziv_aktivnosti'];	
		$korisnik = $red['korisnik_id'];				
		$napomena = $red['napomena'];  
		$tekst = $red['tekst'];  
		if($red['vrijeme'] == '00:00:00') $vrijeme = '';
		else $vrijeme = date("H:i", strtotime($red['vrijeme']));  
		$start1 = date("d.m.Y", strtotime($red['datum_aktivnosti']));		
		if(empty($red["od"])){
            $od = "";
         }
         else{
             $od = date("d.m.Y",strtotime($red['od']));
         }
         if(empty($red["do"])){
            $do = "";
         }
         else{
            $do = date("d.m.Y",strtotime($red['do']));
         }
		if ($red["kalendar"] == 1){
			$kalendar = "checked";			
		}	
		else{
			$kalendar = "";
		}		
	}	
}

include("zaglavlje.php"); 	
?>

<div id="sadrzaj">		
	<form class="" method="post" action="">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Unos aktivnosti</h2>
			
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" class='form-control my-1' value="✔ Spremi"> 
				<a class='btn btn-warning m-1' href='aktivnosti.php'>🡸 Natrag</a>		
			</div>			
		</div>
		<?php	
		if (!empty($id_aktivnost)){
			echo "<input type='hidden' name='id_aktivnost' value='$id_aktivnost'>";
		}
		?>	
		<div class='row'>
			<div class='col-md-4'>
				<label class='my-1'>* Korisnik:</label>			
				<select  name="korisnik" class='form-control my-1' >				
						<?php							
						$sql = ("SELECT * FROM korisnici 
						ORDER BY naziv_korisnika ASC");	
						$rezultat = mysqli_query($veza, $sql);	?>
						<option value="">odaberite Korisnika</option>	
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
				<?php echo $korisnikErr;?>				
			</div>	

			<div class='col-md-4'>
				<label class='my-1'>* Odaberi aktivnost:</label>		
				<select name="aktivnost" class='form-control my-1'>
					<option value="">Svi tipovi aktivnosti</option>
					<?php
					$sql = "SELECT * FROM tipovi_aktivnosti ORDER BY naziv_tipa";
					$res = mysqli_query($veza, $sql);
					while($red = mysqli_fetch_array($res)){
						$naziv_tipa = $red["naziv_tipa"];
					
					?>
					<option <?php if ($aktivnost == $naziv_tipa) echo 'selected' ; ?> value="<?php echo $naziv_tipa; ?>"><?php echo $naziv_tipa; ?></option>	
					<?php
					}
					?>					
												
				</select>
				<?php echo $aktivnostErr;?>	
			</div>				

			

			<div class='col-md-4'>
				<label class='my-1'>* Datum aktivnosti:</label>
				<input class="form-control datum_start my-1" type="text" name="start" value="<?php if (!empty($start1)){ echo $start1;} ?>" autocomplete='off'>	
				<?php echo $startErr;?>
			</div> 	

            <div class='col-md-4'>
				<label class='my-1'>* Od:</label>
				<input class="form-control datum_start my-1" type="text" name="od" value="<?php echo $od; ?>" autocomplete='off'>	
				<?php echo $odErr;?>
			</div> 
            
            <div class='col-md-4'>
				<label class='my-1'>* Do:</label>
				<input class="form-control datum_start my-1" type="text" name="do" value="<?php echo $do; ?>" autocomplete='off'>	
				<?php echo $doErr;?>
			</div>

			<div class='col-md-4'>
				<label class='my-1'>* Vrijeme:</label>
				<input type="text" class="timepicker form-control" value="<?php echo $vrijeme; ?>" name="vrijeme" autocomplete='off'>
				<?php echo $vrijemeErr;?>
			</div>

            <div class='col-md-8'>
				<label class='my-1'>* Opis aktivnosti:</label>	
				<input type="text" name="napomena" value="<?php  echo $napomena;?>" class='form-control my-1'>	
				<?php echo $napomenaErr;?>
			</div>	

			<div class='col-md-4'>
				<label class='m-1 d-block'>Prikaži na kalendaru:</label>
				<input type="checkbox" name="kalendar"	<?php echo $kalendar; ?> class='form-check-input my-1'/>
				<?php echo $kalendarErr; ?>
			</div>
  
					
			
			<div class='col-lg-12 col-md-12'>
				<label class='m-1'>Sadržaj:</label>
				<textarea id="editor" name="tekst" ><?php echo $tekst; ?></textarea>
			</div>	
		</div>
		
		<?php echo $porukaErr; ?>
		
			

		
	</form>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
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
$(document).ready(function(){
    $('input.timepicker').timepicker({
		timeFormat: 'H:mm',
		interval: 30,
		minTime: '6',
		maxTime: '10:00pm',
		
	});
});
</script>


<script>
CKEDITOR.replace('editor', {
	filebrowserUploadUrl: '../ckeditor/ck_upload.php',
	filebrowserUploadMethod: 'form',
    disallowedContent: 'img{width,height};'	
}); 
</script> 
</body>
</html> 
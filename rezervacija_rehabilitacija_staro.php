<?php
ob_start();
require("../moj_spoj/otvori_vezu_cmp.php");		
setlocale(LC_ALL, 'hr_HR.utf-8');	
		

function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data); 
  $data = htmlspecialchars($data);
return $data;}	 

//slanje maila
$zeljeni_dani = array();
$ime_prezime  = $godine = $email = $broj_mobitela = $frakture = $razlog_dolaska = $operativni_zahvati = $porezotine = $traume = $lijekovi = $pravila_privatnosti = $mail_zeljeni_dani = $datum = $datum2 = '';
$ime_prezimeErr = $godineErr = $emailErr = $broj_mobitelaErr = $fraktureErr = $razlog_dolaskaErr = $operativni_zahvatiErr = $porezotineErr = $traumeErr = $lijekoviErr = $pravila_privatnostiErr = '';

if (($_SERVER["REQUEST_METHOD"] == "POST") and (isset($_POST['g-recaptcha-response']))){

	if (empty($_POST['ime_prezime'])) {
		$ime_prezimeErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$ime_prezime = test_input($_POST['ime_prezime']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$ime_prezime)){
			$ime_prezimeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
				
	if (empty($_POST['email'])) {
		$emailErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "<p class='text-danger text-end mt-2'>Morate unijeti ispravan format.</p>"; 
		}
	}	
	
	if (empty($_POST['broj_mobitela'])) {
		$broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$broj_mobitela = test_input($_POST['broj_mobitela']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$broj_mobitela)){
			$broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['godine'])) {
		$godineErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$godine = test_input($_POST['godine']);
		if (!preg_match("/^[0-9+ ]*$/",$godine)){
			$godineErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	
	if (empty($_POST['razlog_dolaska'])) {
		$razlog_dolaskaErr = "";
	} 
	else {
		$razlog_dolaska = test_input($_POST['razlog_dolaska']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$razlog_dolaska)){
			$razlog_dolaskaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['operativni_zahvati'])) {
		$operativni_zahvatiErr = "";
	} 
	else {
		$operativni_zahvati = test_input($_POST['operativni_zahvati']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$operativni_zahvati)){
			$operativni_zahvatiErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['frakture'])) {
		$fraktureErr = "";
	} 
	else {
		$frakture = test_input($_POST['frakture']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$frakture)){
			$fraktureErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['porezotine'])) {
		$porezotineErr = "";
	} 
	else {
		$porezotine = test_input($_POST['porezotine']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$porezotine)){
			$porezotineErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['traume'])) {
		$traumeErr = "";
	} 
	else {
		$traume = test_input($_POST['traume']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$traume)){
			$traumeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}

	if (empty($_POST['lijekovi'])) {
		$lijekoviErr = "";
	} 
	else {
		$lijekovi = test_input($_POST['lijekovi']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$lijekovi)){
			$lijekoviErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if(isset($_POST["pravila_privatnosti"])){
		$pravila_privatnosti = 1;
	}
	else{
		$pravila_privatnostiErr = "<p class='text-danger text-end mt-2'>Morate prihvatiti pravila privatnosti kako bismo mogli spremiti Vaše podatke.</p>";
	}
	
	foreach($_POST["zeljeni_dani"] as $kljuc => $datum){
		$datum2 = test_input($datum);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/", $datum2)){
			$zeljeni_daniErr[] = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
		else{
			$zeljeni_dani[] = $datum2;
			$mail_zeljeni_dani .= "<p>$datum2</p>";
		}
	}
	
	
	if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
		$captchaErrr='';
	}
	else{
		$secretKey = "6Lf4v8shAAAAAGPPTHXt5plljp6u81brAbq-FWTD";
		$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captcha);
		
		$json_response = json_decode($response);									
		
		if($json_response->success == true) {
			$captchaErrr = '';
		} 
		else {
			$captchaErrr = "<p class='text-danger text-end mt-2'>Radite nedozvoljene stvari.</p>";
		}	
				
		$captchaErrr = "<p class='text-danger text-end mt-2'>Radite nedozvoljene stvari. Google recaptcha validacija neuspješna.</p>";
	}
	
	
					
		
			
	
	if(empty($ime_prezimeErr) AND empty($emailErr) AND empty($broj_mobitelaErr) AND empty($godineErr) AND empty($razlog_dolaskaErr) AND empty($operativni_zahvatiErr) AND empty($fraktureErr) AND empty($porezotineErr) AND empty($traumeErr) AND empty($lijekoviErr) AND empty($pravila_privatnostiErr) AND empty($captchaErrr)){
		
			$to = 'healthclub.vrbovec@gmail.com';
					
			$message = "
			<div>
				<h3 class='my-2'>Imate novu prijavu za prvi pregled u FizioOne</h3>
				<p class='my-2'>Korisnik $ime_prezime se prijavio/la za pregled.</p>
				<h4 class='mt-4'>Kontakt podaci:</h4>
				<p><span class='text-success'>Email adresa:</span> $email</p>
				<p><span class='text-success'>Broj mobitela:</span> $broj_mobitela</p>
				<p><span class='text-success'>Broj godina:</span> $godine</p>
				
				<h4 class='mt-4'>Koji je Vaš razlog dolaska?</h4>
				<p>$razlog_dolaska</p>
				
				<h4 class='mt-4'>Operativni zahvati od rođenja do danas? Ukoliko ih je bilo, koji su i kada ste ih obavili?</h4>
				<p>$operativni_zahvati</p>
				
				<h4 class='mt-4'>Frakture (puknuća) kostiju, ligamenata, mišića od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</h4>
				<p>$frakture</p>
				
				<h4 class='mt-4'>Porezotine, opekline, udarci i padovi od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</h4>
				<p>$porezotine</p>
				
				<h4 class='mt-4'>Teške psihološke traume od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</h4>
				<p>$traume</p>
				
				<h4 class='mt-4'>Uzimate li lijekove i ukoliko da opišite ih.</h4>
				<p>$lijekovi</p>
				
				<h4 class='mt-4'>Termini</h4>
				<div>$mail_zeljeni_dani</div>

			</div>
			";
		
			$subject = "Prijava za pregled - $ime_prezime"; 

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'To: '.$to.'' . "\r\n";
			$headers .= 'From: '.$email. "\r\n";
		
			$sql = "INSERT INTO prijava_prvi_pregled (ime_prezime, email, broj_mobitela, godine, razlog_dolaska, operativni_zahvati, frakture, porezotine, traume, lijekovi, pravila_privatnosti, termini) VALUES('$ime_prezime', '$email', '$broj_mobitela', '$godine', '$razlog_dolaska', '$operativni_zahvati', '$frakture', '$porezotine', '$traume', '$lijekovi', '$pravila_privatnosti', '$mail_zeljeni_dani')";
			mysqli_query($veza, $sql);
			
			if(mail($to, $subject, $message, $headers)){				
				echo "<script>window.location = 'zahvala_kontakt.php';</script>";
				
			}
			else{
				$messageErr = "<p class='text-danger'>Dogodila se pogreška, pokušajte ponovno!</p>";
			}
		
	}
	else{
		$messageErr = "<p class='text-danger'>Niste ispunili sva polja!</p>";
	}
	
	
}		
include("zaglavlje.php");
include("navigacija_light.php");

?>


<div class="container flex-grow-1 py-5">
	<h1>Prijavi se na prvi pregled</h1>
	<h2 class="text-secondary mb-3">Rezervacija termina & Informiranje o rehabilitaciji</h2>
	
	<div class="row ps-4">
		
		
		<form method="post" action="" id="form" class="row bg-light">
		
			<div class="col-xl-3 col-lg-6 p-1">
				<label>Ime i prezime:</label>
				<input type="text" name="ime_prezime" class="form-control" value="<?php echo $ime_prezime; ?>">
				<?php echo $ime_prezimeErr; ?>
			</div>
			<div class="col-xl-3 col-lg-6 p-1">
				<label>Godine:</label>
				<input type="number" name="godine" class="form-control" value="<?php echo $godine; ?>">
				<?php echo $godineErr; ?>
			</div>
			
			<div class="col-xl-3 col-lg-6 p-1">
				<label>Email adresa:</label>
				<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
				<?php echo $emailErr; ?>
			</div>
			
			<div class="col-xl-3 col-lg-6 p-1">
				<label>Broj mobitela:</label>
				<input type="text" name="broj_mobitela" class="form-control" value="<?php echo $broj_mobitela; ?>">
				<?php echo $broj_mobitelaErr; ?>
			</div>
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Koji je Vaš razlog dolaska?</label>
				<textarea  name="razlog_dolaska" class="form-control"><?php echo $razlog_dolaska; ?></textarea>
				<?php echo $razlog_dolaskaErr; ?>
			</div>
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Operativni zahvati od rođenja do danas? Ukoliko ih je bilo, koji su i kada ste ih obavili?</label>
				<textarea  name="operativni_zahvati" class="form-control" ><?php echo $operativni_zahvati; ?></textarea>
				<?php echo $operativni_zahvatiErr; ?>
			</div>
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Frakture (puknuća) kostiju, ligamenata, mišića od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</label>
				<textarea  name="frakture" class="form-control" ><?php echo $frakture; ?></textarea>
				<?php echo $fraktureErr; ?>
			</div> 
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Porezotine, opekline, udarci i padovi od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili? </label>
				<textarea  name="porezotine" class="form-control" ><?php echo $porezotine; ?></textarea>
				<?php echo $porezotineErr; ?>
			</div> 
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Teške psihološke traume od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</label>
				<textarea  name="traume" class="form-control" ><?php echo $traume; ?></textarea>
				<?php echo $traumeErr; ?>
			</div> 
		
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Uzimate li lijekove i ukoliko da opišite ih.</label>
				<textarea  name="lijekovi" class="form-control" ><?php echo $lijekovi; ?></textarea>
				<?php echo $lijekoviErr; ?>
			</div> 
			
			<div class="col-xl-6 col-lg-12 p-1">
				<label>Odaberite datume i vrijeme kada biste voljeli rezervirati svoj termin.</label>
				<div id='zeljeni_dani'>
					<?php
					foreach($zeljeni_dani as $key => $value){
						?>
						<input type="text" name="zeljeni_dani[]" class="fontAwesome form-control p-1"  value="<?php echo $value; ?>">
						<?php
						echo $zeljeni_daniErr[$key];
					}
					?>
				</div>
				<input type="text" class="fontAwesome datum_start form-control p-1" placeholder='&#xf073 Kliknite da odaberete datum i vrijeme' value="">
			</div>
			
			<div class="col-xl-6 col-lg-12 p-1">
				<div class="form-check form-switch p-0 m-0">
					<label class="form-check-label m-0 p-0 d-block" for="flexSwitchCheckDefault">Označite da prihvaćate <a href='politika_privatnosti.php' target='_blank'>pravila privatnosti</a> kako bismo mogli obraditi Vaše podatke.</label>
					
					<input class="form-check-input ms-0" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="pravila_privatnosti">
					<?php echo $pravila_privatnostiErr; ?>
				</div>
			</div>

			<div class="col-md-12 p-1 d-flex justify-content-end">
				<input type="submit" name="posalji" value="Pošalji" class="btn btn-warning px-5 g-recaptcha" 
				data-sitekey="6Lf4v8shAAAAALd-tNPK9E5zYML5St99wYDkrsBI" 
				data-callback='onSubmit' 
				data-action='submit'>
			</div>
			
			<div class="col-md-12 p-1 d-flex justify-content-end">
				<div>
					<?php echo "<div class=''>$captchaErrr</div>"; ?>
					<?php echo "<div class=''>$messageErr</div>"; ?>
				</div>
			</div>
			
			
		</form>

	</div>	
</div>





 <script src="https://www.google.com/recaptcha/api.js"></script>
 <script>
   function onSubmit(token) {
     document.getElementById("form").submit();
   }
 </script>
 <script>
	document.addEventListener("DOMContentLoaded", function(event) { 
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
</script>
<script>
jQuery.datetimepicker.setLocale('hr');
$('.datum_start').datetimepicker({
 	format:'d.m.Y H:i',
	dayOfWeekStart: 1,
	
	onSelectTime:function(dp,$input){
		if($input.val().length != 0){
			$('<input>').attr({
				type: 'text',
				class: 'form-control p-1',
				name: 'zeljeni_dani[]',
				value: $input.val(),
			}).appendTo('#zeljeni_dani');
			$('.datum_start').val('');
		}
	},
	
	closeOnDateTimeSelect:true,
});

</script>
<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>
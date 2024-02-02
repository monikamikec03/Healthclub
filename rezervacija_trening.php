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
$ime_prezime  = $email = $broj_mobitela = $artikl = $iskustvo = $ciljevi = $popis_grupa_mail = '';
$ime_prezimeErr = $emailErr = $broj_mobitelaErr = $artiklErr = $iskustvoErr = $ciljeviErr = '';

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
		if (!preg_match("/^[0-9+ ]*$/",$broj_mobitela)){
			$broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['godine'])) {
		$godineErr = "";
	} 
	else {
		$godine = test_input($_POST['godine']);
		if (!preg_match("/^[0-9+ ]*$/",$godine)){
			$godineErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	
	if (empty($_POST['iskustvo'])) {
		$iskustvoErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$iskustvo = test_input($_POST['iskustvo']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$iskustvo)){
			$iskustvoErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if (empty($_POST['artikl'])) {
		$artiklErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$artikl = test_input($_POST['artikl']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$artikl)){
			$artiklErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
		else{
			$sql = "SELECT * FROM artikli_popis WHERE id_artikla = $artikl";
			$res = mysqli_query($veza, $sql);
			if($red = mysqli_fetch_array($res)){
				$naziv_artikla = $red["naziv_artikla"];
			}
		}
	}
	
	if (empty($_POST['ciljevi'])) {
		$ciljeviErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$ciljevi = test_input($_POST['ciljevi']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$ciljevi)){
			$ciljeviErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	if(isset($_POST["pravila_privatnosti"])){
		$pravila_privatnosti = 1;
	}
	else{
		$pravila_privatnostiErr = "<p class='text-danger text-end mt-2'>Morate prihvatiti pravila privatnosti kako bismo mogli spremiti Vaše podatke.</p>";
	}
	
	foreach($_POST["rubrika"] as $kljuc => $rubrika){
		$rubrika2 = test_input($rubrika);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/", $rubrika2)){
			$rubrikaErr[] = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
		else{
			$popis_grupa[] = $rubrika2;
			$popis_grupa_mail .= "<p>$rubrika2</p>";
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
	
	
					
		
			
	
	if(empty($ime_prezimeErr) AND empty($emailErr) AND empty($broj_mobitelaErr) AND empty($godineErr) AND empty($iskustvoErr) AND empty($ciljeviErr) AND empty($artiklErr) AND empty($pravila_privatnostiErr) AND empty($captchaErrr)){
		
			$to = 'healthclub.vrbovec@gmail.com';
					
			$message = "
			<div>
				<h3 class='my-2'>Imate novu prijavu za probni trening u BasicGymOne</h3>
				<p class='my-2'>Korisnik $ime_prezime se prijavo za probni trening na <b>$naziv_artikla</b></p>
				<h4 class='mt-4'>Kontakt podaci:</h4>
				<p><span class='text-success'>Email adresa:</span> $email</p>
				<p><span class='text-success'>Broj mobitela:</span> $broj_mobitela</p>
				<p><span class='text-success'>Broj godina:</span> $godine</p>
				
				<h4 class='mt-4'>Jeste li iskusni vježbač?</h4>
				<p>$iskustvo</p>
				
				<h4 class='mt-4'>Koje ciljeve želite ostvariti vježbanjem?</h4>
				<p>$ciljevi</p>
				
				<h4 class='mt-4'>Željeni tip treninga?</h4>
				<p>$naziv_artikla</p>

				<h4 class='mt-4'>Grupa (ako postoji) ?</h4>
				<p>$popis_grupa_mail</p>
				
			</div>
			"; 
		
			$subject = "Prijava za probni trening - $ime_prezime"; 

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'To: '.$to.'' . "\r\n";
			$headers .= 'From: '.$email. "\r\n";
		
			$sql = "INSERT INTO prijava_probni_trening (ime_prezime, email, broj_mobitela, godine, artikl, popis_grupa, iskustvo, ciljevi, pravila_privatnosti) VALUES('$ime_prezime', '$email', '$broj_mobitela', '$godine', '$artikl', '$popis_grupa_mail', '$iskustvo', '$ciljevi', '$pravila_privatnosti')";
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
	<h1>Prijavi se na probni trening</h1>
	<h2 class="text-success mb-3">Rezervacija termina & Informiranje o treninzima</h2>

	<div class="row">
		<img src="slike/probni_trening.jpg" class="col-lg-6 col-md-4 col-sm-12 p-0 border-start border-success border-5 object-fit-cover">
		<div class="col-lg-6 col-md-8 col-sm-12">
			<form method="post" action="" id="form" class="row p-3 bg-light">
			
				<div class="col-xl-6 col-lg-12 p-1">
					<label>Ime i prezime:</label>
					<input type="text" name="ime_prezime" class="form-control" value="<?php echo $ime_prezime; ?>">
					<?php echo $ime_prezimeErr; ?>
				</div>
				<div class="col-xl-6 col-lg-12 p-1">
					<label>Godine:</label>
					<input type="number" name="godine" class="form-control" value="<?php echo $godine; ?>">
					<?php echo $godineErr; ?>
				</div>
				
				<div class="col-xl-6 col-lg-12 p-1">
					<label>Email adresa:</label>
					<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
					<?php echo $emailErr; ?>
				</div>
				
				<div class="col-xl-6 col-lg-12 p-1">
					<label>Broj mobitela:</label>
					<input type="text" name="broj_mobitela" class="form-control" value="<?php echo $broj_mobitela; ?>">
					<?php echo $broj_mobitelaErr; ?>
				</div>
				
				<div class="col-xl-12 col-lg-12 p-1">
					<label>Jeste li iskusni vježbač?</label>
					<textarea  name="iskustvo" class="form-control" placeholder="Navesti jeste li prije što trenirali i gdje." ><?php echo $iskustvo; ?></textarea>
					<?php echo $iskustvoErr; ?>
				</div>
				
				<div class="col-xl-12 col-lg-12 p-1">
					<label>Koje ciljeve želite ostvariti vježbanjem?</label>
					<textarea  name="ciljevi" class="form-control" ><?php echo $ciljevi; ?></textarea>
					<?php echo $ciljeviErr; ?>
				</div>
				
				<div class="col-xl-12 col-lg-12 p-1">
					<select name="artikl" class="form-control" onChange="traziRubriku(this.value);">
						<option value="" disabled selected>Odaberite željenu vrstu treninga</option>
						<?php
						$sql = "SELECT * FROM artikli_popis, jedinica_mjere 
						WHERE aktivan_artikl = 1 
						AND jed_mjere = id_jed_mjere
						ORDER BY redoslijed";
						$res = mysqli_query($veza, $sql);
						while($red = mysqli_fetch_array($res)){
							
							$id_artikla = $red["id_artikla"];
							$naziv_artikla = $red["naziv_artikla"];
							$naziv_jed_mjere = $red["naziv_jed_mjere"];
							?>
							<option value="<?php echo $id_artikla; ?>" <?php if($id_artikla == $artikl) echo "selected"; ?>><?php echo $naziv_artikla . " [" . $naziv_jed_mjere . "]"; ?>
							<?php
						}
						?>
					</select>
					<?php echo $artiklErr; ?>
				</div> 
				<div class="col-xl-12 col-lg-12 p-1">
					<div id='rubrika'>
						<?php
						if(!empty($artikl)) {

							$query ="SELECT * FROM podgrupe 
							WHERE artikl_id = '" . $artikl . "'
							AND aktivna_podgrupa = 1 
							ORDER BY artikl_id, redoslijed_podgrupe";
							$result = mysqli_query($veza, $query);
							if(mysqli_num_rows($result) > 0){
								echo '<select name="rubrika[]" class="form-control m-1 p-0" multiple>';
								echo '<option class="px-2 py-1" value="" disabled selected>Odaberite željenu grupu</option>';
								while($row = mysqli_fetch_array($result)) {    
									$id_podgrupe = $row["id_podgrupe"];
									$naziv_podgrupe = $row["naziv_podgrupe"];
									?>
										<option class='px-2 py-1' value="<?php echo $naziv_podgrupe; ?>" <?php
										if(in_array($naziv_podgrupe, $popis_grupa)) echo 'selected'; ?>><?php echo $naziv_podgrupe; ?></option>
									<?php
									
								}					
								echo "</select>";
								echo "<small>Ukoliko želite odabrati više grupa, držite tipku CTRL i poklikajte sve željene grupe.</small>";
							}
							
						}
						?>
						
					</div>
					<?php echo $rubrikaErr; ?>
				</div>
				
				<div class="col-xl-12 col-lg-12 p-1">
					<div class="form-check form-switch p-0 m-0">
						<label class="form-check-label m-0 p-0 d-block" for="flexSwitchCheckDefault">Označite da prihvaćate <a href='politika_privatnosti.php' target='_blank'>pravila privatnosti</a> kako bismo mogli obraditi Vaše podatke.</label>
						
						<input class="form-check-input ms-0" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="pravila_privatnosti">
						<?php echo $pravila_privatnostiErr; ?>
					</div>
				</div>
			
				<div class="col-md-12 p-1 d-flex justify-content-end">
					<input type="submit" name="posalji" value="Pošalji" class="btn btn-success px-5 g-recaptcha" 
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
</div>
<script>				
	function traziRubriku(val) {
		$.ajax({
		type: "POST",
		url: "dropdown.php",
		data:'id_artikla='+val,
		success: function(response){
			$("#rubrika").html(response); 
			}
		});
	}
</script>
	
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
<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>
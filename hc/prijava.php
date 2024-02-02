<?php
ob_start();	
session_start();
	
require("../include/var.php");			
require("../include/putanja.php");
include("zaglavlje.php");
$captcha = true;
if(isset($_POST["captcha_code"]) AND $_POST["captcha_code"]!=$_SESSION["captcha_code"]) {
	$captcha = false;
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	
	
$organizacijaErr = $lozinkaErr = $remErr = ""; 
$organizacija = $lozinka = $rem = ""	;	
$poruka = "";
	
if (!empty($_SERVER['HTTP_CLIENT_IP']))   {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else{
	$ip = $_SERVER['REMOTE_ADDR'];
}
	
$ip = substr($ip, 7);

if(empty($ip)){
	header("Location:brava.php");
}
		
	
$sql_failed = "SELECT count(ip_address) AS failed_login_attempt FROM failed_login 
WHERE ip_address = '$ip' 
AND date BETWEEN DATE_SUB( NOW() , INTERVAL 1 DAY ) AND NOW()";
$rezultat = mysqli_query($veza, $sql_failed);
$row = mysqli_fetch_assoc($rezultat);
$failed_login_attempt = $row['failed_login_attempt'];	
			
if($failed_login_attempt >= 5){
	header("Location:brava.php");
}
	
if ($_SERVER['REQUEST_METHOD'] == 'POST'){	
	if (empty($_POST["organizacija"])) {
		$organizacijaErr = "<p class='text-danger px-4'>* morate popuniti polje</p>";
	} 
	else {
		$organizacija = test_input($_POST["organizacija"]);
		if (!filter_var($organizacija, FILTER_VALIDATE_EMAIL)) {
		  $organizacijaErr = "<p class='text-danger px-4'>Neispravan format</p>"; 
		}
	}		
		
	if (empty($_POST["lozinka"])) {
		$lozinkaErr = "<p class='text-danger px-4'>* morate popuniti polje</p>";
	} 
	else {
		$lozinka = test_input(SHA1($_POST["lozinka"]));
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$lozinka)) { 
			$lozinkaErr = "<p class='text-danger px-4'>* specijalni znakovi ne prolaze</p>"; 
		}
	}
		
	if (empty($_POST["remember"])) {
		$remErr = "";
		$rem = 0;
	} 
	else {
		$rem = test_input($_POST["remember"]);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$rem)) { 
			$remErr = "<p class='text-danger px-4'>* specijalni znakovi ne prolaze</p>"; 
		}
	}
		
	if(empty($organizacijaErr) and empty($lozinkaErr) and empty($remErr)){
		$sql = sprintf("SELECT * FROM poslodavac_user, role
		WHERE role.id = poslodavac_user.role_id
		AND email_poslodavac_user = '%s'  
		AND lozinka = '%s'", $organizacija, $lozinka);

		$rezultat = mysqli_query($veza, $sql);
		if(mysqli_num_rows($rezultat) == 1){
			$korisnik = mysqli_fetch_assoc($rezultat);							
			$_SESSION["punoIme"] = $korisnik["puno_ime_poslodavac_user"];	
			$_SESSION["email"] = $korisnik["email_poslodavac_user"];		
			$_SESSION["idKorisnika"] = $korisnik["id_user"];		
			$_SESSION["idRole"] = $korisnik["role_id"]; 	
			if(isset($_POST['remember'])){
				setcookie('email', $organizacija, strtotime("+1 year"));
				setcookie('pass', $_POST["lozinka"], strtotime("+1 year"));
			}	
			
			
			if(in_array($_SESSION['idRole'], [20121990, 15588, 2, 22, 222])){
				header("Location:index.php");								
			}
			
			$obrisi = ("DELETE FROM failed_login WHERE ip_address = '$ip'");	
			mysqli_query($veza, $obrisi);
			
		}	 		
		else
		{
			$poruka = '<p class="text-danger px-4">* prijava nije uspjela pokušajte ponovo</p>';
			
			if ($failed_login_attempt < 5) {
				$veza->query("INSERT INTO failed_login (ip_address,date) VALUES ('$ip', NOW())");
			} 
			else 
			{
				$poruka = "<p class='text-danger px-4'>Imate pravo na tri pokušaja prije vremenskog ključa.</p>";
			}					
		}	  
	}				
	else 
	{
		$poruka = '<p class="text-danger px-4">* imate više od 3 pokušaja</p>';				

		if ($failed_login_attempt < 5) {
			$veza->query("INSERT INTO failed_login (ip_address,date) VALUES ('$ip', NOW())");
		} 
		else 
		{
			$poruka = "<p class='text-danger px-4'>Imate pravo na tri pokušaja prije vremenskog ključa.</p>";
		}
	}	  
}

if(isset($_COOKIE['email']) and isset($_COOKIE['pass'])){	
	$email = $_COOKIE['email'];
	$pass = $_COOKIE['pass'];
	$sql = sprintf("SELECT*FROM poslodavac_user
	WHERE email_poslodavac_user = '%s' 
	AND lozinka = '%s'", $email, sha1($pass));

	$rezultat = mysqli_query($veza, $sql);
	if(mysqli_num_rows($rezultat) == 1){
		$korisnik = mysqli_fetch_assoc($rezultat);							
		$_SESSION["punoIme"] = $korisnik["puno_ime_poslodavac_user"];	
		$_SESSION["email"] = $korisnik["email_poslodavac_user"];
		$_SESSION["idKorisnika"] = $korisnik["id_user"];						
		$_SESSION["idRole"] = $korisnik["role_id"]; 									
		if(isset($_POST['remember'])){
			setcookie('email', $organizacija, strtotime("+1 year"));
			setcookie('pass', $pass, strtotime("+1 year"));
		}	
		
		
		if(in_array($_SESSION['idRole'], [15588, 2, 22, 222])){
			header("Location:index.php");								
		}
		
		$obrisi = ("DELETE FROM failed_login WHERE ip_address = '$ip'");	
		mysqli_query($veza, $obrisi);
		
	}	 		
}	

?>

<div class="bg-light flex-grow-1 d-flex flex-column align-items-center justify-content-center">
	<form method="post" action="" class="w-100">
		<div class="d-flex flex-column justify-content-center align-items-center">
			<img src="../slike/HEALTHCLUB LOGO.png" class="ms-2 logo-slika">
			<span class="link-dark display-6 p-2">HEALTHCLUB</span>
			
			<div class="col-xl-3 col-lg-5 col-md-6 col-11 my-3">
				<div class="p-1">
				
					<input type="email" name="organizacija" class="fontAwesome form-control" placeholder="&#xf0e0; email adresa" autocomplete="off">
					<?php echo $organizacijaErr; ?>
				
				</div>
				<div class="p-1">
					<input type="password" name="lozinka" class="fontAwesome form-control" placeholder="&#xf084; lozinka" autocomplete="off">
					<?php echo $lozinkaErr; ?>
				</div>
				
				<div class="p-1">
					<div class="form-check form-switch d-flex align-items-center">
						<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="remember">
						<label class="form-check-label ms-4" for="flexSwitchCheckDefault">Zapamti moju prijavu</label>
					</div>
				</div>
				
				<div class="p-1">
					<input type="submit" name="prijava" class="btn btn-success w-100" value="Prijava">
				</div>
			</div>
	</form>
</div>
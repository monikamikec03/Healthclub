<?php	 		
session_start(); 
	
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222])){	
	require("../include/var.php");			
	require("../include/putanja.php");
} 
else {
	header("Location:odjava.php"); 
}
	
$punoImeErr = $rolaErr = $emailErr = $lozinkaErr = ""; 
$punoIme = $rola = $email = $lozinka = "";			
					
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}					
if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (empty($_POST["punoIme"])) {
			$punoImeErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} 
		else 
		{
			$punoIme = test_input($_POST["punoIme"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:  ]*$/",$punoIme))  
			{
			$punoImeErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}	

	
		if (empty($_POST["idRole"])) {
			$rolaErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} 
		else 
		{
			$rola = test_input($_POST["idRole"]);
			if (!preg_match("/^[0-9 ]*$/",$rola))  
			{
			$rolaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}
		if (empty($_POST["email"])) {
			$emailErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} 
		else 
		{
			$email = test_input($_POST["email"]);
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "<p class='text-danger'>Niste napisali email adresu ispravnim formatom</p>"; 			  
			}
			
			if(empty($_POST["id_user"])){		
				$kod = "SELECT * FROM poslodavac_user WHERE email_poslodavac_user ='$email'";
				$provjera = mysqli_query($veza, $kod);
				if(mysqli_num_rows($provjera) != 0){
					$emailErr = "<p class='text-danger'>email adresa postoji u bazi</p>"; 
				}
			}
		}
		  

		
		if (empty($_POST["lozinka1"])) {
			$lozinkaErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} 
		else 
		{
			$lozinka = test_input($_POST["lozinka1"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;:##   ]*$/",$lozinka)) 
			{
			$lozinkaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
			else{
				$lozinka = sha1($lozinka);
			}
		}	
		if(!empty($_POST["id_user"]) && empty($lozinka)){
					
			$kod = "SELECT * FROM poslodavac_user WHERE id_user = " . $_POST["id_user"];
			$provjera = mysqli_query($veza, $kod);
			if($red = mysqli_fetch_array($provjera)){
				$lozinka = $red["lozinka"];
				$lozinkaErr = '';
			}
			
			
		}			

		$nadimak = test_input($_POST["nadimak"]);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;:   ]*$/",$nadimak))  
		{
			$nadimakErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
		
		if(isset($_REQUEST["trener"]))
		{
			$trener = 1;
		}
		else
		{
			$trener = 0;
		} 
		if (!preg_match("/^[0-9]*$/",$trener)) 
		{
		$trenerErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}

		if (empty($_POST["aktivan"])) {
			$aktivanErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} else {
			$aktivan = test_input($_POST["aktivan"]);
			if (!preg_match("/^[0-9 ]*$/",$aktivan)){
			$aktivanErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}		
		$opis = htmlentities($_POST["opis"]);												
		
	if(empty ($punoImeErr) AND empty ($nadimak_za_prijavuErr) AND empty ($rolaErr) AND empty ($emailErr) AND empty ($lozinkaErr) AND empty ($nadimakErr)  AND empty ($trenerErr)  AND empty ($opisErr)){

		if(!empty($_POST["id_user"])){
			$id = (int)$_POST["id_user"];
			$sql = ("UPDATE poslodavac_user SET puno_ime_poslodavac_user = '".$punoIme."', role_id = '".$rola."', lozinka = '$lozinka', email_poslodavac_user = '".$email."', spremio = {$_SESSION['idKorisnika']}, aktivan_user = '$aktivan', nadimak = '$nadimak', opis = '$opis', trener = '$trener' WHERE id_user = $id ");	
		}
		else{
			$q ="SELECT id_user FROM poslodavac_user WHERE email_poslodavac_user ='$email'";
			$r = mysqli_query($veza, $q);
			if(mysqli_num_rows($r) > 0){
				$emailErr = "<p class='text-danger'>email adresa je već registrirana i korištena</p>";

			}
			else{
				$sql =("INSERT INTO `poslodavac_user` (`puno_ime_poslodavac_user`, `lozinka`, `role_id`,  `spremio`, `email_poslodavac_user`, `aktivan_user`, `nadimak`, `opis`, `trener`) 
				VALUES ('".$punoIme."', '".$lozinka."', '".$rola."', {$_SESSION['idKorisnika']}, '".$email."', '1', '".$nadimak."', '".$opis."', '".$trener."');");
				
			}
								
			
		}
		
		if (mysqli_query($veza, $sql)){
			header("Location:pregled_usera.php");
					
		}
		else
		{
			$poruka = "<p class='text-danger'>unos /izmjena usera nije uspjela.</p>" . mysqli_error($veza);
		}

	}		
}
if (isset($_GET["id_user"])) 
{								
	$id = (int)$_GET['id_user'];
	$sql = "SELECT * FROM poslodavac_user, role  
	WHERE poslodavac_user.role_id = role.id 
	AND id_user = $id";	
	$rezultat = mysqli_query($veza, $sql);
	if(mysqli_num_rows($rezultat) == 1)
	{
			$red = mysqli_fetch_assoc($rezultat);							  
				$id = $red['id_user'];			
				$punoIme = $red['puno_ime_poslodavac_user']; 
				$lozinka = ""; 							
				$rola = $red['role_id'];	
		
				$email = $red['email_poslodavac_user'];			
				$aktivan = $red['aktivan_user'];		
				$nadimak = $red['nadimak'];		
				$trener = $red['trener'];		
				$opis = $red["opis"];
	} 
	else
	{					
		header("Location:prikaz_poduzetnik_nadimak.php");
	}
}


	
	
require("navigacija.php"); 		
?>	  		

<div id="sadrzaj" class="flex-grow-1">		
	<form method="post" action="">	
		<?php
		if(!empty($id)){
		?>
		<input type='hidden' name='id_user' value='<?php echo $id; ?>'>	
		<?php
		}
		?>
		<div class='d-flex flex-wrap justify-content-between align-items-center border-bottom'>
			<h2 class='m-1'>Unos usera:</h2>
			
			<div class='d-flex flex-wrap justify-content-end'>
				<input class="btn btn-primary m-1" type="submit" name="submit" value="✔ Spremi"> 	
				<a class='btn btn-danger m-1' href='pregled_usera.php'>✖ Odustani</a>		
			</div>			

		</div>
		<div class='row'>
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Puno ime:</label><br>		
				<INPUT TYPE="text" name="punoIme" value="<?php echo $punoIme;?>" class='m-1 form-control'>	
				<?php echo $punoImeErr;?>
			</div>
			
			
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Razina prava:</label>					
				<select  name="idRole" class='m-1 form-control'>				
						<?php	
						$sql = ("SELECT * FROM role");					
						$rezultat = mysqli_query($veza, $sql);
							while ($redak = mysqli_fetch_array($rezultat)){  
								$id = $redak["id"];
								$naziv = $redak["rola"];								
								echo '<option value="' . $id . '"';	
									if ($id == $rola) 
									{
									echo " selected";
									}																			
									echo ">$naziv</option>";					
									}
						?>
				</select>
				<?php echo $rolaErr;?>
			</div>	
			
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Email:</label>	
				<input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo $email;?>" class='m-1 form-control'>
				<?php echo $emailErr;?>
			</div>
			
			
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Aktivnost:</label>
				<select name="aktivan" id="aktivnost" class='m-1 form-control'>
					<option <?php if ($aktivan == 1 ) echo 'selected' ; ?> value="1">aktivan</option>
					<option class="text-danger;" <?php if ($aktivan == 2 ) echo 'selected' ; ?> value="2">neaktivan</option>
				</select>	
				<?php echo $aktivanErr;?>
			</div>				
			
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Lozinka:</label> 
				<input type="text" name="lozinka1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="min: 8 znakova / 1 broj + 1 malo + 1 veliko slovo" placeholder="min 8 zankova od toga: 1 broj + 1 malo + 1 veliko slovo " value="<?php echo $lozinka;?>" autocomplete="off" class='m-1 form-control'>
				<?php echo $lozinkaErr;?>
			</div>

			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Nadimak:</label>					
				<INPUT TYPE="text" name="nadimak" value="<?php echo $nadimak;?>" class='m-1 form-control'>	
				<?php echo $nadimakErr;?>
			</div>
			
			<div class="col-lg-3 col-md-4">
				<label class='m-1'>Trener:</label>
				<input type="checkbox" name="trener" <?php if($trener) echo 'checked'; ?> class='form-check-input d-block'/>
				<?php echo $trenerErr; ?>
			</div>
			
			<div class='col-lg-12 col-md-12'>
				<label class='m-1'>Opis:</label>
				<textarea id="editor" name="opis" ><?php echo $opis ?></textarea>
			</div>
					
					
			<?php echo $poruka; ?>

		</div>				
	</form>

</div>	
<script>
CKEDITOR.replace('editor', {
	filebrowserUploadUrl: '../ckeditor/ck_upload.php',
	filebrowserUploadMethod: 'form'
}); 
</script> 	
<?php 
include("podnozje.php");
?>



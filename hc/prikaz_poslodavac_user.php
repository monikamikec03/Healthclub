<?php	 		
session_start(); 
	
	if(in_array($_SESSION['idRole'], [15588]))
	{	
	require("../include/var.php");			
	require("../include/putanja.php");	
	} else {
		header("Location:odjava.php"); 
	}
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	
$slika_usera = '';
//Učitavanje slike usera
if ($_SERVER["REQUEST_METHOD"] == "POST" AND $_POST["spremi_sliku"] == "Spremi sliku usera") {

	//validacija naslovne slike
	if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"] ["size"] != 0){	
		if (empty($_POST["id_user"])){ 				
			$idErr = "";
			$id = '';
		}
		else {
			$id = test_input($_POST['id_user']);		
			if (!preg_match("/^[0-9 ]*$/",$id)){
			$idErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		} 
		$uploadOk = 1;
		$target_dir = "../slike/";
		$target_file = $target_dir . basename($_FILES["slika"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);				
		
		if ($_FILES["slika"]["size"] > 1000000){
			$porukaErr .= "Slika je prevelike rezolucije, morate je smanjiti.<br>";
			$uploadOk = 0;
		}else{  
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "bmp" && $imageFileType != "BMP" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF" && $imageFileType != "svg" && $imageFileType != "SVG" ) {
				$porukaErr .= "Dokument za spremanje mora biti format: JPG, JPEG, PNG, GIF, BMP, SVG<br>";
				$uploadOk = 0;
			}else{	
				$uploadOk = 1;
				$vrijeme = date("d.m.Y.-H:i:s");
				$putanja = "../slike/{$vrijeme}_{$_FILES["slika"]["name"]}"; 			 
			}
		}
	}
	else{
		$porukaErr .= "Niste unijeli naslovnu sliku.<br>";
	}
	//kraj validacije naslovne slike
	
	if($uploadOk){
		if (move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja)){
			$sql_slika = "UPDATE poslodavac_user SET slika_usera = '$putanja' WHERE id_user = $id";
			if(!mysqli_query($veza, $sql_slika)){
				$porukaErr = "Slika nije unesena.<br>";
				echo mysqli_error($veza);
			}
		}
		else $porukaErr .= "Nije dobra mapa za spremanje slike.<br>";	
	}
	else $porukaErr .= "Učitavanje slike nije uspjelo.<br>";
}


//Učitavanje slike potpisa
if ($_SERVER["REQUEST_METHOD"] == "POST" AND $_POST["spremi_sliku"] == "Spremi sliku potpisa") {

	//validacija naslovne slike
	if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"] ["size"] != 0){	
		if (empty($_POST["id_user"])){ 				
			$idErr = "";
			$id = '';
		}
		else {
			$id = test_input($_POST['id_user']);		
			if (!preg_match("/^[0-9 ]*$/",$id)){
			$idErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		} 
		$uploadOk = 1;
		$target_dir = "../slike/";
		$target_file = $target_dir . basename($_FILES["slika"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);				
		
		if ($_FILES["slika"]["size"] > 1000000){
			$porukaErr .= "Slika je prevelike rezolucije, morate je smanjiti.<br>";
			$uploadOk = 0;
		}else{  
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "bmp" && $imageFileType != "BMP" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF" && $imageFileType != "svg" && $imageFileType != "SVG" ) {
				$porukaErr .= "Dokument za spremanje mora biti format: JPG, JPEG, PNG, GIF, BMP, SVG<br>";
				$uploadOk = 0;
			}else{	
				$uploadOk = 1;
				$vrijeme = date("d.m.Y.-H:i:s");
				$putanja = "../slike/{$vrijeme}_{$_FILES["slika"]["name"]}"; 			 
			}
		}
	}
	else{
		$porukaErr .= "Niste unijeli naslovnu sliku.<br>";
	}
	//kraj validacije naslovne slike
	
	if($uploadOk){
		if (move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja)){
			$sql_slika = "UPDATE poslodavac_user SET slika_potpisa = '$putanja' WHERE id_user = $id AND poslodavac_oib = " . $_SESSION['poslodavac_oib'];
			if(!mysqli_query($veza, $sql_slika)){
				$porukaErr = "Slika nije unesena.<br>";
				echo mysqli_error($veza);
			}
		}
		else $porukaErr .= "Nije dobra mapa za spremanje slike.<br>";	
	}
	else $porukaErr = "Učitavanje slike nije uspjelo.<br>";
}			
		
		
	if (!empty($_GET["id_user"])){  					
		$id = (int)$_GET['id_user'];
		if (!preg_match("/^[0-9 ]*$/",$id))		
		{
			header("Location:../poduzetnik/odjava.php"); 
		}
	}	
	
	if($id <= 0) {
		header("Location:rad_prikaz_poslodavac_tvrtka.php");	
	}
	else
	{
		$id = (int)$id;		
		$sql = "SELECT * FROM poslodavac_user, role
		WHERE poslodavac_user.role_id = role.id 
		AND id_user = $id";	
		$rezultat = mysqli_query($veza, $sql);
		if(mysqli_num_rows($rezultat) == 1)
		{
			$red = mysqli_fetch_assoc($rezultat);	   
			$id = $red['id_user'];			
			$puno_ime = $red['puno_ime_poslodavac_user']; 
			
			$lozinka = $red['lozinka']; 			
		
			
			$rola = $red['rola'];
						
			
			$email = $red['email_poslodavac_user'];		
			$aktivan_user = $red['aktivan_user'];
			$nadimak = $red['nadimak'];
			$slika_usera = $red['slika_usera'];
			
			if ($aktivan_user == 1){
				$aktivan = "aktivan";	
			}else if ($aktivan_user == 2){
				$aktivan = "neaktivan";	 
			}			
		} 
		else
		{					
			header("Location:index.php");
		}
	}
	
	require("navigacija.php"); 	
	
	?>
<div id="sadrzaj">	
	
	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Prikaz poslodavca usera :<i class='text-secondary'> <?php echo $naziv; ?></i></h2>
		
		<div class='d-flex flex-wrap justify-content-end align-items-center'>
		
			<a  class='btn btn-primary m-1' href='unos_poduzetnik_user.php?id_user=<?php echo $id; ?>'><i class='fas fa-edit'></i> Uredi </a> 	
			<a class='btn btn-warning m-1' href='pregled_usera.php'>🡸 Natrag</a>		
		</div>			

	</div>
	<div class='row'>
		<div class="col-md-4">
			<label>Puno ime:</label>
			<p class="text-secondary font-weight-bold form-control bg-light"><?php echo $puno_ime; ?></p>					
		</div>

		<div class="col-md-4">
			<label>Razina prava: </label>								
			<p class="text-secondary font-weight-bold form-control bg-light"><?php echo $rola; ?></p>	
		</div>
		
		<div class="col-md-4">
			<label>Email: </label>								
			<p class="text-secondary font-weight-bold form-control bg-light"><?php echo $email; ?></p>	
		</div>	
		
		<div class="col-md-4">
			<label>Aktivnost: </label>								
			<p class="text-secondary font-weight-bold form-control bg-light"><?php echo $aktivan; ?></p>	
		</div>

		<div class="col-md-4">
			<label>Nadimak: </label>								
			<p class="text-secondary font-weight-bold form-control bg-light"><?php echo $nadimak; ?></p>	
		</div>

	

		<div class="col-md-4">
	
			<label class=''>Trener</label>
			<input type="checkbox"  class="form-check-input d-block bg-light" <?php echo $trener ?> onclick="return false;">		
	
		</div>
		
	</div>
	<div class='row'>
		<div class="col-md-4">
			<label>Slika usera: </label>
			<?php
			if(empty($slika_usera)){
				?>
				<form action="" method="post" class='bg-white shadow-none' enctype="multipart/form-data">
					<input type='hidden' name='id_user' value='<?php echo $id; ?>'>
					<input type="file" class="border-0" name="slika"><br>
					<small class="d-block p-1">Format slike mora biti kvadrat</small>
					<input type="submit" class="btn btn-primary m-1" name="spremi_sliku" value="Spremi sliku usera">
					<?php if(!empty($porukaErr)) echo "<p class='alert alert-danger'>$porukaErr</p>"; ?>
				</form>
				<?php
			}
			else{
				?>
				<div>
					<img src="<?php echo $slika_usera; ?>">
					<input type='hidden' id='id_user' value="<?php echo $id; ?>">
					<?php echo $idErr; ?>
					<p class='link-primary cursor-pointer fw-bold p-2' id='delete1'><span class="display-mobile">Obriši sliku </span><i class="fas fa-minus-circle"></i></p>
					<p class='text-danger'><?php echo $porukaErr; ?></p>
				</div>
				<?php
			} 
			?>
		</div>	
		<div class="col-md-8">
			<label>Opis:</label>
			<p class="text-secondary font-weight-bold form-control"><?php echo $opis; ?></p>	
		</div>		
		
	</div>

</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script> 
<script>									
$(document).ready(function(){
	$("#aktivnost").change(function(){
	 var selectaktivan = $('#aktivnost option:selected').text(); 
		alert (selectaktivan); 
	});			 
});
		
$("#delete1").click(function() {
	
	if (confirm('Potvrdite da želite obrisati sliku usera?')) {
		id = $("#id_user").val();
		console.log(id);
		
		$.ajax({
			type: "POST",
			url: "dropdown.php",
			data: {
				
				obrisi_sliku_usera: 1,
				id_user: id,
			},
			cache: false,
			success: function(data) {
				
				window.location.href='prikaz_poslodavac_user.php?id_user=' + id;
				
			}
		});
    } 

  
});


</script>		
</body>
</html> 
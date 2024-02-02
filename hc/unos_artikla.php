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
	
	$naziv_artiklaErr = $aktivnoErr = "";
	$poruka = $naziv_artikla = $aktivno = "";	
				
	if(isset($_POST['submit'])){
		
		if (empty($_POST["naziv_artikla"])) {
			$naziv_artiklaErr = "<p class='text-danger'>* morate popuniti polje</p>";
		} 
		else {
			$naziv_artikla = test_input($_POST["naziv_artikla"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$naziv_artikla)){
				$naziv_artiklaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}
		
		if (empty($_POST["jedinica_mjere"])) {
			$jedinica_mjereErr = "<p class='text-danger'>Morate popuniti polje.</p>";
		} 
		else {
			$jedinica_mjere = test_input($_POST["jedinica_mjere"]);
			if (!preg_match("/^[0-9]*$/",$jedinica_mjere)){
				$jedinica_mjereErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
			}
		}
		if (empty($_POST["redoslijed"])) {
			$redoslijedErr = "<p class='text-danger'>Morate popuniti polje.</p>";
		} 
		else {
			$redoslijed = test_input($_POST["redoslijed"]);
			if (!preg_match("/^[0-9]*$/",$redoslijed)){
				$redoslijedErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
			}
		}
	
		
		if(isset($_POST["aktivno"])) $aktivno = 1;
		else $aktivno = 0;
		if (!preg_match("/^[0-9]*$/",$aktivno)) $aktivnoErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 	
		
		
		if(empty($naziv_artiklaErr) && empty($aktivnoErr) && empty($jedinica_mjereErr) && empty($redoslijedErr)){										
			if(isset($_POST["id_artikla"])){				
				$id = ($_POST['id_artikla']);
				$sql = ("UPDATE artikli_popis SET naziv_artikla = '".$naziv_artikla."', jed_mjere = '".$jedinica_mjere."', aktivan_artikl = '".$aktivno."', redoslijed = '".$redoslijed."' WHERE id_artikla = $id");
			}
			else {		
				$sql ="INSERT INTO `artikli_popis` (`naziv_artikla`, `jed_mjere`, `aktivan_artikl`, `redoslijed`, `unio`)
				VALUES ('".$naziv_artikla."', '".$jedinica_mjere."', '".$aktivno."', '".$redoslijed."', '".$_SESSION['idKorisnika']."')"; 
			}					 			
			if (mysqli_query($veza, $sql)){
				header("Location: popis_artikala.php");  
			}
			else{
				$poruka = "<p class='text-danger'>unos /izmjena nije uspjela.</p>" . mysqli_error($veza);
			}				
		}	
		else{
			$poruka = "<p class='text-danger'>Niste ispravno unijeli sva polja.</p>";
		}
		
	}	
	if (isset($_GET["id_artikla"]))         //prikaz podataka za postoječi izostanak
	{
		$id = ($_GET['id_artikla']);	
		$sql =("SELECT * FROM artikli_popis WHERE id_artikla = $id ");	
			$rezultat = mysqli_query($veza, $sql);
			if($red = mysqli_fetch_array($rezultat))
				{
				$naslovStranice = "Izmjena artikla";
				$id = $red['id_artikla'];	
				$jedinica_mjere = $red['jed_mjere'];
				$naziv_artikla = $red['naziv_artikla'];		
				$redoslijed = $red['redoslijed'];		
				$aktivno = $red['aktivan_artikl'];
				if($aktivno == 1){
					$aktivno = 'checked';
				}
				else{
					$aktivno = '';
				}
				$linkZaPovratak = "popis_artikala.php";  			

				}	
	}
	else if (!isset($_POST["id_artikla"]))   //novi izostanak 
	{
		$naslovStranice = "Unos artikla";
		$naziv_artikla = "";	
		$linkZaPovratak = "popis_artikala.php";  		
	}	
require("zaglavlje.php"); 	
?> 		
	
<div id="sadrzaj">	
	<form method='post' action=''>
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2><?php echo $naslovStranice; ?></h2>
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi">
				<a class='btn btn-danger text-white m-1' href='<?php echo $linkZaPovratak; ?>'>✖ Odustani</a> 		
			</div>			
		</div>
		<?php							
		if (isset($id)){
			echo "<input type='hidden' name='id_artikla' value='$id'>";
		}
		?>		
		<div class='row'>
			<div class='col-md-3'>
				<label class='m-1'>* Naziv artikla:</label>
				<INPUT class='form-control m-1' type="text" name="naziv_artikla" value="<?php echo $naziv_artikla ?>">	
				<span class="text-danger d-block"><?php echo $naziv_artiklaErr;?></span>
			</div>
			<div class='col-md-3'>
				<label class='m-1'>* Jedinica mjere:</label>
				<select class='form-control m-1' name="jedinica_mjere">
					<option disabled selected>Odaberi jedinicu mjere</option>
					<?php
					$sql = "SELECT * FROM jedinica_mjere WHERE aktivan = 1 ";
					$res = mysqli_query($veza, $sql);
					while($red = mysqli_fetch_array($res)){
						$id_jed_mjere = $red['id_jed_mjere'];
						$naziv_jed_mjere = $red['naziv_jed_mjere'];
						?>
						<option value="<?php echo $id_jed_mjere; ?>" <?php if($jedinica_mjere == $id_jed_mjere) echo "selected"; ?>><?php echo $naziv_jed_mjere; ?></option>
						<?php
					}
					?>
				</select>
				<span class="text-danger d-block"><?php echo $jedinica_mjereErr;?></span>
			</div>
			<div class='col-md-3'>
				<label class='m-1'>* Redoslijed:</label>
				<INPUT class='form-control m-1' type="text" name="redoslijed" value="<?php echo $redoslijed ?>">	
				<span class="text-danger d-block"><?php echo $redoslijedErr;?></span>
			</div>
			<div class='col-md-3'>
				<label class='m-1'>* Aktivno:</label><br>
				<input type='checkbox' name='aktivno' class="form-check-input" <?php echo $aktivno; ?>>
				<span class="text-danger d-block"><?php echo $aktivnoErr;?></span>
			</div>
		</div>
		<?php echo $poruka; ?>				
	</form>
</div>
</body>
</html> 	


	
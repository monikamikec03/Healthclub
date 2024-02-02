<?php	
ob_start();
session_start();
$list = 'kategorije';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
	require("../include/putanja.php");	
	require("navigacija.php");
}
else{
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');
		
	$nazivErr = $kategorijaErr = $redoslijedErr = "";  
	$naziv = $kategorija = $redoslijed = $id_kategorija = $objavljen = ""; 
	
		if(isset($_POST['submit']))
		{
			if (empty($_POST["naziv"])) {
				$nazivErr = "<p class='alert alert-danger'>* morate popuniti polje</p>";
				} 
				else 
				{
					$naziv = test_input($_POST["naziv"]);
					if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$naziv)) 					
					{
					$nazivErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
					}
				}	 

			if (empty($_POST["redoslijed"])) {
				$redoslijedErr = "<p class='alert alert-danger'>* morate popuniti polje</p>";
				} 
				else 
				{
					$redoslijed = test_input($_POST["redoslijed"]);
					if (!preg_match("/^[0-9 ]*$/",$redoslijed)) 					
					{
					$redoslijedErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</P>"; 
					}
				}
				
				
			if(isset($_POST["objavljen"]))
				{
					$objavljen = 1;
					if (!preg_match("/^[0-9 ]*$/",$objavljen)) 					
					{
					$objavljenErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
					}					
				}
				else
				{
					$objavljen = 0;
					if (!preg_match("/^[0-9 ]*$/",$objavljen)) 					
					{
					$objavljenErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
					}					
				} 			
			
	if(empty ($nazivErr) AND empty ($redoslijedErr) AND empty ($objavljenErr))
	{
		if(isset($_POST["id_kategorije"]))   // ako ima "id" onda je izmjena 	
			{				
			$id = ($_POST['id_kategorije']);				
			$sql = ("UPDATE `kategorije` SET naziv_kategorije = '".$naziv."', redoslijed = '".$redoslijed."', aktivna_kategorija = '".$objavljen."' WHERE id_kategorije = $id ");
			}			
			else 						// unos novog 
			{					
			$sql = "INSERT INTO kategorije (naziv_kategorije, redoslijed, aktivna_kategorija) 
					VALUES ('$naziv', '$redoslijed', '$objavljen')"; 	   		
			}			
				if (mysqli_query($veza, $sql))
				{
				header("Location:kategorije.php");					
				}
				else
				{
					$poruka = "<p class='alert alert-danger'>unos /izmjena članaka nije uspjela.</p>";
				}	
				
	}
}	
	if (isset($_GET["id"]))         //prikaz podataka za postoječi izostanak
	{
	$id = ($_GET['id']);	
	$sql = "SELECT * FROM kategorije 
	WHERE id_kategorije = $id";	 	
		$rezultat = mysqli_query($veza, $sql); 
		if($red = mysqli_fetch_array($rezultat))
			{
			
			$id_kategorija = $red['kategorije_id'];
			$id_kategorije = $red['id_kategorije']; 						
			$naziv = $red['naziv_kategorije']; 
			$redoslijed = $red['redoslijed']; 

			$naslovStranice = "Izmjena kategorije: " . $naziv;
			$linkZaPovratak = "kategorije.php";
				if ($red['aktivna_kategorija'] == 1)
				{
						$objavljen = "checked";			
					}	
					else
					{
						$objavljen = "";
					}
			}				
	}
	else if (!isset($_POST["id_kategorije"]))   //novi izostanak   
	{
		$naslovStranice = "Unos kategorije";
		$linkZaPovratak = "kategorije.php";		
	}	
?>	  

<div id="sadrzaj">
		<form action="" method="POST">
		<?php							// sakriva polje id ako je izmjena clanka
			if (isset($id))
			{
			echo "<input type='hidden' name='id_kategorije' value='$id_kategorije'>";
			}
		?>
		<div class="d-flex justify-content-between flex-wrap align-items-center">
			<h2><?php echo $naslovStranice;?></h2>
	
			<div class='d-flex flex-wrap'>	
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi"> 		
		
				<a class='btn btn-danger m-1' href="<?php echo $linkZaPovratak; ?>">✖ Odustani</a>			
			</div>
		</div>
		
		
	

		<div class='row py-3 d-flex align-items-center'>		
			<div class="col-md-3">			
				<label class="m-1">* Kategorija:</label>					
				<INPUT class="form-control m-1" type="text" name="naziv" value="<?php  echo $naziv;?>">
				<?php echo $nazivErr;?>
			</div>  

			
			<div class="col-md-3">
				<label class="m-1">* Redoslijed:</label>					
				<INPUT class="form-control m-1" type="text" name="redoslijed" value="<?php  echo $redoslijed;?>">
				<?php echo $redoslijedErr;?>
			</div>	
			
			<div class='col-md-3'>
				
				<label class="m-1">Aktivno:</label>
				<input type="checkbox" class='form-check-input mx-3' name="objavljen" <?php echo $objavljen ?>/>
				<?php echo $objavljenErr;?>
			</div>		
		</div>
		<?php echo $poruka; ?>
		</form>	
</div>
</body>
</html>

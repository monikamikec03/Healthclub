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
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
		
	$nazivErr = "";
	$poruka = $naziv = "";	
	

		if(isset($_GET["id"])) 
		{	
		$id = (int)($_GET["id"]);
		$sql = "SELECT*FROM aktivnosti
		WHERE id_aktivnost = $id";
			$rezultat = mysqli_query($veza, $sql); 
			if($red = mysqli_fetch_array($rezultat))
				{
				$korisnik_id = $red['korisnik_id'];				 			
				}	
		}		
		
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{		
		if (empty($_POST["naziv"])) {
			$nazivErr = "<p class='alert alert-danger'>* morate popuniti polje</p>";
			} 
			else 
			{
			$naziv = test_input($_POST["naziv"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!.\/\-\_\,:; ]*$/",$naziv)) 
				{
				$nazivErr = "<p class='alert alert-danger'>* specijalni znakovi neĆe biti spremljeni u bazu</p>"; 
				}
			}	


		if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"] ["size"] != 0) 
		{				
			$target_dir = "../file/";
			$target_file = $target_dir . basename($_FILES["slika"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
			
			if ($_FILES["slika"]["size"] > 10000000) {
				$slikaErr = "<p class='alert alert-danger'>Dokument je prevelik, mora biti manji</p>";
			}			
			else
			{	
				$dan = date("d.m.Y_");
				$vrijeme_1 = (MD5(date("H:i:s")));
				$vrijeme = substr($vrijeme_1, 0, 10);
				$putanja = "../file/{$dan}{$vrijeme}.{$imageFileType}"; 
				
				if(!empty($nazivErr)) 
				{
				$nazivErr = "<p class='alert alert-danger'>* morate popuniti polje ili znakovi nisu dozvoljeni.</p>";
				}
				else
				{				
				
					$sql ="INSERT INTO file (`zadaci_id`, `korisnici_id`,`naziv_file`, `putanja`, `autor_id`)
					VALUES ('$id', '$korisnik_id', '$naziv', '$putanja', {$_SESSION['idKorisnika']});";
						
					if (mysqli_query($veza, $sql))	    		
					{
						if (!move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja))
						{
							$poruka = "<p class='alert alert-danger'>nije dobar direktori za spremanje dokumenta</p>";	
						}
					header("Location: prikaz_aktivnosti.php?id_aktivnost=" . $id );
					}
					else
					{
						$poruka = "<p class='alert alert-danger'>spremanje u bazu nije uspjelo</p>";  					
					}
				}
			}		
		}
		else{
			$slikaErr = "<p class='alert alert-danger'>Niste odabrali dokument.</p>";
		}
	}

		include('zaglavlje.php'); 		
?>



<div id="sadrzaj">	
	<form  class="form_unos" method="POST" action="" enctype="multipart/form-data">	
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Unos dokumenta</h2>
			
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" class='form-control my-1' value="✔ Spremi"> 
				<a class='btn btn-warning text-white m-1' href='prikaz_aktivnosti.php?id_aktivnost=<?php echo $id; ?>'>🡸 Natrag</a>		
			</div>			
		</div>
		
		<div class='row'>
			<div class='col-md-6'>
				<label class='my-1'>Naziv dokumenta: </label>
				<input  class="form-control my-1" type="text" name="naziv"  value="<?php echo $naziv; ?>">
				<?php echo $nazivErr;?>
			</div>
			<div class='col-md-6'>
				<span class='d-block my-1'>Dokument:</span>
				<label class="my-1 btn btn-secondary">
					<input type="file" name='slika' class='d-none' onChange='getoutput(this.value)'/>
					Učitaj dokument			
				</label>
				<span id='filename'></span>
				<?php echo $slikaErr; ?>
			</div>
		</div>	
		<?php echo $poruka; ?>
	</form>	
</div>		
<script>
function getoutput(val) {
	let array = val.split("\\");
	let putanja = array.slice(-1);
	let filename = putanja[0];
	document.querySelector('#filename').innerHTML = filename;
}
</script>
</body>
</html> 
<?php ob_end_flush(); ?>
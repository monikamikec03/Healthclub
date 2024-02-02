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
	
	$artikl_idErr = $aktivnoErr = "";
	$poruka = $artikl_id = $aktivno = "";	
				
	if(isset($_POST['submit'])){
		
		if (empty($_POST["artikl_id"])) {
			$artikl_idErr = "<p class='text-danger'>Morate popuniti polje</p>";
		} 
		else {
			$artikl_id = test_input($_POST["artikl_id"]);
			if (!preg_match("/^[0-9 ]*$/",$artikl_id)){
				$artikl_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}
		
	
		
		if (empty($_POST["cijena_iznos"])) {
			$cijena_iznosErr = "<p class='text-danger'>Morate popuniti polje.</p>";
		} 
		else {
			$cijena_iznos = test_input(str_replace(',', '.', $_POST["cijena_iznos"]));
			if (!preg_match("/^[0-9?!.\,:; ]*$/",$cijena_iznos)){
				$cijena_iznosErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
			}
		}
		
		if (empty($_POST["cijena_euro"])) {
			$cijena_euroErr = "<p class='text-danger'>Morate popuniti polje.</p>";
		} 
		else {
			$cijena_euro = test_input(str_replace(',', '.', $_POST["cijena_euro"]));
			if (!preg_match("/^[0-9?!.\,:; ]*$/",$cijena_euro)){
				$cijena_euro = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
			}
		}
		
		if(isset($_POST["aktivno"])) $aktivno = 1;
		else $aktivno = 0;
		if (!preg_match("/^[0-9]*$/",$aktivno)) $aktivnoErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 	
		
		
		if(empty($artikl_idErr) && empty($aktivnoErr) && empty($cijena_iznosErr) && empty($cijena_euroErr)){										
			if(isset($_POST["id_cijena"])){				
				$id = ($_POST['id_cijena']);
				$sql = ("UPDATE artikli_cijene SET artikl_id = '".$artikl_id."', cijena_iznos = '".$cijena_iznos."', cijena_euro = '".$cijena_euro."', cijena_aktivna = '".$aktivno."', kreirao_cijenu = '".$_SESSION['idKorisnika']."' WHERE id_cijena = $id ");
			}
			else {		
				$sql ="INSERT INTO `artikli_cijene` (`artikl_id`, `cijena_iznos`, `cijena_euro`, `cijena_aktivna`, `kreirao_cijenu`)
				VALUES ('".$artikl_id."', '".$cijena_iznos."', '".$cijena_euro."', '".$aktivno."', '".$_SESSION['idKorisnika']."')"; 
			}					 			
			if (mysqli_query($veza, $sql)){
				header("Location: cijene.php");  
			}
			else{
				$poruka = "<p class='text-danger'>unos /izmjena nije uspjela.</p>" . mysqli_error($veza);
			}				
		}	
		
	}	
	if (isset($_GET["id_cijena"]))         //prikaz podataka za postoječi izostanak
	{
		$id = ($_GET['id_cijena']);	
		$sql =("SELECT * FROM artikli_cijene WHERE id_cijena = $id ");	
			$rezultat = mysqli_query($veza, $sql);
			if($red = mysqli_fetch_array($rezultat))
				{
				$naslovStranice = "Izmjena cijene";
				$id = $red['id_cijena'];	
				$cijena_iznos = $red['cijena_iznos'];
				$cijena_euro = $red['cijena_euro'];
				$artikl_id = $red['artikl_id'];		
				$aktivno = $red['cijena_aktivna'];
				if($aktivno == 1){
					$aktivno = 'checked';
				}
				else{
					$aktivno = '';
				}
				$linkZaPovratak = "cijene.php";  			

				}	
	}
	else if (!isset($_POST["id_cijena"]))   //novi izostanak 
	{
		$naslovStranice = "Unos cijene";
		$artikl_id = "";	
		$linkZaPovratak = "cijene.php";  		
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
			echo "<input type='hidden' name='id_cijena' value='$id'>";
		}
		?>		
		<div class='row'>
			<div class='col-md-3'>
				<label class='m-1'>* Odaberi artikl:</label>
				<select class='form-control m-1' name="artikl_id">
					<option disabled selected>Odaberite artikl</option>
					<?php
					$sql = "SELECT * FROM artikli_popis WHERE aktivan_artikl = 1";
					$res = mysqli_query($veza, $sql);
					while($red = mysqli_fetch_array($res)){
						$id_artikla = $red['id_artikla'];
						$naziv_artikla = $red['naziv_artikla'];
						?>
						<option value="<?php echo $id_artikla; ?>" <?php if($artikl_id == $id_artikla) echo "selected"; ?>><?php echo $naziv_artikla; ?></option>
						<?php
					}
					?>
				</select>
				<span class="text-danger d-block"><?php echo $artikl_idErr;?></span>
			</div>
			
			<div class='col-md-3'>
				<label class='m-1'>* Cijena:</label>
				<INPUT class='form-control m-1' type="text" name="cijena_iznos"  data-type="currency" value="<?php echo $cijena_iznos ?>">	
				<span class="text-danger d-block"><?php echo $cijena_iznosErr;?></span>
			</div>
			
			<div class='col-md-3'>
				<label class='m-1'>* Cijena (€):</label>
				<INPUT class='form-control m-1' type="text" name="cijena_euro"  data-type="currency" value="<?php echo $cijena_euro ?>">	
				<span class="text-danger d-block"><?php echo $cijena_euroErr;?></span>
			</div>
			
			<div class='col-md-3'>
				<label class='m-1'>* Aktivno:</label>
				<input type='checkbox' name='aktivno' class='d-block form-check-input mx-4 my-2' <?php echo $aktivno; ?>>
				<span class="text-danger d-block"><?php echo $aktivnoErr;?></span>
			</div>
		</div>
		<?php echo $poruka; ?>				
	</form>
</div>
</body>
</html> 	


	
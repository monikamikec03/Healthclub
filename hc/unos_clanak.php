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
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');		

	$kategorijaErr = $rubrikaErr = $autorErr = $tip_tekstaErr = $naslovErr = $uvodErr = $tekstErr = $datum_objaveErr =$objavljenErr = $istaknutoErr = $omoguci_komentareErr = ""; 
	$rubrika = $kategorija = $autor = $tip_teksta = $naslov = $uvod = $tekst = $datum_objave = $istaknuto = $omoguci_komentare = $objavljen = "";			
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){	 
	
			if (empty($_POST["kategorija"])) {
				$kategorijaErr = "<p class='text-danger'>* morate popuniti polje</p>";
				} 
				else 
				{
					$kategorija = test_input($_POST["kategorija"]);
					if (!preg_match("/^[0-9]*$/",$kategorija)) 
					{
					$kategorijaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
					}
				}	
	
		
			$autor = test_input($_POST["autor"]);
			if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!.,:; ]*$/",$autor)) 
			{
			$autorErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
			
			$oznake = $_POST["oznake"];
			echo $oznake[0];
			
				

			if(isset($_REQUEST["tip_teksta"]))

			$tip_teksta = ($_REQUEST["tip_teksta"]);

			
			if (!preg_match("/^[0-9]*$/",$tip_teksta)) 
			{
			$tip_tekstaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}				
			
			if (empty($_POST["naslov"])) {
				$naslovErr = "";
			} 
			else 
			{
				$naslov = test_input($_POST["naslov"]);
				if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$naslov))  
				{
				$naslovErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
				
				}
			}
		
			$uvod = test_input($_POST["uvod"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$uvod)) 
			{
			$uvodErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
				
				
		if(isset($_REQUEST["objavljen"])){
			$objavljen = 1;
		}
		else
		{
			$objavljen = 0;
		} 
		if (!preg_match("/^[0-9]*$/",$objavljen)) 
		{
			$objavljenErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}				
			
			
		if(isset($_REQUEST["istaknuto"]))
			{
				$istaknuto = 1;
			}
			else
			{
				$istaknuto = 0;
			} 
			if (!preg_match("/^[0-9]*$/",$istaknuto)) 
			{
			$istaknutoErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}					
			
		if(isset($_REQUEST["omoguci_komentare"]))
			{
				$omoguci_komentare = 1;
			}
			else
			{
				$omoguci_komentare = 0;
			} 
			if (!preg_match("/^[0-9]*$/",$omoguci_komentare)) 
			{
			$omoguci_komentareErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}				
			
		$tekst = htmlentities($_POST["tekst"]);	
				

			if (empty($_REQUEST["datum_objave"])) {
			$datum_objaveErr = "<p class='text-danger'>* morate popuniti polje</p>";
			} else {
					$datum_objave1 = test_input($_POST["datum_objave"]);
					$datum_objave = date("d.m.Y",strtotime($datum_objave1));
					$datum_objave2 = date("Y-m-d",strtotime($datum_objave1));					
					if (!preg_match("/\d{2}.\d{2}.\d{4}$/",$datum_objave1))   
					{
					$datum_objaveErr = "<p class='text-danger'>* neispravni znakovi</p>"; 
					}
		}				
				
	if(empty ($kategorijaErr) AND empty ($autorErr) AND empty ($tip_tekstaErr) AND empty ($naslovErr) AND empty ($uvodErr) AND empty ($objavljenErr) AND empty ($datum_objaveErr) AND empty ($istaknutoErr) AND empty ($omoguci_komentareErr))
		{								
		if(isset($_POST["id"]))   // ako ima "id" onda je izmjena članka	
			{				
			$id = ($_POST['id']);			
			$sql = "UPDATE clanak SET kategorije_id = '".$kategorija."' , unos_id = {$_SESSION['idKorisnika']}, naslov_clanka = '".$naslov."', uvod = '".$uvod."', tekst = '".$tekst."',  datum_objave = '".$datum_objave2."', ime_autora = '".$autor."', tip_teksta = '".$tip_teksta."', objavljen = '".$objavljen."', istaknuto = '".$istaknuto."', omoguci_komentare = '".$omoguci_komentare."' WHERE id_clanak = $id";
			} 
			else 						// unos novog članka
			{
			$sql = "INSERT INTO clanak (kategorije_id, unos_id, naslov_clanka, ime_autora, tip_teksta, uvod, tekst, datum_objave, objavljen, istaknuto, omoguci_komentare) 
			VALUES ( '$kategorija', {$_SESSION['idKorisnika']}, '$naslov', '$autor', '$tip_teksta', '$uvod', '$tekst', '$datum_objave2', '$objavljen', '$istaknuto', '$omoguci_komentare')";		
			}
			
				if (mysqli_query($veza, $sql))   
				{
					if(empty($id)){
						header("Location:clanci.php");
					}
					else{
						header("location:clanak.php?id=$id");
					}					
				}
				else
				{				
					$poruka ="<p class='text-danger'>... unos /izmjena članaka nije uspjela.</p>" . mysqli_error($veza);
				}
		}
		else 
		{
			
			$poruka = '<p class="text-danger">* unos u bazu nije uspio, morate popuniti sva polja</p>';	
		}
	
}	
$oznake_clanka = [];
if (isset($_GET["id"]))         //prikaz podataka za postojeći članak
{
	$id = ($_GET['id']);	
	$sql = ("SELECT * FROM clanak 
	WHERE id_clanak = $id");
	$rezultat = mysqli_query($veza, $sql);
	if($redak = mysqli_fetch_array($rezultat)){
		$id_clanak = ($redak["id_clanak"]);			
		$naslovStranice = "Izmjena članka";					
		$linkZaPovratak = "clanak.php?id=$id_clanak";
		$kategorija = $redak["kategorije_id"];					
		$naslov = ($redak["naslov_clanka"]);
		$autor = ($redak["ime_autora"]);					
		$uvod = ($redak["uvod"]);
		$tekst = ($redak["tekst"]);
		$datum_objave = date("d.m.Y",strtotime($redak["datum_objave"]));
		
	
		
		$tip_teksta = ($redak["tip_teksta"]);					
		if ($redak["objavljen"] == 1){
			$objavljen = "checked";			
		}	
		else{
			$objavljen = "";
		}
		
		if ($redak["omoguci_komentare"] == 1){
			$omoguci_komentare = "checked";			
		}	
		else{
			$omoguci_komentare = "";
		}
		if ($redak["istaknuto"] == 1){
			$istaknuto = "checked";			
		}	
		else{
			$istaknuto = "";
		}					
	}
	
	$sql = "SELECT * FROM pridruzene_oznake WHERE clanak_id = $id";
	$res = mysqli_query($veza, $sql);
	while($red = mysqli_fetch_array($res)){
		$oznake_clanka[] = $red["oznaka_id"];
	}
	
}

else if (!isset($_POST["id"]))   //novi članak
{
	$naslovStranice = "Unos članka";
	$linkZaPovratak = "clanci.php"; 		
}	
?>

<div id="sadrzaj">
			
	<form method="POST" action="">    
		<?php					
		if (isset($id)){
			echo "<input type='hidden' name='id' value='$id_clanak'>";
		}
		?>			
		<div class="d-flex justify-content-between flex-md-row flex-sm-column flex-column flex-wrap align-items-center">
			<h2><?php echo $naslovStranice;?></h2>
	
			<div class='d-flex flex-md-row flex-sm-column flex-column'>	
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi"> 		
		
				<a class='btn btn-danger m-1' href="<?php echo $linkZaPovratak; ?>">✖ Odustani</a>			
			</div>
		</div>
		
				
				
				
						 			
				<div class='row'>
					<?php echo $poruka; ?>
					<div class='col-lg-4 col-md-6'>
						<label class='m-1'>Kategorija:</label>
						<?php
						$sql =("SELECT * FROM kategorije 
						WHERE aktivna_kategorija = 1
						ORDER BY redoslijed");
						?>
						<select name="kategorija" class='form-control m-1'>		 
							<?php						
							$result = mysqli_query($veza, $sql);
							while($redak=mysqli_fetch_assoc($result)) 
							{
							$id = $redak["id_kategorije"];
							$naziv = $redak["naziv_kategorije"];	
							echo '<option value="' . $redak["id_kategorije"] . '"';	
							if ($id == $kategorija)
								{
								echo " selected";  
								}
								echo ">$naziv</option>";				
							} ?>				 
						</select>
						<?php echo $kategorijaErr;?>
					</div>	
					
					
					
					<div class='col-lg-4 col-md-6'>
						<label class='m-1'>Članak / Objava / Citat:</label>
						<select name="tip_teksta" class='form-control m-1'>		 
							<?php	
							$sql = "SELECT * FROM tip_teksta";							
							$result = mysqli_query($veza, $sql);
							while($redak=mysqli_fetch_assoc($result)) 
							{
							$id_tipa = $redak["id_tipa"];
							$naziv_tipa = $redak["naziv_tipa"];	
							echo '<option value="' . $redak["id_tipa"] . '"';	
							if ($id_tipa == $tip_teksta)
								{
								echo " selected";  
								}
								echo ">$naziv_tipa</option>";				
							} ?>				 
						</select>
						<?php echo $tip_tekstaErr;?>
					</div>
					<div class='col-lg-4 col-md-6'>
						<label class='m-1'>Autor:</label>
						<input type="text" name="autor" maxlength="90" value="<?php echo $autor ?>" class='form-control m-1'>
						<?php echo $autorErr;?>
					</div>	
					
				
				
				
				<div class='col-lg-8 col-md-12'>
					<div>
						<label class='m-1'>Naslov:</label>
						<input type="text" name="naslov" maxlength="200" value="<?php echo $naslov ?>" class='form-control m-1'>
						<?php echo $naslovErr;?>
					</div>
					
					<div>
						<label class='m-1'>Uvod:</label>
						<input type="text" name="uvod" maxlength="400" value="<?php echo $uvod ?>" class='form-control m-1'>
						<?php echo $uvodErr;?>
					</div>
					
					<div>
						<label class='m-1'>Datum objave:</label>
						<input type="text" id="datepicker" class="datum_start form-control m-1" name="datum_objave"	value="<?php echo $datum_objave; ?>" autocomplete="off">
						<?php echo $datum_objaveErr;?>
					</div>
					
					
					
							
	
				</div> 
				<div class='col-lg-4 col-md-6'>
					
				
					<div class='my-4'>
						<label class='m-1'>Omogući komentare:</label>
						<input type="checkbox" name="omoguci_komentare"	<?php echo $omoguci_komentare ?> class='form-check-input m-1 mx-4'/>
						<?php echo $omoguci_komentareErr; ?>
					</div>
					
					<div class='my-4'>
						<label class='m-1'>Istaknuto:</label>
						<input type="checkbox" name="istaknuto"	<?php echo $istaknuto; ?> class='form-check-input m-1 mx-4'/>
						<?php echo $istaknutoErr; ?>
					</div>
					
					<div class='my-4'>
						<label class='m-1'>Objavljen:</label>
						<input type="checkbox" name="objavljen"	<?php echo $objavljen; ?> class='form-check-input m-1 mx-4'/>
						<?php echo $objavljenErr; ?>
					</div>
					
					
				</div>
				
				
				<div>
					<label class="m-1">Oznake:</label>
					<div class="d-flex flex-wrap">
						<?php
						$sql = "SELECT * FROM oznake";
						$res = mysqli_query($veza, $sql);
						while($red = mysqli_fetch_array($res)){
							$id_oznake = $red["id_oznake"];
							$naziv_oznake = $red["naziv_oznake"];
							?>
							<div class="m-1">
								<input <?php if(empty($id_clanak)) echo "disabled"; ?> type="checkbox" class="btn-check" id="<?php echo $naziv_oznake; ?>" autocomplete="off" value="<?php echo $id_oznake; ?>" <?php if((count($oznake_clanka) > 0) && in_array($id_oznake, $oznake_clanka)) echo "checked"; else echo ''; ?>>
								<label class="btn btn-outline-success" for="<?php echo $naziv_oznake; ?>"><?php echo $naziv_oznake; ?></label><br>
							</div>
							<?php
						}
						if(empty($id_clanak)) echo "<small class='w-100 p-2 fw-bold'>Da biste odabrali oznake, morate prvo spremiti članak.</small>";
						?>
					</div>
				</div>
				
				
				<div class='col-lg-12 col-md-12'>
					<label class='m-1'>Sadržaj:</label>
					<textarea id="editor" name="tekst" ><?php echo $tekst ?></textarea>
				</div>	
			<div>

		</div>		
		<div style="clear:right"></div><br />
		</form>			
	</div> 
<script>
CKEDITOR.replace('editor', {
	filebrowserUploadUrl: '../ckeditor/ck_upload.php',
	filebrowserUploadMethod: 'form',
    disallowedContent: 'img{width,height};'	
}); 
</script> 

<script>
$(function(){
	$(".datum_start").datepicker();
});	

</script>
<script>
$(".btn-check").click(function(){
	let oznaka = $(this);
	let oznaka_id = $(this).val();
	let clanak_id = <?php echo $id_clanak; ?>;
	let naredba;
	
	if($(this).is(':checked')){
		naredba = "insert";
	}
	else{
		naredba = "delete";
	}
	
	
	$.ajax({
		url: "dropdown.php",
		type: "post",
		data: {
			oznaka_id:oznaka_id,
			clanak_id:clanak_id,
			naredba:naredba,
			
		},
		success: function (response) {
		
				oznaka.blur();
		
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
	
	
});

</script>		
<?php
include("podnozje.php");
?>
					
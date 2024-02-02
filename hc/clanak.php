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
	 echo "<script> window.location.replace('prijava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');	

	if (isset($_GET["id"]))   
	{								
		$id = ($_GET["id"]);						
			$sql = "SELECT*FROM clanak, kategorije, poslodavac_user, tip_teksta
				WHERE clanak.kategorije_id = id_kategorije
				AND id_user = unos_id
				AND id_tipa = tip_teksta
				AND  clanak.id_clanak = $id";     
				$rezultat = mysqli_query($veza, $sql);
				if ($redak = mysqli_fetch_array($rezultat))
				{
					$id_clanka = $redak["id_clanak"];  
					$naslov = stripSlashes($redak["naslov_clanka"]);				
					$naziv_kategorije = stripSlashes($redak["naziv_kategorije"]);				
					$naziv_tipa = stripSlashes($redak["naziv_tipa"]);				
					$uvod = stripSlashes($redak["uvod"]);
					$spremio = stripSlashes($redak["puno_ime_poslodavac_user"]);
					$tekst = html_entity_decode($redak["tekst"]);	
					$datum = date("j.n.Y.", strtotime($redak["datum_objave"]));
					$autor = stripSlashes($redak["ime_autora"]);
						if ($redak["objavljen"] == 1)
						{
							$objavljen = "checked";			
						}	
						else
						{
							$objavljen = "";
						}
					if ($redak["omoguci_komentare"] == 1)
					{
						$omoguci_komentare = "checked";			
					}	
					else
					{
						$omoguci_komentare = "";
					}
					if ($redak["istaknuto"] == 1)
					{
						$istaknuto = "checked";			
					}	
					else
					{
						$istaknuto = "";
					}
				}
	}

	$sql = ("SELECT * FROM slike_naslova WHERE clanak_id = $id_clanka "); 									
	$rezultat = mysqli_query($veza, $sql); 
	if (mysqli_num_rows($rezultat) == 0) 
	{ 	
		$putanja = '';
	}
	else{
		$sql = mysqli_query($veza, "SELECT * FROM slike_naslova
		WHERE clanak_id = $id_clanka ");       						
		if($red = mysqli_fetch_array($sql)) {
			$putanja = $red ['putanja'];
		}
	}
	
	
		
?>

<div id="sadrzaj">
	<div class='d-flex flex-wrap justify-content-between align-items-center mx-3'>
		<h2>Prikaz članka</h2>
		
		<div class='d-flex flex-wrap justify-content-end align-items-center'>
			<a  class='btn btn-primary m-1' href='unos_clanak.php?id=<?php echo $id ?>'><i class='fas fa-edit'></i> Uredi </a>
			<a  class='btn btn-danger m-1' href='brisanje_clanka.php?id=<?php echo $id ?>' onclick="return confirm('Jeste li sigurni da želite obrisati članak <?php echo $naslov; ?>?')"><i class='fas fa-trash'></i> Obriši </a>
			<a class='btn btn-warning m-1' href='clanci.php'>🡸 Natrag</a>		
		</div>			
	</div>		
	
		
	<div class='p-3 row justify-content-start'>	
		<div class='text-center col-lg-4 col-md-12 px-3'>
			<div class='bg-light shadow-sm'>
				<?php
				if(empty($putanja)){ 
				?>
				<a href='priprema_slike_naslova.php?id=<?php echo $id; ?>' class='p-4'>
					<i style='font-size:44px' class='pt-4 fa'>&#xf03e;</i>
					<h4 class='text-primary'> Unesi naslovnu sliku</h4>
				</a>
				<?php 
				}
				else{
					
				?>
				<div class='d-flex flex-column'>
					<a href='priprema_slike_naslova.php?id=<?php echo $id; ?>'>
						<div class='py-3'>
							<i style='font-size:44px' class='fa'>&#xf03e;</i>
							<h4>Promijeni naslovnu sliku</h4>
						</div>
					</a>
					<div>
						
						<img src='<?php echo $putanja; ?>'/>
						
					</div>
					
				</div>
				<?php
				}
				?>
				<div class="bg-light p-3 text-start">
					<?php
					$sql = "SELECT COUNT(id_lajka) AS broj_lajkova FROM lajkovi WHERE clanak_id = $id_clanka";
					$res = mysqli_query($veza, $sql);
					if($red = mysqli_fetch_array($res)){
						$broj_lajkova = $red["broj_lajkova"];
					}
					?>
					<h4 class='my-2'><i class="fa-solid fa-heart"></i> Broj lajkova: <b><?php echo $broj_lajkova; ?> </b></h4>
					<h4 class='mt-2'><i class="fa-solid fa-comments"></i> Komentari:</h3>
					<?php
					$sql = "SELECT * FROM komentari WHERE clanak_id = $id_clanka ORDER BY datum_unosa";
					$res = mysqli_query($veza, $sql);
					if(mysqli_num_rows($res) > 0){
						while($red = mysqli_fetch_array($res)){
							$id_komentara = $red["id_komentara"];
							$naziv = $red["naziv"];
							$komentar = $red["komentar"];
							$datum_komentara = date("d.m.Y h:i", strtotime($red["datum_unosa"]));
							echo "<div class=' my-3 py-2 border-top border-warning'>
							<div class='d-flex justify-content-between align-items-center flex-wrap'>
								<h6 class='flex-grow-1'>$naziv</h6>
								<small class='mx-4'>$datum_komentara</small>
								<a class='link-danger ms-auto my-2' href='brisanje_komentara.php?id_komentara=$id_komentara' onclick='return confirm(\"Potvrdite da želite obrisati ovaj komentar.\");'><i class=\"fs-3 fa-solid fa-square-minus\"></i></i></a>
							</div>
							<p>$komentar</p>
							</div>";
							
						}
					}
					else{
						echo "<p class='text-danger'><i>Nema komentara pod ovim člankom.</i></p>";
					}
					?>
				</div>
			</div>
		</div>
		
		<div class='col-lg-8 col-md-12 px-3'>
			<div class='bg-light p-3 shadow-sm h-100'>
				
				<h3 class='py-3 text-primary border-bottom'><?php echo $naslov; ?></h3>
					
				
				<div class='d-flex flex-wrap justify-content-start'>
					<div class='p-3 flex-shrink-1'>
						<p class='my-1'><i class='fs-5 fas text-success'>&#xf303;</i>  : <label><?php echo $autor; ?></label></p>
						<p class='my-1'><i class='fs-5 far text-success'>&#xf073;</i> : <label><?php echo $datum; ?></label></p>
						<p class="my-2">Kategorija: <span class="fw-bold text-success"><?php echo $naziv_kategorije; ?></span></p>
						<p class="my-2">Tip objave: <?php echo $naziv_tipa; ?></p>
						<p class="my-2">Spremio: <?php echo $spremio; ?></p>
					</div>
					<div class='p-3	flex-shrink-1'>
						
						
						<div class=''>
							<label class='m-1'>Omogući komentare:</label>
							<input type="checkbox" onclick="return false;" <?php echo $omoguci_komentare; ?> class='form-check-input mx-4'/>
						</div>
						
						<div class=''>
							<label class='m-1'>Istaknuto:</label>
							<input type="checkbox" onclick="return false;" class='form-check-input mx-4' <?php echo $istaknuto; ?> />
						</div>
						<div class=''>
							<label class='m-1'>Objavljen:</label>
							<input type="checkbox" onclick="return false;" <?php echo $objavljen; ?> class='form-check-input mx-4'/>
						</div>
					</div>
					<div class='p-3 flex-grow-1'>
						<div class="d-flex flex-wrap">
						<?php
						$sql = "SELECT * FROM pridruzene_oznake, oznake WHERE clanak_id = $id
						AND id_oznake = oznaka_id";
						$res = mysqli_query($veza, $sql);
						while($red = mysqli_fetch_array($res)){
							$naziv_oznake = $red["naziv_oznake"];
							echo "<p class='btn btn-outline-secondary m-1'>$naziv_oznake</p>";
						}
						?>
						</div>
					</div>
				</div>
				<div class='my-3 pt-3 border-top'>
					<h5 class='my-2 px-3 font-italic'><?php echo $uvod; ?></h5>
				</div>
				
				<div class='p-3 border-top'>
					<p class=''><?php echo $tekst; ?></p>
				</div>
			</div>
		</div>
	</div>


</div>
<?php
include("podnozje.php");
?>
<?php
require("../moj_spoj/otvori_vezu_cmp.php");

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

if(!empty($_POST["posljednji_clanak"]) && !empty($_POST["posljednji_datum"])){
	$posljednji_clanak = test_input($_POST["posljednji_clanak"]);
	$posljednji_datum = test_input($_POST["posljednji_datum"]);
	
	if(empty($_POST["kategorija"])){
		$kategorija = "";
	}
	else{
		$kategorija = test_input($_POST["kategorija"]);
	}


	if(empty($kategorija)){
		$sql = "SELECT COUNT(id_clanak) AS broj_clanaka FROM clanak
		WHERE tip_teksta = 1
		AND objavljen = 1
		AND datum_objave <= '$posljednji_datum'
		AND id_clanak != $posljednji_clanak
		ORDER BY datum_objave DESC";
		$res = mysqli_query($veza, $sql);
		$red = mysqli_fetch_array($res);
		$broj_clanaka = $red["broj_clanaka"];
		
		$sql = "SELECT * FROM clanak
		WHERE tip_teksta = 1
		AND objavljen = 1
		AND datum_objave <= '$posljednji_datum'
		AND id_clanak != $posljednji_clanak
		ORDER BY datum_objave DESC
		LIMIT 5";
	}
	else{
		$sql = "SELECT COUNT(id_clanak) AS broj_clanaka FROM clanak
		WHERE tip_teksta = 1
		AND objavljen = 1
		AND datum_objave <= '$posljednji_datum'
		AND id_clanak != $posljednji_clanak
		AND kategorije_id = $kategorija
		ORDER BY datum_objave DESC";
		$res = mysqli_query($veza, $sql);
		$red = mysqli_fetch_array($res);
		$broj_clanaka = $red["broj_clanaka"];
		
		$sql = "SELECT * FROM clanak
		WHERE tip_teksta = 1
		AND objavljen = 1
		AND datum_objave <= '$posljednji_datum'
		AND id_clanak != $posljednji_clanak
		AND kategorije_id = $kategorija
		ORDER BY datum_objave DESC
		LIMIT 5";
	}
	$res = mysqli_query($veza, $sql);
	if(mysqli_num_rows($res) > 0){
		while($red = mysqli_fetch_array($res)){
			$id_clanak = $red['id_clanak'];
			$naslov_clanka = $red['naslov_clanka'];
			$uvod = $red['uvod'];
			$ime_autora = $red['ime_autora'];
			$datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
			
		
			
			
			//kako bih znala otkud da učitam još članaka
			$posljednji_datum = $red["datum_objave"];
			$posljednji_clanak = $id_clanak; 
			
			
			$sql_img = "SELECT * FROM slike_naslova WHERE clanak_id = $id_clanak
			ORDER BY datum_unosa ASC LIMIT 1";
			$res_img = mysqli_query($veza, $sql_img);
			if($redak = mysqli_fetch_array($res_img)){
				$putanja = $redak["putanja"];
				
			}
			else{
				$putanja = '';
			}
			
			$sqllike = "SELECT COUNT(id_lajka) AS broj_lajkova FROM lajkovi WHERE clanak_id = $id_clanak";
			$reslike = mysqli_query($veza, $sqllike);
			if(mysqli_num_rows($reslike) > 0){
				$redlike = mysqli_fetch_array($reslike);
				$broj_lajkova = $redlike["broj_lajkova"];
			}
			else{
				$broj_lajkova = 0;
			}
			
			$sqlcomm = "SELECT COUNT(id_komentara) AS broj_komentara FROM komentari WHERE clanak_id = $id_clanak";
			$rescomm = mysqli_query($veza, $sqlcomm);
			if(mysqli_num_rows($rescomm) > 0){
				$redcomm = mysqli_fetch_array($rescomm);
				$broj_komentara = $redcomm["broj_komentara"];
			}
			else{
				$broj_komentara = 0;
			}
			
			?>
			<a href="clanak.php?id=<?php echo $id_clanak; ?>" class="row py-4 px-0 m-0 hover">
				<div class="col-lg-4 col-md-6">
					<?php
					if(!empty($putanja)){
						echo "<img src='$putanja' alt='$naslov_clanka' class='height-200 object-fit-cover shadow-sm' /> ";
					}
					else{
						echo "<div class='h-100 bg-light'><img class='height-200 object-fit-contain' src='slike/HEALTHCLUB LOGO.png' alt='HEALTHCLUB'></div>";
					}
					?>
				</div>
				
				<div class="col-lg-8 col-md-6">
					<h5 class="text-dark pt-3"><?php echo $naslov_clanka; ?> </h5>
					<h6 class="py-3 text-success"> <?php echo "$ime_autora - $datum_objave"; ?></h6>
					<p class="text-dark"><?php echo $uvod; ?></p>
					<div class="d-flex align-items-center mt-3">
						
						<small class="text-dark d-inline-block me-3 text-decoration-underline flex-grow-1">Više...</small>
						<div class="d-flex flex-wrap justify-content-end me-3 bg-light">
							<p class="interactive-icons link-secondary mx-3 p-2 cursor-pointer komentiraj">
								<small><?php echo $broj_lajkova; ?></small>
								<i class=" fa-solid fa-heart"></i>
							</p>
							<p class="interactive-icons link-secondary mx-3 p-2 cursor-pointer komentiraj">
								<small><?php echo $broj_komentara; ?></small>
								<i class=" fa-solid fa-comment"></i>
							</p>
						</div>
						<div></div>
					</div>
				</div>
				<input type="hidden" name="posljednji_datum" value="<?php echo $posljednji_datum; ?>">
				<input type="hidden" name="posljednji_clanak" value="<?php echo $broj_clanaka; ?>">
				<input type="hidden" name="broj_clanaka" value="<?php echo $broj_clanaka; ?>">
			</a>
			
			<?php
		}
	}
	
}


?>

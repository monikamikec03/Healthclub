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
		
if(!empty($_GET["prikaz"])){
	$prikaz = test_input($_GET["prikaz"]);
	if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$prikaz)) {
		header("location:index.php");
	}
	if($prikaz != 'sihterica' && $prikaz != 'raspored'){
		header("location:index.php");
	}
}
else{
	$prikaz = "sihterica";
}
$aktivni_clanovi = $neaktivni_clanovi = $svi_clanovi = 0;
$sql = "SELECT COUNT(id_clanstva) AS aktivni_clanovi FROM clanstva WHERE CURDATE() BETWEEN datum_od AND datum_do";
$res = mysqli_query($veza, $sql);
if($red = mysqli_fetch_array($res)){
	$aktivni_clanovi = $red["aktivni_clanovi"];
}

$sql = "SELECT COUNT(id_clanstva) AS neaktivni_clanovi FROM clanstva WHERE CURDATE() NOT BETWEEN datum_od AND datum_do";
$res = mysqli_query($veza, $sql);
if($red = mysqli_fetch_array($res)){
	$neaktivni_clanovi = $red["neaktivni_clanovi"];
}
$svi_clanovi = $aktivni_clanovi + $neaktivni_clanovi;

$sql = "SELECT COUNT(id_posjetioca) AS broj_posjetioca FROM posjetioci";
$res = mysqli_query($veza, $sql);
if($red = mysqli_fetch_array($res)){
	$broj_posjetioca = $red["broj_posjetioca"];
}


?>
<div class="d-flex bg-light border border-bottom flex-wrap">
	<div class="text-dark flex-grow-1 py-3 d-flex flex-wrap align-items-center">
		<h2 class="px-3 text-start">Upravljačka ploča</h2>
		<small class='px-3 py-0 m-0'>Svi posljednji/novi podaci iskazani su u intervalu posljednih <b>15 dana</b>.</small>
	</div>
	<div class='d-flex'>
		<a class='btn btn-outline-success fs-3 d-flex justify-content-center align-items-center' href='../index.php'><i class="fa-solid fa-earth-europe"></i></a>
		<a href='odjava.php' class="btn btn-secondary px-5 d-flex justify-content-center align-items-center">Odjava</a>
	</div>
</div>
<div id="sadrzaj">

	
	<div class="row">
		<div class="col-lg-4 my-3">
			<div class="d-flex flex-start flex-wrap">
				<form method="post" action="clanovi.php" class='flex-grow-1 d-flex'>
					<button name="submit" class="d-flex flex-column flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">
						<h4 class="text-center"><i class="link-success fa-solid fa-user-plus"></i></h4>
						<h4>Aktivni članovi</h4>
						<h3 class='text-center'><?php echo $aktivni_clanovi; ?></h3>
						<input type="hidden" name="aktivnost" value="da">
					</button>
				</form>
				
				<form class="flex-grow-1 d-flex" method="post" action="clanovi.php">
					<button name="submit" class="d-flex flex-column flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">
						
						<h4 class="text-center"><i class="link-danger fa-solid fa-user-minus"></i></h4>
						<h4>Neaktivni članovi</h4>
						<h3 class='text-center'><?php echo $neaktivni_clanovi; ?></h3>
						<input type="hidden" name="aktivnost" value="ne">
					</button>
				</form>
				<form class="flex-grow-1 d-flex" method="post" action="clanovi.php">
					<button name="submit" class="d-flex flex-column flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">
						
						<h4 class="text-center"><i class="link-secondary fa-solid fa-user-group"></i></h4>
						<h4> Svi članovi</h4>
						<h3 class='text-center'><?php echo $svi_clanovi; ?></h3>
						<input type="hidden" name="aktivnost" value="">
					</button>
				</form>	
				
				<div class="d-flex flex-start flex-wrap w-100 ">
					<div name="submit" class="d-flex flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">
						
						<h4 class="text-center"><i class="fa-solid fa-people-group"></i></h4>
						<h4 class='mx-2'> Posjetioci:</h4>
						<h3 class='text-center'><?php echo $broj_posjetioca; ?></h3>
						
					</div>
				</div>	
				
				<?php
				$sql = "select (select count(*) from komentari) as broj_komentara, (select count(*) from lajkovi) as broj_lajkova";
				$res = mysqli_query($veza, $sql);
				$red = mysqli_fetch_array($res);
				$broj_komentara = $red["broj_komentara"];
				$broj_lajkova = $red["broj_lajkova"];
				?>
				<div class="d-flex flex-start flex-wrap w-100 ">
					<div name="submit" class="d-flex flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">
						<h4 class="text-center"><i class="fa-solid fa-comments"></i></h4>
						<h4 class='mx-2'>Komentari:</h4>
						<h3 class='text-center'><?php echo $broj_komentara; ?></h3>
						
					</div>
				</div>	
				
				<div class="d-flex flex-start flex-wrap w-100 ">
					<div name="submit" class="d-flex flex-grow-1 p-3 m-1 bg-light shadow-sm align-items-center">	
						<h4 class="text-center"><i class="fa-solid fa-heart"></i></h4>					
						<h4 class='mx-2'>Lajkovi:</h4>
						<h3 class='text-center'><?php echo $broj_lajkova; ?></h3>
						
					</div>
				</div>
			</div>	

			
			
			
			
			
			
		</div>
		<div class="col-lg-4 p-3 shadow-sm my-2">
			<h2 class="text-start my-3">Novi korisnici</h2>
			<div class="table-responsive">
				<table class="table table-hover table-light m-0">
				<?php
				$ispis = '';
				$sql = "SELECT * FROM korisnici WHERE kupac = 1
				AND aktivan = 1
				AND prvi_datum > NOW() - INTERVAL 15 DAY
				AND prvi_datum < NOW() + INTERVAL 15 DAY
				ORDER BY prvi_datum DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_korisnik = $red["id_korisnik"];
					$naziv_korisnika = $red["naziv_korisnika"];
					$prvi_datum = date("d.m.Y.", strtotime($red["prvi_datum"]));
					$napomena_korisnika = $red["napomena_korisnika"];
					$ispis .= "<tr>";
					$ispis .= "<td><a class='fw-bold link-secondary' href='prikaz_korisnik.php?id=$id_korisnik'>$naziv_korisnika</a></td>";
					$ispis .= "<td>$prvi_datum</td>";
					$ispis .= "<td>$napomena_korisnika</td>";
					$ispis .= "</tr>";
				}
				echo $ispis;
				?>
				</table>
				
			</div>			
			<a class='btn btn-outline-secondary' href="partneri.php">Svi korisnici</a>
			
			
			<h2 class="text-start my-3">Posljednji komentari</h4>
			<div class="table-responsive">
				<table class="table table-stripped table-hover m-0">
				<?php
				$ispis = '';
				$sql = "SELECT * FROM komentari
				WHERE datum_unosa > NOW() - INTERVAL 15 DAY
				AND datum_unosa < NOW() + INTERVAL 15 DAY
				ORDER BY datum_unosa DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_komentara = $red["id_komentara"];
					$komentar = $red["komentar"];
					$naziv = $red["naziv"];
					$datum_unosa = date("d.m.Y. H:i", strtotime($red["datum_unosa"]));
					$ispis .= "<div class='d-flex flex-column my-3'>";
					$ispis .= "<p class='bg-light p-1 d-flex justify-content-between flex-wrap align-items-center'>
						<small>$naziv</small>
						<b class='p-1'>$datum_unosa</b>
						<small><a class='link-danger pe-2' href='brisanje_komentara.php?id_komentara=$id_komentara' onclick='return confirm(\"Potvrdite da želite obrisati ovaj komentar.\");'><i class=\"fs-3 fa-solid fa-square-minus\"></i></i></a></small>
						</p>";
					$ispis .= "<small class='p-1'>$komentar</small>";
					$ispis .= "";
					$ispis .= "</div>";
				}
				echo $ispis;
				?>
				</table>	
			</div>
			<a class='btn btn-outline-danger' href="komentari.php">Prikaži sve komentare</a>
			
			
		</div>
		<div class="col-lg-4 p-3 shadow-sm my-2">
			<h2 class="text-start my-3">Posljednje uplaćene članarine</h4>
			<div class="table-responsive">
				<table class="table table-stripped table-hover m-0">
				<?php
				$ispis = '';
				$sql = "SELECT * FROM aktivnosti, korisnici WHERE naziv_aktivnosti = 'Članarina'
				AND id_korisnik = korisnik_id
				AND datum_aktivnosti > NOW() - INTERVAL 15 DAY
				AND datum_aktivnosti < NOW() + INTERVAL 15 DAY
				ORDER BY datum_aktivnosti DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_aktivnost = $red["id_aktivnost"];
					$napomena = $red["napomena"];
					$naziv_korisnika = $red["naziv_korisnika"];
					$datum_aktivnosti = date("d.m.Y.", strtotime($red["datum_aktivnosti"]));
					$ispis .= "<tr>";
					$ispis .= "<td><a class='link-success fw-bold' href='prikaz_aktivnosti.php?id_aktivnost=$id_aktivnost'>$napomena</a></td>";
					$ispis .= "<td>$naziv_korisnika</td>";
					$ispis .= "<td>$datum_aktivnosti</td>";
					$ispis .= "</tr>";
				}
				echo $ispis;
				?>
				</table>
				
			</div>
			<a class='btn btn-success' href="partneri.php">Sve aktivnosti</a>
			
		</div>
	</div>
	
	
	<div class="py-5">
		<?php

		include("kalendar_zadataka.php");
		?>
	</div>
	
	
	<div class='my-2 row bg-warning bg-opacity-50'>
		
		<div class="col-md-6 p-3">
			<h6 class='my-3'>Posljednje prijave za pregled</h6>
			<div class="table-responsive">
				<table class="table table-stripped table-hover m-0">
				<?php
				$ispis = '';
				$sql = "SELECT * FROM prijava_prvi_pregled
				WHERE datum_prijave > NOW() - INTERVAL 15 DAY
				AND datum_prijave < NOW() + INTERVAL 15 DAY
				ORDER BY datum_prijave DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_prijave = $red["id_prijave"];
					$ime_prezime = $red["ime_prezime"];
					$email = $red["email"];
					$naziv_artikla = $red["naziv_artikla"];
					$broj_mobitela = $red["broj_mobitela"];
					$datum_prijave = date("d.m.Y. H:i", strtotime($red["datum_prijave"]));
					$ispis .= "<tr>";
					$ispis .= "<td><a class='link-secondary fw-bold' href='prikaz_prijave_p.php?id=$id_prijave'>$ime_prezime</a></td>";
					$ispis .= "<td>$email</td>";
					$ispis .= "<td>$broj_mobitela</td>";
					$ispis .= "<td>$naziv_artikla</td>";
					$ispis .= "<td>$datum_prijave</td>";
					$ispis .= "</tr>";
				}
				echo $ispis;
				?>
				</table>
				
			</div>
			<a class='btn btn-secondary' href="prvi_pregled.php">Sve prijave</a>
	
		</div>
		
		<div class="col-md-6 p-3">
			<h6 class='my-3'>Posljednje prijave za trening</h6>
			<div class="table-responsive">
				<table class="table table-stripped table-hover m-0">
				<?php
				$ispis = '';
				$sql = "SELECT * FROM prijava_probni_trening, artikli_popis WHERE id_artikla = artikl
				AND datum_prijave > NOW() - INTERVAL 15 DAY
				AND datum_prijave < NOW() + INTERVAL 15 DAY
				ORDER BY datum_prijave DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_prijave = $red["id_prijave"];
					$ime_prezime = $red["ime_prezime"];
					$email = $red["email"];
					$naziv_artikla = $red["naziv_artikla"];
					$broj_mobitela = $red["broj_mobitela"];
					$datum_prijave = date("d.m.Y. H:i", strtotime($red["datum_prijave"]));
					$ispis .= "<tr>";
					$ispis .= "<td><a class='link-success fw-bold' href='prikaz_prijave_t.php?id=$id_prijave'>$ime_prezime</a></td>";
					$ispis .= "<td>$email</td>";
					$ispis .= "<td>$broj_mobitela</td>";
					$ispis .= "<td>$naziv_artikla</td>";
					$ispis .= "<td>$datum_prijave</td>";
					$ispis .= "</tr>";
				}
				echo $ispis;
				?>
				</table>
				
			</div>
			<a class='btn btn-success' href="probni_trening.php">Sve prijave</a>
		</div>
	</div>
	
	
	
	
	<div class="py-5">
		<?php

		include("$prikaz.php");
		?>
	</div>
	
	
		
</div>

<script>
	document.addEventListener("DOMContentLoaded", function(event) { 
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
</script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
	var value = $(this).val().toLowerCase();
	$("#myTable tr").filter(function() {
	  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
  });
});
</script>
<?php
include("podnozje.php");
?>
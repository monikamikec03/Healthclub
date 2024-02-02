<?php
include("zaglavlje.php");
include("navigacija_light.php");
require("../moj_spoj/otvori_vezu_cmp.php");	


?>
<div class="pt-5">
	<div class="container">
		<h2 class="text-start">Grupni treninzi</h2>
		<h3 class='my-3'><q>Bila bi velika šteta da vam život prođe, a da ne osjetite što je vaše tijelo u stanju postići.</q></h3>
		
		<div class='row my-5 d-flex align-items-stretch'>
			<div class='col-lg-6 p-0 bg-light flex-grow-1 d-flex align-items-stretch'>
				
					<video  class='w-100 h-100 object-fit-cover' controls>
						<source src="video/video_grupni.mp4" type="video/mp4">
					</video>
				
			</div>
			
			<div class='col-lg-6 p-3 bg-light'>
				<h6 class='mt-3'>Sustav treninga za razvijanje idealne forme</h6>
				<p class='text-justify my-3'>Idealna forma ne odnosi se samo na tjelesni izgled. To je razvijenost tjelesnih sposobnosti (snage, jakosti, izdržljivosti, mobilnosti, fleksibilnosti, koordinacije, eksplozivnosti i dr.) zajedno s tjelesnim izgledom i mentalnom snagom.</p>

				<p class='text-justify my-3'>Na prvom mjestu su zdravlje i dobar osjećaj dok vježbamo. Ako izuzmemo bilo koji dio jednadžbe, nećemo biti u ideallnoj formi. Cilj dobrog sustava treninga je razvijanje svih navedenih dijelova.</p>

				
				
				<h6 class='mt-3'>Ograničen broj mjesta</h6>
				<p class='text-justify my-3'>Na 400 kvadrata imamo svu potrebnu opremu za kvalitetne grupne treninge rekreativaca bez da se radi gužva ili da fali prostora ili opreme + 6 educiranih trenera</p>
				
				<p class='text-justify my-3'>Kako bismo osigurali kvalitetu svakog treninga broj aktivnih članova na grupnim treninzima ograničen je na 20 članova po terminu. Zaboravite na gužvu i uživajte u svom slobodnom vremenu.</p>
			</div>
		</div>
		
		
		
		<div class='row my-5'>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-success h-100'>
					<b class='ps-5 my-2'>Ciljevi treninga</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> unapređenje ZDRAVLJA</li>
						<li><b>&#9900;</b> poboljšanje tjelesnih SPOSOBNOSTI</li>
						<li><b>&#9900;</b> promjena tjelesnog IZGLEDA</li>
						<li><b>&#9900;</b> poboljšanje MENTALNOG STANJA</li>
						<li><b>&#9900;</b> ispravljanje DRŽANJA TIJELA – POSTURE</li>
					</ul>
				</div>
			</div>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-danger h-100'>
					<b class='ps-5 my-2'>Elementi treninga</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> PRIPREMA – opuštanje i reset</li>
						<li><b>&#9900;</b> ZAGRIJAVANJE – mobilnost i jačanje trupa</li>
						<li><b>&#9900;</b> SNAGA – za oblikovanje tijela</li>
						<li><b>&#9900;</b> IZDRŽLJIVOST – za gubitak masnoća</li>
						<li><b>&#9900;</b> ZAVRŠNI DIO – ravnoteža i istezanje</li>
					</ul>
				</div>
			</div>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-secondary h-100'>
					<b class='ps-5 my-2'>Što očekivati</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> SIGURNOST – pravilna tehnika</li>
						<li><b>&#9900;</b> POKRET – 5 glavnih pokreta</li>
						<li><b>&#9900;</b> PRIMJENJIVOST – za svaku osobu</li>
						<li><b>&#9900;</b> ODRŽIVOST – trening za cijeli život</li>
						<li><b>&#9900;</b> PRENOSIVOST – primjena u svakdonevici</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class='my-4'>
			<h2 class='text-start'>Popunjenost grupa</h2>
			<div class='d-flex flex-wrap'>
				<?php
				$sql = "SELECT * FROM podgrupe
				WHERE artikl_id = 1 AND aktivna_podgrupa = 1
				ORDER BY redoslijed_podgrupe";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_podgrupe = $red["id_podgrupe"];
					$naziv_podgrupe = $red["naziv_podgrupe"];
					$max_osoba = $red["max_osoba"];
					
					
					$broji = "SELECT COUNT(id_clanstva) AS broj_clanova FROM clanstva WHERE datum_od <= CURDATE() AND datum_do >= CURDATE() AND podgrupa_id = $id_podgrupe";

					$result = mysqli_query($veza, $broji);
					$redak = mysqli_fetch_array($result);
					$broj_clanova = $redak["broj_clanova"];
					

					if($broj_clanova < $max_osoba){
						$boja = 'text-success';
					}
					else if($broj_clanova == $max_osoba){
						$boja = 'text-danger';
					}
					else{
						$boja = '';
					}
					
					echo "<div class='bg-light text-center m-1 py-2 px-4'>
						<h3>$naziv_podgrupe</h3>
						<p><span class='$boja'>$broj_clanova</span> / $max_osoba</p>
						</div>";
					
				}
				?>
			</div>
		</div>
	
		
	</div>
	

		
		

	<div class='bg-success bg-opacity-25 m-0 d-flex justify-content-center align-items-start flex-wrap'>
		<a href='dojmovi_korisnika.php' class='col-md-4 p-3'><p class='text-start link-dark gw-bold'><b class='text-decoration-underline'>Kliknite ovdje</b> da biste pročitali dojmove naših polaznika</p></a>
		<a href='rezervacija_trening.php' class='col-md-4 p-3'><p class='text-start link-dark gw-bold'><b class='text-decoration-underline'>Prijavi se</b> i dođi na prvi BESPLATNI trening te popričaj s našim trenerima o svojim ciljevima i željama.</p></a>
	</div>
	
	<div class='row p-0 m-0'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa1.jpg' alt="Grupa 1">
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa2.jpg' alt="Grupa 2">
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa3.jpg' alt="Grupa 3">
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa4.jpg' alt="Grupa 4">
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa5.jpg' alt="Grupa 5">
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa6.jpg' alt="Grupa 6">
			
	</div>
	
	<div class="container py-3">
		<?php include("sihterica.php"); ?>
	</div> 
	
</div>





<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>



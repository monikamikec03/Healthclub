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
		setlocale(LC_ALL, 'hr_HR.utf-8');	


	if (isset($_GET["id"])) {
		$id = (int)($_GET['id']);	
			$sql = ("SELECT * FROM  prijava_prvi_pregled
			WHERE id_prijave = $id");
			$rezultat = mysqli_query($veza, $sql);
			if($redak = mysqli_fetch_array($rezultat))
			{
						
				$datum_prijave = date("Y-m-d", strtotime($redak['datum_prijave']));
                $datum_timestamp = strtotime($datum_prijave);
                $october_22_2023_timestamp = strtotime("2023-10-22");

				$id_prijave = $id= $redak['id_prijave'];		 
				$ime_prezime = $redak['ime_prezime'];  
				$email = $redak['email'];
				$broj_mobitela = $redak['broj_mobitela'];
				$godine = $redak['godine'];  				
				$operativni_zahvati = html_entity_decode($redak['operativni_zahvati']);  
				$razlog_dolaska = html_entity_decode($redak['razlog_dolaska']);
				$frakture = html_entity_decode($redak['frakture']);
				$porezotine = html_entity_decode($redak['porezotine']);
				$traume = html_entity_decode($redak['traume']);
				$lijekovi = html_entity_decode($redak['lijekovi']);
				$termini = $redak["termini"];
				$manifestacija_problema_na_tijelu = html_entity_decode($redak["manifestacija_problema_na_tijelu"]);
				$pocetak_simptoma = html_entity_decode($redak["pocetak_simptoma"]);
				$trajanje_simptoma = html_entity_decode($redak["trajanje_simptoma"]);
				$pogorsavanje_simptoma = html_entity_decode($redak["pogorsavanje_simptoma"]);
				$poboljsavanje_simptoma = html_entity_decode($redak["poboljsavanje_simptoma"]);
				$sprjecavanje_u_svakodnevnim_radnjama = html_entity_decode($redak["sprjecavanje_u_svakodnevnim_radnjama"]);
				$ozbiljnost_problema = html_entity_decode($redak["ozbiljnost_problema"]);
				$nabrojeni_simptomi = html_entity_decode($redak["nabrojeni_simptomi"]);
				$uzrok_problema = html_entity_decode($redak["uzrok_problema"]);
				$unutarnji_organi = html_entity_decode($redak["unutarnji_organi"]);
				$druge_terapije = html_entity_decode($redak["druge_terapije"]);
				$procjena_stresa = html_entity_decode($redak["procjena_stresa"]);
				$procjena_razine_energije = html_entity_decode($redak["procjena_razine_energije"]);
				$fokus_koncentracija = html_entity_decode($redak["fokus_koncentracija"]);
				$tjeskoba_depresija = html_entity_decode($redak["tjeskoba_depresija"]);
				$kvaliteta_prehrane = html_entity_decode($redak["kvaliteta_prehrane"]);
				$kolicina_treninga = html_entity_decode($redak["kolicina_treninga"]);
				$zelje = html_entity_decode($redak["zelje"]);
				$cilj = html_entity_decode($redak["cilj"]);

				
			}
	}
			
include("zaglavlje.php"); 	
?>
	

<div id="sadrzaj">
	

	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Prikaz prijave: <?php echo $ime_prezime; ?></h2>

		<div class='d-flex flex-wrap justify-content-end align-items-center'>
			 <button class="btn btn-outline-primary" onclick="printContent()"><i class="fa-solid fa-print"></i> Ispis</button>
			 
			<a  class='btn btn-primary m-1' href='unos_korisnik.php?id_prijave_p=<?php echo $id; ?>'><i class="fa-solid fa-user-plus"></i> Unesi u korisnike</a>
			
			<a class='btn btn-warning m-1' href='prvi_pregled.php'>🡸 Natrag</a>		
		</div>			
	</div>
	<div class='row' id="content_to_print">

		<div class='col-lg-3 col-md-6 my-3'>
			<label>Ime i prezime:</label>
			<span class='font-weight-bold form-control'><?php echo $ime_prezime; ?></span>				
		</div>
		

		<div class='col-lg-3 col-md-6 my-3'>
			<label>Godine:</label>					
			<span class='font-weight-bold form-control'><?php echo $godine; ?></span>					
		</div>		
			
		<div class='col-lg-3 col-md-6 my-3'>
			<label>Email:</label>
			<span class='font-weight-bold form-control'><?php echo $email; ?></span>					
		</div>
		
		<div class='col-lg-3 col-md-6 my-3'>
			<label>Broj mobitela:</label>
			<span class='font-weight-bold form-control'><?php echo $broj_mobitela; ?></span>				
		</div>	

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Objasnite problem/stanje zbog kojeg dolazite?</label>
			<span class='font-weight-bold form-control'><?php echo $razlog_dolaska; ?></span>						
		</div>    

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Koje je mjesto na tijelu gdje se problem najčešće manifestira? </label>
			<span class='font-weight-bold form-control'><?php echo $manifestacija_problema_na_tijelu; ?></span>						
		</div>
        
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Kako su počeli simptomi/stanje?</label>
			<span class='font-weight-bold form-control'><?php echo $pocetak_simptoma; ?></span>						
		</div>      

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Koliko dugo simptomi/stanje traju?</label>
			<span class='font-weight-bold form-control'><?php echo $trajanje_simptoma; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Što pogoršava simptome/stanje?</label>
			<span class='font-weight-bold form-control'><?php echo $pogorsavanje_simptoma; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Što poboljšava simptome/stanje?</label>
			<span class='font-weight-bold form-control'><?php echo $poboljsavanje_simptoma; ?></span>						
		</div>
        
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Da li vas vaše stanje sprječava u svakodnevnim radnjama?</label>
			<span class='font-weight-bold form-control'><?php echo $sprjecavanje_u_svakodnevnim_radnjama; ?></span>						
		</div> 

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Kako biste prema vlastitom osjećaju ocijenili ozbiljnost problema/bol ? (0-10)</label>
			<span class='font-weight-bold form-control'><?php echo $ozbiljnost_problema; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Nabrojite nam sve simptome koje osjećate.</label>
			<span class='font-weight-bold form-control'><?php echo $nabrojeni_simptomi; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Što vi mislite da je uzrok problema?</label>
			<span class='font-weight-bold form-control'><?php echo $uzrok_problema; ?></span>						
		</div>

        <div class='col-lg-6 col-md-12 my-3'>
			<label>Da li ste imali frakture (puknuća) kostiju, ligamenata, mišića ? Ukoliko ih je bilo, koji su i kada ste ih zadobili. </label>
			<span class='font-weight-bold form-control'><?php echo $frakture; ?></span>						
		</div>
		
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Da li ste imali operativne zahvate? Ukoliko ih je bilo, koji su i kada ste ih obavili?</label>
			<span class='font-weight-bold form-control'><?php echo $operativni_zahvati; ?></span>						
		</div>
		
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Da li ste zadobili porezotine, opekline, udarce i padove? Ukoliko ih je bilo, koji su i kada ste ih zadobili? </label>
			<span class='font-weight-bold form-control'><?php echo $porezotine; ?></span>						
		</div>
		
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Imate li ili ste imali problema s unutarnjim organima? Ukoliko da, opišite nam.</label>
			<span class='font-weight-bold form-control'><?php echo $unutarnji_organi; ?></span>						
		</div>
		<?php
        if ($datum_timestamp < $october_22_2023_timestamp) {
         ?>
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Teške psihološke traume od rođenja do danas. Ukoliko ih je bilo, koji su i kada ste ih zadobili?</label>
			<span class='font-weight-bold form-control'><?php echo $traume; ?></span>						
		</div>
        <?php
        }
        ?>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Uzimate li lijekove? Ukoliko da, opišite ih.</label>
			<span class='font-weight-bold form-control'><?php echo $lijekovi; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Da li ste pokušali druge terapije i ukoliko da opišite nam iskustvo? </label>
			<span class='font-weight-bold form-control'><?php echo $druge_terapije; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Vaša subjektivna procjena stresa 0-10? </label>
			<span class='font-weight-bold form-control'><?php echo $procjena_stresa; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Vaša subjektivna procjena razine energije 0-10? </label>
			<span class='font-weight-bold form-control'><?php echo $procjena_razine_energije; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Imate li problema s fokusom i koncentracijom? </label>
			<span class='font-weight-bold form-control'><?php echo $fokus_koncentracija; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Obzirom na simptome/stanje koje doživljavate osjećate li se tjeskobno ili depresivno? </label>
			<span class='font-weight-bold form-control'><?php echo $tjeskoba_depresija; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Kako biste ocijenili vašu kvalitetu prehrane 0 -10? Postoje li namirnice koje izbjegavate radi zdravstvenih razloga?</label>
			<span class='font-weight-bold form-control'><?php echo $kvaliteta_prehrane; ?></span>						
		</div>
	
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Koliko (u minutama) na tjednoj bazi provodite vremena u treningu umjerenog do visokog intenziteta? </label>
			<span class='font-weight-bold form-control'><?php echo $kolicina_treninga; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Što biste najviše voljeli opet moći raditi da boli/problema više nema?</label>
			<span class='font-weight-bold form-control'><?php echo $zelje; ?></span>						
		</div>

		<div class='col-lg-6 col-md-12 my-3'>
			<label>Što bi rekli da vam je cilj postići kod nas u HealthClubu?</label>
			<span class='font-weight-bold form-control'><?php echo $cilj; ?></span>						
		</div>
	
	


		
		<div class='col-lg-6 col-md-12 my-3'>
			<label>Odaberite datume i vrijeme kada biste voljeli rezervirati svoj termin.</label>
			<span class='font-weight-bold form-control'><?php echo $termini; ?></span>		
		</div>	
	
	
	</div>
</div>
<script>
	function printContent() {
		var content = document.getElementById('content_to_print');
		var printWindow = window.open('', '', 'width="100%",height="auto"');
		printWindow.document.open();
		printWindow.document.write('<html><head><style>div{margin-top:20px;} label, span{display:block;font-family:Calibri, sans-serif;} label{font-weight:bold;}</style><title>Prijava na pregled - <?php echo $ime_prezime; ?></title></head><body><h2>Prijava na pregled - <?php echo $ime_prezime; ?></h2>');
		printWindow.document.write(content.innerHTML);
		printWindow.document.write('<div><label>Datum prijave:</label> <span><?php echo date("d.m.Y.", strtotime($datum_prijave)); ?></span></div></body></html>');
		printWindow.document.close();
		printWindow.print();
		printWindow.close();
	}
</script>
</body>
</html> 
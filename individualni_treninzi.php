<?php
include("zaglavlje.php");
include("navigacija_light.php");
require("../moj_spoj/otvori_vezu_cmp.php");	


?>
<div class="pt-5">
	<div class="container">
		<h2 class="text-start">Individualni i poluindividualni treninzi</h2>
		<h3 class='my-3'><q>Individualni i poluindividualni trening za svakog rekreativca!</q></h3>
		
		<div class='row my-5 d-flex align-items-stretch'>
			<div class='col-lg-6 p-0 bg-light flex-grow-1 d-flex align-items-stretch'>
				
					<video  class='w-100 h-100 object-fit-cover' controls>
						<source src="video/video_grupni.mp4" type="video/mp4">
					</video>
				
			</div>
				

			<div class='col-lg-6 p-3 bg-light'>
				<h6 class='mt-3'>Ostvarite svoju  idealnu formu</h6>
				<p class='text-justify my-3'>Nekima je uspjeh olimpijsko odličje, dok je drugima očuvanje zdravlja i uspješno obavljanje svakodnevnih aktivnosti. Krajnji cilj kod svih je isti – biti zadovoljan i ostvariti željeni cilj! Bez obzira jeste li sportaš ili rekreativac, kroz naše treninge vodit ćemo vas do željenog cilja na način potpuno prilagođen vašim željama, mogućnostima i sposobnostima.</p>
				
				<h6 class='mt-3'>Plan prilagođen Vama</h6>
				<p class='text-justify my-3'>Stručna podrška, te prilagođen plan i program rada omogućuju pojedincu da kroz ovaj način rada postigne svoj maksimum uz očuvanje zdravlja i prevenciju ozljeda. U skladu s vašim potrebama radit ćemo na razvoju vaše snage, jakosti, izdržljivosti, mobilnosti, fleksibilnosti, brzine i agilnosti.</p>
			</div>
		</div>

		
		<div class='row my-5'>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-success h-100'>
					<b class='ps-5 my-2'>Različite metode treninga</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> Naši treneri educirani su za provođenje raznih metoda treninga, a sve s ciljem kako bi se u najvećoj mogućoj mjeri prilagodili vama. Vjerujemo da trening treba biti efikasan, ali i pružiti ugodno iskustvo. Vjernost naših dugogodišnjih klijenata najveće nam je priznanje za naš rad!</li>

					</ul>
				</div>
			</div>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-danger h-100'>
					<b class='ps-5 my-2'>Dodatna podrška</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> Svi naši klijenti imaju osiguranu dodatnu stručnu podršku. Trebate li savjet nutricionista, psihologa ili fizioterapeuta, uputit ćemo vas našim dugogodišnjim partnerima u čije smo se znanje i sami uvjerili!</li>
					</ul>
				</div>
			</div>
			
			<div class='col-lg-4 my-1 p-2'>
				<div class='shadow-sm p-3 list-secondary h-100'>
					<b class='ps-5 my-2'>Promjena životnog stila</b>
					<ul class="ps-5">
						
						<li><b>&#9900;</b> Današnji svijet pred nas stavlja nove izazove, ali i potrebe. Tjelesna aktivnost jedna je od njih. Želite li sačuvati zdravlje, postići i održati idealnu formu tjelesna aktivnost je neizbježna. Uz naše trenere pripremite se za svakodnevne izazove i (p)ostanite funkcionalni.</li>
					</ul>
				</div>
			</div>
		</div>
		<h2 class='text-start mt-2'>Paketi</h2>
		<div class='d-flex flex-wrap mb-3'>
			<?php
			$sql = "SELECT * FROM artikli_popis, jedinica_mjere
			WHERE naziv_artikla LIKE '%individualn%'
			AND jed_mjere = id_jed_mjere
			AND aktivan_artikl = 1
			ORDER BY redoslijed";
			$res = mysqli_query($veza, $sql);
			while($red = mysqli_fetch_array($res)){
				$naziv_artikla = $red["naziv_artikla"];
				$puni_naziv = $red["puni_naziv"];
			
				
				
			
				
				echo "<div class='bg-light text-center m-1 py-2 px-5'>
					<h3>$naziv_artikla</h3>
					<p><span class='text-success'>$puni_naziv</p>
					</div>";
				
			}
			?>
		</div>
		
	</div>

	<div class='bg-success bg-opacity-25 m-0 d-flex justify-content-center align-items-start flex-wrap'>
		<a href='dojmovi_korisnika.php' class='col-md-4 p-3'><p class='text-start link-dark gw-bold'><b class='text-decoration-underline'>Kliknite ovdje</b> da biste pročitali dojmove naših polaznika</p></a>
		<a href='rezervacija_trening.php' class='col-md-4 p-3'><p class='text-start link-dark gw-bold'><b class='text-decoration-underline'>Prijavi se</b> i dođi na prvi BESPLATNI trening te popričaj s našim trenerima o svojim ciljevima i željama.</p></a>
	</div>
	
	<div class='row p-0 m-0'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa1.jpg'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa2.jpg'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa3.jpg'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa4.jpg'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa5.jpg'>
		<img class='col-lg-2 col-md-6 col-sm-6 col-12 p-0' src='slike/grupa6.jpg'>
			
	</div>

	
</div>





<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>



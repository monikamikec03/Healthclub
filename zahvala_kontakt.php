<?php
ob_start();
require("../moj_spoj/otvori_vezu_cmp.php");		
setlocale(LC_ALL, 'hr_HR.utf-8');	
		

function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data); 
  $data = htmlspecialchars($data);
return $data;}	 
		
include("zaglavlje.php");
include("navigacija_light.php");

?>
<div class="flex-grow-1">

	<div class="container">
		<div class='d-flex align-items-stretch row justify-content-center'>
			<img src='slike/zahvala.jpg' class=' col-lg-4 col-md-6 m-0 p-0 col-sm-6 order-md-0 object-fit-cover '>
			<div class='col-lg-8 col-md-6 p-5 order-md-1'>
				<h2>Vaš upit je poslan <b class="text-success">Health Club</b> obrtu za održavanje i njegu tijela.</h2>
				<div class="text-center d-flex flex-column align-items-center">
					<q class='text-center'>Veselimo se riješiti Vaš problem i odgovoriti na bilo koje pitanje.</q>
					<p class="my-2">Zbog velikog broja upita i velike zainteresiranosti odgovor očekujte kroz par dana.</p>
					<p class="my-5 col-md-3 border-top border-3 border-success"></p>
					<p>Ukoliko Vas <span class="text-danger">hitno</span> zanima neka stvar ili želite <span class="text-danger">odmah </span>rezervirati grupu/pregled, preporučamo da nas kontaktirate na broj <a href='tel:+385989520746'>+385 98 952 0746</a>.</p>
				</div>
			</div>
	</div>
</div>

<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>
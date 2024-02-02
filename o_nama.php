<?php
ob_start();
require("../moj_spoj/otvori_vezu_cmp.php");		
setlocale(LC_ALL, 'hr_HR.utf-8');	
		

function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data); 
  $data = htmlspecialchars($data);
return $data;}	 

//slanje maila
$ime_prezime  = $email = $broj_mobitela = $zanima = $poruka = '';
$ime_prezimeErr = $emailErr = $broj_mobitelaErr = $zanimaErr = $porukaErr = '';

if (($_SERVER["REQUEST_METHOD"] == "POST") and (isset($_POST['g-recaptcha-response']))){

	if (empty($_POST['ime_prezime'])) {
		$ime_prezimeErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$ime_prezime = test_input($_POST['ime_prezime']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9 ]*$/",$ime_prezime)){
			$ime_prezimeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
				
	if (empty($_POST['email'])) {
		$emailErr = "";
	} 
	else {
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "<p class='text-danger text-end mt-2'>Morate unijeti ispravan format.</p>"; 
		}
	}	
	
	if (empty($_POST['broj_mobitela'])) {
		$broj_mobitelaErr = "";
	} 
	else {
		$broj_mobitela = test_input($_POST['broj_mobitela']);
		if (!preg_match("/^[0-9+ ]*$/",$broj_mobitela)){
			$broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	if(empty($broj_mobitela) && empty($email)){
		$emailErr = "<p class='text-danger text-end mt-2'>Morate unijeti ili email ili broj mobitela.</p>";
	}
	  
	if (empty($_POST['poruka'])) {
		$porukaErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
	} 
	else {
		$poruka = test_input($_POST['poruka']);
		if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/",$poruka)){
			$porukaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>"; 
		}
	}
	
	if(isset($_POST["pravila_privatnosti"])){
		$pravila_privatnosti = 1;
	}
	else{
		$pravila_privatnostiErr = "<p class='text-danger text-end mt-2'>Morate prihvatiti pravila privatnosti kako bismo mogli spremiti Vaše podatke.</p>";
	}
	
	
	if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
		$captchaErrr='';
	}
	else{
		$secretKey = "6Lf4v8shAAAAAGPPTHXt5plljp6u81brAbq-FWTD";
		$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captcha);
		
		$json_response = json_decode($response);									
		
		if($json_response->success == true) {
			$captchaErrr = '';
		} 
		else {
			$captchaErrr = "<p class='text-danger text-end mt-2'>Radiš nedozvoljene stvari.</p>";
		}	
				
		$captchaErrr = "<p class='text-danger text-end mt-2'>Radite nedozvoljene stvari. Google recaptcha validacija neuspješna.</p>";
	}
	
	
					
		
			
	
	if(empty($ime_prezimeErr) AND empty($emailErr) AND empty($broj_mobitelaErr) AND empty($porukaErr) AND empty($pravila_privatnostiErr) AND empty($captchaErrr)){
		
			$to = 'healthclub.vrbovec@gmail.com';
					
			$message = "<div style='font-family:sans-serif;'>";
				$message .= "<h5 style='color:#57B947;'>Imate novu poruku sa stranice <a  style='color:#57B947;' href='https://healthclub.hr/o_nama.php#kontakt'>www.healthclub.hr</a></h5>";
				$message .='<div style="padding:10px;margin-top:10px;">';
					$message .='<div style="border:0.5px solid #ddd;"><span>Vaše ime i prezime: </span>'.$ime_prezime.'</div>';
					$message .= '<div><span>Broj mobitela: </span>'.$broj_mobitela.'</div>';
					$message .= '<div><span>Email: </span>'.$email.'</div>';
					$message .= '<div><span>Zanima me: </span>'.$zanima.'</div>';
					$message .= '<div>Poruka:</div><div style="font-weight:bold;">'.$poruka.'</div>';
					$message .= '<div style="margin-top:10px;"><img src="https://healthclub.hr/slike/Logotip.svg" width="300"></div>';
				$message .= '</div>';
			$message .= '</div>';
		
			$subject = 'Kontakt poruka sa stranice - healthclub.hr'; 

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'To: '.$to.'' . "\r\n";
			$headers .= 'From: '.$email. "\r\n";
		
			 
			if(mail($to, $subject, $message, $headers)){			
				echo "<script>window.location = 'zahvala_kontakt.php';</script>";
			}
			else{
				$message = "<p class='text-danger'>Dogodila se pogreška, pokušajte ponovno!</p>";
			}
		
	}
	else{
		$message = "<p class='text-danger'>Niste ispunili sva polja!</p>";
	}
	
}		
include("zaglavlje.php");
include("navigacija_light.php");

?>


	
<div class="container py-5">
		
	<h3 class="text-start">HEALTHCLUB</h3>
	<div class="my-3">
		<h4>HealthClub je sportsko rehabilitacijski centar! </h4>
		<h4>Kao što samo ime govori <span class="text-success">“Zdravi klub”</span> tako i želimo da naše ime žive i naši korisnici. </h4>
		<h4 class="mb-3">Dopustite nam da vas upoznamo s našom svrhom, misijom te što sve čini naš centar! </h4>
		<div class="row">
			<div class="col-md-6 p-3 ">
				<div class="d-flex align-items-center">
					<span class="bg-warning p-3">
						<img src="slike/basic-gym-one-logo.png" class='height-50 object-fit-contain'>
					</span>
					<h4 class="text-success mx-4">BasicGymOne Vrbovec <br>Prva podružnica u povijesti BasicGymOne</h4>
					
				</div>
				<p class="my-3">U našoj dvorani nudimo redom grupne treninge po Basic training for life sustavu, poluindividualne treninge 1:2 i 1:4 te individualne treninge 1:1. Nadalje, kondicijsku pripremu sportaša, djece sportaša, kompletnu dijagnostiku sposobnosti i treninge klubova. Uz to sve nudimo i posebne programe koji imaju specifične ciljeve kao što su Change For Life -8 kg u 8 tjedana, Strengthlifting, Glutelab i Offseason.</p>

				<p class="my-3">Svaki trening je vođen od strane trenera koji su educirani, ispravljaju pogreške i podižu vaše granice. Treneri se stalno educiraju i sve što radimo ima znanstveni “background”. Također, možemo se pohvaliti i da imamo velik asortiman rekvizita i opreme u skladu sa svjetskim trendovima. </p> 
				
				
				<div class="d-flex align-items-center mt-4">
					<span class="bg-dark p-3">
						<img src="slike/fo_logo.png" class="height-50 object-fit-contain">
					</span>
					<h4 class="text-success mx-4">FizioOne</h4>
					
				</div>
				<p class="my-3">Najefikasnije svjetske tehnike, ortopedski i neurološki pregledi, rehabilitacija sportskih ozljeda, rehabilitacija post-operativnih stanja, oporavak nakon sportskih natjecanja i treninga, tretmani i masaže. Sve navedeno uz visok znanstveni pristup s čvrstim činjenicama o tome što zapravo pomaže i kako naše tijelo funkcionira. </p>
				
				
			</div>
			
			<div class="col-md-6 p-3">
				<img src="slike/ekipa.jpg">
				
				<h3 class="my-3">Naša svrha i misija je pružiti najkvalitetniji trening, educirati ljude o važnosti vježbanja, prehrane, spavanja, pomoći im u ostvarenju njihovih ciljeva! Želimo da Vrbovec konačno može reći <q>Imamo sve što treba!</q> - Najbolje moguće uvjete za trening kako bi sa 50,60 godina i dalje živjeli punim plućima! </h3>
			</div>
			
			
		</div>
	</div>

	
</div>

<div class="bg-warning bg-warning bg-opacity-25">

	<div class="container py-4">
		<div class="row">
			<div class="col-md-4 p-3">
				<h5>HealthClub</h5>
				<div class="my-3">
					<a target="_blank" href="https://www.facebook.com/healthclubcentar" class='btn btn-outline-secondary m-1'><i class="fa-brands fa-facebook-f"></i> healthclubcentar</a>
					<a target="_blank" href="https://www.instagram.com/healthclub_centar/" class='btn btn-outline-secondary m-1'><i class="fa-brands fa-instagram"></i> healthclub_centar</a>
					<a target="_blank" href="https://www.youtube.com/channel/UCObAZDOnC-ykxgI5z6Ri0CQ" class='btn btn-outline-secondary m-1'><i class="fa-brands fa-youtube"></i> HealthClub Vrbovec</a>
				</div>
			</div>
			<div class="col-md-4 p-3">
				<h5>Basic Gym One Vrbovec</h5>
				<div class="my-3">
					<a target="_blank" href="https://www.facebook.com/basicgym1.vrbovec" class='btn btn-outline-danger m-1'><i class="fa-brands fa-facebook-f"></i> basicgym1.vrbovec</a>
					<a target="_blank" href="https://www.instagram.com/basicgym1.vrbovec/" class='btn btn-outline-danger m-1'><i class="fa-brands fa-instagram"></i> basicgym1.vrbovec</a>
					
				</div>
			</div>
			<div class="col-md-4 p-3">
				<h5>Fizio One</h5>
				<div class="my-3">
					<a target="_blank" href="https://www.facebook.com/fizioone" class='btn btn-outline-success m-1'><i class="fa-brands fa-facebook-f"></i> fizioone</a>
					<a target="_blank" href="https://www.instagram.com/fizioone/" class='btn btn-outline-success m-1'><i class="fa-brands fa-instagram"></i> fizioone</a>
					
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container py-5" id="kontakt">
	<h3>Ostani u kontaktu s nama!</h3>
	<div class="row">
		<div class="col-md-6 p-2">
			<h6 class="text-success px-2">Pošalji nam poruku</h6>
			<p class="px-2">Jednostavno nas kontaktiraj putem obrasca i očekuj odgovor već danas. Obavezno unesite email adresu kako Vas možemo povratno kontaktirati. </p>
			<form method="post" action="" id="form">
				<div class="row  p-1 m-0">
					<div class="col-md-6 p-1">
						<input type="text" name="ime_prezime" placeholder="Vaše ime i prezime" class="form-control" value="<?php echo $ime_prezime; ?>">
						<?php echo $ime_prezimeErr; ?>
					</div>
					<div class="col-md-6 p-1">
						<input type="text" name="email" placeholder="Email adresa" class="form-control" value="<?php echo $email; ?>">
						<?php echo $emailErr; ?>
					</div>
					<div class="col-md-6 p-1">
						<select name="zanima" class="form-control">
							<option value='' disabled selected>Zanima me</option>
							<option <?php if($zanima == 'Grupni treninzi') echo "selected"; ?> value='Grupni treninzi'>Grupni treninzi</option>
							<option <?php if($zanima == 'Individualni treninzi') echo "selected"; ?> value='Individualni treninzi'>Individualni treninzi</option>
							<option <?php if($zanima == 'Specijalni programi') echo "selected"; ?> value='Specijalni programi'>Specijalni programi</option>
							<option <?php if($zanima == 'Seminari') echo "selected"; ?> value='Seminari'>Seminari</option>
							<option <?php if($zanima == 'Dijagnostika sposobnosti') echo "selected"; ?> value='Dijagnostika sposobnosti'>Dijagnostika sposobnosti</option>
							<option <?php if($zanima == 'Kondicija') echo "selected"; ?> value='Kondicija'>Kondicija</option>
							<option <?php if($zanima == 'Rehabilitacija') echo "selected"; ?> value='Rehabilitacija'>Rehabilitacija</option>
							<option <?php if($zanima == 'Ostalo') echo "selected"; ?> value='Ostalo'>Ostalo</option>
						</select>
					</div>
					<div class="col-md-6 p-1">
						<input type="text" name="broj_mobitela" placeholder="Broj mobitela" class="form-control" value="<?php echo $broj_mobitela; ?>">
						<?php echo $broj_mobitelaErr; ?>
					</div>
					
					<div class="col-md-12 p-1">
						<textarea name="poruka" class="form-control" placeholder="Napišite Vašu poruku ovdje"><?php echo $poruka; ?></textarea>
						<?php echo $porukaErr; ?>
					</div>
					<div class="col-md-12 p-1">
						<div class="form-check form-switch p-0 m-0">
							<label class="form-check-label m-0 p-0 d-block" for="flexSwitchCheckDefault">Označite da prihvaćate <a href='politika_privatnosti.php' target='_blank'>pravila privatnosti</a> kako bismo mogli obraditi Vaše podatke.</label>
							
							<input class="form-check-input ms-0" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="pravila_privatnosti">
							<?php echo $pravila_privatnostiErr; ?>
						</div>
					</div>
					
					<div class="col-md-12 p-1 d-flex justify-content-end">
						<input type="submit" name="posalji" value="Pošalji" class="btn btn-success px-5 g-recaptcha" 
						data-sitekey="6Lf4v8shAAAAALd-tNPK9E5zYML5St99wYDkrsBI" 
						data-callback='onSubmit' 
						data-action='submit'>
					</div>
					
					<div class="col-md-12 p-1 d-flex justify-content-end">
						<div>
							<?php echo "<div class='p-3'>$captchaErrr</div>"; ?>
							<?php echo "<div class='p-3'>$message</div>"; ?>
						</div>
					</div>
					
				</div>
			</form>
		</div>
		
		<div class="col-md-6 p-2">
			<h6 class="text-success px-2">Kontaktiraj nas</h6> 
			<p class="px-2">Rado ćemo porazgovarati o bilo kojoj temi i pomoći s bilo kojim problemom pa nas kontaktiraj klikom na broj ili email adresu priloženu ispod ove poruke. </p>
			
			<div class="row  p-1 mx-0 my-2">
				<div class="col-xl-6 col-lg-12 p-1">
					<a href='tel:+385989520746' class="m-1 d-flex align-items-center">
						<span class="btn btn-outline-success rounded-circle me-2 height-50 width-50 object-fit-contain d-flex justify-content-center align-items-center"><i class="fa-solid fa-phone"></i></span>
						<h6 class="link-dark fw-bold">+385 98 952 0746</h6>
						
					</a>
				</div>
				
				<div class="col-xl-6 col-lg-12 p-1">
					<a href='mailto:healthclub.vrbovec@gmail.com' class="m-1 d-flex align-items-center">
					
						<span class="p-3 btn btn-outline-success rounded-circle me-2 height-50 width-50 object-fit-contain d-flex justify-content-center align-items-center "><i class="fa-solid fa-envelope"></i></span>
						<h6 class="link-dark fw-bold">healthclub.vrbovec@gmail.com</h6>
					</a>
				</div>
			</div>
			
			<hr>
			
			<h6 class="text-success px-2 mt-2">Posjeti nas</h6> 
			<p class="px-2">Dođi u prostore Health Club-a, popričaj sa našim trenerima i fizioterapeutima, promijeni stil života i uvjeri se za što je sve tvoje tijelo sposobno. </p>
			
			
			<a href='https://goo.gl/maps/EDGL7CuKuXNR8k29A' target='_blank' class="m-1 p-2 d-flex align-items-center">
			
				<span class="p-3 btn btn-outline-success rounded-circle me-2  height-50 width-50 object-fit-contain d-flex justify-content-center align-items-center"><i class=" fa-solid fa-location-dot"></i></span>
				<h6 class="link-dark fw-bold">Zagrebačka 25a, Vrbovec</h6>
			</a>
		</div>
			
		
	</div>
</div>





<div class="container-fluid p-0 m-0">
	<div class="row m-0 p-0">
		<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg.jpg" class="h-100 object-fit-cover">
		</div>

		<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg7.jpg" class="h-100 object-fit-cover">
		</div>
		<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg6.jpg" class="h-100 object-fit-cover">
		</div>
		<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg8.jpg" class="h-100 object-fit-cover">
		</div>
		<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg11.jpg" class="h-100 object-fit-cover">
		</div>
				<div class="col-lg-2 col-md-4 col-sm-6 m-0 p-0">
			<img src="slike/mg9.jpg" class="h-100 object-fit-cover">
		</div>
	</div>
</div>



<!--

<img src="slike/icon-02.svg" class="height-100 width-100 object-fit-contain rounded-circle p-1">
<img src="slike/icon-03.svg" class="height-100 width-100 object-fit-contain rounded-circle p-1">
<img src="slike/icon-04.svg" class="height-100 width-100 object-fit-contain rounded-circle p-1">
<img src="slike/icon-05.svg" class="height-100 width-100 object-fit-contain rounded-circle p-1">
-->
<div class="shadow-sm py-3">
	<div class="container">
		<h2 class="text-success text-start mt-4 ps-2">Vlasnik: <span class="text-dark">Matija Grežina</span></h2>
		<p class="fw-bold ps-2 mb-4">Veliku važnost pridodaje vlastitom obrazovanju kako bi svojim klijentima pružio što bolje rezultate.</p>
		<div class="row">
			<div class="h-auto row row-cols-lg-2 d-flex align-items-stretch">	
				
				<div class="p-4">
					
					<p class="shadow-sm d-flex h-100 align-items-center p-3 border-start border-success border-5 bg-warning bg-opacity-25">Počevši 2020. s otvaranjem HealthClub centra od početka smo imali već ranije spomenutu viziju sjedinjenja treninga i rehabilitacije u jednom. Cijelu priču pokrenuo je osnivač i vlasnik HealthClub centra Matija Grežina.</p>
				</div>
		
			
			
				<div class="p-4 ">
					<p class="shadow-sm d-flex h-100 align-items-center p-3 border-start border-danger border-5">Matija je rođen u malom selu pokraj Vrbovca zvanom Samoborec. Osnovnu školu pohađao je u Vrbovcu gdje je završio i Opću gimnaziju. Nakon završetka srednje škole upisao je studij fizioterapije na Zdravstvenom Veleučilištu u Zagrebu. Matija je nakon završetka studija odradio staž u Poliklinici za reumatske bolesti Dr. Drago Čop i KBC Sestre milosrdnice.</p>
				</div>
			
			
				<div class="p-4 ">
					<p  class="shadow-sm d-flex h-100 align-items-center p-3 border-start border-secondary border-5">Već za vrijeme fakulteta počeo je volontirati u nogometnim klubovima i skupljati znanje starijih kolega te je dodatno iskustvo rada stekao kroz dosadašnji rad u struci i mnogobrojne edukacije, seminare i mentorstva.</p>
				</div>
				
				<div class="p-4 ">
					<p  class="shadow-sm d-flex h-100 align-items-center p-3 border-start border-warning border-5 bg-warning bg-opacity-25">Cyriax - manualno ortopedska tehnika, Ergon IASTM - mobilizacija mekog tkiva, Dry Needling - terapijska procedura opuštanja tkiva, Medical Flossing - opuštanje tkiva, Neurokinetic Therapy(NKT) - primjena teorije motorne kontrole i neuroznanosti - neki su od seminara i edukacija koje je Matija završio te dodatno seminare na temu vježbanja i treninga poput Basic Pro certifikacije, Kettlebell seminara, Bodyweight seminara i ostalih mentorstva iz svijeta treninga i rehabilitacije.</p>
				</div>
			</div>
			
		</div>

	</div>	
</div>

<div class="row row-cols-lg-4 row-cols-md-2 row-cols-sm-2  p-0 m-0">
	<img src="slike/slika1.jpeg" class="m-0 p-0">
	<img src="slike/slika7.jpeg" class="m-0 p-0">
	<img src="slike/slika3.jpeg" class="m-0 p-0">
	<img src="slike/slika19.jpeg" class="m-0 p-0">
</div>

<!--
<div class="container my-5">
	<h3>Naši djelatnici</h3>
	<div class="row row-cols-lg-2 row-cols-md-1">
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/grezina.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Matija Grežina</h5>
				<p class="display-6 text-success text-center">GAZDA</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
		
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/list.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Martin List</h5>
				<p class="display-6 text-success text-center">ZAVODNIK</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
		
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/benedik.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Filip Benedik</h5>
				<p class="display-6 text-success text-center">TATA</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
		
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/pisacic.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Anja Pisačić</h5>
				<p class="display-6 text-success text-center">MATEMATIČARKA</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
		
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/domagoj.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Domagoj Badenić</h5>
				<p class="display-6 text-success text-center">MANEKEN</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
		
		<div class="row my-3">
			<div class="col-md-6 px-2 py-3">
				<img src="slike/ivica.png">
			</div>
			<div class="col-md-6 px-2 py-3">
				<h5 class="fw-bold text-center">Ivica Grgoić</h5>
				<p class="display-6 text-success text-center">ŠARMER</p>
				<p>Pellentesque faucibus cursus nisi eget scelerisque. Praesent commodo, nulla et lobortis venenatis Nam finibus turpis eu libero placerat condimentum. Quisque eu pharetra arcu, ut faucibus lacus. Proin varius orci id justo sollicitudin dapibus. Nam euismod ac tellus nec mattis.Nam finibus</p>
			</div>
		</div>
	</div>
</div>
-->
 <script src="https://www.google.com/recaptcha/api.js"></script>
 <script>
   function onSubmit(token) {
     document.getElementById("form").submit();
   }
 </script>
 <script>
	document.addEventListener("DOMContentLoaded", function(event) { 
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
</script>
<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>
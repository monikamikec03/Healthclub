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
setlocale(LC_ALL, 'hr_HR.utf-8');	
date_default_timezone_set('Europe/Zagreb');

	function test_input($data) {
	  $data = trim($data);
	  $data = strip_tags($data); 
	  $data = htmlspecialchars($data);
	return $data;}	 


$upload_directory = "https://" . $_SERVER['SERVER_NAME'] . "/file/";


if($_POST['submit']){
  
    $emails = $_POST["send_to"];
	if(empty($emails)) $send_toErr = "<p class='alert alert-danger'>Morate odabrati email adresu.</p>";
	
	$sadrzaj = html_entity_decode($_POST["editor1"]);
	if(empty($sadrzaj)) $sadrzajErr = "<p class='alert alert-danger'>Sadržaj maila ne smije biti prazan.</p>";
	
	$naslov_maila = $_POST["naslov_maila"];
	if(empty($emails)) $naslov_mailaErr = "<p class='alert alert-danger'>Niste naveli naslov maila.</p>";


	
	if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"]["size"] != 0) {				
		$target_dir = "../file/";
		$file_name = $_FILES['slika']['name'];
		$size = $_FILES['slika']['size']; 
		$target_file = $target_dir . basename($_FILES["slika"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


		$file_name = $_FILES['slika']['name'];  
		$temp_name = $_FILES['slika']['tmp_name'];  
		$file_type = $_FILES['slika']['type'];
	    $base = basename($file_name);
		$file = $temp_name;
        $content = chunk_split(base64_encode(file_get_contents($file)));
        $uid = md5(uniqid(time()));  //unique identifier
		
		if ($_FILES["slika"]["size"] > 10000000) {
			$slikaErr = "<p class='alert alert-danger'>Dokument je prevelik, mora biti manji</p>";
		}			
		else{	
			$dan = date("d.m.Y_");
			$vrijeme_1 = (MD5(date("H:i:s")));
			$vrijeme = substr($vrijeme_1, 0, 10);
			$putanja = $upload_directory . $dan . $vrijeme . "." .  $imageFileType;
			$putanja_file = "../file/" . $dan . $vrijeme . "." . $imageFileType;

			if (!move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja_file)){
				$slikaErr = "<p class='alert alert-danger'>Nije dobar direktori za spremanje dokumenta</p>";
			}
		}
	}
	
  
    
	$encoded_content = "<a href='$putanja' download>$file_name</a>";

	
	
	$message = "<html><head><title>$naslov_maila</title></head><body>";
	$message .= "$sadrzaj";
	$message .="</body></html>";
	$message .= $encoded_content; // Attaching the encoded file with email
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "Reply-To: ".$_SESSION["email"]. "\r\n";
	$headers .= "From: " . $_SESSION["email"] . "\r\n";
	
	if(empty($slikaErr) && empty($send_toErr) && empty($sadrzajErr) && empty($naslov_mailaErr)){
		foreach($emails as $email){
			
			if(mail($email,$naslov_maila,$message,$headers)){
				$sql ="INSERT INTO file_email (`primatelj`, `naslov_maila`, `sadrzaj`, `naziv_file`, `putanja`, `autor_id`)
				VALUES ('$email', '$naslov_maila', '$sadrzaj', '$file_name', '$putanja', {$_SESSION['idKorisnika']});";
						
				if (mysqli_query($veza, $sql)){
					header("location:mail.php");
				}
				else{
					$porukaErr = "<p class='alert alert-danger'>Mail je poslan no nije spremljen u bazu.</p>"; 					
				}
			}
			else{
				$porukaErr = "<p class='alert alert-danger'>Mail nije poslan, pokušajte ponovno.</p>";
			}
		}
	}
	else $porukaErr = "<p class='alert alert-danger'>Pogledajte jeste li sve ispravno unijeli.</p>";
}		
include('zaglavlje.php'); 	
		
?>


        


	<div id="sadrzaj" class='h-100'>
	
	

	
		<form class="" method="post" action=""  enctype="multipart/form-data">
			<div class="d-flex justify-content-between flex-wrap align-items-center">
				<h2>Slanje maila - samo aktivni korisnici BasicGymOne</h2>
		
				<div class='d-flex flex-wrap'>	
					<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Pošalji"> 		
					<a class='btn btn-danger m-1' href="mail.php">✖ Odustani</a>			
				</div>
			</div>
			


			<div class="row">
				<div class="col-lg-5 col-md-6 h-100">
					<label class='my-1'>* Korisnici:</label>
					<div class='form-control p-0 my-1'>
						<div class='p-2 d-flex bg bg-light flex-wrap'>
							
							<button onclick='oznaci_sve(this)' class='btn btn-warning mx-2'>Označi sve</button>
						</div>
						<div class='d-flex flex-column multiselect' id="myTable">
							<?php
							$sql = ("SELECT DISTINCT(id_korisnik), korisnik_id, mail, naziv_korisnika, datum_od, datum_do FROM korisnici, clanstva WHERE mail != ''
							AND korisnik_id = id_korisnik
							AND CURDATE() BETWEEN datum_od AND datum_do
							ORDER BY naziv_korisnika ASC");
							$rezultat = mysqli_query($veza, $sql);	?> 	 
							<?php
							while ($redak = mysqli_fetch_array($rezultat))
							{
								$id = $redak['id_korisnik'];
								$mail_korisnika = $redak["mail"];
								$naziv_korisnika = $redak["naziv_korisnika"];	
							?>
							
							<div class='d-flex align-items-center px-2 py-1 multiselect_option' id='id_<?php echo $id; ?>' onclick='oznaci_mail(this.id)'>
								<input class='form-check-input' type='checkbox' name='send_to[]' value='<?php echo $mail_korisnika; ?>'>
								<span class='mx-3'><?php echo $naziv_korisnika . '<span class="text-secondary mx-4">' .  $mail_korisnika; ?></span></span>
							</div>
							<?php
							}
							
								
		
							
							
								
							?>
						</div>
					</div>
					<?php echo $send_toErr;?>				
				</div>	
				
				<div class="col-lg-7 col-md-6">
					<div class="row">
						<div class="col-md-12">
							<label class='my-1'>* Naslov maila:</label>
							<input type="text" name="naslov_maila" value="<?php echo $naslov_maila; ?>" autocomplete="off" class='form-control my-1'>
							<?php echo $naslov_mailaErr;?>
						</div>	
				
						<div class='col-md-12'>
							<label class='my-1'>* Sadržaj maila:</label>
							<textarea name="editor1" id="editor" rows="15" class='my-1'>
								<?php echo $sadrzaj; ?>
							</textarea>
							<?php echo $sadrzajErr;?>				
						</div>
				
						<div class='col-md-12'>
							<label class="btn btn-secondary my-1">
								<input type="file" name='slika' class='d-none' onChange='getoutput(this.value)'/>
								Učitaj datoteku
							</label>			
							<span id='filename'></span>
							<?php echo $slikaErr; ?>
						</div>
					</div>
					
			
				</div>
				
			</div>
  			<?php echo $porukaErr; ?>
		</form>
	</div>

<script>
	CKEDITOR.replace('editor1', {
		filebrowserUploadUrl: '../ckeditor/ck_upload.php',
		filebrowserUploadMethod: 'form'
	});
	
	function oznaci_mail(val){

		let div = document.getElementById(val);
		let input = div.childNodes[1];
		
		if(div.classList.contains('active')){
			div.classList.remove('active');
			input.checked = false;
		}
		else{
			div.classList.add('active');
			input.checked = true;
		}
		
		
	}
	function oznaci_sve(val){
		
		event.preventDefault();
		let divs = document.querySelectorAll('.multiselect_option');
		
		
		if(val.innerHTML == 'Označi sve'){
			
			for(let i = 0; i < divs.length; i++){	
				let input = divs[i].childNodes[1];		
				divs[i].classList.add('active');
				input.checked = true;
			}
			val.innerHTML = 'Poništi';
		}
		else{
			for(let i = 0; i < divs.length; i++){	
				let input = divs[i].childNodes[1];		
				divs[i].classList.remove('active');
				input.checked = false;
			}
			val.innerHTML = 'Označi sve';
		}
		
	}

	function getoutput(val) {
		let array = val.split("\\");
		let putanja = array.slice(-1);
		let filename = putanja[0];
		document.querySelector('#filename').innerHTML = filename;
    }
</script>


</body>
</html> 
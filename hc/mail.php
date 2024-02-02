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

$br = 0;

$danas = date("d.m.Y");

	$mail = "SELECT COUNT(*) AS broj_mailova FROM file_email";		
	$broj_mailova = mysqli_query($veza, $mail);	
	$mailovi = mysqli_fetch_array($broj_mailova);
	$broj= $mailovi['broj_mailova'];		
			
				
	$br = 0;
	$sql = "SELECT * FROM file_email, poslodavac_user
	WHERE id_user = autor_id
	ORDER BY datum_unosa_file DESC";
	$rezultat = mysqli_query($veza, $sql);
	while ($redak = mysqli_fetch_array($rezultat))
	{
		$br++;
		$id = $redak["id_file"];
		$primatelj = $redak["primatelj"];
		$naslov_maila = $redak["naslov_maila"];
		$datum_slanja = date( "d.m.Y. \u H:i:s", strtotime($redak["datum_unosa_file"]));
		$autor = $redak["puno_ime_poslodavac_user"];
		
		$korisnik = "SELECT * FROM korisnici WHERE mail = '$primatelj' ";
		if(mysqli_num_rows(mysqli_query($veza, $korisnik)) > 0){
			$row = mysqli_fetch_assoc(mysqli_query($veza, $korisnik));
			$id_korisnik = $row["id_korisnik"];
			$naziv_korisnika = $row["naziv_korisnika"];
			$prikaz_korisnika = "prikaz_korisnik.php?id=$id_korisnik";
		}
		else{
			$korisnik = "SELECT * FROM poslodavac_user WHERE email_poslodavac_user = '$primatelj' ";
			if(mysqli_num_rows(mysqli_query($veza, $korisnik)) > 0){
				$row = mysqli_fetch_assoc(mysqli_query($veza, $korisnik));
				$id_korisnik = $row["id_user"];
				$naziv_korisnika = $row["puno_ime_poslodavac_user"];
				$prikaz_korisnika = "../poduzetnik/prikaz_poslodavac_user.php?id_user=$id_korisnik";
			}
		}


	$display_block_popis .= "
		<tr>
			<td>$br.</td>
			<td>
				<a href='$prikaz_korisnika'>$naziv_korisnika - $primatelj</a>
			</td>					
			<td><a href='' onclick='open_mail(mail_$id)'><b>$naslov_maila </a></td>		
			<td>$datum_slanja</td>
			<td>$autor</td>
		 </tr>";
					
					
		
								
										
	};//zatvaram while fetch popisa novosti



include("zaglavlje.php"); 	
?>
<div class='container-fluid flex-grow-1'>	
	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Pošta: <?php echo $broj ?></h2>
		<div class='d-flex flex-wrap align-items-center'>
			<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži..."> 
			<?php 
			if($_SESSION["idRole"] == "15588" or $_SESSION["idRole"] == "22")
				echo "<a class='btn btn-primary m-1' href='slanje_maila.php'> + pošalji mail</a>"; 
			?>
		</div>
	</div>

	<div class='table-responsive'>		
		<table class='table table-hover table-stripped border-light ' id="table_id"> 
			<thead>
				<tr>
					<th></th>
					<th>Primatelj</th>				
					<th>Mail</th>												
					<th>Datum slanja</th>
					<th>Poslao</th> 					
				</tr>
			</thead>
			<tbody id="myTable">	
				<?php echo $display_block_popis; ?> 
			</tbody>
		</table>
	</div>
</div>	
<div class="container-dark">
	
	<?php
	$sql = "SELECT * FROM file_email, poslodavac_user
	WHERE id_user = autor_id
	ORDER BY datum_unosa_file DESC";
	$rezultat = mysqli_query($veza, $sql);
	while ($redak = mysqli_fetch_array($rezultat))
	{
		$br++;
		$id = $redak["id_file"];
		$primatelj = $redak["primatelj"];
		$naslov_maila = $redak["naslov_maila"];
		$naziv_file = $redak["naziv_file"];
		$putanja = $redak["putanja"];
		$sadrzaj = html_entity_decode($redak["sadrzaj"]);
		$datum_slanja = date( "d.m.Y. \u H:i:s", strtotime($redak["datum_unosa_file"]));
		$autor = $redak["puno_ime_poslodavac_user"];
		
		
		
		$korisnik = "SELECT * FROM korisnici WHERE mail = '$primatelj'";
		if(mysqli_num_rows(mysqli_query($veza, $korisnik)) > 0){
			$row = mysqli_fetch_assoc(mysqli_query($veza, $korisnik));
			$id_korisnik = $row["id_korisnik"];
			$naziv_korisnika = $row["naziv_korisnika"];
			$prikaz_korisnika = "prikaz_korisnik.php?id=$id_korisnik";
		}
		else{
		
			$korisnik = "SELECT * FROM poslodavac_user WHERE email_poslodavac_user = '$primatelj' ";
			if(mysqli_num_rows(mysqli_query($veza, $korisnik)) > 0){
				$row = mysqli_fetch_assoc(mysqli_query($veza, $korisnik));
				$id_korisnik = $row["id_user"];
				$naziv_korisnika = $row["puno_ime_poslodavac_user"];
				$prikaz_korisnika = "../poduzetnik/prikaz_poslodavac_user.php?id_user=$id_korisnik";
			}
		}
		
		
	?>
	<div class="prikaz_mail shadow-sm" id="mail_<?php echo $id; ?>">
	
			<div class="d-flex justify-content-between align-items-center p-3 bg-light">
				
				<h2><?php echo $naslov_maila; ?></h2>
				<button class='btn btn-primary' onclick="close_mail(mail_<?php echo $id; ?>)">
					<span class='font-weight-bold'>X</span>
				</button>
			</div>
			
			<div class="mail row p-3">
				<div class="col-md-9">
					<?php echo $sadrzaj; ?>
				</div>
				<div class="col-md-3 pl-3">
					<h4>Privitak</h4>
					<a href="<?php echo $putanja; ?>" target="_blank"><?php echo $naziv_file; ?></a>
					</a><br><br>
					
					<h4>Primatelj</h4>
					<a href="<?php echo $prikaz_korisnika; ?>" target="_blank"><?php echo $naziv_korisnika; ?></a><br><br>
					
					<h4>Poslao</h4>
					<p><?php echo $autor; ?></p><br>
					
					<h4>Datum slanja</h4>
					<p><?php echo $datum_slanja; ?></p>
					
				</div>
			</div>
		
	</div>
	<?php
	}
	?>
</div>

	
<script>
	function close_mail(id) {
		id.style.display = "none";	
		document.querySelectorAll(".container-dark")[0].style.display = "none";
	}
	function open_mail(id){
		event.preventDefault();
		id.style.display="block";
		document.querySelectorAll(".container-dark")[0].style.display = "block";
	}
</script>

	<script type="text/javascript" language="javascript">
		$(document).ready(function(){
		  $("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#myTable tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		  });
		});
	</script>
	
	<script>
		$(document).ready(function() {
			$('#table_id').DataTable({			
				"paging":   false,
				"searching": false,
				"info":     false,
				"order": [],				
			});
		});		
	</script>

<?php
include("podnozje.php");
?>
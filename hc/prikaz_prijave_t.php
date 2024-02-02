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
			$sql = ("SELECT * FROM  prijava_probni_trening, artikli_popis, jedinica_mjere
			WHERE id_prijave = $id
			AND id_artikla = artikl
			AND id_jed_mjere = jed_mjere");
			$rezultat = mysqli_query($veza, $sql);
			if($redak = mysqli_fetch_array($rezultat))
			{
						
				$id_prijave = $id= $redak['id_prijave'];		 
				$ime_prezime = $redak['ime_prezime'];  
				$email = $redak['email'];
				$broj_mobitela = $redak['broj_mobitela'];
				$godine = $redak['godine'];  
				$naziv_artikla = $redak['naziv_artikla'] . ' - ' . $redak["naziv_jed_mjere"];  
				$popis_grupa = $redak["popis_grupa"];
				$ciljevi = $redak['ciljevi'];  
	 
				$iskustvo = $redak['iskustvo'];
				
			}
	}
			
include("zaglavlje.php"); 	
?>
	

<div id="sadrzaj">
	

	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Prikaz prijave: <?php echo $ime_prezime; ?></h2>

		<div class='d-flex flex-wrap justify-content-end align-items-center'>
			<a  class='btn btn-primary m-1' href='unos_korisnik.php?id_prijave_t=<?php echo $id; ?>'><i class="fa-solid fa-user-plus"></i> Unesi u korisnike</a>
			
			<a class='btn btn-warning m-1' href='probni_trening.php'>🡸 Natrag</a>		
		</div>			
	</div>
	<div class='row'>

		<div class='col-lg-3 col-md-6'>
			<label>Ime i prezime:</label>
			<span class='font-weight-bold form-control'><?php echo $ime_prezime; ?></span>				
		</div>
		

		<div class='col-lg-3 col-md-6'>
			<label>Godine:</label>					
			<span class='font-weight-bold form-control'><?php echo $godine; ?></span>					
		</div>		
			
		<div class='col-lg-3 col-md-6'>
			<label>Email:</label>
			<span class='font-weight-bold form-control'><?php echo $email; ?></span>					
		</div>
		
		<div class='col-lg-3 col-md-6'>
			<label>Broj mobitela:</label>
			<span class='font-weight-bold form-control'><?php echo $broj_mobitela; ?></span>				
		</div>	
		

		<div class='col-lg-3 col-md-6'>
			<label>Željena vrsta treninga:</label>
			<span class='font-weight-bold form-control'><?php echo $naziv_artikla . $popis_grupa; ?></span>		
		</div>	

		<div class='col-lg-3 col-md-6'>
			<label>Jeste li iskusni vježbač?</label>
			<span class='font-weight-bold form-control'><?php echo $iskustvo; ?></span>						
		</div>
		
		<div class='col-lg-3 col-md-6'>
			<label>Koje ciljeve želite ostvariti vježbanjem?</label>
			<span class='font-weight-bold form-control'><?php echo $ciljevi; ?></span>						
		</div>
	
	
	</div>
</div>
</body>
</html> 
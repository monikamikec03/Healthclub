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


	if (isset($_GET["praznici_id"]))    //prikaz podataka za izostanak
	{								
		$id = addSlashes($_GET['praznici_id']);				
		$sql =("SELECT * FROM praznici WHERE praznici_id = $id");	 
		$rezultat = mysqli_query($veza, $sql);					
			while($red = mysqli_fetch_array($rezultat)) 
			{ 
				$id = $red['praznici_id'];	
				$praznik = $red['nazivPraznika'];	
				$datum = strtotime($red['datumPraznika']);	
			}
	}
?>
<div class="container-fluid flex-grow-1">	 
	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Prikaz praznika: <span class='text-primary'><?php echo $praznik; ?></span></h2>
		<div class='d-flex flex-wrap justify-content-end align-items-center'>
			<a class='btn btn-primary m-1' href="rad_unos_praznici.php?praznici_id=<?php echo $id; ?>"><i class='fa fa-edit'></i> Uredi</a>	
			<a class='btn btn-danger m-1' href="brisanje_praznici.php?praznici_id=<?php echo $id; ?>" onclick="return confirm('Potvrdite brisanje.');"><i class='fa fa-trash'></i> Obriši</a>	
			<a class='btn btn-warning m-1' href='praznici.php'>🡸 Natrag</a> 		
		</div>			
	</div>
	<div class='row'>
		<div class='col-md-6'>
			<label class='m-1'>Datum praznika:</label>
			<div class='form-control m-1 bg-light'><?php echo date('d.m.Y.', $datum)?></div>							
		</div>		
		<div class='col-md-6'>
			<label class='m-1'>Naziv praznika:</label> 
			<div class='form-control m-1 bg-light'><?php echo $praznik ?></div>									
		</div>																
				
	</div>	

</div>	
<?php
include("podnozje.php")
?>
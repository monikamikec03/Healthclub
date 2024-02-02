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

$danas = date("d.m.Y");


	$sql = "SELECT * FROM artikli_popis, artikli_cijene, poslodavac_user
	WHERE artikl_id = id_artikla 
	AND id_user = kreirao_cijenu
	ORDER BY cijena_aktivna DESC, datum_unosa_cijene DESC";	
	$res = mysqli_query($veza, $sql);						
	while($red = mysqli_fetch_array($res)) {
		$id = $red['id_artikla'];	 
		$naziv_artikla = $red['naziv_artikla'];  
		$naziv_jed_mjere = $red['naziv_jed_mjere'];  
		$stopa_pdv = $red['stopa_pdv'];  
		$cijena_iznos = $red['cijena_iznos'];  
		$cijena_euro = $red['cijena_euro'];  
		$kreirao = $red['puno_ime_poslodavac_user'];  
		$datum_unosa = date("d.m.Y", strtotime($red['datum_unosa_cijene']));						 
		$aktivno = $red['cijena_aktivna'];
		if($aktivno == 1){
			$klasa = "class='text-success'";	
			$aktivno = 'da';
		}
		else{
			$klasa = "class='text-danger'";							 
			$aktivno = 'ne';
		}
		
					
		$display_block_popis .= "
			<tr>
				<td><b>$id</b></td>		
				<td>$naziv_artikla</td>	
				<td><b><a href='unos_cijene.php?id_cijena=$id'> $cijena_iznos </a></b></td>	
				<td>$cijena_euro</td>			
				<td $klasa>$aktivno</td>			
				<td>$kreirao</td>			
				<td>$datum_unosa</td>			
									
			 </tr>";
						
						
			
									
											
		};

include("zaglavlje.php"); 	
?>
<div id="sadrzaj">	
	<div class='d-flex flex-wrap justify-content-between align-items-center flex-md-row flex-sm-column flex-column'>
		<div class='d-flex align-items-center'>
			<h2>Cijene artikala</h2>
			<input class="form-control my-1 w-auto mx-2" id="myInput" type="text" placeholder="traži ...">
		</div>
		<?php 
		if($_SESSION["idRole"] == "15588" or $_SESSION["idRole"] == "22")
			echo "<a class='btn btn-primary m-1' href='unos_cijene.php'> + dodaj cijenu</a>"; 
		?>	
	</div>

	<div class='table-responsive'>
	
		<table class='table table-hover table-striped'>  
			<thead>
				<tr>
					  <th>ID</th>	
					  <th>Artikl</th>		  
					  <th>Cijena (HRK)</th>		  	  
					  <th>Cijena (€)</th>		  	  
					  <th>Aktivno</th>	  			  
					  <th>Kreirao</th>	  			  
					  <th>Datum_unosa</th>	  			  
				</tr>
			</thead>
			<tbody id="myTable">
				<?php echo $display_block_popis; ?> 
			</tbody>
		</table>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
</body>
</html>
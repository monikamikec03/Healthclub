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


	$sql = "SELECT * FROM artikli_popis, jedinica_mjere
	WHERE jed_mjere = id_jed_mjere 
	ORDER BY artikli_popis.datum_unosa DESC";	
	$res = mysqli_query($veza, $sql);						
	while($red = mysqli_fetch_array($res)) {
		$id = $red['id_artikla'];	 
		$naziv_artikla = $red['naziv_artikla'];  
		$naziv_jed_mjere = $red['naziv_jed_mjere'];  
		$stopa_pdv = $red['stopa_pdv'];  
		$redoslijed = $red['redoslijed'];  
		$datum_unosa = date("d.m.Y", strtotime($red['datum_unosa']));						 
		$aktivno = $red['aktivan'];
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
				<td><b><a href='unos_artikla.php?id_artikla=$id'> $naziv_artikla </a></b></td>	
				<td>$naziv_jed_mjere</td>
				<td $klasa>$aktivno</td>			
				<td>$redoslijed</td>			
									
			 </tr>";
						
						
			
									
											
		};

include("zaglavlje.php"); 	
?>
<div id="sadrzaj">	
	<div class='d-flex flex-wrap justify-content-between align-items-center flex-md-row flex-sm-column flex-column'>
		<div class='d-flex align-items-center'>
			<h2>Popis artikala</h2>
			<input class="form-control my-1 w-auto mx-2" id="myInput" type="text" placeholder="traži ...">
		</div>
		<?php 
		if($_SESSION["idRole"] == "15588" or $_SESSION["idRole"] == "22")
			echo "<a class='btn btn-primary m-1' href='unos_artikla.php'> + dodaj artikl</a>"; 
		?>	
	</div>

	<div class='table-responsive'>
	
		<table class='table table-hover table-striped'>  
			<thead>
				<tr>
					  <th>ID</th>	
					  <th>Naziv</th>		  
					  <th>Jedinica mjere</th>		  	  
					  <th>Aktivno</th>	  			  
					  <th>Redoslijed</th>	  			  
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
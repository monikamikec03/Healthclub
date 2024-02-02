<?php
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/putanja.php");
}
else{
	 echo "<script> window.location.replace('prijava.php');</script>";
}

?>

<div id="sadrzaj">
	<div class='d-flex flex-wrap justify-content-between align-items-center'>		
		<h2>Raspored</h2>	
		<div class='d-flex flex-wrap'>
			<a class="btn btn-success bg-success d-flex justify-content-center align-items-center m-1" href="index.php?prikaz=raspored"><i class="fa-solid fa-rectangle-list"></i></a>
			<a class="btn btn-outline-success d-flex justify-content-center align-items-center m-1 py-1" href="index.php?prikaz=sihterica"><i class="fa-solid fa-calendar-days"></i></a>
			<input class="form-control my-1 w-auto m-1" id="myInput" type="text" placeholder="traži ..."> 			
			<a class='btn btn-primary m-1' href='rad_unos_rad.php'>+ dodaj</a>
		</div>
	</div> 
	<table id="table_id" class="display" style="width:100%">
		<thead>		
			<tr>
					<th></th>
					<th>Grupa</th>						
					<th>Artikl</th>											  				
															  				
					<th>Sat / tjedan</th>									
					<th>Start</th>				
					<th>Kraj</th>	
					
			</tr>
		</thead>
		<tbody id="myTable"> 
			<?php
			$br = 1;
			$sql = "SELECT * FROM evidencije, poslodavac_user, artikli_popis, jedinica_mjere, podgrupe
			WHERE evidencije.spremio = poslodavac_user.id_user 
			AND jed_mjere = id_jed_mjere
			AND id_artikla = kategorija
			AND id_podgrupe = rubrika
			ORDER BY redoslijed_podgrupe";
			$rezultat = mysqli_query($veza, $sql);	
			while($red = mysqli_fetch_array($rezultat))
			{	
				$id_artikla = $red['id_artikla'];		
				$id = $red['id_evidencije']; 		 
				$naziv_artikla = $red['naziv_artikla'];	
				$naziv_podgrupe = $red['naziv_podgrupe'];	
				$puni_naziv = $red['puni_naziv'];					
				$datumUnosa = strtotime($red['datumUnosa']);				
				$start = strtotime($red['start']);
				$kraj = strtotime($red['kraj']);						
				$rn_tjedno_sati = $red['tjedno_sati'];					
				$rad_tjedno_sati = ($red['Mon'])+($red['Tue'])+($red['Wed'])+($red['Thu'])+($red['Fri'])+($red['Sat'])+($red['Sun']);				
				$danas = strtotime(date("Y-m-d"));

					
				echo "<tr>";
				echo "<td align='center'> $br </td>";	$br++;
				echo "<td><a href='prikaz_rasporeda.php?id_evidencije=$id'><b>$naziv_podgrupe </b></a></td>";				
				echo "<td>$naziv_artikla - $puni_naziv</td>";
												 
					
				echo "<td>$rad_tjedno_sati </td>";	
				
				echo "<td>".date("d.m.y", $start)."</td>";
				echo "<td>".date("d.m.y", $kraj)."</td>";
				echo "</tr>";
					
				}			
			?>	
			</tbody>		
		</table>
</div>
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
<?php
include("podnozje.php");
?>
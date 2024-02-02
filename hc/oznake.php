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
	 echo "<script> window.location.replace('odjava.php');</script>";
}		
?> 
<div id="sadrzaj">	
	<div class='d-flex justify-content-between flex-wrap align-items-center'>
		<h2>#Oznake</h2>	
		<?php 
			if(in_array($_SESSION['idRole'], [15588]))
			echo "<a  class='btn btn-primary my-2' href='unos_oznake.php'>+ Dodaj </a>"; 
		?>
	</div>
	<div class='table-responsive'>			
		<table class='table table-hover table-striped'>	
			<thead>
				<tr>	
					<th>Br.</th>				
					<th class="w-75">Naziv oznake</th>	
					<th>Prikaži na webu</th>	
			
				</tr>
			</thead>
						
			<?php
			$br = 1;
			$sql = "SELECT * FROM oznake ORDER BY aktivno_web DESC, id_oznake DESC";	
			$rezultat = mysqli_query($veza, $sql); 
			while($red = mysqli_fetch_array($rezultat)){ 
				$id_oznake = $red['id_oznake']; 	
				
				$naziv_oznake = $red['naziv_oznake']; 		
				$aktivno_web = $red['aktivno_web']; 	

				if($aktivno_web == 1){
					$aktivnost = "";
					$boja = "";
				}				
				else{
					$aktivnost = "Ne";
					$boja = "bg-danger bg-opacity-25 text-center";
				}
				
					
				
				echo "<tr>";
				echo "<td align='center'>$br</td>";	$br++;					
				echo "<td><a href='unos_oznake.php?id_oznake=$id_oznake'>$naziv_oznake</a></td>";				
				echo "<td class='$boja'>$aktivnost</td>";				
				echo "</tr>";
				}	
			?>						
		</table>
	</div>
</div>

<?php
include("podnozje.php"); 
?>
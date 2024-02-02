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
?> 
<div id="sadrzaj">	
	<div class='d-flex justify-content-between flex-wrap align-items-center'>
		<h2>Grupe aktivnosti</h2>	
		<?php 
			if(in_array($_SESSION['idRole'], [15588]))
			echo "<a  class='btn btn-primary my-2' href='unos_tipa_aktivnosti.php'>+ Dodaj </a>"; 
		?>
	</div>
	<div class='table-responsive'>			
		<table class='table table-hover table-striped'>	
			<thead>
				<tr>	
					<th>Br.</th>				
					<th>Naziv oznake</th>	
					
			
				</tr>
			</thead>
						
			<?php
			$br = 1;
			$sql = "SELECT * FROM tipovi_aktivnosti ORDER BY naziv_tipa";	
			$rezultat = mysqli_query($veza, $sql); 
			while($red = mysqli_fetch_array($rezultat)){ 
				$id_tipa = $red['id_tipa']; 	
				
				$naziv_tipa = $red['naziv_tipa']; 		
				
				
					
				
				echo "<tr>";
				echo "<td align='center'>$br</td>";	$br++;					
				echo "<td><a href='unos_tipa_aktivnosti.php?id_tipa=$id_tipa'>$naziv_tipa</a></td>";						
				echo "</tr>";
				}	
			?>						
		</table>
	</div>
</div>

<?php
include("podnozje.php"); 
?>
<?php	
session_start();

	if(in_array($_SESSION['idRole'], [15588, 2,22,222,3]))
	{
		require("../include/var.php");			
		require("../include/putanja.php");			
	}
	else
	{			
	header("Location:prijava.php");  
	}
		


include("navigacija.php");		
?> 
<div id="sadrzaj">	
		
	<div class='d-flex justify-content-between flex-wrap align-items-center'>
		<h2 class='m-1'>Pregled usera</h2>	
		<?php 
			if(in_array($_SESSION['idRole'], [15588]))
			echo "<a class='btn btn-primary m-1' href='unos_poduzetnik_user.php'><i class='fa-solid fa-plus'></i> Dodaj </a>"; 
		?>	
	</div>
	<div class='table-responsive'>
	
		<table  class="table table-hover table-striped">
			<thead>
				<tr>	
					<th></th>				
					<th>Ime i prezime</th>					
					<th>Email - User</th>								
					<th>Rola</th>				
					<th>Datum unosa</th>	
					<th>Aktivan</th>			
				</tr>	
			</thead>
			<tbody>
				<?php
				$br = 1;

				$sql = "SELECT * FROM poslodavac_user, role
				WHERE poslodavac_user.role_id = role.id 
				ORDER BY aktivan_user ASC, datum_unosa_user_poslodavac DESC";	
				$rezultat = mysqli_query($veza, $sql); 
				while($red = mysqli_fetch_array($rezultat)){  
					$id = $red['id_user'];			
					$ime_usera = $red['puno_ime_poslodavac_user']; 								 
					$rola = $red['rola'];
									
					$email = $red['email_poslodavac_user']; 		
					$datum = strtotime($red['datum_unosa_user_poslodavac']);
					$aktivan_user = $red['aktivan_user'];
					
						if ($aktivan_user == 1){
							$aktivan = "aktivan";	
						}else if ($aktivan_user == 2){
							$aktivan = "neaktivan";	 
						}
						$color_aktivan = $aktivan_user == 2? 'class="bg bg-warning"':"";		
					
					echo "<tr>";
					echo "<td align='center'>$br</td>";	$br++;				
					echo "<td><a href='prikaz_poslodavac_user.php?id_user=$id'>$ime_usera</a></td>";	  		 
					echo "<td>$email</td>";						
					echo "<td> $rola</td>";				
					echo "<td> ".date('d.m.Y.', $datum)."</td>";	
					echo "<td {$color_aktivan}> $aktivan</td>";		
					echo "</tr>";
					}	
				?>	
			</tbody>
		</table>
	</div>
	
	
</div>

<?php 
include("podnozje.php");
?>
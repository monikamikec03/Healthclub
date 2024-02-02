<?php	
ob_start();
session_start();
$list = 'kategorije';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
	require("../include/putanja.php");	
	require("navigacija.php");
}
else{
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');
?>


<div id="sadrzaj">
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2 class='m-1'>Kategorije</h2>
		<a class='btn btn-primary m-1' href="unos_kategorija.php">+ dodaj kategoriju</a>
	</div>
	<div class='table-responsive'>
		<table class="table table-hover table-striped">	
			<tr>
				<th>#</th>			
				<th>naziv kategorije</th>			
				<th>redoslijed kategorije</th>				
				<th>aktivna kategorija</th>			
			</tr>								
					
		<?php	
		$sql = "SELECT * FROM kategorije
		ORDER BY redoslijed";		
			$rezultat = mysqli_query($veza, $sql); 
			while($red = mysqli_fetch_array($rezultat)){
				$id = $red['id_kategorije'];
				$redoslijed_kategorija = $red['redoslijed']; 			
				$naziv = $red['naziv_kategorije'];  
					if ($red['aktivna_kategorija'] == 1)
					{
						$aktivna_kategorija = "DA";			
					}	
					else
					{
						$aktivna_kategorija = "NE";
					}						
				echo "<tr>";
					echo "<td> $id</td>";			
					echo "<td><a href='unos_kategorija.php?id=$id'>$naziv</a></td>";						
					echo "<td> $redoslijed_kategorija</td>";			
					echo "<td> $aktivna_kategorija</td>";			
				echo "</tr>";
				}	
		?>						
		</table>
	</div>
</div>
<?php
include("podnozje.php");
?>
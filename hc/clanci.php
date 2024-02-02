<?php	
ob_start();
session_start();
$list = 'clanci';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{		
	require("../include/putanja.php");
	
}
else{
	 echo "<script> window.location.replace('prijava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');	
include("navigacija.php");	
?>
<div id="sadrzaj">
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2 class='m-1'>Članci</h2>
		<div class="d-flex flex-wrap">
			<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži...">
			<a class='btn btn-primary m-1' href="unos_clanak.php">+ dodaj članak</a>
		</div>	
	</div>
	<div class='table-responsive p-1'>
		<table class="table table-hover table-striped border-light blog_clanak" id="table_id">  
			<thead>
				<tr>
					<th width="50">Slika</th>	
					<th>Objavljeno</th>		
					<th>Datum objave</th> 					
					<th>Naslov</th>						
					<th>Uvod</th>
					<th>Kategorija</th>				
					<th>Tip teksta</th>				
					<th>Autor</th>				
					<th>Istaknuto</th>
					<th>Komentari omogućeni</th>	 														
					<th>Komentari</th>	 														
					<th>Lajkovi</th>	 														
					<th>Spremio</th>									
				</tr>
			 </thead>
			<tbody id="myTable">
			<?php		
			
				$sql = "SELECT * FROM clanak, kategorije, poslodavac_user, tip_teksta
				WHERE clanak.kategorije_id = id_kategorije
				AND id_user = unos_id
				AND id_tipa = tip_teksta
				ORDER BY clanak.datum_unosa DESC, datum_objave DESC";		
			
				$rezultat = mysqli_query($veza, $sql);				
				if (mysqli_num_rows($rezultat) == 0)
				{
				echo '<tr><td align="middle">Nema članka</td></tr>'; 
				}
				while ($redak = mysqli_fetch_array($rezultat))
				{	
				$idClanka = $redak["id_clanak"];
				$naslov = $redak["naslov_clanka"];
				$naziv_tipa = $redak["naziv_tipa"];
				$ime_autora = $redak["ime_autora"];				
				$uvod = $redak["uvod"];				
				$kategorija = $redak["naziv_kategorije"];										
				$datum = date("d.m.Y.", strtotime($redak["datum_unosa"]));

				$datum_objave1 = ($redak['datum_objave']);			
				if($datum_objave1 == 0000-00-00){
					$datum_objave = "" ;
				}
				else
				{
					$datum_objave = date("Y.m.d.",strtotime($datum_objave1));						
				}					
		
				
				if ($redak["objavljen"] == 1) {
					$objavljen = "Da";
				}
				else{
					$objavljen = "Ne"; 
				}
		
					$color = $objavljen == "Ne"? 'class="text-white bg bg-danger text-center"':'class="text-center"';
				
				
				if ($redak["istaknuto"] == 1){
					$istaknuto = "Da";			
				}	
				else{
					$istaknuto = "Ne";
				}			
					
				if ($redak["omoguci_komentare"] == 1){
					$omoguci_komentare = "Da";			
				}	
				else{
					$omoguci_komentare = "Ne";
				}

				$broji = "SELECT COUNT(id_lajka) AS broj_lajkova FROM lajkovi WHERE clanak_id = $idClanka";
				$resb = mysqli_query($veza, $broji);
				$row = mysqli_fetch_array($resb);
				$broj_lajkova = $row["broj_lajkova"];
				
				$broji = "SELECT COUNT(id_komentara) AS broj_komentara FROM komentari WHERE clanak_id = $idClanka";
				$resb = mysqli_query($veza, $broji);
				$row = mysqli_fetch_array($resb);
				$broj_komentara = $row["broj_komentara"];
				
				$autor = $redak['puno_ime_poslodavac_user'];			
									
				echo "<tr>";
						echo "<td style='padding:2px 0;'>";
							$sql = mysqli_query($veza, "SELECT * FROM slike_naslova
							WHERE clanak_id = $idClanka");     
							if($slika = mysqli_fetch_array($sql))
							{
							$putanja = $slika ['putanja'];	
							echo "<a href='clanak.php?id=$idClanka'><img src='$putanja'/></a>";
							}
						echo "</td>";
						echo "<td {$color}>$objavljen</td>";				
						echo "<td ><a href='clanak.php?id=$idClanka'>" . $datum_objave . "</a></td>";							
						
						echo "<td><a class='cut-text' href='clanak.php?id=$idClanka'>$naslov</a></td>";
						echo "<td><div class='cut-text'>$uvod</div></td>";
						echo "<td>$kategorija</td>";
						echo "<td>$naziv_tipa</td>";						
						echo "<td>$ime_autora</td>";						
						echo "<td{$color_naslov}> $istaknuto</td>";
						echo "<td{$color_vijesti}> $omoguci_komentare</td>";
						
						echo "<td class='text-center'><i class=\"text-secondary fa-solid fa-comment\"></i> $broj_komentara</td>";  
						echo "<td class='text-center'><i class=\"text-success fa-solid fa-heart\"></i> $broj_lajkova</td>";  
						echo "<td>$autor</td>";  					
				echo "</tr>";					
				
				}	
		
				?>	
			</tbody>
		</table>
	</div>	
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
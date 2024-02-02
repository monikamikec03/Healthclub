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
	
		
	if (isset($_GET["id_evidencije"]))    //prikaz podataka 
	{		
		$id = ($_GET["id_evidencije"]);
		$sql = "SELECT * FROM evidencije, poslodavac_user, artikli_popis, jedinica_mjere, podgrupe
		WHERE evidencije.spremio = poslodavac_user.id_user 
		AND jed_mjere = id_jed_mjere
		AND id_artikla = kategorija
		AND id_podgrupe = rubrika
		AND evidencije.id_evidencije = $id
		ORDER BY evidencije.datumUnosa DESC";		
		$rezultat = mysqli_query($veza, $sql);					
		while($red = mysqli_fetch_array($rezultat)){				
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
			
		
			$tjedno = $red['Mon']+$red['Tue']+$red['Wed']+$red['Thu']+$red['Fri']+$red['Sat']+$red['Sun'];	
			
			if($red['mon_dvokratno']==1)	
			{
				$pon_dvokratno = "checked";			
			}	
			if($red['tue_dvokratno']==1)	
			{
				$uto_dvokratno = "checked";			
			}	
			if($red['wed_dvokratno']==1)	
			{
				$sri_dvokratno = "checked";			
			}					
			if($red['thu_dvokratno']==1)	
			{
				$cet_dvokratno = "checked";			
			}
			if($red['fri_dvokratno']==1)	
			{
				$pet_dvokratno = "checked";			
			}
			if($red['sat_dvokratno']==1)	
			{
				$sub_dvokratno = "checked";			
			}			
			if($red['sun_dvokratno']==1)	
			{
				$ned_dvokratno = "checked";			
			}
				
			
			$pon = $red['Mon'];	
			$pon1 = $red['Mon1'];
				$pon_11 = explode(":", $pon1);			
			$pon2 = $red['Mon2'];
				$pon_22 = explode(":", $pon2);				
			$pon3 = $red['Mon3'];
				$pon_33 = explode(":", $pon3);				
			$pon4 = $red['Mon4'];	
				$pon_44 = explode(":", $pon4);				
			$uto = $red['Tue'];	
			$uto1 = $red['Tue1'];
				$uto_11 = explode(":", $uto1);				
			$uto2 = $red['Tue2'];	
				$uto_22 = explode(":", $uto2);				
			$uto3 = $red['Tue3'];
				$uto_33 = explode(":", $uto3);				
			$uto4 = $red['Tue4'];
				$uto_44 = explode(":", $uto4);				
			$sri = $red['Wed'];	
			$sri1 = $red['Wed1'];
				$sri_11 = explode(":", $sri1);				
			$sri2 = $red['Wed2'];
				$sri_22 = explode(":", $sri2);			
			$sri3 = $red['Wed3'];
				$sri_33 = explode(":", $sri3);			
			$sri4 = $red['Wed4'];
				$sri_44 = explode(":", $sri4);			
			$cet = $red['Thu'];	
			$cet1 = $red['Thu1'];
				$cet_11 = explode(":", $cet1);			
			$cet2 = $red['Thu2'];	
				$cet_22 = explode(":", $cet2);			
			$cet3 = $red['Thu3'];
				$cet_33 = explode(":", $cet3);			
			$cet4 = $red['Thu4'];
				$cet_44 = explode(":", $cet4);			
			$pet = $red['Fri'];	
			$pet1 = $red['Fri1'];
				$pet_11 = explode(":", $pet1);				
			$pet2 = $red['Fri2'];
				$pet_22 = explode(":", $pet2);				
			$pet3 = $red['Fri3'];
				$pet_33 = explode(":", $pet3);				
			$pet4 = $red['Fri4'];
				$pet_44 = explode(":", $pet4);				
			$sub = $red['Sat'];	
			$sub1 = $red['Sat1'];
				$sub_11 = explode(":", $sub1);				
			$sub2 = $red['Sat2'];
				$sub_22 = explode(":", $sub2);			
			$sub3 = $red['Sat3'];
				$sub_33 = explode(":", $sub3);			
			$sub4 = $red['Sat4'];	
				$sub_44 = explode(":", $sub4);			
			$ned = $red['Sun'];	
			$ned1 = $red['Sun1'];
				$ned_11 = explode(":", $ned1);			
			$ned2 = $red['Sun2'];
				$ned_22 = explode(":", $ned2);			
			$ned3 = $red['Sun3'];
				$ned_33 = explode(":", $ned3);			
			$ned4 = $red['Sun4'];
				$ned_44 = explode(":", $ned4);			
			
		} 
	}
require("zaglavlje.php"); 
?>	 

<div id="sadrzaj">
	<div class='d-flex flex-wrap justify-content-between align-items-center'>	
		<h2>Prikaz rasporeda:<i> <?php echo $prezime ." ". $ime;?></i></h2>
		<div class='d-flex flex-wrap justify-content-end align-items-center'>
			<a class='btn btn-primary text-white m-1' href='rad_unos_rad.php?id_evidencije=<?php echo $id; ?>'><i class='fa fa-edit'></i>Uredi</a>  
			<a class='btn btn-warning m-1' href='raspored.php'>🡸 Natrag</a> 
					
		</div>	
	</div>

	<div class='row'>		
		<div class='col-md-4'> 
			<div class='m-1'>* Artikl:</div>
			<div class="form-control m-1 bg-light"> <?php echo $naziv_artikla . ' - ' . $puni_naziv; ?></div>
		</div>
		
		<div class='col-md-4'> 
			<div class='m-1'>* Podgrupa:</div>
			<div class="form-control m-1 bg-light"> <?php echo $naziv_podgrupe; ?></div>
		</div>
		
		
	
		
		<div class='col-md-4'> 
			<div class='m-1'>* Start:</div>
			<div class="form-control m-1 bg-light"> <?php echo date("d.m.Y.", $start) ?></div>
		</div>
		
		<div class='col-md-4'> 
			<div class='m-1'>* Kraj: </div>
			<div class="form-control m-1 bg-light"> <?php echo date('d.m.Y.', $kraj) ?></div>
		</div>	
			
		
		
		
	</div>
	
	<div class='table-responsive p-1 my-3'>
		<table class='table table-hover table-striped'>   
			<tr align="center">
				<th width="20%">dan</th>		
				<th>početak</th>
				<th>završetak</th>
				<th>tjedno:</th>
			</tr>
			<tr>
				
				<th class='text-center bg-primary text-light border' colspan='3'>UKUPNO:</th>
				<th class='text-center bg-primary text-light'><?php echo $tjedno ?></th>
			</tr>
			<tr align="center"><td>PONEDJELJAK :</td>  
				<td width="11%"><?php echo $pon_11[0] . ":" . $pon_11[1] ?></td>
				<td width="11%"><?php echo $pon_22[0] . ":" . $pon_22[1] ?></td>				
			
				<td width="11%"><?php echo $pon; ?></td>
			</tr>			
			<tr align="center"><td>UTORAK :</td> 		
				<td width="11%"><?php echo $uto_11[0] . ":" . $uto_11[1] ?></td>
				<td width="11%"><?php echo $uto_22[0] . ":" . $uto_22[1] ?></td>		
				
				<td width="11%"><?php echo $uto ?></td>				
			</tr>
			<tr align="center"><td>SRIJEDA :</td>
				<td width="11%"><?php echo $sri_11[0] . ":" . $sri_11[1] ?></td>
				<td width="11%"><?php echo $sri_22[0] . ":" . $sri_22[1] ?></td>
				
				<td width="11%"><?php echo $sri ?></td>				
			</tr>
			<tr align="center"><td>ČETVRTAK :</td>	
				<td width="11%"><?php echo $cet_11[0] . ":" . $cet_11[1] ?></td>
				<td width="11%"><?php echo $cet_22[0] . ":" . $cet_22[1] ?></td>
				
				<td width="11%"><?php echo $cet ?></td>				
			</tr>
			<tr align="center"><td>PETAK :</td>		
				<td width="11%"><?php echo $pet_11[0] . ":" . $pet_11[1] ?></td>
				<td width="11%"><?php echo $pet_22[0] . ":" . $pet_22[1] ?></td>		
				
				<td width="11%"><?php echo $pet ?></td>				
			</tr>
			<tr align="center"><td>SUBOTA :</td>		
				<td width="11%"><?php echo $sub_11[0] . ":" . $sub_11[1] ?></td>
				<td width="11%"><?php echo $sub_22[0] . ":" . $sub_22[1] ?></td>		
				
				<td width="11%"><?php echo $sub ?></div></td>				
			</tr>
			<tr align="center"><td>NEDJELJA :</td>		
				<td width="11%"><?php echo $ned_11[0] . ":" . $ned_11[1] ?></td>
				<td width="11%"><?php echo $ned_22[0] . ":" . $ned_22[1] ?></td>		
				
				<td width="11%"><?php echo $ned ?></div></td>				
			</tr>   
		</table>

	</div>
</div>	
</body>
</html>
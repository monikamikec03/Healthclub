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


		function test_input($data) {
		  $data = trim($data);
		  $data = strip_tags($data);
		  $data = htmlspecialchars($data);
		return $data;}				

	$idErr = $aktivnostErr = $imeErr = $partnerErr = $lokacijaErr = $radnoMjestoErr = $startErr = $krajErr = $rubrikaErr = $kategorijaErr = $ponErr = $utoErr = $sriErr = $cetErr = $petErr = $subErr = $nedErr = "";

	$id = $aktivnost = $idRadnici = $kategorija = $rubrika = $partner = $lokacija = $radnoMjesto = $start = $kraj = $pon = $uto = $sri = $cet = $pet = $sub = $ned = $poruka = "";
	

	if(isset($_POST['submit']))
	{	
		if (!empty($_POST["id_evidencije"])) {			
			$id = ($_POST['id_evidencije']);	
			if (!preg_match("/^[0-9 ]*$/",$id)){
			$idErr = "* neispravni znakovi"; 
			}
		}

		
		if (empty($_POST["kategorija"])) {
			$kategorijaErr = "* morate popuniti polje";
		} else {				
			$kategorija = ($_POST['kategorija']);	
			if (!preg_match("/^[0-9 ]*$/",$kategorija)){
			$kategorijaErr = "* neispravni znakovi"; 
			}
		}	
		
		if (empty($_POST["rubrika"])) {
			$rubrikaErr = "* morate popuniti polje";
		} else {				
			$rubrika = ($_POST['rubrika']);	
			if (!preg_match("/^[0-9 ]*$/",$rubrika)){
			$rubrikaErr = "* neispravni znakovi"; 
			}
		}			
													
		if (empty($_REQUEST["start"])) {
			$startErr = "* morate popuniti polje";
		}else {
			$start1 = test_input($_POST["start"]);	
			$start = date("d.m.Y", strtotime($start1));						
			$start2 = date("Y-m-d",strtotime($start1));					
			if (!preg_match("/\d{2}.\d{2}.\d{4}$/",$start1))   
			{
			$startErr = "* neispravni znakovi"; 
			}
		}				
				
		if (empty($_REQUEST["kraj"])) {
			$krajErr = "* morate popuniti polje";
		} else {
				$kraj1 = test_input($_POST["kraj"]);	
				if(strtotime($start1) > strtotime($kraj1)){
				$krajErr = "* Kraj datum mora biti stariji od Start datum";
				}else{						
				$kraj = date("d.m.Y", strtotime($kraj1));					
				$kraj2 = date("Y-m-d",strtotime($kraj1));
				}
				if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/",$kraj1)) 	
				{
				$krajErr = "* neispravni znakovi";  
				}
			}
				

				
				
		if(isset($_POST['submit']))
		{
			$pon1 = ($_POST['pon1']); 
			$pon11 = ($_POST['pon11']); 
			$pon2 = ($_POST['pon2']); 
			$pon22 = ($_POST['pon22']);	
			
			$zpon1=($pon1+($pon11/60));
			$zpon2=($pon2+($pon22/60));			

			if($pon1 > $pon2){
				$ponujutro =(($zpon2 + 24)- $zpon1);
				} else {	
				$ponujutro = $zpon2-$zpon1;  
				}			
			
		}
			$ponedjeljak1 = date("$pon1:$pon11:00");
			$ponedjeljak2 = date("$pon2:$pon22:00");	
			
			$pon = $ponujutro;	
		
		if(isset($_POST['submit']))
		{
			$uto1 = ($_POST['uto1']); 
			$uto11 = ($_POST['uto11']); 
			$uto2 = ($_POST['uto2']); 
			$uto22 = ($_POST['uto22']);	
		
			$zuto1=($uto1+($uto11/60));
			$zuto2=($uto2+($uto22/60));		
				
			if($uto1 > $uto2){
				$utoujutro =(($zuto2 + 24)- $zuto1);  
				} else {	
				$utoujutro = $zuto2-$zuto1;  
				}			

	
	
		}
			$utorak1 = date("$uto1:$uto11:00");
			$utorak2 = date("$uto2:$uto22:00");	
			
			$uto = $utoujutro ;  			

		if(isset($_POST['submit'])){
			$sri1 = ($_POST['sri1']); 
			$sri11 = ($_POST['sri11']); 
			$sri2 = ($_POST['sri2']); 
			$sri22 = ($_POST['sri22']);	
			
			$zsri1=($sri1+($sri11/60));
			$zsri2=($sri2+($sri22/60));		
				
			if($sri1 > $sri2){
				$sriujutro =(($zsri2 + 24)- $zsri1);
				} else {	
				$sriujutro = $zsri2-$zsri1;  
				}			

					
		}				
			$srijeda1 = date("$sri1:$sri11:00");				
			$srijeda2 = date("$sri2:$sri22:00");	
				
			$sri = $sriujutro ;  			

		if(isset($_POST['submit'])){
			$cet1 = ($_POST['cet1']); 
			$cet11 = ($_POST['cet11']); 
			$cet2 = ($_POST['cet2']); 
			$cet22 = ($_POST['cet22']);	
			
			$zcet1=($cet1+($cet11/60));
			$zcet2=($cet2+($cet22/60));		
				
			if($cet1 > $cet2){
				$cetujutro =(($zcet2 + 24)- $zcet1);
				} else {	
				$cetujutro = $zcet2-$zcet1;  
				}			

			
		}				
			$cetvrtak1 = date("$cet1:$cet11:00");				
			$cetvrtak2 = date("$cet2:$cet22:00");		
				
			$cet = $cetujutro ; 
 			
		if(isset($_POST['submit'])){
			$pet1 = ($_POST['pet1']); 
			$pet11 = ($_POST['pet11']); 
			$pet2 = ($_POST['pet2']); 
			$pet22 = ($_POST['pet22']);	
			
			$zpet1=($pet1+($pet11/60));
			$zpet2=($pet2+($pet22/60));		
				
			if($pet1 > $pet2){
				$petujutro =(($zpet2 + 24)- $zpet1);
				} else {	
				$petujutro = $zpet2-$zpet1;  
				}			

				
		}

			$petak1 = date("$pet1:$pet11:00");				
			$petak2 = date("$pet2:$pet22:00");		
		
			$pet = $petujutro;  
				
		if(isset($_POST['submit'])){
			$sub1 = ($_POST['sub1']); 
			$sub11 = ($_POST['sub11']); 
			$sub2 = ($_POST['sub2']); 
			$sub22 = ($_POST['sub22']);	
		
			$zsub1=($sub1+($sub11/60));
			$zsub2=($sub2+($sub22/60));		
				
			if($sub1 > $sub2){
				$subujutro =(($zsub2 + 24)- $zsub1);
				} else {	
				$subujutro = $zsub2-$zsub1;  
				}			

		
		}
		
			$subota1 = date("$sub1:$sub11:00");				
			$subota2 = date("$sub2:$sub22:00");		
					
			$sub = $subujutro; 
			
		if(isset($_POST['submit'])){
			$ned1 = ($_POST['ned1']); 
			$ned11 = ($_POST['ned11']); 
			$ned2 = ($_POST['ned2']); 
			$ned22 = ($_POST['ned22']);	
		
		
			$zned1=($ned1+($ned11/60));
			$zned2=($ned2+($ned22/60));		
				
			if($ned1 > $ned2){
				$nedujutro =(($zned2 + 24)- $zned1);
				} else {	
				$nedujutro = $zned2-$zned1;  
				}			

		
		}

			$nedjelja1 = date("$ned1:$ned11:00");				
			$nedjelja2 = date("$ned2:$ned22:00");		
				
			$ned = $nedujutro; 			
				
		if(empty($idErr) and empty($rubrikaErr) and empty($kategorijaErr) and empty($startErr) and empty($krajErr)  and empty($aktivnostErr))
		{		
			if(empty($id)){
				$sql =("INSERT INTO `evidencije` (`kategorija`, `rubrika`, `spremio`, `start`, `kraj`, `Sun`, `Sun1`, `Sun2`, `Mon`, `Mon1`, `Mon2`, `Tue`, `Tue1`, `Tue2`, `Wed`, `Wed1`, `Wed2`, `Thu`, `Thu1`, `Thu2`, `Fri`, `Fri1`, `Fri2`, `Sat`, `Sat1`, `Sat2`)  
				
				VALUES ('".$kategorija."', '".$rubrika."',  {$_SESSION['idKorisnika']}, '".$start2."', '".$kraj2."', '".$ned."', '".$nedjelja1."', '".$nedjelja2."', '".$pon."', '".$ponedjeljak1."', '".$ponedjeljak2."', '".$uto."', '".$utorak1."', '".$utorak2."', '".$sri."', '".$srijeda1."', '".$srijeda2."', '".$cet."', '".$cetvrtak1."', '".$cetvrtak2."', '".$pet."', '".$petak1."', '".$petak2."', '".$sub."', '".$subota1."', '".$subota2."');");	
			}
			else{
				$sql ="UPDATE `evidencije` SET  `rubrika` = '".$rubrika."', `kategorija` = '".$kategorija."', `spremio` = {$_SESSION['idKorisnika']}, `start` = '".$start2."', `kraj` = '".$kraj2."', `Sun` = '".$ned."', `Sun1` = '".$nedjelja1."', `Sun2` = '".$nedjelja2."', `Mon` = '".$pon."', `Mon1` = '".$ponedjeljak1."', `Mon2` = '".$ponedjeljak2."', `Tue` = '".$uto."', `Tue1` = '".$utorak1."', `Tue2` = '".$utorak2."', `Wed` = '".$sri."', `Wed1` = '".$srijeda1."', `Wed2` = '".$srijeda2."', `Thu` = '".$cet."', `Thu1` = '".$cetvrtak1."', `Thu2` = '".$cetvrtak2."', `Fri` = '".$pet."', `Fri1` = '".$petak1."', `Fri2` = '".$petak2."', `Sat` = '".$sub."', `Sat1` = '".$subota1."', `Sat2` = '".$subota2."' WHERE id_evidencije = $id ";
				
			
			}
			if (mysqli_query($veza, $sql)){
				if(empty($id)){
					header("Location: index.php");
				}
				else{
					header("location:prikaz_rasporeda.php?id_evidencije=$id");
				}
			}
			else
			{
			
				$poruka = '<p class="text-danger fw-bold">'.mysqli_error($veza).' * unos u bazu nije uspio</p>';
			}
		}
		else 
		{
		$poruka = '<p class="text-danger fw-bold">* unos u bazu nije uspio, morate popuniti sva polja</p>';	  				
		}	  				
	}
	
	if (isset($_GET["id_evidencije"]))    //prikaz podataka 
	{		
		$id = ($_GET["id_evidencije"]); 
		$sql = "SELECT * FROM evidencije
		WHERE evidencije.id_evidencije = $id"; 		
		$rezultat = mysqli_query($veza, $sql);					
		while($red = mysqli_fetch_array($rezultat))
		{				
			$id = $red['id_evidencije'];
			
			$rubrika = $red['rubrika'];	
			$kategorija = $red['kategorija'];
		
			
			$start = date('d.m.Y', strtotime($red['start']));
			$kraj = date('d.m.Y', strtotime($red['kraj']));
			
			$tjedno = $red['Mon']+$red['Tue']+$red['Wed']+$red['Thu']+$red['Fri']+$red['Sat']+$red['Sun'];	
			
			$pon = $red['Mon'];	
			
			$pon111 = $red['Mon1'];
			$pon_11 = explode(":", $pon111);
			$pon1 = $pon_11[0]; $pon11 = $pon_11[1];
			
			$pon222 = $red['Mon2'];
			$pon_22 = explode(":", $pon222);
			$pon2 = $pon_22[0]; $pon22 = $pon_22[1];
				
				
			$uto = $red['Tue'];
			
			$uto111 = $red['Tue1'];
			$uto_11 = explode(":", $uto111);
			$uto1 = $uto_11[0]; $uto11 = $uto_11[1];
			
			$uto222 = $red['Tue2'];	
			$uto_22 = explode(":", $uto222);
			$uto2 = $uto_22[0]; $uto22 = $uto_22[1];
			
	
			$sri = $red['Wed'];	
			
			$sri111 = $red['Wed1'];
			$sri_11 = explode(":", $sri111);
			$sri1 = $sri_11[0]; $sri11 = $sri_11[1];
			
			$sri222 = $red['Wed2'];
			$sri_22 = explode(":", $sri222);	
			$sri2 = $sri_22[0]; $sri22 = $sri_22[1];
			
			
			$cet = $red['Thu'];	
			
			$cet111 = $red['Thu1'];
			$cet_11 = explode(":", $cet111);
			$cet1 = $cet_11[0]; $cet11 = $cet_11[1];
			
			$cet222 = $red['Thu2'];	
			$cet_22 = explode(":", $cet222);
			$cet2 = $cet_22[0]; $cet22 = $cet_22[1];

			
			$pet = $red['Fri'];	
			
			$pet111 = $red['Fri1'];
			$pet_11 = explode(":", $pet111);
			$pet1 = $pet_11[0]; $pet11 = $pet_11[1];
			
			$pet222 = $red['Fri2'];
			$pet_22 = explode(":", $pet222);
			$pet2 = $pet_22[0]; $pet22 = $pet_22[1];
			
			
			
			$sub = $red['Sat'];	
			
			$sub111 = $red['Sat1'];
			$sub_11 = explode(":", $sub111);
			$sub1 = $sub_11[0]; $sub11 = $sub_11[1];
			
			$sub222 = $red['Sat2'];
			$sub_22 = explode(":", $sub222);
			$sub2 = $sub_22[0]; $sub22 = $sub_22[1];
			
			
			$ned = $red['Sun'];	
			
			$ned111 = $red['Sun1'];
			$ned_11 = explode(":", $ned111);
			$ned1 = $ned_11[0]; $ned11 = $ned_11[1];
			
			$ned222 = $red['Sun2'];
			$ned_22 = explode(":", $ned222);
			$ned2 = $ned_22[0]; $ned22 = $ned_22[1];
			

								
		} 
	}	
	
	
require("zaglavlje.php"); 	
?>			

<div id="sadrzaj">	
	<form action="" method="POST">  
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Unos rasporeda</h2>
			
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" class='form-control my-1' value="✔ Spremi"> 
				<a class='btn btn-warning m-1' href='index.php'>🡸 Natrag</a>		
			</div>			
		</div>
		<?php							
		if (isset($id)){
			echo "<input type='hidden' name='id_evidencije' value='$id'>";
		}
		?>
		
		<div class='row'>	 	
			<div class='col-md-4'>
				<label class='m-1'>Artikl:</label>
				<?php
				$sql =("SELECT * FROM artikli_popis, jedinica_mjere WHERE id_jed_mjere = jed_mjere 
				AND aktivan_artikl = 1
				ORDER BY redoslijed");
				?>
				<select name="kategorija" onChange="traziRubriku(this.value);" class='form-control m-1'>		 
					<option value="">--- odaberi kategoriju ---</option> 
					<?php						
					$result = mysqli_query($veza, $sql);
					while($redak=mysqli_fetch_assoc($result)) 
					{
					$id = $redak["id_artikla"];
					$naziv = $redak["naziv_artikla"];	
					$naziv_jed_mjere = $redak["naziv_jed_mjere"];	
					echo '<option value="' . $redak["id_artikla"] . '"';	
					if ($id == $kategorija)
						{
						echo " selected";  
						}
						echo ">$naziv - $naziv_jed_mjere</option>";				
					} ?>				 
				</select>
				<?php echo $kategorijaErr;?>
			</div>	
			
			<div class='col-md-4'>
				<label class='m-1'>Rubrika:</label>
				<?php
				if (!empty($kategorija)){
				
				$sql = ("SELECT * FROM podgrupe 
				WHERE aktivna_podgrupa = 1 
				AND artikl_id = $kategorija
				ORDER BY artikl_id, redoslijed_podgrupe"); 
				?>
				<select name="rubrika" class='form-control m-1' id="rubrika">
				<?php
				$rezultat = mysqli_query($veza, $sql);							
				while ($redak = mysqli_fetch_array($rezultat)){
					$id_podgrupe = $redak["id_podgrupe"];
					$naziv = $redak["naziv_podgrupe"];	
					echo '<option value="' . $id_podgrupe . '"';	
					if ($id_podgrupe == $rubrika){
						echo " selected";  
					}
					echo ">$naziv</option>";				
				}
				echo "</select>";	
				} 
				else 	
				{					
				?>	
					<select name="rubrika" id="rubrika" class='form-control m-1'>
						<option selected="selected">--- odaberi rubriku ---</option>
					</select>
				<?php 
				} 
				?>
				<?php echo $rubrikaErr;?>
			</div>
			
			
			<div class="col-md-4"> 
				<label class='m-1'>* Start datum:</label>
					<input class="form-control m-1 datum_start" type="text" name="start" 
					value="<?php echo $start; ?>" autocomplete='off'></b>
				<p class='text-danger fw-bold'><?php echo $startErr;?></p>	
			</div>		
			<div class="col-md-4"> 
				<label class='m-1'>* Kraj datum:</label>
					<b><input class="form-control m-1 datum_start" type="text" name="kraj" value="<?php echo $kraj;?>" autocomplete='off'></b>
				<p class='text-danger fw-bold'><?php echo $krajErr;?></p>
			</div>
		</div>
		
			
		<div class='table-responsive'>
			<table class="table table-striped">   
				<tr align="center" >
					<th width="80px">dan</th>			
					<th width="110px">početak h : mm</th>
					<th width="110px">završetakh : mm</th>			
				</tr>
				
				<tr align="center" ><td>PON:</td> 
					<td><select class="select" name="pon1">	
				<?php foreach(range(0, 23) as $value): $selected = $pon1 == $value ? 'selected' : ''; ?>
					<option value="<?=$value?>" <?=$selected?>><?=$value?></option>
				<?php endforeach; ?>  						
				</select> : 
						<select class="select" name="pon11">
				<?php foreach(range(0, 59) as $value): $selected = $pon11 == $value ? 'selected' : ''; ?>
					<option value="<?=$value?>" <?=$selected?>><?=$value?></option>
				<?php endforeach; ?>  						
				</select>
					</td>

					<td><select class="select" name="pon2">					   		
					<?php for($go=00; $go<=23; $go++):
						$selected='';
						if ($pon2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?> 
						</select> : 

						<select class="select" name="pon22">					
					<?php for($go=00; $go<=59; $go++):
						$selected='';
						if ($pon22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select>
					</td>
			
				</tr>

				<tr align="center" ><td>UTO:</td>   			

					<td><select class="select" name="uto1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($uto1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="uto11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($uto11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="uto2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($uto2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="uto22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($uto22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>
				
				</tr>

				<tr align="center" ><td>SRI:</td>			

					<td><select class="select" name="sri1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($sri1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="sri11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($sri11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="sri2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($sri2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="sri22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($sri22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>				

				</tr>

				<tr align="center" ><td>ČET:</td>			

					<td><select class="select" name="cet1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($cet1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="cet11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($cet11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="cet2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($cet2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="cet22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($cet22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>
					
				</tr>

				<tr align="center" ><td>PET:</td>		

					<td><select class="select" name="pet1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($pet1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="pet11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($pet11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="pet2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($pet2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
					</select>  : 
						<select class="select" name="pet22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($pet22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>
			
					
				</tr>

				<tr align="center" ><td>SUB:</td>			

					<td><select class="select" name="sub1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($sub1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="sub11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($sub11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="sub2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($sub2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="sub22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($sub22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>		
				</tr>

				<tr align="center" ><td>NED:</td>	
					<td><select class="select" name="ned1">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($ned1 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="ned11">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($ned11 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>

					<td><select class="select" name="ned2">					   		
					<?php for($go=00; $go<=23; $go++): 
						$selected='';
						if ($ned2 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>
						</select>  : 
						<select class="select" name="ned22">		
					<?php for($go=00; $go<=59; $go++): 
						$selected='';
						if ($ned22 == $go) $selected=' selected'; ?>
						<option value="<?=$go?>" <?=$selected?>><?=$go?></option>
					<?php endfor; ?>  
						</select> 
					</td>
			
				</tr>   

			</table>
		</div>

	
	
	<?php  echo $poruka ?>		 	
	</form>
</div>	

	<script>				


			$( function() {
				$( "#accordion" ).accordion(); 
			  } );	

  	$(function(){
		$(".datum_start").datepicker({
		changeMonth: true,
		changeYear: true});
		$.datepicker.regional['hr'] = {
			closeText: 'Zatvori',
			prevText: '&#x3c;',
			nextText: '&#x3e;',
			currentText: 'Danas',
			monthNames: ['Siječanj','Veljača','Ožujak','Travanj','Svibanj','Lipanj',
			'Srpanj','Kolovoz','Rujan','Listopad','Studeni','Prosinac'],
			monthNamesShort: ['Sij','Velj','Ožu','Tra','Svi','Lip',
			'Srp','Kol','Ruj','Lis','Stu','Pro'],
			dayNames: ['Nedjelja','Ponedjeljak','Utorak','Srijeda','Četvrtak','Petak','Subota'],
			dayNamesShort: ['Ned','Pon','Uto','Sri','Čet','Pet','Sub'],
			dayNamesMin: ['Ne','Po','Ut','Sr','Če','Pe','Su'],
			weekHeader: 'Tje',
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['hr']);
		jQuery.validator.addMethod("date", function(value, element) {
			return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
		}, "Please enter a correct date");
		jQuery.validator.addMethod("integer2", function(value, element) {
			return this.optional(element) || /^\d{2}$/.test(value);
		}, "A positive or negative non-decimal number please");		
	});	
			
	</script>	 
	<script type="text/javascript" language="javascript">
			var activeTab = 1;
			function openTab(tabId) {
				document.getElementById("tabLink"+activeTab).className = "tabLink";
				document.getElementById("tabContent"+activeTab).className = "tabContent";
				document.getElementById("tabLink"+tabId).className = "tabLinkActive";
				document.getElementById("tabContent"+tabId).className = "tabContentActive";
				activeTab = tabId;
			}
			
		document.querySelectorAll('input[type=number]')
		  .forEach(e => e.oninput = () => {
			// Always 2 digits
			if (e.value.length >= 2) e.value = e.value.slice(0, 2);
			// 0 on the left (doesn't work on FF)
			if (e.value.length === 1) e.value = '0' + e.value;
			// Avoiding letters on FF
			if (!e.value) e.value = '00';
		  });			
	</script>
<script>				
		function traziRubriku(val) {
			$.ajax({
			type: "POST",
			url: "dropdown.php",
			data:'id_artikla='+val,
			success: function(response){
				$("#rubrika").html(response); 
				}
			});
		}
	</script>	
</body>
</html>
<?php ob_end_flush(); ?>
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
	
	$naziv_jed_mjereErr = $aktivnoErr = "";
	$poruka = $naziv_jed_mjere = $aktivno = "";	
				
	if(isset($_POST['submit'])){
		if (empty($_POST["naziv_jed_mjere"])) {
			$naziv_jed_mjereErr = "* morate popuniti polje";
		} 
		else 
		{
			$naziv_jed_mjere = test_input($_POST["naziv_jed_mjere"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$naziv_jed_mjere))   
			{
			$naziv_jed_mjereErr = "* specijalni znakovi neće biti spremljeni u bazu"; 
			}
		}
		
		if (empty($_POST["puni_naziv"])) {
			$puni_nazivErr = "* morate popuniti polje";
		} 
		else 
		{
			$puni_naziv = test_input($_POST["puni_naziv"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$puni_naziv))   
			{
			$puni_nazivErr = "* specijalni znakovi neće biti spremljeni u bazu"; 
			}
		}

		if(isset($_POST["aktivno"])) $aktivno = 1;
		else $aktivno = 0;
		if (!preg_match("/^[0-9]*$/",$aktivno)) $aktivnoErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 			
		
		if(empty ($naziv_jed_mjereErr) && empty ($puni_nazivErr) && empty ($aktivnoErr)){										
			if(isset($_POST["id_jed_mjere"])){				
				$id = ($_POST['id_jed_mjere']);
				$sql = ("UPDATE jedinica_mjere SET naziv_jed_mjere = '".$naziv_jed_mjere."', puni_naziv = '".$puni_naziv."', aktivan = '".$aktivno."' WHERE id_jed_mjere = $id ");
			}
			else {		
				$sql =("INSERT INTO `jedinica_mjere` (`naziv_jed_mjere`, `puni_naziv`, `aktivan`) VALUES ('".$naziv_jed_mjere."', '".$puni_naziv."', '".$aktivno."');"); 
			}					 			
			if (mysqli_query($veza, $sql)){
				header("Location: jedinica_mjere.php");  
			}
			else{
				$poruka = "<p class='text-danger'>unos /izmjena nije uspjela.</p>" . mysqli_error($veza);
			}				
		}	
		
	}	
	if (isset($_GET["id_jed_mjere"]))         //prikaz podataka za postoječi izostanak
	{
		$id = ($_GET['id_jed_mjere']);	
		$sql =("SELECT * FROM jedinica_mjere WHERE id_jed_mjere = $id ");	
			$rezultat = mysqli_query($veza, $sql);
			if($red = mysqli_fetch_array($rezultat))
				{
				$naslovStranice = "Izmjena jedinice mjere";
				$id = $red['id_jed_mjere'];	
				$naziv_jed_mjere = $red['naziv_jed_mjere'];		
				$puni_naziv = $red['puni_naziv'];		
				$aktivno = $red['aktivan'];
				if($aktivno == 1){
					$aktivno = 'checked';
				}
				else{
					$aktivno = '';
				}
				$linkZaPovratak = "jedinica_mjere.php";  			

				}	
	}
	else if (!isset($_POST["id_jed_mjere"]))   //novi izostanak 
	{
		$naslovStranice = "Unos jedinice mjere";
		$naziv_jed_mjere = "";	
		$linkZaPovratak = "jedinica_mjere.php";  		
	}	
require("zaglavlje.php"); 	
?> 		
	
<div id="sadrzaj">	
	<form method='post' action=''>
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2><?php echo $naslovStranice; ?></h2>
			<div class='d-flex flex-wrap justify-content-end align-items-center'>
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi">
				<a class='btn btn-danger text-white m-1' href='<?php echo $linkZaPovratak; ?>'>✖ Odustani</a> 		
			</div>			
		</div>
		<?php							
		if (isset($id)){
			echo "<input type='hidden' name='id_jed_mjere' value='$id'>";
		}
		?>		
		<div class='row'>
			<div class='col-md-4'>
				<label class='m-1'>* Jedinica mjere:</label>
				<INPUT class='form-control m-1' type="text" name="naziv_jed_mjere" value="<?php echo $naziv_jed_mjere ?>">	
				<span class="text-danger d-block"><?php echo $naziv_jed_mjereErr;?></span>
			</div>
			<div class='col-md-4'>
				<label class='m-1'>* Puni naziv:</label>
				<INPUT class='form-control m-1' type="text" name="puni_naziv" value="<?php echo $puni_naziv ?>">	
				<span class="text-danger d-block"><?php echo $naziv_jed_mjereErr;?></span>
			</div>
			<div class='col-md-4'>
				<label class='m-1'>* Aktivno:</label>
				<input type='checkbox' name='aktivno' class='d-block form-check-input mx-4 my-2' <?php echo $aktivno; ?>>
				<span class="text-danger d-block"><?php echo $aktivnoErr;?></span>
			</div>
		</div>
		<?php echo $poruka; ?>				
	</form>
</div>
</body>
</html> 	


	
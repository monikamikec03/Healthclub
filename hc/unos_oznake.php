<?php	
ob_start();
session_start();
$list = 'postavke2';

if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");			
	require("../include/putanja.php");
}
else{
	 echo "<script> window.location.replace('odjava.php');</script>";
}		

		
	$naziv_oznakeErr = ""; 
	$naziv_oznake = "";			
					
			function test_input($data) {
			  $data = trim($data);
			  $data = strip_tags($data); 
			  $data = htmlspecialchars($data);
			return $data;
			}		
				
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{	
		if (empty($_POST["naziv_oznake"])) {
			$naziv_oznakeErr = "<p class='text-danger m-1'>* morate popuniti polje</p>";
		} 
		else 
		{
			$naziv_oznake = test_input($_POST["naziv_oznake"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:#  ]*$/",$naziv_oznake))    
			{
			$naziv_oznakeErr = "<p class='text-danger m-1'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}	
		
		if(isset($_REQUEST["aktivno_web"])){
			$aktivno_web = 1;
		}
		else
		{
			$aktivno_web = 0;
		} 
		if (!preg_match("/^[0-9]*$/",$aktivno_web)) 
		{
			$aktivno_webErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}	
			
		if(empty ($naziv_oznakeErr))
		{	
		if(!empty($_POST["id_oznake"])){
			$id_oznake = $_POST["id_oznake"];
			$sql = "UPDATE oznake SET naziv_oznake = '$naziv_oznake', aktivno_web = '$aktivno_web' WHERE id_oznake = $id_oznake";
		}
		else{		
			$sql =("INSERT INTO `oznake` (`naziv_oznake`, `aktivno_web`) VALUES ( '".$naziv_oznake."', '".$aktivno_web."');");
		}		
			if (mysqli_query($veza, $sql))
				{
				header("Location:oznake.php");
					
				}
				else
				{
					echo mysqli_error($veza);  
				$poruka = "<p class='text-danger'>unos /izmjena oznake nije uspjela.</p>";
				}				
		}		 				
	}
	
if((isset($_GET["id_oznake"])) && (is_numeric($_GET["id_oznake"]))){ 								
	$id_oznake = (int)($_GET['id_oznake']);
	$sql = "SELECT * FROM oznake WHERE id_oznake = '$id_oznake'";	
	$rezultat = mysqli_query($veza, $sql); 
	if($red = mysqli_fetch_array($rezultat))
	{ 					
		$naziv_oznake = $red['naziv_oznake']; 	
		$aktivno_web = $red['aktivno_web']; 	
		$naslov = "Izmjena oznake";		
	}
}
else{
	$naslov = "Unos oznake";
}

	
require("navigacija.php"); 	
?>	  		

<div id="sadrzaj">	
	<form method="post" action="">
		<?php									
			if (isset($id_oznake))
			{
				echo "<input type='hidden' name='id_oznake' value='$id_oznake'>";
			}
		?>	
		<div class='d-flex flex-wrap justify-content-between align-items-center '>
			<h2 class='m-1'><?php echo $naslov; ?><i class='text-secondary'> <?php echo $naziv_oznake?></i></h2>
			
			<div class='d-flex flex-wrap justify-content-end'>
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi"> 	
				<a class='btn btn-danger m-1' href='oznake.php'>✖ Odustani</a>		
			</div>			

		</div>
		<div class='row'>
			<div class="col-md-6">
				<label class='m-1'>Naziv oznake:</label>	
				<INPUT TYPE="text" class='form-control m-1' name="naziv_oznake" placeholder="#BuildingBetterHumans" value="<?php echo $naziv_oznake;?>">
				<?php echo $naziv_oznakeErr;?>
			</div>
			<div class='col-md-6'>
				<label class='m-1'>Prikaži na webu:</label>
				<input type="checkbox" name="aktivno_web"	<?php if($aktivno_web) echo "checked"; ?> class='form-check-input d-block'/>
				<?php echo $aktivno_webErr; ?>
			</div>
						

		</div> 
		<?php echo $poruka; ?>				
	</form>

</div>
<?php
include("podnozje.php");
?>
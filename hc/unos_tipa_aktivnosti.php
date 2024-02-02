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

		
	$naziv_tipaErr = ""; 
	$naziv_tipa = "";			
					
			function test_input($data) {
			  $data = trim($data);
			  $data = strip_tags($data); 
			  $data = htmlspecialchars($data);
			return $data;
			}		
				
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{	
		if (empty($_POST["naziv_tipa"])) {
			$naziv_tipaErr = "<p class='text-danger m-1'>* morate popuniti polje</p>";
		} 
		else 
		{
			$naziv_tipa = test_input($_POST["naziv_tipa"]);
			if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:#  ]*$/",$naziv_tipa))    
			{
			$naziv_tipaErr = "<p class='text-danger m-1'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
			}
		}	
		
	
			
		if(empty ($naziv_tipaErr))
		{	
		if(!empty($_POST["id_tipa"])){
			$id_tipa = $_POST["id_tipa"];
			$sql = "UPDATE tipovi_aktivnosti SET naziv_tipa = '$naziv_tipa' WHERE id_tipa = $id_tipa";
		}
		else{		
			$sql =("INSERT INTO `tipovi_aktivnosti` (`naziv_tipa`) VALUES ( '".$naziv_tipa."');");
		}		
			if (mysqli_query($veza, $sql))
				{
				header("Location:tipovi_aktivnosti.php");
					
				}
				else
				{
					echo mysqli_error($veza);  
				$poruka = "<p class='text-danger'>unos /izmjena tipova aktivnosti nije uspjela.</p>";
				}				
		}		 				
	}
	
if((isset($_GET["id_tipa"])) && (is_numeric($_GET["id_tipa"]))){ 								
	$id_tipa = (int)($_GET['id_tipa']);
	$sql = "SELECT * FROM tipovi_aktivnosti WHERE id_tipa = '$id_tipa'";	
	$rezultat = mysqli_query($veza, $sql); 
	if($red = mysqli_fetch_array($rezultat))
	{ 					
		$naziv_tipa = $red['naziv_tipa']; 	
		$naslov = "Izmjena grupe aktivnosti";		
	}
}
else{
	$naslov = "Unos grupe aktivnosti";
}

	
require("navigacija.php"); 	
?>	  		

<div id="sadrzaj">	
	<form method="post" action="">
		<?php									
			if (isset($id_tipa))
			{
				echo "<input type='hidden' name='id_tipa' value='$id_tipa'>";
			}
		?>	
		<div class='d-flex flex-wrap justify-content-between align-items-center '>
			<h2 class='m-1'><?php echo $naslov; ?><i class='text-secondary'> <?php echo $naziv_tipa?></i></h2>
			
			<div class='d-flex flex-wrap justify-content-end'>
				<input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi"> 	
				<a class='btn btn-danger m-1' href='tipovi_aktivnosti.php'>✖ Odustani</a>		
			</div>			

		</div>
		<div class='row'>
			<div class="col-md-12">
				<label class='m-1'>Naziv grupe aktivnosti:</label>	
				<INPUT TYPE="text" class='form-control m-1' name="naziv_tipa" value="<?php echo $naziv_tipa;?>">
				<?php echo $naziv_tipaErr;?>
			</div>
			
						

		</div> 
		<?php echo $poruka; ?>				
	</form>

</div>
<?php
include("podnozje.php");
?>
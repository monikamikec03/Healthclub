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
	return $data;
}	

$year = date('Y');

	
if(isset($_POST['godine'])){
	
	$year = test_input($_POST["godine"]);
	if (!preg_match("/^[0-9 ]*$/",$year)){
		header('location:praznici.php');
	}
	
}
?>
<div class="container-fluid flex-grow-1">
	<div class="d-flex justify-content-between flex-wrap align-items-center my-2">
		<h2>Praznici i blagdani</h2>	
		<div class='d-flex align-items-center flex-wrap'>
			<form method="post" action="" class='d-flex bg-white shadow-none p-0'>			
				<select onchange="this.form.submit()" name="godine" class='form-control w-auto mx-2 px-4 my-1'>
				<?php foreach(range($year-10, $year+10) as $godina){ ?>
					<option value='<?php echo $godina; ?>' <?php if($godina == $year) echo "selected"; ?>><?php echo $godina; ?></option>
				<?php }	?>		
				</select>
			</form>	
			<a class='btn btn-primary' href='rad_unos_praznici.php'>+ dodaj</a>
	
		</div>
	</div>	
	<div class='table-responsive'>
		<table class="table table-hover table-striped">	
			<tr>	
				<th>rb</th>	
				<th>Datum</th>						
				<th>Praznik</th>					
								
			</tr>	
			<?php
			$br = 1 ;
			$sql = "SELECT * FROM praznici 
			where year(datumPraznika) = $year
			ORDER BY datumPraznika "; 	
			$rezultat = mysqli_query($veza, $sql); 
			while($red = mysqli_fetch_array($rezultat)){
				$id = $red['praznici_id'];		
				$datum = strtotime($red['datumPraznika']);		
				$praznik = $red['nazivPraznika'];			  			
					echo "<tr>";
					echo "<td> $br </td>"; $br++; 
					echo "<td>".date("d.m.Y.", $datum)."</td>";				
					echo "<td><a href='rad_prikaz_praznici.php?praznici_id=$id'>$praznik</a></td>";			
					echo "</tr>";
				}  
			
			?>						
		</table>
	</div>
</div>
<?php
include("podnozje.php"); 
?>


<?php	
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
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
	
	if(isset($_GET["praznici_id"])) 
	{	
	$praznici_id = addSlashes($_GET["praznici_id"]);	 		
		$sql = "SELECT * FROM praznici WHERE praznici_id = $praznici_id";	
		$rezultat = mysqli_query($veza, $sql);			
		if($redak = mysqli_fetch_array($rezultat)){
			$praznici_id = $redak["praznici_id"];
			$sql = "DELETE FROM praznici WHERE praznici_id = $praznici_id";  
			if (!mysqli_query($veza, $sql))			
			{	
				exit ( "Brisanje praznika nije uspjelo"); 
			}
			else{
				header("Location: praznici.php"); 	
			}
		}
		else{
			exit("Nije pronađen traženi praznik u bazi.");
		}
		
		
		
			
	}		
?>	
</body>
</html> 
<?php ob_end_flush(); ?>	
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
	
	if(isset($_GET["id_komentara"])) 
	{	
	$id_komentara = addSlashes($_GET["id_komentara"]);	 		
		$sql = "SELECT * FROM komentari WHERE id_komentara = $id_komentara";	
		$rezultat = mysqli_query($veza, $sql);			
		if($redak = mysqli_fetch_array($rezultat)){
			$id_komentara = $redak["id_komentara"];
			$sql = "DELETE FROM komentari WHERE id_komentara = $id_komentara";  
			if (!mysqli_query($veza, $sql))			
			{	
				exit ( "Brisanje komentara nije uspjelo"); 
			}
			else{
				header("Location: komentari.php"); 	
			}
		}
		else{
			exit("Nije pronađen traženi komentar u bazi.");
		}
		
		
		
			
	}		
?>	
</body>
</html> 
<?php ob_end_flush(); ?>	
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
	
	if(isset($_GET["id"])) 
	{	
	$id = addSlashes($_GET["id"]);	 		
		$sql = "SELECT * FROM slike_naslova WHERE clanak_id = $id";	
		// prvo provjeravamo sve slike vezane uz čanak
		$rezultat = mysqli_query($veza, $sql);			
		while($redak = mysqli_fetch_array($rezultat))		
		{
			$putanja = $redak["putanja"];
			if (file_exists("$putanja"))  
			{
				unlink("$putanja");
			}
		}
		
		mysqli_free_result($rezultat); 
		$sql = "DELETE FROM slike_naslova WHERE clanak_id = $id";  
		if (!mysqli_query($veza, $sql))  // prvo brišemo sve slike vezane uz čanak	
		{				
			exit ( "Brisanje slika nije uspjelo");
		}
		
		$sql = "DELETE FROM clanak WHERE id_clanak = $id";  
		if (!mysqli_query($veza, $sql))			
		{	
			exit ( "Brisanje čanka nije uspjelo"); 
		}
		
		header("Location: clanci.php"); 		
	}		
?>	
</body>
</html> 
<?php ob_end_flush(); ?>	
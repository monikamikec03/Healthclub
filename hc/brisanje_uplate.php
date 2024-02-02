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
	
	if(isset($_GET["id_uplate"])) 
	{	
	$id_uplate = addSlashes($_GET["id_uplate"]);	 		
		$sql = "SELECT * FROM clanstva_uplate WHERE id_uplate = $id_uplate";	
		$rezultat = mysqli_query($veza, $sql);			
		if($redak = mysqli_fetch_array($rezultat)){
			$id_uplate = $redak["id_uplate"];
            $clan_id = $redak["clan_id"];
			$sql = "DELETE FROM clanstva_uplate WHERE id_uplate = $id_uplate";  
			if (!mysqli_query($veza, $sql))			
			{	
				exit ( "Brisanje uplate nije uspjelo"); 
			}
			else{
				header("Location: prikaz_korisnik.php?id=$clan_id"); 	
			}
		}
		else{
			exit("Nije pronađen traženi zapis u bazi.");
		}
		
		
		
			
	}		
?>	
</body>
</html> 
<?php ob_end_flush(); ?>	
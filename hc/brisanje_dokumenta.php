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

setlocale(LC_ALL, 'hr_HR.utf-8');
		
	if(isset($_GET["id"])) 
	{		
		$id = addSlashes($_GET["id"]);
		$sql = ("SELECT*FROM file WHERE id_file = $id ");	
		$rezultat = mysqli_query($veza, $sql);
		if($redak = mysqli_fetch_array($rezultat))	
		{
			$zadaci_id = $redak["zadaci_id"];
			$putanja = $redak["putanja"];
		}
		else
		{
			exit("Slika nije prondena u bazi");
		}			
		mysqli_free_result($rezultat);
		$sql = ("DELETE FROM file WHERE id_file = $id "); 
		if (mysqli_query($veza, $sql))
		{
			if (file_exists($putanja))  
			{
				unlink($putanja);
				if (!unlink($putanja))
				  {
				  echo ("Error deleting $putanja");
				  }
				else
				  {
				  echo ("Deleted $putanja");
				  }	
			}
		}
		else
		{	
			exit ( "brisanje nije uspjelo");
		}			
		header("Location: prikaz_aktivnosti.php?id_aktivnost=" . $zadaci_id );
	}
?>
</body>
</html> 
<?php ob_end_flush(); ?>
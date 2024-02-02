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
		
if(isset($_GET["slika"])) {		
	 $putanja = $_GET['slika'];
	 $pattern = "/https://www.healthclub.hr/i";
	 
	 
	echo $putanja_del = str_replace('https://www.healthclub.hr/', '../', $putanja);
	
	
	if (file_exists($putanja_del)) {
	
		$sql = "DELETE FROM slike WHERE putanja = '$putanja'";
		if(mysqli_query($veza, $sql)){
			unlink($putanja_del);
			if (!unlink($putanja_del))
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
	header('Location:slike.php');
}
?>
</body>
</html> 
<?php ob_end_flush(); ?>		
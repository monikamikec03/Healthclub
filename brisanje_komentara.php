<?php	
ob_start();
require("../moj_spoj/otvori_vezu_cmp.php");

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	


setlocale(LC_ALL, 'hr_HR.utf-8');
	
if(isset($_GET["id"])) {		
	$id = addSlashes($_GET["id"]);
	$sql = ("SELECT * FROM komentari WHERE id_komentara = $id
	AND posjetioc_id = {$_COOKIE['id_posjetioca']}");	
	$rezultat = mysqli_query($veza, $sql);
	if($redak = mysqli_fetch_array($rezultat)){
		$id_komentara = $redak["id_komentara"];
		$clanak_id = $redak["clanak_id"];
		
		$sql = ("DELETE FROM komentari WHERE id_komentara = $id"); 
		if (mysqli_query($veza, $sql)){
			
			header("location:clanak.php?id=$clanak_id");	
				
		}
		else{	
			echo '<script>alert("Brisanje nije uspjelo.");
			window.location.href="blog.php";
			</script>';
		}	
	}
	
	else{
		echo '<script>alert("Komentar nije pronađen u bazi.");
		window.location.href="blog.php";
		</script>';
		
	}	
	
}

ob_end_flush(); 
?>
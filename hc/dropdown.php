<?php	
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
	require("../include/putanja.php");	
}
else{
	 echo "<script> window.location.replace('odjava');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');
	
		
	
		
		
if(!empty($_POST["id_user"]) AND $_POST["obrisi_sliku_usera"] == 1){
	$id_user = (int)(test_input($_POST["id_user"]));
	$sql = "SELECT * FROM poslodavac_user WHERE id_user = $id_user";
	$res = mysqli_query($veza, $sql);
	if($red = mysqli_fetch_array($res)){
		$slika_usera = $red["slika_usera"];
	}
	
	if(!empty($slika_usera)){
		$sql = "UPDATE poslodavac_user SET slika_usera = '' WHERE id_user = $id_user";
		if(mysqli_query($veza, $sql)){
			unlink($slika_usera);
		}
	}
}

if(!empty($_POST["clanak_id"]) && !empty($_POST["oznaka_id"]) && !empty($_POST["naredba"])){
	$porukaErr = $clanak_idErr = $oznaka_idErr = $naredbaErr = '';
	if (empty($_POST["clanak_id"])) {
		$clanak_idErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else 
	{
		$clanak_id = test_input($_POST["clanak_id"]);
		if (!preg_match("/^[0-9]*$/",$clanak_id)) 
		{
		$clanak_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	
	if (empty($_POST["oznaka_id"])) {
		$oznaka_idErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else 
	{
		$oznaka_id = test_input($_POST["oznaka_id"]);
		if (!preg_match("/^[0-9]*$/",$oznaka_id)) 
		{
		$oznaka_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	
	if (empty($_POST["naredba"])) {
		$naredbaErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else 
	{
		$naredba = test_input($_POST["naredba"]);
		if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!.,:; ]*$/",$naredba)) {
			$naredbaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	}
	if(empty($clanak_idErr) && empty($oznaka_idErr) && empty($naredbaErr)){
		if($naredba == "insert"){
			$sql = "INSERT INTO pridruzene_oznake(clanak_id, oznaka_id) VALUES('".$clanak_id."', '".$oznaka_id."')";
		}
		else if($naredba == "delete"){
			$sql = "DELETE FROM pridruzene_oznake WHERE clanak_id = $clanak_id AND oznaka_id = $oznaka_id";
		}
		else{
			$porukaErr = "<p class='text-danger'>Dogodila se pogreška, pokušajte ponovno.</p>";
		}
		if(empty($porukaErr)){
			mysqli_query($veza, $sql);
		}
		else $porukaErr = "<p class='text-danger'>Zahtjev nije bilo moguće izvršiti.</p>";
	
		
	}
	else{
		echo $clanak_idErr;
		echo $oznaka_idErr;
		echo $naredbaErr;
	}
	

	
}
if(!empty($_POST["id_slike"])){
	$id_slike = (int)(test_input($_POST["id_slike"]));
	$sql = "SELECT * FROM slike WHERE id_slike = $id_slike";
	$res = mysqli_query($veza, $sql);
	if($red = mysqli_fetch_array($res)){
		$id_slike = $red["id_slike"];
		$ukljuceno_galerija = $red["ukljuceno_galerija"];
		
		if($ukljuceno_galerija == 0){
			$sql = "UPDATE slike SET ukljuceno_galerija = 1 WHERE id_slike = $id_slike";
		}
		else{
			$sql = "UPDATE slike SET ukljuceno_galerija = 0 WHERE id_slike = $id_slike";
		}
		mysqli_query($veza, $sql);

		
	}
	
	
}

if(!empty($_POST["obrt"]) && !empty($_POST["slika_galerija"])){
	$obrt = (int)(test_input($_POST["obrt"]));
	$slika_galerija = (int)(test_input($_POST["slika_galerija"]));
	$sql = "UPDATE slike SET obrt_id = $obrt WHERE id_slike = $slika_galerija";
	mysqli_query($veza, $sql);

	
}

if(!empty($_POST["id_artikla"])) {
	$query ="SELECT * FROM podgrupe 
	WHERE artikl_id = '" . $_POST["id_artikla"] . "'
	AND aktivna_podgrupa = 1 
	ORDER BY artikl_id, redoslijed_podgrupe";
		$result = mysqli_query($veza, $query);
		while($row = mysqli_fetch_assoc($result)) {    
			$resultset[] = $row;
			}					
		?>
		<option value="">--- odaberi ---</option>    
		<?php
		foreach($resultset as $rubrike) {
		?>
		<option value="<?php echo $rubrike["id_podgrupe"]; ?>"><?php echo $rubrike["naziv_podgrupe"] ?></option>
		<?php  
		}
	}
?>	

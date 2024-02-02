<?php
require("../moj_spoj/otvori_vezu_cmp.php");

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');
	

$clanak_idErr = "";	
$clanak_id = $id_lajka = "";	
	
if(!empty($_POST["clanak_id"])){
	

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
	
	$sql = "SELECT * FROM lajkovi WHERE clanak_id = $clanak_id AND posjetioc_id = " . $_COOKIE["id_posjetioca"];
	$res = mysqli_query($veza, $sql);
	if(mysqli_num_rows($res) > 0){
		$red = mysqli_fetch_array($res);
		$id_lajka = $red["id_lajka"];
	}
	else{
		$id_lajka = '';
	}
	
	if(empty($clanak_idErr)){
		
		if(empty($id_lajka)){
			$sql = "INSERT INTO lajkovi(clanak_id, posjetioc_id) VALUES('".$clanak_id."', '".$_COOKIE["id_posjetioca"]."')";
		}
		else{
			$sql = "DELETE FROM lajkovi WHERE clanak_id = $clanak_id AND posjetioc_id = '".$_COOKIE["id_posjetioca"]."' AND id_lajka = $id_lajka";			
		}
		
		mysqli_query($veza, $sql);

	}
	
}


if(!empty($_POST["id_artikla"])) {

	$query ="SELECT * FROM podgrupe 
	WHERE artikl_id = '" . $_POST["id_artikla"] . "'
	AND aktivna_podgrupa = 1 
	ORDER BY artikl_id, redoslijed_podgrupe";
	$result = mysqli_query($veza, $query);
	if(mysqli_num_rows($result) > 0){
		echo '<select name="rubrika[]" class="form-control m-1 p-0" multiple>';
		echo '<option class="px-2 py-1" value="" disabled selected>Odaberite željenu grupu</option>';
		while($row = mysqli_fetch_array($result)) {    
			$id_podgrupe = $row["id_podgrupe"];
			$naziv_podgrupe = $row["naziv_podgrupe"];
			?>
				<option class='px-2 py-1' value="<?php echo $naziv_podgrupe; ?>"><?php echo $naziv_podgrupe; ?></option>
			<?php
			
		}					
		echo "</select>";
		echo "<small>Ukoliko želite odabrati više grupa, držite tipku CTRL i poklikajte sve željene grupe.</small>";
	}
	
}

if(isset($_POST['osvjezi_stranicu'])){
	$page = $_POST['osvjezi_stranicu'];
	
	header("location:$page");
	
}

if(isset($_POST["prihvati_kolacice"])){
	$sql = "INSERT INTO posjetioci() VALUES()";
	if(mysqli_query($veza, $sql)){
		$id_posjetioca = mysqli_insert_id($veza);
		setcookie("id_posjetioca", $id_posjetioca, strtotime("+1 year"));
	}
}
?>	

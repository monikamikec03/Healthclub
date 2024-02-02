<?php	
ob_start();
session_start();
$list = 'clanci';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
	require("../include/putanja.php");	
	require("navigacija.php");
}
else{
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');

	
$poruka = '';

	
	
if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"] ["size"] != 0 && isset($_POST["submit"])) 
{	
	if(empty($_POST["id_clanak"])){
		$poruka = "<p>Neispravan ID članka.</p>";
	}
	else{
		$id_clanak = (int)(test_input($_POST["id_clanak"]));

	}
	
	$target_dir = "../slike_naslova/";
	$target_file = $target_dir . basename($_FILES["slika"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
	
	if ($_FILES["slika"]["size"] > 5000000) {
		$poruka = "<p>Slika je prevelike rezolucije, morate je smanjiti</p>";
	}			
	else
	{
		// PROVJERI FORMAT FILE  
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "bmp" && $imageFileType != "BMP" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF" ) 
		{
		$poruka="* dokument za spremanje mora biti format: JPG, JPEG, PNG, GIF, BMP";
		}
		else
		{	
		$vrijeme = date("ymdHis");
		$putanja = "../slike_naslova/{$vrijeme}.$imageFileType";   
		
			if(isset ($nazivErr)) 
			{
			$poruka = "* znakovi naziva slike nisu ispravni";
			}
			else
			{	
			$sql ="INSERT INTO slike_naslova (clanak_id, putanja, autor_id) ";
			$sql .="VALUES ( '$id_clanak', '$putanja', {$_SESSION['idKorisnika']} );";

				if (mysqli_query($veza, $sql))	    		
				{
					$putanja = "../slike_naslova/{$vrijeme}.$imageFileType";			
					if (!move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja))
					{
						exit ( "nije dobar direktori za spremanje slike" );	
					}
				header("Location: priprema_slike_naslova.php?id=$id_clanak");
				}
				else
				{
				$poruka = "<p class='text-danger'>Spremanje u bazu nije uspjelo.</p>" . mysqli_error($veza);  					
				}
			} 
		}
	
	}
}
if (isset($_GET["id"]))    //prikaz podataka za canak
{								
	$id = addSlashes($_GET['id']); 								
		$sql = "SELECT * FROM clanak, poslodavac_user WHERE id_clanak = $id AND unos_id = id_user";     
		$rezultat = mysqli_query($veza, $sql);
		if ($redak = mysqli_fetch_array($rezultat))
		{
			$id = $redak["id_clanak"];  
			$naslov = stripSlashes($redak["naslov_clanka"]);				
			$uvod = stripSlashes($redak["uvod"]);
			$tekst = html_entity_decode($redak["tekst"]);							
			$datum = date("j.n.Y.", strtotime($redak["datum_unosa"])); 
			$spremio = $redak["puno_ime_poslodavac_user"];	
			$autor = $redak["ime_autora"];							
			if ($redak["objavljen"] == 1)
			{
				$objavljen = "checked";			
			}	
			else
			{
				$objavljen = "";
			}
		}
}
?> 

<div id="sadrzaj">
	
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2 class='m-1'>Unos naslovne slike</h2>
		

		<a class='btn btn-warning m-1' href='clanak.php?id=<?php echo $id ?>'>🡸 Natrag</a>	
		
	</div>
	
		
	<div class='table-responsive p-1'>
		<table class="table table-hover table-striped">   
			<thead>
				<tr> 
					<th>Slika</th>
					<th>Putanja</th>		
					<th>Brisanje</th>	 				
				</tr> 		
			</thead>
			<tbody>
				<tr>
				<form method="POST" action="" enctype="multipart/form-data" >	
					
					<td colspan="3">
						<input type="file" name="slika">
						<input type="hidden" name="id_clanak" value="<?php echo $id; ?>">
						<?php echo $poruka; ?>
					</td>
		
					<input class="btn btn-success m-1 d-none" type="submit" name="submit" value="&#8730; Spremi">
						   							
				</form>	
				</tr>
			<?php			
					$sql = ("SELECT * FROM slike_naslova
					WHERE clanak_id = $id "); 									
					$rezultat = mysqli_query($veza, $sql);  					
						if (mysqli_num_rows($rezultat) == 0) 
						{
							echo '<tr>
									<td colspan="3" align="middle" class="text-danger bg-light">Nemate spremljenu naslovnu sliku</td>
								  </tr>';
						}
					while ($redak = mysqli_fetch_array($rezultat)){
						$id_slike = $redak["id_slike"];
						$putanja = $redak["putanja"];

						echo "<tr>";
						echo "<td class='col-lg-2 col-md-4'><img src='$putanja'  /></td>";
						echo "<td align='center'>$putanja</td>";					
						echo "<td align='center' align='center'><a href='brisanje_slike_naslova.php?id=$id_slike'>Obriši sliku</a></td>";   					
						echo "</tr>";
					}
			?>
			</tbody>
		</table>
	</div>	

	</form>
</div>
<script>
$("input[name='slika']").change(function() {
    $("input[name='submit']").click();
});
</script>
<?php
include("podnozje.php");
?>
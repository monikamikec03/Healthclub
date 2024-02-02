<?php	
ob_start();
session_start();
$list = 'rubrike';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/var.php");	
	require("../include/putanja.php");	
	require("zaglavlje.php");
}
else{
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}
		
		function test_input($data) {
		  $data = trim($data);
		  $data = strip_tags($data); 
		  $data = htmlspecialchars($data);
		return $data;}	
	
	$poruka = $naziv = "";	

	if (empty($_POST["naziv"])) {
		$naziv = "";
		} 
		else 
		{
		$naziv = test_input($_POST["naziv"]);
		if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!.,:; ]*$/",$naziv)) 
			{
			$nazivErr = "* specijalni znakovi neĆe biti spremljeni u bazu"; 
			}
		}	
			
		if(isset($_FILES["slika"]["tmp_name"]) && $_FILES["slika"] ["size"] != 0) 
		{				
			$target_dir = "/cms/slike_portal/";
			$target_file = $target_dir . basename($_FILES["slika"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
			
			if ($_FILES["slika"]["size"] > 1000000) {
				$poruka = "<h1>Slika je prevelike rezolucije, morate je smanjiti</h1>";
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
				$putanja = "/cms/slike_portal/{$vrijeme}.$imageFileType";   
				
					if(isset ($nazivErr)) 
					{
					$poruka = "* znakovi naziva slike nisu ispravni";
					}
					else
					{	
					$sql ="INSERT INTO slike (opis_slike, putanja, autor_id) ";
					$sql .="VALUES ( '$naziv', '$putanja', {$_SESSION['idKorisnika']} );";

						if (mysqli_query($veza, $sql))	    		
						{
							$putanja = "../slike_portal/{$vrijeme}.$imageFileType";			
							if (!move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja))
							{
								exit ( "nije dobar direktori za spremanje slike" );	
							}
						header("Location: slike.php");
						}
						else
						{
						exit ( "spremanje u bazu nije uspjelo");  					
						}
					} 
				}
			
			}
		}
?>



<div id="sadrzaj">
	<form  class="formUnos" method="POST" action="unos_slike.php" 
		enctype="multipart/form-data" >	
		
		<div style="float:right;">	
			<input class='btn btn-danger m-1' type="button" value="&#x21BB; Odustani" 
					onclick="javascript:void(location.href ='slike.php')"/>			
			<input class="btn btn-success m-1" type="submit" name="submit" value="&#8730; Spremi">
	
		</div>		
			<h1>Unos slike</h1>		
		<div style="clear:both;"></div><hr /><br /><br />

		<input type="file" name="slika"><br /><br />
		<label> Naziv slike: </label>
		<input type="text" name="naziv">		
			<br/></br><h3 style="color:red;"><?php echo $poruka; ?></h3><br />   							
	</form>	
</div>		
</body>
</html> 
<?php ob_end_flush(); ?>
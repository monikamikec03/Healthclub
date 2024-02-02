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
?>
<div id="sadrzaj">	
		<div class="d-flex justify-content-between flex-wrap align-items-center">		
			<h2>slika prikaz</h2>
			<input class='btn btn-danger m-1' type="button" value="&#x21BB; Odustani" 
					onclick="javascript:void(location.href ='slike.php')"/>				
		</div>
							<div syle='clear:both;'></div><hr />
			<?php	
				if (isset($_GET["id"]))
				{						
				$id = addSlashes($_GET["id"]);			
				$sql = "SELECT * FROM slike 
				WHERE id_slike = $id";	  			
				$rezultat = mysqli_query($veza, $sql);
					while ($redak = mysqli_fetch_array($rezultat))  
					{
						$id = $redak["id_slike"];
						$putanja = $redak["putanja"];
						$naslov = $redak["opis_slike"];						
						$datum = strtotime($redak['datum_unosa']);	
					
						echo "<div style='width:80%;margin:10px auto;'>";		
						echo "<h2> $putanja</h2><br />";
						echo "<h3>opis slike: $naslov</h3><br />";						
						echo "<img src='$putanja' width='100%' />";
						
						echo "</div>"; 
					}
				}
			?>	
		<div style="clear:both;"></div>	
		<hr />
		<?php
		if(in_array($_SESSION['idRole'], [15588])){		
		echo "<a class='button_odustani' href='brisanje_slike.php?id=$id'>Obriši sliku</a>";
		}
		?>
</div>
</body>
</html> 
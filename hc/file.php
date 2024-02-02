<?php	
ob_start();
session_start();
$list = 'dokumenti';
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
		require("../include/var.php");		
		require("../$file_putanja/putanja.php");
}
else{
	 echo "<script> window.location.replace('../poduzetnik/odjava.php');</script>";
}
		setlocale(LC_ALL, 'hr_HR.utf-8');	
		

$title = "POPIS POSLOVA U PONUDI";

//*************************************************************************************************************//
//******************************************** popis poslova index **************************************************//
	 $danas = date("d.m.Y");

	
	//prvo prebrojavam broj oglasa
			$poslovi = "SELECT COUNT(id_file) AS broj_dokumenata FROM file";			
				$broj_poslova = mysqli_query($veza, $poslovi);	
				$poslovi = mysqli_fetch_array($broj_poslova);
				$broj= $poslovi['broj_dokumenata'];
				
				
				
	$br = 1;
	$sql = "SELECT * FROM file, korisnici, aktivnosti 
			WHERE id_aktivnost = zadaci_id
			AND korisnici_id = id_korisnik";
			$rezultat = mysqli_query($veza, $sql);
			while ($redak = mysqli_fetch_array($rezultat))
			{
				$id_korisnik = $redak["id_korisnik"];
				$id = $redak["id_file"];						
				$naziv_file = $redak["naziv_file"];
				$putanja = $redak["putanja"];
				$veza_rn = $redak["zadaci_id"];	
				$korisnik = $redak["naziv_korisnika"];				
		
			$display_block_popis .= "
				<tr>
					<td><a class='text-secondary' href='prikaz_korisnik.php?id=$id_korisnik'>$korisnik</a></td>					
					<td>$naziv_file; </a></td>		
					<td><a class='font-weight-bold' href='$putanja' target='_blank'>$putanja</a></td>									
					<td align='center'><a href='prikaz_aktivnosti.php?id_aktivnost=$veza_rn'>$veza_rn</a></td>			
				 </tr>";
					
					
		
								
										
	};



include("zaglavlje.php"); 	
?>
	<div class="sadrzaj">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Dokumenti prodaje: <?php echo $broj ?></h2>
			<div class='d-flex flex-wrap'>
				<input class="form-control my-1 w-auto m-1" id="myInput" type="text" placeholder="traži ..."> 
			</div>		
		</div>

		<table id="table_id" class="display" style="width:100%"> 
			<thead>
				<tr>	
					<td>Naziv korisnika</td>				
					<td>Naziv dokumenta</td>	
					<td>Putanja</td>											
 					<td>Akrivnost br.</td> 
				</tr>
			</thead>
		    <tbody id='myTable'>
				<?php echo $display_block_popis; ?> 
			</tbody>
		</table>
	</div>
	<script type="text/javascript" language="javascript">
		$(document).ready(function(){
		  $("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#myTable tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		  });
		});
	</script>	
	<script>
		var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";

		var blob = new Blob([html], { type: "application/vnd.ms-excel" });
		var a = document.getElementById("dd");
			a.href = URL.createObjectURL(blob);
			a.download = "ure.xls";
	</script>
</body>
</html>
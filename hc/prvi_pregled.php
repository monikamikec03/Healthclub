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

	
		setlocale(LC_ALL, 'hr_HR.utf-8');	

	$danas = date("d.m.Y");
	 
	$red = "SELECT COUNT(id_prijave) AS broj_kupaca FROM prijava_prvi_pregled";			
	$broj_poslova = mysqli_query($veza, $red);	
	$red = mysqli_fetch_array($broj_poslova);
	$broj_kupaca = $red['broj_kupaca'];	 
				

?>
<div class="container-fluid">
	<div class='d-flex flex-wrap justify-content-between align-items-center'>
		<h2>Broj prijava: <?php echo $broj_kupaca ?></h2>
		<div class='d-flex flex-wrap'>
			<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži..."> 
		
			<a href='' id='dd' class="btn btn-success m-1"><i style="font-size:18px" class="fa">&#xf1c3;</i></a>
		</div>
	</div>
	
	<div class="table-responsive">
		<table id="table_id" class="table table-hover table-stripped border-light DataTable">  
			<thead>
				<tr>
					  <td>ID</th>	
					  <td>Ime i prezime</td>		  
					  <td>Email</td>	  
					  <td>Broj mobitela</td>	  
					  <td>Godine</td>	  
					  <td>Datum prijave</td>
				  
				</tr>
			</thead>
			<tbody id='myTable'>
			<?php 
			$sql = ("SELECT * FROM prijava_prvi_pregled ORDER BY datum_prijave DESC");				
			$res = mysqli_query($veza, $sql);						
			while($red = mysqli_fetch_array($res)) 
			{
				$id= $red['id_prijave'];	 
				$ime_prezime = $red['ime_prezime'];  
				$email = $red['email'];  
				$broj_mobitela = $red['broj_mobitela'];		 
				$godine = $red['godine'];		 
				
				$datum_prijave = date("Y.m.d.",strtotime($red['datum_prijave']));		

				?>	
					
				<tr>
					<td><b><?php echo $id; ?></b></td>		
					<td><b><a href='prikaz_prijave_p.php?id=<?php echo $id; ?>'><?php echo $ime_prezime; ?></a></b></td>			
					<td><?php echo $email; ?></td>						
					<td><?php echo $broj_mobitela; ?></td>		
					<td><?php echo $godine; ?></td>		
					<td><?php echo $datum_prijave; ?></td>		
				</tr>
				<?php
				} 
				?>
			</tbody>
		</table>
	</div>
</div>
	<script>
		$(document).ready(function() {
			$('#table_id').DataTable({			
				"paging":   false,
				"searching": false,
				"info":     false,
				"order": [],				
			});
		});		
	</script>
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
			a.download = "excel.xls";
	</script>
</body>
</html>
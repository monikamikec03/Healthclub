<?php
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{
	require("../include/var.php");		
	require("../include/putanja.php");
	require("navigacija.php");
}
else{
	echo "<script> window.location.replace('prijava.php');</script>";
}
setlocale(LC_ALL, 'hr_HR.utf-8');	
date_default_timezone_set('Europe/Zagreb');

	function test_input($data) {
	  $data = trim($data);
	  $data = strip_tags($data); 
	  $data = htmlspecialchars($data);
	return $data;}



	$display_block_popis = "";


	$poslovi = ("SELECT COUNT(id_komentara) AS broj_komentara FROM komentari");					
	$broj_komentara = mysqli_query($veza, $poslovi);	
	while($poslovi = mysqli_fetch_array($broj_komentara)) 
	{
		$broj= $poslovi['broj_komentara'];
	}


	$sql = ("SELECT * FROM komentari, clanak
	WHERE id_clanak = clanak_id
	ORDER BY komentari.datum_unosa DESC");							
	$get_rsPoslovi = mysqli_query($veza, $sql);
	if(mysqli_num_rows($get_rsPoslovi) > 0){				
		while($poslovi = mysqli_fetch_array($get_rsPoslovi)) 
		{
			 $id_komentara = $poslovi['id_komentara'];				 
			 $naziv = $poslovi['naziv'];  
			 $naslov_clanka = $poslovi['naslov_clanka'];  
			 $id_clanak = $poslovi['id_clanak'];  
			 $komentar = $poslovi['komentar'];		 
			 $unijeto = date("Y.m.d. h:i",strtotime($poslovi['datum_unosa']));
	 
	 
	 
			$display_block_popis .= "
				<tr>
					<td>$id_komentara</td>	
					<td>$naziv</td>						
					<td>$komentar</td>	
					<td><a href='clanak.php?id=$id_clanak'>$naslov_clanka [$id_clanak]</a></td>	
					<td>$unijeto</td>		
					<td class='text-center'><a class='link-danger ms-auto my-2' href='brisanje_komentara.php?id_komentara=$id_komentara' onclick='return confirm(\"Potvrdite da želite obrisati ovaj komentar.\");'><i class=\"fs-3 fa-solid fa-square-minus\"></i></i></a></td>					
				 </tr>";
							
									
		}
	}



include("zaglavlje.php"); 	
?>
	<div class="container-fluid flex-grow-1 my-2">
		<div class='d-flex flex-wrap justify-content-between align-items-center'>
			<h2>Komentari: <?php echo $broj; ?></h2>
			<div class='d-flex flex-wrap'>
				<input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži...">
			</div>	
		</div>
	

	
		<div class="table-responsive">
			<table id="table_id" class="table table-hover table-stripped border-light">  
				<thead>  
					<tr>
					  <td>ID</td>	
					  <td>Naziv komentatora</td>	
					  <td>Komentar</td>		  
					  <td>Članak</td>		  
					  <td>Datum unosa</td>
					  <td>Brisanje</td>				  
					</tr>
				</thead>
				<tbody id="myTable">	
					<?php echo $display_block_popis; ?> 
				</tbody>	
		  </table>
		</div>
	</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
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

<script>
  	$(function(){
		$(".datum_start").datepicker({
		changeMonth: true,
		changeYear: true});
		$.datepicker.regional['hr'] = {
			closeText: 'Zatvori',
			prevText: '&#x3c;',
			nextText: '&#x3e;',
			currentText: 'Danas',
			monthNames: ['Siječanj','Veljača','Ožujak','Travanj','Svibanj','Lipanj',
			'Srpanj','Kolovoz','Rujan','Listopad','Studeni','Prosinac'],
			monthNamesShort: ['Sij','Velj','Ožu','Tra','Svi','Lip',
			'Srp','Kol','Ruj','Lis','Stu','Pro'],
			dayNames: ['Nedjelja','Ponedjeljak','Utorak','Srijeda','Četvrtak','Petak','Subota'],
			dayNamesShort: ['Ned','Pon','Uto','Sri','Čet','Pet','Sub'],
			dayNamesMin: ['Ne','Po','Ut','Sr','Če','Pe','Su'],
			weekHeader: 'Tje',
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['hr']);
		jQuery.validator.addMethod("date", function(value, element) {
			return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
		}, "Please enter a correct date");
		jQuery.validator.addMethod("integer2", function(value, element) {
			return this.optional(element) || /^\d{2}$/.test(value);
		}, "A positive or negative non-decimal number please");		
	});	

</script>
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
<?php
include("podnozje.php");
?>
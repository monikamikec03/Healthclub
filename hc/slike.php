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

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');

?>		
<div id="sadrzaj">
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2 class='p-3'>Slike za članke</h2>
		<form method="post" class='p-0 my-0  text-white d-flex justify-content-end align-items-center flex-grow-1 flex-wrap' action="upload_multiple.php" enctype="multipart/form-data">
			<label class='text-dark me-2'>Odaberite najprije gdje želite učitati slike: </label>
			<select name='obrt' class="form-select w-auto rounded-0"> 
				<?php
				$sql2 = "SELECT * FROM obrt";
				$result = mysqli_query($veza, $sql2);
				while($redak = mysqli_fetch_array($result)){
					$id_obrta = $redak["id_obrta"];
					$naziv_obrta = $redak["naziv_obrta"];
					?>
					<option value="<?php echo $id_obrta; ?>"><?php echo $naziv_obrta; ?></option>
					<?php
				}
				?>
			</select>
			<label class="btn btn-outline-success">
				<input type="file" id="file" name='files[]' class='d-none' multiple > 
				<div class='d-flex align-items-center'><i class="fs-4 fas fa-image mx-1"></i> </div>
				<input type='submit' name='posalji' value="Pošalji" class='d-none'>	
			</label>
		</form>
	</div>
	<div class='row m-1 d-flex align-items-top '>
		<div class='table-responsive'>
			<table class='table table-bordered' id='table_id'>
				<thead>
				<tr>
					<th>Slika</th>
					<th>Putanja</th>
					<th>Obrt</th>
					<th>Brisanje</th>
					<th>Uključeno - galerija</th>
					<th>Datum</th>
				</tr>
				</thead>
				<tbody>
				<?php				  
				$sql = "SELECT * FROM slike ORDER BY datum_unosa DESC";
				$res = mysqli_query($veza, $sql);
				while($red = mysqli_fetch_array($res)){
					$id_slike = $red["id_slike"];
					$putanja = $red["putanja"];
					$ukljuceno_galerija = $red["ukljuceno_galerija"];
					$obrt_id = $red["obrt_id"];
					$datum_unosa = date('Y.m.d. h:i', strtotime($red["datum_unosa"]));
				?>	

				<tr>
					<td class='p-0  m-0'><img class='modal-slika height-100 object-fit-contain' src='<?php echo $putanja; ?>'/></td>
					<td><?php echo $putanja; ?></div>
					<td>
						
						<select name='obrt' class="form-select border-0"> 
							<?php
							$sql2 = "SELECT * FROM obrt";
							$result = mysqli_query($veza, $sql2);
							while($redak = mysqli_fetch_array($result)){
								$id_obrta = $redak["id_obrta"];
								$naziv_obrta = $redak["naziv_obrta"];
								?>
								<option value="<?php echo $id_obrta; ?>" <?php if($id_obrta == $obrt_id) echo 'selected'; ?>><?php echo $naziv_obrta; ?></option>
								<?php
							}
							?>
						</select>
						<input type="hidden" name="id_slike" value="<?php echo $id_slike; ?>">
					</td>
					<td><a href='brisanje_slike.php?slika=<?php echo $putanja; ?>' class='text-danger' onclick="return confirm('Jeste li sigurni da želite obrisati ovu sliku?')">Obriši</a></td>

					<td>	
						
						<div class="form-check form-switch d-flex align-items-center">
							<input class="form-check-input ukljuceno_galerija" type="checkbox" role="switch"  <?php if($ukljuceno_galerija == 1) echo "checked"; ?> value="<?php echo $id_slike; ?>">
						</div>
					</td>
					<td><?php echo $datum_unosa; ?></td>	
			
				</tr>

				<?php
				
				}
				?>
				</tbody>
			</table>
		</div>
	</div>		
</div>	


<div id="myModal" class="modal2">
  <span class="close btn btn-danger fs-2 d-inline-block position-fixed top-0 py-0 m-3">&times;</span>
  <img class="modal-content2" id="img01" src='';>

</div>
<script>
$(".close").click(function(){
	$('#myModal').hide();
})

$('.modal-slika').click(function(){
	
	let src = $(this).attr('src');
	console.log(src);
	$('#img01').attr("src", src);
	$('#myModal').show();
	
});
$('.modal2').click(function(){
	$('#myModal').hide();
});

</script>

<script>
$('#file').on("change", function(){
	$("input[name='posalji']").click();
});
</script>
<script>
$(".ukljuceno_galerija").click(function(){
	let id_slike = $(this).val();
	
	$.ajax({
		url: "dropdown.php",
		type: "post",
		data: {
			id_slike:id_slike,
			
			
		},
		success: function (response) {
		
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
});

$("select[name='obrt']").change(function(){
	let obrt = $(this).val();
	let slika_galerija = $(this).next().val();
	
	$.ajax({
		url: "dropdown.php",
		type: "post",
		data: {
			obrt:obrt,
			slika_galerija:slika_galerija,
			
			
		},
		success: function (response) {
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
	
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
</body>
</html> 
<?php ob_end_flush(); ?>
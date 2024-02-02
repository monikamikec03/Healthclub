<?php
include("zaglavlje.php");
include("navigacija_light.php");

function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}

if (!empty($_GET["galerija"])){
	$id = (int)(test_input($_GET['galerija']));	
	$sql =("SELECT * FROM obrt WHERE id_obrta = $id ");	
	$rezultat = mysqli_query($veza, $sql);
	if($red = mysqli_fetch_array($rezultat)){
		$id_obrta = $red["id_obrta"];	
	}
}
else{
	$id_obrta = 1;
}
	

$sql = "SELECT * FROM slike WHERE ukljuceno_galerija = 1 AND obrt_id = $id_obrta ORDER BY datum_unosa DESC";
$res = mysqli_query($veza, $sql);
while($red = mysqli_fetch_array($res)){
	$putanje[] = $red["putanja"];
} 
?>


<div class="image-gallery flex-grow-1 ">
	<?php
	$broj_stupaca = 4;
	$broj_slika_po_stupcu = ceil(count($putanje)/$broj_stupaca);
	for($i = 0; $i < $broj_stupaca; $i++){ 
		
		echo "<div class='column'>";
		for($j = 0; $j < $broj_slika_po_stupcu; $j++){
			$indeks = $i + ($j*$broj_stupaca);
			if(empty($putanje[$indeks])){
				break;
			}
			echo "<div class='image-item'><img onclick='otvori()' class='modal-slika' src='".$putanje[$indeks]."'></div>";
		}
		echo "</div>";
	}
	
	?>

	
</div>



<div id="myModal2" class="modal2">
  <span class="close btn btn-danger fs-2 d-inline-block position-fixed top-0 py-0 m-3">&times;</span>
  <img class="modal-content2" id="img02" src='';>

</div>
<script>
$(".close").click(function(){
	$('#myModal2').hide();
})

$('.modal-slika').click(function(){
	
	let src = $(this).attr('src');
	console.log(src);
	$('#img02').attr("src", src);
	$('#myModal2').show();
	
});
$('.modal2').click(function(){
	$('#myModal2').hide();
});

</script>

<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>



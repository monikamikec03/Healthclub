<?php
include("zaglavlje.php");
include("navigacija_light.php");
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}



if (isset($_GET["rezervacija"])) {								
	$id = test_input($_GET["rezervacija"]);
	if (!preg_match("/^[0-9 ]*$/",$id)){
        
	}    
	
}

?>

    

<div class="container flex-grow-1 my-5">
    <?php
	if($id == 1) header("location:rezervacija_trening.php");
	else if($id == 2) header("location:rezervacija_rehabilitacija.php");
	else{
		?>
		<h2>Odaberite gdje želite rezervirati termin?</h2>
		<div class="d-flex justify-content-center align-items-center my-3">
			<div class="d-flex flex-column align-items-center m-3">
				<a href="rezervacija_trening.php" class="btn btn-success">Probni trening</a>
				<small>BasicGymOne Vrbovec</small>
			</div>
			<div class="d-flex flex-column align-items-center m-3">
				<a href="rezervacija_rehabilitacija.php" class="btn btn-warning">Prvi pregled</a>
				<small>FizioOne</small>
			</div>
		</div>
		
		<?php
	}
	?>
</div>



<script>
	document.addEventListener("DOMContentLoaded", function(event) { 
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
</script>
<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>

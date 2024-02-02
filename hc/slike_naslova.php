<?php	
ob_start();
session_start();
$list = 'slike_naslova';
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
	return $data;
}	

setlocale(LC_ALL, 'hr_HR.utf-8');

?>		
<div id="sadrzaj">
	<div class='d-flex justify-content-between align-items-center flex-wrap'>
		<h2 class='p-3'>Naslovne slike</h2>
	</div>
	<div class='row m-1 d-flex align-items-top '>
	
	<?php				  
	$sql = "SELECT * FROM slike_naslova, clanak 
	WHERE clanak_id = id_clanak
	ORDER BY clanak.datum_unosa DESC";
	$res = mysqli_query($veza, $sql);
	while($red = mysqli_fetch_array($res)){
		$id = $red['id_slike'];
		$putanja = $red['putanja'];
		$naslov_clanka = $red['naslov_clanka'];
	?>	
		<div class='col-md-3 p-2'>
			<div class=' hover-shadow'>
				<img src='<?php echo $putanja; ?>' class='slika'/>
				<p class=' bg-white px-3 pt-2 mb-0 text-break small' id='myInput' disabled><?php echo $putanja; ?></p>
				<div class='d-flex justify-content-end w-100'>
					<a href='brisanje_slike_naslova.php?id=<?php echo $id; ?>' class='text-decoration-underline bg-white border-0 px-3 pb-1 small text-danger' onclick="return confirm('Jeste li sigurni da želite obrisati naslovnu sliku članka: <?php echo $naslov_clanka; ?>?')">Obriši</a>
					<button class='text-decoration-underline bg-white border-0 px-3 pb-1 small text-primary' onclick="copyText('<?php echo $putanja; ?>')">Kopiraj</button>
				
				</div>
			</div>
		</div>
	<?php
	
	}
	?>
	</div>		
</div>	

<div id="myModal" class="modal">
	<span class="close text-white">×</span>
	<img class="modal-content" id="img01" src=''>
	<div id="caption" class='text-break'></div>
</div>


<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var slike = document.querySelectorAll(".slika");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

slike.forEach(item => {
	item.addEventListener('click',function(){
	console.log(item);
	modal.style.display = "block";
	modalImg.src = item.src;
	  captionText.innerHTML = item.src;
	});
	
});

for(let i = 0; i < slike.lenght; i++){
	slike[i].addEventListener("click", function(){
		console.log(slike[i]);
	  modal.style.display = "block";
	  modalImg.src = slike[i].src;
	  captionText.innerHTML = this.alt;
	});
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("modal")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>
<script>
function copyText(copyText) {
  navigator.clipboard.writeText(copyText);
  alert("Kopirani tekst: " + copyText);
}
</script>
<script>
document.querySelector('#file').addEventListener("change", function () {
	document.querySelector("#form").submit();
});
</script>
</body>
</html> 
<?php ob_end_flush(); ?>
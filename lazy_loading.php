<?php
include("zaglavlje.php");
include("navigacija_light.php");

?>
<style>
#block{
	
}
	#block h2{
		padding:30px;
		border:1px solid;
	}
</style>
<div id="block">
<?php
	$sql = "SELECT * FROM clanak
    WHERE tip_teksta = 1
    AND objavljen = 1
    AND datum_objave <= CURDATE()
    ORDER BY datum_objave DESC
	LIMIT 4";
	$res = mysqli_query($veza, $sql);

	while($row = mysqli_fetch_array($res)){
		$id_clanak = $row["id_clanak"];
		$naslov_clanka = $row["naslov_clanka"];
		echo $posljednji_datum = $row["datum_objave"];
		?>
		<h2><?php echo $naslov_clanka; ?></h2>
		<h1><?php echo "ID ČLANKA: $id_clanak"; ?></h1>
		<?php
	}

	
	?>
	
</div>

<script>

let load_flag = 5;
function loadMore(start){
	let posljednji_datum = '<?php echo $posljednji_datum; ?>';
	
	console.log(posljednji_datum);
	$.ajax({
		url: "dohvati_clanke.php",
		type: "post",
		data: {
			start:start,
			posljednji_datum:posljednji_datum,
			
		},
		
		success: function (result) {
			$('#block').append(result);
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
}
$(document).ready(function(){	
	$(window).scroll(function(){
		if($(window).scrollTop() >= $(document).height() - $(window).height()){
			loadMore(load_flag);
			load_flag+=5;
		}
	});
});

</script>
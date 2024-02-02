<div class="d-flex flex-column" id="pozadina_vegas">

<div class="hamburger_icon btn m-3 px-3 py-2 border-3 btn-danger shadow"><i class="fa-solid fa-grip-lines"></i></div>
<div class="flex-shrink-1 shadow-sm bg-light bg-opacity-85 navigacija2 ">
	
	<div class="container d-flex flex-lg-row flex-column justify-content-lg-between align-items-lg-end py-0 animated fadeIn">
		<a href='index.php' class='link-light btn-logo p-3 d-flex flex-column align-items-center justify-content-center text-center bg-none w-auto'>
			<img src="slike/HEALTHCLUB_LOGO.png" class="ms-2 logo-slika" alt="Health Club logo">
			<span class="link-dark">HEALTHCLUB</span> 
		</a>
		<div class="d-flex flex-column align-items-lg-end align-items-center ">
			<div class="d-flex flex-row flex-sm-wrap ms-lg-auto me-lg-0 m-auto"> 
				<a href='https://goo.gl/maps/EDGL7CuKuXNR8k29A' target='_blank' class="btn btn-success m-1"><i class="fa-solid fa-location-dot"></i></a>
				<a href='mailto:healthclub.vrbovec@gmail.com' class="btn btn-success m-1"><i class="fa-solid fa-envelope"></i></a>
				<a href='tel:+385989520746' class="btn btn-success m-1"><i class="fa-solid fa-phone"></i></a>
				<a href="rezervacija.php" class="btn btn-success m-1">Rezervacija</a>
			</div>
			<div class="d-flex flex-md-row flex-column flex-sm-wrap ms-lg-auto me-lg-0 m-auto w-100">
	
				<div class="dropdown">
					<a href='basicgymone.php' class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 <?php if($_GET["galerija"] == 1 || $_GET["rezervacija"] == 1) echo 'active'; ?>">BasicGymOne<i class="ms-2 fa-solid fa-caret-down"></i></a>
					<div class="dropdown-content">
						
						<a href='grupni_treninzi.php' class="nav-link-light">Grupni treninzi</a>
						
						<a href='individualni_treninzi.php' class="nav-link-light">Individualni treninzi</a>
						<a href='kondicija.php' class="nav-link-light">Kondicija </a>
						<a href='dijagnostika.php' class="nav-link-light">Dijagnostika</a>
						<a href='specijalni_programi.php' class="nav-link-light">Specijalni programi</a>
						<!--<a href='seminari' class="nav-link-light">Seminari</a>-->
						<a href='galerija.php?galerija=1' class="nav-link-light <?php if($_GET["galerija"] == 1) echo 'active'; ?>">Galerija </a>
						<a href='dojmovi_bgo.php' class="nav-link-light">Dojmovi</a>
						<a href='rezervacija.php?rezervacija=1' class="nav-link-light <?php if($_GET["rezervacija"] == 1) echo 'active'; ?>">Rezervacija </a>
					</div>
				</div>
				
				<div class="dropdown">
					<a href='fizioone.php' class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 <?php if($_GET["galerija"] == 2 || $_GET["rezervacija"] == 2) echo 'active'; ?>">FizioOne<i class="ms-2 fa-solid fa-caret-down"></i></a>
					<div class="dropdown-content">
						
						<a href='fizioone.php#usluge' class="nav-link-light">Usluge</a>
						<a href='galerija.php?galerija=2' class="nav-link-light <?php if($_GET["galerija"] == 2) echo 'active'; ?>">Galerija </a>
						<a href='dojmovi_hc.php' class="nav-link-light">Dojmovi</a>
						<a href='rezervacija.php?rezervacija=2' class="nav-link-light <?php if($_GET["rezervacija"] == 2) echo 'active'; ?>">Rezervacija </a>
					</div>
				</div>
				
			
				<div class="dropdown">
					<a href="blog.php" class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 ">Blog<i class="ms-2 fa-solid fa-caret-down"></i></a>
					<div class="dropdown-content">
						<?php
	
						$sql = "SELECT * FROM kategorije WHERE aktivna_kategorija = 1 ORDER BY redoslijed";
						$res = mysqli_query($veza, $sql);
						while($red = mysqli_fetch_array($res)){
							$id_kategorije = $red["id_kategorije"];
							$naziv_kategorije = $red["naziv_kategorije"];
							?>
							<a href='blog.php?id=<?php echo $id_kategorije; ?>' class="nav-link-light <?php if($_GET["id"] == $id_kategorije) echo "bg-success text-light"; ?>"><?php echo $naziv_kategorije; ?></a>
							<?php
						}
						?>
					</div>
				</div>
				<a href='dojmovi_korisnika.php' class="nav-link link-light text-dark p-3">Dojmovi korisnika</a>
				<a href='o_nama.php' class="nav-link link-light text-dark p-3">O nama</a>
				
				
			
			</div>
		
		</div>
	
	</div>
</div>
<script>
$(document).ready(function(){
	
	$(".hamburger_icon").click(function(){
		
		$(".navigacija2").animate({'width': 'toggle'});
		$(".hamburger_icon i").toggleClass( "fa-grip-lines" );
		$(".hamburger_icon i").toggleClass( "fa-x" );

	});
});
</script>
<script>


var url = window.location.pathname;

var filename = url.substring(url.lastIndexOf('/')+1);

let active_file = $('a[href$="'+ filename +'"]');

active_file.addClass('active');

var hash = window.location.hash.substr(1);  

if(hash.length == 0){

	$('a[href="' + filename + '"]').addClass('active'); 

}

else{

	$('a[href="' + filename + '#' + hash + '"]').addClass('active'); 

}
</script>
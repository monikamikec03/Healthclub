<?php
include("zaglavlje.php");
include("navigacija_light.php");

?>
<!--<img src="slike/hc_background2.jpg" class="position-fixed bg-image ">-->

<div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center py-5 px-2 bg-light bg-opacity-85 h-100">
    <img src="slike/HEALTHCLUB_LOGO.png" class="ms-3 d-md-none col-sm-4 col-6 p-4 animated rollIn" alt="Health Club logo">
    <div class="col-md-8 text-center py-5 px-2">
        <h1>HEALTH CLUB</h1>
        <h2 class="text-success">Centar za trening i rehabilitaciju</h2>
        <p>Naša misija je pomoći ljudima da izgube kilograme, oblikuju tijelo, riješe se bolova i rehabilitiraju na pravilan i jedinstven način. Cilj je ostati što duže funkcionalan i spreman odgovoriti na zahtjeve svakodnevnog života. Zašto neki ljudi u svojoj 65. godini trče, skaču, vježbaju, žive dinamičan život, a drugi su polupokretni? Zašto ne biste vi bili ti koji žive život punim plućima do kraja? Mi smo tu za Vas! </p>
    </div>
    <div class="d-flex flex-wrap justify-content-center align-items-center mb-5 pb-5">
        <a href="rezervacija_trening.php" class="btn btn-success m-1 fw-bold">Prijavi se na probni trening</a>
        <a href="rezervacija_rehabilitacija.php" class="btn btn-warning m-1 fw-bold">Prijavi se na prvi pregled</a>

    </div>
    <!--
		<div class="my-5 ">
			<div class="container responzivno">
			<?php //include("sihterica.php"); 
            ?>
			</div>
		</div>
		-->


</div>



</div>



<script>
    $("#pozadina_vegas").vegas({
        slides: [{
                src: "/slike/slika15.jpeg"
            },
            {
                src: "/slike/slika7.jpeg"
            },
            {
                src: "/slike/slika14.jpeg"
            },
            {
                src: "/slike/slika1.jpeg"
            },
            {
                src: "/slike/slika3.jpeg"
            },
            {
                src: "/slike/slika18.jpeg"
            },
            {
                src: "/slike/slika19.jpeg"
            },
            {
                src: "/slike/slika6.jpeg"
            },


        ],
        transition: 'blur2',
    });
</script>
<script>
    /*
	document.addEventListener("DOMContentLoaded", function(event) { 
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
	*/
</script>


<?php
include("drustveneMreze_light.php");
include("podnozje.php");
?>
<?php
include("zaglavlje.php");
?>

<div class="hamburger_icon btn m-3 px-3 py-2 btn-success"><i class="fa-solid fa-grip-lines"></i></div>
<div class="d-md-none p-4"></div>


<div class="flex-shrink-1 bg-white shadow-sm navigacija2">

    <div class="d-flex flex-lg-row flex-column justify-content-start align-items-center py-0 ">
        <a href="index.php" class="d-flex flex-row justify-content-center align-items-center nav-link link-light text-dark p-3">
            <img src="../slike/HEALTHCLUB LOGO.png" class="logo-slika2 me-2 h-auto">
            HEALTHCLUB
        </a>
        <div class="d-flex flex-column align-items-lg-end align-items-center ">

            <div class="d-flex flex-md-row flex-column flex-sm-wrap ms-lg-auto me-lg-0 m-auto">


                <div class="dropdown">
                    <button class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 ">Blog<i class="ms-2 fa-solid fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href='kategorije.php' class="nav-link-light ">Kategorije</a>
                        <a href='oznake.php' class="nav-link-light ">Oznake</a>
                        <a href='clanci.php' class="nav-link-light ">Članci</a>
                        <a href='komentari.php' class="nav-link-light">Komentari</a>
                        <a href='slike.php' class="nav-link-light">Galerija</a>
                    </div>
                </div>

                <a href='partneri.php' class="nav-link link-light text-dark p-3">Klijenti</a>
                <a href='clanovi.php' class="nav-link link-light text-dark p-3">Članovi</a>
                <a href='aktivnosti.php' class="nav-link link-light text-dark p-3">Aktivnosti</a>
                <a href='rubrike.php' class="nav-link link-light text-dark p-3">Grupe</a>
                <a href='evidencija_dolazaka.php' class="nav-link link-light text-dark p-3">Evidencija dolazaka</a>

                <div class="dropdown">
                    <button class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 ">Postavke<i class="ms-2 fa-solid fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href='praznici.php' class="nav-link-light ">Praznici</a>
                        <a href='tipovi_aktivnosti.php' class="nav-link-light ">Grupe aktivnosti</a>
                        <a href='jedinica_mjere.php' class="nav-link-light ">Jedinica mjere</a>
                        <a href='popis_artikala.php' class="nav-link-light ">Artikli</a>
                        <a href='cijene.php' class="nav-link-light ">Cijene</a>

                        <a href='pregled_usera.php' class="nav-link-light">Korisnici</a>

                    </div>
                </div>

                <div class="dropdown">
                    <button class="dropdown-btn nav-link link-light text-dark p-3 bg-transparent border-0 ">Prijave<i class="ms-2 fa-solid fa-caret-down"></i></button>
                    <div class="dropdown-content">

                        <a href='probni_trening.php' class="nav-link-light ">Probni trening</a>
                        <a href='prvi_pregled.php' class="nav-link-light ">Prvi pregled</a>


                    </div>
                </div>

                <a href='mail.php' class="nav-link link-light text-dark p-3">Newsletter</a>



            </div>

        </div>

    </div>
</div>
<script>
    $(document).ready(function() {

        $(".hamburger_icon").click(function() {

            $(".navigacija2").animate({
                'width': 'toggle'
            });
            $(".hamburger_icon i").toggleClass("fa-grip-lines");
            $(".hamburger_icon i").toggleClass("fa-x");

        });
    });
</script>
<script>
    var url = window.location.pathname;
    var filename = url.substring(url.lastIndexOf('/') + 1);

    let active_file = $('a[href$="' + filename + '"]');
    active_file.addClass('active');
</script>
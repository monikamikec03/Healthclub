<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
    require("../include/var.php");
    require("../include/putanja.php");
    require("navigacija.php");
} else {
    echo "<script> window.location.replace('prijava.php');</script>";
}
setlocale(LC_ALL, 'hr_HR.utf-8');


if (isset($_GET["id"]))         //prikaz podataka 
{
    $id = (int)($_GET['id']);
    $sql = ("SELECT * FROM korisnici WHERE id_korisnik = $id ");
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {

        $id_korisnik = $id = $redak['id_korisnik'];
        $oib = $redak['oib'];
        $naziv_korisnika = $redak['naziv_korisnika'];
        $adresa_korisnika = $redak['adresa_korisnika'];
        $napomena = $redak['napomena_korisnika'];
        $qr_slika = $redak['qr_slika'];

        $mail = $redak['mail'];
        $telefon = $redak['telefon'];
        $kontakt_osoba = $redak['kontakt_osoba'];
        if ($redak["osloboden_placanja"] == 1) $osloboden_placanja = 'Da';
        else $osloboden_placanja = 'Ne';

        if ($redak["aktivan"] == 1) $aktivan = 'Aktivan';
        else $aktivan = 'Neaktivan';

        if (($redak["kupac"]) == 1) $kupac = ' Kupac ';
        if (($redak["potencijalni_kupac"]) == 1) $potencijalni_kupac = ' Potencijalni kupac ';
        if (($redak["dobavljac"]) == 1) $dobavljac = ' Dobavljač ';
        if (($redak["vlasnik"]) == 1) $vlasnik = ' Vlasnik ';
        if (($redak["partner"]) == 1) $partner = ' Partner ';
        if (($redak["zakupodavac"]) == 1) $zakupodavac = ' Zakupodavac ';
        if (($redak["zakupoprimac"]) == 1) $zakupoprimac = ' Zakupoprimac ';
    }
}




include("zaglavlje.php");
?>


<div id="sadrzaj">

    <div class='tab'>
        <li><a href="javascript:openTab(1);" id="tabLink1" class="tabLinkActive">Korisnik</a></li>
        <li><a href="javascript:openTab(4);" id="tabLink4" class="tabLink">Članarina</a></li>
        <li><a href="javascript:openTab(2);" id="tabLink2" class="tabLink">Aktivnosti</a></li>
        <li><a href="javascript:openTab(3);" id="tabLink3" class="tabLink">Dokumenti</a></li>
        <li><a href="javascript:openTab(5);" id="tabLink5" class="tabLink">Evidencija dolazaka</a></li>
    </div>

    <div class="tabContentActive" id="tabContent1">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2>Prikaz: <?php echo $naziv_korisnika; ?></h2>

            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <a class='btn btn-primary m-1' href='unos_korisnik.php?id=<?php echo $id ?>'><i class='fas fa-edit'></i> Uredi </a>
                <a onclick="return confirm('Potvrdite brisanje.');" class='btn btn-danger m-1' href='brisanje.php?id_korisnik=<?php echo $id ?>'><i class='fa-solid fa-trash'></i> Obriši </a>
                <a class='btn btn-warning m-1' href='clanovi.php'>🡸 Natrag</a>
            </div>
        </div>
        <div class='row'>

            <div class='col-md-3'>
                <label>Naziv kupca:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $naziv_korisnika; ?></span>
            </div>

            <div class='col-md-3'>
                <label>OIB:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $oib; ?></span>
            </div>

            <div class='col-md-3'>
                <label>Adresa:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $adresa_korisnika; ?></span>
            </div>


            <div class='col-md-3'>
                <label>Email:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $mail; ?></span>
            </div>

            <div class='col-md-3'>
                <label>Telefon :</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $telefon; ?></span>
            </div>

            <div class='col-md-3'>
                <label>Kontakt osoba:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $kontakt_osoba; ?></span>
            </div>

            <div class='col-md-3'>
                <label>Aktivan:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $aktivan; ?></span>
            </div>
            <div class='col-md-3'>
                <label>Oslobođen/a plaćanja:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $osloboden_placanja; ?></span>
            </div>

            <div class='col-md-9'>
                <label>Napomena:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo $napomena; ?></span>
            </div>
            <div class='col-md-12'>
                <label>Funkcija:</label>
                <span class='font-weight-bold form-control bg-light'><?php echo "$kupac $potencijalni_kupac $dobavljac $vlasnik $partner $zakupodavac $zakupoprimac"; ?></span>
            </div>
        </div>

        <div class="my-2">
            <?php if (empty($qr_slika)) { ?>
                <a href="generiraj_qr.php?id=<?php echo $id_korisnik; ?>" class="btn btn-secondary">Generiraj QR kod</a>
            <?php
            } else {
                echo "<img src='$qr_slika' class='col-xl-3'>";
            }
            ?>
        </div>
    </div>




    <div class="tabContent" id="tabContent2">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2>Aktivnosti: <?php echo $naziv_korisnika; ?></h2>

            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <input class="form-control my-1 w-auto m-1 fontAwesome" id="myInput" type="text" placeholder="&#xf002 Traži...">
                <a class='btn btn-primary m-1' href='unos_aktivnost.php?id_korisnik=<?php echo $id ?>'>+ Dodaj aktivnost </a>
                <a class='btn btn-warning m-1' href='partneri.php'>🡸 Natrag</a>
            </div>
        </div>
        <div class='table-responsive'>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>r.br.</th>
                        <th>Naziv aktivnosti</th>
                        <th>Napomena</th>
                        <th>Aktivnost izvršio</th>
                        <th>Datum aktivnosti</th>
                        <th>Vrijeme aktivnosti</th>
                        <th>Datum unosa</th>
                    </tr>
                </thead>
                <tbody id="myTable">


                    <?php
                    $br = 1;

                    $sql = "SELECT * FROM aktivnosti, poslodavac_user
					WHERE korisnik_id = $id_korisnik
					AND id_user = unijeo
					";
                    $rezultat = mysqli_query($veza, $sql);
                    while ($red = mysqli_fetch_array($rezultat)) {
                        $id_aktivnost = $red['id_aktivnost'];
                        $naziv = $red['naziv_aktivnosti'];
                        $napomena = $red['napomena'];
                        $datum_unosa = strtotime($red['datum_unosa']);
                        $datum_aktivnosti = strtotime($red['datum_aktivnosti']);
                        if ($red['vrijeme'] == '00:00:00') $vrijeme = '';
                        else $vrijeme = date("H:i", strtotime($red['vrijeme']));
                        $aktivnost_izvrsio = $red['puno_ime_poslodavac_user'];
                        $aktivan_user = $red['aktivan_user'];
                        if ($aktivan_user == 1) {
                            $aktivan = "aktivan";
                        } else if ($aktivan_user == 2) {
                            $aktivan = "neaktivan";
                        }
                        $color_aktivan = $aktivan_user == 2 ? 'class="bg bg-danger text-white"' : "";

                        echo "<tr>";
                        echo "<td>$br</td>";
                        $br++;
                        echo "<td><a href='prikaz_aktivnosti.php?id_aktivnost=$id_aktivnost'><b>$naziv</b></a></td>";
                        echo "<td> $napomena</td>";

                        echo "<td{$color_aktivan}> $aktivnost_izvrsio</td>";
                        echo "<td> " . date('d.m.Y.', $datum_aktivnosti) . "</td>";
                        echo "<td> $vrijeme</td>";
                        echo "<td> " . date('d.m.Y.', $datum_unosa) . "</td>";
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="tabContent" id="tabContent3">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2>Dokumenti: <?php echo $naziv_korisnika; ?></h2>
            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <a class='btn btn-warning m-1' href='kupci.php'>🡸 Natrag</a>
            </div>
        </div>

        <div class='table-responsive'>

            <table class="table table-hover table-striped">
                <tr>
                    <th>r.br.</th>
                    <th>Naziv aktivnosti</th>
                    <th>Naziv dokumenta</th>
                    <th>Datum unosa</th>
                </tr>

                <?php

                $br = 1;
                $sql = "SELECT * FROM file
			WHERE korisnici_id = $id_korisnik
			ORDER BY datum_unosa_file DESC";
                $rezultat = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($rezultat)) {
                    $id_aktivnost = $red['zadaci_id'];
                    $naziv_dokumenta = $red['naziv_file'];
                    $putanja = $red['putanja'];
                    $datum_unosa = date("d.m.Y", strtotime($red['datum_unosa_file']));

                    $akt = "SELECT * FROM aktivnosti WHERE	id_aktivnost = $id_aktivnost";
                    $rez = mysqli_query($veza, $akt);
                    if ($r = mysqli_fetch_array($rez)) $aktivnost = $r['naziv_aktivnosti'];

                    echo "<tr>";
                    echo "<td>$br</td>";
                    $br++;
                    echo "<td><a href='prikaz_aktivnosti.php?id_aktivnost=$id_aktivnost'><b>$aktivnost</b></a></td>";
                    echo "<td><a href='$putanja' target='_blank'> $naziv_dokumenta</a></td>";
                    echo "<td> $datum_unosa</td>";
                    echo "</tr>";
                }

                ?>
            </table>
        </div>
    </div>

    <div class="tabContent" id="tabContent4">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2>Članarina: <?php echo $naziv_korisnika; ?></h2>
            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <a class='btn btn-primary m-1' href='unos_aktivnost.php?id_korisnik=<?php echo $id ?>'>+ Dodaj </a>
                <a class='btn btn-warning m-1' href='kupci.php'>🡸 Natrag</a>
            </div>
        </div>

        <div class='table-responsive'>

            <table class="table table-hover table-striped">
                <tr>
                    <th>r.br.</th>
                    <th>Datum uplate</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Napomena</th>
                </tr>

                <?php

                $br = 1;
                $sql = "SELECT * FROM aktivnosti
			WHERE korisnik_id = $id_korisnik
			AND naziv_aktivnosti = 'Članarina'
			ORDER BY datum_aktivnosti DESC";
                $rezultat = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($rezultat)) {
                    $id_uplate = $red['id_aktivnost'];
                    $datum_uplate = date("d.m.Y.", strtotime($red['datum_aktivnosti']));
                    $od = date("d.m.Y.", strtotime($red['od']));
                    $do = date("d.m.Y.", strtotime($red['do']));
                    $napomena = html_entity_decode($red['napomena']);


                    echo "<tr>";
                    echo "<td>$br.</td>";
                    $br++;
                    echo "<td><a href='prikaz_aktivnosti.php?id_aktivnost=$id_uplate'><b>$datum_uplate</b></a></td>";
                    echo "<td> $od</td>";
                    echo "<td> $do</td>";
                    echo "<td> $napomena</td>";
                    echo "</tr>";
                }

                ?>
            </table>
        </div>
    </div>
    <div class="tabContent" id="tabContent5">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2>Evidencija dolazaka: <?php echo $naziv_korisnika; ?></h2>
            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <a class='btn btn-primary m-1' href='unos_aktivnost.php?id_korisnik=<?php echo $id ?>'>+ Dodaj </a>
                <a class='btn btn-warning m-1' href='kupci.php'>🡸 Natrag</a>
            </div>
        </div>

        <div class='table-responsive'>

            <table class="table table-hover table-striped DataTable w-100" id="table_id">
                <thead>
                    <tr>
                        <th>r.br.</th>
                        <th>Datum</th>
                        <th>Broj termina</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $br = 1;
                    $sql = "SELECT * FROM evidencije_dolazaka
			        WHERE korisnik_id = $id_korisnik
			        ORDER BY datum DESC";
                    $rezultat = mysqli_query($veza, $sql);
                    while ($red = mysqli_fetch_array($rezultat)) {
                        $id_dolaska = $red['id_dolaska'];
                        $redni_broj_termina = $red['redni_broj_termina'];
                        $datum = date("d.m.Y.", strtotime($red['datum']));


                        echo "<tr>";
                        echo "<td>$br.</td>";
                        $br++;
                        echo "<td><b>$datum</b></td>";
                        echo "<td> $redni_broj_termina</td>";
                        echo "<td class='link-danger'><button onclick='deleteDolazak($id_dolaska, this);'>Obriši</button></td>";
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                $('#table_id').DataTable({
                    language: {
                        decimal: ',',
                        thousands: '.',
                    },
                    "paging": true,
                    "info": true,
                    "order": [],
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 redova', '25 redova', '50 redova', 'Svi zapisi']
                    ],
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [':visible']
                            },
                            message: 'Evidencija dolazaka: <?php echo $naziv_korisnika; ?>',
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [':visible']
                            },
                            message: 'Evidencija dolazaka: <?php echo $naziv_korisnika; ?>',
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [':visible']
                            },
                            message: 'Evidencija dolazaka: <?php echo $naziv_korisnika; ?>',
                        },

                        {
                            extend: 'print',
                            orientation: 'portrait',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: [':visible']
                            },
                            message: 'Evidencija dolazaka: <?php echo $naziv_korisnika; ?>',
                        },
                        'pageLength',
                    ],
                    "order": [],
                    columnDefs: [{
                        type: 'date-eu',
                        targets: [1],
                    }],
                });
            });
        </script>
    </div>


</div>
<script>
    var activeTab = 1;

    function openTab(tabId) {
        document.getElementById("tabLink" + activeTab).className = "tabLink";
        document.getElementById("tabContent" + activeTab).className = "tabContent";
        document.getElementById("tabLink" + tabId).className = "tabLinkActive";
        document.getElementById("tabContent" + tabId).className = "tabContentActive";
        activeTab = tabId;
    }
</script>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<script>
    function deleteDolazak(id_dolaska, elem) {
        if (confirm("Potvrdite brisanje.")) {
            let tr = $(elem).closest("tr");

            $.post("brisanje.php", {
                    id_dolaska: id_dolaska,

                },
                function(data, status) {
                    if (data === '') {
                        $(tr).remove();
                    } else {
                        alert(data);
                    }
                });
        }
    }
</script>
<?php
include("podnozje.php");
?>
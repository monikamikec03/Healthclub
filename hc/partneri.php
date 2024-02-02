<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
    require("../include/putanja.php");
    require("navigacija.php");
} else {
    echo "<script> window.location.replace('prijava.php');</script>";
}


setlocale(LC_ALL, 'hr_HR.utf-8');

$danas = date("d.m.Y");

$red = "SELECT COUNT(id_korisnik) AS broj_kupaca FROM korisnici";
$broj_poslova = mysqli_query($veza, $red);
$red = mysqli_fetch_array($broj_poslova);
$broj_kupaca = $red['broj_kupaca'];


?>
<div class="container-fluid">
    <div class='d-flex flex-wrap justify-content-between align-items-center'>
        <h2>Klijenti: <?php echo $broj_kupaca ?></h2>
        <div class='d-flex flex-wrap'>
            <?php
            if ($_SESSION["idRole"] == "15588" or $_SESSION["idRole"] == "22")
                echo "<a class='btn btn-primary m-1' href='unos_korisnik.php'> + dodaj kupca</a>";
            ?>
            <a href='' id='dd' class="btn btn-success m-1"><i style="font-size:18px" class="fa">&#xf1c3;</i></a>
        </div>
    </div>

    <div class="table-responsive">
        <table id="table_id" class="table table-striped DataTable w-100">
            <thead>
                <tr>
                    <td>R.br.</th>
                    <td>Naziv</td>
                    <td>Adresa</td>
                    <td>Telefon</td>
                    <td>Kontakt osoba</td>
                    <td>Funkcija</td>
                    <td>Datum unosa</td>
                    <td>Aktivan</td>

                </tr>
            </thead>
            <tbody id='myTable'>
                <?php
                $br = 1;
                $sql = ("SELECT * FROM korisnici
			    ORDER BY aktivan DESC, dodano DESC, naziv_korisnika ASC		
			");
                $res = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($res)) {
                    $id = $red['id_korisnik'];
                    $naziv_korisnika = $red['naziv_korisnika'];
                    $adresa_korisnika = $red['adresa_korisnika'];
                    $mjesto_korisnika = $red['nazivMjesta'];
                    $broj_zaposlenih = $red['broj_zaposlenih'];
                    $telefon = $red['telefon'];
                    $kontakt_osoba = $red['kontakt_osoba'];
                    $dodano = date("d.m.Y.", strtotime($red['prvi_datum']));

                    if ($red["aktivan"] == 1) {
                        $aktivan = "Aktivan";
                        $css = 'fw-bold text-success';
                    } else {
                        $aktivan = "Neaktivan";
                        $css = 'text-danger fw-bold';
                    }
                    $funkcija = '';
                    if (($red["kupac"]) == 1) $funkcija .= ' Kupac, ';
                    if (($red["potencijalni_kupac"]) == 1) $funkcija .= ' Potencijalni kupac, ';
                    if (($red["dobavljac"]) == 1) $funkcija .= ' Dobavljač, ';
                    if (($red["vlasnik"]) == 1) $funkcija .= ' Vlasnik, ';
                    if (($red["partner"]) == 1) $funkcija .= ' Partner, ';
                    if (($red["zakupodavac"]) == 1) $funkcija .= ' Zakupodavac, ';
                    if (($red["zakupoprimac"]) == 1) $funkcija .= ' Zakupoprimac, ';


                ?>

                    <tr>
                        <td><b><?php echo $br++; ?>.</b></td>
                        <td><b><a href='prikaz_korisnik.php?id=<?php echo $id; ?>'><?php echo $naziv_korisnika; ?></a></b></td>
                        <td><?php echo $adresa_korisnika ?></td>
                        <td><?php echo $telefon ?></td>
                        <td><?php echo $kontakt_osoba ?></td>
                        <td><?php echo $funkcija; ?></td>
                        <td><?php echo $dodano; ?></td>
                        <td class='<?php echo $css; ?>'><?php echo $aktivan; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
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
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },

                {
                    extend: 'print',
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                'pageLength',
            ],
            "order": [],
            columnDefs: [{
                type: 'date-eu',
                targets: [6],
            }],
        });
    });
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
    var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";

    var blob = new Blob([html], {
        type: "application/vnd.ms-excel"
    });
    var a = document.getElementById("dd");
    a.href = URL.createObjectURL(blob);
    a.download = "excel.xls";
</script>
</body>

</html>
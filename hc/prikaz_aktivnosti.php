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

if (isset($_GET["id_aktivnost"]))         //prikaz podataka za postoječi izostanak
{
    $id = (int)($_GET['id_aktivnost']);
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        $sql = "SELECT*FROM aktivnosti, korisnici
		WHERE id_aktivnost = $id
		AND korisnik_id = id_korisnik";
        $rezultat = mysqli_query($veza, $sql);
        if ($red = mysqli_fetch_array($rezultat)) {
            $id_aktivnost = $red['id_aktivnost'];
            $aktivnost = $red['naziv_aktivnosti'];
            $napomena = $red['napomena'];
            $naziv_korisnika = $red['naziv_korisnika'];
            $korisnik = $red['korisnik_id'];
            $napomena = $red['napomena'];
            $tekst = html_entity_decode($red['tekst']);
            $datum_aktivnosti = date("d.m.Y", strtotime($red['datum_aktivnosti']));
            if (empty($red["od"])) {
                $od = "";
            } else {
                $od = date("Y.m.d.", strtotime($red['od']));
            }
            if (empty($red["do"])) {
                $do = "";
            } else {
                $do = date("Y.m.d.", strtotime($red['do']));
            }
            if ($red['vrijeme'] == '00:00:00') $vrijeme = '';
            else $vrijeme = date("H:i", strtotime($red['vrijeme']));
            if ($red['kalendar'] == 1) $kalendar = 'checked';
        }
    } else {
        header("Location:aktivnosti.php");
    }
}



require("zaglavlje.php");

?>

<div id="sadrzaj">

    <div class='d-flex flex-wrap justify-content-between align-items-center'>
        <h2>Aktivnost br: <?php echo $id; ?> - <i><?php echo $naziv_korisnika ?></i></h2>

        <div class='d-flex flex-wrap justify-content-end align-items-center'>
            <a class='btn btn-primary m-1' href="unos_aktivnost.php?id_aktivnost=<?php echo $id ?>"><i class='fas fa-edit'></i> Uredi </a>
            <a class='btn btn-danger m-1' href="brisanje.php?id_aktivnost=<?php echo $id ?>" onclick="return confirm('Potvrdite brisanje.');"><i class='fa-solid fa-trash'></i> Obriši </a>
            <a class='btn btn-warning m-1' href='aktivnosti.php'>🡸 Natrag</a>
        </div>
    </div>



    <div class='row'>
        <div class="col-md-4">
            <label>Korisnik</label>
            <span class="text-dark font-weight-bold form-control bg-light"><?php echo $naziv_korisnika; ?></span>
        </div>

        <div class="col-md-4">
            <label>Naziv aktivnosti:</label>
            <div class="text-dark form-control bg-light"><?php echo $aktivnost; ?></div>
        </div>


        <div class="col-md-4">
            <label>Datum aktivnosti:</label>
            <div class="text-dark form-control bg-light"><?php echo $datum_aktivnosti; ?></div>
        </div>
        <div class="col-md-4">
            <label>Od:</label>
            <div class="text-dark form-control bg-light"><?php echo $od; ?></div>
        </div>
        <div class="col-md-4">
            <label>Do:</label>
            <div class="text-dark form-control bg-light"><?php echo $do; ?></div>
        </div>

        <div class="col-md-4">
            <label>Vrijeme:</label>
            <div class="text-dark form-control bg-light"><?php echo $vrijeme; ?></div>
        </div>
        <div class="col-md-8">
            <label>Opis aktivnosti:</label>
            <div class="text-dark form-control bg-light"><?php echo $napomena; ?></div>
        </div>
        <div class='col-md-4'>
            <label class='m-1 d-block'>Prikaži na kalendaru:</label>
            <input type="checkbox" name="kalendar" <?php echo $kalendar; ?> class='form-check-input my-1' onclick='return false;' />
        </div>



        <div class="col-md-12">
            <label>Sadržaj:</label>
            <div class="text-dark form-control bg-light p-3"><?php echo $tekst; ?></div>
        </div>
    </div>
    <div class=''>

        <div class='d-flex flex-wrap justify-content-between align-items-center my-2'>
            <h4>Dokumenti</h4>
            <a class='btn btn-outline-secondary' href="upload_file.php?id=<?php echo $id_aktivnost ?>">+ Unesi dokument </a>
        </div>

        <div class='table-responsive'>
            <table class='table table-hover table-striped'>
                <tr>
                    <th>r.br.</th>
                    <th>Naziv dokumenta</th>
                    <th>Putanja</th>
                    <th>Datum unosa</th>
                    <th></th>
                </tr>

                <?php
                $br = 1;
                $sql = "SELECT * FROM file
			WHERE zadaci_id = $id
			ORDER BY datum_unosa_file DESC";
                $rezultat = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($rezultat)) {
                    $id_aktivnost = $red['zadaci_id'];
                    $id_file = $red["id_file"];
                    $naziv_dokumenta = $red['naziv_file'];
                    $putanja = $red['putanja'];

                    $datum_unosa = date("d.m.Y", strtotime($red['datum_unosa_file']));

                    $akt = "SELECT * FROM aktivnosti WHERE	id_aktivnost = $id_aktivnost";
                    $rez = mysqli_query($veza, $akt);
                    if ($r = mysqli_fetch_array($rez)) $aktivnost = $r['naziv_aktivnosti'];

                    echo "<tr>";
                    echo "<td>$br</td>";
                    $br++;
                    echo "<td>$naziv_dokumenta</td>";
                    echo "<td><a href='$putanja' target='_blank'> $putanja</a></td>";
                    echo "<td> $datum_unosa</td>";
                ?>
                    <td><a class='link-danger' href='brisanje_dokumenta.php?id=<?php echo $id_file; ?>' onclick="return confirm('Jeste li sigurni da želite obrisati dokument <?php echo $naziv_dokumenta; ?>?')"><i class='fas fa-trash'></i> Obriši </a></td>
                <?php
                    echo "</tr>";
                }
                ?>
            </table>
        </div>




    </div>
</div>

<script>
    $(function() {
        $(".datum_start").datepicker({
            changeMonth: true,
            changeYear: true
        });
        $.datepicker.regional['hr'] = {
            closeText: 'Zatvori',
            prevText: '&#x3c;',
            nextText: '&#x3e;',
            currentText: 'Danas',
            monthNames: ['Siječanj', 'Veljača', 'Ožujak', 'Travanj', 'Svibanj', 'Lipanj',
                'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'
            ],
            monthNamesShort: ['Sij', 'Velj', 'Ožu', 'Tra', 'Svi', 'Lip',
                'Srp', 'Kol', 'Ruj', 'Lis', 'Stu', 'Pro'
            ],
            dayNames: ['Nedjelja', 'Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota'],
            dayNamesShort: ['Ned', 'Pon', 'Uto', 'Sri', 'Čet', 'Pet', 'Sub'],
            dayNamesMin: ['Ne', 'Po', 'Ut', 'Sr', 'Če', 'Pe', 'Su'],
            weekHeader: 'Tje',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['hr']);
        jQuery.validator.addMethod("date", function(value, element) {
            return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
        }, "Please enter a correct date");
        jQuery.validator.addMethod("integer2", function(value, element) {
            return this.optional(element) || /^\d{2}$/.test(value);
        }, "A positive or negative non-decimal number please");
    });
</script>
</body>

</html>
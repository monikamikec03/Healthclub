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

function test_input($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST['posalji'])) {
    if (empty($_POST["podgrupa_id"])) {
        $podgrupa_idErr = "<p class='text-danger'>* niste odabrali podgrupu</p>";
    } else {
        echo $podgrupa_id = test_input($_POST["podgrupa_id"]);
        if (!preg_match("/^[0-9? ]*$/", $podgrupa_id)) {
            $podgrupa_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["korisnik"])) {
        $korisnikErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $korisnik = test_input($_POST["korisnik"]);
        if (!preg_match("/^[0-9? ]*$/", $korisnik)) {
            $korisnikErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["datum_od"])) {
        $datum_odErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $datum_od1 = test_input($_POST["datum_od"]);
        $datum_od = date("d.m.Y", strtotime($datum_od1));
        $datum_od2 = date("Y-m-d", strtotime($datum_od1));
        if (!preg_match("/\d{2}.\d{2}.\d{4}$/", $datum_od1)) {
            $datum_odErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }

    if (empty($_POST["datum_do"])) {
        $datum_do2 = "7777-07-07";
    } else {
        $datum_do1 = test_input($_POST["datum_do"]);
        $datum_do = date("d.m.Y", strtotime($datum_do1));
        $datum_do2 = date("Y-m-d", strtotime($datum_do1));
        if (!preg_match("/\d{2}.\d{2}.\d{4}$/", $datum_do1)) {
            $datum_doErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }


    if (empty($korisnikErr) && empty($datum_odErr) && empty($datum_doErr) && empty($podgrupa_idErr)) {
        $sql = "INSERT INTO clanstva (korisnik_id, datum_od, datum_do, podgrupa_id)
		VALUES('$korisnik', '$datum_od2', '$datum_do2', '$podgrupa_id')";
        if (mysqli_query($veza, $sql)) {
            header("location:prikaz_rubrika.php?id=$podgrupa_id");
        } else {
            $porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Dogodila se pogreška, pokušajte ponovno.</td></tr>";
        }
    } else {
        $porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Niste ispunili sva polja.</td></tr>";
    }
}


if (isset($_GET["id"])) {
    $id = ($_GET['id']);
    $sql = "SELECT * FROM podgrupe, artikli_popis, jedinica_mjere
    WHERE id_podgrupe = $id
    AND id_jed_mjere = jed_mjere
    AND podgrupe.artikl_id = artikli_popis.id_artikla";
    $rezultat = mysqli_query($veza, $sql);
    if ($red = mysqli_fetch_array($rezultat)) {

        $id_artikla = $red['artikl_id'];
        $id_podgrupe = $red['id_podgrupe'];
        $naziv = $red['naziv_podgrupe'];
        $naziv_artikla = $red['naziv_artikla'];
        $redoslijed = $red['redoslijed_podgrupe'];
        $naziv_jed_mjere = $red['naziv_jed_mjere'];
        $max_osoba = $red['max_osoba'];
        $opis_podgrupe = $red['opis_podgrupe'];
        $broj_termina = $red['broj_termina'];

        $naslovStranice = "Izmjena podgrupe: " . $naziv;
        $linkZaPovratak = "rubrike.php";
        if ($red['aktivna_podgrupa'] == 1) {
            $objavljen = "checked";
        } else {
            $objavljen = "";
        }
    }
}

?>

<div id="sadrzaj">


    <div class='d-flex flex-wrap justify-content-between align-items-center'>
        <h2>Prikaz: <?php echo $naziv; ?></h2>

        <div class='d-flex flex-wrap justify-content-end align-items-center'>
            <a class='btn btn-primary m-1' href='unos_rubrika.php?id=<?php echo $id_podgrupe; ?>'><i class='fas fa-edit'></i> Uredi </a>
            <a class='btn btn-outline-primary m-1' href='prikaz_grupe.php?id=<?php echo $id_podgrupe; ?>'>Izvještaj <i class='fa-solid fa-eye'></i></a>
            <a class='btn btn-warning m-1' href='rubrike.php'>🡸 Natrag</a>
        </div>
    </div>



    <div class='row py-3 d-flex align-items-center'>

        <div class="col-md-3">
            <label class="m-1">Kategorija:</label>
            <div class='form-control bg-light m-1'><?php echo "$naziv_artikla - $naziv_jed_mjere"; ?></div>
        </div>
        <div class="col-md-3">
            <label class="m-1">Podgrupa:</label>
            <div class="form-control bg-light m-1"><?php echo $naziv; ?></div>
        </div>


        <div class="col-md-2">
            <label class="m-1">Redoslijed:</label>
            <div class="form-control bg-light m-1"><?php echo $redoslijed; ?></div>
        </div>

        <div class="col-md-2">
            <label class="m-1">Broj osoba:</label>
            <div class="form-control bg-light m-1"><?php echo $max_osoba; ?></div>
        </div>
        <div class="col-md-2">
            <label class="m-1">Broj termina:</label>
            <div class="form-control bg-light m-1"><?php echo $broj_termina; ?></div>
        </div>
        <div class="col-md-9">
            <label class="m-1">Opis grupe:</label>
            <div class="form-control bg-light m-1"><?php echo $opis_podgrupe; ?></div>
        </div>



        <div class='col-md-3'>

            <label class="m-1">Aktivno:</label>
            <input type="checkbox" class='form-check-input mx-3' name="objavljen" <?php echo $objavljen ?> onclick='return false;' />
        </div>
    </div>

    <?php
    $sql = "SELECT COUNT(id_clanstva) AS broj_osoba FROM clanstva WHERE datum_od <= CURDATE() AND datum_do >= CURDATE() AND podgrupa_id = $id_podgrupe";
    $res = mysqli_query($veza, $sql);

    $red = mysqli_fetch_array($res);
    $broj_osoba = $red["broj_osoba"];
    ?>

    <form method="post" action="">
        <div class="table-responsive">
            <table id="table_id" class="table border-warning table-hover">
                <thead>
                    <tr>
                        <td colspan='5' class='bg-light text-dark'>Broj aktivnih članova: <b class='d-inline-block  ms-3 fs-5'><?php echo $broj_osoba . " / " . $max_osoba; ?></b></td>
                    </tr>
                    <input type="text" class="d-none" name="podgrupa_id" value="<?php echo $id_podgrupe; ?>">
                    <tr class='bg-warning'>

                        <td></td>
                        <td>
                            <select name="korisnik" class="form-control">
                                <option value="" disabled selected>Odaberite korisnika</option>
                                <?php

                                $sql = ("SELECT * FROM korisnici 
								ORDER BY naziv_korisnika ASC");
                                $rezultat = mysqli_query($veza, $sql);    ?>
                                <option value="">Svi korisnici</option>
                                <?php
                                while ($redak = mysqli_fetch_array($rezultat)) {
                                    $id_korisnik = $redak["id_korisnik"];
                                    $naziv = $redak["naziv_korisnika"];
                                    echo '<option value="' . $id_korisnik . '"';
                                    if ($id_korisnik == (int)$korisnik) {
                                        echo " selected";
                                    }
                                    echo ">$naziv</option>";
                                }
                                ?>

                            </select>
                            <?php echo $korisnikErr; ?>
                        </td>

                        <td>
                            <input type="text" class="datum_start form-control" name="datum_od" placeholder="Datum početka" value="<?php echo $datum_od; ?>" autocomplete="off">
                            <?php echo $datum_odErr; ?>
                        </td>

                        <td>
                            <input type="text" class="datum_start form-control" name="datum_do" placeholder="Datum završetka (ostaviti prazno ako je aktivni član)" value="<?php echo $datum_do; ?>" autocomplete="off">
                            <?php echo $datum_doErr; ?>
                        </td>

                        <td>
                            <input type="submit" name="posalji" value="Dodaj" class="btn btn-success">
                        </td>

                    </tr>
                    <tr>
                        <th></th>
                        <th>Korisnik</th>
                        <th>Od</th>
                        <th>Do</th>
                        <th></th>
                    </tr>

                </thead>
                <tbody>


                    <?php
                    echo $porukaErr;

                    $date_now = date("Y-m-d");
                    $br = 1;
                    $sql = "SELECT * FROM clanstva, korisnici WHERE podgrupa_id = $id_podgrupe
					AND id_korisnik = korisnik_id
					ORDER BY datum_do DESC, datum_od ASC, naziv_korisnika ASC";
                    $res = mysqli_query($veza, $sql);
                    while ($red = mysqli_fetch_array($res)) {
                        $id_clanstva = $red["id_clanstva"];
                        $naziv_korisnika = $red["naziv_korisnika"];
                        $datum_od = date("d.m.Y", strtotime($red["datum_od"]));
                        if ($red["datum_do"] == "7777-07-07") $datum_do = "";
                        else $datum_do = date("d.m.Y", strtotime($red["datum_do"]));

                        if ($date_now > $red["datum_do"]) {
                            $klasa = " opacity-50 bg-danger bg-opacity-25";
                            $tekst = "";
                        } else if ($date_now < $red["datum_od"]) {
                            $klasa = "bg-success bg-opacity-25";
                            $tekst = "<small class='ms-2'>REZERVACIJA</small>";
                        } else {
                            $klasa = '';
                            $tekst = "";
                        }
                    ?>
                        <tr class='tr <?php echo $klasa; ?>'>
                            <td><?php echo $br++; ?>.</td>
                            <td><?php echo $naziv_korisnika . $tekst; ?></td>
                            <td><?php echo $datum_od; ?></td>
                            <td><?php echo $datum_do; ?></td>
                            <td>
                                <a class='mx-2 link-dark' href="izmjena_clanstva.php?id=<?php echo $id_clanstva; ?>"><i class="fa-solid fa-user-pen"></i></a>
                                <a class='mx-2 link-dark' onclick="return confirm('Potvrdite brisanje.');" href="brisanje.php?id_clanstva=<?php echo $id_clanstva; ?>"><i class="fa-solid fa-trash"></i></a>

                            </td>
                        </tr>
                    <?php
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </form>


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
                targets: [2, 3],
            }],
        });
    });
</script>
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
<?php
include("podnozje.php");
?>
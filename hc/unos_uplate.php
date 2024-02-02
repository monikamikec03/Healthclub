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

function test_input($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

$korisnik =  $napomena = "";
$korisnikErr =  $napomenaErr = "";

if (isset($_POST['submit'])) {
    if (empty($_POST["korisnik"])) {
        $korisnikErr = "<p class='text-danger'>* morate popuniti</p>";
    } else {
        $korisnik = test_input($_POST["korisnik"]);
        if (!preg_match("/^[0-9? ]*$/", $korisnik)) {
            $korisnikErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    $napomena = htmlentities($_REQUEST["napomena"]);


    if (empty($_REQUEST["datum_uplate"])) {
        $datum_uplateErr = "<p class='text-danger'>* morate popuniti</p>";
    } else {
        $datum_uplate1 = test_input($_POST["datum_uplate"]);
        $datum_uplate2 = date("d.m.Y", strtotime($datum_uplate1));
        $datum_uplate = date("Y-m-d", strtotime($datum_uplate1));
        if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/", $datum_uplate1)) {
            $datum_uplateErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }

    if (empty($_REQUEST["od"])) {
        $odErr = "<p class='text-danger'>* morate popuniti</p>";
    } else {
        $od1 = test_input($_POST["od"]);
        $od2 = date("d.m.Y", strtotime($od1));
        $od = date("Y-m-d", strtotime($od1));
        if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/", $od1)) {
            $odErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }

    if (empty($_REQUEST["do"])) {
        $doErr = "<p class='text-danger'>* morate popuniti</p>";
    } else {
        $do1 = test_input($_POST["do"]);
        $do2 = date("d.m.Y", strtotime($do1));
        $do = date("Y-m-d", strtotime($do1));
        if (!preg_match("/\d{1,2}.\d{1,2}.\d{4}$/", $do1)) {
            $doErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }


    $napomena = htmlentities($_POST["napomena"]);

    if (empty($korisnikErr) and empty($napomenaErr) and empty($datum_uplateErr) and empty($odErr) and empty($doErr)) {
        if (isset($_POST["id_uplate"])) {
            $id = ($_POST['id_uplate']);
        }
        if (!empty($id)) {

            $sql = ("UPDATE `clanstva_uplate` SET  clan_id = '" . $korisnik . "', napomena = '" . $napomena . "', datum_uplate = '" . $datum_uplate . "', od = '" . $od . "', do = '" . $do . "', unio = {$_SESSION['idKorisnika']}	
			WHERE id_uplate = $id ");
        } else {
            $sql = ("INSERT INTO `clanstva_uplate`(`clan_id`, `napomena`, `datum_uplate`, `od`, `do`, `unio`) 
			VALUES ('" . $korisnik . "', '" . $napomena . "', '" . $datum_uplate . "', '" . $od . "', '" . $do . "', {$_SESSION['idKorisnika']});");
        }


        if (mysqli_query($veza, $sql)) {
            header("Location:prikaz_korisnik.php?id=$korisnik");
        } else {
            $porukaErr = "<p class='text-danger'>unos /izmjena uplate nije uspjela. " . mysqli_error($veza) . "</p>";
        }
    } else {
        $porukaErr = "<p class='text-danger'>Niste popunili sva polja.</p>";
    }
}
if (isset($_GET["id_korisnik"])) {
    $korisnik = test_input($_GET["id_korisnik"]);
    $sql = "SELECT * FROM korisnici WHERE id_korisnik = $korisnik";
    $res = mysqli_query($veza, $sql);
    $red = mysqli_fetch_array($res);
    $naziv_korisnika = $red["naziv_korisnika"];
    $naslovStranice = "Unos uplate članstva";
} else if (isset($_GET["id_uplate"]))         //prikaz podataka za postoječi izostanak
{
    $id = ($_GET['id_uplate']);
    $sql = "SELECT*FROM clanstva_uplate, korisnici WHERE id_korisnik = clan_id
AND id_uplate = $id";
    $rezultat = mysqli_query($veza, $sql);
    if ($red = mysqli_fetch_array($rezultat)) {
        $naslovStranice = "Izmjena uplate članstva";
        $id_uplate = $red['id_uplate'];
        $korisnik = $red['id_korisnik'];
        $naziv_korisnika = $red['naziv_korisnika'];
        $napomena = $red['napomena'];

        $datum_uplate1 = date("d.m.Y", strtotime($red['datum_uplate']));
        $od1 = date("d.m.Y", strtotime($red['od']));
        $do1 = date("d.m.Y", strtotime($red['do']));
    }
} else {
    $naslovStranice = "Unos članarine";
}


include("zaglavlje.php");
?>

<div id="sadrzaj">
    <form class="" method="post" action="">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2><?php echo $naslovStranice; ?>: <?php echo $naziv_korisnika; ?></h2>

            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <input class="btn btn-success m-1" type="submit" name="submit" class='form-control my-1' value="✔ Spremi">
                <a class='btn btn-danger m-1' href="brisanje_uplate.php?id_uplate=<?php echo $id_uplate; ?>" onclick="return confirm('Potvrdite brisanje.');"><i class='fa fa-trash'></i> Obriši</a>
                <a class='btn btn-warning m-1' href='prikaz_korisnik.php?id=<?php echo $korisnik; ?>'>🡸 Natrag</a>
            </div>
        </div>
        <?php
        if (!empty($id_uplate)) {
            echo "<input type='hidden' name='id_uplate' value='$id_uplate'>";
        }

        ?>
        <div class='row'>
            <div class='col-md-3'>
                <label class='my-1'>* Korisnik:</label>
                <select name="korisnik" class='form-control my-1'>
                    <?php
                    $sql = ("SELECT * FROM korisnici 
						ORDER BY naziv_korisnika ASC");
                    $rezultat = mysqli_query($veza, $sql);    ?>
                    <option value="">odaberite Korisnika</option>
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
            </div>




            <div class='col-md-3'>
                <label class='my-1'>* Datum uplate:</label>
                <input class="form-control datum_datum_uplate my-1" type="text" name="datum_uplate" value="<?php if (!empty($datum_uplate1)) {
                                                                                                                echo $datum_uplate1;
                                                                                                            } ?>" autocomplete='off'>
                <?php echo $datum_uplateErr; ?>
            </div>

            <div class='col-md-3'>
                <label class='my-1'>* Od:</label>
                <input class="form-control datum_start my-1" type="text" name="od" value="<?php if (!empty($od1)) {
                                                                                                echo $od1;
                                                                                            } ?>" autocomplete='off'>
                <?php echo $odErr; ?>
            </div>

            <div class='col-md-3'>
                <label class='my-1'>* Do:</label>
                <input class="form-control datum_start my-1" type="text" name="do" value="<?php if (!empty($do1)) {
                                                                                                echo $do1;
                                                                                            } ?>" autocomplete='off'>
                <?php echo $doErr; ?>
            </div>



            <div class='col-lg-12 col-md-12'>
                <label class='m-1'>Napomena:</label>
                <textarea id="editor" name="napomena" class="form-control"><?php echo $napomena; ?></textarea>
            </div>
        </div>

        <?php echo $porukaErr; ?>




    </form>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>
    $(function() {
        $(".datum_datum_uplate").datepicker({
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
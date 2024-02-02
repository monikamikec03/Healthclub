<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
    require("../include/putanja.php");
    require("navigacija.php");
} else {
    echo "<script> window.location.replace('prijava.php');</script>";
}

function test_input($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

setlocale(LC_ALL, 'hr_HR.utf-8');

$nazivErr = $kategorijaErr = $redoslijedErr = $broj_terminaErr = "";
$naziv = $kategorija = $redoslijed = $id_artikla = $objavljen = $broj_termina = "";

if (isset($_POST['submit'])) {
    if (empty($_POST["naziv"])) {
        $nazivErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $naziv = test_input($_POST["naziv"]);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/", $naziv)) {
            $nazivErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }
    if (empty($_POST["opis_grupe"])) {
        $opis_grupeErr = "";
    } else {
        $opis_grupe = test_input($_POST["opis_grupe"]);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;:\- ]*$/", $opis_grupe)) {
            $opis_grupeErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["redoslijed"])) {
        $redoslijedErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $redoslijed = test_input($_POST["redoslijed"]);
        if (!preg_match("/^[0-9 ]*$/", $redoslijed)) {
            $redoslijedErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</P>";
        }
    }

    if (empty($_POST["max_osoba"])) {
        $max_osobaErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $max_osoba = test_input($_POST["max_osoba"]);
        if (!preg_match("/^[0-9 ]*$/", $max_osoba)) {
            $max_osobaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</P>";
        }
    }
    if (empty($_POST["broj_termina"])) {
        $broj_terminaErr = "";
        $broj_termina = 12;
    } else {
        $broj_termina = test_input($_POST["broj_termina"]);
        if (!preg_match("/^[0-9 ]*$/", $broj_termina)) {
            $broj_terminaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</P>";
        }
    }

    if (empty($_POST["idKategorija"])) {
        $kategorijaErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $kategorija = test_input($_POST["idKategorija"]);
        if (!preg_match("/^[0-9 ]*$/", $kategorija)) {
            $kategorijaEr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</P>";
        }
    }


    if (isset($_POST["objavljen"])) {
        $objavljen = 1;
        if (!preg_match("/^[0-9 ]*$/", $objavljen)) {
            $objavljenErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    } else {
        $objavljen = 0;
        if (!preg_match("/^[0-9 ]*$/", $objavljen)) {
            $objavljenErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($nazivErr) and empty($opis_grupeErr) and empty($redoslijedErr) and empty($objavljenErr) and empty($max_osobaErr) and empty($broj_terminaErr)) {
        if (isset($_POST["id_podgrupe"]))   // ako ima "id" onda je izmjena 	
        {
            $id = ($_POST['id_podgrupe']);
            $sql = ("UPDATE `podgrupe` SET naziv_podgrupe = '" . $naziv . "', opis_podgrupe = '" . $opis_grupe . "', redoslijed_podgrupe = '" . $redoslijed . "', max_osoba = '" . $max_osoba . "', broj_termina = '" . $broj_termina . "', artikl_id = '" . $kategorija . "', aktivna_podgrupa = '" . $objavljen . "' WHERE id_podgrupe = $id");
        } else                         // unos novog 
        {
            $sql = "INSERT INTO podgrupe (naziv_podgrupe, opis_podgrupe, redoslijed_podgrupe, artikl_id, aktivna_podgrupa, max_osoba, broj_termina) 
			VALUES ('$naziv', '$opis_grupe', '$redoslijed', '$kategorija', '$objavljen', '$max_osoba', '$broj_termina')";
        }
        if (mysqli_query($veza, $sql)) {
            header("Location:rubrike.php");
        } else {
            $poruka = "<p class='text-danger'>unos /izmjena podgrupa nije uspjela.</p>";
        }
    }
}
if (isset($_GET["id"]))         //prikaz podataka za postoječi izostanak
{
    $id = ($_GET['id']);
    $sql = "SELECT * FROM podgrupe, artikli_popis 
	WHERE id_podgrupe = $id";
    $rezultat = mysqli_query($veza, $sql);
    if ($red = mysqli_fetch_array($rezultat)) {

        $id_artikla = $red['artikl_id'];
        $id_podgrupe = $red['id_podgrupe'];
        $naziv = $red['naziv_podgrupe'];
        $opis_grupe = $red['opis_podgrupe'];
        $redoslijed = $red['redoslijed_podgrupe'];
        $max_osoba = $red['max_osoba'];
        $broj_termina = $red['broj_termina'];

        $naslovStranice = "Izmjena podgrupe: " . $naziv;
        $linkZaPovratak = "rubrike.php";
        if ($red['aktivna_podgrupa'] == 1) {
            $objavljen = "checked";
        } else {
            $objavljen = "";
        }
    }
} else if (!isset($_POST["id_podgrupe"]))   //novi izostanak   
{
    $naslovStranice = "Unos podgrupe";
    $linkZaPovratak = "rubrike.php";
}
?>

<div id="sadrzaj">
    <form action="" method="POST">
        <?php                            // sakriva polje id ako je izmjena clanka
        if (isset($id)) {
            echo "<input type='hidden' name='id_podgrupe' value='$id_podgrupe'>";
        }
        ?>
        <div class="d-flex justify-content-between flex-wrap align-items-center">
            <h2><?php echo $naslovStranice; ?></h2>

            <div class='d-flex flex-wrap'>
                <input class="btn btn-success m-1" type="submit" name="submit" value="✔ Spremi">

                <a class='btn btn-danger m-1' href="<?php echo $linkZaPovratak; ?>">✖ Odustani</a>
            </div>
        </div>



        <div class='row py-3 d-flex align-items-center'>
            <div class="col-md-3">
                <label class="m-1">* Kategorija:</label>
                <select name="idKategorija" class='form-control m-1'>
                    <?php
                    $sql = ("SELECT * FROM artikli_popis, jedinica_mjere
				WHERE jed_mjere = id_jed_mjere ORDER BY redoslijed");
                    $rezultat = mysqli_query($veza, $sql); ?>
                    <option value="">--- odaberite kategoriju ---</option>
                    <?php
                    while ($redak = mysqli_fetch_array($rezultat)) {
                        $id = $redak["id_artikla"];
                        $naziv_artikla = $redak["naziv_artikla"];
                        $naziv_jed_mjere = $redak["naziv_jed_mjere"];
                        $redoslijed = $red['redoslijed'];
                        echo '<option value="' . $id . '"';
                        if ($id == $id_artikla) {
                            echo " selected";
                        }
                        echo ">$naziv_artikla - $naziv_jed_mjere</option>";
                    }
                    ?>
                </select>
                <?php echo $kategorijaErr; ?>
            </div>

            <div class="col-md-3">
                <label class="m-1">* Podgrupa:</label>
                <INPUT class="form-control m-1" type="text" name="naziv" value="<?php echo $naziv; ?>">
                <?php echo $nazivErr; ?>
            </div>


            <div class="col-md-2">
                <label class="m-1">* Redoslijed:</label>
                <INPUT class="form-control m-1" type="text" name="redoslijed" value="<?php echo $redoslijed; ?>">
                <?php echo $redoslijedErr; ?>
            </div>

            <div class="col-md-2">
                <label class="m-1">* Maksimum osoba u grupi:</label>
                <INPUT class="form-control m-1" type="text" name="max_osoba" value="<?php echo $max_osoba; ?>">
                <?php echo $max_osobaErr; ?>
            </div>
            <div class="col-md-2">
                <label class="m-1">Broj termina:</label>
                <INPUT class="form-control m-1" type="text" name="broj_termina" value="<?php echo $broj_termina; ?>">
                <?php echo $broj_terminaErr; ?>
            </div>


            <div class='col-md-9'>

                <label class="m-1">Opis grupe:</label>
                <input class="form-control m-1" type="text" name="opis_grupe" value="<?php echo $opis_grupe; ?>">
                <?php echo $opis_grupeErr; ?>
            </div>

            <div class='col-md-3'>

                <label class="m-1">Aktivno:</label>
                <input type="checkbox" class='form-check-input mx-3' name="objavljen" <?php echo $objavljen ?> />
                <?php echo $objavljenErr; ?>
            </div>
        </div>
        <?php echo $poruka; ?>

    </form>
</div>
</body>

</html>
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

$oib = $naziv_korisnika = $adresa_korisnika = $mail = $telefon = $kontakt_osoba = $napomena = "";
$oibErr = $naziv_korisnikaErr = $adresa_korisnikaErr = $mailErr = $telefonErr = $kontakt_ososbaErr = $napomenaErr = "";

$potencijalni_kupac = 0;
$kupac = 0;
$dobavljac = 0;
$vlasnik = 0;
$partner = 0;
$zakupodavac = 0;
$zakupoprimac = 0;
$aktivan = 1;
if (isset($_POST['submit'])) {

    if (empty($_POST["oib"])) {
        $oibErr = "";
    } else {
        $oib = test_input($_POST["oib"]);
        if (!preg_match("/^[0-9?! ]*$/", $oib)) {
            $oibErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["naziv_korisnika"])) {
        $naziv_korisnikaErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $naziv_korisnika = test_input($_POST["naziv_korisnika"]);
        if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.\"\-\();:\/\(\)\ ]*$/", $naziv_korisnika)) {
            $naziv_korisnikaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["adresa_korisnika"])) {
        $adresa_korisnikaErr = "";
    } else {
        $adresa_korisnika = test_input($_POST["adresa_korisnika"]);
        if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.\"\-\();:\/\(\)\ ]*$/", $adresa_korisnika)) {
            $adresa_korisnikaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }
    if (empty($_POST["napomena"])) {
        $napomenaErr = "";
    } else {
        $napomena = test_input($_POST["napomena"]);
        if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.\"\-\();:\/\(\)\ ]*$/", $napomena)) {
            $napomenaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }


    if (empty($_POST["mail"])) {
        $mailErr = "";
    } else {
        $mail = test_input($_POST["mail"]);
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $mailErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["telefon"])) {
        $telefon = "";
    } else {
        $telefon = test_input($_POST["telefon"]);
        if (!preg_match("/^[0-9?!\-\/\ ]*$/", $telefon)) {
            $telefonErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["kontakt_osoba"])) {
        $kontakt_osoba = "";
    } else {
        $kontakt_osoba = test_input($_POST["kontakt_osoba"]);
        if (!preg_match("/^[-a-zA-ZćĆčČžŽšŠđĐ0-9?!,.:; ]*$/", $kontakt_osoba)) {
            $kontakt_osobaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    $aktivan = test_input($_POST["aktivan"]);
    if (!preg_match("/^[0-9?!\-\/\ ]*$/", $aktivan)) {
        $aktivanErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
    }


    if (isset($_POST["osloboden_placanja"])) $osloboden_placanja = 1;
    else $osloboden_placanja = 0;

    if (!preg_match("/^[0-9]*$/", $osloboden_placanja)) {
        $osloboden_placanjaErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
    }

    if (isset($_POST["kupac"])) $kupac = 1;
    if (isset($_POST["potencijalni_kupac"])) $potencijalni_kupac = 1;
    if (isset($_POST["dobavljac"])) $dobavljac = 1;
    if (isset($_POST["vlasnik"])) $vlasnik = 1;
    if (isset($_POST["partner"])) $partner = 1;
    if (isset($_POST["zakupodavac"])) $zakupodavac = 1;
    if (isset($_POST["zakupoprimac"])) $zakupoprimac = 1;






    if (empty($naziv_korisnikaErr) and empty($adresa_korisnikaErr) and empty($oibErr) and empty($mailErr) and empty($telefonErr) and empty($kontakt_osobaErr) and empty($aktivanErr) and empty($napomenaErr)) {



        if (isset($_POST["id"]))   // ako ima "id" onda je izmjena članka	
        {
            $id = ($_POST['id']);
            $sql = "UPDATE korisnici SET oib = '" . $oib . "' , naziv_korisnika = '" . $naziv_korisnika . "' , adresa_korisnika = '" . $adresa_korisnika . "',  mail = '" . $mail . "', telefon = '" . $telefon . "', kontakt_osoba = '" . $kontakt_osoba . "', aktivan = '" . $aktivan . "', osloboden_placanja = '" . $osloboden_placanja . "', kupac = '" . $kupac . "', potencijalni_kupac = '" . $potencijalni_kupac . "', vlasnik = '" . $vlasnik . "', partner = '" . $partner . "', dobavljac = '" . $dobavljac . "', zakupodavac = '" . $zakupodavac . "', zakupoprimac = '" . $zakupoprimac . "', napomena_korisnika = '" . $napomena . "'
			WHERE id_korisnik = $id ";
        } else                         // unos novog članka
        {
            $sql = ("INSERT INTO `korisnici` (`oib`, `naziv_korisnika`, `adresa_korisnika`, `mail`, `telefon`, `kontakt_osoba`, `potencijalni_kupac`,  `kupac`, `dobavljac`, `vlasnik`, `zakupodavac`, `zakupoprimac`, `aktivan`, `partner`, `napomena_korisnika`, `osloboden_placanja`) 
			VALUES ('" . $oib . "','" . $naziv_korisnika . "', '" . $adresa_korisnika . "', '" . $mail . "','" . $telefon . "', '" . $kontakt_osoba . "', '" . $potencijalni_kupac . "', '" . $kupac . "', '" . $dobavljac . "', '" . $vlasnik . "', '" . $zakupodavac . "', '" . $zakupoprimac . "', '" . $aktivan . "', '" . $partner . "', '" . $napomena . "', '" . $osloboden_placanja . "');");
        }


        if (mysqli_query($veza, $sql)) {

            header("Location:partneri.php");
        } else {
            $porukaErr = "<p class='text-danger'>unos /izmjena korisnika nije uspjela.</p>";
        }
    } else {
        echo mysqli_error($veza);
        $porukaErr = "<p class='text-danger'>* unos u bazu nije uspio, morate popuniti sva polja</p>";
    }
}

if (!empty($_GET["id"])) {
    $id = ($_GET['id']);
    $sql = ("SELECT * FROM korisnici
	WHERE id_korisnik = $id");
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $naslovStranice = "Izmjena korisnika";
        $linkZaPovratak = "prikaz_korisnik.php?id=$id";
        $id = $redak['id_korisnik'];
        $oib = $redak['oib'];
        $naziv_korisnika = $redak['naziv_korisnika'];
        $adresa_korisnika = $redak['adresa_korisnika'];
        $mail = $redak['mail'];
        $telefon = $redak['telefon'];
        $napomena = $redak['napomena_korisnika'];
        $kontakt_osoba = $redak['kontakt_osoba'];

        if (($redak["osloboden_placanja"]) == 1) $osloboden_placanja = 'checked';

        if (($redak["kupac"]) == 1) $kupac = 'checked';
        if (($redak["potencijalni_kupac"]) == 1) $potencijalni_kupac = 'checked';
        if (($redak["dobavljac"]) == 1) $dobavljac = 'checked';
        if (($redak["vlasnik"]) == 1) $vlasnik = 'checked';
        if (($redak["partner"]) == 1) $partner = ' Partner ';
        if (($redak["zakupodavac"]) == 1) $zakupodavac = 'checked';
        if (($redak["zakupoprimac"]) == 1) $zakupoprimac = 'checked';
    }
} else if (!empty($_GET["id_prijave_t"])) {
    $id_prijave = ($_GET['id_prijave_t']);
    $sql = ("SELECT * FROM prijava_probni_trening WHERE id_prijave = $id_prijave");
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $naslovStranice = "Unos korisnika";
        $linkZaPovratak = "prikaz_prijave_t.php?id=$id_prijave";

        $naziv_korisnika = $redak['ime_prezime'];
        $mail = $redak['email'];
        $telefon = $redak['broj_mobitela'];
    }
} else if (!empty($_GET["id_prijave_p"])) {
    $id_prijave = ($_GET['id_prijave_p']);
    $sql = ("SELECT * FROM prijava_prvi_pregled WHERE id_prijave = $id_prijave");
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $naslovStranice = "Unos korisnika";
        $linkZaPovratak = "prikaz_prijave_p.php?id=$id_prijave";
        $naziv_korisnika = $redak['ime_prezime'];
        $mail = $redak['email'];
        $telefon = $redak['broj_mobitela'];
    }
} else {
    $naslovStranice = "Unos korisnika";
    $linkZaPovratak = "partneri.php";
}



?>

<div id="sadrzaj">
    <form method="post" action="">
        <div class='d-flex flex-wrap justify-content-between align-items-center'>
            <h2><?php echo $naslovStranice; ?></h2>

            <div class='d-flex flex-wrap justify-content-end align-items-center'>
                <input class="btn btn-success m-1" type="submit" name="submit" class='form-control my-1' value="✔ Spremi">
                <a class='btn btn-warning m-1' href='<?php echo $linkZaPovratak; ?>'>🡸 Natrag</a>
            </div>
        </div>

        <?php
        if (!empty($id)) {
            echo "<input type='hidden' name='id' value='$id'>";
        }
        ?>
        <div class='row'>

            <div class='col-md-3'>
                <label class='my-1'>* Naziv korisnika:</label>
                <input type="text" name="naziv_korisnika" class='form-control my-1' value="<?php echo $naziv_korisnika; ?>" autocomplete="off">
                <?php echo $naziv_korisnikaErr; ?>
            </div>

            <div class='col-md-3'>
                <label class='my-1'>OIB:</label>
                <input type="text" name="oib" class='form-control my-1' value="<?php echo $oib; ?>" maxlength="11">
                <?php echo $oibErr; ?>
            </div>

            <div class='col-md-3'>
                <label class='my-1'>Adresa kupca:</label>
                <input type="text" name="adresa_korisnika" class='form-control my-1' value="<?php echo $adresa_korisnika; ?>">
                <?php echo $adresa_korisnikaErr; ?>
            </div>



            <div class='col-md-3'>
                <label class='my-1'>Email:</label>
                <input type="text" name="mail" class='form-control my-1' value="<?php echo $mail; ?>">
                <?php echo $mailErr; ?>
            </div>

            <div class='col-md-3'>
                <label class='my-1'>Telefon :</label>
                <input type="text" name="telefon" class='form-control my-1' value="<?php echo $telefon; ?>">
                <?php echo $telefonErr; ?>
            </div>


            <div class='col-md-3'>
                <label class='my-1'>Kontakt osoba:</label>
                <input type="text" name="kontakt_osoba" class='form-control my-1' value="<?php echo $kontakt_osoba; ?>">
                <?php echo $kontakt_osobaErr; ?>
            </div>

            <div class="col-md-3">
                <label class="my-1">Aktivnost:</label>
                <select name="aktivan" class="form-control my-1">
                    <option value="1" <?php if ($aktivan == 1) echo 'selected'; ?>>Aktivan</option>
                    <option value="0" <?php if ($aktivan == 0) echo 'selected'; ?>>Neaktivan</option>
                </select>
            </div>

            <div class='col-md-3'>

                <label class="m-1">Oslobođen/a plaćanja:</label><br>
                <input type="checkbox" class='form-check-input mx-3' name="osloboden_placanja" <?php echo $osloboden_placanja ?> />
                <?php echo $osloboden_placanjaErr; ?>
            </div>

            <div class='col-md-9'>
                <label class='my-1'>Napomena:</label>
                <textarea name="napomena" class='form-control my-1'><?php echo $napomena; ?></textarea>
                <?php echo $napomenaErr; ?>
            </div>


            <p class="mt-4">Funkcija:</p>
            <div class="col-md-12 d-flex justify-content-start flex-grow-1">

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label for="partner">Partner:</label>
                    <input type="checkbox" name="partner" id="partner" class="ms-2 form-check-input" <?php echo $partner; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label for="kupac">Kupac:</label>
                    <input type="checkbox" name="kupac" id="kupac" class="ms-2 form-check-input" <?php echo $kupac; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label>Potencijalni kupac:</label>
                    <input type="checkbox" name="potencijalni_kupac" class="ms-2 form-check-input" <?php echo $potencijalni_kupac; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label>Dobavljač:</label>
                    <input type="checkbox" name="dobavljac" class="ms-2 form-check-input" <?php echo $dobavljac; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label>Vlasnik:</label>
                    <input type="checkbox" name="vlasnik" class="ms-2 form-check-input" <?php echo $vlasnik; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label>Zakupoprimac:</label>
                    <input type="checkbox" name="zakupoprimac" class="ms-2 form-check-input" <?php echo $zakupoprimac; ?>>
                </div>

                <div class="px-4 py-2 d-flex align-items-center bg-light me-2">
                    <label>Zakupodavac:</label>
                    <input type="checkbox" name="zakupodavac" class="ms-2 form-check-input" <?php echo $zakupodavac; ?>>
                </div>
            </div>



            <?php echo $porukaErr; ?>

    </form>
</div>
</div>
<?php
include("podnozje.php");
?>
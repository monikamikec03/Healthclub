<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
    require("../include/var.php");
    require("../include/putanja.php");
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

if (isset($_GET["id_clanstva"])) {
    $id_clanstva = addSlashes($_GET["id_clanstva"]);
    $sql = "SELECT * FROM clanstva WHERE id_clanstva = $id_clanstva";
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $id_clanstva = $redak["id_clanstva"];
        $podgrupa_id = $redak["podgrupa_id"];
        $sql = "DELETE FROM clanstva WHERE id_clanstva = $id_clanstva";
        if (!mysqli_query($veza, $sql)) {
            exit("Brisanje članstva nije uspjelo");
        } else {
            header("Location: prikaz_rubrika.php?id=$podgrupa_id");
        }
    } else {
        exit("Nije pronađen traženi zapis u bazi.");
    }
} else if (isset($_GET["id_korisnik"])) {
    $id_korisnik = addSlashes($_GET["id_korisnik"]);
    $sql = "SELECT * FROM korisnici WHERE id_korisnik = $id_korisnik";
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $id_korisnik = $redak["id_korisnik"];
        $sql = "DELETE FROM korisnici WHERE id_korisnik = $id_korisnik";
        if (!mysqli_query($veza, $sql)) {
            exit("Brisanje korisnika nije uspjelo");
        } else {
            header("Location: partneri.php");
        }
    } else {
        exit("Nije pronađen traženi zapis u bazi.");
    }
} else if (isset($_GET["id_aktivnost"])) {
    $id_aktivnost = addSlashes($_GET["id_aktivnost"]);
    $sql = "SELECT * FROM aktivnosti WHERE id_aktivnost = $id_aktivnost";
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $id_aktivnost = $redak["id_aktivnost"];
        $sql = "DELETE FROM aktivnosti WHERE id_aktivnost = $id_aktivnost";
        if (!mysqli_query($veza, $sql)) {
            exit("Brisanje aktivnosti nije uspjelo");
        } else {
            header("Location: aktivnosti.php");
        }
    } else {
        exit("Nije pronađen traženi zapis u bazi.");
    }
} else if (isset($_POST["id_dolaska"])) {
    $id_dolaska = addSlashes($_POST["id_dolaska"]);
    $sql = "SELECT * FROM evidencije_dolazaka WHERE id_dolaska = $id_dolaska";
    $rezultat = mysqli_query($veza, $sql);
    if ($redak = mysqli_fetch_array($rezultat)) {
        $id_dolaska = $redak["id_dolaska"];
        $sql = "DELETE FROM evidencije_dolazaka WHERE id_dolaska = $id_dolaska";
        if (!mysqli_query($veza, $sql)) {
            echo ("Brisanje evidencije_dolazaka nije uspjelo");
        }
    } else {
        echo ("Nije pronađen traženi zapis u bazi.");
    }
} else {
    echo ("Nije pronađen traženi zapis u bazi.");
}
 ob_end_flush();

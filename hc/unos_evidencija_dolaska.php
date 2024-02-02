<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
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



if (isset($_POST['id_korisnik']) and isset($_POST["mjesec"]) and isset($_POST["godina"]) and isset($_POST["redni_broj_termina"])) {
    $id_korisnik = test_input($_POST["id_korisnik"]);
    $mjesec = test_input($_POST["mjesec"]);
    $godina = test_input($_POST["godina"]);
    $redni_broj_termina = test_input($_POST["redni_broj_termina"]);
    $dan = date("d");
    $datum = date("Y-m-d", strtotime("$dan.$mjesec.$godina"));

    $sql = "INSERT INTO evidencije_dolazaka (korisnik_id, datum, redni_broj_termina)
    VALUES('$id_korisnik', '$datum', '$redni_broj_termina')";
    if (mysqli_query($veza, $sql)) {
        echo date("d.m.Y.", strtotime("$dan.$mjesec.$godina"));
    } else echo 0;
}

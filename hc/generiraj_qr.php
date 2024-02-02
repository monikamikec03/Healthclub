<?php
ob_start();
session_start();
if(in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3]))
{	
	require("../include/putanja.php");
	require("navigacija.php");
}
else{
	 echo "<script> window.location.replace('prijava.php');</script>";
}

function test_input($data) {
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}	

if(isset($_GET["id"])){
    $id = (int)$_GET["id"];
    $id = test_input($id);
    $sql = "SELECT * FROM korisnici WHERE id_korisnik = $id";
    $res = mysqli_query($veza, $sql);
    if(mysqli_num_rows($res) == 1){
        $red = mysqli_fetch_array($res);
        $id_korisnik = $red["id_korisnik"];
        $naziv_korisnika = $red["naziv_korisnika"];

        $string = $id_korisnik . $naziv_korisnika;
        
        $kod = substr(base_convert(md5($string), 16,32), 0, 12);


        $code = "https://healthclub.hr/qr.php?kod=$kod";
        $imageUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$code&choe=UTF-8";

        // Dohvaćanje sadržaja slike
        $imageContent = file_get_contents($imageUrl);

        // Putanja i naziv datoteke za spremanje slike
        $filename = "../images_qr/$kod.png";

        // Spremanje slike u mapu
        if(file_put_contents($filename, $imageContent)){
            $sql = "UPDATE korisnici SET qr_kod = '$kod', qr_slika = '$filename' WHERE id_korisnik = $id_korisnik";
            if(mysqli_query($veza, $sql)){
                header("location:prikaz_korisnik.php?id=$id_korisnik");
            }
            else{
                die("<p class='text-danger p-3'>Greška prilikom spremanja koda u bazu. ".mysqli_error($veza)."</p>");
            }
        }
        else{
            die("<p class='text-danger p-3'>Greška prilikom generiranja slike koda u mapu.</p>");
        }


        
    }
    else{
        header("location:partneri.php");
    }
}
else{
    header("location:partneri.php");
}




?>
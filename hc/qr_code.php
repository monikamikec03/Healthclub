<?php
$code = "https://healthclub.hr/index.php";
$imageUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$code&choe=UTF-8";

// Dohvaćanje sadržaja slike
$imageContent = file_get_contents($imageUrl);

// Putanja i naziv datoteke za spremanje slike
$filename = 'slika_qr_koda.png';

// Spremanje slike u mapu
if(file_put_contents($filename, $imageContent)){
    echo 'Slika QR koda je spremljena kao ' . $filename;
}



?>
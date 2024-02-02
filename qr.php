<?php
ob_start();

if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}

require("../moj_spoj/otvori_vezu_cmp.php");



?>
<!DOCTYPE html>
<html lang='hr'>
<head>
	<?php 
	if(empty($id_clanak)){ 
	?>
		<link rel="icon" type="image/x-icon" href="/slike/favicon.ico">
		<title>HEALTHCLUB</title>
		<meta name="description" content="HealthClub Vrbovec centar za trening i rehabilitaciju"> 
		
	<?php 
	}
	else{
	?>
		<link rel="icon" type="image/x-icon" href="<?php echo $putanja[0]; ?>">
		<title><?php echo $naslov_clanka; ?></title>
		<meta name="description" content="<?php echo $uvod; ?>">
	<?php
	}
	?>
	
    <meta name="keywords" content="HealthClub, Vrbovec, trening, kondicija, rehabilitacija, pregled, grupni treninzi, individualni treninzi, poluindividualni treninzi, dijagnostika sposobnosti, seminari, specijalni programi, mršavljenje, gubit kilograma, mišići, dobivsnje mišićne mase, bodybuilding, strenghtlifting, changeforlife, promjena">
    <meta name="author" content="HealthClub">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  
	<script src="../include/bootstrap.js"></script>
	<script src="../include/jquery.min.js"></script>
	
    <link href="../css/animate.css" rel="stylesheet" type="text/css"/>
    <link href="../css/style_1.css" rel="stylesheet" type="text/css"/>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
    <link rel="stylesheet" href="../include/jquery-ui.css">
	<link rel="stylesheet" href="include/vegas.min.css">
	
	<link rel="stylesheet" href="../include/bootstrap.css">
	<link rel="stylesheet" href="../include/css.css">
	
	<link rel="stylesheet" type="text/css" href="../include/jquery.datetimepicker.css" >
	<script src="../include/jquery.datetimepicker.full.min.js"></script>

	<script src="../ckeditor4/ckeditor.js"></script>
	<script src="../js/owl.carousel.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
	
	<!-- Waypoints -->
	<script src="../js/jquery.waypoints.min.js"></script>
	<!-- Main -->
	<script src="../js/main.js"></script>
	<script src="../include/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script src="include/vegas.min.js"></script>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/assisfery/SocialShareJS@1.4/social-share.min.css">
	<script src="https://cdn.jsdelivr.net/gh/assisfery/SocialShareJS@1.4/social-share.min.js"></script>
	
</head>
    <body class="h-100 bg-warning d-flex flex-column justify-content-center align-items-center">
        <div class='flex-grow-1 d-flex justify-content-center align-items-center'>
            <div class='shadow p-4 bg-white'>
            <?php
            function test_input($data) {
                $data = trim($data);
                $data = strip_tags($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            if(isset($_GET["kod"])){
                $kod = test_input($_GET["kod"]);
                if (!preg_match("/^[a-zA-Z0-9 ]*$/",$kod)){
                    die("<p class='p-3'>Skenirani QR kod ne postoji u našoj bazi. <br>Za više informacija pošaljite upit na broj <a class='text-decoration-underline' href='tel:+385989520746'>+385 98 952 0746</a></p>");
                }
                else{
                    $sql = "SELECT * FROM korisnici WHERE qr_kod = '$kod' AND aktivan = 1";
                    $res = mysqli_query($veza, $sql);
                    if(mysqli_num_rows($res) <= 0){
                        die("<p class='p-3'>Ne postoji aktivan korisnik s navedenim qr kodom u bazi. <br>Za više informacija pošaljite upit na broj <a class='text-decoration-underline' href='tel:+385989520746'>+385 98 952 0746</a></p>");
                    }
                    else{
                        $red = mysqli_fetch_array($res);
                        $id_korisnik = $red["id_korisnik"];
                        $naziv_korisnika = $red["naziv_korisnika"];
                        $qr_slika = $red["qr_slika"];
                        $sql2 = "SELECT * FROM aktivnosti WHERE korisnik_id = $id_korisnik
                        AND naziv_aktivnosti = 'Članarina'
                        AND CURDATE() BETWEEN od AND DATE_ADD(do, INTERVAL 30 DAY)";
                        $res2 = mysqli_query($veza, $sql2);
                        if(mysqli_num_rows($res2) > 0){
                            $status = "<h1 class='text-success fw-bold p-3'>AKTIVAN</h1>";
                        }
                        else{
                            $status = "<h1 class='text-danger fw-bold p-3'>NEAKTIVAN</h1>";
                        }
            
                        ?>
                            <div class='text-center'>
                                <p>Prikaz stanja članstva korisnika</p>
                                <h2 class='mb-3'><?php echo $naziv_korisnika; ?></h2>
                                <img class="w-100" src='<?php echo $qr_slika; ?>'>
                                <?php echo $status; ?>
                            </div>
                        <?php
                    }
                }
            }
            else{
                die("<p class='p-3'>Skenirani QR kod ne postoji u našoj bazi. <br>Za više informacija pošaljite upit na broj <a class='text-decoration-underline' href='tel:+385989520746'>+385 98 952 0746</a></p>");
            }
            ?>
            </div>
        </div>
        <div class="p-3 d-flex justify-content-end flex-md-row flex-column align-items-center w-100">

                <a href="https://healthclub.hr/index.php" class="link-dark m-2">healthclub.hr</a>
                <a href="mailto:info@healthclub.hr" class="link-dark m-2">info@healthclub.hr</a>
                <a class="link-dark m-2" href='tel:+385989520746'>+385 98 952 0746</a>
          
        </div>

    </body>
</html>
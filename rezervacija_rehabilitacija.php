<?php
ob_start();
require("../moj_spoj/otvori_vezu_cmp.php");
setlocale(LC_ALL, 'hr_HR.utf-8');


function test_input($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

$pattern = '/[A-Za-zćĆčČžŽšŠđĐ0-9!@#$%^&*()_+{}\[\]:;"\'<>,.?\/\\|~-]+/';

//slanje maila
$zeljeni_dani = array();
$ime_prezime  = $godine = $email = $broj_mobitela = $frakture = $razlog_dolaska = $operativni_zahvati = $porezotine = $traume = $lijekovi = $pravila_privatnosti = $mail_zeljeni_dani = $datum = $datum2 = '';
$ime_prezimeErr = $godineErr = $emailErr = $broj_mobitelaErr = $fraktureErr = $razlog_dolaskaErr = $operativni_zahvatiErr = $porezotineErr = $traumeErr = $lijekoviErr = $pravila_privatnostiErr = '';

if (($_SERVER["REQUEST_METHOD"] == "POST") and (isset($_POST['g-recaptcha-response']))) {

    if (empty($_POST['ime_prezime'])) {
        $ime_prezimeErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
    } else {
        $ime_prezime = test_input($_POST['ime_prezime']);
        if (!preg_match($pattern, $ime_prezime)) {
            $ime_prezimeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }


    if (empty($_POST['email'])) {
        $emailErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "<p class='text-danger text-end mt-2'>Morate unijeti ispravan format.</p>";
        }
    }

    if (empty($_POST['broj_mobitela'])) {
        $broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
    } else {
        $broj_mobitela = test_input($_POST['broj_mobitela']);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!,.;:\-\(\)\_\n\r\"\' ]*$/", $broj_mobitela)) {
            $broj_mobitelaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['godine'])) {
        $godineErr = "<p class='text-danger text-end mt-2'>Morate popuniti polje.</p>";
    } else {
        $godine = test_input($_POST['godine']);
        if (!preg_match("/^[0-9+ ]*$/", $godine)) {
            $godineErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['razlog_dolaska'])) {
        $razlog_dolaskaErr = "";
    } else {
        $razlog_dolaska = test_input($_POST['razlog_dolaska']);
        if (!preg_match($pattern, $razlog_dolaska)) {
            $razlog_dolaskaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['manifestacija_problema_na_tijelu'])) {
        $manifestacija_problema_na_tijeluErr = "";
    } else {
        $manifestacija_problema_na_tijelu = test_input($_POST['manifestacija_problema_na_tijelu']);
        if (!preg_match($pattern, $manifestacija_problema_na_tijelu)) {
            $manifestacija_problema_na_tijeluErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['pocetak_simptoma'])) {
        $pocetak_simptomaErr = "";
    } else {
        $pocetak_simptoma = test_input($_POST['pocetak_simptoma']);
        if (!preg_match($pattern, $pocetak_simptoma)) {
            $pocetak_simptomaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['trajanje_simptoma'])) {
        $trajanje_simptomaErr = "";
    } else {
        $trajanje_simptoma = test_input($_POST['trajanje_simptoma']);
        if (!preg_match($pattern, $trajanje_simptoma)) {
            $trajanje_simptomaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['pogorsavanje_simptoma'])) {
        $pogorsavanje_simptomaErr = "";
    } else {
        $pogorsavanje_simptoma = test_input($_POST['pogorsavanje_simptoma']);
        if (!preg_match($pattern, $pogorsavanje_simptoma)) {
            $pogorsavanje_simptomaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['poboljsavanje_simptoma'])) {
        $poboljsavanje_simptomaErr = "";
    } else {
        $poboljsavanje_simptoma = test_input($_POST['poboljsavanje_simptoma']);
        if (!preg_match($pattern, $poboljsavanje_simptoma)) {
            $poboljsavanje_simptomaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['sprjecavanje_u_svakodnevnim_radnjama'])) {
        $sprjecavanje_u_svakodnevnim_radnjamaErr = "";
    } else {
        $sprjecavanje_u_svakodnevnim_radnjama = test_input($_POST['sprjecavanje_u_svakodnevnim_radnjama']);
        if (!preg_match($pattern, $sprjecavanje_u_svakodnevnim_radnjama)) {
            $sprjecavanje_u_svakodnevnim_radnjamaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['ozbiljnost_problema'])) {
        $ozbiljnost_problemaErr = "";
    } else {
        $ozbiljnost_problema = test_input($_POST['ozbiljnost_problema']);
        if (!preg_match($pattern, $ozbiljnost_problema)) {
            $ozbiljnost_problemaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['nabrojeni_simptomi'])) {
        $nabrojeni_simptomiErr = "";
    } else {
        $nabrojeni_simptomi = test_input($_POST['nabrojeni_simptomi']);
        if (!preg_match($pattern, $nabrojeni_simptomi)) {
            $nabrojeni_simptomiErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['uzrok_problema'])) {
        $uzrok_problemaErr = "";
    } else {
        $uzrok_problema = test_input($_POST['uzrok_problema']);
        if (!preg_match($pattern, $uzrok_problema)) {
            $uzrok_problemaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['frakture'])) {
        $fraktureErr = "";
    } else {
        $frakture = test_input($_POST['frakture']);
        if (!preg_match($pattern, $frakture)) {
            $fraktureErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['operativni_zahvati'])) {
        $operativni_zahvatiErr = "";
    } else {
        $operativni_zahvati = test_input($_POST['operativni_zahvati']);
        if (!preg_match($pattern, $operativni_zahvati)) {
            $operativni_zahvatiErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['porezotine'])) {
        $porezotineErr = "";
    } else {
        $porezotine = test_input($_POST['porezotine']);
        if (!preg_match($pattern, $porezotine)) {
            $porezotineErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['unutarnji_organi'])) {
        $unutarnji_organiErr = "";
    } else {
        $unutarnji_organi = test_input($_POST['unutarnji_organi']);
        if (!preg_match($pattern, $unutarnji_organi)) {
            $unutarnji_organiErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['lijekovi'])) {
        $lijekoviErr = "";
    } else {
        $lijekovi = test_input($_POST['lijekovi']);
        if (!preg_match($pattern, $lijekovi)) {
            $lijekoviErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['druge_terapije'])) {
        $druge_terapijeErr = "";
    } else {
        $druge_terapije = test_input($_POST['druge_terapije']);
        if (!preg_match($pattern, $druge_terapije)) {
            $druge_terapijeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['procjena_stresa'])) {
        $procjena_stresaErr = "";
    } else {
        $procjena_stresa = test_input($_POST['procjena_stresa']);
        if (!preg_match($pattern, $procjena_stresa)) {
            $procjena_stresaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['procjena_razine_energije'])) {
        $procjena_razine_energijeErr = "";
    } else {
        $procjena_razine_energije = test_input($_POST['procjena_razine_energije']);
        if (!preg_match($pattern, $procjena_razine_energije)) {
            $procjena_razine_energijeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['fokus_koncentracija'])) {
        $fokus_koncentracijaErr = "";
    } else {
        $fokus_koncentracija = test_input($_POST['fokus_koncentracija']);
        if (!preg_match($pattern, $fokus_koncentracija)) {
            $fokus_koncentracijaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['tjeskoba_depresija'])) {
        $tjeskoba_depresijaErr = "";
    } else {
        $tjeskoba_depresija = test_input($_POST['tjeskoba_depresija']);
        if (!preg_match($pattern, $tjeskoba_depresija)) {
            $tjeskoba_depresijaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['kvaliteta_prehrane'])) {
        $kvaliteta_prehraneErr = "";
    } else {
        $kvaliteta_prehrane = test_input($_POST['kvaliteta_prehrane']);
        if (!preg_match($pattern, $kvaliteta_prehrane)) {
            $kvaliteta_prehraneErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['kolicina_treninga'])) {
        $kolicina_treningaErr = "";
    } else {
        $kolicina_treninga = test_input($_POST['kolicina_treninga']);
        if (!preg_match($pattern, $kolicina_treninga)) {
            $kolicina_treningaErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['zelje'])) {
        $zeljeErr = "";
    } else {
        $zelje = test_input($_POST['zelje']);
        if (!preg_match($pattern, $zelje)) {
            $zeljeErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (empty($_POST['cilj'])) {
        $ciljErr = "";
    } else {
        $cilj = test_input($_POST['cilj']);
        if (!preg_match($pattern, $cilj)) {
            $ciljErr = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        }
    }

    if (isset($_POST["pravila_privatnosti"])) {
        $pravila_privatnosti = 1;
    } else {
        $pravila_privatnostiErr = "<p class='text-danger text-end mt-2'>Morate prihvatiti pravila privatnosti kako bismo mogli spremiti Vaše podatke.</p>";
    }

    foreach ($_POST["zeljeni_dani"] as $kljuc => $datum) {
        $datum2 = test_input($datum);
        if (!preg_match($pattern, $datum2)) {
            $zeljeni_daniErr[] = "<p class='text-danger text-end mt-2'>Specijalni znakovi neće biti spremljeni u bazu.</p>";
        } else {
            $zeljeni_dani[] = $datum2;
            $mail_zeljeni_dani .= "<p>$datum2</p>";
        }
    }


    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
        $captchaErrr = '';
    } else {
        $secretKey = "6Lf4v8shAAAAAGPPTHXt5plljp6u81brAbq-FWTD";
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captcha);

        $json_response = json_decode($response);

        if ($json_response->success == true) {
            $captchaErrr = '';
        } else {
            $captchaErrr = "<p class='text-danger text-end mt-2'>Radite nedozvoljene stvari.</p>";
        }

        $captchaErrr = "<p class='text-danger text-end mt-2'>Radite nedozvoljene stvari. Google recaptcha validacija neuspješna.</p>";
    }






    if (empty($ime_prezimeErr) and empty($emailErr) and empty($broj_mobitelaErr) and empty($godineErr) and empty($razlog_dolaskaErr) and empty($operativni_zahvatiErr) and empty($fraktureErr) and empty($porezotineErr) and empty($traumeErr) and empty($lijekoviErr) and empty($pravila_privatnostiErr) and empty($captchaErrr) and empty($manifestacija_problema_na_tijeluErr) and empty($pocetak_simptomaErr) and empty($trajanje_simptomaErr) and empty($pogorsavanje_simptomaErr) and empty($poboljsavanje_simptomaErr) and empty($sprjecavanje_u_svakodnevnim_radnjamaErr) and empty($ozbiljnost_problemaErr) and empty($nabrojeni_simptomiErr) and empty($uzrok_problemaErr) and empty($unutarnji_organiErr) and empty($druge_terapijeErr) and empty($procjena_stresaErr) and empty($procjena_razine_energijeErr) and empty($fokus_koncentracijaErr) and empty($tjeskoba_depresijaErr) and empty($kvaliteta_prehraneErr) and empty($kolicina_treningaErr) and empty($zeljeErr) and empty($ciljErr)) {

        $to = 'healthclub.vrbovec@gmail.com';
        //$to = 'monikamikec03@gmail.com';

        $message = "
			<div>
				<h3 class='my-2'>Imate novu prijavu za prvi pregled u FizioOne</h3>
				<p class='my-2'>Korisnik $ime_prezime se prijavio/la za pregled.</p>
				<h4 class='mt-4'>Kontakt podaci:</h4>
				<p><span class='text-success'>Email adresa:</span> $email</p>
				<p><span class='text-success'>Broj mobitela:</span> $broj_mobitela</p>
				<p><span class='text-success'>Broj godina:</span> $godine</p>
				
				<h4 class='mt-4'>Objasnite problem/stanje zbog kojeg dolazite? </h4>
				<p>$razlog_dolaska</p>
				
				<h4 class='mt-4'>Koje je mjesto na tijelu gdje se problem najčešće manifestira? </h4>
				<p>$manifestacija_problema_na_tijelu</p>
				
				<h4 class='mt-4'>Kako su počeli simptomi/stanje ? </h4>
				<p>$pocetak_simptoma</p>
				
				<h4 class='mt-4'>Koliko dugo simptomi/stanje traju? </h4>
				<p>$trajanje_simptoma</p>
				
				<h4 class='mt-4'>Što pogoršava simptome/stanje? </h4>
				<p>$pogorsavanje_simptoma</p>

                <h4 class='mt-4'>Što poboljšava simptome/stanje?</h4>
				<p>$poboljsavanje_simptoma</p>
				
				<h4 class='mt-4'>Da li vas vaše stanje sprječava u svakodnevnim radnjama?</h4>
				<p>$sprjecavanje_u_svakodnevnim_radnjama</p>
				
				<h4 class='mt-4'>Kako biste prema vlastitom osjećaju ocijenili ozbiljnost problema/bol ? (0-10)</h4>
				<p>$ozbiljnost_problema </p>
				
				<h4 class='mt-4'>Nabrojite nam sve simptome koje osjećate. </h4>
				<p>$nabrojeni_simptomi</p>
				
				<h4 class='mt-4'>Što vi mislite da je uzrok problema? </h4>
				<p>$uzrok_problema</p>

                <h4 class='mt-4'>Da li ste imali frakture (puknuća) kostiju, ligamenata, mišića ? Ukoliko ih je bilo, koji su i kada ste ih zadobili. </h4>
				<p>$frakture</p>
				
				<h4 class='mt-4'>Da li ste imali operativne zahvate? Ukoliko ih je bilo, koji su i kada ste ih obavili? </h4>
				<p>$operativni_zahvati</p>
							
				<h4 class='mt-4'>Da li ste zadobili porezotine, opekline, udarce i padove? Ukoliko ih je bilo, koji su i kada ste ih zadobili? </h4>
				<p>$porezotine</p>
				
				<h4 class='mt-4'>Imate li ili ste imali problema s unutarnjim organima? Ukoliko da, opišite nam.</h4>
				<p>$unutarnji_organi</p>

				<h4 class='mt-4'>Uzimate li lijekove? Ukoliko da, opišite ih.</h4>
				<p>$lijekovi</p>

				<h4 class='mt-4'>Da li ste pokušali druge terapije i ukoliko da opišite nam iskustvo? </h4>
				<p>$druge_terapije</p>

				<h4 class='mt-4'>Vaša subjektivna procjena stresa 0-10?</h4>
				<p>$procjena_stresa</p>

				<h4 class='mt-4'>Vaša subjektivna procjena razine energije 0-10? </h4>
				<p>$procjena_razine_energije</p>

				<h4 class='mt-4'>Imate li problema s fokusom i koncentracijom? </h4>
				<p>$fokus_koncentracija</p>

				<h4 class='mt-4'>Obzirom na simptome/stanje koje doživljavate osjećate li se tjeskobno ili depresivno? </h4>
				<p>$tjeskoba_depresija</p>

				<h4 class='mt-4'>Kako biste ocijenili vašu kvalitetu prehrane 0 -10? Postoje li namirnice koje izbjegavate radi zdravstvenih razloga? </h4>
				<p>$kvaliteta_prehrane</p>

				<h4 class='mt-4'>Koliko (u minutama) na tjednoj bazi provodite vremena u treningu umjerenog do visokog intenziteta? </h4>
				<p>$kolicina_treninga</p>

				<h4 class='mt-4'>Što biste najviše voljeli opet moći raditi da boli/problema više nema?</h4>
				<p>$zelje</p>

				<h4 class='mt-4'>Što bi rekli da vam je cilj postići kod nas u HealthClubu? </h4>
				<p>$cilj</p>
				
				<h4 class='mt-4'>Termini</h4>
				<div>$mail_zeljeni_dani</div>

			</div>
			";

        $subject = "Prijava za pregled - $ime_prezime";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $to . '' . "\r\n";
        $headers .= 'From: ' . $email . "\r\n";

        $sql = "INSERT INTO prijava_prvi_pregled (ime_prezime, email, broj_mobitela, godine, razlog_dolaska, operativni_zahvati, frakture, porezotine, lijekovi, pravila_privatnosti, termini, manifestacija_problema_na_tijelu, pocetak_simptoma, trajanje_simptoma, pogorsavanje_simptoma, poboljsavanje_simptoma, sprjecavanje_u_svakodnevnim_radnjama, ozbiljnost_problema, nabrojeni_simptomi, uzrok_problema, unutarnji_organi, druge_terapije, procjena_stresa, procjena_razine_energije, fokus_koncentracija, tjeskoba_depresija, kvaliteta_prehrane, kolicina_treninga, zelje, cilj) VALUES('$ime_prezime', '$email', '$broj_mobitela', '$godine', '$razlog_dolaska', '$operativni_zahvati', '$frakture', '$porezotine', '$lijekovi', '$pravila_privatnosti', '$mail_zeljeni_dani', '$manifestacija_problema_na_tijelu', '$pocetak_simptoma', '$trajanje_simptoma', '$pogorsavanje_simptoma', '$poboljsavanje_simptoma', '$sprjecavanje_u_svakodnevnim_radnjama', '$ozbiljnost_problema', '$nabrojeni_simptomi', '$uzrok_problema', '$unutarnji_organi', '$druge_terapije', '$procjena_stresa', '$procjena_razine_energije', '$fokus_koncentracija', '$tjeskoba_depresija', '$kvaliteta_prehrane', '$kolicina_treninga', '$zelje', '$cilj')";
        mysqli_query($veza, $sql);

        if (mail($to, $subject, $message, $headers)) {
            echo "<script>window.location = 'zahvala_kontakt.php';</script>";
        } else {
            $messageErr = "<p class='text-danger'>Dogodila se pogreška, pokušajte ponovno!</p>";
        }
    } else {
        $messageErr = "<p class='text-danger'>Niste ispunili sva polja!</p>";
    }
}
include("zaglavlje.php");
include("navigacija_light.php");

?>


<div class="container flex-grow-1 py-5">
    <h1>Prijavi se na prvi pregled</h1>
    <h2 class="text-secondary mb-3">Rezervacija termina & Informiranje o rehabilitaciji</h2>

    <div class="row ps-4">


        <form method="post" action="" id="form" class="row bg-light">

            <div class="col-xl-3 col-lg-6 p-1">
                <label>Ime i prezime:</label>
                <input type="text" name="ime_prezime" class="form-control" value="<?php echo $ime_prezime; ?>">
                <?php echo $ime_prezimeErr; ?>
            </div>
            <div class="col-xl-3 col-lg-6 p-1">
                <label>Godine:</label>
                <input type="number" name="godine" class="form-control" value="<?php echo $godine; ?>">
                <?php echo $godineErr; ?>
            </div>

            <div class="col-xl-3 col-lg-6 p-1">
                <label>Email adresa:</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <?php echo $emailErr; ?>
            </div>

            <div class="col-xl-3 col-lg-6 p-1">
                <label>Broj mobitela:</label>
                <input type="text" name="broj_mobitela" class="form-control" value="<?php echo $broj_mobitela; ?>">
                <?php echo $broj_mobitelaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Objasnite problem/stanje zbog kojeg dolazite? </label>
                <textarea name="razlog_dolaska" class="form-control"><?php echo $razlog_dolaska; ?></textarea>
                <?php echo $razlog_dolaskaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Koje je mjesto na tijelu gdje se problem najčešće manifestira?</label>
                <textarea name="manifestacija_problema_na_tijelu" class="form-control"><?php echo $manifestacija_problema_na_tijelu; ?></textarea>
                <?php echo $manifestacija_problema_na_tijeluErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Kako su počeli simptomi/stanje?</label>
                <textarea name="pocetak_simptoma" class="form-control"><?php echo $pocetak_simptoma; ?></textarea>
                <?php echo $pocetak_simptomaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Koliko dugo simptomi/stanje traju?</label>
                <textarea name="trajanje_simptoma" class="form-control"><?php echo $trajanje_simptoma; ?></textarea>
                <?php echo $trajanje_simptomaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Što pogoršava simptome/stanje? </label>
                <textarea name="pogorsavanje_simptoma" class="form-control"><?php echo $pogorsavanje_simptoma; ?></textarea>
                <?php echo $pogorsavanje_simptomaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Što poboljšava simptome/stanje? </label>
                <textarea name="poboljsavanje_simptoma" class="form-control"><?php echo $poboljsavanje_simptoma; ?></textarea>
                <?php echo $poboljsavanje_simptomaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Da li vas vaše stanje sprječava u svakodnevnim radnjama? </label>
                <textarea name="sprjecavanje_u_svakodnevnim_radnjama" class="form-control"><?php echo $sprjecavanje_u_svakodnevnim_radnjama; ?></textarea>
                <?php echo $sprjecavanje_u_svakodnevnim_radnjamaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Kako biste prema vlastitom osjećaju ocijenili ozbiljnost problema/bol ? (0-10) </label>
                <textarea name="ozbiljnost_problema" class="form-control"><?php echo $ozbiljnost_problema; ?></textarea>
                <?php echo $ozbiljnost_problemaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Nabrojite nam sve simptome koje osjećate.</label>
                <textarea name="nabrojeni_simptomi" class="form-control"><?php echo $nabrojeni_simptomi; ?></textarea>
                <?php echo $nabrojeni_simptomiErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Što vi mislite da je uzrok problema? </label>
                <textarea name="uzrok_problema" class="form-control"><?php echo $uzrok_problema; ?></textarea>
                <?php echo $uzrok_problemaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Da li ste imali frakture (puknuća) kostiju, ligamenata, mišića ? Ukoliko ih je bilo, koji su i kada ste ih zadobili. </label>
                <textarea name="frakture" class="form-control"><?php echo $frakture; ?></textarea>
                <?php echo $fraktureErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Da li ste imali operativne zahvate? Ukoliko ih je bilo, koji su i kada ste ih obavili?</label>
                <textarea name="operativni_zahvati" class="form-control"><?php echo $operativni_zahvati; ?></textarea>
                <?php echo $operativni_zahvatiErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Da li ste zadobili porezotine, opekline, udarce i padove? Ukoliko ih je bilo, koji su i kada ste ih zadobili? </label>
                <textarea name="porezotine" class="form-control"><?php echo $porezotine; ?></textarea>
                <?php echo $porezotineErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Imate li ili ste imali problema s unutarnjim organima? Ukoliko da, opišite nam.</label>
                <textarea name="unutarnji_organi" class="form-control"><?php echo $unutarnji_organi; ?></textarea>
                <?php echo $unutarnji_organiErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Uzimate li lijekove? Ukoliko da, opišite ih. </label>
                <textarea name="lijekovi" class="form-control"><?php echo $lijekovi; ?></textarea>
                <?php echo $lijekoviErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Da li ste pokušali druge terapije i ukoliko da opišite nam iskustvo?</label>
                <textarea name="druge_terapije" class="form-control"><?php echo $druge_terapije; ?></textarea>
                <?php echo $druge_terapijeErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Vaša subjektivna procjena stresa 0-10?</label>
                <textarea name="procjena_stresa" class="form-control"><?php echo $procjena_stresa; ?></textarea>
                <?php echo $procjena_stresaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Vaša subjektivna procjena razine energije 0-10?</label>
                <textarea name="procjena_razine_energije" class="form-control"><?php echo $procjena_razine_energije; ?></textarea>
                <?php echo $procjena_razine_energijeErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Imate li problema s fokusom i koncentracijom ?</label>
                <textarea name="fokus_koncentracija" class="form-control"><?php echo $fokus_koncentracija; ?></textarea>
                <?php echo $fokus_koncentracijaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Obzirom na simptome/stanje koje doživljavate osjećate li se tjeskobno ili depresivno? </label>
                <textarea name="tjeskoba_depresija" class="form-control"><?php echo $tjeskoba_depresija; ?></textarea>
                <?php echo $tjeskoba_depresijaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Kako biste ocijenili vašu kvalitetu prehrane 0 -10? Postoje li namirnice koje izbjegavate radi zdravstvenih razloga? </label>
                <textarea name="kvaliteta_prehrane" class="form-control"><?php echo $kvaliteta_prehrane; ?></textarea>
                <?php echo $kvaliteta_prehraneErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Koliko (u minutama) na tjednoj bazi provodite vremena u treningu umjerenog do visokog intenziteta? </label>
                <textarea name="kolicina_treninga" class="form-control"><?php echo $kolicina_treninga; ?></textarea>
                <?php echo $kolicina_treningaErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Što biste najviše voljeli opet moći raditi da boli/problema više nema?</label>
                <textarea name="zelje" class="form-control"><?php echo $zelje; ?></textarea>
                <?php echo $zeljeErr; ?>
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <label>Što bi rekli da vam je cilj postići kod nas u HealthClubu?</label>
                <textarea name="cilj" class="form-control"><?php echo $cilj; ?></textarea>
                <?php echo $ciljErr; ?>
            </div>



            <div class="col-xl-6 col-lg-12 p-1">
                <label>Odaberite datume i vrijeme kada biste voljeli rezervirati svoj termin.</label>
                <div id='zeljeni_dani'>
                    <?php
                    foreach ($zeljeni_dani as $key => $value) {
                    ?>
                        <input type="text" name="zeljeni_dani[]" class="fontAwesome form-control p-1" value="<?php echo $value; ?>">
                    <?php
                        echo $zeljeni_daniErr[$key];
                    }
                    ?>
                </div>
                <input type="text" class="fontAwesome datum_start form-control p-1" placeholder='&#xf073 Kliknite da odaberete datum i vrijeme' value="">
            </div>

            <div class="col-xl-6 col-lg-12 p-1">
                <div class="form-check form-switch p-0 m-0">
                    <label class="form-check-label m-0 p-0 d-block" for="flexSwitchCheckDefault">Označite da prihvaćate <a href='politika_privatnosti.php' target='_blank'>pravila privatnosti</a> kako bismo mogli obraditi Vaše podatke.</label>

                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="pravila_privatnosti">
                    <?php echo $pravila_privatnostiErr; ?>
                </div>
            </div>

            <div class="col-md-12 p-1 d-flex justify-content-end">
                <input type="submit" name="posalji" value="Pošalji" class="btn btn-warning px-5 g-recaptcha" data-sitekey="6Lf4v8shAAAAALd-tNPK9E5zYML5St99wYDkrsBI" data-callback='onSubmit' data-action='submit'>
            </div>

            <div class="col-md-12 p-1 d-flex justify-content-end">
                <div>
                    <?php echo "<div class=''>$captchaErrr</div>"; ?>
                    <?php echo "<div class=''>$messageErr</div>"; ?>
                </div>
            </div>


        </form>

    </div>
</div>





<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
    function onSubmit(token) {
        document.getElementById("form").submit();
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        localStorage.setItem('scrollpos', window.scrollY);
    };
</script>
<script>
    jQuery.datetimepicker.setLocale('hr');
    $('.datum_start').datetimepicker({
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,

        onSelectTime: function(dp, $input) {
            if ($input.val().length != 0) {
                $('<input>').attr({
                    type: 'text',
                    class: 'form-control p-1',
                    name: 'zeljeni_dani[]',
                    value: $input.val(),
                }).appendTo('#zeljeni_dani');
                $('.datum_start').val('');
            }
        },

        closeOnDateTimeSelect: true,
    });
</script>
<?php
include("drustveneMreze_light.php");
include("podnozje.php");
?>
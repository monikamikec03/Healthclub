<?php
ob_start();

if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
    $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
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
    if (empty($id_clanak)) {
    ?>
        <link rel="icon" type="image/x-icon" href="/slike/favicon.ico">
        <title>HEALTHCLUB</title>
        <meta name="description" content="HealthClub Vrbovec centar za trening i rehabilitaciju">

    <?php
    } else {
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

    <link href="../css/animate.css" rel="stylesheet" type="text/css" />
    <link href="../css/style_1.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
    <link rel="stylesheet" href="../include/jquery-ui.css">
    <link rel="stylesheet" href="include/vegas.min.css">

    <link rel="stylesheet" href="../include/bootstrap.css">
    <link rel="stylesheet" href="../include/css.css">

    <link rel="stylesheet" type="text/css" href="../include/jquery.datetimepicker.css">
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

<body class="h-100">

    <?php if (empty($_COOKIE["id_posjetioca"])) { ?>
        <div id="myModal" class="modal">
            <div class="modal-content rounded-0 bg-light text-dark p-3 ">
                <div class="modal-header">
                    <h2>Kolačići</h2>
                </div>
                <div class="modal-body">
                    <p>Ova web stranica koristi kolačiće (tzv. cookies) kako bismo Vam omogućili najbolje iskustvo korištenja. Više o kolačićima možete saznati klikom na <span class="text-success">"Politika kolačića"</span>. Kliknite na "Prihvati sve"' ako prihvaćate upotrebu kolačića i želite nastaviti koristiti ovu web stranicu.</p>

                    <button class="btn btn-success w-100 my-3" id="prihvati_kolacice">Prihvati sve</button>

                </div>
            </div>
        </div>
    <?php
    } else {
        $id_posjetioca = $_COOKIE["id_posjetioca"];
        setcookie("id_posjetioca", $id_posjetioca, strtotime("+1 year"));
    }
    ?>

    <script>
        //How to prevent the "Confirm Form Resubmission" dialog?
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script>
        $(document).ready(function() {
            $("button#prihvati_kolacice").click(function() {

                $.ajax({
                    url: "dropdown.php",
                    type: "post",
                    data: {
                        prihvati_kolacice: 1,

                    },
                    success: function(response) {
                        $("#myModal").hide();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });


            });
        });
    </script>
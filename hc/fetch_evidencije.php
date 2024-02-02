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

if (isset($_POST["id_podgrupe"]) and isset($_POST["mjesec"]) and isset($_POST["godina"])) {
    $id_podgrupe = test_input($_POST["id_podgrupe"]);

    $mjesec = test_input($_POST["mjesec"]);

    $formattedMonth = str_pad($mjesec, 2, '0', STR_PAD_LEFT);

    $godina = test_input($_POST["godina"]);

    //PROVJERA
    $sql = "SELECT * FROM evidencije_dolazaka WHERE MONTH(datum) = '$formattedMonth' AND
    YEAR(datum) = '$godina'";
    $res = mysqli_query($veza, $sql);
    while ($red = mysqli_fetch_array($res)) {
        $korisnik_id = $red["korisnik_id"];
        $redni_broj_termina = $red["redni_broj_termina"];
        $datum_baza = $red["datum"];
        $dani_dolaska[$korisnik_id][$redni_broj_termina] = $datum_baza;
    }

    $sql = "SELECT * FROM podgrupe
    WHERE id_podgrupe = $id_podgrupe
    AND aktivna_podgrupa = 1";
    $res = mysqli_query($veza, $sql);
    $red = mysqli_fetch_array($res);
    $id_podgrupe = $red["id_podgrupe"];
    $naziv_podgrupe = $red["naziv_podgrupe"];
    $broj_termina = $red["broj_termina"];
?>

    <div class='table-responsive'>
        <table class='table table-striped DataTable w-100' id="table_id">
            <thead>
                <tr>
                    <th>R.br.</th>
                    <th>Klijent</th>
                    <?php
                    for ($i = 1; $i <= $broj_termina; $i++) {
                        echo "<th>$i</th>";
                    }
                    ?>


                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM clanstva, korisnici 
                WHERE podgrupa_id = $id_podgrupe 
                AND id_korisnik = korisnik_id";
                $res = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($res)) {
                    $naziv_korisnika = $red["naziv_korisnika"];
                    $id_korisnik = $red["id_korisnik"];
                ?>
                    <tr>
                        <td><?php echo ++$br; ?>.</td>
                        <td><a href="prikaz_korisnik.php?id=<?php echo $id_korisnik; ?>"><?php echo $naziv_korisnika; ?></a></td>
                        <?php
                        for ($j = 1; $j <= $broj_termina; $j++) {
                        ?>
                            <td>
                                <?php
                                if (isset($dani_dolaska[$id_korisnik][$j])) {
                                    $dani_dolaska_hr = date("d.m.Y.", strtotime($dani_dolaska[$id_korisnik][$j]));
                                ?>
                                    <p><?php echo $dani_dolaska_hr; ?></p>
                                <?php
                                } else {
                                ?>
                                    <input style='width:1em;height:1em;' class='form-check-input' type='checkbox' value='<?php echo "$j"; ?>' onclick="unesi_dolazak(this);" id_korisnik="<?php echo $id_korisnik; ?>" mjesec="<?php echo $mjesec; ?>" godina="<?php echo $godina; ?>">
                                <?php
                                }
                                ?>

                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }

                ?>
            </tbody>
        </table>
    </div>
	<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            language: {
                decimal: ',',
                thousands: '.',
            },
            "paging": true,
            "info": true,
            "order": [],
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 redova', '25 redova', '50 redova', 'Svi zapisi']
            ],
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },

                {
                    extend: 'print',
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                'pageLength',
            ],
            "order": [],
            columnDefs: [{
                type: 'date-eu',
                targets: [6],
            }],
        });
    });
</script>
    <script>
        function unesi_dolazak(value) {
            let id_korisnik = $(value).attr("id_korisnik");
            let mjesec = $(value).attr("mjesec");
            let godina = $(value).attr("godina");
            let redni_broj_termina = $(value).val();

            $.post("unos_evidencija_dolaska.php", {
                    id_korisnik: id_korisnik,
                    mjesec: mjesec,
                    godina: godina,
                    redni_broj_termina: redni_broj_termina,
                },
                function(data, status) {
                    if (data) {
                        $(value).parent("td").append(`<p>${data}</p>`);
                        $(value).remove();
                    } else {
                        alert("Error");
                    }
                });

        }
    </script>
<?php
}
?>
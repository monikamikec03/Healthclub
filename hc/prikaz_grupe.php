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


if (isset($_POST['posalji'])) {
    if (empty($_POST["podgrupa_id"])) {
        $podgrupa_idErr = "<p class='text-danger'>* niste odabrali podgrupu</p>";
    } else {
        echo $podgrupa_id = test_input($_POST["podgrupa_id"]);
        if (!preg_match("/^[0-9? ]*$/", $podgrupa_id)) {
            $podgrupa_idErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["korisnik"])) {
        $korisnikErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $korisnik = test_input($_POST["korisnik"]);
        if (!preg_match("/^[0-9? ]*$/", $korisnik)) {
            $korisnikErr = "<p class='text-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>";
        }
    }

    if (empty($_POST["datum_od"])) {
        $datum_odErr = "<p class='text-danger'>* morate popuniti polje</p>";
    } else {
        $datum_od1 = test_input($_POST["datum_od"]);
        $datum_od = date("d.m.Y", strtotime($datum_od1));
        $datum_od2 = date("Y-m-d", strtotime($datum_od1));
        if (!preg_match("/\d{2}.\d{2}.\d{4}$/", $datum_od1)) {
            $datum_odErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }

    if (empty($_POST["datum_do"])) {
        $datum_do2 = "7777-07-07";
    } else {
        $datum_do1 = test_input($_POST["datum_do"]);
        $datum_do = date("d.m.Y", strtotime($datum_do1));
        $datum_do2 = date("Y-m-d", strtotime($datum_do1));
        if (!preg_match("/\d{2}.\d{2}.\d{4}$/", $datum_do1)) {
            $datum_doErr = "<p class='text-danger'>* neispravni znakovi</p>";
        }
    }


    if (empty($korisnikErr) && empty($datum_odErr) && empty($datum_doErr) && empty($podgrupa_idErr)) {
        $sql = "INSERT INTO clanstva (korisnik_id, datum_od, datum_do, podgrupa_id)
		VALUES('$korisnik', '$datum_od2', '$datum_do2', '$podgrupa_id')";
        if (mysqli_query($veza, $sql)) {
            header("location:prikaz_rubrika.php?id=$podgrupa_id");
        } else {
            $porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Dogodila se pogreška, pokušajte ponovno.</td></tr>";
        }
    } else {
        $porukaErr = "<tr><td colspan='5' class='text-danger text-center'>Niste ispunili sva polja.</td></tr>";
    }
}


if (isset($_GET["id"])) {
    $id = ($_GET['id']);
    $sql = "SELECT * FROM podgrupe WHERE id_podgrupe = $id";
    $rezultat = mysqli_query($veza, $sql);
    if ($red = mysqli_fetch_array($rezultat)) {
        $id_podgrupe = $red['id_podgrupe'];
        $naziv = $red['naziv_podgrupe'];
    }
}

?>

<div id="sadrzaj">


    <div class='d-flex flex-wrap justify-content-between align-items-center'>
        <h2>Prikaz članova grupe: <?php echo $naziv; ?></h2>

        <div class='d-flex flex-wrap justify-content-end align-items-center'>

            <a class='btn btn-warning m-1' href='rubrike.php'>🡸 Natrag</a>
        </div>
    </div>
    <div class='table-responsive'>

        <table class="table table-striped" id="table_id">
            <caption id="tablecaption"></caption>
            <thead>
                <tr>
                    <th>R.br.</th>
                    <th>Ime i prezime</th>
                    <th>Članstvo od</th>
                    <th>Članstvo do</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT *,
                (SELECT od FROM aktivnosti WHERE korisnik_id = id_korisnik AND naziv_aktivnosti = 'Članarina' ORDER BY datum_aktivnosti DESC LIMIT 1) AS clanstvo_od,
                (SELECT do FROM aktivnosti WHERE korisnik_id = id_korisnik AND naziv_aktivnosti = 'Članarina' ORDER BY datum_aktivnosti DESC LIMIT 1) AS clanstvo_do
                 FROM clanstva, korisnici 
                WHERE podgrupa_id = $id
                AND id_korisnik = korisnik_id
                AND datum_do > CURDATE()
                AND aktivan = 1
                ORDER BY naziv_korisnika";
                $rezultat = mysqli_query($veza, $sql);
                while ($red = mysqli_fetch_array($rezultat)) {
                    $naziv_korisnika = $red["naziv_korisnika"];
                    $id_korisnik = $red["id_korisnik"];
                    $osloboden_placanja = $red["osloboden_placanja"];
                    $clanstvo_od = date("d.m.Y.", strtotime($red["clanstvo_od"]));
                    $clanstvo_do = date("d.m.Y.", strtotime($red["clanstvo_do"]));

                    if (strtotime($clanstvo_do) < time()) {
                        $boja = "text-danger fw-bold";
                    } else {
                        $boja = "";
                    }

                    if ($osloboden_placanja == 1) {
                        $boja = '';
                        $clanstvo_do = '';
                        $clanstvo_od = '';
                    }



                    echo "<tr>";
                    echo "<td>" . ++$br . ".</td>";
                    echo "<td>$naziv_korisnika</td>";
                    if ($osloboden_placanja == 1) {
                        echo "<td>Oslobođen/a plaćanja</td>";
                        echo '<td></td>';
                    } else {
                        echo "<td>$clanstvo_od</td>";
                        echo "<td class='$boja'>$clanstvo_do</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    $('#tablecaption').text('<?php echo date("d.m.Y."); ?>');

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
                    title: '<?php echo $naziv; ?>',
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
                    title: '<?php echo $naziv; ?>',
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
                targets: [2, 3],
            }],
        });
    });
</script>


<?php
include("podnozje.php");
?>
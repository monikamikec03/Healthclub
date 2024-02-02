<?php
ob_start();
session_start();
if (in_array($_SESSION['idRole'], [15588, 2, 22, 222, 3])) {
    require("../include/putanja.php");
    require("navigacija.php");
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
?>
<div id="sadrzaj">
    <div class='d-flex justify-content-between align-items-center flex-wrap'>
        <h2 class='m-1'>Artikli - podgrupe</h2>
        <a class='btn btn-primary m-1' href="unos_rubrika.php">+ dodaj podgrupu</a>
    </div>
    <div class='table-responsive'>
        <table class="table table-hover table-striped">
            <tr>

                <th>Artikl</th>
                <th>Podgrupa</th>
                <th>Redoslijed podgrupa</th>
                <th>Aktivna podgrupa</th>
                <th></th>
            </tr>

            <?php
            $sql = "SELECT * FROM artikli_popis, podgrupe  
	WHERE id_artikla = artikl_id 
	ORDER BY artikl_id, redoslijed_podgrupe";
            $rezultat = mysqli_query($veza, $sql);
            while ($red = mysqli_fetch_array($rezultat)) {
                $id = $red['id_podgrupe'];
                $redoslijed_podgrupa = $red['redoslijed'];
                $naziv = $red['naziv_artikla'];
                if ($red['aktivna_podgrupa'] == 1) {
                    $aktivna_podgrupa = "DA";
                } else {
                    $aktivna_podgrupa = "NE";
                }
                $podgrupa = $red['naziv_podgrupe'];
                $redoslijed_podgrupa = $red['redoslijed_podgrupe'];
                if ($red['aktivna_podgrupa'] == 1) {
                    $aktivne_rubrike = "DA";
                } else {
                    $aktivne_rubrike = "NE";
                }
                echo "<tr>";
                echo "<td> $naziv</td>";
                echo "<td><a href='prikaz_rubrika.php?id=$id'>$podgrupa</a></td>";
                echo "<td> $redoslijed_podgrupa</td>";
                echo "<td> $aktivne_rubrike</td>";
                echo "<td><a href='prikaz_grupe.php?id=$id'>Izvještaj <i class='fa-solid fa-eye'></i></a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>

</html>
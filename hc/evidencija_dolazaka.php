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

$danas = date("d.m.Y");
$mjesec = (int)date("m");
$godina = (int)date("Y");
$yearRange = range(2020, $godina + 1);




?>

<div class="container-fluid">
    <div class="bg-light p-2 d-flex">
        <select class="form-control p-1 w-auto rounded-0" name="podgrupa">
            <option value="">Odaberi grupu</option>
            <?php
            $sql = "SELECT * FROM podgrupe WHERE aktivna_podgrupa = 1 ORDER BY naziv_podgrupe";
            $res = mysqli_query($veza, $sql);
            while ($red = mysqli_fetch_array($res)) {
                $id_podgrupe = $red["id_podgrupe"];
                $naziv_podgrupe = $red["naziv_podgrupe"];
                echo "<option value='$id_podgrupe'>$naziv_podgrupe</option>";
            }
            ?>
        </select>
        <select class="form-control p-1 w-auto rounded-0" name="mjesec">
            <?php
            $sql = "SELECT * FROM mjesec ORDER BY id_mjesec";
            $res = mysqli_query($veza, $sql);
            while ($red = mysqli_fetch_array($res)) {
                $id_mjesec = $red["id_mjesec"];
                $puni_naziv_mjeseca = $red["puni_naziv_mjeseca"];
            ?>
                <option <?php if ($mjesec == $id_mjesec) echo "selected"; ?> value='<?php echo $id_mjesec; ?>'><?php echo $puni_naziv_mjeseca; ?></option>
            <?php
            }
            ?>
        </select>
        <select class="form-control p-1 w-auto rounded-0" name="godina">
            <?php
            foreach ($yearRange as $year) {
            ?>
                <option <?php if ($year == $godina) echo "selected"; ?> value='<?php echo $year; ?>'><?php echo $year; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div id="fetched_data" class="p-2">

    </div>

</div>

<script>
    $("select").change(function(e) {
        e.preventDefault();
        let id_podgrupe = $("select[name='podgrupa']").val();
        let mjesec = $("select[name='mjesec']").val();
        let godina = $("select[name='godina']").val();

        console.log(`Grupa:${id_podgrupe}, Mjesec:${mjesec}, Godina:${godina}, `);
        $.post("fetch_evidencije.php", {
                id_podgrupe: id_podgrupe,
                mjesec: mjesec,
                godina: godina,
            },
            function(data, status) {
                $("#fetched_data").html(data);
            });
    });
</script>
</body>

</html>
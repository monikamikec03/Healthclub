<?php
include("zaglavlje.php");
include("navigacija_light.php");
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}

function contains($str){
    $vulgarizmi = array('pizd', 'kurc', 'kurac', 'kurč', 'kurac', 'pičk', 'mrš', 'sranj', 'seronj', 'serator', 'srat', 'seruck', 'kenja', 'govn', 'seri', 'sranj', 'guzic', 'guz', 'prdonj', 'uhljeb', 'jeb', 'ser');
    foreach($vulgarizmi as $a) {
        if (stripos($str,$a) !== false){
            return true;
        }
    }
    return false;
}
if (isset($_POST["trazi"])) {								
	if(empty($_POST["unos"])){
        $unosErr = "";
    }
    else{
        $unos = test_input($_POST["unos"]);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$unos))  {
            $unosErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu</p>"; 
        
        }
        else{
            if(contains($unos)){
                $unosErr = "<p class='text-danger'>Vaš unos sadrži zabranjene i vulgarne riječi.</p>"; 
            }
        }
    }
}
else{
    $naslov = "Svi članci";
}
?>

    

<div class="container flex-grow-1 my-5">
    <div class="d-flex justify-content-end align-items-center my-1 flex-wrap">
       
        <form class="form-control p-0 m-0   me-1 d-flex align-items-stretch w-auto" method="post" action="pretraga.php">
            <input type="text" name="unos" class="form-control bg-white border-0 shadow-none" placeholder="Traži..." autocomplete="off">
            <input type="submit" value=" &#xf002; " class="btn btn-success fontAwesome" name="trazi">
        </form>
        
    </div>
     <h5 class='flex-grow-1 py-1'>Traži: <span class="text-danger fw-bold"><?php echo $unos; ?></h5>
    <div class="row">
    <?php
    if(empty($unosErr)){
        $sql = "SELECT * FROM clanak
        WHERE objavljen = 1
       
        AND datum_objave <= CURDATE()
        AND 
        (ime_autora LIKE '%$unos%' OR naslov_clanka LIKE '%$unos%' OR uvod LIKE '%$unos%' OR tekst LIKE '%$unos%')
        ORDER BY datum_objave DESC
        ";
        
        $res = mysqli_query($veza, $sql);
        if(mysqli_num_rows($res) > 0){
            while($red = mysqli_fetch_array($res)){
                $id_clanak = $red['id_clanak'];
                $naslov_clanka = $red['naslov_clanka'];
                $uvod = $red['uvod'];
                $ime_autora = $red['ime_autora'];
                $datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
                
                $sql_img = "SELECT * FROM slike_naslova WHERE clanak_id = $id_clanak
                ORDER BY datum_unosa ASC LIMIT 1";
                $res_img = mysqli_query($veza, $sql_img);
                if($redak = mysqli_fetch_array($res_img)){
                    $putanja = $redak["putanja"];
                    
                }
                else{
                    $putanja = '';
                }
                
                $sqllike = "SELECT COUNT(id_lajka) AS broj_lajkova FROM lajkovi WHERE clanak_id = $id_clanak";
                $reslike = mysqli_query($veza, $sqllike);
                if(mysqli_num_rows($reslike) > 0){
                    $redlike = mysqli_fetch_array($reslike);
                    $broj_lajkova = $redlike["broj_lajkova"];
                }
                else{
                    $broj_lajkova = 0;
                }
                
                $sqlcomm = "SELECT COUNT(id_komentara) AS broj_komentara FROM komentari WHERE clanak_id = $id_clanak";
                $rescomm = mysqli_query($veza, $sqlcomm);
                if(mysqli_num_rows($rescomm) > 0){
                    $redcomm = mysqli_fetch_array($rescomm);
                    $broj_komentara = $redcomm["broj_komentara"];
                }
                else{
                    $broj_komentara = 0;
                }
                
                ?>
                <a href="clanak.php?id=<?php echo $id_clanak; ?>" class="col-xl-3 col-lg-4 col-md-6 p-3 hover d-flex flex-column">
                    <div class="">
                        <?php
                        if(!empty($putanja)){
                            echo "<img src='$putanja' alt='$naslov_clanka' class='shadow-none height-200 object-fit-cover ' /> ";
                        }
                        else{
                            echo "<div class='h-100 bg-light'><img class='shadow-none height-200 object-fit-contain' src='slike/HEALTHCLUB LOGO.png' alt='HEALTHCLUB'></div>";
                        }
                        ?>
                    </div>
                    
                    <div class="d-flex flex-column align-items-between justify-content-between flex-grow-1 bg-light px-3">
                        <div class="flex-grow-1">
                            <h5 class="text-dark pt-3"><?php echo $naslov_clanka; ?> </h5>
                            <h6 class="py-3 text-success"> <?php echo "$ime_autora - $datum_objave"; ?></h6>
                            <p class="text-dark"><?php echo $uvod; ?></p>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            
                            <small class="text-dark d-inline-block my-3 me-3 text-decoration-underline flex-grow-1">Više...</small>
                            <div class="d-flex flex-wrap justify-content-end p-3 me-3 bg-light">
                                <p class="interactive-icons link-secondary mx-3 p-2 cursor-pointer komentiraj">
                                    <small><?php echo $broj_lajkova; ?></small>
                                    <i class=" fa-solid fa-heart"></i>
                                </p>
                                <p class="interactive-icons link-secondary mx-3 p-2 cursor-pointer komentiraj">
                                    <small><?php echo $broj_komentara; ?></small>
                                    <i class=" fa-solid fa-comment"></i>
                                </p>
                            </div>
                            <div></div>
                        </div>
                    </div>
                    
                </a>
                
                <?php
            }
        ?>
        </main>

        <?php
            
        }
        
        else{
            echo "<h6 class='my-2'>Nije pronađen nijedan članak.</h6>";
        }
    }
        ?>
            
            
    </div>
</div>

<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>

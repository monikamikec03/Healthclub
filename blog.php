<?php
include("zaglavlje.php");
include("navigacija_light.php");
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}

if (isset($_GET["id"])) {								
	$id = test_input($_GET["id"]);
	if (!preg_match("/^[0-9 ]*$/",$id)){
        header("Location: blog.php");
	}
    else{
        $sql = "SELECT * FROM kategorije WHERE id_kategorije = $id
        AND aktivna_kategorija = 1";
        $res = mysqli_query($veza, $sql);
        if(mysqli_num_rows($res) == 1){
            $red = mysqli_fetch_array($res);
            $kategorija = $red["id_kategorije"];
            $naslov = $naziv_kategorije = $red["naziv_kategorije"];
        }
        else{
            header("Location: blog.php");
        }
    }
}
else{
    $naslov = "Svi članci";
}
?>

    

<div class="container my-5">
   <div class="d-flex justify-content-end mb-2">
        <form class="form-control p-0 m-0 me-1 d-flex align-items-stretch w-auto" method="post" action="pretraga.php">
            <input type="text" name="unos" class="form-control bg-white border-0 shadow-none" placeholder="Traži..." autocomplete="off">
            <input type="submit" value=" &#xf002; " class="btn btn-success fontAwesome" name="trazi">
        </form>
    </div>
    <div class="row">
         
        <div class="col-lg-9 col-md-12" >
            
            <h3><?php echo $naslov; ?></h3>
            <main>
            <?php
            
            if(empty($kategorija)){
                $sql = "SELECT COUNT(id_clanak) AS broj_clanaka FROM clanak
                WHERE tip_teksta = 1
                AND objavljen = 1
                AND datum_objave <= CURDATE()
                ORDER BY datum_objave DESC";
                $res = mysqli_query($veza, $sql);
                $red = mysqli_fetch_array($res);
                $broj_clanaka = $red["broj_clanaka"];
        
                $sql = "SELECT * FROM clanak
                WHERE tip_teksta = 1
                AND objavljen = 1
                AND datum_objave <= CURDATE()
                ORDER BY datum_objave DESC
                LIMIT 5";
            }
            else{
                $sql = "SELECT COUNT(id_clanak) AS broj_clanaka FROM clanak
                WHERE tip_teksta = 1
                AND objavljen = 1
                AND datum_objave <= CURDATE()
                AND kategorije_id = $kategorija
                ORDER BY datum_objave DESC";
                $res = mysqli_query($veza, $sql);
                $red = mysqli_fetch_array($res);
                $broj_clanaka = $red["broj_clanaka"];
        
                $sql = "SELECT * FROM clanak
                WHERE tip_teksta = 1
                AND objavljen = 1
                AND datum_objave <= CURDATE()
                AND kategorije_id = $kategorija
                ORDER BY datum_objave DESC
                LIMIT 5";
            }
            $res = mysqli_query($veza, $sql);
            if(mysqli_num_rows($res) > 0){
                while($red = mysqli_fetch_array($res)){
                    $id_clanak = $red['id_clanak'];
                    $naslov_clanka = $red['naslov_clanka'];
                    $uvod = $red['uvod'];
                    $ime_autora = $red['ime_autora'];
                    $datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
                    
                    //kako bih znala otkud da učitam još članaka
                    $posljednji_datum = $red["datum_objave"];
                    $posljednji_clanak = $id_clanak; 
                    
                    
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
                    <a href="clanak.php?id=<?php echo $id_clanak; ?>" class="row py-4 px-0 m-0 hover">
                        <div class="col-lg-4 col-md-6">
                            <?php
                            if(!empty($putanja)){
                                echo "<img src='$putanja' alt='$naslov_clanka' class='w-100 height-200 object-fit-cover shadow-sm' /> ";
                            }
                            else{
                                echo "<div class='h-100 bg-light'><img class='w-100 height-200 object-fit-contain' src='slike/HEALTHCLUB LOGO.png' alt='HEALTHCLUB'></div>";
                            }
                            ?>
                        </div>
                        
                        <div class="col-lg-8 col-md-6">
                            <h5 class="text-dark pt-3  text-decoration-underline"><?php echo $naslov_clanka; ?> </h5>
                            <h6 class="py-3 text-success"> <?php echo "$ime_autora  $datum_objave"; ?></h6>
                            <p class="link-dark"><?php echo $uvod; ?></p>
                            <div class="d-flex align-items-center mt-3">
                                
                                <div class="d-flex flex-wrap justify-content-end me-3 bg-light">
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
                        <input type="hidden" name="posljednji_datum" value="<?php echo $posljednji_datum; ?>">
                        <input type="hidden" name="posljednji_clanak" value="<?php echo $posljednji_clanak; ?>">
                        <input type="hidden" name="broj_clanaka" value="<?php echo $broj_clanaka; ?>">
                    </a>
                    
                    <?php
                }
            ?>
            </main>
            <div class="d-flex justify-content-center">
                <?php
                if($broj_clanaka > 5){
                   echo '<button class="btn btn-outline-success my-3 w-100" id="load_more">Učitaj još...</button>';
                }
                ?>
                
            </div>
            <?php
                
            }
            
            else{
                echo "<h6>Nema objavljenih članaka iz kategorije <span class='fw-bold text-danger'>$naslov</span>.</h6>";
            }
            ?>
            
            
        </div>
        <div class="col-lg-3 col-md-12">
            
            <div class="my-5">
                <h3 class="pb-3">Objave</h3>
                <?php
                if(empty($kategorija)){
                    $sql = "SELECT * FROM clanak 
                    WHERE tip_teksta = 2
                    AND objavljen = 1
                    AND datum_objave <= CURDATE()
                    ORDER BY datum_objave DESC
                    LIMIT 5";
                }
                else{
                    $sql = "SELECT * FROM clanak 
                    WHERE tip_teksta = 2
                    AND objavljen = 1
                    AND kategorije_id = $kategorija
                    AND datum_objave <= CURDATE()
                    ORDER BY datum_objave DESC
                    LIMIT 5";

                   
                }
                $res = mysqli_query($veza, $sql);
                if(mysqli_num_rows($res) > 0){
                    while($red = mysqli_fetch_array($res)){
                        $id_clanak = $red["id_clanak"];
                        $naslov_clanka = $red["naslov_clanka"];
                        $uvod = $red["uvod"];
                        $datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
                        ?>
                        <a class="row my-2 py-2 hover text-dark text-start btn btn-outline-light shadow-sm" href="clanak.php?id=<?php echo $id_clanak; ?>">
                       
                            <h6><?php echo $naslov_clanka; ?></h6>
                            <p class="text-dark"><?php echo $uvod; ?>...</p>
                           
                            <small class="text-success flex-grow-1"><?php echo $datum_objave; ?></small>
 
                        </a>
                        <?php
                    }
                }
                else{
                    ?>
                    <small class="text-secondary">Nema novih objava.</small>
                    <?php
                }
                ?>
            </div>
            
            <div class="my-5">
                <h3 class="pb-3">Oznake</h3>
                <div class="d-flex flex-wrap">
                    <?php
                    $sql = "SELECT * FROM oznake WHERE aktivno_web = 1";
                    $res = mysqli_query($veza, $sql);
                    while($red = mysqli_fetch_array($res)){
                        $naziv_oznake = $red["naziv_oznake"];
                        $id_oznake = $red["id_oznake"];
                        echo "<a href='pretraga_oznake.php?id_oznake=$id_oznake' class='btn btn-light m-1'>$naziv_oznake</a>";
                    }
                    ?>
                  
                </div>
            </div>
            <div class="my-5">
                <h3 class="pb-3">Istaknuto</h3>
                <?php
                $sql = "SELECT * FROM clanak 
                WHERE tip_teksta = 1
                AND objavljen = 1
                AND istaknuto = 1
                AND datum_objave <= CURDATE()
                ORDER BY datum_objave DESC
                LIMIT 5";
                $res = mysqli_query($veza, $sql);
                if(mysqli_num_rows($res) > 0){
                    while($red = mysqli_fetch_array($res)){
                        $id_clanak = $red["id_clanak"];
                        $naslov_clanka = $red["naslov_clanka"];
                        $uvod = $red["uvod"];
                        $datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
                        ?>
                        <a class="d-flex my-2 py-2 hover text-dark align-items-center" href="clanak.php?id=<?php echo $id_clanak; ?>">
                            
                           
                            <i class="text-success fw-bold me-4 fa fa-angle-right"></i>
                            <div>
                            
                                <h6> <?php echo $naslov_clanka; ?></h6>
                                <p><small><?php echo $uvod; ?></small></p>
                            </div>
                        </a>
                        <?php
                    }
                }
                else{
                    
                }
                ?>
            </div>
        </div>
    </div>
   
</div>

<script>
$(document).ready(function(){
    let kategorija ='';
    $("#load_more").click(function(){
        let posljednji_datum = $("main").find("input[name='posljednji_datum']:last").val();
        let posljednji_clanak = $("main").find("input[name='posljednji_clanak']:last").val();
        let broj_clanaka = $("main").find("input[name='broj_clanaka']:last").val();
        kategorija =  <?php echo $kategorija; ?>
        
        console.log(posljednji_datum);
        console.log(posljednji_clanak);
        console.log(kategorija);
        
        $.ajax({
            url: "dohvati_clanke.php",
            type: "post",
            data: {
                posljednji_clanak:posljednji_clanak,
                posljednji_datum:posljednji_datum,
                kategorija:kategorija,
                
            },
            
            success: function (result) {
                $('main').append(result);
                
                posljednji_datum = $("main").find("input[name='posljednji_datum']:last").val();
                posljednji_clanak = $("main").find("input[name='posljednji_clanak']:last").val();
                broj_clanaka = $("main").find("input[name='broj_clanaka']:last").val();
                
                if(broj_clanaka <= 5){
                    $("#load_more").hide();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
        
    });
});
</script>

<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>

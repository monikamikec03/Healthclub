<?php
include("zaglavlje.php");
include("navigacija_light.php");
function test_input($data) {
	$data = trim($data);
	$data = strip_tags($data); 
	$data = htmlspecialchars($data);
	return $data;
}

?>

    

<div class="container my-5 h-100">
   
            
    <h3 class="m-2">Dojmovi korisnika / recenzije</h3>
	<div class="d-flex flex-wrap mb-3">
		
		<a class="btn btn-outline-warning text-secondary m-2 shadow-sm" href="dojmovi_korisnika.php">Svi dojmovi</a>
		<a class="btn btn-outline-warning text-secondary m-2 shadow-sm" href="dojmovi_bgo.php">Vježbanje <span class="fw-bold">Basic Gym One</span></a>
		<a class="btn btn-warning text-secondary m-2 shadow-sm" href="dojmovi_hc.php">Rehabilitacija <span class="fw-bold">Fizio One</span></a>
		
	</div>
	
    <main class="d-flex flex-wrap">
    <?php


    $sql = "SELECT * FROM clanak, kategorije
    WHERE tip_teksta = 4
    AND objavljen = 1
    AND datum_objave <= CURDATE()
    AND kategorije_id = id_kategorije
	AND id_kategorije = 3
    ORDER BY datum_objave DESC";

    $res = mysqli_query($veza, $sql);
    if(mysqli_num_rows($res) > 0){
        while($red = mysqli_fetch_array($res)){
            $id_clanak = $red['id_clanak'];
            $naslov_clanka = $red['naslov_clanka'];
            $uvod = $red['uvod'];
            $ime_autora = $red['ime_autora'];
            $naziv_kategorije = $red['naziv_kategorije'];
            $tekst = html_entity_decode($red['tekst']);
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
            
            
            ?>
            <div class="d-flex flex-md-row flex-sm-column flex-column m-2 bg-light align-items-center">
                
                    
                
                <div class="d-flex flex-column w-auto p-3">
                    <div class='d-flex justify-content-between flex-wrap'>
                        <h6 class="text-success"> <?php echo "$ime_autora"; ?> <span class='text-dark ms-3'><?php echo " - $naziv_kategorije"; ?></h6>
                        <h6 class="text-success"> <?php echo "$datum_objave"; ?></h6>
                    </div>
                    <h5><?php echo $naslov_clanka; ?> </h5>
                    <p><?php echo $uvod; ?></p>
                    <p><?php echo $tekst; ?></p>
                   
                </div>
                
                <?php
                    if(!empty($putanja)){
                        echo "<img src='$putanja' alt='$naslov_clanka' class=' h-100 col-md-4 object-fit-cover' /> ";
                    }
                    
                ?>
             </div>
            
            <?php
        }
    ?>
    </main>
    
    <?php
        
    }
    
    else{
        echo "<h6>Nema objavljenih recenzija.</h6>";
    }
    ?>

</div>


<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>

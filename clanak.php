
<?php
require("../moj_spoj/otvori_vezu_cmp.php");


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
$putanja = [];
if(isset($_POST["komentiraj"]) && $_POST["komentiraj"] == "Komentiraj"){
    
    if(empty($_POST["naziv"])){
        $naziv = "Anonimno";
    }
    else{
        $naziv = test_input($_POST["naziv"]);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\-_%&\/\,.;: ]*$/",$naslov))  {
            $nazivErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu</p>"; 
        
        }
        else{
            if(contains($naziv)){
                $nazivErr = "<p class='text-danger'>Vaš unos sadrži zabranjene i vulgarne riječi.</p>"; 
            }
        }
    }
    
    if(empty($_POST["komentar"])){
        $komentarErr = "<p class='text-danger'>Niste unijeli Vaš komentar.</p>";
    }
    else{
        $komentar = test_input($_POST["komentar"]);
        if (!preg_match("/^[a-zA-ZćĆčČžŽšŠđĐ0-9?!*+='()\"\n\r\-_%&\/\,.;: ]*$/",$komentar))  {
            $komentarErr = "<p class='text-danger'>Specijalni znakovi neće biti spremljeni u bazu</p>"; 
        }else{
            if(contains($komentar)){
                $komentarErr = "<p class='text-danger'>Vaš unos sadrži zabranjene i vulgarne riječi.</p>"; 
            }
        }
        
    }
    
    if (empty($_POST["id_clanak"])) {
		$id_clanakErr = "<p class='text-danger'>* morate popuniti polje</p>";
	} 
	else {
		$id_clanak = test_input($_POST["id_clanak"]);
		if (!preg_match("/^[0-9 ]*$/",$id_clanak)) { 
			$id_clanakErr = "<p class='text-danger'>* specijalni znakovi ne prolaze</p>"; 
		}
	}
    
    if(empty($komentarErr) && empty($nazivErr) && empty($id_clanakErr)){
        $sql = "INSERT INTO komentari (clanak_id, naziv, komentar, posjetioc_id, aktivan_komentar)
        VALUES('".$id_clanak."', '".$naziv."', '".$komentar."', '".$_COOKIE["id_posjetioca"]."', 1)";
        if(mysqli_query($veza, $sql)){
            header("location:clanak.php?id=$id_clanak");
        }
        else{
             $porukaErr = "<p class='text-danger'>Nismo mogli spremiti Vaš komentar u bazu.</p>"; 
        }
    }
    else{
        $porukaErr = "<p class='text-danger'>Došlo je do pogreške. Pokušajte ponovno.</p>"; 
    }
    
    
    
}


if (isset($_GET["id"])) {								
	$id = test_input($_GET["id"]);
	if (!preg_match("/^[0-9 ]*$/",$id)){
        header("Location: blog.php");
	}
    else{
        $sql = "SELECT * FROM clanak, kategorije
        WHERE objavljen = 1
        AND datum_objave <= CURDATE()
        AND kategorije_id = id_kategorije
        AND id_clanak = $id";
        $res = mysqli_query($veza, $sql);
        if(mysqli_num_rows($res) == 1){
            $red = mysqli_fetch_array($res);
            $id_clanak = $red["id_clanak"];
            $naziv_kategorije = $red["naziv_kategorije"];
            $id_kategorije = $red["id_kategorije"];
            $ime_autora = $red["ime_autora"];
            $naslov_clanka = $red["naslov_clanka"];
            $uvod = $red["uvod"];
            $omoguci_komentare = $red["omoguci_komentare"];
            $tekst = html_entity_decode ($red["tekst"]);
            $datum_objave = date("d.m.Y", strtotime($red["datum_objave"]));
                   
            $sql_img = "SELECT * FROM slike_naslova WHERE clanak_id = $id_clanak
            ORDER BY datum_unosa ASC";
            $res_img = mysqli_query($veza, $sql_img);
            if(mysqli_num_rows($res_img) > 0){
                while($redak = mysqli_fetch_array($res_img)){
                    $putanja[] = $redak["putanja"];
                    $stupac = 4;
                    
                }
            }
            else{
                $stupac = 0;
            }
            
            $sql = "SELECT COUNT(id_lajka) AS broj_lajkova FROM lajkovi WHERE clanak_id = $id_clanak";
            $res = mysqli_query($veza, $sql);
            if(mysqli_num_rows($res) > 0){
                $red = mysqli_fetch_array($res);
                $broj_lajkova = $red["broj_lajkova"];
            }
            else{
                $broj_lajkova = 0;
            }
            
            $sql = "SELECT COUNT(id_komentara) AS broj_komentara FROM komentari WHERE clanak_id = $id_clanak";
            $res = mysqli_query($veza, $sql);
            if(mysqli_num_rows($res) > 0){
                $red = mysqli_fetch_array($res);
                $broj_komentara = $red["broj_komentara"];
            }
            else{
                $broj_komentara = 0;
            }
        }
        else{
            header("Location: blog.php");
        }
    }
}
else{
    //header("Location: blog.php");
}

include("zaglavlje.php");
echo "<img src='$putanja[0]' class='opacity-0 position-fixed' style='z-index:-9999999999'>";
include("navigacija_light.php");
?>



<div class="container flex-grow-1 my-5">
    <div class="d-flex justify-content-end">
        <form class="form-control p-0 m-0 me-1 d-flex align-items-stretch w-auto" method="post" action="pretraga.php">
            <input type="text" name="unos" class="form-control bg-white border-0 shadow-none" placeholder="Traži..." autocomplete="off">
            <input type="submit" value=" &#xf002; " class="btn btn-success fontAwesome" name="trazi">
        </form>
    </div>
    <div class="row">
         
        <div class="col-xl-<?php echo $stupac; ?>">
            <div class="row p-0 m-0">
            <?php
            if(count($putanja) > 0){
                foreach($putanja as $src){
                    echo "<div class='p-2'><img class='col-xl-12 col-lg-6' src='$src' alt='$naslov_clanka'></div>";
                }
            }
            ?>
            </div>
			
			<div class="ss-box ss-outline" data-ss-content="false" data-ss-link="https://healthclub.hr/clanak.php?id=<?php echo $id_clanak; ?>"></div>
        </div>
        
        <div class="col-xl-<?php echo (12-$stupac); ?>">
            <h3 class='p-2'><?php echo $naslov_clanka; ?></h3>
            <h6 class='p-2'><?php echo $uvod; ?></h6>
            <div class="p-2"><?php echo $tekst; ?></div>
			<div class="p-2 d-flex flex-wrap">
				<?php
				$sql = "SELECT * FROM pridruzene_oznake, oznake WHERE clanak_id = $id_clanak
				AND id_oznake = oznaka_id";
				$res = mysqli_query($veza, $sql);
				
				while($red = mysqli_fetch_array($res)){
					$naziv_oznake = $red["naziv_oznake"];
					echo "<p class='fw-bold m-2 text-success'>$naziv_oznake</p>";
				}
				?>
				
			</div>
            <div class="d-flex flex-wrap border-top border-bottom align-items-center">
                <div class="flex-grow-1 d-flex flex-wrap my-3">
              
                    <small class='p-2'><i class="text-success fa-solid fa-feather-pointed"></i> <?php echo $ime_autora; ?></small>
                    <small class='p-2'><a class='link-dark' href="blog.php?id=<?php echo $id_kategorije; ?>"><i class="text-success fa-solid fa-bookmark"></i> <?php echo $naziv_kategorije; ?></a></small>
                    <small class='p-2'><i class="text-success fa-solid fa-calendar-days"></i> <?php echo $datum_objave; ?></small>
                </div>
                
                <div class="flex-grow-1 d-flex flex-wrap justify-content-end my-3">
                
                    <?php
                    $id_lajka = '';
                    $checked = false;
                  
                 
                    $sql = "SELECT * FROM lajkovi 
                    WHERE clanak_id = $id_clanak
                    AND posjetioc_id = " . $_COOKIE["id_posjetioca"];
                    $res = mysqli_query($veza, $sql);
                    if(mysqli_num_rows($res) > 0){
                        $checked = true;
                    }
                    
                  

                    ?>

                    <p class="interactive-icons link-secondary <?php if($checked) echo"link-success"; ?> mx-3 p-2 cursor-pointer lajkaj">
                        <small><?php echo $broj_lajkova; ?></small>
                        <i class=" fa-solid fa-heart"></i>
                    </p>
                    
                    
                    <p class="interactive-icons link-secondary mx-3 p-2 cursor-pointer komentiraj">
                        <small><?php echo $broj_komentara; ?></small>
                        <i class=" fa-solid fa-comment"></i>
                    </p>
                </div>
            </div>
            
            <div class="komentari">
            <?php
            if($omoguci_komentare == 1){
            ?>
                <form class="my-3" action="" method="post">
                    <input type="hidden" name="id_clanak" value="<?php echo $id_clanak; ?>">
                    <input type="text" name="naziv" class="form-control bg-warning border-0" placeholder="Unesite Vaše ime, prezime, nadimak..." pattern="[a-zA-Z0-9-.,!? ]+" onkeydown="return /[a-zA-Z0-9.,!? ]/i.test(event.key)">
                    <?php echo $nazivErr; ?>
                    <textarea rows="6" class="form-control" placeholder="Unesite Vaš komentar" name="komentar"></textarea>
                    <?php echo $komentarErr; ?>
                    <div class="d-flex justify-content-end">
                        <input type="submit" name="komentiraj" class="btn btn-primary" value="Komentiraj">
                    </div>
                    <?php echo $porukaErr; ?>
                </form>
            <?php
            }
            else{
                echo '<p class="text-secondary my-2">Komentiranje nije moguće.</p>';
            }
            
            $sql = "SELECT * FROM komentari
            WHERE clanak_id = $id_clanak
            AND aktivan_komentar = 1
            ORDER BY datum_unosa DESC";
            $res = mysqli_query($veza, $sql);
            while($red = mysqli_fetch_array($res)){
                $id_komentara = $red["id_komentara"];
                $komentator = $red["naziv"];
                $komentar = $red["komentar"];
                $posjetioc_id = $red["posjetioc_id"];
                $datum_unosa = date("d.m.Y. - h:i", strtotime($red["datum_unosa"]));
                ?>
                <div class="my-1 p-3 bg-light">
                    <div class="d-flex flex-wrap align-items-md-center justify-content-between">
                        <h6 class="flex-grow-1 me-3"><?php echo $komentator; ?></h6>
                        <div class="text-end">
                            <p class="me-3"><?php echo $datum_unosa; ?></p>
                            <?php
                            if($posjetioc_id == $_COOKIE["id_posjetioca"]){
                            ?>
                                <a class="me-3" onclick="return confirm('Jeste li sigurni da želite obrisati komentar?')"; href="brisanje_komentara.php?id=<?php echo $id_komentara; ?>">Obriši</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <p class=""><?php echo $komentar; ?></p>
                </div>
                <?php
            }
            ?>
           
            </div>
        </div>
    </div>
   
</div>

<script>
$(document).ready(function(){
    $(".lajkaj").click(function(){
        
        
        let clanak_id = <?php echo $id_clanak; ?>;

        
        $.ajax({
            url: "dropdown.php",
            type: "post",
            data: {
                clanak_id:clanak_id,
                
            },
            success: function (response) {
                $(".komentari").show();
                window.location.reload();
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
        
        
    });
    
    
    $(".komentiraj").click(function(){
        $(".komentari").toggle('fast');
    });
    
    
    
    
    $("input[name='naziv']").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            event.preventDefault();
        }
    });
    
    
});
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
    
    

<?php 
include("drustveneMreze_light.php");
include("podnozje.php");
?>

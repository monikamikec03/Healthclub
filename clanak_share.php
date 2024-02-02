<?php
require("../moj_spoj/otvori_vezu_cmp.php");

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
            ORDER BY datum_unosa ASC LIMIT 1";
            $res_img = mysqli_query($veza, $sql_img);
            if($redak = mysqli_fetch_array($res_img)){
                $putanja = $redak["putanja"];
                   
                    
            }
            

        }
    }
}

?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="icon" type="image/x-icon" href="<?php echo $putanja; ?>">
		<title><?php echo $naslov_clanka; ?></title>
		<meta name="description" content="<?php echo $uvod; ?>">
		<meta name="author" content="<?php echo $autor; ?>">
	</head>
	<body>
		<h1><?php echo $naslov_clanka; ?></h1>
		<h2><?php echo $uvod; ?></h2>
		<p><?php echo $tekst; ?></p>
		<img src="<?php echo $putanja; ?>">
		
		
	</body>
</html>
<?php
header("location:clanak.php?id=$id_clanak");
?>


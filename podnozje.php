<div class="gototop js-top">
    
	<a href="#"><i class="fa fa-arrow-up"></i></a>
	
</div>

<div class="container-fluid bg-warning  py-5">
    <div class="container">
        <div class="row animated fadeIn">
          
           
            <div class="col-12 col-md-4 col-lg-3">
                <h6 class="text-success">Partneri</h6>
           

		
                <div class="d-flex flex-wrap">          
					<a class="m-2 btn btn-outline-light" href="https://basicgymone.com/" target="_blank">
						<img src="slike/basic-gym-one-logo.png" class="logo-slika3" alt="Basic Gym One logo">						
					</a>
				
				
					<a class="m-2 btn btn-outline-light" href="https://mentalnitrening.com/" target="_blank">
						<img src="slike/Mentalni-trening.png" class="logo-slika3" alt="Mentalni trening logo">
						
					</a>
			
					<a class="m-2 btn btn-outline-light" href="https://thenutrivision.com/" target="_blank">
						<img src="slike/nutrivision_logo_horizontal.png" class="logo-slika3" alt="Nutrivision logo">
						
					</a>
					<a class="m-2 btn btn-outline-light" href="https://ftbnutrition.hr/" target="_blank" >
						<img src="slike/ftb-logo.svg" class="logo-slika3" alt="FTB nutrition logo">
						
					</a>
				
					<a class="m-2 btn btn-outline-light" href="https://www.underarmour.com/en-us/" target="_blank">
						<img src="slike/Under_armour_logo_black.png" class="logo-slika3" alt="UnderArmour logo">
						
					</a>

                </div>
            </div>
            <div class="col-12 col-md-3 col-lg-2">
                <h6 class="text-success">Korisni linkovi</h6>
                <ul class="footer_menu">
					
					
					<li><a href="politika_kolacica.php"><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Politika kolačića</a></li>	
					<li><a href="politika_privatnosti.php"><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Politika privatnosti</a></li>	
				
                </ul>
            </div>
            <div class="col-12 col-md-5 col-lg-3 position_footer_relative">
                <h6 class="text-success">Citati</h6>
				<?php
				$sql = "SELECT * FROM clanak
                WHERE tip_teksta = 3
                AND objavljen = 1
                AND datum_objave <= CURDATE()
                ORDER BY datum_objave DESC
                LIMIT 5";
				$res = mysqli_query($veza, $sql);
                if(mysqli_num_rows($res) > 0){
                    while($red = mysqli_fetch_array($res)){
						$id_clanak = $red["id_clanak"];
						$naslov_clanka = $red["naslov_clanka"];
                        $ime_autora = $red["ime_autora"];
                       
						?>
						<a class="mb-3" href="clanak.php?id=<?php echo $id_clanak; ?>">
						
						<q class="text-dark"><?php echo $naslov_clanka; ?></q>
						<span class="small text-success ms-3"><?php echo $ime_autora; ?></span>
						</a>
						<?php
					}
				}
				?>
                
                
            </div>
            <div class="col-12 col-md-12 col-lg-4 ">
                <h6 class="text-success">Gdje se nalazimo</h6>
				<p>Zagrebačka 25a</p>
				<p>Vrbovec</p>
				
				<h6 class="text-success mt-4">Kontakt</h6>
				<p><a class='text-dark' href='mailto:healthclub.vrbovec@gmail.com'>healthclub.vrbovec@gmail.com</a></p>
				<p><a class='text-dark' href='tel:+385989520746'>+385 98 952 0746</a></p>
				
				<a href="hc/prijava.php" class='d-flex align-items-start mt-4'><h6 class="text-dark">Admin <i class="text-success mx-2 fa-solid fa-user"></i></h6></a>
				

            </div>
        </div>
       
    </div>



	
</div>

</body>
</html>
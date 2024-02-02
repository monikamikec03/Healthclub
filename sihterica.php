<?php	

			
	require("../moj_spoj/otvori_vezu_cmp.php");		


	
    setlocale(LC_ALL, 'hr_HR.utf-8');
    $hrvatski_dani = [
        'Mon' => 'Po',
        'Tue' => 'Ut',
        'Wed' => 'Sr',
        'Thu' => 'Če',
        'Fri' => 'Pe',
        'Sat' => 'Su',
        'Sun' => 'Ne'
    ];

    $form_sent = isset($_POST['posalji']);
    $month = intval($form_sent ? $_POST['mjeseci'] : date("m")); 	// poslani mjesec inace trenutni mjesec
    $year = intval($form_sent ? $_POST['godine'] : date("Y"));   	// poslana godina inace trenutna godina
                
    $broj_dana = date('t', mktime(0,0,0,$month, 1, $year));  		// broj dana u mjesecu 
    $days_in_month = cal_days_in_month(0, $month, $year) ;			// isto broj dana u mjesecu 
    $firstDay = mktime(0,0,0,$month, 1, $year);				 		// prvi datum u mjesecu u sekundama
    $title = strftime('%B', $firstDay);         			 		// hrvatski naziv mjeseca 
    $pocetak = date("d.m.Y", mktime(0, 0, 0, $month, 1, $year)); 	// pocetak kalendarski datum
    $poc = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)); 		// pocetak mjeseca za upit bazi
    $end = date('Y-m-d', mktime(0, 0, 0, $month, $days_in_month, $year)); // kraj za upit bazi 	


?>


	<div class='d-flex flex-wrap justify-content-between align-items-center'>			
		
		<h2>Raspored</h2>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class='d-flex flex-wrap align-items-center'>  
								
			<select class="form-control w-auto m-1"  name="godine" >					   		
			<?php foreach(range(date("Y")-10, date("Y")+10) as $value): $selected = $year == $value ? 'selected' : ''; ?>
				<option value="<?=$value?>" <?=$selected?>><?=$value?></option>
			<?php endforeach; ?>  						
			</select>
				
			<select class="form-control w-auto m-1"  name="mjeseci" >	  
				<?php foreach(range(1, 12) as $value): $selected = $month == $value ? 'selected' : ''; ?>
					<option value="<?=$value?>" <?=$selected?>><?=strftime('%B', mktime(0,0,0, $value, 1, 2000))?></option>
				<?php endforeach; ?>
			</select>
			<input class="d-none"  type="submit" name="posalji" value="odaberi">
			
			<input class="form-control my-1 w-auto m-1" id="myInput" type="text" placeholder="traži ...">
			<a href='' id='dd' class="btn btn-success m-1"><i style="font-size:18px" class="fa">&#xf1c3;</i></a>
		</form>	


	</div>

		
    <?php
        $start_date = new DateTime($pocetak);
        $total_days_in_month = $start_date->format('t');
        $date_interval = [];
		
        for($i=1; $i<=$total_days_in_month; $i++) 
		{
            $date_interval[$i] = new DateTime(sprintf('%s.%s.%s', $i, $start_date->format('m'), $start_date->format('Y')));
        }
		
		
		// pocetak obrade drzavni blagdani
		
        $sql = sprintf("SELECT * FROM evidencije, praznici, podgrupe		 
		WHERE podgrupe.id_podgrupe = evidencije.rubrika			
		AND start <= '%s' 
		AND kraj>='%s'", mysqli_real_escape_string($veza, $end), mysqli_real_escape_string($veza, $poc));
		$praznici = array();	
        $rezultat = mysqli_query($veza, $sql);	
        while($red = mysqli_fetch_array($rezultat))
		{				
			$id_podgrupe = $red['rubrika'];			
            $ime = $red['naziv_podgrupe'];	
			$datumPraznika = strtotime($red['datumPraznika']);	
            $start = strtotime($red['start']);				            // pocetak datuma rada
            $kraj = strtotime($red['kraj']);				            // kraj datuma rada
			$day_sati = 0;
            $day_sati_praznici = 0;
			

            if(!isset($praznici[$id_podgrupe]["naziv_podgrupe"])){     
                $praznici[$id_podgrupe]["naziv_podgrupe"] = $ime; 
				$praznici[$id_podgrupe]["ukupno_praznici"] = 0;					
            }
			
			
            for ($i= 1; $i<=$days_in_month; $i+=1)
			{				
                $datum_u_mjesecu = sprintf('%s.%s.%s', $i, $month, $year);
                $datum_u_sekundama = strtotime($datum_u_mjesecu);
                $day = date("D", $datum_u_sekundama); 
				
				$day_sati = (($datum_u_sekundama>=$start and $datum_u_sekundama<=$kraj) ? $red[$day]:0);				
                $day_sati_praznici = (($datum_u_sekundama>=$datumPraznika and $datum_u_sekundama<=$datumPraznika) ? $day_sati:0);	
				
				if($day_sati_praznici) 
				{  					
					if(!isset($praznici[$id_podgrupe][$datum_u_mjesecu]))			     // ako radnik taj dan nema datum u mjesecu
					{  
						$praznici[$id_podgrupe][$datum_u_mjesecu] = array();		         // napravi niz	
					}
					array_push($praznici[$id_podgrupe][$datum_u_mjesecu], $day_sati_praznici);   // dodaje u niz vrijednost na zadnje mjesto

				}	
            }				
			
        }		
	

		// POKUPI RAD RADNIKA I POSLIJE NAPRAVI DISTINCT	
		
        $sql = sprintf("SELECT*FROM evidencije, podgrupe  		
		WHERE podgrupe.id_podgrupe = evidencije.rubrika
		AND start <= '%s' AND kraj>='%s'", $end, $poc);   	       // datum pocetak i kraj mjeseca

        $podgrupe = array();									// varijabla za objekt	       
        $rezultat = mysqli_query($veza, $sql);					
        while($red = mysqli_fetch_array($rezultat)){	
            $id = $red['id_evidencije'];
            $id_podgrupe = $red['rubrika'];				
            $ime = $red['naziv_podgrupe'];	
            
            $start = strtotime($red['start']);				// pocetak datuma rada
            $kraj = strtotime($red['kraj']);				// kraj datuma rada
            $day_sati = 0;	
              
            if(!isset($podgrupe[$id_podgrupe]["naziv_podgrupe"])){  // ako nije postavljeno dali radnik id ima ime
                $podgrupe[$id_podgrupe]["naziv_podgrupe"] = $ime;   // ako ima id_radnika postavi ime kao naziv_podgrupe
			
							
            }					
            for ($i= 1; $i<=$days_in_month; $i+=1){				
                $datum_u_mjesecu = sprintf('%s.%s.%s', $i, $month, $year);
                $datum_u_sekundama = strtotime($datum_u_mjesecu);
                $day = date("D", $datum_u_sekundama);  
                $day_sati = (($datum_u_sekundama>=$start and $datum_u_sekundama<=$kraj) ? ($red[$day]):0);
				
				if($day_sati) {  					
					if(!isset($podgrupe[$id_podgrupe]["$datum_u_mjesecu"])){ 
						$podgrupe[$id_podgrupe]["$datum_u_mjesecu"] = array();	  
					}
					array_push($podgrupe[$id_podgrupe]["$datum_u_mjesecu"], $day_sati); 
				}				
            }
							
        }
			
    ?>      
    <div class='table-responsive sihterica' >
		<table class="table table-sm table-bordered table-hover">   
		<thead>
            <tr> 
                <th rowspan="2">r.br.</th> 			
                <th rowspan="2"></th>  				
                <?php foreach($date_interval as $date): ?>
                <th><?=$date->format('d')?></th>
                <?php endforeach; ?>
                <th rowspan="2">ukupno sati</th> 			
                <tr>
                    <?php foreach($date_interval as $date): ?>
                    <th><?=$hrvatski_dani[$date->format('D')]?></th>
                    <?php endforeach; ?>							
                </tr>			
            </tr>
		</thead>
        <tbody id='myTable'>      
            <?php 
						

		$br = 1;
        $sql = "SELECT distinct(rubrika) FROM evidencije, podgrupe 
		WHERE podgrupe.id_podgrupe = evidencije.rubrika 
		AND start <= '$end' AND kraj>='$poc'";	
								
        $rezultat = mysqli_query($veza, $sql);		 	 
        while($red = mysqli_fetch_assoc($rezultat)){
			$id_podgrupe = $red['rubrika'];
            $ime = $podgrupe[$id_podgrupe]['naziv_podgrupe'];  
			$podgrupe[$id_podgrupe]["ukupno_sati"] = 0;		
			$suma_sati = 0 ;
						
				
            echo "<tr>";
			echo "<td align='center'> $br </td>";	$br++; 					
            echo "<td><b>$ime </b></td>";  
        
                for ($i= 1; $i<=$days_in_month; $i+=1){			
                    $datum_u_mjesecu = sprintf('%s.%s.%s', $i, $month, $year);				
                    $datum_u_sekundama = strtotime($datum_u_mjesecu);
                    $day = date("D", $datum_u_sekundama); 
					
					$dohvati_vrijeme = "SELECT * FROM evidencije WHERE rubrika = $id_podgrupe";
					$query = mysqli_query($veza, $dohvati_vrijeme);
					while($redak = mysqli_fetch_array($query)){
						$stupac1 = $day . "1";
						$stupac2 = $day . "2";
						
						$od = date("H:i", strtotime($redak[$stupac1]));
						$do = date("H:i", strtotime($redak[$stupac2]));
						if($od == "00:00") $od = '';
						if($do == "00:00") $do = '';
						if(!empty($od) && !empty($do)){
							$vrijeme = "$od-$do";
						}
						else{
							$vrijeme = '';
						}
						
					}
																		
					
						$suma_sati_izostanci = 0;
						if(isset($izostanci[$id_podgrupe][$datum_u_mjesecu])){
							$suma_sati_izostanci = array_sum($izostanci[$id_podgrupe][$datum_u_mjesecu]);
							$izostanci[$id_podgrupe]["ukupno_izostanci"] += $suma_sati_izostanci;							
						}	
						if($suma_sati_izostanci == 0)
						{
							$suma_sati_izostanci = "";
						}	  					
																
									
						$suma_sati_praznici = 0;
						if(isset($praznici[$id_podgrupe][$datum_u_mjesecu])){
							$suma_sati_praznici = array_sum($praznici[$id_podgrupe][$datum_u_mjesecu]);	
							$praznici[$id_podgrupe]["ukupno_praznici"] += $suma_sati_praznici;					
						}						
	
						if($suma_sati_praznici == 0)
						{
							$suma_sati_praznici = "";
						}					
																									
											
					
									

					
					 if(!empty($suma_sati_praznici))
					{
						$color_silver = 'class="bg bg-danger bg-opacity-25"' ;
					}					
									
					else if($day == 'Sun'){
						$color_silver = 'class="bg bg-secondary bg-opacity-25"';
					}
					else
					{
						$color_silver = "";
					}	
					
				// ukupni sati podgrupe
				if(!empty($podgrupe[$red['rubrika']][$datum_u_mjesecu])){
					$suma_sati = array_sum($podgrupe[$red['rubrika']][$datum_u_mjesecu]);
					$ukupno_sati = $podgrupe[$id_podgrupe]["ukupno_sati"] += $suma_sati;  
				}

			

				
						
				echo "<td {$color_silver}>";  						
					//echo $suma_sati = ($suma_sati == 0) ? "" : $suma_sati;
					echo $vrijeme;
						
				echo "</td>";						
                }  
				
                echo "<td class='bg bg-primary bg-opacity-25 fs-6'>", $ukupno_sati ,"</td>";			
				
				
			
				$ukupno_sati_praznici = 0;
				if(isset($praznici[$id_podgrupe])){
					$ukupno_sati_praznici1 = ($praznici[$id_podgrupe]["ukupno_praznici"])-($praznici_izostanci[$id_podgrupe]["ukupno_praznici_izostanci"]);
				}
					
				
            echo "</tr>";				
		}		
        ?> 
			</tbody>		
        </table>
				
    </div>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
	var value = $(this).val().toLowerCase();
	$("#myTable tr").filter(function() {
	  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
  });
});
</script>
<script>
	var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";

	var blob = new Blob([html], { type: "application/vnd.ms-excel" });
	var a = document.getElementById("dd");
		a.href = URL.createObjectURL(blob);
		a.download = "hc_raspored.xls";
</script>

	<script>
	$("select[name='godine'], select[name='mjeseci']").on("change", function(){
		$("input[name='posalji']").click();
	});
	</script>
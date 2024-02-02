<?php

	$ip = getenv('HTTP_CLIENT_IP')?:
		  getenv('HTTP_X_FORWARDED_FOR')?:
		  getenv('HTTP_X_FORWARDED')?:
		  getenv('HTTP_FORWARDED_FOR')?:
		  getenv('HTTP_FORWARDED')?:
		  getenv('REMOTE_ADDR');
	
	    $ip = substr($ip, 7);
	
		if(empty($ip)){
				header("Location:brava.php");
			}
			
	$sql = "SELECT*FROM failed_login 
			WHERE ip_address = '$ip' order by date desc Limit 1";
				require("../include/var.php");			
				require("../include/putanja.php");
			$rezultat = mysqli_query($veza, $sql);
			if(mysqli_num_rows($rezultat) == 1)
			{
				$korisnik = mysqli_fetch_assoc($rezultat);
				$id = $korisnik["ip_address"];
				$date = strtotime($korisnik["date"]);	
				$date = strtotime("+1 day", $date);
			}
			$day = date("M d, Y  H:i:s", $date);
			if (empty($id)){
				$poruka_1 = "";
				$poruka_2 = "";
				$poruka_3 = "";
			}
			else
			{
				$poruka_1 = "<div class='d-flex align-items-center flex-column justify-content-center'>
					<p>Vaša IP adresa:</p> 
					<h1 class='text-primary'>$id</h1>
				</div>";
				$poruka_2 = "<p class='my-3 text-center'>Zbog 5 neuspjelih pokušaja zaključana je još:<h1 class='text-primary' id='demo'></h1></p>";
				$poruka_3 = "<p class='border-top my-2 py-2 text-center'>Kontaktirajte svog administratora.</p>
				<div class='d-flex justify-content-center'><a class='btn btn-success' href='../index.php'>Povratak na web stranicu</a></div>	";	
			}
	include("zaglavlje.php");
	
	if(empty($id)) header("location:prijava.php");
?>	

<div class='h-100 flex-grow-1 bg bg-light d-flex justify-content-center align-items-center'>
	<div class='bg bg-white shadow-sm p-5'>
		<?php echo $poruka_1; ?>
		<?php echo $poruka_2; ?>		
		<?php echo $poruka_3; ?>			
	</div>

	 <script>
		var countDownDate = new Date('<?php echo $day; ?>').getTime();
		var x = setInterval(function() {
			var now = new Date().getTime();
			var distance = countDownDate - now;
			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);
			
			document.getElementById("demo").innerHTML = hours + "h "
		  + minutes + "m " + seconds + "s ";       

			  if (distance < 0) {
				clearInterval(x);
				document.getElementById("demo").innerHTML = "Nemate neuspjeli pokušaja";
			  }
		}, 1000);
	 </script>

</div>
</body>
</html>
<?php	
ob_start();
session_start();
require("../include/putanja.php");
require("zaglavlje.php");
require("navigacija.php");

function test_input($data) {
  $data = trim($data);
  $data = strip_tags($data); 
  $data = htmlspecialchars($data);
return $data;}

if(isset($_FILES['files'])){ 


		$obrt = test_input($_POST["obrt"]);
		if (!preg_match("/^[0-9? ]*$/",$obrt))  
		{
			$obrtErr = "<p class='alert alert-danger'>* specijalni znakovi neće biti spremljeni u bazu</p>"; 
		}
	
	
    $targetDir = "../ckeditor_uploads/"; 
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp', 'gif', 'svg', 'JPG', 'PNG', 'JPEG', 'GIF', 'WEBP', 'GIF', 'SVG'); 
     
    $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
	if(empty($obrtErr)){
		if(!empty($fileNames)){ 
			foreach($_FILES['files']['name'] as $key => $val){ 
		   
				$fileName = basename($_FILES['files']['name'][$key]); 
				$targetFilePath = $targetDir . date("ymd") . date("his") . $fileName ; 
				 
				
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
				if(in_array($fileType, $allowTypes)){ 
					
					if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
						$sql = "INSERT INTO slike(putanja, ukljuceno_galerija, obrt_id)
						VALUES ('$targetFilePath', '0', '$obrt')";
						if(mysqli_query($veza, $sql)){
							header("location:slike.php");
						}
					}
					else{ 
						$errorUpload .= $_FILES['files']['name'][$key].' | '; 
					}
					
				}
				else{ 
					$errorUploadType .= $_FILES['files']['name'][$key].' | '; 
				} 
			} 
			 
		}
	}
	
} 
?>

<p class='text-danger'><?php echo $errorUpload; ?></p>
<p class='text-danger'><?php echo $errorUploadType; ?></p>

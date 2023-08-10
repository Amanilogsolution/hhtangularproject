<?php
    ini_set("display_errors", 1);
	require './vendor/autoload.php';
    use \Firebase\JWT\JWT;
	
    header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header('Content-Type: multipart/form-data');
	header('Accept:*/*');
	header('Accept-Encoding: gzip, deflate, br');
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
 	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, authorization, X-Requested-With");
	
    if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		if(!empty($_FILES['image']['name']) && !empty($_REQUEST['folderName']))
		{   
				$responceArray = array();
				$valid_ext = array('png','jpeg','jpg');
				$photoExt1 = @end(explode('.', $_FILES['image']['name'])); // explode the image name to get the extension
				$phototest1 = strtolower($photoExt1);
				$new_profle_pic = time().'.'.$phototest1;
				$location = "./images/".$new_profle_pic;
				$file_extension = pathinfo($new_profle_pic, PATHINFO_EXTENSION);
				$file_extension = strtolower($file_extension);
				if(in_array($file_extension,$valid_ext))
				{
					$accesskey = "bNmQ4TYYDrTwGyeBlUDuDIjOUeUpn8QaVrorfjox9BXoUU9G7u5ZL7RsS2Rsn2tMBk+tsi/kcWI+7JhLp+HppA==";
					$storageAccount = 'swimlocker';
					$containerName = 'wmslocker';
					
				    $compress_image = compressedImage($_FILES['image']['tmp_name'],$location,12);
					$filetoUpload = realpath('images/'.$new_profle_pic);
					$blobName = $_REQUEST['folderName']."/".$new_profle_pic;
					$destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
				function uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey)
				{
					$currentDate = gmdate("D, d M Y H:i:s T", time());
					$handle = fopen($filetoUpload, "r");
					$fileLen = filesize($filetoUpload);

					$headerResource = "x-ms-blob-cache-control:max-age=3600\nx-ms-blob-type:BlockBlob\nx-ms-date:$currentDate\nx-ms-version:2015-12-11";
					$urlResource = "/$storageAccount/$containerName/$blobName";
			
					$arraysign = array();
					$arraysign[] = 'PUT';               /*HTTP Verb*/  
					$arraysign[] = '';                  /*Content-Encoding*/  
					$arraysign[] = '';                  /*Content-Language*/  
					$arraysign[] = $fileLen;            /*Content-Length (include value when zero)*/  
					$arraysign[] = '';                  /*Content-MD5*/  
					$arraysign[] = 'image/png';         /*Content-Type*/  
					$arraysign[] = '';                  /*Date*/  
					$arraysign[] = '';                  /*If-Modified-Since */  
					$arraysign[] = '';                  /*If-Match*/  
					$arraysign[] = '';                  /*If-None-Match*/  
					$arraysign[] = '';                  /*If-Unmodified-Since*/  
					$arraysign[] = '';                  /*Range*/  
					$arraysign[] = $headerResource;     /*CanonicalizedHeaders*/
					$arraysign[] = $urlResource;        /*CanonicalizedResource*/
				
					$str2sign = implode("\n", $arraysign);
				
					$sig = base64_encode(hash_hmac('sha256', urldecode(utf8_encode($str2sign)), base64_decode($accesskey), true));  
					$authHeader = "SharedKey $storageAccount:$sig";
				
					$headers = [
						'authorization: ' . $authHeader,
						'x-ms-blob-cache-control: max-age=3600',
						'x-ms-blob-type: BlockBlob',
						'x-ms-date: ' . $currentDate,
						'x-ms-version: 2015-12-11',
						'Content-Type: image/png',
						'Content-Length: ' . $fileLen
					];
				
					$ch = curl_init($destinationURL);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
					curl_setopt($ch, CURLOPT_INFILE, $handle); 
					curl_setopt($ch, CURLOPT_INFILESIZE, $fileLen); 
					curl_setopt($ch, CURLOPT_UPLOAD, true); 
					$result = curl_exec($ch);
					if($result==true){
						http_response_code(404);
						echo json_encode(array(
							"status" => 0,
							"message" => "Image uploading Failed successfully"
						));
					}else{
						@unlink($filetoUpload);
						http_response_code(200);
						echo json_encode(array(
							"status" => 1,
							"message" => "Image uploaded successfully"
						));
					}
					//echo ('Result<br/>');
					//print_r($result);
					//
					//echo ('Error<br/>');
					//print_r(curl_error($ch));
					//
					//curl_close($ch);
				}
				uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey);
			}else{
				echo 'Image not saved! Please check image type';
			}
		}else{
			http_response_code(404);
				echo json_encode(array(
					"status" => 0,
					"message" => "Please enter all inputs"
				));
		}
}	
		
		function compressedImage($source, $path, $quality) 
		{
			$info = getimagesize($source);
			if ($info['mime'] == 'image/jpeg') 
				$image = imagecreatefromjpeg($source);
			elseif ($info['mime'] == 'image/gif') 
				$image = imagecreatefromgif($source);
			elseif ($info['mime'] == 'image/png') 
				$image = imagecreatefrompng($source);
			imagejpeg($image, $path, $quality);
		}
    
?>
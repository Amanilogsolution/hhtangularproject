<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$fileUploadObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$fileUploadInputs = json_decode(file_get_contents("php://input"));
		if(!empty($fileUploadInputs->locationId))
		{
			$headers = getallheaders();
			$jwt = $headers["authorization"];
			$token = explode(" ", $jwt)[1];
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$findAsnData = $fileUploadObj->uploadFile($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey);
				$findAsnDataArray["records"] = array();
				while($row = sqlsrv_fetch_array($findAsnData, SQLSRV_FETCH_ASSOC))
				{
					$findAsnArra= array(
						"asnno" 	 => $row['asnno'],
						"invoiceno"	 => $row['invoiceno'],
						"client" 	 => $row['custid'],
					);
					array_push($findAsnDataArray["records"], $findAsnArra);
				}
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"asnlist" => $findAsnDataArray
				));

			}catch(Exception $ex){
			http_response_code(500); // no data found
			echo json_encode(array(
				"status" => 0,
				"message" => $ex->getMessage()
			));
			}
		}else{
			http_response_code(404);
			echo json_encode(array(
				"status" => 0,
				"message" => "Please Send Location Id"
			));
		}
	}
?>
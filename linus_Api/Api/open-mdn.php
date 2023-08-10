<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$openMdnObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$openMdnInputs = json_decode(file_get_contents("php://input"));
		if(!empty($openMdnInputs->locationId))
		{
			$headers = getallheaders();
			$jwt = $headers["authorization"];
			$token = explode(" ", $jwt)[1];
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$findMdnData = $openMdnObj->openMdn($openMdnInputs->locationId,$records_per_page, $numer_of_records);
				$findMdnDataArray["records"] = array();
				while($row = sqlsrv_fetch_array($findMdnData, SQLSRV_FETCH_ASSOC))
				{
					$findMdnArra= array(
						"mdnno" 	 => $row['mdnno'],
						"invoiceno"	 => $row['invoiceno'],
						"client" 	 => $row['custid'],
					);
					array_push($findMdnDataArray["records"], $findMdnArra);
				}
				$findMdnDataArray["numberOfPage"]=$records_per_page;
				$findMdnDataArray["numberOfRecords"]=$openMdnObj->mdnListCount($openMdnInputs->locationId);
				
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"mdnlist" => $findMdnDataArray
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
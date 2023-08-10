<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	header("Access-Control-Allow-Origin: http://localhost/swim/Api/Open-as.php");
	header('Access-Control-Allow-Credentials: true');
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, authorization, X-Requested-With");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$openAsnObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$openAsnInputs = json_decode(file_get_contents("php://input"));
		if(!empty($openAsnInputs->locationId))
		{
			$headers = getallheaders();
			$jwt = $headers["authorization"];
			$token = explode(" ", $jwt)[1];
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$findAsnData = $openAsnObj->openAsn($openAsnInputs->locationId,$records_per_page, $numer_of_records);
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
				$findAsnDataArray["numberOfPage"]=$records_per_page;
				$findAsnDataArray["numberOfRecords"]=$openAsnObj->asnListCount($openAsnInputs->locationId);
				
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
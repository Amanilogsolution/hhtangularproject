<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$findMdnObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$findMdnInputs = json_decode(file_get_contents("php://input"));
		if(!empty($findMdnInputs->locationId) && !empty($findMdnInputs->mdnno))
		{
			$headers = getallheaders();
			$jwt = $headers["authorization"];
			$token = explode(" ", $jwt)[1];
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$findMdnData = $findMdnObj->findMdn($findMdnInputs->locationId,$findMdnInputs->mdnno,$records_per_page, $numer_of_records);
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
				$findMdnDataArray["numberOfRecords"]=$findMdnObj->findMdnListCount($findMdnInputs->locationId,$findMdnInputs->mdnno);
				
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
				"message" => "Please enter all inputs"
			));
		}
	}	
?>	
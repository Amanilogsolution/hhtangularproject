<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$aislesDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$cycleCount = json_decode(file_get_contents("php://input"));
		if(!empty($cycleCount->wh) && !empty($cycleCount->Custid) && !empty($cycleCount->Batchid)){
			try{
				 $decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records = 10;

                $aislesDataArray = array();
				$aislesDataArray["records"]=array();
				$aislesDataArray["numberOfPage"]=array();
				$aislesDataArray["numberOfRecords"]=array();
                $aislesData = $aislesDataObj->getAisles($cycleCount->wh, $cycleCount->Custid, $cycleCount->Batchid, $records_per_page, $numer_of_records);
                while($row = sqlsrv_fetch_array($aislesData, SQLSRV_FETCH_ASSOC))
				{
					$aislesDetails =  array(
						 "Aisles"     	 => $row['Aisles'],
                         "Total_Bin"  => $row['Total_Bin']
					);
					array_push($aislesDataArray["records"], $aislesDetails);
				}
				$aislesDataArray["numberOfPage"]=$records_per_page;
				$aislesDataArray["numberOfRecords"]=$aislesDataObj->getAislesCount($cycleCount->wh, $cycleCount->Custid, $cycleCount->Batchid);
				
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
                    "Aisles" => $aislesDataArray
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
				"message" => "All Input needed"
			));
		}
	}	
?>	
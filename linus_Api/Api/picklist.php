<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$pickListObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$pickListInputs = json_decode(file_get_contents("php://input"));
		if(!empty($pickListInputs->locationId))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$pickListData = $pickListObj->getPickListData($pickListInputs->locationId, $records_per_page, $numer_of_records);
				$pickListArray = array();
				$pickListArray["records"]=array();
				$pickListArray["numberOfPage"]=array();
				$pickListArray["numberOfRecords"]=array();
				$rowcount = sqlsrv_num_rows($pickListData);
				while($row = sqlsrv_fetch_array($pickListData, SQLSRV_FETCH_ASSOC))
				{
					$pickListArra= array(
						"dnno" 		 => $row['dnno'],
						"custid" 	 => $row['custid'],
						"custinvno"  => $row['custinvno'],
						"InvQty"	 => $row['InvQty'],
						"PickQTy"    => $row['PickQTy']
					);
					array_push($pickListArray["records"], $pickListArra);
				}
				$pickListArray["numberOfPage"]=$records_per_page;
				$pickListArray["numberOfRecords"]=$pickListObj->pickListCount($pickListInputs->locationId);
				http_response_code(200); // Ok
				echo json_encode(array(
				"status" => 1,
				"picklist" => $pickListArray
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
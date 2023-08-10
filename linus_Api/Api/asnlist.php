<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$asnObj = new Api;
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		
		if(!is_null($_GET['whid']))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$records_per_page = isset($_GET['pageNumber']) ? $_GET['pageNumber'] : 1;
				$numer_of_records = isset($_GET['perPage']) ? $_GET['perPage'] : 1;
				$asnData = $asnObj->Asn($_GET['term'], $_GET['whid'], $records_per_page, $numer_of_records);
				$asnArray = array();
				$asnArray["records"]=array();
				$asnArray["numberOfPage"]=array();
				$asnArray["numberOfRecords"]=array();
				while($row = sqlsrv_fetch_array($asnData, SQLSRV_FETCH_ASSOC))
				{
					$asnArra= array(
						"totalSkuCode" 	=> $row['TOTSKUCODE'],
						 //"totalSkuCode" 	=> $row['TOTSKUCODEV'],
						"invoiceNo" 	=> $row['InvoiceNO'],
						"poNo" 	 		=> $row['PONO'],
						"asnNo"  		=> $row['ASN_NO'],
						"custId"	 	=> $row['CustID'],
						"wh"    		=> $row['WH'],
						"asnQty"    	=> $row['ASNQTY'],
						"scanQty"    	=> $row['ScanQty'],
					);
					array_push($asnArray["records"], $asnArra);
				}
				$asnArray["numberOfPage"]=$records_per_page;
				$asnArray["numberOfRecords"]= $asnObj->AsnCount($_GET['term'], $_GET['whid']);
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"asnlist" => $asnArray
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
				"message" => "Please Send wh id"
			));
		}
	}	
?>	
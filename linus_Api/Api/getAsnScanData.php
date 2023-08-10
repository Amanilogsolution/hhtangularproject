<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$asnScanDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$asnScanDataInputs = json_decode(file_get_contents("php://input"));
		if(!empty($asnScanDataInputs->wh) && !empty($asnScanDataInputs->BoxNo)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =10;

				$asnScanData = $asnScanDataObj->getAsnScanData($asnScanDataInputs->wh, $asnScanDataInputs->BoxNo);
               
				$asnScanDataArray = array();
				$asnScanDataArray["records"]=array();
				while($row = sqlsrv_fetch_array($asnScanData, SQLSRV_FETCH_ASSOC))
				{
					$scanBoxsDetails =  array(
						"Wh" 			    => $row['Wh'],
                        "custid" 			=> $row['custid'],
                        "Custinv" 			=> $row['Custinv'],
                        "ASN_no" 			=> $row['ASN_no'],
                        "TotalBoxQty" 		=> $row['TotalBoxQty'],
                        "ScanQTy" 			=> $row['ScanQTy'],
                        "BoxCount" 			=> $row['BoxCount'],
                        "SKUCount" 			=> $row['SKUCount']
						
					);
					array_push($asnScanDataArray["records"], $scanBoxsDetails);
				}
                $asnScanDataTableArray = array();
				$asnScanDataTableArray["records"]=array();
                $asnScanDataTableArray["numberOfPage"]=array();
				$asnScanDataTableArray["numberOfRecords"]=array();
				$asnScanTableData = $asnScanDataObj->AsnScanTableData($asnScanDataInputs->wh, $asnScanDataInputs->BoxNo,  $records_per_page, $numer_of_records);
                while($row = sqlsrv_fetch_array($asnScanTableData, SQLSRV_FETCH_ASSOC))
				{
					$scanBoxsDetails =  array(
                        "SKU" 			    => $row['SKU'],
                        "TotalBoxQty" 		=> $row['TotalBoxQty'],
                        "ScanQTy" 			=> $row['ScanQTy']
					);
					array_push($asnScanDataTableArray["records"], $scanBoxsDetails);
				}

                $asnScanDataTableArray["numberOfPage"]=$records_per_page;
				$asnScanDataTableArray["numberOfRecords"]=$asnScanDataObj->AsnScanTableCount($asnScanDataInputs->wh, $asnScanDataInputs->BoxNo);
				
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"getAsnScanData"        => $asnScanDataArray,
                    "getAsnScanTableData"   => $asnScanDataTableArray
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
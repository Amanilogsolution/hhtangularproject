<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$asnScanSkuObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$asnScanSkuInputs = json_decode(file_get_contents("php://input"));
		if(!empty($asnScanSkuInputs->asnNo) && !empty($asnScanSkuInputs->WHLocation) && !empty($asnScanSkuInputs->sku) && !empty($asnScanSkuInputs->custId) && !empty($asnScanSkuInputs->invoiceNo) && !empty($asnScanSkuInputs->qty) && !empty($asnScanSkuInputs->scannedQty) && !empty($asnScanSkuInputs->whid))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$asnSkuData = $asnScanSkuObj->InsertUpdateupdateASNScanSku($asnScanSkuInputs->asnNo, $asnScanSkuInputs->WHLocation, $asnScanSkuInputs->sku, $asnScanSkuInputs->custId, $asnScanSkuInputs->invoiceNo, $asnScanSkuInputs->qty, $asnScanSkuInputs->scannedQty, $decoded_data->data->name, $asnScanSkuInputs->whid);
				http_response_code(200); // Ok
				echo json_encode($asnSkuData);
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
				"message" => "Required parameters missing"
			));
		}
	}	
?>	
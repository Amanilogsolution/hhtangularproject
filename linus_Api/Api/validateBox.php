<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$boxObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "GET")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		if(!empty($_GET['asnNo']) && !empty($_GET['WHLocation']) && !empty($_GET['whid']))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$boxNumData = $boxObj->BoxValidation($_GET['asnNo'], $_GET['WHLocation'], $_GET['whid']);
				http_response_code(200); // Ok
				echo json_encode($boxNumData);
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
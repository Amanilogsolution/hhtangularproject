<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php'; 
	use \Firebase\JWT\JWT;
	header("Access-Control-Allow-Origin: http://localhost/swim/list.php");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	include_once("./classes/api_class.php");
	$user_obj = new Api;

	if($_SERVER['REQUEST_METHOD'] === "POST"){
		//$data = json_decode(file_get_contents("php://input"));
		$all_headers = getallheaders();
		$data->jwt = $all_headers['Authorization'];
		if(!empty($data->jwt)){
			try{
				$secret_key = "owt125";
				$decoded_data = JWT::decode($data->jwt, $secret_key, array('HS512'));
				http_response_code(200);
				$user_id = $decoded_data->data->id;
				echo json_encode(array(
					"status" => 1,
					"message" => "We got JWT Token",
					"user_data" => $decoded_data,
					"user_id" => $user_id
				));
			}catch(Exception $ex){
				http_response_code(500); // server error
				echo json_encode(array(
					"status" => 0,
					"message" =>  $ex->getMessage()
				));
			}
		}
	}
 ?>

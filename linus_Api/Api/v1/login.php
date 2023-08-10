<?php
	ini_set("display_errors", 1);
	// include vendor
	require '../vendor/autoload.php';
	use \Firebase\JWT\JWT;
	//include headers
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");
	header("Content-type: application/json; charset=utf-8");
	// including files
	include_once("../classes/api_class.php");
	//objects
	$user_obj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$data = json_decode(file_get_contents("php://input"));
		if(!empty($data->userId) && !empty($data->password)){
			$user_obj->UID = $data->userId;
			//$user_obj->password = $data->password;
			$user_data = $user_obj->check_login();
			
			if(!empty($user_data)){
				$name = $user_data['uName'];
				$password = $user_data['UPWD'];	
				$pwMatchQuery = "select UID,UPWD,UACTIVE from User_Rights WHERE uActive='1' order by id desc";
				$pwMatch = sqlsrv_query($user_obj->myconn, $pwMatchQuery);
				$pw = sqlsrv_fetch_array($pwMatch, SQLSRV_FETCH_ASSOC);
			if($pw['UPWD']==$data->password && $pw['UID']==$data->userId)
				{ 
					// normal password, hashed password
					$iss = "HARIOM";
					$iat = time();
					$nbf = $iat + 10;
					$exp = $iat + 180;
					$aud = "User_Rights";
					$user_arr_data = array(
						"id" => $user_data['UID'],
						"uDept" => $user_data['uDept'],
						"name" => $user_data['uName']
					);
					
					$secret_key = "owt125";
					$payload_info = array(
						"iss"=> $iss,
						"iat"=> $iat,
						"nbf"=> $nbf,
						"exp"=> $exp,
						"aud"=> $aud,
						"data"=> $user_arr_data
					);
					$jwt = JWT::encode($payload_info, $secret_key, 'HS512');
					http_response_code(200);
					echo json_encode(array(
						"status" => true,
						"token" 	 => $jwt,
						"userName"   => $user_arr_data['name'],
						"warehouseLocation"=>$user_arr_data['uDept'],
						"message" => "User logged in successfully"
					));
				}else{
					http_response_code(404);
					echo json_encode(array(
						"status" => 0,
						"message" => "Invalid credentials"
					));
				}
			}else{
				http_response_code(404);
				echo json_encode(array(
					"status" => 0,
					"message" => "Invalid credentials"
				));
			}
		}else{
			http_response_code(404);
			echo json_encode(array(
				"status" => 0,
				"message" => "All data needed"
			));
		}
	}

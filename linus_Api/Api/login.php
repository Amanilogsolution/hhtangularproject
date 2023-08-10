<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$user_obj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$data = json_decode(file_get_contents("php://input"));
		if(!empty($data->userId) && !empty($data->password)){
			$user_data = $user_obj->check_login($data->userId, $data->password);
			$loginHistory = $user_obj->login_history($data->userId, $data->password);
			if(!empty($user_data)){
				if($user_data['UPWD']==$data->password && $user_data['UID']==$data->userId)
				{
					$iss = "34.95.37.168"; // this can be the servername
					$iat = time();  // token issued at
					$nbf = $iat ; //token not before in 0 seconds
					$exp = $iat + 2629746; //expire time token in 2629746 seconds  2592000
					$aud = "User_Rights";
					$user_arr_data = array(
						"id" => $user_data['ID'],
						"uid" => $user_data['UID'],
						"uDept" => $user_data['uDept'],
						"name" => $user_data['uName'],
						"uWH"  =>$user_data['uWH'],
						"WHID"  =>$user_data['WHID'],
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
					$wearhouse = array();
					if($user_arr_data['uDept']!=4){
						$wearhouseObject = ((object)[
							"WHID" =>$user_arr_data['WHID'],
							"WHNAME" => $user_arr_data['uWH']
						]);
						array_push($wearhouse, $wearhouseObject);
					}else{
						$wearhouseRecords = $user_obj->getWearHouse();
						while( $row = sqlsrv_fetch_array($wearhouseRecords, SQLSRV_FETCH_ASSOC)){
							$wearhouseObject = ((object)[
								"WHID" =>$row['WHID'],
								"WHNAME" => $row['WHNAME']
							]);
							array_push($wearhouse, $wearhouseObject);
						}	
					}
					echo json_encode(array(
						"status" => true,
						"token" 	 => $jwt,
						"expireAt" => $exp,
						"userName"   => $user_arr_data['name'],
						"warehouseLocation"=>$wearhouse,
						"message" => "User logged in successfully"
					));
				}else{
					http_response_code(401);
					echo json_encode(array(
						"status" => 0,
						"message" => "Invalid credentials"
					));
				}
			}else{
				http_response_code(401);
				echo json_encode(array(
					"status" => 0,
					"message" => "Invalid credentials"
				));
			}
		}else{
			http_response_code(401);
			echo json_encode(array(
				"status" => 0,
				"message" => "All data needed"
			));
		}
	}

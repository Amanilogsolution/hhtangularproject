<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	
	use \Firebase\JWT\JWT;
	header("Access-Control-Allow-Origin: http://localhost/swim/");
	header('Access-Control-Allow-Credentials: true');
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, authorization, X-Requested-With");
	include_once("./classes/api_class.php");
	
	$secret_key = "owt125";
	$jwt = null;
	$user_obj = new Api;
	

	if($_SERVER['REQUEST_METHOD'] === "POST"){
    $headers = getallheaders();
    $jwt = $headers["authorization"];
	$b = explode(" ", $jwt)[1];
	// print_r($b[1]);exit;
    try{
      
	  $decoded_data = JWT::decode($b, $secret_key, array('HS512'));
	  print_r($decoded_data);exit;
		$user_data = $user_obj->user_list();
		$user_obj->user_id = $decoded_data->data->id;

      $projects = $user_obj->user_list();

      if($projects->num_rows > 0){

        $projects_arr = array();

        while($row = $projects->fetch_assoc()){

           $projects_arr[] = array(
             "id" => $row['id'],
             "name" => $row["name"],
             "description" => $row['description'],
             "user_id" => $row["user_id"],
             "status" => $row["status"],
             "created_at" => $row["created_at"]
           );
        }

         http_response_code(200); // Ok
         echo json_encode(array(
           "status" => 1,
           "projects" => $projects_arr
         ));

      }else{
         http_response_code(404); // no data found
         echo json_encode(array(
           "status" => 0,
           "message" => "No Projects found"
         ));

      }
    }catch(Exception $ex){
      http_response_code(500); // no data found
      echo json_encode(array(
        "status" => 0,
        "message" => $ex->getMessage()
      ));
    }

}
?>	
<?php
	include("classes/user_class.php");
	$Obj = new User(); 
	error_reporting(0);
	$array = array();
	$sql = mysqli_query($Obj->myconn, "SELECT * FROM track WHERE id='".$_POST['pin_id']."'");
	if(mysqli_num_rows($sql)>0)
        {
			while($pinService = mysqli_fetch_array($sql))
			{
				$pinObject = ((object)[
							"Area"=>$pinService['are'],
							"DistrictName"=>$pinService['distriname'],
							"State"=>$pinService['state'],
							"PickupDeliveryService"=>$pinService['pickdilv'],
							"PaymentService"=>$pinService['peyservic'],
							"AreaPin"=>$pinService['arepin'],
						]);
				array_push($array,$pinObject);
			}
			echo $json = json_encode($array);
	    }else{
			echo $json = json_encode("No Data Available");
		}

?> 
	

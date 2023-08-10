<?php
	include("classes/user_class.php");
	$Obj = new User();
	error_reporting(0);
	$array = array();
	$awbNumber = mysqli_fetch_array(mysqli_query($Obj->myconn, "SELECT * FROM awbtrack WHERE awb='".$_POST['awbtrack_id']."' order by created_on ASC"));
	$sql = mysqli_query($Obj->myconn, "SELECT * FROM awbhistory WHERE abwid='".$awbNumber['id']."' order by created_on ASC");
	if(mysqli_num_rows($sql)>0)
        {
			while($awbHistory = mysqli_fetch_array($sql))
			{	
				 $awbObject = ((object)[
							"timestamp"=>$awbHistory['awbdate'],
							"activity"=>$awbHistory['activity'],
							"city"=>$awbHistory['city'],
						]);
				array_push($array,$awbObject);
			}
			$data = ((object)[
				"awbnumber"=>$awbNumber['awb'],
				"origincity"=>$awbNumber['orcity'],
				"destinationcity"=>$awbNumber['descity'],
				"originstate"=>$awbNumber['orstate'],
				"destinationstate"=>$awbNumber['desstate'],
				"shippingtype"=>$awbHistory['shiptype'],
				"data"=>$array,
						]);
			echo $json = json_encode($data);
		}else{
			echo $json = json_encode("No Data Available");
		}	
	
?> 
	

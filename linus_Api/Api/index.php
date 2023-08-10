<?php
	$serverName = "HARIOM";
	$connectionInfo = array("Database"=>"IOT","UID"=>"", "PWD"=>"");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if($conn){
		echo "Database Connection Successfull"."<br>";
	}else{
		echo "Database Connection Failed"."<br>";
		die(print_r(sqlsrv_errors(), true));
	}
$sql = "select UID,UPWD,uName,uDept,uWH,WHID,UACTIVE from User_Rights WHERE uActive='1'  order by id desc";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      echo $row['uName'].", ".$row['uName']."<br />";
}

sqlsrv_free_stmt( $stmt);
	
?>
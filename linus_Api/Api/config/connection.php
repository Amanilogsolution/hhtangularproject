<?php
	@session_start();
	error_reporting(E_ALL ^ E_DEPRECATED);
	date_default_timezone_set("Asia/Kolkata");

	class Database
	{
		private $serverName;
		private $Database;
		private $UID;
		private $PWD;
		private $conn;
		public $myconn;
		
		private $serverName2;
		private $Database2;
		private $UID2;
		private $PWD2;
		private $conn2;
		public $myconn2;
		
		public function connect()
		{
			$this->serverName = "34.95.37.168";
			$this->Database = "IOT";
			$this->UID = "WebIOT";
			$this->PWD = "^g){##!0T##]^";
				
			// $this->serverName = "182.76.62.181";
			// $this->Database = "NEWAWLDB_T";
			// $this->UID = "sa";
			// $this->PWD = "BKUPADMIN";
			
			// $this->serverName = "HARIOM";
			// $this->Database = "IOT";
			// $this->UID = "";
			// $this->PWD = "";
	
			$connectionInfo = array("Database"=>$this->Database,"UID"=>$this->UID, "PWD"=>$this->PWD);
			$conn = sqlsrv_connect($this->serverName, $connectionInfo);
			if($conn){
				//echo "Database Connection Successfull"."<br>";
				
				return $this->myconn= $conn;
			}else{
				echo "Database Connection Failed";
				//die(print_r(sqlsrv_errors(), true));
			}
		}
		public function connect2()
		{		
			$this->serverName2 = "182.76.62.181";
			$this->Database2 = "Newawldb_t";
			$this->UID2 = "webapi_swim";
			$this->PWD2 = "Beta_375";

			///live

			// $this->serverName2 = "182.76.62.178";
			// $this->Database2 = "Newawldb";
			// $this->UID2 = "Webmob";
			// $this->PWD2 = "W@bM0bb__364";
	
			$connectionInfo2 = array("Database"=>$this->Database2,"UID"=>$this->UID2, "PWD"=>$this->PWD2);
			$conn2 = sqlsrv_connect($this->serverName2, $connectionInfo2);
			if($conn2){
				//echo "Database Connection Successfull"."<br>";
				
				return $this->myconn2= $conn2;
			}else{
				echo "Database Connection Failed";
				//die(print_r(sqlsrv_errors(), true));
			}
		}
	}
	// home page url		
	$home_url="http://swim.eprontoexpress.com/api";	
	$containerName = 'wmslocker';
	$blobName = 'SWIM';
	$accesskey = "bNmQ4TYYDrTwGyeBlUDuDIjOUeUpn8QaVrorfjox9BXoUU9G7u5ZL7RsS2Rsn2tMBk+tsi/kcWI+7JhLp+HppA==";
	$storageAccount = 'swimlocker';
	$destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
?>
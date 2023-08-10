<?php
include_once ("./config/connection.php");
error_reporting(0);
class Api extends Database
{
     private $conn;
    private $conn2;
	private $tbl_asnData;
	private $tbl_asnSub;
	private $tbl_mdnData;
	private $tbl_mdnSub;
    private $tbl_pickingdata;
    private $tbl_login;
    private $users_tbl;
    private $tbl_whmaster;
    private $tbl_Boxretail;
    private $Tbl_Pickingsub;
    private $Tbl_PickingDataRetail;
	private $Tbl_Permrn_Upload;
    private $tbl_retailscanning;
    private $Tbl_PutawayTemp;
    private $Tbl_WHLocation;
	private $Tbl_MDNPACKLIST;
	private $Tbl_AsnBoxDetails;
	private $Tbl_Sku_master;
	private $Tbl_whcustmapping;
    private $Tbl_CycleCountBatch;
    private $Tbl_CycleCountScan;
    private $Tbl_AsnBoxDetailsTemp;
    private $Tbl_putway;
    private $Tbl_Putawaytransfer;
	
    public function __construct()
    {
        $this->connect();
		$this->connect2();
        $this->tbl_asnSub 				 = "TBL_ASNSUB";
		$this->tbl_asnData 				 = "Tbl_ASNDATA";
		$this->tbl_login 				 = "TBL_LOGIN";
        $this->tbl_pickingdata 			 = "tbl_PICKINGDATA";
        $this->users_tbl 				 = "User_Rights";
        $this->tbl_whmaster 			 = "tbl_whmaster";
		$this->tbl_mdnSub 				 = "TBL_MDNSUB";
		$this->tbl_mdnData 				 = "Tbl_MDNDATA";
		$this->tbl_Boxretail 			 = "tbl_Boxretail";
		$this->Tbl_Pickingsub 			 = "Tbl_Pickingsub";
		$this->Tbl_PickingDataRetail 	 = "Tbl_PickingDataRetail";
		$this->Tbl_Permrn_Upload 		 = "PREMRN_UPLOAD";
		$this->tbl_retailscanning 		 = "tbl_retailscanning";
		$this->Tbl_PutawayTemp 			 = "Tbl_PutawayTemp";
		$this->Tbl_WHLocation 			 = "WHLocation";
		$this->Tbl_MDNPACKLIST 			 = "Tbl_MDNPACKLIST";
		$this->Tbl_AsnBoxDetails 		 = "Tbl_AsnBoxDetails";
		$this->Tbl_Sku_master 		     = "sku_master";
		$this->Tbl_whcustmapping         = "tbl_whcustmapping";
        $this->Tbl_CycleCountBatch       = "Tbl_CycleCountBatch";
        $this->Tbl_CycleCountScan        = "Tbl_CycleCountScan";
        $this->Tbl_AsnBoxDetailsTemp     = "Tbl_AsnBoxDetailsTemp";
        $this->Tbl_putway                = "Tbl_putway";
        $this->Tbl_Putawaytransfer       = "Tbl_Putawaytransfer";
    }
		public function InsertUpdateTempPutWay($wh, $custId, $custInv, $boxId, $location, $sku, $qty, $entryBy){
		$responceArray = array();
		$TempPutWay = sqlsrv_query($this->myconn2, "SELECT * FROM ".$this->Tbl_PutawayTemp." WITH(NOLOCK) WHERE WH='".$wh."' AND CUSTID='".$custId."' AND BOXID='".$boxId."' AND LOCATION='".$location."' AND SKU='".$sku."' AND CUSTINV='".$custInv."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if(sqlsrv_num_rows($TempPutWay) > 0)
		{
			$updateTempPutWay = sqlsrv_query($this->myconn2, "UPDATE ".$this->Tbl_PutawayTemp." SET QTY=ISNULL(QTY,0) + '".$qty."' WHERE WH='".$wh."' AND CUSTID='".$custId."' AND BOXID='".$boxId."' AND LOCATION='".$location."' AND SKU='".$sku."' AND CUSTINV='".$custInv."'");
			if($updateTempPutWay==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Record updation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "Record has beed updated successfully";
				return $responceArray;
			}
		}else{
			$createTempPutWay = sqlsrv_query($this->myconn2, "INSERT INTO ".$this->Tbl_PutawayTemp."(WH,CUSTID,CUSTINV,BOXID,LOCATION,SKU,QTY,ENTRYBY,ENTRYON)VALUES('".$wh."','".$custId."','".$custInv."','".$boxId."','".$location."','".$sku."','".$qty."','".$entryBy."',GETDATE())");
			
			if($createTempPutWay==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "New record creation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "New record has beed created";
				return $responceArray;
			}
		}
	}
	
	public function InsertUpdateBulkPutWay($wh, $custId, $custInv, $boxId, $location, $skuData, $entryBy){
       
		$responceArray = array();
        foreach($skuData as $data){
            $TempPutWay = sqlsrv_query($this->myconn2, "SELECT * FROM ".$this->Tbl_PutawayTemp." WITH(NOLOCK) WHERE WH='".$wh."' AND CUSTID='".$custId."' AND BOXID='".$boxId."' AND LOCATION='".$location."' AND SKU='".$data->sku."' AND CUSTINV='".$custInv."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
        }
		
		if(sqlsrv_num_rows($TempPutWay) > 0)
		{
            foreach($skuData as $data){
			$updateTempPutWay = sqlsrv_query($this->myconn2, "UPDATE ".$this->Tbl_PutawayTemp." SET QTY=ISNULL(QTY,0) + '".$data->QTY."' WHERE WH='".$wh."' AND CUSTID='".$custId."' AND BOXID='".$boxId."' AND LOCATION='".$location."' AND SKU='".$data->sku."' AND CUSTINV='".$custInv."'");
            }
            if($updateTempPutWay==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Record updation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "Record has beed updated successfully";
				return $responceArray;
			}
		}else{
            foreach($skuData as $data){
			$createTempPutWay = sqlsrv_query($this->myconn2, "INSERT INTO ".$this->Tbl_PutawayTemp."(WH,CUSTID,CUSTINV,BOXID,LOCATION,SKU,QTY,ENTRYBY,ENTRYON)VALUES('".$wh."','".$custId."','".$custInv."','".$boxId."','".$location."','".$data->sku."','".$data->QTY."','".$entryBy."',GETDATE())");
            }
			if($createTempPutWay==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "New record creation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "New record has beed created";
				return $responceArray;
			}
		}
	}
		public function ValidskuInLpn($wh, $WhLocation, $custInv, $custId, $sku)
	{
		$query = "SELECT R.SKU,ISNULL(SUM(R.QTY),0) AS TotLPNSKUQty, (SELECT ISNULL(SUM(p.QTY),0) FROM ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) WHERE P.WH=R.WH AND P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.SKU=R.SKU AND P.BOXID=R.WHLOCATION) AS TOTALPUTWAY,(ISNULL(SUM(R.QTY),0)-(SELECT ISNULL(SUM(p.QTY),0) FROM ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) WHERE P.WH=R.WH AND P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.SKU=R.SKU AND P.BOXID=R.WHLOCATION)) PENDINGPUTWAYQTY FROM ".$this->tbl_retailscanning." R WITH(NOLOCK) WHERE  R.WH='".$wh."' AND R.WHLOCATION='".$WhLocation."' AND R.INVOICENO='".$custInv."' AND R.CUSTID='".$custId."' AND R.SKU='".$sku."' GROUP BY R.CUSTID,R.WH,R.INVOICENO,R.SKU,R.WHLOCATION ";
		
        $skuInLpnValidData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($skuInLpnValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$skuInLpnArray = array();
				$skuInLpnArray["records"]=array();
			if(sqlsrv_num_rows($skuInLpnValidData) > 0){
				while($row = sqlsrv_fetch_array($skuInLpnValidData, SQLSRV_FETCH_ASSOC))
				{
					$skuInLpnArra= array(
						"sku" 				=> $row['SKU'],
						"TotLPNSKUQty" 	 	=> $row['TotLPNSKUQty'],
						"totalPutWay"  		=> $row['TOTALPUTWAY'],
						"pendingPutWayQty"	=> $row['PENDINGPUTWAYQTY'],
					);
					array_push($skuInLpnArray["records"], $skuInLpnArra);
				}
				return $skuInLpnArray;
			}else{
				$skuInLpnArray["message"]="Invalid sku";
				return $skuInLpnArray;
			}
        }
	}
	public function ValidateRackLocation($wh, $custId, $location){
		$rackValid = sqlsrv_query($this->myconn2, "select distinct location from ".$this->Tbl_WHLocation." where wh='".$wh."' and Custid='".$custId."' AND isnull(CCSTATUS,'')='' AND location ='".$location."'",  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($rackValid === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
			$rackArray = array();
			if(sqlsrv_num_rows($rackValid) > 0){
				$rackArray['data'] 		= 	$location;
				$rackArray["message"]	=	"Valid rack location";
				return $rackArray;
			}else{
				$rackArray["message"]	= "Invalid rack location";
				return $rackArray;
			}
        }
	}
	public function ValidateLpa($wh, $WhLocation){
		$query = "select COUNT(DISTINCT WHLOCATION) as totalRows, WHLOCATION AS LPN,R.CUSTID,R.WH,R.INVOICENO,i.MRNNo from ".$this->tbl_retailscanning." r with(nolock) left join tbl_inward i with(nolock) on i.InCustInvNo=r.INVOICENO and i.Cust=r.CUSTID and i.WH=r.WH AND R.SKU=I.SKU LEFT JOIN ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) ON P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.WH=R.WH and r.WHLOCATION=p.boxid where R.WH='".$wh."' AND R.WHLOCATION='".$WhLocation." ' and mrnpost='Y' AND isnull(ItemLocation,'')='' group by WHLOCATION ,R.CUSTID,R.WH,R.INVOICENO,i.MRNNo";
		
        $lpaValidData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($lpaValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$lpaArray = array();
				$lpaArray["records"]=array();
				$lpaArray["numberOfRecords"]=array();
			if(sqlsrv_num_rows($lpaValidData) > 0){
				$lpaTotals = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "SELECT ISNULL(SUM(R.QTY),0) AS TotLPNQty, (SELECT ISNULL(SUM(p.QTY),0) FROM ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) WHERE P.WH=R.WH AND P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.BOXID=R.WHLOCATION ) AS TotalPutwayQty FROM Tbl_RetailScanning R WITH(NOLOCK) WHERE R.WH='".$wh."' AND R.WHLOCATION='".$WhLocation."' GROUP BY R.CUSTID,R.WH,R.INVOICENO,R.WHLOCATION"), SQLSRV_FETCH_ASSOC);
				
				while($row = sqlsrv_fetch_array($lpaValidData, SQLSRV_FETCH_ASSOC))
				{
					$lpaArra= array(
						"lpn" 				=> $row['LPN'],
						"custId" 	 		=> $row['CUSTID'],
						"wh"  				=> $row['WH'],
						"custInv"			=> $row['INVOICENO'],
						"mrnNo"				=> $row['MRNNo'],
						"TotLPNQty"			=> $lpaTotals['TotLPNQty'],
						"TotalPutwayQty"	=> $lpaTotals['TotalPutwayQty'],
					);
					array_push($lpaArray["records"], $lpaArra);
					array_push($lpaArray["numberOfRecords"], $row['totalRows']);
				}
				$lpaArray["numberOfRecords"]=array_sum($lpaArray["numberOfRecords"]);
				return $lpaArray;
			}else{
				$lpaArray["message"]="Invalid Lpa";
				return $lpaArray;
			}
        }
	}
	public function ValidateLpaBOX($wh, $WhLocation){
		$query = "select COUNT(DISTINCT WHLOCATION) as totalRows, WHLOCATION AS LPN,R.CUSTID,R.WH,R.INVOICENO,i.MRNNo from ".$this->tbl_retailscanning." r with(nolock) left join tbl_inward i with(nolock) on i.InCustInvNo=r.INVOICENO and i.Cust=r.CUSTID and i.WH=r.WH AND R.SKU=I.SKU LEFT JOIN ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) ON P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.WH=R.WH and r.WHLOCATION=p.boxid where R.WH='".$wh."' AND R.WHLOCATION='".$WhLocation."' and mrnpost='Y' AND isnull(ItemLocation,'')='' group by WHLOCATION ,R.CUSTID,R.WH,R.INVOICENO,i.MRNNo";
		
        $lpaValidData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($lpaValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$lpaArray = array();
				$lpaArray["records"]=array();
				$lpaArray["numberOfRecords"]=array();
			if(sqlsrv_num_rows($lpaValidData) > 0){
				$lpaTotals = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "SELECT ISNULL(SUM(R.QTY),0) AS TotLPNQty, (SELECT ISNULL(SUM(p.QTY),0) FROM ".$this->Tbl_PutawayTemp." P WITH(NOLOCK) WHERE P.WH=R.WH AND P.CUSTID=R.CUSTID AND P.CUSTINV=R.INVOICENO AND P.BOXID=R.WHLOCATION ) AS TotalPutwayQty FROM Tbl_RetailScanning R WITH(NOLOCK) WHERE R.WH='".$wh."' AND R.WHLOCATION='".$WhLocation."' GROUP BY R.CUSTID,R.WH,R.INVOICENO,R.WHLOCATION"), SQLSRV_FETCH_ASSOC);
				
				while($row = sqlsrv_fetch_array($lpaValidData, SQLSRV_FETCH_ASSOC))
				{
					$lpaArra= array(
						"lpn" 				=> $row['LPN'],
						"custId" 	 		=> $row['CUSTID'],
						"wh"  				=> $row['WH'],
						"custInv"			=> $row['INVOICENO'],
						"mrnNo"				=> $row['MRNNo'],
						"TotLPNQty"			=> $lpaTotals['TotLPNQty'],
						"TotalPutwayQty"	=> $lpaTotals['TotalPutwayQty'],
					);
					array_push($lpaArray["records"], $lpaArra);
					array_push($lpaArray["numberOfRecords"], $row['totalRows']);
				}
				$lpaArray["numberOfRecords"]=array_sum($lpaArray["numberOfRecords"]);
				return $lpaArray;
			}else{
				$lpaArray["message"]="Invalid Lpa";
				return $lpaArray;
			}
        }
	}
	public function gridDataValidateLpaBOX($wh, $WhLocation, $custId, $custInv){
        $query =  " SELECT whlocation,sum(qty) as QTY,sku FROM ".$this->tbl_retailscanning." where INVOICENO='".$custInv."' and wh='".$wh."' and custid='".$custId."' and whlocation='".$WhLocation."' group by WHLocation,SKU ";
      
        $lpaValidData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($lpaValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
            $lpaArray = array();
			$lpaArray["records"]=array();
            if(sqlsrv_num_rows($lpaValidData) > 0){
				while($row = sqlsrv_fetch_array($lpaValidData, SQLSRV_FETCH_ASSOC))
				{
					$lpaArra= array(
						"whlocation" 	=> $row['whlocation'],
						"QTY" 	 		=> $row['QTY'],
						"sku"  			=> $row['sku']
					);
					array_push($lpaArray["records"], $lpaArra);
				}
				return $lpaArray;
			}else{
				$lpaArray["message"]="Invalid Lpa Box Grid";
				return $lpaArray;
			}
        }
    }
public function InsertUpdateupdateASNSku($asnNo, $WHLocation, $sku, $custId, $invoiceNo, $qty, $scannedQty, $entryby, $wh)
	{
		$responceArray = array();
		$boxNum = sqlsrv_query($this->myconn2, "SELECT * FROM ".$this->tbl_retailscanning." WHERE WH='".$wh."' AND CUSTID='".$custId."' AND INVOICENO='".$invoiceNo."' AND SKU='".$sku."' AND WHLOCATION='".$WHLocation."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
				if(sqlsrv_num_rows($boxNum) > 0)
		{
			$pickingSub = sqlsrv_query($this->myconn2, "UPDATE ".$this->tbl_retailscanning." SET QTY=ISNULL(QTY,0)+'".$qty."' WHERE WH='".$wh."' AND CUSTID='".$custId."' AND INVOICENO='".$invoiceNo."' AND SKU='".$sku."' AND WHLOCATION='".$WHLocation."'");
			if($pickingSub==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Record updation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "Record has beed updated successfully";
				return $responceArray;
			}
		}else{
			$savePickingSub = sqlsrv_query($this->myconn2, "insert into ".$this->tbl_retailscanning." with(tablock)(wh,custid,invoiceno,sku,whlocation,qty,entryby,ENTRYON,p_mrn_No)values('".$wh."','".$custId."','".$invoiceNo."','".$sku."','".$WHLocation."','".$qty."','".$entryby."',GETDATE(),'".$asnNo."')");
			
			if($savePickingSub==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "New record creation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "New record has beed created";
				return $responceArray;
			}
		}
	}
public function SKUValidation($asnNo, $WHLocation, $sku, $custId, $invoiceNo, $whid){
		$query = "SELECT COUNT(DISTINCT ITEMCODE) as totalRows, ITEMCODE AS SKU,isnull(sum(p.Qty),0) as ASNQTY,(select  isnull(sum(r.Qty),0) from ".$this->tbl_retailscanning." r with(nolock) where r.wh=p.wh and r.custid=p.CUSTID and r.P_MRN_No=p.P_MRN_NO and r.invoiceno=p.custinv  AND r.sku=p.ItemCode )as ScanQty, (isnull(sum(p.Qty),0)- (select  isnull(sum(r.Qty),0) from ".$this->tbl_retailscanning." r with(nolock) where r.wh=p.wh and r.custid=p.CUSTID and r.P_MRN_No=p.P_MRN_NO and  r.invoiceno=p.custinv  AND r.sku=p.ItemCode )) as BalanceFOrScan FROM ".$this->Tbl_Permrn_Upload." p WITH(NOLOCK) WHERE p.WH='".$whid."' AND (CUSTINV='".$invoiceNo."') AND P.CUSTID='".$custId."' AND ISNULL(p.MRN_NO,'')='' and p.vehinid<>'' AND ItemCode='".$sku."' group by ItemCode,p.wh,p.custid,p.p_mrn_NO,p.custinv";
		
		$scanNumByWhLocation = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "select  isnull(sum(r.Qty),0)as ScanQty from ".$this->tbl_retailscanning." r with(nolock) WHERE r.WH='".$whid."' AND (r.INVOICENO='".$invoiceNo."') AND r.CUSTID='".$custId."' AND WHLOCATION='".$WHLocation."' AND r.sku='".$sku."'"), SQLSRV_FETCH_ASSOC);
		
        $params = array();
        $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $SkuValidData = sqlsrv_query($this->myconn2, $query, $params, $options);
        if ($SkuValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$skuArray = array();
				$skuArray["records"]=array();
				$skuArray["numberOfRecords"]=array();
			if(sqlsrv_num_rows($SkuValidData) > 0){
				while($row = sqlsrv_fetch_array($SkuValidData, SQLSRV_FETCH_ASSOC))
				{
					$asnArra= array(
						"sku" 					=> $row['SKU'],
						"asnQty" 	 			=> $row['ASNQTY'],
						"scanQty"  				=> $row['ScanQty'],
						"BalanceFOrScan"		=> $row['BalanceFOrScan'],
						"scanNumByWhLocation"	=> $scanNumByWhLocation['ScanQty'],
					);
					array_push($skuArray["records"], $asnArra);
					array_push($skuArray["numberOfRecords"], $row['totalRows']);
				}
				$skuArray["numberOfRecords"]=array_sum($skuArray["numberOfRecords"]);
				return $skuArray;
			}else{
				$skuArray["message"]="Invalid Sku";
				return $skuArray;
			}
        }
	}
public function BoxValidation($asnNo, $WHLocation, $whid){
		$responceArray = array();
		$boxNum = sqlsrv_query($this->myconn2, "select DISTINCT WHLOCATION from ".$this->tbl_retailscanning." where WHLocation='".$WHLocation."' and wh='".$whid."' AND P_MRN_NO<>'".$asnNo."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		if(sqlsrv_num_rows($boxNum) > 0){
			$responceArray['status'] 	= 0;
            $responceArray['data'] 		= $asnNo;
            $responceArray['message'] 	= 'Box number Invalid';
			return $responceArray;
        }else{
			$responceArray['status']= 1;
            $responceArray['message'] = 'Box number valid';
			return $responceArray;
        }
	}
	public function AsnCount($term, $whId)
    {
		$term = (!is_null($term) && $term!='undefined')?"(p.P_MRN_nO='".$term."' OR p.CUSTINV='".$term."')":"ISNULL(p.MRN_NO,'')=''";
		$row = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "SELECT COUNT(DISTINCT P_MRN_No) AS total_rows FROM ".$this->Tbl_Permrn_Upload." p WITH(NOLOCK) WHERE p.WH='".$whId."' AND ".$term." and p.vehinid<>''"), SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function Asn($term, $whId, $records_per_page, $numer_of_records){
				if((!is_null($term) && $term!='undefined') && $whId){
			$query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "SELECT COUNT(DISTINCT ITEMCODE) AS TOTSKUCODEV, p.CUSTINV as InvoiceNO,p.PONO,p.P_MRN_nO as ASN_NO,p.CustID,p.WH,isnull(sum(p.Qty),0) as ASNQTY,(select  isnull(sum(r.Qty),0) from ".$this->tbl_retailscanning." r with(nolock) where r.wh=p.wh and r.custid=p.CUSTID and r.P_MRN_No=p.P_MRN_NO and r.invoiceno=p.custinv)as ScanQty FROM ".$this->Tbl_Permrn_Upload." p WITH(NOLOCK) WHERE p.WH='".$whId."' AND (p.P_MRN_nO='".$term."' OR p.CUSTINV='".$term."') AND ISNULL(p.MRN_NO,'')='' and p.vehinid<>'' group by p.CUSTINV,p.PONO,p.P_MRN_nO,p.CustID,p.WH ORDER BY p.CUSTINV OFFSET (@PageNumber-1) * @RowsOfPage ROWS FETCH NEXT @RowsOfPage ROWS ONLY";
		}else{
			$query ="DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "SELECT COUNT(DISTINCT ITEMCODE) AS TOTSKUCODE, p.CUSTINV as InvoiceNO,p.PONO,p.P_MRN_nO as ASN_NO,p.CustID,p.WH,isnull(sum(p.Qty),0) as ASNQTY,(select  isnull(sum(r.Qty),0) from ".$this->tbl_retailscanning." r with(nolock) where r.wh=p.wh and r.custid=p.CUSTID and r.P_MRN_No=p.P_MRN_NO and r.invoiceno=p.custinv)as ScanQty FROM ".$this->Tbl_Permrn_Upload." p WITH(NOLOCK) WHERE p.WH='".$whId."' AND ISNULL(p.MRN_NO,'')='' and p.vehinid<>''group by p.CUSTINV,p.PONO,p.P_MRN_nO,p.CustID,p.WH ORDER BY p.CUSTINV OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		}
		$params = array();
        $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $asnData = sqlsrv_query($this->myconn2, $query, $params, $options);
        if ($asnData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $asnData;
        }
		return array();
	}
		public function getrackLocation($wh, $dnno, $whLocation, $records_per_page, $numer_of_records)
    {
        //$query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct sku,batchno,skudesc,whlocation,dnno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->Tbl_PickingDataRetail . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "' group by sku,batchno,skudesc,whlocation,dnno ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
			
		 $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct sku,batchno,skudesc,whlocation,dnno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->Tbl_PickingDataRetail . " WITH(NOLOCK) where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "' group by sku,batchno,skudesc,whlocation,dnno ORDER BY InvQty ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
			//return $query;
        $rackLocationData = sqlsrv_query($this->myconn2, $query);
        if ($rackLocationData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $rackLocationData;
        }
        return array();
    }
	public function getNewRackLocation($wh, $dnno, $whLocation, $custid, $zoneBarcode, $aisles, $records_per_page, $numer_of_records)
    {
         $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct sku,batchno,skudesc,whlocation,dnno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->Tbl_PickingDataRetail . " WITH(NOLOCK) where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "' group by sku,batchno,skudesc,whlocation,dnno ORDER BY InvQty ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
        $rackLocationData = sqlsrv_query($this->myconn2, $query);
        if ($rackLocationData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $rackLocationData;
        }
        return array();
    }
    public function rackLocationCountt($wh, $dnno, $whLocation)
    {
        $query = "SELECT COUNT(DISTINCT sku) as total_rows FROM " . $this->Tbl_PickingDataRetail . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "'";
        $rackLocationData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($rackLocationData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function totalScan($wh, $custid, $dnno)
    {
    	$query = "SELECT SUM(PICKQTY) AS TOTALSCAN FROM " . $this->Tbl_PickingDataRetail . " WHERE  wh='" . $wh . "' AND CUSTID='" . $custid . "' and dnno='" . $dnno . "'";
    	$totalSacanData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($totalSacanData, SQLSRV_FETCH_ASSOC);
        return $row['TOTALSCAN'];
        if(!$row['TOTALSCAN'])
			{
				$responceArray['status']= 0;
            	$responceArray['message'] = 'Scan not found';
				return $responceArray;
			}else{
				$responceArray['status']= 1;
            	$responceArray['message'] =  $row['TOTALSCAN'];
				return $responceArray;
			}

    }
    public function totalAislesScan($wh, $custid, $dnno, $zoneBarcode, $aisles)
    {
    	$query = "select ISNULL(SUM(PICKQTY),0) AS TotalScanned from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" . $wh . "' AND C.CUSTID='" . $custid . "' and dnno='" . $dnno . "'  AND ZoneBarcode='" . $zoneBarcode . "' AND Aisles='" . $aisles . "'";
    	$totalSacanData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($totalSacanData, SQLSRV_FETCH_ASSOC);
        return $row['TotalScanned'];
        if(!$row['TotalScanned'])
			{
				$responceArray['status']= 0;
            	$responceArray['message'] = 'Aisles scan not found';
				return $responceArray;
			}else{
				$responceArray['status']= 1;
            	$responceArray['message'] =  $row['TotalScanned'];
				return $responceArray;
			}
    }
	public function getZoneWiseData($wh, $custid, $custinvno, $records_per_page, $numer_of_records)
    {
		$query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select DISTINCT ZONE,ZONEBARCODE,ISNULL(SUM(QTY),0) AS TotalQty,ISNULL(SUM(PICKQTY),0) AS TotalScanned from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" . $wh . "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' group by zone,zonebarcode ORDER BY ZONE ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
        $zoneWiseData = sqlsrv_query($this->myconn2, $query);

        if ($zoneWiseData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $zoneWiseData;
        }
        return array();
    }
    public function GetZoneWiseDataCount($wh, $custid, $custinvno)
    {
        $query = "select count(distinct zone) as total_rows from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" . $wh . "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' ";

        $getZoneWiseData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($getZoneWiseData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
    public function getAislesData($wh, $custid, $custinvno, $zoneBarcode, $records_per_page, $numer_of_records)
    {
    	//$query ="DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select DISTINCT Aisles,ISNULL(SUM(QTY),0) AS TotalQty,ISNULL(SUM(PICKQTY),0) AS TotalScanned from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.CUSTid=c.CUSTID and w.Location=c.WHLOCATION where c.wh='" .$wh. "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' AND ZoneBarcode='" .$zoneBarcode. "' group by Aisles ORDER BY Aisles ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
		$query ="DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select Aisles,ISNULL(SUM(QTY),0) AS TotalQty,ISNULL(SUM(PICKQTY),0) AS TotalScanned,case when ISNULL(SUM(PICKQTY),0)=ISNULL(SUM(QTY),0)  then 1 else 0 end  as chck from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" .$wh. "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' AND ZoneBarcode='" .$zoneBarcode. "' group by Aisles ORDER BY CHCK, Aisles ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
		
        $aislesData = sqlsrv_query($this->myconn2, $query);
        if ($aislesData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $aislesData;
        }
        return array();
    }
    public function getAislesDataCount($wh, $custid, $custinvno, $zoneBarcode)
    {
        $query = "select count(DISTINCT aisles) as total_rows  from Tbl_PickingDataretail c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" .$wh. "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' AND ZoneBarcode='" .$zoneBarcode. "'";

        $getZoneWiseData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($getZoneWiseData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
    public function getTrackData($wh, $custid, $custinvno, $zoneBarcode, $aisles, $records_per_page, $numer_of_records)
    {
    	//$query ="DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select C.WHLOCATION,ISNULL(SUM(QTY),0) AS TotalQty,ISNULL(SUM(PICKQTY),0) AS TotalScanned, COUNT(DISTINCT SKU) AS TOTALSKU from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.CUSTid=c.CUSTID and w.Location=c.WHLOCATION where c.wh='" .$wh. "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' AND ZoneBarcode='" .$zoneBarcode. "' AND Aisles='" .$aisles. "' group by WHLOCATION ORDER BY WHLOCATION ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
		$query ="DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select C.WHLOCATION,ISNULL(SUM(QTY),0) AS TotalQty,ISNULL(SUM(PICKQTY),0) AS TotalScanned, COUNT(DISTINCT SKU) AS TOTALSKU,case when ISNULL(SUM(PICKQTY),0)=ISNULL(SUM(QTY),0)  then 1 else 0 end  as chck from " . $this->Tbl_PickingDataRetail . " c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" .$wh. "' AND C.CUSTID='" .$custid. "' and CUSTINVNO='" .$custinvno. "' AND ZoneBarcode='" .$zoneBarcode. "' AND Aisles='" .$aisles. "' AND isnull(CCSTATUS,'')='' group by WHLOCATION ORDER BY CHCK, WHLOCATION ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
        $trackData = sqlsrv_query($this->myconn2, $query);
        if ($trackData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $trackData;
        }
        return array();
    }
    public function getTrackDataCount($wh, $custid, $custinvno, $zoneBarcode, $aisles)
    {
        $query = "select  count(distinct C.WHLOCATION) as total_rows from Tbl_PickingDataretail c with(nolock) left join WHLocation w with(nolock) on w.wh=c.wh and w.Location=c.WHLOCATION where c.wh='" . $wh . "' AND C.CUSTID='" . $custid . "' and CUSTINVNO='" . $custinvno . "' AND ZoneBarcode='" . $zoneBarcode . "' AND Aisles='" . $aisles . "'";

        $getTrackData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($getTrackData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function getBoxPickListDetails($wh, $dnno,  $records_per_page, $numer_of_records)
    {
		$query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . "select distinct wh,custid,dnno,custinvno,sku,skudesc,batchno,whlocation,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->Tbl_PickingDataRetail . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' group by wh,custid,dnno,custinvno,whlocation,sku,batchno,skudesc ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
		
        $boxPickListDetailsData = sqlsrv_query($this->myconn2, $query);
        if ($boxPickListDetailsData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $boxPickListDetailsData;
        }
        return array();
    }
	public function PendingBoxDetailsCount($wh, $dnno)
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->Tbl_PickingDataRetail . " where isnull(picklistno,'')='' and wh='".$wh."' and dnno='".$dnno."'";
        $pendingBoxDetailsData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($pendingBoxDetailsData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function getPendingBoxData($locationId, $dnno, $records_per_page, $numer_of_records)
    {
		//$dn = $dnno?"AND dnno='".$dnno."'":"";
		$dn = $dnno?"AND (dnno='".$dnno."' or  custinvno='".$dnno."')":"";
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct wh,custid,dnno,custinvno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->Tbl_PickingDataRetail . " where isnull(wmsin,'')='' AND wh='" . $locationId . "'  ".$dn." group by wh,custid,dnno,custinvno ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $pendingBoxData = sqlsrv_query($this->myconn2, $query, $params, $options);
        if ($pendingBoxData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $pendingBoxData;
        }
        return array();
    }
	public function PendingBoxCount($locationId)
    {
        $query = "SELECT COUNT(DISTINCT DNNO) as total_rows FROM " . $this->Tbl_PickingDataRetail . " where isnull(picklistno,'')='' and wh='" . $locationId . "'";
        $pendingBoxData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($pendingBoxData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function InsertUpdatePickingSub($wh, $custid, $dnno, $ref_no, $boxno, $sku, $batchno, $qty, $whlocation, $entryby)
	{
		$responceArray = array();
		$boxNum = sqlsrv_query($this->myconn2, "select boxno, sku, dnno from ".$this->Tbl_Pickingsub." where wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and ref_no='".$ref_no."' and boxno='".$boxno."' and sku='".$sku."' and batchno='".$batchno."' and whlocation = '".$whlocation."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if(sqlsrv_num_rows($boxNum) > 0)
		{			
			$pickingSub = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "select whlocation, boxno, qty,sku from " . $this->Tbl_Pickingsub . " WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and sku='".$sku."' and batchno='".$batchno."' and boxno='".$boxno."' and whlocation = '".$whlocation."'") , SQLSRV_FETCH_ASSOC);
			$qtyNegative = $qty < 0;
			
			if($pickingSub['qty'] < abs($qty) && $qtyNegative)
			{
				return $responceArray = 'You are removing excess quantity';
			}else{
				$PickingRetail = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "select whlocation, pickqty, qty,sku from " . $this->Tbl_PickingDataRetail . " WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and sku='".$sku."' and batchno='".$batchno."' and whlocation = '".$whlocation."'") , SQLSRV_FETCH_ASSOC);
			
				$quantityToUpdate = $PickingRetail['pickqty'];
			
				if ($PickingRetail['sku'] == null)
				{
					return $responceArray = 'Picking Data Retail Sku could not found  to update';
				}
				$quantityToUpdate = $PickingRetail['pickqty'] + $qty;
			
				if ($quantityToUpdate > $PickingRetail['qty'] || $quantityToUpdate < 0)
				{
					return $responceArray = 'Picking Data Retail Record could not be updated due to negative quantity Or quantity is greater';
				}			
				$PickingDataRetailQty = sqlsrv_query($this->myconn2, "update ".$this->Tbl_PickingDataRetail." set PickQTy= '".$quantityToUpdate."' WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and sku='".$sku."'  and batchno='".$batchno."' and whlocation = '".$whlocation."'");
				
				if($PickingDataRetailQty==false)
				{
					return $responceArray = "Quintity updation failed";
				}else{			
					$updatePickingSubQty = sqlsrv_query($this->myconn2, "update ".$this->Tbl_Pickingsub." set qty= ISNULL(QTY,0) +'".$qty."'  WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and boxno='".$boxno."' and sku='".$sku."' and batchno='".$batchno."' and whlocation = '".$whlocation."'");
					return $responceArray = "Quintity updation ".$qty."";
				}	
			}
		}else{			
			$PickingRetail = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "select whlocation, pickqty, qty,sku from " . $this->Tbl_PickingDataRetail . " WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and sku='".$sku."'  and batchno='".$batchno."' and whlocation = '".$whlocation."'") , SQLSRV_FETCH_ASSOC);
			
			$quantityToUpdate = $PickingRetail['pickqty'];
		
			if ($PickingRetail['sku'] == null)
			{
				return $responceArray = 'Picking Data Retail Sku could not found to update';
			}
			$quantityToUpdate = $PickingRetail['pickqty'] + $qty;
        
			if ($quantityToUpdate > $PickingRetail['qty'] || $quantityToUpdate < 0)
			{
				return $responceArray = 'Picking Data Retail Record could not be updated due to negative quantity Or quantity is greater';
			}

			$PickingDataRetailQty = sqlsrv_query($this->myconn2, "update ".$this->Tbl_PickingDataRetail." set PickQTy = ".$quantityToUpdate." WHERE wh='".$wh."' and custid='".$custid."' and dnno ='".$dnno."' and sku='".$sku."'  and batchno='".$batchno."' and whlocation = '".$whlocation."'");
			
			$savePickingSub = sqlsrv_query($this->myconn2, "Insert into " . $this->Tbl_Pickingsub . "(wh, custid, dnno, ref_no, boxno, sku, batchno, qty, whlocation, entryby, entryon)values('".$wh."','".$custid."','".$dnno."','".$ref_no."','".$boxno."', '".$sku."', '".$batchno."', '".$qty."', '".$whlocation."','".$entryby."', GETDATE())");
			
			if($savePickingSub==false)
			{
				return $responceArray = "New record creation failed! please check errors";
			}else{
				return $responceArray = "New record has beed created";
			}
		}
	}
	public function BoxNumberValidation($whid, $boxno)
	{
		$responceArray = array();
		$boxNum = sqlsrv_fetch_array(sqlsrv_query($this->myconn2, "select distinct boxno from ".$this->tbl_Boxretail." WHERE whid='".$whid."' and boxno='".$boxno."'") , SQLSRV_FETCH_ASSOC);

        if ($boxNum['boxno'] == null){
			$responceArray['status']= 0;
            $responceArray['message'] = 'No box number found';
			return $responceArray;
        }else{
			$responceArray['status']= 1;
            $responceArray['message'] = $boxNum['boxno'];
			return $responceArray;
        }
	}
	public function NewBoxNumberValidation($whid, $boxno, $custid, $REF_nO)
	{
		$responceArray = array();
		$boxNum = sqlsrv_query($this->myconn2, "select DISTINCT boxno from ".$this->tbl_Boxretail." where whid='".$whid."' AND custid='".$custid."' AND boxno='".$boxno."' AND ISNULL(STATUS,'')=''", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if(sqlsrv_num_rows($boxNum) > 0)
		 {	
        	$valBoxNum = sqlsrv_query($this->myconn2, "select DISTINCT boxno from ".$this->Tbl_Pickingsub."  where wh='".$whid."' AND custid='".$custid."' AND boxno='".$boxno."'  and REF_nO<>'".$REF_nO."' UNION SELECT DISTINCT BOXNO FROM ".$this->Tbl_MDNPACKLIST."  where wh='".$whid."' AND custid='".$custid."' AND boxno='".$boxno."' and REF_nO<>'".$REF_nO."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        	if(sqlsrv_num_rows($valBoxNum) > 0)
			{
				$responceArray['status']= 0;
            	$responceArray['message'] = 'Already Used Box Number';
				return $responceArray;
			}else{
				$query = "select isnull(sum(QTY),0) as qty  from ".$this->Tbl_Pickingsub." where boxno='".$boxno."' and wh='".$whid."' and CUSTID='".$custid."' and REF_nO='".$REF_nO."'";
				$countBoxData = sqlsrv_query($this->myconn2, $query);
        		$row = sqlsrv_fetch_array($countBoxData, SQLSRV_FETCH_ASSOC);

				$responceArray['status']= 1;
            	$responceArray['message'] =  $boxno;
            	$responceArray['qtyOfBox'] = $row['qty'];
				return $responceArray;
			}
			
        }else{
			$responceArray['status']= 0;
            $responceArray['message'] = 'No box number found/Box Locked!';
			return $responceArray;
        }
	}
	
	public function CloseBox($wh, $boxno, $custid){
        $responceArray = array();
		$query = sqlsrv_query($this->myconn2, "update ".$this->tbl_Boxretail." with(tablock) set status='Y' WHERE BOXNO='".$boxno."' AND whid='".$wh."' AND CUSTID='".$custid."'");
        $query2 = sqlsrv_query($this->myconn2, "update ".$this->Tbl_Pickingsub." with(tablock) set status='Y' WHERE BOXNO='".$boxno."' AND WH='".$wh."' AND CUSTID='".$custid."' AND ISNULL(STATUS,'')=''");
      
				if($query==false && $query2==false)
				{
                    $responceArray['status']= 0;
            	    $responceArray['message'] =  "Box Closed failed!";
				    return $responceArray;
				}else{			
					$responceArray['status']= 1;
                    $responceArray['message'] = 'Box Closed Successfully!';
			        return $responceArray;
				}
    }
	public function uploadMdnData($userId, $locationId, $mdn, $data)
	{	
		$responceArray = array();
		foreach($data as $key=>$value)
		{
			$query = "Insert into " . $this->tbl_mdnSub . "(MDNNO,IMURL,FILETYPE,FILENAME,FOLDERNAME)values('".$mdn."','".$value."','".$key."','".$value."','".$mdn."')";
			$saveUploadData = sqlsrv_query($this->myconn, $query);
			$query = " UPDATE " . $this->tbl_mdnData . " SET status='Y', DoneBy='".$userId."', DoneOn=GETDATE() WHERE MDNNO='".$mdn."' and isnull(Status, '')=''";
			$sql = sqlsrv_query($this->myconn, $query);
		}
        if($sql === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
			return $responceArray = 'Data inserted successfully';
		}
	}
	public function findMdn($locationId, $mdnno, $records_per_page, $numer_of_records)
    {	
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" .$numer_of_records ." select distinct mdnno,invoiceno,wh,custid from ".$this->tbl_mdnData." where isnull(status,'')='' and wh='".$locationId."' and mdnno='".$mdnno."' group by mdnno,invoiceno,wh,custid ORDER BY mdnno OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $findMdnData = sqlsrv_query($this->myconn, $query, $params, $options);
        if ($findMdnData === false){
            die(print_r(sqlsrv_errors() , true));
        }
        else{
            return $findMdnData;
        }
        return array();
    }
	public function findMdnListCount($locationId, $mdnno)
    {
        $query = "SELECT COUNT(DISTINCT mdnno) as total_rows FROM ".$this->tbl_mdnData. " where isnull(status,'')='' and wh='".$locationId."' and mdnno='".$mdnno."'";
        $findMdndata = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($findMdn, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function openMdn($locationId,$records_per_page, $numer_of_records)
    {		
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" .$numer_of_records ." select distinct mdnno,invoiceno,wh,custid from ".$this->tbl_mdnData." where isnull(status,'')='' and wh='".$locationId."' group by mdnno,invoiceno,wh,custid ORDER BY mdnno OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $openMdnData = sqlsrv_query($this->myconn, $query, $params, $options);
        if ($openMdnData === false){
            die(print_r(sqlsrv_errors() , true));
        }
        else{
            return $openMdnData;
        }
        return array();
    }
	public function mdnListCount($locationId)
    {
        $query = "SELECT COUNT(DISTINCT asnno) as total_rows FROM ".$this->tbl_mdnData. " where isnull(status,'')='' and wh='".$locationId."'";
        $openMdnata = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($openMdnata, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	
	public function findAsn($locationId, $asnno,$records_per_page, $numer_of_records)
    {	
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" .$numer_of_records ." select distinct asnno,invoiceno,wh,custid from ".$this->tbl_asnData." where isnull(status,'')='' and wh='".$locationId."' and asnno='".$asnno."' group by asnno,invoiceno,wh,custid ORDER BY asnno OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $findAsnData = sqlsrv_query($this->myconn, $query, $params, $options);
        if ($findAsnData === false){
            die(print_r(sqlsrv_errors() , true));
        }
        else{
            return $findAsnData;
        }
        return array();
    }
	public function findAsnListCount($locationId, $asnno)
    {
        $query = "SELECT COUNT(DISTINCT asnno) as total_rows FROM ".$this->tbl_asnData. " where isnull(status,'')='' and wh='".$locationId."' and asnno='".$asnno."'";
        $findAsndata = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($findAsndata, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function openAsn($locationId,$records_per_page, $numer_of_records)
    {		
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" .$numer_of_records ." select distinct asnno,invoiceno,wh,custid from ".$this->tbl_asnData." where isnull(status,'')='' and wh='".$locationId."' group by asnno,invoiceno,wh,custid ORDER BY asnno OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $openAsnData = sqlsrv_query($this->myconn, $query, $params, $options);
        if ($openAsnData === false){
            die(print_r(sqlsrv_errors() , true));
        }
        else{
            return $openAsnData;
        }
        return array();
    }
	public function asnListCount($locationId)
    {
        $query = "SELECT COUNT(DISTINCT asnno) as total_rows FROM ".$this->tbl_asnData. " where isnull(status,'')='' and wh='".$locationId."'";
        $openAsnata = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($openAsnata, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
	public function uploadFileData($userId, $locationId, $asnno, $data)
	{	
		$responceArray = array();
		foreach($data as $key=>$value)
		{
			$query = "Insert into " . $this->tbl_asnSub . "(ASNNO,IMURL,FILETYPE,FILENAME,FOLDERNAME)values('".$asnno."','".$value."','".$key."','".$value."','".$asnno."')";
			$saveUploadData = sqlsrv_query($this->myconn, $query);
			$query = " UPDATE " . $this->tbl_asnData . " SET status='Y', DoneBy='".$userId."', DoneOn=GETDATE() WHERE ASNNO='".$asnno."' and isnull(Status, '')=''";
			$sql = sqlsrv_query($this->myconn, $query);
		}
        if($sql === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
			return $responceArray = 'Data inserted successfully';
		}
	}
    public function login_history($uid, $pass)
    {
		
        $getuserData = sqlsrv_fetch_array(sqlsrv_query($this->myconn, "select * from " . $this->users_tbl . " where uID='" . $uid . "' and uPWD='" . $pass . "'") , SQLSRV_FETCH_ASSOC);
        //return $getuserData;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) //whether ip is from share internet
        
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
            //whether ip is from proxy
            
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
		$pos1 = strpos($_SERVER['HTTP_USER_AGENT'], '(')+1;
		$pos2 = strpos($_SERVER['HTTP_USER_AGENT'], ')')-$pos1;
		$part = substr($_SERVER['HTTP_USER_AGENT'], $pos1, $pos2);
		$parts = explode(" ", $part);
		$deviceName = $parts[0].''.$parts[1].' '.$parts[2].' '.$parts[3].' '.$parts[4];
        $query = "Insert into " . $this->tbl_login . "(user_id,user_name,location,comp_name,comp_ip,login_time,status)values('" . $uid . "','" . $getuserData['uNAME'] . "','" . $getuserData['WHID'] . "','" . $deviceName. "','" . $ip_address . "',GETDATE(), 'login')";
        	
		
		
		 $saveLogin = sqlsrv_query($this->myconn, $query);
		
		
		
        if ($saveLogin === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
		return;
		
    }
    public function logOut($userId)
    {
        $query = " UPDATE " . $this->tbl_login . " SET status='logout', logout_time=GETDATE() WHERE USER_ID='" . $userId . "' and isnull(logout_time, '')=''";
		
		
        $sql = sqlsrv_query($this->myconn, $query);
        if ($sql === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
    }
    public function check_login($uid, $pass)
    {
        $query = "select ID,UID,UPWD,uName,uDept,uWH,WHID,UACTIVE from " . $this->users_tbl . " WHERE uActive='1' AND uID='" . $uid . "' AND uPWD='" . $pass . "' order by id desc";
        $usr_obj = sqlsrv_query($this->myconn, $query);
        if ($usr_obj === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            $data = sqlsrv_fetch_array($usr_obj, SQLSRV_FETCH_ASSOC);
            return $data;
        }
        return array();
    }
    public function getWearHouse()
    {
        $query = "SELECT WHID,WHNAME FROM " . $this->tbl_whmaster . " WHERE WHACTIVE='1'";
        $wearHouse = sqlsrv_query($this->myconn, $query);
        if ($wearHouse === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $wearHouse;
        }
        return array();
    }
    public function getPickListData($locationId, $records_per_page, $numer_of_records)
    {
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct wh,custid,dnno,custinvno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' AND wh='" . $locationId . "' group by wh,custid,dnno,custinvno ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $pickListData = sqlsrv_query($this->myconn, $query, $params, $options);
        if ($pickListData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $pickListData;
        }
        return array();
    }
    public function pickListCount($locationId)
    {
        $query = "SELECT COUNT(DISTINCT DNNO) as total_rows FROM " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' and wh='" . $locationId . "'";
        $pickListData = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($pickListData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
    public function getPickListDetailsData($wh, $dnno)
    {
        $query = "select distinct wh,custid,dnno,custinvno,sku,skudesc,batchno,whlocation,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' group by wh,custid,dnno,custinvno,whlocation,sku,batchno,skudesc";
        $pickListDetailsData = sqlsrv_query($this->myconn, $query);
        if ($pickListDetailsData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $pickListDetailsData;
        }
        return array();
    }
    public function getrackLocationData($wh, $dnno, $whLocation, $records_per_page, $numer_of_records)
    {
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select distinct sku,batchno,skudesc,whlocation,dnno,sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "' group by sku,batchno,skudesc,whlocation,dnno ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $rackLocationData = sqlsrv_query($this->myconn, $query);
        if ($rackLocationData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $rackLocationData;
        }
        return array();
    }
    public function rackLocationCount($wh, $dnno, $whLocation)
    {
        $query = "SELECT COUNT(DISTINCT sku) as total_rows FROM " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "'";
        $rackLocationData = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($rackLocationData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
    public function skuData($skuCode, $wh, $dnno, $whLocation, $records_per_page, $numer_of_records)
    {
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select sku, batchno, skudesc, whlocation,dnno, sum(qty) As InvQty,sum(pickqty) as PickQTy from " . $this->tbl_pickingdata . "  where isnull(picklistno,'')='' and sku='" . $skuCode . "' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "' group by sku,batchno,skudesc,whlocation, dnno ORDER BY DNNO OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
        $skuData = sqlsrv_query($this->myconn, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }
        else
        {
            return $skuData;
        }
        return array();
    }
    public function skuCount($skuCode, $wh, $dnno, $whLocation)
    {
        $query = "SELECT COUNT(DISTINCT sku) as total_rows FROM " . $this->tbl_pickingdata . " where isnull(picklistno,'')='' and sku='" . $skuCode . "' and wh='" . $wh . "' and dnno='" . $dnno . "' and whlocation='" . $whLocation . "'";
        $rackLocationData = sqlsrv_query($this->myconn, $query);
        $row = sqlsrv_fetch_array($rackLocationData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }
    public function getskuUpdateData($wh, $dnno, $sku, $batchno, $qty, $whLocation)
    {
        $responceArray = array();
		$pickqty = sqlsrv_fetch_array(sqlsrv_query($this->myconn, "select pickqty, qty,sku,WHLOCATION from " . $this->tbl_pickingdata . "  where wh='" . $wh . "' and batchno='" . $batchno . "' and sku='" . $sku . "'and  DnNo='" . $dnno . "'  and  WHLOCATION='" . $whLocation . "'") , SQLSRV_FETCH_ASSOC);
        $quantityToUpdate = $pickqty['pickqty'];
		
        if ($pickqty['sku'] == null)
        {
            return $responceArray = 'Sku could not found';
        }
       $quantityToUpdate = $pickqty['pickqty'] + $qty;
        
		if ($quantityToUpdate > $pickqty['qty'] || $quantityToUpdate < 0)
        {
            return $responceArray = 'Record could not be updated due to negative quantity Or quantity is greater';
        }
       
     	
        $params = array();
        $options = array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        );
        $query = "Update " . $this->tbl_pickingdata . " set pickqty='" . $quantityToUpdate . "' where wh='" . $wh . "' and batchno='" . $batchno . "' and sku='" . $sku . "'and  DnNo='" . $dnno . "' and  whlocation='" . $whLocation . "'";
        $skuUpdateData = sqlsrv_query($this->myconn, $query, $params, $options);
        $rows = sqlsrv_num_rows($skuUpdateData);
        if ($skuUpdateData === false)
        {
            return $responceArray = 'Record updation failed';
        }
        elseif ($rows == 0)
        {
            return $responceArray = 'Record could not found';
        }
        else
        {
            return $responceArray = 'Record updated successfully';
        }
        return array();
    }
	
 	public function getAsnScanData($wh, $BoxNo){
        //return $wh;
        $query = "select  b.Wh,b.custid,b.Custinv,p.P_MRN_No as ASN_no,SUM(B.QTY) AS TotalBoxQty,isnull(sum(r.QTY),0) as ScanQTy,(select count(distinct ab.box ) from " . $this->Tbl_AsnBoxDetails . " AB with(nolock) where ab.wh=B.WH AND AB.CUSTINV=B.CUSTINV AND AB.CUSTID=B.CUSTID ) AS BoxCount,(select count(distinct ab.SKU )from " . $this->Tbl_AsnBoxDetails . " AB with(nolock) where ab.wh=B.WH AND AB.CUSTINV=B.CUSTINV AND AB.CUSTID=B.CUSTID AND AB.BOX=B.BOX ) AS SKUCount from " . $this->Tbl_AsnBoxDetails . " b WITH(NOLOCK) left join premrn_Upload p with(nolock) on p.wh=b.wh and p.CustID=b.CUSTID and p.CustInv=b.CUSTINV and P.ItemCode=B.SKU LEFT JOIN Tbl_RetailScanning R WITH(NOLOCK) ON B.WH=R.WH AND B.CUSTID=R.CUSTID AND B.BOX=R.WHLOCATION AND B.SKU=R.SKU AND B.CUSTINV=R.INVOICENO WHERE B.BOX='" . $BoxNo . "' AND B.WH='" . $wh . "' AND ISNULL(MRN_nO,'')='' group by  b.Wh,b.custid,b.Custinv,p.P_MRN_No,B.BOX ";
        
        $asnScanData = sqlsrv_query($this->myconn2, $query);
        if ($asnScanData === false)
        {
            die(print_r(sqlsrv_errors() , true));
			//return "err";
        }else{
            //return $wh;
            return $asnScanData;
        }
        return array();
    }
	
  public function AsnScanTableData($wh, $BoxNo, $records_per_page, $numer_of_records){
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber=" . $records_per_page . " SET @RowsOfPage=" . $numer_of_records . " select B.SKU,SUM(B.QTY) AS TotalBoxQty,isnull(sum(r.QTY),0) as ScanQTy from " .$this->Tbl_AsnBoxDetails. " b WITH(NOLOCK) left join premrn_Upload p with(nolock) on p.wh=b.wh and p.CustID=b.CUSTID and p.CustInv=b.CUSTINV and P.ItemCode=B.SKU LEFT JOIN Tbl_RetailScanning R WITH(NOLOCK) ON B.WH=R.WH AND B.CUSTID=R.CUSTID AND B.BOX=R.WHLOCATION AND B.SKU=R.SKU AND B.CUSTINV=R.INVOICENO WHERE B.BOX='".$BoxNo."' AND B.WH='".$wh."' AND ISNULL(MRN_nO,'')='' group by  b.Wh,b.custid,b.Custinv,p.P_MRN_No,B.SKU,B.BOX order by ScanQTy asc OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
       
        
        $asnTableData = sqlsrv_query($this->myconn2, $query);
        if ($asnTableData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            //return $wh;
            return $asnTableData;
        }
        return array();
    }
	
	public function AsnScanTableCount($wh, $BoxNo){
        $query = "SELECT count(distinct b.sku) as total_rows FROM " .$this->Tbl_AsnBoxDetails. " b WITH(NOLOCK) left join premrn_Upload p with(nolock) on p.wh=b.wh and p.CustID=b.CUSTID and p.CustInv=b.CUSTINV and P.ItemCode=B.SKU LEFT JOIN Tbl_RetailScanning R WITH(NOLOCK) ON B.WH=R.WH AND B.CUSTID=R.CUSTID AND B.BOX=R.WHLOCATION AND B.SKU=R.SKU AND B.CUSTINV=R.INVOICENO WHERE B.BOX='".$BoxNo."' AND B.WH='".$wh."' AND ISNULL(MRN_nO,'')=''";
        $asnScanCount = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($asnScanCount, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function AsnScanRFIDTableData($wh, $BoxNo, $records_per_page, $numer_of_records){
       $query = "select B.SKU,SUM(B.QTY) AS TotalBoxQty,isnull(sum(r.QTY),0) as ScanQTy from " .$this->Tbl_AsnBoxDetails. " b WITH(NOLOCK) left join premrn_Upload p with(nolock) on p.wh=b.wh and p.CustID=b.CUSTID and p.CustInv=b.CUSTINV and P.ItemCode=B.SKU LEFT JOIN ".$this->Tbl_AsnBoxDetailsTemp." R WITH(NOLOCK) ON B.WH=R.WH AND B.CUSTID=R.CUSTID AND B.BOX=R.BOX AND B.SKU=R.SKU AND B.CUSTINV=R.CUSTINV WHERE B.BOX='".$BoxNo."' AND B.WH='".$wh."' AND ISNULL(MRN_nO,'')='' group by b.Wh,b.custid,b.Custinv,p.P_MRN_No,B.SKU,B.BOX order by ScanQTy asc";
       $asnTableData = sqlsrv_query($this->myconn2, $query);
        if ($asnTableData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $asnTableData;
        }
        return array();
    }

    public function getAsnScanQtyData($wh, $BoxNo){
        $query = "SELECT SUM(QTY) AS Scanqty FROM ".$this->Tbl_AsnBoxDetailsTemp." A WITH(NOLOCK) WHERE  A.BOX='".$BoxNo."' AND A.WH='".$wh."' ";
        $asnScanData = sqlsrv_query($this->myconn2, $query);
        if ($asnScanData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $asnScanData;
        }
        return array();
    }
	
	public function InsertUpdateupdateASNScanSku($asnNo, $WHLocation, $sku, $custId, $invoiceNo, $qty, $scannedQty, $entryby, $wh)
	{
		$responceArray = array();
		$boxNum = sqlsrv_query($this->myconn2, "SELECT * FROM ".$this->tbl_retailscanning."  with(nolock) WHERE WH='".$wh."' AND CUSTID='".$custId."' AND INVOICENO='".$invoiceNo."' AND SKU='".$sku."' AND WHLOCATION='".$WHLocation."'", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if(sqlsrv_num_rows($boxNum) > 0)
		{
			$pickingSub = sqlsrv_query($this->myconn2, "UPDATE ".$this->tbl_retailscanning." with(tablock) SET QTY='".$qty."' WHERE WH='".$wh."' AND CUSTID='".$custId."' AND INVOICENO='".$invoiceNo."' AND SKU='".$sku."' AND WHLOCATION='".$WHLocation."'");
			if($pickingSub==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Record updation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "Record has beed updated successfully";
				return $responceArray;
			}
		}else{
			$savePickingSub = sqlsrv_query($this->myconn2, "insert into ".$this->tbl_retailscanning." with(tablock) (wh,custid,invoiceno,sku,whlocation,qty,entryby,ENTRYON,p_mrn_No,SFLAG)values('".$wh."','".$custId."','".$invoiceNo."','".$sku."','".$WHLocation."','".$qty."','".$entryby."',GETDATE(),'".$asnNo."','Y')");
			
			if($savePickingSub==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "New record creation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "New record has beed created";
				return $responceArray;
			}
		}
	}
	 public function getSkuWeight($sku_name){
        $query = "SELECT * FROM ".$this->Tbl_Sku_master. " with(nolock) where sku_Name = '".$sku_name."' ";
		
        $skuInLpnValidData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($skuInLpnValidData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
             if(sqlsrv_num_rows($skuInLpnValidData) > 0){
                $responceArray["status"] = 1;
				$responceArray["message"] = sqlsrv_num_rows($skuInLpnValidData);
				return $responceArray;
           
			}else{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Invalid Sku";
				return $responceArray;
			}
        }
    }
    public function updateSkuWeight($sku_name,$weight){
        $updateSkuWeight = sqlsrv_query($this->myconn2, "UPDATE ".$this->Tbl_Sku_master." with(tablock) SET Weight='".$weight."' WHERE sku_Name='".$sku_name."'");
			if($updateSkuWeight==false)
			{
				$responceArray["status"] = 0;
				$responceArray["message"] = "Record updation failed! please check errors";
				return $responceArray;
			}else{
				$responceArray["status"] = 1;
				$responceArray["message"] = "Record has beed updated successfully";
				return $responceArray;
			}
    }
	
	public function getClient($whid){

        $query = "select DISTINCT C.CUSTID AS CLIENTID,CM.CUSTNAME AS CLIENT from ".$this->Tbl_whcustmapping . " c with(nolock) left join tbl_customer cm with(nolock) on c.custid=cm.custid left join tbl_whmaster w with(nolock) on w.whid=c.whid where c.whid='".$whid."' AND ISNULL(CM.CUSTACTIVE,'')='' order by CM.custname asc";
		$clientsData = sqlsrv_query($this->myconn2, $query);
       if ($clientsData === false)
       {
           die(print_r(sqlsrv_errors() , true));
       }else{
           return $clientsData;
       }
       return array();
    }
	
			 public function getInventroySkuData($whid, $CUST, $SKU){
        $query = "select SUM(BALQTY) AS AvailForAllocation,p.sku,p.WHLOCATION,(SELECT ISNULL(SUM(QTY) ,0)- ISNULL(SUM(PICKQTY),0) FROM ".$this->Tbl_PickingDataRetail." r WITH(NOLOCK) WHERE r.WH=p.WH AND r.CUSTID=p.CUST  AND ISNULL(WMSIN,'')='' and r.SKU=p.sku AND r.WHLOCATION=p.WHLOCATION ) as PendingForPick from Tbl_putway p WITH(NOLOCK) where p.wh='".$whid."' and CUST='".$CUST."' and P.SKU='".$SKU."' group by p.sku,p.WHLocation,p.WH,p.cust";
		
        $skuData = sqlsrv_query($this->myconn2, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $skuData;
        }
        return array();
    }

    public function getInventroyLocationData($whid, $CUST, $location){
     $query = "select SUM(BALQTY) AS AvailForAllocation,p.sku,p.WHLOCATION,(SELECT ISNULL(SUM(QTY) ,0)- ISNULL(SUM(PICKQTY),0) FROM ".$this->Tbl_PickingDataRetail." r WITH(NOLOCK) WHERE r.WH=p.WH AND r.CUSTID=p.CUST AND r.WHLOCATION=p.WHLOCATION AND ISNULL(WMSIN,'')='' and r.SKU=p.sku ) as PendingForPick from Tbl_putway p WITH(NOLOCK) where p.wh='".$whid."' and CUST='".$CUST."' and p.WHLOCATION='".$location."' group by p.sku,p.WHLocation,p.WH,p.cust";
		//return $query;
        $skuData = sqlsrv_query($this->myconn2, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $skuData;
        }
        return array();
    }

    public function getInventroySkuDataCount($whid, $CUST, $SKU){
        $query = "select count(P.WHLOCATION) as total_rows, SUM(BALQTY) AS AvailForAllocation,p.sku,p.WHLOCATION,(SELECT ISNULL(SUM(QTY) ,0)- ISNULL(SUM(PICKQTY),0) FROM ".$this->Tbl_PickingDataRetail." r WITH(NOLOCK) WHERE r.WH=p.WH AND r.CUSTID=p.CUST  AND ISNULL(WMSIN,'')='' and r.SKU=p.sku ) as PendingForPick from Tbl_putway p WITH(NOLOCK) where p.wh='".$whid."' and CUST='".$CUST."' and P.SKU='".$SKU."' group by p.sku,p.WHLocation,p.WH,p.cust";
        //return $query;
        $getZoneWiseData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($getZoneWiseData, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function cycleCountId($wh, $Custid){
        $query = "select DISTINCT Batchid FROM ".$this->Tbl_CycleCountBatch." with(nolock) where wh='".$wh."' and Custid='".$Custid."' and isnull(BatchStatus,'')='' and isnull(void,'')='' ";
		
        $skuData = sqlsrv_query($this->myconn2, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $skuData;
        }
        return array();
    }

    public function cycleCountIdData($wh, $Custid, $Batchid){
        $query = "SELECT COUNT(DISTINCT RackLocation) AS TotalAllotBIN ,(SELECT COUNT(DISTINCT RackLocation) FROM ".$this->Tbl_CycleCountBatch." WITH(NOLOCK) WHERE Batchid='".$Batchid."' AND ISNULL(Binstatus,'')='' ) AS OpenForCount FROM Tbl_CycleCountBatch with(nolock) where wh='".$wh."' and Custid='".$Custid."' and isnull(BatchStatus,'')='' AND Batchid='".$Batchid."'";
      
        $skuData = sqlsrv_query($this->myconn2, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $skuData;
        }
        return array();
    }

    public function cycleCountIdGetBin($wh, $Custid, $Batchid){
        $query = "SELECT DISTINCT RackLocation FROM ".$this->Tbl_CycleCountBatch." WITH(NOLOCK) WHERE WH='".$wh."' AND Custid='".$Custid."' AND Batchid='".$Batchid."' AND ISNULL(Binstatus,'')=''";
      
        $skuData = sqlsrv_query($this->myconn2, $query);
        if ($skuData === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $skuData;
        }
        return array();
    }

    public function ScanBin($wh, $Custid, $Batchid, $RackLocation){
        $query = "SELECT RackLocation FROM ".$this->Tbl_CycleCountBatch." with(nolock) WHERE WH='".$wh."' AND Custid='".$Custid."' AND Batchid='".$Batchid."' and RackLocation='".$RackLocation."'  AND ISNULL(Binstatus,'')=''";
      
        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$sacnBinArray = array();
                $sacnBinArray["status"] = array();
				$sacnBinArray["records"]=array();
                //return sqlsrv_num_rows($scanBinData);
			if(sqlsrv_num_rows($scanBinData) > 0){
				 $getData = sqlsrv_query($this->myconn2, "SELECT P.SLOC,  P.WHLOCATION AS WHLOCATION, ISNULL(SUM(p.BALQTY),0) AS AvailForAllocation, p.SKU,(SELECT ISNULL(SUM(QTY), 0) - ISNULL(SUM(PICKQTY), 0) AS Expr1 FROM  ".$this->Tbl_PickingDataRetail." AS r WITH (NOLOCK) WHERE (WH = p.WH) AND (CUSTID = p.CUST) AND (WHLOCATION = p.WHLOCATION) AND (ISNULL(WMSIN, N'') = '') AND (SKU = p.SKU) AND R.SLOC=P.SLOC) AS PendingForPick, ( ISNULL(SUM(P.BALQTY),0) +(SELECT ISNULL(SUM(QTY), 0) - ISNULL(SUM(PICKQTY), 0) AS Expr1  FROM  ".$this->Tbl_PickingDataRetail." AS r WITH (NOLOCK) WHERE (WH = p.WH) AND (CUSTID = p.CUST) AND (WHLOCATION = p.WHLOCATION) AND (ISNULL(WMSIN, N'') = '') AND (SKU = p.SKU) AND R.SLOC=P.SLOC)) AS Total FROM Tbl_putway AS p WITH (NOLOCK) WHERE  (P.WH = '".$wh."') AND (p.CUST = '".$Custid."') AND P.WHLOCATION='".$RackLocation."' GROUP BY p.SKU, p.WH, p.CUST, p.WHLOCATION,P.SLOC HAVING (SUM(p.BALQTY) + (SELECT    ISNULL(SUM(QTY), 0) - ISNULL(SUM(PICKQTY), 0) AS Expr1 FROM   Tbl_PickingDataRetail AS r WITH (NOLOCK) WHERE (WH = p.WH) AND (CUSTID = p.CUST) AND (WHLOCATION = p.WHLOCATION) AND (ISNULL(WMSIN, N'') = '') AND (SKU = p.SKU) AND R.SLOC=P.SLOC)) > 0");
                 
                 while($row = sqlsrv_fetch_array($getData, SQLSRV_FETCH_ASSOC))
                 {
                     $cycleCountDetails =  array(
                         "WHLOCATION"           =>$row['WHLOCATION'],
                         "AvailForAllocation"   =>$row['AvailForAllocation'],
                         "SKU"                  =>$row['SKU'],
                         "PendingForPick"       =>$row['PendingForPick'],
                         "SLOC"                 =>$row['SLOC'],
                         "Total"                =>$row['Total'],
                         "totalScan"            => 0
                     );
                     array_push($sacnBinArray["records"], $cycleCountDetails);
                 }
                 $sacnBinArray["status"] = 1;
				return $sacnBinArray;
			}else{
                $sacnBinArray["status"] = 0;
				$sacnBinArray["message"]="No Data found";
				return $sacnBinArray;
			}
        }
    }
    public function saveBatchScanBin($wh, $Custid, $Batchid, $WHLocation, $CCQTY, $data, $entryBy){
        //return $entryBy;
        $checkWHLocation = "select distinct WHLOCATION from ".$this->Tbl_CycleCountScan." with(nolock)  where BATCHID='".$Batchid."' and wh='".$wh."' and CUSTID='".$Custid."' and  WHLocation='".$WHLocation."'";
       
        $scanBinData = sqlsrv_query($this->myconn2, $checkWHLocation,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				
			if(sqlsrv_num_rows($scanBinData) > 0){
                $sacnBinArray["status"] = 0;
				$sacnBinArray["message"]="Already Saved!";
				return $sacnBinArray;
			}else{
                // Check SKU Exist!
                if($data){
                    foreach($data as $item){
                        $query = sqlsrv_query($this->myconn2, "insert into ".$this->Tbl_CycleCountScan." with(tablock) (wh,CUSTID,BATCHID,WHLOCATION,sku,QTY,ENTRYBY,ENTRYON,SLOC)VALUES('".$wh."','".$Custid."','".$Batchid."','".$WHLocation."','".$item->SKU."','".$item->totalScan."','".$entryBy."',GETDATE(), '".$item->SLOC."' )");
                    }
                }
                    $update = sqlsrv_query($this->myconn2, "UPDATE ".$this->Tbl_CycleCountBatch." WITH(tablock) SET BinBy='".$entryBy."',BinOn=GETDATE(),BinSTATUS='Y',CCQTY='".$CCQTY."' WHERE Batchid='".$Batchid."' AND Custid='".$Custid."' AND RackLocation='".$WHLocation."'");
                   
                if($update==false)
                {
                    $responceArray["status"] = 0;
                    $responceArray["message"] = "New record creation failed! please check errors";
                    return $responceArray;
                }else{
                    $responceArray["status"] = 1;
                    $responceArray["message"] = "New record has beed created";
                    return $responceArray;
                }
			}
        }
    }

    public function getInventroyBox($BOXNO){
       $query = "select count(distinct SKU) as Total_SKU, SUM(QTY) AS BOXQTY,REF_nO,(SELECT DISTINCT PARTYNAME FROM TBL_DN D WITH(NOLOCK) WHERE D.DN_No=P.DNNO AND D.CustID=P.CUSTID) AS PartyName from ".$this->Tbl_Pickingsub." P with(nolock) where BOXNO='".$BOXNO."' GROUP BY REF_nO,DNNO,CUSTID";
       
       $Data = sqlsrv_query($this->myconn2, $query);
       if ($Data === false)
       {
           die(print_r(sqlsrv_errors() , true));
       }else{
       $boxHeadArray = array();
       $boxHeadArray["records"]=array();
       while($row = sqlsrv_fetch_array($Data, SQLSRV_FETCH_ASSOC))
                 {
                     $cycleCountDetails =  array(
                         "Total_SKU" =>$row['Total_SKU'],
                         "BOXQTY"    =>$row['BOXQTY'],
                         "REF_nO"    =>$row['REF_nO'],
                         "PartyName" =>$row['PartyName'],
                     );
                     array_push($boxHeadArray["records"], $cycleCountDetails);
                 }
				return $boxHeadArray;
       }
       return array();
    }

    public function getInventroyBoxTable($BOXNO, $records_per_page, $numer_of_records){
        $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber ='" . $records_per_page . "' SET @RowsOfPage='" . $numer_of_records . "' select SKU,SUM(QTY) AS QTY from ".$this->Tbl_Pickingsub." P with(nolock) where BOXNO='".$BOXNO."' GROUP BY SKU ORDER BY SKU ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
       
        $Data = sqlsrv_query($this->myconn2, $query);

        if ($Data === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $Data;
        }
        return array();
     }
     public function getInventroyBoxTableCount($BOXNO){
        $query = "select COUNT(SKU) as total_rows FROM ".$this->Tbl_Pickingsub." P with(nolock) where BOXNO='".$BOXNO."'";
        $boxCount = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($boxCount, SQLSRV_FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function storeScanRFIDData($wh, $BoxNo, $P_MRN_NO, $CUSTID, $INVOICENO, $store, $entryBy){
         //return $entryBy;
         $checkWHLocation = "select DISTINCT WHLOCATION from ".$this->tbl_retailscanning." WITH(NOLOCK) WHERE WH='".$wh."' AND CUSTID='".$CUSTID."' AND INVOICENO='".$INVOICENO."' AND WHLOCATION='".$BoxNo."'";
       
         $asnStoreData = sqlsrv_query($this->myconn2, $checkWHLocation,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
         if ($asnStoreData === false){
             die(print_r(sqlsrv_errors() , true));
         }else{
                 
             if(sqlsrv_num_rows($asnStoreData) > 0){
                 $asnStoreDataArray["status"] = 0;
                 $asnStoreDataArray["message"]="Already Saved!";
                 return $asnStoreDataArray;
             }else{
                //return "ok";
                 // Check SKU Exist!
                 if($store){
                     foreach($store as $item){
                            if($item->ScanQTy != 0){
                         $query = sqlsrv_query($this->myconn2, "insert into ".$this->tbl_retailscanning." with(tablock)(wh,CUSTID,INVOICENO,SKU,WHLOCATION,QTY,P_MRN_NO,ENTRYBY,ENTRYON,SFlag)values('".$wh."','".$CUSTID."','".$INVOICENO."','".$item->SKU."','".$BoxNo."','".$item->ScanQTy."','".$P_MRN_NO."','".$entryBy."',GETDATE(),'Y')");
                         }
                        }
                 }
                     $update = sqlsrv_query($this->myconn2, "UPDATE ".$this->Tbl_AsnBoxDetailsTemp." WITH(TABLOCK) SET SentFlag='Y' WHERE BOX='".$BoxNo."' AND CUSTINV='".$INVOICENO."' AND WH='".$wh."' AND CUSTID='".$CUSTID."'");
                     //$update == 'true';
                 if($update=='false')
                 {
                     $responceArray["status"] = 0;
                     $responceArray["message"] = "New record creation failed! please check errors";
                     return $responceArray;
                 }else{
                     $responceArray["status"] = 1;
                     $responceArray["message"] = "New record has beed created";
                     return $responceArray;
                 }
             }
         }
    }

    public function ScanTransferBin($wh, $CUST, $WHLOCATION){
        $query = "select distinct whlocation from ".$this->Tbl_putway." where wh='".$wh."' and CUST='".$CUST."' and BALQTY>0 and WHLOCATION='".$WHLOCATION."'";
      //return $query;
        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$sacnBinArray = array();
                $sacnBinArray["status"] = array();
				$sacnBinArray["records"]=array();
			if(sqlsrv_num_rows($scanBinData) > 0){
                 while($row = sqlsrv_fetch_array($scanBinData, SQLSRV_FETCH_ASSOC))
                 {
                     $sacnBinDetails =  array(
                         "WHLOCATION" => $row['whlocation']
                     );
                     array_push($sacnBinArray["records"], $sacnBinDetails);
                 }
                 $sacnBinArray["status"] = 1;
				return $sacnBinArray;
			}else{
                $sacnBinArray["status"] = 0;
				$sacnBinArray["message"]="BIN WRONG OR NO BALANCE QTY";
				return $sacnBinArray;
			}
        }
    }

    public function getskuScanData($wh, $CUST, $WHLOCATION, $sku){ 
        $query = "select distinct whlocation AS BIN,SLOC ,SKU,QTY, BATCHNO,BALQTY AS AVAILABLEQTY from ".$this->Tbl_putway." with(nolock) where wh='".$wh."' and CUST='".$CUST."' and BALQTY>0 and WHLOCATION='".$WHLOCATION."' AND SKU='".$sku."'";
        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$sacnBinArray = array();
                $sacnBinArray["status"] = array();
				$sacnBinArray["records"]=array();
			if(sqlsrv_num_rows($scanBinData) > 0){
                $i = 0;
                 while($row = sqlsrv_fetch_array($scanBinData, SQLSRV_FETCH_ASSOC))
                 {
					$scanBoxsDetails =  array(
                        "ID"                => $i,
						"BIN" 			    => $row['BIN'],
                        "SKU" 			    => $row['SKU'],
                        "QTY" 			    => $row['QTY'],
                        "AVAILABLEQTY" 		=> $row['AVAILABLEQTY'],
                        "SLOC" 		        => $row['SLOC'],
                        "BATCHNO"           =>$row['BATCHNO']

					);
					array_push($sacnBinArray["records"], $scanBoxsDetails);
                    $i++;
				}
                 $sacnBinArray["status"] = 1;
				return $sacnBinArray;
			}else{
                $sacnBinArray["status"] = 0;
				$sacnBinArray["message"]="SKU NOT BELONGS TO BIN OR QTY IS 0";
				return $sacnBinArray;
			}
        }
    }

    

    public function ScanTransferToBin($WH, $Custid, $Location){
        $query = "DECLARE @TestVariable AS VARCHAR(100)='F-Z999' select distinct LOCATION FROM WHLocation WITH(NOLOCK) WHERE WH='".$WH."' AND Custid='".$Custid."' AND ISNULL(CCSTATUS,'')='' AND ISNULL(LOCATIONSTATUS,'')=''  AND Location='".$Location."' UNION select 'F-Z999'  AS LOCATION  WHERE @TestVariable='".$Location."'";
        //return $query;
        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
				$sacnBinArray = array();
                $sacnBinArray["status"] = array();
				$sacnBinArray["records"]=array();
			if(sqlsrv_num_rows($scanBinData) < 0){
                 while($row = sqlsrv_fetch_array($scanBinData, SQLSRV_FETCH_ASSOC))
                 {
					$scanBoxsDetails =  array(
						"LOCATION"  => $row['LOCATION']
					);
					array_push($sacnBinArray["records"], $scanBoxsDetails);
				}
                 $sacnBinArray["status"] = 1;
				return $sacnBinArray;
			}else{
                $sacnBinArray["status"] = 0;
				$sacnBinArray["message"]="Location not found";
				return $sacnBinArray;
			}
        }
    }

    public function saveToBin($WH, $batchNo, $custid, $fromBin, $qty, $sku, $sloc, $toBin, $entryBy){

        $query = "select qty from ".$this->Tbl_putway." WITH(NOLOCK) where sloc='".$sloc."' and whLocation='".$fromBin."' and SKU='".$sku."' AND BATCHNO='".$batchNo."' and cust='".$custid."' AND WH='".$WH."'";
      
        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
			if(sqlsrv_num_rows($scanBinData) > 0){
                $update = sqlsrv_query($this->myconn2,"update ".$this->Tbl_putway." WITH(TABLOCK) set qty=qty-'".$qty."',balqty=isnull(balqty,0)-'".$qty."' where sloc='".$sloc."' and whLocation='".$fromBin."' and SKU='".$sku."' and BATCHNO='".$batchNo."' and cust='".$custid."' AND WH='".$WH."'"); 
             }
        }
        $query = "select qty from ".$this->Tbl_putway." WITH(NOLOCK) where sloc='".$sloc."' and whLocation='".$toBin."' and SKU='".$sku."' AND BATCHNO='".$batchNo."'  and cust='".$custid."' AND WH='".$WH."'";

        $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanBinData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
			if(sqlsrv_num_rows($scanBinData) > 0){
                $update = sqlsrv_query($this->myconn2, "update ".$this->Tbl_putway."  WITH(TABLOCK) set qty=qty+'". $qty ."', balqty=isnull(balqty,0)+'". $qty ."' where sloc='".$sloc."' and whLocation='".$toBin."' and SKU='".$sku."' AND BATCHNO='".$batchNo."' and cust='".$custid."' AND WH='".$WH."'"); 
               
            }else{
                $insert = sqlsrv_query($this->myconn2, "insert into ".$this->Tbl_putway."  WITH(TABLOCK) (SKU,batchNo,whLocation,qty,sloc,cust,wh,balqty)values('".$sku."','".$batchNo."','".$toBin."','".$qty."','".$sloc."','".$custid."','".$WH."','".$qty."')");
            }
        }
        $query = "insert into ".$this->Tbl_Putawaytransfer."  WITH(TABLOCK) (SKU,batchNo,FROMWHLOC,TOWHLOC,qty,sloc,custID,wh,ENTRYBY,ENTRYON,DOCNO)values
        ('".$sku."','".$batchNo."','".$fromBin."','".$toBin."','".$qty."','".$sloc."','".$custid."','".$WH."','".$entryBy."',GETDATE(), '".uniqid()."')";
    
    $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($scanBinData === false){
        die(print_r(sqlsrv_errors() , true));
    }else{
            $sacnBinArray = array();
            $sacnBinArray["status"] = 1;
            $sacnBinArray["message"]= "Transfer Successfully!";
            return $sacnBinArray;
    }

}
public function getAisles($wh, $Custid, $Batchid, $records_per_page, $numer_of_records){
    $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber ='" . $records_per_page . "' SET @RowsOfPage='" . $numer_of_records . "' SELECT  Aisles,COUNT(DISTINCT Location) AS Total_Bin FROM ".$this->Tbl_WHLocation." WITH(NOLOCK) WHERE WH='".$wh."' AND Custid='".$Custid."' AND Location IN ( select DISTINCT RackLocation from Tbl_CycleCountBatch WITH(NOLOCK) where Batchid='".$Batchid."' and ISNULL(BINSTATUS,'')='') group by Aisles ORDER BY Aisles ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
    $Data = sqlsrv_query($this->myconn2, $query);
        if ($Data === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $Data;
        }
        return array();
}

public function getAislesCount($wh, $Custid, $Batchid){
    $query = "SELECT  Aisles,COUNT(DISTINCT Location) as Total_Bin FROM ".$this->Tbl_WHLocation." WITH(NOLOCK) WHERE WH='".$wh."' AND Custid='".$Custid."' AND Location IN ( select DISTINCT RackLocation from ".$this->Tbl_CycleCountBatch." WITH(NOLOCK) where Batchid='".$Batchid."' and ISNULL(BINSTATUS,'')='') group by Aisles";
   $scanAislesData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($scanAislesData === false){
            die(print_r(sqlsrv_errors() , true));
        }else{
                return sqlsrv_num_rows($scanAislesData);
        }
}

public function scanAisles($aisles, $Batchid, $records_per_page, $numer_of_records){
    $query = "DECLARE @PageNumber AS INT DECLARE @RowsOfPage AS INT  SET @PageNumber ='" . $records_per_page . "' SET @RowsOfPage='" . $numer_of_records . "' select DISTINCT RackLocation from ".$this->Tbl_CycleCountBatch." c WITH(NOLOCK) left join WHLocation w  with(nolock) on c.Wh=w.wh and c.Custid=w.Custid and c.RackLocation=w.Location where Batchid='".$Batchid."' and ISNULL(Binstatus,'')='' and w.Aisles='".$aisles."' ORDER BY RackLocation ASC OFFSET (@PageNumber-1) * @RowsOfPage ROWS  FETCH NEXT @RowsOfPage ROWS ONLY";
    $Data = sqlsrv_query($this->myconn2, $query);
        if ($Data === false)
        {
            die(print_r(sqlsrv_errors() , true));
        }else{
            return $Data;
        }
        return array();
}

public function scanAislesCount($aisles, $Batchid){
    $query = "select DISTINCT RackLocation from ".$this->Tbl_CycleCountBatch." c WITH(NOLOCK) left join WHLocation w  with(nolock) on c.Wh=w.wh and c.Custid=w.Custid and c.RackLocation=w.Location where Batchid='".$Batchid."' and ISNULL(Binstatus,'')='' and w.Aisles='".$aisles."'";
    $scanAislesData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
         if ($scanAislesData === false){
             die(print_r(sqlsrv_errors() , true));
         }else{
                 return sqlsrv_num_rows($scanAislesData);
         }
}

public function fromBin($wh, $custId, $frombin){
    $query = "select * from ".$this->Tbl_putway." WITH(NOLOCK) where wh='".$wh."' and cust='".$custId."' and WHLOCATION='".$frombin."' and balqty>0";
   $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $fromBinData = sqlsrv_query($this->myconn2, $query);
    $row = sqlsrv_fetch_array($fromBinData, SQLSRV_FETCH_ASSOC);
   if ($scanBinData === false){
        die(print_r(sqlsrv_errors() , true));
    }else{
            $sacnBinArray = array();
            $sacnBinArray["status"] = array();
            $sacnBinArray["records"]=array();
        if(sqlsrv_num_rows($scanBinData) > 0){
             $query2 = "select sku,batchNo,balqty,sloc,whLocation from ".$this->Tbl_putway." WITH(NOLOCK) where WHLOCATION='".$row['WHLOCATION']."' and cust='".$custId."' and wh='".$wh."' and balqty>0";
            //return $query2;
             $BinData = sqlsrv_query($this->myconn2, $query2,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
             if ($BinData === false){
                die(print_r(sqlsrv_errors() , true));
             }else{
                if(sqlsrv_num_rows($BinData) > 0){
                    while($row = sqlsrv_fetch_array($BinData, SQLSRV_FETCH_ASSOC))
                    {
                       $scanBoxsDetails =  array(
                            "sku"         => $row['sku'],
                            "batchNo"     => $row['batchNo'],
                            "balqty"      => $row['balqty'],
                            "sloc"        => $row['sloc'],
                            "whLocation"  => $row['whLocation']
                       );
                       array_push($sacnBinArray["records"], $scanBoxsDetails);
                   }
                    $sacnBinArray["status"] = 1;
                   return $sacnBinArray;
               }else{
                   $sacnBinArray["status"] = 0;
                   $sacnBinArray["message"]="Location not found";
                   return $sacnBinArray;
               }
             }
        }else{
            $sacnBinArray["status"] = 0;
            $sacnBinArray["message"]="NO STOCK AVAILABLE/ WRONG BIN";
            return $sacnBinArray;
        }
    }
}

public function getBinContent($wh, $Custid, $WHLOCATION, $SKU){
    $query = "select * from ".$this->Tbl_putway." with(NOLOCK) WHERE WH='".$wh."' AND CUST='".$Custid."' AND BALQTY>0 AND WHLOCATION<>'".$WHLOCATION."' AND SKU='".$SKU."'";
  
    $binData = sqlsrv_query($this->myconn2, $query);
    if ($binData === false)
    {
        die(print_r(sqlsrv_errors() , true));
    }else{
        return $binData;
    }
    return array();
}

public function saveBinToBin($wh, $Custid, $tobin, $fromBin, $body, $entryBy){
    $query = "select (SELECT ISNULL(SUM(QTY),0) - ISNULL(SUM(PICKQTY),0) FROM ".$this->Tbl_PickingDataRetail." r WITH(NOLOCK) WHERE r.WH=p.WH AND r.CUSTID=p.CUST AND r.WHLOCATION=p.WHLOCATION  AND ISNULL(WMSIN,'')='' and r.SKU=p.sku ) as PendingForPick from ".$this->Tbl_putway." p WITH(NOLOCK) where p.wh='".$wh."' and CUST='".$Custid."' and p.WHLOCATION='".$fromBin."'  group by p.sku,p.WHLocation,p.WH,p.cust";
    $scanBinData = sqlsrv_query($this->myconn2, $query,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($scanBinData === false){
        die(print_r(sqlsrv_errors() , true));
    }else{
        $fromBinData = sqlsrv_query($this->myconn2, $query);
        $row = sqlsrv_fetch_array($fromBinData, SQLSRV_FETCH_ASSOC);
        if($row['PendingForPick'] != 0){
            $sacnBinArray["status"] = 0;
            $sacnBinArray["message"]="Some Qty is Blocked";
            return $sacnBinArray;
        }else{
            foreach($body as $item){
                $qty = "select qty from ".$this->Tbl_putway." WITH(NOLOCK) where sloc='".$item->sloc."' and whLocation='".$fromBin."' and SKU='".$item->sku."' AND BATCHNO='".$item->batchNo."'  and cust='".$Custid."' AND WH='".$wh."'";
                
                $checkQty = sqlsrv_query($this->myconn2, $qty,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            if ($checkQty === false){
                die(print_r(sqlsrv_errors() , true));
            }else{
                if(sqlsrv_num_rows($checkQty) > 0){
                    $update = sqlsrv_query($this->myconn2, "update ".$this->Tbl_putway." WITH(TABLOCK) set qty=qty-'".$item->balqty."',balqty=isnull(balqty,0)-'".$item->balqty."' where sloc='".$item->sloc."' and whLocation='".$fromBin."' and SKU='".$item->sku."'  AND BATCHNO='".$item->batchNo."' and cust='".$Custid."' AND WH='".$wh."' ");
                }
                
                 $qty2 = "select qty from ".$this->Tbl_putway." WITH(NOLOCK) where sloc='".$item->sloc."' and whLocation='".$tobin."' and SKU='".$item->sku."' AND BATCHNO='".$item->batchNo."' and cust='".$Custid."' AND WH='".$wh."'";
                 
                 $checkQty2 = sqlsrv_query($this->myconn2, $qty2,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                 if ($checkQty2 === false){
                    die(print_r(sqlsrv_errors() , true));
                }else{
                    if(sqlsrv_num_rows($checkQty2) > 0){
                        $update = sqlsrv_query($this->myconn2, "update ".$this->Tbl_putway." WITH(TABLOCK) set qty=qty+'".$item->balqty."', balqty=isnull(balqty,0)+'".$item->balqty."' where sloc='".$item->sloc."' and whLocation='".$tobin."' and SKU='".$item->sku."' AND BATCHNO='".$item->batchNo."'  and cust='".$Custid."' AND WH='".$wh."' ");
                
                    }else{
                        $insert = sqlsrv_query($this->myconn2, "insert into ".$this->Tbl_putway."  WITH(TABLOCK) (SKU,batchNo,whLocation,qty,sloc,cust,wh,balqty)values('".$item->sku."','".$item->batchNo."','".$tobin."','".$item->balqty."','".$item->sloc."','".$Custid."','".$wh."','".$item->balqty."')");
                    }
                }
                }
                $queryMain = "insert into ".$this->Tbl_Putawaytransfer."  WITH(TABLOCK) (SKU,batchNo,FROMWHLOC,TOWHLOC,qty,sloc,custID,wh,ENTRYBY,ENTRYON,DOCNO)values('".$item->sku."','".$item->batchNo."','".$fromBin."','".$tobin."','".$item->balqty."','".$item->sloc."','".$Custid."','".$wh."','".$entryBy."',GETDATE(), '".uniqid()."')";
               
            }
                $scanBinData = sqlsrv_query($this->myconn2, $queryMain,  array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                    if ($scanBinData === false){
                        die(print_r(sqlsrv_errors() , true));
                    }else{
                            $sacnBinArray = array();
                            $sacnBinArray["status"] = 1;
                            $sacnBinArray["message"]= "Save Successfully!";
                            return $sacnBinArray;
                    }
            } 
        }
    
    }
	
    public function getPaging($page, $total_rows, $records_per_page, $page_url)
    {
        // paging array
        $paging_arr = array();
        // button for first page
        $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";
        // count all products in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);
        // range of links to show
        $range = 2;
        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        $paging_arr['pages'] = array();
        $page_count = 0;
        for ($x = $initial_num;$x < $condition_limit_num;$x++)
        {
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages))
            {
                $paging_arr['pages'][$page_count]["page"] = $x;
                $paging_arr['pages'][$page_count]["url"] = "{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x == $page ? "yes" : "no";
                $page_count++;
            }
        }
        // button for last page
        $paging_arr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";
        // json format
        return $paging_arr;
    }
}
?>
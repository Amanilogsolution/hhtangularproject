import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';
import { Observable, Subscription } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class InventoryService {
  selectedWarehouse: Subscription = new Subscription()
  wh: any;
  apiUrl = environment.api;
  constructor(
    private http:HttpClient,
    private commonService:CommonService
  ) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }

  inventory(): Observable<any> {
    return this.http.post(`${this.apiUrl}/inventory_client.php`, {
      whid: this.wh.WHID,
    })
  }

  getSkuInventory(custId: string, sacn: string) {
    return this.http.post(`${this.apiUrl}/getInventoryBySku.php`, {
      whid: this.wh.WHID,
      CUST: custId,
      SKU: sacn
    })
  }
  getLocationInventory(custId: string, sacn: string) {
    return this.http.post(`${this.apiUrl}/getInventoryByLocation.php`, {
      whid: this.wh.WHID,
      CUST: custId,
      location: sacn
    })
  }

  getBatch(clientId:string){
    return this.http.post(`${this.apiUrl}/cycle_count_id.php`, {
      wh: this.wh.WHID,
      Custid: clientId,
    })
  }

  getBatchData(batchId:string ,clientId:string){
    return this.http.post(`${this.apiUrl}/cycle_count_id_data.php`, {
      wh: this.wh.WHID,
      Custid: clientId,
      Batchid: batchId,
    })
  }
  getBinData(data:any){
    return this.http.post(`${this.apiUrl}/getBin.php`, {
      wh: this.wh.WHID,
      Batchid: data.Batchid,
      Custid: data.Custid,
    })
  }
  getAislesData(batchId:string ,clientId:string, pageNumber: number){
    return this.http.post(`${this.apiUrl}/get_cycle_aisles.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      Batchid: batchId,
      Custid: clientId
    })
  }
  scanAislesData(batchId:string ,clientId:string, aisles:string, pageNumber: number){
    return this.http.post(`${this.apiUrl}/scan_cycle_aisles.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      Batchid: batchId,
      Custid: clientId,
      aisles:aisles
    })
  }

  scanBinData(batchId:string ,clientId:string, scanBin:string){
    return this.http.post(`${this.apiUrl}/scanBin.php`, {
      wh: this.wh.WHID,
      Batchid: batchId,
      Custid: clientId,
      RackLocation:scanBin
    })
  }

  saveScanData(data:any){
    return this.http.post(`${this.apiUrl}/saveBatchScan.php`, {
      wh: this.wh.WHID,
      Batchid: data.Batchid,
      Custid: data.Custid,
      WHLocation: data.rackLocation,
      CCQTY: data.totalScan,
      data: data.totalData
    })
  }
 

  getBoxData(scanBox:string, pageNumber:number): Observable<any> {
    return this.http.post(`${this.apiUrl}/getInventoryBox.php?page=${pageNumber}`, {
     // wh: this.wh.WHID,
      BOXNO: scanBox
    })
  }

  scanBin(scanBin:string, clientId:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/scan_transfer_bin.php`, {
      wh: this.wh.WHID,
      CUST: clientId,
      WHLOCATION: scanBin
    })
  }

  scanSku(scanSku:string, clientId:string, whLocation:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/scan_transfer_sku.php`, {
      wh: this.wh.WHID,
      CUST: clientId,
      WHLOCATION: whLocation,
      sku: scanSku
    })
  }

  scanToBin(scantoBin:string, clientId:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/scan_toBin.php`, {
      WH: this.wh.WHID,
      Custid: clientId,
      Location: scantoBin
    })
  }
  saveToBin(tblSloc:string, clientId:string, whLocation:string, sku:string, qty:number, toBin:string, BATCHNO:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/save_toBin.php`, {
      WH: this.wh.WHID,
      custid: clientId,
      sloc: tblSloc,
      fromBin: whLocation,
      sku: sku,
      qty: qty,
      toBin: toBin,
      batchNo: BATCHNO


    })
  }
  fromBin(scanBin:string, clientId:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/from_bin.php`, {
      wh: this.wh.WHID,
      Custid: clientId,
      frombin: scanBin
    })
  }
  getBinContent(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/getBinContent.php`, {
      wh: this.wh.WHID,
      Custid: data.clientId,
      SKU: data.sku,
      WHLOCATION: data.whLocation
    })
  }

  saveBinToBin(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/save_bin_to_bin.php`, {
      wh: this.wh.WHID,
      Custid: data.custId,
      tobin: data.tobin,
      fromBin: data.fromBin,
      body: data.body
    })
  }
}



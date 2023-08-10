import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BoxService {
  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any

  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }

  getPendingBox(page: any, dnno?: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/pendingBox.php?page=${page}`, {
      locationId: this.wh.WHID,
      
      dnno
    })

  }
  getZoneWiseData(custinvno: string, custid: string, dnno: string, pageNumber: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/get_zone_wise_data.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      custinvno, custid, dnno
    })
  }

  getAislesData(obj: any, pageNumber = 1): Observable<any> {
    return this.http.post(`${this.apiUrl}/get_aisles_data.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      ...obj
    })

  }
  getTrackData(custinvno: string, custid: string, zoneBarcode: string, InvQty: number, PickQTy: string, picklist: string, aisles: string, pageNumber: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/get_track_data.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      custinvno, custid, zoneBarcode, aisles
    })

  }

  getPickListData(dnno: string, custinvno: string, pageNumber: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/box_picklist_details.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      dnno, custinvno
    })
  }
  getRackLocationData(picklistId: string, rackLocation: string, pageNumber: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/box_racklocation.php?page=${pageNumber}`, {

      wh: this.wh.WHID,
      dnno: picklistId,
      whlocation: rackLocation

    })
  }
  getNewRackLocation(picklistId: string, rackLocation: string, custid: string, zoneBarcode: string, aisles: string, pageNumber: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/new_box_racklocation.php?page=${pageNumber}`, {

      wh: this.wh.WHID,
      dnno: picklistId,
      whlocation: rackLocation,
      custid: custid,
      zoneBarcode: zoneBarcode,
      aisles: aisles

    })
  }
  totalScan(dnno: string, custid: string,): Observable<any> {
    return this.http.post(`${this.apiUrl}/total_scan.php`, {
      wh: this.wh.WHID,
      dnno, custid

    })
  }
  totalAislesScan(dnno: string, custid: string, zoneBarcode: string, aisles: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/total_aisles_scan.php`, {
      wh: this.wh.WHID,
      dnno, custid, zoneBarcode, aisles

    })
  }
  updateSKUData(picklistId: string, rackLocation: string, sku: string, qty: string, batchno: string, boxno: string, custinvno: string, custid: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/updateBoxData.php`, {
      wh: this.wh.WHID,
      dnno: picklistId,
      whlocation: rackLocation,
      sku: sku,
      qty: +qty,
      batchno: batchno,
      boxno,
      custinvno,
      custid
    })
  }
  getSKUResults(skuCode: string, pageNumber: number, picklistId: string, rackLocation: string, batchno: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/boxskuData.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      skuCode: skuCode,
      dnno: picklistId,
      whlocation: rackLocation,
      batchno: batchno
    })

  }
  validateBox(boxno: string) {
    return this.http.post(`${this.apiUrl}/boxNumValidation.php`, {
      whid: this.wh.WHID,
      boxno
    })
  }
  newValidateBox(boxno: string, custid: string, REF_nO: string) {
    return this.http.post(`${this.apiUrl}/newBoxNumValidation.php`, {
      whid: this.wh.WHID,
      boxno, custid, REF_nO
    })
  }
  scanBox(boxno: string, custid: string, REF_nO: string) {
    return this.http.post(`${this.apiUrl}/newBoxNumValidation.php`, {
      whid: this.wh.WHID,
      boxno, custid, REF_nO
    })
  }
  validateLocation(location: string, dnno: string) {
    return this.http.post(`${this.apiUrl}/validateRackLocation.php`, {
      whid: this.wh.WHID,
      location,
      dnno
    })
  }
  closePicListBox(boxno: string, custid: string) {
    return this.http.post(`${this.apiUrl}/closeBox.php`, {
      wh: this.wh.WHID,
      boxno,
      custid
    })
  }
  validateZone(obj: any) {
    return this.http.post(`${this.apiUrl}/validateZone.php`, {
      whid: this.wh.WHID,
      location,
      ...obj
    })
  }
}

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PickListService {
  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any

  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }

  getPendingPickList(page: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/picklist.php?page=${page}`,{
      locationId:this.wh.WHID
    })

  }
  getPickListData(picklistId: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/picklist_details.php`, {
      wh: this.wh.WHID,
      dnno: picklistId
    })
  }
  getRackLocationData(picklistId: string, rackLocation: string, pageNumber:number): Observable<any> {
    return this.http.post(`${this.apiUrl}/racklocation.php?page=${pageNumber}`, {

      wh: this.wh.WHID,
      dnno: picklistId,
      whlocation: rackLocation

    })
  }
  getSKUData(picklistId:string, rackLocation: string, sku: string, qty: string, batchno:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/skuUpdate.php`, {
      wh: this.wh.WHID,
      dnno: picklistId,
      whlocation: rackLocation,
      sku: sku,
      qty: qty,
      batchno:batchno
    })
  }

  getSKUResults(skuCode:string, pageNumber:number,picklistId:string, rackLocation: string,batchno:string): Observable<any>{
    return this.http.post(`${this.apiUrl}/skuData.php?page=${pageNumber}`, {
      wh: this.wh.WHID,
      skuCode:skuCode,
      dnno:picklistId,
      whlocation:rackLocation,
      batchno:batchno
    })

  }
}

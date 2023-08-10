import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';
import { Observable, Subscription } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AsnScanBoxService {
  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any;
 
  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }


     getAsnScanData(scanBox:string, pageNumber:number): Observable<any> {
      return this.http.post(`${this.apiUrl}/getAsnScanData.php?page=${pageNumber}`, {
        wh: this.wh.WHID,
        BoxNo: scanBox
      })
  
    }

    getScanSKUData(WHLocation:string, ASN_no:string, custid:string, Custinv:string, qty:string, TotalBoxQty:string, sku:string): Observable<any>{
      return this.http.post(`${this.apiUrl}/updateASNScanSku.php`, {
        whid: this.wh.WHID,
        WHLocation: WHLocation,
        asnNo:ASN_no,
        custId:custid,
        invoiceNo:Custinv,
        qty:qty,
        scannedQty:TotalBoxQty,
        sku:sku
      })
    }
}

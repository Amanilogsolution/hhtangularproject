import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';
import { Observable, Subscription } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MainAsnService {
  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any;

   constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }


     getAsnScanData(scanBox:string): Observable<any> {
      return this.http.post(`${this.apiUrl}/getAsnScanRFID.php`, {
        wh: this.wh.WHID,
        BoxNo: scanBox
      })
    }

    saveScanData(data:any): Observable<any> {
      return this.http.post(`${this.apiUrl}/storeScanRFIDData.php`, {
        wh: this.wh.WHID,
        BoxNo: data.scanBox,
        P_MRN_NO: data.scanData[0]['ASN_no'],
        CUSTID: data.scanData[0]['custid'],
        INVOICENO: data.scanData[0]['Custinv'],
        store: data.scanTableData
      })
    }
}

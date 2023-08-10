import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PutawayService {
  wh: any;
  apiUrl = environment.api
  constructor(
    private http: HttpClient,
    private commonService:CommonService
  ) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }
  validateLPN(data){
    let obj={...data, wh: this.wh.WHID}
    return this.http.post(`${this.apiUrl }/validateLpa.php`,obj)
  }
  validateRackLocation(data){
    let obj={...data, wh: this.wh.WHID}
    return this.http.post(`${this.apiUrl }/validateRackLocation.php`,obj)
  }
  validateASNSKU(data){
    let obj={...data, wh: this.wh.WHID}
    return this.http.post(`${this.apiUrl }/ValidskuInLpn.php`,obj)
  }
  updateTempPutWay(data){
    let obj={...data, wh: this.wh.WHID}
    return this.http.post(`${this.apiUrl }/updateTempPutWay.php`,obj)
  }
}

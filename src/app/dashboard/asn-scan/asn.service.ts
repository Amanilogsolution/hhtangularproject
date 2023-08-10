import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AsnService {
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
  getAsnList(term:string, pageNumber:string, perPage="5"){
    return this.http.get(`${this.apiUrl}/asnlist.php`,{
      params:{
        term,
        pageNumber,
        whid: this.wh.WHID,
        perPage
      }
    })
  }
  validateBox(data:any){
    let obj={...data, whid: this.wh.WHID,}
    return this.http.get(`${this.apiUrl}/validateBox.php`,{
      params:obj,

    })
  }
  validateSKU(data:any){
    let obj={...data, whid: this.wh.WHID,}
    return this.http.get(`${this.apiUrl}/validateSKU.php`,{
      params:obj,

    })
  }
  updateSku(data:any){
    let obj={...data, whid: this.wh.WHID,wh:this.wh}
    return this.http.post(`${this.apiUrl}/updateASNSku.php`,obj)
  }
}

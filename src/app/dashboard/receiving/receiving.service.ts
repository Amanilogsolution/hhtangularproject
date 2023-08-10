import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';


@Injectable({
  providedIn: 'root'
})
export class ReceivingService {

  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any

  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }


  getOpenASN(page: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/open-asn.php?page=${page}`,{
      locationId:this.wh.WHID
    })

  }
  findASN(asn:string){
    return this.http.post(`${this.apiUrl}/find-asn.php`,{
      locationId:this.wh.WHID,
      asnno:asn
    })

  }

  uploadASN(data:string, asn:string,userId:string){
    return this.http.post(`${this.apiUrl}/upload-asn.php`,{
      locationId:this.wh.WHID,
      asnno:asn,
      data:data,
      userId:userId
    })

  }
}

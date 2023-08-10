import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DispatchService {


  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any

  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }


  getOpenMDN(page: number): Observable<any> {
    console.log(this.wh.WHID)
    return this.http.post(`${this.apiUrl}/open-mdn.php?page=${page}`,{
      locationId:this.wh.WHID
    })
  }
  findMDN(mdnno:string){
    return this.http.post(`${this.apiUrl}/find-mdn.php`,{
      locationId:this.wh.WHID,
      mdnno:mdnno
    })

  }

  uploadMDN(data:string, mdnno:string,userId:string){
    return this.http.post(`${this.apiUrl}/upload-mdn.php`,{
      locationId:this.wh.WHID,
      mdn:mdnno,
      data:data,
      userId:userId
    })

  }
}

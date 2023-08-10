import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonService } from 'src/app/common/common.service';
import { environment } from 'src/environments/environment';
import { Observable, Subscription } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SkuWeightUpdationService {
  selectedWarehouse: Subscription = new Subscription()
  apiUrl = environment.api;
  wh: any;

  constructor(private http: HttpClient,
    private commonService: CommonService) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.wh = res
    })
  }

  getSkuWeightData(skuName:string): Observable<any> {
    return this.http.post(`${this.apiUrl}/getSkuWeightData.php`, {
      sku_Name: skuName
    })

  }

  updateSkuWeigh(skuName:string, weight:string):Observable<any> {
    return this.http.post(`${this.apiUrl}/updateSkuWeight.php`, {
      sku_Name: skuName,
      weight: weight
    })
  }
}

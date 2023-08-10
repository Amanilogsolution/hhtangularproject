import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { SkuWeightUpdationService } from './sku-weight-updation.service';

@Component({
  selector: 'app-sku-weight-updation',
  templateUrl: './sku-weight-updation.component.html',
  styleUrls: ['./sku-weight-updation.component.scss']
})
export class SkuWeightUpdationComponent implements OnInit {
  @ViewChild ('f',{static:false}) f: NgForm;
  @ViewChild ('f1',{static:false}) f1: NgForm;

  hhtScanEnabled:boolean= true;
  skuName:string = '';
  weight:string = '';
  valideSku:string ='';

  constructor( private SkuWeightUpdationService: SkuWeightUpdationService) { }

  ngOnInit(): void {
  }

  hhtScanToggle(e){
    this.hhtScanEnabled=e.checked
  }
  search(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.skuName = form.value.skuName;
    this.SkuWeightData(form.value.skuName)
  }

  changeLPN(e){
    if(this.hhtScanEnabled){
      this.skuName = e.target.value;
      this.SkuWeightData(e.target.value)
     
    }
  }

  SkuWeightData(skuName:string){
    this.SkuWeightUpdationService.getSkuWeightData(skuName).subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      if(res.status == 0){
        alert(res.message)
        this.valideSku = null;
        this.f.reset();
      }
      if(res.status ==1){
        this.valideSku = res.status;
      }
    })
  }

  updateSku(form:NgForm){
    if (!form.valid) {
      return
    }
    this.weight = form.value.weight;
    console.log(form.value.weight);
    this.Update(this.skuName, form.value.weight);
  }
  UpdateLPN(e){
    if(this.hhtScanEnabled){
      console.log(e.target.value);
      this.Update(this.skuName, e.target.value)
    }
  }

  Update(skuName:string, weight:string){
    this.SkuWeightUpdationService.updateSkuWeigh(skuName,weight).subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      if(res.status == 0){
        alert(res.message)
        this.f1.reset();
      }else{
        alert(res.message)
        this.valideSku = null;
        this.f.reset();
        this.f1.reset();
      }

    })
  }

}

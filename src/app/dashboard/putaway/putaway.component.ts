import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ScanModalComponent } from 'src/app/common/scan-modal/scan-modal.component';
import { PutawayService } from './putaway/putaway.service';

@Component({
  selector: 'app-putaway',
  templateUrl: './putaway.component.html',
  styleUrls: ['./putaway.component.scss']
})
export class PutawayComponent implements OnInit {
  @ViewChild ('f',{static:false}) f: NgForm;
  @ViewChild ('f1',{static:false}) f1: NgForm;
  @ViewChild ('f2',{static:false}) f2: NgForm;
  @ViewChild ('f3',{static:false}) f3: NgForm;
  lpnValid:boolean=false;
  lpn:string;
  location:string;
  rackValid:boolean=false;
  hhtScanEnabled:boolean= true;
  lpndata:any;
  skuData:any;
  showform:boolean=false;
  sku;
  isScanning:boolean= false;
  constructor(
    private putawaService:PutawayService,
    public dialog: MatDialog,
  ) { }

  ngOnInit(): void {
  }
  hhtScanToggle(e){
    this.hhtScanEnabled=e.checked
  }
  findLPN(form:NgForm){
    if(!form.valid){
      return
    }
    this.validateLPN(form.value.WhLocation)
  }
  scanLPN(){
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.validateLPN(res)
      }
    })
  }
  validateLPN(WhLocation){

    if( this.isScanning ){
      return;
    };
    this.isScanning = true
    this.putawaService.validateLPN({WhLocation}).subscribe(
      (res:any)=>{
        this.isScanning = false
        this.lpnValid = true;
        this.lpn = res.lpalist.records[0].lpn;
        this.lpndata=res.lpalist.records[0]
      },err=>{
        this.isScanning = false
        this.lpnValid = false;
        this.lpn = null;
        this.lpndata=null;
        this.f.resetForm()

        alert(`Invalid LPN ${WhLocation}`);
      }
    )

  }
  changeLPN(e){
    this.lpnValid = false;
    this.showform= false;
    this.rackValid= false;
    if(this.hhtScanEnabled){
      this.validateLPN(e.target.value)
    }
  }
  findRack(form:NgForm){
      if(!form.valid){
        return
      }
      this.validateRack(form.value.location)
  }
  scanRack(){
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.validateRack(res)
      }
    })
  }
  validateRack(location){
    if( this.isScanning ){
      return;
    };
    this.isScanning = true
   const obj={
     location,
     custId:this.lpndata.custId
    }
    this.putawaService.validateRackLocation(obj).subscribe(
      res=>{
        this.rackValid = true;
        this.location = location;
        this.isScanning = false;
      },err=>{
        this.rackValid = false;
        this.location = null;
        this.isScanning = false;
        this.f1.resetForm();
        alert(`Invalid  Rack ${location}`)
      }
    )
  }
  changeRack(e){
    this.rackValid= false;
    this.showform= false;
    if(this.hhtScanEnabled){
      this.validateRack(e.target.value)
    }
  }
  findSku(form:NgForm){
    if(!form.valid){
      return
    }
    this.validateSKU(form.value.sku)
  }
  scanSKU(){
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.validateSKU(res)
      }
    })
  }
  validateSKU(sku:string){
    if( this.isScanning ){
      return;
    };
    this.isScanning = true
    const obj={
      sku,
      custId:this.lpndata.custId,
       WhLocation:this.lpn,
       ...this.lpndata
     }
     this.putawaService.validateASNSKU(obj).subscribe(
       (res:any)=>{
         this.skuData=res.skuInLpalist.records[0];
         this.showform=true;
         this.sku=sku;
         this.isScanning = false
       },err=>{
         alert(`Invalid SKU ${sku}`);
         this.showform=false;
         this.sku=null;
         this.f2.resetForm();
         this.isScanning = false;

       }
     )
  }
  changeSKU(e){
    this.showform= false;
    if(this.hhtScanEnabled){
      this.validateSKU(e.target.value)
    }
  }
  updateQty(form:NgForm){
    if(!form.valid){
      return;
    }
    if(this.skuData.totalPutWay+form.value.qty > this.skuData.TotLPNSKUQty){
      alert(`Max allowed Qty: ${this.skuData.TotLPNSKUQty - this.skuData.totalPutWay}`)
      return;
    }
    let obj={
      sku:this.sku,
      custId:this.lpndata.custId,
       boxId:this.lpn,
      qty:form.value.qty,
      location:this.location,
      ...this.lpndata

    }

    this.putawaService.updateTempPutWay(obj).subscribe(
      (res:any)=>{
        this.lpndata.TotalPutwayQty = this.lpndata.TotalPutwayQty+form.value.qty;
        this.showform=false;
        this.rackValid= false;
        this.f1.resetForm();
        this.f2.resetForm();
        this.f3.resetForm();

        alert("Location Updated Successfully");
      },err=>{
        this.f3.resetForm();
        alert(`Invalid SKU ${this.sku}`);
        this.showform=false;

      }
    )

  }
}

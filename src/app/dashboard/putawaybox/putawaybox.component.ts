import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { PutawayboxService } from './putawaybox.service';

@Component({
  selector: 'app-putawaybox',
  templateUrl: './putawaybox.component.html',
  styleUrls: ['./putawaybox.component.scss']
})
export class PutawayboxComponent implements OnInit {
  @ViewChild ('f',{static:false}) f: NgForm;
  @ViewChild ('f1',{static:false}) f1: NgForm;
  @ViewChild ('f2',{static:false}) f2: NgForm;
  hhtScanEnabled:boolean= true;
  rackValid:boolean=false;
  lpndata:any;
  lpnGridData:any;
  isScanning:boolean= false;
  location:string;
  lpn:string;

  constructor(private PutawayboxService:PutawayboxService) { }

  ngOnInit(): void {
  }
  hhtScanToggle(e){
    this.hhtScanEnabled=e.checked
  }
  findLPNBOX(form:NgForm){
    if(!form.valid){
      return
    }
    //console.log(form.value.WhLocation);
    this.validateLPN(form.value.WhLocation)

  }

  validateLPN(WhLocation){

    if( this.isScanning ){
      return;
    };
    this.isScanning = true
    this.PutawayboxService.validateLPN({WhLocation}).subscribe(
      (res:any)=>{
        this.isScanning = false
        this.lpndata=res.lpalist.records[0]
        this.lpnGridData=res.gridData.records;
        this.lpn = res.lpalist.records[0].lpn;
      },err=>{
        this.isScanning = false
        this.lpndata=null;
        this.lpnGridData=null;
        this.lpn = null;
        this.f.resetForm()

        alert(`Invalid LPN ${WhLocation}`);
      }
    )

  }

  changeLPN(e){
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
validateRack(location){
  if( this.isScanning ){
    return;
  };
  this.isScanning = true
 const obj={
   location,
   custId:this.lpndata.custId
  }
  this.PutawayboxService.validateRackLocation(obj).subscribe(
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
  if(this.hhtScanEnabled){
    this.validateRack(e.target.value)
  }
}

updateQty(form:NgForm){

  if(this.lpndata.TotalPutwayQty >= this.lpndata.TotLPNQty){
    alert('Already Updated');
  }else{
  let obj={
    skuData:this.lpnGridData,
    custId:this.lpndata.custId,
     boxId:this.lpn,
    location:this.location,
    ...this.lpndata

  }
  this.PutawayboxService.updateTempPutWay(obj).subscribe(
    (res:any)=>{
      this.f.resetForm();
      this.f1.resetForm();
      this.lpndata=null;
      this.location = null;
      this.lpnGridData = null;
      alert(res.message);
    },err=>{
      alert('Went Something Wrong');
    }
  )
}
  }
  


}



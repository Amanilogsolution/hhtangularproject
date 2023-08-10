
import { Component, OnInit, Inject } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { AsnScanBoxService } from '../asn-scan-box.service';

@Component({
  selector: 'app-asn-scan-manual-update',
  templateUrl: './asn-scan-manual-update.component.html',
  styleUrls: ['./asn-scan-manual-update.component.scss']
})
export class AsnScanManualUpdateComponent implements OnInit {
  qty:string=""

  constructor(
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<AsnScanManualUpdateComponent>,
    private AsnScanBoxService:AsnScanBoxService
  ) { }

  ngOnInit(): void {
  }

  updateSku(form:NgForm){
    if(!form.valid){
      return;
    }
   // console.log(this.data);
     this.qty = JSON.stringify(form.value.qty);
     //console.log(this.data, this.qty);
    this.updateQty()

  }

  // decrementByOne(){
  //   this.qty = "-1";
  //   this.updateQty()

  // }
  // incrementByOne(){
  //   this.qty = "+1";
  //   this.updateQty()
  // }

  updateQty(){
    if(this.data.TotalBoxQty < this.qty){
      alert("You cant update Qty greater than "+this.data.TotalBoxQty);
    }else {
      //console.log(this.data.scanData.ASN_no);
     // console.log(this.data);
       this.AsnScanBoxService.getScanSKUData(this.data.WHLocation, this.data.scanData.ASN_no,  this.data.scanData.custid, this.data.scanData.Custinv, this.qty, this.data.TotalBoxQty, this.data.sku)
    .subscribe(res => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.dialogRef.close();


    })
    }
   
  }
}

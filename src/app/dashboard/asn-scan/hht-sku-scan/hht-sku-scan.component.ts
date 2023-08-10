import { Component, Inject, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { AsnService } from '../asn.service';

@Component({
  selector: 'app-hht-sku-scan',
  templateUrl: './hht-sku-scan.component.html',
  styleUrls: ['./hht-sku-scan.component.scss']
})
export class HhtSkuScanComponent implements OnInit {

  constructor(
    private asnService: AsnService,
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<HhtSkuScanComponent>,
  ) { }

  ngOnInit(): void {
  }

  scanDone(form:NgForm){

    if (1 > (+this.data.asnQty - this.data.scanQty)) {
      alert(`Allowed max value:${+this.data.asnQty - this.data.scanQty}`)
      return;
    }
      let obj ={
          asnNo: this.data.asnNo,
          WHLocation: this.data.WHLocation,
          sku: form.value.sku,
          custId: this.data.custId,
          invoiceNo: this.data.invoiceNo,
          scannedQty:this.data.scanQty+1,
          qty:1
        }
        this.asnService.updateSku(obj).subscribe(res=>{
          this.data.scanQty = obj.scannedQty;

        },err=>  alert("Updation failed"))
  }

}

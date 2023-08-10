import { Component, Inject, OnInit } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { AsnService } from 'src/app/dashboard/asn-scan/asn.service';

@Component({
  selector: 'app-scan-modal',
  templateUrl: './scan-modal.component.html',
  styleUrls: ['./scan-modal.component.scss']
})
export class ScanModalComponent implements OnInit {

  constructor(
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<ScanModalComponent>,

    private asnService:AsnService
  ) { }

  ngOnInit(): void {
  }

  newResult(data: any) {
    if(data && data.success){
      this.dialogRef.close(data.result);
      // if(data.type !==   "hhtASNscanQty"){
      // this.dialogRef.close(data.result)
      // }else{
      //   let obj ={
      //     asnNo: this.data.asnNo,
      //     WHLocation: this.data.boxNo,
      //     sku: this.data.sku,
      //     custId: this.data.custId,
      //     invoiceNo: this.data.invoiceNo,
      //     scannedQty:this.data.scanQty+1,
      //     qty:1
      //   }
      //   this.asnService.updateSku(obj).subscribe(res=>{

      //   },err=>  alert("Updation failed"))

      // }
    }
  }
}

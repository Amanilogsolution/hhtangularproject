import { Component, Inject, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { PickListService } from '../../../pick-list.service';

@Component({
  selector: 'app-manual-update',
  templateUrl: './manual-update.component.html',
  styleUrls: ['./manual-update.component.scss']
})
export class ManualUpdateComponent implements OnInit {
  qty:string=""

  constructor(
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<ManualUpdateComponent>,
    private picklistService:PickListService
  ) { }

  ngOnInit(): void {
  }
  updateSku(form:NgForm){
    if(!form.valid){
      return;
    }
    this.qty = JSON.stringify(form.value.qty);

   this.updateQty()

  }
  decrementByOne(){
    this.qty = "-1";
    this.updateQty()

  }
  incrementByOne(){
    this.qty = "+1";
    this.updateQty()
  }
  updateQty(){
    this.picklistService.getSKUData(this.data.pickList, this.data.rackLocation, this.data.sku, this.qty, this.data.batchno)
    .subscribe(res => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.dialogRef.close();


    })
  }
}

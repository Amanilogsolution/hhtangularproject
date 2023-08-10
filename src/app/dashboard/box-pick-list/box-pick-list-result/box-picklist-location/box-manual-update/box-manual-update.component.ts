import { Component, Inject, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { BoxService } from '../../../box.service';

@Component({
  selector: 'app-box-manual-update',
  templateUrl: './box-manual-update.component.html',
  styleUrls: ['./box-manual-update.component.scss']
})
export class BoxManualUpdateComponent implements OnInit {
  inputdata:any=""
  constructor(
    private boxService:BoxService,
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<BoxManualUpdateComponent>,
    private snackbar:MatSnackBar
  ) { }

  ngOnInit(): void {
  }
  updateSku(form:NgForm){
    if(!form.valid){
      return;
    }
    this.inputdata = form.value;

   this.updateQty()

  }
  decrementByOne(){
    this.inputdata.qty = "-1";
    this.updateQty()

  }
  incrementByOne(){
    this.inputdata.qty = "+1";
    this.updateQty()
  }
  updateQty(){
    console.log({...this.data,...this.inputdata})
    this.boxService.updateSKUData(this.data.pickList, this.data.location, this.inputdata.skuCode,this.inputdata.qty,"", this.data.boxno,this.data.custinvno,this.data.custid).subscribe((res: any) => {
      //console.log(res)
      this.snackbar.open(res.message, "Dismiss");
      if (res.status == 1) {

       this.dialogRef.close("success")
      }
    }, (err: any) => {
      let msg = "Something went wrong, please try again"
      if (err.message) {
        msg = err.error.message

      }
      this.snackbar.open(msg, "Dismiss");
    })
   }
}

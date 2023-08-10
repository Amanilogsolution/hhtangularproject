import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { BoxService } from '../../box.service';

@Component({
  selector: 'app-close-box',
  templateUrl: './close-box.component.html',
  styleUrls: ['./close-box.component.scss']
})
export class CloseBoxComponent implements OnInit {

  constructor(
    private boxService:BoxService,
    @Inject(MAT_DIALOG_DATA) public data:any,
    public dialogRef: MatDialogRef<CloseBoxComponent>
   
  ) { }

  ngOnInit(): void {
  }
  onNoClick(): void {
    this.dialogRef.close();
  }

  onClick(){
    this.CloseBox();
  }

  CloseBox(){
    this.boxService.closePicListBox(this.data.boxno,this.data.custid).subscribe((res: any) => {
     
      if (res.status == 1) {
       this.dialogRef.close("success")

      }
    }, (err: any) => {
      let msg = "Something went wrong, please try again"
      if (err.message) {
        msg = err.error.message
      }
     
    })
   }
}

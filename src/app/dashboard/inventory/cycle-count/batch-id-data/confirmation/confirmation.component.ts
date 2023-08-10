import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { InventoryService } from '../../../inventory.service';
import { MatSnackBar } from "@angular/material/snack-bar";

@Component({
  selector: 'app-confirmation',
  templateUrl: './confirmation.component.html',
  styleUrls: ['./confirmation.component.scss']
})
export class ConfirmationComponent implements OnInit {

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  public dialogRef: MatDialogRef<ConfirmationComponent>,
  private InventoryService:InventoryService,
  private snackbar: MatSnackBar,
  ) { }

  ngOnInit(): void {
    //console.log(this.data);
  }

  saveData(){
    //console.log("save_data");
    this.saveScan(this.data)
  }
  saveScan(data: string) {
    this.InventoryService.saveScanData(data).subscribe(
      (res: any) => {
        if (res.status === 0) {
          this.snackbar.open(res.message, "Dismiss");
        }
        if (res.status === 1) {
          this.snackbar.open(res.message, "OK");
          
        }
      },
      (err) => {
        this.snackbar.open(
          err.error.message || "Something went wrong",
          "dismiss"
        );
      }
    );
  }

  onNoClick(): void {
    //console.log('close');
    this.dialogRef.close('close');
  }

}

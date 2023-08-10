import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatSnackBar } from "@angular/material/snack-bar";
import { MainAsnService } from '../../main-asn.service';

@Component({
  selector: 'app-rfid-confirmation',
  templateUrl: './rfid-confirmation.component.html',
  styleUrls: ['./rfid-confirmation.component.scss']
})
export class RfidConfirmationComponent implements OnInit {

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  public dialogRef: MatDialogRef<RfidConfirmationComponent>,
  private MainAsnService:MainAsnService,
  private snackbar: MatSnackBar,
  ) { }

  ngOnInit(): void {
    //console.log(this.data);
  }

  saveData(){
    console.log("save_data");
    this.saveScan(this.data)
  }
  saveScan(data: string) {
    this.MainAsnService.saveScanData(data).subscribe(
      (res: any) => {
        if (res.status === 0) {
          this.snackbar.open(res.message, "Dismiss");
        }
        if (res.status === 1) {
          this.snackbar.open(res.message, "Dismiss");
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

}

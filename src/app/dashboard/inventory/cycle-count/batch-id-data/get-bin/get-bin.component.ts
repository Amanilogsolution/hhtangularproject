import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { InventoryService } from '../../../inventory.service';
import { MatSnackBar } from "@angular/material/snack-bar";

@Component({
  selector: 'app-get-bin',
  templateUrl: './get-bin.component.html',
  styleUrls: ['./get-bin.component.scss']
})
export class GetBinComponent implements OnInit {
  error:boolean= false;
  errorMsg:string='';
  RackLocation:any='';

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  public dialogRef: MatDialogRef<GetBinComponent>,
  private InventoryService:InventoryService,
  private snackbar: MatSnackBar,) { }

  ngOnInit(): void {
    //console.log(this.data);
    this.getBinDetails(this.data);
  }

 

  getBinDetails(data: string) {
    this.InventoryService.getBinData(data).subscribe(
      (res: any) => {
        if (res.status === 0) {
          this.snackbar.open(res.message, "Dismiss");
        }
        if (res.status === 1) {
          if (res.rackLocationData.records == "") {
            this.snackbar.open(res.message, "No Records has been found!");
            this.RackLocation = null;
          } else {
            this.RackLocation = res.rackLocationData.records;
          }
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

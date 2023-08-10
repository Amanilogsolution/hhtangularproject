import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { InventoryService } from '../../../inventory.service';
import { MatSnackBar } from "@angular/material/snack-bar";

@Component({
  selector: 'app-get-btn-bin',
  templateUrl: './get-btn-bin.component.html',
  styleUrls: ['./get-btn-bin.component.scss']
})
export class GetBtnBinComponent implements OnInit {

  btnBinData: any ='';

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  private InventoryService: InventoryService,
  private snackbar: MatSnackBar,
  public dialogRef: MatDialogRef<GetBtnBinComponent>,
  ) {
    
   }

  ngOnInit(): void {
this.getBin(this.data);
  }

  getBin(data: string){
    this.InventoryService.getBinContent(data).subscribe(
      (res: any) => {
        if (res.status == 1) {
          this.btnBinData = res.binData.records;
        }else {
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

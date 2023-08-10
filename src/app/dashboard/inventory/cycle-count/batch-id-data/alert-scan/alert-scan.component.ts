import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { InventoryService } from '../../../inventory.service';
import { MatSnackBar } from "@angular/material/snack-bar";

@Component({
  selector: 'app-alert-scan',
  templateUrl: './alert-scan.component.html',
  styleUrls: ['./alert-scan.component.scss']
})
export class AlertScanComponent implements OnInit {

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  public dialogRef: MatDialogRef<AlertScanComponent>,
  private InventoryService:InventoryService,
  private snackbar: MatSnackBar,
  ) { }

  ngOnInit(): void {
  }

}

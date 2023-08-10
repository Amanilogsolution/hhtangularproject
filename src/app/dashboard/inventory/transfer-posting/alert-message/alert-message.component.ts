import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'app-alert-message',
  templateUrl: './alert-message.component.html',
  styleUrls: ['./alert-message.component.scss']
})
export class AlertMessageComponent implements OnInit {

  constructor( @Inject(MAT_DIALOG_DATA) public data:any,
  public dialogRef: MatDialogRef<AlertMessageComponent>,
  ) { }

  ngOnInit(): void {
  }

}

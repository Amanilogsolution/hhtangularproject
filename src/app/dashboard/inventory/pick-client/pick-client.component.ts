import { Component, OnInit ,Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'app-pick-client',
  templateUrl: './pick-client.component.html',
  styleUrls: ['./pick-client.component.scss']
})
export class PickClientComponent implements OnInit {
  
 

  constructor(
    public dialogRef: MatDialogRef<PickClientComponent>,
    @Inject(MAT_DIALOG_DATA) public data:any,
  ) { }

  ngOnInit(): void {
    
  }
  onChage() {
    alert('hello');
  }
}

import { Component, OnInit } from '@angular/core';
import { InventoryService } from '../../inventory.service';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'app-client-dialog',
  templateUrl: './client-dialog.component.html',
  styleUrls: ['./client-dialog.component.scss']
})
export class ClientDialogComponent implements OnInit {

  clientData: any = '';
  clientId: any = '';

  constructor(private InventoryService: InventoryService,
    public dialogRef: MatDialogRef<ClientDialogComponent>,) { }

  ngOnInit(): void {

    
      this.InventoryService.inventory().subscribe(res=>{
        if (res.message && res.failed) {
          alert(res.message);
          return;
        }
        this.clientData = res.clientData.records;
      })
    }

    getBatchId(e) {
      this.clientId = e;
      this.dialogRef.close(this.clientId);
    }
}

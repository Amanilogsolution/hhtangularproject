import { Component, OnInit } from '@angular/core';
import { MatDialog } from "@angular/material/dialog";
import { InventoryService } from '../inventory.service';
import { ClientDialogComponent } from './client-dialog/client-dialog.component';

@Component({
  selector: 'app-transfer-posting',
  templateUrl: './transfer-posting.component.html',
  styleUrls: ['./transfer-posting.component.scss']
})
export class TransferPostingComponent implements OnInit {

  clientData: any = '';
  clientId: any = '';


  constructor(
    public dialog: MatDialog,
    private InventoryService: InventoryService,
  ) { }

  ngOnInit(): void {

    let manualdialogRef = this.dialog.open(ClientDialogComponent, {
      
    });
    manualdialogRef.afterClosed().subscribe((res) => {
      if(res){
        //console.log(res);
        this.clientId = res;
        //this.clientId
      }
    });
  }
}

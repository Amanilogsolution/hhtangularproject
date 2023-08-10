import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { InventoryService } from '../../inventory.service';
import { ActivatedRoute } from '@angular/router';
import { AlertMessageComponent } from '../alert-message/alert-message.component';
import { MatDialog } from '@angular/material/dialog';

@Component({
  selector: 'app-bin-to-bin-transfer',
  templateUrl: './bin-to-bin-transfer.component.html',
  styleUrls: ['./bin-to-bin-transfer.component.scss']
})
export class BinToBinTransferComponent implements OnInit {

  @ViewChild("f", { static: false }) f: NgForm;
  @ViewChild("f1", { static: false }) f1: NgForm;

  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  scanBin:string = '';
  clientId:string = '';
  message: string = '';
  whLocation: string = '';
  fromBinData:any = '';
  tobin:string = '';

  constructor(private InventoryService: InventoryService,
    private snackbar: MatSnackBar,
    private route: ActivatedRoute,
    public dialog:MatDialog,) {

      this.route.params.subscribe(res => {
      this.clientId = res.clientId;
      })
      
     }

  ngOnInit(): void {
  }

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked;
  }

  search(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.scanBin = form.value.bin;
    this.whLocation = form.value.bin;
    this.getBin(form.value.bin);
  }

  changeLPN(e: any) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.scanBin = e.target.value;
      this.whLocation = e.target.value;
      this.getBin(e.target.value);
    }
  }

  getBin(scanBin:any) {
    this.InventoryService.fromBin(scanBin, this.clientId).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.fromBinData = res.records;
          } else {
            this.snackbar.open(res.message);
            this.message = res.message;
            this.fromBinData = res.records;
          }
        }else {
          this.message = res.message;
          this.fromBinData = '';
          this.whLocation = '';
          this.AlertMessage();
        }
      },
      (err) => {
        this.snackbar.open(
          err.error.message || "Something went wrong",
          "dismiss"
        );
          this.fromBinData = '';
          this.whLocation = '';
      }
    );
  }

  tobinScan(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.tobin = form.value.tobin;
    if(this.tobin.replace(/\s/g, '') == this.whLocation){
      this.message = 'To-Bin can not be same';
      this.AlertMessage();
      this.tobin = '';
      this.f1.reset();
    }else{
      this.getToBIn(form.value.tobin);
    }
  }

  changeSku(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.tobin = e.target.value;
      if(this.tobin.replace(/\s/g, '') == this.whLocation){
        this.message = 'To-Bin can not be same';
        this.AlertMessage();
        this.tobin = '';
        this.f1.reset();
      }else{
        this.getToBIn(e.target.value);
      }
    }
  }

  getToBIn(tobin:any) {
    this.InventoryService.scanToBin(tobin, this.clientId).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.tobin = res.records[0]['LOCATION'];
            this.f1.reset();
          } else {
            this.message = res.message;
            this.tobin = '';
            this.f1.reset();
          }
        }else {
          this.f1.reset();
          this.message = res.message;
          this.tobin = '';
          this.AlertMessage();
        }
      },
      (err) => {
        this.snackbar.open(
          err.error.message || "Something went wrong",
          "dismiss"
        );
          this.f1.reset();
          this.tobin = '';
      }
    );
  }

  AlertMessage(){
    let manualdialogRef = this.dialog.open(AlertMessageComponent, {
       data: {
        msg: this.message,
      },
    });
    manualdialogRef.afterClosed().subscribe((res) => {
      
    });
  }

  backToBin(){
    this.fromBinData = '';
    this.tobin = '';
    this.whLocation = '';
  }
  save(){
    
    const data = {
      custId: this.clientId,
      tobin: this.tobin,
      fromBin: this.whLocation,
      body: this.fromBinData
    }
    this.InventoryService.saveBinToBin(data).subscribe(
      (res: any) => {
        if (res.status === 1) {
          this.message = res.message;
          this.AlertMessage();
          this.backToBin();
        }else {
           this.message = res.message;
           this.AlertMessage();
        }
      },
      (err) => {
        this.snackbar.open(
          err.error.message || "Something went wrong",
          "dismiss"
        );
          this.f1.reset();
          this.tobin = '';
      }
    );
  }

}

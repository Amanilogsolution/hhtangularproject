import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { InventoryService } from '../../inventory.service';
import { ActivatedRoute } from '@angular/router';
import { AlertMessageComponent } from '../alert-message/alert-message.component';
import { MatDialog } from '@angular/material/dialog';
import { GetBtnBinComponent } from './get-btn-bin/get-btn-bin.component';

@Component({
  selector: 'app-bin-to-content-transfer',
  templateUrl: './bin-to-content-transfer.component.html',
  styleUrls: ['./bin-to-content-transfer.component.scss']
})
export class BinToContentTransferComponent implements OnInit {

  @ViewChild("f", { static: false }) f: NgForm;
  @ViewChild("f1", { static: false }) f1: NgForm;
  @ViewChild("f2", { static: false }) f2: NgForm;
  @ViewChild("f3", { static: false }) f3: NgForm;

  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  table:any= '';
  numberOfRecords:number=0;
  pageNumber:number=1;
  scanBin:string = '';
  clientId:string = '';
  message: string = '';
  whLocation: string = '';
  sku:string = '';
  toBin: any= '';
  tblSloc: string = '';
  qty:any = '';
  BATCHNO: any= '';
  btnBinData: any ='';

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
    this.pageNumber = 1;
    if (!form.valid) {
      return;
    }
    this.scanBin = form.value.bin;
    this.getBin(form.value.bin);
  }

  changeLPN(e: any) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.scanBin = e.target.value;
      this.getBin(e.target.value);
    }
  }

  getBin(scanBin:any) {
    this.InventoryService.scanBin(scanBin, this.clientId).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.whLocation = res.records[0].WHLOCATION;
          } else {
            this.snackbar.open(res.message);
            this.message = res.message;
            this.whLocation = '';
            
          }
        }else {
          this.message = res.message;
          this.whLocation = '';
          this.AlertMessage();
          this.f.reset();
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

  skuScan(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.sku = form.value.sku;
    this.getSku(form.value.sku);
  }

  changeSku(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.sku = e.target.value;
      this.getSku(e.target.value);
    }
  }

  getSku(scanSku:any) {
    this.InventoryService.scanSku(scanSku, this.clientId, this.whLocation).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.table = res.records;
            this.table = this.table;
            this.f1.reset();
          } else {
            this.message = res.message;
            this.table = '';
            this.sku = '';
            this.f1.reset();
          }
        }else {
          this.f1.reset();
          this.message = res.message;
          this.table = '';
          this.sku = '';
          this.AlertMessage();
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

  ToBinScan(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.toBin = form.value.toBin;
    if(this.toBin.replace(/\s/g, '') == this.whLocation){
      this.message = 'To-Bin can not be same';
      this.AlertMessage();
      this.toBin = '';
      this.f2.reset();
    }else{
      this.getToBin(form.value.toBin);
    }
  }

  changeToBin(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.toBin = e.target.value;
      if(this.toBin.replace(/\s/g, '') == this.whLocation){
        this.message = 'To-Bin can not be same';
        this.AlertMessage();
        this.toBin = '';
      }else{
        this.getToBin(e.target.value);
      }
    }
  }

  getToBin(scantoBin:any) {
    this.InventoryService.scanToBin(scantoBin, this.clientId).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.toBin = res.records[0]['LOCATION'];
            //this.f2.reset();
          } else {
            this.message = res.message;
            this.toBin = '';
            //this.f2.reset();
          }
        }else {
          //this.f2.reset();
          this.message = res.message;
          this.toBin = '';
          this.AlertMessage();
        }
        this.f2.reset();
      },
      (err) => {
        this.snackbar.open(
          err.error.message || "Something went wrong",
          "dismiss"
        );
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

  getBinAlert(){
    let manualdialogRef = this.dialog.open(GetBtnBinComponent, {
      data: {
        clientId: this.clientId, 
        whLocation: this.whLocation, 
        sku: this.sku
     },
   });
   manualdialogRef.afterClosed().subscribe((res) => {
     
   });
  }

  getTableSloc(e){
    this.tblSloc = e;
  }

  qtySave(form: NgForm) {
    if (!form.valid) {
      return;
    }
    if(this.tblSloc){
      this.qty = form.value.qty;
      this.CheckQTY(this.tblSloc);
    }else{
      this.makeSloc(0);
    }
  }

 

  changeqtySave(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.qty = e.target.value;
      if(this.tblSloc){
        this.CheckQTY(this.tblSloc);
      }else{
        this.makeSloc(0);
        this.CheckQTY(this.tblSloc);
      }
    }
  }

  makeSloc(i){
    let val = this.table.find((s) => s.ID == i);
    if(val){
      this.tblSloc = val.SLOC;
    }else{
      let manualdialogRef = this.dialog.open(AlertMessageComponent, {
        data: {
          msg: "SLOC not belongs to Bin!",
        },
      });
      manualdialogRef.afterClosed().subscribe((res) => {
      });
    }
  }


  CheckQTY(tblSloc) {
    let val = this.table.find((s) => s.SLOC == tblSloc);
    if(val){
      let batch = val.BATCHNO;
      this.BATCHNO = batch;
      if(this.qty <= val.AVAILABLEQTY){
        this.getToBinSave();
      }else{
        let manualdialogRef = this.dialog.open(AlertMessageComponent, {
          data: {
            msg: "You can not save more than BAL QTY!",
          },
        });
        manualdialogRef.afterClosed().subscribe((res) => {
        });
      }
    }else{
      let manualdialogRef = this.dialog.open(AlertMessageComponent, {
        data: {
          msg: "SLOC not belongs to Bin!",
        },
      });
      manualdialogRef.afterClosed().subscribe((res) => {
      });
    }
  }

  getToBinSave() {
    this.InventoryService.saveToBin(this.tblSloc, this.clientId, this.whLocation, this.sku, this.qty, this.toBin, this.BATCHNO).subscribe(
      (res: any) => {
        if (res.status == 1) {
          this.message = res.message;
          this.AlertMessage();
          this.backToBin();
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
  getBinBtn(){
    this.getBinAlert();
  }

  backToBin(){
    this.table = '';
    this.sku = '';
    this.whLocation = '';
    this.toBin = '';
  }

  backToSku(){
    this.table = '';
    this.sku = '';
    this.f1.reset();
    this.toBin = '';


  }

  backToToBIN(){
    this.toBin = '';
  }

}

import { Component, OnInit, ViewChild, AfterViewInit, ElementRef } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { InventoryService } from "../../inventory.service";
import { MatDialog } from "@angular/material/dialog";
import { GetBinComponent } from "./get-bin/get-bin.component";
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { ConfirmationComponent } from "./confirmation/confirmation.component";
import { AlertScanComponent } from "./alert-scan/alert-scan.component";
import { Router } from "@angular/router";

@Component({
  selector: "app-batch-id-data",
  templateUrl: "./batch-id-data.component.html",
  styleUrls: ["./batch-id-data.component.scss"],
})
export class BatchIdDataComponent implements OnInit, AfterViewInit {
  @ViewChild("f", { static: false }) f: NgForm;
  @ViewChild("f1", { static: false }) f1: NgForm;
  @ViewChild('scanBinInput') scanBinInputElement!: ElementRef<HTMLInputElement>;

  batchId: string;
  Custid: string;
  data: any;
  error: boolean = false;
  errorMsg: string = "";
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  scanBinData: any = "";
  rackLocation: any = "";
  test: any = "";
  saveBtn: number = 0;
  totalBin: number = 0;
  totalScan: number = 0;
  aisles:any = '';
  scanAislesData:any = '';
  pageNumber: number = 1;
  numberOfRecords: number = 0;

  constructor(
    private route: ActivatedRoute,
    private InventoryService: InventoryService,
    public dialog: MatDialog,
    private snackbar: MatSnackBar,
    private router: Router
  ) {
    this.route.queryParams.subscribe((res) => {
      this.batchId = res.batchId;
      this.Custid = res.Custid;
      this.aisles = res.aisles;
      this.getData();
      this.ScanAisles();
    });
  }

  ngOnInit(): void {
    //this.scanBinInputElement.nativeElement.focus();
  }

  ngAfterViewInit(): void{
    //this.scanBinInputElement.nativeElement.focus();
  }

  ScanAisles(pageNumber = 1) {
    this.InventoryService.scanAislesData(
      this.batchId,
      this.Custid,
      this.aisles,
      pageNumber
    ).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.scanAislesData = res.Bin.records;
            this.numberOfRecords = res.Bin.numberOfRecords;
          } else {
            this.snackbar.open(res.message, "No Records has been found!");
          }
        } else {
          this.snackbar.open(res.message, "No Records has been found!");
        }
      },
      (err) => {
        this.error = true;
        this.errorMsg = `Something went wrong`;
      }
    );
  }

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked;
  }

  search(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.rackLocation = form.value.scanBin;
    this.ScanBin(form.value.scanBin);
  }

  changeLPN(e: any) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.rackLocation = e.target.value;
      this.ScanBin(e.target.value);
    }
  }

  getData() {
    this.InventoryService.getBatchData(this.batchId, this.Custid).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.cycleCountData.records != "") {
            this.data = res.cycleCountData.records[0];
          } else {
          }
        }
      },
      (err) => {
        this.error = true;
        this.errorMsg = `Something went wrong`;
      }
    );
  }

  backToAisles(){
      this.router.navigate(["dashboard", "cycle-count", "aislesData"], {
      queryParams: { batchId: this.batchId, Custid: this.Custid },
    });
  }

  resetAll(){
    this.saveBtn = 0;
    this.rackLocation = '';
    this.scanBinData = '';
    this.scanAislesData = '';
    this.ScanAisles();
  }

  ScanBin(scanBin: any) {
    this.InventoryService.scanBinData(
      this.batchId,
      this.Custid,
      scanBin
    ).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.scanBinData = res.records;
          } else {
            //this.snackbar.open(res.message, "No Records has been found!");
            this.saveBtn = 1;
            this.f.reset();
          }
        } else {
          this.snackbar.open(res.message, "No Records has been found!");
          this.f.reset();
        }
      },
      (err) => {
        this.error = true;
        this.errorMsg = `Something went wrong`;
      }
    );
  }
  skuScan(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.IncreaseSacn(form.value.sku);
  }

  changeSku(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      //console.log(e.target.value);
      this.IncreaseSacn(e.target.value);
    }
  }

  IncreaseSacn(sku) {
    let val = this.scanBinData.find((s) => s.SKU == sku);
    if(val){
      let index = this.scanBinData.indexOf(val);
      let scan = val.totalScan;
      if(val.Total > val.totalScan){
        val.totalScan = scan + 1;
      this.scanBinData[index] = val;
      }else{
        let manualdialogRef = this.dialog.open(AlertScanComponent, {
          data: {
            msg: "You can not scan excess!",
          },
        });
        manualdialogRef.afterClosed().subscribe((res) => {
        });
      }
      
    }else{
      let manualdialogRef = this.dialog.open(AlertScanComponent, {
        data: {
          msg: "SKU not belongs to Bin!",
        },
      });
      manualdialogRef.afterClosed().subscribe((res) => {
      });
    }
    this.f1.reset();
    
  }


  save() {
    if(this.scanBinData){
      let val = this.scanBinData;
      let totalScan: number = val.map(a => a.totalScan).reduce(function(a, b)
      {
        return a + b;
      });
      let totalBin: number = val.map(a => a.Total).reduce(function(a, b)
      {
        return a + b;
      });
      this.totalScan = totalScan;
      this.totalBin = totalBin;
    }
    //console.log(totalBin);
    let manualdialogRef = this.dialog.open(ConfirmationComponent, {
      data: {
        Custid: this.Custid,
        Batchid: this.batchId,
        rackLocation: this.rackLocation,
        totalScan: this.totalScan,
        totalBin: this.totalBin,
        totalData: this.scanBinData
      },
    });
    manualdialogRef.afterClosed().subscribe((res) => {
      if(res=='close'){
        
      }else{
        this.scanBinData = '';
        this.rackLocation = '';
        this.scanAislesData = '';
        this.getData();
        this.saveBtn = 0;
        this.ScanAisles();
      }
    });
  }

  
  onPaginateChange(event: any) {
      this.ScanAisles(event.pageIndex + 1)
  }

}

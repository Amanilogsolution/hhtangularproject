import { Component, OnInit, ViewChild } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { InventoryService } from "../../inventory.service";
import { MatDialog } from "@angular/material/dialog";
import { GetBinComponent } from "../batch-id-data/get-bin/get-bin.component"; 
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { ConfirmationComponent } from "../batch-id-data/confirmation/confirmation.component"; 
import { AlertScanComponent } from "../batch-id-data/alert-scan/alert-scan.component"; 
import { Router } from "@angular/router";

@Component({
  selector: 'app-cycle-aisles',
  templateUrl: './cycle-aisles.component.html',
  styleUrls: ['./cycle-aisles.component.scss']
})
export class CycleAislesComponent implements OnInit {

  @ViewChild("f", { static: false }) f: NgForm;
  @ViewChild("f1", { static: false }) f1: NgForm;

  batchId: string;
  Custid: string;
  data: any;
  error: boolean = false;
  pageNumber: number = 1;
  errorMsg = '';
  numberOfRecords: number = 0;
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  aisles:any='';
  getAislesData: any = "";

  constructor(
    private route: ActivatedRoute,
    private InventoryService: InventoryService,
    private snackbar: MatSnackBar,
    private router: Router
  ) {
    this.route.queryParams.subscribe((res) => {
      this.batchId = res.batchId;
      this.Custid = res.Custid;
      this.getData();
      this.getAisles();
    });
  }

  ngOnInit(): void {}

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked;
  }

  search(form: NgForm) {
    if (!form.valid) {
      return;
    }
    this.aisles = form.value.scanAisles;
    this.getBatchData();
  }

  changeLPN(e: any) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.aisles = e.target.value;
     this.getBatchData();
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

  getAisles(pageNumber = 1) {
    this.InventoryService.getAislesData(
      this.batchId,
      this.Custid,
      pageNumber
    ).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.records != "") {
            this.getAislesData = res.Aisles.records;
            this.numberOfRecords = res.Aisles.numberOfRecords;
          } else {
            this.snackbar.open(res.Aisles.message, "No Records has been found!");
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

  onPaginateChange(event: any) {
      this.getAisles(event.pageIndex + 1)
  }
  getBatchData() {
    this.router.navigate(["dashboard", "cycle-count", "batchData"], {
      queryParams: { batchId: this.batchId, Custid: this.Custid, aisles: this.aisles },
    });
  }

}

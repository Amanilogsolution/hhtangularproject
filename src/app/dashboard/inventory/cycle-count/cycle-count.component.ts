import { Component, OnInit, ViewChild } from "@angular/core";
import { InventoryService } from "../inventory.service";
import { MatDialog } from "@angular/material/dialog";
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { Router } from "@angular/router";

@Component({
  selector: "app-cycle-count",
  templateUrl: "./cycle-count.component.html",
  styleUrls: ["./cycle-count.component.scss"],
})
export class CycleCountComponent implements OnInit {
  @ViewChild("f", { static: false }) f: NgForm;

  clientData: any = "";
  records: any = "";
  clientId: any = "";
  batchRecords: any = "";

  constructor(
    private InventoryService: InventoryService,
    public dialog: MatDialog,
    private snackbar: MatSnackBar,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.InventoryData();
  }

  

  InventoryData() {
    this.InventoryService.inventory().subscribe((res) => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.clientData = res.clientData.records;
    });
  }

  getBatchId(e) {
    this.clientId = e;
    //console.log("the selected value is " + e);
    this.batchId(e);
  }

  batchId(clientId: string) {
    this.InventoryService.getBatch(clientId).subscribe(
      (res: any) => {
        if (res.status === 0) {
          this.snackbar.open(res.message, "Dismiss");
        }
        if (res.status === 1) {
          if (res.cycleCountBatchid.records == "") {
            this.snackbar.open(res.message, "No Records has been found!");
            this.batchRecords = null;
          } else {
            this.batchRecords = res.cycleCountBatchid.records;
          }
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

  // getBatchData(e: any) {
  //   //console.log(e);
  //   this.router.navigate(["dashboard", "cycle-count", "batchData"], {
  //     queryParams: { batchId: e, Custid: this.clientId },
  //   });
  // }
  getAislesData(e: any) {
    //console.log(e);
    this.router.navigate(["dashboard", "cycle-count", "aislesData"], {
      queryParams: { batchId: e, Custid: this.clientId },
    });
  }
}

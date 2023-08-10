import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from "@angular/forms";
import { MatSnackBar } from "@angular/material/snack-bar";
import { InventoryService } from '../inventory.service';

@Component({
  selector: 'app-hu-content-search',
  templateUrl: './hu-content-search.component.html',
  styleUrls: ['./hu-content-search.component.scss']
})
export class HuContentSearchComponent implements OnInit {
  @ViewChild("f", { static: false }) f: NgForm;

  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  header:any= '';
  table:any= '';
  numberOfRecords:number=0;
  pageNumber:number=1;
  scanBox:string = '';

  constructor(private InventoryService: InventoryService,
    private snackbar: MatSnackBar) { }

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
    this.scanBox = form.value.box;
    this.getBox(form.value.box);
  }

  changeLPN(e: any) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.scanBox = e.target.value;
      this.getBox(e.target.value);
    }
  }

  getBox(scanBox:any) {
    this.InventoryService.getBoxData(scanBox, this.pageNumber).subscribe(
      (res: any) => {
        if (res.status === 1) {
          if (res.BoxHeader.records != "") {
            this.header = res.BoxHeader.records[0];
            this.table = res.BoxTable.records;
            this.numberOfRecords =res.BoxTable.numberOfRecords;
            this.f.reset();
          } else {
            this.snackbar.open("No Records has been found!");
            this.f.reset();
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

  onPaginateChange(event:any){
    this.pageNumber=event.pageIndex+1;
    this.getBox(this.scanBox);
    
  }

}

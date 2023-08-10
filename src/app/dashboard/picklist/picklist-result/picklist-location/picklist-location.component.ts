import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { PickListService } from '../../pick-list.service';
import { ManualUpdateComponent } from './manual-update/manual-update.component';

@Component({
  selector: 'app-picklist-location',
  templateUrl: './picklist-location.component.html',
  styleUrls: ['./picklist-location.component.scss']
})
export class PicklistLocationComponent implements OnInit {
  rackLocation: any;
  pickList: any;
  rackData: any = [];
  isLocationScan: boolean = true;
  error:Boolean = false;
  errorMsg:string=""
  numberOfRecords:number=0
  pageNumber:number=1
  skuCode:string="";
  scanned:boolean=false

  constructor(
    private route: ActivatedRoute,
    private picklistService: PickListService,
    public dialog: MatDialog
  ) {
    this.route.params.subscribe(res => {
      this.rackLocation = res.rackLocation;
      this.pickList = res.picklist;
      this.getLocationData(this.pageNumber)
    })
  }

  ngOnInit(): void {
  }
  scanLocation(newRackLocation: any) {
    if (newRackLocation.success) {
      this.rackLocation = newRackLocation.result;
      this.isLocationScan = false;
      this.getLocationData(this.pageNumber)
    }
  }
  searchLocation(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.scanLocation({
      result: form.value.rackLocation,
      success:true
    })
  }
  getLocationData(pageNumber:number) {
    this.picklistService.getRackLocationData(this.pickList, this.rackLocation, pageNumber)
      .subscribe(res => {
        if (res.message && res.failed) {
          alert(res.message);
          return;
        }
        this.rackData = res.rackLocation.records || [];
        this.numberOfRecords =res.rackLocation.numberOfRecords

      })
  }
  scanSKU(sku: any) {
    if (sku.success) {
      this.scanned = true;
      let f: any = this.rackData.filter((e: any) => {
        return (e.skuCode === sku.result) && e.pck < e.inv;
      })
      let batchno = f.length == 0 ? "" : f[0].batchno
      this.picklistService.getSKUData(this.pickList, this.rackLocation, sku.result, "+1", batchno)
        .subscribe(res => {
          if (res.message && res.failed) {
            alert(res.message);
            return;
          }
          this.getLocationData(this.pageNumber)

        })
    }
  }

  searchSku(form: NgForm) {
    this.pageNumber = 1;
    if (!form.valid) {
      return
    }

    this.skuCode = form.value.skuCode;
    this.getSkuData(form.value.skuCode)

  }
  getSkuData(skuCode:string){
    let f: any = this.rackData.filter((e: any) => {
      return (e.skuCode === skuCode) && e.pck < e.inv;
    })
    let batchno = f.length == 0 ? "" : f[0].batchno

    this.picklistService.getSKUResults(skuCode, this.pageNumber, this.pickList, this.rackLocation, batchno).subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }

      this.rackData = res.skudata.records
      this.numberOfRecords =res.skudata.numberOfRecords

    })
  }
  scanOtherLocation() {
    this.isLocationScan = true;
    this.rackData = []
  }


  manualUpdate(sku: any, batchno: string) {

    let manualdialogRef = this.dialog.open(ManualUpdateComponent, {
      data: {
        sku: sku,
        batchno: batchno,
        pickList:this.pickList,
        rackLocation:this.rackLocation
      }
    });
    manualdialogRef.afterClosed().subscribe(res => {
      this.getLocationData(this.pageNumber)
    })
  }
  onPaginateChange(event:any){
    this.pageNumber=event.pageIndex+1;
    if(this.isLocationScan){
    this.getLocationData(event.pageIndex+1)
    }
    else{
      this.getSkuData(this.skuCode)
    }
  }
}

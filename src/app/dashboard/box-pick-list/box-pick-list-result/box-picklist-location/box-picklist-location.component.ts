import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute } from '@angular/router';
import { BoxService } from '../../box.service';
import { BoxManualUpdateComponent } from './box-manual-update/box-manual-update.component';

@Component({
  selector: 'app-box-picklist-location',
  templateUrl: './box-picklist-location.component.html',
  styleUrls: ['./box-picklist-location.component.scss']
})
export class BoxPicklistLocationComponent implements OnInit {
  @ViewChild('f',{static:true}) f:NgForm;
  @ViewChild('f2',{static:true}) f2 : NgForm;
  @ViewChild('f3',{static:true}) f3 : NgForm;

  selectedIndex: number = 0
  selectedTab: number = 0
  rackLocation: any;
  pickList: any;
  rackData: any = [];
  isLocationScan: boolean = true;
  error: Boolean = false;
  errorMsg: string = ""
  numberOfRecords: number = 0
  pageNumber: number = 1
  skuCode: string = "";
  scanned: boolean = false;
  boxno: string = ''
  queryPrams: any;
  qty: any = "+1"
  dnno: any;
  location:any = false;
  custinvno:string='';
  custid:string=""

  constructor(
    private route: ActivatedRoute,
    private boxService: BoxService,
    public dialog: MatDialog,
    private snackbar: MatSnackBar
  ) {
    this.route.params.subscribe(res => {
      //console.log(res)
      this.rackLocation = res.dnno;
      this.pickList = res.picklist;
      this.getLocationData(this.pageNumber)
    })
    this.route.queryParams.subscribe(res=>{
      this.custinvno = res.custinvno
      this.custid= res.custid,
      this.queryPrams  = res
    })
  }

  ngOnInit(): void {
  }

  getLocationData(pageNumber: number) {
    this.boxService.getRackLocationData(this.pickList, this.rackLocation, pageNumber)
      .subscribe(res => {
        if (res.message && res.failed) {
          alert(res.message);
          return;
        }
        this.rackData = res.rackLocation.records || [];
        this.numberOfRecords = res.rackLocation.numberOfRecords

      })
  }
  onTabChanged(event: any) {
    this.selectedTab = event.index;
    this.selectedIndex = event.index;
  }


  scanBox(boxScan: any) {
    if (boxScan.success) {
      this.validateBox(boxScan.result)
    }
  }
  searchBox(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.validateBox(form.value.boxno)
  }
  validateBox(boxNo: string) {
    this.boxService.validateBox(boxNo).subscribe((res: any) => {
      if (res.status === 0) {
        this.snackbar.open(res.message, "Dismiss");
      }
      if (res.status === 1) {
        this.boxno = boxNo;
        this.selectedIndex = 1
        this.selectedTab = 1;

      }

    },
      err => {
        this.snackbar.open(err.error.message || "Something went wrong", "dismiss")
      })
  }
  scanLocation(locationScan: any) {
    if (locationScan.success) {
      this.validateLocation(locationScan.result)
    }
  }
  searchLocation(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.validateLocation(form.value.location)
  }
  validateLocation(location: string) {

    this.boxService.getRackLocationData(this.pickList, location, this.pageNumber).subscribe((res: any) => {
      if (res.status === 1) {
        this.rackLocation = location;
        this.selectedIndex = 2
        this.selectedTab = 2;
        this.location = true;
        this.rackData =  res.rackLocation.records
      } else {
        this.snackbar.open(res.message, "Dismiss");
        this.location = false;
        this.rackData =[]

      }
    }, err => {
      this.location = false;
      this.rackData =[]
      this.snackbar.open(err.error.message, "Dismiss");
    })
  }

  scanSku(skuScan: any) {

    if (skuScan.success) {
      this.skuCode = skuScan.result;
      this.processSku(skuScan.result)
    }

  }
  searchSku(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.processSku(form.value.sku)

  }
  processSku(sku:string){
    this.boxService.updateSKUData(this.pickList, this.rackLocation, sku,"+1","", this.boxno,this.custinvno,this.custid).subscribe((res: any) => {
      //console.log(res)
      this.snackbar.open(res.message, "Dismiss");
      if (res.status == 1) {

        this.skuCode = sku;
        let data = { ...this.queryPrams.params }
        data.location = this.rackLocation
        data.sku = this.skuCode
        data.boxno = this.boxno
        data.qty = this.qty;
       this.getLocationData(1);
      }
    }, (err: any) => {
      let msg = "Something went wrong, please try again"
      if (err.message) {
        msg = err.error.message
      }
      this.snackbar.open(msg, "Dismiss");
    })
  }

  getSkuData(skuCode: string) {
    let f: any = this.rackData.filter((e: any) => {
      return (e.skuCode === skuCode) && e.pck < e.inv;
    })
    let batchno = f.length == 0 ? "" : f[0].batchno

    this.boxService.getSKUResults(skuCode, this.pageNumber, this.pickList, this.rackLocation, batchno).subscribe((res: any) => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }

      this.rackData = res.skudata.records
      this.numberOfRecords = res.skudata.numberOfRecords

    })
  }



  manualUpdate() {

    let manualdialogRef = this.dialog.open(BoxManualUpdateComponent, {
      data: {
        custinvno : this.custinvno,
        custid:this.custid,
        skuCode : this.skuCode,
        location : this.rackLocation,
        boxno:this.boxno,
        pickList:this.pickList
      }
    });
    manualdialogRef.afterClosed().subscribe((res:any) => {
      if(res == "success"){
      this.getLocationData(this.pageNumber)
      }
    })
  }
  onPaginateChange(event: any) {
    this.pageNumber = event.pageIndex + 1;
    if (this.isLocationScan) {
      this.getLocationData(event.pageIndex + 1)
    }
    else {
      this.getSkuData(this.skuCode)
    }
  }
  reset(){
    this.selectedIndex=0;
    this.selectedTab=0;
    this.f.reset()
    this.f2.reset()
    this.f3.reset()
    this.location= false;
    this.boxno=""

  }

}

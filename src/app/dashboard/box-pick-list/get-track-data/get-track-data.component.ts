import { Component, OnInit, ViewChild ,ElementRef} from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { BoxService } from '../box.service';
import { NgForm } from '@angular/forms';
import { Location } from '@angular/common';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { BoxManualUpdateComponent } from '../box-pick-list-result/box-picklist-location/box-manual-update/box-manual-update.component';
import { CloseBoxComponent } from './close-box/close-box.component';

@Component({
  selector: 'app-get-track-data',
  templateUrl: './get-track-data.component.html',
  styleUrls: ['./get-track-data.component.scss']
})
export class GetTrackDataComponent implements OnInit {

  @ViewChild('f', { static: true }) f: NgForm;
  @ViewChild('f2', { static: true }) f2: NgForm;
  @ViewChild('f3', { static: true }) f3: NgForm;
  @ViewChild('inputBoxno', { static: true }) inputBoxno: ElementRef<HTMLInputElement>;
  @ViewChild('inputLocation', { static: true }) inputLocation: ElementRef<HTMLInputElement>;
  @ViewChild('inputSku', { static: true }) inputSku: ElementRef<HTMLInputElement>;

  selectedIndex: number = 0
  selectedTab: number = 0
  rackLocation: any;
  rackData: any = [];
  isLocationScan: boolean = true;
  skuCode: string = "";
  scanned: boolean = false;
  queryPrams: any;
  qty: any = "+1"
  dnno: any;
  location: any = false;
  getTrackData: any;
  LocationSub: Subscription = new Subscription();
  pageNumber: number = 1;
  error: boolean = false;
  errorMsg: string = "";
  numberOfRecords: number = 0;
  custinvno: string;
  custid: string;
  zoneBarcode: string;
  InvQty: number = 0;
  PickQTy: string;
  picklist: string;
  aisles: string;
  totalAisles: number = 0;
  pickedAisles: number = 0;
  boxno: string = '';
  numberOfBox: string = '';
  sku: string = '';
  numberOfPage: number = 0;
  pagedata: number = 1;
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;

  constructor(private router: Router,
    private route: ActivatedRoute,
    private boxService: BoxService,
    private commonService: CommonService,
    private backPage: Location,
    public dialog: MatDialog,
    private snackbar: MatSnackBar) {
    this.commonService.selectedWarehouse.subscribe(res => {
    }).unsubscribe();

    this.route.params.subscribe(res => {
      this.custid = res.custid
      this.custinvno = res.custinvno;
      this.rackLocation = res.dnno;
    })
    this.route.queryParams.subscribe(res => {
      this.zoneBarcode = res.zoneBarcode;
      this.aisles = res.aisles;
      this.InvQty = res.InvQty;
      this.PickQTy = res.PickQTy;
      this.picklist = res.picklist;
    })
    setTimeout(() => {
      this.getData()
    }, 200);
  }
  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
      this.inputBoxno.nativeElement.focus();
      // this.inputLocation.nativeElement.focus();
      // this.inputSku.nativeElement.focus();
  }

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked
  }


  getData(pageNumber = 1) {
    this.isScanning = true;
    this.boxService.getTrackData(this.custinvno, this.custid, this.zoneBarcode, this.InvQty, this.PickQTy, this.picklist, this.aisles, pageNumber).subscribe((res: any) => {

      if (!res.gettrackData.records.length) {
        alert("Aisles " + this.aisles + " not found");
        this.backPage.back();
        this.error = true;
        this.errorMsg = `No data found for location ${this.location.WHNAME}`
        this.aisles = null;
        this.isScanning = false;
      } else {
        this.getTrackData = res.gettrackData.records;
        this.numberOfRecords = res.gettrackData.numberOfRecords
        this.totalAisles = res.gettrackData.records.reduce((sum, item) => sum + item.totalQty, 0);
        this.pickedAisles = res.gettrackData.records.reduce((sum, item) => sum + item.totalScanned, 0);
        this.isScanning = false;
      }

    },
      (err: any) => {
        console.log(err)
        this.error = true;
        this.errorMsg = "Some thing went wrong";
        this.isScanning = false;
      })
  }

  getLocationData(pageNumber: number) {
    this.boxService.getRackLocationData(this.picklist, this.rackLocation, pageNumber)
      .subscribe(res => {
        if (res.message && res.failed) {
          alert(res.message);
          return;
        }
        this.rackData = res.rackLocation.records || [];

      })
  }

  getNewBoxRackLocation(pageNumber: number) {
    this.boxService.getNewRackLocation(this.picklist, this.rackLocation, this.custid, this.zoneBarcode, this.aisles, pageNumber).subscribe((res: any) => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.rackData = res.rackLocation.records || [];
      this.PickQTy = res.rackLocation.totalScan;
      this.pickedAisles = res.rackLocation.totalAislesScan;
      this.numberOfBox = "";
      this.scanBox(this.boxno, this.custid, this.custinvno);
    })
  }

  backToAisles() {
    this.router.navigate(['dashboard', 'get-aisles-data', this.custid, this.custinvno], { queryParams: { zone: this.zoneBarcode, InvQty: this.InvQty, PickQTy: this.PickQTy, picklist: this.picklist } })
  }

  changeBox() {
    this.getData(this.pageNumber);
    this.reset();
    this.rackData = [];
  }

  backToRackLocation() {
    this.getData(this.pageNumber);
    this.selectedIndex = 1;
    this.selectedTab = 1;
    this.f2.reset()
    this.f3.reset()
    this.location = false;
    this.rackData = [];
  }

  onTabChanged(event: any) {
    this.selectedTab = event.index;
    this.selectedIndex = event.index;
    this.inputBoxno.nativeElement.focus();
    this.inputLocation.nativeElement.focus();
    this.inputSku.nativeElement.focus();
  }

  boxSearch(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.newValidateBox(form.value.boxno, this.custid, this.custinvno)

  }

  newValidateBox(boxNo: string, custid: string, REF_nO: string) {

    this.boxService.newValidateBox(boxNo, custid, REF_nO).subscribe((res: any) => {
      if (res.status === 0) {
        this.snackbar.open(res.message, "Dismiss");
      }
      if (res.status === 1) {
        this.boxno = boxNo;
        this.selectedIndex = 1
        this.selectedTab = 1;
        this.numberOfBox = res.qtyOfBox;


      }

    },
      err => {
        this.snackbar.open(err.error.message || "Something went wrong", "dismiss")

      })
  }

  scanBox(boxNo: string, custid: string, REF_nO: string) {
    this.boxService.scanBox(boxNo, custid, REF_nO).subscribe((res: any) => {
      if (res.status === 0) {
        this.snackbar.open(res.message, "Dismiss");
      }
      if (res.status === 1) {
        this.boxno = boxNo;
        this.numberOfBox = res.qtyOfBox;

      }

    },
      err => {
        this.snackbar.open(err.error.message || "Something went wrong", "dismiss")
      })
  }


  searchLocation(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.validateLocation(form.value.location)
  }
  validateLocation(location: string) {

    this.boxService.getRackLocationData(this.picklist, location, this.pageNumber).subscribe((res: any) => {
      if (res.status === 1) {
        this.rackLocation = location;
        this.selectedIndex = 2
        this.selectedTab = 2;
        this.location = true;
        this.rackData = res.rackLocation.records
        this.numberOfRecords = res.rackLocation.numberOfRecords
        this.numberOfPage = res.rackLocation.numberOfPage
        this.getTrackData = [];
      } else {
        this.snackbar.open(res.message, "Dismiss");
        this.location = false;
        this.rackData = []

      }
    }, err => {
      this.location = false;
      this.rackData = []
      this.snackbar.open(err.error.message, "Dismiss");
    })
  }
  searchSku(form: NgForm) {
    if (!form.valid) {
      return
    }
    let inputSku = this.rackData[0]['skuCode'];
    if (form.value.sku == inputSku) {
      this.processSku(form.value.sku)
    } else {
      alert('Sku not Matched');
      this.f3.reset();
    }


  }
  processSku(sku: string) {
    this.isScanning = true;
    this.boxService.updateSKUData(this.picklist, this.rackLocation, sku, "+1", "", this.boxno, this.custinvno, this.custid).subscribe((res: any) => {
      if (res.status == 1) {
        this.getNewBoxRackLocation(this.pagedata);
        this.f3.reset()
        if (res.message != "Quintity updation 1" && res.message != "New record has beed created") {
          this.snackbar.open(res.message, "Dismiss");
        }
      }
      this.isScanning = false;
    }, (err: any) => {
      let msg = "Something went wrong, please try again"
      if (err.message) {
        msg = err.error.message
      }
      this.isScanning = false;
      this.snackbar.open(msg, "Dismiss");
    })
  }

  getSkuData(skuCode: string) {
    let f: any = this.rackData.filter((e: any) => {
      return (e.skuCode === skuCode) && e.pck < e.inv;
    })
    let batchno = f.length == 0 ? "" : f[0].batchno

    this.boxService.getSKUResults(skuCode, this.pageNumber, this.picklist, this.rackLocation, batchno).subscribe((res: any) => {
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }

      this.rackData = res.skudata.records
      this.numberOfRecords = res.skudata.numberOfRecords

    })
  }



  manualUpdate(item: any) {

    let manualdialogRef = this.dialog.open(BoxManualUpdateComponent, {
      data: {
        custinvno: this.custinvno,
        custid: this.custid,
        skuCode: item.skuCode,
        location: this.rackLocation,
        boxno: this.boxno,
        pickList: this.picklist
      }
    });
    manualdialogRef.afterClosed().subscribe((res: any) => {
      if (res == "success") {
        this.getNewBoxRackLocation(this.pagedata)
      }
    })
  }

  changeLPN(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.newValidateBox(e.target.value, this.custid, this.custinvno);
       this.f.reset();
    }
  }
  changeLocatoin(e) {

    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.validateLocation(e.target.value);
      this.f2.reset();
    
    }
  }
  changeSku(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      let inputSku = this.rackData[0]['skuCode'];
      if (e.target.value == inputSku) {
        this.processSku(e.target.value);
        this.f3.reset();
      } else {
        alert('Sku not Matched');
        this.f3.reset();
      }
    }
  }

  closeBox(){
    let manualdialogRef = this.dialog.open(CloseBoxComponent, {
      data: {
        custid: this.custid,
        boxno: this.boxno
      }
    });
    manualdialogRef.afterClosed().subscribe((res: any) => {
      if (res == "success") {
        this.changeBox();
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

  skuNextPaginate(numberOfPage: number) {
    let page = this.numberOfPage++;
    this.pagedata = +numberOfPage + 1;
    this.getLocationData(this.pagedata);
  }
  skuPreviousPaginate(numberOfPage: number) {
    this.pagedata = +numberOfPage - 1;
    let page = this.numberOfPage--;
    this.getLocationData(this.pagedata);
  }

  onPaginateChangeData(event: any) {
    this.getData(event.pageIndex + 1)
  }

  reset() {
    this.selectedIndex = 0;
    this.selectedTab = 0;
    this.f.reset()
    this.f2.reset()
    this.f3.reset()
    this.location = false;
    this.boxno = ""
    this.numberOfBox = ""

  }

}

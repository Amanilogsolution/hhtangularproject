import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { BoxService } from '../box.service';
import { NgForm } from '@angular/forms';
import { Location } from '@angular/common';


@Component({
  selector: 'app-get-aisles-data',
  templateUrl: './get-aisles-data.component.html',
  styleUrls: ['./get-aisles-data.component.scss']
})
export class GetAislesDataComponent implements OnInit {

  @ViewChild('f', { static: true }) f: NgForm;
  @ViewChild('aisles', { static: true }) aisles: ElementRef<HTMLInputElement>;

  location: any = '';
  getAislesData: any;
  LocationSub: Subscription = new Subscription();
  pageNumber: number = 1;
  error: boolean = false;
  errorMsg: string = "";
  numberOfRecords: number = 0;
  custinvno: string;
  custid: string;
  zoneBarcode: string;
  InvQty: string;
  PickQTy: string;
  picklist: string;
  aislesTotal: string;
  aislesPicked: string;
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;

  constructor(private router: Router,
    private route: ActivatedRoute,
    private boxService: BoxService,
    private commonService: CommonService,
    private backPage: Location) {
    this.commonService.selectedWarehouse.subscribe(res => {
      this.location = res
    }).unsubscribe();

    this.route.params.subscribe(res => {
      this.custid = res.custid
      this.custinvno = res.custinvno
    })
    this.route.queryParams.subscribe(res => {
      this.zoneBarcode = res.zone;
      this.InvQty = res.InvQty;
      this.PickQTy = res.PickQTy;
      this.picklist = res.picklist;
      this.aislesTotal = res.picklist;
      this.aislesPicked = res.picklist;
    })

  }

  ngOnInit(): void {
    setTimeout(() => {
      this.getData()
    }, 200);
  }
ngAfterViewInit(): void {
    this.aisles.nativeElement.focus();
}

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked
  }

  getData(pageNumber = 1) {
    this.isScanning = true;
    let obj = {
      custinvno: this.custinvno, custid: this.custid, zoneBarcode: this.zoneBarcode, InvQty: this.InvQty, PickQTy: this.PickQTy, dnno: this.picklist,
    }
    
    this.boxService.getAislesData(obj, pageNumber).subscribe((res: any) => {

      if (!res.getaislesData.records.length) {
        this.error = true;
        this.errorMsg = `No data found for location ${this.location.WHNAME}`
        this.zoneBarcode = null;
        this.isScanning = false;
      } else {
        this.getAislesData = res.getaislesData.records;
        this.numberOfRecords = res.getaislesData.numberOfRecords;
        this.PickQTy = res.getaislesData.totalScan;
        this.isScanning = false;
      }

    },
      (err: any) => {
        console.log(err)
        this.error = true;
        this.errorMsg = "Some thing went wrong"
      })
  }

  onPaginateChange(event: any) {
    this.getData(event.pageIndex + 1)
  }

  getTrackData(aisles) {
    this.isScanning = true;
    this.boxService.getTrackData(this.custinvno, this.custid, this.zoneBarcode, +this.InvQty, this.PickQTy, this.picklist, aisles, 1).subscribe((res: any) => {

      if (!res.gettrackData.records.length) {
        this.handleError(aisles)
        this.isScanning = false;
        this.f.reset();
      } else {
        this.router.navigate(['dashboard', 'get-track-data', this.custid, this.custinvno], { queryParams: { zoneBarcode: this.zoneBarcode, InvQty: this.InvQty, PickQTy: this.PickQTy, picklist: this.picklist, aisles: aisles, aislesTotal: this.aislesTotal, aislesPicked: this.aislesPicked } })
        this.isScanning = false;
        this.f.reset();
      }
    },
      (err: any) => {
        this.handleError(aisles)
      })
  }
  handleError(aisles) {
    alert("Aisles " + aisles + " not found");
    this.isScanning = false;
    this.f.reset();
  }
  search(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.getTrackData(form.value.aisles);
  }

  changeAisle(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.getTrackData(e.target.value);
       this.f.reset();
    }
  }

}

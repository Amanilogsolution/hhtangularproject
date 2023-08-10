import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { BoxService } from '../box.service';
import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-box-pick-list-result',
  templateUrl: './box-pick-list-result.component.html',
  styleUrls: ['./box-pick-list-result.component.scss']
})
export class BoxPickListResultComponent implements OnInit {

  @ViewChild('f', { static: true }) f: NgForm;
  @ViewChild('zoneBarcode', { static: true }) zoneBarcode: ElementRef<HTMLInputElement>;

  picklist: any;
  pickListDetails: any;
  error: boolean = false;
  pageNumber: number = 1;
  errorMsg = '';
  numberOfRecords: number = 0;
  custinvno: string;
  custid: string;
  InvQty: string;
  PickQTy: string;
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;

  constructor(private route: ActivatedRoute,
    private pickListService: BoxService,
    private router: Router) {
    this.route.params.subscribe(res => {
      this.picklist = res.picklist;
    })
    this.route.queryParams.subscribe(res => {
      this.custinvno = res.custinvno
      this.custid = res.custid
      this.InvQty = res.InvQty
      this.PickQTy = res.PickQTy
    })

  }
  ngOnInit(): void {
    this.getData(1);
  }
   ngAfterViewInit(): void {
      this.zoneBarcode.nativeElement.focus();
  }

  hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked
  }

  goToItem(item: any) {
    this.router.navigate(['dashboard', 'box-pick-list', this.picklist, item.location], { queryParamsHandling: 'preserve' })
  }

  getData(pageNumber = 1) {
    this.pickListService.getZoneWiseData(this.custinvno, this.custid, this.picklist, pageNumber)
      .subscribe(res => {
        if (!res.getZoneWiseData.records.length) {
          this.error = true;
          this.errorMsg = `No Picklist data found for ${this.picklist} at choosen location`
        } else {
          this.pickListDetails = res.getZoneWiseData.records;
          this.numberOfRecords = res.getZoneWiseData.numberOfRecords;
          this.PickQTy = res.getZoneWiseData.totalScan;
        }
      }, err => {
        this.error = true;
        this.errorMsg = `Something went wrong`
      })
  }
  onPaginateChange(event: any) {
    this.getData(event.pageIndex + 1)
  }
  search(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.validateZone(form.value.zoneBarcode)

  }
  changeZone(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value) {
      this.validateZone(e.target.value)
      this.f.reset();
    }
  }
  validateZone(zoneBarcode) {
    this.isScanning = true;
    let obj = {
      custinvno: this.custinvno,
      custid: this.custid,
      dnno:this.picklist,
      zoneBarcode
    }
    this.pickListService.getAislesData(obj)
      .subscribe((res: any) => {
        if (res.getaislesData.records.length) {
          this.router.navigate(['dashboard', 'get-aisles-data', this.custid, this.custinvno], { queryParams: { zone: zoneBarcode, InvQty: this.InvQty, PickQTy: this.PickQTy, picklist: this.picklist } })
          this.isScanning = false;
          this.f.reset();
        }
        else {
          this.handleError(zoneBarcode)
        }
      }, err => {
        this.handleError(zoneBarcode)
        
      })
  }
  handleError(zoneBarcode) {

    alert(`No records found for Zone code: ${zoneBarcode}`);
    this.isScanning = false;
    this.f.reset();
  }

}

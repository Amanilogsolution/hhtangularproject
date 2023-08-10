import { AfterViewInit, Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute } from '@angular/router';
import { ScanModalComponent } from 'src/app/common/scan-modal/scan-modal.component';
import { AsnService } from '../asn.service';
import { HhtSkuScanComponent } from '../hht-sku-scan/hht-sku-scan.component';

@Component({
  selector: 'app-asn-update',
  templateUrl: './asn-update.component.html',
  styleUrls: ['./asn-update.component.scss']
})
export class AsnUpdateComponent implements OnInit {
  @ViewChild('skuInput', { static: false }) skuInput: ElementRef;
  @ViewChild('f1', { static: false }) f1: NgForm;

  asnNo: string;
  item: any;
  boxNo: string;
  boxNoValid: boolean = false;
  sku: string;
  skuValid: boolean = false;
  skulist = [];
  scanNumByWhLocation;
  scanning: boolean = true;
  hhtScanEnabled: boolean = false;
  constructor(
    private route: ActivatedRoute,
    private asnservice: AsnService,
    public dialog: MatDialog,

  ) {
    this.route.queryParams.subscribe(res => {
      this.asnNo = res.asn
    })
  }

  ngOnInit(): void {
    this.getAsnInfo()
  }
  getAsnInfo() {
    this.asnservice.getAsnList(this.asnNo, "1").subscribe((res: any) => {
      this.item = res.asnlist.records[0];
    })
  }
  searchBox(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.findBox(form.value.WHLocation)

  }
  findBox(WHLocation) {
    const data = {
      asnNo: this.asnNo,
      WHLocation: WHLocation
    }
    this.asnservice.validateBox(data).subscribe((res: any) => {
      if (res.status) {
        this.boxNo = WHLocation;
        this.boxNoValid = true
      } else {
        this.invalidBox()
      }
    }, err => {
      this.invalidBox()
    })
  }
  invalidBox() {
    this.boxNo = null;
    this.boxNoValid = false;
    alert("Invalid Box: Box/LPN already used in otther ASN");
  }

  searchSKU(form: NgForm) {

    if (!form.valid) {
      return
    }

    this.validateSKu(form.value.sku)
  }
  validateSKu(sku) {

    const data = {
      asnNo: this.asnNo,
      WHLocation: this.boxNo,
      sku: sku,
      custId: this.item.custId,
      invoiceNo: this.item.invoiceNo
    }
    this.skulist = [];

    this.skuValid = false;
    this.scanNumByWhLocation = 0;
    this.asnservice.validateSKU(data).subscribe((res: any) => {
      if (res.status) {
        this.sku = sku;
        this.skuValid = true
        this.skulist = res.skulist.records,

          this.scanNumByWhLocation = +res.skulist.scanNumByWhLocation;
        if (this.hhtScanEnabled && +res.skulist.records[0].BalanceFOrScan) {

          this.findnUpdateSKU(1, sku)
        } else if (!+res.skulist.records[0].BalanceFOrScan) {
          alert("Scan Completed for this SKU")
        }
      } else {
        this.invalidSku()
      }
    }, err => {
      this.invalidSku()
    })
  }
  invalidSku() {
    this.sku = null
    this.skuValid = false;
    this.scanning = true;
    this.f1.resetForm();
    alert("Invalid SKU: No record found")
  }
  updateSKU(form: NgForm) {

    if (!form.valid) {
      return
    }
    if(form.value.qty <= 0){
      alert(`Invalid Qty`)
      return;
    }
    if (form.value.qty > (+this.skulist[0].asnQty - this.skulist[0].scanQty)) {
      alert(`Allowed max value:${+this.skulist[0].asnQty - this.skulist[0].scanQty}`)
      return;
    }
    this.findnUpdateSKU(form.value.qty, null, form.submitted)

  }
  findnUpdateSKU(qty, sku?, formsubmit?) {
    const data = {
      asnNo: this.asnNo,
      WHLocation: this.boxNo,
      sku: this.sku ?? sku,
      custId: this.item.custId,
      invoiceNo: this.item.invoiceNo,
      qty: qty,
      scannedQty: this.item.scanQty + qty
    }
    this.asnservice.updateSku(data).subscribe((res: any) => {
      this.item.scanQty = data.scannedQty;
      this.skulist[0].scanQty = this.skulist[0].scanQty + qty;
      this.skulist[0].scanNumByWhLocation = +this.skulist[0].scanNumByWhLocation + qty;
      if (!this.hhtScanEnabled || formsubmit) {
        alert("Qty Updated");
        this.skuValid = false;
        this.f1.resetForm();
      } else {
        this.f1.resetForm();
        this.scanning = true;
      }
    }, err => {
      alert("Updation failed")
    })
  }
  updateQty() {
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.findnUpdateSKU(res)
      }
    })
  }
  scanASN() {
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        console.log(res)
        this.findBox(res)
      }
    })
  }
  scanSKU() {
    let dialogRef = this.dialog.open(ScanModalComponent)

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        console.log(res)
        this.findnUpdateSKU(res)
      }
    })
  }
  changeBox() {
    if (this.boxNoValid) {
      this.boxNoValid = !this.boxNoValid;
      this.skuValid = !this.skuValid;
      this.skulist = []
    }
  }
  changeSKU(e) {

    if (this.skuValid) {
      this.skuValid = !this.skuValid
    }
    if (this.hhtScanEnabled && this.scanning) {
      this.scanning = false;
      let sku = e.target.value
      this.validateSKu(sku)
    }

  }
  hhtToggle(e) {

    this.hhtScanEnabled = e.target.checked

  }
  // httModal() {
  //   let dialogRef = this.dialog.open(HhtSkuScanComponent, {
  //     data: {
  //       asnNo: this.asnNo,
  //       WHLocation: this.boxNo,
  //       sku: this.sku,
  //       custId: this.item.custId,
  //       invoiceNo: this.item.invoiceNo,
  //       scannedQty: this.item.scanQty,
  //       asnQty:this.skulist[0].asnQty,
  //       scanQty:this.skulist[0].scanQty
  //     }
  //   })

  //   dialogRef.afterClosed().subscribe(res => {
  //     this.getAsnInfo()
  //   })
  // }
}

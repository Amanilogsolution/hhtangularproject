import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { AsnScanBoxService } from './asn-scan-box.service';
import { MatDialog } from '@angular/material/dialog';
import { AsnScanManualUpdateComponent } from './asn-scan-manual-update/asn-scan-manual-update.component';

@Component({
  selector: 'app-asn-scan-box',
  templateUrl: './asn-scan-box.component.html',
  styleUrls: ['./asn-scan-box.component.scss']
})
export class AsnScanBoxComponent implements OnInit {
  @ViewChild ('f',{static:false}) f: NgForm;

  scanBox:string = '';
  numberOfRecords:number=0;
  pageNumber:number=1;
  scanData: any = '';
  scanTableData:any = '';
  hhtScanEnabled:boolean= true;

  constructor(
    private AsnScanBoxService: AsnScanBoxService,
    public dialog: MatDialog
  ) { }

  ngOnInit(): void {
  }
  hhtScanToggle(e){
    this.hhtScanEnabled=e.checked
  }

  search(form: NgForm) {
    this.pageNumber = 1;
    if (!form.valid) {
      return
    }
    this.scanBox = form.value.scanBox;
    this.AsnScanData(form.value.scanBox)
  }

  changeLPN(e){
    if(this.hhtScanEnabled){
      this.AsnScanData(e.target.value)
     
    }
  }

  AsnScanData(scanBox:string){
    this.AsnScanBoxService.getAsnScanData(scanBox, this.pageNumber).subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.scanBox = scanBox;
      this.scanData = res.getAsnScanData.records;
      this.scanTableData = res.getAsnScanTableData.records;
      this.numberOfRecords =res.getAsnScanTableData.numberOfRecords;
      this.f.reset();
    })
  }

  manualUpdate(SKU:any, ScanQTy:any, TotalBoxQty:any) {

    let manualdialogRef = this.dialog.open(AsnScanManualUpdateComponent, {
      data: {
        sku:SKU,
        qty:ScanQTy,
        TotalBoxQty:TotalBoxQty,
        scanData:this.scanData[0],
        WHLocation:this.scanBox
      }
    });
    manualdialogRef.afterClosed().subscribe(res => {
     this.AsnScanData(this.scanBox)
    })
  }

  onPaginateChange(event:any){
    this.pageNumber=event.pageIndex+1;
    this.AsnScanData(event.pageIndex+1)
    
  }
  
}

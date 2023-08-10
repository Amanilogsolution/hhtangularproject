import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MainAsnService } from '../main-asn.service';
import { RfidConfirmationComponent } from './rfid-confirmation/rfid-confirmation.component';

@Component({
  selector: 'app-asn-scan-rfid',
  templateUrl: './asn-scan-rfid.component.html',
  styleUrls: ['./asn-scan-rfid.component.scss']
})
export class AsnScanRfidComponent implements OnInit {
  @ViewChild ('f',{static:false}) f: NgForm;

  scanBox:string = '';
  scanData: any = '';
  scanTableData:any = '';
  hhtScanEnabled:boolean= true;
  Scanqty:number=0;

  constructor(private MainAsnService: MainAsnService,
    public dialog: MatDialog) { }

  ngOnInit(): void {
  }
  hhtScanToggle(e){
    this.hhtScanEnabled=e.checked
  }

  search(form: NgForm) {
   // this.pageNumber = 1;
    if (!form.valid) {
      return
    }
    this.scanBox = form.value.scanBox;
    this.AsnScanData(form.value.scanBox)
  }

  changeLPN(e){
    if(this.hhtScanEnabled){
      this.scanBox = e.target.value;
      this.AsnScanData(e.target.value)
     
    }
  }

  AsnScanData(scanBox:string){
    this.MainAsnService.getAsnScanData(scanBox).subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.scanBox = scanBox;
      this.scanData = res.getAsnScanData.records;
      this.scanTableData = res.getAsnScanTableData.records;
      //this.numberOfRecords =res.getAsnScanTableData.numberOfRecords;
      this.Scanqty = res.asnScanQtyData.records[0]['Scanqty'];
      //console.log(this.Scanqty);
      this.f.reset();
    })
  }

  save() {
    let manualdialogRef = this.dialog.open(RfidConfirmationComponent, {
      data: {
        scanData: this.scanData,
         scanTableData: this.scanTableData,
         scanBox: this.scanBox,
         totalScan: this.Scanqty
      },
    });
    manualdialogRef.afterClosed().subscribe((res) => {
   
    });
  }

  // onPaginateChange(event:any){
  //   this.pageNumber=event.pageIndex+1;
  //   this.AsnScanData(this.scanBox)
    
  // }
}

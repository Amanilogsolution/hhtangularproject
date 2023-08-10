import { Component, OnInit, ViewChild } from '@angular/core';
import { InventoryService } from './inventory.service';
import { MatDialog } from '@angular/material/dialog';
import { NgForm } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatPaginator } from '@angular/material/paginator';
import {MatTableDataSource} from '@angular/material/table';


export interface PeriodicElement {
  WHLOCATION: string;
  sku: number;
  AvailForAllocation: number;
  PendingForPick: string;
}

@Component({
  selector: 'app-inventory',
  templateUrl: './inventory.component.html',
  styleUrls: ['./inventory.component.scss']
})
export class InventoryComponent implements OnInit{

  @ViewChild('f', { static: false }) f: NgForm;
  @ViewChild('scanInput') inputName; 
  @ViewChild(MatPaginator) matPaginator!: MatPaginator;

  clientData: any = '';
  hhtScanEnabled: boolean = true;
  isScanning: boolean = false;
  records: any ='';
  displayedColumns: string[] = ['WHLOCATION', 'sku', 'AvailForAllocation', 'PendingForPick'];
  dataSource: MatTableDataSource<any> = new MatTableDataSource<any>();

  constructor(
    private InventoryService:InventoryService,
    public dialog:MatDialog,
    private snackbar: MatSnackBar
  ) { }

  
  ngOnInit(): void {
    this.InventoryData();
  }

  search(form: NgForm) {
    if (!form.valid) {
      return
    }else{
      console.log(form.value);
      if(form.value.searchBy == 'sku'){
        this.skuInventroy(form.value.clientId, form.value.scan);
      }else{
        this.locationInventroy(form.value.clientId, form.value.scan);
      }
    }
  }

  
 hhtScanToggle(e) {
    this.hhtScanEnabled = e.checked
  }
  resetField(){
    this.inputName.nativeElement.value = '';
    this.records = '';
  }
  scan(e) {
    if (this.hhtScanEnabled && !this.isScanning && e.target.value && this.f.form.value.searchBy) {
      if(this.f.form.value.searchBy == 'sku'){
        this.skuInventroy(this.f.form.value.clientId, e.target.value);
      }else{
        this.locationInventroy(this.f.form.value.clientId, e.target.value);
      }
    }
  }
  

  InventoryData(){
    this.InventoryService.inventory().subscribe(res=>{
      if (res.message && res.failed) {
        alert(res.message);
        return;
      }
      this.clientData = res.clientData.records;
    })
  }

  locationInventroy(custId: string, sacn: string) {
    this.InventoryService.getLocationInventory(custId, sacn).subscribe((res: any) => {
      if (res.status === 0) {
        this.snackbar.open(res.message, "Dismiss");
      }
      if (res.status === 1) {
        if(res.getLocationData.records == ""){
          this.snackbar.open(res.message,"No Records has been found!");
        }else{
          this.records = res.getLocationData.records;
          this.dataSource = new MatTableDataSource<any>(this.records);
          if(this.matPaginator){
            this.dataSource.paginator = this.matPaginator;
          }
        }
      }
    },
      err => {
        this.snackbar.open(err.error.message || "Something went wrong", "dismiss")

      })
  }

  skuInventroy(custId: string, sacn: string) {
    this.InventoryService.getSkuInventory(custId, sacn).subscribe((res: any) => {
      if (res.status === 0) {
        this.snackbar.open(res.message, "Dismiss");
      }
      if (res.status === 1) {
        if(res.getSkuData.records == ""){
          this.snackbar.open(res.message,"No Records has been found!");
        }else{
          this.records = res.getSkuData.records;
          this.dataSource = new MatTableDataSource<any>(this.records);
          if(this.matPaginator){
            this.dataSource.paginator = this.matPaginator;
          }
        }
      }
    },
      err => {
        this.snackbar.open(err.error.message || "Something went wrong", "dismiss")

      })
  }

}


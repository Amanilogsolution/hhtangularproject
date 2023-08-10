import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { PickListService } from '../pick-list.service';

@Component({
  selector: 'app-pending-picklist',
  templateUrl: './pending.component.html',
  styleUrls: ['./pending.component.scss']
})
export class PendingPicklistComponent implements OnInit, OnDestroy {
  location: any = '';
  LocationSub: Subscription = new Subscription();
  pendingPicklist:any=[];
  pageNumber:number=1;
  error:boolean = false;
  errorMsg:string="";
  numberOfRecords:number=0


  constructor(private router: Router,
    private pckService: PickListService,
    private commonService: CommonService) {

    this.commonService.selectedWarehouse.subscribe(res => {
      this.location = res
    }).unsubscribe();

  }
  ngOnDestroy() {
    this.LocationSub.unsubscribe()
  }
  ngOnInit(): void {
    this.getData()
  }
  getData(pageNumber=1) {
    this.pckService.getPendingPickList(pageNumber).subscribe(res => {

      if (!res.picklist.records.length) {
       this.error= true;
       this.errorMsg=`No data found for location ${this.location.WHNAME}`
      }else{
        this.pendingPicklist=res.picklist.records;
        this.numberOfRecords =res.picklist.numberOfRecords
      }

    },
    err=>{
       this.error= true;
       this.errorMsg="Some thing went wrong"
    })
  }
  goToItem(DNNO:string){
    this.router.navigate(['dashboard','pick-list', DNNO])
  }
  onPaginateChange(event:any){
    this.getData(event.pageIndex+1)
  }
}

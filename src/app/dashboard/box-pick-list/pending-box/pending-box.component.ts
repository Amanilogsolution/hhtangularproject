import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { BoxService } from '../box.service';

@Component({
  selector: 'app-pending-box',
  templateUrl: './pending-box.component.html',
  styleUrls: ['./pending-box.component.scss']
})
export class PendingBoxComponent implements OnInit {

  location: any = '';
  LocationSub: Subscription = new Subscription();
  pendingPicklist:any=[];
  pageNumber:number=1;
  error:boolean = false;
  errorMsg:string="";
  numberOfRecords:number=0;
  boxNumber:any;
  dnno:string


  constructor(private router: Router,
    private route:ActivatedRoute,
    private boxService: BoxService,
    private commonService: CommonService) {

    this.commonService.selectedWarehouse.subscribe(res => {
      this.location = res
    }).unsubscribe();

    this.route.params.subscribe(res=>{
      this.boxNumber = res.boxNumber;

    })
    this.route.queryParams.subscribe(res=>{
      this.dnno = res.dnno
    })
    setTimeout(() => {
      this.getData()
    }, 200);

  }
  ngOnInit(): void {

  }
  getData(pageNumber=1) {
    console.log(this.dnno)
    this.boxService.getPendingBox(pageNumber, this.dnno).subscribe((res:any) => {
      console.log(res)
      
      if (!res.pendingBoxlist.records.length) {
       this.error= true;
       this.errorMsg=`No data found for location ${this.location.WHNAME}`
      }else{
        this.pendingPicklist=res.pendingBoxlist.records;
        this.numberOfRecords =res.pendingBoxlist.numberOfRecords
      }

    },
    (err:any)=>{
      console.log(err)
       this.error= true;
       this.errorMsg="Some thing went wrong"
    })
    console.log(this.boxService)

  }
  goToItem(item:any){
    this.router.navigate(['dashboard','box-pick-list', item.dnno],{queryParams:{custinvno:item.custinvno, custid:item.custid, InvQty:item.InvQty, PickQTy:item.PickQTy}})
  }

  onPaginateChange(event:any){
    this.getData(event.pageIndex+1)
  }
  navigateToNext(item:any){
    console.log(item)
    this.router.navigate(['dashboard', 'box-pick-list',item.dnno], {queryParams:item})
  }
}

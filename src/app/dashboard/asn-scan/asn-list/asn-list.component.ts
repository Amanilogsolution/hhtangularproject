
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AsnService } from '../asn.service';

@Component({
  selector: 'app-asn-list',
  templateUrl: './asn-list.component.html',
  styleUrls: ['./asn-list.component.scss']
})
export class AsnListComponent implements OnInit {
  list=[];
  error:boolean= false;
  pageNumber:number=1;
  errorMsg:string='';
  numberOfRecords:number=0;
  term:string;
  perPage:string="5"

  constructor(
    private route:ActivatedRoute,
    private asnService:AsnService,
    private router:Router
  ) {
    this.route.queryParams.subscribe(res=>{
      this.term= res.term;
      this.getData()
    })

  }

  ngOnInit(): void {

  }
  getData(pageNumber=1){
    this.asnService.getAsnList(this.term,  pageNumber.toString(), this.perPage)
    .subscribe((res:any)=>{


      if (!res.asnlist.records.length) {
        this.error= true;
        this.errorMsg=`No data found for choosen location`
       }else{
        this.numberOfRecords =   res.asnlist.numberOfRecords;
        this.list= res.asnlist.records
       }
    },err=>{
      this.error= true;
      this.errorMsg=`Something went wrong`
    })
  }
  onPaginateChange(event:any){
    this.getData(event.pageIndex+1)
  }

  goToItem(item:any){
    this.router.navigate(['dashboard','asn','detail'], {queryParams:{asn:item.asnNo}})
  }
}

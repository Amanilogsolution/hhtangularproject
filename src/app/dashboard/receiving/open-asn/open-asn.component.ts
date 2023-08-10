import { Component, OnInit } from '@angular/core';
import { ReceivingService } from '../receiving.service';

@Component({
  selector: 'app-open-asn',
  templateUrl: './open-asn.component.html',
  styleUrls: ['./open-asn.component.scss']
})
export class OpenASNComponent implements OnInit {
  errorMsg:string="";
  listItems:any=[]
  pageNumber:number=1;
  error:boolean = false;
  numberOfRecords:number=0

  constructor(
    private receivingService: ReceivingService
  ) {
    this.getASNList(this.pageNumber)

   }

   getASNList(pageNumber:number){
    this.error=false;
    this.receivingService.getOpenASN(pageNumber).subscribe(res=>{
      this.listItems= res.asnlist.records
      this.numberOfRecords= res.asnlist.numberOfRecords
      if(!this.listItems.length){
        this.error=true
        this.errorMsg="No Records found"
      }
    },err=>{
      this.error=true
      this.errorMsg="Some Error Occured"
    })
   }

  ngOnInit(): void {
  }
  onPaginateChange(event:any){
    this.getASNList(event.pageIndex+1)
  }
}

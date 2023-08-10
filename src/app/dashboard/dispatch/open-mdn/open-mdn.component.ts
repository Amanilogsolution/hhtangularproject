import { Component, OnInit } from '@angular/core';
import { DispatchService } from '../dispatch.service';

@Component({
  selector: 'app-open-mdn',
  templateUrl: './open-mdn.component.html',
  styleUrls: ['./open-mdn.component.scss']
})
export class OpenMdnComponent implements OnInit {

  errorMsg:string="";
  listItems:any=[]
  pageNumber:number=1;
  error:boolean = false;
  numberOfRecords:number=0

  constructor(
    private receivingService: DispatchService
  ) {
    this.getASNList(this.pageNumber)

   }

   getASNList(pageNumber:number){
    this.error=false;
    this.receivingService.getOpenMDN(pageNumber).subscribe(res=>{
      this.listItems= res.mdnlist.records
      this.numberOfRecords= res.mdnlist.numberOfRecords
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

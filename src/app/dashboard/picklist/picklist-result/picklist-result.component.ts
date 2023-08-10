import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { PickListService } from '../pick-list.service';

@Component({
  selector: 'app-picklist-result',
  templateUrl: './picklist-result.component.html',
  styleUrls: ['./picklist-result.component.scss']
})
export class PicklistResultComponent implements OnInit {
  picklist:any;
  pickListDetails:any;
  error:boolean= false;
  errorMsg:string=''

  constructor(private route: ActivatedRoute,
    private pickListService:PickListService) {
    this.route.params.subscribe(res=>{
      this.picklist = res.picklist;
    })
  }

  ngOnInit(): void {
    console.log(this.picklist)
    this.pickListService.getPickListData(this.picklist)
    .subscribe(res=>{
      this.pickListDetails=res.pickListDetails;

      if (!res.pickListDetails.length) {
        this.error= true;
        this.errorMsg=`No Picklist data found for ${this.picklist} at choosen location`
       }else{
         this.pickListDetails=res.pickListDetails;
       }
    },err=>{
      this.error= true;
      this.errorMsg=`Something went wrong`
    })
  }

}

import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-scantest',
  templateUrl: './scantest.component.html',
  styleUrls: ['./scantest.component.scss']
})
export class ScantestComponent implements OnInit {
  results:any=[];


  constructor() {}
  ngOnInit() {

  }
  newResult(newResult:any){
    this.results.unshift(newResult)
  }

}

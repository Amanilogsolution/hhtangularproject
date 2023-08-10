import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-box-scan',
  templateUrl: './box-scan.component.html',
  styleUrls: ['./box-scan.component.scss']
})
export class BoxScanComponent implements OnInit {

  constructor(
    private router:Router
  ) { }

  ngOnInit(): void {
  }

  newResult(data: any) {
    if(data && data.success){
     this.router.navigate(['dashboard','box-pick-list','pending'], {queryParams:{dnno:data.result}})
    }
  }

}

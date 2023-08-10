import { Component, OnDestroy, OnInit } from '@angular/core';
import { Router } from '@angular/router';

declare let ZXing: any;
@Component({
  selector: 'app-scan-picklist',
  templateUrl: './scan-picklist.component.html',
  styleUrls: ['./scan-picklist.component.scss']
})
export class ScanPicklistComponent implements OnInit {

  constructor(
    private router: Router
  ) { }

  ngOnInit(): void {
  }
  newResult(data: any) {
    if(data && data.success){
     this.router.navigate(['dashboard','pick-list', data.result])
    }
  }
}

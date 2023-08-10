import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-asn-scaner',
  templateUrl: './asn-scaner.component.html',
  styleUrls: ['./asn-scaner.component.scss']
})
export class AsnScanerComponent implements OnInit {

  constructor(
    private  router:Router
  ) { }

  ngOnInit(): void {
  }
  newResult(data: any) {
    if(data && data.success){
      this.router.navigate(['dashboard','asn','list'], {queryParams:{term:data.result}})
    }
  }

}

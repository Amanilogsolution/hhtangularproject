import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-asn-scan',
  templateUrl: './asn-scan.component.html',
  styleUrls: ['./asn-scan.component.scss']
})
export class AsnScanComponent implements OnInit {

  constructor(
    private router: Router
  ) { }

  ngOnInit(): void {
  }
  search(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.router.navigate(['dashboard','asn','list'], {queryParams:{term:form.value.term}})
  }
}

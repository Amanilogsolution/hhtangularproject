import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-receiving',
  templateUrl: './receiving.component.html',
  styleUrls: ['./receiving.component.scss']
})
export class ReceivingComponent implements OnInit {

  constructor(
    private router : Router,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
  }
  search(form:NgForm){
    if(!form.valid){
      return
    }
    this.router.navigate(["open-asn",form.value.asn], {relativeTo:this.route})

  }
}

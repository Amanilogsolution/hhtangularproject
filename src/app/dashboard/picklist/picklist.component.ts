import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-picklist',
  templateUrl: './picklist.component.html',
  styleUrls: ['./picklist.component.scss']
})
export class PicklistComponent implements OnInit {

    constructor(private router :Router,
      private route: ActivatedRoute) { }

  ngOnInit(): void {
  }
  search(form:NgForm){
    if(!form.valid){
      return
    }
    this.router.navigate([form.value.picklist], {relativeTo:this.route})
  }

}

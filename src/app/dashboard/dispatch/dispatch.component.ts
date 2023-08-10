import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-dispatch',
  templateUrl: './dispatch.component.html',
  styleUrls: ['./dispatch.component.scss']
})
export class DispatchComponent implements OnInit {

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
    this.router.navigate(["open-mdn",form.value.mdn], {relativeTo:this.route})

  }
}

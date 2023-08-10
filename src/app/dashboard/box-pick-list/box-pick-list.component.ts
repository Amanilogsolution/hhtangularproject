import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CommonService } from 'src/app/common/common.service';

@Component({
  selector: 'app-box-pick-list',
  templateUrl: './box-pick-list.component.html',
  styleUrls: ['./box-pick-list.component.scss']
})
export class BoxPickListComponent implements OnInit {
  wh: any;
  constructor(private router: Router,
    private commonService: CommonService,
    private route: ActivatedRoute) {

  }

  ngOnInit(): void {
  }
  search(form: NgForm) {
    if (!form.valid) {
      return
    }
    this.router.navigate(['dashboard','box-pick-list','pending'], {queryParams:{dnno:form.value.picklist}})
  }
}

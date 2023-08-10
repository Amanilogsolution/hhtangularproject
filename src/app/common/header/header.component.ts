import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/login/auth.service';
import { CommonService } from '../common.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  constructor(
    private location:Location,
    private authService: AuthService,
    private commonService: CommonService
  ) { }

  ngOnInit(): void {
  }
  goback(){
    this.location.back();
  }
  logout(){
    this.authService.logout()
  }
  toggleSideNav(){
    this.commonService.toggleSideNav()
  }

}

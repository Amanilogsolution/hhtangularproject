import { Component, OnDestroy, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { CommonService } from '../common/common.service';
import { AuthService } from '../login/auth.service';
import { PickWarehouseLocationComponent } from './pick-warehouse-location/pick-warehouse-location.component';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit, OnDestroy {
  navOpen:boolean= false;
  userSub:Subscription = new Subscription();
  user:any;
  selectedWarehouse:Subscription= new Subscription()
  wareHouse:any;


  constructor(
    private commonService: CommonService,
    private authService:AuthService,
    public dialog:MatDialog,
    private router: Router

  ) {
    this.commonService.menuToggle.subscribe(res=>{
      this.navOpen = !this.navOpen
    })
    this.userSub= this.authService.user.subscribe(user=>{

       this.user = user;
     })
     this.selectedWarehouse = this.commonService.selectedWarehouse.subscribe(res=>{

      this.wareHouse = res

    })
  }

  ngOnInit(): void {


  }

  ngOnDestroy(){
    this.selectedWarehouse.unsubscribe()
  }
  changeLocatoin(){
    let dialogRef = this.dialog.open(PickWarehouseLocationComponent)

    dialogRef.afterClosed().subscribe(res=>{
      if(res){
        this.router.navigate(["dashboard", "summary"])
      }
    })

  }
}

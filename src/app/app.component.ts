import { analyzeAndValidateNgModules } from '@angular/compiler';
import { ChangeDetectorRef, Component, OnDestroy, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Subscription, fromEvent, Observable, of, } from 'rxjs';
import { map } from 'rxjs/operators';
import { CommonService } from './common/common.service';
import { PickWarehouseLocationComponent } from './dashboard/pick-warehouse-location/pick-warehouse-location.component';
import { AuthService } from './login/auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, OnDestroy {
 onlineEvent?: Observable<Event>;
  public offlineEvent?: Observable<Event>;
  public subscriptions: Subscription[] = [];
  public connectionStatusMessage?: string;
  public connectionStatus?: string;

  title = 'swim';
  user: Subscription = new Subscription();
  appLoading: boolean = false;
  constructor(
    private authService: AuthService,
    private commonService: CommonService,
    public dialog: MatDialog,
    private cdRef: ChangeDetectorRef
  ) {

    this.onlineEvent = fromEvent(window, 'online');
    this.offlineEvent = fromEvent(window, 'offline');
    this.subscriptions.push(this.onlineEvent.subscribe(event => {
      this.connectionStatusMessage = 'Connected to internet! You are online';
      this.connectionStatus = 'online';
    }));
    this.subscriptions.push(this.offlineEvent.subscribe(e => {
      this.connectionStatusMessage = 'Connection lost! You are offline. Your data will not be saved';
      this.connectionStatus = 'offline';
    }));
  }

  ngOnInit() {
    this.commonService.getSpinnerObserver().subscribe((status) => {
      this.appLoading = (status === 'start');
      this.cdRef.detectChanges();
    });



    this.authService.autoLogin();
    this.user = this.authService.user.subscribe(u => {
      if (u) {
        let warehouseLocation = u.warehouseLocation;
        if (warehouseLocation.length === 1) {
          this.commonService.setUserLocation(warehouseLocation[0])
        }
        else if (warehouseLocation.length > 1) {

          this.dialog.open(PickWarehouseLocationComponent, { disableClose: true })
        }
      }

    })
  }
  ngOnDestroy() {
    this.subscriptions.forEach(subscription => subscription.unsubscribe());

  }

}

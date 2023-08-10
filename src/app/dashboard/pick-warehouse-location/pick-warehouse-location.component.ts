import { AfterViewInit, Component, ElementRef, Inject, OnInit, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { Subscription } from 'rxjs';
import { CommonService } from 'src/app/common/common.service';
import { AuthService } from 'src/app/login/auth.service';

@Component({
  selector: 'app-pick-warehouse-location',
  templateUrl: './pick-warehouse-location.component.html',
  styleUrls: ['./pick-warehouse-location.component.scss']
})
export class PickWarehouseLocationComponent implements OnInit, AfterViewInit {
  @ViewChild('location', { static: true }) location?: ElementRef
  user: Subscription = new Subscription();
  data: any;

  constructor(
    public dialogRef: MatDialogRef<PickWarehouseLocationComponent>,
    private authService: AuthService,
    private commonService: CommonService
  ) { }

  ngOnInit(): void {
    this.user = this.authService.user.subscribe(u => {
      if (u) {
        this.data = u.warehouseLocation;
      }
    })
  }
  ngAfterViewInit() {

  }
  onChage() {
    if (this.location?.nativeElement.value) {

      this.commonService.setUserLocation(this.data[this.location?.nativeElement.value])
    }
    this.dialogRef.close(this.location?.nativeElement.value);
  }

}

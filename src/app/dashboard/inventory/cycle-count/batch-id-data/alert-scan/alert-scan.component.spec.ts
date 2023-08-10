import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AlertScanComponent } from './alert-scan.component';

describe('AlertScanComponent', () => {
  let component: AlertScanComponent;
  let fixture: ComponentFixture<AlertScanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AlertScanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AlertScanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

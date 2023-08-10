import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnScanRfidComponent } from './asn-scan-rfid.component';

describe('AsnScanRfidComponent', () => {
  let component: AsnScanRfidComponent;
  let fixture: ComponentFixture<AsnScanRfidComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnScanRfidComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnScanRfidComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

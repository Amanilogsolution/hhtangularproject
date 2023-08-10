import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnScanComponent } from './asn-scan.component';

describe('AsnScanComponent', () => {
  let component: AsnScanComponent;
  let fixture: ComponentFixture<AsnScanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnScanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnScanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

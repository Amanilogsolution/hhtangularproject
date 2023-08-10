import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnScanBoxComponent } from './asn-scan-box.component';

describe('AsnScanBoxComponent', () => {
  let component: AsnScanBoxComponent;
  let fixture: ComponentFixture<AsnScanBoxComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnScanBoxComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnScanBoxComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

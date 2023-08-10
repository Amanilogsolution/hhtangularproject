import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnScanManualUpdateComponent } from './asn-scan-manual-update.component';

describe('AsnScanManualUpdateComponent', () => {
  let component: AsnScanManualUpdateComponent;
  let fixture: ComponentFixture<AsnScanManualUpdateComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnScanManualUpdateComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnScanManualUpdateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

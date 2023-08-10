import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HhtSkuScanComponent } from './hht-sku-scan.component';

describe('HhtSkuScanComponent', () => {
  let component: HhtSkuScanComponent;
  let fixture: ComponentFixture<HhtSkuScanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HhtSkuScanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(HhtSkuScanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

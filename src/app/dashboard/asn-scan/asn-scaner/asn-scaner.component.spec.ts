import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnScanerComponent } from './asn-scaner.component';

describe('AsnScanerComponent', () => {
  let component: AsnScanerComponent;
  let fixture: ComponentFixture<AsnScanerComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnScanerComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnScanerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

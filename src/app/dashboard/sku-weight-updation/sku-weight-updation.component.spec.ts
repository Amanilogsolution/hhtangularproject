import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SkuWeightUpdationComponent } from './sku-weight-updation.component';

describe('SkuWeightUpdationComponent', () => {
  let component: SkuWeightUpdationComponent;
  let fixture: ComponentFixture<SkuWeightUpdationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SkuWeightUpdationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SkuWeightUpdationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

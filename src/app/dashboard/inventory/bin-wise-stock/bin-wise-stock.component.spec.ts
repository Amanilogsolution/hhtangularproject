import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BinWiseStockComponent } from './bin-wise-stock.component';

describe('BinWiseStockComponent', () => {
  let component: BinWiseStockComponent;
  let fixture: ComponentFixture<BinWiseStockComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BinWiseStockComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BinWiseStockComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BinToContentTransferComponent } from './bin-to-content-transfer.component';

describe('BinToContentTransferComponent', () => {
  let component: BinToContentTransferComponent;
  let fixture: ComponentFixture<BinToContentTransferComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BinToContentTransferComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BinToContentTransferComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

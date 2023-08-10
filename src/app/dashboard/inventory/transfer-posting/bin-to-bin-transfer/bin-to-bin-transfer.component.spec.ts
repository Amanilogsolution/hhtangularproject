import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BinToBinTransferComponent } from './bin-to-bin-transfer.component';

describe('BinToBinTransferComponent', () => {
  let component: BinToBinTransferComponent;
  let fixture: ComponentFixture<BinToBinTransferComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BinToBinTransferComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BinToBinTransferComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GetBtnBinComponent } from './get-btn-bin.component';

describe('GetBtnBinComponent', () => {
  let component: GetBtnBinComponent;
  let fixture: ComponentFixture<GetBtnBinComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GetBtnBinComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GetBtnBinComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

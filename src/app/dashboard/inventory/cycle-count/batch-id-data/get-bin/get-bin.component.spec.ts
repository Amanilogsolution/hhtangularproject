import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GetBinComponent } from './get-bin.component';

describe('GetBinComponent', () => {
  let component: GetBinComponent;
  let fixture: ComponentFixture<GetBinComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GetBinComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GetBinComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

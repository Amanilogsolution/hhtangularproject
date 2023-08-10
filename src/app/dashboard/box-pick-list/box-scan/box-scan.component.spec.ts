import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BoxScanComponent } from './box-scan.component';

describe('BoxScanComponent', () => {
  let component: BoxScanComponent;
  let fixture: ComponentFixture<BoxScanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BoxScanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BoxScanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BoxPicklistLocationComponent } from './box-picklist-location.component';

describe('BoxPicklistLocationComponent', () => {
  let component: BoxPicklistLocationComponent;
  let fixture: ComponentFixture<BoxPicklistLocationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BoxPicklistLocationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BoxPicklistLocationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

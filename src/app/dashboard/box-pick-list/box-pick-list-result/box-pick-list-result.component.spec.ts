import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BoxPickListResultComponent } from './box-pick-list-result.component';

describe('BoxPickListResultComponent', () => {
  let component: BoxPickListResultComponent;
  let fixture: ComponentFixture<BoxPickListResultComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BoxPickListResultComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BoxPickListResultComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

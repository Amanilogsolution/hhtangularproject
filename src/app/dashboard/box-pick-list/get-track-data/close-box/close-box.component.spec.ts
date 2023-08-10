import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CloseBoxComponent } from './close-box.component';

describe('CloseBoxComponent', () => {
  let component: CloseBoxComponent;
  let fixture: ComponentFixture<CloseBoxComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CloseBoxComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CloseBoxComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

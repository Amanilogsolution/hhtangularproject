import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PutawayboxComponent } from './putawaybox.component';

describe('PutawayboxComponent', () => {
  let component: PutawayboxComponent;
  let fixture: ComponentFixture<PutawayboxComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PutawayboxComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PutawayboxComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

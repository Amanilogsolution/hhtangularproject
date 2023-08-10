import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CycleAislesComponent } from './cycle-aisles.component';

describe('CycleAislesComponent', () => {
  let component: CycleAislesComponent;
  let fixture: ComponentFixture<CycleAislesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CycleAislesComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CycleAislesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

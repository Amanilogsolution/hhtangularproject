import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GetAislesDataComponent } from './get-aisles-data.component';

describe('GetAislesDataComponent', () => {
  let component: GetAislesDataComponent;
  let fixture: ComponentFixture<GetAislesDataComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GetAislesDataComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GetAislesDataComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

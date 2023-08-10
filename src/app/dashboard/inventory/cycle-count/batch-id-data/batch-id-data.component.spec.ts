import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BatchIdDataComponent } from './batch-id-data.component';

describe('BatchIdDataComponent', () => {
  let component: BatchIdDataComponent;
  let fixture: ComponentFixture<BatchIdDataComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BatchIdDataComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BatchIdDataComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

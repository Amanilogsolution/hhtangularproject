import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HuContentSearchComponent } from './hu-content-search.component';

describe('HuContentSearchComponent', () => {
  let component: HuContentSearchComponent;
  let fixture: ComponentFixture<HuContentSearchComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HuContentSearchComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(HuContentSearchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

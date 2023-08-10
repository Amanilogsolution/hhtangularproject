import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BoxManualUpdateComponent } from './box-manual-update.component';

describe('BoxManualUpdateComponent', () => {
  let component: BoxManualUpdateComponent;
  let fixture: ComponentFixture<BoxManualUpdateComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BoxManualUpdateComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BoxManualUpdateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

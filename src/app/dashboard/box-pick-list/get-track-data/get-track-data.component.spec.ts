import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GetTrackDataComponent } from './get-track-data.component';

describe('GetTrackDataComponent', () => {
  let component: GetTrackDataComponent;
  let fixture: ComponentFixture<GetTrackDataComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GetTrackDataComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GetTrackDataComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

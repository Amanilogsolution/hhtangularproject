import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AsnUpdateComponent } from './asn-update.component';

describe('AsnUpdateComponent', () => {
  let component: AsnUpdateComponent;
  let fixture: ComponentFixture<AsnUpdateComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AsnUpdateComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AsnUpdateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

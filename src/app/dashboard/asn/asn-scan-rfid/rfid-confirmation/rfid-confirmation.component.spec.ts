import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RfidConfirmationComponent } from './rfid-confirmation.component';

describe('RfidConfirmationComponent', () => {
  let component: RfidConfirmationComponent;
  let fixture: ComponentFixture<RfidConfirmationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RfidConfirmationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RfidConfirmationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

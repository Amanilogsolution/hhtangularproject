import { TestBed } from '@angular/core/testing';

import { PutawayboxService } from './putawaybox.service';

describe('PutawayboxService', () => {
  let service: PutawayboxService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PutawayboxService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

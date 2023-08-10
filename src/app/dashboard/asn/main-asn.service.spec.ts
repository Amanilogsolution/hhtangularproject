import { TestBed } from '@angular/core/testing';

import { MainAsnService } from './main-asn.service';

describe('MainAsnService', () => {
  let service: MainAsnService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MainAsnService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

import { TestBed } from '@angular/core/testing';

import { AsnScanBoxService } from './asn-scan-box.service';

describe('AsnScanBoxService', () => {
  let service: AsnScanBoxService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(AsnScanBoxService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

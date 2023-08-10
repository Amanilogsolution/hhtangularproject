import { TestBed } from '@angular/core/testing';

import { SkuWeightUpdationService } from './sku-weight-updation.service';

describe('SkuWeightUpdationService', () => {
  let service: SkuWeightUpdationService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SkuWeightUpdationService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

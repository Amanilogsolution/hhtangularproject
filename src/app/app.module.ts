import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { MaterialModule } from './material/material.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LoginComponent } from './login/login.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptorService } from './login/auth-interceptor.service';
import { FormsModule } from '@angular/forms';
import { HeaderComponent } from './common/header/header.component';
import { FooterComponent } from './common/footer/footer.component';
import { SummeryComponent } from './dashboard/summery/summery.component';
import { PutawayComponent } from './dashboard/putaway/putaway.component';
import { PicklistComponent } from './dashboard/picklist/picklist.component';
import { ScantestComponent } from './dashboard/scantest/scantest.component';
import { PendingPicklistComponent } from './dashboard/picklist/pending-picklist/pending.component';
import { PicklistResultComponent } from './dashboard/picklist/picklist-result/picklist-result.component';
import { ScanPicklistComponent } from './dashboard/picklist/scan-picklist/scan-picklist.component';
import { ServiceWorkerModule } from '@angular/service-worker';
import { environment } from '../environments/environment';
import { PickWarehouseLocationComponent } from './dashboard/pick-warehouse-location/pick-warehouse-location.component';
import { ScanerComponent } from './scaner/scaner.component';
import { PicklistLocationComponent } from './dashboard/picklist/picklist-result/picklist-location/picklist-location.component';
import { ManualUpdateComponent } from './dashboard/picklist/picklist-result/picklist-location/manual-update/manual-update.component';
import { ConnectionStatusComponent } from './connection-status/connection-status.component';
import { ReceivingComponent } from './dashboard/receiving/receiving.component';
import { DispatchComponent } from './dashboard/dispatch/dispatch.component';
import { OpenASNComponent } from './dashboard/receiving/open-asn/open-asn.component';
import { UploadASNInfoComponent } from './dashboard/receiving/upload-asn-info/upload-asn-info.component';
import { OpenMdnComponent } from './dashboard/dispatch/open-mdn/open-mdn.component';
import { UploadMdnInfoComponent } from './dashboard/dispatch/upload-mdn-info/upload-mdn-info.component';
import { BoxPickListComponent } from './dashboard/box-pick-list/box-pick-list.component';
import { PendingBoxComponent } from './dashboard/box-pick-list/pending-box/pending-box.component';
import { MAT_SNACK_BAR_DEFAULT_OPTIONS } from '@angular/material/snack-bar';
import { BoxPickListResultComponent } from './dashboard/box-pick-list/box-pick-list-result/box-pick-list-result.component';
import { BoxPicklistLocationComponent } from './dashboard/box-pick-list/box-pick-list-result/box-picklist-location/box-picklist-location.component';
import { BoxManualUpdateComponent } from './dashboard/box-pick-list/box-pick-list-result/box-picklist-location/box-manual-update/box-manual-update.component';
import { BoxScanComponent } from './dashboard/box-pick-list/box-scan/box-scan.component';
import { AsnScanComponent } from './dashboard/asn-scan/asn-scan.component';
import { AsnListComponent } from './dashboard/asn-scan/asn-list/asn-list.component';
import { AsnUpdateComponent } from './dashboard/asn-scan/asn-update/asn-update.component';
import { AsnScanerComponent } from './dashboard/asn-scan/asn-scaner/asn-scaner.component';
import { ScanModalComponent } from './common/scan-modal/scan-modal.component';
import { HhtSkuScanComponent } from './dashboard/asn-scan/hht-sku-scan/hht-sku-scan.component';
import { GetAislesDataComponent } from './dashboard/box-pick-list/get-aisles-data/get-aisles-data.component';
import { GetTrackDataComponent } from './dashboard/box-pick-list/get-track-data/get-track-data.component';
import { AsnScanBoxComponent } from './dashboard/asn-scan-box/asn-scan-box.component';
import { AsnScanManualUpdateComponent } from './dashboard/asn-scan-box/asn-scan-manual-update/asn-scan-manual-update.component';
import { PutawayboxComponent } from './dashboard/putawaybox/putawaybox.component';
import { CloseBoxComponent } from './dashboard/box-pick-list/get-track-data/close-box/close-box.component';
import { SkuWeightUpdationComponent } from './dashboard/sku-weight-updation/sku-weight-updation.component';
import { InventoryComponent } from './dashboard/inventory/inventory.component';
import { PickClientComponent } from './dashboard/inventory/pick-client/pick-client.component';
import { BinWiseStockComponent } from './dashboard/inventory/bin-wise-stock/bin-wise-stock.component';
import { CycleCountComponent } from './dashboard/inventory/cycle-count/cycle-count.component';
import { TransferPostingComponent } from './dashboard/inventory/transfer-posting/transfer-posting.component';
import { BatchIdDataComponent } from './dashboard/inventory/cycle-count/batch-id-data/batch-id-data.component';
import { GetBinComponent } from './dashboard/inventory/cycle-count/batch-id-data/get-bin/get-bin.component';
import { ConfirmationComponent } from './dashboard/inventory/cycle-count/batch-id-data/confirmation/confirmation.component';
import { HuContentSearchComponent } from './dashboard/inventory/hu-content-search/hu-content-search.component';
import { AsnComponent } from './dashboard/asn/asn.component';
import { AsnScanRfidComponent } from './dashboard/asn/asn-scan-rfid/asn-scan-rfid.component';
import { RfidConfirmationComponent } from './dashboard/asn/asn-scan-rfid/rfid-confirmation/rfid-confirmation.component';
import { AlertScanComponent } from './dashboard/inventory/cycle-count/batch-id-data/alert-scan/alert-scan.component';
import { BinToBinTransferComponent } from './dashboard/inventory/transfer-posting/bin-to-bin-transfer/bin-to-bin-transfer.component';
import { BinToContentTransferComponent } from './dashboard/inventory/transfer-posting/bin-to-content-transfer/bin-to-content-transfer.component';
import { ClientDialogComponent } from './dashboard/inventory/transfer-posting/client-dialog/client-dialog.component';
import { AlertMessageComponent } from './dashboard/inventory/transfer-posting/alert-message/alert-message.component';
import { CycleAislesComponent } from './dashboard/inventory/cycle-count/cycle-aisles/cycle-aisles.component';
import { GetBtnBinComponent } from './dashboard/inventory/transfer-posting/bin-to-content-transfer/get-btn-bin/get-btn-bin.component';



@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    DashboardComponent,
    HeaderComponent,
    FooterComponent,
    SummeryComponent,
    PutawayComponent,
    PicklistComponent,
    ScantestComponent,
    PendingPicklistComponent,
    PicklistResultComponent,
    ScanPicklistComponent,
    PickWarehouseLocationComponent,
    ScanerComponent,
    PicklistLocationComponent,
    ManualUpdateComponent,
    ConnectionStatusComponent,
    ReceivingComponent,
    DispatchComponent,
    OpenASNComponent,
    UploadASNInfoComponent,
    OpenMdnComponent,
    UploadMdnInfoComponent,
    BoxPickListComponent,
    PendingBoxComponent,
    BoxPickListResultComponent,
    BoxPicklistLocationComponent,
    BoxManualUpdateComponent,
    BoxScanComponent,
    AsnScanComponent,
    AsnListComponent,
    AsnUpdateComponent,
    AsnScanerComponent,
    ScanModalComponent,
    HhtSkuScanComponent,
    GetAislesDataComponent,
    GetTrackDataComponent,
    AsnScanBoxComponent,
    AsnScanManualUpdateComponent,
    PutawayboxComponent,
    CloseBoxComponent,
    SkuWeightUpdationComponent,
    InventoryComponent,
    PickClientComponent,
    BinWiseStockComponent,
    CycleCountComponent,
    TransferPostingComponent,
    BatchIdDataComponent,
    GetBinComponent,
    ConfirmationComponent,
    HuContentSearchComponent,
    AsnComponent,
    AsnScanRfidComponent,
    RfidConfirmationComponent,
    AlertScanComponent,
    BinToBinTransferComponent,
    BinToContentTransferComponent,
    ClientDialogComponent,
    AlertMessageComponent,
    CycleAislesComponent,
    GetBtnBinComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MaterialModule,
    FormsModule,
    BrowserAnimationsModule,
    HttpClientModule,
    // ServiceWorkerModule.register('ngsw-worker.js', { enabled: true })
  ],
  providers: [ { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptorService , multi:true},
    {
      provide: MAT_SNACK_BAR_DEFAULT_OPTIONS, useValue: {
        duration: 1500,
        horizontalPosition: "center",
        verticalPosition: 'bottom'
      }
    }],
  bootstrap: [AppComponent]
})
export class AppModule { }

import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AsnScanComponent } from './dashboard/asn-scan/asn-scan.component';
import { AsnListComponent } from './dashboard/asn-scan/asn-list/asn-list.component';
import { BoxPickListResultComponent } from './dashboard/box-pick-list/box-pick-list-result/box-pick-list-result.component';
import { BoxPicklistLocationComponent } from './dashboard/box-pick-list/box-pick-list-result/box-picklist-location/box-picklist-location.component';
import { BoxPickListComponent } from './dashboard/box-pick-list/box-pick-list.component';
import { BoxScanComponent } from './dashboard/box-pick-list/box-scan/box-scan.component';
import { PendingBoxComponent } from './dashboard/box-pick-list/pending-box/pending-box.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DispatchComponent } from './dashboard/dispatch/dispatch.component';
import { OpenMdnComponent } from './dashboard/dispatch/open-mdn/open-mdn.component';
import { UploadMdnInfoComponent } from './dashboard/dispatch/upload-mdn-info/upload-mdn-info.component';
import { PendingPicklistComponent } from './dashboard/picklist/pending-picklist/pending.component';
import { PicklistLocationComponent } from './dashboard/picklist/picklist-result/picklist-location/picklist-location.component';
import { PicklistResultComponent } from './dashboard/picklist/picklist-result/picklist-result.component';
import { PicklistComponent } from './dashboard/picklist/picklist.component';
import { ScanPicklistComponent } from './dashboard/picklist/scan-picklist/scan-picklist.component';
import { PutawayComponent } from './dashboard/putaway/putaway.component';
import { OpenASNComponent } from './dashboard/receiving/open-asn/open-asn.component';
import { ReceivingComponent } from './dashboard/receiving/receiving.component';
import { UploadASNInfoComponent } from './dashboard/receiving/upload-asn-info/upload-asn-info.component';
import { ScantestComponent } from './dashboard/scantest/scantest.component';
import { SummeryComponent } from './dashboard/summery/summery.component';
import { AuthGaurd } from './login/auth.gaurd';
import { LoginComponent } from './login/login.component';
import { AsnUpdateComponent } from './dashboard/asn-scan/asn-update/asn-update.component';
import { AsnScanerComponent } from './dashboard/asn-scan/asn-scaner/asn-scaner.component';
import { GetAislesDataComponent } from './dashboard/box-pick-list/get-aisles-data/get-aisles-data.component';
import { GetTrackDataComponent } from './dashboard/box-pick-list/get-track-data/get-track-data.component';
import { AsnScanBoxComponent } from './dashboard/asn-scan-box/asn-scan-box.component';
import { PutawayboxComponent } from './dashboard/putawaybox/putawaybox.component';
import { SkuWeightUpdationComponent } from './dashboard/sku-weight-updation/sku-weight-updation.component';
import { InventoryComponent } from './dashboard/inventory/inventory.component';
import { BinWiseStockComponent } from './dashboard/inventory/bin-wise-stock/bin-wise-stock.component';
import { CycleCountComponent } from './dashboard/inventory/cycle-count/cycle-count.component';
import { TransferPostingComponent } from './dashboard/inventory/transfer-posting/transfer-posting.component';
import { BatchIdDataComponent } from './dashboard/inventory/cycle-count/batch-id-data/batch-id-data.component';
import { HuContentSearchComponent } from './dashboard/inventory/hu-content-search/hu-content-search.component';
import { AsnComponent } from './dashboard/asn/asn.component';
import { AsnScanRfidComponent } from './dashboard/asn/asn-scan-rfid/asn-scan-rfid.component';
import { BinToBinTransferComponent } from './dashboard/inventory/transfer-posting/bin-to-bin-transfer/bin-to-bin-transfer.component';
import { BinToContentTransferComponent } from './dashboard/inventory/transfer-posting/bin-to-content-transfer/bin-to-content-transfer.component';
import { CycleAislesComponent } from './dashboard/inventory/cycle-count/cycle-aisles/cycle-aisles.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  {
    path: "dashboard", component: DashboardComponent, canActivate: [AuthGaurd], children: [
      { path: "summary", component: SummeryComponent },
      { path: "pick-list", component: PicklistComponent },
      { path: "pick-list/pending", component: PendingPicklistComponent },
      { path: "pick-list/scan", component: ScanPicklistComponent },
      { path: "pick-list/:picklist", component: PicklistResultComponent },
      { path: "pick-list/:picklist/:rackLocation", component: PicklistLocationComponent },
      { path: "receiving", component: ReceivingComponent },
      { path: "receiving/open-asn", component: OpenASNComponent },
      { path: "receiving/open-asn/:asn", component: UploadASNInfoComponent },
      { path: "put-away", component: PutawayComponent },
      { path: "scan-test", component: ScantestComponent },
      { path: "dispatch", component: DispatchComponent },
      { path: "dispatch/open-mdn", component: OpenMdnComponent },
      { path: "dispatch/open-mdn/:mdn", component: UploadMdnInfoComponent },

      { path: "box-pick-list", component: BoxPickListComponent },
      { path: "box-pick-list/pending", component: PendingBoxComponent },
      { path: "box-pick-list/scan", component: BoxScanComponent },
      { path: "box-pick-list/:picklist", component: BoxPickListResultComponent },
      { path: "box-pick-list/:picklist/:dnno", component: BoxPicklistLocationComponent },
      { path: "get-aisles-data/:custid/:custinvno", component: GetAislesDataComponent},
      { path: "get-track-data/:custid/:custinvno", component: GetTrackDataComponent},
      { path: "asn", component: AsnScanComponent },
      { path: "asn/list", component: AsnListComponent },
      { path: "asn/detail", component:AsnUpdateComponent },

      {path: "main-asn", component:AsnComponent},
      {path: "asn-scan-rfid", component:AsnScanRfidComponent},

      { path: "asn/scan", component:AsnScanerComponent },
      { path: "return-gate-in", component:AsnScanBoxComponent },
      {path: "put-away-box", component:PutawayboxComponent},
      {path: "sku-weight-updation", component:SkuWeightUpdationComponent},
      {path: "inventory", component:InventoryComponent},
      {path: "inventory/bin-wise-stock", component:BinWiseStockComponent},
      {path: "inventory/cycle-count", component:CycleCountComponent},
      {path: "inventory/transfer-posting", component:TransferPostingComponent},
      {path: "cycle-count/batchData", component:BatchIdDataComponent},
      {path: "cycle-count/aislesData", component:CycleAislesComponent},
      {path: "inventory/hu-content-search", component:HuContentSearchComponent},
      {path: "transfer-posting/bin-to-bin-transfer/:clientId", component:BinToBinTransferComponent},
      {path: "transfer-posting/bin-to-content-transfer/:clientId", component:BinToContentTransferComponent},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

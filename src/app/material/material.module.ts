import { NgModule } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatIconModule } from '@angular/material/icon'
import {MatMenuModule} from '@angular/material/menu';
import {MatCardModule} from '@angular/material/card';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatInputModule} from "@angular/material/input";
import {MatSelectModule} from '@angular/material/select';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatListModule} from '@angular/material/list';
import {MatDialogModule} from '@angular/material/dialog';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatPaginatorModule} from '@angular/material/paginator';
import {MatSnackBarModule} from '@angular/material/snack-bar';
import {MatTabsModule} from '@angular/material/tabs';
import {MatSlideToggleModule} from '@angular/material/slide-toggle';
import {MatRadioModule} from '@angular/material/radio';
import {MatTableModule} from '@angular/material/table';

const MaterialComponents: any[] = [
  MatButtonModule,
  MatSidenavModule,
  MatToolbarModule,
  MatIconModule,
  MatMenuModule,
  MatCardModule,
  MatFormFieldModule,
  MatInputModule,
  MatSelectModule,
  MatAutocompleteModule,
  MatTooltipModule,
  MatListModule,
  MatDialogModule,
  MatProgressSpinnerModule,
  MatPaginatorModule,
  MatSnackBarModule,
  MatTabsModule,
  MatRadioModule,
  MatTableModule,
  MatSlideToggleModule
 
];

@NgModule({
  imports: [
    MaterialComponents
  ],
  exports: [
    MaterialComponents
  ]
})
export class MaterialModule { }

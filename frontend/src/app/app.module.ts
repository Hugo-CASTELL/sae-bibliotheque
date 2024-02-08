import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { LivresListComponent } from './livres-list/livres-list.component';
import { LoginComponent } from './login/login.component';
import { AccountComponent } from './account/account.component';
import { LivreDetailsComponent } from './livre-details/livre-details.component';
import { ReservationComponent } from './reservation/reservation.component';
import { ReservationCardComponent } from './reservation-card/reservation-card.component';

@NgModule({
  declarations: [
    AppComponent,
    LivresListComponent,
    LoginComponent,
    AccountComponent,
    LivreDetailsComponent,
    ReservationComponent,
    ReservationCardComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }

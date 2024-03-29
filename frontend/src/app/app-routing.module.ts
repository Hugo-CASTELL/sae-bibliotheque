import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LivresListComponent } from './livres-list/livres-list.component';
import { ReservationComponent } from './reservation/reservation.component';
import { LoginComponent } from './login/login.component';
import { AccountComponent } from './account/account.component';
import { LivreDetailsComponent } from './livre-details/livre-details.component';
import { EmpruntComponent } from './emprunt/emprunt.component';

const routes: Routes = [
  { path: 'livres',  component: LivresListComponent },
  { path: 'livre/:id', component: LivreDetailsComponent },
  { path: 'reservation',  component: ReservationComponent },
  { path: 'emprunt',  component: EmpruntComponent },
  { path: 'login', component: LoginComponent },
  { path: 'account', component: AccountComponent },
  { path: '', redirectTo: '/livres', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LivresListComponent } from './livres-list/livres-list.component';
import { LoginComponent } from './login/login.component';
import { AccountComponent } from './account/account.component';

const routes: Routes = [
  { path: 'livres',  component: LivresListComponent },
  { path: 'login', component: LoginComponent },
  { path: 'account', component: AccountComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LivresListComponent } from './livres-list/livres-list.component';

const routes: Routes = [
  { path: 'livres',  component: LivresListComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

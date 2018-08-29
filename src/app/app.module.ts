import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";

import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";


const moduleDeclarations = [AppComponent];

@NgModule({
	imports: 		[BrowserModule, HttpClientModule, ReactiveFormsModule, FormsModule, routing],
	declarations:  [...moduleDeclarations, ...allAppComponents],
	bootstrap:		[AppComponent],
	providers:		[appRoutingProviders]
})

export class AppModule {}

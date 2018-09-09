import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";

import {NgxPaginationModule} from "ngx-pagination";
import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {JwtModule} from "@auth0/angular-jwt";
import {RecaptchaModule} from "ng-recaptcha";
import {NguiMapModule} from "@ngui/map";

import { CloudinaryModule } from '@cloudinary/angular-5.x';
import * as  Cloudinary from 'cloudinary-core';



const moduleDeclarations = [AppComponent];

//configure the parameters fot the JwtModule
const JwtHelper = JwtModule.forRoot({
	config: {
		tokenGetter: () => {
			return localStorage.getItem("jwt-token");
		},
		skipWhenExpired:true,
		whitelistedDomains: ["localhost:7272", "https://bootcamp-coders.cnm.edu/"],
		headerName:"X-JWT-TOKEN",
		authScheme: ""
	}
});

@NgModule({
	imports: 		[
		BrowserModule,
		HttpClientModule,
		JwtHelper,
		ReactiveFormsModule,
		FormsModule,
		routing,
		NgxPaginationModule,
		RecaptchaModule.forRoot(),
		NguiMapModule.forRoot({apiUrl: 'https://maps.google.com/maps/api/js?key=AIzaSyBlQW1ByPR9Lj47wQv3gk8agkF8rpS4KN4'}),
		CloudinaryModule.forRoot(Cloudinary,{
			cloud_name: 'deep-dive',
			upload_preset: 'lostpaws'})
	],
	declarations:  [...moduleDeclarations, ...allAppComponents],
	bootstrap:		[AppComponent],
	providers:		[appRoutingProviders]
})

export class AppModule {}

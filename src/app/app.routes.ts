//Import needed @angularDependencies.
import {RouterModule, Routes} from "@angular/router";

// Place needed components here!
import {HomeComponent} from "./home/home.component";



// Import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";


//Import needed services
import {AnimalService} from "./shared/services/animal.service";
import {CommentService} from "./shared/services/comment.service";
import {ProfileService} from "./shared/services/profile.service";
import {SessionService} from "./shared/services/session.service";
import {SignOutService} from "./shared/services/sign.out.service";

// Add components to the array that will be passed off to the module
export const allAppComponents = [HomeComponent];
/**
 * Add routes to the array that will be passed off to the module.
 * Place them in order of most specific to least specific.
 **/
export const routes: Routes = [
	{path: "", component: HomeComponent}
];

// An array of services

const services: any[] = [AnimalService, CommentService, ProfileService, SessionService, SignOutService];

// An array of misc providers
const providers: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true}
];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);

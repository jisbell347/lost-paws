//Import needed @angularDependencies.
import {RouterModule, Routes} from "@angular/router";

// Place needed components here!
import {HomeComponent} from "./home/home.component";
import {AboutUsComponent} from "./about-us/about-us.component";
import {AnimalPostComponent} from "./animal-post/animal-post.component";
import {AnimalCardComponent} from "./animal/animal.card.component";
import {AnimalCommentComponent} from "./animal/animal.comment.component";
import {ContactComponent} from "./animal/contact.component";
import {NavbarComponent} from "./shared/components/navbar/navbar.component";
import {NavbarComponentSignedOut} from "./shared/components/navbar/navbar-signed-out.component";
import {SigninComponent} from "./shared/components/navbar/signin.component";
import {ProfileComponent} from "./profile/profile.component";


// Import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";


//Import needed services
import {AnimalService} from "./shared/services/animal.service";
import {CommentService} from "./shared/services/comment.service";
import {ProfileService} from "./shared/services/profile.service";
import {SessionService} from "./shared/services/session.service";
import {SignOutService} from "./shared/services/sign.out.service";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";



// Add components to the array that will be passed off to the module
export const allAppComponents = [HomeComponent,NavbarComponent, NavbarComponentSignedOut, AnimalCardComponent, AnimalCommentComponent, AnimalPostComponent, AboutUsComponent, ContactComponent, SigninComponent, ProfileComponent];
/**
 * Add routes to the array that will be passed off to the module.
 * Place them in order of most specific to least specific.
 **/
export const routes: Routes = [
	{path: "animal/:animalId", component: AnimalCardComponent},
	{path: "animal-post", component: AnimalPostComponent},
	{path: "about-us", component: AboutUsComponent},
	{path: "profile", component: ProfileComponent},
	{path: "", component: HomeComponent},
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

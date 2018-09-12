import {Component} from "@angular/core";
import {AuthService} from "../shared/services/auth.service";
import {ProfileService} from "../shared/services/profile.service";
import {ActivatedRoute} from "@angular/router";


@Component ({
	template: require("./home.template.html"),
	selector: "home"
})

export class HomeComponent {
	isAuthenticated: boolean;

	constructor(
		protected authService: AuthService,
		protected router: ActivatedRoute
	) {
	};

	ngOnInit() {
		//set session storage for sign in purposes
		this.router.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
		this.isAuthenticated = this.authService.isAuthenticated();
	};

}


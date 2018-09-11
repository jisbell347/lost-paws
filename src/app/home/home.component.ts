import {Component} from "@angular/core";
import {AuthService} from "../shared/services/auth.service";
import {ProfileService} from "../shared/services/profile.service";


@Component ({
	template: require("./home.template.html"),
	selector: "home"
})

export class HomeComponent {
	isAuthenticated: boolean;

	constructor(
		protected authService: AuthService
	) {
	};

	ngOnInit() {
		//set session storage for sign in purposes
		window.sessionStorage.setItem('url',window.location.href);
		this.isAuthenticated = this.authService.isAuthenticated();
	};

}


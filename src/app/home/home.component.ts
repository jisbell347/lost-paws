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
		this.isAuthenticated = this.authService.isAuthenticated();
	};

}


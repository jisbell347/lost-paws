import{Component, OnInit} from "@angular/core";
import {AuthService} from "../../services/auth.service";

@Component({
	template: require("./navbar.template.html"),
	selector: "navbar"
})

export class NavbarComponent implements OnInit{

	isAuthenticated: boolean;

	constructor(protected authService: AuthService) {

	}

	ngOnInit() {
		this.isAuthenticated = this.authService.loggedIn();
	}


}


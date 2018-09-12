import {Component} from "@angular/core";
import {AuthService} from "../shared/services/auth.service";
import {ActivatedRoute} from "@angular/router";

@Component ({
	template: require("./about-us.template.html")
})



export class AboutUsComponent {

	constructor(
		protected route: ActivatedRoute
	) {
	};

	ngOnInit() {
		//set session storage for sign in purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));

	};

}
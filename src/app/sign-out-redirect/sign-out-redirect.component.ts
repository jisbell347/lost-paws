import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";

@Component ({
	template: require("./sign-out-redirect.template.html"),
	selector: "signed-out"
})

export class SignOutRedirectComponent {
	constructor(
		protected route: ActivatedRoute
	) {
	};
	ngOnInit() {
		//set session storage for sign in purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
	};

}
import {Component} from "@angular/core";
import {AuthService} from "../shared/services/auth.service";
import {ActivatedRoute} from "@angular/router";


@Component({
	selector: "shelter",
	template: require("./shelter.info.template.html")

})

export class ShelterInfoComponent {

	constructor(

		protected route: ActivatedRoute
	) {
	};
	ngOnInit() {
		//set session storage for sign in purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
	};

}
import {Component} from "@angular/core";


@Component({
	selector: "shelter",
	template: require("./shelter.info.template.html")

})

export class ShelterInfoComponent {
	ngOnInit() {
		//set session storage for sign in purposes
		window.sessionStorage.setItem('url',window.location.href);
	};

}
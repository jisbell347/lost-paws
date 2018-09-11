import {Component} from "@angular/core";

@Component ({
	template: require("./about-us.template.html")
})



export class AboutUsComponent {
	ngOnInit() {
		//set session storage for sign in purposes
		window.sessionStorage.setItem('url',window.location.href);
	};

}
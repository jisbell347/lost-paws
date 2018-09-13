import {Component, Input, OnInit} from "@angular/core";
import {Observable} from "rxjs";
import { RecaptchaModule} from "ng-recaptcha";
import {RecaptchaFormsModule} from "ng-recaptcha/forms";

//Interfaces
import  {Profile} from "../shared/interfaces/profile";

//Services
import {ProfileService} from "../shared/services/profile.service";
import {AuthService} from "../shared/services/auth.service";

@Component({
	selector: "contact",
	template: require("./contact.template.html")
	})

export class ContactComponent  implements OnInit{
	@Input() animalProfileId: string;
	profile: Profile = null;
	isAuthenticated: boolean;
	public resolved(captchaResponse: string) {
		console.log(`Resolved captcha with response ${captchaResponse}:`);
	}


	constructor(
		protected profileService: ProfileService,
		protected authService: AuthService
	) {
	};

	ngOnInit() {
		this.profileService.getProfile(this.animalProfileId) . subscribe(profile => this.profile = profile);
		this.isAuthenticated = this.authService.isAuthenticated();
	};


}


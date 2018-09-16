import { Component, OnInit, ViewChild } from "@angular/core";
import { Profile } from "../shared/interfaces/profile";
import { ProfileService } from "../shared/services/profile.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";
import {ActivatedRoute, Router} from "@angular/router";
import {Animal} from "../shared/interfaces/animal";
import {AnimalService} from "../shared/services/animal.service";

@Component({
	selector: "profile",
	template: require("./profile.template.html")
})
export class ProfileComponent implements OnInit {
	profile: Profile = null;
	profileId: string;
	submitted = false;
	status : Status = null;
	animals: Animal[] = [];

	// need to grab profileId from the current Session
	// the remaining fields - from the form
	constructor(
		protected authService: AuthService,
		protected profileService: ProfileService,
		protected animalService: AnimalService,
		protected router: Router,
		protected route: ActivatedRoute) {
	}

	ngOnInit() : void {
		//set session storage for sign in purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
		//grab the current logged in profileId off JWT
		this.profileId = this.getJwtProfileId();
		/*console.log(this.profileId);*/
		this.loadProfile();
		this.getAnimalbyProfileId();
	}

	updateProfile(profile: Profile) : void {
		this.router.navigate(["/profile-edit/", this.profileId]);
	}

	loadProfile() {
		if (this.profileId) {
			this.profileService.getProfile(this.profileId).subscribe( reply => {
				this.profile = reply;
			});
		}
	}

	sortFunc(a: any, b: any): any {
		return b.animalDate - a.animalDate
	}



	getJwtProfileId() : any {
		if(this.authService.decodeJwt()) {
			return this.authService.decodeJwt().auth.profileId;
		} else {
			return false;
		}
	}

	getAnimalbyProfileId(){
		this.animalService.getAnimalbyProfileId(this.profileId).subscribe(reply => this.animals = reply);
	}
}

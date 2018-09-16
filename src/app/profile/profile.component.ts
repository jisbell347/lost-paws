import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from '@angular/forms';
import { Profile } from "../shared/interfaces/profile";
import { ProfileService } from "../shared/services/profile.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";
import {ActivatedRoute} from "@angular/router";
import {Animal} from "../shared/interfaces/animal";
import {AnimalService} from "../shared/services/animal.service";

@Component({
	selector: "profile",
	template: require("./profile.template.html")
})
export class ProfileComponent implements OnInit {
	@ViewChild('f1') userForm: NgForm;
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

	onSubmit() : boolean {
		this.submitted = true;
		this.profile.profileId = this.profileId;
		this.profile.profileName = this.userForm.value.first + " " + this.userForm.value.last;
		this.profile.profileEmail = this.userForm.value.email;
		this.profile.profilePhone = this.userForm.value.phone;

/*		console.log(this.profile.profileId);
		console.log(this.profile.profileName);
		console.log(this.profile.profileEmail);
		console.log(this.profile.profilePhone);*/

		this.profileService.editProfile(this.profile).subscribe(reply => {
			this.status = reply;
		if (this.status.status === 200 ) {
			this.userForm.reset();
			return true;
		}});
		return false;
	}

	getAnimalbyProfileId(){
		this.animalService.getAnimalbyProfileId(this.profileId).subscribe(reply => this.animals = reply);
	}
}

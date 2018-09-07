import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from '@angular/forms';
import { Profile } from "../shared/interfaces/profile";
import { ProfileService } from "../shared/services/profile.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";

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

	// need to grab profileId from the current Session
	// the remaining fields - from the form
	constructor(protected authService: AuthService, protected profileService: ProfileService) {
	}

	ngOnInit() : void {
		//grab the current logged in profileId off JWT
		this.profileId = this.getJwtProfileId();
		this.loadProfile();
	}

	loadProfile() {
		if (this.profileId) {
			this.profileService.getProfile(this.profileId).subscribe( reply => {
				this.profile = reply;
			});
		}
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

		this.profileService.editProfile(this.profile).subscribe(reply => {
			this.status = reply;
		if (this.status.status === 200 ) {
			this.userForm.reset();
			return true;
		}});
		return false;
	}
}

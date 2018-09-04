import {Component, OnInit, ViewChild} from "@angular/core";
import { NgForm } from '@angular/forms';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "../shared/services/profile.service";
import {Profile} from "../shared/interfaces/profile";
import {AuthService} from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";

@Component({
	selector: "profile",
	template: require("./profile.template.html")
})
export class ProfileComponent implements OnInit {
	@ViewChild('f1') userForm: NgForm;
	profile: Profile;
	profileId: string;
	firstName: string = 'Your first name';
	lastName: string = 'Your last name';
	userName: string[] = [];
	submitted = false;
	status : Status = null;

	// need to grab profileId from the current Session
	// the remaining fields - from the form
	constructor(protected authService: AuthService, protected profileService: ProfileService, protected route: ActivatedRoute) {
	}

	ngOnInit() : void {

		//grab the current logged in profileId off JWT
		this.profileId = this.authService.decodeJwt().auth.profileId;
		this.loadProfile();

		this.userName = this.profile.profileName.split(' ');
		this.firstName = this.userName[0];
		this.lastName = this.userName[this.userName.length - 1];
	}

	loadProfile() {
		this.profileService.getProfile(this.profileId).subscribe( reply => {
			this.profile = reply;
		})
	}

	onSubmit() : void {
		this.submitted = true;
		this.profile.profileName = this.userForm.value.firstname + " " + this.userForm.value.lastname;
		this.profile.profileEmail = this.userForm.value.email;
		this.profile.profilePhone = this.userForm.value.phone;

		this.profileService.editProfile(this.profile).subscribe(status => {this.status = status;
		if (this.status.status === 200 ) {
			this.userForm.reset();
		}});
	}
}
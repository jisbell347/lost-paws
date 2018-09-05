import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from '@angular/forms';
import { Profile } from "../shared/interfaces/profile";
import { ProfileService } from "../shared/services/profile.service";
import {Status} from "../shared/interfaces/status";

@Component({
	selector: "profile",
	template: require("./profile.template.html")
})
export class ProfileComponent implements OnInit {
	@ViewChild('f1') userForm: NgForm;
	profile: Profile = null;
	submitted = false;
	status : Status = null;

	// need to grab profileId from the current Session
	// the remaining fields - from the form
	constructor(protected profileService: ProfileService) {
	}

	ngOnInit() : void {
	}

	onSubmit() : void {
		this.submitted = true;
		this.profile.profileName = this.userForm.value.first + " " + this.userForm.value.last;
		this.profile.profileEmail = this.userForm.value.email;
		this.profile.profilePhone = this.userForm.value.phone;

		this.profileService.editProfile(this.profile).subscribe(status => {this.status = status;
		if (this.status.status === 200 ) {
			this.userForm.reset();
		}});
	}
}
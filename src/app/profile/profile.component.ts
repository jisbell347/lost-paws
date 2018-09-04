import {Component, OnInit, ViewChild} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { NgForm } from '@angular/forms';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "../shared/services/profile.service";
import {Profile} from "../shared/interfaces/profile";

@Component({
	selector: "profile",
	template: require("./profile.component.html")
})
export class ProfileComponent implements OnInit {
	@ViewChild('f') userForm: NgForm;
	profile: Profile;
	profileId: string;
	fname: string = 'Your first name';
	lname: string = 'Your last name';
	userName: string[] = [];
	submitted = false;

	// need to grab profileId from the current Session
	// the remaining fields - from the form
	constructor(protected profileService: ProfileService, protected route: ActivatedRoute) {
		this.profileId = route.snapshot.data["profileId"];
	}

	ngOnInit() : void {
		this.loadProfile();
		this.userName = this.profile.profileName.split(' ');
		this.fname = this.userName[0];
		this.lname = this.userName[this.userName.length - 1];
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
		this.userForm.reset();
	}
}
import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {ProfileService} from "../shared/services/profile.service";
import {Profile} from "../shared/interfaces/profile";


@Component({
	selector: "profile",
	template: require("./profile.component.html")
})
export class ProfileComponent implements OnInit {
	profile: Profile;
	profileForm: FormGroup;


	constructor(protected formBuilder: FormBuilder, protected profileService: ProfileService, private router: Router) {}

	// need to grab profileId from the current Session
	// the remaining fields - from the form

	ngOnInit() : void {
		this.profileForm = this.formBuilder.group({
			first-name: ["", [Validators.maxLength(46), Validators.required]],

		});

	}



}
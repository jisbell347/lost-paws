import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {ProfileService} from "./app/shared/servides/profile.service";
import {Profile} from "../classes/Profile";


@Component({
	selector: "profile",
	template: require("./profile.component.html")
})
export class ProfileComponent{
	profile: Profile = {};

	constructor(protected formBuilder: FormBuilder, protected profileService: ProfileService, private router: Router) {}
	}
}
import {Component, ViewChild, OnInit} from "@angular/core";
import {ActivatedRoute, Router} from "@angular/router";
import {ProfileService} from "../shared/services/profile.service";
import  {Profile} from "../shared/interfaces/profile";
import {Observable} from "rxjs";


@Component({
	template: require("./contact.template.html"),
	selector: "contact"
})

export class ContactComponent  implements OnInit{
	profile: Profile;

	constructor(protected profileService: ProfileService, protected router: ActivatedRoute) {

	}

	profileId = this.router.snapshot.params["profileId"];
	ngOnInit() {
		this.loadProfile();
	}

	loadProfile() {
		this.profileService.getProfile(this.profileId).subscribe( reply => {
			this.profile = reply;
		})
	}
}


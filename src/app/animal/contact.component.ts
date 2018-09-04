import {Component, Input, OnInit} from "@angular/core";
import {Observable} from "rxjs";

//Interfaces
import  {Profile} from "../shared/interfaces/profile";

//Services
import {ProfileService} from "../shared/services/profile.service";

@Component({
	selector: "contact",
	template: require("./contact.template.html")
	})

export class ContactComponent  implements OnInit{
	@Input() animalProfileId: string;
	profile: Profile = null;


	constructor(
		protected profileService: ProfileService,
	) {
	};

	ngOnInit() {
		this.profileService.getProfile(this.animalProfileId) . subscribe(profile => this.profile = profile);
	};
}


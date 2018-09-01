import {Component, OnInit} from "@angular/core";

//Interfaces
import  {Profile} from "../shared/interfaces/profile";
import {Animal} from "../shared/interfaces/animal";

//Services
import {ProfileService} from "../shared/services/profile.service";
import {AnimalService} from "../shared/services/animal.service";

//Router
import {ActivatedRoute} from "@angular/router";



@Component({
	template: require("./contact.template.html"),
	selector: "contact"
})

export class ContactComponent  implements OnInit{
	animal: Animal;
	animalId = this.route.snapshot.params["animalId"];
	animalProfileId = this.route.snapshot["animalProfileId"];
	profile: Profile;
	profileId = this.route.snapshot.params["profileId"];

	constructor(
		protected animalService: AnimalService,
		protected profileService: ProfileService,
		protected route: ActivatedRoute
	) {

	}


	ngOnInit() {
		this.loadProfile();
		this.animalService.getAnimal(this.animalId).subscribe(reply => this.animal = reply);
	}

	loadProfile() {
		this.profileService.getProfile(this.profileId).subscribe( reply => {
			this.profile = reply;
		})
	}
}


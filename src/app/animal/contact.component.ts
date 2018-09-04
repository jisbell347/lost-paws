import {Component, Input, OnInit} from "@angular/core";
import {Observable} from "rxjs";

//Interfaces
import  {Profile} from "../shared/interfaces/profile";
import {Animal} from "../shared/interfaces/animal";

//Services
import {ProfileService} from "../shared/services/profile.service";
import {AnimalService} from "../shared/services/animal.service";

//Router
import {ActivatedRoute, Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";


@Component({
	selector: "contact",
	template: require("./contact.template.html")
	})

export class ContactComponent  implements OnInit{
	@Input() animalProfileId: string;
	profile: Profile = null;
	// animalId = this.route.snapshot.params["animalId"];
	// animalProfileId = this.route.snapshot["animalProfileId"];
	// profile: Profile;
	// profileId = this.route.snapshot.params["profileId"];
	// status: Status = {status: null, message: null, type: null};

	constructor(
		// protected animalService: AnimalService,
		protected profileService: ProfileService,
		// protected router: ActivatedRoute
	) {

	}

	// animalId= this.router.snapshot.params["animalId"];

	ngOnInit() {
		this.profileService.getProfile(this.animalProfileId) . subscribe(profile => this.profile = profile);

		// this.loadAnimal();


		// this.loadProfile();
		// this.animalService.getAnimal(this.animalId).	subscribe(profileId => this.animal = profileId);
	}

	// loadAnimal() {
	// 	this.animalService.getAnimal(this.animalId).subscribe(reply => {
	// 		this.animal = reply;
	// 	});
	// }
	// loadProfile() {
	// 	this.profileService.getProfile(this.profileId).subscribe( profile => {
	// 		this.profile = profile;
	// 	})
	//
	//
	// }
}


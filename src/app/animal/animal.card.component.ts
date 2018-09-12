import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Router} from "@angular/router";
import {AnimalService} from "../shared/services/animal.service";
import {Animal} from "../shared/interfaces/animal";
import {Profile} from "../shared/interfaces/profile";
import {ProfileService} from "../shared/services/profile.service";
import {AuthService} from "../shared/services/auth.service";
import {Observable} from "rxjs";

@Component({
	selector: "animal-card",
	template: require("./animal.card.template.html")

})

export class AnimalCardComponent implements OnInit{
	animal: Animal = null;
	profile: Profile = {profileId: null, profileEmail: null, profileName: null, profilePhone: null};
	profileId: string;


	constructor(
		protected animalService: AnimalService,
		protected route: ActivatedRoute,
		protected profileService: ProfileService,
		protected authService: AuthService,
		protected router: Router
	) {

	}
	animalId = this.route.snapshot.params["animalId"];
	ngOnInit() {
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
		this.loadAnimal();
		this.profileId = this.getJwtProfileId();
	}

	loadAnimal() {
		this.animalService.getAnimal(this.animalId).subscribe(reply => {
			this.animal = reply;
		});
	}
	updateAnimal(animal: Animal) : void {
		this.router.navigate(["/animal-post/", this.animalId]);
	}

	getJwtProfileId() : any {
		if(this.authService.loggedIn()) {
			return this.authService.decodeJwt().auth.profileId;
		} else {
			return false;
		}
	}

}


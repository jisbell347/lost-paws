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
	animal: Animal = {animalId: null, animalProfileId: null, animalColor: null, animalDate: null, animalDescription: null, animalGender: null, animalImageUrl: null, animalLocation: null, animalName: null, animalSpecies: null, animalStatus: null};
	profile: Profile;
	profileId: string = null;


	constructor(protected animalService: AnimalService, protected router: ActivatedRoute, protected profileService: ProfileService, protected authService: AuthService, protected route: Router) {

	}
	animalId = this.router.snapshot.params["animalId"];
	ngOnInit() {
		window.sessionStorage.setItem('url', window.location.pathname);
		this.loadAnimal();
		this.getJwtProfileId();
	}

	loadAnimal() {
		this.animalService.getAnimal(this.animalId).subscribe(reply => {
			this.animal = reply;
		});
	}
	updateAnimal(animal: Animal) : void {
		this.route.navigate(["/animal-post/", this.animalId]);
	}

	getJwtProfileId() : any {
		if(this.authService.decodeJwt()) {
			console.log(this.authService.decodeJwt().auth.profileId);
		} else {
			return false;
		}
	}

}


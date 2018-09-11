import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Router} from "@angular/router";
import {AnimalService} from "../shared/services/animal.service";
import {Animal} from "../shared/interfaces/animal";
import {Observable} from "rxjs";

@Component({
	selector: "animal-card",
	template: require("./animal.card.template.html")

})

export class AnimalCardComponent implements OnInit{
	animal: Animal;


	constructor(protected animalService: AnimalService, protected router: ActivatedRoute) {

	}
	animalId = this.router.snapshot.params["animalId"];
	ngOnInit() {
		window.sessionStorage.setItem('url',window.location.href);
		this.loadAnimal();
	}

	loadAnimal() {
		this.animalService.getAnimal(this.animalId).subscribe(reply => {
			this.animal = reply;
		});
	}

}
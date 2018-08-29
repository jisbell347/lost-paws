import {Component, OnInit} from "@angular/core";

import {AnimalService} from "../shared/services/animal.service";
import {Status} from "../shared/interfaces/status";
import {Animal} from "../shared/interfaces/animal";
import {FormBuilder, FormGroup} from "@angular/forms";

@Component({
	template: require("./animal.search.template.html")
})

export class AnimalSearchComponent implements OnInit{
	animals: Animal[] = [];
	searchForm : FormGroup;
	status: Status = {status: null, message: null, type: null};

	constructor( protected animalService : AnimalService, protected formBuilder: FormBuilder) {

	}

	ngOnInit() {
		this.loadSearchResults();
		this.searchForm = this.formBuilder.group({
			animalDescription: ["", [Validators.maxLength(250), Validators.required]],
			animalGender: ["", [Validators.maxLength(7), Validators.required]],
			animalSpecies: ["", [Validators.maxLength(3), Validators.required]],
			animalsStatus: ["", [Validators.maxLength(8), Validators.required]]
		})
	}

	loadSearchResults() {
		this.animalService.getAllCurrentAnimals().subscribe(animals => this.animals = animals)
	}
}

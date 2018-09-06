import {Component, Input, OnInit} from "@angular/core";

import {AnimalService} from "../shared/services/animal.service";
import {Status} from "../shared/interfaces/status";
import {Animal} from "../shared/interfaces/animal";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ActivatedRoute, Route} from "@angular/router";


@Component({
	template: require("./animal.search.template.html"),
	selector: "search"
})

export class AnimalSearchComponent implements OnInit{
	animals: Animal[] = [];
	searchForm : FormGroup;

	status: Status = {status: null, message: null, type: null};
	searchParameters : any[] = [
		{"parameter" : "color"},
		{"parameter" : "gender"},
		{"parameter" : "species"},
		{"parameter" : "status"},
	];


	constructor(protected animalService : AnimalService, protected formBuilder: FormBuilder, protected route : ActivatedRoute) {

	}

	ngOnInit() {
		this.searchForm = this.formBuilder.group({
			searchContent: ["", [Validators.maxLength(64), Validators.required]],
			searchParameter: ["", [Validators.required]]
		});
		// this.getAllAnimals();

	}

	loadSearchResults() {
		// make an api to the animal API using any parameter


		//this.animalService.getAnimalByAnimalGender(this.searchForm.value.searchContent).subscribe(animals =>this.animals = animals);
		// this.animalService.getAnimalByAnimalSpecies(this.searchForm.value.searchContent).subscribe(animals => this.animals = animals);
		//

		// console.log the result

	console.log(this.animals);

		// display the results on the dom

	}


	loadColor(animalColor: string){
		this.animalService.getAnimalByAnimalColor(animalColor).subscribe(animals => this.animals = animals);
	}

	loadGender(animalGender: string){
		this.animalService.getAnimalByAnimalGender(animalGender).subscribe(animals => this.animals = animals);
	}

	loadSpecies(animalSpecies: string){
		if(animalSpecies === "8472") {
			alert("THE WEAK SHALL PERISH");
		}
		this.animalService.getAnimalByAnimalSpecies(animalSpecies).subscribe(animals => this.animals = animals);
	}

	loadStatus(animalStatus: string){
		this.animalService.getAnimalByAnimalStatus(animalStatus).subscribe(animals => this.animals = animals);
	}

	getAllAnimals() {
		this.animalService.getAllCurrentAnimals().subscribe(animals => this.animals = animals);
	}


}

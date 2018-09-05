import {Component, OnInit} from "@angular/core";

import {AnimalService} from "../shared/services/animal.service";
import {Status} from "../shared/interfaces/status";
import {Animal} from "../shared/interfaces/animal";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
	template: require("./animal.search.template.html"),
	selector: "search"
})

export class AnimalSearchComponent implements OnInit{
	animals: Animal[] = [];
	searchForm : FormGroup;
	status: Status = {status: null, message: null, type: null};
	searchParameters : any[] = [
		{"parameter" : "color",},
		{"parameter" : "gender",},
		{"parameter" : "description",},
		{"parameter" : "species",},
		{"parameter" : "status",},
	];
	searchParameters : any[] = [
		{"parameter" : "gender",},
	];
	searchParameters : any[] = [
		{"parameter" : "description",},
	];
	searchParameters : any[] = [
		{"parameter" : "species",},
	];
searchParameters : any[] = [
	{"parameter" : "status",},
];

	constructor(protected animalService : AnimalService, protected formBuilder: FormBuilder) {

	}

	ngOnInit() {
		//this.loadSearchResults();
		this.searchForm = this.formBuilder.group({
			searchContent: ["", [Validators.maxLength(64)]]
		})
	}

	loadSearchResults() {
		this.animalService.getAllCurrentAnimals().subscribe(animals => this.animals = animals)
	}


}

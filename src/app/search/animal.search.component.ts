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
	searchParametersColor : any[] = [
		{"parameter" : "color"},
		// {"parameter" : "gender"},
		// {"parameter" : "description"},
		// {"parameter" : "species"},
		// {"parameter" : "status"},
	];
	searchParametersGender : any[] = [
		{"parameter" : "gender",},
	];
	searchParametersDescription : any[] = [
		{"parameter" : "description",},
	];
	searchParametersSpecies : any[] = [
		{"parameter" : "species",},
	];
searchParametersStatus : any[] = [
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

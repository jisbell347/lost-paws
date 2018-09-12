import {Component, Input, OnInit} from "@angular/core";

import {AnimalService} from "../shared/services/animal.service";
import {Status} from "../shared/interfaces/status";
import {Animal} from "../shared/interfaces/animal";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ActivatedRoute, Route, Router} from "@angular/router";


@Component({
	template: require("./animal.search.template.html"),
	selector: "search"
})

export class AnimalSearchComponent implements OnInit{
	filterByValue: any;
	animals: Animal[] = [];
	searchForm : FormGroup;
	animalParameter: string;
	animalValue: string;

	status: Status = {status: null, message: null, type: null};
	searchParameters : any[] = [
		{"parameter" : "color"},
		{"parameter" : "gender"},
		{"parameter" : "species"},
		{"parameter" : "status"},
	];


	constructor(
		protected animalService : AnimalService,
		protected formBuilder: FormBuilder,
		protected route : ActivatedRoute,
		protected router: Router) {
		router.onSameUrlNavigation = "reload";
	}

	ngOnInit() {
		//set session storage for sign in purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
		this.searchForm = this.formBuilder.group({
			searchContent: ["", [Validators.maxLength(64), Validators.required]],
			searchParameter: ["", [Validators.required]]
		});
		this.loadSearchResults();
	}

	getSearchResults() {
		let searchParameter = this.searchForm.value.searchParameter;
		let searchContent = this.searchForm.value.searchContent;
		this.router.navigate(["/search", "animal" + searchParameter.charAt(0).toUpperCase() + searchParameter.substring(1), searchContent])
			.then(() => this.loadSearchResults());
		// this.router.navigate(["search", this.searchForm.value.searchParameter, this.searchForm.value.searchContent])
	}

	loadSearchResults() {
		this.animalParameter = this.route.snapshot.params["animalParameter"];
		this.animalValue = this.route.snapshot.params["animalValue"];
		if(this.animalParameter === "animalStatus"){
			this.loadStatus(this.animalValue);
		} else if(this.animalParameter === "animalColor"){
			this.loadColor(this.animalValue);
		} else if(this.animalParameter === "animalGender"){
			this.loadGender(this.animalValue);
		} else if(this.animalParameter === "animalSpecies"){
			this.loadSpecies(this.animalValue);
		}
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

	sortFunc(a: any, b: any): any {
		return b.animalDate - a.animalDate
	}


}

import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from '@angular/forms';
import { Animal } from "../shared/interfaces/animal";
import { AnimalService } from "../shared/services/animal.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";

@Component({
	selector: "animal-post",
	template: require("./animal-post.template.html")
})
export class AnimalPostComponent implements OnInit {
	@ViewChild('f2') animalForm: NgForm;



	constructor() {
	}

	ngOnInit() : void {}

	loadAnimal() {}

	onSubmit() : void {}
}
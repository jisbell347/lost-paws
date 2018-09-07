import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl,  ReactiveFormsModule, Validators } from '@angular/forms';
import { Animal } from "../shared/interfaces/animal";
import { AnimalService } from "../shared/services/animal.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";

@Component({
	selector: "animal-post",
	template: require("./animal-post.template.html")
})
export class AnimalPostComponent implements OnInit {
	animalForm: FormGroup;
	submitted : boolean = false;
	status : Status = null;

	constructor(protected authService: AuthService,
					protected animalService: AnimalService,
					protected fb: FormBuilder) {
	}

	ngOnInit() : void {
		this.animalForm = this.fb.group({
			status: ["", [Validators.required]],
			species: ["", [Validators.required]],
			gender: ["", [Validators.required]],
			name: ["", [Validators.maxLength(100)]],
			color: ["", [Validators.required]],
			location: ["", [Validators.maxLength(200)]],
			description: ["", [Validators.maxLength(500), Validators.required]],
		});
	}

	createAnimal() : void {
		this.submitted = true;

		const animal: Animal = {
			animalId: null,
			animalProfileId: null,
			animalColor: this.animalForm.value.color,
			animalDate: null,
			animalDescription: this.animalForm.value.description,
			animalGender: this.animalForm.value.gender,
			animalImageUrl: 'https://images.pexels.com/photos/551628/pexels-photo-551628.jpeg',
			animalLocation: this.animalForm.value.location,
			animalName: this.animalForm.value.name,
			animalSpecies: this.animalForm.value.species,
			animalStatus: this.animalForm.value.status
		};

/*		console.log(this.animalForm.value.color || "color is undefined");
		console.log(this.animalForm.value.description || "description is undefined");
		console.log(this.animalForm.value.gender || "gender is undefined");
		console.log(this.animalForm.value.location || "location is undefined");
		console.log(this.animalForm.value.name || "name is undefined");
		console.log(this.animalForm.value.species || "species is undefined");
		console.log(this.animalForm.value.status || "status is undefined");
		'https://images.pexels.com/photos/159541/wildlife-photography-pet-photography-dog-animal-159541.jpeg'
		'https://images.pexels.com/photos/551628/pexels-photo-551628.jpeg'
		'https://images.pexels.com/photos/57416/cat-sweet-kitty-animals-57416.jpeg'
		'https://images.pexels.com/photos/126407/pexels-photo-126407.jpeg'

		*/

		if (animal) {
			this.animalService.createAnimal(animal).subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.animalForm.reset();
				}
			});
		}
	}
}

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
	profileId: string;
	animal: Animal;
	submitted : boolean = false;
	status : Status = null;

	constructor(protected authService: AuthService,
					protected animalService: AnimalService,
					private fb: FormBuilder) {
	}

	ngOnInit() : void {
		//grab the current logged in profileId off JWT
		this.profileId = this.getJwtProfileId();

		console.log(this.profileId || "no profile ID was retrieved");

		this.animalForm = this.fb.group({
			status: ["", [Validators.required]],
			species: ["", [Validators.required]],
			gender: ["", [Validators.required]],
			name: ["", [Validators.maxLength(100)]],
			color: ["", [Validators.required]],
			location: ["", [Validators.maxLength(200)]],
			description: ["", [Validators.maxLength(500), Validators.required]],
		});

		console.log(this.animalForm["status"]);

	}

	getJwtProfileId() : any {
		if(this.authService.decodeJwt()) {
			return this.authService.decodeJwt().auth.profileId;
		} else {
			return false
		}
	}

	createAnAnimal(id: string) : void {
		this.animal.animalId = null;
		this.animal.animalProfileId = id;
		this.animal.animalColor = this.animalForm.value.color;
		this.animal.animalDate = null;
		this.animal.animalDescription = this.animalForm.value.description;
		this.animal.animalGender = this.animalForm.value.gender;
		this.animal.animalImageUrl = null;
		this.animal.animalLocation = this.animalForm.value.location;
		this.animal.animalName = this.animalForm.value.name;
		this.animal.animalSpecies = this.animalForm.value.species;
		this.animal.animalStatus = this.animalForm.value.status;
	}

	onSubmit() : void {
		this.submitted = true;
		if (this.profileId) {
			this.createAnAnimal(this.profileId);
		}

		this.animalService.createAnimal(this.animal).subscribe(status => {this.status = status;
			if (this.status.status === 200 ) {
				this.animalForm.reset();
			}});
	}
}

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
	submitted = false;
	status : Status = null;

	constructor(protected authService: AuthService, protected animalService: AnimalService, private fb: FormBuilder) {
	}

	ngOnInit() : void {
		//grab the current logged in profileId off JWT
		this.profileId = this.getJwtProfileId();
		this.animalForm = this.fb.group({
			astatus: ["", [Validators.required]],
			aspecies: ["", [Validators.required]],
			agender: ["", [Validators.required]],
			aname: ["", [Validators.maxLength(100)]],
			acolor: ["", [Validators.maxLength(25)]],
			alocation: ["", [Validators.maxLength(200)]],
			adescription: ["", [Validators.maxLength(500), Validators.required]],
		});
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
		this.animal.animalColor = this.animalForm.value.acolor;
		this.animal.animalDate = null;
		this.animal.animalDescription = this.animalForm.value.adesription;
		this.animal.animalGender = this.animalForm.value.agender;
		this.animal.animalImageUrl = null;
		this.animal.animalLocation = this.animalForm.value.alocation;
		this.animal.animalName = this.animalForm.value.aname;
		this.animal.animalSpecies = this.animalForm.value.aspecies;
		this.animal.animalStatus = this.animalForm.value.astatus;
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

import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Animal} from "../shared/interfaces/animal";
import {AnimalService} from "../shared/services/animal.service";
import {Status} from "../shared/interfaces/status";
import 'rxjs/add/observable/from';
import {ActivatedRoute, Router} from "@angular/router";

@Component({
	template: require("./animal-edit.template.html"),
})

export class AnimalEditComponent implements OnInit{
	animalForm: FormGroup;
	submitted: boolean = false;
	status: Status = null;
	animal: Animal = {animalId: null, animalProfileId: null, animalColor: null, animalDate: null, animalDescription: null, animalGender: null, animalImageUrl: null, animalLocation: null, animalName: null, animalSpecies: null, animalStatus: null};
	animalId = this.route.snapshot.params["animalId"];
	success: boolean = false;

	constructor(protected animalService: AnimalService,
					protected fb: FormBuilder,
					protected route: ActivatedRoute,
					protected router: Router) {
		this.animalForm = this.fb.group({
			animalStatus: ["", [Validators.required]],
			animalSpecies: ["", [Validators.required]],
			animalGender: ["", [Validators.required]],
			animalName: ["", [Validators.maxLength(100)]],
			animalColor: ["", [Validators.required]],
			animalLocation: ["", [Validators.maxLength(200)]],
			animalDescription: ["", [Validators.maxLength(500), Validators.required]]
		});
	}

	ngOnInit(){
		this.applyFormChanges();
		this.loadAnimalValues();
	}

	applyFormChanges(): void {
		this.animalForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.animal[field] = values[field];
			}
		});
	}

	loadAnimalValues() {
		this.animalService.getAnimal(this.animalId).subscribe(animal => {
			this.animal = animal;
			this.animalForm.patchValue(animal);
		});
	}

	editAnimal() {
		this.animalService.editAnimal(this.animal).subscribe(status => {
			this.status = status;
		});
		this.animalForm.reset();
		if(this.status.status === 200) {
			this.success = true;
			// this.router.navigate(["animal/:animalId"]);
		}
	}
}


import { Component, OnInit, ViewChild, ElementRef } from "@angular/core";
import {FormBuilder, FormGroup, FormControl, ReactiveFormsModule, Validators, NgForm} from '@angular/forms';
import { Animal } from "../shared/interfaces/animal";
import { AnimalService } from "../shared/services/animal.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";
import { CloudinaryModule } from '@cloudinary/angular-5.x';
import * as  Cloudinary from 'cloudinary-core';
import { FileUploader } from 'ng2-file-upload';
import { Cookie } from 'ng2-cookies';
import { Observable } from 'rxjs';
import 'rxjs/add/observable/from';
import {ActivatedRoute, Router} from "@angular/router";


@Component({
	selector: "animal-post",
	template: require("./animal-post.template.html"),
	/*directives: [FILE_UPLOAD_DIRECTIVES, NgClass, NgStyle, CORE_DIRECTIVES, FORM_DIRECTIVES]*/
})
export class AnimalPostComponent implements OnInit {
	animalForm: FormGroup;
	submitted : boolean = false;
	status : Status = null;
	animal: Animal = {
		animalId: null,
		animalProfileId: null,
		animalColor: null,
		animalDate: null,
		animalDescription: null,
		animalGender: null,
		animalImageUrl: null,
		animalLocation: null,
		animalName: null,
		animalSpecies: null,
		animalStatus: null};
	animalId = this.route.snapshot.params["animalId"];
	success: boolean = false;
	deleted: boolean = false;


	public uploader: FileUploader = new FileUploader(
		{
			itemAlias: 'pet',
			url: './api/image/',
			headers: [{name: 'X-XSRF-TOKEN', value: Cookie.get('XSRF-TOKEN')}],
			additionalParameter: {}
		}
	)

	cloudinarySecureUrl: string = '';
	cloudinaryPublicObservable: Observable<string> = new Observable<string>();

		constructor(protected authService: AuthService,
					protected animalService: AnimalService,
					protected fb: FormBuilder,
					protected route: ActivatedRoute,
					protected router: Router) {
	}

	ngOnInit() : void {
		//set session storage for sign in purposes
		window.sessionStorage.setItem('url', window.location.pathname);
		this.loadAnimalValues();
		this.animalForm = this.fb.group({
			animalStatus: ["", [Validators.required]],
			animalSpecies: ["", [Validators.required]],
			animalGender: ["", [Validators.required]],
			animalName: ["", [Validators.maxLength(100)]],
			animalColor: ["", [Validators.required]],
			animalLocation: ["", [Validators.maxLength(200)]],
			animalDescription: ["", [Validators.maxLength(500), Validators.required]]
		});
		this.applyFormChanges();
	}

	uploadImage(): void {
		this.uploader.onSuccessItem = ( item: any, response: string, status: number, headers: any ) => {
			let reply = JSON.parse(response);
			this.cloudinarySecureUrl = reply.data;
			this.cloudinaryPublicObservable = Observable.from(this.cloudinarySecureUrl);
		};

		this.uploader.uploadAll();
	}

	getCloudinaryUrl(): void {
		this.cloudinaryPublicObservable.subscribe(cloudinarySecureUrl => this.cloudinarySecureUrl = cloudinarySecureUrl);
	}

	applyFormChanges() :void {
			this.animalForm.valueChanges.subscribe(values => {
				for(let field in values) {
					this.animal[field] = values[field];
				}
			});
	}

	loadAnimalValues(){
			this.animalService.getAnimal(this.animalId).subscribe(animal => {
				this.animal = animal;
				this.animalForm.patchValue(animal);
			});
	}

	createAnimal() : void {
		this.submitted = true;
		this.getCloudinaryUrl();

		const animal: Animal = {
			animalId: null,
			animalProfileId: null,
			animalColor: this.animalForm.value.color,
			animalDate: null,
			animalDescription: this.animalForm.value.description,
			animalGender: this.animalForm.value.gender,
			animalImageUrl: this.cloudinarySecureUrl,
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

	editAnimal(){
		this.animalService.editAnimal(this.animal).subscribe(status => {
			this.status = status;
		});
		this.animalForm.reset();
		if(this.status.status === 200) {
			this.success = true;
			// this.router.navigate(["animal/:animalId"]);
		}
	}

	deleteAnimal(){
		this.animalService.deleteAnimal(this.animal.animalId).subscribe(status => {
			this.status = status;
			if(this.status.status === 200){
				this.deleted = true;
				this.animal = {animalId: null, animalProfileId: null, animalColor: null, animalDate: null, animalDescription: null, animalGender: null, animalImageUrl: null, animalLocation: null, animalName: null, animalSpecies: null, animalStatus: null};
				this.router.navigate([""]);
			}
		})
	}
}

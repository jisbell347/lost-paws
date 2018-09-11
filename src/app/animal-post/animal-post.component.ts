import { Component, OnInit, ViewChild, ElementRef } from "@angular/core";
import {FormBuilder, FormGroup, FormControl, ReactiveFormsModule, Validators, NgForm} from '@angular/forms';
import { Animal } from "../shared/interfaces/animal";
import { AnimalService } from "../shared/services/animal.service";
import { AuthService } from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";
import { CloudinaryModule } from '@cloudinary/angular-5.x';
import * as  Cloudinary from 'cloudinary-core';
import {ActivatedRoute} from "@angular/router";

/*import {CORE_DIRECTIVES, FORM_DIRECTIVES, NgClass, NgStyle} from '@angular/common';
import {FILE_UPLOAD_DIRECTIVES, FileUploader} from "ng2-file-upload";*/


@Component({
	selector: "animal-post",
	template: require("./animal-post.template.html"),
	/*directives: [FILE_UPLOAD_DIRECTIVES, NgClass, NgStyle, CORE_DIRECTIVES, FORM_DIRECTIVES]*/
})
export class AnimalPostComponent implements OnInit {
	animalForm: FormGroup;
	submitted : boolean = false;
	status : Status = null;
	animal: Animal = {animalId: null, animalProfileId: null, animalColor: null, animalDate: null, animalDescription: null, animalGender: null, animalImageUrl: null, animalLocation: null, animalName: null, animalSpecies: null, animalStatus: null};
	imageUrl : string = 'https://images.pexels.com/photos/551628/pexels-photo-551628.jpeg';
	baseURL: string = "https://api.cloudinary.com/v1_1/deep-dive";
	/*cloudinary: Cloudinary.Cloudinary;
	*/
	@ViewChild("photo") photo: ElementRef;
	animalId = this.route.snapshot.params["animalId"];


		constructor(protected authService: AuthService,
					protected animalService: AnimalService,
					protected fb: FormBuilder,
					protected route: ActivatedRoute) {
	}

	ngOnInit() : void {
		//set session storage for sign in purposes
		window.sessionStorage.setItem('url',window.location.href);
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


	upload() : void {
		this.photo.nativeElement.cloudinary.openUploadWidget({
			cloud_name: 'deep-dive', upload_preset: 'lostpaws'}, (error: any, result: any) => {
			if (result) {
				console.log(result[0]['secure_url']);
				this.imageUrl = result[0]['secure_url'].toString();
			}
		});
	}

	/*upload(): void {
		this.cloudinary.openUploadWidget({
			cloud_name: 'deep-dive', upload_preset: 'lostpaws'}, (error: any, result: any) => {
			if (result) {
				console.log(result[0]['secure_url']);
				this.imageUrl = result[0]['secure_url'].toString();
			}
		});*/

		/*cloudinary.openUploadWidget({cloud_name: 'deep-dive', upload_preset: 'lostpaws'},
			function(error: string, result: string) {
				/!*this.imageUrl = result[0].url.toString();*!/
				console.log(result[0]);
				console.log('secure URL: ' + result[0]['secure_url']);
				console.log('public ID: ' + result[0]['public_id']);*/
				/*

								this.imageUrl = result[0]['secure_url'];
								console.log("this.imageUrl: " + this.imageUrl);
				*/


				/*this.pubId = result[0]['public_id'];
				  console.log("public ID: " + this.pubId);*/

				/*this.imageUrl = result[0]['secure_url'];
				console.log("this.imageUrl: " + this.imageUrl);*/
	/*		});*/
/*	}*/

	createAnimal() : void {
		this.submitted = true;

		const animal: Animal = {
			animalId: null,
			animalProfileId: null,
			animalColor: this.animalForm.value.color,
			animalDate: null,
			animalDescription: this.animalForm.value.description,
			animalGender: this.animalForm.value.gender,
			animalImageUrl: this.imageUrl,
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

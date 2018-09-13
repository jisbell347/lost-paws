import {Component} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Animal} from "../shared/interfaces/animal";
import {AnimalService} from "../shared/services/animal.service";
import {AuthService} from "../shared/services/auth.service";
import {Status} from "../shared/interfaces/status";
import {FileUploader} from 'ng2-file-upload';
import {Cookie} from 'ng2-cookies';
import {Observable} from 'rxjs';
import 'rxjs/add/observable/from';
import {ActivatedRoute, Router} from "@angular/router";

@Component({
	selector: "animal-post",
	template: require("./animal-post.template.html"),
	/*directives: [FILE_UPLOAD_DIRECTIVES, NgClass, NgStyle, CORE_DIRECTIVES, FORM_DIRECTIVES]*/
})
export class AnimalPostComponent {
	animalForm: FormGroup;
	submitted: boolean = false;
	status: Status = null;
	animal: Animal;

	animalId = this.route.snapshot.params["animalId"];
	success: boolean = false;
	imageUploaded: boolean = false;


	public uploader: FileUploader = new FileUploader(
		{
			itemAlias: 'pet',
			url: './api/image/',
			headers: [
				{name: 'X-JWT-TOKEN', value: window.localStorage.getItem('jwt-token')},
				{name: 'X-XSRF-TOKEN', value: Cookie.get('XSRF-TOKEN')}
			],
			additionalParameter: {}
		}
	);

	cloudinarySecureUrl: string;
	cloudinaryPublicObservable: Observable<string> = new Observable<string>();

	constructor(protected authService: AuthService,
					protected animalService: AnimalService,
					protected fb: FormBuilder,
					protected route: ActivatedRoute,
					protected router: Router) {
		this.animalForm = this.fb.group({
			status: ["", [Validators.required]],
			species: ["", [Validators.required]],
			gender: ["", [Validators.required]],
			name: ["", [Validators.maxLength(100)]],
			color: ["", [Validators.required]],
			location: ["", [Validators.maxLength(200)]],
			description: ["", [Validators.maxLength(500), Validators.required]]
		});
	}

	uploadImage(): void {
		this.uploader.uploadAll();
		this.cloudinaryPublicObservable.subscribe(cloudinarySecureUrl => this.cloudinarySecureUrl = cloudinarySecureUrl);
		this.uploader.onSuccessItem = (item: any, response: string, status: number, headers: any) => {
			let reply = JSON.parse(response);
			this.cloudinarySecureUrl = reply.data;
			this.cloudinaryPublicObservable = Observable.from(this.cloudinarySecureUrl);
			if (this.cloudinarySecureUrl) {
				this.imageUploaded = true;
			}
		};
	}

	getAnimalFromInput(): void {
		if (this.cloudinarySecureUrl) {
			this.animal = {
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
		}
	}

	postAnimal(): void {
		if (this.cloudinarySecureUrl) {
			this.submitted = true;
			this.getAnimalFromInput();
			if(this.animal) {
				this.animalService.createAnimal(this.animal).subscribe(status => {
					this.status = status;
					if(this.status.status === 200) {
						this.animalForm.reset();
					}
				});
			}
		}
	}
}

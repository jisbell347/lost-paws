import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/internal/Observable";
import {Profile} from "../interfaces/profile";
import {Animal} from"../interfaces/animal";

@Injectable()
export class AnimalService {
	constructor(protected http : HttpClient ) {}

	//define the API endpoint
	private animalUrl = "api/animal/";

	// call to the animal API and delete the animal in question
	deleteAnimal(animalId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.animalUrl + animalId));
	}

	// call to the Animal API and edit the animal in question
	editAnimal(animal : Animal) : Observable<Status> {
		return(this.http.put<Status>(this.animalUrl + animal.animalId, animal));
	}

	// call to the Animal API and create an animal posting
	createAnimal(animal: Animal) : Observable<Status> {
		return(this.http.post<Status>(this.animalUrl, animal));
	}

	//call to the Animal API and get an animal by animalId
	getAnimal(animalId: string) : Observable<Animal> {
		return(this.http.get<Animal>(this.animalUrl + animalId));
	}

	//call to the Animal API and get an array of animals by  profileId
	getAnimalbyProfileId(animalProfileId: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalProfileId));
	}

	//call to the Animal API and get an array of animals by animalColor
	getAnimalByAnimalColor(animalColor: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalColor));
	}

	//call to the Animal API and get an array of animals by animalDescription
	getAnimalByAnimalDescription(animalDescription: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalDescription));
	}

	//call to the Animal API and get an array of animals by animalGender
	getAnimalByAnimalGender(animalGender: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalGender));
	}

	//call to the Animal API and get an array of animals by animalSpecies
	getAnimalByAnimalSpecies(animalSpecies: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalSpecies));
	}

	//call to the Animal API and get an array of animals by animalStatus
	getAnimalByAnimalStatus(animalStatus: string) : Observable<Animal[]> {
		return(this.http.get<Animal[]>(this.animalUrl + animalStatus));
	}




}
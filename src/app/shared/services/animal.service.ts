import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/internal/Observable";
import {User} from "../interfaces/user";

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



}
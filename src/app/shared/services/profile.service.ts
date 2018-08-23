import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/internal/Observable";
import {Status} from "../interfaces/status";
import {Profile} from"../interfaces/profile";

@Injectable()
export class ProfileService {
	constructor(protected http : HttpClient ) {}

	// define an API endpoint
	private profileUrl = "api/profile/";

	//reach out to the profile  API and delete the profile in question
	seletProfile(profileId: string) : Observable<Statustatus> {
		return(this.http.delete<Status>(this.profileUrl + profileId));
	}

	// call to the Profile API and edit the profile in question



	// call to the Profile API and get a Profile object by its id


	// call to the Profile API and get a Profile object by its email address



	// call to the Profile API and get a Profile object by its phone number




}
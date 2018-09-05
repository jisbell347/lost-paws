import {HttpClient, HttpParams} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/internal/Observable";
import {Status} from "../interfaces/status";
import {Profile} from"../interfaces/profile";
import {Animal} from "../interfaces/animal";

@Injectable()
export class ProfileService {
	constructor(protected http : HttpClient ) {}

	// define an API endpoint
	private profileUrl = "api/profile/";

	//reach out to the profile  API and delete the profile in question
	deleteProfile(id: string) : Observable<Status> {
		return(this.http.delete<Status>(this.profileUrl + id));
	}

	// call to the Profile API and edit the profile in question
	editProfile(profile: Profile) : Observable<Status> {
		return(this.http.put<Status>(this.profileUrl , profile));
	}

	// call to the Profile API and get a Profile object by its id
	getProfile(id: string) : Observable<Profile> {
		if (this.profileUrl[this.profileUrl.length - 1] !== "/") {
			id = "/" + id;
		}
		return(this.http.get<Profile>(this.profileUrl + id));
	}

	// call to the Profile API and get a Profile object by its email address
	getProfileByProfileEmail(profileEmail: string) : Observable<Profile> {
		return(this.http.get<Profile>(this.profileUrl, {params: new HttpParams().set("profileEmail", profileEmail)}));
	}

	// call to the Profile API and get a Profile object by its phone number
	getProfileByProfilePhone(profilePhone: string) : Observable<Profile> {
		return(this.http.get<Profile>(this.profileUrl,  {params: new HttpParams().set("profilePhone", profilePhone)}));
	}
}
import {HttpClient, HttpParams} from "@angular/common/http";
import {Injectable} from "@angular/core";
import  {Observable} from "rxjs";
import  {Status} from "../interfaces/status";

@Injectable()
export class GoogleExitService {
	constructor(protected http: HttpClient) {

	}

	private googleExit ="api/google-exit/";


	//perform the the post to initiate redirect

	getRedirect(code: string) : Observable<Status> {

		//make sure there are no stale JWT tokens in local storage
		localStorage.removeItem("jwt-token");

		return(this.http.get<Status>(this.googleExit, {params: new HttpParams().set("code", code)}));
	}
}

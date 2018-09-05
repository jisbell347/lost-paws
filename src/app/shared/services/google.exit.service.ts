import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import  {Observable} from "rxjs";
import  {Status} from "../interfaces/status";
import {Redirect} from "../interfaces/redirect";

@Injectable()
export class GoogleExitService {
	constructor(protected http: HttpClient) {

	}

	private googleSignIn ="api/google-exit/";


	//perform the the post to initiate redirect

	getRedirect(redirect: Redirect) : Observable<Status> {
		return(this.http.get<Status>(this.googleSignIn, redirect));
	}
}

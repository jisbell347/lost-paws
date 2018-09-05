import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import  {Observable} from "rxjs";
import  {Status} from "../interfaces/status";
import {Redirect} from "../interfaces/redirect";

@Injectable()
export class RedirectService {
	constructor(protected http: HttpClient) {

	}

	private googleSignIn ="api/google/";


	//perform the the post to initiate redirect

	postRedirect(redirect: Redirect) : Observable<Status> {
		return(this.http.post<Status>(this.googleSignIn, redirect));
	}
}

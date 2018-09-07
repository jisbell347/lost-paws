import {HttpClient, HttpParams} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Status} from "../interfaces/status";

@Injectable()
export class FacebookExitService {
	constructor(protected http: HttpClient){

	}

	private facebookExit = "api/facebook/";


	getRedirect(code: string) : Observable<Status>{
		localStorage.removeItem("jwt-token");

		return(this.http.get<Status>(this.facebookExit, {params: new HttpParams().set("code", code)}));
	}
}
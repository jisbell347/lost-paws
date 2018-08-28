import {Injectable} from "@angular/core";

import {Status} from "../interfaces/status";
import {Comment} from "../interfaces/comment";
import {Observable} from "rxjs/internal/Observable";
import {HttpClient, HttpParams} from "@angular/common/http";

@Injectable ()
export class CommentService {
	constructor(protected http : HttpClient) {}

	//define the API endpoint
	private commentUrl = "api/comment/";

	// call to the comment API and delete the comment in question
	deleteComment(commentId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.commentUrl + commentId));

	}

	// call to the comment API and edit the comment in question
	editComment(comment : Comment) : Observable<Status> {
		return(this.http.put<Status>(this.commentUrl + comment.commentId, comment));
	}

	// call to the comment API and create the comment in question
	createComment(comment : Comment) : Observable<Status> {
		return(this.http.post<Status>(this.commentUrl, comment));
	}

	// call to the comment API and get a comment object based on its Id
	getComment(commentId : string) : Observable<Comment> {
		return(this.http.get<Comment>(this.commentUrl + commentId));
	}

	// call to the comment API and get an array of tweets based off the animalId
	getCommentbyAnimalId(commentAnimalId : string) : Observable<Comment[]> {
		return(this.http.get<Comment[]>(this.commentUrl, {params: new HttpParams().set("commentAnimalId", commentAnimalId)}));
	}

	// call to the comment API and get an array of comments based off the profileId
	getCommentbyProfileId(commentProfileId : string) : Observable<Comment[]> {
		return(this.http.get<Comment[]>(this.commentUrl, {params: new HttpParams().set("commentProfileId", commentProfileId)}));
	}

	// call to the comment API and get an array of comments based off the commentText
	getCommentByText(commentText : string) : Observable<Comment[]> {
		return(this.http.get<Comment[]>(this.commentUrl, {params: new HttpParams().set("commentText", commentText)}));
	}
}
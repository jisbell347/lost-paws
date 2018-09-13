import {Component, EventEmitter, OnInit, Output} from "@angular/core";
import {GoogleExitService} from "../../services/google.exit.service";
import {ActivatedRoute, Router} from "@angular/router";

import {Status} from "../../interfaces/status";

@Component({
	template: require("./google.exit.template.html")
})

export class GoogleExitComponent implements OnInit{
	code: string = this.route.snapshot.queryParams["code"];
	status: Status = null;
	@Output() isAuthenticatedEvent: EventEmitter<boolean> = new EventEmitter<boolean>();

	constructor(protected googleExitService: GoogleExitService, protected route: ActivatedRoute, protected router: Router){
		router.onSameUrlNavigation = "reload";

	}

	ngOnInit(){
		// get return url from session storage

		let returnUrl = JSON.parse(window.sessionStorage.getItem('url'));

		this.googleExitService.getRedirect(this.code).subscribe(status => {
			this.status = status;

			if(this.status.status === 200) {
				this.isAuthenticatedEvent.emit(true);

				// navigate home if user is on the sign-out page
				if(window.sessionStorage.getItem("url").includes("signed-out")){
					this.router.navigate([""]);
				}
				//navigate back to the page the user was on
				else if (returnUrl !== null) {
					// returnUrl = ["/", ...returnUrl];
					let url = "";
					returnUrl.map((part: any) => url = url + "/" + part.path);
					this.router.navigateByUrl(url);
				//if there is no stored return url, navigate home.
				} else {
					this.router.navigate([""]);
				}
			}
		});
	}
}
import {Component, EventEmitter, OnInit, Output} from "@angular/core";
import {GoogleExitService} from "../../services/google.exit.service";
import {ActivatedRoute, Router} from "@angular/router";

import {Status} from "../../interfaces/status";
import {stringify} from "querystring";

@Component({
	template: require("./google.exit.template.html")
})

export class GoogleExitComponent implements OnInit{
	code: string = this.route.snapshot.queryParams["code"];
	status: Status = null;
	@Output() isAuthenticatedEvent: EventEmitter<boolean> = new EventEmitter<boolean>();

	constructor(protected googleExitService: GoogleExitService, protected route: ActivatedRoute, protected router: Router){


	}

	ngOnInit(){
		// get return url from session storage
		let returnUrl = window.sessionStorage.getItem('url');

		this.googleExitService.getRedirect(this.code).subscribe(status => {
			this.status = status;

			if(this.status.status === 200) {
				this.isAuthenticatedEvent.emit(true);

				// navigate to the stored return url
				if (window.sessionStorage.getItem("url").includes("animal")) {
					window.location.href=returnUrl;
				//if user is not on an animal card page, the user will be redirected home.
				} else {
					this.router.navigate([""]);
				}
			}
		});
	}
}
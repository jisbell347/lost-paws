import {Component, EventEmitter, OnInit, Output} from "@angular/core";
import {FacebookExitService} from "../../services/facebook.exit.service";
import {ActivatedRoute, Router} from "@angular/router";

import {Status} from "../../interfaces/status";

@Component({
	template: require("./google.exit.template.html")
})

export class FacebookExitComponent implements OnInit{
	code: string = this.route.snapshot.queryParams["code"];
	status: Status = null;
	@Output() isAuthenticatedEvent: EventEmitter<boolean> = new EventEmitter<boolean>();

	constructor(protected facebookExitService: FacebookExitService, protected route: ActivatedRoute, protected router: Router){

	}

	ngOnInit(){
		this.facebookExitService.getRedirect(this.code).subscribe(status => {
			this.status = status;

			if(this.status.status === 200){
				this.isAuthenticatedEvent.emit(true);
				this.router.navigate([""]);
			}
		});
	}
}
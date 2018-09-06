import {Component, OnInit} from "@angular/core";
import {Status} from "./shared/interfaces/status";
import {SessionService} from "./shared/services/session.service";

@Component({
	selector: "lost-paws",
	template: require("./app.component.html")
})
export class AppComponent implements OnInit{

	status: Status = null;

	constructor(protected sessionService: SessionService) {

	}

	ngOnInit(){
		this.sessionService.setSession()
			.subscribe(status => this.status = status);
	}

}
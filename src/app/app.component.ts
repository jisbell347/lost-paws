import {Component, OnInit} from "@angular/core";
import {Status} from "./shared/interfaces/status";
import {SessionService} from "./shared/services/session.service";

@Component({
	selector: "lost-paws",
	template: require("./app.component.html")
})
export class AppComponent implements OnInit{
	constructor(protected sessionService: SessionService) {

	}

	ngOnInit(){
		this.sessionService.setSession();
	}

}
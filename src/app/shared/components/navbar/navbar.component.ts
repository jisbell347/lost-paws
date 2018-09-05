import{Component, OnInit} from "@angular/core";
import {AuthService} from "../../services/auth.service";
import {SignOutService} from "../../services/sign.out.service";
import {Status} from "../../interfaces/status";
import {ActivatedRoute, Router} from "@angular/router";
import {CookieService} from "ng2-cookies";

@Component({
	template: require("./navbar.template.html"),
	selector: "navbar"
})

export class NavbarComponent implements OnInit{
	status: Status = {status: null, message: null, type: null};
	isAuthenticated: boolean;

	constructor(protected authService: AuthService, protected cookieService: CookieService, protected signOutService: SignOutService, protected router: Router) {

	}

	ngOnInit() {
		this.isAuthenticated = this.authService.isAuthenticated();
	}

	signOut() : void {
		this.signOutService.getSignOut()

			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {

					//delete cookies and jwt
					this.cookieService.deleteAll();
					localStorage.clear();

					//send user back home, refresh page
					this.router.navigate([""]);
					location.reload();
					console.log("goodbye");
				}
			});
	}


}


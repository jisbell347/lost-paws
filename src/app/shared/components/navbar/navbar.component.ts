import{Component} from "@angular/core";
import {SignOutService} from "../../services/sign.out.service";
import {Status} from "../../interfaces/status";
import {Router} from "@angular/router";
import {CookieService} from "ng2-cookies";
import {AuthService} from "../../services/auth.service";

@Component({
	template: require("./navbar.template.html"),
	selector: "navbar"
})

export class NavbarComponent{
	status: Status = {status: null, message: null, type: null};

	constructor(protected authService: AuthService, protected cookieService: CookieService, protected signOutService: SignOutService, protected router: Router) {

	}

	isAuthenticated(): boolean {
		return(this.authService.isAuthenticated());
	}

	signOut() : void {
		this.signOutService.getSignOut()

			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {

					//delete cookies and jwt
					this.cookieService.deleteAll();
					localStorage.clear();

					//send user to signed out component with alert, then refreshes app back to home.
					this.router.navigate(["signed-out"]);
					console.log("goodbye");
				}
			});
	}


}


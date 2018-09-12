import { Component, OnInit } from '@angular/core';
import { FileUploader } from 'ng2-file-upload';
import { Cookie } from 'ng2-cookies';
import { Observable } from 'rxjs';
import 'rxjs/add/observable/from';

@Component({
	selector: 'image-upload',
	templateUrl: './image-upload.template.html'
})
export class ImageUploadComponent implements OnInit {

	public uploader: FileUploader = new FileUploader(
		{
			itemAlias: 'pet',
			url: './api/image/',
			headers: [{name: 'X-XSRF-TOKEN', value: Cookie.get('XSRF-TOKEN')}],
			additionalParameter: {}
		}
	)

	cloudinarySecureUrl: string = '';
	cloudinaryPublicObservable: Observable<string> = new Observable<string>();

	ngOnInit(): void {
		this.uploader.onSuccessItem = ( item: any, response: string, status: number, headers: any ) => {
			let reply = JSON.parse(response);
			this.cloudinarySecureUrl = reply.data;
			this.cloudinaryPublicObservable = Observable.from(this.cloudinarySecureUrl);
		};
	}

	uploadImage(): void {
		this.uploader.uploadAll();
	}

	getCloudinaryUrl(): void {
		this.cloudinaryPublicObservable.subscribe(cloudinarySecureUrl => this.cloudinarySecureUrl = cloudinarySecureUrl);
	}
}
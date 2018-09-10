import { Component, OnInit, Input, NgZone } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { FileUploader, FileUploaderOptions, ParsedResponseHeaders } from 'ng2-file-upload';
import { Cloudinary } from '@cloudinary/angular-5.x';

@Component({
	selector: 'image-uploader',
	templateUrl: 'image-upload.template.html'
})
export class ImageUploadComponent implements OnInit {
	@Input()
	responses: Array<any>;

	private hasBaseDropZoneOver: boolean = false;
	private uploader: FileUploader;
	private title: string;

	constructor (
			private cloudinary: Cloudinary,
			private zone: NgZone,
			private http: HttpClient
	) {
		this.responses = [];
		this.title = '';
	}

	ngOnInit(): void {
		// create the file uploader and hook it up to our account
		const uploaderOptions: FileUploaderOptions = {
			url: `https://api.cloudinary.com/v1_1/deep-dive${this.cloudinary.config().cloud_name}/upload`,
			// upload file automatically upon addition to upload queue
			autoUpload: true,
			// use xhrTransport in favor of iframeTransport
			isHTML5: true,
			// calculate progress independently for each uploaded file
			removeAfterUpload: true,
			// XHR request headers
			headers: [
				{
					name: 'X-Requested-With',
					value: 'XMLHttpRequest'
				}
			]
		};
		this.uploader = new FileUploader(uploaderOptions);




	}


}
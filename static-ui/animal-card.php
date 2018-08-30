<?php require_once("head-utils.php"); ?>

<?php require_once("navbar.php"); ?>


	<main class="sfooter, pt-5">
		<div class="sfooter-content">
			<div class="container p-4">
				<div class="row justify-content-center">
					<h3 class="text-center">Animal Profile</h3>
				</div>
				<div class="card border border-0">
					<img src="http://placekitten.com/500/500" alt="Random image of a cat"
						  class="card-image-top p-5 img-fluid mx-auto">
					<h4 class="text-center">About this animal:</h4>
					<ul class="list-group">
						<li class="list-group-item">Status: Found</li>
						<li class="list-group-item">Date Lost/Found: August 26, 2018</li>
						<li class="list-group-item">Species: Cat</li>
						<li class="list-group-item">Name: Fuzzy</li>
						<li class="list-group-item">Color: White/Brown</li>
						<li class="list-group-item">Location Lost/Found: 1st and Main</li>
						<li class="list-group-item">Gender: Male</li>
						<li class="list-group-item">Description: Fuzzy is a three year-old male cat with light brown and white
							fur. He was last seen near our house on 1st and Main about 10pm on the 18th of August wearing a
							blue collar.
						</li>
					</ul>
					<h4 class="p-3 text-center mt-4">Contact:</h4>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#contact">Info</button>
				</div>
			</div>
		</div>
		<!-- comment section -->
		<div class="container">
			<div class="row">
				<div class="col-sm-2 col-md-3"></div>
				<div class="card col-12 col-sm-8 col-md-6 col-lg-10 offset-1">
					<br>
					<p><strong>Comments</strong></p>
					<br>
					<form id="createCommentForm" name="createCommentForm" novalidate>
						<textarea class="form-control" type="text" name="commentContent" id="commentContent"
									 placeholder="Write your comment here!" formControlName="commentContent" rows="3"></textarea>
						<button class="btn btn-success mt-3" type="submit">Submit Comment</button>
					</form>
					<div class="col-12 col-lg-12">
						<!--<div class="card-columns">-->

						<!-- begin comment item -->
						<div class="row border-bottom">
							<div class="card-body">
								<h4 class="card-title">Mike Smith</h4>
								<p class="card-text">Quit losing your dog!</p>
								<div class="small text-muted">Aug 25, 2018
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3"></div>
		</div>
	</main>
<?php require_once("contact-modal.php"); ?>
<?php require_once("footer.php"); ?>
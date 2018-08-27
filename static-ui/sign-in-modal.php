<?php require_once("head-utils.php"); ?>
<?php require_once("navbar.php"); ?>

<section>
	<div class="modal fade" id="sign-in-modal" tabindex="-1" role="dialog" aria-labelledby="sign-in-modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row ml-auto">
					<h4 class="modal-title pl-3">Sign-In</h4>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row justify-content-center">
					<div class="g-signin2" data-width="110" data-onsuccess="onSignIn"></div><br>
					</div>
					<div class="row justify-content-center mt-3">
					<a id="facebook-button" class="btn btn-social btn-facebook">
						<i class="fab fa-facebook-square pr-3"></i> Sign in
					</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

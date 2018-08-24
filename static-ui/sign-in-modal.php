<?php require_once("head-utils.php"); ?>
<?php require_once("navbar.php"); ?>


<main>
	<!--this button is for testing purposes-->
	<button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#sign-in-modal">
		Test Modal!
	</button>


	<div class="modal fade" id="sign-in-modal" tabindex="-1" role="dialog" aria-labelledby="sign-in-modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title ml-auto mr-auto">Sign-In</h4>
				</div>
				<div class="modal-body ml-auto mr-auto">
					<div class="g-signin2" data-onsuccess="onSignIn"></div>
				</div>
			</div>
		</div>
	</div>
</main>

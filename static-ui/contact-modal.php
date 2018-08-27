<?php require_once ("head-utils.php");?>

<?php require_once("animal-card.php");?>

<section>

	<!-- Modal -->
	<div id="contact" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row ml-auto">
						<h5 class="modal-title">Contact Information</h5>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="mx-auto modal-body">
					<div class="g-recaptcha" data-sitekey="insert sitekey here"></div>
<!--					Below is some example text of what can be displayed when recaptcha is verified d.none is for testing purposes-->
					<div class="text-center d-none">
						<h4>Name</h4>
						<p>John Doe</p>
						<h4>Phone</h4>
						<p>505.555.5555</p>
						<h4>Email</h4>
						<p>john.doe@aol.com</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


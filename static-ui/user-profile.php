<?php require_once ("head-utils.php");?>
<?php require_once("navbar.php");?>
<?php /*require_once ("footer.php"); */?>

<main class="form-padding">
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-6">
				<h1 class="text-center my-5">Update Profile</h1>
			</div><!--col-md-6-->
		</div><!--row-->
		<form class="form-control-lg" id="form-profile" action="" method="post">
			<div class="row justify-content-md-center">
				<div class="col-md-3">
					<label class="control-label" for="first-name">First Name <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="first-name" placeholder="Your first name" name="first-name" required />
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
				<div class="col-md-3">
					<label class="control-label" for="last-name">Last Name <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="last-name" placeholder="Your last name" name="last-name" required />
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<div class="form-group mb-3">
						<label class="control-label" for="email">Email <span class="text-danger">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
							</div><!--input-group-prepend-->
							<input type="text" class="form-control" placeholder="Enter your email address" name="email" aria-describedby="emailHelp" required />
						</div><!--	input-group-->
						<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					</div><!--	form-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<label class="control-label" for="phone">Phone Number <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-phone text-primary"></i></span>
						</div><!--	input-group-prepend-->
						<input type="tel" id="phone" name="phone" class="form-control" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required />
						<span class="validity"></span>
					</div><!--		input-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<button type="button" class="btn btn-primary text-light mt-3 mr-3 profile-btn"><i class="fas fa-edit"></i>&nbsp;&nbsp;Update</button>
					<button type="button" class="btn btn-warning text-light mt-3 profile-btn"><i class="fas fa-undo"></i>&nbsp;&nbsp;Clear All</button>
				</div><!--col-md-6-->
			</div><!--row-->
		</form>
	</div><!--		container-->
</main>

<?php require_once "footer.php"; ?>

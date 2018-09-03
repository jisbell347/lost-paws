<?php require_once ("head-utils.php");?>
<?php require_once("navbar.php");?>


<main class="sfooter">
	<div class="sfooter-content">
	<form class="form-control-md mt-5" id="form-profile">
		<div class="container">
			<div class="row justify-content-md-center">
				<div class="form-group col-md-6">
					<h1 class="text-center my-5">Update Profile</h1>
				</div><!--col-md-6-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center">
				<div class="form-group col-md-3">
					<label class="control-label" for="first-name">First Name <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="first-name" name="first-name" placeholder="Your first name" required />
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
				<div class="form-group col-md-3">
					<label class="control-label" for="last-name">Last Name <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="last-name" name="last-name" placeholder="Your last name" required />
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center mb-3">
				<div class="form-group col-md-6">
					<label class="control-label" for="email">Email <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input class="form-control" type="text" id="email" name="email" placeholder="Enter your email address" aria-describedby="emailHelp" required />
					</div><!--	input-group-->
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
				</div><!--form-group col-md-6-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center">
				<div class="form-group col-md-6">
					<label class="control-label" for="phone">Phone Number <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-phone text-primary"></i></span>
						</div><!--	input-group-prepend-->
						<input class="form-control" type="tel" id="phone" name="phone" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required />
						<span class="validity"></span>
					</div><!--		input-group mb-3-->
				</div><!--form-group col-md-6-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center mt-3 mb-5">
				<div class="form-group col-md-6">
					<button type="submit" class="btn btn-primary text-light mr-3 profile-btn"><i class="fas fa-edit"></i>&nbsp;&nbsp;Update</button>
					<button type="reset" class="btn btn-warning text-light profile-btn"><i class="fas fa-undo"></i>&nbsp;&nbsp;Clear All</button>
				</div><!--col-md-6-->
			</div><!--row justify-content-md-center mt-3 mb-5-->
		</div><!--		container-->
	</form>
	</div>
</main>
<?php require_once "footer.php"; ?>


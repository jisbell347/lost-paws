<?php require_once ("head-utils.php");?>
<?php require_once("navbar.php");?>
<?php //require_once ("footer.php"); ?>

<main>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-6">
				<h1 class="text-center my-5">Post an Animal</h1>
			</div><!--col-md-6-->
		</div><!--row-->
		<form class="form-control-lg" id="form-animal" action="" method="post">
			<div class="row justify-content-md-center">
				<div class="col-md-3">
					<label class="control-label" for="animal-species">Animal Species <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-check-square text-primary"></i></span>
						</div><!--input-group-prepend-->
						<select class="form-control" id="animal-species">
							<option selected>Dog</option>
							<option>Cat</option>
						</select>
					</div><!--		input-group mb-3-->
				</div><!--col-md-3-->
				<div class="col-md-3">
					<label class="control-label" for="animal-gender">Animal Gender <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-check-square text-primary"></i></span>
						</div><!--input-group-prepend-->
						<select class="form-control" id="animal-gender">
							<option selected>Unknow</option>
							<option>Female</option>
							<option>Male</option>
						</select>
					</div><!--		input-group mb-3-->
				</div><!--col-md-3-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
				<label class="control-label" for="animal-name">Animal Name</label>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-pen text-primary"></i></span>
					</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="animal-name" placeholder="Animal name. Leave empty if unknow." name="animal-name" />
					</div><!--		input-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row justify-content-md-center-->
		</form>
	</div><!--		container-->

</main>
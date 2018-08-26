<?php require_once ("head-utils.php");?>
<?php require_once("navbar.php");?>
<?php //require_once ("footer.php"); ?>

<main>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-6">
				<h1 class="text-center my-5">Post a Pet</h1>
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
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
				<div class="col-md-3">
					<label class="control-label" for="animal-gender">Pet Gender <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-check-square text-primary"></i></span>
						</div><!--input-group-prepend-->
						<select class="form-control" id="animal-gender">
							<option selected>Unknow</option>
							<option>Female</option>
							<option>Male</option>
						</select>
					</div><!--input-group mb-3-->
				</div><!--col-md-3-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
				<label class="control-label" for="animal-name">Pet Name</label>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-pen text-primary"></i></span>
					</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="animal-name" placeholder="Animal name. Leave empty if unknown." name="animal-name" />
					</div><!--input-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row justify-content-md-center-->
			<div class="row justify-content-md-center">
						<div class="col-md-6">
					<div class="form-group mb-3">
						<label class="control-label" for="animal-color">Pet Color</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-check-square text-primary"></i></span>
							</div><!--input-group-prepend-->
							<select class="form-control" id="animal-color" aria-describedby="colorHelp">
								<option selected>Unknow</option>
								<option>Brown</option>
								<option>Red</option>
								<option>Gold</option>
								<option>Yellow</option>
								<option>Cream</option>
								<option>Black</option>
								<option>Blue</option>
								<option>Gray</option>
								<option>White</option>
								<option>Black and Tan</option>
								<option>Blue and Tan</option>
								<option>Black and White</option>
								<option>Bicolor</option>
								<option>Tricolor</option>
								<option>Merle</option>
								<option>Spotted</option>
								<option>Harlequin</option>
								<option>Dilute</option>
								<option>Smoke</option>
								<option>Tabbies</option>
								<option>Points</option>
							</select>
						</div><!--input-group-->
						<small id="colorHelp">Please choose a predominant pet color or pattern.</small>
					</div><!--form-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<label class="control-label" for="animal-location">Pet Last Known Location</label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-map-marker-alt text-primary"></i></span>
						</div><!--input-group-prepend-->
						<input type="text" class="form-control" id="animal-location" placeholder="Please enter the last know pet location." name="animal-location" required />
					</div><!--		input-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<label class="control-label" for="animal-description">Pet Description <span class="text-danger">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-comment text-primary"></i></span>
						</div>
						<textarea class="form-control" rows="5" placeholder="Please enter more detailed pet description." name="animal-description" required></textarea>
					</div><!--input-group mb-3-->
				</div><!--col-md-6-->
			</div><!--row-->
			<div class="row justify-content-md-center">
				<div class="col-md-6">
					<button type="button" class="btn btn-primary text-light mt-3 mr-3 animal-btn"><i class="fas fa-edit"></i>&nbsp;&nbsp;Post</button>
					<button type="button" class="btn btn-warning text-light mt-3 animal-btn"><i class="fas fa-undo"></i>&nbsp;&nbsp;Clear All</button>
				</div><!--col-md-6-->
			</div><!--row-->
		</form>
	</div><!--		container-->
</main>
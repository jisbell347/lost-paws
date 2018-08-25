<?php require_once ("head-utils.php");?>
<?php require_once("navbar.php");?>
<?php //require_once ("footer.php"); ?>

<main>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-8">
				<h1 class="text-center my-5">Post an Animal</h1>
			</div><!--col-md-8-->
		</div><!--row-->
		<form class="form-control-lg" id="form-animal" action="" method="post">
			<div class="row justify-content-md-center">
				<div class="col-md-4">
					<label class="control-label" for="animal-species">Animal Species <span class="red-star">*</span></label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-check-square"></i></span>
						</div><!--input-group-prepend-->
						<select class="form-control" id="animalSpecies">
							<option selected>Dog</option>
							<option>Cat</option>
						</select>
					</div><!--		input-group mb-3-->
				</div><!--col-md-4-->
			</div><!--row justify-content-md-center-->
		</form>
	</div><!--		container-->

</main>
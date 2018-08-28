<?php require_once("head-utils.php"); ?>
<?php require_once("navbar.php"); ?>

<main>
	<div class="container mt-4 home text-center">
		<div class="row">
			<div class="col-md">
				<h1>Welcome to Lost Paws!</h1>
				<p>Lost Paws is home to a community of pet lovers dedicated to helping lost pets reunite with their families.</p>
			</div>
		</div>
	</div>
	<div class="container pl-1 pr-1 mt-4">
	<a href="#" type="button" class="btn btn-primary btn-lg btn-block">Post a lost or found animal</a>
	</div>
	<div class="container animal-buttons mt-4 text-center">
		<div class="row">
			<div class="col-md">
				<h2>Search lost and found animals.</h2>
			</div>
		</div>
	</div>
	<div class="container mt-3 mb-5 text-center">
		<div class="row">
			<div class="col-md-6">
				<a href="#">
				<div class="card">
					<img class="card-img" src="http://placekitten.com/200/300" alt="card image">
					<div class="card-img-overlay">
						<div class="card-title">
						<h3>See All <br>Lost Animals</h3>
						</div>
					</div>
				</div>
				</a>
			</div>
			<div class="col-md-6">
				<a href="#">
					<div class="card">
						<img class="card-img" src="http://placekitten.com/300/300" alt="card image">
						<div class="card-img-overlay">
							<div class="card-title">
								<h3>See All <br>Found Animals</h3>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</main>

<?php require_once "footer.php";?>
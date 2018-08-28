<?php require_once ("head-utils.php"); ?>
<?php require_once ("navbar.php"); ?>

<main>
	<div class="container pt-5">
		<div class="row justify-content-between align-items-center">
			<h2 class="p-3">Search Results</h2>
			<label for="sort"></label>
			<select id="sort">
				<option value="">--Filter By--</option>
				<option value="color">Color</option>
				<option value="gender">Gender</option>
				<option value="name">Name</option>
				<option value="species">Species</option>
				<option value="status">Status</option>
			</select>
		</div>

		<div class="row bg-dark text-light justify-content-center rounded-top">
			<h4>Fuzzy</h4>
		</div>
		<div class="row align-items-center bg-dark rounded-bottom pb-3 mb-4">
			<div class="col-md-4">
				<img src="http://placekitten.com/300/250" alt="placeholder cat" class="img-fluid rounded p-3">
			</div>
			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item">Name: Fuzzy</li>
					<li class="list-group-item">Status: Lost</li>
					<li class="list-group-item">Species: Cat</li>
				</ul>
			</div>
			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item">Location: 1st and Main</li>
					<li class="list-group-item">Gender: Male</li>
					<li class="list-group-item">Color: Grey</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container pt-5">
		<div class="row justify-content-center">
			<nav aria-label="...">
				<ul class="pagination">
					<li class="page-item disabled">
						<a class="page-link" href="#" tabindex="-1">Previous</a>
					</li>
					<li class="page-item active">
						<a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
					</li>
					<li class="page-item">
						<a class="page-link" href="#">2</a>
					</li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item">
						<a class="page-link" href="#">Next</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</main>

<?php require_once ("footer.php");?>

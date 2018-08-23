<?php require_once ("head-utils.php");?>
<?php //require_once  ("sign-up-modal.php");?>


<header>
	<nav class="navbar navbar-expand-lg navbar-light border-bottom border-secondary shadow-sm bg-light fixed-top" id="navigation-bar" >

		<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="icon-bar top-bar"></span>
			<span class="icon-bar middle-bar"></span>
			<span class="icon-bar bottom-bar"></span>
		</button>
		<a class="navbar-brand text-secondary pl-3" href="#">Lost Paws</a>
		<button class="btn btn-secondary d-lg-none d-xl-none" type="submit">Search</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="#link1">Search Pets</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#link2">Post a Pet</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#link3">User Profile</a>
				</li>
				<li class="nav-item">
					<!--link triggers modal-->
					<!--<signIn></signIn>-->
					<a class="nav-link" href="#link4">Sign In</a>
				</li>
			</ul>
		</div>

	</nav>
</header>

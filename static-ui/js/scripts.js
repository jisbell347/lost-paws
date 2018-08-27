//this jquery statement closes the mobile navbar on link click.
$(document).ready('.navbar-nav>li>a').on('click', function(){
	$('.navbar-collapse').collapse('hide');
});
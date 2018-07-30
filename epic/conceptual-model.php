<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Lost Paws Conceptual Model</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/styles.css" type="text/css">
	</head>
	<body>
		<h1>Conceptual Model</h1>
		<h2>oAuth</h2>
		<ul>
			<li>oAuthId (primary key)</li>
			<li>oAuthSource</li>
		</ul>
		<h2>Profile</h2>
		<ul>
			<li>profileId (primary key)</li>
			<li>profileOAuthId (foreign key)</li>
			<li>profileAccessToken</li>
			<li>profileEmail</li>
			<li>profileName</li>
			<li>profilePhone</li>
		</ul>
		<h2>Animal</h2>
		<ul>
			<li>animalId (primary key)</li>
			<li>animalProfileId (foreign key)</li>
			<li>animalColor</li>
			<li>animalDate</li>
			<li>animalDescription</li>
			<li>animalGender</li>
			<li>animalImageUrl</li>
			<li>animalLocation</li>
			<li>animalName</li>
			<li>animalSpecies</li>
			<li>animalStatus</li>
		</ul>
		<h2>Comment</h2>
		<ul>
			<li>commentId (primary key)</li>
			<li>commentAnimalId (foreign key)</li>
			<li>commentProfileId (foreign key)</li>
			<li>commentDate</li>
			<li>commentText</li>
		</ul>
		<h2>Relations</h2>
		<ul>
			<li>One oAuth can create many Profiles 1-m</li>
			<li>Multiple Profiles can Post multiple Animals m-n</li>
			<li>Multiple Animals can Contain multiple Comments m-m</li>
			<li>Multiple Profiles can Upload multiple images m-n</li>
			<li>Multiple Profiles can Post multiple Comments m-n</li>
		</ul>
		<h1>Entity Relationship Diagram</h1>
		<img src="./images/lost-paws-erd.svg" alt="entity relationship diagram">
		<h1>Entity Relationship Diagram Using Crow’s Foot Notation</h1>
		<img src="./images/lost-paws.jpg" alt="entity relationship diagram 2">
		<nav>
			<ul>
				<li><a href="index.php">Index Page</a></li>
				<li><a href="conceptual-model.php">Conceptual Model</a></li>
				<li><a href="use-case.php">Use Case</a></li>
			</ul>
		</nav>

	</body>
</html>
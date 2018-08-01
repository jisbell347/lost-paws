-- set UTF-8 charset
ALTER DATABASE lostfuzzy CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- drop tables if exist
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS animal;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS oAuth;

CREATE TABLE oAuth (
	oAuthId TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	oAuthSource VARCHAR(16),
	PRIMARY KEY (oAuthId)
);

-- create the Profile entity
CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileOAuthId TINYINT UNSIGNED NOT NULL,
	profileAccessToken VARCHAR(255),
	profileEmail VARCHAR(128) NOT NULL,
	profileName VARCHAR(92) NOT NULL,
	profilePhone VARCHAR(15),
	-- create indexes
	INDEX (profileOAuthId),
	INDEX (profileName),
	UNIQUE KEY (profileEmail),
	FOREIGN KEY(profileOAuthId) REFERENCES oAuth(oAuthId),
	PRIMARY KEY (profileId)
);

-- create the Animal entity
CREATE TABLE animal (
	animalId BINARY(16) NOT NULL,
	animalProfileId BINARY(16) NOT NULL,
	animalColor VARCHAR(25),
	animalDate DATETIME(6) NOT NULL,
	animalDescription VARCHAR(250) NOT NULL,
	animalGender VARCHAR(7) NOT NULL,
	animalImageUrl VARCHAR(500),
	animalLocation VARCHAR(200),
	animalName VARCHAR(100),
	animalSpecies CHAR(3) NOT NULL ,
	animalStatus VARCHAR(8) NOT NULL,
	INDEX (animalColor),
	FOREIGN KEY(animalProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(animalId)
);

-- create the Comment entity
CREATE TABLE comment (
	commentId BINARY(16) NOT NULL,
	commentAnimalId BINARY(16) NOT NULL,
	commentProfileId BINARY(16) NOT NULL,
	commentDate DATETIME(6) NOT NULL,
	commentText VARCHAR(1000) NOT NULL,
	INDEX (commentAnimalId),
	INDEX (commentProfileId),
	INDEX(commentDate),
	FOREIGN KEY(commentAnimalId) REFERENCES animal(animalId),
	FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(commentId)
);


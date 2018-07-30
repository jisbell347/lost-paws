-- set UTF-8 charset
ALTER DATABASE [databasename] CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- drop tables if exist
DROP TABLE IF EXISTS oAuth;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS animal;
DROP TABLE IF EXISTS comment;

CREATE TABLE oAuth (
	oAuthId BINARY(16) NOT NULL,
	oAuthSource VARCHAR(16)
);

-- create the Profile entity
CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileOAuthId BINARY(16) NOT NULL,
	profileAccessToken CHAR(32),
	profileEmail VARCHAR(128) NOT NULL,
	profileName VARCHAR(92) NOT NULL,
	profilePhone VARCHAR(15),
	-- create indexes
	INDEX(profileEmail),
	INDEX (profileName),
	FOREIGN KEY(profileOAuthId) REFERENCES oAuth(oAuthId),
	PRIMARY KEY (profileId)
);

-- create the Animal entity
CREATE TABLE animal (
	animalId BINARY(16) NOT NULL,
	animalProfileId BINARY(16) NOT NULL,
	animalDate DATETIME(6) NOT NULL,
	animalDescription VARCHAR(250) NOT NULL,
	animalGender ENUM('male', 'female'),
	animalImageUrl VARCHAR(500),
	animalLocation VARCHAR(200),
	animalName VARCHAR(100),
	animalSpecies ENUM('dog', 'cat'),
	animalStatus ENUM('found', 'lost', 'reunited'),
	INDEX (animalDate),
	INDEX (animalDescription),
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
	INDEX(commentDate),
	FOREIGN KEY(commentAnimalId) REFERENCES animal(animalId),
	FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(commentId)
);


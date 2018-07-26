USE [databasename];

-- set UTF-8 charset
ALTER DATABASE [databasename] CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- create the Profile entity
CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileActivationToken CHAR(32),
	profileEmail VARCHAR(128) NOT NULL,
	profileHash CHAR(97) NOT NULL,
	profileName VARCHAR(92) NOT NULL,
	-- create a unique key
	UNIQUE KEY (userEmail),
	-- create index
	INDEX (userName),
	PRIMARY KEY (prifileId)
);

-- create the Animal entity
CREATE TABLE animal (
	animalId BINARY(16) NOT NULL,
	animalProfileId BINARY(16) NOT NULL,
	animalDate DATETIME(6) NOT NULL,
	animalDescription VARCHAR(250) NOT NULL,
	animalFeatures VARCHAR(100),
	animalGender ENUM('male', 'female'),
	animalImageUrl VARCHAR(2083),
	animalLocation VARCHAR(200),
	animalName VARCHAR(100),
	animalSpecies ENUM('dog', 'cat'),
	animalStatus ENUM('found', 'lost')
);

-- create the Comments entity
CREATE TABLE animal (
);

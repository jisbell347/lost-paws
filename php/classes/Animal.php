<?php

namespace \namespace\here;

/*
 * Animal section of the lostpaws.com site. After logging in, a user can post information about the animal including: status, color, name, species, gender and location as well as upload an image of the animal.
 */

class Animal {
	useValidateUuid;
	useValidateDate;

	/**
	 * id for animal: this is the primary key
	 * @var Uuid $animalId
	 */
	private $animalId;
	/**
	 * profileId for this animal; this is the foreign key
	 * @var Uuid $animalProfileId
	 */
	private $animalProfileId;

	private $animalColor;
	private $animalDate;
	private $animalDescription;
	private $animalGender;
	private $animalImageUrl;
	private $animalLocation;
	private $animalName;
	private $animalSpecies;
	private $animalStatus;

}
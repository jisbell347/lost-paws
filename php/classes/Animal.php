<?php

namespace \namespace\here;

/*
 * Animal section of the lostpaws.com site. After logging in, a user can post information about the animal including: status, color, name, species, gender and location as well as upload an image of the animal.
 */

class Animal {
	use ValidateUuid;
	use ValidateDate;

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
	/**
	 * color of the animal
	 * @var string $animalColor
	 */
	private $animalColor;
	/**
	 * date the animal was last seen
	 * @var \DateTime $animalDate
	 */
	private $animalDate;
	/**
	 * description of the animal
	 * @var string $animalDescription
	 */
	private $animalDescription;
	/**
	 * specify if animal is male or female
	 * @var string $animalGender
	 */
	private $animalGender;
	/**link to animal picture
	 * @var string $animalImageUrl
	 */
	private $animalImageUrl;
	/**
	 * location of the last place the animal was seen
	 * @var string $animalLocation
	 */
	private $animalLocation;
	/**
	 * The animal's name
	 * @var string $animalName
	 */
	private $animalName;
	/**
	 * Is the animal a cat or dog
	 * @var string $animalSpecies
	 */
	private $animalSpecies;
	/**
	 * is the animal lost, found, or reunited
	 * @var string $animalStatus
	 */
	private $animalStatus;

}
<?php
namespace Jisbell347\LostPaws\Test;
/**
 * TODO: Add oAuth
 */
use Jisbell347\LostPaws\{
	Profile,
	Animal,
	OAuth
};
use PDO;
use PHPUnit\DbUnit\TestCase;

//grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

/**
 * Full PHPUnit test for the Animal class
 *
 * This is a complete PHPUnit test of the Animal class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see \Jisbell347\LostPaws\Animal
 * @author Jude Baca-Miller <judeamiller@gmail.com>
 **/

Class AnimalTest extends LostPawsTest{
	/**
	 * OAuth Id that created profile
	 * @var OAuth oAuth
	 */
	protected $oAuth = null;
	/**
	 * Profile that created the Animal: this is the foreign key
	 * @var Profile profile
	 **/
	protected $profile = null;
	/**
	 * Color of the Animal
	 * @var string $VALID_ANIMAL_COLOR
	 **/
	protected $VALID_ANIMAL_COLOR = "PHPUnit test passing";
	/**
	 * Updated color of the Animal
	 * @var string $VALID_ANIMAL_COLOR2
	 **/
	protected $VALID_ANIMAL_COLOR2 = "PHPUnit test is still passing";
	/**
	 * timestamp of the Animal; this starts as null and is assigned later
	 * @var \DateTime $VALID_ANIMAL_DATE
	 **/
	protected $VALID_ANIMAL_DATE = null;
	/**
	 * update timestamp of the Animal; this starts as null and is assigned later
	 * @var \DateTime $VALID_ANIMAL_DATE2
	 **/
	protected $VALID_ANIMAL_DATE2 = null;
	/**
	 * Description of the Animal
	 * @var string $VALID_ANIMAL_DESCRIPTION
	 **/
	protected $VALID_ANIMAL_DESCRIPTION = "PHPUnit test passing";
	/**
	 * Updated description of the Animal
	 * @var string $VALID_ANIMAL_DESCRIPTION2
	 **/
	protected $VALID_ANIMAL_DESCRIPTION2 = "PHPUnit test is still passing";
	/**
	 * Gender of the Animal
	 * @var string $VALID_ANIMAL_GENDER
	 **/
	protected $VALID_ANIMAL_GENDER = "PHPUnit test passing";
	/**
	 * Updated Gender of the Animal
	 * @var string $VALID_ANIMAL_GENDER2
	 **/
	protected $VALID_ANIMAL_GENDER2 = "PHPUnit test is still passing";
	/**
	 * URL of Animal photo
	 * @var string $VALID_ANIMAL_IMAGEURL
	 **/
	protected $VALID_ANIMAL_IMAGEURL = "PHPUnit test passing";
	/**
	 * Updated URL of Animal photo
	 * @var string $VALID_ANIMAL_IMAGEURL2
	 **/
	protected $VALID_ANIMAL_IMAGEURL2 = "PHPUnit test is still passing";
	/**
	 * Location of the Animal
	 * @var string $VALID_ANIMAL_LOCATION
	 **/
	protected $VALID_ANIMAL_LOCATION = "PHPUnit test passing";
	/**
	 * Updated location of the Animal
	 * @var string $VALID_ANIMAL_LOCATION2
	 **/
	protected $VALID_ANIMAL_LOCATION2 = "PHPUnit test is still passing";
	/**
	 * Name of the Animal
	 * @var string $VALID_ANIMAL_NAME
	 **/
	protected $VALID_ANIMAL_NAME = "PHPUnit test passing";
	/**
	 * Updated name of the Animal
	 * @var string $VALID_ANIMAL_NAME2
	 **/
	protected $VALID_ANIMAL_NAME2 = "PHPUnit test is still passing";
	/**
	 * Species of the Animal
	 * @var string $VALID_ANIMAL_SPECIES
	 **/
	protected $VALID_ANIMAL_SPECIES = "PHPUnit test passing";
	/**
	 * Updated species of the Animal
	 * @var string $VALID_ANIMAL_SPECIES2
	 **/
	protected $VALID_ANIMAL_SPECIES2 = "PHPUnit test is still passing";
	/**
	 * Status of the Animal
	 * @var string $VALID_ANIMAL_STATUS
	 **/
	protected $VALID_ANIMAL_STATUS = "PHPUnit test passing";
	/**
	 * Updated status of the Animal
	 * @var string $VALID_ANIMAL_STATUS2
	 **/
	protected $VALID_ANIMAL_STATUS2 = "PHPUnit test is still passing";




	/**
	 * Valid timestamp to use as an OLD_ANIMAL_POST_DATE
	 **/
	protected $VALID_OLD_ANIMAL_POST_DATE = null;
	/**
	 * Valid timestamp to use as an NEW_ANIMAL_POST_DATE
	 **/
	protected $VALID_NEW_ANIMAL_POST_DATE = null;


	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp(): void {
		parent::setUp();
		//create and insert a Profile to own and test the Animal.
		$this->oAuth = new OAuth($this->oAuth->getOAuthId(),$this->oAuth->getOAuthSource());
		$this->profile = new Profile($this->profile->getProfileId(), $this->profile->getProfileOAuthId(), $this->profile->getProfileAccessToken(), $this->profile->getProfileEmail(), $this->profile->getProfileName(), $this->profile->getProfilePhone());
		$this->profile->insert($this->getPDO());
		//calculate the date(use the time the unit test was setup)
		$this->VALID_ANIMAL_DATE = new \DateTime();
		//format the new animal post date to use for testing P5D means period of 5 days
		$this->VALID_NEW_ANIMAL_POST_DATE = new \DateTime();
		$this->VALID_NEW_ANIMAL_POST_DATE->sub(new \DateInterval("P5D"));
		//format the old animal post date to use for testing
		$this->VALID_OLD_ANIMAL_POST_DATE = new \DateTime();
		$this->VALID_OLD_ANIMAL_POST_DATE->sub(new \DateInterval("P5D"));
	}

	/**
	 * test inserting a valid Animal Posting, and verify that the actual mySQL data matches
	 **/
	public function testInsertValidAnimal(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");
		$animalId = generateUuidV4();
		$animal = new Animal($animalId, $this->profile->getProfileId(),$this->VALID_ANIMAL_COLOR, $this->VALID_ANIMAL_DATE, $this->VALID_ANIMAL_DESCRIPTION, $this->VALID_ANIMAL_GENDER,$this->VALID_ANIMAL_IMAGEURL, $this->VALID_ANIMAL_LOCATION, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_SPECIES, $this->VALID_ANIMAL_STATUS);
		$animal->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoAnimal = Animal::getAnimalByAnimalId($this->getPDO(),$animal->getAnimalId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$this->assertEquals($pdoAnimal->getAnimalId(), $animalId);
		$this->assertEquals($pdoAnimal->getAnimalProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoAnimal->getAnimalColor(), $this->VALID_ANIMAL_COLOR);
		$this->assertEquals($pdoAnimal->getAnimalDescription(),$this->VALID_ANIMAL_DESCRIPTION);
		$this->assertEquals($pdoAnimal->getAnimalGender(), $this->VALID_ANIMAL_GENDER);
		$this->assertEquals($pdoAnimal->getAnimalImageUrl(),$this->VALID_ANIMAL_IMAGEURL);
		$this->assertEquals($pdoAnimal->getAnimalLocation(), $this->VALID_ANIMAL_LOCATION);
		$this->assertEquals($pdoAnimal->getAnimalName(),$this->VALID_ANIMAL_NAME);
		$this->assertEquals($pdoAnimal->getAnimalSpecies(), $this->VALID_ANIMAL_SPECIES);
		$this->assertEquals($pdoAnimal->getAnimalStatus(), $this->VALID_ANIMAL_STATUS);
		//format the date to seconds since the beginning of time to avoid round off error.
		$this->assertEquals($pdoAnimal->getAnimalDate()->getTimestamp(), $this->VALID_ANIMAL_DATE->getTimestamp());
	}


}
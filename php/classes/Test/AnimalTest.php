<?php
namespace Jisbell347\LostPaws;

use Jisbell347\LostPaws\Test\LostPawsTest;
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
	 * Profile that created the Animal: this is the foreign key
	 * @var Profile profile
	 **/
	protected $profile = null;
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


	//insert color etc

	/**
	 * timestamp of the Animal; this starts as null and is assigned later
	 * @var \DateTime $VALID_ANIMAL_DATE
	 **/
	protected $VALID_ANIMAL_DATE = null;
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
		//create and insert a Profile to own and test the Animal.
		$this->profile = new Profile(generateUuidV4(), null);
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
		$animal = new Animal($animalId, $this->profile->getProfileId(),$this->VALID_ANIMAL_CONTENT, $this->VALID_ANIMAL_DATE);
		$animal->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoAnimal = Animal::getAnimalByAnimalId($this->getPDO(),$animal->getAnimalId());
		//see line 82 on tweettest


	}


}
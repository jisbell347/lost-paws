<?php
namespace Jisbell347\LostPaws\Test;
// grab the class under scrutiny
require_once (dirname(__DIR__) . "/autoload.php");
//require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Jisbell347\LostPaws\OAuth;
use Jisbell347\LostPaws\Profile;
use Ramsey\Uuid;
/**
 * Full PHPUnit test for the Profile class
 *
 * This is a complete PHPUnit test of the Profile class.
 *
 * @see OAuth
 * @see Profile
 * Asya Nikitina <anikitina@cnm.edu>
 * @version 1.0.0
 **/

class ProfileTest extends LostPawsTest {

	/**
	 * declare state variables
	 */
	protected $oAuth = 100;
	protected $VALID_ACCESS_TOKEN;
	protected $VALID_EMAIL1 = "b.smith@gmail.com";
	protected $VALID_EMAIL2 = "j.martin@yahoo.com";
	protected $VALID_NAME1 = "Bob Smith";
	protected $VALID_NAME2 = "Jen Martin";
	protected $VALID_PHONE1 = "+1 (505) 555-5555";
	protected $VALID_PHONE2 = "+1-222-333-4455";

	/*
	public final function setUp() {
		parent::setUp();
	}
*/
	/**
	 * instantiate a valid Profile object and return it
	 **/
	public function createProfile() : Profile {
		$this->VALID_ACCESS_TOKEN = bin2hex(random_bytes(16));
		return(new Profile(generateUuidV4(), $this->oAuth, $this->VALID_ACCESS_TOKEN, $this->VALID_EMAIL1, $this->VALID_NAME1, $this->VALID_PHONE1));
	}


	/**
	 * insert a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());

		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileAccessToken(), $this->VALID_ACCESS_TOKEN);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL1);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_NAME1);
		$this->assertEquals($pdoProfile->getProfilePhone(), $this->VALID_PHONE1);
	}

	/**
	 * insert a valid Profile, edit it, and update it and then verify that the actual mySQL data matches
	 **/
	public function testUpdateValidProfile() :void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());
		// edit three different properties in the current Profile object
		$profile->setProfileEmail($this->VALID_EMAIL2);
		$profile->setProfileName($this->VALID_NAME2);
		$profile->setProfilePhone($this->VALID_PHONE2);
		// update the current profile record in the database
		$profile->update($this->getPDO());
		// grab the record from the database and check if each field matches the correspondent value
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());

		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileAccessToken(), $this->VALID_ACCESS_TOKEN);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL2);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_NAME2);
		$this->assertEquals($pdoProfile->getProfilePhone(), $this->VALID_PHONE2);
	}

	/**
	 * insert a valid Profile into the database and then delete it
	 **/
	public function testDeleteValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());
		// check if it was inserted successfully
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("profile"));
		$currProfileId = $profile->getProfileId();
		// delete it
		$profile->delete($this->getPDO());
		// try to find it in the database
		$pdoProfile Profile::getProfileByProfileId($this->getPDO(), $currProfileId);
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
	}

	/**
	 * grab a Profile from the database using a valid Profile ID
	 **/
	public function testGetProfileByValidId() : void {

	}
	/**
	 * grab a Profile from the database using an invalid Profile ID
	 **/
	public function testGetProfileByInvalidId() : void {

	}

	/**
	 * grab a Profile from the database using a valid phone number
	 **/
	public function testGetProfileByValidPhone() : void {

	}

	/**
	 * grab a Profile from the database using an invalid phone number
	 **/
	public function testGetProfileByInvalidPhone() : void {

	}

	/**
	 * try to insert a Profile that has invalid properties to the database
	 **/
	public function testInsertInvalidProfile() : void {

	}

	/**
	 * try to update a Profile in the database with invalid properties
	 **/
	public function updateProfileByInvalidValues() : void{

	}

	/*
		updateProfile
		deleteProfile
		getProfileByProfileId
		getProfileByProfileOAuthId
		getProfileByProfileEmail
		*/

	/*
	 * profileId BINARY(16) NOT NULL,
	profileOAuthId TINYINT UNSIGNED NOT NULL,
	profileAccessToken VARCHAR(255),
	profileEmail VARCHAR(128) NOT NULL,
	profileName VARCHAR(92) NOT NULL,
	profilePhone VARCHAR(15),
	*/

}
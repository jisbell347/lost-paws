<?php
namespace Jisbell347\LostPaws\Test;
// grab the class under scrutiny
require_once (dirname(__DIR__) . "/autoload.php");
//require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Jisbell347\LostPaws\{OAuth, Profile};
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
		$this->$VALID_ACCESS_TOKEN = generateUuidV4();
		$profile = (new Profile($this->$VALID_ACCESS_TOKEN, $this->oAuth, $this->VALID_ACCESS_TOKEN, $this->VALID_EMAIL1, $this->VALID_NAME1, $this->VALID_PHONE1);
		// make sure that accessors return the expected properties
		$this->assertEquals($profile->getProfileId(), $this->$VALID_ACCESS_TOKEN);
		$this->assertEquals($profile->getProfileOAuthId(), $this->oAuth);
		$this->assertEquals($profile->getProfileAccessToken(), $this->VALID_ACCESS_TOKEN);
		$this->assertEquals($profile->getProfileEmail(), $this->VALID_EMAIL1);
		$this->assertEquals($profile->getProfileName(), $this->VALID_NAME1);
		$this->assertEquals($profile->getProfilePhone(), $this->VALID_PHONE1);

		return($profile);
	}

	/**
	 * test inserting a valid Profile and verifying a new record appeared in the database
	 * @depends createProfile
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());

		// make sure that the record is inserted
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("profile"));
	}

	/**
	 * test grabing a Profile from the database using a valid Profile ID
	 * @depends testInsertValidProfile
	 **/
	public function testGetProfileByValidId() : void {
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileId(), $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileOAuthId(), $profile->getProfileOAuthId());
		$this->assertEquals($pdoProfile->getProfileAccessToken(), $profile->getProfileAccessToken());
		$this->assertEquals($pdoProfile->getProfileEmail(), $profile->getProfileEmail());
		$this->assertEquals($pdoProfile->getProfileName(), $profile->getProfileName());
		$this->assertEquals($pdoProfile->getProfilePhone(), $profile->getProfilePhone());
	}

	/**
	 * test grabing a Profile (that doesn't exist) from the database using a valid but wrong Profile ID
	 * @depends testInsertValidProfile
	 **/
	public function testGetProfileByWrongProfileId() : void {
		$wrongProfileId = generateUuidV4();
		// make sure that our test database has at least one record
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());
		// try to grab a record with wrong profile ID
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
	}

	/**
	 * test inserting a valid Profile, editing it, updating it and then verifying that the actual mySQL data matches
	 * @depends testInsertValidProfile
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
		$this->assertEquals($pdoProfile->getProfileId(),  $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileOAuthId(), $profile->getProfileOAuthId());
		$this->assertEquals($pdoProfile->getProfileAccessToken(),  $profile->getProfileAccessToken());
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL2);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_NAME2);
		$this->assertEquals($pdoProfile->getProfilePhone(), $this->VALID_PHONE2);
	}

	/**
	 * teset inserting a valid Profile into the database and then deleting it
	 * @depends testInsertValidProfile
	 **/
	public function testDeleteValidProfile() : void {
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());
		// count the number of rows after the profile was inserted
		$numRows = $this->getConnection()->getRowCount("profile");
		// save profile ID that is going to be deleted
		$currProfileId = $profile->getProfileId();
		// delete this profile
		$profile->delete($this->getPDO());
		// check if this profile is still in the database
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $currProfileId);
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows-1, $this->getConnection()->getRowCount("profile"));
	}

	/**
	 * test grabing a Profile from the database using a valid email address
	 * @depends testInsertValidProfile
	 **/
	public function testGetProfileByValidEmail() : void {
		// create an instance of the Profile class and insert it into the database
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());

		// try grabing the data from mySQL and check if the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileEmail($this->getPDO(), $profile->getProfileEmail());
		$this->assertEquals($pdoProfile->getProfileId(), $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileOAuthId(), $profile->getProfileOAuthId());
		$this->assertEquals($pdoProfile->getProfileAccessToken(), $profile->getProfileAccessToken());
		$this->assertEquals($pdoProfile->getProfileEmail(), $profile->getProfileEmail());
		$this->assertEquals($pdoProfile->getProfileName(), $profile->getProfileName());
		$this->assertEquals($pdoProfile->getProfilePhone(), $profile->getProfilePhone());
	}

	/**
	 * test grabing a Profile from the database using a wrong email address
	 **/
	public function testGetProfileByInvalidEmail() : void {
		// make sure that our test database has at least one record
		$profile = $this->createProfile();
		$profile->insert($this->getPDO());
		// try to grab a record with wrong email address
		$pdoProfile = Profile::getProfileByProfileEmail($this->getPDO(), "yesyes@nonono.com");
		$this->assertNull($pdoProfile);
	}

}
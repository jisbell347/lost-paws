<?php
namespace Jisbell347\LostPaws\Test;
// grab the class under scrutiny
require_once (dirname(__DIR__) . "/autoload.php");
//require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
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

	protected $VALID_PROFILE_ID;
	protected $VALID_OAUTH = null;
	protected $profile = null;
	protected $VALID_ACCESS_TOKEN;
	protected $VALID_EMAIL1 = "b.smith@gmail.com";
	protected $VALID_EMAIL2 = "j.martin@yahoo.com";
	protected $VALID_NAME1 = "Bob Smith";
	protected $VALID_NAME2 = "Jen Martin";
	protected $VALID_PHONE1 = "+1 (505) 555-5555";
	protected $VALID_PHONE2 = "+1-222-333-4455";


	public final function setUp() : void {
		parent::setUp();

		$oAuth = new OAuth(null, "google");
		$oAuth->insert($this->getPDO());
		$this->VALID_OAUTH = $oAuth->getOAuthId();
		$this->VALID_PROFILE_ID = generateUuidV4();
		$this->VALID_ACCESS_TOKEN = bin2hex(random_bytes(16));
		$this->profile = new Profile($this->VALID_PROFILE_ID, $this->VALID_OAUTH, $this->VALID_ACCESS_TOKEN, $this->VALID_EMAIL1, $this->VALID_NAME1, $this->VALID_PHONE1);
	}

	public function testValidProfileCreated() : void {
		$this->assertNotNull($this->profile);
		$this->assertEquals($this->profile->getProfileId(), $this->VALID_PROFILE_ID);
		$this->assertEquals($this->profile->getProfileOAuthId(), $this->VALID_OAUTH);
		$this->assertEquals($this->profile->getProfileAccessToken(), $this->VALID_ACCESS_TOKEN);
		$this->assertEquals($this->profile->getProfileEmail(), $this->VALID_EMAIL1);
		$this->assertEquals($this->profile->getProfileName(), $this->VALID_NAME1);
		$this->assertEquals($this->profile->getProfilePhone(), Profile::normalizePhoneNumber($this->VALID_PHONE1));
	}

	/**
	 * test inserting a valid Profile and verifying a new record appeared in the database
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a PDO connection object
		$pdo = $this->getPDO();
		$this->assertNotNull($pdo, "The PDO connection object is supposed to be not null");

		// insert an instance of the Profile class into the database
		$this->profile->insert($pdo);
		$newNumProws = $this->getConnection()->getRowCount("profile");

		// make sure that the record is inserted
		$this->assertEquals($numRows+1, $newNumProws, "Expected number of rows: " .strval($numRows+1) ." Actual number of rows: " .strval($newNumProws));

		// grab just inserted profile from the database and compare it to the original object
		$pdoProfile = Profile::getProfileByProfileId($pdo, $this->profile->getProfileId());
		$this->assertNotNull($pdoProfile, "The profile object is supposed to be not null");

		$this->assertEquals($pdoProfile->getProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileOAuthId(), $this->profile->getProfileOAuthId());
		$this->assertEquals($pdoProfile->getProfileAccessToken(), $this->profile->getProfileAccessToken());
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->profile->getProfileEmail());
		$this->assertEquals($pdoProfile->getProfileName(), $this->profile->getProfileName());
		$this->assertEquals($pdoProfile->getProfilePhone(), $this->profile->getProfilePhone());
	}

	/**
	 * test grabing a Profile (that doesn't exist) from the database using a valid but wrong Profile ID
	 **/
	public function testGetProfileByWrongProfileId() : void {
		$wrongProfileId = generateUuidV4();
		$pdo = $this->getPDO();
		// make sure that our test database has at least one record
		$this->profile->insert($pdo);
		// try to grab a record with wrong profile ID
		$pdoProfile = Profile::getProfileByProfileId($pdo, $wrongProfileId);
		$this->assertNull($pdoProfile);
	}

	/**
	 * test inserting a valid Profile, editing it, updating it and then verifying that the actual mySQL data matches
	 **/
	public function testUpdateValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create a PDO connection object
		$pdo = $this->getPDO();
		// insert an instance of the Profile class into the database
		$this->profile->insert($pdo);
		$newNumProws = $this->getConnection()->getRowCount("profile");
		$this->assertEquals($numRows+1, $newNumProws, "Expected number of rows: " .strval($numRows+1) ." Actual number of rows: " .strval($newNumProws));
		// edit three different properties in the current Profile object
		$this->profile->setProfileEmail($this->VALID_EMAIL2);
		$this->profile->setProfileName($this->VALID_NAME2);
		$this->profile->setProfilePhone($this->VALID_PHONE2);
		// update the current profile record in the database
		$this->profile->update($pdo);
		// grab the record from the database and check if each field matches the correspondent value
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $this->profile->getProfileId());
		// make sure that pdoProfile object is not null

		$this->assertNotNull($pdoProfile, "The profile object is supposed to be not null");
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(),  $this->profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileOAuthId(), $this->profile->getProfileOAuthId());
		$this->assertEquals($pdoProfile->getProfileAccessToken(),  $this->profile->getProfileAccessToken());
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL2);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_NAME2);
		$this->assertEquals($pdoProfile->getProfilePhone(), Profile::normalizePhoneNumber($this->VALID_PHONE2)); // normalize the phone number
	}









}
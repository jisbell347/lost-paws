<?php
namespace Jisbell347\LostPaws\Test;
// grab the class under scrutiny
require_once (dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
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


	/*
	 * profileId BINARY(16) NOT NULL,
	profileOAuthId TINYINT UNSIGNED NOT NULL,
	profileAccessToken VARCHAR(255),
	profileEmail VARCHAR(128) NOT NULL,
	profileName VARCHAR(92) NOT NULL,
	profilePhone VARCHAR(15),
	*/

	/**
	 * declare state variables
	 */
	protected $oAuth = null;
	protected $VALID_ACCESS_TOKEN;
	protected $VALID_EMAIL1 = "b.smith@gmail.com";
	protected $VALID_EMAIL2 = "j.martin@yahoo.com";
	protected $VALID_NAME1 = "Bob Smith";
	protected $VALID_NAME2 = "Jen Martin";
	protected $VALID_PHONE1 = "+1 (505) 555-5555";
	protected $VALID_PHONE2 = "+1-222-333-4455";

	public final function setUp() {
		parent::setUp();
		$this->VALID_ACCESS_TOKEN = bin2hex(random_bytes(16));
	}

	/**
	 * insert a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create an instance of the Profile class
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->oAuth, $this->VALID_ACCESS_TOKEN, $this->VALID_EMAIL1, $this->VALID_NAME1, $this->VALID_PHONE1);
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

	public function testInsertInvalidProfile() {

	}

	public function updateProfileByValidValues() {

	}

	public function updateProfileByInvalidValues() {

	}

	public function updateProfileUsingValidProfileId() {

	}

	public function updateProfileUsingInvalidProfileId() {

	}

	public function deleteProfileUsingValidProfileId() {

	}

	public function deleteProfileUsingInvalidProfileId() {

	}

	/*
updateProfile
deleteProfile
getProfileByProfileId
getProfileByProfileOAuthId
getProfileByProfileEmail
*/
}
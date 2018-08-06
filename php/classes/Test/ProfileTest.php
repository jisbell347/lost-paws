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

		$oAuth = new OAuth(null, "google");
		$oAuth->insert($this->getPDO());
		}

	public function createProfile() : Profile {
		$VALID_PROFILE_ID = bin2hex(random_bytes(16));
		$VALID_ACCESS_TOKEN = generateUuidV4();
		$profile = new Profile($this->VALID_PROFILE_ID, $this->$oAuth, $this->VALID_ACCESS_TOKEN, $this->VALID_EMAIL1, $this->VALID_NAME1, $this->VALID_PHONE1);
		$this->assertNotNull($profile);
		return($profile);
	}

	public function testValidProfileCreated() : void {
		$profile = $this->createProfile();

		$this->assertEquals($profile->getProfileId(), $this->VALID_PROFILE_ID);
		$this->assertEquals($profile->getProfileOAuthId(), $this->oAuth);
		$this->assertEquals($profile->getProfileAccessToken(), $this->VALID_ACCESS_TOKEN);
		$this->assertEquals($profile->getProfileEmail(), $this->VALID_EMAIL1);
		$this->assertEquals($profile->getProfileName(), $this->VALID_NAME1);
		$this->assertEquals($profile->getProfilePhone(), $this->VALID_PHONE1);
	}








}
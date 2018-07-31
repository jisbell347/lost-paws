<?php
namespace Jisbell347\LostPaws\Test;

use Jisbell347\LostPaws\OAuth;

// grab the class under scrutiny
require_once (dirname(__DIR__) . "/autoload.php");

/**
 * Full PHPUnit test for the OAuth class
 *
 * This is a complete PHPUnit test of the OAuth class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see OAuth
 * @author Joseph Isbell <jisbell1@cnm.edu>
 **/

class OAuthTest extends LostPawsTest {

	/**
	 * valid oAuth source to use
	 * @var string $VALID_SOURCE
	 **/
	protected $VALID_SOURCE = "oauth test";

	/**
	 * valid oAuth source to update
	 * @var string $VALID_SOURCE2
	 **/
	protected $VALID_SOURCE2 = "still passing test";

	public function setUp(): void {
		parent::setUp();
	}

	/**
	 * test inserting a valid oAuth and verify that the actual mySQL data matches
	 **/
	public function testInsertValidOAuth() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("oAuth");
		$oAuthId = 1;
		$oAuth = new OAuth($oAuthId, $this->VALID_SOURCE);
		$oAuth->insert($this->getPDO());

		// edit the OAuth and insert into mySQL
		$oAuth->setOAuthSource($this->VALID_SOURCE);
		$oAuth->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoOAuth = OAuth::getOAuthByOAuthId($this->getPDO(), $oAuth->getOAuthId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("oAuth"));
		$this->assertEquals($pdoOAuth->getOAuthId(), $oAuth);
		$this->assertEquals($pdoOAuth->getOAuthSource(), $this->VALID_SOURCE);
	}

	/**
	 * test inserting an OAuth, editing it, and then updating it
	 **/
	public function testUpdateValidOAuth() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("oAuth");

		//create a new OAuth and insert it into mySQL
		$oAuthId = 2;
		$oAuth = new OAuth($oAuthId, $this->VALID_SOURCE);
		$oAuth->insert($this->getPDO());

		//edit the OAuth and update it in mySQL
		$oAuth->setOAuthSource($this->VALID_SOURCE2);
		$oAuth->update($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoOAuth = OAuth::getOAuthByOAuthId($this->getPDO(), $oAuth->getOAuthId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("oAuth"));
		$this->assertEquals($pdoOAuth->getOAuthId(), $oAuthId);
		$this->assertEquals($pdoOAuth->getOAuthSource(), $this->VALID_SOURCE);
	}

	/**
	 * test creating an OAuth then deleting it
	 **/
	public function testDeleteValidOAuth() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("oAuth");

		$oAuthId = 3;
		$oAuth = new OAuth($oAuthId, $this->VALID_SOURCE);
		$oAuth->insert($this->getPDO());

		//delete the OAuth from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("oAuth"));
		$oAuth->delete($this->getPDO());

		//grab the data from mySQL and enforce the OAuth does not exist
		$pdoOAuth = OAuth::getOAuthByOAuthId($this->getPDO(), $oAuth->getOAuthId());
		$this->assertNull($pdoOAuth);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("oAuth"));
	}

	/**
	 * test inserting an OAuth and regrabbing it from mySQL
	 **/
	public function testGetValidOAuthByOAuthId() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("oAuth");
	}
}
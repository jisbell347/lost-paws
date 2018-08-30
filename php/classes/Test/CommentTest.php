<?php
namespace Jisbell347\LostPaws\Test;

use Jisbell347\LostPaws\{
	Animal,
	OAuth,
	Profile,
	Comment
};


//grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

//grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Comment class
 *
 * This is a complete PHPUnit test of the Comment class. It is complete because *ALL* mySQL/PDO enabled methods are tested
 * for both invalid and valid inputs
 *
 * @see Comment
 * @author Adel Moreno
 **/
class CommentTest extends LostPawsTest {
	/**
	 * OAuth for profile
	 * @var OAuth $OAuth
	 */

	protected $oAuth = null;

	/**
	 * Profile that created the Comment; this is for foreign key relations
	 * @var Profile $profile
	 **/
	protected $profile = null;

	/**
	 * Animal that the Comment was made on, this is for foreign key relations
	 * @var Animal $animal
	 **/
	protected $animal = null;

	/**
	 * text of the Comment
	 * @var string $VALID_COMMENTTEXT
	 **/
	protected $VALID_COMMENTTEXT = "PHPUnit test passing";

	/**
	 * text of the updated Comment
	 * @var string $VALID_COMMENTCONTENT2
	 **/
	protected $VALID_COMMENTTEXT2 = "PHPUnit test still passing";

	/**
	 * timestamp of the Comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_COMMENTDATE
	 **/
	protected $VALID_COMMENTDATE = null;

	/**
	 *create dependent objects before running each test
	 **/

	public final function setUp(): void {
		parent::setUp();
		//create and insert an Animal to receive the the test Comment

		// create and insert a Profile to own the test Comment

		$this->oAuth = new OAuth(null, "facebook");
		$this->oAuth->insert($this->getPDO());

		$this->profile = new Profile(generateUuidV4(), $this->oAuth->getOAuthId(), "123", "catsanddogs@found.com", "TheGoodestBoy", "1234567890");
		$this->profile->insert($this->getPDO());

		$this->animal = new Animal(generateUuidV4(), $this->profile->getProfileId(), "white", new \DateTime(), "Cocker Spaniel Dachsund Mix", "Female", "https://placedog.net/520", "Downtown Albuquerque", "Lady", "Dog", "Lost");
		$this->animal->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup)
		$this->VALID_COMMENTDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Comment and verify that the actual mySQL data matches
	 */
	public function testInsertValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTDATE, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);
	}

	/**
	 * test inserting a Comment, editing it, and then updating it
	 */

	public function testUpdateValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentid = generateUuidV4();
		$comment = new Comment($commentid, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTDATE, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// edit the Comment and update it in the mySQL
		$comment->setCommentText($this->VALID_COMMENTTEXT2);
		$comment->update($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($pdoComment->getCommentId(), $commentid);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT2);

	}

	/**
	 * test creating a Comment and then deleting it
	 **/
	public function testDeleteValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTDATE, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// delete the Comment from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$comment->delete($this->getPDO());

		// grab the data from mySQL and ensure that the Comment does not exist
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertNull($pdoComment);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));

	}

	/**
	 * test grabbing a Comment that does not exist
	 **/
	public function testGetInvalidCommentByCommentId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
		$this->assertNull($comment);
	}


	/**
	 * test inserting a Comment and regrabbing it from mySQL by Profile Id
	 **/
	public function testGetValidCommentByCommentProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = $comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTDATE, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $comment->getCommentProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Jisbell347\LostPaws\Comment", $results);
//		var_dump($results);
		// grab the result from the array and validate it
		$pdoComment = $results[0];


		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);

	}

	/**
	 * test grabbing a Comment that does not exist
	 */
	public function testGetInvalidCommentByCommentProfileId(): void {
		// grab a profile id that exceeds the maximum allowable comment profile id
		$comment = Comment::getCommentByCommentProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}

	/**
	 * test grabbing a comment by animal id
	 **/

	public function testGetValidCommentByCommentAnimalId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), null, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$results = Comment::getCommentByCommentAnimalId($this->getPDO(), $comment->getCommentAnimalId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		//$this->assertContainsOnlyInstancesOf("Jisbell347\LostPaws\Comment", $results);

		//grab the result from the array and validate it
		$pdoComment = $results[0];
		$pdoProfile = $results[0];

		$this->assertEquals($pdoComment->comment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->comment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->comment->getCommentProfileId(), $this->profile->getProfileId());
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->comment->getCommentDate()->getTimestamp(), $comment->getCommentDate()->getTimestamp());
		$this->assertEquals($pdoComment->comment->getCommentText(), $this->VALID_COMMENTTEXT);
		$this->assertEquals($pdoProfile->profileName, $this->profile->getProfileName());

	}

	/**
	 * test grabbing a comment that does not exist
	 **/
	public function testGetInvalidCommentByCommentAnimalId(): void {
		// grab a profile id that exceeds the maximum allowable comment animal id
		$comment = Comment::getCommentByCommentAnimalId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);

	}

	/**
	 * test grabbing a Comment by the comment Text
	 **/
	public function testGetValidCommentByCommentText(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTDATE, $this->VALID_COMMENTTEXT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$results = Comment::getCommentByCommentText($this->getPDO(), $comment->getCommentText());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);

		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Jisbell347\LostPaws\Comment", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);
	}

	public function testGetInvalidCommentByCommentText(): void {
		//grab a comment by text that does not exist
		$comment = Comment::getCommentByCommentText($this->getPDO(), "bork!");
		$this->assertCount(0, $comment);
	}
}

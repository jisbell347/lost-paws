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

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/uuid.php");

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

		$this->oAuth = new OAuth($this->oAuth->getOAuthId(), "facebook");
		$this->oAuth->insert($this->getPDO());

		$this->profile = new Profile($this->profile->getProfileId(), $this->profile->getProfileOAuthId(), "woof", "catsanddogs@found.com", "TheGoodestBoy", "1234567890");
		$this->profile->insert($this->getPDO());

		$this->animal = new Animal($this->animal->getAnimalId(), $this->animal->getAnimalProfileId(), "white", $this->animal->getAnimalDate(), "Cocker Spaniel Dachsund Mix", "Female", "https://placedog.net/520", "Downtown Albuquerque", "Lady", "dog", "lost");
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
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
	}

	/**
	 * test inserting a Comment, editing it, and then updating it
	 */

	public function testUpdateValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentid = generateUuidV4();
		$comment = new Comment($commentid, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
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
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT2);
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());

	}

	/**
	 * test creating a Comment and then deleting it
	 **/
	public function testDeleteValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
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
	 * test inserting a Comment and regrabbing it from mySQL
	 **/
	public function testGetValidCommentByCommentProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = $comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $comment->getCommentProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Jisbell347\LostPaws\Comment", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];

		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());
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
		$comment = new Comment($commentId, $this->animal->getAnimalId(), $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$results = Comment::getCommentByCommentAnimalId($this->getPDO(), $comment->getCommentAnimalId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Jisbell347\LostPaws\Comment", $results);

		//grab the result from the array and validate it
		$pdoComment = $results[0];

		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentText(), $this->VALID_COMMENTTEXT);
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENTDATE->getTimestamp());

	}

	/**
	 * test grabbing a comment that does not exist
	 **/
	public function testGetInvalidCommentByCommentAnimalId(): void {
		// grab a profile id that exceeds the maximum allowable comment animal id
		$comment = Comment::getCommentByCommentAnimalId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);

	}
}
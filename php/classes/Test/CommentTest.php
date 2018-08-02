<?php
namespace Jisbell347\LostPaws;

use use PHPUnit\DbUnit\TestCase;...
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
abstract class CommentTest extends TestCase {
	/**
	 * Animal that the Comment was made on, this is for foreign key relations
	 * @var Animal $animal
	 **/
	protected $animal;

	/**
	 * Profile that created the Comment; this is for foreign key relations
	 * @var Profile $profile
	 **/
	protected $profile;

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
		//create and insert an Animal to receive the the test Comment

		// create and insert a Profile to own the test Comment
		/**
		 * NEED TO ASK ABOUT THIS LINE, NOT SURE HOW TO STRUCTURE AND CAUSING ERRORS :(
		 **/
		$this->profile = new Profile(generateUuidV4(), null, "username", );

		// calculate the date (just use the time the unit test was setup)
		$this->VALID_COMMENTDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Comment and verify that the actual mySQL data matches
	 */
	public function testInsertValidComment() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("comment"));
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

	public function testUpdateValidComment() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert it into mySQL
		$commentid = generateUuidV4();
		$comment = new Comment($commentid, $this->profile->getProfileId(), $this->VALID_COMMENTTEXT, $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// edit the Comment and update it in the mySQL
		$comment->setCommentText($this->VALID_COMMENTTEXT2);
		$comment->update($this->getPDO());

		// grab the data from mySQL and ensure the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
	}


}
<?php
namespace Jisbell347\LostPaws;

use use PHPUnit\DbUnit\TestCase;...
//grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
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
abstract class CommentTest extends TestCase {
	/**
	 * Profile that created the Comment; this is for foreign key relations
	 * @var Profile $profile
	 **/
	protected $profile;

	/**
	 * Animal that the Comment was made on, this is for foreign key relations
	 * @var Animal $animal
	 **/
	protected $animal;

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
	 *
	 */
}
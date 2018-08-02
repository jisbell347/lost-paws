<?php
namespace Jisbell347\LostPaws;

use Jisbell347\LostPaws\Test\LostPawsTest;
use PDO;
use PHPUnit\DbUnit\TestCase;

//grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

/**
 * Full PHPUnit test for the Animal class
 *
 * This is a complete PHPUnit test of the Animal class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see \Jisbell347\LostPaws\Animal
 * @author Jude Baca-Miller <judeamiller@gmail.com>
 **/

Class AnimalTest extends LostPawsTest{
	/**
	 * Profile that created the Animal: this is the foreign key
	 * @var Profile profile
	 **/
	protected $profile = null;
	/**
	 * Content the Animal entity
	 * @var string $VALID_ANIMAL_CONTENT
	 **/
	protected $VALID_ANIMAL_CONTENT = "PHPUnit test passing";
	/**
	 * content of updated Animal entity
	 * @var string $VALID_ANIMAL_CONTENT2
	 */
	protected $VALID_ANIMAL_CONTENT2 = "PHPUnit test is still passing";
	/**
	 * timestamp of the Animal; this starts as null and is assigned later
	 * @var \DateTime $VALID_ANIMAL_DATE
	 **/
	protected $VALID_ANIMAL_DATE = null;
	/**
	 * Valid timestamp to use as an OLD_ANIMAL_POST_DATE
	 */
	protected $VALID_OLD_ANIMAL_POST_DATE = null;
	/**
	 * Valid timestamp to use as an NEW_ANIMAL_POST_DATE
	 */
	protected $VALID_NEW_ANIMAL_POST_DATE = null;
}
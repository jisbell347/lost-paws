<?php

namespace \namespace\here;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *oAuth section of lostpaws.com.
 *
 * The user clicks on an authentication service (Google, Facebook, Github) which then
 * creates a profile on the lostpaws site based on the information gathered from the authentication site of their choice.
 * @author Joseph Isbell <jisbell1@cnm.edu>
 * @version 1.0.0
 **/

class OAuth {
	use ValidateDate;
	use ValidateUuid;

	/**
	 * id for this OAuth; This is the primary key
	 * @var int unsigned $oAuthId
	 **/
	private $oAuthId;

	/**
	 * Source where the data from the OAuth is being accessed from
	 * @var string $oAuthSource
	 **/
	private $oAuthSource;

	/**
	 * Constructor for the oAuth
	 *
	 * @param int $newOAuthId id of the oAuth or null if a new oAuth
	 * @param string $newOAuthSource the source the oAuth is getting the data from.
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if values exceed the preset bounds (e.g.; the strings have too many characters)
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if data violates the type hints
	 * @Documentation https:/php.net/manuel/en/languqge.oop5.decon.php
	 */

	public function __construct(int $newOAuthId, string $newOAuthSource) {
		try {
			$this->setOAuthId($newOAuthId);
			$this->setOAuthSource($newOAuthSource);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * The accessor method for the oAuthId
	 *
	 * @return int value of oAuth id
	 **/
	public function getOAuthId() : int {
		return($this->oAuthId);
	}

	/**
	 * The mutator method for the oAuth id
	 *
	 * @param int unsigned $newOAuthId new value of oAuth id
	 * @throws \RangeException if $newOAuthId is not positive
	 * @throws \TypeError if $newOAuthId is not an integer
	 **/
	public function setOAuthId(int $newOAuthId) : void {
		//Verifies that the oAuth Id is a positive number
		if($newOAuthId <= 0) {
			throw (new \InvalidArgumentException("Id cannot be less than or equal to zero"));
		}
		//Stores the value if passes validation
		$this->oAuthId = $newOAuthId;
	}

	/**
	 *The accessor method for the oAuth source
	 *
	 *@returns string value of oAuth source
	 **/
	public function getOAuthSource() : string {
		return($this->oAuthSource);
	}

	/**
	 * The mutator method for the oAuth source
	 *
	 * @param string $newOAuthSource new value of oAuth source
	 * @throws \RangeException if $newOAuthSource is > 16 characters
	 * @throws \TypeError if $newOAuthSource is not a string
	 **/

	public function setOAuthSource(string $newOAuthSource) : void {
		//Verify the source content is secure, trim the whitespace and remove any malicious html tags
		$newOAuthSource = trim($newOAuthSource);
		$newOAuthSource = filter_var($newOAuthSource, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		//Checks to see if value exceeds set character length and throws an error if it does
		if(strlen($newOAuthSource) > 16) {
			throw (new \RangeException("Character length cannot exceed 16 characters"));
		}
		$this->oAuthSource = $newOAuthSource;
	}

	/**
	 * Inserts the oAuth into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mysSQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		//create query template
		$query = "INSERT INTO oAuth(oAuthId, oAuthSource) VALUES (:oAuthId, :oAuthSource)";
		$statement = $pdo->prepare($query);

		//binds the member variables to the place holders in the template
		$parameters = ["oAuthId" => $this->oAuthId, "oAuthSource" => $this->oAuthSource ];
		$statement->execute($parameters);
	}

	/**
	 * Deletes the oAuth from mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//Creates the query template
		$query = "DELETE FROM oAuth WHERE oAuthId = :oAuthId";
		$statement = $pdo->prepare($query);

		//Binds the member variables to the place holders in the template
		$parameters = ["oAuthId" => $this->oAuthId];
		$statement->execute($parameters);
	}

	/**
	 * Updates the oAuth in mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update (\PDO $pdo) : void {
		//Create query template
		$query = "UPDATE oAuth SET oAuthSource = :oAuthSource WHERE oAuthId = :oAuthId";
		$statement = $pdo->prepare($query);

		//Binds the member variables to the place holders in the template
		$parameters = ["oAuthId" => $this->oAuthId, "oAuthSource" => $this->oAuthSource];
		$statement->execute($parameters);
	}
}
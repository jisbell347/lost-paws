<?php

namespace Jisbell347\LostPaws;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

/**
 *oAuth section of lostpaws.com.
 *
 * The user clicks on an authentication service (Google, Facebook, Github) which then
 * creates a profile on the lostpaws site based on the information gathered from the authentication site of their choice.
 * @author Joseph Isbell <jisbell1@cnm.edu>
 * @version 1.0.0
 **/

class OAuth implements \JsonSerializable {

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
	 **/

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

	/**
	 * Gets the oAuth by oAuthID
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $oAuthId oAuth Id to search for
	 * @return OAuth|null OAuth found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public static function getOAuthByOAuthId(\PDO $pdo, $oAuthId) : ?OAuth {
		//Create the query template
		$query = "SELECT oAuthId, oAuthSource FROM oAuth WHERE oAuthId = :oAuthId";
		$statement = $pdo->prepare($query);

		//Bind the oAuth Id to the place holder in the template
		$parameters = ["oAuthId" => $oAuthId];
		$statement->execute($parameters);

		//Grab the oAuth from mySQL
		try {
			$oAuth = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$oAuth = new OAuth($row["oAuthId"], $row["oAuthSource"]);
			}
		} catch(\Exception $exception) {
			//If the row couldn't be converted, rethrow it
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($oAuth);
	}

	/**
	 * Gets oAuth by source
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $oAuthSource oAuth source to search for
	 * @return \SplFixedArray SplFixedArray of sources found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getOAuthByOAuthSource(\PDO $pdo, string $oAuthSource) : \SplFixedArray {
		//Sanitize the source before searching for it
		$oAuthSource = trim($oAuthSource);
		$oAuthSource = filter_var($oAuthSource, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		//Escape any mySQL wild cards
		$oAuthSource = str_replace("_", "\\", str_replace("%", "\\%", $oAuthSource));

		//Create the query template
		$query = "SELECT oAuthId, oAuthSource FROM oAuth WHERE oAuthSource LIKE :oAuthSource";
		$statement = $pdo->prepare($query);

		//Bind the oAuth source to the place holder in the template
		$oAuthSource = "%$oAuthSource%";
		$parameters = ["oAuthSource" => $oAuthSource];
		$statement->execute($parameters);

		//Build an array of sources
		$sources = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$source = new OAuth($row["oAuthId"], $row["oAuthSource"]);
				$sources[$sources->key()] = $source;
				$sources->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($sources);
	}

	/**
	 * Gets all oAuths
	 *
	 * @param \PDO $pdo PDO Connection object
	 * @return \SplFixedArray of OAuths found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function  getAllOAuths(\PDO $pdo) : \SplFixedArray {
		//Create query template
		$query = "SELECT oAuthId, oAuthSource FROM oAuth";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//Build an array of oAuths
		$oAuths = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$oAuth = new OAuth($row["oAuthId"], $row["oAuthSource"]);
				$oAuths[$oAuths->key()] = $oAuth;
				$oAuths->next();
			} catch(\Exception $exception) {
				//If the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($oAuths);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["oAuthId"] = $this->oAuthId->toString();

		return($fields);
	}

}
<?php



namespace Jisbell347\LostPaws;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

/*
 * Profile section of the lostpaws.com site. After logging in with oAuth, a user profile is created which displays name and contact info.
 */
/**
 * for Profile Names: first - to lower, then - capitalize the first letter
 * TODO: setProfileName()
 *
 **/

/**
 * Profile class describes a registered user of LostPaws.com
 *
 * This entity depends on the OAuth entity
 *
 * @author Asya Nikitina <a.f.nikitina@gmail.com>
 * @version 1.0.0
 **/

class Profile implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this Profile; this is the primary key
	 * @var Uuid/string $profileId
	 **/
	private $profileId;

	/**
	 * OAuth id for this Profile; this is the foreign key
	 * @var int $profileOAuthId
	 **/
	private $profileOAuthId;

	/**
	 * access token for this profile
	 * @var ?string $profileAccessToken
	 **/
	private $profileAccessToken;

	/**
	 * email for this Profile; this is a unique index
	 * @var string $profileEmail
	 **/
	private $profileEmail;

	/**
	 * full name for this profile
	 * @var string $profileName
	 **/
	private $profileName;

	/**
	 * phone number for this profile
	 * @var ?string $profilePhone
	 **/
	private $profilePhone;

	/**
	 * Profile constructor.
	 * @param string|Uuid $newProfileId id of this Profile or null if a new Profile
	 * @param int $newProfileOAuthId OAuth id for this Profile
	 * @param ?string $newProfileAccessToken access token to safe guard against malicious accounts (could be null)
	 * @param string $newProfileEmail string containing email for this Profile
	 * @param string $newProfileName string containing a full name for this Profile
	 * @param string $newProfilePhone string containing phone number for this Profile
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if a data type violates a data hint
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newProfileId, int $newProfileOAuthId, ?string $newProfileAccessToken,
										 string $newProfileEmail, string $newProfileName, ?string $newProfilePhone) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileOAuthId($newProfileOAuthId);
			$this->setProfileAccessToken($newProfileAccessToken);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileName($newProfileName);
			$this->setProfilePhone($newProfilePhone);
		} catch(\InvalidArgumentException | \RangeException |\TypeError | \Exception $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id (or null if new Profile)
	 **/
	public function getProfileId(): Uuid {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param  Uuid|string $newProfileId value of new profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if the profile Id is not
	 **/
	public function setProfileId($newProfileId): void {
		try {
			// make sure that $newProfileId is a valid UUID
			$newProfileId = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception  $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for OAuth id
	 *
	 * @return int $profileOAuthId value for this Profile as an integer
	 **/
	public function getProfileOAuthId(): int {
		return ($this->profileOAuthId);
	}

	/**
	 * mutator method for OAuth id
	 *
	 * @param  int $newProfileOAuthId OAuth id for this Profile
	 * @throws \RangeException if $newProfileOAuthId is not positive
	 **/
	public function setProfileOAuthId(int $newProfileOAuthId): void {
		if ($newProfileOAuthId < 0) {
			throw (new \RangeException("OAuth ID must be a positive integer."));
		}
		if ($newProfileOAuthId > 255) {
			throw (new \RangeException("OAuth ID coudl not be greater than 255."));
		}
		$this->profileOAuthId = $newProfileOAuthId;
	}

	/**
	 * accessor method for an access token for this Profile
	 *
	 * @return string $profileAccessToken value for this Profile as string
	 **/
	public function getProfileAccessToken(): ?string {
		return ($this->profileAccessToken);
	}

	/**
	 * mutator method for account activation token (we allow Access Token to be NULL
	 *
	 * @param string $newProfileAccessToken
	 * @throws \RangeException if the token is not exactly 32 characters
	 */
	public function setProfileAccessToken(?string $newProfileAccessToken): void {
		$this->profileAccessToken = null;
		if ($newProfileAccessToken) {
			$newProfileAccessToken = trim($newProfileAccessToken);
			// remove all '-' if any
			$newProfileAccessToken = str_replace(["-"], "", $newProfileAccessToken);

			if(strlen($newProfileAccessToken) > 255) {
				throw(new \RangeException("Access token cannot be longer than 255-character long."));
			}

			if (ctype_xdigit($newProfileAccessToken)) {
				$this->profileAccessToken = $newProfileAccessToken;
			}
		}
	}

	/**
	 * accessor method for an email address
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 * mutator method for setting/changing an email address
	 *
	 * @param string $newProfileEmail new value of an email address
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify that the email address is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail)) {
			throw(new \InvalidArgumentException("Email address is empty or insecure."));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("Email address is too long."));
		}
		// store the valid email address
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for a full profile name
	 *
	 * @return string value of a Profile name
	 **/
	public function getProfileName(): string {
		return $this->profileName;
	}

	/**
	 * mutator method for setting/changing a profile name
	 *
	 * @param string $newProfileName new value of a user name for this Profile
	 * @throws \InvalidArgumentException if $newProfileName is not a valid string
	 * @throws \RangeException if $newProfileName is longer than 92-character long
	 **/
	public function setProfileName(string $newProfileName): void {
		// verify that the profile name is not empty and shorter than 92 characters
		$newProfileName = trim($newProfileName);
		if(empty($newProfileName)) {
			throw(new \InvalidArgumentException("Name is empty or invalid."));
		} else if (strlen($newProfileName) > 92) {
			throw(new \RangeException("Name is too long."));
		}
		// store the valid name in the class state variable
		$this->profileName = $newProfileName;
	}

	/**
	 * accessor method for a Profile phone number
	 *
	 * @return string value of a Profile phone number
	 **/
	public function getProfilePhone(): string {
		return $this->profilePhone;
	}

	/**
	 * this is a helper function that normalize the phone number
	 *
	 * @return string value of a normalized phone number or an empty string
	 **/
	public static function normalizePhoneNumber(string $phoneNum) : string {
		$phoneNum = trim($phoneNum);
		// FILTER_SANITIZE_NUMBER_INT removes everything except digits, "+", and "-"
		$phoneNum = filter_var($phoneNum, FILTER_SANITIZE_NUMBER_INT);
		if(!empty($phoneNum)) {
			// remove all "-" from the phone number (but allow "+" at the beginning
			$phoneNum = str_replace(["-"], "", $phoneNum);
		}
		return $phoneNum;
	}

	/**
	 * mutator method for setting/changing a Profile phone number
	 *
	 * @param string $newProfilePhone new value of a phone number for this Profile
	 * @throws \InvalidArgumentException if $newProfileName is empty or contains digits and special characters
	 * @throws \RangeException if $newProfileName is longer than 92-character long
	 **/
	public function setProfilePhone(?string $newProfilePhone): void {
		//if $profilePhone is null return it right away
		if($newProfilePhone === null) {
			$this->profilePhone = "";
			return;
		}
		// verify the phone is secure
		$newProfilePhone = Profile::normalizePhoneNumber($newProfilePhone);

		// verify the phone will fit in the database
		if(strlen($newProfilePhone) > 15) {
			throw(new \RangeException("Phone number is too long."));
		}
		// store the phone
		$this->profilePhone = $newProfilePhone;
	}

	/**
	 * inserts this Profile into mySQL
	 *
	 * @param \PDO $pdo PDO database connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public function insert(\PDO $pdo): void {
		// create query template
		$query = "INSERT INTO profile(profileId, profileOAuthId, profileAccessToken, profileEmail, profileName, profilePhone)
 									VALUES (:profileId, :profileOAuthId, :profileAccessToken, :profileEmail, :profileName, :profilePhone)";
		$statement = $pdo->prepare($query);
		$parameters = ["profileId" => $this->profileId->getBytes(),
							"profileOAuthId" => $this->profileOAuthId,
							"profileAccessToken" => $this->profileAccessToken,
							"profileEmail" => $this->profileEmail,
							"profileName" => $this->profileName,
							"profilePhone" => $this->profilePhone];
		$statement->execute($parameters);
	}


	/**
	 * updates a profile in mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */


	/**
	 * update this Profile from mySQL where profileId matches the search
	 *
	 * @param \PDO $pdo database connection object
	 * @throws \PDOException in case of mySQL related errors
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public function update(\PDO $pdo): void {
		try {
			$query = "UPDATE profile SET profileOAuthId = :profileOAuthId,
                                    profileAccessToken = :profileAccessToken,
                                    profileEmail = :profileEmail,
                                    profileName = :profileName,                              
                                    profilePhone = :profilePhone WHERE profileId = :profileId";
			$stmt = $pdo->prepare($query);
			$parameters = ["profileId" => $this->profileId->getBytes(),
				"profileOAuthId" => $this->profileOAuthId,
				"profileAccessToken" => $this->profileAccessToken,
				"profileEmail" => $this->profileEmail,
				"profileName" => $this->profileName,
				"profilePhone" => $this->profilePhone];
			$stmt->execute($parameters);
		} catch (\PDOException | \Exception $exception) {
			// re-throw exception if occured
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * delete this Profile from mySQL where profileId matches
	 *
	 * @param \PDO $pdo database connection object
	 * @throws \PDOException in case of mySQL related errors
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public function delete(\PDO $pdo): void {
		try {
			$query = "DELETE FROM profile WHERE profileId = :profileId";
			$stmt = $pdo->prepare($query);
			$parameters = ["profileId" => $this->profileId->getBytes()];
			$stmt->execute($parameters);
		} catch (\PDOException | \Exception $exception) {
			// re-throw exception if occured
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * get this Profile by profileId
	 *
	 * @param \PDO $pdo database connection object
	 * @param string $currProfileId profile Id to search for
	 * @return Profile object or null if profile is not found
	 * @throws \PDOException in case of mySQL related errors
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public static function getProfileByProfileId(\PDO $pdo, $profileId) : ?Profile {
		// sanitize the profile id before searching
		try {
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT * FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the profile id to the place holder in the template
		$parameters = ["profileId" => $profileId->getBytes()];
		$statement->execute($parameters);
		$newProfile = null;
		try {
			// grab the profile from mySQL
			$row = $statement->fetch(\PDO::FETCH_ASSOC);
			if ($row) {
				$newProfile = new Profile($row["profileId"], $row["profileOAuthId"], $row["profileAccessToken"],
					$row["profileEmail"], $row["profileName"], $row["profilePhone"]);
			}
		} catch (\PDOException | \Exception $exception) {
			// re-throw exception if occured
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// if everything went well, return $newProfile
		return ($newProfile);
	}

	/**
	 * get this Profile by profileOAuthId
	 *
	 * @param \PDO $pdo database connection object
	 * @param string $currProfileOAuthId profile OAuth Id to search for
	 * @return Profile object or null if profile is not found
	 * @throws \PDOException in case of mySQL related errors
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public static function getProfileByProfileOAuthId(\PDO $pdo, string $profileOAuthId): ?Profile {
		try {
			$query = "SELECT * FROM profile WHERE profileOAuthId = :profileOAuthId";
			$statement = $pdo->prepare($query);
			// bind the profile id to the place holder in the template
			$parameters = ["profileOAuthId" => $profileOAuthId];
			$statement->execute($parameters);
		}  catch (\Exception $exception) {
			// re-throw exception if occured
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
		$newProfile = null;
		try {
			// grab the profile from mySQL
			$row = $statement->fetch(\PDO::FETCH_ASSOC);
			if ($row) {
				$newProfile = new Profile($row["profileId"], $row["profileOAuthId"], $row["profileAccessToken"],
					$row["profileEmail"], $row["profileName"], $row["profilePhone"]);
			}
		} catch (\PDOException | \Exception $exception) {
			// re-throw exception if occured
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// if everything went well, return $newProfile
		return $newProfile;
	}

	/**
	 * get this Profile by profile email address
	 *
	 * @param \PDO $pdo database connection object
	 * @param string $currProfileEmail profile email address to search for
	 * @return Profile object or null if profile is not found
	 * @throws \PDOException in case of mySQL related errors
	 * @throws \InvalidArgumentException in case email address is empty or insecure
	 * @throws \Exception -- all others except for \PDOException exception
	 **/
	public function getProfileByProfileEmail(\PDO $pdo, string $currProfileEmail): ?Profile {
		// verify that the email address is secure
		$currProfileEmail = trim($currProfileEmail);
		$currProfileEmail = filter_var($currProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($currProfileEmail)) {
			throw(new \InvalidArgumentException("Profile email address is empty or insecure."));
		}
		try {
			$query = "SELECT * FROM profile WHERE profileEmail = :profileEmail";
			$statement = $pdo->prepare($query);
			// bind the profile id to the place holder in the template
			$parameters = ["profileEmail" => $currProfileEmail];
			$statement->execute($parameters);
		}   catch (\Exception $exception) {
			// re-throw exception if occured
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
		$newProfile = null;
		try {
			// grab the selected profile from mySQL
			$row = $statement->fetch(\PDO::FETCH_ASSOC);
			if ($row) {
				$newProfile = new Profile($row["profileId"], $row["profileOAuthId"], $row["profileAccessToken"],
					$row["profileEmail"], $row["profileName"], $row["profilePhone"]);
			}
		} catch (\PDOException | \Exception $exception) {
			// re-throw exception if occured
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// if everything went well, return $newProfile
		return $newProfile;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		// keep this profileAccessToken secret
		return [
			'profileId' => strval($this->profileId),
			'profileOAuthId' => strval($this->profileOAuthId),
			'profileEmail' => $this->profileEmail,
			'profileName' => $this->profileName,
			'profilePhone' => $this->profilePhone
		];
	}
}
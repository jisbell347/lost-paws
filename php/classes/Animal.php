<?php

namespace Jisbell347\LostPaws;
use Ramsey\Uuid\Uuid;

/**
 * Animal section of the lostpaws.com site. After logging in, a user can post information about the animal including: status, color, name, species, gender and location as well as upload an image of the animal.
 *
 * @author  Jude Baca-Miller <jmiller156@cnm.edu>
 **/

class Animal {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * id for animal: this is the primary key
	 * @var Uuid $animalId
	 **/
	private $animalId;
	/**
	 * profileId for this animal; this is the foreign key
	 * @var Uuid $animalProfileId
	 **/
	private $animalProfileId;
	/**
	 * color of the animal
	 * @var string $animalColor
	 **/
	private $animalColor;
	/**
	 * date the animal was last seen
	 * @var \DateTime $animalDate
	 **/
	private $animalDate;
	/**
	 * description of the animal
	 * @var string $animalDescription
	 **/
	private $animalDescription;
	/**
	 * specify if animal is male or female
	 * @var string $animalGender
	 **/
	private $animalGender;
	/**link to animal picture
	 * @var string $animalImageUrl
	 **/
	private $animalImageUrl;
	/**
	 * location of the last place the animal was seen
	 * @var string $animalLocation
	 **/
	private $animalLocation;
	/**
	 * The animal's name
	 * @var string $animalName
	 **/
	private $animalName;
	/**
	 * Is the animal a cat or dog
	 * @var string $animalSpecies
	 **/
	private $animalSpecies;
	/**
	 * is the animal lost, found, or reunited
	 * @var string $animalStatus
	 **/
	private $animalStatus;

	/**
	 * Animal constructor.
	 * @param Uuid $newAnimalId
	 * @param Uuid $newAnimalProfileId
	 * @param \DateTime| $newAnimalDate
	 * @param string $animalDescription
	 * @param string $newAnimalGender
	 * @param string $newAnimalImageUrl
	 * @param string $newAnimalLocation
	 * @param string $newAnimalName
	 * @param string $newAnimalSpecies
	 * @param string $newAnimalStatus
	 **/
	public function __construct(Uuid $newAnimalId, Uuid $newAnimalProfileId, $newAnimalDate, string $animalDescription, string $newAnimalGender, string $newAnimalImageUrl, string $newAnimalLocation, string $newAnimalName, string $newAnimalSpecies, string $newAnimalStatus) {
		try {
			$this->setAnimalId($newAnimalId);
			$this->setAnimalProfileId($newAnimalProfileId);
			$this->setAnimalDate($newAnimalDate);
			$this->setAnimalDescription($animalDescription);
			$this->setAnimalGender($newAnimalGender);
			$this->setAnimalImageUrl($newAnimalImageUrl);
			$this->setAnimalLocation($newAnimalLocation);
			$this->setAnimalName($newAnimalName);
			$this->setAnimalSpecies($newAnimalSpecies);
			$this->setAnimalStatus($newAnimalStatus);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for animal id
	 *
	 * @return Uuid value of animal id
	 */
	public function getAnimalId(): Uuid {
		return ($this->animalId);
	}

	/**
	 * mutator method for animal id
	 *
	 * @param Uuid| string $newAnimalId value of new animal id
	 * @throws \rangeException if $newAnimalId is not positive
	 * @throws \TypeError if animal id is not valid
	 */
	public function setAnimalId($newAnimalId): void {
		try {
			$uuid = self::validateUuid($newAnimalId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the animal id
		$this->animalId = $uuid;
	}

	/**
	 * accessor method for animal profile id
	 *
	 * @return Uuid value of animal profile id
	 */
	public function getAnimalProfileId(): Uuid {
		return ($this->animalProfileId);
	}

	/**
	 * mutator method for animal profile id
	 *
	 * @param Uuid| string $newAnimalProfileId value of new animal id
	 * @throws \rangeException if $newAnimalProfileId  is not positive
	 * @throws \TypeError if animal profile id is not valid
	 **/
	public function setAnimalProfileId($newAnimalProfileId): void {
		try {
			$uuid = self::validateUuid($newAnimalProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the animal id
		$this->animalProfileId = $uuid;
	}

	/**
	 * accessor method for animal color
	 *
	 * @return string value of animal color
	 */
	public function getAnimalColor(): string {
		return ($this->animalColor);
	}

	/**
	 * mutator method for animal color
	 *
	 * @param string $newAnimalColor new value of animal color
	 * @throws \InvalidArgumentException if $newAnimalColor is not a string or is insecure
	 * @throws \RangeException if $newAnimalColor  is > 25 characters
	 * @throws \TypeError if $newAnimalColor is not a string
	 **/
	public function setAnimalColor($newAnimalColor): void {
		// verify the animal color description string is secure
		$newAnimalColor = trim($newAnimalColor);
		$newAnimalColor = filter_var($newAnimalColor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAnimalColor) === true) {
			throw(new \InvalidArgumentException("Animal color description is empty or insecure"));
		}

		// verify the animal color description will fit in the database
		if(strlen($newAnimalColor) > 25) {
			throw(new \RangeException("animal color description is too long"));
		}

		// store the author name
		$this->animalColor = $newAnimalColor;
	}

	/**
	 * accessor method for animal date
	 *
	 * @return \DateTime value of animal last seen
	 */
	public function getAnimalDate(): \DateTime {
		return ($this->animalDate);
	}

	public function setAnimalDate($newAnimalDate = null): void {
		//base case: if the animal date is empty, use the current date and time
		if($newAnimalDate === null) {
			$this->animalDate = new \DateTime();
			return;
		}

		//store the animal using ValidateDate trait
		try {
			$newAnimalDate = self::validateDateTime($newAnimalDate);
		} catch(\InvalidArgumentException | \RangeException | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->animalDate = $newAnimalDate;
	}

	/**
	 * accessor method for animal description
	 *
	 * @return string description of animal
	 */
	public function getAnimalDescription(): string {
		return ($this->animalDescription);
	}

	public function setAnimalDescription(string $newAnimalDescription): void {
		//verify animal description is secure
		$newAnimalDescription = trim($newAnimalDescription);
		$newAnimalDescription = filter_var($newAnimalDescription, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAnimalDescription) === true) {
			throw(new \InvalidArgumentException("Animal description content is empty or insecure"));
		}

		// verify the animal description content will fit in the database
		if(strlen($newAnimalDescription) > 250) {
			throw(new \RangeException("Animal description content is too long. Limit 250 characters"));
		}

		// store the animal description
		$this->animalDescription = $newAnimalDescription;
	}

	/**
	 * accessor method for animal gender
	 *
	 * @return string value of animal gender
	 */
	public function getAnimalGender(): string {
		return ($this->animalGender);
	}

	/**
	 * mutator method for animal gender
	 *
	 * @param string $newAnimalGender new value of animal gender
	 * @throws \InvalidArgumentException if $newAnimalgender is not a string or is insecure
	 * @throws \RangeException if $newAnimalGender  is > 7 characters
	 * @throws \TypeError if $newAnimalGender is not a string
	 **/
	public function setAnimalGender($newAnimalGender): void {
		// verify the animal gender description string is secure
		$newAnimalGender = trim($newAnimalGender);
		$newAnimalGender = filter_var($newAnimalGender, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAnimalGender) === true) {
			throw(new \InvalidArgumentException("Animal gender description is empty or insecure."));
		}

		// verify the animal color description will fit in the database
		if(strlen($newAnimalGender) > 7) {
			throw(new \RangeException("animal color description is too long"));
		}

		// store the author name
		$this->animalGender = $newAnimalGender;
	}

	/**
	 * accessor method for animal image url
	 *
	 * @return string value of image URL
	 */
	public function getAnimalImageUrl(): string {
		return ($this->animalImageUrl);
	}

	/**
	 * mutator method for animal image url
	 *
	 * @param string $newAnimalImageUrl new value of url location of animal picture
	 * @throws \InvalidArgumentException if $newAnimalImageUrl is not a string or insecure
	 * @throws \RangeException if $newAnimalImageUrl is > 500 characters
	 * @throws \TypeError if $newAnimalImageUrl is not a string
	 **/
	public function setAnimalImageUrl(string $newAnimalImageUrl): void {
		// verify the animal picture link is secure
		$newAnimalImageUrl = trim($newAnimalImageUrl);
		$newAnimalImageUrl = filter_var($newAnimalImageUrl, FILTER_SANITIZE_URL);
		if(empty($newAnimalImageUrl) === true) {
			throw(new \InvalidArgumentException("animal picture link is empty or insecure"));
		}

		// verify the animal image url link will fit in the database
		if(strlen($newAnimalImageUrl) > 500) {
			throw(new \RangeException("animal picture link is too long. limit 500 characters"));
		}

		// store the animal picture link
		$this->animalImageUrl = $newAnimalImageUrl;
	}

	/**
	 * accessor method for animal location
	 *
	 * @return string value of animal location
	 */
	public function getAnimalLocation(): string {
		return ($this->animalLocation);
	}

	/**
	 * mutator method for animal location
	 *
	 * @param string $newAnimalLocation new value of animal location
	 * @throws \InvalidArgumentException if $newAnimalLocation is not a string or insecure
	 * @throws \RangeException if $newAnimalLocation is > 200 characters
	 * @throws \TypeError if $newAnimalLocation is not a string
	 **/
	public function setAnimalLocation(string $newAnimalLocation): void {
		// verify the animal location description is secure
		$newAnimalLocation = trim($newAnimalLocation);
		$newAnimalLocation = filter_var($newAnimalLocation, FILTER_SANITIZE_URL);
		if(empty($newAnimalLocation) === true) {
			throw(new \InvalidArgumentException("animal location description is empty or insecure"));
		}

		// verify the animal location description will fit in the database
		if(strlen($newAnimalLocation) > 200) {
			throw(new \RangeException("animal location description is too long. Limit 200 characters"));
		}

		// store the animal location description
		$this->animalLocation = $newAnimalLocation;
	}

	/**
	 * accessor method for animal name
	 *
	 * @return string value of animal name
	 */
	public function getAnimalName(): string {
		return ($this->animalName);
	}

	/**
	 * mutator method for animal Name
	 *
	 * @param string $newAnimalName new value of animal name
	 * @throws \InvalidArgumentException if $newAnimalName is not a string or insecure
	 * @throws \RangeException if $newAnimalName is > 100 characters
	 * @throws \TypeError if $newAnimalName is not a string
	 **/
	public function setAnimalName(string $newAnimalName): void {
		// verify the animal name is secure
		$newAnimalName = trim($newAnimalName);
		$newAnimalName = filter_var($newAnimalName, FILTER_SANITIZE_URL);
		if(empty($newAnimalName) === true) {
			throw(new \InvalidArgumentException("animal name is empty or insecure. Type unknown if unknown"));
		}

		// verify the animal name will fit in the database
		if(strlen($newAnimalName) > 100) {
			throw(new \RangeException("animal name text is too long. Limit 100 characters"));
		}

		// store the animal name
		$this->animalName = $newAnimalName;
	}

	/**
	 * accessor method for animal species
	 *
	 * @return string value of animal species
	 */
	public function getAnimalSpecies(): string {
		return ($this->animalSpecies);
	}

	/**
	 * mutator method for animal species (dog or cat)
	 *
	 * @param string $newAnimalSpecies new value of animal species
	 * @throws \InvalidArgumentException if $newAnimalSpecies is not a string or insecure
	 * @throws \RangeException if $newAnimalSpecies is > 3 characters
	 * @throws \TypeError if $newAnimalSpecies is not a string
	 **/
	public function setAnimalSpecies(string $newAnimalSpecies): void {
		// verify the animal species input is secure
		$newAnimalSpecies = trim($newAnimalSpecies);
		$newAnimalSpecies = filter_var($newAnimalSpecies, FILTER_SANITIZE_URL);
		if(empty($newAnimalSpecies) === true) {
			throw(new \InvalidArgumentException("animal species is empty or insecure. Type unknown if unknown"));
		}

		// verify the animal species input will fit in the database
		if(strlen($newAnimalSpecies) > 3) {
			throw(new \RangeException("animal species text is too long. Limit 3 characters. Dog or Cat."));
		}

		// store the animal species
		$this->animalSpecies = $newAnimalSpecies;
	}

	/**
	 * accessor method for animal status
	 *
	 * @return string value of animal status
	 */
	public function getAnimalStatus(): string {
		return ($this->animalStatus);
	}

	/**
	 * mutator method for animal status (lost, found, reunited)
	 *
	 * @param string $newAnimalStatus new value of animal status
	 * @throws \InvalidArgumentException if $newAnimalStatus is not a string or insecure
	 * @throws \RangeException if $newAnimalStatus is > 8 characters
	 * @throws \TypeError if $newAnimalStatus is not a string
	 **/
	public function setAnimalStatus(string $newAnimalStatus): void {
		// verify the animal status input is secure
		$newAnimalStatus = trim($newAnimalStatus);
		$newAnimalStatus = filter_var($newAnimalStatus, FILTER_SANITIZE_URL);
		if(empty($newAnimalStatus) === true) {
			throw(new \InvalidArgumentException("animal status is empty or insecure."));
		}

		// verify the animal species input will fit in the database
		if(strlen($newAnimalStatus) > 8) {
			throw(new \RangeException("animal status text is too long. Limit 8 characters."));
		}

		// store the animal status
		$this->animalStatus = $newAnimalStatus;
	}

	/**
	 * inserts lest pet into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws\PDOException when mySQL related errors occur
	 *
	 * @throws\TypeError if $pdo is not a PDO connection object.*
	 **/

	public function insert(\PDO $pdo): void {
		//create query template
		$query = "INSERT INTO animal(animalId, animalProfileId, animalColor, animalDate, animalDescription, animalGender, animalImageUrl, animalLocation, animalName, animalSpecies, animalStatus) VALUES(:animalId, :animalProfileId, :animalColor, :animalDate, :animalDescription, :animalGender, :animalImageUrl, :animalLocation, :animalName, :animalSpecies, :animalStatus)";
		$statement = $pdo->prepare($query);

		//bind the member variable to the placeholders in template
		$formattedDate = $this->animalDate->format("Y-m-d H:i:s.u");
		$parameters = ["animalId" => $this->animalId->getBytes(), "animalProfileId" => $this->animalProfileId->getBytes(), "animalColor" => $this->animalColor, "animalDate" => $formattedDate, "animalDescription" => $this->animalDescription, "animalGender" => $this->animalGender, "animalImageUrl" => $this->animalImageUrl, "animalLocation" => $this->animalLocation, "animalName" => $this->animalName, "animalSpecies" => $this->animalSpecies, "animalStatus" => $this->animalStatus];
		$statement->execute($parameters);
	}

	/**
	 * updates an animal post in mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo): void {
		//create query template
		$query = "UPDATE animal SET animalProfileID = :animalProfileId, animalColor = :animalColor, animalDate = :animalDate, animalDescription = :animalDescription, animalDescription = :animalGender, animalImageUrl = :animalImageUrl, animalLocation = :animalLocation, animalName = :animalName, animalSpecies = :animalSpecies, animalStatus = :animalStatus WHERE  animalId = :animalId";
		$statement = $pdo->prepare($query);


		//bind member variables to the placeholders in template
		$formattedDate = $this->animalDate->format("Y-m-d H:i:s.u");
		$parameters = ["animalId" => $this->animalId->getBytes(), "animalProfileId" => $this->animalProfileId->getBytes(), "animalColor" => $this->animalColor, "animalDate" => $formattedDate, "animalDescription" => $this->animalDescription, "animalGender" => $this->animalGender, "animalImageUrl" => $this->animalImageUrl, "animalLocation" => $this->animalLocation, "animalName" => $this->animalName, "animalSpecies" => $this->animalSpecies, "animalStatus" => $this->animalStatus];
		$statement->execute($parameters);
	}

	/**
	 * deletes an animal posting from mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {
		//create query template
		$query = "DELETE FROM animal WHERE animalId = :animalId";
		$statement = $pdo->prepare($query);

		//bind member variables into placeholders in the template
		$parameters = ["animalId" => $this->animalId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets Animal by animalId (the primary key)
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $animalId is animal id
	 * @return Animal|null Animal found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/

	public static function getAnimalByAnimalId(\PDO $pdo, $animalId): ?Animal {
		//sanitize animalID before searching
		try {
			$animalId = self::validateUuid($animalId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT animalId, animalProfileId, animalColor, animalDate, animalDescription, animalGender, animalImageUrl, animalLocation, animalName, animalSpecies, animalStatus FROM animal WHERE animalId = :animalId";
		$statement = $pdo->prepare($query);

		//bind the animal id to the placeholder in the template.
		$parameters = ["animalId" => $animalId->getBytes()];
		$statement->execute($parameters);

		//grab the animal from mySQL.
		try{
			$animal = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$animal = new Animal($row["animalId"], $row["animalProfileId"], $row["animalColor"], $row["animalDate"], $row["animalDescription"], $row["animalGender"], $row["animalImageUrl"], $row["animalLocation"], $row["animalName"], $row["animalSpecies"], $row["animalStatus"]);
				}
			}catch (\Exception $exception) {
				//if the row could not be converted, rethrow it.
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($animal);
		}

	/**
	 * get animals by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid\string $animalProfileId is profile id to search by
	 * @return \SplFixedArray  of animals found
	 * @throws \PDOException when mySQl related errors happen
	 * @throws \TypeError when a variable is not correct data type	 *
	 **/
		public static function getAnimalByAnimalProfileID(\PDO $pdo, $animalProfileId): \SplFixedArray {
		//sanitize animalProfileId before searching
		try {
			$animalProfileId = self::validateUuid($animalProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT animalId, animalProfileId, animalColor, animalDate, animalDescription, animalGender, animalImageUrl, animalLocation, animalName, animalSpecies, animalStatus FROM animal WHERE animalProfileId = :animalProfileId";
		$statement = $pdo->prepare($query);


		//bind the animal profile id to the placeholder in template
		$parameters = ["animalProfileId" => $animalProfileId->getBytes()];
		$statement->execute($parameters);

		// build an array of animals by profile
		$animals = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$animal = new Animal ($row["animalId"], $row["animalProfileId"], $row["animalColor"], $row["animalDate"], $row["animalDescription"], $row["animalGender"], $row["animalImageUrl"], $row["animalLocation"], $row["animalName"], $row["animalSpecies"], $row["animalStatus"]);
				$animals[$animals->key()] = $animal;
				$animals->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($animals);
	}

	/**
	 * Get Animals By Color
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $animalColor to search for
	 * @return \SplFixedArray of animals found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getAnimalByAnimalColor(\PDO $pdo, string $animalColor) : \SplFixedArray {
		//sanitize the animal color description before searching
		$animalColor = trim($animalColor);
		$animalColor = filter_var($animalColor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($animalColor) === true) {
			throw(new \PDOException("animal color is invalid."));
		}

			//escape any mySQL wildcards
			$animalColor = str_replace("_", "\\_", str_replace("%", "\\%", $animalColor));

			//create query template
			$query = "SELECT animalId, animalProfileId, animalColor, animalDate, animalDescription, animalGender, animalImageUrl, animalLocation, animalName, animalSpecies, animalStatus FROM animal WHERE animalColor LIKE :animalProfileId";
			$statement = $pdo->prepare($query);

			//bind the animal color to the placeholder in template
			$animalColor = "%$animalColor%";
			$parameters = ["animalColor" => $animalColor];
			$statement->execute($parameters);

			//build an array of animals by color
			$animals = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$animal = new Animal ($row["animalId"], $row["animalProfileId"], $row["animalColor"], $row["animalDate"], $row["animalDescription"], $row["animalGender"], $row["animalImageUrl"], $row["animalLocation"], $row["animalName"], $row["animalSpecies"], $row["animalStatus"]);
					$animals[$animals->key()] = $animal;
					$animal->next();
				} catch(\Exception $exception) {
					//if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($animals);
		}
}




<?php

namespace \Jisbell347\LostPaws;

/*
 * Comment section of the lostpaws.com site. A user will be able to post comments about the animal to contact other people in regards to the status of the animal.
 */

class Comment {
	/**
	 * id for comment, this is the primary key
	 * @var Uuid $commentId
	 **/
	private $commentId;
	/**
	 * id of the animal that receives the comment, this is a foreign key
	 * @var Uuid $commentAnimalId
	 **/
	private $commentAnimalId;
	/**
	 * id of the profile that made the comment, this is a foreign key
	 * @var Uuid $commentProfileId
	 **/
	private $commentProfileId;
	/**
	 * date and time this comment was made, in a PHP DateTime Object
	 * @var \DateTime $commentDate
	 */
	private $commentDate;
	/**
	 *textual content of this comment
	 * @var string $commentText
	 **/
	private $commentText;


	public function __construct($newCommentId, $newCommentAnimalId, $newCommentProfileId, $newCommentDate = null, string $newCommentText) {
		try{
			$this->setCommentId($newCommentId);
			$this->setCommentAnimalId($newCommentAnimalId);
			$this->setCommentProfileId($newCommentProfileId);
			$this->setCommentDate($newCommentDate);
			$this->setCommentText($newCommentText);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for comment id
	 *
	 * @return Uuid value of comment id
	**/
	/**
	 * @return Uuid
	 */
	public function getCommentId(): Uuid {
		return($this->commentId);
	}

	/**
	 * mutator method for comment id
	 *
	 * @param Uuid|string $newCommentId new value of comment id
	 * @throws \RangeException if $newCommentId is not positive
	 * @throws \TypeError if $newCommentId is not a uuid or string
	**/
	public function setCommentId($newCommentId) : void {
		try {
			$uuid = self::validateUuid($newCommentId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		//convert and store the comment id
		$this->commentId = $uuid;
	}

	/**
	 * accessor method for comment animal id
	 *
	 * @return Uuid value of comment animal id
	 */
	public function getCommentAnimalId(): Uuid {
		return($this->commentAnimalId);
	}

	/**
	 * mutator method for comment animal id
	 *
	 * @param Uuid|string $newCommentAnimalId new value of comment animal id
	 * @throws \RangeException if $newCommentAnimalId is not positive
	 * @throws \TypeError if $newCommentAnimalId is not a uuid or string
	**/
	public function setCommentAnimalId($newCommentAnimalId) : void {
		try {
			$uuid = self::validateUuid($newCommentAnimalId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comment animal id
		$this->commentAnimalId = $uuid;
	}

	/**
	 * accessor method for commentProfileId
	 *
	 * @return Uuid value of comment profile id
	**/
	public function getCommentProfileId(): Uuid {
		return($this->commentProfileId);
	}

	/**
	 * mutator method for comment profile id
	 *
	 * @param Uuid|string $newCommentProfileId new value of comment profile id
	 * @throws \RangeException if $newCommentProfileId is not positive
	 * @throws \TypeError if $newCommentProfileId is not a uuid or string
	**/
	public function setCommentProfileId($newCommentProfileId) : void {
		try {
			$uuid = self::validateUuid($newCommentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comment animal id
		$this->commentProfileId = $uuid;
	}

	/**
	 * accessor method for comment date
	 *
	 * @return \DateTime value of comment date
	**/
	public function getCommentDate() : \DateTime {
		return($this->commentDate);
	}

	/**
	 * mutator method for comment date
	 *
	 * @param \DateTime|string|null $newCommentDate comment date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newCommentDate is not a valid object or string
	 * @throws \RangeException if $newCommentDate is a date that does not exist
	**/
	public function setCommentDate($newCommentDate = null) : void {
		//base case: if the date is null, use the current date and time
		if($newCommentDate === null) {
			$this->commentDate = new \DateTime();
			return;
		}

		//store the like date using the ValidateDate trait
		try {
			$newCommentDate = self::validateDateTime($newCommentDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->commentDate = $newCommentDate;
	}

	/**
	 * accessor method for comment text
	 *
	 * @return string value of comment text
	 */
	public function getCommentText() : string {
		return($this->commentText);
	}

	/**
	 * mutator method for comment text
	 *
	 * @param string $newCommentText new value of comment text
	 * @throws \InvalidArgumentException if $newCommentText is not a string or insecure
	 * @throws \RangeException if $newCommentText is > 1000 characters
	 * @throws \TypeError if $newCommentText is not a string
	**/
	public function setCommentText(string $newCommentText) : void {
		//verify comment text is secure
		$newCommentText = trim($newCommentText);
		$newCommentText = filter_var($newCommentText, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentText) === true) {
			throw(new \InvalidArgumentException("comment text is empty or insecure"));
		}

		//verify the comment content will fit in the database
		if(strlen($newCommentText) > 1000) {
			throw(new \RangeException("comment text is too large"));
		}

		//store the comment text
		$this->commentText = $newCommentText;
	}

}
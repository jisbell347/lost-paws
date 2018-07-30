<?php

namespace \namespace\here;

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

}
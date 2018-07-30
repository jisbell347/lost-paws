<?php

namespace \namespace\here ;
/*
 * Profile section of the lostpaws.com site. After logging in with oAuth, a user profile is created which displays name and contact info.
 */


/*
 *
 *
    public function __construct($newUserId, string $newUserEmail, string $newUserHash, string $newUserName) {
		try {
			$this->setUserId($newUserId);
			$this->setUserEmail($newUserEmail);
			$this->setUserHash($$newUserHash);
			$this->setUserName($newUserName);
		} catch (\InvalidArgumentException | \RangeException | \TypeError | \Exception $e) {
			// rethrow uncought by the mutators exceptions
			throw(new $exceptionType($e->getMessage(), 0, $e));
		}
	}

 */

class Profile {
	private $profileId;
	private $profileOAuthId;
	private $profileAccessToken;
	private $profileEmail;
	private $profileName;
	private $profilePhone;

	/*
	 * TODO: find out about access token
	 */
	public function __construct($newProfileId, int $newProfileOAuthId, ?string $newProfileAccessToken,
										 string $newProfileEmail, string $newProfileName, ?string $newProfilePhone) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileOAuthId($newProfileOAuthId);
			$this->setProfileAccessToken($newProfileAccessToken);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileName($newProfileName);
			$this->setProfilePhone($newProfilePhone);
		} cath () {

		}
	}





}
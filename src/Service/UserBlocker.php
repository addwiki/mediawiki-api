<?php

namespace Mediawiki\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\User;

/**
 * @author Adam Shorland
 */
class UserBlocker {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var array of params allowed in a block calls $options parameter
	 */
	private $allowedBlockOptions = array(
		'anononly', // Block anonymous users only (i.e. disable anonymous edits for this IP)
		'nocreate', // Prevent account creation
		'autoblock', // Automatically block the last used IP address, and any subsequent IP addresses they try to login from
		'noemail', // Prevent user from sending email through the wiki. (Requires the "blockemail" right.)
		'hidename', // Hide the username from the block log. (Requires the "hideuser" right.)
		'allowusertalk', // Allow the user to edit their own talk page (depends on $wgBlockAllowsUTEdit)
		'reblock', // If the user is already blocked, overwrite the existing block
		'watchuser', // Watch the user/IP's user and talk pages
	);

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.3
	 *
	 * @param User|string $user
	 * @param string $expiry
	 * @param string $reason
	 * @param string[] $options @see $this->allowedBlockOptions
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function block( $user, $expiry = 'never', $reason = '', $options = array() ) {
		if( !$user instanceof User && !is_string( $user ) ) {
			throw new InvalidArgumentException( '$user must be either a string or User object' );
		}

		if( $user instanceof User ) {
			$user = $user->getName();
		}

		$params = array(
			'user' => $user,
			'token' => $this->api->getToken( 'block' ),
		);
		if( $expiry !== 'never' ) {
			$params['expiry'] = $expiry;
		}
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		foreach( $options as $option ) {
			if( in_array( $option, $this->$allowedBlockOptions ) ) {
				$params[$option] = '';
			}
		}

		$this->api->postAction( 'block', $params );
		return true;
	}

}

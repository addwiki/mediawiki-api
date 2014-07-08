<?php

namespace Mediawiki\Api\Service;

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
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.3
	 *
	 * @param User $user
	 * @param string $expiry
	 * @param string $reason
	 * @param string[] $options
	 *          anononly      - Block anonymous users only (i.e. disable anonymous edits for this IP)
	 *          nocreate      - Prevent account creation
	 *          autoblock     - Automatically block the last used IP address, and any subsequent IP addresses they try to login from
	 *          noemail       - Prevent user from sending email through the wiki. (Requires the "blockemail" right.)
	 *          hidename      - Hide the username from the block log. (Requires the "hideuser" right.)
	 *          allowusertalk - Allow the user to edit their own talk page (depends on $wgBlockAllowsUTEdit)
	 *          reblock       - If the user is already blocked, overwrite the existing block
	 *          watchuser     - Watch the user/IP's user and talk pages
	 *
	 * @return bool
	 */
	public function block( User $user, $expiry = 'never', $reason = '', $options = array() ) {
		$params = array(
			'user' => $user->getName(),
			'token' => $this->api->getToken( 'block' ),
		);
		if( $expiry !== 'never' ) {
			$params['expiry'] = $expiry;
		}
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		//todo think of a nicer way to do the below options
		if( in_array( 'anononly', $options ) ) {
			$params['anononly'] = '';
		}
		if( in_array( 'nocreate', $options ) ) {
			$params['nocreate'] = '';
		}
		if( in_array( 'autoblock', $options ) ) {
			$params['autoblock'] = '';
		}
		if( in_array( 'noemail', $options ) ) {
			$params['noemail'] = '';
		}
		if( in_array( 'hidename', $options ) ) {
			$params['hidename'] = '';
		}
		if( in_array( 'allowusertalk', $options ) ) {
			$params['allowusertalk'] = '';
		}
		if( in_array( 'reblock', $options ) ) {
			$params['reblock'] = '';
		}
		if( in_array( 'watchuser', $options ) ) {
			$params['watchuser'] = '';
		}

		$this->api->postAction( 'block', $params );
		return true;
	}

}

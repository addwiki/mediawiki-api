<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class BlockOptions {

	/**
	 * @var string
	 */
	private $expiry = 'never';
	/**
	 * @var string
	 */
	private $reason = '';
	/**
	 * @var bool Block anonymous users only (i.e. disable anonymous edits for this IP)
	 */
	private $anononly = false;
	/**
	 * @var bool Prevent account creation
	 */
	private $nocreate = false;
	/**
	 * @var bool Automatically block the last used IP address, and any subsequent IP addresses they try to login from
	 */
	private $autoblock = false;
	/**
	 * @var bool Prevent user from sending email through the wiki. (Requires the "blockemail" right.)
	 */
	private $noemail = false;
	/**
	 * @var bool Hide the username from the block log. (Requires the "hideuser" right.)
	 */
	private $hidename = false;
	/**
	 * @var bool Allow the user to edit their own talk page (depends on $wgBlockAllowsUTEdit)
	 */
	private $allowusertalk = false;
	/**
	 * @var bool If the user is already blocked, overwrite the existing block
	 */
	private $reblock = false;
	/**
	 * @var bool Watch the user/IP's user and talk pages
	 */
	private $watchuser = false;

	/**
	 * @since 0.4
	 *
	 * @param boolean $allowusertalk
	 *
	 * @return $this
	 */
	public function setAllowusertalk( $allowusertalk ) {
		$this->allowusertalk = $allowusertalk;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getAllowusertalk() {
		return $this->allowusertalk;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $anononly
	 *
	 * @return $this
	 */
	public function setAnononly( $anononly ) {
		$this->anononly = $anononly;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getAnononly() {
		return $this->anononly;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $autoblock
	 *
	 * @return $this
	 */
	public function setAutoblock( $autoblock ) {
		$this->autoblock = $autoblock;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getAutoblock() {
		return $this->autoblock;
	}

	/**
	 * @since 0.4
	 *
	 * @param string $expiry
	 *
	 * @return $this
	 */
	public function setExpiry( $expiry ) {
		$this->expiry = $expiry;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return string
	 */
	public function getExpiry() {
		return $this->expiry;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $hidename
	 *
	 * @return $this
	 */
	public function setHidename( $hidename ) {
		$this->hidename = $hidename;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getHidename() {
		return $this->hidename;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $nocreate
	 *
	 * @return $this
	 */
	public function setNocreate( $nocreate ) {
		$this->nocreate = $nocreate;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getNocreate() {
		return $this->nocreate;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $noemail
	 *
	 * @return $this
	 */
	public function setNoemail( $noemail ) {
		$this->noemail = $noemail;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getNoemail() {
		return $this->noemail;
	}

	/**
	 * @since 0.4
	 *
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function setReason( $reason ) {
		$this->reason = $reason;
		return $this;
	}

	/**
	 * @since 0.4
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $reblock
	 *
	 * @return $this
	 */
	public function setReblock( $reblock ) {
		$this->reblock = $reblock;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getReblock() {
		return $this->reblock;
	}

	/**
	 * @since 0.4
	 *
	 * @param boolean $watchuser
	 *
	 * @return $this
	 */
	public function setWatchuser( $watchuser ) {
		$this->watchuser = $watchuser;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return boolean
	 */
	public function getWatchuser() {
		return $this->watchuser;
	}


} 
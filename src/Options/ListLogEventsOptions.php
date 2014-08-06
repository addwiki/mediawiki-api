<?php

namespace Mediawiki\Api\Options;

/**
 * @author Thomas Arrow
 */
class ListLogEventsOptions {
	/**
	 * @var string
	 */
	private $type = '';
	/**
	 * @var string
	 */
	private $action = '';
	/**
	 * @var string
	 */
	private $start = '';
	/**
	 * @var string
	 */
	private $end = '';
	/**
	 * @var string
	 */
	private $user = '';
	/**
	 * @var string
	 */
	private $title = '';
	/**
	 * @var string
	 */
	private $namespace = null;

	/**
	 * @param string $action
	 *
	 * @return $this
	 * @since 0.4
	 */
	public function setAction($action)
	{
		$this->action = $action;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @param string $end
	 *
	 * @return $this
	 * @since 0.4
	 */
	public function setEnd($end)
	{
		$this->end = $end;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getEnd()
	{
		return $this->end;
	}

	/**
	 * @param string $namespace
	 *
	 * @return $this
	 * @since 0.4
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @param string $start
	 *
	 * @return $this
	 * @since 0.4
	 */
	public function setStart($start)
	{
		$this->start = $start;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getStart()
	{
		return $this->start;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 * @since 0.4
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $type
	 * @return $this
	 * @since 0.4
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $user
	 * @return $this
	 * @since 0.4
	 */
	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return string
	 * @since 0.4
	 */
	public function getUser()
	{
		return $this->user;
	}

}


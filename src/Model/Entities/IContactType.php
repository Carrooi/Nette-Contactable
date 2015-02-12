<?php

namespace Carrooi\Contactable\Model\Entities;

/**
 *
 * @author David Kudera
 */
interface IContactType
{


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);


	/**
	 * @return string
	 */
	public function getTitle();


	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title);


	/**
	 * @return bool
	 */
	public function hasPattern();


	/**
	 * @return string
	 */
	public function getPattern();


	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function setPattern($pattern);


	/**
	 * @return bool
	 */
	public function hasUrl();


	/**
	 * @return string
	 */
	public function getUrl();


	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url);

}

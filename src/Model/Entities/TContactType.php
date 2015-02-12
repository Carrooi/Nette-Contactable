<?php

namespace Carrooi\Contactable\Model\Entities;

/**
 *
 * @author David Kudera
 */
trait TContactType
{


	/**
	 * @ORM\Column(type="string", length=15, nullable=false, unique=true)
	 * @var string
	 */
	private $name;


	/**
	 * @ORM\Column(type="string", length=30, nullable=false)
	 * @var string
	 */
	private $title;


	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 * @var string
	 */
	private $pattern;


	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 * @var string
	 */
	private $url;


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function hasPattern()
	{
		return $this->pattern !== null;
	}


	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}


	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function hasUrl()
	{
		return $this->url !== null;
	}


	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

}

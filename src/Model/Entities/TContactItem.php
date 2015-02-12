<?php

namespace Carrooi\Contactable\Model\Entities;

use Carrooi\Contactable\InvalidArgumentException;
use Carrooi\Contactable\InvalidStateException;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Validators;

/**
 *
 * @author David Kudera
 */
trait TContactItem
{


	/**
	 * @ORM\ManyToOne(targetEntity="\Carrooi\Contactable\Model\Entities\IContactType")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \Carrooi\Contactable\Model\Entities\IContactType
	 */
	private $contactType;


	/**
	 * @ORM\Column(type="string", length=50, nullable=false)
	 * @var string
	 */
	private $value;


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function getContactType()
	{
		return $this->contactType;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @return $this
	 */
	public function setContactType(IContactType $type)
	{
		$this->contactType = $type;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value)
	{
		if (!$this->validateValue($value)) {
			throw new InvalidArgumentException('Could not add contact value '. $value. ' to contact type '. $this->getContactType()->getName());
		}

		$this->value = $value;
		return $this;
	}


	/**
	 * @param string $value
	 * @return bool
	 */
	public function validateValue($value)
	{
		if (!$this->getContactType()) {
			throw new InvalidStateException('Please, set contact type for contact value '. $value. '.');
		}

		if (!$this->getContactType()->hasPattern()) {
			return true;
		}

		return Validators::is($value, 'pattern:'. $this->getContactType()->getPattern());
	}

}

<?php

namespace Carrooi\Contactable\Model\Entities;

/**
 *
 * @author David Kudera
 */
interface IContactItem
{


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function getContactType();


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @return $this
	 */
	public function setContactType(IContactType $type);


	/**
	 * @return string
	 */
	public function getValue();


	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value);


	/**
	 * @param string $value
	 * @return bool
	 */
	public function validateValue($value);

}

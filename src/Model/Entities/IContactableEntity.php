<?php

namespace Carrooi\Contactable\Model\Entities;

/**
 *
 * @author David Kudera
 */
interface IContactableEntity
{


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem[]
	 */
	public function getContacts();


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $contactItem
	 * @return $this
	 */
	public function addContact(IContactItem $contactItem);


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $contactItem
	 * @return $this
	 */
	public function removeContact(IContactItem $contactItem);

}

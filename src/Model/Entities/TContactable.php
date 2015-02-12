<?php

namespace Carrooi\Contactable\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @author David Kudera
 */
trait TContactable
{


	/** @var \Doctrine\Common\Collections\ArrayCollection */
	private $contacts;


	private function initContacts()
	{
		if ($this->contacts === null) {
			$this->contacts = new ArrayCollection;
		}
	}


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem[]
	 */
	public function getContacts()
	{
		$this->initContacts();
		return $this->contacts->toArray();
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $contactItem
	 * @return $this
	 */
	public function addContact(IContactItem $contactItem)
	{
		$this->initContacts();
		$this->contacts->add($contactItem);
		return $this;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $contactItem
	 * @return $this
	 */
	public function removeContact(IContactItem $contactItem)
	{
		$this->initContacts();
		$this->contacts->removeElement($contactItem);
		return $this;
	}

}

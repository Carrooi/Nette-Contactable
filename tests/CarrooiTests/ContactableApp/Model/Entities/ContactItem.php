<?php

namespace CarrooiTests\ContactableApp\Model\Entities;

use Carrooi\Contactable\Model\Entities\IContactItem;
use Carrooi\Contactable\Model\Entities\TContactItem;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class ContactItem extends BaseEntity implements IContactItem
{


	use Identifier;

	use TContactItem;


	/**
	 * @ORM\ManyToOne(targetEntity="\CarrooiTests\ContactableApp\Model\Entities\User")
	 * @var \CarrooiTests\ContactableApp\Model\Entities\User
	 */
	private $user;


	/**
	 * @return bool
	 */
	public function hasUser()
	{
		return $this->user !== null;
	}


	/**
	 * @return \CarrooiTests\ContactableApp\Model\Entities\User
	 */
	public function getUser()
	{
		return $this->user;
	}


	/**
	 * @param \CarrooiTests\ContactableApp\Model\Entities\User $user
	 * @return $this
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
		return $this;
	}

}

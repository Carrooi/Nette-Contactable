<?php

namespace CarrooiTests\ContactableApp\Model\Facade;

use CarrooiTests\ContactableApp\Model\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class UsersFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;


	/**
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @return \CarrooiTests\ContactableApp\Model\Entities\User
	 */
	public function create()
	{
		$user = new User;

		$this->em->persist($user)->flush();

		return $user;
	}

}

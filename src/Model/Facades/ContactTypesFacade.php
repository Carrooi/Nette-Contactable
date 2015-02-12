<?php

namespace Carrooi\Contactable\Model\Facades;

use Carrooi\Contactable\Model\Entities\IContactType;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class ContactTypesFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $dao;

	/** @var string */
	private $class;


	/**
	 * @param string $class
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct($class, EntityManager $em)
	{
		$this->em = $em;
		$this->class = $class;

		$this->dao = $em->getRepository('Carrooi\Contactable\Model\Entities\IContactType');
	}


	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function createNew()
	{
		$class = $this->getClass();
		return new $class;
	}


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactType[]
	 */
	public function findAll()
	{
		return $this->dao->findAll();
	}


	/**
	 * @return array
	 */
	public function findAllNames()
	{
		return $this->dao->findPairs([], 'name', [], 'id');
	}


	/**
	 * @param int $id
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function findOneById($id)
	{
		return $this->dao->findOneBy([
			'id' => $id,
		]);
	}


	/**
	 * @param string $name
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function findOneByName($name)
	{
		return $this->dao->findOneBy([
			'name' => $name,
		]);
	}


	/**
	 * @param string $name
	 * @param string $title
	 * @param array $values
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	public function create($name, $title, array $values = [])
	{
		$type = $this->createNew();
		$type->setName($name);
		$type->setTitle($title);

		if (array_key_exists('pattern', $values)) {
			$type->setPattern($values['pattern']);
		}

		if (array_key_exists('url', $values)) {
			$type->setUrl($values['url']);
		}

		$this->em->persist($type)->flush();

		return $type;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @param array $values
	 * @return $this
	 */
	public function update(IContactType $type, array $values)
	{
		if (array_key_exists('name', $values)) {
			$type->setName($values['name']);
		}

		if (array_key_exists('title', $values)) {
			$type->setTitle($values['title']);
		}

		if (array_key_exists('pattern', $values)) {
			$type->setPattern($values['pattern']);
		}

		if (array_key_exists('url', $values)) {
			$type->setUrl($values['url']);
		}

		$this->em->persist($type)->flush();

		return $this;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @return $this
	 */
	public function remove(IContactType $type)
	{
		$this->em->remove($type)->flush();
		return $this;
	}

}

<?php

namespace Carrooi\Contactable\Model\Facades;

use Carrooi\Contactable\ContactableAssociationException;
use Carrooi\Contactable\Model\Entities\IContactableEntity;
use Carrooi\Contactable\Model\Entities\IContactItem;
use Carrooi\Contactable\Model\Entities\IContactType;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class ContactItemsFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $dao;

	/** @var \Carrooi\Contactable\Model\Facades\AssociationsManager */
	private $associations;

	/** @var string */
	private $class;


	/**
	 * @param string $class
	 * @param \Kdyby\Doctrine\EntityManager $em
	 * @param \Carrooi\Contactable\Model\Facades\AssociationsManager $associations
	 */
	public function __construct($class, EntityManager $em, AssociationsManager $associations)
	{
		$this->em = $em;
		$this->associations = $associations;
		$this->class = $class;

		$this->dao = $em->getRepository($class);
	}


	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}


	/**
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem
	 */
	public function createNew()
	{
		$class = $this->getClass();
		return new $class;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactableEntity $entity
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @param string $value
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem
	 */
	public function addContact(IContactableEntity $entity, IContactType $type, $value)
	{
		$class = get_class($entity);

		if (!$this->associations->hasAssociation($class)) {
			throw new ContactableAssociationException('Please register contactable association for entity '. $class. '.');
		}

		$item = $this->createNew();
		$item->setContactType($type);
		$item->setValue($value);

		$field = $this->associations->getAssociation($class);

		$this->em->getClassMetadata($this->getClass())->setFieldValue($item, $field, $entity);

		$entity->addContact($item);

		$this->em->persist([
			$item, $entity,
		])->flush();

		return $item;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $item
	 * @param array $values
	 * @return $this
	 */
	public function update(IContactItem $item, array $values)
	{
		if (array_key_exists('contactType', $values)) {
			$item->setContactType($values['contactType']);
		}

		if (array_key_exists('value', $values)) {
			$item->setValue($values['value']);
		}

		$this->em->persist($item)->flush();

		return $this;
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactItem $item
	 * @return $this
	 */
	public function remove(IContactItem $item)
	{
		$metadata = $this->em->getClassMetadata($this->getClass());

		foreach ($this->associations->getAssociations() as $class => $field) {
			$assoc = $metadata->getFieldValue($item, $field);		/** @var \Carrooi\Contactable\Model\Entities\IContactableEntity $assoc */
			$assoc->removeContact($item);
		}

		$this->em->remove($item)->flush();
		return $this;
	}


	/**
	 * @param int $id
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem
	 */
	public function findOneById($id)
	{
		return $this->dao->findOneBy([
			'id' => $id,
		]);
	}


	/**
	 * @param \Carrooi\Contactable\Model\Entities\IContactableEntity $entity
	 * @return \Carrooi\Contactable\Model\Entities\IContactItem[]
	 */
	public function findAllByEntity(IContactableEntity $entity)
	{
		$class = get_class($entity);

		if (!$this->associations->hasAssociation($class)) {
			throw new ContactableAssociationException('Please register contactable association for entity '. $class. '.');
		}

		$field = $this->associations->getAssociation($class);

		return $this->dao->findBy([
			$field => $entity,
		]);
	}

}

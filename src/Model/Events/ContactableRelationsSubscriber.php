<?php

namespace Carrooi\Contactable\Model\Events;

use Carrooi\Contactable\ContactableAssociationException;
use Carrooi\Contactable\Model\Facades\AssociationsManager;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Nette\Object;
use Kdyby\Events\Subscriber;

/**
 *
 * @author David Kudera
 */
class ContactableRelationsSubscriber extends Object implements Subscriber
{


	const ASSOCIATION_FIELD_NAME = 'contacts';


	/** @var \Carrooi\Contactable\Model\Facades\AssociationsManager */
	private $associations;

	/** @var string */
	private $contactItemClass;


	/**
	 * @param string $contactItemClass
	 * @param \Carrooi\Contactable\Model\Facades\AssociationsManager $associations
	 */
	public function __construct($contactItemClass, AssociationsManager $associations)
	{
		$this->contactItemClass = $contactItemClass;
		$this->associations = $associations;
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::loadClassMetadata => 'loadClassMetadata',
		];
	}


	/**
	 * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
	{
		$metadata = $eventArgs->getClassMetadata();			/** @var \Kdyby\Doctrine\Mapping\ClassMetadata $metadata */
		$class = $metadata->getName();

		if ($class === $this->contactItemClass) {
			foreach ($this->associations->getAssociations() as $associationClass => $field) {
				if (!$metadata->hasAssociation($field)) {
					throw new ContactableAssociationException('Found registered contactable association '. $associationClass. ', but there is no associated field in entity '. $class. '.');
				}

				$metadata->setAssociationOverride($field, [
					'type' => ClassMetadataInfo::MANY_TO_ONE,
					'targetEntity' => $associationClass,
					'fieldName' => $field,
					'inversedBy' => self::ASSOCIATION_FIELD_NAME,
					'joinColumn' => [
						'nullable' => true,
					],
				]);
			}

		} elseif (in_array('Carrooi\Contactable\Model\Entities\IContactableEntity', class_implements($class))) {
			if (!$this->associations->hasAssociation($class)) {
				throw new ContactableAssociationException('Found contactable entity '. $class. ' which is not registered in contactable configuration.');
			}

			$metadata->mapOneToMany([
				'targetEntity' => 'Carrooi\Contactable\Model\Entities\IContactItem',
				'fieldName' => self::ASSOCIATION_FIELD_NAME,
				'mappedBy' => $this->associations->getAssociation($class),
			]);
		}
	}

}

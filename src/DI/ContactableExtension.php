<?php

namespace Carrooi\Contactable\DI;

use Carrooi\Contactable\ConfigurationException;
use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Doctrine\DI\ITargetEntityProvider;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;

/**
 *
 * @author David Kudera
 */
class ContactableExtension extends CompilerExtension implements IEntityProvider, ITargetEntityProvider
{


	/** @var array */
	private $defaults = [
		'contactItemClass' => null,
		'contactTypeClass' => 'Carrooi\Contactable\Model\DefaultEntities\DefaultContactType',
		'associations' => [],
	];


	/** @var string */
	private $contactItemClass;

	/** @var string */
	private $contactTypeClass;


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		if (!$config['contactItemClass']) {
			throw new ConfigurationException('Please set contactItemClass variable in your contactable configuration.');
		}

		if (!class_exists($config['contactItemClass'])) {
			throw new ConfigurationException('Class '. $config['contactItemClass']. ' does not exists.');
		}

		if (!class_exists($config['contactTypeClass'])) {
			throw new ConfigurationException('Class '. $config['contactTypeClass']. ' does not exists.');
		}

		$this->contactItemClass = $config['contactItemClass'];
		$this->contactTypeClass = $config['contactTypeClass'];

		$associations = $builder->addDefinition($this->prefix('facade.associations'))
			->setClass('Carrooi\Contactable\Model\Facades\AssociationsManager');

		foreach ($config['associations'] as $class => $field) {
			$associations->addSetup('addAssociation', [$class, $field]);
		}

		$builder->addDefinition($this->prefix('event.relations'))
			->setClass('Carrooi\Contactable\Model\Events\ContactableRelationsSubscriber')
			->setArguments([$this->contactItemClass])
			->addTag(EventsExtension::TAG_SUBSCRIBER);

		$builder->addDefinition($this->prefix('facade.types'))
			->setClass('Carrooi\Contactable\Model\Facades\ContactTypesFacade')
			->setArguments([$this->contactTypeClass]);

		$builder->addDefinition($this->prefix('facade.items'))
			->setClass('Carrooi\Contactable\Model\Facades\ContactItemsFacade')
			->setArguments([$this->contactItemClass]);
	}


	/**
	 * @return array
	 */
	function getEntityMappings()
	{
		$mappings = [
			'Carrooi\Contactable\Model\Entities' => __DIR__. '/../Model/Entities',
		];

		if ($this->contactTypeClass === 'Carrooi\Contactable\Model\DefaultEntities\DefaultContactType') {
			$mappings['Carrooi\Contactable\Model\DefaultEntities'] = __DIR__. '/../Model/DefaultEntities';
		}

		return $mappings;
	}


	/**
	 * @return array
	 */
	function getTargetEntityMappings()
	{
		return [
			'Carrooi\Contactable\Model\Entities\IContactItem' => $this->contactItemClass,
			'Carrooi\Contactable\Model\Entities\IContactType' => $this->contactTypeClass,
		];
	}

}

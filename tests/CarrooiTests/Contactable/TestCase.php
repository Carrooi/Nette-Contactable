<?php

namespace CarrooiTests\Contactable;

use Carrooi\Contactable\Model\Entities\IContactType;
use CarrooiTests\ContactableApp\Model\Entities\User;
use Nette\Configurator;
use Tester\FileMock;
use Tester\TestCase as BaseTestCase;

/**
 *
 * @author David Kudera
 */
class TestCase extends BaseTestCase
{


	/** @var \Nette\DI\Container */
	private $container;

	/** @var \Carrooi\Contactable\Model\Facades\ContactTypesFacade */
	protected $types;

	/** @var \Carrooi\Contactable\Model\Facades\ContactItemsFacade */
	protected $items;

	/** @var \Kdyby\Doctrine\EntityManager */
	protected $em;


	/**
	 * @return \Nette\DI\Container
	 */
	protected function createContainer()
	{
		if (!$this->container) {
			copy(__DIR__. '/../ContactableApp/Model/database', TEMP_DIR. '/database');

			$config = new Configurator;
			$config->setTempDirectory(TEMP_DIR);
			$config->addParameters(['appDir' => __DIR__. '/../ContactableApp']);
			$config->addConfig(__DIR__. '/../ContactableApp/config/config.neon');
			$config->addConfig(FileMock::create('parameters: {databasePath: %tempDir%/database}', 'neon'));

			$this->container = $config->createContainer();
		}

		return $this->container;
	}


	public function setUp()
	{
		$container = $this->createContainer();

		$this->types = $container->getByType('Carrooi\Contactable\Model\Facades\ContactTypesFacade');
		$this->items = $container->getByType('Carrooi\Contactable\Model\Facades\ContactItemsFacade');
		$this->em = $container->getByType('Kdyby\Doctrine\EntityManager');
	}


	public function tearDown()
	{
		$this->container = $this->types = $this->items = $this->em = null;
	}


	/**
	 * @param string $name
	 * @param string $title
	 * @param string $pattern
	 * @param string $url
	 * @return \Carrooi\Contactable\Model\Entities\IContactType
	 */
	protected function createType($name, $title, $pattern = null, $url = null)
	{
		$class = $this->types->getClass();

		/** @var \Carrooi\Contactable\Model\Entities\IContactType $type */

		$type = new $class;
		$type->setName($name);
		$type->setTitle($title);

		if ($pattern) {
			$type->setPattern($pattern);
		}

		if ($url) {
			$type->setUrl('localhost');
		}

		$this->em->persist($type)->flush();

		return $type;
	}


	/**
	 * @param \CarrooiTests\ContactableApp\Model\Entities\User $user
	 * @param \Carrooi\Contactable\Model\Entities\IContactType $type
	 * @param string $value
	 * @return \CarrooiTests\ContactableApp\Model\Entities\ContactItem
	 */
	protected function createItem(User $user, IContactType $type, $value)
	{
		$class = $this->items->getClass();

		/** @var \CarrooiTests\ContactableApp\Model\Entities\ContactItem $item */

		$item = new $class;
		$item->setContactType($type);
		$item->setValue($value);
		$item->setUser($user);
		$this->em->persist($item);

		$user->addContact($item);
		$this->em->persist($user);

		$this->em->flush();

		return $item;
	}

}

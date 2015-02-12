<?php

/**
 * Test: Carrooi\Contactable\Model\Facades\AssociationsManager
 *
 * @testCase CarrooiTests\Contactable\Model\Facades\AssociationsManagerTest
 * @author David Kudera
 */

namespace CarrooiTests\Contactable\Model\Facades;

use Carrooi\Contactable\Model\Facades\AssociationsManager;
use CarrooiTests\Contactable\TestCase;
use Nette\Object;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class AssociationsManagerTest extends TestCase
{


	public function testFunctionality()
	{
		$manager = new AssociationsManager;

		Assert::count(0, $manager->getAssociations());

		$manager->addAssociation('\Nette\Object', 'object');

		Assert::same('object', $manager->getAssociation('Nette\Object'));
		Assert::same('object', $manager->getAssociation('\Nette\Object'));

		Assert::same([
			'Nette\Object' => 'object',
		], $manager->getAssociations());

		Assert::true($manager->hasAssociation('Nette\Object'));
		Assert::true($manager->hasAssociation('\Nette\Object'));
	}


	public function testFunctionality_extendedClass()
	{
		$manager = new AssociationsManager;

		Assert::count(0, $manager->getAssociations());

		$manager->addAssociation('\Nette\Object', 'object');

		Assert::same('object', $manager->getAssociation('CarrooiTests\Contactable\Model\Facades\SuperObject'));
		Assert::same('object', $manager->getAssociation('\CarrooiTests\Contactable\Model\Facades\SuperObject'));

		Assert::same([
			'Nette\Object' => 'object',
			'CarrooiTests\Contactable\Model\Facades\SuperObject' => 'object',
		], $manager->getAssociations());

		Assert::true($manager->hasAssociation('CarrooiTests\Contactable\Model\Facades\SuperObject'));
		Assert::true($manager->hasAssociation('\CarrooiTests\Contactable\Model\Facades\SuperObject'));
	}

}


/**
 *
 * @author David Kudera
 */
class SuperObject extends Object {}


run(new AssociationsManagerTest);

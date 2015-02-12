<?php

/**
 * Test: Carrooi\Contactable\Model\Facades\ContactTypesFacade
 *
 * @testCase CarrooiTests\Contactable\Model\Facades\ContactTypesFacadeTest
 * @author David Kudera
 */

namespace CarrooiTests\Contactable\Model\Facades;

use CarrooiTests\Contactable\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class ContactTypesFacadeTest extends TestCase
{


	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;


	public function setUp()
	{
		parent::setUp();

		$this->dao = $this->em->getRepository('Carrooi\Contactable\Model\Entities\IContactType');
	}


	public function tearDown()
	{
		parent::tearDown();

		$this->dao = null;
	}


	public function testCreate()
	{
		$type = $this->types->create('phone', 'Phone number', [
			'pattern' => '[0-9]{9}',
			'url' => 'localhost',
		]);

		Assert::notSame(null, $type->getId());

		/** @var \Carrooi\Contactable\Model\Entities\IContactType $found */
		$found = $this->dao->findOneBy([
			'id' => $type->getId(),
		]);

		Assert::same('phone', $found->getName());
		Assert::same('Phone number', $found->getTitle());
		Assert::same('[0-9]{9}', $found->getPattern());
		Assert::same('localhost', $found->getUrl());
	}


	public function testUpdate()
	{
		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$this->types->update($type, [
			'name' => 'email',
			'title' => 'Email',
			'pattern' => '.+',
			'url' => 'email@localhost.com',
		]);

		/** @var \Carrooi\Contactable\Model\Entities\IContactType $found */
		$found = $this->dao->findOneBy([
			'id' => $type->getId(),
		]);

		Assert::same($type->getId(), $found->getId());
		Assert::same('email', $found->getName());
		Assert::same('Email', $found->getTitle());
		Assert::same('.+', $found->getPattern());
		Assert::same('email@localhost.com', $found->getUrl());
	}


	public function testRemove()
	{
		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$this->types->remove($type);

		$found = $this->dao->findOneBy([
			'id' => $type->getId(),
		]);

		Assert::null($found);
	}


	public function testFindAll()
	{
		$this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');
		$this->createType('email', 'Email', '.+', 'email@localhost.com');

		$types = $this->types->findAll();

		Assert::count(2, $types);
		Assert::type('Carrooi\Contactable\Model\Entities\IContactType', $types[0]);
		Assert::type('Carrooi\Contactable\Model\Entities\IContactType', $types[1]);
	}


	public function testFindAllNames()
	{
		$type1 = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');
		$type2 = $this->createType('email', 'Email', '.+', 'email@localhost.com');

		$names = $this->types->findAllNames();

		Assert::equal([
			$type1->getId() => 'phone',
			$type2->getId() => 'email',
		], $names);
	}


	public function testFindOneById()
	{
		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$found = $this->types->findOneById($type->getId());

		Assert::notSame(null, $found);
		Assert::same($type->getId(), $found->getId());
	}


	public function testFindOneByName()
	{
		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$found = $this->types->findOneByName('phone');

		Assert::notSame(null, $found);
		Assert::same($type->getId(), $found->getId());
	}

}


run(new ContactTypesFacadeTest);

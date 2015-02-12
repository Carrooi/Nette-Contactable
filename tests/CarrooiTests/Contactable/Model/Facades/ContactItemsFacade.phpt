<?php

/**
 * Test: Carrooi\Contactable\Model\Facades\ContactItemsFacade
 *
 * @testCase CarrooiTests\Contactable\Model\Facades\ContactItemsFacadeTest
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
class ContactItemsFacadeTest extends TestCase
{


	/** @var \CarrooiTests\ContactableApp\Model\Facade\UsersFacade */
	private $users;

	/** @var \Kdyby\Doctrine\EntityDao */
	private $daoTypes;

	/** @var \Kdyby\Doctrine\EntityDao */
	private $daoItems;


	public function setUp()
	{
		parent::setUp();

		$container = $this->createContainer();

		$this->users = $container->getByType('CarrooiTests\ContactableApp\Model\Facade\UsersFacade');
		$this->daoTypes = $this->em->getRepository('Carrooi\Contactable\Model\Entities\IContactType');
		$this->daoItems = $this->em->getRepository('Carrooi\Contactable\Model\Entities\IContactItem');
	}


	public function tearDown()
	{
		parent::tearDown();

		$this->users = $this->daoTypes = $this->daoItems = null;
	}


	public function testCreate()
	{
		$user = $this->users->create();

		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$item = $this->items->addContact($user, $type, '111222333');

		Assert::notSame(null, $item);

		/** @var \CarrooiTests\ContactableApp\Model\Entities\ContactItem $found */
		$found = $this->daoItems->findOneBy([
			'id' => $item->getId(),
		]);

		Assert::same($item->getId(), $found->getId());
		Assert::same($type->getId(), $found->getContactType()->getId());
		Assert::same('111222333', $found->getValue());

		$contacts = $user->getContacts();

		Assert::count(1, $contacts);
		Assert::same('111222333', $contacts[0]->getValue());

		Assert::true($found->hasUser());
		Assert::same($user->getId(), $found->getUser()->getId());
	}


	public function testUpdate()
	{
		$user = $this->users->create();

		$phone = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');
		$mail = $this->createType('mail', 'Email', '.+', 'mail.localhost');

		$item = $this->createItem($user, $phone, '111222333');

		$this->items->update($item, [
			'contactType' => $mail,
			'value' => 'email@localhost.com',
		]);

		/** @var \CarrooiTests\ContactableApp\Model\Entities\ContactItem $found */
		$found = $this->daoItems->findOneBy([
			'id' => $item->getId(),
		]);

		Assert::same('email@localhost.com', $found->getValue());
		Assert::same($mail->getId(), $found->getContactType()->getId());
	}


	public function testRemove()
	{
		$user = $this->users->create();

		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$item = $this->createItem($user, $type, '111222333');

		$this->items->remove($item);

		$found = $this->daoItems->findOneBy([
			'id' => $item->getId(),
		]);

		Assert::null($found);
		Assert::count(0, $user->getContacts());
	}


	public function testFindOneById()
	{
		$user = $this->users->create();

		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$item = $this->createItem($user, $type, '111222333');

		$found = $this->items->findOneById($item->getId());

		Assert::notSame(null, $found);
		Assert::same($item->getId(), $found->getId());
	}


	public function testFindAllByItem()
	{
		$user = $this->users->create();

		$type = $this->createType('phone', 'Phone number', '[0-9]{9}', 'localhost');

		$this->createItem($user, $type, '111222333');
		$this->createItem($user, $type, '222333444');
		$this->createItem($user, $type, '333444555');

		$this->createItem($this->users->create(), $type, '999888777');

		$found = $this->items->findAllByEntity($user);

		Assert::count(3, $found);
		Assert::same('111222333', $found[0]->getValue());
		Assert::same('222333444', $found[1]->getValue());
		Assert::same('333444555', $found[2]->getValue());
	}

}


run(new ContactItemsFacadeTest);

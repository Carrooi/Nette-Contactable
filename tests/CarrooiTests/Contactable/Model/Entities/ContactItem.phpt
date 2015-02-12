<?php

/**
 * Test: Carrooi\Labels\Contactable\Model\Entities\TContactItem
 *
 * @testCase CarrooiTests\Labels\Contactable\Model\Entities\TContactItemTest
 * @author David Kudera
 */

namespace CarrooiTests\Labels\Model\Facades;

use Carrooi\Contactable\Model\Entities\IContactItem;
use Carrooi\Contactable\Model\Entities\IContactType;
use Carrooi\Contactable\Model\Entities\TContactItem;
use Carrooi\Contactable\Model\Entities\TContactType;
use CarrooiTests\Contactable\TestCase;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class ContactItemTest extends TestCase
{


	public function testValidateValue_typeNotSet()
	{
		$item = new ContactItem;

		Assert::exception(function() use ($item) {
			$item->validateValue('lorem');
		}, 'Carrooi\Contactable\InvalidStateException', 'Please, set contact type for contact value lorem.');
	}


	public function testValidateValue_notMatch()
	{
		$item = new ContactItem;
		$type = new ContactType;

		$type->setPattern('[0-9]+');

		$item->setContactType($type);

		Assert::false($item->validateValue('Lorem'));
	}


	public function testValidate()
	{
		$item = new ContactItem;
		$type = new ContactType;

		$type->setPattern('[0-9]+');

		$item->setContactType($type);

		Assert::true($item->validateValue('8568'));
		Assert::true($item->validateValue(8568));
	}


	public function testSetValue_notMatch()
	{
		$item = new ContactItem;
		$type = new ContactType;

		$type->setName('test');
		$type->setPattern('[0-9]+');

		$item->setContactType($type);

		Assert::exception(function() use ($item) {
			$item->setValue('Lorem');
		}, 'Carrooi\Contactable\InvalidArgumentException', 'Could not add contact value Lorem to contact type test');
	}


	public function testSetValue()
	{
		$item = new ContactItem;
		$type = new ContactType;

		$type->setName('test');
		$type->setPattern('[a-zA-Z]+');

		$item->setContactType($type);

		$item->setValue('Lorem');

		Assert::same('Lorem', $item->getValue());
	}

}


/**
 *
 * @author David Kudera
 */
class ContactItem implements IContactItem
{

	use Identifier;

	use TContactItem;

}


/**
 *
 * @author David Kudera
 */
class ContactType implements IContactType
{

	use Identifier;

	use TContactType;

}


run(new ContactItemTest);
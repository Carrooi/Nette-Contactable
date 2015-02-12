# Carrooi/Addressable

[![Build Status](https://img.shields.io/travis/Carrooi/Nette-Contactable.svg?style=flat-square)](https://travis-ci.org/Carrooi/Nette-Contactable)
[![Donate](https://img.shields.io/badge/donate-PayPal-brightgreen.svg?style=flat-square)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SZRZJA7TCK4N2)

Contactable module for Nette framework and Doctrine.

## Installation

```
$ composer require carrooi/contactable
$ composer update
```

## Configuration

```neon
extensions:
	contactable: Carrooi\Contactable\DI\ContactableExtension

contactable:
	contactItemClass: App\Model\Entities\ContactItem
	associations:
		App\Model\Entities\User: user
```

* **contactItemClass**: entity with associations between contact types and eg. user entity
* **associations**: list of contactable entities with field names which are manyToOne associations in `contactItemClass`

## Contactable entity

Entity which implements `Carrooi\Contactable\Model\Entities\IContactableEntity` interface with these methods:

* `getId()`: returns identifier
* `getContacts()`: returns array of `Carrooi\Contactable\Model\Entities\IContactItem` entities
* `addContact()`: adds new `Carrooi\Contactable\Model\Entities\IContactItem` entity to collection
* `removeContact()`: removes `Carrooi\Contactable\Model\Entities\IContactItem` entity from collection

Of course you don't need to implement all required methods on your own (except for `getId()` method). Just use prepared trait `Carrooi\Contactable\Model\Entities\TContactable`.

```php
namespace App\Model\Entities;

use Carrooi\Contactable\Model\Entities\IContactableEntity;
use Carrooi\Contactable\Model\Entities\TContactable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author David Kudera
 */
class User implements IContactableEntity
{

	use TContactable;

	// ...

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
```

## Contact item entity

Entity which holds the actual value of contact, eg. actual email address of user. This entity must implement `Carrooi\Contactable\Model\Entities\IContactItem` interface with these methods:

* `getId()`: returns identifier
* `getContactType()` returns `Carrooi\Contactable\Model\Entities\IContactType` entity
* `setContactType()` sets `Carrooi\Contactable\Model\Entities\IContactType` entity
* `getValue()`: returns value of contact
* `setValue()`: sets value of contact
* `validateValue()` validate value against pattern in contact type

Again, you can use prepared trait `Carrooi\Contactable\Model\Entities\TContactItem`.

```php
namespace App\Model\Entities;

use Carrooi\Contactable\Model\Entities\IContactItem;
use Carrooi\Contactable\Model\Entities\TContactItem;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author David Kudera
 */
class ContactItem implements IContactItem
{

	use TContactItem;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="\App\Model\Entities\User")
	 * @var \App\Model\Entities\User
	 */
	private $user;

	// ... some getters and setters for all fields

}
```

As you can see, we've got `user` field which corresponding with `associations` setup in your configuration.

## Contact types

You can create as many types as you want, eg. mails, facebook, phone numbers etc.

There is already prepared service for that: `Carrooi\Contactable\Model\Facades\ContactTypesFacade`.

**Create contact type**:

```php
$type = $types->create('mail', 'Email', [
	'pattern' => '[a-z]+@[a-z]+\.[a-z]{2,3}',    // really naive mail regex
	'url' => 'mail.org',
]);
```

arguments:

* `name`: required "system" name
* `title`: required "public" name
* `values`:
	+ not required list of other values
	+ `pattern`: not required pattern for all contact values of this type
	+ `url`: not required url to contact

**Update contact type**:

```php
$types->update($type, [
	'name' => 'email',
	'title' => 'Email address',
	'pattern' => '.+',            // no more naive, just stupid
	'url' => 'mail.com',
]);
```

**Remove contact type**:

```php
$types->remove($type);
```

**Get all contact types**:

```php
foreach ($types->findAll() as $type) {
	// ...
}
```

**Get id => names pairs**:

```php
foreach ($types->findAllNames() as $id => $name) {
	// ...
}
```

**Find contact type by id**:

```php
$type = $types->findOneById($id);
```

**Find contact type by name**:

```php
$type = $types->findOneByName($name);
```

## Contact items facade

There is also service `Carrooi\Contactable\Model\Facades\ContactItemsFacade` which can be used for adding contacts to contactable entities.

**Add contact**:

```php
$item = $items->addContact($user, $type, 'lorem@ipsum.com');
```

**Update contact**:

```php
$items->update($item, [
	'contactType' => $anotherType,
	'value' => '999888777',
]);
```

**Remove contact**:

```php
$items->remove($item);
```

**Find by id**:

```php
$item = $items->findOneById($id);
```

**Find all by entity**:

```php
foreach ($items->findAllByEntity($user) as $item) {
	// ...
}
```

## Changelog

* 1.0.0
	+ Initial version

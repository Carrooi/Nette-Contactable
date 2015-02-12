<?php

namespace CarrooiTests\ContactableApp\Model\Entities;

use Carrooi\Contactable\Model\Entities\IContactableEntity;
use Carrooi\Contactable\Model\Entities\TContactable;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class User extends BaseEntity implements IContactableEntity
{


	use Identifier;

	use TContactable;

}

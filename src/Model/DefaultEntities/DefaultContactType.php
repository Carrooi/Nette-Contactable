<?php

namespace Carrooi\Contactable\Model\DefaultEntities;

use Carrooi\Contactable\Model\Entities\IContactType;
use Carrooi\Contactable\Model\Entities\TContactType;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="contact_type")
 *
 * @author David Kudera
 */
class DefaultContactType extends BaseEntity implements IContactType
{


	use Identifier;

	use TContactType;

}

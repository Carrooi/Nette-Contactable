<?php

namespace Carrooi\Contactable\Model\Facades;

use Nette\Object;

/**
 *
 * @author David Kudera
 */
class AssociationsManager extends Object
{


	/** @var array */
	private $associations = [];


	/**
	 * @param string $class
	 * @return string
	 */
	private function loadRealClass($class)
	{
		$class = ltrim($class, '\\');

		if (isset($this->associations[$class])) {
			return $class;
		}

		$parents = class_parents($class);

		foreach ($parents as $parent) {
			if (isset($this->associations[$parent])) {
				$this->addAssociation($class, $this->associations[$parent]);

				return $class;
			}
		}

		return null;
	}


	/**
	 * @param string $class
	 * @param string $field
	 * @return $this
	 */
	public function addAssociation($class, $field)
	{
		$class = ltrim($class, '\\');
		$this->associations[$class] = $field;
		return $this;
	}


	/**
	 * @param string $class
	 * @return bool
	 */
	public function hasAssociation($class)
	{
		$class = $this->loadRealClass($class);
		return isset($this->associations[$class]);
	}


	/**
	 * @param string $class
	 * @return string
	 */
	public function getAssociation($class)
	{
		$class = $this->loadRealClass($class);
		return $this->hasAssociation($class) ? $this->associations[$class] : null;
	}


	/**
	 * @return array
	 */
	public function getAssociations()
	{
		return $this->associations;
	}

}
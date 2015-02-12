<?php

namespace Carrooi\Contactable;

class RuntimeException extends \RuntimeException {}

class InvalidArgumentException extends \InvalidArgumentException {}

class InvalidStateException extends RuntimeException {}

class ConfigurationException extends RuntimeException {}

class ContactableAssociationException extends RuntimeException {}

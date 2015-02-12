<?php

if (!$loader = @include __DIR__ . '/../../../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}
$loader->addPsr4('CarrooiTests\\ContactableApp\\', __DIR__ . '/../');

use Nette\Configurator;
use Tester\FileMock;

$config = new Configurator;
$config->setTempDirectory(__DIR__. '/../temp');
$config->addParameters(['appDir' => __DIR__. '/..']);
$config->addConfig(__DIR__. '/../config/config.neon');
$config->addConfig(FileMock::create('parameters: {databasePath: %appDir%/Model/database}', 'neon'));

$context = $config->createContainer();

$application = $context->getByType('Kdyby\Console\Application');		/** @var \Kdyby\Console\Application $application */

$application->run();

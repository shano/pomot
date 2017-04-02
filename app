#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use Pomotodo\PomotodoQuery;
use Pomotodo\Command\TodoListCommand;
use Pomotodo\Command\TodoCreateCommand;
use Pomotodo\Command\PomoListCommand;
use Pomotodo\Command\PomoCreateCommand;
use GuzzleHttp\Client;

$configDirectories = array(__DIR__.'/src/Resources/config');
$locator = new FileLocator($configDirectories);
$yamlConfig = $locator->locate('configuration.yml', null, true);
if (file_exists($yamlConfig)) {
    $yamlString = file_get_contents($yamlConfig);
    $config = Yaml::parse($yamlString);
} else {
    throw new \RuntimeException(
        "The file $yamlConfig does not exist."
    );
}


$queryClient = new PomotodoQuery(new Client(), $config['auth_key']);


$application = new Application();
$application->add(new TodoListCommand($queryClient));
$application->add(new TodoCreateCommand($queryClient));
$application->add(new PomoListCommand($queryClient));
$application->add(new PomoCreateCommand($queryClient));
$application->run();

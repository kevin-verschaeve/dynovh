<?php

require __DIR__ . '/vendor/autoload.php';

use App\Factory\ProviderFactory;
use App\Command\UpdateOvhRecordCommand;
use Ovh\Api;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__.'/config/ovh.yaml');
$ovh = new Api($config['app_key'], $config['app_secret'], $config['api_endpoint'] ?? 'ovh-eu', $config['consumer_key']);
$application = new Application();
$application->add(new UpdateOvhRecordCommand($ovh, new ProviderFactory()));
$application->run();

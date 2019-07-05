<?php

require __DIR__ . '/vendor/autoload.php';

use App\Command\UpdateOvhRecordCommand;
use Ovh\Api;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->load(__DIR__.'/.env');

$ovh = new Api($_ENV['OVH_APP_KEY'], $_ENV['OVH_APP_SECRET'], 'ovh-eu', $_ENV['OVH_CONSUMER_KEY']);
$application = new Application();
$application->add(new UpdateOvhRecordCommand($ovh));
$application->run();


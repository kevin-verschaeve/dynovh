<?php

namespace App\Command;

use GuzzleHttp\Exception\RequestException;
use Ovh\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UpdateOvhRecordCommand extends Command
{
    private $ovh;

    public function __construct(Api $ovh, $name = null)
    {
        $this->ovh = $ovh;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:ovh:set-ip')
            ->setDescription('Set a new ip for an Ovh record.')
            ->addArgument('ip', InputArgument::OPTIONAL, 'The new ip to set. If null, check the ip from the box.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ip = $input->getArgument('ip') ?? $this->fetchIpFromBox();

        try {
            $this->ovh->put(
                sprintf('/domain/zone/%s/record/%d', $_ENV['OVH_ZONE_NAME'], $_ENV['OVH_RECORD_ID']),
                ['target' => $ip]
            );
        } catch (RequestException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln(sprintf('Ip updated to %s.', $ip));
    }

    private function fetchIpFromBox()
    {
        $process = new Process([
            'curl',
            '-s',
            '-X',
            'POST',
            '-H',
            'Content-Type: application/x-sah-ws-1-call+json',
            '-d',
            '{"service":"NMC","method":"getWANStatus","parameters":{}}',
            'http://192.168.1.1/ws',
        ]);
        $process->mustRun();

        $result = \json_decode($process->getOutput(), true);

        return $result['result']['data']['IPAddress'];
    }
}

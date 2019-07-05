<?php

namespace App\Command;

use GuzzleHttp\Exception\RequestException;
use Ovh\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->addArgument('ip', InputArgument::REQUIRED, 'The new ip to set. If null, check the ip from the box.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->ovh->put(
                sprintf('/domain/zone/%s/record/%d', $_ENV['OVH_ZONE_NAME'], $_ENV['OVH_RECORD_ID']),
                ['target' => $input->getArgument('ip')]
            );
        } catch (RequestException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln('Ip updated.');
    }
}

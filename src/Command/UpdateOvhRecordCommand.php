<?php

namespace App\Command;

use App\Factory\ProviderFactory;
use GuzzleHttp\Exception\RequestException;
use Ovh\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateOvhRecordCommand extends Command
{
    private $ovh;
    private $factory;

    public function __construct(Api $ovh, ProviderFactory $factory)
    {
        $this->ovh = $ovh;
        $this->factory = $factory;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('dynovh:set-ip')
            ->setDescription('Set a new ip for an Ovh record.')
            ->addArgument('zone', InputArgument::REQUIRED, 'The OVH dns zone to change the ip to.')
            ->addArgument('ip', InputArgument::OPTIONAL, 'The new ip to set. If null, check the ip from the box.')
            ->addOption('provider', 'p', InputOption::VALUE_REQUIRED, 'Your internet provider name.')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('ip') && !$input->getOption('provider')) {
            throw new LogicException('If you don\'t provide an ip address, you must set the provider option.');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ip = $input->getArgument('ip') ?? $this->factory->getProvider($input->getOption('provider'))->fetchIp();

        if (!$ip) {
            throw new \RuntimeException('Ip not found from the box. Try manually by passing the ip as the second argument of the command.');
        }

        try {
            $record = $this->ovh->get(sprintf('/domain/zone/%s/record?fieldType=A', $input->getArgument('zone')))[0];
            $this->ovh->put(
                sprintf('/domain/zone/%s/record/%f', $input->getArgument('zone'), $record),
                ['target' => $ip]
            );
        } catch (RequestException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln(sprintf('Ip updated to %s.', $ip));
    }
}

<?php

namespace App\Factory;

use Symfony\Component\Process\Process;

class Orange implements Provider
{
    public function fetchIp(): string
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

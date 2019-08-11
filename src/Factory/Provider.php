<?php

namespace App\Factory;

interface Provider
{
    public function fetchIp(): string;
}

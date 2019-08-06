<?php

namespace App\Adapter;

interface Provider
{
    public function fetchIp(): string;
}

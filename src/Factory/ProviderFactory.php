<?php

namespace App\Factory;

class ProviderFactory
{
    private const ORANGE = 'orange';

    private const ALL = [
        self::ORANGE,
    ];

    public function getProvider(string $name): Provider
    {
        switch (\strtolower($name)) {
            case self::ORANGE:
                return new Orange();
            default:
                throw new \InvalidArgumentException(\sprintf('Provider "%s" not implemented. Valid providers are "%s".', $name, \implode('", "', self::ALL)));
        }
    }
}

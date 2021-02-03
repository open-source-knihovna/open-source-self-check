<?php

/**
 * Factory for controllers.
 */

namespace Tohu\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PatronControllerFactory
{
    public static function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new PatronController($config);
    }
}

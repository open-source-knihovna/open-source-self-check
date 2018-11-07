<?php

/**
 * Factory for controllers.
 */

namespace Tohu\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory
{
    public static function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new IndexController($config);
    }
}

<?php
declare(strict_types=1);

/**
 * Factory for controllers.
 */

namespace Tohu\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new IndexController($config);
    }
}

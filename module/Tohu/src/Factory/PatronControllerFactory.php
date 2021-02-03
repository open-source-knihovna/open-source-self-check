<?php
declare(strict_types=1);

namespace Tohu\Factory;

use Tohu\Controller\PatronController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PatronControllerFactory implements FactoryInterface
{
    /**
      * Create service
      *
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PatronController($container->get('configuration'));
    }
}

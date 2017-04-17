<?php
namespace Tohu\Factory;

use Tohu\Controller\PatronController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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

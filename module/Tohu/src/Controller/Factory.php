<?php

/**
 * Factory for controllers.
 */

namespace Tohu\Controller;

use Zend\ServiceManager\ServiceManager;

class Factory
{
    public static function getPatronController(ServiceManager $sm)
    {
        return new PatronController($sm->getServiceLocator);
    }
}

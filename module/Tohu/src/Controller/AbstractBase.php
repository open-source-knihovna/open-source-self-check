<?php
/**
* Tohu controller base
*/

namespace Tohu\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AbstractBase extends AbstractActionController
{
    protected $config;

    /**
     * Constructor
     *
     * @param ServiceLocatorInterface $sm Service locator
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get the ILS connection.
     *
     * @return \Tohu\ILS\DriverInterface
     */
    public function getILS()
    {
        $classname = "\\Tohu\\ILS\\Driver\\" . $this->config['tohu']['driver'];
        $ils = new $classname();
        $ils->init($this->config[strtolower($this->config['tohu']['driver'])]);
        return $ils;
    }
}

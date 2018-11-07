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
        $this->mode = $this->parseMode();
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

    protected function parseMode()
    {
        $mode = $this->config['tohu']['mode'];
        return [
            'checkin' => in_array($mode, ['checkin', 'all']),
            'checkout' => in_array($mode, ['checkout', 'all']),
        ];
    }
}

<?php
declare(strict_types=1);

/**
* Tohu controller base
*/

namespace Tohu\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class AbstractBase extends AbstractActionController
{
    protected array $config;

    protected array $mode;

    /**
     * Constructor
     *
     * @param array $config Configuration
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->mode = $this->parseMode();
    }

    /**
     * Get the ILS connection.
     *
     * @return \Tohu\ILS\Driver\DriverInterface
     */
    public function getILS()
    {
        $classname = "\\Tohu\\ILS\\Driver\\" . $this->config['tohu']['driver'];
        $ils = new $classname();
        $ils->init($this->config[strtolower($this->config['tohu']['driver'])]);
        return $ils;
    }

    /**
     * @return array
     */
    protected function parseMode(): array
    {
        $mode = $this->config['tohu']['mode'];
        return [
            'checkin' => in_array($mode, ['checkin', 'all']),
            'checkout' => in_array($mode, ['checkout', 'all']),
        ];
    }
}

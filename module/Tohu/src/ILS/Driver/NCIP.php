<?php
/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

use Scriptotek\Ncip\NcipConnector;
use Scriptotek\Ncip\NcipClient;

namespace Tohu\ILS\Driver;

class NCIP extends AbstractDriver
{
    protected $ncipUrl;

    protected $userAgent = "Tohu version " . \Tohu\Module::VERSION;

    protected $agencyId;

    /**
     * @var Scriptotek\Ncip\NcipClient
     */
    protected $ncip;

    public function init($config = [])
    {
        $this->ncipUrl = $config["ils"]["server"] ?? null;
        $this->agencyId = $config["ils"]["agencyId"] ?? null;
        $conn = new NcipConnector($this->ncipUrl, $this->userAgent, $this->agencyId);
        $this->ncip = new NcipClient($conn);
    }
}
